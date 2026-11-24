import { BadRequestException, ConflictException, ForbiddenException, HttpException, Injectable, InternalServerErrorException, Logger } from '@nestjs/common';
import { DataSource } from 'typeorm';
import { InjectDataSource } from '@nestjs/typeorm';
import { SoporteRepositoryPort } from '../../domain/ports/soporte.repository.port';
import { CreateSolicitudDto } from '../../application/dtos/create-solicitud.dto';
import { CreateSoporteManualDto } from '../../application/dtos/create-soporte-manual.dto';
import { DB_CONNECTIONS } from '../../../../config/database.constants';
import { SolicitudTypeOrmEntity } from '../../domain/entities/solicitud.typeorm-entity';
import { SolicitudItemTypeOrmEntity } from '../../domain/entities/solicitud-item.typeorm-entity';
import { AsignarSoporteDto } from '../../application/dtos/asignar-soporte.dto';
import { ReasignarSoporteDto } from '../../application/dtos/reasignar-soporte.dto';
import { SoporteAsignacionTypeOrmEntity } from '../../domain/entities/asignacion.typeorm-entity';
import { SoporteReasignacionTypeOrmEntity } from '../../domain/entities/reasignacion.typeorm-entity';
import { FinalizarSoporteDto } from '../../application/dtos/finalizar-soporte.dto';
import { SoporteArchivoActaTypeOrmEntity } from '../../domain/entities/acta.typeorm-entity';

const normalizarPrioridad = (prioridad?: string | null): 'BAJA' | 'MEDIA' | 'ALTA' => {
  const valor = String(prioridad || '').toUpperCase();
  if (valor === 'BAJA') return 'BAJA';
  if (valor === 'ALTA' || valor === 'URGENTE') return 'ALTA';
  return 'MEDIA';
};



@Injectable()
export class PostgresSoporteRepository implements SoporteRepositoryPort {
  private readonly logger = new Logger(PostgresSoporteRepository.name);
  private esquemaUbicacionViaje: Promise<void> | null = null;

  constructor(
    @InjectDataSource(DB_CONNECTIONS.SIGHOV)
    private readonly dataSource: DataSource,
  ) { }

  private async asegurarColumnasUbicacionViaje(): Promise<void> {
    if (!this.esquemaUbicacionViaje) {
      this.esquemaUbicacionViaje = this.dataSource.query(`
        ALTER TABLE administracion.soporte_asignaciones
          ADD COLUMN IF NOT EXISTS fecha_en_camino timestamp NULL,
          ADD COLUMN IF NOT EXISTS latitud_en_camino double precision NULL,
          ADD COLUMN IF NOT EXISTS longitud_en_camino double precision NULL,
          ADD COLUMN IF NOT EXISTS fecha_llegada timestamp NULL,
          ADD COLUMN IF NOT EXISTS latitud_llegada double precision NULL,
          ADD COLUMN IF NOT EXISTS longitud_llegada double precision NULL
      `).then(() => undefined).catch((error) => {
        this.esquemaUbicacionViaje = null;
        throw error;
      });
    }
    await this.esquemaUbicacionViaje;
  }

  async crearSolicitudTransaccional(idUsuario: number, dto: CreateSolicitudDto): Promise<any> {
    const queryRunner = this.dataSource.createQueryRunner();
    await queryRunner.connect();
    await queryRunner.startTransaction();

    try {
      // 1. Crear instancia de la cabecera usando la entidad de TypeORM
      const nuevaSolicitud = new SolicitudTypeOrmEntity();
      nuevaSolicitud.idUsuario = idUsuario;

      nuevaSolicitud.piso = dto.piso ?? null;
      nuevaSolicitud.oficina = dto.oficina ?? null;
      nuevaSolicitud.consultorio = dto.consultorio ?? null;
      nuevaSolicitud.estado = 'PENDIENTE';

      // Guardamos la cabecera usando el manager del QueryRunner (Transaccional puro TypeORM)
      const solicitudGuardada = await queryRunner.manager.save(nuevaSolicitud);

      const itemsCreados = [];

      // 2. Insertar Detalle usando la entidad de TypeORM
      if (dto.items && dto.items.length > 0) {
        for (const item of dto.items) {
          const nuevoItem = new SolicitudItemTypeOrmEntity();
          nuevoItem.idSolicitud = solicitudGuardada.id; // Usamos el ID generado arriba
          nuevoItem.idTipoSoporte = item.id_tipo_soporte;
          nuevoItem.descripcion = item.descripcion ?? null;

          const itemGuardado = await queryRunner.manager.save(nuevoItem);
          itemsCreados.push(itemGuardado);
        }
      }

      const [solicitudConCodigo] = await queryRunner.query(
        `SELECT codigo_ticket FROM administracion.soporte_solicitudes WHERE id = $1`,
        [solicitudGuardada.id],
      );

      await queryRunner.commitTransaction();

      return {
        id: solicitudGuardada.id,
        idSolicitud: solicitudGuardada.id,
        codigoTicket: solicitudConCodigo?.codigo_ticket,
        items: itemsCreados
      };

    } catch (error: any) {
      await queryRunner.rollbackTransaction();
      this.logger.error(`Error registrando soporte: ${error?.message}`, error?.stack);
      throw new InternalServerErrorException('No se pudo guardar la solicitud de soporte.');
    } finally {
      await queryRunner.release();
    }
  }

  async crearSoporteManual(idTecnico: number, dto: CreateSoporteManualDto): Promise<any> {
    const queryRunner = this.dataSource.createQueryRunner();
    await queryRunner.connect();
    await queryRunner.startTransaction();

    try {
      const prioridad = normalizarPrioridad(dto.prioridad);
      const [solicitud] = await queryRunner.query(
        `INSERT INTO administracion.soporte_solicitudes
          (id_usuario, piso, oficina, consultorio, estado, es_manual, solicitante_manual, created_at, updated_at)
         VALUES ($1, $2, $3, $4, 'EN_PROCESO', TRUE, $5, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
         RETURNING id, created_at, codigo_ticket`,
        [
          idTecnico,
          dto.piso?.trim() || null,
          dto.oficina.trim(),
          dto.consultorio?.trim() || null,
          dto.solicitante_nombre.trim(),
        ],
      );

      const [item] = await queryRunner.query(
        `INSERT INTO administracion.soporte_solicitud_items
          (id_solicitud, id_tipo_soporte, descripcion, created_at)
         VALUES ($1, 1, $2, CURRENT_TIMESTAMP)
         RETURNING id`,
        [solicitud.id, dto.descripcion.trim()],
      );

      const [asignacion] = await queryRunner.query(
        `INSERT INTO administracion.soporte_asignaciones
          (id_solicitud_item, id_soporte, id_asignado_por, fecha_asignacion, fecha_inicio, estado, observacion, prioridad)
         VALUES ($1, $2, $2, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'EN_PROCESO', $3, $4)
         RETURNING id`,
        [item.id, idTecnico, `Soporte presencial registrado para ${dto.solicitante_nombre.trim()}.`, prioridad],
      );

      await queryRunner.commitTransaction();
      return {
        idSolicitud: Number(solicitud.id),
        codigoTicket: solicitud.codigo_ticket,
        idAsignacion: Number(asignacion.id),
        estado: 'EN_PROCESO',
        prioridad,
      };
    } catch (error: any) {
      await queryRunner.rollbackTransaction();
      this.logger.error(`Error registrando soporte manual: ${error?.message}`, error?.stack);
      throw new InternalServerErrorException(error?.message || 'No se pudo guardar el soporte manual.');
    } finally {
      await queryRunner.release();
    }
  }

  async asignarSoporteTransaccional(idOperador: number, dto: AsignarSoporteDto): Promise<any> {
    const queryRunner = this.dataSource.createQueryRunner();
    await queryRunner.connect();
    await queryRunner.startTransaction();

    try {
      const idSoporte = Number(dto.idTecnico ?? dto.id_soporte);
      let idSolicitudItem = dto.id_solicitud_item;
      let idSolicitudCabecera = dto.idSolicitud;

      if (!idSoporte) {
        throw new BadRequestException('Debes seleccionar un técnico para asignar el soporte.');
      }

      // Resolvemos primero el id de cabecera si solo enviaron id_solicitud_item
      if (!idSolicitudCabecera && idSolicitudItem) {
        const itemResult = await queryRunner.query(
          `SELECT id_solicitud FROM administracion.soporte_solicitud_items WHERE id = $1`,
          [idSolicitudItem]
        );
        if (!itemResult || itemResult.length === 0) {
          throw new BadRequestException('El ítem de solicitud no existe.');
        }
        idSolicitudCabecera = Number(itemResult[0].id_solicitud);
      }

      if (!idSolicitudCabecera) {
        throw new BadRequestException('Falta el identificador de la solicitud para asignar.');
      }

      // Bloqueo pesimista a nivel de fila: si dos operadores asignan al mismo tiempo,
      // el segundo espera aquí hasta que el primero haga COMMIT y luego verá el estado actualizado.
      const [solicitudBloqueada] = await queryRunner.query(
        `SELECT id, codigo_ticket, estado
         FROM administracion.soporte_solicitudes
         WHERE id = $1
         FOR UPDATE`,
        [idSolicitudCabecera]
      );

      if (!solicitudBloqueada) {
        throw new BadRequestException('La solicitud de soporte no existe.');
      }

      const [asignacionActiva] = await queryRunner.query(
        `SELECT
           a.id,
           COALESCE(NULLIF(TRIM(CONCAT_WS(' ', p.nombres, p.p_apellido, p.s_apellido)), ''), u.name) as nombre_tecnico
         FROM administracion.soporte_asignaciones a
         INNER JOIN administracion.soporte_solicitud_items si ON si.id = a.id_solicitud_item
         LEFT JOIN administracion.users u ON u.id = a.id_soporte
         LEFT JOIN administracion.persona p ON p.id = u.id_persona
         WHERE si.id_solicitud = $1
           AND a.estado IN ('ASIGNADA', 'EN_PROCESO')
         ORDER BY a.id DESC
         LIMIT 1`,
        [idSolicitudCabecera]
      );

      if (asignacionActiva) {
        const tecnicoTexto = asignacionActiva.nombre_tecnico ? ` a ${asignacionActiva.nombre_tecnico}` : '';
        throw new ConflictException(`Este ticket ya fue asignado${tecnicoTexto} por otro operador.`);
      }

      if (solicitudBloqueada.estado !== 'PENDIENTE') {
        throw new ConflictException(
          `Este ticket ya no está pendiente (estado actual: ${solicitudBloqueada.estado}).`
        );
      }

      // Si desde el frontend nos envían idSolicitud (ej. Ticket #6), buscamos su ítem principal
      if (!idSolicitudItem) {
        const items = await queryRunner.query(
          `SELECT id FROM administracion.soporte_solicitud_items WHERE id_solicitud = $1 ORDER BY id ASC LIMIT 1`,
          [idSolicitudCabecera]
        );

        if (items && items.length > 0) {
          idSolicitudItem = items[0].id;
        } else {
          // Si por alguna razón el ticket no tenía ítems, creamos uno por defecto para poder asignarlo
          const nuevoItem = await queryRunner.query(
            `INSERT INTO administracion.soporte_solicitud_items (id_solicitud, id_tipo_soporte, descripcion)
             VALUES ($1, 1, 'Soporte general') RETURNING id`,
            [idSolicitudCabecera]
          );
          idSolicitudItem = nuevoItem[0].id;
        }
      }

      const prioridadValida = normalizarPrioridad(dto.prioridad);

      const asignacionQuery = `
        INSERT INTO administracion.soporte_asignaciones 
        (id_solicitud_item, id_soporte, id_asignado_por, fecha_asignacion, estado, observacion, prioridad, instrucciones_tecnico) 
        VALUES ($1, $2, $3, CURRENT_TIMESTAMP, 'ASIGNADA', NULL, $4, $5) 
        RETURNING id;
      `;

      const asignacionResult = await queryRunner.query(asignacionQuery, [
        idSolicitudItem,
        idSoporte,
        idOperador,
        prioridadValida,
        dto.instruccionesTecnico?.trim() || dto.observacion?.trim() || null,
      ]);
      const idAsignacion = asignacionResult[0].id;

      const updateCabeceraQuery = `
        UPDATE administracion.soporte_solicitudes 
        SET estado = 'ASIGNADA', updated_at = CURRENT_TIMESTAMP 
        WHERE id = $1;
      `;
      await queryRunner.query(updateCabeceraQuery, [idSolicitudCabecera]);

      await queryRunner.commitTransaction();

      return {
        id: idAsignacion,
        idAsignacion,
        idSolicitud: idSolicitudCabecera,
        codigoTicket: solicitudBloqueada?.codigo_ticket,
        estado: 'ASIGNADA'
      };

    } catch (error: any) {
      await queryRunner.rollbackTransaction();
      if (error instanceof HttpException) {
        throw error;
      }
      this.logger.error(`Error al asignar soporte: ${error?.message}`, error?.stack);
      throw new InternalServerErrorException('No se pudo asignar el soporte.');
    } finally {
      await queryRunner.release();
    }
  }

