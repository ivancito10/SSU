import { paginationMeta } from '../../../common/application/pagination-meta';
import { UsuarioAPIsPort, UsuarioAPIsError, validarEntero } from '../domain/usuarioAPIs';
import type { FiltroUsuarioAPIs } from '../domain/usuarioAPIs';

export class ConsultarUsuariosAPIsUseCase {
    constructor(private readonly repo: UsuarioAPIsPort) {}

    async listar(q: Record<string, unknown>) {
        const textos = ['buscar', 'username'] as const;
        const permitidos = [
            ...textos,
            'pagina',
            'limite',
            'estado',
            'id_user',
        ];
        if (Object.keys(q).some((k) => !permitidos.includes(k)))
            throw new UsuarioAPIsError(400, 'Filtro no permitido');
        const filtro: FiltroUsuarioAPIs = {
            pagina: Number(validarEntero(q.pagina ?? '1', 1000000n)),
            limite: Number(validarEntero(q.limite ?? '20', 2000n)),
        };
        for (const campo of textos) {
            const valor = q[campo];
            if (valor !== undefined) {
                if (typeof valor !== 'string' || valor.length > 100)
                    throw new UsuarioAPIsError(400, `Filtro ${campo} inválido`);
                filtro[campo] = valor.trim();
            }
        }
        if (q.estado !== undefined) {
            if (q.estado !== 'true' && q.estado !== 'false')
                throw new UsuarioAPIsError(400, 'estado debe ser true o false');
            filtro.estado = q.estado === 'true';
        }
        if (q.id_user !== undefined)
            filtro.idUser = Number(validarEntero(q.id_user, 2147483647n));
        const { datos, total } = await this.repo.listar(filtro);
        return {
            datos,
            total,
            meta: paginationMeta(total, filtro.pagina, filtro.limite),
        };
    }

    async obtener(id: string) {
        const usuarioAPI = await this.repo.obtener(
            validarEntero(id, 9223372036854775807n),
        );
        if (!usuarioAPI) throw new UsuarioAPIsError(404, 'Usuario API no encontrado');
        return usuarioAPI;
    }
}
