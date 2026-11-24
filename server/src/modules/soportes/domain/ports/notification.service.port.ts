export abstract class NotificationServicePort {
  abstract enviarNotificacion(
    destino: number | number[] | string | string[],
    titulo: string,
    mensaje: string,
    dataPayload?: Record<string, string>,
  ): Promise<boolean>;

  abstract listarNotificaciones(idUsuario: number): Promise<any[]>;

  abstract marcarNotificacionLeida(idUsuario: number, idNotificacion: number): Promise<any | null>;

  abstract marcarTodasLeidas(idUsuario: number): Promise<number>;
}
