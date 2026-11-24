import { Injectable, BadRequestException, Inject } from '@nestjs/common';
import { SoporteRepositoryPort } from '../../domain/ports/soporte.repository.port';
import { BandejaSoporteDto, RolBandeja } from '../dtos/bandeja-soporte.dto';

@Injectable()
export class ListarBandejasUseCase {
  constructor(
    @Inject('SoporteRepositoryPort')
    private readonly soporteRepo: SoporteRepositoryPort,
  ) { }

  async ejecutar(idUsuario: number, dto: BandejaSoporteDto) {
    if (!idUsuario) {
      throw new BadRequestException('Usuario no autenticado.');
    }

    let datos = [];

    switch (dto.rol) {
      case RolBandeja.USUARIO:
        datos = await this.soporteRepo.obtenerMisSolicitudesUsuario(idUsuario, dto.estado);
        break;
      case RolBandeja.OPERADOR:
        datos = await this.soporteRepo.obtenerBandejaOperador(dto.estado);
        break;
      case RolBandeja.TECNICO:
        datos = await this.soporteRepo.obtenerMisTareasTecnico(idUsuario, dto.estado);
        break;
      case RolBandeja.ADMINISTRADOR:
        // ELIMINAMOS LA LÓGICA ANTERIOR Y PROTEGEMOS LA RUTA
        throw new BadRequestException('El Administrador no utiliza la bandeja de tickets, utiliza la Gestión de Roles.');
      default:
        throw new BadRequestException('Rol no válido.');
    }

    return {
      exito: true,
      mensaje: `Bandeja de ${dto.rol} cargada exitosamente.`,
      datos,
    };
  }
}