import { Injectable, BadRequestException, Inject } from '@nestjs/common';
import { SoporteRepositoryPort } from '../../domain/ports/soporte.repository.port';
import { ReasignarSoporteDto } from '../dtos/reasignar-soporte.dto';
import { NotificationServicePort } from '../../domain/ports/notification.service.port';


@Injectable()
export class ReasignarSoporteUseCase {
  constructor(
    @Inject('SoporteRepositoryPort')
    private readonly soporteRepo: SoporteRepositoryPort, // <-- Cambiado aquí también
    @Inject('NotificationServicePort')
    private readonly notificationService: NotificationServicePort,
  ) {}

  async ejecutar(idAsignacion: number, idDeSoporte: number, dto: ReasignarSoporteDto) {
    if (!idDeSoporte) {
      throw new BadRequestException('Técnico actual no identificado.');
    }
    
    if (Number(idDeSoporte) === Number(dto.id_a_soporte)) {
      throw new BadRequestException('No puedes reasignarte la tarea a ti mismo.');
    }
    
    const resultado = await this.soporteRepo.reasignarSoporteTransaccional(idAsignacion, idDeSoporte, dto, 'TECNICO');

    void this.notificationService
      .enviarNotificacion(
        Number(dto.id_a_soporte),
        'Soporte derivado',
        `Te derivaron el ticket ${resultado.codigoTicket || `#${resultado.idSolicitud}`}. Cambia al modo TÉCNICO para atenderlo.`,
        {
          idAsignacion: String(resultado.idAsignacion ?? ''),
          idSolicitud: String(resultado.idSolicitud ?? ''),
          codigoTicket: String(resultado.codigoTicket ?? ''),
          rolDestino: 'TECNICO',
          tipoNotificacion: 'ASIGNACION_TECNICO',
        },
      )
      .catch((error) => {
        console.warn('Derivación completada, pero falló la notificación en segundo plano:', error);
      });

    return {
      exito: true,
      mensaje: 'El soporte ha sido reasignado correctamente.',
      datos: resultado,
    };
  }
}
