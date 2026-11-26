import React, { useState, useEffect, useRef } from 'react';
import {
    View,
    Text,
    StyleSheet,
    TextInput,
    TouchableOpacity,
    Alert,
    ActivityIndicator,
    ScrollView,
    Modal,
    Image,
    Linking,
    Platform,
    Keyboard,
    KeyboardAvoidingView,
    useWindowDimensions,
} from 'react-native';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import * as ImagePicker from 'expo-image-picker';
import * as Location from 'expo-location';
import { File as ExpoFile, Paths as ExpoPaths } from 'expo-file-system';
import * as MediaLibrary from 'expo-media-library';
import { theme } from '../../../core/theme/theme';
import { AppIcon, NombreIcono } from '../../../core/components/AppIcon';
import { AppAlert } from '../../../core/components/AlertaGlobalModal';
import { RolBandeja, TicketSoporte } from '../types/soporte.types';
import {
    resolicitarSoporte,
    asignarSoporte,
    reasignarSoporte,
    finalizarSoporte,
    obtenerDetallesSolicitud,
    agregarItemSoporte,
    obtenerTecnicos,
    ponerEnProcesoSoporte,
    registrarTecnicoEnCamino,
    registrarLlegadaTecnico,
    reasignarSoporteOperador,
    obtenerActaSolicitud,
    construirUrlArchivo,
    obtenerBandeja,
    obtenerHistorialTecnicos,
    obtenerCalificacionSoporte,
    calificarTecnicoSoporte,
    CalificacionTecnico,
} from '../api/soporte.service';

// Opciones de tipos de soporte para el modal de añadir problemas
const TIPOS_SOPORTE: Array<{ id: number; icono: NombreIcono; titulo: string }> = [
    { id: 1, icono: 'laptop', titulo: 'Problema de Hardware' },
    { id: 2, icono: 'gear', titulo: 'Sistemas Institucionales' },
    { id: 3, icono: 'document-text', titulo: 'Programas y ofimáticas' },
    { id: 4, icono: 'globe', titulo: 'Redes e Internet' },
];

// Niveles de prioridad seleccionables
const NIVELES_PRIORIDAD = [
    { valor: 'ALTA', etiqueta: 'Alta', dot: '#e74c3c', color: '#c0392b', bg: '#fdecea' },
    { valor: 'MEDIA', etiqueta: 'Media', dot: '#e67e22', color: '#d35400', bg: '#fef5e7' },
    { valor: 'BAJA', etiqueta: 'Baja', dot: '#3498db', color: '#2980b9', bg: '#ebf5fb' },
];

const obtenerEstiloEstado = (estado?: string) => {
    switch (estado) {
        case 'FINALIZADA':
            return { texto: 'FINALIZADA', color: '#15803d', bg: '#dcfce7', border: '#bbf7d0' };
        case 'EN_PROCESO':
            return { texto: 'EN PROCESO', color: '#6d28d9', bg: '#f3e8ff', border: '#e9d5ff' };
        case 'ASIGNADA':
            return { texto: 'ASIGNADA', color: '#1d4ed8', bg: '#dbeafe', border: '#bfdbfe' };
        case 'PENDIENTE':
        default:
            return { texto: 'PENDIENTE', color: '#b45309', bg: '#fef3c7', border: '#fde68a' };
    }
};

const obtenerInfoPrioridad = (prioridad?: string) => {
    const p = (prioridad || '').toUpperCase();
    if (p === 'URGENTE' || p === 'ALTA') return { texto: 'ALTA', dot: '#e74c3c', color: '#c0392b', bg: '#fdecea' };
    if (p === 'NORMAL' || p === 'MEDIA') return { texto: 'MEDIA', dot: '#e67e22', color: '#d35400', bg: '#fef5e7' };
    if (p === 'BAJA') return { texto: 'BAJA', dot: '#3498db', color: '#2980b9', bg: '#ebf5fb' };
    return null;
};

const formatearFechaHora = (fechaIso?: string) => {
    if (!fechaIso) return 'No registrada';
    const d = new Date(fechaIso);
    if (isNaN(d.getTime())) return 'No registrada';
    const fecha = d.toLocaleDateString('es-BO', { day: '2-digit', month: '2-digit', year: 'numeric' });
    const hora = d.toLocaleTimeString('es-BO', { hour: '2-digit', minute: '2-digit' });
    return `${fecha}  •  ${hora}`;
};

const obtenerInfoTipoSoporte = (idTipo: number | string): { icono: NombreIcono; nombre: string } => {
    const id = Number(idTipo);
    if (id === 1) return { icono: 'laptop', nombre: 'Problema de Hardware' };
    if (id === 2) return { icono: 'gear', nombre: 'Sistemas Institucionales' };
    if (id === 3) return { icono: 'document-text', nombre: 'Programas y ofimáticas' };
    if (id === 4) return { icono: 'globe', nombre: 'Redes e Internet' };
    return { icono: 'wrench', nombre: 'Otro' };
};

const formatearSegundos = (totalSegundos: number) => {
    const segs = Math.max(0, Math.floor(totalSegundos));
    const horas = Math.floor(segs / 3600);
    const minutos = Math.floor((segs % 3600) / 60);
    const segundos = segs % 60;

    if (horas > 0) {
        return `${horas}h ${minutos}m ${segundos}s`;
    }
    return `${minutos}m ${segundos}s`;
};

