import { Platform } from 'react-native';
import { api } from '../../../core/api/axios.config';
import { BandejaSoporteDto, CreateSolicitudDto } from '../types/soporte.types';
import { CrearSolicitudPayload } from '../types/soporte.types';

// 1. Crear una nueva solicitud (Usuario)
export const crearSolicitud = async (data: CreateSolicitudDto) => {
    const response = await api.post('/soportes/solicitud', data);
    return response.data;
};

// 2. Obtener la bandeja dinámica (depende del rol)
export const obtenerBandeja = async (params: any) => {
    const response = await api.get('/soportes/bandeja', { params });

    // Si esta línea no existe, o no devuelve response.data, 
    // la pantalla recibe 'undefined' y no muestra los tickets.
    return response.data;
};

export type CalificacionTecnico = 'MUY_BIEN' | 'BIEN' | 'MAL';

export const obtenerCalificacionSoporte = async (idSolicitud: number) => {
    const response = await api.get(`/soportes/solicitud/${idSolicitud}/calificacion`);
    return response.data?.data || null;
};

export const calificarTecnicoSoporte = async (idSolicitud: number, calificacion: CalificacionTecnico) => {
    const response = await api.post(`/soportes/solicitud/${idSolicitud}/calificacion`, { calificacion });
    return response.data?.data || null;
};

export interface CrearSoporteManualPayload {
    solicitante_nombre: string;
    oficina: string;
    piso?: string;
    consultorio?: string;
    descripcion: string;
    prioridad: 'BAJA' | 'MEDIA' | 'ALTA';
}

export const crearSoporteManual = async (payload: CrearSoporteManualPayload) => {
    const response = await api.post('/soportes/manual', payload);
    return response.data?.data;
};

export interface NotificacionSoporte {
    id: number;
    titulo: string;
    mensaje: string;
    datos: Record<string, string>;
    leida: boolean;
    creado_en: string;
}

export const obtenerNotificaciones = async (): Promise<NotificacionSoporte[]> => {
    const response = await api.get('/soportes/notificaciones');
    return Array.isArray(response.data?.data) ? response.data.data : [];
};

export const marcarNotificacionLeida = async (id: number) => {
    await api.patch(`/soportes/notificaciones/${id}/leida`);
};

export const marcarTodasNotificacionesLeidas = async () => {
    await api.patch('/soportes/notificaciones/leidas');
};

// 3. Re-solicitar o Insistir (Usuario)
export const resolicitarSoporte = async (idSolicitud: number, motivo?: string) => {
    const textoMotivo = motivo?.trim() || 'El usuario solicita atención inmediata';
    const payload = {
        motivo: textoMotivo,
        observacion: textoMotivo,
    };

    const response = await api.post(`/soportes/solicitud/${idSolicitud}/resolicitar`, payload);
    return response.data;
};

// 4. Asignar técnico (Operador) con nivel de prioridad
export const asignarSoporte = async (idSolicitud: number, idTecnico: number, prioridad: string = 'MEDIA', instruccionesTecnico?: string) => {
    const response = await api.post('/soportes/asignar', { idSolicitud, idTecnico, prioridad, instruccionesTecnico });
    return response.data;
};

// 5. Subir acta y finalizar (Técnico) - Compatible con Web y Móvil
export const finalizarSoporte = async (idAsignacion: number, uriArchivo: string, observacion: string) => {
    const formData = new FormData();

    const filename = uriArchivo.split('/').pop() || 'acta.jpg';
    const match = /\.(\w+)$/.exec(filename);
    const type = match ? `image/${match[1]}` : `image/jpeg`;

    // El backend en SoportesController espera @Body('observacion') y FileInterceptor('archivo')
    formData.append('observacion', observacion || '');

    if (Platform.OS === 'web') {
        const res = await fetch(uriArchivo);
        const blob = await res.blob();
        const fileNameWeb = filename.includes('.') ? filename : 'acta.jpg';
        formData.append('archivo', blob, fileNameWeb);
    } else {
        // @ts-ignore - Sintaxis de FormData en React Native móvil
        formData.append('archivo', { uri: uriArchivo, name: filename, type });
    }

    const response = await api.post(`/soportes/asignacion/${idAsignacion}/finalizar`, formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    });

    return response.data;
};

