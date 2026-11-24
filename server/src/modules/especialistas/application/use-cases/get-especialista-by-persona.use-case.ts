import {
    Injectable,
    BadRequestException,
    NotFoundException,
    ConflictException,
} from '@nestjs/common';
import { EspecialistaRepositoryPort } from '../../domain/ports/especialista.repository.port';

@Injectable()
export class GetEspecialistaByPersonaUseCase {
    constructor(private readonly repo: EspecialistaRepositoryPort) {}

    async ejecutar(idPersona: number) {
        if (
            !Number.isInteger(idPersona) ||
            idPersona < 1 ||
            idPersona > 2147483647
        )
            throw new BadRequestException('ID de persona inválido');
        const especialistas = await this.repo.buscarPorPersona(idPersona);
        if (!especialistas.length)
            throw new NotFoundException(
                `Especialista activo para la persona ${idPersona} no encontrado`,
            );
        if (especialistas.length > 1)
            throw new ConflictException(
                'La persona tiene varios especialistas activos; consulte por ID de especialista',
            );
        return { especialista: especialistas[0] };
    }
}