<?php

namespace App\Services;

use App\Services\ObtenerToken;
use App\Models\Administracion\LinkServicio;
use Illuminate\Support\Facades\Http;

class BuscarOrdenImagenologia
{
    public function datosOrdenImagenologia($datosOrden)
    {
        //dd($datosOrden);
        $tokenService = new ObtenerToken();
        $tokenService->refresh_token();
        $link_servicio = LinkServicio::where('nombre_servicio', 'VERIFICAR ORDEN')->first();
        // dd($link_servicio->link_servicio, session('access_token'));
        $datos = [
            'nro_orden' => $datosOrden['orden'],
            'gestion' => $datosOrden['gestion'],
        ];
        $response = Http::withToken(session('access_token'))->post($link_servicio->link_servicio, $datos);
        //dd($response->json());
        //dd(session('access_token'));  
        if ($response->successful()) {
            return $response->json() ?? null;
        }
        // en caso de salir error 400 revisar a detalle la solucion
        return null;
    }
}
