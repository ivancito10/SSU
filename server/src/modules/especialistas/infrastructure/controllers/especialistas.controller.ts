import {
    Controller,
    Get,
    Param,
    ParseIntPipe,
    Query,
    UseGuards,
} from '@nestjs/common';
import { AuthGuard } from '@nestjs/passport';
import { GetEspecialistasActivosUseCase } from '../../application/use-cases/get-especialistas-activos.use-case';
import { GetEspecialistaByIdUseCase } from '../../application/use-cases/get-especialista-by-id.use-case';
import { SearchEspecialistaDto } from '../../application/dtos/search-especialista.dto';
import { GetEspecialistaByPersonaUseCase } from '../../application/use-cases/get-especialista-by-persona.use-case';

@Controller('especialistas')
@UseGuards(AuthGuard('jwt'))
export class EspecialistasController {
    constructor(
        private readonly getActivosUseCase: GetEspecialistasActivosUseCase,
        private readonly getByIdUseCase: GetEspecialistaByIdUseCase,
        private readonly getByPersonaUseCase: GetEspecialistaByPersonaUseCase,
    ) {}

    @Get()
    async listarActivos(@Query() dto: SearchEspecialistaDto) {
        return await this.getActivosUseCase.ejecutar({
            buscar: (dto.buscar ?? dto.q ?? '').trim(),
            especialidad: dto.especialidad?.trim(),
            idEspecialidad: dto.idEspecialidad,
            pagina: dto.pagina,
            limite: dto.limite,
        });
    }

    @Get('persona/:idPersona')
    async buscarPorPersona(@Param('idPersona', ParseIntPipe) idPersona: number) {
        return this.getByPersonaUseCase.ejecutar(idPersona);
    }

    @Get(':id')
    async buscarPorId(@Param('id', ParseIntPipe) id: number) {
        return await this.getByIdUseCase.ejecutar(id);
    }
}