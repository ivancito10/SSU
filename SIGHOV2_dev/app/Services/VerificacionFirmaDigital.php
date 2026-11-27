<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\Administracion\LinkServicio;

class VerificacionFirmaDigital
{
    public function comprobarServicioActivo(){
        $linkServicio = LinkServicio::where('nombre_servicio', 'VERIFICAR SERVICIO FIRMA DIGITAL')->first();
        $verServActivo = $linkServicio->link_servicio;

        $verificado = null;

        if($verServActivo != null){
            $response = Http::withoutVerifying()
                ->get($verServActivo);
            $verificado = $response->status();
        }
        
        if($verificado == 200){
            return true;
        } else {
            return false;
        }        
    }

    public function verificarDocumento($docBase64){
        $respuesta = null;
        if($this->comprobarServicioActivo()){
            if($docBase64 !== null){
                $linkServicio = LinkServicio::where('nombre_servicio', 'VALIDAR PDF FIRMA DIGITAL')->first();
                $verificarDocumento = $linkServicio->link_servicio;
                $tipo = "application/pdf";
                $data = [
                    "tipo" => $tipo,
                    "base64" => $docBase64,
                ];
    
                $response = Http::withoutVerifying()
                    ->post($verificarDocumento, $data);
                $respuesta = $response->json();
            }  
        }
        //dd($respuesta);
        return $respuesta; 
    }

}