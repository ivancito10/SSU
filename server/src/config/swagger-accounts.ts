import { EntitySchema, DataSource } from 'typeorm';
import * as bcrypt from 'bcrypt';
import { createHmac, randomBytes, timingSafeEqual } from 'node:crypto';
import { usuariosQuery } from '../modules/usuarios/infrastructure/postgres-usuarios.repository';
import { usuariosAPIsQuery } from '../modules/usuariosAPIs/infrastructure/postgres-usuariosAPIs.repository';
import { UsuarioOrm } from '../common/persistence/orm/entities';

export interface SwaggerAccount {
    id: string;
    idUser: string;
    username: string;
    password: string;
    estado: boolean;
    rol: 'ADMIN' | 'CONSULTA';
    debeCambiarPassword: boolean;
}

export const SwaggerAccountOrm = new EntitySchema<SwaggerAccount>({
    name: 'SwaggerAccount',
    schema: 'administracion',
    tableName: 'usuarios_api',
    columns: {
        id: { type: 'bigint', primary: true, generated: 'increment' },
        idUser: { name: 'id_user', type: 'bigint' },
        username: { type: 'varchar', length: 255 },
        password: { type: 'varchar', length: 255, select: false },
        estado: { type: 'boolean' },
        rol: { type: 'varchar', length: 8 },
        debeCambiarPassword: { name: 'debe_cambiar_password', type: 'boolean', default: false },
    },
});

// No emite JWT de las APIs. Esta sesion sirve exclusivamente para documentacion.
export class SwaggerAccounts {
    private readonly dummy = bcrypt.hashSync(randomBytes(24).toString('hex'), 12);
    constructor(private readonly db: DataSource, private readonly secret: string) {
        if (secret.length < 32) throw new Error('SWAGGER_SESSION_SECRET debe tener al menos 32 caracteres');
    }

    private async active(id: string): Promise<SwaggerAccount | null> {
        const account = await this.db.getRepository(SwaggerAccountOrm).findOne({
            where: { id, estado: true },
            select: { id: true, idUser: true, username: true, password: true, estado: true, rol: true, debeCambiarPassword: true },
        });
        if (!account || !await this.db.getRepository(UsuarioOrm).existsBy({ id: account.idUser, estado: true })) return null;
        return account;
    }

    async login(username: unknown, password: unknown): Promise<string | null> {
        if (typeof username !== 'string' || typeof password !== 'string'
            || username.length > 255 || !password || Buffer.byteLength(password) > 72) return null;
        const found = await this.db.getRepository(SwaggerAccountOrm).findOneBy({ username: username.trim().toLowerCase() });
        const account = found ? await this.active(found.id) : null;
        const valid = await bcrypt.compare(password, (account?.password ?? this.dummy).replace(/^\$2y\$/, '$2b$'));
        if (!account || !valid) return null;
        return this.session(account);
    }

    session(account: SwaggerAccount): string {
        const payload = Buffer.from(JSON.stringify({
            id: account.id, exp: Date.now() + 8 * 60 * 60 * 1000,
            version: this.sign(account.password),
        })).toString('base64url');
        return payload + '.' + this.sign(payload);
    }

    private sign(value: string): string {
        return createHmac('sha256', this.secret).update('swagger:' + value).digest('hex');
    }

    async authenticated(token: unknown): Promise<boolean> {
        return !!await this.current(token);
    }

    async current(token: unknown): Promise<SwaggerAccount | null> {
        if (typeof token !== 'string' || token.length > 2048) return null;
        const [payload, signature, extra] = token.split('.');
        if (!payload || !signature || extra || !/^[a-f0-9]{64}$/.test(signature)) return null;
        if (!timingSafeEqual(Buffer.from(signature), Buffer.from(this.sign(payload)))) return null;
        let claims: { id?: unknown; exp?: unknown; version?: unknown };
        try { claims = JSON.parse(Buffer.from(payload, 'base64url').toString()); }
        catch { return null; }
        if (!claims || typeof claims.id !== 'string' || !/^[1-9]\d{0,18}$/.test(claims.id)
            || typeof claims.exp !== 'number' || !Number.isFinite(claims.exp) || claims.exp <= Date.now()) return null;
        const account = await this.active(claims.id);
        return account && claims.version === this.sign(account.password) ? account : null;
    }

    csrf(token: string): string { return this.sign('csrf:' + token); }

    async searchUsers(actor: SwaggerAccount, text: string, page: number) {
        if (actor.rol !== 'ADMIN' || actor.debeCambiarPassword) throw new SwaggerInputError('Acceso denegado', 403);
        if (text.length > 100 || !Number.isSafeInteger(page) || page < 1 || page > 100000) throw new SwaggerInputError('Búsqueda inválida');
        const [rows, total] = await usuariosQuery(this.db, { buscar: text })
            .orderBy('u.name', 'ASC').addOrderBy('u.id', 'ASC').skip((page - 1) * 20).take(20).getManyAndCount();
        return { datos: rows.map(u => ({ id: u.id, name: u.name, username: u.email, estado: u.estado })), total, page, lastPage: Math.max(1, Math.ceil(total / 20)) };
    }

