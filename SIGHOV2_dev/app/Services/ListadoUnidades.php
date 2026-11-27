<?php

namespace App\Services;

use App\Models\Administracion\LinkServicio;
use Illuminate\Support\Facades\Http;

class ListadoUnidades
{
    public function listadoUnidades()
    {
        $tokenService=new ObtenerToken();
        $tokenService->refresh_token();
        $link_servicio = LinkServicio::where('nombre_servicio', 'LISTA DE UNIDADES')->first();
        // dd($link_servicio->link_servicio, session('access_token'));
        $response = Http::withToken(session('access_token'))->get($link_servicio->link_servicio);
        // dd($response);
        //dd(session('access_token'));  
        if ($response->successful()) {
            return $response->json()['data'] ?? null;
        }
        // en caso de salir error 400 revisar a detalle la solucion
        return null;
    }
}