  async reasignarSoporteTransaccional(
    idAsignacion: number,
    idDeSoporte: number,
    dto: ReasignarSoporteDto,
    rolDerivacion: 'TECNICO' | 'OPERADOR',
  ): Promise<any> {
    const queryRunner = this.dataSource.createQueryRunner();
    await queryRunner.connect();
    await queryRunner.startTransaction();

    try {
      // 1. Buscar la asignación usando TypeORM puro
      const asignacion = await queryRunner.manager.findOne(SoporteAsignacionTypeOrmEntity, {
        where: { id: idAsignacion, idSoporte: idDeSoporte },
      });

      if (!asignacion) {
        throw new Error('La asignación no existe o no te pertenece.');
      }

      const solicitudRows = await queryRunner.query(
        `SELECT id_solicitud FROM administracion.soporte_solicitud_items WHERE id = $1`,
        [asignacion.idSolicitudItem],
      );
      const idSolicitud = Number(solicitudRows?.[0]?.id_solicitud);
      const [solicitudCodigo] = await queryRunner.query(
        `SELECT codigo_ticket FROM administracion.soporte_solicitudes s
         INNER JOIN administracion.soporte_solicitud_items si ON si.id_solicitud = s.id
         WHERE si.id = $1`,
        [asignacion.idSolicitudItem],
      );

      // 2. Insertar en soporte_reasignaciones usando Entidad
      const nuevaReasignacion = new SoporteReasignacionTypeOrmEntity();
      nuevaReasignacion.idAsignacion = idAsignacion;
      nuevaReasignacion.idDeSoporte = idDeSoporte;
      nuevaReasignacion.idASoporte = dto.id_a_soporte;
      nuevaReasignacion.justificacion = dto.justificacion;
      nuevaReasignacion.rolDerivacion = rolDerivacion;

      await queryRunner.manager.save(nuevaReasignacion);

      if (!['ASIGNADA', 'EN_PROCESO'].includes(String(asignacion.estado).toUpperCase())) {
        throw new Error('Solo se puede derivar una asignación activa.');
      }

      // Cerrar la asignación del técnico actual; fecha_inicio permanece NULL si no inició el proceso.
      const etapaCerrada = await queryRunner.query(
        `UPDATE administracion.soporte_asignaciones
         SET estado = 'DERIVADA', fecha_fin = CURRENT_TIMESTAMP
         WHERE id = $1 AND id_soporte = $2 AND estado IN ('ASIGNADA', 'EN_PROCESO')
         RETURNING id`,
        [idAsignacion, idDeSoporte]
      );
      if (!etapaCerrada?.length) throw new Error('La asignación ya no está activa para derivar.');

      // Cada técnico tiene su propia asignación y sus cronómetros comienzan en cero.
      const nuevaAsignacion = await queryRunner.query(
        `INSERT INTO administracion.soporte_asignaciones
          (id_solicitud_item, id_soporte, id_asignado_por, fecha_asignacion, fecha_inicio, fecha_fin, estado, observacion, prioridad, instrucciones_tecnico)
         VALUES ($1, $2, $3, CURRENT_TIMESTAMP, NULL, NULL, 'ASIGNADA', NULL, $4, $5)
         RETURNING id`,
        [asignacion.idSolicitudItem, Number(dto.id_a_soporte), idDeSoporte, normalizarPrioridad(asignacion.prioridad), asignacion.instruccionesTecnico ?? null]
      );

      // Aseguramos que la solicitud vuelva a ASIGNADA para el nuevo técnico
      await queryRunner.query(
        `UPDATE administracion.soporte_solicitudes s
         SET estado = 'ASIGNADA', updated_at = CURRENT_TIMESTAMP
         FROM administracion.soporte_solicitud_items si
         WHERE si.id_solicitud = s.id AND si.id = $1`,
        [asignacion.idSolicitudItem]
      );

      await queryRunner.commitTransaction();

      return {
        idAsignacionAnterior: asignacion.id,
        idAsignacion: Number(nuevaAsignacion[0].id),
        idSolicitud,
        codigoTicket: solicitudCodigo?.codigo_ticket,
        nuevoTecnico: dto.id_a_soporte,
      };

    } catch (error: any) {
      await queryRunner.rollbackTransaction();
      this.logger.error(`Error al reasignar soporte: ${error?.message}`, error?.stack);
      throw new InternalServerErrorException(error?.message || 'No se pudo reasignar el soporte.');
    } finally {
      await queryRunner.release();
    }

  }

  async finalizarSoporteTransaccional(
    idAsignacion: number,
    idSoporte: number,
    dto: FinalizarSoporteDto,
  ): Promise<any> {
    const queryRunner = this.dataSource.createQueryRunner();
    await queryRunner.connect();
    await queryRunner.startTransaction();

    try {
      // 1. Buscar la asignación usando TypeORM puro
      const asignacion = await queryRunner.manager.findOne(SoporteAsignacionTypeOrmEntity, {
        where: { id: idAsignacion, idSoporte: idSoporte },
      });

      if (!asignacion) {
        throw new Error('La asignación no existe o no estás autorizado para finalizarla.');
      }

      if (asignacion.estado === 'FINALIZADA') {
        throw new Error('Esta solicitud ya fue finalizada anteriormente.');
      }

      // 2. Actualizar la tabla soporte_asignaciones (Cierre del ticket usando CURRENT_TIMESTAMP de la BD)
      const cierreResult = await queryRunner.query(
        `UPDATE administracion.soporte_asignaciones
         SET fecha_fin = CURRENT_TIMESTAMP,
             fecha_inicio = COALESCE(fecha_inicio, fecha_asignacion),
             estado = 'FINALIZADA',
             observacion = $1
         WHERE id = $2
         RETURNING fecha_fin`,
        [dto.observacion ?? null, idAsignacion]
      );
      asignacion.fechaFin = cierreResult?.[0]?.fecha_fin ?? new Date();

      // Actualizamos también el estado de la cabecera soporte_solicitudes a FINALIZADA
      await queryRunner.query(
        `UPDATE administracion.soporte_solicitudes s
         SET estado = 'FINALIZADA', updated_at = CURRENT_TIMESTAMP
         FROM administracion.soporte_solicitud_items si
         WHERE si.id_solicitud = s.id AND si.id = $1`,
        [asignacion.idSolicitudItem]
      );

      // 3. Crear el registro del acta/evidencia fotográfica
      const nuevaActa = new SoporteArchivoActaTypeOrmEntity();
      nuevaActa.idAsignacion = idAsignacion;
      nuevaActa.nombreArchivo = dto.nombre_archivo;
      nuevaActa.rutaUrl = dto.ruta_url;
      nuevaActa.tipoMime = dto.tipo_mime ?? null;

      await queryRunner.manager.save(nuevaActa);

      await queryRunner.commitTransaction();

      return {
        idAsignacion: asignacion.id,
        fechaFin: asignacion.fechaFin,
        actaUrl: nuevaActa.rutaUrl
      };

    } catch (error: any) {
      await queryRunner.rollbackTransaction();
      this.logger.error(`Error al finalizar soporte: ${error?.message}`, error?.stack);
      throw new InternalServerErrorException(error?.message || 'No se pudo finalizar el soporte.');
    } finally {
      await queryRunner.release();
    }
  }
  async obtenerMisSolicitudesUsuario(idUsuario: number, estadoFiltro?: string): Promise<any[]> {
    await this.asegurarColumnasUbicacionViaje();
    let query = `
      SELECT 
        s.id,
        s.id as id_solicitud,
        s.codigo_ticket,
        s.id_usuario as "idUsuario",
        s.piso,
        s.oficina,
        s.consultorio,
        s.estado,
        BOOL_OR(s.es_manual) as es_manual,
        MAX(s.solicitante_manual) as solicitante_manual,
        s.created_at as fecha_solicitud,
        s.created_at as "createdAt",
        s.updated_at as "updatedAt",
        MAX(a.id) as id_asignacion,
        MAX(a.id_soporte) as id_tecnico,
        MAX(a.observacion) as diagnostico,
        MAX(a.prioridad) as prioridad,
        MAX(ac.ruta_url) as url_acta,
        MAX(ac.nombre_archivo) as nombre_archivo_acta,
        MAX(ac.tipo_mime) as tipo_mime_acta,
        MAX(a.fecha_asignacion) as fecha_asignacion,
        MAX(a.fecha_inicio) as fecha_inicio,
        MAX(a.fecha_fin) as fecha_finalizacion,
        MAX(a.fecha_en_camino) as fecha_en_camino,
        MAX(a.latitud_en_camino) as latitud_en_camino,
        MAX(a.longitud_en_camino) as longitud_en_camino,
        MAX(a.fecha_llegada) as fecha_llegada,
        MAX(a.latitud_llegada) as latitud_llegada,
        MAX(a.longitud_llegada) as longitud_llegada,
        MAX(GREATEST(0, FLOOR(EXTRACT(EPOCH FROM (COALESCE(a.fecha_inicio, a.fecha_fin, CURRENT_TIMESTAMP) - a.fecha_asignacion))))::int) as segundos_desde_asignacion,
        MAX(CASE WHEN a.fecha_inicio IS NOT NULL THEN GREATEST(0, FLOOR(EXTRACT(EPOCH FROM (COALESCE(a.fecha_fin, CURRENT_TIMESTAMP) - a.fecha_inicio))))::int ELSE NULL END) as segundos_desde_inicio,
        GREATEST(0, FLOOR(EXTRACT(EPOCH FROM (CURRENT_TIMESTAMP - s.created_at))))::int as segundos_sin_asignar,
        (SELECT c.calificacion FROM administracion.soporte_calificaciones c WHERE c.id_solicitud = s.id LIMIT 1) as calificacion_usuario,
        (SELECT c.created_at FROM administracion.soporte_calificaciones c WHERE c.id_solicitud = s.id LIMIT 1) as fecha_calificacion_usuario,
        (SELECT MIN(ax.fecha_asignacion)
         FROM administracion.soporte_asignaciones ax
         INNER JOIN administracion.soporte_solicitud_items six ON six.id = ax.id_solicitud_item
         WHERE six.id_solicitud = s.id) as fecha_primera_asignacion,
        (SELECT GREATEST(0, FLOOR(EXTRACT(EPOCH FROM (MIN(ax.fecha_asignacion) - s.created_at))))::int
         FROM administracion.soporte_asignaciones ax
         INNER JOIN administracion.soporte_solicitud_items six ON six.id = ax.id_solicitud_item
         WHERE six.id_solicitud = s.id
         HAVING COUNT(*) > 0) as segundos_hasta_asignacion,
        MAX(COALESCE(NULLIF(TRIM(CONCAT_WS(' ', p.nombres, p.p_apellido, p.s_apellido)), ''), u.name)) as nombre_tecnico,
        (
          SELECT COUNT(*)::int
          FROM administracion.soporte_re_solicitudes rs2
          WHERE rs2.id_solicitud = s.id
        ) as cantidad_resolicitudes,
        (
          SELECT rs2.motivo
          FROM administracion.soporte_re_solicitudes rs2
          WHERE rs2.id_solicitud = s.id
          ORDER BY rs2.id DESC
          LIMIT 1
        ) as ultima_resolicitud_motivo,
        (
          SELECT COALESCE(
            json_agg(
              json_build_object(
                'id', rs2.id,
                'motivo', rs2.motivo,
                'fecha', COALESCE(to_jsonb(rs2)->>'fecha_resolicitud', to_jsonb(rs2)->>'fecha', to_jsonb(rs2)->>'created_at')
              )
              ORDER BY rs2.id ASC
            ),
            '[]'::json
          )
          FROM administracion.soporte_re_solicitudes rs2
          WHERE rs2.id_solicitud = s.id
        ) as resolicitudes_historial
      FROM administracion.soporte_solicitudes s
      LEFT JOIN administracion.soporte_solicitud_items si ON si.id_solicitud = s.id
      LEFT JOIN LATERAL (
        SELECT ax.* FROM administracion.soporte_asignaciones ax
        WHERE ax.id_solicitud_item = si.id
        ORDER BY ax.id DESC LIMIT 1
      ) a ON TRUE
      LEFT JOIN administracion.soporte_archivos_actas ac ON ac.id_asignacion = a.id
      LEFT JOIN administracion.users u ON u.id = a.id_soporte
      LEFT JOIN administracion.persona p ON p.id = u.id_persona
      WHERE s.id_usuario = $1
        AND COALESCE(s.es_manual, FALSE) = FALSE
    `;

    const params: any[] = [idUsuario];
    if (estadoFiltro) {
      query += ` AND s.estado = $2`;
      params.push(estadoFiltro);
    }

    query += `
      GROUP BY s.id, s.codigo_ticket, s.id_usuario, s.piso, s.oficina, s.consultorio, s.estado, s.created_at, s.updated_at
      ORDER BY s.created_at ASC
    `;

    return await this.dataSource.query(query, params);
  }

