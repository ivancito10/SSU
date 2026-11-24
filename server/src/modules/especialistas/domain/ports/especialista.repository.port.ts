import { Especialista } from '../entities/especialista.entity';

export abstract class EspecialistaRepositoryPort {
    abstract listarActivos(
        consulta: ConsultaEspecialistas,
    ): Promise<{ datos: Especialista[]; total: number }>;
    abstract buscarPorId(idEspecialista: number): Promise<Especialista | null>;
    abstract buscarPorPersona(idPersona: number): Promise<Especialista[]>;
}
export interface ConsultaEspecialistas {
    buscar: string;
    especialidad?: string;
    idEspecialidad?: number;
    pagina: number;
    limite: number;
}
