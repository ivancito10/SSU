import React, { useState, useEffect, useRef } from 'react';
import {
    Modal,
    View,
    Text,
    TouchableOpacity,
    StyleSheet,
    Animated,
    Platform,
} from 'react-native';
import { AppIcon, NombreIcono } from './AppIcon';

export type TipoAlerta = 'success' | 'error' | 'warning' | 'info';

export interface BotonAlerta {
    text?: string;
    style?: 'default' | 'cancel' | 'destructive';
    onPress?: () => void;
}

export interface ConfigAlerta {
    titulo: string;
    mensaje?: string;
    tipo?: TipoAlerta;
    botones?: BotonAlerta[];
}

type ListenerAlerta = (config: ConfigAlerta) => void;

const listeners = new Set<ListenerAlerta>();

const inferirTipoAlerta = (titulo: string, mensaje?: string): TipoAlerta => {
    const texto = `${titulo} ${mensaje || ''}`.toLowerCase();
    if (
        texto.includes('error') ||
        texto.includes('no se pudo') ||
        texto.includes('incorrect') ||
        texto.includes('inválid') ||
        texto.includes('falló')
    ) {
        return 'error';
    }
    if (
        texto.includes('éxito') ||
        texto.includes('exito') ||
        texto.includes('finalizado') ||
        texto.includes('registrado') ||
        texto.includes('registrada') ||
        texto.includes('enviada') ||
        texto.includes('derivado') ||
        texto.includes('iniciado') ||
        texto.includes('asignado') ||
        texto.includes('reasignado') ||
        texto.includes('añadió')
    ) {
        return 'success';
    }
    if (
        texto.includes('atención') ||
        texto.includes('faltan') ||
        texto.includes('requerid') ||
        texto.includes('permiso') ||
        texto.includes('vacío') ||
        texto.includes('vacios') ||
        texto.includes('sin archivo') ||
        texto.includes('sin asignación') ||
        texto.includes('debes ') ||
        texto.includes('selecciona')
    ) {
        return 'warning';
    }
    return 'info';
};

export const AppAlert = {
    alert: (
        titulo: string,
        mensaje?: string,
        botones?: BotonAlerta[],
        tipo?: TipoAlerta
    ) => {
        const config: ConfigAlerta = {
            titulo,
            mensaje,
            tipo: tipo || inferirTipoAlerta(titulo, mensaje),
            botones,
        };
        listeners.forEach((cb) => cb(config));
    },
};

const PALETA_POR_TIPO: Record<
    TipoAlerta,
    {
        icono: NombreIcono;
        colorPrincipal: string;
        fondoSuave: string;
        haloExterior: string;
        borde: string;
    }
> = {
    success: {
        icono: 'check',
        colorPrincipal: '#16a34a',
        fondoSuave: '#dcfce7',
        haloExterior: '#f0fdf4',
        borde: '#bbf7d0',
    },
    error: {
        icono: 'close',
        colorPrincipal: '#dc2626',
        fondoSuave: '#fee2e2',
        haloExterior: '#fef2f2',
        borde: '#fecaca',
    },
    warning: {
        icono: 'alert',
        colorPrincipal: '#d97706',
        fondoSuave: '#fef3c7',
        haloExterior: '#fffbeb',
        borde: '#fde68a',
    },
    info: {
        icono: 'bell',
        colorPrincipal: '#164a96',
        fondoSuave: '#dbeafe',
        haloExterior: '#eff6ff',
        borde: '#bfdbfe',
    },
};