  async obtenerCalificacionSolicitud(idSolicitud: number, idUsuario: number): Promise<any> {
    const rows = await this.dataSource.query(`
      SELECT c.calificacion, c.created_at as fecha_calificacion,
        COALESCE(NULLIF(TRIM(CONCAT_WS(' ', p.nombres, p.p_apellido, p.s_apellido)), ''), u.name) as nombre_tecnico
      FROM administracion.soporte_solicitudes s
      JOIN administracion.soporte_solicitud_items si ON si.id_solicitud = s.id
      JOIN LATERAL (
        SELECT ax.id, ax.id_soporte FROM administracion.soporte_asignaciones ax
        WHERE ax.id_solicitud_item = si.id AND ax.estado = 'FINALIZADA'
        ORDER BY ax.id DESC LIMIT 1
      ) a ON TRUE
      JOIN administracion.users u ON u.id = a.id_soporte
      LEFT JOIN administracion.persona p ON p.id = u.id_persona
      LEFT JOIN administracion.soporte_calificaciones c ON c.id_solicitud = s.id
      WHERE s.id = $1 AND s.id_usuario = $2 AND s.estado = 'FINALIZADA'
        AND COALESCE(s.es_manual, FALSE) = FALSE
      ORDER BY a.id DESC LIMIT 1
    `, [idSolicitud, idUsuario]);
    if (!rows.length) return null;
    return rows[0];
  }

  async calificarTecnico(idSolicitud: number, idUsuario: number, calificacion: 'MUY_BIEN' | 'BIEN' | 'MAL'): Promise<any> {
    const queryRunner = this.dataSource.createQueryRunner();
    await queryRunner.connect();
    await queryRunner.startTransaction();
    try {
      const solicitud = await queryRunner.query(`
        SELECT s.id FROM administracion.soporte_solicitudes s
        WHERE s.id = $1 AND s.id_usuario = $2 AND s.estado = 'FINALIZADA'
          AND COALESCE(s.es_manual, FALSE) = FALSE
        FOR UPDATE
      `, [idSolicitud, idUsuario]);
      if (!solicitud.length) throw new ConflictException('La solicitud no está finalizada o no te pertenece.');

      const existente = await queryRunner.query(
        `SELECT id FROM administracion.soporte_calificaciones WHERE id_solicitud = $1`, [idSolicitud],
      );
      if (existente.length) throw new ConflictException('Este soporte ya fue calificado y no se puede cambiar.');

      const filas = await queryRunner.query(`
        INSERT INTO administracion.soporte_calificaciones
          (id_solicitud, id_asignacion, id_usuario, id_tecnico, calificacion)
        SELECT $1, a.id, $2, a.id_soporte, $3
        FROM administracion.soporte_solicitud_items si
        JOIN LATERAL (
          SELECT ax.id, ax.id_soporte FROM administracion.soporte_asignaciones ax
          WHERE ax.id_solicitud_item = si.id AND ax.estado = 'FINALIZADA'
          ORDER BY ax.id DESC LIMIT 1
        ) a ON TRUE
        WHERE si.id_solicitud = $1
        ORDER BY a.id DESC LIMIT 1
        RETURNING id, calificacion, created_at as fecha_calificacion
      `, [idSolicitud, idUsuario, calificacion]);
      if (!filas.length) throw new ConflictException('No se encontró el técnico que finalizó el soporte.');
      await queryRunner.commitTransaction();
      return filas[0];
    } catch (error: any) {
      await queryRunner.rollbackTransaction();
      if (error instanceof ConflictException) throw error;
      if (error?.code === '23505') throw new ConflictException('No se pudo actualizar la calificación de este soporte.');
      throw new InternalServerErrorException(error?.message || 'No se pudo guardar la calificación.');
    } finally {
      await queryRunner.release();
    }
  }

