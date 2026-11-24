import { Controller, Post, Body, HttpCode, HttpStatus, Req, BadRequestException } from '@nestjs/common';
import { LoginUseCase } from '../../application/use-cases/login.use-case';
import { LoginDto } from '../../application/dtos/login.dto';
import { RegistrarTokenDto } from '../../application/dtos/registrar-token.dto';
import { UserAuthRepositoryPort } from '../../domain/ports/user-auth.repository.port';
import { Public } from '../jwt/public.decorator';

@Controller('auth')
export class AuthController {
  constructor(
    private readonly loginUseCase: LoginUseCase,
    private readonly userAuthRepo: UserAuthRepositoryPort,
  ) {}

  @Post('login')
  @Public()
  @HttpCode(HttpStatus.OK)
  async login(@Body() dto: LoginDto) {
    return await this.loginUseCase.ejecutar(dto, dto.plataforma);
  }

  @Post('registrar-token')
  @HttpCode(HttpStatus.OK)
  async registrarToken(@Req() req: any, @Body() dto: RegistrarTokenDto) {
    const idUsuario = Number(req.user?.id);
    const tokenDispositivo = (dto.token || dto.token_dispositivo || '').trim();
    const plataforma = (dto.plataforma || 'android').trim();

    if (!tokenDispositivo) {
      throw new BadRequestException('El token del dispositivo es obligatorio.');
    }

    await this.userAuthRepo.guardarTokenFCM(idUsuario, tokenDispositivo, plataforma);

    return {
      status: 200,
      success: true,
      message: 'Token FCM vinculado correctamente al dispositivo.',
    };
  }
}
