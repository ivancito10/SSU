import { CreateSolicitudDto } from '../../application/dtos/create-solicitud.dto';
import { AsignarSoporteDto } from '../../application/dtos/asignar-soporte.dto';
import { ReasignarSoporteDto } from '../../application/dtos/reasignar-soporte.dto';
import { FinalizarSoporteDto } from '../../application/dtos/finalizar-soporte.dto';
import { BandejaSoporteDto } from '../../application/dtos/bandeja-soporte.dto';
import { CreateSoporteManualDto } from '../../application/dtos/create-soporte-manual.dto';


export abstract class SoporteRepositoryPort {
  abstract crearSolicitudTransaccional(idUsuario: number, dto: CreateSolicitudDto): Promise<any>;

  abstract crearSoporteManual(idTecnico: number, dto: CreateSoporteManualDto): Promise<any>;

  abstract asignarSoporteTransaccional(idOperador: number, dto: AsignarSoporteDto): Promise<any>;


  abstract reasignarSoporteTransaccional(
    idAsignacion: number,
    idDeSoporte: number,
    dto: ReasignarSoporteDto,
    rolDerivacion: 'TECNICO' | 'OPERADOR'
  ): Promise<any>;



  abstract finalizarSoporteTransaccional(
    idAsignacion: number,
    idSoporte: number,
    dto: FinalizarSoporteDto
  ): Promise<any>;

  abstract obtenerMisSolicitudesUsuario(idUsuario: number, estadoFiltro?: string): Promise<any[]>;

  abstract obtenerBandejaOperador(estadoFiltro?: string): Promise<any[]>;

  abstract obtenerMisTareasTecnico(idSoporte: number, estadoFiltro?: string): Promise<any[]>;

  abstract resolicitarSoporte(idSolicitud: number, idUsuario: number, observacion?: string): Promise<any>;

  abstract obtenerBandejaAdministrador(estadoFiltro?: string): Promise<any[]>;

  abstract obtenerTokensOperadoresActivos(): Promise<string[]>;
  abstract obtenerIdsOperadoresActivos(): Promise<number[]>;

  // Añade esto junto a las otras funciones que ya tienes
  abstract obtenerItemsDeSolicitud(idSolicitud: number): Promise<any[]>;

  abstract obtenerHistorialTecnicosSolicitud(idSolicitud: number): Promise<any[]>;

  abstract agregarItemASolicitud(idSolicitud: number, idTipoSoporte: number, descripcion: string): Promise<any>;

  abstract obtenerTecnicosDisponibles(): Promise<any[]>;

  abstract ponerEnProcesoSoporte(idSolicitud: number, idSoporte: number): Promise<any>;

  abstract registrarTecnicoEnCamino(idAsignacion: number, idSoporte: number, latitud: number, longitud: number): Promise<any>;

  abstract registrarLlegadaTecnico(idAsignacion: number, idSoporte: number, latitud: number, longitud: number): Promise<any>;

  abstract reasignarPorOperador(idSolicitud: number, idNuevoTecnico: number, justificacion: string, prioridad?: string, idOperador?: number, instruccionesTecnico?: string): Promise<any>;

  // GESTIÓN DE ROLES (ADMINISTRADOR)
  abstract esUsuarioAdministrador(idUsuario: number): Promise<boolean>;

  abstract obtenerUsuariosConRoles(): Promise<any[]>;

  abstract asignarRolUsuario(idUsuario: number, rolSoporte: string): Promise<any>;

  abstract quitarRolUsuario(idUsuario: number, rolSoporte?: string): Promise<any>;

  abstract obtenerConsultoriosPorSede(idSede: number): Promise<any[]>;

  abstract obtenerTiposSoporteConOpciones(): Promise<any[]>;

  abstract crearOpcionTipoSoporte(idTipoSoporte: number, nombre: string): Promise<any>;

  abstract alternarEstadoOpcionTipoSoporte(idOpcion: number, estado: boolean): Promise<any>;

  abstract guardarAdjuntoSolicitud(idSolicitud: number, idUsuario: number, archivo: { nombre_archivo: string; ruta_url: string; tipo_mime: string }): Promise<any>;

  abstract obtenerCalificacionSolicitud(idSolicitud: number, idUsuario: number): Promise<any>;

  abstract calificarTecnico(idSolicitud: number, idUsuario: number, calificacion: 'MUY_BIEN' | 'BIEN' | 'MAL'): Promise<any>;

  abstract obtenerReporteSoportesConcluidos(
    rol: string,
    idUsuario: number,
    fechaInicio?: string,
    fechaFin?: string,
  ): Promise<any[]>;

  abstract obtenerNombreUsuarioPorId(idUsuario: number): Promise<string>;
}