export const AlertaGlobalModal = () => {
    const [cola, setCola] = useState<ConfigAlerta[]>([]);
    const escalaAnim = useRef(new Animated.Value(0.9)).current;
    const opacidadAnim = useRef(new Animated.Value(0)).current;

    const alertaActual = cola[0] || null;

    useEffect(() => {
        const manejarNuevaAlerta: ListenerAlerta = (config) => {
            setCola((prev) => [...prev, config]);
        };
        listeners.add(manejarNuevaAlerta);
        return () => {
            listeners.delete(manejarNuevaAlerta);
        };
    }, []);

    useEffect(() => {
        if (alertaActual) {
            escalaAnim.setValue(0.88);
            opacidadAnim.setValue(0);
            Animated.parallel([
                Animated.spring(escalaAnim, {
                    toValue: 1,
                    friction: 7,
                    tension: 90,
                    useNativeDriver: true,
                }),
                Animated.timing(opacidadAnim, {
                    toValue: 1,
                    duration: 180,
                    useNativeDriver: true,
                }),
            ]).start();
        }
    }, [alertaActual, escalaAnim, opacidadAnim]);

    const cerrarYEjecutar = (onPress?: () => void) => {
        setCola((prev) => prev.slice(1));
        if (onPress) {
            setTimeout(onPress, 50);
        }
    };

    if (!alertaActual) return null;

    const tipo = alertaActual.tipo || 'info';
    const paleta = PALETA_POR_TIPO[tipo];
    const botones: BotonAlerta[] =
        alertaActual.botones && alertaActual.botones.length > 0
            ? alertaActual.botones
            : [{ text: 'Entendido', style: 'default' }];

    return (
        <Modal
            visible={Boolean(alertaActual)}
            transparent
            animationType="fade"
            statusBarTranslucent
            onRequestClose={() => cerrarYEjecutar(botones[0]?.onPress)}
        >
            <View style={styles.overlay}>
                <Animated.View
                    style={[
                        styles.card,
                        {
                            opacity: opacidadAnim,
                            transform: [{ scale: escalaAnim }],
                        },
                    ]}
                >
                    {/* Franja decorativa superior */}
                    <View style={[styles.topStrip, { backgroundColor: paleta.colorPrincipal }]} />

                    <View style={styles.body}>
                        {/* Icono circular institucional con doble halo */}
                        <View
                            style={[
                                styles.iconHaloOuter,
                                { backgroundColor: paleta.haloExterior, borderColor: paleta.borde },
                            ]}
                        >
                            <View style={[styles.iconHaloInner, { backgroundColor: paleta.fondoSuave }]}>
                                <AppIcon name={paleta.icono} size={26} color={paleta.colorPrincipal} />
                            </View>
                        </View>

                        <Text style={styles.title}>{alertaActual.titulo}</Text>

                        {Boolean(alertaActual.mensaje) && (
                            <Text style={styles.message}>{alertaActual.mensaje}</Text>
                        )}

                        <View
                            style={[
                                styles.buttonsRow,
                                botones.length > 2 && { flexDirection: 'column' },
                            ]}
                        >
                            {botones.map((btn, idx) => {
                                const esCancelar = btn.style === 'cancel';
                                const esDestructivo = btn.style === 'destructive';
                                const bgColor = esCancelar
                                    ? '#f1f5f9'
                                    : esDestructivo
                                    ? '#dc2626'
                                    : tipo === 'success'
                                    ? '#16a34a'
                                    : '#164a96';
                                const textColor = esCancelar ? '#475569' : '#ffffff';

                                return (
                                    <TouchableOpacity
                                        key={idx}
                                        style={[
                                            styles.button,
                                            botones.length === 2 && { flex: 1 },
                                            { backgroundColor: bgColor },
                                            esCancelar && styles.cancelButtonBorder,
                                        ]}
                                        onPress={() => cerrarYEjecutar(btn.onPress)}
                                        activeOpacity={0.85}
                                    >
                                        <Text style={[styles.buttonText, { color: textColor }]}>
                                            {btn.text || 'Aceptar'}
                                        </Text>
                                    </TouchableOpacity>
                                );
                            })}
                        </View>
                    </View>
                </Animated.View>
            </View>
        </Modal>
    );
};

const styles = StyleSheet.create({
    overlay: {
        flex: 1,
        backgroundColor: 'rgba(15, 23, 42, 0.56)',
        justifyContent: 'center',
        alignItems: 'center',
        paddingHorizontal: 24,
    },
    card: {
        width: '100%',
        maxWidth: 380,
        backgroundColor: '#ffffff',
        borderRadius: 24,
        overflow: 'hidden',
        borderWidth: 1,
        borderColor: '#e2e8f0',
        ...Platform.select({
            ios: {
                shadowColor: '#0f172a',
                shadowOffset: { width: 0, height: 14 },
                shadowOpacity: 0.22,
                shadowRadius: 24,
            },
            android: {
                elevation: 14,
            },
            web: {
                boxShadow: '0px 18px 40px rgba(15, 23, 42, 0.24)',
            } as any,
        }),
    },
    topStrip: {
        height: 5,
        width: '100%',
    },
    body: {
        paddingHorizontal: 24,
        paddingTop: 24,
        paddingBottom: 22,
        alignItems: 'center',
    },
    iconHaloOuter: {
        width: 72,
        height: 72,
        borderRadius: 36,
        borderWidth: 1.5,
        justifyContent: 'center',
        alignItems: 'center',
        marginBottom: 16,
    },
    iconHaloInner: {
        width: 54,
        height: 54,
        borderRadius: 27,
        justifyContent: 'center',
        alignItems: 'center',
    },
    title: {
        fontSize: 19,
        fontWeight: '800',
        color: '#0f172a',
        textAlign: 'center',
        marginBottom: 8,
        letterSpacing: -0.2,
    },
    message: {
        fontSize: 14.5,
        lineHeight: 21,
        color: '#475569',
        textAlign: 'center',
        marginBottom: 22,
    },
    buttonsRow: {
        flexDirection: 'row',
        width: '100%',
        gap: 10,
    },
    button: {
        minHeight: 48,
        borderRadius: 24,
        justifyContent: 'center',
        alignItems: 'center',
        paddingHorizontal: 20,
        width: '100%',
    },
    cancelButtonBorder: {
        borderWidth: 1,
        borderColor: '#cbd5e1',
    },
    buttonText: {
        fontSize: 15,
        fontWeight: '700',
        letterSpacing: 0.2,
    },
});
