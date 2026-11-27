<?php

namespace App\Http\Controllers\Aportes;

use App\Http\Controllers\Controller;
use App\Models\Aportes\AporteInstitucional;
use App\Models\Aportes\Comprobante;
use DomPDF;
use SnappyPDF;

class planillaExternosPdf extends Controller
{
    public function generarPlanillasPdf($id)
    {
        $count = 0;
        ini_set('memory_limit', '10024M');
        ini_set('max_execution_time', 12000);

        $comprobante = Comprobante::find($id);
        // $datos = AporteInstitucional::with('personaAporteInstitucional', 'institucionAporteInstitucional', 'mesAporteInstitucional')
        //     ->where('id_comp', $id)
        //     ->get();
        // dd($datos[0]);

        // $datos = AporteInstitucional::with('personaAporteInstitucional', 'institucionAporteInstitucional', 'mesAporteInstitucional')
        // ->where('id_comp', $id)
        // ->get()
        // ->sortBy(function($aporte) {
        //     return $aporte->personaAporteInstitucional->p_apellido;
        // });

        $tablaAporte = (new AporteInstitucional)->getTable();

        $datos = AporteInstitucional::with(['personaAporteInstitucional', 'institucionAporteInstitucional', 'mesAporteInstitucional'])
            ->join('administracion.persona', "$tablaAporte.id_persona", '=', 'administracion.persona.id')
            ->where("$tablaAporte.id_comp", $id)
            ->orderByRaw('administracion.persona.p_apellido IS  NOT NULL ASC')
            ->orderBy('administracion.persona.p_apellido', 'asc')
            ->orderBy('administracion.persona.s_apellido', 'asc')
            ->orderBy('administracion.persona.nombres', 'asc')
            ->select("$tablaAporte.*")
            ->get();

        $numeroDeArrays = count($datos);
        //dd($numeroDeArrays,  $comprobante);
        $tipoAporte =
        $comprobante->reintegro ? 'REINTEGRO' :
        ($comprobante->beneficio_social ? 'BENEFICIO SOCIAL' :
        ($comprobante->planilla_adicional ? 'PLANILLA ADICIONAL' :
        ($comprobante->retroactivo ? 'RETROACTIVO' :
        'PATRONAL')));

        // $total_ganado_formatted=number_format($comp, 2, '.', '');
        // $pdf_planilla = DomPDF::loadView('Aportes.pdf.planillaPdfExternos', compact('datos', 'comprobante', 'tipoAporte', 'count'))->setPaper('A4', 'landscape');
        // return $pdf_planilla->stream('ASUSS' . '.pdf', [
        //     'Attachment' => false,
        //     'Content-Disposition' => 'inline'
        // ]);

        $pdf = SnappyPdf::loadView('Aportes.pdf.planillaPdfExternos', compact('datos', 'comprobante', 'tipoAporte', 'count'))
            ->setPaper('legal', 'landscape')
            ->setOption('footer-html', view('Aportes.pdf.footer')->render())
            //  ->setOption('footer-html', url('/pdf-footer'))
            ->setOption('footer-spacing', 2);

        return $pdf->stream('Planilla_'.$comprobante->institucionComprobante->acronimo.'_'.$comprobante->mesComprobante->mes.'_'.$comprobante->gestion.'_'.$tipoAporte.'.pdf');
    }
}
