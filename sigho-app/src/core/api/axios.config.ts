import axios from 'axios';
import { Platform } from 'react-native';
import { obtenerItem, eliminarItem } from '../utils/storage';

// Si existe una variable de entorno configurada (para producción), la usa; 
// si no, usa la IP de desarrollo local:
const API_URL_PROD = process.env.EXPO_PUBLIC_API_URL;
const IP_LOCAL_DEV = '192.168.2.151';

const obtenerBaseUrl = () => {
    // 1. Si hay URL de producción (Android, iOS, Web o .exe), tiene prioridad:
    if (API_URL_PROD) {
        return API_URL_PROD;
    }
    // 2. Desarrollo local en Web:
    if (Platform.OS === 'web' && typeof window !== 'undefined' && window.location?.hostname) {
        return `http://${window.location.hostname}:3000/api`;
    }
    // 3. Desarrollo local en móvil:
    return `http://${IP_LOCAL_DEV}:3000/api`;
};

export const api = axios.create({
    baseURL: obtenerBaseUrl(),
    headers: {
        'Content-Type': 'application/json',
    },
});

let manejadorSesionExpirada: (() => void) | null = null;
let notificandoExpiracion = false;

export const registrarManejadorSesionExpirada = (cb: (() => void) | null) => {
    manejadorSesionExpirada = cb;
};

const decodificarBase64 = (b64: string): string => {
    if (typeof atob === 'function') return atob(b64);
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
    let output = '';
    for (let i = 0; i < b64.length; i += 4) {
        const c1 = chars.indexOf(b64[i]);
        const c2 = chars.indexOf(b64[i + 1]);
        const c3 = chars.indexOf(b64[i + 2]);
        const c4 = chars.indexOf(b64[i + 3]);
        output += String.fromCharCode((c1 << 2) | (c2 >> 4));
        if (c3 !== 64 && c3 !== -1) output += String.fromCharCode(((c2 & 15) << 4) | (c3 >> 2));
        if (c4 !== 64 && c4 !== -1) output += String.fromCharCode(((c3 & 3) << 6) | c4);
    }
    return output;
};

export const tokenJwtExpirado = (token?: string | null): boolean => {
    if (!token) return true;
    try {
        const partes = token.split('.');
        if (partes.length < 2) return false;
        let base64 = partes[1].replace(/-/g, '+').replace(/_/g, '/');
        while (base64.length % 4 !== 0) base64 += '=';
        const jsonStr = decodificarBase64(base64);
        const payload = JSON.parse(jsonStr);
        if (typeof payload?.exp !== 'number') return false;
        return payload.exp * 1000 <= Date.now();
    } catch {
        return false;
    }
};

// Interceptor de peticiones (Se ejecuta ANTES de que la petición salga hacia NestJS)
api.interceptors.request.use(
    async (config) => {
        // 1. Buscamos el token guardado en el dispositivo
        const token = await obtenerItem('jwtToken');

        // 2. Si existe, lo inyectamos en la cabecera Authorization
        if (token && config.headers) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Interceptor de respuestas: si el token venció (401), cerramos la sesión automáticamente
api.interceptors.response.use(
    (response) => response,
    async (error) => {
        const status = error?.response?.status;
        const url = String(error?.config?.url || '');
        const esLogin = url.includes('/auth/login');

        if (status === 401 && !esLogin && !notificandoExpiracion) {
            notificandoExpiracion = true;
            try {
                await eliminarItem('jwtToken');
                await eliminarItem('userData');
                if (manejadorSesionExpirada) {
                    manejadorSesionExpirada();
                }
            } finally {
                setTimeout(() => {
                    notificandoExpiracion = false;
                }, 1500);
            }
        }
        return Promise.reject(error);
    }
);