// 5B. Reasignar / Derivar soporte a otro técnico (Rol TÉCNICO)
export const reasignarSoporte = async (idAsignacion: number, idASoporte: number, justificacion: string) => {
    const response = await api.post(`/soportes/asignacion/${idAsignacion}/reasignar`, {
        id_a_soporte: Number(idASoporte),
        justificacion,
    });
    return response.data;
};
// Añade esta nueva función al final:
export const crearNuevaSolicitud = async (payload: CrearSolicitudPayload) => {
    // Utilizamos tu misma instancia de axios (api) que ya tiene el token JWT configurado
    const response = await api.post('/soportes/solicitud', payload);
    return response.data;
};

export const subirAdjuntoSolicitud = async (idSolicitud: number, uri: string) => {
    const formData = new FormData();
    const nombre = uri.split('/').pop()?.split('?')[0] || 'imagen-problema.jpg';
    const extension = nombre.split('.').pop()?.toLowerCase();
    const tipoMime = extension === 'png' ? 'image/png' : extension === 'webp' ? 'image/webp' : extension === 'heic' || extension === 'heif' ? `image/${extension}` : 'image/jpeg';
    if (Platform.OS === 'web') {
        const response = await fetch(uri);
        formData.append('archivo', await response.blob(), nombre);
    } else {
        // @ts-ignore Sintaxis de archivo de React Native
        formData.append('archivo', { uri, name: nombre, type: tipoMime });
    }
    const response = await api.post(`/soportes/solicitud/${idSolicitud}/adjunto`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
    });
    return response.data;
};
export const obtenerDetallesSolicitud = async (idSolicitud: number) => {
    try {
        const response = await api.get(`/soportes/solicitud/${idSolicitud}/items`);
        // Extraemos los datos dependiendo de cómo los devuelva tu backend
        return response.data?.data || response.data?.datos || response.data || [];
    } catch (error) {
        console.error('Error al obtener los detalles del ticket:', error);
        return [];
    }
};

export const obtenerHistorialTecnicos = async (idSolicitud: number) => {
    const response = await api.get(`/soportes/solicitud/${idSolicitud}/historial-tecnicos`);
    return response.data?.datos || [];
};
// Añade esto al final del archivo
export const agregarItemSoporte = async (idSolicitud: number, payload: { id_tipo_soporte: number; descripcion: string }) => {
    const response = await api.post(`/soportes/solicitud/${idSolicitud}/items`, payload);
    return response.data;
};
export const obtenerTecnicos = async () => {
    try {
        const response = await api.get('/soportes/tecnicos/disponibles');
        return response.data?.datos || [];
    } catch (error) {
        console.error('Error al obtener técnicos:', error);
        return [];
    }
};

// 6. Técnico pone el ticket EN PROCESO
export const ponerEnProcesoSoporte = async (idSolicitud: number) => {
    const response = await api.post(`/soportes/solicitud/${idSolicitud}/en-proceso`);
    return response.data;
};

export const registrarTecnicoEnCamino = async (idAsignacion: number, latitud: number, longitud: number) => {
    const response = await api.post(`/soportes/asignacion/${idAsignacion}/en-camino`, { latitud, longitud });
    return response.data?.data || response.data?.datos || response.data;
};

export const registrarLlegadaTecnico = async (idAsignacion: number, latitud: number, longitud: number) => {
    const response = await api.post(`/soportes/asignacion/${idAsignacion}/llegada`, { latitud, longitud });
    return response.data?.data || response.data?.datos || response.data;
};

// 7. Operador reasigna el ticket a otro técnico con justificación y prioridad
export const reasignarSoporteOperador = async (idSolicitud: number, idTecnico: number, justificacion: string, prioridad?: string, instruccionesTecnico?: string) => {
    const response = await api.post(`/soportes/solicitud/${idSolicitud}/reasignar-operador`, {
        idTecnico,
        justificacion,
        prioridad,
        instruccionesTecnico,
    });
    return response.data;
};

