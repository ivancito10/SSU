import { IsNotEmpty, IsOptional, IsString } from 'class-validator';

export class LoginDto {
  @IsNotEmpty({ message: 'El usuario o correo es obligatorio' })
  @IsString()
  username: string;

  @IsNotEmpty({ message: 'La contraseña es obligatoria' })
  @IsString()
  password: string;

  @IsOptional()
  @IsString()
  plataforma?: string;
}