import {
  Controller, Post, Patch, Body, Req, Res, UseGuards, UseFilters,
  Param, ParseIntPipe, UseInterceptors, UploadedFile, BadRequestException, ForbiddenException, UnauthorizedException, Inject
} from '@nestjs/common';
import type { Response } from 'express';
import { JwtService } from '@nestjs/jwt';
import { FileInterceptor } from '@nestjs/platform-express';
import { multerSoporteConfig } from '../config/multer-soporte.config';
import { multerAdjuntoSolicitudConfig } from '../config/multer-adjunto-solicitud.config';
import { JwtAuthGuard } from '../../../auth/infrastructure/jwt/jwt-auth.guard';
import { Public } from '../../../auth/infrastructure/jwt/public.decorator';
import { ExcelReportService } from '../reports/excel-report.service';
import { CreateSolicitudUseCase } from '../../application/use-cases/create-solicitud.use-case';
import { CreateSolicitudDto } from '../../application/dtos/create-solicitud.dto';
import { CreateSoporteManualDto } from '../../application/dtos/create-soporte-manual.dto';
import { SoporteExceptionFilter } from '../filters/soporte-exception.filter';
import { AsignarSoporteUseCase } from '../../application/use-cases/asignar-soporte.use-case';
import { AsignarSoporteDto } from '../../application/dtos/asignar-soporte.dto';
import { ReasignarSoporteUseCase } from '../../application/use-cases/reasignar-soporte.use-case';
import { ReasignarSoporteDto } from '../../application/dtos/reasignar-soporte.dto';
import { FinalizarSoporteUseCase } from '../../application/use-cases/finalizar-soporte.use-case';
import { FinalizarSoporteDto } from '../../application/dtos/finalizar-soporte.dto';
import { Get, Query } from '@nestjs/common';
import { ListarBandejasUseCase } from '../../application/use-cases/listar-bandejas.use-case';
import { BandejaSoporteDto } from '../../application/dtos/bandeja-soporte.dto';


@Controller('soportes')
@UseGuards(JwtAuthGuard)
@UseFilters(SoporteExceptionFilter) // <-- Aplicado el filtro
export class SoportesController {
  constructor(
    private readonly createUseCase: CreateSolicitudUseCase,
    private readonly asignarUseCase: AsignarSoporteUseCase,
    private readonly reasignarUseCase: ReasignarSoporteUseCase,
    private readonly finalizarUseCase: FinalizarSoporteUseCase,
    private readonly listarBandejasUseCase: ListarBandejasUseCase,
    private readonly jwtService: JwtService,
    private readonly excelReportService: ExcelReportService,

    @Inject('SoporteRepositoryPort') private readonly soporteRepo: any,
    @Inject('NotificationServicePort') private readonly notificationService: any
  ) { }

  @Post('solicitud')
  async crear(@Req() req: any, @Body() dto: CreateSolicitudDto) {
    const idUsuario = req.user.id;

    // CORRECCIÓN 4: Primero va idUsuario, luego dto
    const response = await this.createUseCase.ejecutar(idUsuario, dto);

    return {
      status: 201,
      success: response.exito,
      message: response.mensaje,
      data: response.datos ?? response
    };
  }

  @Post('manual')
  async crearSoporteManual(@Req() req: any, @Body() dto: CreateSoporteManualDto) {
    const idTecnico = Number(req.user?.id);
    const rolesActivos: string[] = await this.soporteRepo.obtenerRolesDeUsuario(idTecnico);
    if (!rolesActivos.includes('TECNICO')) {
      throw new ForbiddenException('Solo un usuario con rol TÉCNICO activo puede registrar un soporte manual.');
    }

    const datos = await this.soporteRepo.crearSoporteManual(idTecnico, dto);
    return {
      status: 201,
      success: true,
      message: 'Soporte manual registrado y puesto EN PROCESO.',
      data: datos,
    };
  }

  @Post('asignar')
  async asignarSoporte(@Body() dto: AsignarSoporteDto, @Req() req: any) {
    const idOperador = req.user?.id;
    const response = await this.asignarUseCase.ejecutar(idOperador, dto);

    return {
      status: 201,
      success: response.exito,
      message: response.mensaje,
      data: response.datos
    };
  }

