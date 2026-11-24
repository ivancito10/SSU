export interface Usuario {
    id: string;
    name: string;
    email: string;
    estado: boolean | null;
    imagen: string | null;
    id_persona: number | null;
    created_at: Date | null;
    updated_at: Date | null;
}

export interface FiltroUsuario {
    pagina: number;
    limite: number;
    buscar?: string;
    nombre?: string;
    email?: string;
    estado?: boolean;
    idPersona?: number;
}

export abstract class UsuarioPort {
    abstract listar(
        filtro: FiltroUsuario,
    ): Promise<{ datos: Usuario[]; total: number }>;
    abstract obtener(id: string): Promise<Usuario | null>;
}

export class UsuarioError extends Error {
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
        throw new UsuarioError(400, 'ID o parámetro de paginación inválido');
    return valor;
}
