import { Injectable, Logger } from '@nestjs/common';
import { InjectDataSource, InjectRepository } from '@nestjs/typeorm';
import { DataSource, In, Repository } from 'typeorm';
// 1. IMPORTACIONES ACTUALIZADAS PARA FIREBASE ADMIN v12+
import { initializeApp, getApps, cert } from 'firebase-admin/app';
import { getMessaging, MulticastMessage } from 'firebase-admin/messaging';
import * as path from 'path';

import { NotificationServicePort } from '../../domain/ports/notification.service.port';
import { SoporteFcmTokenTypeOrmEntity } from '../../domain/entities/fcm-token.typeorm-entity';
import { DB_CONNECTIONS } from '../../../../config/database.constants';

@Injectable()
export class FcmNotificationService implements NotificationServicePort {
  private readonly logger = new Logger(FcmNotificationService.name);

  constructor(
    @InjectRepository(SoporteFcmTokenTypeOrmEntity, DB_CONNECTIONS.SIGHOV)
    private readonly tokenRepo: Repository<SoporteFcmTokenTypeOrmEntity>,
    @InjectDataSource(DB_CONNECTIONS.SIGHOV)
    private readonly dataSource: DataSource,
  ) {
    if (getApps().length === 0) {
      try {
        // 1. Buscamos el archivo JSON en la raíz del proyecto
        const serviceAccountPath = path.resolve(process.cwd(), 'firebase-adminsdk.json');
        
        // 2. Inicializamos con las credenciales físicas
        initializeApp({
          credential: cert(serviceAccountPath),
        });
        this.logger.log('Firebase Admin inicializado correctamente con credenciales físicas.');
      } catch (error) {
        this.logger.error('Error al inicializar Firebase Admin. Asegúrate de poner el firebase-adminsdk.json en la raíz del proyecto', error);
      }
    }
  }

  /**
   * Fase 3: Función genérica para enviar notificaciones Push con Firebase Admin.
   * Acepta un token FCM directo (string), un arreglo de tokens (string[]) o un id_usuario (number).
   */
  async enviarNotificacion(
    destino: number | number[] | string | string[],
    titulo: string,
    mensaje: string,
    dataPayload?: Record<string, string>,
  ): Promise<boolean> {
    try {
      let deviceTokens: string[] = [];

      if (typeof destino === 'string') {
        deviceTokens = [destino.trim()].filter(Boolean);
      } else if (Array.isArray(destino)) {
        const idsNumericos = Array.from(
          new Set(
            destino
              .filter((v): v is number => typeof v === 'number' && Number.isFinite(v) && v > 0)
          )
        );
        if (idsNumericos.length > 0) {
          await Promise.all(
            idsNumericos.map((idUsr) => this.registrarNotificacion(idUsr, titulo, mensaje, dataPayload)),
          );
          const tokens = await this.tokenRepo.find({
            where: { idUsuario: In(idsNumericos) },
          });
          deviceTokens = Array.from(new Set(tokens.map((t) => t.tokenDispositivo).filter(Boolean)));
        } else {
          deviceTokens = Array.from(
            new Set(destino.map((t) => String(t || '').trim()).filter(Boolean))
          );
        }
      } else if (typeof destino === 'number') {
        await this.registrarNotificacion(destino, titulo, mensaje, dataPayload);
        const tokens = await this.tokenRepo.find({
          where: { idUsuario: destino },
        });
        deviceTokens = Array.from(new Set(tokens.map((t) => t.tokenDispositivo).filter(Boolean)));
      }

      if (!deviceTokens || deviceTokens.length === 0) {
        this.logger.log(
          `No se encontraron tokens FCM registrados para el destino: ${JSON.stringify(destino)} (el usuario no tiene un dispositivo móvil vinculado aún).`,
        );
        return false;
      }

      // Configuración con prioridad máxima tipo WhatsApp para Android + Multicast
      const payload: MulticastMessage = {
        notification: {
          title: titulo,
          body: mensaje,
        },
        android: {
          priority: 'high',
          notification: {
            channelId: 'default',
            sound: 'default',
            priority: 'max',
            defaultVibrateTimings: true,
          },
        },
        data: dataPayload || {},
        tokens: deviceTokens,
      };

      const response = await getMessaging().sendEachForMulticast(payload);

      this.logger.log(
        `Notificación Push enviada (${response.successCount} exitosas, ${response.failureCount} fallidas): "${mensaje}"`,
      );

      if (response.failureCount > 0) {
        this.logger.warn(`Fallaron ${response.failureCount} notificaciones hacia ${JSON.stringify(destino)}.`);
      }

      return response.successCount > 0;
    } catch (error: any) {
      this.logger.error(`Error enviando notificación Push: ${error.message}`);
      return false;
    }
  }

  async listarNotificaciones(idUsuario: number) {
    const notificaciones = await this.dataSource.query(
      `SELECT id, titulo, mensaje, datos, leida, creado_en
       FROM administracion.soporte_notificaciones
       WHERE id_usuario = $1
         AND leida = false
       ORDER BY creado_en DESC
       LIMIT 50`,
      [idUsuario],
    );

    return notificaciones.map((notificacion: any) => ({
      ...notificacion,
      no_leida: !notificacion.leida,
    }));
  }

  async marcarNotificacionLeida(idUsuario: number, idNotificacion: number) {
    const [notificacion] = await this.dataSource.query(
      `UPDATE administracion.soporte_notificaciones
       SET leida = true, leida_en = COALESCE(leida_en, NOW())
       WHERE id = $1 AND id_usuario = $2
       RETURNING id, leida, leida_en`,
      [idNotificacion, idUsuario],
    );
    return notificacion ?? null;
  }

  async marcarTodasLeidas(idUsuario: number) {
    const resultado = await this.dataSource.query(
      `UPDATE administracion.soporte_notificaciones
       SET leida = true, leida_en = COALESCE(leida_en, NOW())
       WHERE id_usuario = $1 AND leida = false
       RETURNING id`,
      [idUsuario],
    );
    return resultado.length;
  }

  private async registrarNotificacion(
    idUsuario: number,
    titulo: string,
    mensaje: string,
    datos?: Record<string, string>,
  ) {
    try {
      await this.dataSource.query(
        `INSERT INTO administracion.soporte_notificaciones
          (id_usuario, titulo, mensaje, datos)
         VALUES ($1, $2, $3, $4::jsonb)`,
        [idUsuario, titulo, mensaje, JSON.stringify(datos ?? {})],
      );
    } catch (error: any) {
      this.logger.error(`No se pudo guardar la notificación interna para el usuario ${idUsuario}: ${error.message}`);
    }
  }
}
