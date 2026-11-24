import { Usuario } from "../../usuarios/domain/usuario";

export interface UsuarioAPIs {
    id: string;
    username: string;
    estado: boolean | null;
    rol: string | null;
    debe_cambiar_password: boolean | null;
    id_user: number | null;
    name?: Usuario | null;
    created_at: Date | null;
    updated_at: Date | null;
}

export interface FiltroUsuarioAPIs {
    pagina: number;
    limite: number;
    buscar?: string;
    username?: string;
    estado?: boolean;
    idUser?: number;
    createdAt?: Date,
    updatedAt?: Date,
}

export abstract class UsuarioAPIsPort {
    abstract listar(
        filtro: FiltroUsuarioAPIs,
    ): Promise<{ datos: UsuarioAPIs[]; total: number }>;
    abstract obtener(id: string): Promise<UsuarioAPIs | null>;
}

export class UsuarioAPIsError extends Error {
    constructor(
        public readonly status: 400 | 404,
        message: string,
    ) {
        super(message);
    }
}

export function validarEntero(valor: unknown, max: bigint): string {
    if (
        typeof valor !== 'string' ||
        !/^[1-9]\d{0,18}$/.test(valor) ||
        BigInt(valor) > max
    )
        throw new UsuarioAPIsError(400, 'ID o parámetro de paginación inválido');
    return valor;
}