  // BANDEJA 2: Lo que ve el Operador (Normalmente PENDIENTES)
  // =========================================================
  // BANDEJA OPERADOR: Solicitudes + Alertas de Re-solicitud
  // =========================================================
  async obtenerBandejaOperador(estadoFiltro?: string): Promise<any[]> {
    await this.asegurarColumnasUbicacionViaje();
    let query = `
      SELECT 
        s.id as id_solicitud, 
        s.codigo_ticket,
        s.created_at as fecha_solicitud, 
        s.piso, 
        s.oficina, 
        s.consultorio,
        s.estado,
        BOOL_OR(s.es_manual) as es_manual,
        MAX(s.solicitante_manual) as solicitante_manual,
        MAX(COALESCE(NULLIF(TRIM(CONCAT_WS(' ', ps.nombres, ps.p_apellido, ps.s_apellido)), ''), us.name)) as nombre_usuario,
        MAX(us.name) as username,
        MAX(ps.ci) as ci_usuario,
        COUNT(DISTINCT rs.id)::int as cantidad_resolicitudes,
        MAX(COALESCE(to_jsonb(rs)->>'fecha_resolicitud', to_jsonb(rs)->>'fecha', to_jsonb(rs)->>'created_at')) as ultima_resolicitud,
        (
          SELECT rs2.motivo
          FROM administracion.soporte_re_solicitudes rs2
          WHERE rs2.id_solicitud = s.id
          ORDER BY rs2.id DESC
          LIMIT 1
        ) as ultima_resolicitud_motivo,
        (
          SELECT COALESCE(
            json_agg(
              json_build_object(
                'id', rs2.id,
                'motivo', rs2.motivo,
                'fecha', COALESCE(to_jsonb(rs2)->>'fecha_resolicitud', to_jsonb(rs2)->>'fecha', to_jsonb(rs2)->>'created_at')
              )
              ORDER BY rs2.id ASC
            ),
            '[]'::json
          )
          FROM administracion.soporte_re_solicitudes rs2
          WHERE rs2.id_solicitud = s.id
        ) as resolicitudes_historial,
        
        -- MAPEAMOS TUS COLUMNAS REALES
        MAX(a.id) as id_asignacion,
        MAX(a.id_soporte) as id_tecnico,
        MAX(a.observacion) as diagnostico,
        MAX(a.prioridad) as prioridad,
        MAX(ac.ruta_url) as url_acta,
        MAX(ac.nombre_archivo) as nombre_archivo_acta,
        MAX(ac.tipo_mime) as tipo_mime_acta,
        MAX(a.fecha_asignacion) as fecha_asignacion,
        MAX(a.fecha_inicio) as fecha_inicio,
        MAX(a.fecha_fin) as fecha_finalizacion,
        MAX(a.fecha_en_camino) as fecha_en_camino,
        MAX(a.latitud_en_camino) as latitud_en_camino,
        MAX(a.longitud_en_camino) as longitud_en_camino,
        MAX(a.fecha_llegada) as fecha_llegada,
        MAX(a.latitud_llegada) as latitud_llegada,
        MAX(a.longitud_llegada) as longitud_llegada,
        MAX(GREATEST(0, FLOOR(EXTRACT(EPOCH FROM (COALESCE(a.fecha_inicio, a.fecha_fin, CURRENT_TIMESTAMP) - a.fecha_asignacion))))::int) as segundos_desde_asignacion,
        MAX(CASE WHEN a.fecha_inicio IS NOT NULL THEN GREATEST(0, FLOOR(EXTRACT(EPOCH FROM (COALESCE(a.fecha_fin, CURRENT_TIMESTAMP) - a.fecha_inicio))))::int ELSE NULL END) as segundos_desde_inicio,
        GREATEST(0, FLOOR(EXTRACT(EPOCH FROM (CURRENT_TIMESTAMP - s.created_at))))::int as segundos_sin_asignar,
        (SELECT c.calificacion FROM administracion.soporte_calificaciones c WHERE c.id_solicitud = s.id LIMIT 1) as calificacion_usuario,
        (SELECT c.created_at FROM administracion.soporte_calificaciones c WHERE c.id_solicitud = s.id LIMIT 1) as fecha_calificacion_usuario,
        (SELECT MIN(ax.fecha_asignacion)
         FROM administracion.soporte_asignaciones ax
         INNER JOIN administracion.soporte_solicitud_items six ON six.id = ax.id_solicitud_item
         WHERE six.id_solicitud = s.id) as fecha_primera_asignacion,
        (SELECT GREATEST(0, FLOOR(EXTRACT(EPOCH FROM (MIN(ax.fecha_asignacion) - s.created_at))))::int
         FROM administracion.soporte_asignaciones ax
         INNER JOIN administracion.soporte_solicitud_items six ON six.id = ax.id_solicitud_item
         WHERE six.id_solicitud = s.id
         HAVING COUNT(*) > 0) as segundos_hasta_asignacion,
        MAX(COALESCE(NULLIF(TRIM(CONCAT_WS(' ', p.nombres, p.p_apellido, p.s_apellido)), ''), u.name)) as nombre_tecnico,
        (
          SELECT r.justificacion
          FROM administracion.soporte_reasignaciones r
          INNER JOIN administracion.soporte_asignaciones a2 ON r.id_asignacion = a2.id
          INNER JOIN administracion.soporte_solicitud_items si2 ON a2.id_solicitud_item = si2.id
          WHERE si2.id_solicitud = s.id
          ORDER BY r.id DESC
          LIMIT 1
        ) as ultima_reasignacion_motivo,
        (
          SELECT COUNT(*)::int
          FROM administracion.soporte_reasignaciones r
          INNER JOIN administracion.soporte_asignaciones a2 ON a2.id = r.id_asignacion
          INNER JOIN administracion.soporte_solicitud_items si2 ON si2.id = a2.id_solicitud_item
          WHERE si2.id_solicitud = s.id
        ) as cantidad_reasignaciones
        
      FROM administracion.soporte_solicitudes s
      LEFT JOIN administracion.soporte_re_solicitudes rs ON rs.id_solicitud = s.id
      -- Puente para llegar a las asignaciones a través de los ítems
      LEFT JOIN administracion.soporte_solicitud_items si ON si.id_solicitud = s.id
      LEFT JOIN LATERAL (
        SELECT ax.* FROM administracion.soporte_asignaciones ax
        WHERE ax.id_solicitud_item = si.id
        ORDER BY ax.id DESC LIMIT 1
      ) a ON TRUE
      LEFT JOIN administracion.soporte_archivos_actas ac ON ac.id_asignacion = a.id
      -- En tu BD la tabla de usuarios es administracion.users y la persona tiene el nombre completo
      LEFT JOIN administracion.users u ON u.id = a.id_soporte
      LEFT JOIN administracion.persona p ON p.id = u.id_persona
      LEFT JOIN administracion.users us ON us.id = s.id_usuario
      LEFT JOIN administracion.persona ps ON ps.id = us.id_persona
    `;

    const queryParams: any[] = [];

    if (estadoFiltro) {
      query += ` WHERE s.estado = $1`;
      queryParams.push(estadoFiltro);
    }

    // AGRUPAMOS POR EL TICKET PRINCIPAL
    query += `
      GROUP BY 
        s.id, 
        s.codigo_ticket,
        s.created_at, 
        s.piso, 
        s.oficina, 
        s.consultorio,
        s.estado
      ORDER BY s.created_at ASC
    `;

    // Ejecutamos la consulta
    const resultados = await this.dataSource.query(query, queryParams);

    // Calculamos el tiempo de resolución
    return resultados.map((ticket: any) => {
      let tiempo_resolucion = 'No finalizado';

      if (ticket.fecha_asignacion && ticket.fecha_finalizacion) {
        const inicio = new Date(ticket.fecha_asignacion).getTime();
        const fin = new Date(ticket.fecha_finalizacion).getTime();
        const diferenciaMinutos = Math.floor((fin - inicio) / (1000 * 60));

        if (diferenciaMinutos < 60) {
          tiempo_resolucion = `${diferenciaMinutos} minutos`;
        } else {
          const horas = Math.floor(diferenciaMinutos / 60);
          const minutos = diferenciaMinutos % 60;
          tiempo_resolucion = `${horas}h ${minutos}m`;
        }
      }

      return {
        ...ticket,
        tiempo_resolucion,
      };
    });
  }
  // =========================================================
  // BANDEJA ADMINISTRADOR: Tiempos de atención e Historial
  // =========================================================
  async obtenerBandejaAdministrador(estadoFiltro?: string): Promise<any[]> {
    let query = `
      SELECT 
        a.id as id_asignacion,
        s.id as id_solicitud,
        s.codigo_ticket,
        s.oficina,
        s.consultorio,
        s.es_manual,
        s.solicitante_manual,
        COALESCE(NULLIF(TRIM(CONCAT_WS(' ', ps.nombres, ps.p_apellido, ps.s_apellido)), ''), us.name) as nombre_usuario,
        us.name as username,
        ps.ci as ci_usuario,
        a.id_soporte as id_tecnico, 
        a.estado,
        a.fecha_asignacion,
        a.fecha_fin,
        
        -- Calculamos la diferencia en minutos desde que se asignó hasta que finalizó
        COALESCE(EXTRACT(EPOCH FROM (a.fecha_fin - a.fecha_asignacion))/60, 0) as minutos_atencion,
        
        -- Contamos cuántas veces se reasignó este ticket
        (SELECT COUNT(*) FROM administracion.soporte_reasignaciones r WHERE r.id_asignacion = a.id) as cantidad_reasignaciones
      
      FROM administracion.soporte_asignaciones a
      
      -- JOIN con la tabla intermedia 'soporte_solicitud_items'
      INNER JOIN administracion.soporte_solicitud_items si ON a.id_solicitud_item = si.id
      
      -- JOIN con la tabla cabecera 'soporte_solicitudes' para obtener la oficina y el id
      INNER JOIN administracion.soporte_solicitudes s ON si.id_solicitud = s.id
      LEFT JOIN administracion.users us ON us.id = s.id_usuario
      LEFT JOIN administracion.persona ps ON ps.id = us.id_persona
    `;

    const params: any[] = [];
    if (estadoFiltro) {
      query += ` WHERE a.estado = $1`;
      params.push(estadoFiltro);
    }

    query += ` ORDER BY a.fecha_asignacion ASC`;

    return await this.dataSource.query(query, params);
  }

  // BANDEJA 3: Lo que ve el Técnico de Soporte
  async obtenerMisTareasTecnico(idSoporte: number, estadoFiltro?: string): Promise<any[]> {
    await this.asegurarColumnasUbicacionViaje();
    let query = `
      SELECT 
        a.id as id_asignacion,
        s.id as id_solicitud,
        s.codigo_ticket,
        a.instrucciones_tecnico,
        s.created_at as fecha_solicitud,
        (SELECT MIN(ax.fecha_asignacion)
         FROM administracion.soporte_asignaciones ax
         INNER JOIN administracion.soporte_solicitud_items six ON six.id = ax.id_solicitud_item
         WHERE six.id_solicitud = s.id) as fecha_primera_asignacion,
        (SELECT GREATEST(0, FLOOR(EXTRACT(EPOCH FROM (MIN(ax.fecha_asignacion) - s.created_at))))::int
         FROM administracion.soporte_asignaciones ax
         INNER JOIN administracion.soporte_solicitud_items six ON six.id = ax.id_solicitud_item
         WHERE six.id_solicitud = s.id
         HAVING COUNT(*) > 0) as segundos_hasta_asignacion,
        s.piso,
        s.oficina,
        s.consultorio,
        s.es_manual,
        s.solicitante_manual,
        COALESCE(NULLIF(TRIM(CONCAT_WS(' ', ps.nombres, ps.p_apellido, ps.s_apellido)), ''), us.name) as nombre_usuario,
        us.name as username,
        ps.ci as ci_usuario,
        a.estado,
        a.prioridad,
        CASE WHEN a.estado = 'ASIGNADA' AND legado.fecha_derivacion IS NOT NULL THEN legado.fecha_derivacion ELSE a.fecha_asignacion END as fecha_asignacion,
        CASE WHEN a.estado = 'ASIGNADA' AND legado.fecha_derivacion IS NOT NULL THEN NULL ELSE a.fecha_inicio END as fecha_inicio,
        a.fecha_fin as fecha_finalizacion,
        a.fecha_en_camino,
        a.latitud_en_camino,
        a.longitud_en_camino,
        a.fecha_llegada,
        a.latitud_llegada,
        a.longitud_llegada,
        GREATEST(0, FLOOR(EXTRACT(EPOCH FROM (COALESCE(a.fecha_inicio, a.fecha_fin, CURRENT_TIMESTAMP) - CASE WHEN a.estado = 'ASIGNADA' AND legado.fecha_derivacion IS NOT NULL THEN legado.fecha_derivacion ELSE a.fecha_asignacion END))))::int as segundos_desde_asignacion,
        CASE WHEN a.fecha_inicio IS NOT NULL AND NOT (a.estado = 'ASIGNADA' AND legado.fecha_derivacion IS NOT NULL) THEN GREATEST(0, FLOOR(EXTRACT(EPOCH FROM (COALESCE(a.fecha_fin, CURRENT_TIMESTAMP) - a.fecha_inicio))))::int ELSE NULL END as segundos_desde_inicio,
        a.observacion as diagnostico,
        (
          SELECT ac.ruta_url
          FROM administracion.soporte_archivos_actas ac
          WHERE ac.id_asignacion = a.id
          ORDER BY ac.id DESC
          LIMIT 1
        ) as url_acta,
        (
          SELECT ac.nombre_archivo
          FROM administracion.soporte_archivos_actas ac
          WHERE ac.id_asignacion = a.id
          ORDER BY ac.id DESC
          LIMIT 1
        ) as nombre_archivo_acta,
        (
          SELECT ac.tipo_mime
          FROM administracion.soporte_archivos_actas ac
          WHERE ac.id_asignacion = a.id
          ORDER BY ac.id DESC
          LIMIT 1
        ) as tipo_mime_acta,
        COALESCE(NULLIF(TRIM(CONCAT_WS(' ', p.nombres, p.p_apellido, p.s_apellido)), ''), u.name) as nombre_tecnico,
        (
          SELECT r.justificacion
          FROM administracion.soporte_reasignaciones r
          INNER JOIN administracion.soporte_asignaciones anterior ON anterior.id = r.id_asignacion
          INNER JOIN administracion.soporte_solicitud_items si2 ON si2.id = anterior.id_solicitud_item
          WHERE si2.id_solicitud = s.id
          ORDER BY r.id DESC LIMIT 1
        ) as ultima_reasignacion_motivo,
        (
          SELECT COUNT(*)::int
          FROM administracion.soporte_reasignaciones r
          INNER JOIN administracion.soporte_asignaciones anterior ON anterior.id = r.id_asignacion
          INNER JOIN administracion.soporte_solicitud_items si2 ON si2.id = anterior.id_solicitud_item
          WHERE si2.id_solicitud = s.id
        ) as cantidad_reasignaciones,
        (
          SELECT COUNT(*)::int
          FROM administracion.soporte_re_solicitudes rs2
          WHERE rs2.id_solicitud = s.id
        ) as cantidad_resolicitudes,
        (
          SELECT rs2.motivo
          FROM administracion.soporte_re_solicitudes rs2
          WHERE rs2.id_solicitud = s.id
          ORDER BY rs2.id DESC
          LIMIT 1
        ) as ultima_resolicitud_motivo,
        (
          SELECT COALESCE(
            json_agg(
              json_build_object(
                'id', rs2.id,
                'motivo', rs2.motivo,
                'fecha', COALESCE(to_jsonb(rs2)->>'fecha_resolicitud', to_jsonb(rs2)->>'fecha', to_jsonb(rs2)->>'created_at')
              )
              ORDER BY rs2.id ASC
            ),
            '[]'::json
          )
          FROM administracion.soporte_re_solicitudes rs2
          WHERE rs2.id_solicitud = s.id
        ) as resolicitudes_historial
      FROM administracion.soporte_asignaciones a
      INNER JOIN administracion.soporte_solicitud_items si ON a.id_solicitud_item = si.id
      INNER JOIN administracion.soporte_solicitudes s ON si.id_solicitud = s.id
      LEFT JOIN administracion.users u ON u.id = a.id_soporte
      LEFT JOIN administracion.persona p ON p.id = u.id_persona
      LEFT JOIN administracion.users us ON us.id = s.id_usuario
      LEFT JOIN administracion.persona ps ON ps.id = us.id_persona
      LEFT JOIN LATERAL (
        SELECT MAX(r.fecha) AS fecha_derivacion
        FROM administracion.soporte_reasignaciones r
        WHERE r.id_asignacion = a.id
      ) legado ON TRUE
      WHERE a.id_soporte = $1
    `;

    const params: any[] = [idSoporte];
    if (estadoFiltro === 'DERIVADA') {
      query += ` AND a.estado = $2`;
      params.push('DERIVADA');
    } else {
      query += ` AND a.estado <> 'DERIVADA'`;
    }
    if (estadoFiltro && estadoFiltro !== 'DERIVADA') {
      query += ` AND a.estado = $2`;
      params.push(estadoFiltro);
    }

    query += ` ORDER BY a.fecha_asignacion ASC`;

    return await this.dataSource.query(query, params);
  }

