import { DataSource } from 'typeorm';
import { entidadesEtapa1 } from '../../../../common/persistence/orm/entities';
import { especialistasActivosPorPersonaQuery } from './postgres-especialista.repository';

describe('Especialista activo por persona', () => {
    const db = new DataSource({ type: 'postgres', entities: entidadesEtapa1 });
    beforeAll(async () => {
        await (db as unknown as { buildMetadatas(): Promise<void> }).buildMetadatas();
    });
    it('filtra persona y estado true antes de limitar candidatos', () => {
        const query = especialistasActivosPorPersonaQuery(db, 20729);
        const [sql, params] = query.getQueryAndParameters();
        expect(sql).toContain('"e"."id_persona" = $1');
        expect(sql).toContain('"e"."estado" = $2');
        expect(params).toEqual([20729, true]);
        expect(query.expressionMap.take).toBe(2);
    });
});