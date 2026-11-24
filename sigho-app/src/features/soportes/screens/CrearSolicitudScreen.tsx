import React, { useState, useEffect, useRef, useMemo } from 'react';
import {
    View,
    Text,
    TextInput,
    TouchableOpacity,
    StyleSheet,
    ScrollView,
    ActivityIndicator,
    Alert,
    Platform,
    Image,
    Keyboard,
    KeyboardAvoidingView,
    Modal,
    useWindowDimensions
} from 'react-native';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import { useNavigation } from '@react-navigation/native';
import * as ImagePicker from 'expo-image-picker';
import { guardarItem, obtenerItem } from '../../../core/utils/storage';
import { AppIcon, NombreIcono } from '../../../core/components/AppIcon';
import { AppAlert } from '../../../core/components/AlertaGlobalModal';
import { useCrearSolicitud } from '../hooks/useCrearSolicitud';
import { CrearSolicitudPayload } from '../types/soporte.types';
import { obtenerSedes, obtenerConsultoriosPorSede } from '../api/soporte.service';
import { obtenerTiposSoporteConOpciones } from '../api/soporte.service';

interface UbicacionGuardada {
    piso: string;
    oficina: string;
    consultorio: string;
}

const STORAGE_KEY_UBICACIONES = 'soporte_ubicaciones_guardadas';

const ICONOS_TIPO: Record<number, NombreIcono> = { 1: 'laptop', 2: 'gear', 3: 'document-text', 4: 'globe' };

