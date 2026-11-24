import { IsInt, IsOptional, IsString } from 'class-validator';
import { Type } from 'class-transformer';

export class AsignarSoporteDto {
  @IsOptional()
  @Type(() => Number)
  @IsInt()
  idSolicitud?: number;

  @IsOptional()
  @Type(() => Number)
  @IsInt()
  idTecnico?: number;

  @IsOptional()
  @Type(() => Number)
  @IsInt()
  id_solicitud_item?: number;

  @IsOptional()
  @Type(() => Number)
  @IsInt()
  id_soporte?: number;

  @IsOptional()
  @IsString()
  observacion?: string;

  @IsOptional()
  @IsString()
  instruccionesTecnico?: string;

  @IsOptional()
  @IsString()
  prioridad?: string;
}
