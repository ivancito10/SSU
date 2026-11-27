<?php

namespace App\Http\Controllers\Afiliacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DomPDF;
use App\Models\Administracion\Persona;
use App\Models\Afiliacion\Estudiante;
use Illuminate\Support\Facades\Storage;

class SsueCarnet extends Controller
{
    
    public function genrarCarnetSsue($rowId){

        $personaActual = Persona::find($rowId);
        $nombres = $personaActual->nombres;
        $p_apellido = $personaActual->p_apellido;
        $s_apellido = $personaActual->s_apellido;
        $complemento = $personaActual->complemento;
        $matricula = $personaActual->matricula_seguro;
        if ($complemento) {
            $ci = $personaActual->ci . ' - ' .  $complemento;
        } else {
            $ci = $personaActual->ci;
        }
        $estudiante = Estudiante::where('id_persona', $rowId)->where('estado', true)->first();
        $foto_estudiante = $this->obtenerImagenBase64($estudiante->foto_nombre);
         

        $id= $rowId; 
         $pdf = DomPDF::loadView(
            'Afiliacion.Ssue.ssue_carnet',
            compact(
                'foto_estudiante' ,'nombres', 'p_apellido', 's_apellido', 'ci', 'matricula'
            )
        )
        ->setPaper('letter', 'portrait'); 
        $nombre_pdf = 'ssue_cerificado_umsa';
         
        return $pdf->stream($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline'
        ]);
       
    }
 
    private function obtenerImagenBase64($filename)
    {
        try {
            if ($filename) {
                $path = 'Afiliacion/SSUE/FOTOS/' . $filename;
                $imageData = Storage::disk('ftp')->get($path);
                
                $fotito = base64_encode($imageData);
                return $fotito;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }
}
