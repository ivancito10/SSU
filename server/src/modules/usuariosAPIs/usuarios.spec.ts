import { DataSource } from 'typeorm';
import { entidadesEtapa1 } from '../../common/persistence/orm/entities';
import type { UsuarioRow } from '../../common/persistence/orm/entities';
import { ConsultarUsuariosUseCase } from './application/consultar-usuariosAPIs.use-case';
import {
    usuariosQuery,
    mapUsuario,
} from './infrastructure/postgres-usuariosAPIs.repository';
import type { UsuarioPort } from './domain/usuarioAPIs';
import { limitarDocumento } from '../../config/swagger';
import type { OpenAPIObject } from '@nestjs/swagger';

describe('Usuarios: consulta segura de etapa 1', () => {
    const db = new DataSource({ type: 'postgres', entities: entidadesEtapa1 });
    const listar = jest.fn(async () => ({ datos: [], total: 41 }));
    const obtener = jest.fn(async () => null);
    const repo: UsuarioPort = { listar, obtener };
    const service = new ConsultarUsuariosUseCase(repo);

    beforeAll(async () => {
        await (
            db as unknown as { buildMetadatas(): Promise<void> }
        ).buildMetadatas();
    });
    beforeEach(() => jest.clearAllMocks());

    it('pagina con el contrato meta común y conserva estado false', async () => {
        const result = await service.listar({
            pagina: '2',
            limite: '20',
            estado: 'false',
        });
        expect(listar).toHaveBeenCalledWith({
            pagina: 2,
            limite: 20,
            estado: false,
        });
        expect(result.meta).toEqual({
            total: 41,
            page: 2,
            lastPage: 3,
            limit: 20,
            hasNextPage: true,
            hasPrevPage: true,
        });
    });

    it.each([
        { limite: '2001' },
        { pagina: '0' },
        { pagina: ['1'] },
        { estado: '0' },
        { complemento: '1A' },
        { ci: '123456' },
        { matricula: 'ABC123' },
        { ci: '123', complemento: '' },
        { email: { objeto: 'x' } },
        { id_persona: '-1' },
        { password: 'x' },
    ])('rechaza filtros inválidos %j', async (q) => {
        await expect(service.listar(q)).rejects.toMatchObject({ status: 400 });
        expect(listar).not.toHaveBeenCalled();
    });

    it('mantiene bigint completo y responde 404 si no existe', async () => {
        await expect(service.obtener('9007199254740993')).rejects.toMatchObject(
            { status: 404 },
        );
        expect(obtener).toHaveBeenCalledWith('9007199254740993');
        await expect(
            service.obtener('9223372036854775808'),
        ).rejects.toMatchObject({ status: 400 });
    });

    it('selecciona solo campos públicos y usa LEFT JOIN para usuarios sin persona', () => {
        const sql = usuariosQuery(db).getQuery();
        const select = sql.split(' FROM ')[0];
        expect(sql).toContain('LEFT JOIN');
        expect(sql).toContain('"administracion"."users"');
        expect(select).not.toMatch(
            /password|last_login_at|id_user_created|id_user_updated/,
        );
        expect(select).toContain('"created_at"');
    });

    it('buscar num�rico consulta nombre y correo, nunca CI o matr�cula', () => {
        const [sql, params] = usuariosQuery(db, {
            buscar: '123456',
        }).getQueryAndParameters();
        expect(sql.split(' WHERE ')[1]).not.toMatch(/ci|matricula_seguro/);
        expect(params).toEqual(['%123456%', '%123456%']);
    });

    it('enlaza parámetros y escapa comodines', () => {
        const [sql, params] = usuariosQuery(db, {
            email: "a%_'",
            estado: false,
            nombre: 'Ana Perez',
        }).getQueryAndParameters();
        expect(sql).not.toContain("a%_'");
        expect(params).toContain("%a\\%\\_'%");
        expect(params).toContain(false);
        expect(params).toContain('%Perez%');
    });

    it('el mapeo no expone campos adicionales aunque existan en la entidad', () => {
        const result = mapUsuario({
            id: '9007199254740993',
            name: 'Prueba',
            email: 'prueba@example.test',
            estado: true,
            imagen: null,
            idPersona: null,
            createdAt: null,
            updatedAt: null,
            password: 'NO-EXPONER',
            idUsuarioCreador: '1',
        } as unknown as UsuarioRow);
        expect(Object.keys(result)).toEqual([
            'id',
            'name',
            'email',
            'estado',
            'imagen',
            'id_persona',
            'created_at',
            'updated_at',
        ]);
        expect(result.id).toBe('9007199254740993');
    });

    it('documenta ambas rutas con Bearer, filtros, meta y errores', () => {
        const document = limitarDocumento({
            paths: {
                '/api/usuarios': { get: {} },
                '/api/usuarios/{id}': { get: {} },
            },
        } as OpenAPIObject);
        const list = document.paths['/api/usuarios'].get!;
        const detail = document.paths['/api/usuarios/{id}'].get!;
        expect(list.security).toEqual([{ bearer: [] }]);
        expect(list.parameters).toEqual(
            expect.arrayContaining([
                expect.objectContaining({ name: 'nombre' }),
                expect.objectContaining({ name: 'estado' }),
                expect.objectContaining({ name: 'pagina' }),
            ]),
        );
        expect(detail.responses['404'].description).toContain('Usuario');
        expect(detail.parameters![0]).toMatchObject({
            schema: { type: 'string' },
        });
        expect(list.responses).toHaveProperty('401');
    });
});
