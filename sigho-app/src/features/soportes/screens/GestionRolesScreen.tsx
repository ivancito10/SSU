import React, { useState, useEffect, useCallback } from 'react';
import {
    View,
    Text,
    StyleSheet,
    TouchableOpacity,
    TextInput,
    FlatList,
    ScrollView,
    ActivityIndicator,
    Modal,
    Alert,
    Platform,
    RefreshControl,
    Image,
    useWindowDimensions,
} from 'react-native';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import { theme } from '../../../core/theme/theme';
import { obtenerItem, guardarItem, eliminarItem } from '../../../core/utils/storage';
import { MenuLateralModal } from '../../../core/components/MenuLateralModal';
import { AppIcon, NombreIcono } from '../../../core/components/AppIcon';
import { AppAlert } from '../../../core/components/AlertaGlobalModal';
import { NotificacionesCampana } from '../../../core/components/NotificacionesCampana';
import {
    obtenerUsuariosConRoles,
    asignarRolUsuario,
    quitarRolUsuario,
    obtenerTiposSoporteConOpciones,
    crearOpcionTipoSoporte,
    cambiarEstadoOpcionTipoSoporte,
} from '../api/soporte.service';

export interface UsuarioRolItem {
    id_usuario: number;
    username: string;
    email?: string;
    nombre_completo: string;
    ci?: string;
    rol_soporte: 'USUARIO' | 'OPERADOR' | 'TECNICO' | 'ADMINISTRADOR' | string;
    roles_activos?: string[];
    tiene_rol_especial?: boolean;
}

const ROLES_DISPONIBLES: Array<{
    rol: 'USUARIO' | 'OPERADOR' | 'TECNICO' | 'ADMINISTRADOR';
    titulo: string;
    descripcion: string;
    icono: NombreIcono;
    color: string;
    bg: string;
}> = [
    {
        rol: 'USUARIO',
        titulo: 'USUARIO',
        descripcion: 'Crea solicitudes de soporte técnico y hace seguimiento a sus tickets.',
        icono: 'user',
        color: '#16a34a',
        bg: '#dcfce7',
    },
    {
        rol: 'OPERADOR',
        titulo: 'OPERADOR',
        descripcion: 'Recibe solicitudes, asigna prioridad y deriva a técnicos.',
        icono: 'headset',
        color: '#1d4ed8',
        bg: '#dbeafe',
    },
    {
        rol: 'TECNICO',
        titulo: 'TÉCNICO',
        descripcion: 'Atiende los soportes asignados, cronometra y sube el acta.',
        icono: 'wrench',
        color: '#6d28d9',
        bg: '#f3e8ff',
    },
    {
        rol: 'ADMINISTRADOR',
        titulo: 'ADMINISTRADOR',
        descripcion: 'Gestiona los permisos y roles del personal en el sistema.',
        icono: 'shield',
        color: '#b45309',
        bg: '#fef3c7',
    },
];

const obtenerBadgeRol = (rol?: string): { texto: string; icono: NombreIcono; color: string; bg: string; border: string } => {
    const r = (rol || '').toUpperCase();
    if (r === 'OPERADOR') {
        return { texto: 'OPERADOR', icono: 'headset', color: '#1d4ed8', bg: '#dbeafe', border: '#bfdbfe' };
    }
    if (r === 'TECNICO') {
        return { texto: 'TÉCNICO', icono: 'wrench', color: '#6d28d9', bg: '#f3e8ff', border: '#e9d5ff' };
    }
    if (r === 'ADMINISTRADOR') {
        return { texto: 'ADMINISTRADOR', icono: 'shield', color: '#b45309', bg: '#fef3c7', border: '#fde68a' };
    }
    if (r === 'USUARIO') {
        return { texto: 'USUARIO', icono: 'user', color: '#16a34a', bg: '#dcfce7', border: '#bbf7d0' };
    }
    return { texto: 'USUARIO NO REGISTRADO', icono: 'lock', color: '#64748b', bg: '#f1f5f9', border: '#e2e8f0' };
};

const obtenerRolesDeUsuario = (item?: UsuarioRolItem | null): string[] => {
    if (!item) return [];
    if (Array.isArray(item.roles_activos) && item.roles_activos.length > 0) {
        return item.roles_activos
            .map((r) => String(r).toUpperCase())
            .filter((r) => r === 'USUARIO' || r === 'OPERADOR' || r === 'TECNICO' || r === 'ADMINISTRADOR');
    }
    const r = (item.rol_soporte || '').toUpperCase();
    if (r === 'USUARIO' || r === 'OPERADOR' || r === 'TECNICO' || r === 'ADMINISTRADOR') {
        return [r];
    }
    return [];
};

const IconoSalirHeader = () => {
    if (Platform.OS === 'web') {
        return (
            <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="#ffffff" strokeWidth="2.1" strokeLinecap="round" strokeLinejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                <polyline points="16 17 21 12 16 7" />
                <line x1="21" y1="12" x2="9" y2="12" />
            </svg>
        );
    }
    return (
        <View style={{ flexDirection: 'row', alignItems: 'center' }}>
            <View
                style={{
                    width: 11,
                    height: 15,
                    borderTopWidth: 2,
                    borderLeftWidth: 2,
                    borderBottomWidth: 2,
                    borderColor: '#ffffff',
                    borderTopLeftRadius: 3,
                    borderBottomLeftRadius: 3,
                }}
            />
            <Text style={{ color: '#fff', fontSize: 15, fontWeight: 'bold', marginLeft: -4 }}>→</Text>
        </View>
    );
};

