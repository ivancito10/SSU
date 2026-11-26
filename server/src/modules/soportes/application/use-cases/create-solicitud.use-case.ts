import { Injectable, Inject } from '@nestjs/common';
import { SoporteRepositoryPort } from '../../domain/ports/soporte.repository.port';
import { NotificationServicePort } from '../../domain/ports/notification.service.port'; // <-- 1. Importar el puerto
import { CreateSolicitudDto } from '../dtos/create-solicitud.dto';

@Injectable()
export class CreateSolicitudUseCase {
  constructor(
    @Inject('SoporteRepositoryPort')
    private readonly soporteRepository: SoporteRepositoryPort,

    @Inject('NotificationServicePort') // <-- 2. Inyectar el servicio de notificaciones
    private readonly notificationService: NotificationServicePort,
  ) { }

  async ejecutar(idUsuario: number, dto: CreateSolicitudDto) {
    // 3. Crear la solicitud en la base de datos (tu lógica original)
    // CORRECCIÓN 3: Renombrar a crearSolicitudTransaccional
    const nuevaSolicitud = await this.soporteRepository.crearSolicitudTransaccional(idUsuario, dto);

    // 4. Enviar Notificación Push + Campanita en segundo plano sin bloquear la respuesta HTTP
    const ubicacionTexto = [dto.oficina, dto.consultorio, dto.piso].filter(Boolean).join(' — ') || 'ubicación registrada';
    void (async () => {
      try {
        const idsOperadores = await this.soporteRepository.obtenerIdsOperadoresActivos();
        if (idsOperadores && idsOperadores.length > 0) {
          await this.notificationService.enviarNotificacion(
            idsOperadores,
            'Nueva Solicitud de Soporte',
            `Se registró el ticket ${nuevaSolicitud.codigoTicket || `#${nuevaSolicitud.id}`} en ${ubicacionTexto}.`,
            {
              idSolicitud: String(nuevaSolicitud.id ?? ''),
              codigoTicket: String(nuevaSolicitud.codigoTicket ?? ''),
              tipoNotificacion: 'NUEVA_SOLICITUD_OPERADOR',
            }
          );
        }
      } catch (error) {
        console.warn('La solicitud se guardó, pero falló la notificación en segundo plano:', error);
      }
    })();

    return nuevaSolicitud;
  }
}
