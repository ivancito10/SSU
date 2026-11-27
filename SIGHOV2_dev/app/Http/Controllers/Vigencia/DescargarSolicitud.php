<?php

namespace App\Http\Controllers\Vigencia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class DescargarSolicitud extends Controller
{
    public function descargarArchivo($nombrePdf, $codCite, $gestionCreado){
        $path = 'Vigencia/DOCUMENTOS/SOLICITUDES/'.$nombrePdf;
        $name = 'SOLICITUD_PRESTACION_MEDICA_No_'.$codCite.'_'.$gestionCreado.'.pdf';

        if (!Storage::disk('ftp')->exists($path) || $nombrePdf === null) {
            abort(404, 'Archivo no encontrado.');
        }

        return Storage::disk('ftp')->download($path, $name);
    }
}
