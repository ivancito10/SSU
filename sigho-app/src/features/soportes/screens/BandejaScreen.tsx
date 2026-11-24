import React, { useEffect, useState, useCallback, useRef, useMemo } from 'react';
import {
    View,
    Text,
    StyleSheet,
    FlatList,
    ActivityIndicator,
    RefreshControl,
    TouchableOpacity,
    TextInput,
    Modal,
    ScrollView,
    KeyboardAvoidingView,
    Image,
    Platform,
    Linking,
    useWindowDimensions,
} from 'react-native';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import { obtenerItem, eliminarItem } from '../../../core/utils/storage';
import { dispararAlertaInsistenciaDispositivo } from '../../../core/utils/notifications';
import { theme } from '../../../core/theme/theme';
import { crearSoporteManual, generarEnlaceReporteExcel, obtenerBandeja, obtenerSedes, obtenerConsultoriosPorSede } from '../api/soporte.service';
import { TicketSoporte, RolBandeja } from '../types/soporte.types';
import { CardTicket } from '../components/CardTicket';
import { MenuLateralModal } from '../../../core/components/MenuLateralModal';
import { AppIcon } from '../../../core/components/AppIcon';
import { AppAlert } from '../../../core/components/AlertaGlobalModal';
import { NotificacionesCampana } from '../../../core/components/NotificacionesCampana';