export const GestionRolesScreen = ({
    onLogout,
    rolActivo = 'ADMINISTRADOR',
    rolesDisponibles = ['ADMINISTRADOR'],
    onCambiarRol,
    onRolesActualizados,
    onNotificacionSeleccionada,
}: any) => {
    const { width } = useWindowDimensions();
    const insets = useSafeAreaInsets();
    const esEscritorio = Platform.OS === 'web' && width > 768;
    const paddingBottomNav = Platform.OS === 'web' ? 10 : Math.max(insets.bottom, 12);

    const [usuarios, setUsuarios] = useState<UsuarioRolItem[]>([]);
    const [cargando, setCargando] = useState<boolean>(true);
    const [refrescando, setRefrescando] = useState<boolean>(false);
    const [guardandoAccion, setGuardandoAccion] = useState<boolean>(false);
    const [menuLateralVisible, setMenuLateralVisible] = useState<boolean>(false);
    const [nombreUsuario, setNombreUsuario] = useState('');
    const [modalOpcionesVisible, setModalOpcionesVisible] = useState(false);
    const [tiposConOpciones, setTiposConOpciones] = useState<Array<{ id: number; nombre: string; opciones: Array<{ id: number; nombre: string; estado?: boolean }> }>>([]);
    const [nuevaOpcion, setNuevaOpcion] = useState('');
    const [tipoOpcionSeleccionado, setTipoOpcionSeleccionado] = useState<number | null>(null);
    const mostrarMenuCuentaSesion = Array.isArray(rolesDisponibles) && rolesDisponibles.length > 0;

    const cargarTiposConOpciones = async () => {
        try {
            const datos = await obtenerTiposSoporteConOpciones();
            setTiposConOpciones(datos);
            setTipoOpcionSeleccionado((actual) => actual ?? datos[0]?.id ?? null);
        } catch (error) {
            AppAlert.alert('Error', 'No se pudieron cargar las opciones de soporte.');
        }
    };

    const guardarNuevaOpcion = async () => {
        if (!tipoOpcionSeleccionado || !nuevaOpcion.trim()) return;
        try {
            await crearOpcionTipoSoporte(tipoOpcionSeleccionado, nuevaOpcion.trim());
            setNuevaOpcion('');
            await cargarTiposConOpciones();
        } catch (error: any) {
            AppAlert.alert('No se pudo guardar', error?.response?.data?.message || 'Verifica que la opción no exista ya en esta categoría.');
        }
    };

    const alternarOpcion = async (id: number, estado: boolean) => {
        try {
            await cambiarEstadoOpcionTipoSoporte(id, estado);
            await cargarTiposConOpciones();
        } catch {
            AppAlert.alert('Error', 'No se pudo actualizar el estado de la opción.');
        }
    };

    // Buscador y pestañas
    const [busqueda, setBusqueda] = useState<string>('');
    const [tabActivo, setTabActivo] = useState<'ASIGNADOS' | 'TODOS'>('ASIGNADOS');
    const [filtroRol, setFiltroRol] = useState<'USUARIO' | 'OPERADOR' | 'TECNICO' | 'ADMINISTRADOR' | null>(null);

    // ID del administrador logueado
    const [idAdminActual, setIdAdminActual] = useState<number | null>(null);

    // Modal de gestión del usuario seleccionado
    const [usuarioSeleccionado, setUsuarioSeleccionado] = useState<UsuarioRolItem | null>(null);
    const [modalVisible, setModalVisible] = useState<boolean>(false);
    const [confirmandoQuitar, setConfirmandoQuitar] = useState<boolean>(false);

    const mostrarMensaje = (titulo: string, mensaje: string) => {
        AppAlert.alert(titulo, mensaje);
    };

    const manejarCerrarSesion = async () => {
        await eliminarItem('jwtToken');
        await eliminarItem('userData');
        if (onLogout) onLogout();
    };

    const cargarUsuarios = useCallback(async (silencioso = false) => {
        if (!silencioso) setCargando(true);
        try {
            const userDataStr = await obtenerItem('userData');
            if (userDataStr) {
                const userData = JSON.parse(userDataStr);
                setNombreUsuario(userData.nombreCompleto || userData.nombre_completo || userData.nombre || userData.username || '');
                if (userData?.id) {
                    setIdAdminActual(Number(userData.id));
                }
            }

            const lista = await obtenerUsuariosConRoles();
            setUsuarios(Array.isArray(lista) ? lista : []);
        } catch (error: any) {
            console.error('Error al cargar usuarios con roles:', error);
            if (!silencioso) {
                const msg = error?.response?.data?.message || 'No se pudo cargar la lista de usuarios.';
                mostrarMensaje('Error', msg);
            }
        } finally {
            setCargando(false);
            setRefrescando(false);
        }
    }, []);

    useEffect(() => {
        cargarUsuarios(false);
    }, [cargarUsuarios]);

    const onRefresh = () => {
        setRefrescando(true);
        cargarUsuarios(true);
    };

    // Contadores KPI de personal por rol
    const totalUsuarios = usuarios.filter((u) => obtenerRolesDeUsuario(u).includes('USUARIO')).length;
    const totalOperadores = usuarios.filter((u) => obtenerRolesDeUsuario(u).includes('OPERADOR')).length;
    const totalTecnicos = usuarios.filter((u) => obtenerRolesDeUsuario(u).includes('TECNICO')).length;
    const totalAdmins = usuarios.filter((u) => obtenerRolesDeUsuario(u).includes('ADMINISTRADOR')).length;

    const termino = busqueda.trim().toLowerCase();
    const personalAsignado = usuarios.filter((u) => obtenerRolesDeUsuario(u).length > 0);
    const baseLista = filtroRol ? usuarios : tabActivo === 'ASIGNADOS' ? personalAsignado : usuarios;

    const usuariosFiltrados = baseLista.filter((u) => {
        if (filtroRol && !obtenerRolesDeUsuario(u).includes(filtroRol)) return false;
        if (!termino) return true;
        const nombre = (u.nombre_completo || '').toLowerCase();
        const user = (u.username || '').toLowerCase();
        const ci = (u.ci || '').toLowerCase();
        const email = (u.email || '').toLowerCase();
        const rolesArr = obtenerRolesDeUsuario(u);
        const rolesTexto = rolesArr.length > 0 ? rolesArr.join(' ').toLowerCase() : 'sin rol no registrado';
        return (
            nombre.includes(termino) ||
            user.includes(termino) ||
            ci.includes(termino) ||
            email.includes(termino) ||
            rolesTexto.includes(termino)
        );
    });

    const abrirModalUsuario = (item: UsuarioRolItem) => {
        setUsuarioSeleccionado(item);
        setConfirmandoQuitar(false);
        setModalVisible(true);
    };

    const sincronizarRolesPropiosEnSesion = async (nuevosRoles: string[]) => {
        try {
            const listaFinal = nuevosRoles.length > 0 ? nuevosRoles : ['ADMINISTRADOR'];
            if (onRolesActualizados) {
                onRolesActualizados(listaFinal);
            }
            const userDataStr = await obtenerItem('userData');
            if (userDataStr) {
                const userData = JSON.parse(userDataStr);
                userData.roles = listaFinal;
                await guardarItem('userData', JSON.stringify(userData));
            }
        } catch (e) {
            console.log('No se pudo actualizar roles de sesión:', e);
        }
    };

    const handleAlternarRol = async (rolObjetivo: 'USUARIO' | 'OPERADOR' | 'TECNICO' | 'ADMINISTRADOR') => {
        if (!usuarioSeleccionado) return;

        const rolesActuales = obtenerRolesDeUsuario(usuarioSeleccionado);
        const yaTieneEsteRol = rolesActuales.includes(rolObjetivo);
        const esMismoAdmin =
            idAdminActual !== null &&
            Number(usuarioSeleccionado.id_usuario) === Number(idAdminActual);

        if (yaTieneEsteRol) {
            if (esMismoAdmin && rolObjetivo === 'ADMINISTRADOR') {
                mostrarMensaje(
                    'Acción bloqueada',
                    'Por seguridad, no puedes quitarte tu propio rol de ADMINISTRADOR.'
                );
                return;
            }

            setGuardandoAccion(true);
            try {
                const resp = await quitarRolUsuario(Number(usuarioSeleccionado.id_usuario), rolObjetivo);
                const rolesNuevos: string[] = resp?.datos?.roles_activos || rolesActuales.filter((r) => r !== rolObjetivo);
                if (esMismoAdmin) {
                    await sincronizarRolesPropiosEnSesion(rolesNuevos);
                }
                setModalVisible(false);
                setUsuarioSeleccionado(null);
                await cargarUsuarios(true);
            } catch (error: any) {
                console.error('Error al quitar rol específico:', error);
                const msg = error?.response?.data?.message || 'No se pudo quitar el rol.';
                mostrarMensaje('Error', msg);
            } finally {
                setGuardandoAccion(false);
            }
            return;
        }

        setGuardandoAccion(true);
        try {
            const resp = await asignarRolUsuario(Number(usuarioSeleccionado.id_usuario), rolObjetivo);
            const rolesNuevos: string[] = resp?.datos?.roles_activos || Array.from(new Set([...rolesActuales, rolObjetivo]));
            if (esMismoAdmin) {
                await sincronizarRolesPropiosEnSesion(rolesNuevos);
            }
            setModalVisible(false);
            setUsuarioSeleccionado(null);
            await cargarUsuarios(true);
        } catch (error: any) {
            console.error('Error al asignar rol:', error);
            const msg = error?.response?.data?.message || 'No se pudo asignar el rol.';
            mostrarMensaje('Error', msg);
        } finally {
            setGuardandoAccion(false);
        }
    };

    const ejecutarQuitarRol = async () => {
        if (!usuarioSeleccionado) return;

        setGuardandoAccion(true);
        try {
            await quitarRolUsuario(Number(usuarioSeleccionado.id_usuario));
            setModalVisible(false);
            setConfirmandoQuitar(false);
            setUsuarioSeleccionado(null);
            await cargarUsuarios(true);
        } catch (error: any) {
            console.error('Error al quitar rol:', error);
            const msg = error?.response?.data?.message || 'No se pudo quitar el rol.';
            mostrarMensaje('Error', msg);
        } finally {
            setGuardandoAccion(false);
        }
    };

    const renderUsuarioCard = ({ item }: { item: UsuarioRolItem }) => {
        const rolesUsuario = obtenerRolesDeUsuario(item);
        const badges =
            rolesUsuario.length > 0
                ? rolesUsuario.map((r) => obtenerBadgeRol(r))
                : [obtenerBadgeRol('SIN_ROL')];
        const esUsuarioActual =
            idAdminActual !== null && Number(item.id_usuario) === Number(idAdminActual);

        return (
            <TouchableOpacity
                style={styles.card}
                onPress={() => abrirModalUsuario(item)}
                activeOpacity={0.85}
            >
                {/* Fila superior con insignias de rol */}
                <View style={styles.cardTopRow}>
                    <View style={{ flexDirection: 'row', flexWrap: 'wrap', gap: 6, flex: 1 }}>
                        {badges.map((b, i) => (
                            <View
                                key={i}
                                style={[
                                    styles.roleBadge,
                                    { backgroundColor: b.bg, borderColor: b.border, flexDirection: 'row', alignItems: 'center', gap: 5 },
                                ]}
                            >
                                <AppIcon name={b.icono} size={12} color={b.color} />
                                <Text style={[styles.roleBadgeText, { color: b.color }]}>
                                    {b.texto}
                                </Text>
                            </View>
                        ))}
                    </View>
                    <Text style={styles.userIdRight}>ID #{item.id_usuario}</Text>
                </View>

                {/* Fila principal con avatar circular + Nombre del funcionario */}
                <View style={styles.cardMainRow}>
                    <View style={styles.userAvatarCircle}>
                        <AppIcon name="user" size={19} color="#164a96" />
                    </View>
                    <View style={{ flex: 1 }}>
                        <Text style={styles.userName}>
                            {item.nombre_completo}
                            {esUsuarioActual ? ' (Tú)' : ''}
                        </Text>
                        <Text style={styles.userSub}>
                            USUARIO: {item.username}
                        </Text>
                    </View>
                </View>

                <View style={styles.divider} />

                <View style={styles.cardFooterRow}>
                    <View style={{ flexDirection: 'row', alignItems: 'center' }}>
                        <AppIcon name="id-card" size={15} color="#475569" />
                        <Text style={[styles.userMetaText, { marginLeft: 6 }]}>
                            CI: {item.ci || 'No registrado'}
                        </Text>
                    </View>

                    <TouchableOpacity
                        style={styles.detailsButton}
                        onPress={() => abrirModalUsuario(item)}
                        activeOpacity={0.8}
                    >
                        <Text style={styles.detailsButtonText}>Gestionar rol</Text>
                    </TouchableOpacity>
                </View>
            </TouchableOpacity>
        );
    };

    const rolesSeleccionado = obtenerRolesDeUsuario(usuarioSeleccionado);
    const rolActualSeleccionado = rolesSeleccionado.length > 0 ? rolesSeleccionado.join(' + ') : 'USUARIO NO REGISTRADO';
    const tieneRolEspecial = rolesSeleccionado.length > 0;
    const esAutoEdicion =
        usuarioSeleccionado !== null &&
        idAdminActual !== null &&
        Number(usuarioSeleccionado.id_usuario) === Number(idAdminActual);

    return (
        <View style={styles.container}>
            {/* CABECERA AZUL CON ONDA CURVA */}
            <View style={styles.topHeader}>
                <View
                    style={[
                        styles.topBarRow,
                        esEscritorio && { maxWidth: 880, alignSelf: 'center' },
                    ]}
                >
                    <View style={styles.leftBarGroup}>
                        {mostrarMenuCuentaSesion && (
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

                    <Text style={styles.navBarTitle} numberOfLines={1}>
                        Gestión de Roles
                    </Text>

                    <View style={{ flexDirection: 'row', alignItems: 'center', gap: 8 }}>
                        <NotificacionesCampana
                            buttonStyle={styles.iconActionBtn}
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

                <Image
                    source={require('../../../../assets/ondas-superiores.png')}
                    style={styles.headerBottomWave}
                    resizeMode="stretch"
                />
            </View>

                {/* ENCABEZADO DE SECCIÓN + FILTROS POR ROL + BUSCADOR */}
            <View style={[styles.topSummarySection, esEscritorio && { width: '100%', maxWidth: 912, alignSelf: 'center' }]}>
                <View style={styles.sectionBannerRow}>
                    <View style={styles.blueCircleIcon}>
                        <AppIcon name="shield" size={20} color="#ffffff" />
                    </View>
                    <View style={{ flex: 1 }}>
                        <Text style={styles.sectionBannerTitle}>
                            Personal y Permisos del Sistema
                        </Text>
                        <Text style={styles.sectionBannerSub}>
                            Modo activo: ADMINISTRADOR
                        </Text>
                    </View>
                </View>

                {/* Filtros de personal por rol */}
                <View style={[styles.kpiRow, width < 520 && styles.kpiRowMobile]}>
                    {([
                        { rol: 'USUARIO', etiqueta: 'Usuarios', valor: totalUsuarios, color: '#16a34a' },
                        { rol: 'OPERADOR', etiqueta: 'Operadores', valor: totalOperadores, color: '#1d4ed8' },
                        { rol: 'TECNICO', etiqueta: 'Técnicos', valor: totalTecnicos, color: '#6d28d9' },
                        { rol: 'ADMINISTRADOR', etiqueta: 'Admins', valor: totalAdmins, color: '#b45309' },
                    ] as const).map((item) => (
                        <TouchableOpacity
                            key={item.rol}
                            style={[styles.kpiCard, width < 520 && styles.kpiCardMobile, filtroRol === item.rol && styles.kpiCardActive]}
                            onPress={() => {
                                setTabActivo('TODOS');
                                setFiltroRol((actual) => actual === item.rol ? null : item.rol);
                            }}
                            accessibilityRole="button"
                            accessibilityState={{ selected: filtroRol === item.rol }}
                            activeOpacity={0.78}
                        >
                            <Text style={[styles.kpiNumber, width < 390 && styles.kpiNumberCompact, { color: item.color }]}>{item.valor}</Text>
                            <Text style={[styles.kpiLabel, width < 390 && styles.kpiLabelCompact]}>{item.etiqueta}</Text>
                        </TouchableOpacity>
                    ))}
                </View>

                {/* Buscador en forma de píldora */}
                <View style={styles.searchPillWrapper}>
                    <View style={{ marginRight: 8 }}>
                        <AppIcon name="search" size={16} color="#64748b" />
                    </View>
                    <TextInput
                        style={styles.searchInput}
                        placeholder="Buscar por nombre, usuario, email, CI o rol..."
                        placeholderTextColor="#94a3b8"
                        value={busqueda}
                        onChangeText={setBusqueda}
                    />
                    {busqueda.length > 0 && (
                        <TouchableOpacity onPress={() => setBusqueda('')} style={{ padding: 6 }}>
                            <AppIcon name="close" size={14} color="#64748b" />
                        </TouchableOpacity>
                    )}
                </View>
                <TouchableOpacity
                    style={styles.manageOptionsButton}
                    onPress={() => { setModalOpcionesVisible(true); cargarTiposConOpciones(); }}
                    activeOpacity={0.8}
                >
                    <AppIcon name="gear" size={16} color="#1d5bbf" />
                    <Text style={styles.manageOptionsButtonText}>Configurar opciones de soporte</Text>
                </TouchableOpacity>
            </View>

            {/* LISTADO */}
            <View style={{ flex: 1 }}>
                {cargando ? (
                    <View style={styles.centerContainer}>
                        <ActivityIndicator size="large" color={theme.colors.primary} />
                    </View>
                ) : (
                    <FlatList
                        data={usuariosFiltrados}
                        keyExtractor={(item, index) => `${item.id_usuario}-${index}`}
                        contentContainerStyle={[
                            styles.listContainer,
                            esEscritorio && { width: '100%', maxWidth: 912, alignSelf: 'center' },
                        ]}
                        renderItem={renderUsuarioCard}
                        refreshControl={
                            <RefreshControl
                                refreshing={refrescando}
                                onRefresh={onRefresh}
                                colors={[theme.colors.primary]}
                            />
                        }
                        ListEmptyComponent={
                            <View style={styles.emptyCardBox}>
                                <Text style={styles.emptyText}>
                                    {termino
                                        ? `No se encontraron funcionarios que coincidan con "${busqueda}".`
                                        : tabActivo === 'ASIGNADOS'
                                            ? 'Aún no hay usuarios con rol de Operador, Técnico o Administrador.'
                                            : 'No se encontraron usuarios registrados.'}
                                </Text>
                                {tabActivo === 'ASIGNADOS' && (
                                    <TouchableOpacity
                                        style={styles.goToAllButton}
                                        onPress={() => setTabActivo('TODOS')}
                                    >
                                        <Text style={styles.goToAllButtonText}>
                                            Ir a "Todos los Usuarios" para otorgar un rol
                                        </Text>
                                    </TouchableOpacity>
                                )}
                            </View>
                        }
                    />
                )}

                {/* Ondas celestes decorativas en la base */}
                <View style={styles.bottomWavesWrapper} pointerEvents="none">
                    <Image
                        source={require('../../../../assets/ondas-inferiores.png')}
                        style={styles.bottomWavesImg}
                        resizeMode="stretch"
                    />
                </View>
            </View>

            {/* BARRA DE NAVEGACIÓN INFERIOR */}
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
                            tabActivo === 'ASIGNADOS' && styles.bottomNavTabActive,
                        ]}
                        onPress={() => { setTabActivo('ASIGNADOS'); setFiltroRol(null); }}
                        activeOpacity={0.8}
                    >
                        <AppIcon
                            name="shield"
                            size={17}
                            color={tabActivo === 'ASIGNADOS' ? '#ffffff' : '#475569'}
                        />
                        <Text
                            style={[
                                styles.bottomNavLabel,
                                { marginTop: 3 },
                                tabActivo === 'ASIGNADOS' && styles.bottomNavTextActive,
                            ]}
                        >
                            Personal Asignado ({personalAsignado.length})
                        </Text>
                    </TouchableOpacity>

                    <TouchableOpacity
                        style={[
                            styles.bottomNavTab,
                            tabActivo === 'TODOS' && styles.bottomNavTabActive,
                        ]}
                        onPress={() => { setTabActivo('TODOS'); setFiltroRol(null); }}
                        activeOpacity={0.8}
                    >
                        <AppIcon
                            name="users"
                            size={17}
                            color={tabActivo === 'TODOS' ? '#ffffff' : '#475569'}
                        />
                        <Text
                            style={[
                                styles.bottomNavLabel,
                                { marginTop: 3 },
                                tabActivo === 'TODOS' && styles.bottomNavTextActive,
                            ]}
                        >
                            Todos los Usuarios ({usuarios.length})
                        </Text>
                    </TouchableOpacity>
                </View>
            </View>

            <Modal transparent animationType="fade" visible={modalOpcionesVisible} onRequestClose={() => setModalOpcionesVisible(false)}>
                <View style={styles.modalOverlay}>
                    <View style={styles.optionsModalContent}>
                        <View style={styles.modalHeaderRow}>
                            <Text style={styles.modalTitle}>Opciones por categoría</Text>
                            <TouchableOpacity onPress={() => setModalOpcionesVisible(false)}><Text style={{ fontSize: 18, color: '#666', fontWeight: 'bold' }}>✕</Text></TouchableOpacity>
                        </View>
                        <Text style={styles.modalSubtitle}>Agrega o activa las opciones que aparecerán al registrar solicitudes.</Text>
                        <View style={styles.optionTypesWrap}>
                            {tiposConOpciones.map((tipo) => (
                                <TouchableOpacity key={tipo.id} style={[styles.optionTypePill, tipoOpcionSeleccionado === tipo.id && styles.optionTypePillActive]} onPress={() => setTipoOpcionSeleccionado(tipo.id)}>
                                    <Text style={[styles.optionTypePillText, tipoOpcionSeleccionado === tipo.id && { color: '#fff' }]}>{tipo.nombre}</Text>
                                </TouchableOpacity>
                            ))}
                        </View>
                        <View style={styles.addOptionRow}>
                            <TextInput style={styles.addOptionInput} value={nuevaOpcion} onChangeText={setNuevaOpcion} placeholder="Ej.: Monitor, Teclado..." maxLength={120} />
                            <TouchableOpacity style={styles.addOptionButton} onPress={guardarNuevaOpcion}><Text style={styles.addOptionButtonText}>Agregar</Text></TouchableOpacity>
                        </View>
                        <ScrollView style={{ maxHeight: 300 }} keyboardShouldPersistTaps="handled">
                            {tiposConOpciones.find((tipo) => tipo.id === tipoOpcionSeleccionado)?.opciones.map((opcion) => (
                                <View key={opcion.id} style={styles.optionAdminRow}>
                                    <Text style={[styles.optionAdminName, opcion.estado === false && { color: '#94a3b8' }]}>{opcion.nombre}</Text>
                                    <TouchableOpacity onPress={() => alternarOpcion(opcion.id, opcion.estado === false)}>
                                        <Text style={styles.optionAdminAction}>{opcion.estado === false ? 'Activar' : 'Desactivar'}</Text>
                                    </TouchableOpacity>
                                </View>
                            ))}
                        </ScrollView>
                    </View>
                </View>
            </Modal>

            {/* MODAL PARA OTORGAR / CAMBIAR / QUITAR ROL */}
            <Modal
                animationType="fade"
                transparent={true}
                visible={modalVisible}
                onRequestClose={() => setModalVisible(false)}
            >
                <View style={styles.modalOverlay}>
                    <View style={styles.modalContent}>
                        {usuarioSeleccionado && (
                            <>
                                <View style={styles.modalHeaderRow}>
                                    <Text style={styles.modalTitle}>Asignar Permiso / Rol</Text>
                                    <TouchableOpacity
                                        onPress={() => setModalVisible(false)}
                                        style={{ padding: 4 }}
                                    >
                                        <Text style={{ fontSize: 18, color: '#666', fontWeight: 'bold' }}>✕</Text>
                                    </TouchableOpacity>
                                </View>

                                <View style={styles.selectedUserBox}>
                                    <Text style={styles.selectedUserName}>
                                        {usuarioSeleccionado.nombre_completo}
                                        {esAutoEdicion ? ' (Tu cuenta)' : ''}
                                    </Text>
                                    <Text style={styles.selectedUserSub}>
                                        Usuario: {usuarioSeleccionado.username}
                                        {usuarioSeleccionado.ci ? ` | CI: ${usuarioSeleccionado.ci}` : ''}
                                    </Text>
                                    {Boolean(usuarioSeleccionado.email) && (
                                        <Text style={styles.selectedUserSub}>
                                            Email: {usuarioSeleccionado.email}
                                        </Text>
                                    )}
                                    <Text style={styles.selectedUserCurrentRole}>
                                        Rol actual:{' '}
                                        <Text style={{ fontWeight: 'bold', color: theme.colors.primary }}>
                                            {rolActualSeleccionado}
                                        </Text>
                                    </Text>
                                </View>

                                {esAutoEdicion && (
                                    <View style={styles.selfWarningBox}>
                                        <Text style={styles.selfWarningText}>
                                            💡 Puedes agregarte un segundo rol (como OPERADOR) para usar el menú ☰ y alternar entre vistas. Tu rol de ADMINISTRADOR está protegido.
                                        </Text>
                                    </View>
                                )}

                                <Text style={styles.sectionLabel}>
                                    Toca un rol para activarlo o desactivarlo (permite múltiples roles):
                                </Text>

                                {ROLES_DISPONIBLES.map((opcion) => {
                                    const esRolActivo = rolesSeleccionado.includes(opcion.rol);
                                    const bloqueadoQuitarPropioAdmin =
                                        esAutoEdicion && opcion.rol === 'ADMINISTRADOR' && esRolActivo;

                                    return (
                                        <TouchableOpacity
                                            key={opcion.rol}
                                            disabled={guardandoAccion || bloqueadoQuitarPropioAdmin}
                                            style={[
                                                styles.roleOptionBtn,
                                                {
                                                    borderColor: esRolActivo ? opcion.color : '#ddd',
                                                    backgroundColor: esRolActivo ? opcion.bg : '#fafafa',
                                                },
                                            ]}
                                            onPress={() => handleAlternarRol(opcion.rol)}
                                        >
                                            <View style={{ flexDirection: 'row', alignItems: 'center', justifyContent: 'space-between' }}>
                                                <View style={{ flexDirection: 'row', alignItems: 'center', gap: 7 }}>
                                                    <AppIcon name={opcion.icono} size={16} color={opcion.color} />
                                                    <Text style={[styles.roleOptionTitle, { color: opcion.color }]}>
                                                        {opcion.titulo}
                                                    </Text>
                                                </View>
                                                {esRolActivo ? (
                                                    <Text style={{ fontSize: 12, fontWeight: 'bold', color: opcion.color }}>
                                                        {bloqueadoQuitarPropioAdmin ? 'ACTIVO (PROTEGIDO)' : '✓ ACTIVO (Toca para quitar)'}
                                                    </Text>
                                                ) : (
                                                    <Text style={{ fontSize: 12, fontWeight: '600', color: '#7f8c8d' }}>
                                                        + Agregar rol
                                                    </Text>
                                                )}
                                            </View>
                                            <Text style={styles.roleOptionDesc}>{opcion.descripcion}</Text>
                                        </TouchableOpacity>
                                    );
                                })}

                                {/* BOTÓN ROJO PARA QUITAR ROL (VOLVER A USUARIO POR DEFECTO) */}
                                {tieneRolEspecial && !esAutoEdicion && (
                                    <View style={{ marginTop: 10 }}>
                                        {!confirmandoQuitar ? (
                                            <TouchableOpacity
                                                disabled={guardandoAccion}
                                                style={[styles.removeRoleBtn, { flexDirection: 'row', justifyContent: 'center', gap: 8 }]}
                                                onPress={() => setConfirmandoQuitar(true)}
                                            >
                                                <AppIcon name="trash" size={16} color="#c0392b" />
                                                <Text style={styles.removeRoleBtnText}>
                                                    Quitar todos los roles (Dejar sin rol)
                                                </Text>
                                            </TouchableOpacity>
                                        ) : (
                                            <View style={styles.confirmRemoveBox}>
                                                <Text style={styles.confirmRemoveTitle}>
                                                    ¿Confirmas quitar todos los roles a {usuarioSeleccionado.nombre_completo}?
                                                </Text>
                                                <Text style={styles.confirmRemoveSub}>
                                                    El funcionario pasará a estar SIN ROL y no podrá realizar solicitudes ni acceder a las opciones hasta que le asignes un rol.
                                                </Text>
                                                <View style={{ flexDirection: 'row', justifyContent: 'flex-end', gap: 10, marginTop: 10 }}>
                                                    <TouchableOpacity
                                                        style={styles.cancelConfirmBtn}
                                                        onPress={() => setConfirmandoQuitar(false)}
                                                        disabled={guardandoAccion}
                                                    >
                                                        <Text style={{ color: '#555', fontWeight: 'bold' }}>Cancelar</Text>
                                                    </TouchableOpacity>
                                                    <TouchableOpacity
                                                        style={styles.confirmDangerBtn}
                                                        onPress={ejecutarQuitarRol}
                                                        disabled={guardandoAccion}
                                                    >
                                                        {guardandoAccion ? (
                                                            <ActivityIndicator color="#fff" size="small" />
                                                        ) : (
                                                            <Text style={{ color: '#fff', fontWeight: 'bold' }}>
                                                                Sí, Quitar Rol
                                                            </Text>
                                                        )}
                                                    </TouchableOpacity>
                                                </View>
                                            </View>
                                        )}
                                    </View>
                                )}

                                <TouchableOpacity
                                    style={styles.closeModalBtn}
                                    onPress={() => setModalVisible(false)}
                                    disabled={guardandoAccion}
                                >
                                    <Text style={styles.closeModalBtnText}>Cerrar</Text>
                                </TouchableOpacity>
                            </>
                        )}
                    </View>
                </View>
            </Modal>

            {/* MENÚ LATERAL PARA CAMBIAR ENTRE ROLES */}
            <MenuLateralModal
                visible={menuLateralVisible}
                onClose={() => setMenuLateralVisible(false)}
                rolesDisponibles={rolesDisponibles}
                nombreUsuario={nombreUsuario}
                rolActivo={rolActivo}
                onSelectRol={(nuevoRol) => {
                    if (onCambiarRol) onCambiarRol(nuevoRol);
                }}
                onLogout={manejarCerrarSesion}
            />
        </View>
    );
};

