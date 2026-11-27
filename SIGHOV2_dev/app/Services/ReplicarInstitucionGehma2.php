<?php

namespace App\Services;

use App\Models\Administracion\LinkServicio;
use Illuminate\Support\Facades\Http;

class ReplicarInstitucionGehma2
{

    public function replicarInstitucionGehma2($data)
    {
        $link = LinkServicio::where('nombre_servicio', 'REPLICAR INSTITUCION')->first();
        $url = $link->url;
        $respuesta = Http::post($url, $data);
        $id_user_created = auth()->user()->id;
        $replica = new RespuestaApisService();
        $origen = "APORTES - SIGHO V2";
        $replica->guardarRespuesta(
            $data,
            $respuesta,
            "SIGHO V1",
            "REPLICA INSTITUCION - GEHMA 2",
            $origen,
            $id_user_created
        );
    }
}
