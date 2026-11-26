import React, { useState, useEffect, useRef, useCallback } from 'react';
import {
    View,
    Text,
    TextInput,
    TouchableOpacity,
    StyleSheet,
    ActivityIndicator,
    Alert,
    Platform,
    Image,
    ScrollView,
    KeyboardAvoidingView,
} from 'react-native';
import { useWindowDimensions, } from 'react-native';
import { theme } from '../../../core/theme/theme';
import { AppAlert } from '../../../core/components/AlertaGlobalModal';
import { AppIcon } from '../../../core/components/AppIcon';
import { guardarItem, obtenerItem, eliminarItem } from '../../../core/utils/storage';
import { login } from '../api/auth.service';
import { registrarParaNotificacionesPush } from '../../../core/utils/notifications';
import { vincularTokenFCM } from '../api/auth.service';
import * as LocalAuthentication from 'expo-local-authentication';

const STORAGE_KEY_CREDENCIALES = 'soporte_credenciales_recordadas';
const STORAGE_KEY_LOGIN_BIOMETRICO = 'soporte_login_biometrico';

// 1. Icono de Usuario con círculo (izquierda del campo Usuario)
const IconoUsuarioInput = () => {
    if (Platform.OS === 'web') {
        return (
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1e5bb0" strokeWidth="1.9" strokeLinecap="round" strokeLinejoin="round">
                <circle cx="12" cy="12" r="10" />
                <circle cx="12" cy="9" r="3" />
                <path d="M6.7 19.3a6.5 6.5 0 0 1 10.6 0" />
            </svg>
        );
    }
    return (
        <View style={{ width: 22, height: 22, borderRadius: 11, borderWidth: 1.6, borderColor: '#1e5bb0', justifyContent: 'center', alignItems: 'center', overflow: 'hidden' }}>
            <View style={{ width: 6, height: 6, borderRadius: 3, borderWidth: 1.4, borderColor: '#1e5bb0', marginTop: 2 }} />
            <View style={{ width: 12, height: 6, borderTopLeftRadius: 6, borderTopRightRadius: 6, borderWidth: 1.4, borderColor: '#1e5bb0', marginTop: 2 }} />
        </View>
    );
};

// 2. Icono de Candado (izquierda del campo Contraseña)
const IconoCandadoInput = () => {
    if (Platform.OS === 'web') {
        return (
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1e5bb0" strokeWidth="1.9" strokeLinecap="round" strokeLinejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
            </svg>
        );
    }
    return (
        <View style={{ width: 22, height: 22, justifyContent: 'center', alignItems: 'center' }}>
            <View style={{ width: 10, height: 7, borderTopLeftRadius: 5, borderTopRightRadius: 5, borderWidth: 1.6, borderBottomWidth: 0, borderColor: '#1e5bb0', marginBottom: -1 }} />
            <View style={{ width: 15, height: 11, borderRadius: 3, borderWidth: 1.6, borderColor: '#1e5bb0', justifyContent: 'center', alignItems: 'center' }}>
                <View style={{ width: 2.5, height: 3.5, borderRadius: 1.5, backgroundColor: '#1e5bb0' }} />
            </View>
        </View>
    );
};

// 3. Icono de Ojo (derecha del campo Contraseña)
const IconoOjoMinimalista = ({ abierto }: { abierto: boolean }) => {
    if (Platform.OS === 'web') {
        return abierto ? (
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#64748b" strokeWidth="1.9" strokeLinecap="round" strokeLinejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                <circle cx="12" cy="12" r="3" />
            </svg>
        ) : (
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#64748b" strokeWidth="1.9" strokeLinecap="round" strokeLinejoin="round">
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" />
                <line x1="1" y1="1" x2="23" y2="23" />
            </svg>
        );
    }

    return (
        <View style={{ width: 24, height: 18, justifyContent: 'center', alignItems: 'center' }}>
            <View
                style={{
                    width: 21,
                    height: 12,
                    borderRadius: 10,
                    borderWidth: 1.7,
                    borderColor: '#64748b',
                    justifyContent: 'center',
                    alignItems: 'center',
                }}
            >
                <View style={{ width: 5.5, height: 5.5, borderRadius: 3, backgroundColor: '#64748b' }} />
            </View>
            {!abierto && (
                <View
                    style={{
                        position: 'absolute',
                        width: 23,
                        height: 1.8,
                        backgroundColor: '#64748b',
                        transform: [{ rotate: '-35deg' }],
                    }}
                />
            )}
        </View>
    );
};

