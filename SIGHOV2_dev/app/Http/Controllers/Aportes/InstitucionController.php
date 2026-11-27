<?php

namespace App\Http\Controllers\Aportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aportes\Institucion;
use App\Models\Aportes\AporteInstitucional;
use App\Models\Aportes\Mes;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReporteAfiliadosPorInstitucion;

class InstitucionController extends Controller
{
    public function showRegisterForm()
    {
        //dd("mesnajeit");  esto revisart 
        return view('livewire.aportes.institucion.institucion-register');
    }

    public function exportarExcelAfiliadosInstitucion($id_institucion, $id_mes, $gestion)
    {
        set_time_limit(130000);
        // dd("mesnajeit", $id_institucion, $id_mes, $gestion);
        /*  $id_institucion = 4;
        $id_mes = 1;
        $gestion = 2025; */
        /* $aportantesInstitucionales = AporteInstitucional::join('administracion.persona as p', 'p.id', '=', 'aportes.aporte_i.id_persona')
            ->where('p.afiliado', true)
            ->where('id_institucion', $id_institucion)
            ->where('id_mes', $id_mes)
            ->where('gestion', $gestion)
            ->get(); */

        $aportantesInstitucionales = AporteInstitucional::with('personaAporteInstitucional')
            // ->whereHas('personaAporteInstitucional', function ($query) {
            //     $query->where('afiliado', true);
            // })
            ->where('id_institucion', $id_institucion)
            ->where('id_mes', $id_mes)
            ->where('gestion', $gestion)
            ->get()
            ->sortBy(function ($aporte) {
                return $aporte->personaAporteInstitucional->p_apellido;
            })
            ->values();

        //dd('hola', $aportantesInstitucionales);
        $data = $aportantesInstitucionales->map(function ($aporte, $index) {
            $tipoAporte = 'PLANILLA MENSUAL';

            if ($aporte->beneficio_social) {
                $tipoAporte = 'BENEFICIO SOCIAL';
            } elseif ($aporte->retoactivo) {
                $tipoAporte = 'RETROACTIVO';
            } elseif ($aporte->planilla_adicional) {
                $tipoAporte = 'PLANILLA ADICIONAL';
            } elseif ($aporte->reintegro_incr_salaria) {
                $tipoAporte = 'REINTEGRO INCREMENTO SALARIAL';
            }

            return [
                'nro' => $index + 1,
                'p_apellido' => $aporte->personaAporteInstitucional->p_apellido,
                's_apellido' => $aporte->personaAporteInstitucional->s_apellido,
                'nombre' => $aporte->personaAporteInstitucional->nombres,
                'ci' => $aporte->personaAporteInstitucional->ci,
                'complemento' => $aporte->personaAporteInstitucional->complemento,
                'matricula_seguro' => $aporte->personaAporteInstitucional->matricula_seguro,
                'total_ganado' => $aporte->total_ganado,
                'total_aporte' => $aporte->total_aporte,
                'tipo_aporte' => $tipoAporte,
                'afiliado' => $aporte->personaAporteInstitucional->afiliado ? 'AFILIADO' : 'NO AFILIADO',

            ];
        })->toArray();

        $datosInstitucion = Institucion::find($id_institucion);
        $mes_encontrado = Mes::find($id_mes);
        $mes = $mes_encontrado->mes;
        // dd($datosInstitucion);
        // dd($data);
        $filename = 'DETALLE APORTANTES' . ' - ' . $datosInstitucion->nombre . ' - ' . $id_mes . '-' . $gestion  . '.xlsx';
        //dd($filename);
        return Excel::download(new ReporteAfiliadosPorInstitucion($data, $datosInstitucion, $mes, $gestion), $filename);
    }
}
