import React from 'react';
import {
    View,
    Text,
    StyleSheet,
    Modal,
    TouchableOpacity,
    Image,
    Platform,
    ScrollView,
} from 'react-native';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import { theme } from '../theme/theme';
import { AppIcon, NombreIcono } from './AppIcon';

interface MenuLateralModalProps {
    visible: boolean;
    onClose: () => void;
    rolesDisponibles: string[];
    rolActivo: string;
    onSelectRol: (nuevoRol: string) => void;
    onLogout?: () => void;
    nombreUsuario?: string;
}

const INFO_ROLES: Record<
    string,
    { titulo: string; subtitulo: string; icono: NombreIcono; color: string; bg: string }
> = {
    OPERADOR: {
        titulo: 'Bandeja de Operador',
        subtitulo: 'Recibir solicitudes, asignar prioridad y derivar a técnicos',
        icono: 'headset',
        color: '#2980b9',
        bg: '#ebf5fb',
    },
    ADMINISTRADOR: {
        titulo: 'Gestión de Roles (Admin)',
        subtitulo: 'Otorgar o quitar permisos al personal del sistema',
        icono: 'shield',
        color: '#d35400',
        bg: '#fef5e7',
    },
    TECNICO: {
        titulo: 'Bandeja de Técnico',
        subtitulo: 'Atender soportes asignados, cronometrar y subir actas',
        icono: 'wrench',
        color: '#8e44ad',
        bg: '#f5eef8',
    },
    USUARIO: {
        titulo: 'Mis Solicitudes (Usuario)',
        subtitulo: 'Solicitar soporte técnico y hacer seguimiento',
        icono: 'user',
        color: '#27ae60',
        bg: '#eafaf1',
    },
};

export const MenuLateralModal = ({
    visible,
    onClose,
    rolesDisponibles,
    rolActivo,
    onSelectRol,
    onLogout,
    nombreUsuario,
}: MenuLateralModalProps) => {
    const insets = useSafeAreaInsets();
    const rolesLimpios = Array.from(
        new Set((rolesDisponibles || []).map((r) => String(r).toUpperCase()).filter(Boolean))
    );
    const soloUnRol = rolesLimpios.length === 1;

    return (
        <Modal
            animationType="fade"
            transparent={true}
            visible={visible}
            onRequestClose={onClose}
            statusBarTranslucent={Platform.OS === 'android'}
            navigationBarTranslucent={Platform.OS === 'android'}
        >
            <View style={styles.overlay}>
                {/* Panel lateral izquierdo */}
                <View style={[styles.drawer, {
                    paddingTop: Math.max(insets.top, Platform.OS === 'web' ? 24 : 12),
                    paddingBottom: Math.max(insets.bottom, 16),
                }]}>
                    {/* Cabecera del menú lateral */}
                    <View style={styles.drawerHeader}>
                        <Image
                            source={require('../../../assets/ssu-logo-transparent.png')}
                            style={styles.logo}
                            resizeMode="contain"
                        />

                        <TouchableOpacity style={styles.closeBtn} onPress={onClose}>
                            <Text style={styles.closeBtnText}>✕</Text>
                        </TouchableOpacity>
                    </View>

                    <View style={styles.profileCard}>
                        <View style={styles.avatar}><AppIcon name="user" size={21} color="#ffffff" /></View>
                        <View style={styles.profileInfo}>
                            <Text style={styles.profileEyebrow}>CUENTA ACTIVA</Text>
                            <Text style={styles.profileName} numberOfLines={1}>{nombreUsuario?.trim() || 'Funcionario'}</Text>
                            <Text style={styles.profileRole} numberOfLines={1}>{String(rolActivo || 'SIN ROL').replace('_', ' ')}</Text>
                        </View>
                    </View>

                    <Text style={styles.drawerTitle}>{soloUnRol ? 'Mi cuenta' : 'Cambiar Modo / Rol'}</Text>
                    <Text style={styles.drawerSubtitle}>
                        {soloUnRol ? 'Tu perfil de acceso está activo.' : `Tienes ${rolesLimpios.length} roles asignados en tu cuenta:`}
                    </Text>

                    {/* Lista de roles del usuario */}
                    <ScrollView
                        style={styles.rolesList}
                        contentContainerStyle={styles.rolesContent}
                        showsVerticalScrollIndicator={false}
                        keyboardShouldPersistTaps="handled"
                    >
                        {!soloUnRol && rolesLimpios.map((rolKey) => {
                            const info = INFO_ROLES[rolKey] || {
                                titulo: `Modo ${rolKey}`,
                                subtitulo: 'Acceder a la vista de este rol',
                                icono: 'user' as NombreIcono,
                                color: theme.colors.primary,
                                bg: '#eef2f9',
                            };
                            const esActivo = String(rolActivo).toUpperCase() === rolKey;

                            return (
                                <TouchableOpacity
                                    key={rolKey}
                                    style={[
                                        styles.roleCard,
                                        {
                                            borderColor: esActivo ? info.color : '#e2e8f0',
                                            backgroundColor: esActivo ? info.bg : '#ffffff',
                                        },
                                    ]}
                                    onPress={() => {
                                        onSelectRol(rolKey);
                                        onClose();
                                    }}
                                    activeOpacity={0.75}
                                >
                                    <View style={styles.roleHeaderRow}>
                                        <View style={{ flexDirection: 'row', alignItems: 'center', flex: 1, marginRight: 6 }}>
                                            <AppIcon name={info.icono} size={17} color={esActivo ? info.color : '#2c3e50'} />
                                            <Text style={[styles.roleTitle, { color: esActivo ? info.color : '#2c3e50', marginLeft: 8, marginRight: 0 }]}>
                                                {info.titulo}
                                            </Text>
                                        </View>
                                        {esActivo && (
                                            <View style={[styles.activePill, { backgroundColor: info.color }]}>
                                                <Text style={styles.activePillText}>ACTIVO</Text>
                                            </View>
                                        )}
                                    </View>
                                    <Text style={styles.roleSubtitle}>{info.subtitulo}</Text>
                                </TouchableOpacity>
                            );
                        })}
                    </ScrollView>

                    {/* Pie del menú lateral */}
                    <View style={styles.drawerFooter}>
                        {onLogout && (
                            <TouchableOpacity
                                style={[styles.logoutDrawerBtn, { flexDirection: 'row', justifyContent: 'center', gap: 8 }]}
                                onPress={() => {
                                    onClose();
                                    onLogout();
                                }}
                            >
                                <AppIcon name="logout" size={16} color="#c0392b" />
                                <Text style={styles.logoutDrawerText}>Cerrar Sesión</Text>
                            </TouchableOpacity>
                        )}
                    </View>
                </View>

                {/* Área oscura a la derecha para cerrar al tocar fuera */}
                <TouchableOpacity
                    style={styles.backdropTouch}
                    activeOpacity={1}
                    onPress={onClose}
                />
            </View>
        </Modal>
    );
};

