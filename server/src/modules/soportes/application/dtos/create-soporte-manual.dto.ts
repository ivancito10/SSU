import { IsIn, IsNotEmpty, IsOptional, IsString, MaxLength } from 'class-validator';

export class CreateSoporteManualDto {
  @IsString()
  @IsNotEmpty()
  @MaxLength(180)
  solicitante_nombre: string;

  @IsString()
  @IsNotEmpty()
  @MaxLength(100)
  oficina: string;

  @IsOptional()
  @IsString()
  @MaxLength(100)
  piso?: string;

  @IsOptional()
  @IsString()
  @MaxLength(100)
  consultorio?: string;

  @IsString()
  @IsNotEmpty()
  @MaxLength(3000)
  descripcion: string;

  @IsOptional()
  @IsIn(['BAJA', 'MEDIA', 'ALTA'])
  prioridad?: string;
}
