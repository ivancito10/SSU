import { Test } from '@nestjs/testing';
import { APP_GUARD } from '@nestjs/core';
import { ConfigService } from '@nestjs/config';
import { JwtService } from '@nestjs/jwt';
import { PassportModule } from '@nestjs/passport';
import type { INestApplication } from '@nestjs/common';
import request from 'supertest';
import { JwtAuthGuard } from '../auth/infrastructure/jwt/jwt-auth.guard';
import { JwtStrategy } from '../auth/infrastructure/jwt/jwt.strategy';
import { UsuariosController } from './infrastructure/usuariosAPIs.controller';
import { ConsultarUsuariosUseCase } from './application/consultar-usuariosAPIs.use-case';
import { EspecialistasController } from '../especialistas/infrastructure/controllers/especialistas.controller';
import { GetEspecialistaByIdUseCase } from '../especialistas/application/use-cases/get-especialista-by-id.use-case';
import { GetEspecialistaByPersonaUseCase } from '../especialistas/application/use-cases/get-especialista-by-persona.use-case';
import { GetEspecialistasActivosUseCase } from '../especialistas/application/use-cases/get-especialistas-activos.use-case';

describe('HTTP usuarios con JWT real y repositorio aislado', () => {
    let app: INestApplication;
    const secret = 'clave-exclusiva-de-pruebas-no-es-configuracion-real';
    const jwt = new JwtService({ secret });
    const repo = {
        listar: jest.fn(async () => ({ datos: [], total: 0 })),
        obtener: jest.fn(async () => null),
    };
    beforeAll(async () => {
        const module = await Test.createTestingModule({
            imports: [PassportModule.register({ defaultStrategy: 'jwt' })],
            controllers: [UsuariosController, EspecialistasController],
            providers: [
                { provide: GetEspecialistasActivosUseCase, useValue: {} },
                {
                    provide: GetEspecialistaByIdUseCase,
                    useValue: {
                        ejecutar: (id: number) => ({
                            especialista: {
                                idEspecialista: id,
                                idPersona: 123,
                            },
                        }),
                    },
                },
                {
                    provide: GetEspecialistaByPersonaUseCase,
                    useValue: {
                        ejecutar: (id: number) => ({
                            especialista: {
                                idEspecialista: 352,
                                idPersona: id,
                            },
                        }),
                    },
                },
                {
                    provide: ConsultarUsuariosUseCase,
                    useValue: new ConsultarUsuariosUseCase(repo),
                },
                {
                    provide: ConfigService,
                    useValue: { getOrThrow: () => secret },
                },
                JwtStrategy,
                { provide: APP_GUARD, useClass: JwtAuthGuard },
            ],
        }).compile();
        app = module.createNestApplication();
        app.setGlobalPrefix('api');
        await app.init();
    });
    afterAll(async () => app?.close());

    it.each([
        '/api/usuarios',
        '/api/usuarios/1',
        '/api/especialistas/persona/123',
    ])('rechaza sin token %s', async (path) => {
        await request(app.getHttpServer()).get(path).expect(401);
    });
    it('distingue rutas de especialista y persona y valida el parámetro', async () => {
        const token = jwt.sign({ sub: '1' });
        const detail = await request(app.getHttpServer())
            .get('/api/especialistas/352')
            .set('Authorization', `Bearer ${token}`)
            .expect(200);
        const persona = await request(app.getHttpServer())
            .get('/api/especialistas/persona/123')
            .set('Authorization', `Bearer ${token}`)
            .expect(200);
        expect(persona.body).toEqual(detail.body);
        await request(app.getHttpServer())
            .get('/api/especialistas/persona/invalido')
            .set('Authorization', `Bearer ${token}`)
            .expect(400);
    });
    it('rechaza token inválido y vencido', async () => {
        for (const token of [
            'invalido',
            jwt.sign({ sub: '1' }, { expiresIn: -1 }),
        ]) {
            await request(app.getHttpServer())
                .get('/api/usuarios')
                .set('Authorization', `Bearer ${token}`)
                .expect(401);
        }
    });
    it('permite consulta válida y devuelve meta aun sin coincidencias', async () => {
        const response = await request(app.getHttpServer())
            .get('/api/usuarios?buscar=123')
            .set('Authorization', `Bearer ${jwt.sign({ sub: '1' })}`)
            .expect(200);
        expect(response.body).toMatchObject({
            datos: [],
            total: 0,
            meta: { page: 1, lastPage: 1 },
        });
    });
    it('devuelve errores HTTP 400 y 404 para un usuario autenticado', async () => {
        const token = jwt.sign({ sub: '1' });
        await request(app.getHttpServer())
            .get('/api/usuarios?estado=incorrecto')
            .set('Authorization', `Bearer ${token}`)
            .expect(400);
        const response = await request(app.getHttpServer())
            .get('/api/usuarios/1')
            .set('Authorization', `Bearer ${token}`)
            .expect(404);
        expect(response.body).toEqual({
            statusCode: 404,
            message: 'Usuario no encontrado',
        });
    });
});