  @Post('asignacion/:id/reasignar')
  async reasignarSoporte(
    @Param('id', ParseIntPipe) idAsignacion: number,
    @Body() dto: ReasignarSoporteDto,
    @Req() req: any
  ) {
    const idDeSoporte = req.user?.id; // Técnico actual logueado
    const response = await this.reasignarUseCase.ejecutar(idAsignacion, idDeSoporte, dto);

    return {
      status: 200,
      success: response.exito,
      message: response.mensaje,
      data: response.datos
    };
  }

  @Post('asignacion/:id/finalizar')
  @UseInterceptors(FileInterceptor('archivo', multerSoporteConfig)) // 'archivo' es el key que enviará React Native
  async finalizarSoporte(
    @Param('id', ParseIntPipe) idAsignacion: number,
    @UploadedFile() file: Express.Multer.File, // <-- Captura el archivo físico
    @Body('observacion') observacion: string,
    @Req() req: any
  ) {
    if (!file) {
      throw new BadRequestException('Debes adjuntar el acta (Imagen o PDF) para finalizar el soporte.');
    }
    if (!observacion || !observacion.trim()) {
      throw new BadRequestException('El diagnóstico u observación del trabajo realizado es obligatorio para finalizar el soporte.');
    }

    const idSoporte = req.user?.id;

    // Mapeamos los datos del archivo físico guardado a nuestro DTO
    const dto: FinalizarSoporteDto = {
      nombre_archivo: file.originalname,
      ruta_url: `/uploads/soportes/actas/${file.filename}`, // Ruta local que se guardará en BD
      tipo_mime: file.mimetype,
      observacion: observacion || null,
    };

    const response = await this.finalizarUseCase.ejecutar(idAsignacion, idSoporte, dto);

    return {
      status: 200,
      success: response.exito,
      message: response.mensaje,
      data: response.datos
    };
  }

  @Get('bandeja')
  async listarBandeja(
    @Query() dto: BandejaSoporteDto,
    @Req() req: any
  ) {
    const idUsuario = req.user?.id;
    const response = await this.listarBandejasUseCase.ejecutar(idUsuario, dto);

    return {
      status: 200,
      success: response.exito,
      message: response.mensaje,
      data: response.datos
    };
  }

  @Get('solicitud/:id/calificacion')
  async obtenerCalificacion(
    @Param('id', ParseIntPipe) idSolicitud: number,
    @Req() req: any,
  ) {
    const datos = await this.soporteRepo.obtenerCalificacionSolicitud(idSolicitud, Number(req.user?.id));
    if (!datos) throw new BadRequestException('No se encontró una solicitud finalizada que te pertenezca.');
    return { status: 200, success: true, data: datos };
  }

  @Post('solicitud/:id/calificacion')
  async calificarTecnico(
    @Param('id', ParseIntPipe) idSolicitud: number,
    @Body() body: { calificacion?: string },
    @Req() req: any,
  ) {
    const calificacion = String(body?.calificacion || '').toUpperCase();
    if (!['MUY_BIEN', 'BIEN', 'MAL'].includes(calificacion)) {
      throw new BadRequestException('Selecciona Muy bien, Bien o Mal.');
    }
    const datos = await this.soporteRepo.calificarTecnico(
      idSolicitud,
      Number(req.user?.id),
      calificacion as 'MUY_BIEN' | 'BIEN' | 'MAL',
    );
    return { status: 201, success: true, message: 'Gracias por calificar la atención.', data: datos };
  }

  @Get('notificaciones')
  async listarNotificaciones(@Req() req: any) {
    const idUsuario = Number(req.user?.id);
    const datos = await this.notificationService.listarNotificaciones(idUsuario);
    return { status: 200, success: true, data: datos };
  }