const styles = StyleSheet.create({
    overlay: {
        flex: 1,
        flexDirection: 'row',
        backgroundColor: 'rgba(0, 0, 0, 0.5)',
    },
    drawer: {
        width: '70%',
        maxWidth: 300,
        backgroundColor: '#ffffff',
        height: '100%',
        paddingHorizontal: 14,
        elevation: 10,
        shadowColor: '#000',
        shadowOffset: { width: 3, height: 0 },
        shadowOpacity: 0.25,
        shadowRadius: 8,
    },
    backdropTouch: {
        flex: 1,
        height: '100%',
    },
    drawerHeader: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
        paddingBottom: 16,
        borderBottomWidth: 1,
        borderBottomColor: '#edf2f7',
    },
    logo: { width: 140, height: 52 },
    profileCard: {
        flexDirection: 'row', alignItems: 'center', gap: 10,
        marginTop: 14, padding: 11, borderRadius: 14,
        backgroundColor: '#f1f6fc', borderWidth: 1, borderColor: '#dce7f5',
    },
    avatar: {
        width: 44, height: 44, borderRadius: 22, backgroundColor: theme.colors.primary,
        alignItems: 'center', justifyContent: 'center',
    },
    profileInfo: { flex: 1 },
    profileEyebrow: { fontSize: 10, fontWeight: '700', letterSpacing: 0.8, color: '#64748b' },
    profileName: { marginTop: 2, fontSize: 15, fontWeight: '700', color: '#1e293b' },
    profileRole: { marginTop: 2, fontSize: 12, fontWeight: '600', color: theme.colors.primary },
    closeBtn: {
        width: 34,
        height: 34,
        borderRadius: 17,
        backgroundColor: '#f1f5f9',
        justifyContent: 'center',
        alignItems: 'center',
    },
    closeBtnText: {
        fontSize: 16,
        fontWeight: 'bold',
        color: '#475569',
    },
    drawerTitle: {
        fontSize: 18,
        fontWeight: 'bold',
        color: theme.colors.primary,
        marginTop: 16,
    },
    drawerSubtitle: {
        fontSize: 13,
        color: '#64748b',
        marginTop: 4,
        marginBottom: 14,
    },
    rolesList: { flex: 1, minHeight: 0, marginHorizontal: -2 },
    rolesContent: { paddingHorizontal: 2, paddingBottom: 12 },
    roleCard: {
        borderWidth: 1.5,
        borderRadius: 10,
        padding: 11,
        marginBottom: 10,
    },
    roleHeaderRow: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
    },
    roleTitle: {
        fontSize: 13,
        fontWeight: 'bold',
        flex: 1,
        marginRight: 6,
    },
    roleSubtitle: {
        fontSize: 11,
        color: '#64748b',
        marginTop: 4,
        lineHeight: 15,
    },
    activePill: {
        paddingHorizontal: 7,
        paddingVertical: 2,
        borderRadius: 4,
    },
    activePillText: {
        color: '#ffffff',
        fontSize: 10,
        fontWeight: 'bold',
    },
    drawerFooter: {
        borderTopWidth: 1,
        borderTopColor: '#edf2f7',
        paddingTop: 14,
        marginTop: 8,
    },
    logoutDrawerBtn: {
        backgroundColor: '#fdecea',
        borderWidth: 1,
        borderColor: '#f5b7b1',
        paddingVertical: 11,
        borderRadius: 8,
        alignItems: 'center',
    },
    logoutDrawerText: {
        color: '#c0392b',
        fontWeight: 'bold',
        fontSize: 14,
    },
});
