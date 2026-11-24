import React, { useState, useEffect } from 'react';
import {
    View,
    Text,
    StyleSheet,
    TouchableOpacity,
    Image,
    ActivityIndicator,
    Platform,
    useWindowDimensions,
} from 'react-native';
import { theme } from '../../../core/theme/theme';
import { AppIcon } from '../../../core/components/AppIcon';
import { obtenerItem, eliminarItem } from '../../../core/utils/storage';

interface SinRolScreenProps {
    onLogout: () => void;
    onVerificar: () => Promise<void> | void;
}

export const SinRolScreen = ({ onLogout, onVerificar }: SinRolScreenProps) => {
    const { width } = useWindowDimensions();
    const esEscritorio = Platform.OS === 'web' && width > 768;

    const [nombreUsuario, setNombreUsuario] = useState<string>('Funcionario');
    const [username, setUsername] = useState<string>('');
    const [verificando, setVerificando] = useState<boolean>(false);

    useEffect(() => {
        const cargarDatos = async () => {
            try {
                const userDataStr = await obtenerItem('userData');
                if (userDataStr) {
                    const u = JSON.parse(userDataStr);
                    if (u.nombreCompleto) setNombreUsuario(u.nombreCompleto);
                    if (u.username) setUsername(u.username);
                }
            } catch (e) {
                console.log('Error leyendo userData en SinRolScreen:', e);
            }
        };
        cargarDatos();
    }, []);

    const handleVerificar = async () => {
        setVerificando(true);
        try {
            await onVerificar();
        } finally {
            setVerificando(false);
        }
    };

    const manejarCerrarSesion = async () => {
        await eliminarItem('jwtToken');
        await eliminarItem('userData');
        if (onLogout) onLogout();
    };

    return (
        <View style={styles.container}>
            {/* CABECERA AZUL CON LOGO SSU Y ONDAS */}
            <View style={styles.topHeader}>
                <View style={[styles.topBarRow, esEscritorio && { maxWidth: 880, alignSelf: 'center' }]}>
                    <Image
                        source={require('../../../../assets/7.png')}
                        style={{
                            width: esEscritorio ? 90 : 72,
                            height: esEscritorio ? 36 : 28,
                        }}
                        resizeMode="contain"
                    />

                    <Text style={styles.navBarTitle} numberOfLines={1}>
                        Sistema de Soporte
                    </Text>

                    <TouchableOpacity
                        style={styles.logoutBtn}
                        onPress={manejarCerrarSesion}
                        activeOpacity={0.75}
                    >
                        <AppIcon name="logout" size={18} color="#ffffff" />
                    </TouchableOpacity>
                </View>

                <Image
                    source={require('../../../../assets/ondas-superiores.png')}
                    style={styles.headerBottomWave}
                    resizeMode="stretch"
                />
            </View>

            {/* CONTENIDO CENTRAL */}
            <View style={[styles.contentWrapper, esEscritorio && { maxWidth: 540, alignSelf: 'center', width: '100%' }]}>
                <View style={styles.card}>
                    {/* Icono de candado con badge circular */}
                    <View style={styles.lockBadgeCircle}>
                        <AppIcon name="lock" size={36} color="#d97706" />
                    </View>

                    <Text style={styles.title}>Acceso Pendiente de Autorización</Text>

                    <Text style={styles.greeting}>
                        Hola, <Text style={{ fontWeight: 'bold', color: theme.colors.primary }}>{nombreUsuario}</Text>
                        {/* {username ? ` (@${username})` : ''} */}
                    </Text>

                    <View style={styles.messageBox}>
                        <Text style={styles.messageText}>
                            Has ingresado correctamente a tu cuenta, pero{' '}
                            <Text style={{ fontWeight: 'bold', color: '#b45309' }}>
                                actualmente no tienes acceso
                            </Text>{' '}
                            {/* (Usuario, Operador, Técnico o Administrador). */}
                        </Text>

                        <Text style={[styles.messageText, { marginTop: 10 }]}>
                            Comunícate con el Administrador del sistema para que active los permisos correspondientes.
                        </Text>
                    </View>

                    {/* Botón para comprobar si el admin ya le asignó rol */}
                    <TouchableOpacity
                        style={styles.checkButton}
                        onPress={handleVerificar}
                        disabled={verificando}
                        activeOpacity={0.8}
                    >
                        {verificando ? (
                            <ActivityIndicator size="small" color="#ffffff" />
                        ) : (
                            <View style={{ flexDirection: 'row', alignItems: 'center', gap: 8 }}>
                                <AppIcon name="refresh" size={17} color="#ffffff" />
                                <Text style={styles.checkButtonText}>
                                    Comprobar si ya me dieron acceso
                                </Text>
                            </View>
                        )}
                    </TouchableOpacity>

                    {/* Botón para salir */}
                    <TouchableOpacity
                        style={styles.exitButton}
                        onPress={manejarCerrarSesion}
                        activeOpacity={0.8}
                    >
                        <AppIcon name="logout" size={16} color="#dc2626" />
                        <Text style={styles.exitButtonText}>Cerrar Sesión</Text>
                    </TouchableOpacity>
                </View>
            </View>

            {/* Ondas celestes decorativas en la base */}
            <View style={styles.bottomWavesWrapper} pointerEvents="none">
                <Image
                    source={require('../../../../assets/ondas-inferiores.png')}
                    style={styles.bottomWavesImg}
                    resizeMode="stretch"
                />
            </View>
        </View>
    );
};

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#f8fafc',
    },
    topHeader: {
        backgroundColor: '#164a96',
        paddingTop: Platform.OS === 'web' ? 14 : 36,
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
    navBarTitle: {
        color: '#ffffff',
        fontSize: 17,
        fontWeight: 'bold',
        textAlign: 'center',
        flex: 1,
        marginHorizontal: 10,
    },
    logoutBtn: {
        padding: 8,
        backgroundColor: 'rgba(255,255,255,0.15)',
        borderRadius: 8,
    },
    headerBottomWave: {
        width: '100%',
        height: 24,
        marginTop: 4,
    },
    contentWrapper: {
        flex: 1,
        justifyContent: 'center',
        paddingHorizontal: 20,
        zIndex: 10,
    },
    card: {
        backgroundColor: '#ffffff',
        borderRadius: 16,
        padding: 24,
        alignItems: 'center',
        elevation: 3,
        shadowColor: '#000',
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.1,
        shadowRadius: 8,
        borderWidth: 1,
        borderColor: '#e2e8f0',
    },
    lockBadgeCircle: {
        width: 72,
        height: 72,
        borderRadius: 36,
        backgroundColor: '#fef3c7',
        borderWidth: 2,
        borderColor: '#fde68a',
        justifyContent: 'center',
        alignItems: 'center',
        marginBottom: 16,
    },
    title: {
        fontSize: 18,
        fontWeight: 'bold',
        color: '#1e293b',
        textAlign: 'center',
        marginBottom: 8,
    },
    greeting: {
        fontSize: 14,
        color: '#64748b',
        textAlign: 'center',
        marginBottom: 16,
    },
    messageBox: {
        backgroundColor: '#fffbeb',
        borderLeftWidth: 4,
        borderLeftColor: '#d97706',
        padding: 14,
        borderRadius: 8,
        width: '100%',
        marginBottom: 20,
    },
    messageText: {
        fontSize: 13,
        color: '#78350f',
        lineHeight: 19,
    },
    checkButton: {
        backgroundColor: '#164a96',
        paddingVertical: 13,
        paddingHorizontal: 20,
        borderRadius: 10,
        width: '100%',
        alignItems: 'center',
        justifyContent: 'center',
        marginBottom: 10,
    },
    checkButtonText: {
        color: '#ffffff',
        fontWeight: 'bold',
        fontSize: 14,
    },
    exitButton: {
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'center',
        gap: 6,
        paddingVertical: 10,
        width: '100%',
    },
    exitButtonText: {
        color: '#dc2626',
        fontWeight: '600',
        fontSize: 14,
    },
    bottomWavesWrapper: {
        position: 'absolute',
        bottom: 0,
        left: 0,
        right: 0,
        height: 60,
        overflow: 'hidden',
    },
    bottomWavesImg: {
        width: '100%',
        height: 60,
    },
});
