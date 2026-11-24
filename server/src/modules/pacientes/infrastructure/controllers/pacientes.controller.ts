import {
  Controller,
  Get,
  Param,
  ParseIntPipe,
  Query,
  UseFilters,
  UseGuards,
  UnauthorizedException,
} from '@nestjs/common';
import { AuthGuard } from '@nestjs/passport'; // O tu JwtAuthGuard institucional
import { SearchPacientesUseCase } from '../../application/use-cases/search-pacientes.use-case';
import { GetPacienteByIdUseCase } from '../../application/use-cases/get-paciente-by-id.use-case';
import { PacienteRepositoryPort } from '../../domain/ports/paciente.repository.port';
import { PaginationPacienteDto } from '../../application/dtos/search-paciente.dto';
import { PacienteExceptionFilter } from '../filters/paciente-exception.filter';

@Controller('pacientes')
@UseFilters(PacienteExceptionFilter) // Aplica el manejo unificado de errores
export class PacientesController {
  constructor(
    private readonly searchPacientesUseCase: SearchPacientesUseCase,
    private readonly getPacienteByIdUseCase: GetPacienteByIdUseCase,
    private readonly pacienteRepo: PacienteRepositoryPort,
  ) {}

  // Listado y búsqueda paginada
  @Get()
  // @UseGuards(AuthGuard('jwt')) // Descomentar si el endpoint requiere token JWT (genera 401 si falta)
  async listarPacientesInterno(@Query() queryDto: PaginationPacienteDto) {
    return await this.searchPacientesUseCase.ejecutar(queryDto);
  }

  // Detalle por ID (genera 404 si no existe)
  @Get(':id')
  // @UseGuards(AuthGuard('jwt')) // Descomentar para exigir autenticación
  async obtenerPorId(@Param('id', ParseIntPipe) id: number) {
    return await this.getPacienteByIdUseCase.ejecutar(id);
  }

  // Compatibilidad Laravel
  @Get('s1/administracion/pacientes')
  async listarPacientesLaravel(@Query('limite') limite?: number) {
    const data = await this.pacienteRepo.listarPacientesAdministracion(
      limite ? Number(limite) : 50,
    );
    return {
      status: 200,
      success: true,
      message: 'Peticion pacientes Exitosa',
      data,
    };
  }
}