export interface PacienteInstitucionDetalle {
  idInstitucion: number;
  nombre: string;
  tipoInstitucion: string;
  activo: boolean;
}

export class Paciente {
  constructor(
    public readonly idPersona: number,
    public readonly ci: string,
    public readonly matricula: string,
    public readonly nombreCompleto: string,
    public readonly fechaNacimiento: string,
    public readonly sexo: string,
    public readonly tipoAsegurado: string,
    public readonly estado: boolean,
    public readonly institucion: string,
    public readonly instituciones: PacienteInstitucionDetalle[] = [],
    public readonly primerApellido: string = '',
    public readonly segundoApellido: string = '',
    public readonly nombres: string = '',
    public readonly idTipoAsegurado: number | null = null,
  ) {}
}