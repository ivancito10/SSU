import React, { useState, useEffect, useRef, useCallback } from 'react';
import { NavigationContainer } from '@react-navigation/native';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { obtenerItem, guardarItem, eliminarItem } from '../utils/storage';
import { View, ActivityIndicator, Platform, Alert, AppState, Image, Text } from 'react-native';
import { registrarManejadorSesionExpirada, tokenJwtExpirado } from '../api/axios.config';
import { AppAlert } from '../components/AlertaGlobalModal';
import { theme } from '../theme/theme';
import { LoginScreen } from '../../features/auth/screens/LoginScreen';
import { vincularTokenFCM } from '../../features/auth/api/auth.service';
import { escucharAperturaNotificacion, registrarParaNotificacionesPush } from '../utils/notifications';
import { BandejaScreen } from '../../features/soportes/screens/BandejaScreen';
import { TicketDetalleScreen } from '../../features/soportes/screens/TicketDetalleScreen';
import { CrearSolicitudScreen } from '../../features/soportes/screens/CrearSolicitudScreen';
import { GestionRolesScreen } from '../../features/soportes/screens/GestionRolesScreen';
import { SinRolScreen } from '../../features/soportes/screens/SinRolScreen';
import { obtenerMisRoles } from '../../features/soportes/api/soporte.service';
import * as LocalAuthentication from 'expo-local-authentication';

const Stack = createNativeStackNavigator();
const STORAGE_KEY_LOGIN_BIOMETRICO = 'soporte_login_biometrico';
const TIEMPO_INACTIVIDAD_MS = 15 * 60 * 1000; // 15 minutos sin actividad del usuario

