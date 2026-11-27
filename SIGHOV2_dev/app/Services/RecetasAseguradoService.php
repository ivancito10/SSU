<?php

namespace App\Services;

use App\Models\Administracion\LinkServicio;
use Illuminate\Support\Facades\Http;
use App\Services\ObtenerToken;
// use App\Models\RecetasAsegurado;

class RecetasAseguradoService
{
    public static function recetasPersona($id_persona)
    {
        $authTokenService = new ObtenerToken();
        $authTokenService->refresh_token();
        $link_servicio = LinkServicio::where('nombre_servicio', 'RECETAS PERSONA')->first();
        return  Http::withToken(session('access_token'))
            ->post($link_servicio->link_servicio, [
                'id_persona' => $id_persona
            ]);
    }
    public static function detalleRecetas($id_receta)
    {
        $authTokenService = new ObtenerToken();
        $authTokenService->refresh_token();
        $link_servicio = LinkServicio::where('nombre_servicio', 'DETALLE RECETAS PERSONA')->first();
        return  Http::withToken(session('access_token'))
            ->post($link_servicio->link_servicio, [
                'id_receta' => $id_receta
            ]);
        // return [
        //     'medicamentos' => RecetasAsegurado::all()
        // ];
    }
}