// 4. Icono de Ingresar [->]
const IconoIngresarBtn = () => {
    if (Platform.OS === 'web') {
        return (
            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#ffffff" strokeWidth="2.3" strokeLinecap="round" strokeLinejoin="round" style={{ marginLeft: 8 }}>
                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                <polyline points="10 17 15 12 10 7" />
                <line x1="15" y1="12" x2="3" y2="12" />
            </svg>
        );
    }
    return (
        <View style={{ flexDirection: 'row', alignItems: 'center', marginLeft: 8 }}>
            <Text style={{ color: '#fff', fontSize: 16, fontWeight: 'bold', marginRight: -4 }}>→</Text>
            <View
                style={{
                    width: 11,
                    height: 16,
                    borderTopWidth: 2,
                    borderRightWidth: 2,
                    borderBottomWidth: 2,
                    borderColor: '#ffffff',
                    borderTopRightRadius: 3,
                    borderBottomRightRadius: 3,
                }}
            />
        </View>
    );
};

export const LoginScreen = ({ onLogin, iniciarConContrasena = false }: { onLogin: () => void; iniciarConContrasena?: boolean }) => {
    const [usuario, setUsuario] = useState('');
    const [contrasena, setContrasena] = useState('');
    const [mostrarContrasena, setMostrarContrasena] = useState(false);
    const [recordarContrasena, setRecordarContrasena] = useState(false);
    const [cargando, setCargando] = useState(false);
    const [metodoCargando, setMetodoCargando] = useState<'password' | 'biometrico' | null>(null);
    const [biometriaDisponible, setBiometriaDisponible] = useState(false);
    const [tipoBiometria, setTipoBiometria] = useState<'faceid' | 'huella' | 'generico'>('generico');
    const [usarBiometria, setUsarBiometria] = useState(false);
    const [biometriaActiva, setBiometriaActiva] = useState(false);
    const [biometriaGuardadaAlIniciar, setBiometriaGuardadaAlIniciar] = useState(false);
    const [preferenciasCargadas, setPreferenciasCargadas] = useState(false);
    const [mostrarLoginManual, setMostrarLoginManual] = useState(iniciarConContrasena);
    const [errorBiometrico, setErrorBiometrico] = useState('');
    const intentoBiometricoAutomatico = useRef(false);
    const autenticandoRef = useRef(false);

    const { height } = useWindowDimensions();
    // En celular queda ~230px; en tablet crece proporcionalmente hasta 320px
    const heroMinHeight = Math.max(230, Math.min(height * 0.30, 320));

    const registrarPushSinBloquearLogin = () => {
        void (async () => {
            try {
                const tokenFCM = await registrarParaNotificacionesPush();
                if (tokenFCM) {
                    await vincularTokenFCM(tokenFCM);
                }
            } catch (error) {
                console.warn('Notificaciones omitidas en este entorno:', error);
            }
        })();
    };

    useEffect(() => {
        const cargarCredencialesRecordadas = async () => {
            try {
                if (Platform.OS !== 'web') {
                    const [tieneHardware, tieneBiometria, tipos, biometriaGuardada] = await Promise.all([
                        LocalAuthentication.hasHardwareAsync(),
                        LocalAuthentication.isEnrolledAsync(),
                        LocalAuthentication.supportedAuthenticationTypesAsync(),
                        obtenerItem(STORAGE_KEY_LOGIN_BIOMETRICO),
                    ]);
                    let tipoDetectado: 'faceid' | 'huella' | 'generico' = 'generico';
                    if (Platform.OS === 'ios' && tipos.includes(LocalAuthentication.AuthenticationType.FACIAL_RECOGNITION)) {
                        tipoDetectado = 'faceid';
                    } else if (tipos.includes(LocalAuthentication.AuthenticationType.FINGERPRINT)) {
                        tipoDetectado = 'huella';
                    } else if (tipos.includes(LocalAuthentication.AuthenticationType.FACIAL_RECOGNITION)) {
                        tipoDetectado = 'faceid';
                    }
                    setTipoBiometria(tipoDetectado);
                    const biometriaConfigurada = biometriaGuardada === 'true' && tieneHardware && tieneBiometria;
                    setBiometriaDisponible(tieneHardware && tieneBiometria);
                    setBiometriaGuardadaAlIniciar(biometriaConfigurada);
                    setBiometriaActiva(biometriaConfigurada);
                    setUsarBiometria(biometriaConfigurada);
                }
                const guardadasStr = await obtenerItem(STORAGE_KEY_CREDENCIALES);
                if (guardadasStr) {
                    const parsed = JSON.parse(guardadasStr);
                    if (parsed?.usuario && parsed?.contrasena) {
                        setUsuario(parsed.usuario);
                        setContrasena(parsed.contrasena);
                        setRecordarContrasena(true);
                    }
                }
            } catch (e) {
                console.log('No se pudieron cargar credenciales recordadas:', e);
            } finally {
                setPreferenciasCargadas(true);
            }
        };
        cargarCredencialesRecordadas();
    }, []);

    const handleLogin = async () => {
        if (!usuario || !contrasena) {
            AppAlert.alert('Campos vacíos', 'Por favor ingresa tu usuario y contraseña.');
            return;
        }

        setMetodoCargando('password');
        setCargando(true);
        try {
            await login(usuario, contrasena);

            try {
                if (recordarContrasena || usarBiometria) {
                    await guardarItem(
                        STORAGE_KEY_CREDENCIALES,
                        JSON.stringify({ usuario, contrasena })
                    );
                } else {
                    await eliminarItem(STORAGE_KEY_CREDENCIALES);
                }
                if (usarBiometria) {
                    await guardarItem(STORAGE_KEY_LOGIN_BIOMETRICO, 'true');
                    setBiometriaActiva(true);
                } else {
                    await eliminarItem(STORAGE_KEY_LOGIN_BIOMETRICO);
                    setBiometriaActiva(false);
                }
            } catch (errCred) {
                console.log('No se pudo actualizar credenciales recordadas:', errCred);
            }

            registrarPushSinBloquearLogin();
            onLogin();
        } catch (error: any) {
            console.error('Error detallado del servidor:', error);
            const mensajeError = error.response?.data?.message;

            const textoFinal = Array.isArray(mensajeError)
                ? mensajeError.join('\n')
                : mensajeError || (error?.code === 'ECONNABORTED'
                    ? 'El servidor no respondió en 15 segundos. Revisa la conexión e intenta nuevamente.'
                    : 'Credenciales incorrectas o error de red');

            AppAlert.alert('Error al ingresar', textoFinal);
        } finally {
            setCargando(false);
            setMetodoCargando(null);
        }
    };

    const handleBiometricLogin = useCallback(async () => {
        if (Platform.OS === 'web' || autenticandoRef.current) return;
        autenticandoRef.current = true;
        setErrorBiometrico('');
        setMetodoCargando('biometrico');
        setCargando(true);
        try {
            const compatible = await LocalAuthentication.hasHardwareAsync();
            const enrollado = await LocalAuthentication.isEnrolledAsync();
            if (!compatible || !enrollado) {
                setBiometriaDisponible(false);
                setUsarBiometria(false);
                setMostrarLoginManual(true);
                return;
            }

            const credencialesStr = await obtenerItem(STORAGE_KEY_CREDENCIALES);
            const credenciales = credencialesStr ? JSON.parse(credencialesStr) : null;
            if (!credenciales?.usuario || !credenciales?.contrasena) {
                throw new Error('No hay credenciales guardadas. Ingresa con tu contraseña y activa el acceso biométrico.');
            }

            const promptMessage =
                tipoBiometria === 'faceid'
                    ? 'Confirma tu identidad con Face ID'
                    : tipoBiometria === 'huella'
                        ? 'Confirma tu identidad con tu huella'
                        : 'Confirma tu identidad';

            const resultado = await LocalAuthentication.authenticateAsync({
                promptMessage,
                cancelLabel: 'Cancelar',
                disableDeviceFallback: true,
            });
            if (!resultado.success) {
                if (resultado.error !== 'user_cancel' && resultado.error !== 'system_cancel' && resultado.error !== 'app_cancel') {
                    const msgFallo =
                        tipoBiometria === 'faceid'
                            ? 'No se pudo validar con Face ID. Intenta de nuevo o ingresa con tu contraseña.'
                            : tipoBiometria === 'huella'
                                ? 'No se pudo validar la huella. Intenta de nuevo o ingresa con tu contraseña.'
                                : 'No se pudo validar tu biometría. Intenta de nuevo o ingresa con tu contraseña.';
                    setErrorBiometrico(msgFallo);
                }
                return;
            }

            await login(credenciales.usuario, credenciales.contrasena);
            registrarPushSinBloquearLogin();
            onLogin();
        } catch (error: any) {
            const mensaje = error?.response?.data?.message || (error?.code === 'ECONNABORTED'
                ? 'El servidor no respondió en 15 segundos. Revisa la conexión e intenta nuevamente.'
                : error?.message || 'No se pudo ingresar con biometría.');
            setErrorBiometrico(Array.isArray(mensaje) ? mensaje.join('\n') : mensaje);
        } finally {
            autenticandoRef.current = false;
            setCargando(false);
            setMetodoCargando(null);
        }
    }, [tipoBiometria]);

    useEffect(() => {
        if (
            Platform.OS !== 'web' && preferenciasCargadas && biometriaGuardadaAlIniciar && biometriaActiva && usarBiometria &&
            !mostrarLoginManual && !intentoBiometricoAutomatico.current
        ) {
            let timerId: ReturnType<typeof setTimeout> | null = null;
            const rafId = requestAnimationFrame(() => {
                timerId = setTimeout(() => {
                    if (!intentoBiometricoAutomatico.current) {
                        intentoBiometricoAutomatico.current = true;
                        void handleBiometricLogin();
                    }
                }, 450);
            });
            return () => {
                cancelAnimationFrame(rafId);
                if (timerId) clearTimeout(timerId);
            };
        }
    }, [preferenciasCargadas, biometriaGuardadaAlIniciar, biometriaActiva, usarBiometria, mostrarLoginManual, handleBiometricLogin]);

    const mostrarSoloBiometria = Platform.OS !== 'web' && biometriaGuardadaAlIniciar && biometriaActiva && usarBiometria && !mostrarLoginManual;
    const textosBiometria =
        tipoBiometria === 'faceid'
            ? {
                titulo: 'Iniciar con Face ID',
                subtitulo: 'Confirma tu identidad con Face ID para ingresar.',
                esperando: 'Esperando validación con Face ID...',
                sugerencia: 'Toca el ícono de Face ID o vuelve a intentar',
                botonManual: 'INGRESAR CON FACE ID',
                activarTexto: 'Activar ingreso con Face ID',
            }
            : tipoBiometria === 'huella'
                ? {
                    titulo: 'Iniciar con huella',
                    subtitulo: 'Confirma tu identidad con tu huella digital.',
                    esperando: 'Esperando validación con huella...',
                    sugerencia: 'Toca el sensor de huella o vuelve a intentar',
                    botonManual: 'INGRESAR CON HUELLA',
                    activarTexto: 'Activar ingreso con huella',
                }
                : {
                    titulo: 'Login biométrico',
                    subtitulo: 'Confirma tu identidad con la biometría de este dispositivo.',
                    esperando: 'Esperando validación biométrica…',
                    sugerencia: 'Toca el sensor o vuelve a intentar',
                    botonManual: 'INGRESAR CON BIOMETRÍA',
                    activarTexto: Platform.OS === 'ios' ? 'Activar Face ID / Touch ID' : 'Activar ingreso biométrico',
                };

    return (
        <View style={styles.root}>
            <KeyboardAvoidingView
                style={{ flex: 1 }}
                behavior={Platform.OS === 'ios' ? 'padding' : undefined}
            >
                <ScrollView
                    contentContainerStyle={styles.scrollContent}
                    keyboardShouldPersistTaps="handled"
                    bounces={false}
                >
                    {/* BLOQUE SUPERIOR AGRUPADO: así la tarjeta nunca se despega de la cabecera */}
                    <View>
                        {/* 1. CABECERA AZUL CON ALTURA RESPONSIVA */}
                        <View style={[styles.heroHeader, { minHeight: heroMinHeight }]}>
                            <View style={styles.heroGlowCircle1} />
                            <View style={styles.heroGlowCircle2} />

                            <Image
                                source={require('../../../../assets/7.png')}
                                style={styles.heroLogoWhite}
                                resizeMode="contain"
                            />
                            <Text style={styles.heroSubtitle}>Sistema de Gestión de Soporte</Text>

                            <Image
                                source={require('../../../../assets/ondas-superiores.png')}
                                style={styles.topWaveImage}
                                resizeMode="stretch"
                            />
                        </View>

                        {/* 2. TARJETA FLOTANTE DE INICIO DE SESIÓN */}
                        <View style={styles.cardWrapper}>
                            <View style={styles.card}>
                                {mostrarSoloBiometria ? (
                                    <View style={styles.biometricOnlyPanel}>
                                        <Text style={styles.cardTitle}>{textosBiometria.titulo}</Text>
                                        <Text style={styles.cardSubtitle}>
                                            {textosBiometria.subtitulo}
                                        </Text>
                                        <TouchableOpacity
                                            style={styles.fingerprintCircle}
                                            onPress={handleBiometricLogin}
                                            disabled={cargando}
                                            activeOpacity={0.75}
                                        >
                                            {tipoBiometria === 'faceid' ? (
                                                <AppIcon name="face-id" size={46} color="#0f8b78" />
                                            ) : tipoBiometria === 'huella' ? (
                                                <AppIcon name="fingerprint" size={46} color="#0f8b78" />
                                            ) : (
                                                <Text style={styles.fingerprintMark}>◎</Text>
                                            )}
                                        </TouchableOpacity>
                                        <Text style={styles.biometricHint}>
                                            {cargando ? textosBiometria.esperando : textosBiometria.sugerencia}
                                        </Text>
                                        {Boolean(errorBiometrico) && (
                                            <Text style={styles.biometricError}>{errorBiometrico}</Text>
                                        )}
                                        <TouchableOpacity
                                            style={styles.biometricButton}
                                            onPress={handleBiometricLogin}
                                            disabled={cargando}
                                            activeOpacity={0.8}
                                        >
                                            {cargando && metodoCargando === 'biometrico' ? <ActivityIndicator color="#1d5bbf" /> : (
                                                <Text style={styles.biometricButtonText}>VOLVER A INTENTAR</Text>
                                            )}
                                        </TouchableOpacity>
                                        <TouchableOpacity
                                            style={styles.manualLoginLink}
                                            onPress={() => {
                                                setMostrarLoginManual(true);
                                                setErrorBiometrico('');
                                            }}
                                            activeOpacity={0.7}
                                        >
                                            <Text style={styles.manualLoginLinkText}>Usar usuario y contraseña</Text>
                                        </TouchableOpacity>
                                    </View>
                                ) : <>
                                <Text style={styles.cardTitle}>Iniciar sesión</Text>
                                <Text style={styles.cardSubtitle}>
                                    Accede a tu cuenta para gestionar soportes técnicos
                                </Text>

                                {/* CAMPO USUARIO (PÍLDORA REDONDEADA CON ICONO Y BOTÓN X) */}
                                <View style={styles.pillInputWrapper}>
                                    <View style={styles.leftIconBox}>
                                        <IconoUsuarioInput />
                                    </View>
                                    <TextInput
                                        style={styles.pillInput}
                                        placeholder="Usuario o correo institucional"
                                        placeholderTextColor="#94a3b8"
                                        value={usuario}
                                        onChangeText={setUsuario}
                                        autoCapitalize="none"
                                        keyboardType="email-address"
                                    />
                                    {usuario.length > 0 && (
                                        <TouchableOpacity
                                            style={styles.rightActionBox}
                                            onPress={() => setUsuario('')}
                                            activeOpacity={0.7}
                                        >
                                            <Text style={styles.clearBtnText}>✕</Text>
                                        </TouchableOpacity>
                                    )}
                                </View>

                                {/* CAMPO CONTRASEÑA (PÍLDORA REDONDEADA CON CANDADO Y OJO) */}
                                <View style={styles.pillInputWrapper}>
                                    <View style={styles.leftIconBox}>
                                        <IconoCandadoInput />
                                    </View>
                                    <TextInput
                                        style={styles.pillInput}
                                        placeholder="••••••••"
                                        placeholderTextColor="#94a3b8"
                                        value={contrasena}
                                        onChangeText={setContrasena}
                                        secureTextEntry={!mostrarContrasena}
                                        autoCapitalize="none"
                                    />
                                    <TouchableOpacity
                                        style={styles.rightActionBox}
                                        onPress={() => setMostrarContrasena((prev) => !prev)}
                                        activeOpacity={0.7}
                                    >
                                        <IconoOjoMinimalista abierto={mostrarContrasena} />
                                    </TouchableOpacity>
                                </View>

                                {/* BOTÓN INGRESAR TIPO PÍLDORA AZUL */}
                                <TouchableOpacity
                                    style={styles.loginButton}
                                    onPress={handleLogin}
                                    disabled={cargando}
                                    activeOpacity={0.85}
                                >
                                    {cargando && metodoCargando === 'password' ? (
                                        <ActivityIndicator color="#ffffff" />
                                    ) : (
                                        <View style={styles.loginButtonInner}>
                                            <Text style={styles.loginButtonText}>INGRESAR</Text>
                                            <IconoIngresarBtn />
                                        </View>
                                    )}
                                </TouchableOpacity>

                                {/* CHECKBOX "Recordarme" DEBAJO DEL BOTÓN */}
                                {Platform.OS !== 'web' && biometriaActiva && usarBiometria && (
                                    <TouchableOpacity
                                        style={styles.biometricButton}
                                        onPress={handleBiometricLogin}
                                        disabled={cargando}
                                        activeOpacity={0.8}
                                    >
                                        {cargando && metodoCargando === 'biometrico' ? <ActivityIndicator color="#1d5bbf" /> : (
                                            <View style={{ flexDirection: 'row', alignItems: 'center', gap: 8 }}>
                                                {tipoBiometria !== 'generico' && (
                                                    <AppIcon name={tipoBiometria === 'faceid' ? 'face-id' : 'fingerprint'} size={18} color="#1d4ed8" />
                                                )}
                                                <Text style={styles.biometricButtonText}>{textosBiometria.botonManual}</Text>
                                            </View>
                                        )}
                                    </TouchableOpacity>
                                )}

                                <TouchableOpacity
                                    style={styles.rememberRow}
                                    onPress={() => setRecordarContrasena((prev) => !prev)}
                                    activeOpacity={0.7}
                                >
                                    <View style={[styles.checkbox, recordarContrasena && styles.checkboxChecked]}>
                                        {recordarContrasena && <Text style={styles.checkboxMark}>✓</Text>}
                                    </View>
                                    <Text style={styles.rememberText}>Recordarme</Text>
                                </TouchableOpacity>

                                {Platform.OS !== 'web' && biometriaDisponible && (
                                    <TouchableOpacity
                                        style={[styles.rememberRow, { marginTop: 4 }]}
                                        onPress={() => {
                                            const activar = !usarBiometria;
                                            setUsarBiometria(activar);
                                            setMostrarLoginManual(false);
                                            setErrorBiometrico('');
                                            if (!activar) {
                                                setBiometriaActiva(false);
                                                intentoBiometricoAutomatico.current = false;
                                                void eliminarItem(STORAGE_KEY_LOGIN_BIOMETRICO);
                                            }
                                        }}
                                        activeOpacity={0.7}
                                    >
                                        <View style={[styles.checkbox, usarBiometria && styles.checkboxChecked]}>
                                            {usarBiometria && <Text style={styles.checkboxMark}>✓</Text>}
                                        </View>
                                        <Text style={styles.rememberText}>
                                            {textosBiometria.activarTexto}
                                        </Text>
                                    </TouchableOpacity>
                                )}
                                </>}
                            </View>
                        </View>
                    </View>

                    {/* 3. PIE CON LOGO SigHov2 (4.jpeg) + "móvil" */}
                    <View style={styles.footerBrandContainer}>
                        <View style={{ alignItems: 'flex-end' }}>
                            <Image
                                source={require('../../../../assets/4.jpeg')}
                                style={styles.sighoLogo}
                                resizeMode="contain"
                            />
                            <Text style={styles.movilSubtext}>móvil</Text>
                        </View>
                    </View>

                    {/* 4. FORMAS ONDULADAS CELESTES EN LA PARTE INFERIOR (debajo del logotipo SigHov2) */}
                    <View style={styles.bottomWavesContainer} pointerEvents="none">
                        <Image
                            source={require('../../../../assets/ondas-inferiores.png')}
                            style={styles.bottomWaveImage}
                            resizeMode="stretch"
                        />
                    </View>
                </ScrollView>
            </KeyboardAvoidingView>
        </View>
    );
};

