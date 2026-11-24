import { DataSource } from 'typeorm';
import { entidadesEtapa1 } from '../../../common/persistence/orm/entities';
import type { PersonaRow } from '../../../common/persistence/orm/entities';
import {
    mapPersona,
    personasQuery,
} from '../../../common/persistence/orm/lecturas';

describe('Contrato de respuesta de Personas', () => {
    it('conserva nombres originales y añade los tres campos solicitados', () => {
        const persona = mapPersona({
            id: 1,
            nombres: 'ANA',
            primerApellido: 'PEREZ',
            segundoApellido: 'LOPEZ',
            matricula: '900101PLA',
            claveUnica: '1231A',
            grupoSanguineo: 'O+',
            alergias: 'Dato de prueba',
        } as PersonaRow);
        expect(persona).toMatchObject({
            p_apellido: 'PEREZ',
            s_apellido: 'LOPEZ',
            matricula_seguro: '900101PLA',
            clave_unica: '1231A',
            grupo_sanguineo: 'O+',
            alergias: 'Dato de prueba',
            nombreCompleto: 'ANA PEREZ LOPEZ',
        });
        for (const key of ['primerApellido', 'segundoApellido', 'matricula'])
            expect(persona).not.toHaveProperty(key);
    });
    it('mantiene null en los campos opcionales sin datos', () => {
        expect(mapPersona({ nombres: 'ANA' } as PersonaRow)).toMatchObject({
            grupo_sanguineo: null,
            alergias: null,
        });
    });
    it('devuelve la jerarquía de nacimiento sin confundirla con expedido', () => {
        const response = mapPersona({
            nombres: 'ANA',
            idNacimientoMunicipio: 120,
            idDeptExp: 3,
            municipio: {
                id: 120,
                nombre: 'MUNICIPIO',
                idProvincia: 15,
                provincia: {
                    id: 15,
                    nombre: 'PROVINCIA',
                    idDepartamento: 2,
                    departamento: { id: 2, nombre: 'LA PAZ' },
                },
            },
        } as PersonaRow);
        expect(response).toMatchObject({
            departamentoNacimiento: { id: 2, nombre: 'LA PAZ' },
            provinciaNacimiento: { id: 15, nombre: 'PROVINCIA' },
            municipioNacimiento: { id: 120, nombre: 'MUNICIPIO' },
            idDeptExp: 3,
        });
        expect(mapPersona({ nombres: 'ANA' } as PersonaRow)).toMatchObject({
            departamentoNacimiento: { id: null, nombre: null },
            provinciaNacimiento: { id: null, nombre: null },
        });
    });
    it('selecciona los campos nuevos con TypeORM', async () => {
        const db = new DataSource({
            type: 'postgres',
            entities: entidadesEtapa1,
        });
        await (
            db as unknown as { buildMetadatas(): Promise<void> }
        ).buildMetadatas();
        const sql = personasQuery(db).getQuery();
        expect(sql).toContain('"p"."grupo_sanguineo"');
        expect(sql).toContain('"p"."alergias"');
    });
});