import type {
    RegistroFisioterapeuta,
    FisioterapeutaCreado,
    CatalogosFisioterapeuta,
} from '../models/fisioterapeuta.model';
export abstract class FisioterapeutasRepositoryPort {
    abstract crear(
        datos: RegistroFisioterapeuta,
    ): Promise<FisioterapeutaCreado>;
    abstract catalogos(
        idDepartamento?: number,
        idProvincia?: number,
    ): Promise<CatalogosFisioterapeuta>;
}