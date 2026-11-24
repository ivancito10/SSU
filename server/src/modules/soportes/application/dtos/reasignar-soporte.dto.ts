import { IsInt, IsNotEmpty, IsString } from 'class-validator';
import { Type } from 'class-transformer';

export class ReasignarSoporteDto {
  @IsNotEmpty({ message: 'Debe seleccionar a qué técnico se reasigna la tarea' })
  @Type(() => Number)
  @IsInt()
  id_a_soporte: number;

  @IsNotEmpty({ message: 'La justificación es obligatoria' })
  @IsString()
  justificacion: string;
}