// 8. Obtener el archivo de acta adjunta de una solicitud (soporte_archivos_actas)
export const obtenerActaSolicitud = async (idSolicitud: number) => {
    try {
        const response = await api.get(`/soportes/solicitud/${idSolicitud}/acta`);
        return response.data?.datos || response.data?.data || null;
    } catch (error) {
        console.error('Error al obtener el acta de la solicitud:', error);
        return null;
    }
};

// 9. Construir la URL pública completa del archivo a partir de ruta_url (ej. /uploads/soportes/actas/...)
export const construirUrlArchivo = (rutaUrl?: string | null): string | null => {
    if (!rutaUrl) return null;
    if (rutaUrl.startsWith('http://') || rutaUrl.startsWith('https://')) {
        return rutaUrl;
    }
    const baseApiUrl = api.defaults.baseURL || '';
    const baseServerUrl = baseApiUrl.replace(/\/api\/?$/, '');
    const pathLimpio = rutaUrl.startsWith('/') ? rutaUrl : `/${rutaUrl}`;
    return `${baseServerUrl}${pathLimpio}`;
};

// =========================================================
// 10. GESTIÓN DE ROLES (ADMINISTRADOR)
// =========================================================

export const obtenerUsuariosConRoles = async () => {
    const response = await api.get('/soportes/roles/usuarios');
    return response.data?.datos || response.data?.data || response.data || [];
};

export const asignarRolUsuario = async (idUsuario: number, rolSoporte: 'USUARIO' | 'OPERADOR' | 'TECNICO' | 'ADMINISTRADOR') => {
    const response = await api.post('/soportes/roles/asignar', {
        id_usuario: Number(idUsuario),
        rol_soporte: rolSoporte,
    });
    return response.data;
};

export const quitarRolUsuario = async (idUsuario: number, rolSoporte?: string) => {
    const response = await api.patch(`/soportes/roles/${Number(idUsuario)}/quitar`, {
        rol_soporte: rolSoporte,
    });
    return response.data;
};

export const obtenerMisRoles = async () => {
    const response = await api.get('/soportes/mis-roles');
    return response.data;
};

// 11. Sedes y Consultorios en cascada
export const obtenerSedes = async () => {
    const response = await api.get('/sedes?pagina=1&limite=100');
    return response.data?.datos || [];
};

export const obtenerConsultoriosPorSede = async (idSede: number) => {
    const response = await api.get(`/soportes/sedes/${idSede}/consultorios`);
    return {
        datos: response.data?.datos || [],
        tiene_consultorios: !!response.data?.tiene_consultorios
    };
};

export const obtenerTiposSoporteConOpciones = async () => {
    const response = await api.get('/soportes/tipos-opciones');
    return response.data?.datos || [];
};

export const crearOpcionTipoSoporte = async (idTipo: number, nombre: string) => {
    const response = await api.post(`/soportes/tipos/${idTipo}/opciones`, { nombre });
    return response.data?.datos;
};

export const cambiarEstadoOpcionTipoSoporte = async (idOpcion: number, estado: boolean) => {
    const response = await api.patch(`/soportes/tipos/opciones/${idOpcion}/estado`, { estado });
    return response.data?.datos;
};

export interface GenerarReporteExcelParams {
    rol: 'OPERADOR' | 'TECNICO';
    fechaInicio?: string;
    fechaFin?: string;
    etiquetaFiltro?: string;
}

export const generarEnlaceReporteExcel = async (params: GenerarReporteExcelParams) => {
    const response = await api.post('/soportes/reporte-excel/enlace', params);
    const token = String(response.data?.token || '');
    const filename = String(response.data?.filename || 'reporte-soportes.xlsx');
    const total = Number(response.data?.total || 0);
    const baseURL = String(api.defaults.baseURL || '').replace(/\/$/, '');
    const url = `${baseURL}/soportes/reporte-excel/descargar?token=${encodeURIComponent(token)}`;
    return { token, filename, total, url };
};