export const TicketDetalleScreen = ({ route, navigation }: any) => {
    const { width } = useWindowDimensions();
    const insets = useSafeAreaInsets();
    const esEscritorio = Platform.OS === 'web' && width > 768;

    const { ticket: ticketInicial, rolUsuario } = route.params as { ticket: TicketSoporte; rolUsuario: RolBandeja };
    const scrollViewRef = useRef<React.ElementRef<typeof ScrollView>>(null);
    const [ticket, setTicket] = useState<TicketSoporte>(ticketInicial);
    const idReal = Number(ticket.id || ticket.id_solicitud);
    const fechaSolicitudTicket = ticket.fecha_solicitud || (ticket as any).creado_en || (ticket as any).fecha_creacion || (ticket as any).createdAt;

    const [estadoActual, setEstadoActual] = useState<string>(ticket.estado);

    const [cargando, setCargando] = useState(false);
    const [cargandoItems, setCargandoItems] = useState(true);
    const [itemsProblema, setItemsProblema] = useState<any[]>([]);
    const [calificacionGuardada, setCalificacionGuardada] = useState<any | null>(null);
    const [calificacionSeleccionada, setCalificacionSeleccionada] = useState<CalificacionTecnico | null>(null);
    const [cargandoCalificacion, setCargandoCalificacion] = useState(false);
    const [guardandoCalificacion, setGuardandoCalificacion] = useState(false);

    const [observacion, setObservacion] = useState('');
    const [justificacionReasignacion, setJustificacionReasignacion] = useState('');
    const [instruccionesTecnico, setInstruccionesTecnico] = useState('');
    const [diagnostico, setDiagnostico] = useState('');

    const [tecnicos, setTecnicos] = useState<any[]>([]);
    const [idTecnico, setIdTecnico] = useState<number | null>(null);
    const [prioridad, setPrioridad] = useState<string>(
        ticketInicial.prioridad?.toUpperCase() === 'NORMAL' ? 'MEDIA' : ticketInicial.prioridad?.toUpperCase() === 'URGENTE' ? 'ALTA' : ticketInicial.prioridad?.toUpperCase() || 'MEDIA'
    );

    // Modal añadir otro problema
    const [modalVisible, setModalVisible] = useState(false);
    const [nuevoTipoSoporte, setNuevoTipoSoporte] = useState<number>(1);
    const [nuevaDescripcion, setNuevaDescripcion] = useState('');
    const [guardandoItem, setGuardandoItem] = useState(false);

    // Estados del Técnico (Cronómetros, Derivación, Acta)
    const [tiempoDesdeAsignacion, setTiempoDesdeAsignacion] = useState<string>('Calculando...');
    const [tiempoEnProceso, setTiempoEnProceso] = useState<string>('No iniciado');
    const [tiempoEsperaAsignacion, setTiempoEsperaAsignacion] = useState<string>('Calculando...');
    const [baseSegundosInicio, setBaseSegundosInicio] = useState<number | null>(
        ticket.segundos_desde_inicio !== undefined && ticket.segundos_desde_inicio !== null
            ? Number(ticket.segundos_desde_inicio)
            : null
    );
    const [timestampInicioLocal, setTimestampInicioLocal] = useState<number>(() => Date.now());
    const [modalReasignarVisible, setModalReasignarVisible] = useState(false);
    const [modalHistorialTecnicosVisible, setModalHistorialTecnicosVisible] = useState(false);
    const [historialTecnicos, setHistorialTecnicos] = useState<any[]>([]);
    const [idNuevoTecnico, setIdNuevoTecnico] = useState<number | null>(null);
    const [motivoDerivacion, setMotivoDerivacion] = useState('');
    const [guardandoReasignacion, setGuardandoReasignacion] = useState(false);
    const [registrandoTrayecto, setRegistrandoTrayecto] = useState(false);
    const [ahoraTrayecto, setAhoraTrayecto] = useState(Date.now());
    const [archivoActaUri, setArchivoActaUri] = useState<string | null>(null);
    const [nombreArchivoActa, setNombreArchivoActa] = useState<string>('');

    // Estado para ver el acta subida
    const [rutaActaGuardada, setRutaActaGuardada] = useState<string | null>(ticket.url_acta || null);
    const [nombreActaGuardada, setNombreActaGuardada] = useState<string>(ticket.nombre_archivo_acta || '');
    const [tipoMimeActaGuardada, setTipoMimeActaGuardada] = useState<string>(ticket.tipo_mime_acta || '');
    const [modalVerActaVisible, setModalVerActaVisible] = useState<boolean>(false);
    const [imagenAdjuntaModal, setImagenAdjuntaModal] = useState<{ url: string; nombre: string } | null>(null);
    const [descargandoArchivo, setDescargandoArchivo] = useState<boolean>(false);

    // Detección de teclado para ocultar ondas inferiores
    const [tecladoVisible, setTecladoVisible] = useState(false);

    useEffect(() => {
        const showSub = Keyboard.addListener('keyboardDidShow', () => setTecladoVisible(true));
        const hideSub = Keyboard.addListener('keyboardDidHide', () => setTecladoVisible(false));
        return () => {
            showSub.remove();
            hideSub.remove();
        };
    }, []);

    useEffect(() => {
        if (!ticket.fecha_en_camino || ticket.fecha_llegada) return;
        const intervalo = setInterval(() => setAhoraTrayecto(Date.now()), 1000);
        return () => clearInterval(intervalo);
    }, [ticket.fecha_en_camino, ticket.fecha_llegada]);

    // Carga de ítems y sincronización en tiempo real
    useEffect(() => {
        const cargarDatos = async () => {
            if (idReal) {
                const items = await obtenerDetallesSolicitud(idReal);
                setItemsProblema(Array.isArray(items) ? items : []);

                if (rolUsuario === RolBandeja.OPERADOR || rolUsuario === RolBandeja.TECNICO) {
                    try {
                        const historial = await obtenerHistorialTecnicos(idReal);
                        setHistorialTecnicos(Array.isArray(historial) ? historial : []);
                    } catch (error) {
                        console.error('Error al cargar el historial de técnicos:', error);
                    }
                }

                if (estadoActual === 'FINALIZADA') {
                    const acta = await obtenerActaSolicitud(idReal);
                    if (acta?.ruta_url) {
                        setRutaActaGuardada(acta.ruta_url);
                        setNombreActaGuardada(acta.nombre_archivo || '');
                        setTipoMimeActaGuardada(acta.tipo_mime || '');
                    }
                }
            }
            if (rolUsuario === RolBandeja.OPERADOR || rolUsuario === RolBandeja.TECNICO) {
                const listaTecnicos = await obtenerTecnicos();
                setTecnicos(Array.isArray(listaTecnicos) ? listaTecnicos : []);
            }
            setCargandoItems(false);
        };
        cargarDatos();

        const intervaloSync = setInterval(async () => {
            try {
                if (!idReal) return;
                const itemsActualizados = await obtenerDetallesSolicitud(idReal);
                if (Array.isArray(itemsActualizados)) {
                    setItemsProblema(itemsActualizados);
                }

                const respuestaBandeja = await obtenerBandeja({ rol: rolUsuario });
                const lista = respuestaBandeja?.data || respuestaBandeja?.datos || respuestaBandeja || [];
                if (Array.isArray(lista)) {
                    const encontrado = lista.find(
                        (t: any) => Number(t.id_solicitud || t.id) === Number(idReal)
                    );
                    if (encontrado) {
                        setTicket((prev) => ({ ...prev, ...encontrado }));
                        if (encontrado.estado && encontrado.estado !== estadoActual) {
                            setEstadoActual(encontrado.estado);
                        }
                        if (
                            encontrado.segundos_desde_inicio !== undefined &&
                            encontrado.segundos_desde_inicio !== null &&
                            baseSegundosInicio === null
                        ) {
                            setTimestampInicioLocal(Date.now());
                            setBaseSegundosInicio(Number(encontrado.segundos_desde_inicio));
                        }
                        if (encontrado.url_acta) {
                            setRutaActaGuardada(encontrado.url_acta);
                        }
                    }
                }
            } catch (_e) {}
        }, 4000);

        return () => clearInterval(intervaloSync);
    }, [idReal, rolUsuario, estadoActual, baseSegundosInicio]);

    useEffect(() => {
        let activo = true;
        if (rolUsuario !== RolBandeja.USUARIO || estadoActual !== 'FINALIZADA' || !idReal) return;
        setCargandoCalificacion(true);
        obtenerCalificacionSoporte(idReal)
            .then((datos) => {
                if (!activo) return;
                setCalificacionGuardada(datos?.calificacion ? datos : null);
                setCalificacionSeleccionada(datos?.calificacion || null);
            })
            .catch((error) => console.error('No se pudo cargar la calificación:', error))
            .finally(() => { if (activo) setCargandoCalificacion(false); });
        return () => { activo = false; };
    }, [idReal, rolUsuario, estadoActual]);

    const seleccionarCalificacion = async (calificacion: CalificacionTecnico) => {
        if (calificacionGuardada || guardandoCalificacion) return;
        setCalificacionSeleccionada(calificacion);
        setGuardandoCalificacion(true);
        try {
            const guardada = await calificarTecnicoSoporte(idReal, calificacion);
            setCalificacionGuardada(guardada || { calificacion });
            AppAlert.alert('¡Gracias por tu calificación!', 'Tu calificación quedó registrada correctamente.');
            (navigation as any).navigate('Main', {
                filtroInicial: 'FINALIZADAS',
                filtroTs: Date.now(),
            });
        } catch (error: any) {
            setCalificacionSeleccionada(null);
            AppAlert.alert('No se pudo calificar', error?.response?.data?.message || 'Intenta nuevamente más tarde.');
        } finally {
            setGuardandoCalificacion(false);
        }
    };

    // 2 Cronómetros en tiempo real
    useEffect(() => {
        const montoPantallaMs = Date.now();
        const fechaSolicitudMs = fechaSolicitudTicket ? new Date(fechaSolicitudTicket).getTime() : NaN;
        const fechaPrimeraAsignacionMs = ticket.fecha_primera_asignacion || ticket.fecha_asignacion
            ? new Date(ticket.fecha_primera_asignacion || ticket.fecha_asignacion || '').getTime()
            : NaN;
        const segSinAsignarServidor = ticket.segundos_sin_asignar !== undefined && ticket.segundos_sin_asignar !== null
            ? Math.max(0, Number(ticket.segundos_sin_asignar))
            : null;
        const segundosEsperaCerrada = ticket.segundos_hasta_asignacion !== undefined && ticket.segundos_hasta_asignacion !== null
            ? Number(ticket.segundos_hasta_asignacion)
            : Number.isFinite(fechaSolicitudMs) && Number.isFinite(fechaPrimeraAsignacionMs)
                ? Math.max(0, Math.floor((fechaPrimeraAsignacionMs - fechaSolicitudMs) / 1000))
                : null;
        const fechaAsignacionMs = ticket.fecha_asignacion ? new Date(ticket.fecha_asignacion).getTime() : NaN;
        const fechaInicioMs = ticket.fecha_inicio ? new Date(ticket.fecha_inicio).getTime() : NaN;
        const segAsignacionServidor = ticket.segundos_desde_asignacion !== undefined && ticket.segundos_desde_asignacion !== null
            ? Math.max(0, Number(ticket.segundos_desde_asignacion))
            : null;
        const fechaFinAsignacionMs = estadoActual === 'ASIGNADA'
            ? Date.now()
            : estadoActual === 'EN_PROCESO' && Number.isFinite(fechaInicioMs)
                ? fechaInicioMs
                : estadoActual === 'FINALIZADA' && ticket.fecha_finalizacion
                    ? new Date(ticket.fecha_finalizacion).getTime()
                    : NaN;
        const segAsignacionPorFecha = Number.isFinite(fechaAsignacionMs) && Number.isFinite(fechaFinAsignacionMs)
            ? Math.max(0, Math.floor((fechaFinAsignacionMs - fechaAsignacionMs) / 1000))
            : null;
        const segAsignacionBase = segAsignacionServidor !== null && segAsignacionPorFecha !== null
            ? Math.max(segAsignacionServidor, segAsignacionPorFecha)
            : segAsignacionServidor ?? segAsignacionPorFecha;

        const actualizarEsperaAsignacion = (ahora: number, deltaSegundos = 0) => {
            if (segundosEsperaCerrada !== null) {
                setTiempoEsperaAsignacion(formatearSegundos(segundosEsperaCerrada));
            } else if (estadoActual === 'PENDIENTE') {
                const baseEspera = segSinAsignarServidor !== null
                    ? segSinAsignarServidor
                    : (Number.isFinite(fechaSolicitudMs)
                        ? Math.max(0, Math.floor((montoPantallaMs - fechaSolicitudMs) / 1000))
                        : 0);
                setTiempoEsperaAsignacion(formatearSegundos(baseEspera + deltaSegundos));
            } else {
                setTiempoEsperaAsignacion('No disponible');
            }
        };

        if (estadoActual === 'FINALIZADA') {
            actualizarEsperaAsignacion(Date.now());
            if (segAsignacionBase !== null) {
                setTiempoDesdeAsignacion(formatearSegundos(segAsignacionBase));
            } else if (ticket.tiempo_resolucion) {
                setTiempoDesdeAsignacion(ticket.tiempo_resolucion);
            }
            if (baseSegundosInicio !== null) {
                setTiempoEnProceso(formatearSegundos(baseSegundosInicio));
            }
            return;
        }

        const actualizarRelojes = () => {
            const ahora = Date.now();
            const deltaSegundos = Math.max(0, Math.floor((ahora - montoPantallaMs) / 1000));
            actualizarEsperaAsignacion(ahora, deltaSegundos);

            if (segAsignacionBase !== null) {
                setTiempoDesdeAsignacion(formatearSegundos(segAsignacionBase + (estadoActual === 'ASIGNADA' ? deltaSegundos : 0)));
            } else {
                setTiempoDesdeAsignacion('Sin asignación');
            }

            if (baseSegundosInicio !== null && estadoActual === 'EN_PROCESO') {
                const deltaInicio = Math.floor((ahora - timestampInicioLocal) / 1000);
                setTiempoEnProceso(formatearSegundos(baseSegundosInicio + deltaInicio));
            } else if (ticket.fecha_inicio && estadoActual === 'EN_PROCESO') {
                const segs = Math.max(0, Math.floor((ahora - new Date(ticket.fecha_inicio).getTime()) / 1000));
                setTiempoEnProceso(formatearSegundos(segs));
            } else if (baseSegundosInicio !== null) {
                setTiempoEnProceso(formatearSegundos(baseSegundosInicio));
            } else {
                setTiempoEnProceso('Aún no iniciado');
            }
        };

        actualizarRelojes();
        const intervalo = setInterval(actualizarRelojes, 1000);
        return () => clearInterval(intervalo);
    }, [fechaSolicitudTicket, ticket.segundos_sin_asignar, ticket.fecha_primera_asignacion, ticket.segundos_hasta_asignacion, ticket.fecha_asignacion, ticket.fecha_inicio, ticket.fecha_finalizacion, ticket.segundos_desde_asignacion, estadoActual, baseSegundosInicio, timestampInicioLocal]);

    // Acciones
    const handleAgregarItem = async () => {
        if (!nuevaDescripcion.trim()) {
            return AppAlert.alert('Error', 'Debes describir el problema.');
        }

        setGuardandoItem(true);
        try {
            await agregarItemSoporte(idReal, {
                id_tipo_soporte: nuevoTipoSoporte,
                descripcion: nuevaDescripcion.trim(),
            });

            AppAlert.alert('Éxito', 'Se añadió el problema a tu ticket.');
            setModalVisible(false);
            setNuevaDescripcion('');

            setCargandoItems(true);
            const itemsRefresh = await obtenerDetallesSolicitud(idReal);
            setItemsProblema(Array.isArray(itemsRefresh) ? itemsRefresh : []);
            setCargandoItems(false);
        } catch (_error) {
            AppAlert.alert('Error', 'No se pudo añadir el problema.');
        } finally {
            setGuardandoItem(false);
        }
    };

    const handleResolicitar = async () => {
        setCargando(true);
        try {
            await resolicitarSoporte(idReal, observacion);
            setObservacion('');
            AppAlert.alert('Éxito', 'Tu solicitud de insistencia ha sido enviada.');
            navigation.goBack();
        } catch (error: any) {
            console.error('Error al resolicitar soporte:', error);
            AppAlert.alert('Error', 'No se pudo enviar la solicitud de insistencia.');
        } finally {
            setCargando(false);
        }
    };

    const handleAsignar = async () => {
        if (!idTecnico) {
            AppAlert.alert('Atención', 'Por favor selecciona un técnico disponible.');
            return;
        }

        setCargando(true);
        try {
            await asignarSoporte(Number(idReal), Number(idTecnico), prioridad, instruccionesTecnico.trim() || undefined);
            AppAlert.alert('Éxito', `El ticket ha sido asignado al técnico con prioridad ${prioridad}.`);
            (navigation as any).navigate('Main', {
                filtroInicial: 'ASIGNADAS',
                filtroTs: Date.now(),
            });
        } catch (error: any) {
            console.error('Error al asignar técnico:', error);
            const msg = error?.response?.data?.message || 'No se pudo asignar el técnico al ticket.';
            AppAlert.alert('No se pudo asignar', msg);
            if (error?.response?.status === 409) {
                navigation.goBack();
            }
        } finally {
            setCargando(false);
        }
    };

    const handleReasignarOperador = async () => {
        if (!idTecnico) {
            AppAlert.alert('Atención', 'Por favor selecciona al nuevo técnico.');
            return;
        }
        if (!justificacionReasignacion.trim()) {
            AppAlert.alert('Atención', 'Debes escribir el motivo por el cual estás reasignando.');
            return;
        }

        setCargando(true);
        try {
            await reasignarSoporteOperador(Number(idReal), Number(idTecnico), justificacionReasignacion.trim(), prioridad, instruccionesTecnico.trim() || undefined);
            AppAlert.alert('Éxito', 'El ticket fue reasignado al nuevo técnico correctamente.');
            (navigation as any).navigate('Main', {
                filtroInicial: 'ASIGNADAS',
                filtroTs: Date.now(),
            });
        } catch (error: any) {
            console.error('Error al reasignar técnico:', error);
            const msg = error?.response?.data?.message || 'No se pudo reasignar el técnico.';
            AppAlert.alert('Error', msg);
        } finally {
            setCargando(false);
        }
    };

    const handlePonerEnProceso = async () => {
        setCargando(true);
        try {
            await ponerEnProcesoSoporte(Number(idReal));
            setEstadoActual('EN_PROCESO');
            setTimestampInicioLocal(Date.now());
            setBaseSegundosInicio(0);

            // Registrar ubicación GPS en segundo plano al iniciar la atención
            if (ticket.id_asignacion && !ticket.fecha_en_camino) {
                try {
                    const coordenadas = await capturarUbicacionActual();
                    const datos = await registrarTecnicoEnCamino(
                        Number(ticket.id_asignacion),
                        coordenadas.latitud,
                        coordenadas.longitud,
                    );
                    setTicket((actual) => ({ ...actual, ...datos, estado: 'EN_PROCESO' }));
                } catch (errUbicacion: any) {
                    console.warn('No se pudo registrar la ubicación al iniciar atención:', errUbicacion?.message || errUbicacion);
                }
            }

            AppAlert.alert('Atención iniciada', 'Has iniciado la atención del soporte.');
            (navigation as any).navigate('Main', {
                filtroInicial: 'EN_PROCESO',
                filtroTs: Date.now(),
            });
        } catch (error: any) {
            console.error('Error al poner en proceso:', error);
            AppAlert.alert('Error', 'No se pudo iniciar la atención del soporte.');
        } finally {
            setCargando(false);
        }
    };

    const capturarUbicacionActual = async () => {
        const permiso = await Location.requestForegroundPermissionsAsync();
        if (permiso.status !== 'granted') {
            throw new Error('Activa el permiso de ubicación para registrar el trayecto.');
        }
        try {
            const ubicacion = await Promise.race([
                Location.getCurrentPositionAsync({ accuracy: Location.Accuracy.Balanced }),
                new Promise<never>((_, reject) =>
                    setTimeout(() => reject(new Error('Tiempo de espera agotado al obtener GPS.')), 6000),
                ),
            ]);
            return {
                latitud: ubicacion.coords.latitude,
                longitud: ubicacion.coords.longitude,
            };
        } catch {
            // Respaldo rápido en interiores: última ubicación conocida del dispositivo
            const ultima = await Location.getLastKnownPositionAsync();
            if (ultima?.coords) {
                return {
                    latitud: ultima.coords.latitude,
                    longitud: ultima.coords.longitude,
                };
            }
            throw new Error('No se pudo obtener la ubicación GPS actual.');
        }
    };

    const handleRegistrarEnCamino = async () => {
        if (!ticket.id_asignacion) {
            AppAlert.alert('Sin asignación', 'No se encontró la asignación activa de este técnico.');
            return;
        }
        setRegistrandoTrayecto(true);
        try {
            const coordenadas = await capturarUbicacionActual();
            const datos = await registrarTecnicoEnCamino(Number(ticket.id_asignacion), coordenadas.latitud, coordenadas.longitud);
            setTicket((actual) => ({ ...actual, ...datos }));
            AppAlert.alert('Trayecto iniciado', 'Se registró la hora y ubicación GPS de salida.');
        } catch (error: any) {
            AppAlert.alert('No se pudo registrar', error?.response?.data?.message || error?.message || 'No se pudo obtener la ubicación actual.');
        } finally {
            setRegistrandoTrayecto(false);
        }
    };

    const handleRegistrarLlegada = async () => {
        if (!ticket.id_asignacion) {
            AppAlert.alert('Sin asignación', 'No se encontró la asignación activa de este técnico.');
            return;
        }
        setRegistrandoTrayecto(true);
        try {
            const coordenadas = await capturarUbicacionActual();
            const datos = await registrarLlegadaTecnico(Number(ticket.id_asignacion), coordenadas.latitud, coordenadas.longitud);
            setTicket((actual) => ({ ...actual, ...datos }));
            AppAlert.alert('Llegada registrada', 'Se guardó la hora y ubicación GPS de llegada.');
        } catch (error: any) {
            AppAlert.alert('No se pudo registrar', error?.response?.data?.message || error?.message || 'No se pudo obtener la ubicación actual.');
        } finally {
            setRegistrandoTrayecto(false);
        }
    };

    const abrirMapa = (latitud?: number | null, longitud?: number | null) => {
        if (latitud === null || longitud === null || latitud === undefined || longitud === undefined) return;
        const url = `https://www.google.com/maps/search/?api=1&query=${latitud},${longitud}`;
        Linking.openURL(url).catch(() => AppAlert.alert('No se pudo abrir el mapa', 'Verifica que el dispositivo tenga una aplicación de mapas disponible.'));
    };

    const descargarArchivoDirecto = async (url?: string | null, nombreArchivo?: string | null) => {
        if (!url || descargandoArchivo) return;
        setDescargandoArchivo(true);
        try {
            const nombreDesdeUrl = url.split('?')[0].split('/').pop() || 'imagen-soporte.jpg';
            const nombreBase = (nombreArchivo || nombreDesdeUrl).trim() || 'imagen-soporte.jpg';
            const nombreLimpio = nombreBase.replace(/[<>:"/\\|?*]+/g, '_');

            if (Platform.OS === 'web' && typeof window !== 'undefined' && typeof document !== 'undefined') {
                const respuesta = await fetch(url);
                if (!respuesta.ok) {
                    throw new Error('No se pudo obtener el archivo desde el servidor.');
                }
                const blob = await respuesta.blob();
                const blobUrl = window.URL.createObjectURL(blob);
                const enlace = document.createElement('a');
                enlace.href = blobUrl;
                enlace.download = nombreLimpio;
                document.body.appendChild(enlace);
                enlace.click();
                document.body.removeChild(enlace);
                setTimeout(() => window.URL.revokeObjectURL(blobUrl), 1000);
                return;
            }

            const permiso = await MediaLibrary.requestPermissionsAsync(true);
            if (!permiso.granted) {
                AppAlert.alert('Permiso requerido', 'Permite el acceso a la galería para guardar la imagen.');
                return;
            }

            const destino = new ExpoFile(ExpoPaths.cache, `${Date.now()}_${nombreLimpio}`);
            const archivoDescargado = await ExpoFile.downloadFileAsync(url, destino, { idempotent: true });
            await MediaLibrary.saveToLibraryAsync(archivoDescargado.uri);
            AppAlert.alert('Descarga completada', 'La imagen se guardó en tu galería.');
        } catch (error: any) {
            console.error('Error al descargar archivo:', error);
            AppAlert.alert('Error al descargar', error?.message || 'No se pudo descargar el archivo.');
        } finally {
            setDescargandoArchivo(false);
        }
    };

    const handleDerivarTecnico = async () => {
        if (!idNuevoTecnico) {
            AppAlert.alert('Atención', 'Debes seleccionar al técnico al que deseas derivar el soporte.');
            return;
        }
        if (!motivoDerivacion.trim()) {
            AppAlert.alert('Atención', 'La justificación de la derivación es obligatoria.');
            return;
        }
        const idAsignacion = ticket.id_asignacion;
        if (!idAsignacion) {
            AppAlert.alert('Error', 'No se encontró el identificador de asignación.');
            return;
        }

        setGuardandoReasignacion(true);
        try {
            // Detener y registrar ubicación final si el técnico ya había iniciado atención antes de derivar
            if (ticket.fecha_en_camino && !ticket.fecha_llegada) {
                try {
                    const coordenadas = await capturarUbicacionActual();
                    await registrarLlegadaTecnico(Number(idAsignacion), coordenadas.latitud, coordenadas.longitud);
                } catch (errUbicacion: any) {
                    console.warn('No se pudo registrar ubicación antes de derivar:', errUbicacion?.message || errUbicacion);
                }
            }
            await reasignarSoporte(Number(idAsignacion), Number(idNuevoTecnico), motivoDerivacion.trim());
            setModalReasignarVisible(false);
            setMotivoDerivacion('');
            AppAlert.alert('Soporte Derivado', 'El soporte ha sido derivado exitosamente.');
            (navigation as any).navigate('Main', {
                filtroInicial: 'DERIVADAS',
                filtroTs: Date.now(),
            });
        } catch (error: any) {
            console.error('Error al derivar soporte:', error);
            const msg = error?.response?.data?.message || 'No se pudo derivar el soporte.';
            AppAlert.alert('Error', msg);
        } finally {
            setGuardandoReasignacion(false);
        }
    };

    const handleSeleccionarActa = async (usarCamara: boolean) => {
        try {
            if (usarCamara) {
                const permiso = await ImagePicker.requestCameraPermissionsAsync();
                if (!permiso.granted) {
                    AppAlert.alert('Permiso requerido', 'Permite el acceso a la cámara para tomar la foto del acta.');
                    return;
                }
            } else {
                const permiso = await ImagePicker.requestMediaLibraryPermissionsAsync();
                if (!permiso.granted) {
                    AppAlert.alert('Permiso requerido', 'Permite el acceso a la galería para seleccionar la foto del acta.');
                    return;
                }
            }
            const result = usarCamara
                ? await ImagePicker.launchCameraAsync({ mediaTypes: ImagePicker.MediaTypeOptions.Images, allowsEditing: false, quality: 0.8 })
                : await ImagePicker.launchImageLibraryAsync({ mediaTypes: ImagePicker.MediaTypeOptions.Images, allowsEditing: false, quality: 0.8 });

            if (!result.canceled && result.assets && result.assets.length > 0) {
                const asset = result.assets[0];
                setArchivoActaUri(asset.uri);
                setNombreArchivoActa(asset.fileName || 'acta_soporte.jpg');
            }
        } catch (error) {
            AppAlert.alert('Error', usarCamara ? 'No se pudo abrir la cámara.' : 'No se pudo abrir la galería.');
        }
    };

    const handleFinalizar = async () => {
        if (!archivoActaUri) {
            AppAlert.alert('Acta requerida', 'Debes adjuntar la imagen/foto del Acta de Soporte para finalizar.');
            return;
        }
        if (!diagnostico.trim()) {
            AppAlert.alert('Diagnóstico requerido', 'Debes escribir el diagnóstico u observación del trabajo realizado.');
            return;
        }
        const idAsignacion = ticket.id_asignacion;
        if (!idAsignacion) {
            AppAlert.alert('Error', 'No se encontró el ID de asignación.');
            return;
        }

        setCargando(true);
        try {
            // Detener el seguimiento de ubicación del técnico al subir el acta y finalizar
            if (ticket.fecha_en_camino && !ticket.fecha_llegada) {
                try {
                    const coordenadas = await capturarUbicacionActual();
                    await registrarLlegadaTecnico(Number(idAsignacion), coordenadas.latitud, coordenadas.longitud);
                } catch (errLlegada: any) {
                    console.warn('No se pudo registrar la ubicación final al concluir:', errLlegada?.message || errLlegada);
                }
            }
            await finalizarSoporte(Number(idAsignacion), archivoActaUri, diagnostico.trim());
            AppAlert.alert('¡Soporte Finalizado!', 'El acta y el registro del soporte se guardaron correctamente.');
            (navigation as any).navigate('Main', {
                filtroInicial: 'FINALIZADAS',
                filtroTs: Date.now(),
            });
        } catch (error: any) {
            console.error('Error al finalizar soporte:', error);
            const msg = error?.response?.data?.message || 'No se pudo finalizar el soporte.';
            AppAlert.alert('Error', msg);
        } finally {
            setCargando(false);
        }
    };

    // Fechas formateadas
    const fechaRaw = fechaSolicitudTicket;
    const fechaObj = fechaRaw ? new Date(fechaRaw) : null;
    const fechaSolicitud = fechaObj && !isNaN(fechaObj.getTime())
        ? fechaObj.toLocaleDateString('es-BO', { day: '2-digit', month: '2-digit', year: 'numeric' })
        : 'Hoy';
    const horaSolicitud = fechaObj && !isNaN(fechaObj.getTime())
        ? fechaObj.toLocaleTimeString('es-BO', { hour: '2-digit', minute: '2-digit' })
        : 'Reciente';

    const estiloEstado = obtenerEstiloEstado(estadoActual);
    const infoPrioridad = obtenerInfoPrioridad(ticket.prioridad || prioridad);
    const etiquetaTiempoEtapa = estadoActual === 'PENDIENTE'
        ? 'ESPERA DE ASIGNACIÓN'
        : estadoActual === 'ASIGNADA'
            ? 'TIEMPO ASIGNADO'
            : estadoActual === 'EN_PROCESO'
                ? 'TIEMPO EN PROCESO'
                : 'HORA';
    const tiempoEtapaActual = estadoActual === 'PENDIENTE'
        ? tiempoEsperaAsignacion
        : estadoActual === 'ASIGNADA'
            ? tiempoDesdeAsignacion
            : estadoActual === 'EN_PROCESO'
                ? tiempoEnProceso
                : horaSolicitud;
    const fechaCaminoMs = ticket.fecha_en_camino ? new Date(ticket.fecha_en_camino).getTime() : NaN;
    const fechaLlegadaMs = ticket.fecha_llegada ? new Date(ticket.fecha_llegada).getTime() : NaN;
    const tiempoTrayecto = Number.isFinite(fechaCaminoMs)
        ? formatearSegundos(((Number.isFinite(fechaLlegadaMs) ? fechaLlegadaMs : ahoraTrayecto) - fechaCaminoMs) / 1000)
        : null;
    const puedeRegistrarTrayecto = rolUsuario === RolBandeja.TECNICO
        && (estadoActual === 'ASIGNADA' || estadoActual === 'EN_PROCESO');

    return (
        <View style={styles.screenWrapper}>
            {/* 1. CABECERA FIJA SUPERIOR AZUL INSTITUCIONAL CON ONDAS */}
            <View style={[styles.topHeader, { paddingTop: Platform.OS === 'web' ? 14 : Math.max(insets.top, 14) + 6 }]}>
                <View style={[styles.topBarRow, esEscritorio && { maxWidth: 880, alignSelf: 'center' }]}>
                    <TouchableOpacity
                        style={styles.backButton}
                        onPress={() => navigation.goBack()}
                        activeOpacity={0.75}
                    >
                        <AppIcon name="arrow-left" size={20} color="#ffffff" />
                    </TouchableOpacity>

                    <Text style={styles.navBarTitle} numberOfLines={1}>
                        Detalle del Soporte
                    </Text>

                    <View style={styles.headerSpacerRight} />
                </View>

                {/* Onda superior curva en la base del header */}
                <Image
                    source={require('../../../../assets/ondas-superiores.png')}
                    style={styles.headerBottomWave}
                    resizeMode="stretch"
                />
            </View>

            {/* 2. CUERPO CON SCROLL NATURAL Y EVITACIÓN DE TECLADO */}
            <KeyboardAvoidingView
                style={styles.keyboardAvoiding}
                behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
                keyboardVerticalOffset={0}
            >
                <ScrollView
                    ref={scrollViewRef}
                    style={styles.scrollView}
                    contentContainerStyle={[styles.scrollContent, { paddingBottom: 40 }]}
                    keyboardShouldPersistTaps="handled"
                    keyboardDismissMode="on-drag"
                    showsVerticalScrollIndicator={false}
                >
                    <View style={[styles.innerContainer, { maxWidth: 760 }]}>
                        {/* BANNER INSTITUCIONAL DEL TICKET */}
                        <View style={styles.sectionBannerRow}>
                            <View style={styles.blueCircleIcon}>
                                <AppIcon name="clipboard" size={22} color="#ffffff" />
                            </View>
                            <View style={{ flex: 1 }}>
                                <Text style={styles.sectionBannerTitle}>
                                    Ticket {ticket.codigo_ticket || `#${idReal}`}
                                </Text>
                                <Text style={styles.sectionBannerSub}>
                                    Información y seguimiento de la solicitud
                                </Text>
                            </View>
                        </View>

                        {/* TARJETA 1: RESUMEN DEL TICKET */}
                        <View style={styles.card}>
                            {/* Bloque superior: Estado actual + Prioridad + Solicitante */}
                            <View style={styles.summaryBlock1}>
                                <View style={styles.summaryRow}>
                                    <View>
                                        <Text style={styles.summaryMiniLabel}>ESTADO ACTUAL</Text>
                                        <View style={{ flexDirection: 'row', alignItems: 'center', flexWrap: 'wrap', gap: 6, marginTop: 4 }}>
                                            <View style={[styles.statusBadge, { backgroundColor: estiloEstado.bg, borderColor: estiloEstado.border }]}>
                                                <Text style={[styles.statusBadgeText, { color: estiloEstado.color }]}>
                                                    {estiloEstado.texto}
                                                </Text>
                                            </View>
                                            {rolUsuario === RolBandeja.USUARIO &&
                                                estadoActual !== 'FINALIZADA' &&
                                                !ticket.fecha_llegada &&
                                                (estadoActual === 'EN_PROCESO' || Boolean(ticket.fecha_en_camino)) && (
                                                    <View style={[styles.statusBadge, { backgroundColor: '#ecfeff', borderColor: '#a5f3fc' }]}>
                                                        <Text style={[styles.statusBadgeText, { color: '#0e7490' }]}>
                                                            EN CAMINO
                                                        </Text>
                                                    </View>
                                                )}
                                            {infoPrioridad && (
                                                <View style={[styles.priorityBadge, { backgroundColor: infoPrioridad.bg, borderColor: infoPrioridad.color }]}>
                                                    <View style={[styles.priorityDot, { backgroundColor: infoPrioridad.dot }]} />
                                                    <Text style={[styles.priorityBadgeText, { color: infoPrioridad.color }]}>
                                                        {infoPrioridad.texto}
                                                    </Text>
                                                </View>
                                            )}
                                        </View>
                                    </View>
                                </View>

                                <View style={{ marginTop: 12, borderTopWidth: 1, borderTopColor: '#e2e8f0', paddingTop: 8 }}>
                                    <Text style={styles.summaryMiniLabel}>SOLICITANTE</Text>
                                    <Text style={styles.summaryValueName}>
                                        {ticket.solicitante_manual || ticket.nombre_usuario || (ticket as any).username || 'Funcionario institucional'}
                                    </Text>
                                </View>
                            </View>

                            {/* Bloque: 2 Columnas de Fecha y Hora */}
                            <View style={styles.summaryBlock2}>
                                <View style={styles.summaryColCard}>
                                    <View style={{ flexDirection: 'row', alignItems: 'center', gap: 5 }}>
                                        <AppIcon name="calendar" size={14} color="#164a96" />
                                        <Text style={styles.summaryColLabel}>FECHA</Text>
                                    </View>
                                    <Text style={styles.summaryColValue}>{fechaSolicitud}</Text>
                                </View>

                                {rolUsuario !== RolBandeja.USUARIO && (
                                    <View style={styles.summaryColCard}>
                                        <View style={{ flexDirection: 'row', alignItems: 'center', gap: 5 }}>
                                            <AppIcon name="clock" size={14} color="#164a96" />
                                            <Text style={styles.summaryColLabel}>{etiquetaTiempoEtapa}</Text>
                                        </View>
                                        <Text style={styles.summaryColValue}>{tiempoEtapaActual}</Text>
                                    </View>
                                )}
                            </View>

                            {rolUsuario === RolBandeja.OPERADOR && estadoActual !== 'PENDIENTE' && Number(ticket.cantidad_reasignaciones || 0) === 0 && (ticket.segundos_hasta_asignacion !== undefined && ticket.segundos_hasta_asignacion !== null || Boolean(fechaSolicitudTicket && (ticket.fecha_primera_asignacion || ticket.fecha_asignacion))) && (
                                <View style={styles.waitAssignmentSummary}>
                                    <Text style={styles.summaryColLabel}>ESPERA HASTA LA ASIGNACIÓN</Text>
                                    <Text style={styles.summaryColValue}>{tiempoEsperaAsignacion}</Text>
                                </View>
                            )}

                            {/* Ubicación */}
                            <View style={styles.locationSection}>
                                <View style={styles.sectionHeaderRow}>
                                    <View style={styles.sectionIconPill}>
                                        <AppIcon name="location" size={15} color="#164a96" />
                                    </View>
                                    <Text style={styles.sectionCardTitle}>Ubicación</Text>
                                </View>
                                <View style={styles.infoContentBox}>
                                    <Text style={styles.locationTitle}>{ticket.oficina || 'Sin oficina'}</Text>
                                    <Text style={styles.locationSub}>
                                        {ticket.piso ? `Piso: ${ticket.piso}` : 'Piso no registrado'}
                                        {(ticket as any).consultorio ? ` • Consultorio: ${(ticket as any).consultorio}` : ''}
                                    </Text>
                                </View>
                            </View>

                            {rolUsuario === RolBandeja.OPERADOR && (
                                <View style={styles.travelStatusBox}>
                                    <View style={styles.sectionHeaderRow}>
                                        <View style={[styles.sectionIconPill, { backgroundColor: '#ecfeff' }]}>
                                            <AppIcon name="location" size={15} color="#0e7490" />
                                        </View>
                                        <Text style={styles.sectionCardTitle}>Ubicación del técnico</Text>
                                    </View>
                                    {!ticket.fecha_en_camino ? (
                                        <Text style={styles.travelHelpText}>El técnico aún no ha iniciado la atención.</Text>
                                    ) : (
                                        <>
                                            <View style={styles.travelStatusPill}>
                                                <Text style={styles.travelStatusPillText}>
                                                    {ticket.fecha_llegada || estadoActual === 'FINALIZADA' ? 'FINALIZADO' : 'EN CAMINO'}
                                                </Text>
                                            </View>
                                            <View style={styles.travelInfoRow}>
                                                <Text style={styles.travelInfoLabel}>Salió:</Text>
                                                <Text style={styles.travelInfoValue}>{formatearFechaHora(ticket.fecha_en_camino)}</Text>
                                            </View>
                                            {ticket.latitud_en_camino !== null && ticket.latitud_en_camino !== undefined && ticket.longitud_en_camino !== null && ticket.longitud_en_camino !== undefined && Number.isFinite(Number(ticket.latitud_en_camino)) && Number.isFinite(Number(ticket.longitud_en_camino)) && (
                                                <TouchableOpacity onPress={() => abrirMapa(ticket.latitud_en_camino, ticket.longitud_en_camino)} activeOpacity={0.75}>
                                                    <Text style={styles.travelMapLink}>
                                                        Ver ubicación de salida · {Number(ticket.latitud_en_camino).toFixed(5)}, {Number(ticket.longitud_en_camino).toFixed(5)}
                                                    </Text>
                                                </TouchableOpacity>
                                            )}
                                            <View style={styles.travelInfoRow}>
                                                <Text style={styles.travelInfoLabel}>{ticket.fecha_llegada ? 'Finalizó / Llegó:' : 'Tiempo en camino:'}</Text>
                                                <Text style={styles.travelInfoValue}>{ticket.fecha_llegada ? formatearFechaHora(ticket.fecha_llegada) : tiempoTrayecto}</Text>
                                            </View>
                                            {ticket.fecha_llegada && ticket.latitud_llegada !== null && ticket.latitud_llegada !== undefined && ticket.longitud_llegada !== null && ticket.longitud_llegada !== undefined && Number.isFinite(Number(ticket.latitud_llegada)) && Number.isFinite(Number(ticket.longitud_llegada)) && (
                                                <TouchableOpacity onPress={() => abrirMapa(ticket.latitud_llegada, ticket.longitud_llegada)} activeOpacity={0.75}>
                                                    <Text style={styles.travelMapLink}>
                                                        Ver ubicación final · {Number(ticket.latitud_llegada).toFixed(5)}, {Number(ticket.longitud_llegada).toFixed(5)}
                                                    </Text>
                                                </TouchableOpacity>
                                            )}
                                            {ticket.fecha_llegada && (
                                                <View style={styles.travelInfoRow}>
                                                    <Text style={styles.travelInfoLabel}>Duración del trayecto:</Text>
                                                    <Text style={styles.travelInfoValue}>{tiempoTrayecto}</Text>
                                                </View>
                                            )}
                                        </>
                                    )}
                                </View>
                            )}

                            {rolUsuario === RolBandeja.USUARIO &&
                                estadoActual !== 'FINALIZADA' &&
                                (estadoActual === 'EN_PROCESO' || !!ticket.fecha_en_camino) && (
                                    <View style={styles.travelStatusBox}>
                                        <View style={[styles.sectionHeaderRow, { marginBottom: 0 }]}>
                                            <View style={[styles.sectionIconPill, { backgroundColor: '#ecfeff' }]}>
                                                <AppIcon name="location" size={15} color="#0e7490" />
                                            </View>
                                            <Text style={[styles.sectionCardTitle, { color: '#0e7490' }]}>En camino</Text>
                                        </View>
                                    </View>
                                )}
                        </View>

                        {/* TARJETA 2: PROBLEMAS REPORTADOS Y TÉCNICO */}
                        <View style={styles.card}>
                            <View style={styles.sectionHeaderRow}>
                                <View style={styles.sectionIconPill}>
                                    <AppIcon name="wrench" size={15} color="#164a96" />
                                </View>
                                <Text style={styles.sectionCardTitle}>Problemas Reportados</Text>
                            </View>

                            {cargandoItems ? (
                                <ActivityIndicator size="small" color={theme.colors.primary} style={{ marginVertical: 14 }} />
                            ) : itemsProblema.length > 0 ? (
                                itemsProblema.map((item, index) => {
                                    const infoTipo = obtenerInfoTipoSoporte(item.id_tipo_soporte);
                                    return (
                                        <View key={index} style={styles.itemBox}>
                                            <View style={{ flexDirection: 'row', alignItems: 'center', marginBottom: 4 }}>
                                                <AppIcon name={infoTipo.icono} size={15} color="#164a96" />
                                                <Text style={styles.itemType}>{infoTipo.nombre}</Text>
                                            </View>
                                            <Text style={styles.itemDesc}>{item.descripcion}</Text>
                                            {index === 0 && (item.adjuntos || []).map((adjunto: any) => {
                                                const urlImagen = construirUrlArchivo(adjunto.ruta_url);
                                                return urlImagen ? (
                                                    <TouchableOpacity
                                                        key={adjunto.id}
                                                        onPress={() =>
                                                            setImagenAdjuntaModal({
                                                                url: urlImagen,
                                                                nombre: adjunto.nombre_archivo || 'imagen-adjunta.jpg',
                                                            })
                                                        }
                                                        style={styles.problemAttachment}
                                                    >
                                                        <Image source={{ uri: urlImagen }} style={styles.problemAttachmentImage} resizeMode="cover" />
                                                        <Text style={styles.problemAttachmentText}>Ver imagen adjunta</Text>
                                                    </TouchableOpacity>
                                                ) : null;
                                            })}
                                        </View>
                                    );
                                })
                            ) : (
                                <Text style={styles.emptyItemsText}>No se pudieron cargar los problemas.</Text>
                            )}

                            {rolUsuario === RolBandeja.TECNICO && Boolean(ticket.instrucciones_tecnico?.trim()) && (
                                <View style={styles.operatorInstructionsBox}>
                                    <View style={styles.operatorInstructionsHeading}>
                                        <AppIcon name="document-text" size={16} color="#1d4ed8" />
                                        <Text style={styles.operatorInstructionsTitle}>Indicaciones del operador</Text>
                                    </View>
                                    <Text style={styles.operatorInstructionsText}>{ticket.instrucciones_tecnico}</Text>
                                </View>
                            )}

                            {/* TÉCNICO ASIGNADO */}
                            {Boolean(ticket.nombre_tecnico) && (
                                <View style={styles.techAssignedBox}>
                                    <AppIcon name="user" size={16} color="#1d4ed8" />
                                    <View style={{ flex: 1, marginLeft: 8 }}>
                                        <Text style={styles.techAssignedLabel}>Técnico Asignado:</Text>
                                        <Text style={styles.techAssignedName}>{ticket.nombre_tecnico}</Text>
                                    </View>
                                </View>
                            )}

                            {rolUsuario === RolBandeja.OPERADOR && estadoActual === 'FINALIZADA' && Boolean(ticket.calificacion_usuario) && (
                                <View style={styles.operatorRatingBox}>
                                    <Text style={styles.operatorRatingEmoji}>
                                        {ticket.calificacion_usuario === 'MUY_BIEN' ? '😊' : ticket.calificacion_usuario === 'BIEN' ? '😐' : '🙁'}
                                    </Text>
                                    <View style={{ flex: 1 }}>
                                        <Text style={styles.operatorRatingTitle}>Calificación del usuario</Text>
                                        <Text style={styles.operatorRatingValue}>
                                            {ticket.calificacion_usuario === 'MUY_BIEN' ? 'Muy bien' : ticket.calificacion_usuario === 'BIEN' ? 'Bien' : 'Mal'}
                                        </Text>
                                    </View>
                                </View>
                            )}

                            {(rolUsuario === RolBandeja.OPERADOR || rolUsuario === RolBandeja.TECNICO) && Number(ticket.cantidad_reasignaciones || 0) > 0 && historialTecnicos.length > 0 && (
                                <TouchableOpacity
                                    style={styles.viewTechnicianHistoryButton}
                                    onPress={() => setModalHistorialTecnicosVisible(true)}
                                    activeOpacity={0.8}
                                >
                                    <AppIcon name="users" size={15} color="#1d5bbf" />
                                    <Text style={styles.viewTechnicianHistoryText}>Ver técnicos y tiempos anteriores</Text>
                                </TouchableOpacity>
                            )}

                            {/* AVISO DE REASIGNACIÓN PREVIA */}
                            {Boolean(ticket.ultima_reasignacion_motivo) && (
                                <View style={styles.warningAlertBox}>
                                    <View style={{ flexDirection: 'row', alignItems: 'center' }}>
                                        <AppIcon name="refresh" size={13} color="#d35400" />
                                        <Text style={styles.warningAlertTitle}>Ticket Reasignado / Derivado</Text>
                                    </View>
                                    <Text style={styles.warningAlertText}>
                                        Motivo: {ticket.ultima_reasignacion_motivo}
                                    </Text>
                                </View>
                            )}

                            {/* AVISO DE INSISTENCIA */}
                            {(Number(ticket.cantidad_resolicitudes || 0) > 0 || (Array.isArray(ticket.resolicitudes_historial) && ticket.resolicitudes_historial.length > 0)) && (
                                <View style={styles.dangerAlertBox}>
                                    <View style={{ flexDirection: 'row', alignItems: 'center' }}>
                                        <AppIcon name="alert" size={13} color="#c0392b" />
                                        <Text style={styles.dangerAlertTitle}>
                                            Insistencias del usuario ({ticket.resolicitudes_historial?.length || ticket.cantidad_resolicitudes}): Atención demorada
                                        </Text>
                                    </View>
                                    {Array.isArray(ticket.resolicitudes_historial) && ticket.resolicitudes_historial.length > 0 ? (
                                        <View style={styles.insistenciaLista}>
                                            {ticket.resolicitudes_historial.map((ins, idx) => (
                                                <View key={ins.id ?? idx} style={styles.insistenciaItemRow}>
                                                    <Text style={styles.insistenciaItemIndex}>#{idx + 1}</Text>
                                                    <View style={{ flex: 1 }}>
                                                        <Text style={styles.dangerAlertText}>
                                                            {ins.motivo ? `"${ins.motivo}"` : 'Sin comentario'}
                                                        </Text>
                                                        {ins.fecha ? (
                                                            <Text style={styles.insistenciaItemFecha}>
                                                                {formatearFechaHora(ins.fecha)}
                                                            </Text>
                                                        ) : null}
                                                    </View>
                                                </View>
                                            ))}
                                        </View>
                                    ) : ticket.ultima_resolicitud_motivo ? (
                                        <Text style={styles.dangerAlertText}>
                                            "{ticket.ultima_resolicitud_motivo}"
                                        </Text>
                                    ) : null}
                                </View>
                            )}
                        </View>

                        {/* TARJETA 3: ACCIONES SEGÚN ROL */}

                        {/* --- ROL USUARIO --- */}
                        {rolUsuario === RolBandeja.USUARIO && estadoActual !== 'FINALIZADA' && (
                            <View style={styles.card}>
                                <Text style={styles.actionSectionTitle}>¿Atención demorada?</Text>
                                <TextInput
                                    style={styles.textInputArea}
                                    placeholder="Escribe el motivo de tu insistencia (opcional)..."
                                    placeholderTextColor="#94a3b8"
                                    value={observacion}
                                    onChangeText={setObservacion}
                                    multiline
                                />

                                <TouchableOpacity
                                    style={[styles.primaryActionBtn, { backgroundColor: '#e67e22' }]}
                                    onPress={handleResolicitar}
                                    disabled={cargando}
                                >
                                    {cargando ? (
                                        <ActivityIndicator color="#ffffff" />
                                    ) : (
                                        <>
                                            <AppIcon name="bell" size={16} color="#ffffff" />
                                            <Text style={styles.primaryActionBtnText}>Re-solicitar / Insistir</Text>
                                        </>
                                    )}
                                </TouchableOpacity>

                                <TouchableOpacity
                                    style={styles.secondaryActionBtn}
                                    onPress={() => setModalVisible(true)}
                                >
                                    <Text style={styles.secondaryActionBtnText}>+ Añadir otro problema a esta solicitud</Text>
                                </TouchableOpacity>
                            </View>
                        )}

                        {rolUsuario === RolBandeja.USUARIO && estadoActual === 'FINALIZADA' && (
                            <View style={styles.card}>
                                <Text style={styles.actionSectionTitle}>¿Cómo fue la atención?</Text>
                                <Text style={styles.ratingSubtitle}>
                                    Califica el soporte brindado por {ticket.nombre_tecnico || 'el técnico'}.
                                </Text>
                                {cargandoCalificacion ? (
                                    <ActivityIndicator color="#1d5bbf" style={{ marginVertical: 18 }} />
                                ) : calificacionGuardada ? (
                                    <View style={styles.ratingThanks}>
                                        <Text style={styles.ratingThanksEmoji}>
                                            {calificacionGuardada.calificacion === 'MUY_BIEN' ? '😊' : calificacionGuardada.calificacion === 'BIEN' ? '😐' : '🙁'}
                                        </Text>
                                        <Text style={styles.ratingThanksText}>Gracias por tu opinión. Tu calificación quedó registrada y no se puede cambiar.</Text>
                                    </View>
                                ) : (
                                    <>
                                        <View style={styles.ratingOptions}>
                                            {([
                                                { value: 'MUY_BIEN', label: 'Muy bien', emoji: '😊' },
                                                { value: 'BIEN', label: 'Bien', emoji: '😐' },
                                                { value: 'MAL', label: 'Mal', emoji: '🙁' },
                                            ] as const).map((opcion) => {
                                                const seleccionada = calificacionSeleccionada === opcion.value;
                                                return (
                                                    <TouchableOpacity
                                                        key={opcion.value}
                                                        style={[styles.ratingOption, seleccionada && styles.ratingOptionSelected, guardandoCalificacion && { opacity: 0.7 }]}
                                                        onPress={() => seleccionarCalificacion(opcion.value)}
                                                        disabled={guardandoCalificacion}
                                                        accessibilityRole="button"
                                                        accessibilityLabel={`Calificar ${opcion.label}`}
                                                        accessibilityState={{ selected: seleccionada }}
                                                        activeOpacity={0.75}
                                                    >
                                                        <Text style={styles.ratingEmoji}>{opcion.emoji}</Text>
                                                        <Text style={[styles.ratingLabel, seleccionada && styles.ratingLabelSelected]}>{opcion.label}</Text>
                                                    </TouchableOpacity>
                                                );
                                            })}
                                        </View>

                                        {guardandoCalificacion && (
                                            <View style={styles.ratingSaving}>
                                                <ActivityIndicator color="#1d5bbf" />
                                                <Text style={styles.ratingSavingText}>Guardando calificación...</Text>
                                            </View>
                                        )}
                                    </>
                                )}
                            </View>
                        )}

                        {/* --- ROL OPERADOR: ASIGNACIÓN INICIAL --- */}
                        {rolUsuario === RolBandeja.OPERADOR && estadoActual === 'PENDIENTE' && (
                            <View style={styles.card}>
                                <Text style={styles.actionSectionTitle}>Asignar a Técnico</Text>

                                <Text style={styles.inputMiniLabel}>1. Nivel de prioridad:</Text>
                                <View style={styles.priorityGrid}>
                                    {NIVELES_PRIORIDAD.map((p) => {
                                        const activo = prioridad === p.valor;
                                        return (
                                            <TouchableOpacity
                                                key={p.valor}
                                                style={[
                                                    styles.priorityOptionCard,
                                                    { borderColor: activo ? p.color : '#e2e8f0', backgroundColor: activo ? p.bg : '#ffffff' },
                                                ]}
                                                onPress={() => setPrioridad(p.valor)}
                                            >
                                                <View style={[styles.priorityDot, { backgroundColor: p.dot }]} />
                                                <Text style={[styles.priorityOptionText, { color: activo ? p.color : '#475569' }]}>
                                                    {p.etiqueta}
                                                </Text>
                                            </TouchableOpacity>
                                        );
                                    })}
                                </View>

                                <Text style={styles.inputMiniLabel}>2. Indicaciones para el técnico (opcional):</Text>
                                <TextInput
                                    style={[styles.textInputArea, styles.operatorInstructionsInput]}
                                    placeholder="Describe cómo ingresar al sistema o agrega datos de acceso temporales necesarios para atender el soporte..."
                                    placeholderTextColor="#94a3b8"
                                    value={instruccionesTecnico}
                                    onChangeText={setInstruccionesTecnico}
                                    multiline
                                    maxLength={1000}
                                    textAlignVertical="top"
                                />
                                <Text style={styles.instructionsHelperText}>Estas indicaciones solo se mostrarán al técnico asignado.</Text>

                                <Text style={styles.inputMiniLabel}>3. Selecciona al técnico disponible:</Text>
                                {tecnicos.length === 0 ? (
                                    <ActivityIndicator color={theme.colors.primary} style={{ marginVertical: 14 }} />
                                ) : (
                                    <View style={{ marginBottom: 12 }}>
                                        {tecnicos.map((tec) => (
                                            <TouchableOpacity
                                                key={tec.id}
                                                style={[
                                                    styles.selectOptionRow,
                                                    idTecnico === tec.id && styles.selectOptionRowActive,
                                                ]}
                                                onPress={() => setIdTecnico(tec.id)}
                                            >
                                                <AppIcon
                                                    name="user"
                                                    size={15}
                                                    color={idTecnico === tec.id ? '#164a96' : '#64748b'}
                                                />
                                                <Text
                                                    style={[
                                                        styles.selectOptionText,
                                                        idTecnico === tec.id && styles.selectOptionTextActive,
                                                    ]}
                                                >
                                                    {tec.nombre}
                                                </Text>
                                            </TouchableOpacity>
                                        ))}
                                    </View>
                                )}

                                <TouchableOpacity
                                    style={[
                                        styles.primaryActionBtn,
                                        { backgroundColor: '#164a96', opacity: idTecnico ? 1 : 0.5 },
                                    ]}
                                    onPress={handleAsignar}
                                    disabled={cargando || !idTecnico}
                                >
                                    {cargando ? (
                                        <ActivityIndicator color="#ffffff" />
                                    ) : (
                                        <>
                                            <Text style={styles.primaryActionBtnText}>Asignar Ticket</Text>
                                            <AppIcon name="arrow-right" size={16} color="#ffffff" />
                                        </>
                                    )}
                                </TouchableOpacity>
                            </View>
                        )}

                        {/* --- ROL OPERADOR: REASIGNACIÓN --- */}
                        {rolUsuario === RolBandeja.OPERADOR && (estadoActual === 'ASIGNADA' || estadoActual === 'EN_PROCESO') && (
                            <View style={styles.card}>
                                <Text style={styles.actionSectionTitle}>Reasignar a otro Técnico</Text>

                                <Text style={styles.inputMiniLabel}>1. Motivo del cambio *</Text>
                                <TextInput
                                    style={styles.textInputArea}
                                    placeholder="Explica por qué se cambia de técnico..."
                                    placeholderTextColor="#94a3b8"
                                    value={justificacionReasignacion}
                                    onChangeText={setJustificacionReasignacion}
                                    multiline
                                />

                                <Text style={styles.inputMiniLabel}>2. Indicaciones para el nuevo técnico (opcional):</Text>
                                <TextInput
                                    style={[styles.textInputArea, styles.operatorInstructionsInput]}
                                    placeholder="Agrega instrucciones de acceso o datos temporales que el nuevo técnico necesite..."
                                    placeholderTextColor="#94a3b8"
                                    value={instruccionesTecnico}
                                    onChangeText={setInstruccionesTecnico}
                                    multiline
                                    maxLength={1000}
                                    textAlignVertical="top"
                                />
                                <Text style={styles.instructionsHelperText}>Si lo dejas vacío, se conservarán las indicaciones existentes.</Text>

                                <Text style={styles.inputMiniLabel}>3. Nivel de prioridad:</Text>
                                <View style={styles.priorityGrid}>
                                    {NIVELES_PRIORIDAD.map((p) => {
                                        const activo = prioridad === p.valor;
                                        return (
                                            <TouchableOpacity
                                                key={p.valor}
                                                style={[
                                                    styles.priorityOptionCard,
                                                    { borderColor: activo ? p.color : '#e2e8f0', backgroundColor: activo ? p.bg : '#ffffff' },
                                                ]}
                                                onPress={() => setPrioridad(p.valor)}
                                            >
                                                <View style={[styles.priorityDot, { backgroundColor: p.dot }]} />
                                                <Text style={[styles.priorityOptionText, { color: activo ? p.color : '#475569' }]}>
                                                    {p.etiqueta}
                                                </Text>
                                            </TouchableOpacity>
                                        );
                                    })}
                                </View>

                                <Text style={styles.inputMiniLabel}>4. Nuevo técnico disponible:</Text>
                                <View style={{ marginBottom: 12 }}>
                                    {tecnicos.map((tec) => {
                                        const esActual = ticket.nombre_tecnico && tec.nombre === ticket.nombre_tecnico;
                                        return (
                                            <TouchableOpacity
                                                key={tec.id}
                                                disabled={Boolean(esActual)}
                                                style={[
                                                    styles.selectOptionRow,
                                                    esActual && { opacity: 0.5, backgroundColor: '#f1f5f9' },
                                                    idTecnico === tec.id && styles.selectOptionRowActive,
                                                ]}
                                                onPress={() => setIdTecnico(tec.id)}
                                            >
                                                <AppIcon
                                                    name="user"
                                                    size={15}
                                                    color={idTecnico === tec.id ? '#164a96' : '#64748b'}
                                                />
                                                <Text
                                                    style={[
                                                        styles.selectOptionText,
                                                        idTecnico === tec.id && styles.selectOptionTextActive,
                                                    ]}
                                                >
                                                    {tec.nombre} {esActual ? '(Asignado)' : ''}
                                                </Text>
                                            </TouchableOpacity>
                                        );
                                    })}
                                </View>

                                <TouchableOpacity
                                    style={[
                                        styles.primaryActionBtn,
                                        {
                                            backgroundColor: '#e67e22',
                                            opacity: idTecnico && justificacionReasignacion.trim() ? 1 : 0.5,
                                        },
                                    ]}
                                    onPress={handleReasignarOperador}
                                    disabled={cargando || !idTecnico || !justificacionReasignacion.trim()}
                                >
                                    {cargando ? (
                                        <ActivityIndicator color="#ffffff" />
                                    ) : (
                                        <>
                                            <AppIcon name="refresh" size={16} color="#ffffff" />
                                            <Text style={styles.primaryActionBtnText}>Confirmar Reasignación</Text>
                                        </>
                                    )}
                                </TouchableOpacity>
                            </View>
                        )}

                        {/* --- HISTORIAL / FINALIZADA (OPERADOR / TÉCNICO) --- */}
                        {(rolUsuario === RolBandeja.OPERADOR || rolUsuario === RolBandeja.TECNICO) && estadoActual === 'FINALIZADA' && (
                            <View style={styles.card}>
                                <Text style={styles.actionSectionTitle}>Historial y Acta de Soporte</Text>

                                <View style={styles.historyMetricsBox}>
                                    <View style={styles.historyMetricRow}>
                                        <Text style={styles.historyMetricLabel}>Técnico:</Text>
                                        <Text style={styles.historyMetricValue}>{ticket.nombre_tecnico || 'Técnico'}</Text>
                                    </View>
                                    <View style={styles.historyMetricRow}>
                                        <Text style={styles.historyMetricLabel}>Asignación:</Text>
                                        <Text style={[styles.historyMetricValue, { color: '#1d4ed8' }]}>
                                            {formatearFechaHora(ticket.fecha_asignacion)}
                                        </Text>
                                    </View>
                                    <View style={styles.historyMetricRow}>
                                        <Text style={styles.historyMetricLabel}>Finalización:</Text>
                                        <Text style={[styles.historyMetricValue, { color: '#15803d' }]}>
                                            {formatearFechaHora(ticket.fecha_finalizacion)}
                                        </Text>
                                    </View>
                                    <View style={styles.historyMetricRow}>
                                        <Text style={styles.historyMetricLabel}>Tiempo total:</Text>
                                        <Text style={[styles.historyMetricValue, { color: '#1d4ed8' }]}>
                                            {tiempoDesdeAsignacion}
                                        </Text>
                                    </View>
                                    <View style={styles.historyMetricRow}>
                                        <Text style={styles.historyMetricLabel}>Trabajo efectivo:</Text>
                                        <Text style={[styles.historyMetricValue, { color: '#6d28d9' }]}>
                                            {tiempoEnProceso}
                                        </Text>
                                    </View>
                                    {Boolean(ticket.diagnostico) && (
                                        <View style={{ marginTop: 8, borderTopWidth: 1, borderTopColor: '#e2e8f0', paddingTop: 8 }}>
                                            <Text style={styles.historyMetricLabel}>Diagnóstico:</Text>
                                            <Text style={{ fontSize: 13, color: '#334155', marginTop: 2 }}>
                                                {ticket.diagnostico}
                                            </Text>
                                        </View>
                                    )}
                                </View>

                                <TouchableOpacity
                                    style={[styles.primaryActionBtn, { backgroundColor: '#15803d', marginTop: 12 }]}
                                    onPress={async () => {
                                        let ruta = rutaActaGuardada || ticket.url_acta || null;
                                        if (!ruta && idReal) {
                                            const acta = await obtenerActaSolicitud(idReal);
                                            if (acta?.ruta_url) {
                                                ruta = acta.ruta_url;
                                                setRutaActaGuardada(acta.ruta_url);
                                                setNombreActaGuardada(acta.nombre_archivo || '');
                                                setTipoMimeActaGuardada(acta.tipo_mime || '');
                                            }
                                        }
                                        const urlCompleta = construirUrlArchivo(ruta);
                                        if (!urlCompleta) {
                                            AppAlert.alert('Sin archivo', 'No se encontró un archivo de acta adjunto.');
                                            return;
                                        }
                                        const esPdf =
                                            (tipoMimeActaGuardada && tipoMimeActaGuardada.toLowerCase().includes('pdf')) ||
                                            urlCompleta.toLowerCase().endsWith('.pdf');
                                        if (esPdf) {
                                            Linking.openURL(urlCompleta);
                                        } else {
                                            setModalVerActaVisible(true);
                                        }
                                    }}
                                >
                                    <AppIcon name="document" size={16} color="#ffffff" />
                                    <Text style={styles.primaryActionBtnText}>Ver Acta Adjunta</Text>
                                </TouchableOpacity>
                            </View>
                        )}

                        {/* --- ROL TÉCNICO (SOPORTE ACTIVO) --- */}
                        {rolUsuario === RolBandeja.TECNICO && (estadoActual === 'ASIGNADA' || estadoActual === 'EN_PROCESO') && (
                            <View style={styles.card}>
                                <Text style={styles.actionSectionTitle}>Cronómetros en Vivo</Text>

                                <View style={{ flexDirection: 'row', gap: 8, marginBottom: 14 }}>
                                    <View style={styles.timerCard}>
                                        <Text style={styles.timerCardLabel}>1. Desde Asignación</Text>
                                        <Text style={[styles.timerCardNumber, { color: '#1d4ed8' }]}>
                                            {tiempoDesdeAsignacion}
                                        </Text>
                                    </View>
                                    <View style={styles.timerCard}>
                                        <Text style={styles.timerCardLabel}>2. En Proceso</Text>
                                        <Text style={[styles.timerCardNumber, { color: '#6d28d9' }]}>
                                            {tiempoEnProceso}
                                        </Text>
                                    </View>
                                </View>

                                {estadoActual === 'ASIGNADA' && (
                                    <TouchableOpacity
                                        style={[styles.primaryActionBtn, { backgroundColor: '#6d28d9', marginBottom: 14 }]}
                                        onPress={handlePonerEnProceso}
                                        disabled={cargando}
                                    >
                                        {cargando ? (
                                            <ActivityIndicator color="#ffffff" />
                                        ) : (
                                            <>
                                                <AppIcon name="play" size={16} color="#ffffff" />
                                                <Text style={styles.primaryActionBtnText}>Iniciar atención</Text>
                                            </>
                                        )}
                                    </TouchableOpacity>
                                )}

                                {(estadoActual === 'ASIGNADA' || estadoActual === 'EN_PROCESO') && (
                                    <TouchableOpacity
                                        style={[styles.secondaryActionBtn, { borderColor: '#e67e22', marginBottom: 14 }]}
                                        onPress={() => setModalReasignarVisible(true)}
                                        disabled={cargando}
                                    >
                                        <AppIcon name="refresh" size={15} color="#e67e22" />
                                        <Text style={[styles.secondaryActionBtnText, { color: '#e67e22' }]}>Derivar a otro Técnico</Text>
                                    </TouchableOpacity>
                                )}

                                {/* FINALIZAR Y SUBIR ACTA: disponible después de iniciar */}
                                {estadoActual === 'EN_PROCESO' && <View style={{ borderTopWidth: 1, borderTopColor: '#e2e8f0', paddingTop: 14 }}>
                                    <Text style={styles.actionSectionTitle}>Finalizar Soporte y Subir Acta</Text>

                                    <Text style={styles.inputMiniLabel}>1. Foto del Acta *</Text>
                                    <View style={styles.actaUploadActions}>
                                        <TouchableOpacity
                                            style={[styles.uploadActaBtn, styles.actaUploadAction, archivoActaUri && { backgroundColor: '#dcfce7', borderColor: '#15803d' }]}
                                            onPress={() => handleSeleccionarActa(false)}
                                        >
                                            <AppIcon name={archivoActaUri ? 'check' : 'folder'} size={16} color={archivoActaUri ? '#15803d' : '#164a96'} />
                                            <Text style={[styles.uploadActaBtnText, archivoActaUri && { color: '#15803d' }]} numberOfLines={1}>
                                                {archivoActaUri ? nombreArchivoActa : 'Galería'}
                                            </Text>
                                        </TouchableOpacity>
                                        <TouchableOpacity style={[styles.uploadActaBtn, styles.actaUploadAction]} onPress={() => handleSeleccionarActa(true)}>
                                            <AppIcon name="camera" size={16} color="#164a96" />
                                            <Text style={styles.uploadActaBtnText}>Tomar foto</Text>
                                        </TouchableOpacity>
                                    </View>

                                    <Text style={styles.inputMiniLabel}>2. Diagnóstico o Solución *</Text>
                                    <TextInput
                                        style={styles.textInputArea}
                                        placeholder="Describe qué trabajo o solución técnica se realizó..."
                                        placeholderTextColor="#94a3b8"
                                        value={diagnostico}
                                        onChangeText={setDiagnostico}
                                        multiline
                                        textAlignVertical="top"
                                        onFocus={() => {
                                            setTimeout(() => scrollViewRef.current?.scrollToEnd({ animated: true }), 250);
                                        }}
                                    />

                                    <TouchableOpacity
                                        style={[
                                            styles.primaryActionBtn,
                                            {
                                                backgroundColor: '#15803d',
                                                opacity: archivoActaUri && diagnostico.trim() ? 1 : 0.5,
                                            },
                                        ]}
                                        onPress={handleFinalizar}
                                        disabled={cargando || !archivoActaUri || !diagnostico.trim()}
                                    >
                                        {cargando ? (
                                            <ActivityIndicator color="#ffffff" />
                                        ) : (
                                            <>
                                                <AppIcon name="check" size={16} color="#ffffff" />
                                                <Text style={styles.primaryActionBtnText}>Subir Acta y Finalizar</Text>
                                            </>
                                        )}
                                    </TouchableOpacity>
                                </View>}
                            </View>
                        )}
                    </View>

                    {/* 3. ONDAS INFERIORES DECORATIVAS (OCULTAS CUANDO EL TECLADO ESTÁ ABIERTO) */}
                    {!tecladoVisible && (
                        <View style={styles.bottomWavesWrapper} pointerEvents="none">
                            <Image
                                source={require('../../../../assets/ondas-inferiores.png')}
                                style={styles.bottomWavesImg}
                                resizeMode="stretch"
                            />
                        </View>
                    )}
                </ScrollView>
            </KeyboardAvoidingView>

            {/* MODAL INTERNO: AÑADIR PROBLEMA */}
            <Modal
                animationType="slide"
                transparent={true}
                visible={modalVisible}
                onRequestClose={() => setModalVisible(false)}
            >
                <View style={styles.modalOverlay}>
                    <View style={styles.modalContent}>
                        <Text style={styles.modalTitle}>Añadir otro problema</Text>

                        <Text style={styles.inputMiniLabel}>Tipo de Problema</Text>
                        <View style={styles.optionsRow}>
                            {TIPOS_SOPORTE.map((tipo) => (
                                <TouchableOpacity
                                    key={tipo.id}
                                    style={[
                                        styles.miniOption,
                                        nuevoTipoSoporte === tipo.id && styles.miniOptionActive,
                                    ]}
                                    onPress={() => setNuevoTipoSoporte(tipo.id)}
                                >
                                    <AppIcon
                                        name={tipo.icono}
                                        size={18}
                                        color={nuevoTipoSoporte === tipo.id ? '#164a96' : '#64748b'}
                                    />
                                    <Text
                                        style={[
                                            styles.miniOptionText,
                                            nuevoTipoSoporte === tipo.id && styles.miniOptionTextActive,
                                        ]}
                                    >
                                        {tipo.titulo}
                                    </Text>
                                </TouchableOpacity>
                            ))}
                        </View>

                        <Text style={styles.inputMiniLabel}>Descripción</Text>
                        <TextInput
                            style={[styles.textInputArea, { height: 90 }]}
                            multiline
                            placeholder="Describe el nuevo problema..."
                            placeholderTextColor="#94a3b8"
                            value={nuevaDescripcion}
                            onChangeText={setNuevaDescripcion}
                        />

                        <View style={styles.modalButtons}>
                            <TouchableOpacity
                                style={styles.modalBtnCancel}
                                onPress={() => setModalVisible(false)}
                            >
                                <Text style={styles.modalBtnCancelText}>Cancelar</Text>
                            </TouchableOpacity>
                            <TouchableOpacity
                                style={styles.modalBtnSubmit}
                                onPress={handleAgregarItem}
                                disabled={guardandoItem}
                            >
                                {guardandoItem ? (
                                    <ActivityIndicator color="#fff" />
                                ) : (
                                    <Text style={styles.modalBtnSubmitText}>Guardar</Text>
                                )}
                            </TouchableOpacity>
                        </View>
                    </View>
                </View>
            </Modal>

            {/* MODAL INTERNO: DERIVAR TÉCNICO */}
            <Modal
                animationType="slide"
                transparent={true}
                visible={modalReasignarVisible}
                onRequestClose={() => setModalReasignarVisible(false)}
            >
                <View style={styles.modalOverlay}>
                    <View style={styles.modalContent}>
                        <Text style={styles.modalTitle}>Derivar Soporte a otro Técnico</Text>

                        <Text style={styles.inputMiniLabel}>1. Selecciona al técnico *</Text>
                        <View style={{ maxHeight: 160, marginBottom: 12 }}>
                            <ScrollView>
                                {tecnicos.map((tec) => {
                                    const esMismo = ticket.nombre_tecnico && tec.nombre === ticket.nombre_tecnico;
                                    return (
                                        <TouchableOpacity
                                            key={tec.id}
                                            disabled={Boolean(esMismo)}
                                            style={[
                                                styles.selectOptionRow,
                                                esMismo && { opacity: 0.5, backgroundColor: '#f1f5f9' },
                                                idNuevoTecnico === tec.id && styles.selectOptionRowActive,
                                            ]}
                                            onPress={() => setIdNuevoTecnico(tec.id)}
                                        >
                                            <AppIcon
                                                name="user"
                                                size={15}
                                                color={idNuevoTecnico === tec.id ? '#164a96' : '#64748b'}
                                            />
                                            <Text
                                                style={[
                                                    styles.selectOptionText,
                                                    idNuevoTecnico === tec.id && styles.selectOptionTextActive,
                                                ]}
                                            >
                                                {tec.nombre} {esMismo ? '(Tú)' : ''}
                                            </Text>
                                        </TouchableOpacity>
                                    );
                                })}
                            </ScrollView>
                        </View>

                        <Text style={styles.inputMiniLabel}>2. Justificación *</Text>
                        <TextInput
                            style={[styles.textInputArea, { height: 80 }]}
                            multiline
                            placeholder="Explica el motivo de la derivación..."
                            placeholderTextColor="#94a3b8"
                            value={motivoDerivacion}
                            onChangeText={setMotivoDerivacion}
                        />

                        <View style={styles.modalButtons}>
                            <TouchableOpacity
                                style={styles.modalBtnCancel}
                                onPress={() => setModalReasignarVisible(false)}
                            >
                                <Text style={styles.modalBtnCancelText}>Cancelar</Text>
                            </TouchableOpacity>
                            <TouchableOpacity
                                style={[
                                    styles.modalBtnSubmit,
                                    {
                                        backgroundColor: '#e67e22',
                                        opacity: idNuevoTecnico && motivoDerivacion.trim() ? 1 : 0.5,
                                    },
                                ]}
                                onPress={handleDerivarTecnico}
                                disabled={guardandoReasignacion || !idNuevoTecnico || !motivoDerivacion.trim()}
                            >
                                {guardandoReasignacion ? (
                                    <ActivityIndicator color="#fff" />
                                ) : (
                                    <Text style={styles.modalBtnSubmitText}>Confirmar</Text>
                                )}
                            </TouchableOpacity>
                        </View>
                    </View>
                </View>
            </Modal>

            {/* MODAL: HISTORIAL DE TÉCNICOS Y CRONÓMETROS */}
            <Modal
                animationType="fade"
                transparent
                visible={modalHistorialTecnicosVisible}
                onRequestClose={() => setModalHistorialTecnicosVisible(false)}
            >
                <View style={styles.modalOverlay}>
                    <View style={[styles.modalContent, { maxWidth: 560, padding: 20 }]}>
                        <View style={styles.historyModalHeader}>
                            <View style={styles.historyModalHeaderLeft}>
                                <View style={styles.historyHeaderIconBadge}>
                                    <AppIcon name="users" size={18} color="#164a96" />
                                </View>
                                <View style={{ flex: 1 }}>
                                    <Text style={styles.historyModalTitle}>Técnicos involucrados</Text>
                                    <Text style={styles.historyModalSubtitle}>
                                        {historialTecnicos.length} {historialTecnicos.length === 1 ? 'etapa registrada' : 'etapas registradas'}
                                    </Text>
                                </View>
                            </View>
                            <TouchableOpacity
                                style={styles.historyCloseBtn}
                                onPress={() => setModalHistorialTecnicosVisible(false)}
                                activeOpacity={0.75}
                            >
                                <AppIcon name="close" size={16} color="#475569" />
                            </TouchableOpacity>
                        </View>

                        <ScrollView style={{ maxHeight: 460 }} showsVerticalScrollIndicator={true}>
                            {historialTecnicos.map((etapa, index) => {
                                const esUltimo = index === historialTecnicos.length - 1;
                                const tipoMov = String(etapa.tipo_movimiento || 'MOVIMIENTO HISTÓRICO').toUpperCase();
                                const esDerivacion = tipoMov.includes('DERIV');
                                const esReasignacion = tipoMov.includes('REASIG');
                                const badgeBg = esDerivacion ? '#fef3c7' : esReasignacion ? '#f3e8ff' : '#dbeafe';
                                const badgeColor = esDerivacion ? '#b45309' : esReasignacion ? '#6d28d9' : '#1d4ed8';
                                const nombreDestino = etapa.fecha_asignacion
                                    ? `${etapa.nombre_tecnico || 'Técnico'} (TÉCNICO)`
                                    : `${etapa.nombre_destino || 'Técnico receptor'} (TÉCNICO)`;

                                return (
                                    <View key={etapa.id_asignacion || index} style={styles.timelineRow}>
                                        <View style={styles.timelineTrackCol}>
                                            <View style={[styles.timelineStepCircle, esUltimo && styles.timelineStepCircleActive]}>
                                                <Text style={[styles.timelineStepNum, esUltimo && styles.timelineStepNumActive]}>
                                                    {index + 1}
                                                </Text>
                                            </View>
                                            {!esUltimo && <View style={styles.timelineConnector} />}
                                        </View>

                                        <View style={styles.technicianHistoryCard}>
                                            <View style={styles.historyCardTopRow}>
                                                <View style={[styles.historyTypeBadge, { backgroundColor: badgeBg }]}>
                                                    <Text style={[styles.historyTypeBadgeText, { color: badgeColor }]}>
                                                        {tipoMov}
                                                    </Text>
                                                </View>
                                                <Text style={styles.historyStepTag}>Paso {index + 1}</Text>
                                            </View>

                                            <View style={styles.historyFlowBox}>
                                                <View style={styles.historyFlowLine}>
                                                    <Text style={styles.historyFlowLabel}>De:</Text>
                                                    <Text style={styles.historyFlowValue}>
                                                        {etapa.nombre_actor || 'Actor no identificado'}{' '}
                                                        <Text style={styles.historyFlowRole}>({etapa.rol_actor || 'ROL'})</Text>
                                                    </Text>
                                                </View>
                                                <View style={styles.historyFlowLine}>
                                                    <Text style={styles.historyFlowLabel}>Para:</Text>
                                                    <Text style={[styles.historyFlowValue, { color: '#164a96' }]}>
                                                        {nombreDestino}
                                                    </Text>
                                                </View>
                                            </View>

                                            <View style={styles.historyMetricsRow}>
                                                <View style={styles.historyMetricCard}>
                                                    <View style={styles.historyMetricHeader}>
                                                        <AppIcon name="clock" size={12} color="#1d4ed8" />
                                                        <Text style={styles.techMetricLabel}>TIEMPO ASIGNADO</Text>
                                                    </View>
                                                    <Text style={styles.techMetricValue}>
                                                        {etapa.segundos_desde_asignacion === null
                                                            ? 'No disponible'
                                                            : formatearSegundos(Number(etapa.segundos_desde_asignacion || 0))}
                                                    </Text>
                                                    <Text style={styles.historyMetricDate}>
                                                        {etapa.fecha_asignacion ? formatearFechaHora(etapa.fecha_asignacion) : 'Sin registro'}
                                                    </Text>
                                                </View>

                                                <View style={[styles.historyMetricCard, { backgroundColor: '#faf5ff', borderColor: '#e9d5ff' }]}>
                                                    <View style={styles.historyMetricHeader}>
                                                        <AppIcon name="wrench" size={12} color="#6d28d9" />
                                                        <Text style={[styles.techMetricLabel, { color: '#6d28d9' }]}>EN PROCESO</Text>
                                                    </View>
                                                    <Text style={[styles.techMetricValue, { color: '#4c1d95' }]}>
                                                        {etapa.segundos_en_proceso === null
                                                            ? (etapa.fecha_asignacion ? 'No iniciado' : 'No disponible')
                                                            : formatearSegundos(Number(etapa.segundos_en_proceso || 0))}
                                                    </Text>
                                                    <Text style={styles.historyMetricDate}>
                                                        {etapa.fecha_inicio ? formatearFechaHora(etapa.fecha_inicio) : 'Sin inicio'}
                                                    </Text>
                                                </View>
                                            </View>

                                            {rolUsuario === RolBandeja.OPERADOR && Boolean(etapa.fecha_en_camino) && (
                                                <View
                                                    style={{
                                                        marginTop: 8,
                                                        padding: 10,
                                                        borderRadius: 10,
                                                        backgroundColor: '#ecfeff',
                                                        borderWidth: 1,
                                                        borderColor: '#a5f3fc',
                                                    }}
                                                >
                                                    <View style={{ flexDirection: 'row', alignItems: 'center', justifyContent: 'space-between', marginBottom: 4 }}>
                                                        <View style={{ flexDirection: 'row', alignItems: 'center', gap: 5 }}>
                                                            <AppIcon name="location" size={12} color="#0e7490" />
                                                            <Text style={{ fontSize: 11, fontWeight: '800', color: '#0e7490' }}>
                                                                UBICACIÓN Y TRAYECTO
                                                            </Text>
                                                        </View>
                                                        {etapa.segundos_en_camino !== null && etapa.segundos_en_camino !== undefined && (
                                                            <Text style={{ fontSize: 11, fontWeight: '800', color: '#155e75' }}>
                                                                {formatearSegundos(Number(etapa.segundos_en_camino || 0))}
                                                            </Text>
                                                        )}
                                                    </View>

                                                    <Text style={{ fontSize: 12, color: '#334155' }}>
                                                        Salió: {formatearFechaHora(etapa.fecha_en_camino)}
                                                    </Text>
                                                    {etapa.latitud_en_camino !== null &&
                                                        etapa.latitud_en_camino !== undefined &&
                                                        etapa.longitud_en_camino !== null &&
                                                        etapa.longitud_en_camino !== undefined &&
                                                        Number.isFinite(Number(etapa.latitud_en_camino)) &&
                                                        Number.isFinite(Number(etapa.longitud_en_camino)) && (
                                                            <TouchableOpacity
                                                                onPress={() => abrirMapa(etapa.latitud_en_camino, etapa.longitud_en_camino)}
                                                                activeOpacity={0.75}
                                                                style={{ marginTop: 3 }}
                                                            >
                                                                <Text style={styles.travelMapLink}>
                                                                    Ver ubicación de salida · {Number(etapa.latitud_en_camino).toFixed(5)}, {Number(etapa.longitud_en_camino).toFixed(5)}
                                                                </Text>
                                                            </TouchableOpacity>
                                                        )}

                                                    {etapa.fecha_llegada ? (
                                                        <>
                                                            <Text style={{ fontSize: 12, color: '#334155', marginTop: 4 }}>
                                                                Detenido: {formatearFechaHora(etapa.fecha_llegada)}
                                                            </Text>
                                                            {etapa.latitud_llegada !== null &&
                                                                etapa.latitud_llegada !== undefined &&
                                                                etapa.longitud_llegada !== null &&
                                                                etapa.longitud_llegada !== undefined &&
                                                                Number.isFinite(Number(etapa.latitud_llegada)) &&
                                                                Number.isFinite(Number(etapa.longitud_llegada)) && (
                                                                    <TouchableOpacity
                                                                        onPress={() => abrirMapa(etapa.latitud_llegada, etapa.longitud_llegada)}
                                                                        activeOpacity={0.75}
                                                                        style={{ marginTop: 3 }}
                                                                    >
                                                                        <Text style={styles.travelMapLink}>
                                                                            Ver ubicación final · {Number(etapa.latitud_llegada).toFixed(5)}, {Number(etapa.longitud_llegada).toFixed(5)}
                                                                        </Text>
                                                                    </TouchableOpacity>
                                                                )}
                                                        </>
                                                    ) : (
                                                        <Text style={{ fontSize: 11, color: '#0e7490', fontWeight: '700', marginTop: 4 }}>
                                                            En camino actualmente
                                                        </Text>
                                                    )}
                                                </View>
                                            )}

                                            {etapa.fecha_derivacion && etapa.nombre_destino ? (
                                                <View style={styles.historyHandoffRow}>
                                                    <AppIcon name="refresh" size={12} color="#475569" />
                                                    <Text style={styles.historyHandoffText}>
                                                        {etapa.rol_derivacion === 'OPERADOR'
                                                            ? 'Reasignado por OPERADOR'
                                                            : etapa.rol_derivacion === 'TECNICO'
                                                                ? 'Derivado por TÉCNICO'
                                                                : 'Transferido'}{' '}
                                                        a <Text style={{ fontWeight: '700', color: '#0f172a' }}>{etapa.nombre_destino}</Text> · {formatearFechaHora(etapa.fecha_derivacion)}
                                                    </Text>
                                                </View>
                                            ) : null}

                                            {(etapa.motivo_derivacion || etapa.motivo_entrada) ? (
                                                <View style={styles.historyReasonBox}>
                                                    <AppIcon name="alert" size={12} color="#b45309" />
                                                    <Text style={styles.technicianHistoryReason}>
                                                        {etapa.motivo_derivacion
                                                            ? `Motivo: ${etapa.motivo_derivacion}`
                                                            : `Motivo registrado: ${etapa.motivo_entrada}`}
                                                    </Text>
                                                </View>
                                            ) : null}
                                        </View>
                                    </View>
                                );
                            })}
                            {historialTecnicos.length === 0 && (
                                <Text style={styles.emptyItemsText}>No se encontró el historial de asignaciones.</Text>
                            )}
                        </ScrollView>

                        <TouchableOpacity
                            style={styles.historyFooterCloseBtn}
                            onPress={() => setModalHistorialTecnicosVisible(false)}
                            activeOpacity={0.85}
                        >
                            <Text style={styles.historyFooterCloseText}>Cerrar</Text>
                        </TouchableOpacity>
                    </View>
                </View>
            </Modal>

            {/* MODAL INTERNO: VER ACTA ADJUNTA */}
            <Modal
                animationType="fade"
                transparent={true}
                visible={modalVerActaVisible}
                onRequestClose={() => setModalVerActaVisible(false)}
            >
                <View style={styles.modalOverlay}>
                    <View style={[styles.modalContent, { maxWidth: 620 }]}>
                        <Text style={styles.modalTitle}>Acta de Soporte Adjunta</Text>
                        {Boolean(nombreActaGuardada) && (
                            <Text style={{ textAlign: 'center', color: '#64748b', fontSize: 12, marginBottom: 10 }}>
                                {nombreActaGuardada}
                            </Text>
                        )}

                        {construirUrlArchivo(rutaActaGuardada || ticket.url_acta) ? (
                            <View style={{ alignItems: 'center', marginBottom: 14 }}>
                                <Image
                                    source={{ uri: construirUrlArchivo(rutaActaGuardada || ticket.url_acta)! }}
                                    style={{ width: '100%', height: 400, borderRadius: 12, backgroundColor: '#f1f5f9' }}
                                    resizeMode="contain"
                                />
                            </View>
                        ) : (
                            <Text style={styles.emptyItemsText}>No se pudo cargar la imagen del acta.</Text>
                        )}

                        <View style={styles.modalButtons}>
                            <TouchableOpacity
                                style={[styles.modalBtnCancel, { marginRight: 'auto' }, descargandoArchivo && { opacity: 0.65 }]}
                                disabled={descargandoArchivo}
                                onPress={() => {
                                    const url = construirUrlArchivo(rutaActaGuardada || ticket.url_acta);
                                    void descargarArchivoDirecto(url, nombreActaGuardada || 'acta-soporte.jpg');
                                }}
                            >
                                {descargandoArchivo ? (
                                    <ActivityIndicator size="small" color="#164a96" />
                                ) : (
                                    <Text style={{ color: '#164a96', fontWeight: 'bold', fontSize: 13 }}>
                                        Descargar
                                    </Text>
                                )}
                            </TouchableOpacity>

                            <TouchableOpacity
                                style={styles.modalBtnSubmit}
                                onPress={() => setModalVerActaVisible(false)}
                            >
                                <Text style={styles.modalBtnSubmitText}>Cerrar</Text>
                            </TouchableOpacity>
                        </View>
                    </View>
                </View>
            </Modal>

            {/* MODAL INTERNO: VER IMAGEN ADJUNTA DEL PROBLEMA (MISMO ESTILO QUE ACTA) */}
            <Modal
                animationType="fade"
                transparent={true}
                visible={imagenAdjuntaModal !== null}
                onRequestClose={() => setImagenAdjuntaModal(null)}
            >
                <View style={styles.modalOverlay}>
                    <View style={[styles.modalContent, { maxWidth: 620 }]}>
                        <Text style={styles.modalTitle}>Imagen Adjunta del Problema</Text>
                        {Boolean(imagenAdjuntaModal?.nombre) && (
                            <Text style={{ textAlign: 'center', color: '#64748b', fontSize: 12, marginBottom: 10 }}>
                                {imagenAdjuntaModal?.nombre}
                            </Text>
                        )}

                        {imagenAdjuntaModal?.url ? (
                            <View style={{ alignItems: 'center', marginBottom: 14 }}>
                                <Image
                                    source={{ uri: imagenAdjuntaModal.url }}
                                    style={{ width: '100%', height: 400, borderRadius: 12, backgroundColor: '#f1f5f9' }}
                                    resizeMode="contain"
                                />
                            </View>
                        ) : (
                            <Text style={styles.emptyItemsText}>No se pudo cargar la imagen adjunta.</Text>
                        )}

                        <View style={styles.modalButtons}>
                            <TouchableOpacity
                                style={[styles.modalBtnCancel, { marginRight: 'auto' }, descargandoArchivo && { opacity: 0.65 }]}
                                disabled={descargandoArchivo}
                                onPress={() => {
                                    void descargarArchivoDirecto(imagenAdjuntaModal?.url, imagenAdjuntaModal?.nombre);
                                }}
                            >
                                {descargandoArchivo ? (
                                    <ActivityIndicator size="small" color="#164a96" />
                                ) : (
                                    <Text style={{ color: '#164a96', fontWeight: 'bold', fontSize: 13 }}>
                                        Descargar
                                    </Text>
                                )}
                            </TouchableOpacity>

                            <TouchableOpacity
                                style={styles.modalBtnSubmit}
                                onPress={() => setImagenAdjuntaModal(null)}
                            >
                                <Text style={styles.modalBtnSubmitText}>Cerrar</Text>
                            </TouchableOpacity>
                        </View>
                    </View>
                </View>
            </Modal>
        </View>
    );
};

const styles = StyleSheet.create({
    screenWrapper: {
        flex: 1,
        backgroundColor: '#f4f7fb',
    },

    // CABECERA FIJA SUPERIOR
    topHeader: {
        backgroundColor: '#164a96',
        paddingBottom: 0,
        position: 'relative',
    },
    topBarRow: {
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'space-between',
        paddingHorizontal: 16,
        paddingBottom: 8,
        width: '100%',
    },
    backButton: {
        width: 38,
        height: 38,
        borderRadius: 10,
        backgroundColor: 'rgba(255, 255, 255, 0.16)',
        justifyContent: 'center',
        alignItems: 'center',
    },
    navBarTitle: {
        color: '#ffffff',
        fontSize: 18,
        fontWeight: 'bold',
        textAlign: 'center',
        flex: 1,
        marginHorizontal: 12,
    },
    headerSpacerRight: {
        width: 38,
        height: 38,
    },
    headerBottomWave: {
        width: '100%',
        height: 24,
        marginTop: 4,
    },

    // SCROLL Y CONTENEDOR
    keyboardAvoiding: {
        flex: 1,
    },
    scrollView: {
        flex: 1,
    },
    scrollContent: {
        paddingTop: 14,
        paddingBottom: 40,
    },
    innerContainer: {
        width: '100%',
        alignSelf: 'center',
        paddingHorizontal: 16,
    },

    // BANNER INSTITUCIONAL
    sectionBannerRow: {
        flexDirection: 'row',
        alignItems: 'center',
        marginBottom: 14,
    },
    blueCircleIcon: {
        width: 44,
        height: 44,
        borderRadius: 22,
        backgroundColor: '#1d68d8',
        justifyContent: 'center',
        alignItems: 'center',
        marginRight: 12,
        elevation: 2,
    },
    sectionBannerTitle: {
        fontSize: 18,
        fontWeight: 'bold',
        color: '#0f294a',
    },
    sectionBannerSub: {
        fontSize: 12.5,
        color: '#475569',
        marginTop: 1,
    },

    // TARJETAS
    card: {
        backgroundColor: '#ffffff',
        borderRadius: 16,
        padding: 16,
        marginBottom: 14,
        borderWidth: 1,
        borderColor: '#e2e8f0',
        elevation: 2,
        shadowColor: '#0f172a',
        shadowOffset: { width: 0, height: 1 },
        shadowOpacity: 0.05,
        shadowRadius: 4,
    },

    // RESUMEN BLOQUE 1
    summaryBlock1: {
        backgroundColor: '#f8fafc',
        borderRadius: 12,
        padding: 12,
        marginBottom: 10,
    },
    summaryRow: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
    },
    summaryMiniLabel: {
        fontSize: 11,
        fontWeight: '700',
        color: '#64748b',
        letterSpacing: 0.5,
    },
    summaryValueName: {
        fontSize: 15,
        fontWeight: 'bold',
        color: '#0f294a',
        marginTop: 3,
    },
    statusBadge: {
        paddingHorizontal: 10,
        paddingVertical: 4,
        borderRadius: 8,
        borderWidth: 1,
    },
    statusBadgeText: {
        fontSize: 11.5,
        fontWeight: 'bold',
    },
    priorityBadge: {
        flexDirection: 'row',
        alignItems: 'center',
        paddingHorizontal: 8,
        paddingVertical: 3,
        borderRadius: 6,
        borderWidth: 1,
    },
    priorityDot: {
        width: 7,
        height: 7,
        borderRadius: 4,
        marginRight: 5,
    },
    priorityBadgeText: {
        fontSize: 11,
        fontWeight: 'bold',
    },

    // RESUMEN BLOQUE 2 (2 COLUMNAS)
    summaryBlock2: {
        flexDirection: 'row',
        gap: 10,
        marginBottom: 12,
    },
    summaryColCard: {
        flex: 1,
        backgroundColor: '#f8fafc',
        borderRadius: 12,
        padding: 12,
    },
    waitAssignmentSummary: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
        backgroundColor: '#eff6ff',
        borderRadius: 10,
        paddingHorizontal: 12,
        paddingVertical: 9,
        marginBottom: 12,
    },
    summaryColLabel: {
        fontSize: 11,
        fontWeight: '700',
        color: '#64748b',
        letterSpacing: 0.3,
    },
    summaryColValue: {
        fontSize: 14,
        fontWeight: 'bold',
        color: '#0f294a',
        marginTop: 4,
    },

    // UBICACIÓN
    locationSection: {
        borderTopWidth: 1,
        borderTopColor: '#e2e8f0',
        paddingTop: 12,
        marginTop: 2,
    },
    sectionHeaderRow: {
        flexDirection: 'row',
        alignItems: 'center',
        marginBottom: 10,
    },
    sectionIconPill: {
        width: 28,
        height: 28,
        borderRadius: 8,
        backgroundColor: '#eef4ff',
        justifyContent: 'center',
        alignItems: 'center',
        marginRight: 8,
    },
    sectionCardTitle: {
        fontSize: 15,
        fontWeight: 'bold',
        color: '#0f294a',
    },
    infoContentBox: {
        backgroundColor: '#f8fafc',
        borderRadius: 10,
        padding: 12,
        borderWidth: 1,
        borderColor: '#e2e8f0',
    },
    locationTitle: {
        fontSize: 14,
        fontWeight: 'bold',
        color: '#1e293b',
    },
    locationSub: {
        fontSize: 12.5,
        color: '#64748b',
        marginTop: 2,
    },
    travelStatusBox: {
        marginTop: 12,
        padding: 12,
        backgroundColor: '#f0fdfa',
        borderWidth: 1,
        borderColor: '#a5f3fc',
        borderRadius: 12,
    },
    travelStatusPill: {
        alignSelf: 'flex-start',
        paddingHorizontal: 9,
        paddingVertical: 4,
        backgroundColor: '#cffafe',
        borderRadius: 20,
        marginBottom: 8,
    },
    travelStatusPillText: {
        color: '#0e7490',
        fontSize: 10,
        fontWeight: '800',
        letterSpacing: 0.3,
    },
    travelHelpText: {
        color: '#64748b',
        fontSize: 12.5,
    },
    travelInfoRow: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        gap: 8,
        marginTop: 4,
    },
    travelInfoLabel: {
        color: '#475569',
        fontSize: 12,
        fontWeight: '600',
    },
    travelInfoValue: {
        flex: 1,
        color: '#0f294a',
        fontSize: 12,
        fontWeight: '700',
        textAlign: 'right',
    },
    travelMapLink: {
        color: '#0369a1',
        fontSize: 11.5,
        fontWeight: '600',
        textDecorationLine: 'underline',
        marginTop: 5,
        marginBottom: 4,
    },
    travelActionButton: {
        marginTop: 12,
        marginBottom: 0,
    },

    // ÍTEMS DEL PROBLEMA
    itemBox: {
        backgroundColor: '#f8fafc',
        padding: 10,
        borderRadius: 10,
        borderWidth: 1,
        borderColor: '#e2e8f0',
        marginBottom: 8,
    },
    itemType: {
        fontSize: 13.5,
        fontWeight: 'bold',
        color: '#0f294a',
        marginLeft: 6,
    },
    itemDesc: {
        fontSize: 13,
        color: '#475569',
        marginTop: 2,
        lineHeight: 18,
    },
    problemAttachment: { flexDirection: 'row', alignItems: 'center', marginTop: 8, alignSelf: 'flex-start' },
    problemAttachmentImage: { width: 56, height: 56, borderRadius: 8, backgroundColor: '#e2e8f0' },
    problemAttachmentText: { marginLeft: 9, color: '#1d5bbf', fontSize: 12, fontWeight: '600' },
    emptyItemsText: {
        textAlign: 'center',
        color: '#94a3b8',
        fontSize: 13,
        paddingVertical: 10,
        fontStyle: 'italic',
    },

    // FICHAS INFORMATIVAS
    techAssignedBox: {
        flexDirection: 'row',
        alignItems: 'center',
        backgroundColor: '#eff6ff',
        borderRadius: 10,
        padding: 10,
        borderWidth: 1,
        borderColor: '#bfdbfe',
        marginTop: 6,
    },
    techAssignedLabel: {
        fontSize: 11,
        fontWeight: '600',
        color: '#1d4ed8',
    },
    techAssignedName: {
        fontSize: 13.5,
        fontWeight: 'bold',
        color: '#1e3a8a',
    },
    operatorRatingBox: {
        flexDirection: 'row',
        alignItems: 'center',
        backgroundColor: '#f0fdf4',
        borderRadius: 10,
        padding: 10,
        borderWidth: 1,
        borderColor: '#bbf7d0',
        marginTop: 8,
    },
    operatorRatingEmoji: {
        fontSize: 25,
        marginRight: 10,
    },
    operatorRatingTitle: {
        color: '#166534',
        fontSize: 11,
        fontWeight: '600',
    },
    operatorRatingValue: {
        color: '#14532d',
        fontSize: 14,
        fontWeight: '700',
        marginTop: 2,
    },
    operatorInstructionsBox: {
        backgroundColor: '#eff6ff',
        borderWidth: 1,
        borderColor: '#bfdbfe',
        borderRadius: 12,
        padding: 12,
        marginTop: 10,
    },
    operatorInstructionsHeading: {
        flexDirection: 'row',
        alignItems: 'center',
        marginBottom: 6,
    },
    operatorInstructionsTitle: {
        color: '#1e40af',
        fontSize: 13,
        fontWeight: '700',
        marginLeft: 6,
    },
    operatorInstructionsText: {
        color: '#1e293b',
        fontSize: 13,
        lineHeight: 19,
    },
    operatorInstructionsInput: {
        minHeight: 88,
        marginBottom: 4,
    },
    instructionsHelperText: {
        color: '#64748b',
        fontSize: 12,
        marginBottom: 14,
    },
    viewTechnicianHistoryButton: {
        width: '100%',
        flexDirection: 'row',
        alignItems: 'center',
        marginTop: 8,
        paddingVertical: 13,
        paddingHorizontal: 12,
        borderRadius: 10,
        backgroundColor: '#eff6ff',
        borderWidth: 1,
        borderColor: '#bfdbfe',
    },
    viewTechnicianHistoryText: {
        marginLeft: 8,
        color: '#1d5bbf',
        fontSize: 13.5,
        fontWeight: '700',
    },
    historyModalHeader: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
        marginBottom: 14,
        paddingBottom: 12,
        borderBottomWidth: 1,
        borderBottomColor: '#e2e8f0',
    },
    historyModalHeaderLeft: {
        flexDirection: 'row',
        alignItems: 'center',
        flex: 1,
        marginRight: 10,
    },
    historyHeaderIconBadge: {
        width: 38,
        height: 38,
        borderRadius: 11,
        backgroundColor: '#eff6ff',
        borderWidth: 1,
        borderColor: '#bfdbfe',
        justifyContent: 'center',
        alignItems: 'center',
        marginRight: 10,
    },
    historyModalTitle: {
        fontSize: 17,
        fontWeight: 'bold',
        color: '#0f294a',
    },
    historyModalSubtitle: {
        fontSize: 12,
        color: '#64748b',
        marginTop: 1,
    },
    historyCloseBtn: {
        width: 34,
        height: 34,
        borderRadius: 17,
        backgroundColor: '#f1f5f9',
        justifyContent: 'center',
        alignItems: 'center',
    },
    timelineRow: {
        flexDirection: 'row',
        alignItems: 'stretch',
    },
    timelineTrackCol: {
        width: 28,
        alignItems: 'center',
        marginRight: 8,
    },
    timelineStepCircle: {
        width: 24,
        height: 24,
        borderRadius: 12,
        backgroundColor: '#e2e8f0',
        justifyContent: 'center',
        alignItems: 'center',
        marginTop: 10,
    },
    timelineStepCircleActive: {
        backgroundColor: '#164a96',
    },
    timelineStepNum: {
        fontSize: 11,
        fontWeight: 'bold',
        color: '#475569',
    },
    timelineStepNumActive: {
        color: '#ffffff',
    },
    timelineConnector: {
        width: 2,
        flex: 1,
        backgroundColor: '#cbd5e1',
        marginVertical: 4,
    },
    technicianHistoryCard: {
        flex: 1,
        padding: 13,
        borderRadius: 12,
        borderWidth: 1,
        borderColor: '#e2e8f0',
        backgroundColor: '#ffffff',
        marginBottom: 12,
        shadowColor: '#0f172a',
        shadowOffset: { width: 0, height: 1 },
        shadowOpacity: 0.04,
        shadowRadius: 4,
        elevation: 1,
    },
    historyCardTopRow: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
        marginBottom: 8,
        gap: 8,
    },
    historyTypeBadge: {
        paddingHorizontal: 8,
        paddingVertical: 3,
        borderRadius: 6,
    },
    historyTypeBadgeText: {
        fontSize: 10.5,
        fontWeight: '800',
        letterSpacing: 0.3,
    },
    historyStepTag: {
        fontSize: 11,
        fontWeight: '600',
        color: '#64748b',
    },
    historyFlowBox: {
        backgroundColor: '#f8fafc',
        borderRadius: 8,
        padding: 9,
        borderWidth: 1,
        borderColor: '#e2e8f0',
        marginBottom: 9,
        gap: 4,
    },
    historyFlowLine: {
        flexDirection: 'row',
        alignItems: 'flex-start',
        gap: 6,
    },
    historyFlowLabel: {
        fontSize: 11.5,
        fontWeight: '700',
        color: '#64748b',
        width: 34,
    },
    historyFlowValue: {
        flex: 1,
        fontSize: 12,
        fontWeight: '600',
        color: '#0f172a',
    },
    historyFlowRole: {
        fontSize: 11,
        fontWeight: '500',
        color: '#64748b',
    },
    historyMetricsRow: {
        flexDirection: 'row',
        gap: 8,
    },
    historyMetricCard: {
        flex: 1,
        backgroundColor: '#eff6ff',
        borderWidth: 1,
        borderColor: '#dbeafe',
        borderRadius: 8,
        padding: 8,
    },
    historyMetricHeader: {
        flexDirection: 'row',
        alignItems: 'center',
        gap: 4,
        marginBottom: 3,
    },
    techMetricLabel: {
        fontSize: 9.5,
        fontWeight: '800',
        color: '#1d4ed8',
        letterSpacing: 0.3,
    },
    techMetricValue: {
        fontSize: 13.5,
        fontWeight: '800',
        color: '#0f294a',
    },
    historyMetricDate: {
        fontSize: 10.5,
        color: '#64748b',
        marginTop: 2,
    },
    historyHandoffRow: {
        flexDirection: 'row',
        alignItems: 'flex-start',
        gap: 6,
        marginTop: 9,
        paddingTop: 8,
        borderTopWidth: 1,
        borderTopColor: '#f1f5f9',
    },
    historyHandoffText: {
        flex: 1,
        fontSize: 11.5,
        color: '#475569',
        lineHeight: 16,
    },
    historyReasonBox: {
        flexDirection: 'row',
        alignItems: 'flex-start',
        gap: 6,
        marginTop: 8,
        backgroundColor: '#fffbeb',
        borderRadius: 8,
        padding: 8,
        borderLeftWidth: 3,
        borderLeftColor: '#f59e0b',
    },
    technicianHistoryReason: {
        flex: 1,
        color: '#92400e',
        fontSize: 12,
        fontWeight: '600',
    },
    historyFooterCloseBtn: {
        marginTop: 10,
        backgroundColor: '#164a96',
        borderRadius: 10,
        paddingVertical: 11,
        alignItems: 'center',
    },
    historyFooterCloseText: {
        color: '#ffffff',
        fontSize: 13.5,
        fontWeight: '700',
    },
    warningAlertBox: {
        backgroundColor: '#fff8e1',
        borderRadius: 8,
        padding: 10,
        borderLeftWidth: 4,
        borderLeftColor: '#f39c12',
        marginTop: 8,
    },
    warningAlertTitle: {
        fontSize: 12.5,
        fontWeight: 'bold',
        color: '#d35400',
        marginLeft: 5,
    },
    warningAlertText: {
        fontSize: 12,
        color: '#555',
        marginTop: 2,
    },
    dangerAlertBox: {
        backgroundColor: '#fdecea',
        borderRadius: 8,
        padding: 10,
        borderLeftWidth: 4,
        borderLeftColor: '#e74c3c',
        marginTop: 8,
    },
    dangerAlertTitle: {
        fontSize: 12.5,
        fontWeight: 'bold',
        color: '#c0392b',
        marginLeft: 5,
        flex: 1,
    },
    dangerAlertText: {
        fontSize: 12,
        color: '#922b21',
        marginTop: 2,
    },
    insistenciaLista: {
        marginTop: 6,
        gap: 6,
    },
    insistenciaItemRow: {
        flexDirection: 'row',
        alignItems: 'flex-start',
        gap: 6,
        paddingTop: 5,
        borderTopWidth: 1,
        borderTopColor: '#f5b7b1',
    },
    insistenciaItemIndex: {
        fontSize: 11,
        fontWeight: 'bold',
        color: '#b91c1c',
        backgroundColor: '#fadbd8',
        paddingHorizontal: 6,
        paddingVertical: 1,
        borderRadius: 6,
        marginTop: 1,
    },
    insistenciaItemFecha: {
        fontSize: 11,
        color: '#c0392b',
        marginTop: 1,
    },

    // ACCIONES
    actionSectionTitle: {
        fontSize: 15,
        fontWeight: 'bold',
        color: '#0f294a',
        marginBottom: 10,
    },
    ratingSubtitle: {
        color: '#64748b',
        fontSize: 13,
        marginTop: -4,
        marginBottom: 14,
    },
    ratingOptions: {
        flexDirection: 'row',
        gap: 8,
    },
    ratingOption: {
        flex: 1,
        minHeight: 92,
        alignItems: 'center',
        justifyContent: 'center',
        paddingVertical: 10,
        paddingHorizontal: 4,
        backgroundColor: '#f8fafc',
        borderWidth: 1,
        borderColor: '#dbe5f1',
        borderRadius: 12,
    },
    ratingOptionSelected: {
        backgroundColor: '#eff6ff',
        borderColor: '#1d5bbf',
        borderWidth: 2,
    },
    ratingEmoji: {
        fontSize: 32,
        marginBottom: 5,
    },
    ratingLabel: {
        color: '#334155',
        fontSize: 12,
        fontWeight: '700',
        textAlign: 'center',
    },
    ratingLabelSelected: {
        color: '#1d4ed8',
    },
    ratingSaving: {
        alignItems: 'center',
        flexDirection: 'row',
        gap: 8,
        justifyContent: 'center',
        marginTop: 12,
    },
    ratingSavingText: {
        color: '#475569',
        fontSize: 12,
    },
    ratingThanks: {
        alignItems: 'center',
        backgroundColor: '#f0fdf4',
        borderColor: '#bbf7d0',
        borderRadius: 12,
        borderWidth: 1,
        padding: 14,
    },
    ratingThanksEmoji: {
        fontSize: 34,
        marginBottom: 5,
    },
    ratingThanksText: {
        color: '#166534',
        fontSize: 13,
        fontWeight: '600',
        textAlign: 'center',
    },
    inputMiniLabel: {
        fontSize: 12,
        fontWeight: '600',
        color: '#475569',
        marginBottom: 6,
    },
    textInputArea: {
        borderWidth: 1,
        borderColor: '#cbd5e1',
        borderRadius: 10,
        padding: 10,
        fontSize: 13.5,
        color: '#1e293b',
        backgroundColor: '#ffffff',
        minHeight: 70,
        textAlignVertical: 'top',
        marginBottom: 12,
    },
    primaryActionBtn: {
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'center',
        gap: 8,
        backgroundColor: '#164a96',
        borderRadius: 10,
        paddingVertical: 12,
        paddingHorizontal: 16,
    },
    primaryActionBtnText: {
        color: '#ffffff',
        fontWeight: 'bold',
        fontSize: 14,
    },
    secondaryActionBtn: {
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'center',
        gap: 6,
        borderWidth: 1,
        borderColor: '#164a96',
        borderRadius: 10,
        paddingVertical: 10,
        marginTop: 10,
        backgroundColor: 'transparent',
    },
    secondaryActionBtnText: {
        color: '#164a96',
        fontWeight: 'bold',
        fontSize: 13,
    },

    // GRILLA PRIORIDAD
    priorityGrid: {
        flexDirection: 'row',
        flexWrap: 'wrap',
        gap: 8,
        marginBottom: 12,
    },
    priorityOptionCard: {
        width: '48%',
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'center',
        paddingVertical: 8,
        borderRadius: 8,
        borderWidth: 1,
    },
    priorityOptionText: {
        fontSize: 13,
        fontWeight: 'bold',
    },

    // SELECTORES
    selectOptionRow: {
        flexDirection: 'row',
        alignItems: 'center',
        padding: 10,
        borderRadius: 8,
        borderWidth: 1,
        borderColor: '#e2e8f0',
        backgroundColor: '#f8fafc',
        marginBottom: 6,
    },
    selectOptionRowActive: {
        borderColor: '#164a96',
        backgroundColor: '#eff6ff',
    },
    selectOptionText: {
        fontSize: 13,
        color: '#334155',
        marginLeft: 8,
    },
    selectOptionTextActive: {
        color: '#164a96',
        fontWeight: 'bold',
    },

    // CRONÓMETROS
    timerCard: {
        flex: 1,
        backgroundColor: '#f8fafc',
        borderRadius: 10,
        padding: 10,
        borderWidth: 1,
        borderColor: '#e2e8f0',
        alignItems: 'center',
    },
    timerCardLabel: {
        fontSize: 11,
        fontWeight: '600',
        color: '#64748b',
    },
    timerCardNumber: {
        fontSize: 15,
        fontWeight: 'bold',
        marginTop: 4,
    },
    uploadActaBtn: {
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'center',
        gap: 8,
        paddingVertical: 10,
        paddingHorizontal: 12,
        borderRadius: 10,
        borderWidth: 1,
        borderColor: '#cbd5e1',
        borderStyle: 'dashed',
        backgroundColor: '#f8fafc',
        marginBottom: 12,
    },
    actaUploadActions: { flexDirection: 'row', gap: 8 },
    actaUploadAction: { flex: 1, marginBottom: 12, minWidth: 0 },
    uploadActaBtnText: {
        fontSize: 13,
        color: '#164a96',
        fontWeight: '600',
    },

    // HISTORIAL METRICAS
    historyMetricsBox: {
        backgroundColor: '#f8fafc',
        borderRadius: 10,
        padding: 12,
        borderWidth: 1,
        borderColor: '#e2e8f0',
        gap: 6,
    },
    historyMetricRow: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
    },
    historyMetricLabel: {
        fontSize: 12,
        color: '#64748b',
        fontWeight: '600',
    },
    historyMetricValue: {
        fontSize: 13,
        fontWeight: 'bold',
        color: '#1e293b',
    },

    // ONDAS INFERIORES
    bottomWavesWrapper: {
        height: 44,
        width: '100%',
        overflow: 'hidden',
        marginTop: 10,
    },
    bottomWavesImg: {
        width: '100%',
        height: 44,
    },

    // MODALES INTERNOS
    modalOverlay: {
        flex: 1,
        backgroundColor: 'rgba(0, 0, 0, 0.5)',
        justifyContent: 'center',
        padding: 20,
    },
    modalContent: {
        backgroundColor: '#ffffff',
        borderRadius: 16,
        padding: 20,
        width: '100%',
        maxWidth: 480,
        alignSelf: 'center',
        elevation: 6,
    },
    modalTitle: {
        fontSize: 17,
        fontWeight: 'bold',
        color: '#0f294a',
        marginBottom: 12,
        textAlign: 'center',
    },
    optionsRow: {
        flexDirection: 'row',
        gap: 8,
        marginBottom: 12,
    },
    miniOption: {
        flex: 1,
        alignItems: 'center',
        paddingVertical: 10,
        paddingHorizontal: 6,
        borderRadius: 8,
        borderWidth: 1,
        borderColor: '#e2e8f0',
        backgroundColor: '#f8fafc',
    },
    miniOptionActive: {
        borderColor: '#164a96',
        backgroundColor: '#eff6ff',
    },
    miniOptionText: {
        fontSize: 11,
        color: '#64748b',
        marginTop: 4,
        textAlign: 'center',
    },
    miniOptionTextActive: {
        color: '#164a96',
        fontWeight: 'bold',
    },
    modalButtons: {
        flexDirection: 'row',
        justifyContent: 'flex-end',
        alignItems: 'center',
        gap: 10,
        marginTop: 8,
    },
    modalBtnCancel: {
        paddingVertical: 8,
        paddingHorizontal: 14,
    },
    modalBtnCancelText: {
        color: '#64748b',
        fontWeight: '600',
        fontSize: 14,
    },
    modalBtnSubmit: {
        backgroundColor: '#164a96',
        borderRadius: 8,
        paddingVertical: 9,
        paddingHorizontal: 18,
    },
    modalBtnSubmitText: {
        color: '#ffffff',
        fontWeight: 'bold',
        fontSize: 14,
    },
});
