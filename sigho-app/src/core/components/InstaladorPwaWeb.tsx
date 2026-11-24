import React, { useEffect, useState } from 'react';
import { Modal, Platform, StyleSheet, Text, TouchableOpacity, View } from 'react-native';
import { AppIcon } from './AppIcon';

interface BeforeInstallPromptEvent extends Event {
    prompt: () => Promise<void>;
    userChoice: Promise<{ outcome: 'accepted' | 'dismissed' }>;
}

const STORAGE_DISMISS_KEY = 'pwa_install_banner_dismissed';

export const InstaladorPwaWeb = () => {
    const [deferredPrompt, setDeferredPrompt] = useState<BeforeInstallPromptEvent | null>(null);
    const [esMovilWeb, setEsMovilWeb] = useState(false);
    const [esIOS, setEsIOS] = useState(false);
    const [yaInstalada, setYaInstalada] = useState(false);
    const [oculto, setOculto] = useState(false);
    const [modalGuiaVisible, setModalGuiaVisible] = useState(false);

    useEffect(() => {
        if (Platform.OS !== 'web' || typeof window === 'undefined' || typeof document === 'undefined') {
            return;
        }

        // 1. Inyectar metaetiquetas y manifest PWA en <head>
        const asegurarLink = (rel: string, href: string) => {
            if (!document.querySelector(`link[rel="${rel}"][href="${href}"]`)) {
                const link = document.createElement('link');
                link.rel = rel;
                link.href = href;
                document.head.appendChild(link);
            }
        };
        const asegurarMeta = (name: string, content: string) => {
            let meta = document.querySelector(`meta[name="${name}"]`) as HTMLMetaElement | null;
            if (!meta) {
                meta = document.createElement('meta');
                meta.name = name;
                document.head.appendChild(meta);
            }
            meta.content = content;
        };

        asegurarLink('manifest', '/manifest.json');
        asegurarLink('apple-touch-icon', '/icon-192.png');
        asegurarMeta('theme-color', '#164a96');
        asegurarMeta('apple-mobile-web-app-capable', 'yes');
        asegurarMeta('apple-mobile-web-app-title', 'Soporte SSU');
        asegurarMeta('mobile-web-app-capable', 'yes');

        // 2. Registrar Service Worker (solo en contexto seguro: HTTPS o localhost)
        if ('serviceWorker' in navigator && window.isSecureContext) {
            navigator.serviceWorker.register('/sw.js').catch(() => {});
        }

        // 3. Detectar si ya está abierta como app instalada (standalone)
        const standalone =
            window.matchMedia?.('(display-mode: standalone)').matches ||
            Boolean((navigator as any).standalone);
        setYaInstalada(standalone);

        const ua = navigator.userAgent || '';
        const ios = /iPad|iPhone|iPod/.test(ua);
        const movil = ios || /Android|Mobile|SamsungBrowser/i.test(ua);
        setEsIOS(ios);
        setEsMovilWeb(movil);

        try {
            if (window.sessionStorage?.getItem(STORAGE_DISMISS_KEY) === '1') {
                setOculto(true);
            }
        } catch {
            // ignorar
        }

        const manejarBeforeInstall = (e: Event) => {
            e.preventDefault();
            setDeferredPrompt(e as BeforeInstallPromptEvent);
        };

        const manejarAppInstalled = () => {
            setDeferredPrompt(null);
            setYaInstalada(true);
        };

        window.addEventListener('beforeinstallprompt', manejarBeforeInstall);
        window.addEventListener('appinstalled', manejarAppInstalled);

        return () => {
            window.removeEventListener('beforeinstallprompt', manejarBeforeInstall);
            window.removeEventListener('appinstalled', manejarAppInstalled);
        };
    }, []);

    if (Platform.OS !== 'web' || yaInstalada || oculto) {
        return null;
    }

    // Mostrar si el navegador ofreció instalación nativa O si es navegador móvil (para guía de acceso directo)
    if (!deferredPrompt && !esMovilWeb) {
        return null;
    }

    const cerrarBanner = () => {
        setOculto(true);
        try {
            window.sessionStorage?.setItem(STORAGE_DISMISS_KEY, '1');
        } catch {
            // ignorar
        }
    };

    const handleInstalar = async () => {
        if (deferredPrompt) {
            try {
                await deferredPrompt.prompt();
                const choice = await deferredPrompt.userChoice;
                if (choice.outcome === 'accepted') {
                    setYaInstalada(true);
                }
                setDeferredPrompt(null);
            } catch {
                setModalGuiaVisible(true);
            }
            return;
        }
        setModalGuiaVisible(true);
    };

    return (
        <>
            <View style={styles.wrapper} pointerEvents="box-none">
                <View style={styles.banner}>
                    <View style={styles.iconBadge}>
                        <AppIcon name="laptop" size={16} color="#164a96" />
                    </View>
                    <View style={{ flex: 1 }}>
                        <Text style={styles.title}>Instalar Soporte SSU</Text>
                        <Text style={styles.subtitle}>
                            {deferredPrompt
                                ? 'Crea un acceso directo en tu pantalla'
                                : 'Agrega un acceso directo en tu celular'}
                        </Text>
                    </View>
                    <TouchableOpacity style={styles.installBtn} onPress={() => void handleInstalar()} activeOpacity={0.85}>
                        <Text style={styles.installBtnText}>{deferredPrompt ? 'Instalar' : 'Cómo'}</Text>
                    </TouchableOpacity>
                    <TouchableOpacity
                        style={styles.closeBtn}
                        onPress={cerrarBanner}
                        hitSlop={{ top: 8, bottom: 8, left: 8, right: 8 }}
                        accessibilityLabel="Cerrar aviso de instalación"
                    >
                        <Text style={styles.closeBtnText}>✕</Text>
                    </TouchableOpacity>
                </View>
            </View>

            <Modal
                visible={modalGuiaVisible}
                transparent
                animationType="fade"
                onRequestClose={() => setModalGuiaVisible(false)}
            >
                <View style={styles.modalBackdrop}>
                    <View style={styles.modalCard}>
                        <Text style={styles.modalTitle}>Agregar acceso directo</Text>
                        <Text style={styles.modalSub}>
                            Sigue estos pasos para tener el ícono de Soporte SSU en tu pantalla de inicio:
                        </Text>
                        {esIOS ? (
                            <View style={styles.stepsBox}>
                                <Text style={styles.stepText}>1. Toca el botón Compartir (cuadrado con flecha hacia arriba) en Safari.</Text>
                                <Text style={styles.stepText}>2. Desliza y elige "Agregar a inicio".</Text>
                                <Text style={styles.stepText}>3. Toca "Agregar" arriba a la derecha.</Text>
                            </View>
                        ) : (
                            <View style={styles.stepsBox}>
                                <Text style={styles.stepText}>1. Toca el menú del navegador (los 3 puntos ⋮ o las 3 líneas ≡).</Text>
                                <Text style={styles.stepText}>2. Selecciona "Agregar a la pantalla principal" o "Instalar aplicación".</Text>
                                <Text style={styles.stepText}>3. Confirma en "Agregar" para crear el acceso directo.</Text>
                            </View>
                        )}
                        <TouchableOpacity
                            style={styles.modalOkBtn}
                            onPress={() => setModalGuiaVisible(false)}
                            activeOpacity={0.85}
                        >
                            <Text style={styles.modalOkText}>Entendido</Text>
                        </TouchableOpacity>
                    </View>
                </View>
            </Modal>
        </>
    );
};

