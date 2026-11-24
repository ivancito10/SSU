import { Injectable } from '@nestjs/common';
import { InjectDataSource } from '@nestjs/typeorm';
import { Brackets, DataSource, ILike } from 'typeorm';
import { DB_CONNECTIONS } from '../../../config/database.constants';
import { UsuarioOrm } from '../../../common/persistence/orm/entities';
import type { UsuarioRow } from '../../../common/persistence/orm/entities';
import { patron } from '../../../common/persistence/orm/lecturas';
import { UsuarioPort } from '../domain/usuario';
import type { FiltroUsuario, Usuario } from '../domain/usuario';

export function usuariosQuery(db: DataSource, f: Partial<FiltroUsuario> = {}) {
    const q = db
        .getRepository(UsuarioOrm)
        .createQueryBuilder('u')
        .select([
            'u.id',
            'u.name',
            'u.email',
            'u.estado',
            'u.imagen',
            'u.idPersona',
            'u.createdAt',
            'u.updatedAt',
        ])
        .leftJoin('u.persona', 'p');
    if (f.estado !== undefined) q.andWhere({ estado: f.estado });
    if (f.idPersona !== undefined) q.andWhere({ idPersona: f.idPersona });
    if (f.email) q.andWhere({ email: ILike(patron(f.email)) });
    if (f.nombre) {
        for (const palabra of f.nombre.split(/\s+/)) {
            const like = ILike(patron(palabra));
            q.andWhere(
                new Brackets((b) =>
                    b
                        .where({ name: like })
                        .orWhere({ persona: { nombres: like } })
                        .orWhere({ persona: { primerApellido: like } })
                        .orWhere({ persona: { segundoApellido: like } }),
                ),
            );
        }
    }
    if (f.buscar) {
        const like = ILike(patron(f.buscar));
        q.andWhere(
            new Brackets((b) =>
                b.where({ name: like }).orWhere({ email: like }),
            ),
        );
    }
    return q;
}

export function mapUsuario(row: UsuarioRow): Usuario {
    return {
        id: String(row.id),
        name: row.name,
        email: row.email,
        estado: row.estado,
        imagen: row.imagen,
        id_persona: row.idPersona,
        created_at: row.createdAt,
        updated_at: row.updatedAt,
    };
}

@Injectable()
export class PostgresUsuariosRepository implements UsuarioPort {
    constructor(
        @InjectDataSource(DB_CONNECTIONS.SIGHOV)
        private readonly db: DataSource,
    ) {}

    async listar(f: FiltroUsuario) {
        const [rows, total] = await usuariosQuery(this.db, f)
            .orderBy('u.name', 'ASC')
            .addOrderBy('u.id', 'ASC')
            .skip((f.pagina - 1) * f.limite)
            .take(f.limite)
            .getManyAndCount();
        return { datos: rows.map(mapUsuario), total };
    }

    async obtener(id: string) {
        const row = await usuariosQuery(this.db).where({ id }).getOne();
        return row ? mapUsuario(row) : null;
    }
}