  @Patch('notificaciones/:id/leida')
  async marcarNotificacionLeida(
    @Param('id', ParseIntPipe) idNotificacion: number,
    @Req() req: any,
  ) {
    const idUsuario = Number(req.user?.id);
    const notificacion = await this.notificationService.marcarNotificacionLeida(idUsuario, idNotificacion);
    if (!notificacion) throw new BadRequestException('La notificación no existe o no te pertenece.');
    return { status: 200, success: true, data: notificacion };
  }

  @Patch('notificaciones/leidas')
  async marcarTodasNotificacionesLeidas(@Req() req: any) {
    const idUsuario = Number(req.user?.id);
    const cantidad = await this.notificationService.marcarTodasLeidas(idUsuario);
    return { status: 200, success: true, data: { cantidad } };
  }

  @Post('solicitud/:id/resolicitar')
  async resolicitar(
    @Param('id', ParseIntPipe) idSolicitud: number,
    @Body() body: { observacion?: string; motivo?: string },
    @Req() req: any
  ) {
    const idUsuario = req.user.id;
    const motivo = body?.observacion || body?.motivo;

    // 1. Registra la insistencia en soporte_re_solicitudes y obtiene los tokens FCM del Técnico asignado y de los Operadores
    const response = await this.soporteRepo.resolicitarSoporte(idSolicitud, idUsuario, motivo);

    // 2. Formateamos la ubicación tal como pide la Fase 4 (ej. "Odontología Piso 1")
    const pisoFormateado = response.piso
      ? /^(piso|planta)/i.test(String(response.piso).trim())
        ? String(response.piso).trim()
        : `Piso ${String(response.piso).trim()}`
      : '';
    const ubicacion = [response.oficina, pisoFormateado].filter(Boolean).join(' ') || `Ticket ${response.codigoTicket || `#${idSolicitud}`}`;

    const mensajeAlerta = `⚠️ Insistencia: El usuario de ${ubicacion} requiere atención demorada`;

    // 3. Disparamos la notificación Push + Campanita hacia los Operadores y el Técnico asignado
    const destino = response.idsDestino?.length ? response.idsDestino : response.tokensDestino;
    if (destino && destino.length > 0) {
      this.notificationService
        .enviarNotificacion(
          destino,
          `⚠️ Insistencia - Ticket ${response.codigoTicket || `#${idSolicitud}`}`,
          mensajeAlerta,
          {
            idSolicitud: idSolicitud.toString(),
            codigoTicket: String(response.codigoTicket || ''),
            oficina: String(response.oficina || ''),
            piso: String(response.piso || ''),
            motivo: String(response.motivo || ''),
            tipoNotificacion: 'INSISTENCIA_SOPORTE',
          },
        )
        .catch((e: any) => console.log('Fallo notificación push en insistencia:', e));
    }

    return {
      status: 201,
      success: true,
      message: response.mensaje,
      alerta: mensajeAlerta,
    };
  }

  @Post('solicitud/:id/adjunto')
  @UseInterceptors(FileInterceptor('archivo', multerAdjuntoSolicitudConfig))
  async subirAdjuntoSolicitud(
    @Param('id', ParseIntPipe) idSolicitud: number,
    @UploadedFile() file: Express.Multer.File,
    @Req() req: any
  ) {
    if (!file) throw new BadRequestException('Selecciona una imagen para adjuntar.');
    if (!String(file.mimetype).startsWith('image/')) throw new BadRequestException('El adjunto debe ser una imagen.');
    const guardado = await this.soporteRepo.guardarAdjuntoSolicitud(idSolicitud, Number(req.user?.id), {
      nombre_archivo: file.originalname,
      ruta_url: `/uploads/soportes/solicitudes/${file.filename}`,
      tipo_mime: file.mimetype,
    });
    if (!guardado) throw new ForbiddenException('La solicitud no existe o no te pertenece.');
    return { exito: true, datos: guardado };
  }

  @Get('solicitud/:id/items')
  async obtenerItemsSolicitud(@Param('id') id: string) {
    const items = await this.soporteRepo.obtenerItemsDeSolicitud(parseInt(id, 10));
    return {
      exito: true,
      datos: items
    };
  }

