import type { EntityManager } from 'typeorm';
import { crearPersona, crearEspecialista, crearUsuario } from './orm-escritura';
import type { RegistroFisioterapeuta } from '../../domain/models/fisioterapeuta.model';

describe('Fechas de alta de fisioterapeutas', () => {
    const datos: RegistroFisioterapeuta = {
        ci: '123456',
        complemento: null,
        nombres: 'Ana',
        primerApellido: 'Perez',
        segundoApellido: null,
        sexo: 'F',
        esExtranjero: false,
        fechaNacimiento: '2000-01-01',
        idEstadoCivil: 1,
        idDeptExp: 1,
        idNacimientoMunicipio: 69,
        matricula: '005101PEA',
        claveUnica: '123456',
        permanente: true,
        fechaContratoInicio: null,
        fechaContratoFin: null,
        gradoAcademico: 'LICENCIADO',
        nombreUsuario: 'Ana Perez',
        email: 'aperezp@ssulapaz.org',
        passwordHash: 'hash-prueba',
        idUsuarioCreador: 1,
    };

    it.each(['persona', 'especialista', 'usuario'] as const)(
        'envía las fechas de %s sin depender del DEFAULT de PostgreSQL',
        async (tipo) => {
            const ahora = new Date('2026-10-15T16:00:00Z');
            jest.useFakeTimers().setSystemTime(ahora);
            try {
                const repo = {
                    existsBy: jest.fn().mockResolvedValue(tipo === 'persona'),
                    create: jest.fn((value) => value),
                    save: jest.fn(async (value) => ({ ...value, id: 10 })),
                    update: jest.fn().mockResolvedValue({ affected: 1 }),
                };
                const manager = {
                    getRepository: () => repo,
                } as unknown as EntityManager;
                if (tipo === 'persona') await crearPersona(manager, datos);
                if (tipo === 'especialista')
                    await crearEspecialista(manager, 10, datos);
                if (tipo === 'usuario') await crearUsuario(manager, 10, datos);
                expect(repo.save).toHaveBeenCalledWith(
                    expect.objectContaining({
                        createdAt: ahora,
                        updatedAt: ahora,
                    }),
                );
                for (const call of repo.update.mock.calls) {
                    expect(call[1]).not.toHaveProperty('createdAt');
                }
            } finally {
                jest.useRealTimers();
            }
        },
    );
});
