<?php

namespace App\Http\Controllers\Aportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Administracion\Persona;
use App\Models\Aportes\AvisoBajaInstitucional;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Aportes\AvisoBajaMedica;
use App\Models\Aportes\Institucion;
use DomPDF;


class AvisoBajaController extends Controller
{
    //
    public function pdf_aviso_baja($id)
    {
        
        $aviso = AvisoBajaInstitucional::find($id);
        $id_persona = $aviso->id_persona;
        $fecha_baja =  Carbon::parse($aviso->fecha_baja)->format('d/m/Y'); 
        $motivo_baja = $aviso->motivo_baja;
        $ultimo_salario = number_format($aviso->ultimo_salario, 2, ',', '.');
        $id_institucion = $aviso->id_institucion;
        $fecha_solicitud = $aviso->fecha_solicitud;
        $nombre_persona_registra = $aviso->usuarioCreador->name;

        $persona = Persona::find($id_persona);
        if($persona){
            $nombres = $persona->nombres;
            $p_apellido = $persona->p_apellido;
            $s_apellido = $persona->s_apellido;
        
            if($persona->complemento){
                $ci = $persona->ci.'-'. $persona->complemento;
            }else{
                $ci = $persona->ci;
            }
            $matricula_seguro = $persona->matricula_seguro ?? '';
        }
        $institucion = Institucion::find($id_institucion);
        $nombre_insti = $institucion->nombre;
        $nro_empleador = $institucion->nro_empleador;

       
        $fecha_carbon = Carbon::parse($fecha_solicitud);
        $fecha_formateada = $fecha_carbon->isoFormat('DD [de] MMMM [de] YYYY');
        
        

        $pdf = DomPDF::loadView(
            'Aportes.AvisoBajaInstitucional.pdfAvisoBajaMedica',
            compact(
                'nombres','p_apellido', 's_apellido', 'ci', 'fecha_baja', 'motivo_baja', 
                'ultimo_salario', 'nombre_insti', 'nro_empleador', 'fecha_formateada', 'matricula_seguro', 'nombre_persona_registra'
            )
        )
        ->setPaper('letter', 'portrait');

        
        /*   Para guardar el pdf en ftp */
        $pdfContent = $pdf->output();
        $fileName = 'AV_' . $id . '.pdf';
        $filePath = 'Aportes/AvisoBajaInstitucional/' . $fileName;
        Storage::disk('ftp')->put($filePath, $pdfContent);


        $nombre_pdf = 'aviso_baja_medica';
        // Para descargar pdf
        return $pdf->download($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline'
        ]);
    }
}