const styles = StyleSheet.create({
    wrapper: {
        position: 'absolute',
        left: 12,
        right: 12,
        bottom: 76,
        alignItems: 'center',
        zIndex: 999,
    },
    banner: {
        width: '100%',
        maxWidth: 480,
        flexDirection: 'row',
        alignItems: 'center',
        gap: 10,
        backgroundColor: '#0f294a',
        borderRadius: 14,
        paddingVertical: 10,
        paddingHorizontal: 12,
        shadowColor: '#0f172a',
        shadowOffset: { width: 0, height: 4 },
        shadowOpacity: 0.24,
        shadowRadius: 10,
        elevation: 8,
    },
    iconBadge: {
        width: 34,
        height: 34,
        borderRadius: 9,
        backgroundColor: '#eff6ff',
        alignItems: 'center',
        justifyContent: 'center',
    },
    title: {
        color: '#ffffff',
        fontSize: 13,
        fontWeight: '700',
    },
    subtitle: {
        color: '#cbd5e1',
        fontSize: 11,
        marginTop: 1,
    },
    installBtn: {
        backgroundColor: '#2563eb',
        paddingHorizontal: 13,
        paddingVertical: 7,
        borderRadius: 9,
    },
    installBtnText: {
        color: '#ffffff',
        fontSize: 12,
        fontWeight: '700',
    },
    closeBtn: {
        padding: 4,
    },
    closeBtnText: {
        color: '#94a3b8',
        fontSize: 14,
        fontWeight: '700',
    },
    modalBackdrop: {
        flex: 1,
        backgroundColor: 'rgba(15,23,42,0.55)',
        justifyContent: 'center',
        padding: 20,
    },
    modalCard: {
        width: '100%',
        maxWidth: 400,
        alignSelf: 'center',
        backgroundColor: '#ffffff',
        borderRadius: 16,
        padding: 20,
    },
    modalTitle: {
        fontSize: 17,
        fontWeight: '800',
        color: '#0f294a',
    },
    modalSub: {
        fontSize: 13,
        color: '#475569',
        marginTop: 6,
        lineHeight: 18,
    },
    stepsBox: {
        marginTop: 14,
        backgroundColor: '#f8fafc',
        borderRadius: 12,
        padding: 12,
        borderWidth: 1,
        borderColor: '#e2e8f0',
        gap: 8,
    },
    stepText: {
        fontSize: 13,
        color: '#1e293b',
        lineHeight: 19,
    },
    modalOkBtn: {
        marginTop: 16,
        backgroundColor: '#164a96',
        borderRadius: 10,
        paddingVertical: 11,
        alignItems: 'center',
    },
    modalOkText: {
        color: '#ffffff',
        fontSize: 13.5,
        fontWeight: '700',
    },
});
