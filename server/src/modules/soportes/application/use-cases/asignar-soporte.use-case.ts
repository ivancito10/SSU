import { Injectable, Inject } from '@nestjs/common';
import { SoporteRepositoryPort } from '../../domain/ports/soporte.repository.port';
import { NotificationServicePort } from '../../domain/ports/notification.service.port';
import { AsignarSoporteDto } from '../dtos/asignar-soporte.dto';

@Injectable()
export class AsignarSoporteUseCase {
  constructor(
    @Inject('SoporteRepositoryPort')
    private readonly soporteRepository: SoporteRepositoryPort,
    
    @Inject('NotificationServicePort')
    private readonly notificationService: NotificationServicePort,
  ) {}

  async ejecutar(idOperador: number, dto: AsignarSoporteDto) {
    const asignacion = await this.soporteRepository.asignarSoporteTransaccional(idOperador, dto);
    const idSoporte = Number(dto.idTecnico ?? dto.id_soporte);

    if (idSoporte) {
      void this.notificationService
        .enviarNotificacion(
          idSoporte,
          'Nuevo Ticket Asignado',
          `Te asignaron el ticket ${asignacion.codigoTicket || `#${asignacion.idSolicitud ?? dto.idSolicitud}`}. Cambia al modo TÉCNICO para atenderlo.`,
          {
            idAsignacion: String(asignacion.idAsignacion ?? asignacion.id ?? ''),
            idSolicitud: String(asignacion.idSolicitud ?? dto.idSolicitud ?? ''),
            codigoTicket: String(asignacion.codigoTicket ?? ''),
            rolDestino: 'TECNICO',
            tipoNotificacion: 'ASIGNACION_TECNICO',
          }
        )
        .catch((error) => {
          console.warn('Asignación exitosa, pero falló la notificación en segundo plano:', error);
        });
    }

    return {
      exito: true,
      mensaje: 'Ticket asignado exitosamente al técnico.',
      datos: asignacion,
    };
  }
}