const styles = StyleSheet.create({
    root: {
        flex: 1,
        backgroundColor: '#f8fafc',
    },
    scrollContent: {
        flexGrow: 1,
        justifyContent: 'space-between',
        backgroundColor: '#f8fafc',
    },

    // CABECERA AZUL INSTITUCIONAL
    heroHeader: {
        backgroundColor: '#164a96',
        paddingTop: Platform.OS === 'web' ? 38 : 54,
        paddingBottom: 66,
        alignItems: 'center',
        justifyContent: 'center',
        position: 'relative',
        overflow: 'hidden',
    },
    heroGlowCircle1: {
        position: 'absolute',
        width: 280,
        height: 280,
        borderRadius: 140,
        backgroundColor: 'rgba(255, 255, 255, 0.05)',
        top: -80,
        left: -60,
    },
    heroGlowCircle2: {
        position: 'absolute',
        width: 240,
        height: 240,
        borderRadius: 120,
        backgroundColor: 'rgba(56, 189, 248, 0.09)',
        bottom: -50,
        right: -40,
    },
    heroLogoWhite: {
        width: 195,
        height: 82,
    },
    heroSubtitle: {
        color: '#e0f2fe',
        fontSize: 13,
        fontWeight: '600',
        marginTop: 6,
        letterSpacing: 0.3,
    },
    topWaveImage: {
        position: 'absolute',
        bottom: -2,
        left: 0,
        right: 0,
        width: '100%',
        height: 54,
    },

    // TARJETA CENTRAL
    cardWrapper: {
        paddingHorizontal: 20,
        marginTop: -22,
        zIndex: 5,
    },
    card: {
        backgroundColor: '#ffffff',
        width: '100%',
        maxWidth: 430,
        alignSelf: 'center',
        borderRadius: 20,
        paddingHorizontal: 22,
        paddingVertical: 24,
        elevation: 6,
        shadowColor: '#0f172a',
        shadowOffset: { width: 0, height: 6 },
        shadowOpacity: 0.1,
        shadowRadius: 14,
        borderWidth: 1,
        borderColor: '#f1f5f9',
    },
    cardTitle: {
        fontSize: 19,
        fontWeight: 'bold',
        color: '#0f294a',
        textAlign: 'center',
    },
    cardSubtitle: {
        fontSize: 12.5,
        color: '#64748b',
        textAlign: 'center',
        marginTop: 4,
        marginBottom: 20,
    },
    biometricOnlyPanel: {
        alignItems: 'center',
        paddingVertical: 8,
    },
    fingerprintCircle: {
        width: 84,
        height: 84,
        borderRadius: 42,
        borderWidth: 2,
        borderColor: '#d1fae5',
        backgroundColor: '#f0fdfa',
        alignItems: 'center',
        justifyContent: 'center',
        marginVertical: 18,
    },
    fingerprintMark: {
        color: '#0f8b78',
        fontSize: 54,
        lineHeight: 64,
    },
    biometricHint: {
        color: '#64748b',
        textAlign: 'center',
        fontSize: 13,
        marginBottom: 8,
    },
    biometricError: {
        color: '#b42318',
        textAlign: 'center',
        fontSize: 12,
        lineHeight: 18,
        marginTop: 4,
        marginBottom: 6,
    },
    manualLoginLink: {
        paddingVertical: 12,
        paddingHorizontal: 10,
        marginTop: 4,
    },
    manualLoginLinkText: {
        color: '#1d5bbf',
        fontWeight: '600',
        fontSize: 13,
    },

    // INPUTS EN FORMA DE PÍLDORA
    pillInputWrapper: {
        flexDirection: 'row',
        alignItems: 'center',
        borderWidth: 1,
        borderColor: '#cbd5e1',
        borderRadius: 28,
        backgroundColor: '#ffffff',
        height: 50,
        marginBottom: 14,
        paddingHorizontal: 14,
    },
    leftIconBox: {
        marginRight: 10,
        justifyContent: 'center',
        alignItems: 'center',
    },
    pillInput: {
        flex: 1,
        fontSize: 14.5,
        color: '#1e293b',
        paddingVertical: 8,
    },
    rightActionBox: {
        paddingLeft: 10,
        paddingVertical: 8,
        justifyContent: 'center',
        alignItems: 'center',
    },
    clearBtnText: {
        fontSize: 15,
        color: '#475569',
        fontWeight: 'bold',
    },

    // BOTÓN INGRESAR
    loginButton: {
        backgroundColor: '#1d5bbf',
        height: 48,
        borderRadius: 26,
        justifyContent: 'center',
        alignItems: 'center',
        marginTop: 4,
        elevation: 3,
        shadowColor: '#1d5bbf',
        shadowOffset: { width: 0, height: 3 },
        shadowOpacity: 0.28,
        shadowRadius: 6,
    },
    loginButtonInner: {
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'center',
    },
    loginButtonText: {
        color: '#ffffff',
        fontWeight: 'bold',
        fontSize: 14.5,
        letterSpacing: 0.6,
    },
    biometricButton: {
        backgroundColor: '#eff6ff',
        borderColor: '#bfdbfe',
        borderWidth: 1,
        height: 46,
        borderRadius: 24,
        justifyContent: 'center',
        alignItems: 'center',
        marginTop: 10,
    },
    biometricButtonText: {
        color: '#1d4ed8',
        fontWeight: '700',
        fontSize: 12,
        letterSpacing: 0.2,
    },

    // CHECKBOX RECORDARME
    rememberRow: {
        flexDirection: 'row',
        alignItems: 'center',
        marginTop: 14,
        paddingVertical: 4,
        alignSelf: 'flex-start',
    },
    checkbox: {
        width: 19,
        height: 19,
        borderRadius: 4,
        borderWidth: 1.5,
        borderColor: '#1d68d8',
        backgroundColor: '#ffffff',
        justifyContent: 'center',
        alignItems: 'center',
        marginRight: 8,
    },
    checkboxChecked: {
        backgroundColor: '#1d68d8',
    },
    checkboxMark: {
        color: '#ffffff',
        fontSize: 12,
        fontWeight: 'bold',
        lineHeight: 14,
    },
    rememberText: {
        fontSize: 12.5,
        color: '#475569',
        fontWeight: '500',
    },

    // LOGO INFERIOR SigHov2 móvil
    footerBrandContainer: {
        alignItems: 'center',
        justifyContent: 'center',
        marginTop: 26,
        marginBottom: 16,
    },
    sighoLogo: {
        width: 185,
        height: 48,
    },
    movilSubtext: {
        fontSize: 14,
        color: '#1e4e8c',
        fontStyle: 'italic',
        fontWeight: '500',
        marginTop: -2,
        marginRight: 6,
    },

    // ONDAS INFERIORES CELESTES
    bottomWavesContainer: {
        height: 82,
        width: '100%',
        justifyContent: 'flex-end',
        overflow: 'hidden',
    },
    bottomWaveImage: {
        width: '100%',
        height: 82,
    },
});
