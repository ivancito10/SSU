import React, { useEffect, useState } from 'react';
import { View, Text, StyleSheet, TouchableOpacity } from 'react-native';
import { TicketSoporte } from '../types/soporte.types';
import { AppIcon } from '../../../core/components/AppIcon';

interface Props {
    ticket: TicketSoporte;
    onPress: () => void;
    mostrarTiempoEnVivo?: boolean;
    ocultarTiempos?: boolean;
}

const obtenerEstiloEstado = (estado: string) => {
    switch (estado) {
        case 'FINALIZADA':
            return { texto: 'FINALIZADA', color: '#15803d', bg: '#dcfce7', border: '#bbf7d0' };
        case 'EN_PROCESO':
            return { texto: 'EN PROCESO', color: '#6d28d9', bg: '#f3e8ff', border: '#e9d5ff' };
        case 'ASIGNADA':
            return { texto: 'ASIGNADA', color: '#1d4ed8', bg: '#dbeafe', border: '#bfdbfe' };
        case 'DERIVADA':
            return { texto: 'DERIVADA', color: '#c2410c', bg: '#ffedd5', border: '#fed7aa' };
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
    const hora = d.toLocaleTimeString('es-BO', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    return `${fecha}  —  ${hora}`;
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

const calcularBaseSegundos = (
    segundosServidor: number | null | undefined,
    fechaInicioIso?: string,
    fechaFinIso?: string
): number | null => {
    if (segundosServidor !== undefined && segundosServidor !== null) {
        return Math.max(0, Number(segundosServidor));
    }
    const inicioMs = fechaInicioIso ? new Date(fechaInicioIso).getTime() : NaN;
    const finMs = fechaFinIso ? new Date(fechaFinIso).getTime() : Date.now();
    return Number.isFinite(inicioMs) && Number.isFinite(finMs)
        ? Math.max(0, Math.floor((finMs - inicioMs) / 1000))
        : null;
};

const TiempoEnVivo = ({ ticket }: { ticket: TicketSoporte }) => {
    const estado = ticket.estado;
    const sinAsignarAun = estado === 'PENDIENTE' || !ticket.fecha_asignacion;

    const baseSinAsignar = sinAsignarAun
        ? calcularBaseSegundos(ticket.segundos_sin_asignar, ticket.fecha_solicitud)
        : calcularBaseSegundos(
            ticket.segundos_hasta_asignacion,
            ticket.fecha_solicitud,
            ticket.fecha_primera_asignacion || ticket.fecha_asignacion
        );

    const baseAsignado = !sinAsignarAun
        ? calcularBaseSegundos(
            ticket.segundos_desde_asignacion,
            ticket.fecha_asignacion,
            estado === 'EN_PROCESO' ? ticket.fecha_inicio : undefined
        )
        : null;

    const baseEnProceso = estado === 'EN_PROCESO'
        ? calcularBaseSegundos(ticket.segundos_desde_inicio, ticket.fecha_inicio)
        : null;

    const baseActiva = sinAsignarAun
        ? baseSinAsignar
        : estado === 'ASIGNADA'
            ? baseAsignado
            : estado === 'EN_PROCESO'
                ? baseEnProceso
                : null;

    const [syncedAt, setSyncedAt] = useState(() => Date.now());
    const [ahora, setAhora] = useState(() => Date.now());

    useEffect(() => {
        const ts = Date.now();
        setSyncedAt(ts);
        setAhora(ts);
        const intervalo = setInterval(() => setAhora(Date.now()), 1000);
        return () => clearInterval(intervalo);
    }, [ticket.id_solicitud, estado, baseActiva]);

    if (estado === 'FINALIZADA' || baseActiva === null) return null;

    const delta = Math.max(0, Math.floor((ahora - syncedAt) / 1000));
    const segsSinAsignar = sinAsignarAun ? (baseSinAsignar ?? 0) + delta : baseSinAsignar;
    const segsAsignado = !sinAsignarAun
        ? estado === 'ASIGNADA'
            ? (baseAsignado ?? 0) + delta
            : baseAsignado
        : null;
    const segsEnProceso = estado === 'EN_PROCESO' ? (baseEnProceso ?? 0) + delta : null;

    return (
        <View style={styles.liveTimersContainer}>
            {segsSinAsignar !== null && (
                <View style={styles.liveTimerBox}>
                    <AppIcon name="clock" size={14} color={sinAsignarAun ? '#b45309' : '#475569'} />
                    <Text style={styles.liveTimerText}>
                        {sinAsignarAun ? 'Sin asignar: ' : 'Esperó asignación: '}
                        <Text style={[styles.liveTimerValue, sinAsignarAun && { color: '#b45309' }]}>
                            {formatearSegundos(segsSinAsignar)}
                        </Text>
                    </Text>
                </View>
            )}
            {segsAsignado !== null && (
                <View style={styles.liveTimerBox}>
                    <AppIcon name="clock" size={14} color="#1e40af" />
                    <Text style={styles.liveTimerText}>
                        {estado === 'ASIGNADA' ? 'Tiempo asignado: ' : 'Tiempo asignado: '}
                        <Text style={styles.liveTimerValue}>{formatearSegundos(segsAsignado)}</Text>
                    </Text>
                </View>
            )}
            {segsEnProceso !== null && (
                <View style={[styles.liveTimerBox, { backgroundColor: '#f3e8ff', borderColor: '#ddd6fe' }]}>
                    <AppIcon name="wrench" size={14} color="#6d28d9" />
                    <Text style={[styles.liveTimerText, { color: '#5b21b6' }]}>
                        En proceso:{' '}
                        <Text style={[styles.liveTimerValue, { color: '#4c1d95' }]}>
                            {formatearSegundos(segsEnProceso)}
                        </Text>
                    </Text>
                </View>
            )}
        </View>
    );
};

const obtenerTiempoTotalAsignacion = (ticket: TicketSoporte) => {
    if (ticket.segundos_desde_asignacion !== undefined && ticket.segundos_desde_asignacion !== null) {
        return formatearSegundos(Number(ticket.segundos_desde_asignacion));
    }
    if (ticket.fecha_asignacion && ticket.fecha_finalizacion) {
        const segs = Math.floor((new Date(ticket.fecha_finalizacion).getTime() - new Date(ticket.fecha_asignacion).getTime()) / 1000);
        return formatearSegundos(segs);
    }
    return ticket.tiempo_resolucion || 'No registrado';
};

const obtenerTiempoEfectivoTrabajo = (ticket: TicketSoporte) => {
    if (ticket.segundos_desde_inicio !== undefined && ticket.segundos_desde_inicio !== null) {
        return formatearSegundos(Number(ticket.segundos_desde_inicio));
    }
    if (ticket.fecha_inicio && ticket.fecha_finalizacion) {
        const segs = Math.floor((new Date(ticket.fecha_finalizacion).getTime() - new Date(ticket.fecha_inicio).getTime()) / 1000);
        return formatearSegundos(segs);
    }
    return 'No iniciado';
};

// Icono circular institucional de soporte
const IconoSoporteCircular = () => (
    <View style={styles.avatarCircle}>
        <AppIcon name="laptop" size={20} color="#164a96" />
    </View>
);

export const CardTicket = ({ ticket, onPress, mostrarTiempoEnVivo = false, ocultarTiempos = false }: Props) => {
    const idReal = ticket.id || ticket.id_solicitud;
    const estiloEstado = obtenerEstiloEstado(ticket.estado);
    const numInsistencias = Number(ticket.cantidad_resolicitudes || 0);
    const tieneInsistencia = numInsistencias > 0 && ticket.estado !== 'FINALIZADA';
    const infoPrioridad = obtenerInfoPrioridad(ticket.prioridad);

    return (
        <TouchableOpacity
            style={[
                styles.card,
                tieneInsistencia && { borderColor: '#fca5a5', backgroundColor: '#fffcfc' }
            ]}
            onPress={onPress}
            activeOpacity={0.85}
        >
            {/* 1. FILA SUPERIOR: Pastillas de Estado / Prioridad / Insistencia (Izquierda) + Ticket #ID (Derecha) */}
            <View style={styles.topRow}>
                <View style={styles.badgesWrap}>
                    {/* Pastilla suave de Estado */}
                    <View style={[styles.softPill, { backgroundColor: estiloEstado.bg, borderColor: estiloEstado.border }]}>
                        <Text style={[styles.softPillText, { color: estiloEstado.color }]}>
                            {estiloEstado.texto}
                        </Text>
                    </View>

                    {/* Pastilla de Prioridad (Punto de color nativo + Texto) */}
                    {infoPrioridad && (
                        <View style={[styles.softPill, { backgroundColor: infoPrioridad.bg, borderColor: infoPrioridad.color, flexDirection: 'row', alignItems: 'center' }]}>
                            <View style={{ width: 8, height: 8, borderRadius: 4, backgroundColor: infoPrioridad.dot, marginRight: 5 }} />
                            <Text style={[styles.softPillText, { color: infoPrioridad.color }]}>
                                {infoPrioridad.texto}
                            </Text>
                        </View>
                    )}

                    {/* Pastilla de Insistencia */}
                    {tieneInsistencia && (
                        <View style={[styles.softPill, { backgroundColor: '#fee2e2', borderColor: '#f87171', flexDirection: 'row', alignItems: 'center', gap: 5 }]}>
                            <AppIcon name="bell" size={12} color="#b91c1c" />
                            <Text style={[styles.softPillText, { color: '#b91c1c' }]}>
                                INSISTENCIA ({numInsistencias})
                            </Text>
                        </View>
                    )}

                    {ticket.es_manual && (
                        <View style={[styles.softPill, { backgroundColor: '#ecfeff', borderColor: '#67e8f9' }]}>
                            <Text style={[styles.softPillText, { color: '#0e7490' }]}>MANUAL</Text>
                        </View>
                    )}
                </View>

                <Text style={styles.ticketIdRight}>
                    {ticket.estado === 'FINALIZADA'
                        ? `Historial ${ticket.codigo_ticket || `#${idReal}`}`
                        : `Ticket ${ticket.codigo_ticket || `#${idReal}`}`}
                </Text>
            </View>

            {/* 2. FILA PRINCIPAL CON ICONO CIRCULAR + OFICINA Y PISO */}
            <View style={styles.mainHeaderRow}>
                <IconoSoporteCircular />
                <View style={{ flex: 1 }}>
                    <Text style={styles.officeTitle}>
                        {(ticket.oficina || ticket.consultorio || 'ATENCIÓN DE SOPORTE').toUpperCase()}
                    </Text>
                    <Text style={styles.floorSubtitle}>
                        PISO: {(ticket.piso || 'NO ESPECIFICADO').toUpperCase()}
                    </Text>
                </View>
            </View>

            <View style={styles.divider} />

            {/* 3. DETALLES CON ICONOS VECTORIALES LIMPIOS */}
            <View style={styles.detailsList}>
                {ticket.es_manual ? (
                    <View style={styles.iconRowItem}>
                        <AppIcon name="user" size={15} color="#0e7490" />
                        <Text style={[styles.detailRowText, { color: '#0e7490', fontWeight: '600', marginLeft: 7 }]}>
                            Solicitud verbal de: {ticket.solicitante_manual || 'Solicitante sin nombre'}
                        </Text>
                    </View>
                ) : (ticket.nombre_usuario || ticket.username) ? (
                    <View style={styles.iconRowItem}>
                        <AppIcon name="user" size={15} color="#0f4c9c" />
                        <Text style={[styles.detailRowText, { marginLeft: 7 }]}>
                            Solicitante:{' '}
                            <Text style={{ fontWeight: '600', color: '#0f172a' }}>
                                {ticket.nombre_usuario || ticket.username}
                            </Text>
                            {ticket.ci_usuario ? ` (CI: ${ticket.ci_usuario})` : ''}
                        </Text>
                    </View>
                ) : null}

                {ticket.estado === 'EN_PROCESO' ? (
                    <View style={styles.iconRowItem}>
                        <AppIcon name="wrench" size={15} color="#6d28d9" />
                        <Text style={[styles.detailRowText, { color: '#6d28d9', fontWeight: '600', marginLeft: 7 }]}>
                            En proceso por: {ticket.nombre_tecnico || 'Técnico asignado'}
                        </Text>
                    </View>
                ) : ticket.nombre_tecnico ? (
                    <View style={styles.iconRowItem}>
                        <AppIcon name="user" size={15} color="#1e3a8a" />
                        <Text style={[styles.detailRowText, { marginLeft: 7 }]}>
                            Técnico: <Text style={{ fontWeight: '600', color: '#1e3a8a' }}>{ticket.nombre_tecnico}</Text>
                        </Text>
                    </View>
                ) : (
                    <View style={styles.iconRowItem}>
                        <AppIcon name="clock" size={15} color="#64748b" />
                        <Text style={[styles.detailRowText, { color: '#64748b', marginLeft: 7 }]}>
                            Esperando asignación de técnico
                        </Text>
                    </View>
                )}

                <View style={styles.iconRowItem}>
                    <AppIcon name="pin" size={15} color="#475569" />
                    <Text style={[styles.detailRowText, { marginLeft: 7 }]}>
                        Ubicación: {[ticket.oficina, ticket.consultorio, ticket.piso].filter(Boolean).join(' — ')}
                    </Text>
                </View>

                {mostrarTiempoEnVivo && ticket.estado !== 'FINALIZADA' && (
                    <TiempoEnVivo ticket={ticket} />
                )}

                {/* HORAS Y TIEMPOS EN TICKETS FINALIZADOS */}
                {ticket.estado === 'FINALIZADA' && !ocultarTiempos && (
                    <View style={styles.historyTimesBox}>
                        <View style={styles.iconRowItem}>
                            <AppIcon name="calendar" size={13} color="#1e40af" />
                            <Text style={[styles.historyTimeItem, { marginLeft: 6 }]}>
                                <Text style={{ fontWeight: 'bold', color: '#1e40af' }}>Asignado:</Text> {formatearFechaHora(ticket.fecha_asignacion)}
                            </Text>
                        </View>
                        <View style={styles.iconRowItem}>
                            <AppIcon name="play" size={13} color="#6d28d9" />
                            <Text style={[styles.historyTimeItem, { marginLeft: 6 }]}>
                                <Text style={{ fontWeight: 'bold', color: '#6d28d9' }}>En Proceso:</Text> {formatearFechaHora(ticket.fecha_inicio)}
                            </Text>
                        </View>
                        <View style={styles.iconRowItem}>
                            <AppIcon name="check" size={13} color="#15803d" />
                            <Text style={[styles.historyTimeItem, { marginLeft: 6 }]}>
                                <Text style={{ fontWeight: 'bold', color: '#15803d' }}>Finalizado:</Text> {formatearFechaHora(ticket.fecha_finalizacion)}
                            </Text>
                        </View>

                        <View style={styles.metricsSummaryRow}>
                            {ticket.segundos_hasta_asignacion !== undefined && ticket.segundos_hasta_asignacion !== null && (
                                <View style={styles.iconRowItem}>
                                    <AppIcon name="clock" size={13} color="#b45309" />
                                    <Text style={[styles.metricItemText, { marginLeft: 6 }]}>
                                        <Text style={{ fontWeight: 'bold', color: '#b45309' }}>Sin asignar:</Text>{' '}
                                        <Text style={{ fontWeight: 'bold', color: '#0f172a' }}>
                                            {formatearSegundos(Number(ticket.segundos_hasta_asignacion))}
                                        </Text>
                                    </Text>
                                </View>
                            )}
                            <View style={styles.iconRowItem}>
                                <AppIcon name="clock" size={13} color="#1e40af" />
                                <Text style={[styles.metricItemText, { marginLeft: 6 }]}>
                                    <Text style={{ fontWeight: 'bold', color: '#1e40af' }}>Total desde Asignación:</Text>{' '}
                                    <Text style={{ fontWeight: 'bold', color: '#0f172a' }}>{obtenerTiempoTotalAsignacion(ticket)}</Text>
                                </Text>
                            </View>
                            <View style={styles.iconRowItem}>
                                <AppIcon name="wrench" size={13} color="#6d28d9" />
                                <Text style={[styles.metricItemText, { marginLeft: 6 }]}>
                                    <Text style={{ fontWeight: 'bold', color: '#6d28d9' }}>Efectivo de trabajo:</Text>{' '}
                                    <Text style={{ fontWeight: 'bold', color: '#0f172a' }}>{obtenerTiempoEfectivoTrabajo(ticket)}</Text>
                                </Text>
                            </View>
                        </View>
                    </View>
                )}

                {ticket.ultima_reasignacion_motivo ? (
                    <View style={[styles.iconRowItem, { marginTop: 4 }]}>
                        <AppIcon name="refresh" size={13} color="#d97706" />
                        <Text style={[styles.reassignedText, { marginTop: 0, marginLeft: 6 }]}>
                            Reasignado — Motivo: {ticket.ultima_reasignacion_motivo}
                        </Text>
                    </View>
                ) : null}

                {tieneInsistencia && (
                    <View style={styles.insistenciaAlertBox}>
                        <View style={styles.iconRowItem}>
                            <AppIcon name="alert" size={14} color="#b91c1c" />
                            <Text style={[styles.insistenciaAlertTitle, { marginLeft: 6, flex: 1 }]}>
                                Insistencias de {[ticket.oficina, ticket.piso].filter(Boolean).join(' — ') || 'usuario'} ({ticket.cantidad_resolicitudes})
                            </Text>
                        </View>
                        {Array.isArray(ticket.resolicitudes_historial) && ticket.resolicitudes_historial.length > 0 ? (
                            <View style={styles.insistenciaLista}>
                                {ticket.resolicitudes_historial.map((ins, idx) => (
                                    <View key={ins.id ?? idx} style={styles.insistenciaItemRow}>
                                        <Text style={styles.insistenciaItemIndex}>#{idx + 1}</Text>
                                        <View style={{ flex: 1 }}>
                                            <Text style={styles.insistenciaAlertMotivo}>
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
                            <Text style={styles.insistenciaAlertMotivo}>
                                Motivo: "{ticket.ultima_resolicitud_motivo}"
                            </Text>
                        ) : null}
                    </View>
                )}
            </View>

            {/* 4. PIE DE TARJETA CON BOTÓN "Detalles" AZUL MARINO */}
            <View style={styles.cardFooterRow}>
                <TouchableOpacity
                    style={styles.detailsButton}
                    onPress={onPress}
                    activeOpacity={0.8}
                >
                    <Text style={styles.detailsButtonText}>Detalles</Text>
                </TouchableOpacity>
            </View>
        </TouchableOpacity>
    );
};

const styles = StyleSheet.create({
    card: {
        backgroundColor: '#ffffff',
        padding: 16,
        borderRadius: 16,
        marginBottom: 14,
        elevation: 3,
        shadowColor: '#0f172a',
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.07,
        shadowRadius: 8,
        borderWidth: 1,
        borderColor: '#e2e8f0',
    },
    topRow: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
        marginBottom: 12,
    },
    badgesWrap: {
        flexDirection: 'row',
        alignItems: 'center',
        flexWrap: 'wrap',
        gap: 6,
        flex: 1,
        marginRight: 8,
    },
    softPill: {
        paddingHorizontal: 10,
        paddingVertical: 4,
        borderRadius: 8,
        borderWidth: 1,
    },
    softPillText: {
        fontSize: 10.5,
        fontWeight: 'bold',
        letterSpacing: 0.3,
    },
    ticketIdRight: {
        fontSize: 10.5,
        color: '#64748b',
        fontWeight: '600',
        maxWidth: '54%',
        flexShrink: 1,
        textAlign: 'right',
    },

    mainHeaderRow: {
        flexDirection: 'row',
        alignItems: 'center',
        marginBottom: 12,
    },
    avatarCircle: {
        width: 44,
        height: 44,
        borderRadius: 22,
        backgroundColor: '#eff6ff',
        justifyContent: 'center',
        alignItems: 'center',
        marginRight: 12,
        borderWidth: 1,
        borderColor: '#dbeafe',
    },
    officeTitle: {
        fontSize: 15,
        fontWeight: 'bold',
        color: '#1e3a8a',
        letterSpacing: 0.2,
    },
    floorSubtitle: {
        fontSize: 12,
        color: '#64748b',
        marginTop: 2,
        fontWeight: '500',
    },

    divider: {
        height: 1,
        backgroundColor: '#f1f5f9',
        marginBottom: 10,
    },

    detailsList: {
        gap: 6,
    },
    iconRowItem: {
        flexDirection: 'row',
        alignItems: 'center',
    },
    detailRowText: {
        fontSize: 13,
        color: '#334155',
    },
    liveTimersContainer: {
        marginTop: 4,
        gap: 6,
    },
    liveTimerBox: {
        flexDirection: 'row',
        alignItems: 'center',
        gap: 7,
        backgroundColor: '#eff6ff',
        borderRadius: 8,
        paddingHorizontal: 10,
        paddingVertical: 8,
        borderWidth: 1,
        borderColor: '#dbeafe',
    },
    liveTimerText: {
        flex: 1,
        color: '#1e40af',
        fontSize: 12.5,
        fontWeight: '600',
    },
    liveTimerValue: {
        color: '#0f294a',
        fontWeight: 'bold',
        fontVariant: ['tabular-nums'],
    },
    historyTimesBox: {
        marginTop: 6,
        paddingTop: 8,
        borderTopWidth: 1,
        borderTopColor: '#f1f5f9',
        gap: 4,
    },
    historyTimeItem: {
        fontSize: 12,
        color: '#475569',
    },
    metricsSummaryRow: {
        marginTop: 6,
        paddingTop: 6,
        borderTopWidth: 1,
        borderTopColor: '#f8fafc',
        gap: 3,
    },
    metricItemText: {
        fontSize: 12,
        color: '#475569',
    },
    reassignedText: {
        fontSize: 12,
        color: '#d97706',
        marginTop: 4,
        fontStyle: 'italic',
    },
    insistenciaAlertBox: {
        marginTop: 6,
        paddingVertical: 7,
        paddingHorizontal: 10,
        backgroundColor: '#fef2f2',
        borderRadius: 8,
        borderLeftWidth: 3,
        borderLeftColor: '#ef4444',
    },
    insistenciaAlertTitle: {
        fontSize: 12,
        fontWeight: 'bold',
        color: '#b91c1c',
    },
    insistenciaAlertMotivo: {
        fontSize: 12,
        color: '#991b1b',
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
        borderTopColor: '#fecaca',
    },
    insistenciaItemIndex: {
        fontSize: 11,
        fontWeight: 'bold',
        color: '#b91c1c',
        backgroundColor: '#fee2e2',
        paddingHorizontal: 6,
        paddingVertical: 1,
        borderRadius: 6,
        marginTop: 1,
    },
    insistenciaItemFecha: {
        fontSize: 11,
        color: '#b91c1c',
        marginTop: 1,
    },

    cardFooterRow: {
        flexDirection: 'row',
        justifyContent: 'flex-end',
        marginTop: 12,
    },
    detailsButton: {
        backgroundColor: '#0f4c9c',
        paddingHorizontal: 18,
        paddingVertical: 8,
        borderRadius: 10,
    },
    detailsButtonText: {
        color: '#ffffff',
        fontSize: 12.5,
        fontWeight: 'bold',
    },
});