  @Get('solicitud/:id/historial-tecnicos')
  async obtenerHistorialTecnicos(@Param('id', ParseIntPipe) idSolicitud: number) {
    const historial = await this.soporteRepo.obtenerHistorialTecnicosSolicitud(idSolicitud);
    return { exito: true, datos: historial };
  }

  @Get('solicitud/:id/acta')
  async obtenerActaSolicitud(@Param('id', ParseIntPipe) idSolicitud: number) {
    const acta = await this.soporteRepo.obtenerActaDeSolicitud(idSolicitud);
    return {
      exito: true,
      datos: acta
    };
  }
  @Post('solicitud/:id/items')
  async agregarItem(
    @Param('id') id: string,
    @Body() body: { id_tipo_soporte: number, descripcion: string }
  ) {
    await this.soporteRepo.agregarItemASolicitud(parseInt(id, 10), body.id_tipo_soporte, body.descripcion);
    return { exito: true, mensaje: 'Problema añadido correctamente' };
  }

  @Get('tecnicos/disponibles')
  async obtenerTecnicos() {
    const tecnicos = await this.soporteRepo.obtenerTecnicosDisponibles();
    return { exito: true, datos: tecnicos };
  }

  @Get('mis-roles')
  async obtenerMisRoles(@Req() req: any) {
    const idUsuario = Number(req.user?.id);
    const roles: string[] = await this.soporteRepo.obtenerRolesDeUsuario(idUsuario);
    let rolPrincipal = 'SIN_ROL';
    if (roles.includes('OPERADOR')) {
      rolPrincipal = 'OPERADOR';
    } else if (roles.includes('ADMINISTRADOR')) {
      rolPrincipal = 'ADMINISTRADOR';
    } else if (roles.includes('TECNICO')) {
      rolPrincipal = 'TECNICO';
    } else if (roles.includes('USUARIO')) {
      rolPrincipal = 'USUARIO';
    } else if (roles.length > 0) {
      rolPrincipal = roles[0];
    }
    return {
      status: 200,
      success: true,
      rol: rolPrincipal,
      roles: roles,
    };
  }

  @Post('solicitud/:id/en-proceso')
  async ponerEnProceso(
    @Param('id', ParseIntPipe) idSolicitud: number,
    @Req() req: any
  ) {
    const idSoporte = req.user?.id;
    const resultado = await this.soporteRepo.ponerEnProcesoSoporte(idSolicitud, idSoporte);
    return {
      status: 200,
      success: true,
      message: 'El soporte ha sido puesto EN PROCESO.',
      data: resultado
    };
  }

  @Post('asignacion/:id/en-camino')
  async registrarEnCamino(
    @Param('id', ParseIntPipe) idAsignacion: number,
    @Body() body: { latitud: number; longitud: number },
    @Req() req: any,
  ) {
    const { latitud, longitud } = this.validarCoordenadas(body);
    const datos = await this.soporteRepo.registrarTecnicoEnCamino(
      idAsignacion, Number(req.user?.id), latitud, longitud,
    );
    if (!datos) throw new BadRequestException('No se encontró una asignación activa tuya para registrar el inicio del viaje.');
    return { status: 200, success: true, message: 'Inicio del trayecto registrado.', data: datos };
  }

  @Post('asignacion/:id/llegada')
  async registrarLlegada(
    @Param('id', ParseIntPipe) idAsignacion: number,
    @Body() body: { latitud: number; longitud: number },
    @Req() req: any,
  ) {
    const { latitud, longitud } = this.validarCoordenadas(body);
    const datos = await this.soporteRepo.registrarLlegadaTecnico(
      idAsignacion, Number(req.user?.id), latitud, longitud,
    );
    if (!datos) throw new BadRequestException('Primero registra «En camino» en una asignación activa para registrar la llegada.');
    return { status: 200, success: true, message: 'Hora y ubicación de llegada registradas.', data: datos };
  }

