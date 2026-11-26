import React, { useCallback, useEffect, useMemo, useRef, useState } from 'react';
import {
    ActivityIndicator,
    Animated,
    Modal,
    PanResponder,
    Platform,
    Pressable,
    ScrollView,
    StyleSheet,
    Text,
    TouchableOpacity,
    View,
} from 'react-native';
import { AppIcon } from './AppIcon';
import {
    marcarNotificacionLeida,
    marcarTodasNotificacionesLeidas,
    NotificacionSoporte,
    obtenerNotificaciones,
} from '../../features/soportes/api/soporte.service';

interface NotificacionesCampanaProps {
    buttonStyle?: any;
    onSeleccionar?: (notificacion: NotificacionSoporte) => void;
    insistencias?: Array<{ id: number; titulo: string; mensaje: string; detalle?: string }>;
    onSeleccionarInsistencia?: (id: number) => void;
}

const fechaLegible = (valor: string) => {
    const fecha = new Date(valor);
    if (Number.isNaN(fecha.getTime())) return '';
    return fecha.toLocaleString('es-BO', { dateStyle: 'short', timeStyle: 'short' });
};

// Evita que múltiples instancias montadas de NotificacionesCampana en el stack
// apilen varios toasts idénticos uno encima del otro en Web.
let ultimoToastIdMostradoGlobal: number | null = null;
const listenersCerrarAvisoWeb = new Set<() => void>();
const notificarCierreAvisoWebGlobal = () => {
    listenersCerrarAvisoWeb.forEach((fn) => fn());
};

interface TarjetaNotificacionProps {
    item: NotificacionSoporte;
    onAbrir: (item: NotificacionSoporte) => void;
    onDescartar: (item: NotificacionSoporte) => void;
}

const TarjetaNotificacionDeslizable = ({ item, onAbrir, onDescartar }: TarjetaNotificacionProps) => {
    const translateX = useRef(new Animated.Value(0)).current;
    const descartadoRef = useRef(false);

    const animarYSacar = useCallback(() => {
        if (descartadoRef.current) return;
        descartadoRef.current = true;
        Animated.timing(translateX, {
            toValue: 360,
            duration: 180,
            useNativeDriver: true,
        }).start(() => {
            onDescartar(item);
        });
    }, [item, onDescartar, translateX]);

    const panResponder = useMemo(
        () =>
            PanResponder.create({
                onMoveShouldSetPanResponder: (_evt, gesture) =>
                    gesture.dx > 12 && Math.abs(gesture.dx) > Math.abs(gesture.dy) * 1.3,
                onPanResponderMove: (_evt, gesture) => {
                    if (gesture.dx > 0) {
                        translateX.setValue(gesture.dx);
                    }
                },
                onPanResponderRelease: (_evt, gesture) => {
                    if (gesture.dx > 90 || gesture.vx > 0.6) {
                        animarYSacar();
                    } else {
                        Animated.spring(translateX, {
                            toValue: 0,
                            useNativeDriver: true,
                            bounciness: 6,
                        }).start();
                    }
                },
                onPanResponderTerminate: () => {
                    Animated.spring(translateX, {
                        toValue: 0,
                        useNativeDriver: true,
                    }).start();
                },
            }),
        [animarYSacar, translateX]
    );

    const opacity = translateX.interpolate({
        inputRange: [0, 220],
        outputRange: [1, 0.25],
        extrapolate: 'clamp',
    });

    return (
        <Animated.View
            style={{ transform: [{ translateX }], opacity }}
            {...panResponder.panHandlers}
        >
            <TouchableOpacity
                style={[styles.item, !item.leida && styles.itemNoLeido]}
                onPress={() => onAbrir(item)}
                activeOpacity={0.85}
            >
                <View style={styles.itemFila}>
                    <Text style={styles.itemTitulo} numberOfLines={2}>{item.titulo}</Text>
                    <View style={styles.itemAccionesDerecha}>
                        {!item.leida && <View style={styles.puntoNoLeido} />}
                        <TouchableOpacity
                            style={styles.itemCerrarBtn}
                            onPress={(e) => {
                                e?.stopPropagation?.();
                                animarYSacar();
                            }}
                            hitSlop={{ top: 8, bottom: 8, left: 8, right: 8 }}
                            accessibilityLabel="Cerrar notificación"
                        >
                            <Text style={styles.itemCerrarTexto}>✕</Text>
                        </TouchableOpacity>
                    </View>
                </View>
                <Text style={styles.itemMensaje}>{item.mensaje}</Text>
                <Text style={styles.itemFecha}>{fechaLegible(item.creado_en)}</Text>
            </TouchableOpacity>
        </Animated.View>
    );
};