export const CrearSolicitudScreen = () => {
    const { width } = useWindowDimensions();
    const insets = useSafeAreaInsets();
    const navigation = useNavigation();
    const { enviarSolicitud, isLoading } = useCrearSolicitud();

    const esPantallaAncha = width >= 640;
    const esEscritorio = Platform.OS === 'web' && width > 768;

    const scrollViewRef = useRef<React.ElementRef<typeof ScrollView>>(null);

    // Estados para la carga asistida de Sedes y Consultorios
    const [sedes, setSedes] = useState<Array<{ id: number; piso: string; ubicacion: string }>>([]);
    const [cargandoSedes, setCargandoSedes] = useState(true);
    const [idSedeSeleccionada, setIdSedeSeleccionada] = useState<number | null>(null);

    const [consultorios, setConsultorios] = useState<Array<{ id: number; numero_consultorio: string; id_sedes: number }>>([]);
    const [cargandoConsultorios, setCargandoConsultorios] = useState(false);
    const [mostrarConsultorios, setMostrarConsultorios] = useState(true);

    const [modalPisoVisible, setModalPisoVisible] = useState(false);

    // Estados locales para el formulario de ubicación (3 TextInput editables)
    const [piso, setPiso] = useState('');
    const [oficina, setOficina] = useState('');
    const [consultorio, setConsultorio] = useState('');
    const [ubicacionesGuardadas, setUbicacionesGuardadas] = useState<UbicacionGuardada[]>([]);

    // Estados locales para el ítem del problema
    const [tipoSoporte, setTipoSoporte] = useState<number>(1);
    const [tiposSoporte, setTiposSoporte] = useState<Array<{ id: number; nombre: string; opciones: Array<{ id: number; nombre: string }> }>>([]);
    const [opcionSoporte, setOpcionSoporte] = useState<{ id: number; nombre: string } | null>(null);
    const [opcionesSoporteVisibles, setOpcionesSoporteVisibles] = useState(true);
    const [descripcion, setDescripcion] = useState('');
    const [imagenProblemaUri, setImagenProblemaUri] = useState<string | null>(null);

    // Estado para resaltar input activo y detector de teclado abierto
    const [campoEnfocado, setCampoEnfocado] = useState<string | null>(null);
    const [tecladoVisible, setTecladoVisible] = useState(false);

    // Estados de validación de campos obligatorios y mensaje de alerta
    const [errores, setErrores] = useState<{
        piso?: string;
        oficina?: string;
        descripcion?: string;
        opcionSoporte?: string;
    }>({});
    const [mensajeAlerta, setMensajeAlerta] = useState<string | null>(null);

    useEffect(() => {
        const showSub = Keyboard.addListener('keyboardDidShow', () => setTecladoVisible(true));
        const hideSub = Keyboard.addListener('keyboardDidHide', () => setTecladoVisible(false));
        return () => {
            showSub.remove();
            hideSub.remove();
        };
    }, []);

    useEffect(() => {
        obtenerTiposSoporteConOpciones()
            .then((datos) => {
                if (!Array.isArray(datos)) return;
                setTiposSoporte(datos.map((tipo: any) => ({
                    id: Number(tipo.id),
                    nombre: String(tipo.nombre),
                    opciones: (tipo.opciones || []).filter((opcion: any) => opcion.estado !== false),
                })));
                if (datos.length > 0) setTipoSoporte(Number(datos[0].id));
            })
            .catch((error) => console.error('Error al cargar categorías de soporte:', error));
    }, []);

    // 1. Cargar la lista completa de sedes al montar la pantalla
    useEffect(() => {
        const cargarListaSedes = async () => {
            try {
                setCargandoSedes(true);
                const datos = await obtenerSedes();
                setSedes(Array.isArray(datos) ? datos : []);
            } catch (e) {
                console.error('Error al cargar sedes:', e);
            } finally {
                setCargandoSedes(false);
            }
        };
        cargarListaSedes();
    }, []);

    // Agrupación de sedes por ubicación (ej. 'AV. 6 DE AGOSTO', 'OBRAJES', 'AV. BRASIL')
    const sedesAgrupadas = useMemo(() => {
        const mapa: Record<string, Array<{ id: number; piso: string; ubicacion: string }>> = {};
        sedes.forEach((s) => {
            const ub = (s.ubicacion || 'OTRA UBICACIÓN').trim();
            if (!mapa[ub]) mapa[ub] = [];
            mapa[ub].push(s);
        });
        return mapa;
    }, [sedes]);

    // Función para consultar consultorios por sede seleccionada
    const cargarConsultorios = async (sedeId: number) => {
        setCargandoConsultorios(true);
        try {
            const resp = await obtenerConsultoriosPorSede(sedeId);
            setConsultorios(Array.isArray(resp.datos) ? resp.datos : []);
        } catch (e) {
            console.error('Error al cargar consultorios:', e);
            setConsultorios([]);
        } finally {
            setCargandoConsultorios(false);
        }
    };

    // Al elegir una sede desde el modal asistido
    const seleccionarSedeDesdeModal = (s: { id: number; piso: string; ubicacion: string }) => {
        const textoPiso = s.ubicacion ? `${s.piso} (${s.ubicacion})` : s.piso;
        setPiso(textoPiso);
        setIdSedeSeleccionada(s.id);
        setMostrarConsultorios(true);
        setModalPisoVisible(false);
        if (errores.piso) {
            setErrores((prev) => {
                const next = { ...prev };
                delete next.piso;
                return next;
            });
        }
        cargarConsultorios(s.id);
    };

    // Cargar las ubicaciones guardadas anteriormente al abrir la pantalla
    useEffect(() => {
        const cargarUbicaciones = async () => {
            try {
                const userDataStr = await obtenerItem('userData');
                const userId = userDataStr ? JSON.parse(userDataStr)?.id : 'default';
                const key = `${STORAGE_KEY_UBICACIONES}_${userId}`;
                const guardadasStr = await obtenerItem(key);
                if (guardadasStr) {
                    const lista = JSON.parse(guardadasStr);
                    if (Array.isArray(lista)) {
                        setUbicacionesGuardadas(lista);
                    }
                }
            } catch (e) {
                console.log('No se pudieron leer ubicaciones guardadas:', e);
            }
        };
        cargarUbicaciones();
    }, []);

    const guardarUbicacionEnHistorial = async (nueva: UbicacionGuardada) => {
        try {
            const userDataStr = await obtenerItem('userData');
            const userId = userDataStr ? JSON.parse(userDataStr)?.id : 'default';
            const key = `${STORAGE_KEY_UBICACIONES}_${userId}`;

            // Evitamos duplicados exactos y guardamos hasta las últimas 5
            const sinDuplicados = ubicacionesGuardadas.filter(
                (u) =>
                    !(
                        u.piso.toLowerCase() === nueva.piso.toLowerCase() &&
                        u.oficina.toLowerCase() === nueva.oficina.toLowerCase() &&
                        u.consultorio.toLowerCase() === nueva.consultorio.toLowerCase()
                    )
            );
            const actualizada = [nueva, ...sinDuplicados].slice(0, 5);
            setUbicacionesGuardadas(actualizada);
            await guardarItem(key, JSON.stringify(actualizada));
        } catch (e) {
            console.log('No se pudo guardar la ubicación:', e);
        }
    };

    const eliminarUbicacionGuardada = async (indexAEliminar: number) => {
        try {
            const userDataStr = await obtenerItem('userData');
            const userId = userDataStr ? JSON.parse(userDataStr)?.id : 'default';
            const key = `${STORAGE_KEY_UBICACIONES}_${userId}`;
            const actualizada = ubicacionesGuardadas.filter((_, idx) => idx !== indexAEliminar);
            setUbicacionesGuardadas(actualizada);
            await guardarItem(key, JSON.stringify(actualizada));
        } catch (e) {
            console.log('Error al eliminar ubicación guardada:', e);
        }
    };

    const seleccionarUbicacionGuardada = (u: UbicacionGuardada) => {
        setPiso(u.piso);
        setOficina(u.oficina);
        setConsultorio(u.consultorio);
        setMostrarConsultorios(!u.consultorio);
        setIdSedeSeleccionada(null);
        setConsultorios([]);

        // Si coincide con alguna sede registrada, activamos sus consultorios
        const match = sedes.find(
            (s) =>
                u.piso.toLowerCase().includes(s.piso.toLowerCase()) &&
                (!s.ubicacion || u.piso.toLowerCase().includes(s.ubicacion.toLowerCase()))
        );
        if (match) {
            setIdSedeSeleccionada(match.id);
            cargarConsultorios(match.id);
        }

        setErrores((prev) => {
            const next = { ...prev };
            delete next.piso;
            delete next.oficina;
            return next;
        });
    };

    const manejarEnvio = () => {
        const nuevosErrores: { piso?: string; oficina?: string; descripcion?: string; opcionSoporte?: string } = {};
        const listaMensajes: string[] = [];

        if (!piso.trim()) {
            nuevosErrores.piso = 'Indica el piso donde se requiere la atención';
            listaMensajes.push('• Piso requerido');
        }

        if (!descripcion.trim()) {
            nuevosErrores.descripcion = 'Describe brevemente el problema presentado';
            listaMensajes.push('• Descripción detallada del problema');
        }

        const opcionesDelTipo = tiposSoporte.find((tipo) => tipo.id === tipoSoporte)?.opciones || [];
        if (opcionesDelTipo.length > 0 && !opcionSoporte) {
            nuevosErrores.opcionSoporte = 'Selecciona el equipo o servicio que presenta el problema';
            listaMensajes.push('• Opción de soporte');
        }

        if (Object.keys(nuevosErrores).length > 0) {
            setErrores(nuevosErrores);
            setMensajeAlerta('Por favor completa todos los campos obligatorios marcados en rojo.');

            AppAlert.alert('Faltan datos', 'Por favor completa los campos marcados en rojo.');

            if (nuevosErrores.piso) {
                scrollViewRef.current?.scrollTo({ y: 0, animated: true });
            }
            return;
        }

        setErrores({});
        setMensajeAlerta(null);

        const ubicacionActual: UbicacionGuardada = {
            piso: piso.trim(),
            oficina: oficina.trim(),
            consultorio: consultorio.trim(),
        };

        const payload: CrearSolicitudPayload = {
            ...ubicacionActual,
            items: [
                {
                    id_tipo_soporte: tipoSoporte,
                    descripcion: [opcionSoporte ? `Opción: ${opcionSoporte.nombre}` : null, descripcion.trim()].filter(Boolean).join('\n'),
                }
            ]
        };

        enviarSolicitud(payload, async () => {
            await guardarUbicacionEnHistorial(ubicacionActual);
            AppAlert.alert('¡Solicitud Enviada!', 'Tu solicitud de soporte ha sido registrada y está lista para ser atendida.');
            navigation.goBack();
        }, imagenProblemaUri);
    };

    const seleccionarImagenProblema = async (usarCamara: boolean) => {
        try {
            if (usarCamara) {
                const permiso = await ImagePicker.requestCameraPermissionsAsync();
                if (!permiso.granted) {
                    AppAlert.alert('Permiso requerido', 'Permite el acceso a la cámara para tomar una foto.');
                    return;
                }
            } else {
                const permiso = await ImagePicker.requestMediaLibraryPermissionsAsync();
                if (!permiso.granted) {
                    AppAlert.alert('Permiso requerido', 'Permite el acceso a la galería para elegir una imagen.');
                    return;
                }
            }
            const resultado = usarCamara
                ? await ImagePicker.launchCameraAsync({ mediaTypes: ImagePicker.MediaTypeOptions.Images, quality: 0.8 })
                : await ImagePicker.launchImageLibraryAsync({ mediaTypes: ImagePicker.MediaTypeOptions.Images, quality: 0.8 });
            if (!resultado.canceled && resultado.assets?.[0]?.uri) setImagenProblemaUri(resultado.assets[0].uri);
        } catch (error) {
            AppAlert.alert('No se pudo abrir la cámara o galería', 'Intenta nuevamente.');
        }
    };

    return (
        <View style={styles.screenWrapper}>
            {/* 1. CABECERA INSTITUCIONAL PROPIA CON BOTÓN ATRÁS Y ONDAS */}
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
                        Nueva Solicitud
                    </Text>

                    {/* Espaciador simétrico derecho para centrar perfectamente el título */}
                    <View style={styles.headerSpacerRight} />
                </View>

                {/* Onda superior curva en el borde inferior */}
                <Image
                    source={require('../../../../assets/ondas-superiores.png')}
                    style={styles.headerBottomWave}
                    resizeMode="stretch"
                />
            </View>

            {/* CONTENEDOR CON EVITACIÓN DE TECLADO */}
            <KeyboardAvoidingView
                style={styles.keyboardAvoiding}
                behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
                keyboardVerticalOffset={Platform.OS === 'ios' ? 0 : 20}
            >
                {/* CONTENIDO DEL FORMULARIO CON SCROLL */}
                <ScrollView
                    ref={scrollViewRef}
                    style={styles.scrollView}
                    contentContainerStyle={[styles.scrollContent, { paddingBottom: 40 }]}
                    keyboardShouldPersistTaps="handled"
                    keyboardDismissMode="on-drag"
                    showsVerticalScrollIndicator={false}
                >
                    <View style={[styles.innerContainer, { maxWidth: 720 }]}>
                        {/* 2. ENCABEZADO DE SECCIÓN (BANNER INSTITUCIONAL) */}
                        <View style={styles.sectionBannerRow}>
                            <View style={styles.blueCircleIcon}>
                                <AppIcon name="clipboard" size={22} color="#ffffff" />
                            </View>
                            <View style={{ flex: 1 }}>
                                <Text style={styles.sectionBannerTitle}>
                                    Registrar soporte técnico
                                </Text>
                                <Text style={styles.sectionBannerSub}>
                                    Completa los datos para asignar tu ticket a un técnico
                                </Text>
                            </View>
                        </View>

                        {/* 3. TARJETA 1 — UBICACIÓN PARA SOPORTE */}
                        <View style={styles.card}>
                            <View style={styles.cardHeaderRow}>
                                <View style={styles.cardHeaderIconBadge}>
                                    <AppIcon name="pin" size={17} color="#1d5bbf" />
                                </View>
                                <View style={{ flex: 1 }}>
                                    <Text style={styles.cardHeaderTitle}>Ubicación para Soporte</Text>
                                    <Text style={styles.cardHeaderSub}>Indica dónde se encuentra el equipo o problema</Text>
                                </View>
                            </View>

                            {/* CHIPS DE UBICACIONES GUARDADAS */}
                            {ubicacionesGuardadas.length > 0 && (
                                <View style={styles.savedLocationsBox}>
                                    <View style={{ flexDirection: 'row', alignItems: 'center', marginBottom: 8 }}>
                                        <AppIcon name="pin" size={13} color="#1d5bbf" />
                                        <Text style={styles.savedLocationsTitle}>
                                            Ubicaciones recientes (toca para rellenar):
                                        </Text>
                                    </View>
                                    {ubicacionesGuardadas.map((u, idx) => {
                                        const esSeleccionada =
                                            piso === u.piso &&
                                            oficina === u.oficina &&
                                            consultorio === u.consultorio;
                                        const resumen = [
                                            u.piso ? `Piso: ${u.piso}` : null,
                                            u.oficina ? `Ofic.: ${u.oficina}` : null,
                                            u.consultorio ? `Cons.: ${u.consultorio}` : null,
                                        ]
                                            .filter(Boolean)
                                            .join('  •  ');

                                        return (
                                            <View
                                                key={idx}
                                                style={[
                                                    styles.savedChipRow,
                                                    esSeleccionada && styles.savedChipRowActive,
                                                ]}
                                            >
                                                <TouchableOpacity
                                                    style={{ flex: 1, paddingVertical: 8, paddingHorizontal: 10, flexDirection: 'row', alignItems: 'center' }}
                                                    onPress={() => seleccionarUbicacionGuardada(u)}
                                                    disabled={isLoading}
                                                >
                                                    <AppIcon
                                                        name={esSeleccionada ? 'check' : 'pin'}
                                                        size={13}
                                                        color={esSeleccionada ? '#1d5bbf' : '#64748b'}
                                                    />
                                                    <Text
                                                        style={[
                                                            styles.savedChipText,
                                                            esSeleccionada && styles.savedChipTextActive,
                                                        ]}
                                                        numberOfLines={1}
                                                    >
                                                        {resumen}
                                                    </Text>
                                                </TouchableOpacity>

                                                <TouchableOpacity
                                                    style={styles.savedChipDeleteBtn}
                                                    onPress={() => eliminarUbicacionGuardada(idx)}
                                                    disabled={isLoading}
                                                >
                                                    <Text style={styles.savedChipDeleteText}>✕</Text>
                                                </TouchableOpacity>
                                            </View>
                                        );
                                    })}
                                </View>
                            )}

                            {/* 1. CAMPO PISO (OBLIGATORIO + SELECTOR DE SEDES) */}
                            <View style={styles.inputGroup}>
                                <Text style={styles.inputLabel}>
                                    Piso <Text style={styles.requiredAsterisk}>*</Text>
                                </Text>
                                <View style={[
                                    styles.inputWithIconWrapper,
                                    campoEnfocado === 'piso' && styles.inputWithIconWrapperFocused,
                                    Boolean(errores.piso) && styles.inputWithIconWrapperError,
                                ]}>
                                    <View style={styles.inputIconPrefix}>
                                        <AppIcon
                                            name="home"
                                            size={17}
                                            color={errores.piso ? '#dc2626' : campoEnfocado === 'piso' ? '#1d5bbf' : '#64748b'}
                                        />
                                    </View>
                                    <TextInput
                                        style={styles.textInputField}
                                        placeholder="Ej: Planta Baja, Piso 2..."
                                        placeholderTextColor="#94a3b8"
                                        value={piso}
                                        onChangeText={(text) => {
                                            setPiso(text);
                                            if (idSedeSeleccionada !== null) {
                                                setIdSedeSeleccionada(null);
                                                setConsultorios([]);
                                            }
                                            if (errores.piso) {
                                                setErrores((prev) => {
                                                    const next = { ...prev };
                                                    delete next.piso;
                                                    return next;
                                                });
                                            }
                                        }}
                                        onFocus={() => setCampoEnfocado('piso')}
                                        onBlur={() => setCampoEnfocado(null)}
                                        editable={!isLoading}
                                    />
                                    <TouchableOpacity
                                        style={styles.inputSelectorPillBtn}
                                        onPress={() => setModalPisoVisible(true)}
                                        disabled={isLoading || cargandoSedes}
                                        activeOpacity={0.7}
                                    >
                                        <AppIcon name="pin" size={12} color="#1d5bbf" />
                                        <Text style={styles.inputSelectorPillBtnText}>Sedes ▾</Text>
                                    </TouchableOpacity>
                                </View>
                                {Boolean(errores.piso) && (
                                    <View style={styles.errorHelperRow}>
                                        <AppIcon name="alert" size={13} color="#dc2626" />
                                        <Text style={styles.errorTextHelper}>{errores.piso}</Text>
                                    </View>
                                )}
                            </View>

                            {/* 2. CAMPO CONSULTORIO (HÍBRIDO: TEXTO LIBRE + CHIPS ASISTIDOS DEL PISO) */}
                            <View style={styles.inputGroup}>
                                <Text style={styles.inputLabel}>
                                    Consultorio <Text style={styles.optionalTag}>(Opcional)</Text>
                                </Text>
                                <View style={[
                                    styles.inputWithIconWrapper,
                                    campoEnfocado === 'consultorio' && styles.inputWithIconWrapperFocused
                                ]}>
                                    <View style={styles.inputIconPrefix}>
                                        <AppIcon
                                            name="pin"
                                            size={17}
                                            color={campoEnfocado === 'consultorio' ? '#1d5bbf' : '#64748b'}
                                        />
                                    </View>
                                    <TextInput
                                        style={styles.textInputField}
                                        placeholder="Ej: Consultorio 12, Sala A..."
                                        placeholderTextColor="#94a3b8"
                                        value={consultorio}
                                        onChangeText={(text) => {
                                            setConsultorio(text);
                                        }}
                                        onFocus={() => setCampoEnfocado('consultorio')}
                                        onBlur={() => setCampoEnfocado(null)}
                                        editable={!isLoading}
                                    />
                                    {Boolean(consultorio) && (
                                        <TouchableOpacity
                                            onPress={() => setConsultorio('')}
                                            hitSlop={{ top: 8, bottom: 8, left: 8, right: 8 }}
                                            style={{ padding: 4 }}
                                        >
                                            <AppIcon name="close" size={14} color="#94a3b8" />
                                        </TouchableOpacity>
                                    )}
                                </View>

                                {/* INDICADOR DE CARGA DE CONSULTORIOS */}
                                {cargandoConsultorios && (
                                    <View style={styles.consultoriosLoadingRow}>
                                        <ActivityIndicator size="small" color="#1d5bbf" />
                                        <Text style={styles.consultoriosLoadingText}>Cargando consultorios del piso...</Text>
                                    </View>
                                )}

                                {/* CHIPS SUGERIDOS DE CONSULTORIOS CUANDO idSedeSeleccionada TIENE REGISTROS */}
                                {Boolean(!cargandoConsultorios && idSedeSeleccionada !== null && consultorios.length > 0) && (
                                    <View style={styles.consultoriosChipsContainer}>
                                        <TouchableOpacity
                                            style={styles.consultoriosToggle}
                                            onPress={() => setMostrarConsultorios((visible) => !visible)}
                                            activeOpacity={0.7}
                                        >
                                            <Text style={styles.chipsLabel}>
                                                {mostrarConsultorios
                                                    ? 'Consultorios sugeridos en este piso (toca para rellenar):'
                                                    : 'Mostrar consultorios para cambiar'}
                                            </Text>
                                            <Text style={styles.consultoriosToggleIcon}>{mostrarConsultorios ? '▴' : '▾'}</Text>
                                        </TouchableOpacity>
                                        {mostrarConsultorios && <View style={styles.chipsWrapRow}>
                                            {consultorios.map((c) => {
                                                const nombreSugerido = `Consultorio ${c.numero_consultorio}`;
                                                const activo = consultorio.trim().toLowerCase() === nombreSugerido.toLowerCase();
                                                return (
                                                    <TouchableOpacity
                                                        key={c.id}
                                                        style={[
                                                            styles.consultorioChip,
                                                            activo && styles.consultorioChipActive,
                                                        ]}
                                                        onPress={() => {
                                                            if (activo) {
                                                                setConsultorio('');
                                                                setMostrarConsultorios(true);
                                                            } else {
                                                                setConsultorio(nombreSugerido);
                                                                setMostrarConsultorios(false);
                                                            }
                                                        }}
                                                        activeOpacity={0.7}
                                                    >
                                                        <AppIcon
                                                            name={activo ? 'check' : 'pin'}
                                                            size={12}
                                                            color={activo ? '#ffffff' : '#1d5bbf'}
                                                        />
                                                        <Text style={[
                                                            styles.consultorioChipText,
                                                            activo && styles.consultorioChipTextActive,
                                                        ]}>
                                                            {nombreSugerido}
                                                        </Text>
                                                    </TouchableOpacity>
                                                );
                                            })}
                                        </View>}
                                    </View>
                                )}

                                {/* AVISO DISCRETO EN GRIS SI EL PISO NO TIENE CONSULTORIOS */}
                                {Boolean(!cargandoConsultorios && idSedeSeleccionada !== null && consultorios.length === 0) && (
                                    <Text style={styles.sinConsultoriosNotice}>
                                        Este piso no tiene consultorios registrados (solo oficinas administrativas)
                                    </Text>
                                )}
                            </View>

                            {/* 3. CAMPO OFICINA (OPCIONAL) */}
                            <View style={styles.inputGroup}>
                                <Text style={styles.inputLabel}>
                                    Oficina <Text style={styles.optionalTag}>(Opcional)</Text>
                                </Text>
                                <View style={[
                                    styles.inputWithIconWrapper,
                                    campoEnfocado === 'oficina' && styles.inputWithIconWrapperFocused,
                                ]}>
                                    <View style={styles.inputIconPrefix}>
                                        <AppIcon
                                            name="document"
                                            size={17}
                                            color={campoEnfocado === 'oficina' ? '#1d5bbf' : '#64748b'}
                                        />
                                    </View>
                                    <TextInput
                                        style={styles.textInputField}
                                        placeholder="Ej: Contabilidad, RRHH, Plataforma..."
                                        placeholderTextColor="#94a3b8"
                                        value={oficina}
                                        onChangeText={setOficina}
                                        onFocus={() => setCampoEnfocado('oficina')}
                                        onBlur={() => setCampoEnfocado(null)}
                                        editable={!isLoading}
                                    />
                                </View>
                            </View>

                        </View>

                        {/* 4. TARJETA 2 — DETALLE DEL PROBLEMA */}
                        <View style={styles.card}>
                            <View style={styles.cardHeaderRow}>
                                <View style={styles.cardHeaderIconBadge}>
                                    <AppIcon name="gear" size={17} color="#1d5bbf" />
                                </View>
                                <View style={{ flex: 1 }}>
                                    <Text style={styles.cardHeaderTitle}>Detalle del Problema</Text>
                                    <Text style={styles.cardHeaderSub}>Especifica la categoría y explica qué sucede</Text>
                                </View>
                            </View>

                            <Text style={styles.inputLabel}>
                                ¿Qué tipo de problema tienes? <Text style={styles.requiredAsterisk}>*</Text>
                            </Text>

                            {/* TARJETAS SELECCIONABLES CON CHECK A LA DERECHA */}
                            <View style={styles.categoriesContainer}>
                                {tiposSoporte.map((tipo) => {
                                    const activo = tipoSoporte === tipo.id;
                                    return (
                                        <React.Fragment key={tipo.id}>
                                        <TouchableOpacity
                                            style={[
                                                styles.categoryCard,
                                                activo && styles.categoryCardActive,
                                            ]}
                                            onPress={() => {
                                                setTipoSoporte(tipo.id);
                                                setOpcionSoporte(null);
                                                setOpcionesSoporteVisibles(true);
                                            }}
                                            disabled={isLoading}
                                            activeOpacity={0.8}
                                        >
                                            <View style={[styles.categoryIconCircle, activo && styles.categoryIconCircleActive]}>
                                                <AppIcon
                                                    name={ICONOS_TIPO[tipo.id] || 'gear'}
                                                    size={19}
                                                    color={activo ? '#1d5bbf' : '#64748b'}
                                                />
                                            </View>

                                            <View style={styles.categoryTextWrapper}>
                                                <Text style={[styles.categoryTitle, activo && styles.categoryTitleActive]}>
                                                    {tipo.nombre}
                                                </Text>
                                                <Text style={styles.categoryDesc}>
                                                    {tipo.id === 1 ? 'Equipos, periféricos y componentes físicos.' : tipo.id === 2 ? 'Módulos, permisos, accesos y sistemas institucionales.' : tipo.id === 3 ? 'Programas y herramientas ofimáticas.' : 'Conectividad, internet y red local.'}
                                                </Text>
                                            </View>

                                            {/* Círculo selector con check a la derecha */}
                                            <View style={[styles.radioCircle, activo && styles.radioCircleActive]}>
                                                {activo && <AppIcon name="check" size={13} color="#ffffff" />}
                                            </View>
                                        </TouchableOpacity>
                                        {activo && tipo.opciones.length > 0 && (
                                            <View style={styles.supportOptionsBox}>
                                                <TouchableOpacity style={styles.supportOptionsHeader} onPress={() => setOpcionesSoporteVisibles((visible) => !visible)}>
                                                    <View style={{ flex: 1 }}>
                                                        <Text style={styles.inputLabel}>¿Qué equipo o servicio presenta el problema? <Text style={styles.requiredAsterisk}>*</Text></Text>
                                                        {!opcionesSoporteVisibles && <Text style={styles.supportOptionSummary}>{opcionSoporte?.nombre || 'Selecciona una opción'}</Text>}
                                                    </View>
                                                    <Text style={styles.consultoriosToggleIcon}>{opcionesSoporteVisibles ? '▴' : '▾'}</Text>
                                                </TouchableOpacity>
                                                {opcionesSoporteVisibles && (
                                                    <View style={styles.supportOptionsWrap}>
                                                        {tipo.opciones.map((opcion) => {
                                                            const activa = opcionSoporte?.id === opcion.id;
                                                            return (
                                                                <TouchableOpacity key={opcion.id} style={[styles.consultorioChip, activa && styles.consultorioChipActive]} onPress={() => {
                                                                    setOpcionSoporte(opcion);
                                                                    setOpcionesSoporteVisibles(false);
                                                                    setErrores((prev) => { const next = { ...prev }; delete next.opcionSoporte; return next; });
                                                                }}>
                                                                    <Text style={[styles.consultorioChipText, activa && styles.consultorioChipTextActive]}>{opcion.nombre}</Text>
                                                                </TouchableOpacity>
                                                            );
                                                        })}
                                                    </View>
                                                )}
                                                {errores.opcionSoporte && <Text style={styles.errorTextHelper}>{errores.opcionSoporte}</Text>}
                                            </View>
                                        )}
                                        </React.Fragment>
                                    );
                                })}
                            </View>

                            <View style={styles.problemImageBox}>
                                <Text style={styles.inputLabel}>Imagen del problema <Text style={styles.optionalTag}>(Opcional)</Text></Text>
                                <Text style={styles.problemImageHint}>Puedes elegir una imagen de la galería o tomar una foto (máximo 5 MB).</Text>
                                <View style={styles.problemImageActions}>
                                    <TouchableOpacity style={styles.problemImageButton} onPress={() => seleccionarImagenProblema(false)} disabled={isLoading}>
                                        <AppIcon name="folder" size={15} color="#1d5bbf" />
                                        <Text style={styles.problemImageButtonText}>Galería</Text>
                                    </TouchableOpacity>
                                    <TouchableOpacity style={styles.problemImageButton} onPress={() => seleccionarImagenProblema(true)} disabled={isLoading}>
                                        <AppIcon name="camera" size={15} color="#1d5bbf" />
                                        <Text style={styles.problemImageButtonText}>Tomar foto</Text>
                                    </TouchableOpacity>
                                </View>
                                {imagenProblemaUri && (
                                    <View style={styles.problemImagePreviewRow}>
                                        <Image source={{ uri: imagenProblemaUri }} style={styles.problemImagePreview} />
                                        <Text style={styles.problemImageSelected}>Imagen lista para adjuntar</Text>
                                        <TouchableOpacity onPress={() => setImagenProblemaUri(null)} style={{ padding: 8 }}>
                                            <AppIcon name="close" size={16} color="#64748b" />
                                        </TouchableOpacity>
                                    </View>
                                )}
                            </View>

                            {/* DESCRIPCIÓN DETALLADA CON CONTADOR Y AUTO-SCROLL AL ENFOCAR */}
                            <View style={styles.inputGroup}>
                                <Text style={styles.inputLabel}>
                                    Descripción detallada <Text style={styles.requiredAsterisk}>*</Text>
                                </Text>
                                <View style={[
                                    styles.textAreaWrapper,
                                    campoEnfocado === 'descripcion' && styles.textAreaWrapperFocused,
                                    Boolean(errores.descripcion) && styles.textAreaWrapperError,
                                ]}>
                                    <TextInput
                                        style={styles.textAreaField}
                                        placeholder="Describe el problema detalladamente (ej: el sistema arroja error al imprimir, el equipo no enciende, no hay conexión)..."
                                        placeholderTextColor="#94a3b8"
                                        multiline
                                        numberOfLines={4}
                                        maxLength={500}
                                        value={descripcion}
                                        onChangeText={(text) => {
                                            setDescripcion(text);
                                            if (errores.descripcion) {
                                                setErrores((prev) => {
                                                    const next = { ...prev };
                                                    delete next.descripcion;
                                                    return next;
                                                });
                                            }
                                        }}
                                        onFocus={() => {
                                            setCampoEnfocado('descripcion');
                                            setTimeout(() => {
                                                scrollViewRef.current?.scrollToEnd({ animated: true });
                                            }, 150);
                                        }}
                                        onBlur={() => setCampoEnfocado(null)}
                                        editable={!isLoading}
                                    />
                                    <View style={styles.charCounterContainer}>
                                        <Text style={[
                                            styles.charCounterText,
                                            descripcion.length >= 450 && styles.charCounterWarning
                                        ]}>
                                            {descripcion.length} / 500
                                        </Text>
                                    </View>
                                </View>
                                {Boolean(errores.descripcion) && (
                                    <View style={styles.errorHelperRow}>
                                        <AppIcon name="alert" size={13} color="#dc2626" />
                                        <Text style={styles.errorTextHelper}>{errores.descripcion}</Text>
                                    </View>
                                )}
                            </View>
                        </View>

                        {/* NOTIFICACIÓN / ALERTA VISUAL DE ERRORES */}
                        {mensajeAlerta && (
                            <View style={styles.alertBanner}>
                                <View style={styles.alertBannerIcon}>
                                    <AppIcon name="alert" size={18} color="#dc2626" />
                                </View>
                                <Text style={styles.alertBannerText}>{mensajeAlerta}</Text>
                            </View>
                        )}

                        {/* 5. BOTÓN PRINCIPAL PÍLDORA */}
                        <TouchableOpacity
                            style={[styles.submitButton, isLoading && styles.submitButtonDisabled]}
                            onPress={manejarEnvio}
                            disabled={isLoading}
                            activeOpacity={0.85}
                        >
                            {isLoading ? (
                                <ActivityIndicator color="#ffffff" size="small" />
                            ) : (
                                <View style={styles.submitButtonContent}>
                                    <AppIcon name="check" size={18} color="#ffffff" />
                                    <Text style={styles.submitButtonText}>Enviar Solicitud</Text>
                                </View>
                            )}
                        </TouchableOpacity>
                    </View>

                    {/* 6. ONDAS INFERIORES DECORATIVAS (OCULTAS MIENTRAS EL TECLADO ESTÁ ABIERTO) */}
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

            {/* MODAL LIMPIO: LISTADO DE SEDES AGRUPADAS POR UBICACIÓN */}
            <Modal
                visible={modalPisoVisible}
                transparent={true}
                animationType="fade"
                onRequestClose={() => setModalPisoVisible(false)}
            >
                <TouchableOpacity
                    style={styles.modalSelectorOverlay}
                    activeOpacity={1}
                    onPress={() => setModalPisoVisible(false)}
                >
                    <TouchableOpacity activeOpacity={1} style={styles.modalSelectorCard}>
                        <View style={styles.modalSelectorHeader}>
                            <View style={{ flex: 1 }}>
                                <Text style={styles.modalSelectorTitle}>Seleccionar Piso / Sede</Text>
                                <Text style={styles.modalSelectorSub}>
                                    Elige la sede para cargar automáticamente los consultorios
                                </Text>
                            </View>
                            <TouchableOpacity
                                style={styles.modalCloseBtn}
                                onPress={() => setModalPisoVisible(false)}
                            >
                                <AppIcon name="close" size={16} color="#64748b" />
                            </TouchableOpacity>
                        </View>

                        <ScrollView
                            style={styles.modalSelectorScroll}
                            keyboardShouldPersistTaps="handled"
                            showsVerticalScrollIndicator={false}
                        >
                            {Object.entries(sedesAgrupadas).map(([ubicacion, listaPisos]) => (
                                <View key={ubicacion} style={styles.groupSection}>
                                    <View style={styles.groupHeaderRow}>
                                        <AppIcon name="pin" size={14} color="#1d5bbf" />
                                        <Text style={styles.groupHeaderTitle}>{ubicacion}</Text>
                                    </View>
                                    <View style={styles.groupPisosGrid}>
                                        {listaPisos.map((s) => {
                                            const activo = idSedeSeleccionada === s.id;
                                            return (
                                                <TouchableOpacity
                                                    key={s.id}
                                                    style={[
                                                        styles.pisoPillItem,
                                                        activo && styles.pisoPillItemActive
                                                    ]}
                                                    onPress={() => seleccionarSedeDesdeModal(s)}
                                                    activeOpacity={0.7}
                                                >
                                                    <AppIcon
                                                        name="home"
                                                        size={13}
                                                        color={activo ? '#ffffff' : '#1d5bbf'}
                                                    />
                                                    <Text style={[
                                                        styles.pisoPillText,
                                                        activo && styles.pisoPillTextActive
                                                    ]}>
                                                        {s.piso}
                                                    </Text>
                                                </TouchableOpacity>
                                            );
                                        })}
                                    </View>
                                </View>
                            ))}
                        </ScrollView>
                    </TouchableOpacity>
                </TouchableOpacity>
            </Modal>
        </View>
    );
};

