import { Platform } from 'react-native';
import { api } from '../../../core/api/axios.config';
//import * as SecureStore from 'expo-secure-store';
import { guardarItem } from '../../../core/utils/storage';

export const login = async (usuario: string, contrasena: string) => {
    // Ajusta las llaves 'correo' y 'contrasena' según lo que espere tu AuthController en NestJS
    const response = await api.post('/auth/login', {
        username: usuario,
        password: contrasena,
        plataforma: Platform.OS === 'web' ? 'web' : 'mobile',
    }, { timeout: 15000 });

    // Extraemos el token ya sea que venga como access_token o token
    const token = response.data?.accessToken;
    const userData = response.data?.usuario;

    if (!token) {
        throw new Error('El backend no devolvió ningún token.');
    }

    // 1. Guardamos el token en la bóveda segura del celular
    await guardarItem('jwtToken', token);

    // 2. Guardamos los datos del usuario (como su id y rol) para usarlos en la Bandeja
    await guardarItem('userData', JSON.stringify(userData));

    return userData;
};
export const vincularTokenFCM = async (fcmToken: string) => {
    await api.post('/auth/registrar-token', {
        token: fcmToken,
        token_dispositivo: fcmToken,
        plataforma: Platform.OS,
    });
};