  private validarCoordenadas(body: { latitud: number; longitud: number }) {
    const latitud = Number(body?.latitud);
    const longitud = Number(body?.longitud);
    if (!Number.isFinite(latitud) || latitud < -90 || latitud > 90 || !Number.isFinite(longitud) || longitud < -180 || longitud > 180) {
      throw new BadRequestException('La ubicación GPS no contiene coordenadas válidas.');
    }
    return { latitud, longitud };
  }

  @Post('solicitud/:id/reasignar-operador')
  async reasignarPorOperador(
    @Param('id', ParseIntPipe) idSolicitud: number,
    @Body() body: { idTecnico: number; justificacion: string; prioridad?: string; instruccionesTecnico?: string },
    @Req() req: any
  ) {
    if (!body.idTecnico || !body.justificacion?.trim()) {
      throw new BadRequestException('Debes seleccionar un nuevo técnico e ingresar el motivo de la reasignación.');
    }

    const resultado = await this.soporteRepo.reasignarPorOperador(
      idSolicitud,
      Number(body.idTecnico),
      body.justificacion.trim(),
      body.prioridad,
      Number(req.user?.id),
      body.instruccionesTecnico
    );

    try {
      await this.notificationService.enviarNotificacion(
        Number(body.idTecnico),
        'Soporte reasignado',
        `Te asignaron el ticket ${resultado?.codigoTicket || `#${idSolicitud}`}. Cambia al modo TÉCNICO para atenderlo.`,
        {
          idAsignacion: String(resultado?.idAsignacion ?? ''),
          idSolicitud: String(idSolicitud),
          codigoTicket: String(resultado?.codigoTicket ?? ''),
          rolDestino: 'TECNICO',
          tipoNotificacion: 'ASIGNACION_TECNICO',
        },
      );
    } catch (error) {
      console.error('Reasignación completada, pero no se pudo enviar el aviso al técnico:', error);
    }

    return {
      status: 200,
      success: true,
      message: 'El soporte fue reasignado correctamente.',
      data: resultado
    };
  }

  // =========================================================
  // ENDPOINTS DE GESTIÓN DE ROLES (EXCLUSIVO ADMINISTRADOR)
  // =========================================================

  private async verificarRolAdministrador(req: any) {
    const rolToken = String(req.user?.rolSoporte || '').toUpperCase();
    const rolesToken: string[] = Array.isArray(req.user?.rolesSoporte)
      ? req.user.rolesSoporte.map((r: any) => String(r).toUpperCase())
      : [];

    if (rolToken === 'ADMINISTRADOR' || rolesToken.includes('ADMINISTRADOR')) return;

    const esAdminEnBd = await this.soporteRepo.esUsuarioAdministrador(Number(req.user?.id));
    if (!esAdminEnBd) {
      throw new ForbiddenException('Acceso denegado: Solo un usuario con rol ADMINISTRADOR puede gestionar roles.');
    }
  }

  @Get('roles/usuarios')
  async listarUsuariosConRoles(@Req() req: any) {
    await this.verificarRolAdministrador(req);
    const usuarios = await this.soporteRepo.obtenerUsuariosConRoles();
    return {
      status: 200,
      exito: true,
      success: true,
      datos: usuarios,
      data: usuarios,
    };
  }

  @Post('roles/asignar')
  async asignarRol(
    @Body() body: { id_usuario?: number; idUsuario?: number; rol_soporte?: string; rolSoporte?: string },
    @Req() req: any
  ) {
    await this.verificarRolAdministrador(req);

    const idUsuarioDestino = Number(body.id_usuario ?? body.idUsuario);
    const rolSoporte = String(body.rol_soporte ?? body.rolSoporte ?? '').trim().toUpperCase();

    if (!idUsuarioDestino || !rolSoporte) {
      throw new BadRequestException('Debes proporcionar id_usuario y rol_soporte (USUARIO, OPERADOR, TECNICO o ADMINISTRADOR).');
    }

    const idAdminActual = Number(req.user?.id);
    // El Administrador sí puede asignarse un segundo rol adicional (ej. OPERADOR) sin perder su rol ADMINISTRADOR

    const resultado = await this.soporteRepo.asignarRolUsuario(idUsuarioDestino, rolSoporte);
    return {
      status: 200,
      exito: true,
      success: true,
      mensaje: `Rol ${rolSoporte} asignado correctamente.`,
      message: `Rol ${rolSoporte} asignado correctamente.`,
      datos: resultado,
      data: resultado,
    };
  }