  async resolicitarSoporte(idSolicitud: number, idUsuario: number, observacion?: string): Promise<any> {
    // 1. Verificamos si la solicitud es del usuario, si no está finalizada y obtenemos su ubicación (oficina, piso)
    const solicitud = await this.dataSource.query(
      `SELECT id, codigo_ticket, estado, piso, oficina, consultorio
       FROM administracion.soporte_solicitudes 
       WHERE id = $1 AND id_usuario = $2`,
      [idSolicitud, idUsuario]
    );

    if (!solicitud.length) {
      throw new Error('La solicitud no existe o no te pertenece.');
    }

    const ticket = solicitud[0];

    if (ticket.estado === 'FINALIZADA') {
      throw new Error('No puedes re-solicitar un ticket que ya está finalizado.');
    }

    const motivoLimpio = observacion?.trim() || 'El usuario está pidiendo atención nuevamente.';

    // 2. Insertamos el registro de re-solicitud (insistencia) en administracion.soporte_re_solicitudes
    await this.dataSource.query(
      `INSERT INTO administracion.soporte_re_solicitudes (id_solicitud, fecha, motivo) 
       VALUES ($1, CURRENT_TIMESTAMP, $2)`,
      [idSolicitud, motivoLimpio]
    );

    // 3. Buscamos el fcm_token (token_dispositivo) del Técnico asignado a este ticket (si ya tiene uno)
    const rowsTecnico = await this.dataSource.query(
      `SELECT DISTINCT ft.token_dispositivo, a.id_soporte
       FROM administracion.soporte_asignaciones a
       INNER JOIN administracion.soporte_solicitud_items si ON a.id_solicitud_item = si.id
       LEFT JOIN administracion.soporte_fcm_tokens ft ON ft.id_usuario = a.id_soporte
       WHERE si.id_solicitud = $1`,
      [idSolicitud]
    );

    const tokensTecnico: string[] = rowsTecnico
      .map((r: any) => r.token_dispositivo)
      .filter(Boolean);
    const idsTecnico: number[] = rowsTecnico
      .map((r: any) => Number(r.id_soporte))
      .filter((id: number) => Number.isFinite(id) && id > 0);

    // 4. Buscamos todos los Operadores activos y sus tokens FCM
    const rowsOperadores = await this.dataSource.query(
      `SELECT DISTINCT ft.token_dispositivo, sur.id_usuario
       FROM administracion.soportes_usuarios_roles sur
       LEFT JOIN administracion.soporte_fcm_tokens ft ON ft.id_usuario = sur.id_usuario
       WHERE UPPER(sur.rol_soporte) = 'OPERADOR' AND sur.estado = true`
    );

    const tokensOperadores: string[] = rowsOperadores
      .map((r: any) => r.token_dispositivo)
      .filter(Boolean);
    const idsOperadores: number[] = rowsOperadores
      .map((r: any) => Number(r.id_usuario))
      .filter((id: number) => Number.isFinite(id) && id > 0);

    // Unificamos ambos conjuntos sin duplicados para enviar la alerta tanto a Operadores como al Técnico asignado
    const tokensDestino = Array.from(new Set([...tokensTecnico, ...tokensOperadores]));
    const idsDestino = Array.from(new Set([...idsTecnico, ...idsOperadores]));

    return {
      exito: true,
      mensaje: 'Re-solicitud enviada correctamente al operador y al técnico.',
      codigoTicket: ticket.codigo_ticket,
      piso: ticket.piso,
      oficina: ticket.oficina,
      consultorio: ticket.consultorio,
      motivo: motivoLimpio,
      tokensTecnico,
      tokensOperadores,
      tokensDestino,
      idsDestino,
    };
  }

  async obtenerIdsOperadoresActivos(): Promise<number[]> {
    const rows = await this.dataSource.query(
      `SELECT DISTINCT id_usuario
       FROM administracion.soportes_usuarios_roles
       WHERE UPPER(rol_soporte) = 'OPERADOR' AND estado = true`
    );
    return rows
      .map((r: any) => Number(r.id_usuario))
      .filter((id: number) => Number.isFinite(id) && id > 0);
  }

  async obtenerTokensOperadoresActivos(): Promise<string[]> {
    const rows = await this.dataSource.query(
      `SELECT DISTINCT ft.token_dispositivo
       FROM administracion.soportes_usuarios_roles sur
       INNER JOIN administracion.soporte_fcm_tokens ft ON ft.id_usuario = sur.id_usuario
       WHERE sur.rol_soporte = 'OPERADOR' AND sur.estado = true`
    );
    return rows.map((r: any) => r.token_dispositivo).filter(Boolean);
  }
  // Añade esta función en tu clase PostgresSoporteRepository
  async obtenerItemsDeSolicitud(idSolicitud: number): Promise<any[]> {
    const items = await this.dataSource.query(
      `SELECT id, id_tipo_soporte, descripcion
       FROM administracion.soporte_solicitud_items
       WHERE id_solicitud = $1
       ORDER BY id ASC`,
      [idSolicitud]
    );
    if (items.length === 0) return items;

    try {
      const adjuntos = await this.dataSource.query(
        `SELECT id, nombre_archivo, ruta_url, tipo_mime
         FROM administracion.soporte_solicitud_adjuntos
         WHERE id_solicitud = $1
         ORDER BY id ASC`,
        [idSolicitud]
      );
      return items.map((item: any) => ({ ...item, adjuntos }));
    } catch (error: any) {
      // La tabla es una migración opcional; los detalles del ticket deben seguir disponibles si aún no se aplicó.
      if (error?.code === '42P01' || error?.driverError?.code === '42P01') {
        return items.map((item: any) => ({ ...item, adjuntos: [] }));
      }
      throw error;
    }
  }

