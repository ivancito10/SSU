import { IsOptional, IsString } from 'class-validator';

export class RegistrarTokenDto {
  @IsOptional()
  @IsString()
  token?: string;

  @IsOptional()
  @IsString()
  token_dispositivo?: string;

  @IsOptional()
  @IsString()
  plataforma?: string;
}
