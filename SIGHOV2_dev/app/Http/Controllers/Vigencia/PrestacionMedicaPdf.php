<?php

namespace App\Http\Controllers\Vigencia;

use App\Models\Vigencia\SolicitudPMedica;
use App\Models\Vigencia\AseguradoSolicitante;
use App\Models\Vigencia\SolMedicaEspecialidad;
use App\Models\Vigencia\SolMedicaServicio;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\QRCodeService;
use DomPDF;


class PrestacionMedicaPdf extends Controller
{
    public function prestracion_medica_pdf($id_prestacion)
    {
        $hoy = Carbon::now()->format('d/m/Y');
        $año = Carbon::now()->format('Y');

        $id = $id_prestacion;

        $solicitantes = AseguradoSolicitante::where('id_solicitud_p_medica', $id_prestacion)
            ->with(['solicitudPMedicaAseguradoSolicitante'])
            ->with(['personaAseguradoSolicitante', 'personaAseguradoSolicitante.tipoAseguradoPersona'])
            ->with(['personaAseguradoTitular'])
            ->orderBy('id', 'asc')
            ->get();

        //PEQUEÑA LOGICA PARA SABER SI EN LA SOLICITUD EXISTE O NO UN TITULAR
        $titularSwitch = 0;
        foreach ($solicitantes as $key => $existeTitular) {
            if ($existeTitular->id_asegurado_solicitante == $existeTitular->id_asegurado_titular) {

                $titularSwitch = $titularSwitch + 1;
            }
        }

        $solicitud = SolicitudPMedica::where('id', $id_prestacion)
            ->with(['seguroInteriorSolicitudPMedica'])
            ->with(['usuarioCreador', 'usuarioCreador.personaUser'])
            ->first();

        $diasValidez = (Carbon::parse($solicitud->fecha_fin)->diffInDays(Carbon::parse($solicitud->fecha_inicio))) + 1;

        $añoCreated =  Carbon::parse($solicitud->created_at)->format('Y');

        $especialidades = SolMedicaEspecialidad::where('id_solicitud_p_medica', $id_prestacion)
            ->with(['especialidadSolMedicaEspecialidad'])
            ->get();

        $servicios = SolMedicaServicio::where('id_solicitud_p_medica', $id_prestacion)
            ->with(['servicioSolMedicaServicio'])
            ->get();

        // Para abrir el blade vista del pdf con variables ya definidas
        $codigos = QRCodeService::generarQR();
        $cod_seguridad = $codigos['cod_seguridad'];
        $qrCode = $codigos['qrCode'];

        $pdf = DomPDF::loadView
(
            'Vigencia.SolicitudesPrestacionesMedicas.solicitudPrestacionMedicaPdf',
            compact(
                'id_prestacion',
                'solicitud',
                'solicitantes',
                'especialidades',
                'servicios',
                'hoy',
                'año',
                'añoCreated',
                'titularSwitch',
                'diasValidez',
                'qrCode'
            )
        )
            ->setPaper('letter', 'portrait');

        $id_persona = $solicitantes->first()->id_asegurado_solicitante;
        QRCodeService::registrarPDF($pdf, $cod_seguridad, $id_persona, 12, 'VIGENCIA DE DERECHOS');

        $nombre_pdf = 'SOLICITUD_PRESTACION_MEDICA_No_' . $solicitud->cod_cite . '_' . $añoCreated;
        // Para abrir pdf stream
        return $pdf->download($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline'
        ]);
    }
}
