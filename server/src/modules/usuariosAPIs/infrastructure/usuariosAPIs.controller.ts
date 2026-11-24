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
import { UsuarioAPIsError } from '../domain/usuarioAPIs';
import { ConsultarUsuariosAPIsUseCase } from '../application/consultar-usuariosAPIs.use-case';

@Catch(UsuarioAPIsError)
export class UsuarioAPIsFilter implements ExceptionFilter {
    catch(error: UsuarioAPIsError, host: ArgumentsHost) {
        host.switchToHttp()
            .getResponse<Response>()
            .status(error.status)
            .json({ statusCode: error.status, message: error.message });
    }
}

@Controller('usuariosAPIs')
@UseFilters(UsuarioAPIsFilter)
export class UsuariosAPIsController {
    constructor(private readonly service: ConsultarUsuariosAPIsUseCase) {}

    @Get()
    listar(@Query() query: Record<string, unknown>) {
        return this.service.listar(query);
    }

    @Get(':id')
    obtener(@Param('id') id: string) {
        return this.service.obtener(id);
    }
}
