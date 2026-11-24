import * as Device from 'expo-device';
import { Platform } from 'react-native';

// Evitamos cargar expo-notifications en Web porque genera advertencias en consola
const Notifications = Platform.OS !== 'web' ? require('expo-notifications') : null;

// Esto le dice a la app qué hacer si recibe una notificación MIENTRAS el usuario la está usando (solo móvil)
if (Platform.OS !== 'web' && Notifications) {
    Notifications.setNotificationHandler({
        handleNotification: async (notification: any) => {
            return {
                shouldShowAlert: true,
                shouldPlaySound: true,
                shouldSetBadge: false,
                shouldShowBanner: true,
                shouldShowList: true,
            };
        },
    });
}

export async function registrarParaNotificacionesPush(): Promise<string | undefined> {
    if (Platform.OS === 'web') {
        console.log('Notificaciones Push omitidas en entorno web.');
        return undefined;
    }
    // -------------------------------

    let token;

    if (Platform.OS === 'android') {
        // Android requiere un "canal" de notificaciones obligatorio
        await Notifications.setNotificationChannelAsync('default', {
            name: 'Alertas SigHov2',
            importance: Notifications.AndroidImportance.MAX,
            vibrationPattern: [0, 250, 250, 250],
            lightColor: '#2B548F', // Tu azul institucional
        });
    }

    // Comprobamos que sea un celular físico (los emuladores web no siempre soportan push)
    if (Device.isDevice) {
        const { status: existingStatus } = await Notifications.getPermissionsAsync();
        let finalStatus = existingStatus;

        // Si no tiene permiso, se lo pedimos al usuario
        if (existingStatus !== 'granted') {
            const { status } = await Notifications.requestPermissionsAsync();
            finalStatus = status;
        }

        if (finalStatus !== 'granted') {
            console.log('Permiso denegado para notificaciones push.');
            return undefined;
        }

        try {
            //IMPORTANTE: Usamos getDevicePushTokenAsync() para obtener el token puro de Firebase (FCM)
            // porque tu backend usa firebase-admin, NO el servidor intermediario de Expo.
            const pushTokenData = await Notifications.getDevicePushTokenAsync();
            token = pushTokenData.data;
            console.log('Token FCM generado:', token);
        } catch (error) {
            console.error('Error generando token FCM:', error);
        }
    } else {
        console.log('Estás en un emulador o web. Usa un dispositivo físico para Push.');
    }

    return token;
}

/** Escucha la apertura de avisos de asignación, incluso al iniciar la app desde una notificación. */
export function escucharAperturaNotificacion(callback: (datos: Record<string, unknown>) => void) {
    if (Platform.OS === 'web' || !Notifications) return () => {};

    let ultimaRespuesta = '';
    const procesarRespuesta = (respuesta: any) => {
        if (!respuesta) return;
        const datos = respuesta.notification?.request?.content?.data;
        if (!datos || !datos.idSolicitud) return;
        const clave = `${respuesta.notification.request.identifier}:${respuesta.actionIdentifier}`;
        if (clave === ultimaRespuesta) return;
        ultimaRespuesta = clave;
        callback(datos);
        Notifications.clearLastNotificationResponseAsync?.().catch(() => {});
    };

    const suscripcion = Notifications.addNotificationResponseReceivedListener(procesarRespuesta);
    Notifications.getLastNotificationResponseAsync?.().then(procesarRespuesta).catch(() => {});
    return () => suscripcion.remove();
}

/** Permite mostrar dentro de la app los avisos que llegan mientras está abierta, en cualquier rol. */
export function escucharNotificacionRecibida(callback: (notificacion: { titulo: string; cuerpo: string; datos: Record<string, unknown> }) => void) {
    if (Platform.OS === 'web' || !Notifications) return () => {};

    const suscripcion = Notifications.addNotificationReceivedListener((notificacion: any) => {
        const contenido = notificacion.request?.content;
        callback({
            titulo: String(contenido?.title || 'Nueva notificación'),
            cuerpo: String(contenido?.body || ''),
            datos: contenido?.data || {},
        });
    });
    return () => suscripcion.remove();
}

export const registrarCanalNotificaciones = async () => {
    if (Platform.OS === 'android') {
        try {
            await Notifications.setNotificationChannelAsync('default', {
                name: 'default',
                importance: Notifications.AndroidImportance.MAX,
                vibrationPattern: [0, 250, 250, 250],
                lightColor: '#FF231F7C',
            });
        } catch (error) {
            // Silenciamos el error en Expo Go para que no bloquee la app
            console.warn("No se pudo configurar el canal de notificaciones en este entorno:", error);
        }
    }
};

/**
 * Dispara una notificación emergente tipo WhatsApp en el celular (Android/iOS)
 * o una notificación de escritorio en el navegador Web cuando llega una insistencia.
 */
export const dispararAlertaInsistenciaDispositivo = async (titulo: string, cuerpo: string) => {
    try {
        if (Platform.OS === 'web') {
            if (typeof window !== 'undefined' && 'Notification' in window) {
                if (window.Notification.permission === 'granted') {
                    new window.Notification(titulo, { body: cuerpo });
                } else if (window.Notification.permission !== 'denied') {
                    const perm = await window.Notification.requestPermission();
                    if (perm === 'granted') {
                        new window.Notification(titulo, { body: cuerpo });
                    }
                }
            }
            return;
        }

        if (Notifications) {
            await Notifications.scheduleNotificationAsync({
                content: {
                    title: titulo,
                    body: cuerpo,
                    sound: true,
                    priority: Notifications.AndroidNotificationPriority?.MAX,
                },
                trigger: null, // Inmediata
            });
        }
    } catch (e) {
        console.log('Aviso mostrando notificación local:', e);
    }
};
