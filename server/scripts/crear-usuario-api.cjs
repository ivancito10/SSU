// Ejecutar localmente: npm run swagger:crear-usuario -- <id de administracion.users>
// No pasar contraseñas por argumentos ni compartir la salida de este comando.
require('reflect-metadata');
const { DataSource, EntitySchema } = require('typeorm');
const bcrypt = require('bcrypt');
const { randomBytes } = require('node:crypto');

const userId = process.argv[2];
if (!userId || !/^[1-9]\d{0,18}$/.test(userId) || BigInt(userId) > 9223372036854775807n) {
    console.error('Uso: npm run swagger:crear-usuario -- <id_user>');
    process.exit(1);
}
const User = new EntitySchema({
    name: 'InstitutionalUser', schema: 'administracion', tableName: 'users',
    columns: { id: { type: 'bigint', primary: true }, email: { type: 'varchar' }, estado: { type: 'boolean' } },
});
const Account = new EntitySchema({
    name: 'ApiAccount', schema: 'administracion', tableName: 'usuarios_api',
    columns: {
        id: { type: 'bigint', primary: true, generated: 'increment' },
        id_user: { type: 'bigint' }, username: { type: 'varchar' },
        password: { type: 'varchar' }, estado: { type: 'boolean' },
        debe_cambiar_password: { type: 'boolean' },
    },
});
const db = new DataSource({
    type: 'postgres', host: process.env.DB_SIGHOV_HOST,
    port: Number(process.env.DB_SIGHOV_PORT || 5432),
    username: process.env.DB_SIGHOV_USERNAME, password: process.env.DB_SIGHOV_PASSWORD,
    database: process.env.DB_SIGHOV_DATABASE, entities: [User, Account],
    synchronize: false, logging: false,
});
async function main() {
    await db.initialize();
    const password = randomBytes(18).toString('base64url');
    const hash = await bcrypt.hash(password, 10);
    const account = await db.transaction(async (manager) => {
        const user = await manager.getRepository(User).findOneBy({ id: userId, estado: true });
        if (!user) throw new Error('El usuario institucional no existe o está inactivo.');
        const username = user.email?.trim().toLowerCase();
        if (!username || username.length > 255) {
            throw new Error('El usuario institucional no tiene un identificador válido en users.email.');
        }
        const repo = manager.getRepository(Account);
        if (await repo.exists({ where: [{ id_user: userId }, { username }] })) {
            throw new Error('Ya existe una cuenta API para ese usuario o correo; no se modificó.');
        }
        return repo.save(repo.create({ id_user: userId, username, password: hash, estado: true, debe_cambiar_password: true }));
    });
    console.log('Cuenta creada. Entregue estas credenciales al titular por un medio privado.');
    console.log('Usuario:', account.username);
    console.log('Contraseña inicial (se muestra solo ahora):', password);
}
main().catch((error) => {
    // Nunca imprimir QueryFailedError: puede incluir el hash y parámetros sensibles.
    console.error(error.driverError || error.code ? 'No se pudo crear la cuenta. Revise conexión, tabla, permisos o duplicados.' : error.message);
    process.exitCode = 1;
}).finally(async () => { if (db.isInitialized) await db.destroy(); });
