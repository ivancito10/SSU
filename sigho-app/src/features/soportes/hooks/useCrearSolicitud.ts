// src/features/soportes/hooks/useCrearSolicitud.ts
import { useState } from 'react';
import axios from 'axios';
import { AppAlert } from '../../../core/components/AlertaGlobalModal';
import { crearNuevaSolicitud, subirAdjuntoSolicitud } from '../api/soporte.service';
import { CrearSolicitudPayload } from '../types/soporte.types';

export const useCrearSolicitud = () => {
    const [isLoading, setIsLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);

    // La función que el botón de "Enviar" ejecutará
    const enviarSolicitud = async (payload: CrearSolicitudPayload, onSuccess?: (respuesta?: any) => void | Promise<void>, adjuntoUri?: string | null) => {
        setIsLoading(true);
        setError(null);

        try {
            // 1. Intentamos enviar los datos a NestJS
            const respuesta = await crearNuevaSolicitud(payload);

            if (adjuntoUri) {
                const solicitud = respuesta?.data ?? respuesta?.datos ?? respuesta;
                const idSolicitud = Number(solicitud?.idSolicitud ?? solicitud?.id);
                if (!idSolicitud) throw new Error('La solicitud se creó, pero no se recibió su identificador para adjuntar la imagen.');
                try {
                    await subirAdjuntoSolicitud(idSolicitud, adjuntoUri);
                } catch (error: any) {
                    const mensaje = error?.response?.data?.message || 'La solicitud se creó, pero no se pudo subir la imagen.';
                    AppAlert.alert('Adjunto no cargado', mensaje);
                }
            }

            // 2. Si todo sale bien, ejecutamos la acción de éxito (ej. volver a la bandeja)
            if (onSuccess) {
                await onSuccess(respuesta);
            }

            return respuesta;

        } catch (err: unknown) {
            // 3. Capturamos los errores de red (como el Network Error o el Error 500)
            let mensajeError = 'Ocurrió un error inesperado al enviar la solicitud.';

            if (axios.isAxiosError(err)) {
                // Leemos el mensaje exacto que nos envía NestJS, o un error de red genérico
                mensajeError = err.response?.data?.message || err.message || 'Error de conexión con el servidor.';
            } else if (err instanceof Error) {
                mensajeError = err.message;
            }

            setError(mensajeError);

            // Lanzamos alerta UI/UX institucional compatible con Web y Móvil
            AppAlert.alert('Error al solicitar soporte', mensajeError);

            return null;
        } finally {
            // 4. Pase lo que pase (éxito o error), apagamos el estado de carga
            setIsLoading(false);
        }
    };

    // El hook devuelve la función ejecutora y las variables de estado
    return {
        enviarSolicitud,
        isLoading,
        error,
    };
};
