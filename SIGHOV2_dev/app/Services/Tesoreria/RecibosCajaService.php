<?php

namespace App\Services\Tesoreria;

use App\Models\Administracion\LinkServicio;
use App\Services\ObtenerToken;
use Illuminate\Support\Facades\Http;

class RecibosCajaService
{
    public function listadoRecibos()
    {
        $tokenService = new ObtenerToken();
        $tokenService->refresh_token();
        $link_servicio = LinkServicio::where('nombre_servicio', 'OBTENER RECIBOS')->first();
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

    public function reciboSeleccionado($id_recibo)
    {
        $tokenService = new ObtenerToken();
        $tokenService->refresh_token();
        $link_servicio = LinkServicio::where('nombre_servicio', 'OBTENER RECIBO SELECCIONADO')->first();
        // dd($link_servicio->link_servicio, session('access_token'));
        $response = Http::withToken(session('access_token'))->get($link_servicio->link_servicio . $id_recibo);
        // dd($response->json()['data']);
        //dd(session('access_token'));  
        if ($response->successful()) {
            return $response->json()['data'] ?? null;
        }
        // en caso de salir error 400 revisar a detalle la solucion
        return null;
    }
    public function asignarComprobante($ids_recibo, $id_comprobante)
    {
        /* 
        {
              "id_comprobante": 0,
              "ids_recibo": [
                0
              ]
            }
        */
            // dd($ids_recibo, $id_comprobante);
        $tokenService = new ObtenerToken();
        $tokenService->refresh_token();
        $link_servicio = LinkServicio::where('nombre_servicio', 'ASIGNAR COMPROBANTE')->first();
        // dd($link_servicio->link_servicio, session('access_token'));
        $response = Http::withToken(session('access_token'))->post($link_servicio->link_servicio, [
            'id_comprobante' => (int)$id_comprobante,
            'ids_recibo' => $ids_recibo
        ]);
        // dd($response->json());
        //dd(session('access_token'));  
        if ($response->successful()) {
            return $response->json()['data'] ?? null;
        }
        // en caso de salir error 400 revisar a detalle la solucion
        return null;
    }
}