const styles = StyleSheet.create({
    container: { flex: 1, backgroundColor: '#f8fafc' },
    centerContainer: { flex: 1, justifyContent: 'center', alignItems: 'center' },

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
    iconActionBtn: {
        width: 38,
        height: 38,
        borderRadius: 10,
        backgroundColor: 'rgba(255, 255, 255, 0.14)',
        justifyContent: 'center',
        alignItems: 'center',
    },
    headerBottomWave: {
        position: 'absolute',
        bottom: 0,
        left: 0,
        right: 0,
        width: '100%',
        height: 32,
    },

    // SECCIÓN DE RESUMEN Y BUSCADOR
    topSummarySection: {
        paddingHorizontal: 16,
        paddingTop: 10,
        paddingBottom: 6,
    },
    sectionBannerRow: {
        flexDirection: 'row',
        alignItems: 'center',
        marginBottom: 12,
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
    kpiRow: {
        flexDirection: 'row',
        gap: 10,
        marginBottom: 12,
    },
    kpiRowMobile: {
        flexWrap: 'nowrap',
        gap: 5,
    },
    kpiCard: {
        flex: 1,
        backgroundColor: '#ffffff',
        borderRadius: 14,
        paddingVertical: 10,
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
    kpiCardMobile: {
        flexBasis: 0,
        paddingHorizontal: 2,
    },
    kpiCardActive: {
        backgroundColor: '#eff6ff',
        borderColor: '#1d68d8',
        borderWidth: 2,
    },
    kpiNumber: {
        fontSize: 19,
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
    searchPillWrapper: {
        flexDirection: 'row',
        alignItems: 'center',
        backgroundColor: '#ffffff',
        borderRadius: 24,
        paddingHorizontal: 14,
        borderWidth: 1,
        borderColor: '#cbd5e1',
        height: 46,
    },
    manageOptionsButton: {
        alignSelf: 'flex-start',
        flexDirection: 'row',
        alignItems: 'center',
        marginTop: 10,
        paddingHorizontal: 12,
        paddingVertical: 8,
        borderRadius: 10,
        backgroundColor: '#eef4ff',
    },
    manageOptionsButtonText: { color: '#1d5bbf', fontSize: 13, fontWeight: '600', marginLeft: 7 },
    optionsModalContent: { width: '100%', maxWidth: 520, maxHeight: '85%', backgroundColor: '#fff', borderRadius: 18, padding: 18 },
    modalSubtitle: { fontSize: 13, color: '#64748b', marginBottom: 12 },
    optionTypesWrap: { flexDirection: 'row', flexWrap: 'wrap', marginBottom: 8, gap: 7 },
    optionTypePill: { paddingHorizontal: 12, paddingVertical: 8, borderRadius: 16, backgroundColor: '#f1f5f9' },
    optionTypePillActive: { backgroundColor: '#1d5bbf' },
    optionTypePillText: { color: '#334155', fontSize: 12, fontWeight: '600' },
    addOptionRow: { flexDirection: 'row', alignItems: 'center', marginBottom: 12 },
    addOptionInput: { flex: 1, height: 42, borderWidth: 1, borderColor: '#cbd5e1', borderRadius: 10, paddingHorizontal: 12, color: '#1e293b' },
    addOptionButton: { marginLeft: 8, paddingHorizontal: 14, paddingVertical: 11, borderRadius: 10, backgroundColor: '#1d5bbf' },
    addOptionButtonText: { color: '#fff', fontWeight: '700', fontSize: 12 },
    optionAdminRow: { flexDirection: 'row', alignItems: 'center', justifyContent: 'space-between', paddingVertical: 11, borderBottomWidth: 1, borderBottomColor: '#e2e8f0' },
    optionAdminName: { flex: 1, color: '#1e293b', fontSize: 14 },
    optionAdminAction: { color: '#1d5bbf', fontWeight: '600', fontSize: 12, padding: 6 },
    searchIcon: { fontSize: 15, marginRight: 8 },
    searchInput: {
        flex: 1,
        fontSize: 14,
        color: '#1e293b',
    },

    // TARJETAS DE USUARIOS
    listContainer: {
        paddingHorizontal: 16,
        paddingTop: 10,
        paddingBottom: 90,
    },
    card: {
        backgroundColor: '#ffffff',
        padding: 15,
        borderRadius: 16,
        marginBottom: 12,
        elevation: 3,
        shadowColor: '#0f172a',
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.06,
        shadowRadius: 6,
        borderWidth: 1,
        borderColor: '#e2e8f0',
    },
    cardTopRow: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
        marginBottom: 10,
    },
    roleBadge: {
        paddingHorizontal: 10,
        paddingVertical: 4,
        borderRadius: 8,
        borderWidth: 1,
    },
    roleBadgeText: {
        fontSize: 10.5,
        fontWeight: 'bold',
    },
    userIdRight: {
        fontSize: 11.5,
        color: '#64748b',
        fontWeight: '600',
    },
    cardMainRow: {
        flexDirection: 'row',
        alignItems: 'center',
        marginBottom: 10,
    },
    userAvatarCircle: {
        width: 42,
        height: 42,
        borderRadius: 21,
        backgroundColor: '#eff6ff',
        justifyContent: 'center',
        alignItems: 'center',
        marginRight: 12,
        borderWidth: 1,
        borderColor: '#dbeafe',
    },
    userName: {
        fontSize: 14.5,
        fontWeight: 'bold',
        color: '#1e3a8a',
    },
    userSub: {
        fontSize: 12,
        color: '#64748b',
        marginTop: 2,
    },
    divider: {
        height: 1,
        backgroundColor: '#f1f5f9',
        marginBottom: 10,
    },
    cardFooterRow: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
    },
    userMetaText: {
        fontSize: 12.5,
        color: '#334155',
    },
    detailsButton: {
        backgroundColor: '#0f4c9c',
        paddingHorizontal: 16,
        paddingVertical: 7,
        borderRadius: 10,
    },
    detailsButtonText: {
        color: '#ffffff',
        fontSize: 12,
        fontWeight: 'bold',
    },

    emptyCardBox: {
        backgroundColor: '#ffffff',
        borderRadius: 12,
        padding: 18,
        borderWidth: 1,
        borderColor: '#e2e8f0',
        alignItems: 'center',
        marginTop: 10,
    },
    emptyText: {
        textAlign: 'center',
        color: '#475569',
        fontSize: 14,
        lineHeight: 20,
    },
    goToAllButton: {
        marginTop: 12,
        backgroundColor: '#0f4c9c',
        paddingVertical: 10,
        paddingHorizontal: 18,
        borderRadius: 10,
    },
    goToAllButtonText: {
        color: '#fff',
        fontWeight: 'bold',
        fontSize: 13,
    },

    // ONDAS INFERIORES + BOTTOM NAV
    bottomWavesWrapper: {
        position: 'absolute',
        bottom: 0,
        left: 0,
        right: 0,
        height: 44,
        overflow: 'hidden',
    },
    bottomWavesImg: {
        width: '100%',
        height: 44,
    },
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

    // MODAL
    modalOverlay: {
        flex: 1,
        backgroundColor: 'rgba(0,0,0,0.5)',
        justifyContent: 'center',
        padding: 20,
    },
    modalContent: {
        backgroundColor: '#fff',
        borderRadius: 14,
        padding: 20,
        maxWidth: 500,
        width: '100%',
        alignSelf: 'center',
        elevation: 6,
    },
    modalHeaderRow: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
        marginBottom: 12,
    },
    modalTitle: {
        fontSize: 18,
        fontWeight: 'bold',
        color: theme.colors.primary,
    },
    selectedUserBox: {
        backgroundColor: '#f4f6f9',
        padding: 12,
        borderRadius: 8,
        marginBottom: 14,
        borderLeftWidth: 4,
        borderLeftColor: theme.colors.primary,
    },
    selectedUserName: {
        fontSize: 15,
        fontWeight: 'bold',
        color: '#2c3e50',
    },
    selectedUserSub: {
        fontSize: 13,
        color: '#666',
        marginTop: 2,
    },
    selectedUserCurrentRole: {
        fontSize: 13,
        color: '#444',
        marginTop: 6,
    },
    selfWarningBox: {
        backgroundColor: '#fff8e1',
        borderLeftWidth: 4,
        borderLeftColor: '#f39c12',
        padding: 10,
        borderRadius: 6,
        marginBottom: 12,
    },
    selfWarningText: {
        fontSize: 12,
        color: '#8a6d3b',
        fontWeight: '500',
    },
    sectionLabel: {
        fontSize: 13,
        fontWeight: 'bold',
        color: '#444',
        marginBottom: 8,
    },
    roleOptionBtn: {
        borderWidth: 1.5,
        borderRadius: 10,
        padding: 12,
        marginBottom: 10,
    },
    roleOptionTitle: {
        fontSize: 14,
        fontWeight: 'bold',
    },
    roleOptionDesc: {
        fontSize: 12,
        color: '#666',
        marginTop: 4,
    },
    removeRoleBtn: {
        backgroundColor: '#fdecea',
        borderWidth: 1,
        borderColor: '#e74c3c',
        paddingVertical: 12,
        borderRadius: 8,
        alignItems: 'center',
    },
    removeRoleBtnText: {
        color: '#c0392b',
        fontWeight: 'bold',
        fontSize: 14,
    },
    confirmRemoveBox: {
        backgroundColor: '#fdecea',
        borderWidth: 1,
        borderColor: '#e74c3c',
        borderRadius: 8,
        padding: 12,
    },
    confirmRemoveTitle: {
        fontSize: 13,
        fontWeight: 'bold',
        color: '#c0392b',
    },
    confirmRemoveSub: {
        fontSize: 12,
        color: '#922b21',
        marginTop: 4,
    },
    cancelConfirmBtn: {
        paddingVertical: 8,
        paddingHorizontal: 14,
        borderRadius: 6,
        backgroundColor: '#fff',
        borderWidth: 1,
        borderColor: '#ccc',
    },
    confirmDangerBtn: {
        paddingVertical: 8,
        paddingHorizontal: 14,
        borderRadius: 6,
        backgroundColor: '#e74c3c',
    },
    closeModalBtn: {
        marginTop: 12,
        paddingVertical: 11,
        borderRadius: 8,
        backgroundColor: '#ecf0f1',
        alignItems: 'center',
    },
    closeModalBtnText: {
        color: '#555',
        fontWeight: 'bold',
        fontSize: 14,
    },
});
