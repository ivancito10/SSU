import {
    Controller,
    Get,
    Param,
    Query,
    Catch,
    UseFilters,
} from '@nestjs/common';
import type { ArgumentsHost, ExceptionFilter } from '@nestjs/common';
import type { Response } from 'express';
import { UsuarioError } from '../domain/usuario';
import { ConsultarUsuariosUseCase } from '../application/consultar-usuarios.use-case';

@Catch(UsuarioError)
export class UsuarioFilter implements ExceptionFilter {
    catch(error: UsuarioError, host: ArgumentsHost) {
        host.switchToHttp()
            .getResponse<Response>()
            .status(error.status)
            .json({ statusCode: error.status, message: error.message });
    }
}

@Controller('usuarios')
@UseFilters(UsuarioFilter)
export class UsuariosController {
    constructor(private readonly service: ConsultarUsuariosUseCase) {}

    @Get()
    listar(@Query() query: Record<string, unknown>) {
        return this.service.listar(query);
    }

    @Get(':id')
    obtener(@Param('id') id: string) {
        return this.service.obtener(id);
    }
}
