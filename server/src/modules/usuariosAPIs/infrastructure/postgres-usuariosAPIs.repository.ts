import { Injectable } from '@nestjs/common';
import { InjectDataSource } from '@nestjs/typeorm';
import { Brackets, DataSource, ILike } from 'typeorm';
import { DB_CONNECTIONS } from '../../../config/database.constants';
import { UsuarioAPIsOrm } from '../../../common/persistence/orm/entities';
import type { UsuarioAPIsRow, UsuarioRow } from '../../../common/persistence/orm/entities';
import { patron } from '../../../common/persistence/orm/lecturas';
import { UsuarioAPIsPort } from '../domain/usuarioAPIs';
import type { FiltroUsuarioAPIs, UsuarioAPIs } from '../domain/usuarioAPIs';

export function usuariosAPIsQuery(db: DataSource, f: Partial<FiltroUsuarioAPIs> = {}) {
    const q = db
        .getRepository(UsuarioAPIsOrm)
        .createQueryBuilder('uapi')
        .select([
            'uapi.id',
            'uapi.username',
            'uapi.estado',
            'uapi.rol',
            'uapi.debeCambiarPassword',
            'uapi.idUser',
            'uapi.createdAt',
            'uapi.updatedAt',
        ])
        .leftJoin('uapi.user', 'u')
        .addSelect(['u.id', 'u.name']);
    if (f.estado !== undefined) q.andWhere({ estado: f.estado });
    if (f.idUser !== undefined) q.andWhere({ idUser: f.idUser });
    if (f.username) q.andWhere({ username: ILike(patron(f.username)) });
    if (f.buscar) {
        const like = ILike(patron(f.buscar));
        q.andWhere(new Brackets(b => b.where({ username: like }).orWhere({ user: { name: like } })));
    }
    return q;
}

export function mapUsuarioAPIs(row: UsuarioAPIsRow): UsuarioAPIs {
    return {
        id: String(row.id),
        username: row.username,
        estado: row.estado,
        rol: row.rol,
        debe_cambiar_password: row.debeCambiarPassword,
        id_user: row.idUser,
        created_at: row.createdAt,
        updated_at: row.updatedAt,
    };
}

@Injectable()
export class PostgresUsuariosAPIsRepository implements UsuarioAPIsPort {
    constructor(
        @InjectDataSource(DB_CONNECTIONS.SIGHOV)
        private readonly db: DataSource,
    ) {}

    async listar(f: FiltroUsuarioAPIs) {
        const [rows, total] = await usuariosAPIsQuery(this.db, f)
            .orderBy('uapi.username', 'ASC')
            .addOrderBy('uapi.id', 'ASC')
            .skip((f.pagina - 1) * f.limite)
            .take(f.limite)
            .getManyAndCount();
        return { datos: rows.map(mapUsuarioAPIs), total };
    }

    async obtener(id: string) {
        const row = await usuariosAPIsQuery(this.db).where({ id }).getOne();
        return row ? mapUsuarioAPIs(row) : null;
    }
}