  @Get('sedes/:idSede/consultorios')
  async obtenerConsultoriosPorSede(@Param('idSede') idSede: string) {
    const datos = await this.soporteRepo.obtenerConsultoriosPorSede(parseInt(idSede, 10));
    return {
      exito: true,
      tiene_consultorios: datos.length > 0,
      datos,
    };
  }

  @Get('tipos-opciones')
  async obtenerTiposSoporteConOpciones() {
    const filas = await this.soporteRepo.obtenerTiposSoporteConOpciones();
    const tipos = new Map<number, any>();
    for (const fila of filas) {
      if (!tipos.has(Number(fila.id_tipo_soporte))) {
        tipos.set(Number(fila.id_tipo_soporte), {
          id: Number(fila.id_tipo_soporte), nombre: fila.tipo_nombre, opciones: [],
        });
      }
      if (fila.id_opcion !== null) {
        tipos.get(Number(fila.id_tipo_soporte)).opciones.push({ id: Number(fila.id_opcion), nombre: fila.opcion_nombre, estado: fila.opcion_estado });
      }
    }
    return { exito: true, datos: Array.from(tipos.values()) };
  }

  @Post('tipos/:idTipo/opciones')
  async crearOpcionTipoSoporte(
    @Param('idTipo', ParseIntPipe) idTipo: number,
    @Body() body: { nombre?: string },
    @Req() req: any
  ) {
    await this.verificarRolAdministrador(req);
    const nombre = String(body?.nombre || '').trim();
    if (!nombre || nombre.length > 120) throw new BadRequestException('El nombre es obligatorio y debe tener hasta 120 caracteres.');
    const opcion = await this.soporteRepo.crearOpcionTipoSoporte(idTipo, nombre);
    if (!opcion) throw new BadRequestException('La categoría no existe o está inactiva.');
    return { exito: true, datos: opcion };
  }

  @Patch('tipos/opciones/:idOpcion/estado')
  async alternarEstadoOpcionTipoSoporte(
    @Param('idOpcion', ParseIntPipe) idOpcion: number,
    @Body() body: { estado?: boolean },
    @Req() req: any
  ) {
    await this.verificarRolAdministrador(req);
    if (typeof body?.estado !== 'boolean') throw new BadRequestException('El estado debe ser verdadero o falso.');
    const opcion = await this.soporteRepo.alternarEstadoOpcionTipoSoporte(idOpcion, body.estado);
    if (!opcion) throw new BadRequestException('La opción no existe.');
    return { exito: true, datos: opcion };
  }

  @Patch('roles/:idUsuario/quitar')
  async quitarRol(
    @Param('idUsuario', ParseIntPipe) idUsuario: number,
    @Body() body: { rol_soporte?: string; rolSoporte?: string },
    @Req() req: any
  ) {
    await this.verificarRolAdministrador(req);

    const rolAQuitar = (body?.rol_soporte ?? body?.rolSoporte)?.trim().toUpperCase();
    const idAdminActual = Number(req.user?.id);

    if (idAdminActual === Number(idUsuario) && (!rolAQuitar || rolAQuitar === 'ADMINISTRADOR')) {
      throw new BadRequestException('Por seguridad, no puedes quitarte el rol de ADMINISTRADOR a ti mismo.');
    }

    const resultado = await this.soporteRepo.quitarRolUsuario(idUsuario, rolAQuitar);
    return {
      status: 200,
      exito: true,
      success: true,
      mensaje: rolAQuitar
        ? `Rol ${rolAQuitar} quitado correctamente.`
        : 'Roles revocados correctamente. El funcionario queda sin rol asignado.',
      message: rolAQuitar
        ? `Rol ${rolAQuitar} quitado correctamente.`
        : 'Roles revocados correctamente. El funcionario queda sin rol asignado.',
      datos: resultado,
      data: resultado,
    };
  }

