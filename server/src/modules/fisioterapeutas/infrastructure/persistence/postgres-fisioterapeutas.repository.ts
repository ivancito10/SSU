import { RegistroFisioterapeutaError } from '../../domain/errors/registro-fisioterapeuta.error';
import { Injectable } from '@nestjs/common';
import { InjectDataSource } from '@nestjs/typeorm';
import { DataSource } from 'typeorm';
import { DB_CONNECTIONS } from '../../../../config/database.constants';
import {
    EstadoCivilOrm,
    DepartamentoOrm,
    ProvinciaOrm,
    MunicipioOrm,
} from '../../../../common/persistence/orm/entities';
import { FisioterapeutasRepositoryPort } from '../../domain/ports/fisioterapeutas.repository.port';
import type { RegistroFisioterapeuta } from '../../domain/models/fisioterapeuta.model';
import {
    transaccionFisio,
    referencias,
    documentoLibre,
    crearPersona,
    crearEspecialista,
    crearUsuario,
} from './orm-escritura';
@Injectable()
export class PostgresFisioterapeutasRepository implements FisioterapeutasRepositoryPort {
    constructor(
        @InjectDataSource(DB_CONNECTIONS.SIGHOV)
        private readonly dataSource: DataSource,
    ) {}
    async catalogos(idDepartamento?: number, idProvincia?: number) {
        if (
            idDepartamento !== undefined &&
            !(await this.dataSource
                .getRepository(DepartamentoOrm)
                .existsBy({ id: idDepartamento }))
        )
            throw new RegistroFisioterapeutaError(
                'validacion',
                'Departamento inexistente',
            );
        if (
            idProvincia !== undefined &&
            !(await this.dataSource.getRepository(ProvinciaOrm).existsBy({
                id: idProvincia,
                ...(idDepartamento !== undefined ? { idDepartamento } : {}),
            }))
        )
            throw new RegistroFisioterapeutaError(
                'validacion',
                'Provincia inexistente o no pertenece al departamento',
            );
        const provincias =
            idDepartamento === undefined
                ? []
                : await this.dataSource.getRepository(ProvinciaOrm).find({
                      where: { idDepartamento },
                      order: { nombre: 'ASC' },
                  });
        const municipios =
            idProvincia === undefined
                ? []
                : await this.dataSource.getRepository(MunicipioOrm).find({
                      where: { idProvincia },
                      order: { nombre: 'ASC' },
                  });
        const [estadosCiviles, departamentos] = await Promise.all([
            this.dataSource
                .getRepository(EstadoCivilOrm)
                .find({ order: { id: 'ASC' } }),
            this.dataSource
                .getRepository(DepartamentoOrm)
                .find({ order: { id: 'ASC' } }),
        ]);
        return {
            estadosCiviles,
            departamentos: departamentos.map(({ id, nombre }) => ({
                id,
                nombre,
            })),
            provincias,
            municipios,
            expedidos: departamentos.map((d) => ({
                id: d.id,
                nombre: d.nombre,
                sigla: d.sigla ?? '',
            })),
        };
    }
    crear(d: RegistroFisioterapeuta) {
        return transaccionFisio(this.dataSource, async (m) => {
            await documentoLibre(m, d);
            await referencias(m, d, d.idUsuarioCreador);
            const p = await crearPersona(m, d);
            const e = await crearEspecialista(m, p.id, d),
                u = await crearUsuario(m, p.id, d);
            return {
                idPersona: p.id,
                esExtranjero: d.esExtranjero,
                idEspecialista: e.id,
                idUsuario: u.id,
                nombreCompleto: d.nombreUsuario,
                email: u.email,
                matricula: d.matricula,
                foto: e.foto,
                imagen: u.imagen,
            };
        });
    }
}