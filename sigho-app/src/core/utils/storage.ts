import { Platform } from 'react-native';
import * as SecureStore from 'expo-secure-store';

export const guardarItem = async (key: string, value: string) => {
    if (Platform.OS === 'web') {
        localStorage.setItem(key, value);
    } else {
        await SecureStore.setItemAsync(key, value);
    }
};

export const obtenerItem = async (key: string) => {
    if (Platform.OS === 'web') {
        return localStorage.getItem(key);
    } else {
        return await SecureStore.getItemAsync(key);
    }
};

// Añade esto al final de tu archivo storage.ts
export const eliminarItem = async (key: string) => {
    try {
        if (Platform.OS === 'web') {
            localStorage.removeItem(key);
        } else {
            await SecureStore.deleteItemAsync(key);
        }
    } catch (error) {
        console.error('Error eliminando item', error);
    }
};