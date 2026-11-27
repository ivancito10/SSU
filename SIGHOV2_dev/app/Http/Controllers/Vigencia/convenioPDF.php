<?php

namespace App\Http\Controllers\Vigencia;

use App\Http\Controllers\Controller;
use App\Models\Administracion\Persona;
use App\Models\Administracion\PersonalSsu;
use App\Models\Vigencia\AutorizacionInterior;
use App\Models\Vigencia\AutorizacionIntEspecialidad;
use App\Models\Vigencia\AutorizacionIntServicio;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class convenioPDF extends Controller
{
    //
    public function pdf_convenio($id)
    {
        // return response()->json($id,200);
        // resources/views/Vigencia/Convenios/Interior/convenioPDF.blade.php

        $datosConvenio = AutorizacionInterior::where('id', $id)
            ->with([
                'seguroInteriorAutorizacionInterior.personaSeguroInterior',
                'personaInteriorAutorizacionInterior',
                'seguroInteriorAutorizacionInterior.residenciaSeguroInterior'
            ])
            ->first();
        
        $diasVigencia = Carbon::parse($datosConvenio->fecha_fin)->diffInDays(Carbon::parse($datosConvenio->fecha_inicio)) + 1;

        $datosGerente = PersonalSsu::where('cargo', 'GERENTE GENERAL')
            ->with('personaPersonalSsu')
            ->first();


        $especialidades = 'AUTORIZAN ATENCIÓN MÉDICA EN ';

        $datosEsp = AutorizacionIntEspecialidad::where('id_autorizacion_interior', $id)->with(['especialidadAutorizacionIntEspecialidad'])->get();
        if ($datosEsp->count() > 0) {
            if ($datosEsp->count() == 1) {
                $especialidades .= $datosEsp[0]->especialidadAutorizacionIntEspecialidad->especialidad . '.';
            } else {
                foreach ($datosEsp as $key => $datoEsp) {
                    if ($key == $datosEsp->count() - 1) {
                        $especialidades .= 'y ' . $datoEsp->especialidadAutorizacionIntEspecialidad->especialidad . '.';
                    } elseif ($key == $datosEsp->count() - 2) {
                        $especialidades .= $datoEsp->especialidadAutorizacionIntEspecialidad->especialidad . ' ';
                    } else {
                        $especialidades .= $datoEsp->especialidadAutorizacionIntEspecialidad->especialidad . ', ';
                    }
                }
            }
        } else {
            $especialidades = '';
        }

        $servicios = ' ADEMÁS DE ';

        $datosServ = AutorizacionIntServicio::where('id_autorizacion_interior', $id)->with(['servicioAutorizacionIntServicio'])->get();
        if ($datosServ->count() > 0) {
            if ($datosServ->count() == 1) {
                $servicios .= $datosServ[0]->servicioAutorizacionIntServicio->servicio . ' SI SE REQUIERE.';
            } else {
                foreach ($datosServ as $key => $datoServ) {
                    if ($key == $datosServ->count() - 1) {
                        $servicios .= 'y ' . $datoServ->servicioAutorizacionIntServicio->servicio . ' SI SE REQUIERE.';
                    } elseif ($key == $datosServ->count() - 2) {
                        $servicios .= $datoServ->servicioAutorizacionIntServicio->servicio . ' ';
                    } else {
                        $servicios .= $datoServ->servicioAutorizacionIntServicio->servicio . ', ';
                    }
                }
            }
        } else {
            $servicios = '';
        }



        $liname = 'TAMBIÉN MEDICAMENTOS SEGÚN LA ';

        if ($datosConvenio->medicamentos_liname && !$datosConvenio->medicamentos_extraliname) {
            $liname .= 'LINAME.';
        } elseif (!$datosConvenio->medicamentos_liname && $datosConvenio->medicamentos_extraliname) {
            $liname .= 'EXTRALINAME.';
        } elseif ($datosConvenio->medicamentos_liname && $datosConvenio->medicamentos_extraliname) {
            $liname .= 'LINAME Y EXTRALINAME.';
        } else {
            $liname = '';
        }

        $otrasLimitacionesTexto = $datosConvenio->otras_limitaciones;
        if($otrasLimitacionesTexto == '<p></p>'){
            $otrasLimitacionesTexto = null;
        }
        $observacionesTexto = $datosConvenio->observacion;
        if($observacionesTexto == '<p></p>'){
            $observacionesTexto = null;
        }

        if ($otrasLimitacionesTexto != ''  && $observacionesTexto == '') {
            $textoFinal = $otrasLimitacionesTexto . '.';
        } elseif ($otrasLimitacionesTexto == '' && $observacionesTexto != '') {
            $textoFinal = $observacionesTexto . '.';
        } elseif ($otrasLimitacionesTexto != '' && $observacionesTexto != '') {
            $textoFinal = $otrasLimitacionesTexto . '. ' . $observacionesTexto . '.';
        } else {
            $textoFinal = '';
        }

        $fecha_registro = Carbon::now()->format('d-m-Y');
        $nombre_usuario = auth()->user()->name;
        $vista_final = view('Vigencia.Convenios.Interior.convenioPDF', compact(
            'id',
            'fecha_registro',
            'nombre_usuario',
            'datosConvenio',
            'datosGerente',
            'diasVigencia',
            'especialidades',
            'servicios',
            'liname',
            'otrasLimitacionesTexto',
            'observacionesTexto'
        ));
        $pdf = Pdf::loadHTML($vista_final); // Las dimensiones deben estar en puntos (1 cm = 28.35 puntos)
        $pdf->setPaper('letter', 'portrait'); // Las dimensiones deben estar en puntos (1 cm = 28.35 puntos)
        return $pdf->stream('pdf_convenio_interior.pdf');
    }
}