export const BandejaScreen = ({ navigation, onLogout, rolActivo, rolesDisponibles = [], onCambiarRol, solicitudDesdeNotificacion, onNotificacionAbierta, onNotificacionSeleccionada }: any) => {
    const { width } = useWindowDimensions();
    const insets = useSafeAreaInsets();
    const esEscritorio = Platform.OS === 'web' && width > 768;
    const paddingBottomNav = Platform.OS === 'web' ? 10 : Math.max(insets.bottom, 12);

    const [tickets, setTickets] = useState<TicketSoporte[]>([]);
    const [ticketsDerivadas, setTicketsDerivadas] = useState<TicketSoporte[]>([]);
    const [cargando, setCargando] = useState(true);
    const [refrescando, setRefrescando] = useState(false);
    const [rolUsuario, setRolUsuario] = useState<RolBandeja>((rolActivo as RolBandeja) || RolBandeja.USUARIO);
    const [tabActivo, setTabActivo] = useState<'PENDIENTES' | 'CONCLUIDAS'>('PENDIENTES');
    const [filtroKpi, setFiltroKpi] = useState<'PENDIENTES' | 'ASIGNADAS' | 'EN_PROCESO' | 'DERIVADAS' | 'FINALIZADAS' | 'ACTIVOS' | 'TODOS' | null>(null);
    const [filtroFechaHistorial, setFiltroFechaHistorial] = useState<'TODAS' | 'HOY' | 'AYER' | 'SEMANA' | 'MES' | 'ESPECIFICA'>('TODAS');
    const [fechaEspecificaHistorial, setFechaEspecificaHistorial] = useState<string | null>(null);
    const [modalCalendarioVisible, setModalCalendarioVisible] = useState(false);
    const [exportandoExcel, setExportandoExcel] = useState(false);
    const [mesCalendario, setMesCalendario] = useState(() => {
        const hoy = new Date();
        return new Date(hoy.getFullYear(), hoy.getMonth(), 1);
    });
    const [menuLateralVisible, setMenuLateralVisible] = useState(false);
    const [modalSoporteManual, setModalSoporteManual] = useState(false);
    const [guardandoSoporteManual, setGuardandoSoporteManual] = useState(false);
    const [errorSoporteManual, setErrorSoporteManual] = useState('');
    const [formSoporteManual, setFormSoporteManual] = useState({
        solicitante_nombre: '',
        sede: '',
        oficina: '',
        piso: '',
        consultorio: '',
        descripcion: '',
        prioridad: 'MEDIA' as 'BAJA' | 'MEDIA' | 'ALTA',
    });
    const [sedesManual, setSedesManual] = useState<Array<{ id: number; piso: string; ubicacion: string }>>([]);
    const [cargandoSedesManual, setCargandoSedesManual] = useState(false);
    const [idSedeManualSeleccionada, setIdSedeManualSeleccionada] = useState<number | null>(null);
    const [consultoriosManual, setConsultoriosManual] = useState<Array<{ id: number; numero_consultorio: string; id_sedes: number }>>([]);
    const [cargandoConsultoriosManual, setCargandoConsultoriosManual] = useState(false);
    const [mostrarConsultoriosManual, setMostrarConsultoriosManual] = useState(true);
    const [selectorSedeManualVisible, setSelectorSedeManualVisible] = useState(false);
    const [nombreUsuario, setNombreUsuario] = useState('');
    const mostrarMenuCuenta = rolesDisponibles.length > 0;

    // ESTADOS DE LA CAMPANA DE NOTIFICACIONES Y ALERTA TIPO WHATSAPP
    const [alertaEmergente, setAlertaEmergente] = useState<{
        ticket: TicketSoporte;
        mensaje: string;
    } | null>(null);
    const mapaInsistenciasRef = useRef<Record<number, number>>({});
    const primeraCargaRef = useRef<boolean>(true);
    const solicitudNotificacionProcesadaRef = useRef<number | null>(null);
    const manualScrollRef = useRef<React.ElementRef<typeof ScrollView>>(null);

    useEffect(() => {
        if (!alertaEmergente) return;
        const timer = setTimeout(() => {
            setAlertaEmergente(null);
        }, 5000);
        return () => clearTimeout(timer);
    }, [alertaEmergente]);

    const manejarCerrarSesion = async () => {
        await eliminarItem('jwtToken');
        await eliminarItem('userData');
        if (onLogout) onLogout();
    };

    const cargarDatos = useCallback(async (silencioso = false) => {
        try {
            const userDataStr = await obtenerItem('userData');
            if (userDataStr) {
                const userData = JSON.parse(userDataStr);
                setNombreUsuario(userData.nombreCompleto || userData.nombre_completo || userData.nombre || userData.username || '');
                const rol = (rolActivo || userData.rol || RolBandeja.OPERADOR) as RolBandeja;
                setRolUsuario(rol);

                const respuesta = await obtenerBandeja({ rol });

                if (!silencioso) {
                    console.log('DATOS QUE LLEGAN DEL BACKEND:', JSON.stringify(respuesta, null, 2));
                }

                const arregloTickets: TicketSoporte[] = Array.isArray(respuesta?.data || respuesta?.datos || respuesta)
                    ? (respuesta?.data || respuesta?.datos || respuesta)
                    : [];

                if (rol === RolBandeja.TECNICO) {
                    try {
                        const respuestaDerivadas = await obtenerBandeja({ rol, estado: 'DERIVADA' });
                        const listaDerivadas = respuestaDerivadas?.data || respuestaDerivadas?.datos || respuestaDerivadas;
                        setTicketsDerivadas(Array.isArray(listaDerivadas) ? listaDerivadas : []);
                    } catch (error) {
                        console.error('Error al cargar soportes derivados:', error);
                        setTicketsDerivadas([]);
                    }
                } else {
                    setTicketsDerivadas([]);
                }

                if (rol === RolBandeja.OPERADOR || rol === RolBandeja.TECNICO) {
                    arregloTickets.forEach((t) => {
                        const idT = Number(t.id_solicitud || t.id);
                        const cantActual = Number(t.cantidad_resolicitudes || 0);
                        const cantPrevia = mapaInsistenciasRef.current[idT] ?? 0;

                        if (!primeraCargaRef.current && cantActual > cantPrevia && t.estado !== 'FINALIZADA') {
                            const msg = `⚠️ Insistencia: El usuario de ${t.oficina} ${t.piso ? `(${t.piso})` : ''} requiere atención demorada`;
                            setAlertaEmergente({ ticket: t, mensaje: msg });
                            dispararAlertaInsistenciaDispositivo(`🔔 Ticket ${t.codigo_ticket || `#${idT}`} Re-solicitado`, msg);
                        }
                        mapaInsistenciasRef.current[idT] = cantActual;
                    });
                    primeraCargaRef.current = false;
                }

                setTickets(arregloTickets);
            }
        } catch (error) {
            if (!silencioso) {
                console.error('Error al cargar la bandeja:', error);
                setTickets([]);
            }
        } finally {
            if (!silencioso) {
                setCargando(false);
            }
            setRefrescando(false);
        }
    }, [rolActivo]);

    useEffect(() => {
        cargarDatos(false);

        let intervalo: ReturnType<typeof setInterval> | null = setInterval(() => {
            cargarDatos(true);
        }, 4000);

        const unsubscribeFocus = navigation.addListener('focus', () => {
            cargarDatos(true);
            if (!intervalo) {
                intervalo = setInterval(() => {
                    cargarDatos(true);
                }, 4000);
            }
        });

        const unsubscribeBlur = navigation.addListener('blur', () => {
            if (intervalo) {
                clearInterval(intervalo);
                intervalo = null;
            }
        });

        return () => {
            if (intervalo) clearInterval(intervalo);
            unsubscribeFocus();
            unsubscribeBlur();
        };
    }, [navigation, cargarDatos]);

    const onRefresh = useCallback(() => {
        setRefrescando(true);
        cargarDatos(true);
    }, [cargarDatos]);

    useEffect(() => {
        let activo = true;
        const cargarSedes = async () => {
            try {
                setCargandoSedesManual(true);
                const datos = await obtenerSedes();
                if (activo) setSedesManual(Array.isArray(datos) ? datos : []);
            } catch (e) {
                console.error('Error al cargar sedes:', e);
            } finally {
                if (activo) setCargandoSedesManual(false);
            }
        };
        void cargarSedes();
        return () => {
            activo = false;
        };
    }, []);

    const sedesAgrupadasManual = useMemo(() => {
        const mapa: Record<string, Array<{ id: number; piso: string; ubicacion: string }>> = {};
        sedesManual.forEach((s) => {
            const ub = (s.ubicacion || 'OTRA UBICACIÓN').trim();
            if (!mapa[ub]) mapa[ub] = [];
            mapa[ub].push(s);
        });
        return mapa;
    }, [sedesManual]);

    const cargarConsultoriosManual = async (sedeId: number) => {
        setCargandoConsultoriosManual(true);
        try {
            const resp = await obtenerConsultoriosPorSede(sedeId);
            setConsultoriosManual(Array.isArray(resp.datos) ? resp.datos : []);
        } catch (e) {
            console.error('Error al cargar consultorios:', e);
            setConsultoriosManual([]);
        } finally {
            setCargandoConsultoriosManual(false);
        }
    };

    const seleccionarSedeManual = (s: { id: number; piso: string; ubicacion: string }) => {
        const textoPiso = s.ubicacion ? `${s.piso} (${s.ubicacion})` : s.piso;
        setFormSoporteManual((actual) => ({ ...actual, piso: textoPiso, sede: s.ubicacion || s.piso }));
        setIdSedeManualSeleccionada(s.id);
        setMostrarConsultoriosManual(true);
        setSelectorSedeManualVisible(false);
        void cargarConsultoriosManual(s.id);
    };

    const guardarSoporteManual = async () => {
        const pisoLimpio = formSoporteManual.piso.trim();
        const oficinaLimpia = formSoporteManual.oficina.trim();
        const consultorioLimpio = formSoporteManual.consultorio.trim();
        if (!formSoporteManual.solicitante_nombre.trim() || !pisoLimpio || !formSoporteManual.descripcion.trim()) {
            setErrorSoporteManual('Completa el nombre de quien solicitó, el piso/sede y el problema.');
            return;
        }

        const oficinaEnvio = (oficinaLimpia || consultorioLimpio || pisoLimpio).slice(0, 100);

        setGuardandoSoporteManual(true);
        setErrorSoporteManual('');
        try {
            await crearSoporteManual({
                solicitante_nombre: formSoporteManual.solicitante_nombre.trim(),
                oficina: oficinaEnvio,
                piso: pisoLimpio,
                consultorio: consultorioLimpio || undefined,
                descripcion: formSoporteManual.descripcion.trim(),
                prioridad: formSoporteManual.prioridad,
            });
            setModalSoporteManual(false);
            setSelectorSedeManualVisible(false);
            setIdSedeManualSeleccionada(null);
            setConsultoriosManual([]);
            setFormSoporteManual({ solicitante_nombre: '', sede: '', oficina: '', piso: '', consultorio: '', descripcion: '', prioridad: 'MEDIA' });
            setTabActivo('PENDIENTES');
            setFiltroKpi(null);
            await cargarDatos(true);
            AppAlert.alert('Soporte registrado', 'El soporte quedó registrado y está en proceso.');
        } catch (error: any) {
            setErrorSoporteManual(error?.response?.data?.message || 'No se pudo registrar el soporte manual.');
        } finally {
            setGuardandoSoporteManual(false);
        }
    };

    // Métricas para las 3 tarjetas superiores de resumen (KPIs)
    const totalPendientes = tickets.filter((t) => t.estado === 'PENDIENTE').length;
    const totalAsignadas = tickets.filter((t) => t.estado === 'ASIGNADA').length;
    const totalEnProceso = tickets.filter((t) => t.estado === 'EN_PROCESO').length;
    const totalActivos = tickets.filter((t) => t.estado !== 'FINALIZADA').length;
    const totalFinalizados = tickets.filter((t) => t.estado === 'FINALIZADA').length;

    const ticketsFiltrados = filtroKpi === 'DERIVADAS' ? ticketsDerivadas : tickets.filter((ticket) => {
        if (filtroKpi === 'PENDIENTES') return rolUsuario === RolBandeja.TECNICO ? ticket.estado === 'ASIGNADA' : ticket.estado === 'PENDIENTE';
        if (filtroKpi === 'ASIGNADAS') return ticket.estado === 'ASIGNADA';
        if (filtroKpi === 'EN_PROCESO') return ticket.estado === 'EN_PROCESO';
        if (filtroKpi === 'FINALIZADAS') return ticket.estado === 'FINALIZADA';
        if (filtroKpi === 'ACTIVOS') return ticket.estado !== 'FINALIZADA';
        if (filtroKpi === 'TODOS') return true;
        if (tabActivo === 'PENDIENTES') {
            return ticket.estado !== 'FINALIZADA';
        } else {
            return ticket.estado === 'FINALIZADA';
        }
    });

    const esVistaHistorial =
        tabActivo === 'CONCLUIDAS' || filtroKpi === 'FINALIZADAS' || filtroKpi === 'DERIVADAS';

    const diasConHistorialSet = useMemo(() => {
        const set = new Set<string>();
        if (!esVistaHistorial) return set;
        ticketsFiltrados.forEach((ticket) => {
            const raw = ticket.fecha_finalizacion || ticket.fecha_asignacion || ticket.fecha_solicitud;
            if (!raw) return;
            const d = new Date(raw);
            if (Number.isNaN(d.getTime())) return;
            const clave = `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
            set.add(clave);
        });
        return set;
    }, [esVistaHistorial, ticketsFiltrados]);

    const formatearClaveFechaCorta = (clave: string) => {
        const [y, m, d] = clave.split('-');
        return y && m && d ? `${d}/${m}/${y}` : clave;
    };

    const puedeExportarExcel =
        esVistaHistorial &&
        filtroKpi !== 'DERIVADAS' &&
        (rolUsuario === RolBandeja.OPERADOR || rolUsuario === RolBandeja.TECNICO);

    const handleExportarExcel = async () => {
        if (exportandoExcel || !puedeExportarExcel) return;

        const ahora = new Date();
        const fmtIso = (d: Date) =>
            `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
        const hoy = new Date(ahora.getFullYear(), ahora.getMonth(), ahora.getDate());

        let fechaInicio: string | undefined;
        let fechaFin: string | undefined;
        let etiquetaFiltro = 'Todas las fechas';

        if (filtroFechaHistorial === 'HOY') {
            fechaInicio = fmtIso(hoy);
            fechaFin = fmtIso(hoy);
            etiquetaFiltro = `Hoy (${formatearClaveFechaCorta(fechaInicio)})`;
        } else if (filtroFechaHistorial === 'AYER') {
            const ayer = new Date(hoy);
            ayer.setDate(hoy.getDate() - 1);
            fechaInicio = fmtIso(ayer);
            fechaFin = fmtIso(ayer);
            etiquetaFiltro = `Ayer (${formatearClaveFechaCorta(fechaInicio)})`;
        } else if (filtroFechaHistorial === 'SEMANA') {
            const semana = new Date(hoy);
            semana.setDate(hoy.getDate() - 6);
            fechaInicio = fmtIso(semana);
            fechaFin = fmtIso(hoy);
            etiquetaFiltro = `Últimos 7 días (${formatearClaveFechaCorta(fechaInicio)} - ${formatearClaveFechaCorta(fechaFin)})`;
        } else if (filtroFechaHistorial === 'MES') {
            const inicioMes = new Date(ahora.getFullYear(), ahora.getMonth(), 1);
            fechaInicio = fmtIso(inicioMes);
            fechaFin = fmtIso(hoy);
            etiquetaFiltro = `Este mes (${formatearClaveFechaCorta(fechaInicio)} - ${formatearClaveFechaCorta(fechaFin)})`;
        } else if (filtroFechaHistorial === 'ESPECIFICA' && fechaEspecificaHistorial) {
            fechaInicio = fechaEspecificaHistorial;
            fechaFin = fechaEspecificaHistorial;
            etiquetaFiltro = `Fecha ${formatearClaveFechaCorta(fechaEspecificaHistorial)}`;
        }

        setExportandoExcel(true);
        try {
            const { url, filename } = await generarEnlaceReporteExcel({
                rol: rolUsuario === RolBandeja.OPERADOR ? 'OPERADOR' : 'TECNICO',
                fechaInicio,
                fechaFin,
                etiquetaFiltro,
            });

            if (Platform.OS === 'web' && typeof document !== 'undefined') {
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', filename);
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            } else {
                await Linking.openURL(url);
            }
        } catch (error: any) {
            const msg = error?.response?.data?.message || 'No se pudo generar el reporte de Excel.';
            AppAlert.alert('Exportar Excel', Array.isArray(msg) ? msg.join('\n') : msg);
        } finally {
            setExportandoExcel(false);
        }
    };

    type ElementoListaBandeja =
        | { tipo: 'FECHA'; key: string; etiqueta: string; cantidad: number }
        | { tipo: 'TICKET'; key: string; ticket: TicketSoporte };

    const elementosLista = useMemo<ElementoListaBandeja[]>(() => {
        if (!esVistaHistorial) {
            return ticketsFiltrados.map((ticket, index) => ({
                tipo: 'TICKET',
                key: `${ticket.id_solicitud || ticket.id || 't'}-${ticket.id_asignacion || 'a'}-${index}`,
                ticket,
            }));
        }

        const ahora = new Date();
        const inicioHoy = new Date(ahora.getFullYear(), ahora.getMonth(), ahora.getDate());
        const inicioAyer = new Date(inicioHoy);
        inicioAyer.setDate(inicioHoy.getDate() - 1);
        const inicioSemana = new Date(inicioHoy);
        inicioSemana.setDate(inicioHoy.getDate() - 6);
        const inicioMes = new Date(ahora.getFullYear(), ahora.getMonth(), 1);

        const claveLocal = (d: Date) =>
            `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;

        const claveHoy = claveLocal(inicioHoy);
        const claveAyer = claveLocal(inicioAyer);

        const conFecha = ticketsFiltrados
            .map((ticket) => {
                const raw = ticket.fecha_finalizacion || ticket.fecha_asignacion || ticket.fecha_solicitud;
                const d = raw ? new Date(raw) : null;
                const valida = d && !Number.isNaN(d.getTime()) ? d : null;
                return { ticket, fecha: valida };
            })
            .filter(({ fecha }) => {
                if (filtroFechaHistorial === 'TODAS') return true;
                if (!fecha) return false;
                const soloDia = new Date(fecha.getFullYear(), fecha.getMonth(), fecha.getDate());
                if (filtroFechaHistorial === 'HOY') return claveLocal(soloDia) === claveHoy;
                if (filtroFechaHistorial === 'AYER') return claveLocal(soloDia) === claveAyer;
                if (filtroFechaHistorial === 'SEMANA') return soloDia >= inicioSemana;
                if (filtroFechaHistorial === 'MES') return soloDia >= inicioMes;
                if (filtroFechaHistorial === 'ESPECIFICA' && fechaEspecificaHistorial) {
                    return claveLocal(soloDia) === fechaEspecificaHistorial;
                }
                return true;
            })
            .sort((a, b) => (b.fecha?.getTime() || 0) - (a.fecha?.getTime() || 0));

        const grupos = new Map<string, { etiqueta: string; items: TicketSoporte[] }>();

        conFecha.forEach(({ ticket, fecha }) => {
            let clave = 'SIN_FECHA';
            let etiqueta = 'Sin fecha registrada';
            if (fecha) {
                clave = claveLocal(fecha);
                const fechaCorta = fecha.toLocaleDateString('es-BO', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                });
                if (clave === claveHoy) {
                    etiqueta = `Hoy · ${fechaCorta}`;
                } else if (clave === claveAyer) {
                    etiqueta = `Ayer · ${fechaCorta}`;
                } else {
                    const textoLargo = fecha.toLocaleDateString('es-BO', {
                        weekday: 'long',
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric',
                    });
                    etiqueta = textoLargo.charAt(0).toUpperCase() + textoLargo.slice(1);
                }
            }
            if (!grupos.has(clave)) {
                grupos.set(clave, { etiqueta, items: [] });
            }
            grupos.get(clave)!.items.push(ticket);
        });

        const resultado: ElementoListaBandeja[] = [];
        grupos.forEach((grupo, clave) => {
            resultado.push({
                tipo: 'FECHA',
                key: `fecha-${clave}`,
                etiqueta: grupo.etiqueta,
                cantidad: grupo.items.length,
            });
            grupo.items.forEach((ticket, idx) => {
                resultado.push({
                    tipo: 'TICKET',
                    key: `${clave}-${ticket.id_solicitud || ticket.id || 't'}-${ticket.id_asignacion || 'a'}-${idx}`,
                    ticket,
                });
            });
        });

        return resultado;
    }, [esVistaHistorial, ticketsFiltrados, filtroFechaHistorial, fechaEspecificaHistorial]);

    const ticketsConInsistencia = tickets.filter(
        (t) => t.estado !== 'FINALIZADA' && Number(t.cantidad_resolicitudes || 0) > 0
    );

    const kpis =
        rolUsuario === RolBandeja.USUARIO
            ? [
                { valor: totalActivos, etiqueta: 'En proceso', filtro: 'ACTIVOS' as const, tab: 'PENDIENTES' as const },
                { valor: totalFinalizados, etiqueta: 'Concluidas', filtro: 'FINALIZADAS' as const, tab: 'CONCLUIDAS' as const },
                { valor: tickets.length, etiqueta: 'Total solicitudes', filtro: 'TODOS' as const, tab: 'PENDIENTES' as const },
            ]
            : rolUsuario === RolBandeja.OPERADOR
                ? [
                    { valor: totalPendientes, etiqueta: 'Pendientes', filtro: 'PENDIENTES' as const, tab: 'PENDIENTES' as const },
                    { valor: totalAsignadas, etiqueta: 'Asignadas', filtro: 'ASIGNADAS' as const, tab: 'PENDIENTES' as const },
                    { valor: totalEnProceso, etiqueta: 'En proceso', filtro: 'EN_PROCESO' as const, tab: 'PENDIENTES' as const },
                    { valor: totalFinalizados, etiqueta: 'Finalizadas', filtro: 'FINALIZADAS' as const, tab: 'CONCLUIDAS' as const },
                ]
                : [
                    { valor: totalAsignadas, etiqueta: 'Pendientes', filtro: 'PENDIENTES' as const, tab: 'PENDIENTES' as const },
                    { valor: totalEnProceso, etiqueta: 'En proceso', filtro: 'EN_PROCESO' as const, tab: 'PENDIENTES' as const },
                    { valor: ticketsDerivadas.length, etiqueta: 'Derivadas', filtro: 'DERIVADAS' as const, tab: 'CONCLUIDAS' as const },
                    { valor: totalFinalizados, etiqueta: 'Finalizadas', filtro: 'FINALIZADAS' as const, tab: 'CONCLUIDAS' as const },
                ];

    useEffect(() => {
        if (!solicitudDesdeNotificacion) {
            solicitudNotificacionProcesadaRef.current = null;
            return;
        }
        const idObjetivo = Number(solicitudDesdeNotificacion);
        if (solicitudNotificacionProcesadaRef.current === idObjetivo) return;
        const listaCombinada = [...tickets, ...ticketsDerivadas];
        const ticketObjetivo = listaCombinada.find(
            (ticket) => Number(ticket.id_solicitud || ticket.id) === idObjetivo
        );
        if (!ticketObjetivo) return;
        solicitudNotificacionProcesadaRef.current = idObjetivo;
        navigation.navigate('TicketDetalle', { ticket: ticketObjetivo, rolUsuario });
        onNotificacionAbierta?.();
    }, [solicitudDesdeNotificacion, rolUsuario, tickets, ticketsDerivadas, navigation, onNotificacionAbierta]);

    const renderCabecera = () => (
        <View style={styles.topHeader}>
            <View
                style={[
                    styles.topBarRow,
                    esEscritorio && { maxWidth: 880, alignSelf: 'center' },
                ]}
            >
                {/* Izquierda: [ ☰ ] + [ Logo SSU ] */}
                <View style={styles.leftBarGroup}>
                    {mostrarMenuCuenta && (
                        <TouchableOpacity
                            style={styles.iconActionBtn}
                            onPress={() => setMenuLateralVisible(true)}
                            activeOpacity={0.75}
                        >
                            <AppIcon name="menu" size={20} color="#ffffff" />
                        </TouchableOpacity>
                    )}

                    <Image
                        source={require('../../../../assets/7.png')}
                        style={{
                            width: esEscritorio ? 90 : 70,
                            height: esEscritorio ? 36 : 28,
                        }}
                        resizeMode="contain"
                    />
                </View>

                {/* Centro: Título limpio */}
                <Text style={styles.navBarTitle} numberOfLines={1}>
                    Bandeja de Soportes
                </Text>

                {/* Derecha: Campana + Icono Salir */}
                <View style={styles.rightBarGroup}>
                    <NotificacionesCampana
                        buttonStyle={[
                            styles.iconActionBtn,
                            ticketsConInsistencia.length > 0 && { backgroundColor: '#ef4444' },
                        ]}
                        insistencias={ticketsConInsistencia.map((ticket) => {
                            const id = Number(ticket.id_solicitud || ticket.id);
                            return {
                                id,
                                titulo: `Ticket ${ticket.codigo_ticket || `#${id}`} — ${ticket.oficina || 'Soporte'}`,
                                mensaje: `El usuario requiere atención (${ticket.cantidad_resolicitudes} insistencia(s)).`,
                                detalle: ticket.ultima_resolicitud_motivo ? `Motivo: ${ticket.ultima_resolicitud_motivo}` : undefined,
                            };
                        })}
                        onSeleccionarInsistencia={(id) => {
                            const ticket = tickets.find((item) => Number(item.id_solicitud || item.id) === id);
                            if (ticket) navigation.navigate('TicketDetalle', { ticket, rolUsuario });
                        }}
                        onSeleccionar={(notificacion) => onNotificacionSeleccionada?.(notificacion)}
                    />

                    <TouchableOpacity
                        style={styles.iconActionBtn}
                        onPress={manejarCerrarSesion}
                        activeOpacity={0.75}
                    >
                        <AppIcon name="logout" size={19} color="#ffffff" />
                    </TouchableOpacity>
                </View>
            </View>

            {/* Onda superior curva en la base de la barra azul */}
            <Image
                source={require('../../../../assets/ondas-superiores.png')}
                style={styles.headerBottomWave}
                resizeMode="stretch"
            />
        </View>
    );

    const renderEncabezadoLista = () => (
        <View>
            {/* 1. Título de sección con icono circular azul */}
            <View style={styles.sectionBannerRow}>
                <View style={styles.blueCircleIcon}>
                    <AppIcon name="home" size={20} color="#ffffff" />
                </View>
                <View style={{ flex: 1 }}>
                    <Text style={styles.sectionBannerTitle}>
                        {rolUsuario === RolBandeja.OPERADOR
                            ? 'Gestión y asignación de soportes'
                            : rolUsuario === RolBandeja.TECNICO
                                ? 'Mis soportes técnicos asignados'
                                : 'Mis solicitudes de soporte'}
                    </Text>
                    <Text style={styles.sectionBannerSub}>
                        Modo activo: {rolUsuario}
                    </Text>
                </View>
            </View>

            {/* 2. Tres tarjetas KPI de resumen */}
            <View style={[styles.kpiRow, kpis.length === 4 && styles.kpiGridFour]}>
                {kpis.map((item, idx) => (
                    <TouchableOpacity
                        key={idx}
                        style={[styles.kpiCard, kpis.length === 4 && styles.kpiCardFour, filtroKpi === item.filtro && styles.kpiCardActive]}
                        onPress={() => {
                            setFiltroKpi((actual) => actual === item.filtro ? null : item.filtro);
                            setTabActivo(item.tab);
                        }}
                        accessibilityRole="button"
                        accessibilityState={{ selected: filtroKpi === item.filtro }}
                        activeOpacity={0.78}
                    >
                        <Text style={[styles.kpiNumber, kpis.length === 4 && width < 390 && styles.kpiNumberCompact]}>{item.valor}</Text>
                        <Text style={[styles.kpiLabel, kpis.length === 4 && width < 390 && styles.kpiLabelCompact]}>{item.etiqueta}</Text>
                    </TouchableOpacity>
                ))}
            </View>

            {rolUsuario === RolBandeja.TECNICO && (
                <TouchableOpacity
                    style={styles.manualSupportButton}
                    onPress={() => {
                        setErrorSoporteManual('');
                        setModalSoporteManual(true);
                    }}
                    activeOpacity={0.82}
                >
                    <AppIcon name="plus" size={17} color="#ffffff" />
                    <Text style={styles.manualSupportButtonText}>Registrar soporte manual</Text>
                </TouchableOpacity>
            )}

            {/* 3. Subtítulo de la lista activa + botón Exportar Excel */}
            <View style={styles.listHeadingRow}>
                <Text style={[styles.listHeadingText, { flex: 1, marginBottom: 0 }]}>
                    {filtroKpi === 'DERIVADAS'
                        ? 'Soportes derivados por ti'
                        : filtroKpi === 'PENDIENTES'
                            ? rolUsuario === RolBandeja.OPERADOR ? 'Solicitudes pendientes de asignación' : 'Soportes pendientes de atención'
                        : filtroKpi === 'ASIGNADAS'
                            ? 'Soportes asignados a técnicos'
                            : filtroKpi === 'EN_PROCESO'
                                ? 'Soportes en proceso'
                                : tabActivo === 'PENDIENTES'
                        ? rolUsuario === RolBandeja.OPERADOR
                            ? 'Nuevos y pendientes de atención'
                            : 'Soportes en proceso'
                        : rolUsuario === RolBandeja.OPERADOR
                            ? 'Últimos soportes finalizados y actas'
                            : 'Historial de soportes concluidos'}
                </Text>

                {puedeExportarExcel && (
                    <TouchableOpacity
                        style={[styles.exportExcelButton, exportandoExcel && { opacity: 0.7 }]}
                        onPress={() => void handleExportarExcel()}
                        disabled={exportandoExcel}
                        activeOpacity={0.82}
                    >
                        {exportandoExcel ? (
                            <ActivityIndicator size="small" color="#ffffff" />
                        ) : (
                            <AppIcon name="document-text" size={14} color="#ffffff" />
                        )}
                        <Text style={styles.exportExcelButtonText}>
                            {exportandoExcel ? 'Generando...' : 'Exportar Excel'}
                        </Text>
                    </TouchableOpacity>
                )}
            </View>

            {esVistaHistorial && (
                <ScrollView
                    horizontal
                    showsHorizontalScrollIndicator={false}
                    contentContainerStyle={styles.historyDateFilterRow}
                >
                    {([
                        { id: 'TODAS', label: 'Todas las fechas' },
                        { id: 'HOY', label: 'Hoy' },
                        { id: 'AYER', label: 'Ayer' },
                        { id: 'SEMANA', label: 'Últimos 7 días' },
                        { id: 'MES', label: 'Este mes' },
                    ] as const).map((opcion) => {
                        const activo = filtroFechaHistorial === opcion.id;
                        return (
                            <TouchableOpacity
                                key={opcion.id}
                                style={[
                                    styles.historyDateFilterChip,
                                    activo && styles.historyDateFilterChipActive,
                                ]}
                                onPress={() => {
                                    setFiltroFechaHistorial(opcion.id);
                                    setFechaEspecificaHistorial(null);
                                }}
                                activeOpacity={0.78}
                            >
                                <Text
                                    style={[
                                        styles.historyDateFilterText,
                                        activo && styles.historyDateFilterTextActive,
                                    ]}
                                >
                                    {opcion.label}
                                </Text>
                            </TouchableOpacity>
                        );
                    })}
                    <TouchableOpacity
                        style={[
                            styles.historyDateFilterChip,
                            styles.historyDatePickerChip,
                            filtroFechaHistorial === 'ESPECIFICA' && styles.historyDateFilterChipActive,
                        ]}
                        onPress={() => {
                            if (fechaEspecificaHistorial) {
                                const [y, m] = fechaEspecificaHistorial.split('-').map(Number);
                                if (y && m) setMesCalendario(new Date(y, m - 1, 1));
                            } else {
                                const hoy = new Date();
                                setMesCalendario(new Date(hoy.getFullYear(), hoy.getMonth(), 1));
                            }
                            setModalCalendarioVisible(true);
                        }}
                        activeOpacity={0.78}
                    >
                        <AppIcon
                            name="calendar"
                            size={13}
                            color={filtroFechaHistorial === 'ESPECIFICA' ? '#ffffff' : '#164a96'}
                        />
                        <Text
                            style={[
                                styles.historyDateFilterText,
                                filtroFechaHistorial === 'ESPECIFICA'
                                    ? styles.historyDateFilterTextActive
                                    : { color: '#164a96' },
                            ]}
                        >
                            {filtroFechaHistorial === 'ESPECIFICA' && fechaEspecificaHistorial
                                ? formatearClaveFechaCorta(fechaEspecificaHistorial)
                                : 'Elegir fecha'}
                        </Text>
                        {filtroFechaHistorial === 'ESPECIFICA' && fechaEspecificaHistorial && (
                            <TouchableOpacity
                                onPress={(e) => {
                                    e?.stopPropagation?.();
                                    setFechaEspecificaHistorial(null);
                                    setFiltroFechaHistorial('TODAS');
                                }}
                                hitSlop={{ top: 6, bottom: 6, left: 6, right: 6 }}
                                style={{ marginLeft: 2 }}
                            >
                                <Text style={{ color: '#ffffff', fontSize: 11, fontWeight: '800' }}>✕</Text>
                            </TouchableOpacity>
                        )}
                    </TouchableOpacity>
                </ScrollView>
            )}
        </View>
    );

    if (cargando) {
        return (
            <View style={styles.centerContainer}>
                <ActivityIndicator size="large" color={theme.colors.primary} />
            </View>
        );
    }

    return (
        <View style={styles.container}>
            {renderCabecera()}

            {/* BANNER FLOTANTE CUANDO LLEGA UNA NUEVA INSISTENCIA */}
            {alertaEmergente && (
                <View
                    style={[
                        styles.emergentAlertBanner,
                        esEscritorio && { width: '100%', maxWidth: 880, alignSelf: 'center' },
                    ]}
                >
                    <TouchableOpacity
                        style={{ flex: 1, marginRight: 10 }}
                        onPress={() => {
                            const t = alertaEmergente.ticket;
                            setAlertaEmergente(null);
                            navigation.navigate('TicketDetalle', { ticket: t, rolUsuario });
                        }}
                    >
                        <Text style={{ color: '#fff', fontWeight: 'bold', fontSize: 14 }}>
                            🔔 Nueva Notificación de Insistencia (Ticket {alertaEmergente.ticket.codigo_ticket || `#${alertaEmergente.ticket.id_solicitud || alertaEmergente.ticket.id}`})
                        </Text>
                        <Text style={{ color: '#eafaf1', fontSize: 13, marginTop: 2 }}>
                            {alertaEmergente.mensaje}
                        </Text>
                        {alertaEmergente.ticket.ultima_resolicitud_motivo ? (
                            <Text style={{ color: '#fff', fontSize: 12, marginTop: 2, fontStyle: 'italic' }}>
                                "{alertaEmergente.ticket.ultima_resolicitud_motivo}" — Toca para abrir
                            </Text>
                        ) : null}
                    </TouchableOpacity>
                    <TouchableOpacity onPress={() => setAlertaEmergente(null)} style={{ padding: 6 }}>
                        <Text style={{ color: '#fff', fontWeight: 'bold', fontSize: 16 }}>✕</Text>
                    </TouchableOpacity>
                </View>
            )}

            {/* CONTENIDO PRINCIPAL */}
            <View style={{ flex: 1 }}>
                <FlatList
                    data={elementosLista}
                    keyExtractor={(item) => item.key}
                    ListHeaderComponent={renderEncabezadoLista}
                    contentContainerStyle={[
                        styles.listContainer,
                        { paddingBottom: 120 },
                        esEscritorio && { width: '100%', maxWidth: 912, alignSelf: 'center' },
                    ]}
                    renderItem={({ item }) => {
                        if (item.tipo === 'FECHA') {
                            return (
                                <View style={styles.historyDateSectionHeader}>
                                    <View style={styles.historyDateSectionLeft}>
                                        <AppIcon name="calendar" size={14} color="#164a96" />
                                        <Text style={styles.historyDateSectionTitle}>{item.etiqueta}</Text>
                                    </View>
                                    <View style={styles.historyDateSectionBadge}>
                                        <Text style={styles.historyDateSectionCount}>
                                            {item.cantidad} {item.cantidad === 1 ? 'soporte' : 'soportes'}
                                        </Text>
                                    </View>
                                </View>
                            );
                        }
                        return (
                            <CardTicket
                                ticket={item.ticket}
                                mostrarTiempoEnVivo={rolUsuario === RolBandeja.OPERADOR}
                                ocultarTiempos={rolUsuario === RolBandeja.USUARIO}
                                onPress={() =>
                                    navigation.navigate('TicketDetalle', {
                                        ticket: item.ticket,
                                        rolUsuario: rolUsuario,
                                    })
                                }
                            />
                        );
                    }}
                    ListEmptyComponent={
                        <View style={styles.emptyCardBox}>
                            <Text style={styles.emptyCardText}>
                                No hay solicitudes registradas en esta sección.
                            </Text>
                        </View>
                    }
                    refreshControl={
                        <RefreshControl
                            refreshing={refrescando}
                            onRefresh={onRefresh}
                            colors={[theme.colors.primary]}
                        />
                    }
                />

                {/* Ondas celestes decorativas justo encima de la barra inferior */}
                <View style={styles.bottomWavesWrapper} pointerEvents="none">
                    <Image
                        source={require('../../../../assets/ondas-inferiores.png')}
                        style={styles.bottomWavesImg}
                        resizeMode="stretch"
                    />
                </View>

                {/* BOTÓN FLOTANTE VERDE ESMERALDA ESTILO "Nueva cita" (SOLO USUARIO) */}
                {rolUsuario === RolBandeja.USUARIO && (
                    <TouchableOpacity
                        style={styles.emeraldFab}
                        onPress={() => navigation.navigate('CrearSolicitud')}
                        activeOpacity={0.85}
                    >
                        <AppIcon name="plus" size={16} color="#ffffff" />
                        <Text style={[styles.emeraldFabText, { marginLeft: 7 }]}>Nueva solicitud</Text>
                    </TouchableOpacity>
                )}
            </View>

            {/* BARRA DE NAVEGACIÓN INFERIOR (BOTTOM NAVIGATION BAR) */}
            <View style={[styles.bottomNavBar, { paddingBottom: paddingBottomNav }]}>
                <View
                    style={[
                        styles.bottomNavInner,
                        esEscritorio && { maxWidth: 880, alignSelf: 'center' },
                    ]}
                >
                    <TouchableOpacity
                        style={[
                            styles.bottomNavTab,
                            tabActivo === 'PENDIENTES' && styles.bottomNavTabActive,
                        ]}
                        onPress={() => { setTabActivo('PENDIENTES'); setFiltroKpi(null); }}
                        activeOpacity={0.8}
                    >
                        <AppIcon
                            name="clipboard"
                            size={17}
                            color={tabActivo === 'PENDIENTES' ? '#ffffff' : '#475569'}
                        />
                        <Text
                            style={[
                                styles.bottomNavLabel,
                                { marginTop: 3 },
                                tabActivo === 'PENDIENTES' && styles.bottomNavTextActive,
                            ]}
                        >
                            {rolUsuario === RolBandeja.OPERADOR ? 'Nuevos y Pendientes' : 'En Proceso'}
                        </Text>
                    </TouchableOpacity>

                    <TouchableOpacity
                        style={[
                            styles.bottomNavTab,
                            tabActivo === 'CONCLUIDAS' && styles.bottomNavTabActive,
                        ]}
                        onPress={() => { setTabActivo('CONCLUIDAS'); setFiltroKpi(null); }}
                        activeOpacity={0.8}
                    >
                        <AppIcon
                            name="folder"
                            size={17}
                            color={tabActivo === 'CONCLUIDAS' ? '#ffffff' : '#475569'}
                        />
                        <Text
                            style={[
                                styles.bottomNavLabel,
                                { marginTop: 3 },
                                tabActivo === 'CONCLUIDAS' && styles.bottomNavTextActive,
                            ]}
                        >
                            {rolUsuario === RolBandeja.OPERADOR ? 'Historial y Actas' : rolUsuario === RolBandeja.TECNICO ? 'Historial' : 'Concluidas'}
                        </Text>
                    </TouchableOpacity>
                </View>
            </View>

            <Modal
                visible={modalSoporteManual}
                transparent
                statusBarTranslucent
                animationType="fade"
                onRequestClose={() => setModalSoporteManual(false)}
            >
                <KeyboardAvoidingView
                    style={styles.manualModalBackdrop}
                    behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
                    keyboardVerticalOffset={Platform.OS === 'ios' ? 0 : 16}
                >
                    <View style={styles.manualModal}>
                        <View style={styles.manualModalHeader}>
                            <View style={{ flex: 1 }}>
                                <Text style={styles.manualModalTitle}>Registrar soporte manual</Text>
                                <Text style={styles.manualModalSubtitle}>Se guardará asignado a ti y en proceso desde ahora.</Text>
                            </View>
                            <TouchableOpacity onPress={() => setModalSoporteManual(false)} style={styles.manualCloseButton}>
                                <AppIcon name="close" size={17} color="#475569" />
                            </TouchableOpacity>
                        </View>

                        <ScrollView
                            ref={manualScrollRef}
                            keyboardShouldPersistTaps="handled"
                            keyboardDismissMode="on-drag"
                            showsVerticalScrollIndicator={false}
                            contentContainerStyle={{ paddingBottom: 20 }}
                        >
                            <Text style={styles.manualFieldLabel}>Nombre de quien solicitó *</Text>
                            <TextInput
                                style={styles.manualInput}
                                value={formSoporteManual.solicitante_nombre}
                                onChangeText={(valor) => setFormSoporteManual((actual) => ({ ...actual, solicitante_nombre: valor }))}
                                placeholder="Nombre del funcionario"
                                maxLength={180}
                            />

                            <Text style={styles.manualFieldLabel}>Piso / Sede *</Text>
                            <View style={styles.manualInputWithBtn}>
                                <AppIcon name="home" size={16} color="#64748b" />
                                <TextInput
                                    style={styles.manualInputInner}
                                    value={formSoporteManual.piso}
                                    onChangeText={(valor) => {
                                        setFormSoporteManual((actual) => ({ ...actual, piso: valor }));
                                        if (idSedeManualSeleccionada !== null) {
                                            setIdSedeManualSeleccionada(null);
                                            setConsultoriosManual([]);
                                        }
                                    }}
                                    placeholder="Ej: Planta Baja, Piso 2..."
                                    placeholderTextColor="#94a3b8"
                                    maxLength={100}
                                />
                                <TouchableOpacity
                                    style={styles.manualSedePillBtn}
                                    onPress={() => setSelectorSedeManualVisible(true)}
                                    disabled={guardandoSoporteManual || cargandoSedesManual}
                                    activeOpacity={0.75}
                                >
                                    <AppIcon name="pin" size={12} color="#1d5bbf" />
                                    <Text style={styles.manualSedePillBtnText}>Sedes ▾</Text>
                                </TouchableOpacity>
                            </View>

                            <Text style={styles.manualFieldLabel}>
                                Consultorio <Text style={styles.manualOptionalLabel}>(Opcional)</Text>
                            </Text>
                            <View style={styles.manualInputWithBtn}>
                                <AppIcon name="pin" size={16} color="#64748b" />
                                <TextInput
                                    style={styles.manualInputInner}
                                    value={formSoporteManual.consultorio}
                                    onChangeText={(valor) => setFormSoporteManual((actual) => ({ ...actual, consultorio: valor }))}
                                    placeholder="Ej: Consultorio 12, Sala A..."
                                    placeholderTextColor="#94a3b8"
                                    maxLength={100}
                                />
                                {Boolean(formSoporteManual.consultorio) && (
                                    <TouchableOpacity
                                        onPress={() => setFormSoporteManual((actual) => ({ ...actual, consultorio: '' }))}
                                        hitSlop={{ top: 8, bottom: 8, left: 8, right: 8 }}
                                        style={{ padding: 4 }}
                                    >
                                        <AppIcon name="close" size={14} color="#94a3b8" />
                                    </TouchableOpacity>
                                )}
                            </View>

                            {cargandoConsultoriosManual && (
                                <View style={styles.manualConsultoriosLoading}>
                                    <ActivityIndicator size="small" color="#1d5bbf" />
                                    <Text style={styles.manualConsultoriosLoadingText}>Cargando consultorios del piso...</Text>
                                </View>
                            )}

                            {Boolean(!cargandoConsultoriosManual && idSedeManualSeleccionada !== null && consultoriosManual.length > 0) && (
                                <View style={styles.manualChipsBox}>
                                    <TouchableOpacity
                                        style={styles.manualChipsToggle}
                                        onPress={() => setMostrarConsultoriosManual((v) => !v)}
                                        activeOpacity={0.7}
                                    >
                                        <Text style={styles.manualChipsLabel}>
                                            {mostrarConsultoriosManual
                                                ? 'Consultorios sugeridos en este piso (toca para rellenar):'
                                                : 'Mostrar consultorios para cambiar'}
                                        </Text>
                                        <Text style={styles.manualChipsToggleIcon}>{mostrarConsultoriosManual ? '▴' : '▾'}</Text>
                                    </TouchableOpacity>
                                    {mostrarConsultoriosManual && (
                                        <View style={styles.manualChipsWrap}>
                                            {consultoriosManual.map((c) => {
                                                const nombreSugerido = `Consultorio ${c.numero_consultorio}`;
                                                const activo = formSoporteManual.consultorio.trim().toLowerCase() === nombreSugerido.toLowerCase();
                                                return (
                                                    <TouchableOpacity
                                                        key={c.id}
                                                        style={[styles.manualChip, activo && styles.manualChipActive]}
                                                        onPress={() => {
                                                            if (activo) {
                                                                setFormSoporteManual((actual) => ({ ...actual, consultorio: '' }));
                                                                setMostrarConsultoriosManual(true);
                                                            } else {
                                                                setFormSoporteManual((actual) => ({ ...actual, consultorio: nombreSugerido }));
                                                                setMostrarConsultoriosManual(false);
                                                            }
                                                        }}
                                                        activeOpacity={0.75}
                                                    >
                                                        <AppIcon
                                                            name={activo ? 'check' : 'pin'}
                                                            size={12}
                                                            color={activo ? '#ffffff' : '#1d5bbf'}
                                                        />
                                                        <Text style={[styles.manualChipText, activo && styles.manualChipTextActive]}>
                                                            {nombreSugerido}
                                                        </Text>
                                                    </TouchableOpacity>
                                                );
                                            })}
                                        </View>
                                    )}
                                </View>
                            )}

                            {Boolean(!cargandoConsultoriosManual && idSedeManualSeleccionada !== null && consultoriosManual.length === 0) && (
                                <Text style={styles.manualSinConsultoriosNotice}>
                                    Este piso no tiene consultorios registrados (solo oficinas administrativas)
                                </Text>
                            )}

                            <Text style={styles.manualFieldLabel}>
                                Oficina <Text style={styles.manualOptionalLabel}>(Opcional)</Text>
                            </Text>
                            <View style={styles.manualInputWithBtn}>
                                <AppIcon name="document-text" size={16} color="#64748b" />
                                <TextInput
                                    style={styles.manualInputInner}
                                    value={formSoporteManual.oficina}
                                    onChangeText={(valor) => setFormSoporteManual((actual) => ({ ...actual, oficina: valor }))}
                                    placeholder="Ej: Contabilidad, RRHH, Plataforma..."
                                    placeholderTextColor="#94a3b8"
                                    maxLength={100}
                                />
                            </View>

                            <Text style={styles.manualFieldLabel}>Problema reportado *</Text>
                            <TextInput
                                style={[styles.manualInput, styles.manualDescriptionInput]}
                                value={formSoporteManual.descripcion}
                                onChangeText={(valor) => setFormSoporteManual((actual) => ({ ...actual, descripcion: valor }))}
                                onFocus={() => {
                                    setTimeout(() => {
                                        manualScrollRef.current?.scrollToEnd({ animated: true });
                                    }, 160);
                                }}
                                placeholder="Describe el problema y el trabajo realizado hasta ahora"
                                multiline
                                textAlignVertical="top"
                                maxLength={3000}
                            />

                            <Text style={styles.manualFieldLabel}>Prioridad</Text>
                            <View style={styles.manualPriorityRow}>
                                {(['BAJA', 'MEDIA', 'ALTA'] as const).map((prioridad) => (
                                    <TouchableOpacity
                                        key={prioridad}
                                        style={[
                                            styles.manualPriorityOption,
                                            formSoporteManual.prioridad === prioridad && styles.manualPrioritySelected,
                                        ]}
                                        onPress={() => setFormSoporteManual((actual) => ({ ...actual, prioridad }))}
                                    >
                                        <Text style={[
                                            styles.manualPriorityText,
                                            formSoporteManual.prioridad === prioridad && styles.manualPriorityTextSelected,
                                        ]}>{prioridad}</Text>
                                    </TouchableOpacity>
                                ))}
                            </View>

                            {!!errorSoporteManual && <Text style={styles.manualError}>{errorSoporteManual}</Text>}

                            <View style={styles.manualActionsRow}>
                                <TouchableOpacity
                                    style={styles.manualCancelButton}
                                    onPress={() => setModalSoporteManual(false)}
                                    disabled={guardandoSoporteManual}
                                >
                                    <Text style={styles.manualCancelText}>Cancelar</Text>
                                </TouchableOpacity>
                                <TouchableOpacity
                                    style={[styles.manualSaveButton, guardandoSoporteManual && { opacity: 0.65 }]}
                                    onPress={() => void guardarSoporteManual()}
                                    disabled={guardandoSoporteManual}
                                >
                                    {guardandoSoporteManual
                                        ? <ActivityIndicator color="#ffffff" size="small" />
                                        : <Text style={styles.manualSaveText}>Guardar en proceso</Text>}
                                </TouchableOpacity>
                            </View>
                        </ScrollView>
                    </View>
                    {selectorSedeManualVisible && (
                        <View style={styles.manualSedeOverlay}>
                            <View style={styles.manualSedeCard}>
                                <View style={styles.manualModalHeader}>
                                    <View style={{ flex: 1 }}>
                                        <Text style={styles.manualModalTitle}>Seleccionar Piso / Sede</Text>
                                        <Text style={styles.manualModalSubtitle}>Elige la ubicación registrada en el sistema</Text>
                                    </View>
                                    <TouchableOpacity
                                        onPress={() => setSelectorSedeManualVisible(false)}
                                        style={styles.manualCloseButton}
                                    >
                                        <AppIcon name="close" size={17} color="#475569" />
                                    </TouchableOpacity>
                                </View>

                                <ScrollView style={{ maxHeight: 380 }} showsVerticalScrollIndicator={true}>
                                    {Object.keys(sedesAgrupadasManual).length === 0 ? (
                                        <Text style={{ color: '#64748b', textAlign: 'center', paddingVertical: 24 }}>
                                            {cargandoSedesManual ? 'Cargando sedes...' : 'No hay sedes registradas.'}
                                        </Text>
                                    ) : (
                                        Object.entries(sedesAgrupadasManual).map(([ubicacion, lista]) => (
                                            <View key={ubicacion} style={{ marginBottom: 14 }}>
                                                <Text style={styles.manualSedeGroupTitle}>{ubicacion}</Text>
                                                {lista.map((s) => {
                                                    const seleccionada = idSedeManualSeleccionada === s.id;
                                                    return (
                                                        <TouchableOpacity
                                                            key={s.id}
                                                            style={[styles.manualSedeOption, seleccionada && styles.manualSedeOptionActive]}
                                                            onPress={() => seleccionarSedeManual(s)}
                                                            activeOpacity={0.75}
                                                        >
                                                            <AppIcon name="home" size={15} color={seleccionada ? '#1d5bbf' : '#64748b'} />
                                                            <Text style={[styles.manualSedeOptionText, seleccionada && styles.manualSedeOptionTextActive]}>
                                                                {s.piso}
                                                            </Text>
                                                            {seleccionada && <AppIcon name="check" size={15} color="#1d5bbf" />}
                                                        </TouchableOpacity>
                                                    );
                                                })}
                                            </View>
                                        ))
                                    )}
                                </ScrollView>
                            </View>
                        </View>
                    )}
                </KeyboardAvoidingView>
            </Modal>

            {/* MODAL CALENDARIO: ELEGIR FECHA ESPECÍFICA DEL HISTORIAL */}
            <Modal
                visible={modalCalendarioVisible}
                transparent
                animationType="fade"
                onRequestClose={() => setModalCalendarioVisible(false)}
            >
                <View style={styles.manualModalBackdrop}>
                    <View style={styles.calendarModalCard}>
                        <View style={styles.manualModalHeader}>
                            <View style={{ flex: 1 }}>
                                <Text style={styles.manualModalTitle}>Elegir fecha</Text>
                                <Text style={styles.manualModalSubtitle}>
                                    Los días con punto azul tienen soportes en tu historial
                                </Text>
                            </View>
                            <TouchableOpacity
                                onPress={() => setModalCalendarioVisible(false)}
                                style={styles.manualCloseButton}
                            >
                                <AppIcon name="close" size={17} color="#475569" />
                            </TouchableOpacity>
                        </View>

                        <View style={styles.calendarMonthBar}>
                            <TouchableOpacity
                                style={styles.calendarNavBtn}
                                onPress={() =>
                                    setMesCalendario((actual) => new Date(actual.getFullYear(), actual.getMonth() - 1, 1))
                                }
                            >
                                <Text style={styles.calendarNavText}>‹</Text>
                            </TouchableOpacity>
                            <Text style={styles.calendarMonthTitle}>
                                {(() => {
                                    const t = mesCalendario.toLocaleDateString('es-BO', {
                                        month: 'long',
                                        year: 'numeric',
                                    });
                                    return t.charAt(0).toUpperCase() + t.slice(1);
                                })()}
                            </Text>
                            <TouchableOpacity
                                style={styles.calendarNavBtn}
                                onPress={() =>
                                    setMesCalendario((actual) => new Date(actual.getFullYear(), actual.getMonth() + 1, 1))
                                }
                            >
                                <Text style={styles.calendarNavText}>›</Text>
                            </TouchableOpacity>
                        </View>

                        <View style={styles.calendarWeekRow}>
                            {['Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá', 'Do'].map((dia) => (
                                <Text key={dia} style={styles.calendarWeekDay}>{dia}</Text>
                            ))}
                        </View>

                        <View style={styles.calendarGrid}>
                            {(() => {
                                const anio = mesCalendario.getFullYear();
                                const mes = mesCalendario.getMonth();
                                const primerDiaSemana = (new Date(anio, mes, 1).getDay() + 6) % 7;
                                const totalDiasMes = new Date(anio, mes + 1, 0).getDate();
                                const hoy = new Date();
                                const claveHoyStr = `${hoy.getFullYear()}-${String(hoy.getMonth() + 1).padStart(2, '0')}-${String(hoy.getDate()).padStart(2, '0')}`;

                                const celdas: Array<number | null> = [];
                                for (let i = 0; i < primerDiaSemana; i++) celdas.push(null);
                                for (let d = 1; d <= totalDiasMes; d++) celdas.push(d);
                                while (celdas.length % 7 !== 0) celdas.push(null);

                                return celdas.map((dia, idx) => {
                                    if (dia === null) {
                                        return <View key={`vacio-${idx}`} style={styles.calendarCell} />;
                                    }
                                    const clave = `${anio}-${String(mes + 1).padStart(2, '0')}-${String(dia).padStart(2, '0')}`;
                                    const seleccionado = filtroFechaHistorial === 'ESPECIFICA' && fechaEspecificaHistorial === clave;
                                    const esHoy = clave === claveHoyStr;
                                    const tieneDatos = diasConHistorialSet.has(clave);

                                    return (
                                        <TouchableOpacity
                                            key={clave}
                                            style={styles.calendarCell}
                                            onPress={() => {
                                                setFechaEspecificaHistorial(clave);
                                                setFiltroFechaHistorial('ESPECIFICA');
                                                setModalCalendarioVisible(false);
                                            }}
                                            activeOpacity={0.75}
                                        >
                                            <View
                                                style={[
                                                    styles.calendarDayCircle,
                                                    esHoy && styles.calendarDayToday,
                                                    seleccionado && styles.calendarDaySelected,
                                                ]}
                                            >
                                                <Text
                                                    style={[
                                                        styles.calendarDayText,
                                                        esHoy && styles.calendarDayTodayText,
                                                        seleccionado && styles.calendarDaySelectedText,
                                                    ]}
                                                >
                                                    {dia}
                                                </Text>
                                            </View>
                                            {tieneDatos ? (
                                                <View
                                                    style={[
                                                        styles.calendarDot,
                                                        seleccionado && { backgroundColor: '#164a96' },
                                                    ]}
                                                />
                                            ) : (
                                                <View style={styles.calendarDotPlaceholder} />
                                            )}
                                        </TouchableOpacity>
                                    );
                                });
                            })()}
                        </View>

                        <View style={styles.calendarFooterRow}>
                            <TouchableOpacity
                                style={styles.manualCancelButton}
                                onPress={() => {
                                    setFechaEspecificaHistorial(null);
                                    setFiltroFechaHistorial('TODAS');
                                    setModalCalendarioVisible(false);
                                }}
                            >
                                <Text style={styles.manualCancelText}>Ver todas</Text>
                            </TouchableOpacity>
                            <TouchableOpacity
                                style={styles.manualSaveButton}
                                onPress={() => {
                                    const hoy = new Date();
                                    const claveHoyStr = `${hoy.getFullYear()}-${String(hoy.getMonth() + 1).padStart(2, '0')}-${String(hoy.getDate()).padStart(2, '0')}`;
                                    setFechaEspecificaHistorial(claveHoyStr);
                                    setFiltroFechaHistorial('ESPECIFICA');
                                    setModalCalendarioVisible(false);
                                }}
                            >
                                <Text style={styles.manualSaveText}>Ir a hoy</Text>
                            </TouchableOpacity>
                        </View>
                    </View>
                </View>
            </Modal>

            {/* MENÚ LATERAL PARA CAMBIAR ENTRE ROLES */}
            <MenuLateralModal
                visible={menuLateralVisible}
                onClose={() => setMenuLateralVisible(false)}
                rolesDisponibles={rolesDisponibles}
                nombreUsuario={nombreUsuario}
                rolActivo={rolUsuario}
                onSelectRol={(nuevoRol) => {
                    if (onCambiarRol) onCambiarRol(nuevoRol);
                }}
                onLogout={manejarCerrarSesion}
            />
        </View>
    );
};

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#f8fafc',
    },
    centerContainer: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: '#f8fafc',
    },

    // CABECERA AZUL CON ONDA CURVA
    topHeader: {
        backgroundColor: '#164a96',
        paddingTop: Platform.OS === 'web' ? 18 : 42,
        paddingBottom: 34,
        position: 'relative',
        overflow: 'hidden',
    },
    topBarRow: {
        width: '100%',
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'space-between',
        paddingHorizontal: 16,
    },
    leftBarGroup: {
        flexDirection: 'row',
        alignItems: 'center',
        gap: 8,
    },
    navBarTitle: {
        color: '#ffffff',
        fontSize: Platform.OS === 'web' ? 17 : 15,
        fontWeight: 'bold',
        textAlign: 'center',
        flex: 1,
        marginHorizontal: 4,
    },
    rightBarGroup: {
        flexDirection: 'row',
        alignItems: 'center',
        gap: 8,
    },
    iconActionBtn: {
        width: 38,
        height: 38,
        borderRadius: 10,
        backgroundColor: 'rgba(255, 255, 255, 0.14)',
        justifyContent: 'center',
        alignItems: 'center',
        position: 'relative',
    },
    bellBadgeCount: {
        position: 'absolute',
        top: -4,
        right: -4,
        backgroundColor: '#ffffff',
        borderRadius: 9,
        paddingHorizontal: 5,
        paddingVertical: 1,
        minWidth: 18,
        alignItems: 'center',
    },
    bellBadgeCountText: {
        color: '#dc2626',
        fontSize: 10,
        fontWeight: 'bold',
    },
    headerBottomWave: {
        position: 'absolute',
        bottom: 0,
        left: 0,
        right: 0,
        width: '100%',
        height: 32,
    },

    // BANNER DE SECCIÓN Y TARJETAS KPI
    sectionBannerRow: {
        flexDirection: 'row',
        alignItems: 'center',
        marginBottom: 14,
        marginTop: 4,
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
        fontSize: 16.5,
        fontWeight: 'bold',
        color: '#0f294a',
    },
    sectionBannerSub: {
        fontSize: 12.5,
        color: '#475569',
        marginTop: 1,
    },
    manualSupportButton: {
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'center',
        gap: 8,
        backgroundColor: '#0f4c9c',
        borderRadius: 12,
        paddingVertical: 11,
        paddingHorizontal: 14,
        marginBottom: 16,
    },
    manualSupportButtonText: { color: '#ffffff', fontWeight: '700', fontSize: 13 },
    manualModalBackdrop: { flex: 1, backgroundColor: 'rgba(15,23,42,0.55)', justifyContent: 'center', padding: 16 },
    manualModal: { width: '100%', maxWidth: 540, maxHeight: '92%', alignSelf: 'center', backgroundColor: '#ffffff', borderRadius: 18, padding: 18 },
    manualModalHeader: { flexDirection: 'row', alignItems: 'center', gap: 10, marginBottom: 12 },
    manualModalTitle: { color: '#173b70', fontSize: 19, fontWeight: '800' },
    manualModalSubtitle: { color: '#64748b', fontSize: 12, lineHeight: 17, marginTop: 3 },
    manualCloseButton: { width: 34, height: 34, borderRadius: 17, backgroundColor: '#f1f5f9', alignItems: 'center', justifyContent: 'center' },
    manualFieldLabel: { color: '#334155', fontSize: 12, fontWeight: '700', marginTop: 10, marginBottom: 5 },
    manualOptionalLabel: { color: '#94a3b8', fontWeight: '500' },
    manualInputWithBtn: {
        flexDirection: 'row',
        alignItems: 'center',
        borderWidth: 1,
        borderColor: '#cbd5e1',
        borderRadius: 10,
        backgroundColor: '#f8fafc',
        paddingHorizontal: 10,
        minHeight: 44,
        gap: 8,
    },
    manualInputInner: {
        flex: 1,
        paddingVertical: 9,
        fontSize: 14,
        color: '#0f172a',
    },
    manualSedePillBtn: {
        flexDirection: 'row',
        alignItems: 'center',
        gap: 4,
        backgroundColor: '#eff6ff',
        borderWidth: 1,
        borderColor: '#bfdbfe',
        borderRadius: 8,
        paddingHorizontal: 10,
        paddingVertical: 6,
    },
    manualSedePillBtnText: {
        color: '#1d5bbf',
        fontSize: 12,
        fontWeight: '700',
    },
    manualConsultoriosLoading: {
        flexDirection: 'row',
        alignItems: 'center',
        gap: 8,
        marginTop: 8,
    },
    manualConsultoriosLoadingText: {
        color: '#64748b',
        fontSize: 12,
    },
    manualChipsBox: {
        marginTop: 8,
        backgroundColor: '#f8fafc',
        borderRadius: 10,
        padding: 10,
        borderWidth: 1,
        borderColor: '#e2e8f0',
    },
    manualChipsToggle: {
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'space-between',
        gap: 8,
    },
    manualChipsLabel: {
        flex: 1,
        fontSize: 11.5,
        color: '#475569',
        fontWeight: '600',
    },
    manualChipsToggleIcon: {
        color: '#1d5bbf',
        fontSize: 13,
        fontWeight: 'bold',
    },
    manualChipsWrap: {
        flexDirection: 'row',
        flexWrap: 'wrap',
        gap: 6,
        marginTop: 8,
    },
    manualChip: {
        flexDirection: 'row',
        alignItems: 'center',
        gap: 5,
        paddingHorizontal: 10,
        paddingVertical: 6,
        borderRadius: 20,
        backgroundColor: '#eff6ff',
        borderWidth: 1,
        borderColor: '#bfdbfe',
    },
    manualChipActive: {
        backgroundColor: '#1d5bbf',
        borderColor: '#1d5bbf',
    },
    manualChipText: {
        fontSize: 12,
        fontWeight: '600',
        color: '#1d5bbf',
    },
    manualChipTextActive: {
        color: '#ffffff',
    },
    manualSinConsultoriosNotice: {
        fontSize: 11.5,
        color: '#64748b',
        fontStyle: 'italic',
        marginTop: 6,
    },
    manualSedeOverlay: {
        position: 'absolute',
        top: 0,
        right: 0,
        bottom: 0,
        left: 0,
        backgroundColor: 'rgba(15,23,42,0.55)',
        justifyContent: 'center',
        padding: 16,
    },
    manualSedeCard: {
        width: '100%',
        maxWidth: 500,
        maxHeight: '85%',
        alignSelf: 'center',
        backgroundColor: '#ffffff',
        borderRadius: 18,
        padding: 18,
    },
    manualSedeGroupTitle: {
        fontSize: 12,
        fontWeight: '800',
        color: '#1d5bbf',
        marginBottom: 6,
        textTransform: 'uppercase',
        letterSpacing: 0.4,
    },
    manualSedeOption: {
        flexDirection: 'row',
        alignItems: 'center',
        gap: 8,
        paddingVertical: 10,
        paddingHorizontal: 12,
        borderRadius: 10,
        backgroundColor: '#f8fafc',
        borderWidth: 1,
        borderColor: '#e2e8f0',
        marginBottom: 6,
    },
    manualSedeOptionActive: {
        backgroundColor: '#eff6ff',
        borderColor: '#93c5fd',
    },
    manualSedeOptionText: {
        flex: 1,
        fontSize: 13.5,
        color: '#1e293b',
        fontWeight: '600',
    },
    manualSedeOptionTextActive: {
        color: '#1d5bbf',
        fontWeight: '700',
    },
    manualInput: { minHeight: 42, borderWidth: 1, borderColor: '#cbd5e1', borderRadius: 10, paddingHorizontal: 11, paddingVertical: 9, color: '#0f172a', backgroundColor: '#ffffff', fontSize: 14 },
    manualDescriptionInput: { height: 95, paddingTop: 10 },
    manualFieldsRow: { flexDirection: 'row', gap: 10 },
    manualPriorityRow: { flexDirection: 'row', gap: 8 },
    manualPriorityOption: { flex: 1, alignItems: 'center', justifyContent: 'center', paddingVertical: 9, borderRadius: 9, borderWidth: 1, borderColor: '#cbd5e1', backgroundColor: '#ffffff' },
    manualPrioritySelected: { borderColor: '#1d4ed8', backgroundColor: '#eff6ff' },
    manualPriorityText: { color: '#475569', fontSize: 12, fontWeight: '700' },
    manualPriorityTextSelected: { color: '#1d4ed8' },
    manualError: { color: '#b91c1c', fontSize: 12, marginTop: 10 },
    manualActionsRow: { flexDirection: 'row', gap: 10, marginTop: 18, marginBottom: 4 },
    manualCancelButton: { flex: 1, alignItems: 'center', justifyContent: 'center', borderWidth: 1, borderColor: '#cbd5e1', borderRadius: 10, paddingVertical: 12 },
    manualCancelText: { color: '#475569', fontWeight: '700', fontSize: 13 },
    manualSaveButton: { flex: 1.4, alignItems: 'center', justifyContent: 'center', backgroundColor: '#0f4c9c', borderRadius: 10, paddingVertical: 12 },
    manualSaveText: { color: '#ffffff', fontWeight: '700', fontSize: 13 },

    kpiRow: {
        flexDirection: 'row',
        gap: 10,
        marginBottom: 18,
    },
    kpiGridFour: {
        flexWrap: 'nowrap',
        gap: 5,
    },
    kpiCard: {
        flex: 1,
        backgroundColor: '#ffffff',
        borderRadius: 14,
        paddingVertical: 12,
        paddingHorizontal: 8,
        alignItems: 'center',
        justifyContent: 'center',
        borderWidth: 1,
        borderColor: '#e2e8f0',
        elevation: 2,
        shadowColor: '#0f172a',
        shadowOffset: { width: 0, height: 1 },
        shadowOpacity: 0.05,
        shadowRadius: 4,
    },
    kpiCardFour: {
        flexBasis: 0,
        paddingHorizontal: 2,
    },
    kpiCardActive: {
        borderColor: '#1d68d8',
        backgroundColor: '#eff6ff',
        borderWidth: 2,
    },
    kpiNumber: {
        fontSize: 20,
        fontWeight: 'bold',
        color: '#0f4c9c',
    },
    kpiNumberCompact: {
        fontSize: 17,
    },
    kpiLabel: {
        fontSize: 11,
        color: '#64748b',
        marginTop: 2,
        textAlign: 'center',
        fontWeight: '500',
    },
    kpiLabelCompact: {
        fontSize: 9,
    },

    listHeadingRow: {
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'space-between',
        gap: 10,
        marginBottom: 12,
    },
    listHeadingText: {
        fontSize: 15,
        fontWeight: 'bold',
        color: '#0f294a',
        marginBottom: 12,
    },
    exportExcelButton: {
        flexDirection: 'row',
        alignItems: 'center',
        gap: 6,
        backgroundColor: '#1D6F42',
        paddingHorizontal: 13,
        paddingVertical: 8,
        borderRadius: 20,
        elevation: 2,
        shadowColor: '#0f172a',
        shadowOffset: { width: 0, height: 1 },
        shadowOpacity: 0.14,
        shadowRadius: 3,
    },
    exportExcelButtonText: {
        color: '#ffffff',
        fontSize: 12,
        fontWeight: '700',
    },
    historyDateFilterRow: {
        flexDirection: 'row',
        alignItems: 'center',
        gap: 8,
        paddingBottom: 12,
    },
    historyDateFilterChip: {
        paddingHorizontal: 12,
        paddingVertical: 6,
        borderRadius: 18,
        backgroundColor: '#ffffff',
        borderWidth: 1,
        borderColor: '#cbd5e1',
    },
    historyDatePickerChip: {
        flexDirection: 'row',
        alignItems: 'center',
        gap: 6,
        borderColor: '#93c5fd',
        backgroundColor: '#eff6ff',
    },
    calendarModalCard: {
        width: '100%',
        maxWidth: 380,
        alignSelf: 'center',
        backgroundColor: '#ffffff',
        borderRadius: 18,
        padding: 18,
    },
    calendarMonthBar: {
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'space-between',
        marginBottom: 12,
        backgroundColor: '#f8fafc',
        borderRadius: 12,
        paddingVertical: 6,
        paddingHorizontal: 8,
        borderWidth: 1,
        borderColor: '#e2e8f0',
    },
    calendarNavBtn: {
        width: 34,
        height: 34,
        borderRadius: 10,
        backgroundColor: '#ffffff',
        borderWidth: 1,
        borderColor: '#e2e8f0',
        alignItems: 'center',
        justifyContent: 'center',
    },
    calendarNavText: {
        fontSize: 20,
        fontWeight: '700',
        color: '#164a96',
        lineHeight: 22,
    },
    calendarMonthTitle: {
        fontSize: 15,
        fontWeight: '700',
        color: '#0f294a',
    },
    calendarWeekRow: {
        flexDirection: 'row',
        marginBottom: 6,
    },
    calendarWeekDay: {
        width: '14.285%',
        textAlign: 'center',
        fontSize: 11.5,
        fontWeight: '700',
        color: '#64748b',
    },
    calendarGrid: {
        flexDirection: 'row',
        flexWrap: 'wrap',
        marginBottom: 12,
    },
    calendarCell: {
        width: '14.285%',
        alignItems: 'center',
        paddingVertical: 4,
    },
    calendarDayCircle: {
        width: 34,
        height: 34,
        borderRadius: 17,
        alignItems: 'center',
        justifyContent: 'center',
    },
    calendarDayToday: {
        borderWidth: 1.5,
        borderColor: '#164a96',
        backgroundColor: '#eff6ff',
    },
    calendarDaySelected: {
        backgroundColor: '#164a96',
    },
    calendarDayText: {
        fontSize: 13,
        fontWeight: '600',
        color: '#1e293b',
    },
    calendarDayTodayText: {
        color: '#164a96',
        fontWeight: '800',
    },
    calendarDaySelectedText: {
        color: '#ffffff',
        fontWeight: '800',
    },
    calendarDot: {
        width: 5,
        height: 5,
        borderRadius: 2.5,
        backgroundColor: '#2563eb',
        marginTop: 2,
    },
    calendarDotPlaceholder: {
        width: 5,
        height: 5,
        marginTop: 2,
    },
    calendarFooterRow: {
        flexDirection: 'row',
        gap: 10,
        marginTop: 4,
    },
    historyDateFilterChipActive: {
        backgroundColor: '#164a96',
        borderColor: '#164a96',
    },
    historyDateFilterText: {
        fontSize: 12,
        fontWeight: '600',
        color: '#475569',
    },
    historyDateFilterTextActive: {
        color: '#ffffff',
    },
    historyDateSectionHeader: {
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'space-between',
        backgroundColor: '#e8f0fe',
        borderWidth: 1,
        borderColor: '#bfdbfe',
        borderRadius: 10,
        paddingHorizontal: 12,
        paddingVertical: 8,
        marginTop: 4,
        marginBottom: 10,
    },
    historyDateSectionLeft: {
        flexDirection: 'row',
        alignItems: 'center',
        gap: 7,
        flex: 1,
    },
    historyDateSectionTitle: {
        fontSize: 13,
        fontWeight: '700',
        color: '#0f294a',
    },
    historyDateSectionBadge: {
        backgroundColor: '#ffffff',
        borderRadius: 12,
        paddingHorizontal: 8,
        paddingVertical: 2,
        borderWidth: 1,
        borderColor: '#bfdbfe',
    },
    historyDateSectionCount: {
        fontSize: 11,
        fontWeight: '700',
        color: '#164a96',
    },

    listContainer: {
        paddingHorizontal: 16,
        paddingTop: 10,
    },
    emptyCardBox: {
        backgroundColor: '#ffffff',
        borderRadius: 12,
        padding: 18,
        borderWidth: 1,
        borderColor: '#e2e8f0',
    },
    emptyCardText: {
        color: '#475569',
        fontSize: 13.5,
    },

    emergentAlertBanner: {
        backgroundColor: '#1e8449',
        marginHorizontal: 16,
        marginTop: 10,
        padding: 14,
        borderRadius: 12,
        elevation: 6,
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'space-between',
    },

    // ONDAS INFERIORES + BOTÓN FLOTANTE VERDE ESMERALDA
    bottomWavesWrapper: {
        position: 'absolute',
        bottom: 0,
        left: 0,
        right: 0,
        height: 46,
        overflow: 'hidden',
    },
    bottomWavesImg: {
        width: '100%',
        height: 46,
    },
    emeraldFab: {
        position: 'absolute',
        right: 18,
        bottom: 16,
        backgroundColor: '#179b6b',
        flexDirection: 'row',
        alignItems: 'center',
        paddingVertical: 12,
        paddingHorizontal: 18,
        borderRadius: 28,
        elevation: 6,
        shadowColor: '#0f172a',
        shadowOffset: { width: 0, height: 3 },
        shadowOpacity: 0.25,
        shadowRadius: 6,
    },
    emeraldFabIcon: {
        color: '#ffffff',
        fontSize: 14,
        marginRight: 7,
    },
    emeraldFabText: {
        color: '#ffffff',
        fontWeight: 'bold',
        fontSize: 14,
    },

    // BARRA DE NAVEGACIÓN INFERIOR (ESTILO SIGHOV2)
    bottomNavBar: {
        backgroundColor: '#ffffff',
        borderTopWidth: 1,
        borderTopColor: '#e2e8f0',
        paddingTop: 8,
        paddingHorizontal: 14,
        elevation: 10,
        shadowColor: '#0f172a',
        shadowOffset: { width: 0, height: -2 },
        shadowOpacity: 0.08,
        shadowRadius: 6,
    },
    bottomNavInner: {
        flexDirection: 'row',
        width: '100%',
        gap: 10,
    },
    bottomNavTab: {
        flex: 1,
        paddingVertical: 8,
        borderRadius: 14,
        alignItems: 'center',
        justifyContent: 'center',
        backgroundColor: 'transparent',
    },
    bottomNavTabActive: {
        backgroundColor: '#0f4c9c',
    },
    bottomNavIcon: {
        fontSize: 16,
        color: '#475569',
        marginBottom: 2,
    },
    bottomNavLabel: {
        fontSize: 11.5,
        fontWeight: '600',
        color: '#475569',
    },
    bottomNavTextActive: {
        color: '#ffffff',
        fontWeight: 'bold',
    },
});