export const AppNavigator = () => {
    const [cargando, setCargando] = useState(true);
    const [mostrarBienvenida, setMostrarBienvenida] = useState(true);
    const [autenticado, setAutenticado] = useState(false);
    const [rolActivo, setRolActivo] = useState<string | null>(null);
    const [rolesDisponibles, setRolesDisponibles] = useState<string[]>([]);
    const [solicitudDesdeNotificacion, setSolicitudDesdeNotificacion] = useState<number | null>(null);
    const [inicioBiometricoCancelado, setInicioBiometricoCancelado] = useState(false);
    const escudosHistorialRef = useRef<number>(0);
    const biometriaInicialVerificadaRef = useRef(false);
    const autenticandoBiometriaRef = useRef(false);
    const ultimaActividadRef = useRef<number>(Date.now());
    const cerrandoSesionRef = useRef<boolean>(false);
    const tipoNotificacionPendienteRef = useRef<string | null>(null);

    const cerrarSesionPorSeguridad = useCallback(async (motivo: 'inactividad' | 'expirada') => {
        if (cerrandoSesionRef.current) return;
        cerrandoSesionRef.current = true;
        try {
            await eliminarItem('jwtToken');
            await eliminarItem('userData');
            setInicioBiometricoCancelado(false);
            setAutenticado(false);
            setRolActivo(null);
            setRolesDisponibles([]);
            const msg = motivo === 'inactividad'
                ? 'Tu sesión se cerró automáticamente por inactividad (15 minutos).'
                : 'Tu sesión ha expirado. Por favor inicia sesión nuevamente.';
            AppAlert.alert('Sesión cerrada', msg);
        } finally {
            setTimeout(() => {
                cerrandoSesionRef.current = false;
            }, 1000);
        }
    }, []);

    // Esta función la llamamos tanto al inicio como justo después del login o al pulsar "Verificar acceso"
    const verificarSesion = async (forzarConsultaServidor = false) => {
        try {
            const token = await obtenerItem('jwtToken');
            let userDataStr = await obtenerItem('userData');

            if (token && userDataStr) {
                if (tokenJwtExpirado(token)) {
                    await eliminarItem('jwtToken');
                    await eliminarItem('userData');
                    setAutenticado(false);
                    setRolActivo(null);
                    setRolesDisponibles([]);
                    return;
                }
                if (!biometriaInicialVerificadaRef.current) {
                    biometriaInicialVerificadaRef.current = true;
                    const biometriaActivada = await obtenerItem(STORAGE_KEY_LOGIN_BIOMETRICO);

                    if (Platform.OS !== 'web' && biometriaActivada === 'true' && !autenticandoBiometriaRef.current) {
                        let autenticacionBiometricaCorrecta = false;
                        autenticandoBiometriaRef.current = true;
                        try {
                            const compatible = await LocalAuthentication.hasHardwareAsync();
                            const enrollado = await LocalAuthentication.isEnrolledAsync();
                            if (compatible && enrollado) {
                                await new Promise<void>((resolve) => {
                                    requestAnimationFrame(() => {
                                        setTimeout(resolve, 450);
                                    });
                                });
                                const resultado = await LocalAuthentication.authenticateAsync({
                                    promptMessage: 'Confirma tu identidad',
                                    cancelLabel: 'Cancelar',
                                    disableDeviceFallback: true,
                                });
                                autenticacionBiometricaCorrecta = resultado.success;
                            }
                        } catch (error) {
                            console.warn('No se pudo completar el desbloqueo biométrico:', error);
                        } finally {
                            autenticandoBiometriaRef.current = false;
                        }

                        if (!autenticacionBiometricaCorrecta) {
                            await eliminarItem('jwtToken');
                            await eliminarItem('userData');
                            setInicioBiometricoCancelado(true);
                            setAutenticado(false);
                            setRolActivo(null);
                            setRolesDisponibles([]);
                            return;
                        }
                    }
                }

                let userData = JSON.parse(userDataStr);

                // Si no tiene roles o se pide forzar consulta, le preguntamos al backend si el admin ya le asignó rol
                if (forzarConsultaServidor || userData.rol === 'SIN_ROL' || !userData.roles || userData.roles.length === 0) {
                    try {
                        const respRoles = await obtenerMisRoles();
                        if (respRoles?.success) {
                            userData.rol = respRoles.rol || 'SIN_ROL';
                            userData.roles = respRoles.roles || [];
                            await guardarItem('userData', JSON.stringify(userData));
                        }
                    } catch (e) {
                        console.log('Error actualizando roles desde backend:', e);
                    }
                }

                const listaRoles: string[] = Array.isArray(userData.roles)
                    ? userData.roles.filter((r: string) => r && r !== 'SIN_ROL')
                    : [];

                const rolInicial = listaRoles.length > 0
                    ? (userData.rol && listaRoles.includes(userData.rol) ? userData.rol : listaRoles[0])
                    : 'SIN_ROL';

                setRolesDisponibles(listaRoles);
                setRolActivo(rolInicial);
                setAutenticado(true);

                // Sincronizar token FCM en segundo plano si está en dispositivo móvil
                if (Platform.OS !== 'web') {
                    registrarParaNotificacionesPush()
                        .then((fcmToken) => {
                            if (fcmToken) {
                                return vincularTokenFCM(fcmToken);
                            }
                        })
                        .catch((err) => console.log('Aviso sincronizando token FCM:', err));
                }
            } else {
                setAutenticado(false);
                setRolActivo(null);
                setRolesDisponibles([]);
            }
        } catch (error) {
            console.error('Error leyendo sesión:', error);
            setAutenticado(false);
        } finally {
            setCargando(false);
        }
    };

    // Permite cambiar entre roles (ej. OPERADOR <-> ADMINISTRADOR) en caliente y persistirlo en userData
    const cambiarRol = async (nuevoRol: string) => {
        setRolActivo(nuevoRol);
        try {
            const userDataStr = await obtenerItem('userData');
            if (userDataStr) {
                const userData = JSON.parse(userDataStr);
                userData.rol = nuevoRol;
                await guardarItem('userData', JSON.stringify(userData));
            }
        } catch (e) {
            console.log('No se pudo persistir el cambio de rol en userData:', e);
        }
    };

    useEffect(() => {
        const timerBienvenida = setTimeout(() => {
            setMostrarBienvenida(false);
            void verificarSesion();
        }, 1800);
        return () => clearTimeout(timerBienvenida);
    }, []);

    useEffect(() => {
        registrarManejadorSesionExpirada(() => {
            if (autenticado) {
                void cerrarSesionPorSeguridad('expirada');
            }
        });
        return () => registrarManejadorSesionExpirada(null);
    }, [autenticado, cerrarSesionPorSeguridad]);

    const timerInactividadRef = useRef<ReturnType<typeof setTimeout> | null>(null);
    const ultimoThrottleRef = useRef<number>(0);

    const reiniciarTemporizadorInactividad = useCallback(() => {
        if (!autenticado) return;
        const ahora = Date.now();
        ultimaActividadRef.current = ahora;
        if (timerInactividadRef.current) {
            clearTimeout(timerInactividadRef.current);
        }
        timerInactividadRef.current = setTimeout(() => {
            void cerrarSesionPorSeguridad('inactividad');
        }, TIEMPO_INACTIVIDAD_MS);
    }, [autenticado, cerrarSesionPorSeguridad]);

    const registrarActividadUsuario = useCallback(() => {
        const ahora = Date.now();
        if (ahora - ultimoThrottleRef.current < 2000) return;
        ultimoThrottleRef.current = ahora;
        reiniciarTemporizadorInactividad();
    }, [reiniciarTemporizadorInactividad]);

    useEffect(() => {
        if (!autenticado) {
            if (timerInactividadRef.current) {
                clearTimeout(timerInactividadRef.current);
                timerInactividadRef.current = null;
            }
            return;
        }

        reiniciarTemporizadorInactividad();

        const subAppState = AppState.addEventListener('change', async (estado) => {
            if (estado !== 'active') return;
            const inactivoMs = Date.now() - ultimaActividadRef.current;
            if (inactivoMs >= TIEMPO_INACTIVIDAD_MS) {
                void cerrarSesionPorSeguridad('inactividad');
                return;
            }
            const token = await obtenerItem('jwtToken');
            if (tokenJwtExpirado(token)) {
                void cerrarSesionPorSeguridad('expirada');
                return;
            }
            reiniciarTemporizadorInactividad();
        });

        if (Platform.OS === 'web' && typeof window !== 'undefined') {
            const eventos: Array<keyof WindowEventMap> = [
                'pointerdown',
                'keydown',
                'mousemove',
                'wheel',
                'touchstart',
                'scroll',
            ];
            eventos.forEach((ev) => window.addEventListener(ev, registrarActividadUsuario, true));
            return () => {
                subAppState.remove();
                eventos.forEach((ev) => window.removeEventListener(ev, registrarActividadUsuario, true));
                if (timerInactividadRef.current) {
                    clearTimeout(timerInactividadRef.current);
                    timerInactividadRef.current = null;
                }
            };
        }

        return () => {
            subAppState.remove();
            if (timerInactividadRef.current) {
                clearTimeout(timerInactividadRef.current);
                timerInactividadRef.current = null;
            }
        };
    }, [autenticado, reiniciarTemporizadorInactividad, registrarActividadUsuario, cerrarSesionPorSeguridad]);

    useEffect(() => escucharAperturaNotificacion((datos) => {
        const idSolicitud = Number(datos?.idSolicitud);
        if (Number.isFinite(idSolicitud) && idSolicitud > 0) {
            tipoNotificacionPendienteRef.current = typeof datos?.tipoNotificacion === 'string' ? datos.tipoNotificacion : null;
            setSolicitudDesdeNotificacion(idSolicitud);
        }
    }), []);

    useEffect(() => {
        if (!autenticado || !solicitudDesdeNotificacion) return;
        const tipo = tipoNotificacionPendienteRef.current;
        if (tipo === 'ASIGNACION_TECNICO' && rolesDisponibles.includes('TECNICO')) {
            if (rolActivo !== 'TECNICO') void cambiarRol('TECNICO');
        } else if (tipo === 'SOPORTE_CONCLUIDO' && rolesDisponibles.includes('USUARIO')) {
            if (rolActivo !== 'USUARIO') void cambiarRol('USUARIO');
        } else if (
            (tipo === 'NUEVA_SOLICITUD_OPERADOR' || tipo === 'INSISTENCIA_SOPORTE') &&
            rolesDisponibles.includes('OPERADOR') &&
            rolActivo === 'ADMINISTRADOR'
        ) {
            void cambiarRol('OPERADOR');
        } else if (rolActivo === 'ADMINISTRADOR') {
            if (rolesDisponibles.includes('OPERADOR')) {
                void cambiarRol('OPERADOR');
            } else if (rolesDisponibles.includes('TECNICO')) {
                void cambiarRol('TECNICO');
            }
        }
    }, [autenticado, solicitudDesdeNotificacion, rolesDisponibles, rolActivo, cambiarRol]);

    // Bloquear el botón "Atrás" propio del navegador web en TODOS los roles.
    // NOTA: Chrome ignora history.pushState si no ocurre dentro de un clic/gesto real del usuario
    // (por eso saltaba directo a Google con "Page entered Back-Forward Cache").
    // Al registrar el pushState dentro de 'pointerdown'/'keydown', Chrome lo valida como navegación real
    // y jamás permite que el botón Atrás salga hacia el buscador de Google.
    useEffect(() => {
        if (Platform.OS !== 'web' || typeof window === 'undefined') return;

        const agregarEscudoConGesto = () => {
            if (escudosHistorialRef.current < 5) {
                window.history.pushState(
                    { soporteGuard: Date.now(), nivel: escudosHistorialRef.current + 1 },
                    '',
                    window.location.href
                );
                escudosHistorialRef.current += 1;
            }
        };

        const manejarPopState = () => {
            escudosHistorialRef.current = Math.max(0, escudosHistorialRef.current - 1);
            window.history.pushState(
                { soporteGuard: Date.now(), recuperado: true },
                '',
                window.location.href
            );
        };

        // Capturamos cualquier clic, toque o tecla del usuario para blindar el historial con activación real
        window.addEventListener('pointerdown', agregarEscudoConGesto, true);
        window.addEventListener('keydown', agregarEscudoConGesto, true);
        window.addEventListener('popstate', manejarPopState);

        return () => {
            window.removeEventListener('pointerdown', agregarEscudoConGesto, true);
            window.removeEventListener('keydown', agregarEscudoConGesto, true);
            window.removeEventListener('popstate', manejarPopState);
        };
    }, []);

    if (cargando || mostrarBienvenida) {
        return (
            <View style={{ flex: 1, justifyContent: 'center', alignItems: 'center', backgroundColor: '#005096', paddingHorizontal: 28 }}>
                <Image
                    source={require('../../../assets/7.png')}
                    style={{ width: 240, height: 140, marginBottom: 22 }}
                    resizeMode="contain"
                />
                <Text style={{ color: '#ffffff', fontSize: 17, fontWeight: '700', textAlign: 'center', marginBottom: 22 }}>
                    Bienvenido a Soporte SSU La Paz
                </Text>
                <ActivityIndicator size="large" color="#ffffff" />
            </View>
        );
    }

    return (
        <View style={{ flex: 1 }} onTouchStartCapture={autenticado ? registrarActividadUsuario : undefined}>
        <NavigationContainer
            onStateChange={() => {
                if (Platform.OS === 'web' && typeof document !== 'undefined') {
                    // Quitamos el foco del elemento anterior para evitar el warning de aria-hidden en Web
                    (document.activeElement as HTMLElement)?.blur?.();
                }
            }}
        >
            <Stack.Navigator screenOptions={{ headerShown: false }}>
                {!autenticado ? (
                    <Stack.Screen name="Login">
                        {(props) => <LoginScreen {...props} onLogin={async () => {
                            // Cuando el login termina, forzamos a leer el rol antes de cambiar de pantalla
                            biometriaInicialVerificadaRef.current = true;
                            setInicioBiometricoCancelado(false);
                            setCargando(true);
                            await verificarSesion();
                        }} iniciarConContrasena={inicioBiometricoCancelado} />}
                    </Stack.Screen>
                ) : (
                    <>
                        {/* 1. Si el usuario no tiene ningún rol asignado, pantalla de espera sin acceso */}
                        {rolActivo === 'SIN_ROL' || rolesDisponibles.length === 0 ? (
                            <Stack.Screen name="Main">
                                {(props) => (
                                    <SinRolScreen
                                        {...props}
                                        onVerificar={() => verificarSesion(true)}
                                        onLogout={() => {
                                            setInicioBiometricoCancelado(false);
                                            setAutenticado(false);
                                            setRolActivo(null);
                                            setRolesDisponibles([]);
                                        }}
                                    />
                                )}
                            </Stack.Screen>
                        ) : rolActivo === 'ADMINISTRADOR' ? (
                            <Stack.Screen name="Main">
                                {(props) => (
                                    <GestionRolesScreen
                                        {...props}
                                        rolActivo={rolActivo}
                                        rolesDisponibles={rolesDisponibles}
                                        onCambiarRol={cambiarRol}
                                        onRolesActualizados={setRolesDisponibles}
                                        onNotificacionSeleccionada={(notificacion: any) => {
                                            const idSolicitud = Number(notificacion?.datos?.idSolicitud);
                                            if (Number.isFinite(idSolicitud) && idSolicitud > 0) {
                                                tipoNotificacionPendienteRef.current = notificacion?.datos?.tipoNotificacion || null;
                                                setSolicitudDesdeNotificacion(idSolicitud);
                                            }
                                        }}
                                        onLogout={() => {
                                            setInicioBiometricoCancelado(false);
                                            setAutenticado(false);
                                            setRolActivo(null);
                                            setRolesDisponibles([]);
                                        }}
                                    />
                                )}
                            </Stack.Screen>
                        ) : (
                            <Stack.Screen name="Main">
                                {(props) => (
                                    <BandejaScreen
                                        {...props}
                                        rolActivo={rolActivo}
                                        rolesDisponibles={rolesDisponibles}
                                        onCambiarRol={cambiarRol}
                                        solicitudDesdeNotificacion={solicitudDesdeNotificacion}
                                        onNotificacionAbierta={() => setSolicitudDesdeNotificacion(null)}
                                        onNotificacionSeleccionada={(notificacion: any) => {
                                            const idSolicitud = Number(notificacion?.datos?.idSolicitud);
                                            if (Number.isFinite(idSolicitud) && idSolicitud > 0) {
                                                tipoNotificacionPendienteRef.current = notificacion?.datos?.tipoNotificacion || null;
                                                setSolicitudDesdeNotificacion(idSolicitud);
                                            }
                                        }}
                                        onLogout={() => {
                                            setInicioBiometricoCancelado(false);
                                            setAutenticado(false);
                                            setRolActivo(null);
                                            setRolesDisponibles([]);
                                        }}
                                    />
                                )}
                            </Stack.Screen>
                        )}

                        <Stack.Screen
                            name="TicketDetalle"
                            component={TicketDetalleScreen}
                            options={{ headerShown: false }}
                        />
                        <Stack.Screen
                            name="CrearSolicitud"
                            component={CrearSolicitudScreen}
                            options={{ headerShown: false }}
                        />
                    </>
                )}
            </Stack.Navigator>
        </NavigationContainer>
        </View>
    );
};