    async searchUsuariosAPIs(actor: SwaggerAccount, text: string, page: number) {
        if (actor.rol !== 'ADMIN' || actor.debeCambiarPassword) throw new SwaggerInputError('Acceso denegado', 403);
        if (text.length > 100 || !Number.isSafeInteger(page) || page < 1 || page > 100000) throw new SwaggerInputError('Búsqueda inválida');
        const [rows, total] = await usuariosAPIsQuery(this.db, { buscar: text })
            .orderBy('uapi.username', 'ASC').addOrderBy('uapi.id', 'ASC').skip((page - 1) * 20).take(20).getManyAndCount();
        return { datos: rows.map(u => ({ id: u.id, name: u.user?.name, username: u.username, estado: u.estado, rol: u.rol, debeCambiarPassword: u.debeCambiarPassword})), total, page, lastPage: Math.max(1, Math.ceil(total / 20)) };
    }

    async create(actor: SwaggerAccount, idUser: unknown, rol: unknown) {
        if (actor.rol !== 'ADMIN' || actor.debeCambiarPassword) throw new SwaggerInputError('Acceso denegado', 403);
        if (typeof idUser !== 'string' || !/^[1-9]\d{0,18}$/.test(idUser) || BigInt(idUser) > 9223372036854775807n
            || !['ADMIN', 'CONSULTA'].includes(String(rol))) throw new SwaggerInputError('Usuario o rol inválido');
        const passwordTemporal = randomBytes(18).toString('base64url');
        const hash = await bcrypt.hash(passwordTemporal, 12);
        try {
            const account = await this.db.transaction(async m => {
                const operator = await m.getRepository(SwaggerAccountOrm).findOneBy({ id: actor.id, estado: true, rol: 'ADMIN', debeCambiarPassword: false });
                if (!operator) throw new SwaggerInputError('Acceso denegado', 403);
                const user = await m.getRepository(UsuarioOrm).findOneBy({ id: idUser, estado: true });
                const username = user?.email?.trim().toLowerCase();
                if (!username || username.length > 255) throw new SwaggerInputError('El usuario está inactivo o no tiene un identificador válido en users.email');
                const repo = m.getRepository(SwaggerAccountOrm);
                if (await repo.exists({ where: [{ idUser }, { username }] })) throw new SwaggerInputError('El usuario o identificador ya tiene acceso a la documentacion de las APIs', 409);
                return repo.save(repo.create({ idUser, username, password: hash, estado: true, rol: rol as 'ADMIN' | 'CONSULTA', debeCambiarPassword: true }));
            });
            return { username: account.username, rol: account.rol, passwordTemporal };
        } catch (error) {
            if ((error as { driverError?: { code?: string } }).driverError?.code === '23505') throw new SwaggerInputError('El usuario o identificador ya tiene acceso a la documentacion de las APIs', 409);
            throw error;
        }
    }

    async manageAccess(actor: SwaggerAccount, id: unknown, action: 'baja' | 'restablecer' | 'rehabilitar') {
        if (actor.rol !== 'ADMIN' || actor.debeCambiarPassword) throw new SwaggerInputError('Acceso denegado', 403);
        if (typeof id !== 'string' || !/^[1-9]\d{0,18}$/.test(id) || BigInt(id) > 9223372036854775807n) throw new SwaggerInputError('ID de cuenta inválido');
        if (actor.id === id) throw new SwaggerInputError('Para su propia cuenta utilice Mi perfil');
        const repo = this.db.getRepository(SwaggerAccountOrm);
        const passwordTemporal = action === 'restablecer' ? randomBytes(18).toString('base64url') : undefined;
        const rehabilitar = action === 'rehabilitar';
        const values = passwordTemporal
            ? { password: await bcrypt.hash(passwordTemporal, 12), debeCambiarPassword: true }
            : { estado: rehabilitar };
        const result = await repo.update({ id, estado: !rehabilitar }, values);
        if (!result.affected) throw new SwaggerInputError('La cuenta cambió de estado o ya no existe. Actualice el listado.', 409);
        return passwordTemporal ? { passwordTemporal } : { estado: rehabilitar };
    }

    async changePassword(actor: SwaggerAccount, actual: unknown, nueva: unknown, confirmacion: unknown) {
        if (typeof actual !== 'string' || typeof nueva !== 'string' || typeof confirmacion !== 'string'
            || Buffer.byteLength(actual) > 72 || nueva.length < 12 || Buffer.byteLength(nueva) > 72 || nueva !== confirmacion) {
            throw new SwaggerInputError('La nueva contraseña debe tener al menos 12 caracteres y coincidir con su confirmación');
        }
        if (!await bcrypt.compare(actual, actor.password.replace(/^\$2y\$/, '$2b$'))) throw new SwaggerInputError('La contraseña actual es incorrecta');
        if (actual === nueva) throw new SwaggerInputError('La nueva contraseña debe ser diferente de la actual');
        const password = await bcrypt.hash(nueva, 12);
        const result = await this.db.getRepository(SwaggerAccountOrm).update(
            { id: actor.id, password: actor.password, estado: true }, { password, debeCambiarPassword: false },
        );
        if (result.affected !== 1) throw new SwaggerInputError('La cuenta cambió; vuelva a iniciar sesión', 409);
        // Nunca modifica administracion.users; el trigger actualiza la auditoría.
        return this.session({ ...actor, password, debeCambiarPassword: false });
    }
}

export class SwaggerInputError extends Error {
    constructor(message: string, public readonly status = 400) { super(message); }
}