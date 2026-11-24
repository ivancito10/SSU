import { IsString, IsOptional, IsArray, ValidateNested, IsInt, IsNotEmpty } from 'class-validator';
import { Type } from 'class-transformer';

export class SolicitudItemDto {
    @IsNotEmpty({ message: 'El tipo de soporte es obligatorio' })
    @IsInt()
    id_tipo_soporte: number;

    @IsString()
    @IsOptional()
    descripcion?: string | null;
}

export class CreateSolicitudDto {
    @IsString()
    @IsOptional()
    piso?: string | null;

    @IsString()
    @IsOptional()
    oficina?: string | null;

    @IsString()
    @IsOptional()
    consultorio?: string | null;

    @IsArray()
    @ValidateNested({ each: true })
    @Type(() => SolicitudItemDto)
    items: SolicitudItemDto[];
}