  async obtenerHistorialTecnicosSolicitud(idSolicitud: number): Promise<any[]> {
    return await this.dataSource.query(`
      WITH etapas AS (
        SELECT a.id AS id_asignacion, a.id_soporte,
          COALESCE(NULLIF(TRIM(CONCAT_WS(' ', p.nombres, p.p_apellido, p.s_apellido)), ''), u.name) AS nombre_tecnico,
          COALESCE(NULLIF(TRIM(CONCAT_WS(' ', pd.nombres, pd.p_apellido, pd.s_apellido)), ''), ud.name,
            NULLIF(TRIM(CONCAT_WS(' ', pa.nombres, pa.p_apellido, pa.s_apellido)), ''), ua.name) AS nombre_actor,
          COALESCE(entrada.rol_derivacion, CASE WHEN entrada.id IS NULL THEN 'OPERADOR' ELSE NULL END) AS rol_actor,
          CASE WHEN entrada.id IS NULL THEN 'ASIGNACIÓN INICIAL'
               WHEN entrada.rol_derivacion = 'OPERADOR' THEN 'REASIGNACIÓN POR OPERADOR'
               WHEN entrada.rol_derivacion = 'TECNICO' THEN 'DERIVACIÓN POR TÉCNICO'
               ELSE 'REASIGNACIÓN HISTÓRICA (ROL NO REGISTRADO)' END AS tipo_movimiento,
          COALESCE(NULLIF(TRIM(CONCAT_WS(' ', pdes.nombres, pdes.p_apellido, pdes.s_apellido)), ''), udest.name) AS nombre_destino,
          CASE WHEN a.estado = 'ASIGNADA' AND legado.fecha_derivacion IS NOT NULL THEN legado.fecha_derivacion ELSE a.fecha_asignacion END AS fecha_asignacion,
          CASE WHEN a.estado = 'ASIGNADA' AND legado.fecha_derivacion IS NOT NULL THEN NULL ELSE a.fecha_inicio END AS fecha_inicio,
          a.fecha_fin, a.estado,
          GREATEST(0, FLOOR(EXTRACT(EPOCH FROM (COALESCE(a.fecha_inicio, a.fecha_fin, CURRENT_TIMESTAMP) - CASE WHEN a.estado = 'ASIGNADA' AND legado.fecha_derivacion IS NOT NULL THEN legado.fecha_derivacion ELSE a.fecha_asignacion END))))::int AS segundos_desde_asignacion,
          CASE WHEN a.fecha_inicio IS NOT NULL AND NOT (a.estado = 'ASIGNADA' AND legado.fecha_derivacion IS NOT NULL)
            THEN GREATEST(0, FLOOR(EXTRACT(EPOCH FROM (COALESCE(a.fecha_fin, CURRENT_TIMESTAMP) - a.fecha_inicio))))::int
            ELSE NULL END AS segundos_en_proceso,
          entrada.justificacion AS motivo_entrada,
          salida.rol_derivacion,
          salida.fecha AS fecha_derivacion,
          salida.justificacion AS motivo_derivacion,
          CASE WHEN a.estado = 'ASIGNADA' AND legado.fecha_derivacion IS NOT NULL THEN legado.fecha_derivacion ELSE a.fecha_asignacion END AS orden
        FROM administracion.soporte_asignaciones a
        INNER JOIN administracion.soporte_solicitud_items si ON si.id = a.id_solicitud_item
        LEFT JOIN administracion.users u ON u.id = a.id_soporte
        LEFT JOIN administracion.persona p ON p.id = u.id_persona
        LEFT JOIN administracion.users ua ON ua.id = a.id_asignado_por
        LEFT JOIN administracion.persona pa ON pa.id = ua.id_persona
        LEFT JOIN LATERAL (
          SELECT r0.* FROM administracion.soporte_reasignaciones r0
          WHERE r0.id_a_soporte = a.id_soporte
            AND r0.fecha <= COALESCE(a.fecha_asignacion, CURRENT_TIMESTAMP)
            AND EXISTS (
              SELECT 1 FROM administracion.soporte_asignaciones ax
              WHERE ax.id = r0.id_asignacion AND ax.id_solicitud_item = a.id_solicitud_item
            )
          ORDER BY r0.fecha DESC, r0.id DESC LIMIT 1
        ) entrada ON TRUE
        LEFT JOIN administracion.users ud ON ud.id = entrada.id_de_soporte
        LEFT JOIN administracion.persona pd ON pd.id = ud.id_persona
        LEFT JOIN LATERAL (
          SELECT MAX(r2.fecha) AS fecha_derivacion
          FROM administracion.soporte_reasignaciones r2
          WHERE r2.id_asignacion = a.id AND r2.id_de_soporte <> a.id_soporte
        ) legado ON TRUE
        LEFT JOIN LATERAL (
          SELECT r1.justificacion, r1.rol_derivacion, r1.fecha, r1.id_a_soporte FROM administracion.soporte_reasignaciones r1
          WHERE r1.id_asignacion = a.id
            AND (a.estado = 'DERIVADA' OR r1.id_de_soporte = a.id_soporte)
          ORDER BY r1.id DESC LIMIT 1
        ) salida ON TRUE
        LEFT JOIN administracion.users udest ON udest.id = salida.id_a_soporte
        LEFT JOIN administracion.persona pdes ON pdes.id = udest.id_persona
        WHERE si.id_solicitud = $1

        UNION ALL

        -- Compatibilidad con derivaciones antiguas que sobrescribieron la fila de asignación.
        SELECT -r.id AS id_asignacion, r.id_de_soporte AS id_soporte,
          COALESCE(NULLIF(TRIM(CONCAT_WS(' ', p.nombres, p.p_apellido, p.s_apellido)), ''), u.name) AS nombre_tecnico,
          COALESCE(NULLIF(TRIM(CONCAT_WS(' ', pd.nombres, pd.p_apellido, pd.s_apellido)), ''), ud.name) AS nombre_actor,
          r.rol_derivacion AS rol_actor,
          CASE WHEN r.rol_derivacion = 'OPERADOR' THEN 'REASIGNACIÓN POR OPERADOR'
               WHEN r.rol_derivacion = 'TECNICO' THEN 'DERIVACIÓN POR TÉCNICO'
               ELSE 'REASIGNACIÓN HISTÓRICA (ROL NO REGISTRADO)' END AS tipo_movimiento,
          COALESCE(NULLIF(TRIM(CONCAT_WS(' ', pdes.nombres, pdes.p_apellido, pdes.s_apellido)), ''), udest.name) AS nombre_destino,
          NULL::timestamp AS fecha_asignacion, NULL::timestamp AS fecha_inicio, r.fecha AS fecha_fin,
          'DERIVADA'::varchar AS estado, NULL::int AS segundos_desde_asignacion,
          NULL::int AS segundos_en_proceso, r.justificacion AS motivo_entrada,
          r.rol_derivacion, r.fecha AS fecha_derivacion, r.justificacion AS motivo_derivacion,
          r.fecha AS orden
        FROM administracion.soporte_reasignaciones r
        INNER JOIN administracion.soporte_asignaciones asignacion_actual ON asignacion_actual.id = r.id_asignacion
        INNER JOIN administracion.soporte_solicitud_items si ON si.id = asignacion_actual.id_solicitud_item
        LEFT JOIN administracion.users u ON u.id = r.id_de_soporte
        LEFT JOIN administracion.persona p ON p.id = u.id_persona
        LEFT JOIN administracion.users ua ON ua.id = r.id_de_soporte
        LEFT JOIN administracion.persona pa ON pa.id = ua.id_persona
        LEFT JOIN administracion.users ud ON ud.id = r.id_de_soporte
        LEFT JOIN administracion.persona pd ON pd.id = ud.id_persona
        LEFT JOIN administracion.users udest ON udest.id = r.id_a_soporte
        LEFT JOIN administracion.persona pdes ON pdes.id = udest.id_persona
        WHERE si.id_solicitud = $1
          AND NOT EXISTS (
            SELECT 1 FROM administracion.soporte_asignaciones anterior
            WHERE anterior.id_solicitud_item = si.id
              AND anterior.id_soporte = r.id_de_soporte
              AND anterior.estado = 'DERIVADA'
          )
      )
      SELECT id_asignacion, id_soporte, nombre_tecnico, nombre_actor, rol_actor, tipo_movimiento, nombre_destino,
        fecha_asignacion, fecha_inicio, fecha_fin, estado, segundos_desde_asignacion, segundos_en_proceso,
        motivo_entrada, rol_derivacion, fecha_derivacion, motivo_derivacion
      FROM etapas ORDER BY orden ASC NULLS LAST, id_asignacion ASC
    `, [idSolicitud]);
  }

  async obtenerActaDeSolicitud(idSolicitud: number): Promise<any | null> {
    const query = `
      SELECT 
        ac.id, 
        ac.id_asignacion, 
        ac.nombre_archivo, 
        ac.ruta_url, 
        ac.tipo_mime, 
        ac.created_at
      FROM administracion.soporte_archivos_actas ac
      INNER JOIN administracion.soporte_asignaciones a ON ac.id_asignacion = a.id
      INNER JOIN administracion.soporte_solicitud_items si ON a.id_solicitud_item = si.id
      WHERE si.id_solicitud = $1
      ORDER BY ac.id DESC
      LIMIT 1
    `;
    const rows = await this.dataSource.query(query, [idSolicitud]);
    return rows && rows.length > 0 ? rows[0] : null;
  }

  async agregarItemASolicitud(idSolicitud: number, idTipoSoporte: number, descripcion: string): Promise<any> {
    const query = `
    INSERT INTO administracion.soporte_solicitud_items (id_solicitud, id_tipo_soporte, descripcion)
    VALUES ($1, $2, $3) RETURNING id
  `;
    return await this.dataSource.query(query, [idSolicitud, idTipoSoporte, descripcion]);
  }

  async obtenerTecnicosDisponibles(): Promise<any[]> {
    // Cruza la tabla de roles con administracion.users (y persona) para obtener el nombre
    const query = `
      SELECT 
        u.id, 
        COALESCE(NULLIF(TRIM(CONCAT_WS(' ', p.nombres, p.p_apellido, p.s_apellido)), ''), u.name) as nombre
      FROM administracion.soportes_usuarios_roles sur
      JOIN administracion.users u ON sur.id_usuario = u.id
      LEFT JOIN administracion.persona p ON p.id = u.id_persona
      WHERE sur.rol_soporte = 'TECNICO' AND sur.estado = true
    `;
    return await this.dataSource.query(query);
  }

  // TÉCNICO: Poner soporte EN_PROCESO
  async registrarTecnicoEnCamino(idAsignacion: number, idSoporte: number, latitud: number, longitud: number): Promise<any> {
    await this.asegurarColumnasUbicacionViaje();
    const filas = await this.dataSource.query(
      `UPDATE administracion.soporte_asignaciones
       SET fecha_en_camino = COALESCE(fecha_en_camino, CURRENT_TIMESTAMP),
           latitud_en_camino = COALESCE(latitud_en_camino, $3),
           longitud_en_camino = COALESCE(longitud_en_camino, $4)
       WHERE id = $1 AND id_soporte = $2 AND estado IN ('ASIGNADA', 'EN_PROCESO')
       RETURNING id AS id_asignacion, fecha_en_camino, latitud_en_camino, longitud_en_camino,
         fecha_llegada, latitud_llegada, longitud_llegada`,
      [idAsignacion, idSoporte, latitud, longitud],
    );
    return filas?.[0] ?? null;
  }

  async registrarLlegadaTecnico(idAsignacion: number, idSoporte: number, latitud: number, longitud: number): Promise<any> {
    await this.asegurarColumnasUbicacionViaje();
    const filas = await this.dataSource.query(
      `UPDATE administracion.soporte_asignaciones
       SET fecha_llegada = COALESCE(fecha_llegada, CURRENT_TIMESTAMP),
           latitud_llegada = COALESCE(latitud_llegada, $3),
           longitud_llegada = COALESCE(longitud_llegada, $4)
       WHERE id = $1 AND id_soporte = $2 AND estado IN ('ASIGNADA', 'EN_PROCESO')
         AND fecha_en_camino IS NOT NULL
       RETURNING id AS id_asignacion, fecha_en_camino, latitud_en_camino, longitud_en_camino,
         fecha_llegada, latitud_llegada, longitud_llegada`,
      [idAsignacion, idSoporte, latitud, longitud],
    );
    return filas?.[0] ?? null;
  }

  async ponerEnProcesoSoporte(idSolicitud: number, idSoporte: number): Promise<any> {
    const queryRunner = this.dataSource.createQueryRunner();
    await queryRunner.connect();
    await queryRunner.startTransaction();

    try {
      // Actualizamos la asignación del técnico a EN_PROCESO y registramos fecha_inicio
      const asignaciones = await queryRunner.query(
        `UPDATE administracion.soporte_asignaciones a
         SET estado = 'EN_PROCESO',
             fecha_asignacion = CASE WHEN EXISTS (
               SELECT 1 FROM administracion.soporte_reasignaciones r WHERE r.id_asignacion = a.id
             ) THEN (
               SELECT MAX(r.fecha) FROM administracion.soporte_reasignaciones r WHERE r.id_asignacion = a.id
             ) ELSE a.fecha_asignacion END,
             fecha_inicio = CASE WHEN EXISTS (
               SELECT 1 FROM administracion.soporte_reasignaciones r WHERE r.id_asignacion = a.id
             ) THEN CURRENT_TIMESTAMP ELSE COALESCE(a.fecha_inicio, CURRENT_TIMESTAMP) END
         WHERE a.id = (
           SELECT ax.id
           FROM administracion.soporte_asignaciones ax
           INNER JOIN administracion.soporte_solicitud_items si ON si.id = ax.id_solicitud_item
           WHERE si.id_solicitud = $1 AND ax.id_soporte = $2 AND ax.estado = 'ASIGNADA'
           ORDER BY ax.id DESC LIMIT 1
         )
         RETURNING a.id`,
        [idSolicitud, idSoporte]
      );

      if (!asignaciones || asignaciones.length === 0) {
        throw new Error('No se encontró la asignación de este ticket para tu usuario.');
      }

      // Actualizamos el estado de la cabecera de la solicitud a EN_PROCESO
      await queryRunner.query(
        `UPDATE administracion.soporte_solicitudes
         SET estado = 'EN_PROCESO', updated_at = CURRENT_TIMESTAMP
         WHERE id = $1`,
        [idSolicitud]
      );

      await queryRunner.commitTransaction();
      return { exito: true, estado: 'EN_PROCESO' };
    } catch (error: any) {
      await queryRunner.rollbackTransaction();
      this.logger.error(`Error al poner en proceso el soporte: ${error?.message}`, error?.stack);
      throw new InternalServerErrorException(error?.message || 'No se pudo poner en proceso el soporte.');
    } finally {
      await queryRunner.release();
    }
  }