  @Post('reporte-excel/enlace')
  async generarEnlaceReporteExcel(
    @Body()
    body: {
      rol?: string;
      fechaInicio?: string;
      fechaFin?: string;
      etiquetaFiltro?: string;
    },
    @Req() req: any,
  ) {
    const idUsuario = Number(req.user?.id);
    const rolSolicitado = String(body?.rol || '').trim().toUpperCase();

    if (rolSolicitado !== 'OPERADOR' && rolSolicitado !== 'TECNICO') {
      throw new ForbiddenException('Solo los roles OPERADOR o TECNICO pueden exportar reportes en Excel.');
    }

    const rolesActivos: string[] = await this.soporteRepo.obtenerRolesDeUsuario(idUsuario);
    if (!rolesActivos.includes(rolSolicitado)) {
      throw new ForbiddenException(`No cuentas con el rol activo ${rolSolicitado} para generar este reporte.`);
    }

    const fechaInicio = body?.fechaInicio?.trim() || undefined;
    const fechaFin = body?.fechaFin?.trim() || undefined;
    const etiquetaFiltro = body?.etiquetaFiltro?.trim() || undefined;

    const filas = await this.soporteRepo.obtenerReporteSoportesConcluidos(
      rolSolicitado,
      idUsuario,
      fechaInicio,
      fechaFin,
    );

    if (!filas || filas.length === 0) {
      throw new BadRequestException('No existen soportes concluidos para el filtro de fechas seleccionado.');
    }

    const hoy = new Date();
    const yyyymmdd = `${hoy.getFullYear()}${String(hoy.getMonth() + 1).padStart(2, '0')}${String(hoy.getDate()).padStart(2, '0')}`;
    const filename = `reporte-soportes-${rolSolicitado.toLowerCase()}-${yyyymmdd}.xlsx`;

    const token = this.jwtService.sign(
      {
        sub: idUsuario,
        rol: rolSolicitado,
        fechaInicio,
        fechaFin,
        etiquetaFiltro,
        filename,
        tipo: 'excel_download',
      },
      { expiresIn: '2m' },
    );

    return {
      status: 201,
      success: true,
      token,
      filename,
      total: filas.length,
    };
  }

  @Public()
  @Get('reporte-excel/descargar')
  async descargarReporteExcel(@Query('token') token: string, @Res() res: Response) {
    if (!token) {
      throw new UnauthorizedException('Token de descarga requerido.');
    }

    let payload: any;
    try {
      payload = this.jwtService.verify(token);
    } catch {
      throw new UnauthorizedException('El enlace de descarga ha expirado o es inválido. Vuelve a generar el reporte.');
    }

    if (payload?.tipo !== 'excel_download' || !payload?.sub) {
      throw new UnauthorizedException('Token de descarga inválido.');
    }

    const rol = String(payload.rol || '').toUpperCase();
    if (rol !== 'OPERADOR' && rol !== 'TECNICO') {
      throw new ForbiddenException('Rol no autorizado para este reporte.');
    }

    const idUsuario = Number(payload.sub);
    const filas = await this.soporteRepo.obtenerReporteSoportesConcluidos(
      rol,
      idUsuario,
      payload.fechaInicio,
      payload.fechaFin,
    );
    const generadoPor = await this.soporteRepo.obtenerNombreUsuarioPorId(idUsuario);

    const buffer = await this.excelReportService.generarReporteSoportesConcluidos(filas, {
      rol: rol as 'OPERADOR' | 'TECNICO',
      generadoPor,
      etiquetaFiltro: payload.etiquetaFiltro,
      fechaInicio: payload.fechaInicio,
      fechaFin: payload.fechaFin,
    });

    const filename = String(payload.filename || `reporte-soportes-${rol.toLowerCase()}.xlsx`).replace(/[^a-zA-Z0-9._-]/g, '_');

    res.setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    res.setHeader('Content-Disposition', `attachment; filename="${filename}"`);
    res.setHeader('Cache-Control', 'no-store');
    res.send(buffer);
  }
}
