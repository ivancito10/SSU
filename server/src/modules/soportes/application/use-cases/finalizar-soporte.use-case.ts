import { Injectable, BadRequestException, Inject } from '@nestjs/common';
import { SoporteRepositoryPort } from '../../domain/ports/soporte.repository.port';
import { NotificationServicePort } from '../../domain/ports/notification.service.port';
import { FinalizarSoporteDto } from '../dtos/finalizar-soporte.dto';

@Injectable()
export class FinalizarSoporteUseCase {
  constructor(
    @Inject('SoporteRepositoryPort')
    private readonly soporteRepo: SoporteRepositoryPort,

    @Inject('NotificationServicePort')
    private readonly notificationService: NotificationServicePort,
  ) {}

  async ejecutar(idAsignacion: number, idSoporte: number, dto: FinalizarSoporteDto) {
    if (!idSoporte) {
      throw new BadRequestException('Técnico no autenticado.');
    }

    const resultado = await this.soporteRepo.finalizarSoporteTransaccional(idAsignacion, idSoporte, dto);

    if (resultado?.idUsuario && !resultado?.esManual) {
      const codigo = resultado.codigoTicket || `#${resultado.idSolicitud ?? idAsignacion}`;
      void this.notificationService
        .enviarNotificacion(
          Number(resultado.idUsuario),
          'Soporte Concluido',
          `El soporte del ticket ${codigo} fue concluido. Ya puedes ingresar para calificar la atención recibida.`,
          {
            idSolicitud: String(resultado.idSolicitud ?? ''),
            idAsignacion: String(resultado.idAsignacion ?? idAsignacion),
            codigoTicket: String(resultado.codigoTicket ?? ''),
            rolDestino: 'USUARIO',
            tipoNotificacion: 'SOPORTE_CONCLUIDO',
          },
        )
        .catch((error) => {
          console.warn('Soporte finalizado, pero falló la notificación al usuario en segundo plano:', error);
        });
    }

    return {
      exito: true,
      mensaje: 'El soporte ha sido finalizado y el acta fue guardada con éxito.',
      datos: resultado,
    };
  }
}