  // OPERADOR: Reasignar técnico guardando justificación en soporte_reasignaciones (Opción A)
  async reasignarPorOperador(idSolicitud: number, idNuevoTecnico: number, justificacion: string, prioridad?: string, idOperador?: number, instruccionesTecnico?: string): Promise<any> {
    const queryRunner = this.dataSource.createQueryRunner();
    await queryRunner.connect();
    await queryRunner.startTransaction();

    try {
      // 1. Buscamos la asignación actual del ticket
      const asignacionActual = await queryRunner.query(
        `SELECT a.id, a.id_soporte, a.estado, a.prioridad, a.id_solicitud_item, a.id_asignado_por, a.instrucciones_tecnico,
                s.estado as estado_solicitud, s.codigo_ticket
         FROM administracion.soporte_asignaciones a
         INNER JOIN administracion.soporte_solicitud_items si ON a.id_solicitud_item = si.id
         INNER JOIN administracion.soporte_solicitudes s ON si.id_solicitud = s.id
         WHERE si.id_solicitud = $1
         ORDER BY a.id DESC LIMIT 1`,
        [idSolicitud]
      );

      if (!asignacionActual || asignacionActual.length === 0) {
        throw new Error('Este ticket aún no tiene una asignación activa para reasignar.');
      }

      const asignacion = asignacionActual[0];

      if (Number(asignacion.id_soporte) === Number(idNuevoTecnico)) {
        throw new Error('El ticket ya está asignado a este mismo técnico.');
      }

      // 2. Insertamos el historial en administracion.soporte_reasignaciones
      await queryRunner.query(
        `INSERT INTO administracion.soporte_reasignaciones
         (id_asignacion, id_de_soporte, id_a_soporte, justificacion, fecha, rol_derivacion)
         VALUES ($1, $2, $3, $4, CURRENT_TIMESTAMP, 'OPERADOR')`,
        [asignacion.id, asignacion.id_soporte, idNuevoTecnico, justificacion]
      );

      const prioridadFinal = normalizarPrioridad(prioridad || asignacion.prioridad);

      // 3. Cerramos la asignación anterior y guardamos su tiempo.
      await queryRunner.query(
        `UPDATE administracion.soporte_asignaciones
         SET estado = 'DERIVADA', fecha_fin = CURRENT_TIMESTAMP
         WHERE id = $1`,
        [asignacion.id]
      );

      const nuevaAsignacion = await queryRunner.query(
        `INSERT INTO administracion.soporte_asignaciones
          (id_solicitud_item, id_soporte, id_asignado_por, fecha_asignacion, fecha_inicio, fecha_fin, estado, observacion, prioridad, instrucciones_tecnico)
         VALUES ($1, $2, $3, CURRENT_TIMESTAMP, NULL, NULL, 'ASIGNADA', NULL, $4, $5)
         RETURNING id`,
        [asignacion.id_solicitud_item, idNuevoTecnico, idOperador || asignacion.id_asignado_por, prioridadFinal, instruccionesTecnico?.trim() || asignacion.instrucciones_tecnico || null]
      );

      await queryRunner.query(
        `UPDATE administracion.soporte_solicitudes
         SET estado = 'ASIGNADA', updated_at = CURRENT_TIMESTAMP
         WHERE id = $1`,
        [idSolicitud]
      );

      await queryRunner.commitTransaction();
      return {
        exito: true,
        idAsignacionAnterior: asignacion.id,
        idAsignacion: Number(nuevaAsignacion[0].id),
        idDeSoporte: asignacion.id_soporte,
        idASoporte: idNuevoTecnico,
        codigoTicket: asignacion.codigo_ticket,
        instruccionesTecnico: instruccionesTecnico?.trim() || asignacion.instrucciones_tecnico || null,
      };
    } catch (error: any) {
      await queryRunner.rollbackTransaction();
      this.logger.error(`Error al reasignar por operador: ${error?.message}`, error?.stack);
      throw new InternalServerErrorException(error?.message || 'No se pudo reasignar el soporte.');
    } finally {
      await queryRunner.release();
    }
  }

  // =========================================================
  // GESTIÓN DE ROLES (ADMINISTRADOR)
  // =========================================================

  async esUsuarioAdministrador(idUsuario: number): Promise<boolean> {
    if (!idUsuario) return false;
    const rows = await this.dataSource.query(
      `SELECT rol_soporte
       FROM administracion.soportes_usuarios_roles
       WHERE id_usuario = $1 AND UPPER(rol_soporte) = 'ADMINISTRADOR' AND estado = true
       LIMIT 1`,
      [idUsuario]
    );
    return rows && rows.length > 0;
  }

  async obtenerRolesDeUsuario(idUsuario: number): Promise<string[]> {
    if (!idUsuario) return [];
    const rows = await this.dataSource.query(
      `SELECT DISTINCT UPPER(rol_soporte) as rol
       FROM administracion.soportes_usuarios_roles
       WHERE id_usuario = $1 AND estado = true`,
      [idUsuario]
    );
    return rows.map((r: any) => r.rol);
  }

  private async asegurarEsquemaMultiRol(queryRunner: any): Promise<void> {
    try {
      // Si existía una restricción UNIQUE o PK de una sola columna sobre id_usuario,
      // la reemplazamos por una restricción compuesta (id_usuario, rol_soporte) para permitir múltiples roles por funcionario.
      const constraints = await queryRunner.query(`
        SELECT c.conname, c.contype, pg_get_constraintdef(c.oid) as def
        FROM pg_constraint c
        JOIN pg_namespace n ON n.oid = c.connamespace
        JOIN pg_class t ON t.oid = c.conrelid
        WHERE n.nspname = 'administracion'
          AND t.relname = 'soportes_usuarios_roles'
          AND c.contype IN ('p', 'u')
      `);
      for (const c of constraints || []) {
        const def = String(c.def || '');
        if (def.includes('(id_usuario)') && !def.includes('rol_soporte')) {
          await queryRunner.query(
            `ALTER TABLE administracion.soportes_usuarios_roles DROP CONSTRAINT IF EXISTS "${c.conname}"`
          );
        }
      }
    } catch (_e) {
      // Ignoramos si el usuario de BD no tiene permisos DDL o si ya fue ajustado
    }
  }

  async obtenerUsuariosConRoles(): Promise<any[]> {
    const query = `
      SELECT
        u.id as id_usuario,
        u.name as username,
        u.email,
        COALESCE(
          NULLIF(TRIM(CONCAT_WS(' ', p.nombres, p.p_apellido, p.s_apellido)), ''),
          u.name
        ) as nombre_completo,
        p.ci as ci,
        COALESCE(sur.rol_principal, 'SIN_ROL') as rol_soporte,
        COALESCE(sur.roles_activos, ARRAY[]::text[]) as roles_activos,
        CASE WHEN sur.id_usuario IS NOT NULL AND array_length(sur.roles_activos, 1) > 0 THEN true ELSE false END as tiene_rol_especial
      FROM administracion.users u
      LEFT JOIN administracion.persona p ON p.id = u.id_persona
      LEFT JOIN (
        SELECT
          id_usuario,
          ARRAY_AGG(DISTINCT UPPER(rol_soporte) ORDER BY UPPER(rol_soporte)) as roles_activos,
          MAX(UPPER(rol_soporte)) as rol_principal
        FROM administracion.soportes_usuarios_roles
        WHERE estado = true
        GROUP BY id_usuario
      ) sur ON sur.id_usuario = u.id
      WHERE u.estado = true
      ORDER BY
        CASE WHEN sur.id_usuario IS NOT NULL THEN 0 ELSE 1 END ASC,
        COALESCE(NULLIF(TRIM(CONCAT_WS(' ', p.nombres, p.p_apellido, p.s_apellido)), ''), u.name) ASC
    `;
    return await this.dataSource.query(query);
  }

  async asignarRolUsuario(idUsuario: number, rolSoporte: string): Promise<any> {
    const rolLimpio = String(rolSoporte || '').trim().toUpperCase();
    const rolesPermitidos = ['USUARIO', 'OPERADOR', 'TECNICO', 'ADMINISTRADOR'];
    if (!rolesPermitidos.includes(rolLimpio)) {
      throw new Error('Rol no válido. Solo se permiten USUARIO, OPERADOR, TECNICO o ADMINISTRADOR.');
    }

    const queryRunner = this.dataSource.createQueryRunner();
    await queryRunner.connect();
    await queryRunner.startTransaction();

    try {
      await this.asegurarEsquemaMultiRol(queryRunner);

      // Verificamos si este usuario ya tiene ese rol específico (activo o inactivo)
      const existenteMismoRol = await queryRunner.query(
        `SELECT id_usuario, rol_soporte
         FROM administracion.soportes_usuarios_roles
         WHERE id_usuario = $1 AND UPPER(rol_soporte) = $2
         LIMIT 1`,
        [idUsuario, rolLimpio]
      );

      if (existenteMismoRol && existenteMismoRol.length > 0) {
        await queryRunner.query(
          `UPDATE administracion.soportes_usuarios_roles
           SET estado = true, rol_soporte = $1
           WHERE id_usuario = $2 AND UPPER(rol_soporte) = $1`,
          [rolLimpio, idUsuario]
        );
      } else {
        // Reutilizamos una fila inactiva si existe, o insertamos una nueva fila activa para permitir múltiples roles simultáneos
        const filaInactiva = await queryRunner.query(
          `SELECT rol_soporte
           FROM administracion.soportes_usuarios_roles
           WHERE id_usuario = $1 AND estado = false
           LIMIT 1`,
          [idUsuario]
        );

        if (filaInactiva && filaInactiva.length > 0) {
          await queryRunner.query(
            `UPDATE administracion.soportes_usuarios_roles
             SET rol_soporte = $1, estado = true
             WHERE id_usuario = $2 AND rol_soporte = $3`,
            [rolLimpio, idUsuario, filaInactiva[0].rol_soporte]
          );
        } else {
          await queryRunner.query(
            `INSERT INTO administracion.soportes_usuarios_roles (id_usuario, rol_soporte, estado)
             VALUES ($1, $2, true)`,
            [idUsuario, rolLimpio]
          );
        }
      }

      const rolesActivosRows = await queryRunner.query(
        `SELECT DISTINCT UPPER(rol_soporte) as rol
         FROM administracion.soportes_usuarios_roles
         WHERE id_usuario = $1 AND estado = true`,
        [idUsuario]
      );
      const rolesActivos = rolesActivosRows.map((r: any) => r.rol);

      await queryRunner.commitTransaction();
      return {
        id_usuario: Number(idUsuario),
        rol_soporte: rolLimpio,
        roles_activos: rolesActivos,
        estado: true,
      };
    } catch (error: any) {
      await queryRunner.rollbackTransaction();
      this.logger.error(`Error al asignar rol a usuario #${idUsuario}: ${error?.message}`, error?.stack);
      throw new InternalServerErrorException(error?.message || 'No se pudo asignar el rol al usuario.');
    } finally {
      await queryRunner.release();
    }
  }

