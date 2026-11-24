export interface CrearFisioterapeuta {
    ci: string;
    complemento?: string | null;
    nombres: string;
    primerApellido?: string | null;
    segundoApellido?: string | null;
    sexo: 'M' | 'F';
    esExtranjero: boolean;
    fechaNacimiento: string;
    idEstadoCivil: number;
    idDeptExp: number;
    idDepartamentoNacimiento?: number;
    idNacimientoMunicipio: number | null;
    tipoContrato: 'EVENTUAL' | 'PERMANENTE';
    fechaContratoInicio?: string | null;
    fechaContratoFin?: string | null;
    gradoAcademico: 'DOCTOR' | 'DOCTORA' | 'LICENCIADO' | 'LICENCIADA';
}
export interface RegistroFisioterapeuta {
    ci: string;
    complemento: string | null;
    nombres: string;
    primerApellido: string | null;
    segundoApellido: string | null;
    sexo: 'M' | 'F';
    esExtranjero: boolean;
    fechaNacimiento: string;
    idEstadoCivil: number;
    idDeptExp: number;
    idDepartamentoNacimiento?: number;
    idNacimientoMunicipio: number | null;
    matricula: string;
    claveUnica: string;
    permanente: boolean;
    fechaContratoInicio: string | null;
    fechaContratoFin: string | null;
    gradoAcademico: 'DOCTOR' | 'DOCTORA' | 'LICENCIADO' | 'LICENCIADA';
    nombreUsuario: string;
    email: string;
    passwordHash: string;
    idUsuarioCreador: number;
}
export interface FisioterapeutaCreado {
    esExtranjero: boolean;
    idPersona: number;
    idEspecialista: number;
    idUsuario: string;
    nombreCompleto: string;
    email: string;
    matricula: string;
    foto: string;
    imagen: string;
}
export interface CatalogosFisioterapeuta {
    departamentos: { id: number; nombre: string }[];
    provincias: { id: number; nombre: string; idDepartamento: number | null }[];
    municipios: { id: number; nombre: string; idProvincia: number | null }[];
    estadosCiviles: { id: number; nombre: string }[];
    expedidos: { id: number; nombre: string; sigla: string }[];
}