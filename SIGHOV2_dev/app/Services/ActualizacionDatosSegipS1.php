<?php

namespace App\Services;

use App\Models\Administracion\LinkServicio;
use Illuminate\Support\Facades\Http;

class ActualizacionDatosSegipS1
{
    /*public function datosActualizados($datos)
    {
        dd($datos);
    }*/

    public function enviarDatosActualizados($data)
    {
        //dd($data);
        $url = LinkServicio::where('nombre_servicio', 'ACTUALIZAR DATOS AFILIADO')->first();
        $urlSigho1 = $url->link_servicio;
        $respuesta = Http::put($urlSigho1, $data);
        $id_user_created = auth()->user()->id;
        $replica = new RespuestaApisService();
        $replica->guardarRespuesta(
            $data,
            $respuesta,
            "SIGHO V1",
            "DATOS PERSONALES - SIGHO V1",
            "ACTUALIZACION DE DATOS PERSONALES - SIGHO V2",
            $id_user_created
        );
        /*$validacionExito = $respuesta->status() === 200 ? true : false;
        return $validacionExito;*/
    }
}
