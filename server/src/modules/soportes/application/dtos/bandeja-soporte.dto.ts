import { IsEnum, IsOptional } from 'class-validator';

export enum RolBandeja {
  USUARIO = 'USUARIO',
  OPERADOR = 'OPERADOR',
  TECNICO = 'TECNICO',
  ADMINISTRADOR = 'ADMINISTRADOR',
}

export class BandejaSoporteDto {
  @IsEnum(RolBandeja, { message: 'El rol debe ser USUARIO, OPERADOR o TECNICO' })
  rol: RolBandeja;

  @IsOptional()
  estado?: string; // Por si queremos filtrar solo las FINALIZADAS o ASIGNADAS
}