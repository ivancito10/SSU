import { Type } from 'class-transformer';
import { IsInt, IsOptional, Min, Max } from 'class-validator';
export class CatalogosFisioterapeutasDto {
    @IsOptional()
    @Type(() => Number)
    @IsInt()
    @Min(1)
    @Max(2147483647)
    idDepartamento?: number;
    @IsOptional()
    @Type(() => Number)
    @IsInt()
    @Min(1)
    @Max(2147483647)
    idProvincia?: number;
}
