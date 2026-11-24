import { FisioterapeutasRepositoryPort } from '../../domain/ports/fisioterapeutas.repository.port';
export class ObtenerCatalogosFisioterapeutasUseCase {
    constructor(private readonly repository: FisioterapeutasRepositoryPort) {}
    ejecutar(idDepartamento?: number, idProvincia?: number) {
        return this.repository.catalogos(idDepartamento, idProvincia);
    }
}