  async quitarRolUsuario(idUsuario: number, rolSoporte?: string): Promise<any> {
    if (rolSoporte && rolSoporte.trim()) {
      const rolLimpio = rolSoporte.trim().toUpperCase();
      await this.dataSource.query(
        `UPDATE administracion.soportes_usuarios_roles
         SET estado = false
         WHERE id_usuario = $1 AND UPPER(rol_soporte) = $2`,
        [idUsuario, rolLimpio]
      );
    } else {
      await this.dataSource.query(
        `UPDATE administracion.soportes_usuarios_roles
         SET estado = false
         WHERE id_usuario = $1`,
        [idUsuario]
      );
    }

    const rolesRestantesRows = await this.dataSource.query(
      `SELECT DISTINCT UPPER(rol_soporte) as rol
       FROM administracion.soportes_usuarios_roles
       WHERE id_usuario = $1 AND estado = true`,
      [idUsuario]
    );
    const rolesRestantes = rolesRestantesRows.map((r: any) => r.rol);

    return {
      id_usuario: Number(idUsuario),
      rol_soporte: rolesRestantes[0] || 'SIN_ROL',
      roles_activos: rolesRestantes,
      estado: rolesRestantes.length > 0,
    };
  }

  async obtenerConsultoriosPorSede(idSede: number): Promise<any[]> {
    const query = `
      SELECT id, numero_consultorio, id_sedes
      FROM plataforma.consultorio
      WHERE id_sedes = $1
        AND estado = true
      ORDER BY numero_consultorio ASC
    `;
    return await this.dataSource.query(query, [idSede]);
  }

  async obtenerTiposSoporteConOpciones(): Promise<any[]> {
    return await this.dataSource.query(`
      SELECT t.id AS id_tipo_soporte, t.nombre AS tipo_nombre, t.estado AS tipo_estado,
             o.id AS id_opcion, o.nombre AS opcion_nombre, o.estado AS opcion_estado
      FROM administracion.soporte_tipos t
      LEFT JOIN administracion.soporte_tipo_opciones o ON o.id_tipo_soporte = t.id
      WHERE t.estado = true
      ORDER BY t.id, o.nombre
    `);
  }

  async crearOpcionTipoSoporte(idTipoSoporte: number, nombre: string): Promise<any> {
    const rows = await this.dataSource.query(`
      INSERT INTO administracion.soporte_tipo_opciones (id_tipo_soporte, nombre)
      SELECT id, $2 FROM administracion.soporte_tipos WHERE id = $1 AND estado = true
      RETURNING id, id_tipo_soporte, nombre, estado, created_at, updated_at
    `, [idTipoSoporte, nombre]);
    return rows[0] ?? null;
  }

  async alternarEstadoOpcionTipoSoporte(idOpcion: number, estado: boolean): Promise<any> {
    const rows = await this.dataSource.query(`
      UPDATE administracion.soporte_tipo_opciones
      SET estado = $2, updated_at = CURRENT_TIMESTAMP
      WHERE id = $1
      RETURNING id, id_tipo_soporte, nombre, estado, created_at, updated_at
    `, [idOpcion, estado]);
    return rows[0] ?? null;
  }

  async guardarAdjuntoSolicitud(idSolicitud: number, idUsuario: number, archivo: { nombre_archivo: string; ruta_url: string; tipo_mime: string }): Promise<any> {
    const rows = await this.dataSource.query(`
      INSERT INTO administracion.soporte_solicitud_adjuntos
        (id_solicitud, nombre_archivo, ruta_url, tipo_mime)
      SELECT s.id, $3, $4, $5
      FROM administracion.soporte_solicitudes s
      WHERE s.id = $1 AND s.id_usuario = $2
      RETURNING id, id_solicitud, nombre_archivo, ruta_url, tipo_mime, created_at
    `, [idSolicitud, idUsuario, archivo.nombre_archivo, archivo.ruta_url, archivo.tipo_mime]);
    return rows[0] ?? null;
  }

  async obtenerNombreUsuarioPorId(idUsuario: number): Promise<string> {
    const rows = await this.dataSource.query(
      `SELECT COALESCE(NULLIF(TRIM(CONCAT_WS(' ', p.nombres, p.p_apellido, p.s_apellido)), ''), u.name) as nombre
       FROM administracion.users u
       LEFT JOIN administracion.persona p ON p.id = u.id_persona
       WHERE u.id = $1
       LIMIT 1`,
      [idUsuario],
    );
    return rows[0]?.nombre || `Usuario #${idUsuario}`;
  }

  async obtenerReporteSoportesConcluidos(
    rol: string,
    idUsuario: number,
    fechaInicio?: string,
    fechaFin?: string,
  ): Promise<any[]> {
    const rolNormalizado = String(rol || '').trim().toUpperCase();
    if (rolNormalizado !== 'OPERADOR' && rolNormalizado !== 'TECNICO') {
      throw new ForbiddenException('Solo los roles OPERADOR o TECNICO pueden exportar este reporte.');
    }

    const esFechaValida = (v?: string) => Boolean(v && /^\d{4}-\d{2}-\d{2}$/.test(v.trim()));
    const inicioLimpio = esFechaValida(fechaInicio) ? fechaInicio!.trim() : null;
    const finLimpio = esFechaValida(fechaFin) ? fechaFin!.trim() : null;

    let filasBase: any[] = [];
    if (rolNormalizado === 'OPERADOR') {
      const params: any[] = [];
      let whereFecha = '';
      if (inicioLimpio) {
        params.push(inicioLimpio);
        whereFecha += ` AND COALESCE(a.fecha_fin, a.fecha_asignacion, s.created_at)::date >= $${params.length}::date`;
      }
      if (finLimpio) {
        params.push(finLimpio);
        whereFecha += ` AND COALESCE(a.fecha_fin, a.fecha_asignacion, s.created_at)::date <= $${params.length}::date`;
      }

      const query = `
        SELECT
          s.id as id_solicitud,
          s.codigo_ticket,
          s.created_at as fecha_solicitud,
          s.piso,
          s.oficina,
          s.consultorio,
          s.estado,
          BOOL_OR(s.es_manual) as es_manual,
          MAX(s.solicitante_manual) as solicitante_manual,
          MAX(COALESCE(NULLIF(TRIM(CONCAT_WS(' ', ps.nombres, ps.p_apellido, ps.s_apellido)), ''), us.name)) as nombre_usuario,
          MAX(us.name) as username,
          MAX(a.prioridad) as prioridad,
          MAX(a.observacion) as diagnostico,
          MAX(a.fecha_asignacion) as fecha_asignacion,
          MAX(a.fecha_inicio) as fecha_inicio,
          MAX(a.fecha_fin) as fecha_finalizacion,
          MAX(GREATEST(0, FLOOR(EXTRACT(EPOCH FROM (COALESCE(a.fecha_inicio, a.fecha_fin, CURRENT_TIMESTAMP) - a.fecha_asignacion))))::int) as segundos_desde_asignacion,
          (SELECT GREATEST(0, FLOOR(EXTRACT(EPOCH FROM (MIN(ax.fecha_asignacion) - s.created_at))))::int
           FROM administracion.soporte_asignaciones ax
           INNER JOIN administracion.soporte_solicitud_items six ON six.id = ax.id_solicitud_item
           WHERE six.id_solicitud = s.id
           HAVING COUNT(*) > 0) as segundos_hasta_asignacion,
          MAX(COALESCE(NULLIF(TRIM(CONCAT_WS(' ', p.nombres, p.p_apellido, p.s_apellido)), ''), u.name)) as nombre_tecnico,
          STRING_AGG(DISTINCT NULLIF(TRIM(si.descripcion), ''), ' | ') as descripcion_problema
        FROM administracion.soporte_solicitudes s
        LEFT JOIN administracion.soporte_solicitud_items si ON si.id_solicitud = s.id
        LEFT JOIN LATERAL (
          SELECT ax.* FROM administracion.soporte_asignaciones ax
          WHERE ax.id_solicitud_item = si.id
          ORDER BY ax.id DESC LIMIT 1
        ) a ON TRUE
        LEFT JOIN administracion.users u ON u.id = a.id_soporte
        LEFT JOIN administracion.persona p ON p.id = u.id_persona
        LEFT JOIN administracion.users us ON us.id = s.id_usuario
        LEFT JOIN administracion.persona ps ON ps.id = us.id_persona
        WHERE s.estado = 'FINALIZADA'
          ${whereFecha}
        GROUP BY s.id, s.codigo_ticket, s.created_at, s.piso, s.oficina, s.consultorio, s.estado
        ORDER BY COALESCE(MAX(a.fecha_fin), MAX(a.fecha_asignacion), s.created_at) DESC
      `;
      filasBase = await this.dataSource.query(query, params);
    } else {
      const params: any[] = [idUsuario];
      let whereFecha = '';
      if (inicioLimpio) {
        params.push(inicioLimpio);
        whereFecha += ` AND COALESCE(a.fecha_fin, a.fecha_asignacion, s.created_at)::date >= $${params.length}::date`;
      }
      if (finLimpio) {
        params.push(finLimpio);
        whereFecha += ` AND COALESCE(a.fecha_fin, a.fecha_asignacion, s.created_at)::date <= $${params.length}::date`;
      }

      const query = `
        SELECT
          a.id as id_asignacion,
          s.id as id_solicitud,
          s.codigo_ticket,
          s.created_at as fecha_solicitud,
          s.piso,
          s.oficina,
          s.consultorio,
          a.estado,
          s.es_manual,
          s.solicitante_manual,
          COALESCE(NULLIF(TRIM(CONCAT_WS(' ', ps.nombres, ps.p_apellido, ps.s_apellido)), ''), us.name) as nombre_usuario,
          us.name as username,
          a.prioridad,
          a.observacion as diagnostico,
          a.fecha_asignacion,
          a.fecha_inicio,
          a.fecha_fin as fecha_finalizacion,
          GREATEST(0, FLOOR(EXTRACT(EPOCH FROM (COALESCE(a.fecha_inicio, a.fecha_fin, CURRENT_TIMESTAMP) - a.fecha_asignacion))))::int as segundos_desde_asignacion,
          (SELECT GREATEST(0, FLOOR(EXTRACT(EPOCH FROM (MIN(ax.fecha_asignacion) - s.created_at))))::int
           FROM administracion.soporte_asignaciones ax
           INNER JOIN administracion.soporte_solicitud_items six ON six.id = ax.id_solicitud_item
           WHERE six.id_solicitud = s.id
           HAVING COUNT(*) > 0) as segundos_hasta_asignacion,
          COALESCE(NULLIF(TRIM(CONCAT_WS(' ', p.nombres, p.p_apellido, p.s_apellido)), ''), u.name) as nombre_tecnico,
          (SELECT STRING_AGG(DISTINCT NULLIF(TRIM(si2.descripcion), ''), ' | ')
           FROM administracion.soporte_solicitud_items si2
           WHERE si2.id_solicitud = s.id) as descripcion_problema
        FROM administracion.soporte_asignaciones a
        INNER JOIN administracion.soporte_solicitud_items si ON a.id_solicitud_item = si.id
        INNER JOIN administracion.soporte_solicitudes s ON si.id_solicitud = s.id
        LEFT JOIN administracion.users u ON u.id = a.id_soporte
        LEFT JOIN administracion.persona p ON p.id = u.id_persona
        LEFT JOIN administracion.users us ON us.id = s.id_usuario
        LEFT JOIN administracion.persona ps ON ps.id = us.id_persona
        WHERE a.id_soporte = $1
          AND a.estado = 'FINALIZADA'
          ${whereFecha}
        ORDER BY COALESCE(a.fecha_fin, a.fecha_asignacion, s.created_at) DESC
      `;
      filasBase = await this.dataSource.query(query, params);
    }

    return filasBase;
  }

}