export const NotificacionesCampana = ({
    buttonStyle,
    onSeleccionar,
    insistencias = [],
    onSeleccionarInsistencia,
}: NotificacionesCampanaProps) => {
    const [notificaciones, setNotificaciones] = useState<NotificacionSoporte[]>([]);
    const [visible, setVisible] = useState(false);
    const [cargando, setCargando] = useState(true);
    const [mensajeError, setMensajeError] = useState('');
    const [avisoWeb, setAvisoWeb] = useState<NotificacionSoporte | null>(null);
    const inicializadoRef = useRef(false);
    const idsPreviosRef = useRef<Set<number>>(new Set());
    const timerAvisoRef = useRef<ReturnType<typeof setTimeout> | null>(null);

    const cargar = useCallback(async () => {
        try {
            const nuevas = await obtenerNotificaciones();
            if (inicializadoRef.current && Platform.OS === 'web') {
                const reciente = nuevas.find((item) => !idsPreviosRef.current.has(Number(item.id)) && !item.leida);
                if (reciente && Number(reciente.id) !== ultimoToastIdMostradoGlobal) {
                    ultimoToastIdMostradoGlobal = Number(reciente.id);
                    notificarCierreAvisoWebGlobal();
                    setAvisoWeb(reciente);
                    if (timerAvisoRef.current) clearTimeout(timerAvisoRef.current);
                    timerAvisoRef.current = setTimeout(() => setAvisoWeb(null), 7000);
                }
            }
            idsPreviosRef.current = new Set(nuevas.map((item) => Number(item.id)));
            inicializadoRef.current = true;
            setNotificaciones(nuevas);
            setMensajeError('');
        } catch {
            setMensajeError('No se pudieron cargar las notificaciones.');
        } finally {
            setCargando(false);
        }
    }, []);

    useEffect(() => {
        const limpiarLocal = () => {
            if (timerAvisoRef.current) {
                clearTimeout(timerAvisoRef.current);
                timerAvisoRef.current = null;
            }
            setAvisoWeb(null);
        };
        listenersCerrarAvisoWeb.add(limpiarLocal);
        void cargar();
        const intervalo = setInterval(() => void cargar(), 8000);
        return () => {
            listenersCerrarAvisoWeb.delete(limpiarLocal);
            clearInterval(intervalo);
            if (timerAvisoRef.current) clearTimeout(timerAvisoRef.current);
        };
    }, [cargar]);

    const pendientes = notificaciones.filter((item) => !item.leida).length;
    const totalBadge = pendientes + insistencias.length;

    const seleccionar = async (notificacion: NotificacionSoporte) => {
        setAvisoWeb(null);
        setVisible(false);
        setNotificaciones((actuales) => actuales.filter((item) => item.id !== notificacion.id));
        if (!notificacion.leida) {
            try {
                await marcarNotificacionLeida(notificacion.id);
            } catch {
                setMensajeError('No se pudo actualizar el estado de lectura.');
            }
        }
        onSeleccionar?.(notificacion);
    };

    const descartar = useCallback(async (notificacion: NotificacionSoporte) => {
        setNotificaciones((actuales) => actuales.filter((item) => item.id !== notificacion.id));
        if (!notificacion.leida) {
            try {
                await marcarNotificacionLeida(notificacion.id);
            } catch {
                setMensajeError('No se pudo marcar como leída.');
            }
        }
    }, []);

    const leerTodas = async () => {
        try {
            await marcarTodasNotificacionesLeidas();
            setNotificaciones([]);
            setAvisoWeb(null);
            setVisible(false);
        } catch {
            setMensajeError('No se pudieron marcar como leídas.');
        }
    };

    const cerrarAvisoWeb = useCallback(() => {
        notificarCierreAvisoWebGlobal();
    }, []);

    return (
        <>
            <TouchableOpacity
                style={[styles.boton, buttonStyle]}
                onPress={() => { setVisible(true); void cargar(); }}
                activeOpacity={0.75}
                accessibilityRole="button"
                accessibilityLabel={`Notificaciones${totalBadge ? `, ${totalBadge} pendientes` : ''}`}
            >
                <AppIcon name="bell" size={19} color="#ffffff" />
                {totalBadge > 0 && (
                    <View style={styles.badge}>
                        <Text style={styles.badgeTexto}>{totalBadge > 99 ? '99+' : totalBadge}</Text>
                    </View>
                )}
            </TouchableOpacity>

            {Platform.OS === 'web' && avisoWeb && (
                <Modal transparent visible animationType="fade" onRequestClose={cerrarAvisoWeb}>
                    <Pressable style={styles.toastOverlay} onPress={cerrarAvisoWeb}>
                        <TouchableOpacity
                            style={styles.avisoWeb}
                            onPress={(e) => {
                                e?.stopPropagation?.();
                                cerrarAvisoWeb();
                                setVisible(true);
                            }}
                            activeOpacity={0.9}
                        >
                            <Text style={styles.avisoTitulo} numberOfLines={1}>{avisoWeb.titulo}</Text>
                            <Text style={styles.avisoMensaje} numberOfLines={2}>{avisoWeb.mensaje}</Text>
                            <Text style={styles.avisoAccion}>Ver notificaciones</Text>
                        </TouchableOpacity>
                    </Pressable>
                </Modal>
            )}

            <Modal
                visible={visible}
                transparent
                animationType="fade"
                onRequestClose={() => setVisible(false)}
            >
                <View style={styles.fondoModal}>
                    <View style={styles.modal}>
                        <View style={styles.encabezado}>
                            <View>
                                <Text style={styles.titulo}>Notificaciones</Text>
                                <Text style={styles.subtitulo}>
                                    {pendientes ? `${pendientes} sin leer` : 'Estás al día'}
                                </Text>
                            </View>
                            <TouchableOpacity onPress={() => setVisible(false)} style={styles.cerrar} accessibilityLabel="Cerrar">
                                <Text style={styles.cerrarTexto}>×</Text>
                            </TouchableOpacity>
                        </View>

                        {pendientes > 0 && (
                            <TouchableOpacity style={styles.leerTodas} onPress={() => void leerTodas()}>
                                <Text style={styles.leerTodasTexto}>Marcar todas como leídas</Text>
                            </TouchableOpacity>
                        )}

                        {cargando ? (
                            <ActivityIndicator color="#1d4ed8" style={{ padding: 24 }} />
                        ) : (
                            <ScrollView style={styles.lista}>
                                {notificaciones.length === 0 && insistencias.length === 0 && (
                                    <Text style={styles.vacio}>{mensajeError || 'Todavía no tienes notificaciones.'}</Text>
                                )}
                                {notificaciones.map((item) => (
                                    <TarjetaNotificacionDeslizable
                                        key={item.id}
                                        item={item}
                                        onAbrir={(n) => void seleccionar(n)}
                                        onDescartar={(n) => void descartar(n)}
                                    />
                                ))}
                                {insistencias.length > 0 && (
                                    <>
                                        <Text style={styles.seccion}>Insistencias activas</Text>
                                        {insistencias.map((item) => (
                                            <TouchableOpacity
                                                key={`insistencia-${item.id}`}
                                                style={[styles.item, styles.itemInsistencia]}
                                                onPress={() => {
                                                    setVisible(false);
                                                    onSeleccionarInsistencia?.(item.id);
                                                }}
                                                activeOpacity={0.8}
                                            >
                                                <Text style={[styles.itemTitulo, { color: '#b91c1c' }]}>{item.titulo}</Text>
                                                <Text style={styles.itemMensaje}>{item.mensaje}</Text>
                                                {!!item.detalle && <Text style={styles.itemFecha}>{item.detalle}</Text>}
                                            </TouchableOpacity>
                                        ))}
                                    </>
                                )}
                            </ScrollView>
                        )}
                        {!!mensajeError && notificaciones.length > 0 && <Text style={styles.error}>{mensajeError}</Text>}
                    </View>
                </View>
            </Modal>
        </>
    );
};