const styles = StyleSheet.create({
    screenWrapper: {
        flex: 1,
        backgroundColor: '#f4f7fb',
    },
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
    keyboardAvoiding: {
        flex: 1,
    },
    scrollView: {
        flex: 1,
    },
    scrollContent: {
        paddingBottom: 40,
    },
    innerContainer: {
        width: '100%',
        alignSelf: 'center',
        paddingHorizontal: 16,
        paddingTop: 16,
    },
    // Encabezado de sección
    sectionBannerRow: {
        flexDirection: 'row',
        alignItems: 'center',
        backgroundColor: '#ffffff',
        paddingHorizontal: 16,
        paddingVertical: 14,
        borderRadius: 16,
        marginBottom: 16,
        borderWidth: 1,
        borderColor: '#e2e8f0',
        elevation: 2,
        shadowColor: '#000',
        shadowOffset: { width: 0, height: 1 },
        shadowOpacity: 0.06,
        shadowRadius: 4,
    },
    blueCircleIcon: {
        width: 44,
        height: 44,
        borderRadius: 22,
        backgroundColor: '#1d5bbf',
        justifyContent: 'center',
        alignItems: 'center',
        marginRight: 14,
    },
    sectionBannerTitle: {
        fontSize: 16,
        fontWeight: 'bold',
        color: '#1e293b',
    },
    sectionBannerSub: {
        fontSize: 12,
        color: '#64748b',
        marginTop: 2,
    },
    // Tarjetas del formulario
    card: {
        backgroundColor: '#ffffff',
        borderRadius: 16,
        padding: 18,
        borderWidth: 1,
        borderColor: '#e2e8f0',
        marginBottom: 16,
        elevation: 2,
        shadowColor: '#000',
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.06,
        shadowRadius: 5,
    },
    cardHeaderRow: {
        flexDirection: 'row',
        alignItems: 'center',
        borderBottomWidth: 1,
        borderBottomColor: '#f1f5f9',
        paddingBottom: 12,
        marginBottom: 14,
    },
    cardHeaderIconBadge: {
        width: 34,
        height: 34,
        borderRadius: 8,
        backgroundColor: '#eef4ff',
        justifyContent: 'center',
        alignItems: 'center',
        marginRight: 10,
    },
    cardHeaderTitle: {
        fontSize: 15,
        fontWeight: 'bold',
        color: '#1e293b',
    },
    cardHeaderSub: {
        fontSize: 12,
        color: '#64748b',
        marginTop: 1,
    },
    // Chips de ubicaciones guardadas
    savedLocationsBox: {
        backgroundColor: '#f8fafc',
        borderRadius: 12,
        padding: 12,
        marginBottom: 14,
        borderWidth: 1,
        borderColor: '#e2e8f0',
    },
    savedLocationsTitle: {
        fontSize: 12,
        fontWeight: 'bold',
        color: '#1d5bbf',
        marginLeft: 6,
    },
    savedChipRow: {
        flexDirection: 'row',
        alignItems: 'center',
        backgroundColor: '#ffffff',
        borderRadius: 8,
        borderWidth: 1,
        borderColor: '#cbd5e1',
        marginBottom: 6,
    },
    savedChipRowActive: {
        borderColor: '#1d5bbf',
        backgroundColor: '#eef4ff',
        borderWidth: 1.5,
    },
    savedChipText: {
        fontSize: 12,
        color: '#334155',
        fontWeight: '500',
        marginLeft: 6,
        flex: 1,
    },
    savedChipTextActive: {
        color: '#1d5bbf',
        fontWeight: 'bold',
    },
    savedChipDeleteBtn: {
        paddingHorizontal: 10,
        paddingVertical: 8,
    },
    savedChipDeleteText: {
        fontSize: 12,
        color: '#94a3b8',
        fontWeight: 'bold',
    },
    // Formularios e inputs
    twoColRow: {
        flexDirection: 'row',
        gap: 12,
    },
    colHalf: {
        flex: 1,
        marginBottom: 14,
    },
    inputGroup: {
        marginBottom: 14,
    },
    inputLabel: {
        fontSize: 13,
        fontWeight: '600',
        color: '#334155',
        marginBottom: 6,
    },
    requiredAsterisk: {
        color: '#dc2626',
        fontWeight: 'bold',
    },
    optionalTag: {
        fontSize: 12,
        color: '#94a3b8',
        fontWeight: 'normal',
    },
    inputWithIconWrapper: {
        flexDirection: 'row',
        alignItems: 'center',
        backgroundColor: '#f8fafc',
        borderWidth: 1,
        borderColor: '#cbd5e1',
        borderRadius: 12,
        paddingHorizontal: 12,
    },
    inputWithIconWrapperFocused: {
        borderWidth: 1.8,
        borderColor: '#1d5bbf',
        backgroundColor: '#ffffff',
    },
    inputWithIconWrapperError: {
        borderWidth: 1.8,
        borderColor: '#ef4444',
        backgroundColor: '#fef2f2',
    },
    errorHelperRow: {
        flexDirection: 'row',
        alignItems: 'center',
        marginTop: 5,
        marginLeft: 4,
    },
    errorTextHelper: {
        fontSize: 12,
        color: '#dc2626',
        fontWeight: '500',
        marginLeft: 6,
    },
    alertBanner: {
        flexDirection: 'row',
        alignItems: 'center',
        backgroundColor: '#fef2f2',
        borderWidth: 1.5,
        borderColor: '#fca5a5',
        borderRadius: 14,
        paddingHorizontal: 14,
        paddingVertical: 12,
        marginBottom: 16,
    },
    alertBannerIcon: {
        marginRight: 10,
        justifyContent: 'center',
        alignItems: 'center',
    },
    alertBannerText: {
        fontSize: 13,
        color: '#b91c1c',
        fontWeight: '600',
        flex: 1,
        lineHeight: 18,
    },
    inputIconPrefix: {
        marginRight: 8,
        justifyContent: 'center',
        alignItems: 'center',
    },
    textInputField: {
        flex: 1,
        paddingVertical: 11,
        fontSize: 14,
        color: '#1e293b',
    },
    textAreaWrapper: {
        backgroundColor: '#f8fafc',
        borderWidth: 1,
        borderColor: '#cbd5e1',
        borderRadius: 12,
        paddingHorizontal: 12,
        paddingTop: 10,
        paddingBottom: 8,
    },
    textAreaWrapperFocused: {
        borderWidth: 1.8,
        borderColor: '#1d5bbf',
        backgroundColor: '#ffffff',
    },
    textAreaWrapperError: {
        borderWidth: 1.8,
        borderColor: '#ef4444',
        backgroundColor: '#fef2f2',
    },
    textAreaField: {
        minHeight: 100,
        fontSize: 14,
        color: '#1e293b',
        textAlignVertical: 'top',
        padding: 0,
    },
    charCounterContainer: {
        alignItems: 'flex-end',
        marginTop: 6,
    },
    charCounterText: {
        fontSize: 11,
        color: '#94a3b8',
        fontWeight: '500',
    },
    charCounterWarning: {
        color: '#d97706',
        fontWeight: 'bold',
    },
    // Tarjetas de categorías / tipos de soporte
    categoriesContainer: {
        marginBottom: 14,
        marginTop: 2,
    },
    categoryCard: {
        flexDirection: 'row',
        alignItems: 'center',
        padding: 12,
        borderWidth: 1,
        borderColor: '#e2e8f0',
        borderRadius: 14,
        backgroundColor: '#ffffff',
        marginBottom: 10,
    },
    categoryCardActive: {
        borderWidth: 1.5,
        borderColor: '#1d5bbf',
        backgroundColor: '#eef4ff',
    },
    categoryIconCircle: {
        width: 42,
        height: 42,
        borderRadius: 21,
        backgroundColor: '#f1f5f9',
        justifyContent: 'center',
        alignItems: 'center',
        marginRight: 12,
    },
    categoryIconCircleActive: {
        backgroundColor: '#dbeafe',
    },
    categoryTextWrapper: {
        flex: 1,
        marginRight: 10,
    },
    categoryTitle: {
        fontSize: 14,
        fontWeight: 'bold',
        color: '#1e293b',
    },
    categoryTitleActive: {
        color: '#1d5bbf',
    },
    categoryDesc: {
        fontSize: 12,
        color: '#64748b',
        marginTop: 2,
        lineHeight: 16,
    },
    supportOptionsBox: {
        marginBottom: 14,
        padding: 12,
        backgroundColor: '#f8fafc',
        borderWidth: 1,
        borderColor: '#e2e8f0',
        borderRadius: 12,
    },
    supportOptionsHeader: { flexDirection: 'row', alignItems: 'center' },
    supportOptionSummary: { fontSize: 12, color: '#1d5bbf', fontWeight: '600', marginTop: -4, marginBottom: 5 },
    supportOptionsWrap: { flexDirection: 'row', flexWrap: 'wrap', gap: 8, marginTop: 6 },
    problemImageBox: { marginTop: 4, marginBottom: 18, padding: 12, borderRadius: 12, borderWidth: 1, borderColor: '#e2e8f0', backgroundColor: '#f8fafc' },
    problemImageHint: { color: '#64748b', fontSize: 12, marginTop: -4, marginBottom: 10 },
    problemImageActions: { flexDirection: 'row', flexWrap: 'wrap', gap: 8 },
    problemImageButton: { flexDirection: 'row', alignItems: 'center', paddingHorizontal: 12, paddingVertical: 9, borderRadius: 9, backgroundColor: '#eef4ff' },
    problemImageButtonText: { color: '#1d5bbf', fontSize: 12, fontWeight: '600', marginLeft: 6 },
    problemImagePreviewRow: { flexDirection: 'row', alignItems: 'center', marginTop: 10, padding: 6, borderRadius: 9, backgroundColor: '#fff' },
    problemImagePreview: { width: 54, height: 54, borderRadius: 7, backgroundColor: '#e2e8f0' },
    problemImageSelected: { flex: 1, marginLeft: 10, color: '#334155', fontSize: 12 },
    radioCircle: {
        width: 24,
        height: 24,
        borderRadius: 12,
        borderWidth: 2,
        borderColor: '#cbd5e1',
        justifyContent: 'center',
        alignItems: 'center',
    },
    radioCircleActive: {
        borderWidth: 0,
        backgroundColor: '#1d5bbf',
    },
    // Botón de envío
    submitButton: {
        backgroundColor: '#164a96',
        height: 50,
        borderRadius: 26,
        alignItems: 'center',
        justifyContent: 'center',
        marginTop: 4,
        marginBottom: 28,
        elevation: 3,
        shadowColor: '#164a96',
        shadowOffset: { width: 0, height: 4 },
        shadowOpacity: 0.25,
        shadowRadius: 6,
    },
    submitButtonDisabled: {
        backgroundColor: '#94a3b8',
        elevation: 0,
    },
    submitButtonContent: {
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'center',
    },
    submitButtonText: {
        color: '#ffffff',
        fontSize: 15,
        fontWeight: 'bold',
        marginLeft: 8,
        letterSpacing: 0.3,
    },
    // Ondas decorativas en la base
    bottomWavesWrapper: {
        width: '100%',
        height: 48,
        overflow: 'hidden',
        marginTop: 8,
    },
    bottomWavesImg: {
        width: '100%',
        height: 48,
    },
    // Estilos para Selectores en cascada y Modales limpios
    selectorTrigger: {
        flexDirection: 'row',
        alignItems: 'center',
        backgroundColor: '#f8fafc',
        borderWidth: 1,
        borderColor: '#cbd5e1',
        borderRadius: 12,
        paddingHorizontal: 12,
        paddingVertical: 12,
        minHeight: 46,
    },
    selectorTriggerDisabled: {
        backgroundColor: '#f1f5f9',
        borderColor: '#e2e8f0',
        opacity: 0.75,
    },
    selectorTriggerText: {
        flex: 1,
        fontSize: 14,
        color: '#1e293b',
        fontWeight: '500',
    },
    selectorPlaceholderText: {
        color: '#94a3b8',
        fontWeight: 'normal',
    },
    selectorSubNotice: {
        fontSize: 11,
        color: '#94a3b8',
        marginTop: 2,
        fontStyle: 'italic',
    },
    // Botón selector integrado en el TextInput de Piso
    inputSelectorPillBtn: {
        flexDirection: 'row',
        alignItems: 'center',
        backgroundColor: '#eef4ff',
        paddingHorizontal: 10,
        paddingVertical: 7,
        borderRadius: 8,
        marginLeft: 6,
    },
    inputSelectorPillBtnText: {
        fontSize: 12,
        fontWeight: 'bold',
        color: '#1d5bbf',
        marginLeft: 4,
    },
    // Estilos de carga y chips de consultorios
    consultoriosLoadingRow: {
        flexDirection: 'row',
        alignItems: 'center',
        marginTop: 8,
        marginLeft: 4,
    },
    consultoriosLoadingText: {
        fontSize: 12,
        color: '#1d5bbf',
        marginLeft: 6,
        fontStyle: 'italic',
    },
    consultoriosChipsContainer: {
        marginTop: 10,
    },
    consultoriosToggle: {
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'space-between',
    },
    consultoriosToggleIcon: {
        color: '#1d5bbf',
        fontSize: 16,
        fontWeight: '700',
        marginLeft: 8,
    },
    chipsLabel: {
        fontSize: 12,
        fontWeight: '600',
        color: '#475569',
        marginBottom: 6,
    },
    chipsWrapRow: {
        flexDirection: 'row',
        flexWrap: 'wrap',
        gap: 8,
    },
    consultorioChip: {
        flexDirection: 'row',
        alignItems: 'center',
        backgroundColor: '#f1f5f9',
        borderWidth: 1,
        borderColor: '#cbd5e1',
        paddingHorizontal: 12,
        paddingVertical: 7,
        borderRadius: 16,
    },
    consultorioChipActive: {
        backgroundColor: '#164a96',
        borderColor: '#164a96',
    },
    consultorioChipText: {
        fontSize: 12,
        fontWeight: '500',
        color: '#1d5bbf',
        marginLeft: 5,
    },
    consultorioChipTextActive: {
        color: '#ffffff',
        fontWeight: 'bold',
    },
    sinConsultoriosNotice: {
        fontSize: 12,
        color: '#64748b',
        marginTop: 6,
        marginLeft: 4,
        fontStyle: 'italic',
    },
    // Estilos de agrupación para modal de pisos
    groupSection: {
        marginBottom: 16,
    },
    groupHeaderRow: {
        flexDirection: 'row',
        alignItems: 'center',
        paddingBottom: 6,
        borderBottomWidth: 1,
        borderBottomColor: '#f1f5f9',
        marginBottom: 10,
    },
    groupHeaderTitle: {
        fontSize: 13,
        fontWeight: 'bold',
        color: '#1d5bbf',
        marginLeft: 6,
        letterSpacing: 0.3,
    },
    groupPisosGrid: {
        flexDirection: 'row',
        flexWrap: 'wrap',
        gap: 8,
    },
    pisoPillItem: {
        flexDirection: 'row',
        alignItems: 'center',
        backgroundColor: '#f8fafc',
        borderWidth: 1,
        borderColor: '#e2e8f0',
        paddingHorizontal: 12,
        paddingVertical: 8,
        borderRadius: 10,
    },
    pisoPillItemActive: {
        backgroundColor: '#1d5bbf',
        borderColor: '#1d5bbf',
    },
    pisoPillText: {
        fontSize: 13,
        color: '#334155',
        fontWeight: '500',
        marginLeft: 6,
    },
    pisoPillTextActive: {
        color: '#ffffff',
        fontWeight: 'bold',
    },
    modalSelectorOverlay: {
        flex: 1,
        backgroundColor: 'rgba(15, 23, 42, 0.5)',
        justifyContent: 'center',
        alignItems: 'center',
        padding: 20,
    },
    modalSelectorCard: {
        width: '100%',
        maxWidth: 480,
        backgroundColor: '#ffffff',
        borderRadius: 20,
        padding: 20,
        elevation: 8,
        shadowColor: '#000',
        shadowOffset: { width: 0, height: 4 },
        shadowOpacity: 0.15,
        shadowRadius: 12,
    },
    modalSelectorHeader: {
        flexDirection: 'row',
        alignItems: 'flex-start',
        borderBottomWidth: 1,
        borderBottomColor: '#f1f5f9',
        paddingBottom: 14,
        marginBottom: 12,
    },
    modalSelectorTitle: {
        fontSize: 16,
        fontWeight: 'bold',
        color: '#1e293b',
    },
    modalSelectorSub: {
        fontSize: 12,
        color: '#64748b',
        marginTop: 2,
    },
    modalCloseBtn: {
        width: 32,
        height: 32,
        borderRadius: 16,
        backgroundColor: '#f1f5f9',
        justifyContent: 'center',
        alignItems: 'center',
        marginLeft: 8,
    },
    modalSelectorScroll: {
        maxHeight: 340,
    },
    modalOptionRow: {
        flexDirection: 'row',
        alignItems: 'center',
        paddingVertical: 12,
        paddingHorizontal: 12,
        borderRadius: 12,
        backgroundColor: '#f8fafc',
        borderWidth: 1,
        borderColor: '#e2e8f0',
        marginBottom: 8,
    },
    modalOptionRowActive: {
        backgroundColor: '#eef4ff',
        borderColor: '#1d5bbf',
        borderWidth: 1.5,
    },
    modalOptionIconCircle: {
        width: 32,
        height: 32,
        borderRadius: 16,
        backgroundColor: '#ffffff',
        justifyContent: 'center',
        alignItems: 'center',
        marginRight: 10,
    },
    modalOptionIconCircleActive: {
        backgroundColor: '#dbeafe',
    },
    modalOptionText: {
        flex: 1,
        fontSize: 14,
        color: '#1e293b',
        fontWeight: '500',
    },
    modalOptionTextActive: {
        color: '#1d5bbf',
        fontWeight: 'bold',
    },
});
