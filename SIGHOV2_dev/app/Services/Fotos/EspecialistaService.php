<?php

namespace App\Services\Fotos;

use App\Models\Plataforma\Especialista;
use App\Services\Fotos\ImagenService;
use Illuminate\Support\Facades\URL;

class EspecialistaService
{
    protected $imagenService;

    public function __construct(ImagenService $imagenService)
    {
        $this->imagenService = $imagenService;
    }
    public function obtener_imagen_especialista($id_especialista)
    {
        $especialista = Especialista::find($id_especialista);
        if (!$especialista) {
            abort(404, 'Especialista no encontrado');
        }
        $nombre_imagen = $especialista->imagen;
        $direccion_foto = 'Plataforma/Especialista/Fotos/';
        return $this->imagenService->obtener_imagen($nombre_imagen, $direccion_foto);
    }
    public function url_imagen_especialista($id_especialista)
    {
        $imagenUrl = URL::temporarySignedRoute(
            'obtener_imagen_especialista',        // Nombre de la ruta que devuelve la imagen
            now()->addMinutes(90),     // Duración de la validez (10 minutos aquí)
            ['id_especialista' => $id_especialista]   // Parámetros que necesita la ruta
        );
        return $imagenUrl;
        // return url('/api/administracion/obtener_imagen_usuario/' . $id_user);
    }
}