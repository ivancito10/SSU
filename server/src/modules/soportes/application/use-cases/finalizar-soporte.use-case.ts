import { Injectable, BadRequestException, Inject } from '@nestjs/common';
import { SoporteRepositoryPort } from '../../domain/ports/soporte.repository.port';
import { FinalizarSoporteDto } from '../dtos/finalizar-soporte.dto';

@Injectable()
export class FinalizarSoporteUseCase {
  constructor(
    @Inject('SoporteRepositoryPort')
    private readonly soporteRepo: SoporteRepositoryPort, // <-- Cambiado de soporteRepository a soporteRepo
  ) {}

  async ejecutar(idAsignacion: number, idSoporte: number, dto: FinalizarSoporteDto) {
    if (!idSoporte) {
      throw new BadRequestException('Técnico no autenticado.');
    }
    
    const resultado = await this.soporteRepo.finalizarSoporteTransaccional(idAsignacion, idSoporte, dto);

    return {
      exito: true,
      mensaje: 'El soporte ha sido finalizado y el acta fue guardada con éxito.',
      datos: resultado,
    };
  }
}