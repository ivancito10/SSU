// Reflejo exacto del enum en tu backend
export enum RolBandeja {
    USUARIO = 'USUARIO',
    OPERADOR = 'OPERADOR',
    TECNICO = 'TECNICO',
    ADMINISTRADOR = 'ADMINISTRADOR',
}

// DTO para enviar solicitudes nuevas
export interface CreateSolicitudDto {
    piso: string;
    oficina: string;
    descripcion: string;
}

// DTO para solicitar la bandeja
export interface BandejaSoporteDto {
    rol: RolBandeja;
    estado?: string;
}

// 1. Definimos cómo se ve un solo problema (ítem) reportado
export interface CrearSolicitudItemPayload {
    id_tipo_soporte: number;
    descripcion?: string; // Opcional, por si el usuario quiere dar más detalles
}

// 2. Definimos la cabecera completa de la solicitud
export interface CrearSolicitudPayload {
    piso?: string;
    oficina?: string;
    consultorio?: string;
    items: CrearSolicitudItemPayload[];
}

// Estructura del ticket que devuelve la base de datos (según tus consultas SQL)
export interface TicketSoporte {
    id?: number;
    id_solicitud: number;
    codigo_ticket?: string;
    id_asignacion?: number; // Viene en la bandeja de técnico/administrador
    instrucciones_tecnico?: string | null;
    fecha_solicitud?: string;
    fecha_asignacion?: string;
    fecha_primera_asignacion?: string;
    segundos_hasta_asignacion?: number | null;
    segundos_sin_asignar?: number | null;
    piso: string;
    oficina: string;
    consultorio?: string;
    estado: string;
    es_manual?: boolean;
    solicitante_manual?: string | null;
    nombre_usuario?: string;
    username?: string;
    ci_usuario?: string;

    // Alertas (Operador)
    cantidad_resolicitudes?: number;
    ultima_resolicitud?: string;
    ultima_resolicitud_motivo?: string;
    resolicitudes_historial?: Array<{
        id: number;
        motivo?: string | null;
        fecha?: string | null;
    }>;

    // Métricas (Administrador)
    minutos_atencion?: number;
    cantidad_reasignaciones?: number;

    // PROPIEDADES PARA EL OPERADOR Y TÉCNICO
    id_tecnico?: number;
    prioridad?: string;
    fecha_inicio?: string;
    fecha_finalizacion?: string;
    fecha_en_camino?: string | null;
    latitud_en_camino?: number | null;
    longitud_en_camino?: number | null;
    fecha_llegada?: string | null;
    latitud_llegada?: number | null;
    longitud_llegada?: number | null;
    segundos_desde_asignacion?: number;
    segundos_desde_inicio?: number;
    tiempo_resolucion?: string;
    diagnostico?: string;
    url_acta?: string;
    nombre_archivo_acta?: string;
    tipo_mime_acta?: string;
    nombre_tecnico?: string;
    calificacion_usuario?: 'MUY_BIEN' | 'BIEN' | 'MAL' | null;
    fecha_calificacion_usuario?: string | null;
    ultima_reasignacion_motivo?: string;
}