const styles = StyleSheet.create({
    boton: {
        minWidth: 42,
        height: 42,
        borderRadius: 12,
        alignItems: 'center',
        justifyContent: 'center',
        backgroundColor: 'rgba(255,255,255,0.16)',
        position: 'relative',
    },
    badge: {
        position: 'absolute',
        top: -4,
        right: -4,
        minWidth: 18,
        height: 18,
        borderRadius: 9,
        paddingHorizontal: 4,
        backgroundColor: '#ef4444',
        alignItems: 'center',
        justifyContent: 'center',
        borderWidth: 1,
        borderColor: '#ffffff',
    },
    badgeTexto: { color: '#ffffff', fontSize: 10, fontWeight: '800' },
    avisoWeb: {
        width: 300,
        maxWidth: '85vw' as any,
        padding: 14,
        borderRadius: 12,
        backgroundColor: '#ffffff',
        borderWidth: 1,
        borderColor: '#bfdbfe',
        shadowColor: '#0f172a',
        shadowOpacity: 0.2,
        shadowRadius: 12,
        elevation: 8,
    },
    toastOverlay: { flex: 1, alignItems: 'flex-end', paddingTop: 46, paddingHorizontal: 14 },
    avisoTitulo: { color: '#173b70', fontWeight: '800', fontSize: 14 },
    avisoMensaje: { color: '#475569', fontSize: 13, marginTop: 5 },
    avisoAccion: { color: '#1d4ed8', fontSize: 12, fontWeight: '700', marginTop: 8 },
    fondoModal: { flex: 1, backgroundColor: 'rgba(15,23,42,0.48)', justifyContent: 'center', padding: 18 },
    modal: { width: '100%', maxWidth: 520, maxHeight: '82%', alignSelf: 'center', borderRadius: 18, backgroundColor: '#ffffff', padding: 18 },
    encabezado: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginBottom: 12 },
    titulo: { color: '#173b70', fontSize: 20, fontWeight: '800' },
    subtitulo: { color: '#64748b', fontSize: 12, marginTop: 2 },
    cerrar: { width: 34, height: 34, borderRadius: 17, backgroundColor: '#f1f5f9', alignItems: 'center', justifyContent: 'center' },
    cerrarTexto: { color: '#334155', fontSize: 23, lineHeight: 25 },
    leerTodas: { alignSelf: 'flex-end', paddingVertical: 7, paddingHorizontal: 2 },
    leerTodasTexto: { color: '#1d4ed8', fontSize: 12, fontWeight: '700' },
    lista: { flexGrow: 0 },
    item: { padding: 13, marginBottom: 9, borderRadius: 12, backgroundColor: '#f8fafc', borderWidth: 1, borderColor: '#e2e8f0' },
    itemNoLeido: { backgroundColor: '#eff6ff', borderColor: '#bfdbfe' },
    itemInsistencia: { backgroundColor: '#fef2f2', borderColor: '#fecaca' },
    seccion: { color: '#b91c1c', fontSize: 13, fontWeight: '800', marginTop: 8, marginBottom: 9 },
    itemFila: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'flex-start', gap: 8 },
    itemTitulo: { flex: 1, color: '#173b70', fontSize: 14, fontWeight: '800' },
    itemAccionesDerecha: { flexDirection: 'row', alignItems: 'center', gap: 8 },
    puntoNoLeido: { width: 9, height: 9, borderRadius: 5, backgroundColor: '#2563eb' },
    itemCerrarBtn: {
        width: 24,
        height: 24,
        borderRadius: 12,
        backgroundColor: '#e2e8f0',
        alignItems: 'center',
        justifyContent: 'center',
    },
    itemCerrarTexto: { color: '#475569', fontSize: 12, fontWeight: '800', lineHeight: 14 },
    itemMensaje: { color: '#475569', fontSize: 13, lineHeight: 18, marginTop: 5 },
    itemFecha: { color: '#94a3b8', fontSize: 11, marginTop: 7 },
    vacio: { color: '#64748b', fontSize: 14, textAlign: 'center', paddingVertical: 30 },
    error: { color: '#b91c1c', fontSize: 12, marginTop: 8 },
});
