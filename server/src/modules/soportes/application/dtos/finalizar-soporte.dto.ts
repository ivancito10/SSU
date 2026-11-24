import { IsNotEmpty, IsOptional, IsString } from 'class-validator';

export class FinalizarSoporteDto {
  
  @IsNotEmpty({ message: 'El nombre del archivo es obligatorio' })
  @IsString()
  nombre_archivo: string;

  @IsNotEmpty({ message: 'La URL o ruta del archivo es obligatoria' })
  @IsString()
  ruta_url: string;

  @IsOptional()
  @IsString()
  tipo_mime?: string;     

  @IsOptional()
  @IsString()
  observacion?: string | null;   
}