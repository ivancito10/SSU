<?php

namespace App\Http\Controllers\Plataforma;

use App\Http\Controllers\Controller;
use App\Models\Plataforma\Agenda;
use App\Services\ListadoAsegurados;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FichaAgendaController extends Controller
{
    public function generarPdfFichaAgenda($idAgenda)
    {
        $datosAgenda = Agenda::where('id', $idAgenda)
            ->with([
                'personaAgenda',
                'asignacionHorarioAgenda.especialistaAsignacionHorario.persona',
                'asignacionHorarioAgenda.especialistaAsignacionHorario.especialidadEspecialista',
                'asignacionHorarioAgenda.consultorioAsignacionHorario.consultorioSedes'
            ])
            ->first();
        $datosPers = new ListadoAsegurados();
        $datosPersona = $datosPers->busquedaAfiliadoPorId($datosAgenda->personaAgenda->id);
        $codigo_inst = $datosPersona['codigo_institucional'];
        if ($codigo_inst != null) {
            $codigo_institucional = ' - ' . $codigo_inst;
        } else {
            $codigo_institucional = '';
        }
        $fechaActual = Carbon::now()->format('d/m/Y H:i:s');


        //PARA DIRIGIRSE AL PDF
        $pdf = PDF::loadView(
            'Plataforma.Agendamiento.Plataforma.pdfFichaAgenda',
            compact('datosAgenda', 'datosPersona', 'fechaActual', 'codigo_institucional')
        )
            ->setPaper('letter', 'portrait');
        $pdf->setOptions([
            'margin-top' => 0, // Estándar para imágenes
            'margin-right' => 0,
            'margin-bottom' => 0,
            'margin-left' => 0,
        ]);
        $nombre_pdf = "ficha_agenda";
        // Para abrir el pdf
        return $pdf->stream($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline'
        ]);
    }
}
