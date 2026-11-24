import { paginationMeta } from '../../../common/application/pagination-meta';
import { UsuarioPort, UsuarioError, validarEntero } from '../domain/usuario';
import type { FiltroUsuario } from '../domain/usuario';

export class ConsultarUsuariosUseCase {
    constructor(private readonly repo: UsuarioPort) {}

    async listar(q: Record<string, unknown>) {
        const textos = ['buscar', 'nombre', 'email'] as const;
        const permitidos = [
            ...textos,
            'pagina',
            'limite',
            'estado',
            'id_persona',
        ];
        if (Object.keys(q).some((k) => !permitidos.includes(k)))
            throw new UsuarioError(400, 'Filtro no permitido');
        const filtro: FiltroUsuario = {
            pagina: Number(validarEntero(q.pagina ?? '1', 1000000n)),
            limite: Number(validarEntero(q.limite ?? '20', 2000n)),
        };
        for (const campo of textos) {
            const valor = q[campo];
            if (valor !== undefined) {
                if (typeof valor !== 'string' || valor.length > 100)
                    throw new UsuarioError(400, `Filtro ${campo} inválido`);
                filtro[campo] = valor.trim();
            }
        }
        if (q.estado !== undefined) {
            if (q.estado !== 'true' && q.estado !== 'false')
                throw new UsuarioError(400, 'estado debe ser true o false');
            filtro.estado = q.estado === 'true';
        }
        if (q.id_persona !== undefined)
            filtro.idPersona = Number(validarEntero(q.id_persona, 2147483647n));
        const { datos, total } = await this.repo.listar(filtro);
        return {
            datos,
            total,
            meta: paginationMeta(total, filtro.pagina, filtro.limite),
        };
    }

    async obtener(id: string) {
        const usuario = await this.repo.obtener(
            validarEntero(id, 9223372036854775807n),
        );
        if (!usuario) throw new UsuarioError(404, 'Usuario no encontrado');
        return usuario;
    }
}
