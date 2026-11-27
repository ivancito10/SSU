<?php

namespace App\Http\Controllers\Vigencia;

use App\Http\Controllers\Controller;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Aportes\Institucion;
use App\Models\Vigencia\Preafiliacion;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\QRCodeService;
use Carbon\Carbon;

class PreafiliacionPdf extends Controller
{
    public function preafiliacionPdf($idPreafiliacion)
    {
        $nombreTitular = '';
        $matriculaTitular = '';
        $año = Carbon::now()->format('Y');
        $hoy = Carbon::now()->format('d/m/Y');
        $preafiliacion = Preafiliacion::with([
            'personaPreafiliacion',
            'titularPreafiliacion.titularPersona',
            'institucionPreafiliacion',
            'usuarioCreador'
        ])
            ->find($idPreafiliacion);

        $nroPreafiliacion = 'PRE/' . $preafiliacion->nro_preafiliacion . '/'. Carbon::now()->format('Y');
        $fechaSolicitud = Carbon::parse($preafiliacion->fecha_inicio)->format('d/m/Y');
        $nombreCompleto = implode(' ', array_filter([
            $preafiliacion->personaPreafiliacion->p_apellido,
            $preafiliacion->personaPreafiliacion->s_apellido,
            $preafiliacion->personaPreafiliacion->nombres
        ]));
        $carnet = implode(' - ', array_filter([
            $preafiliacion->personaPreafiliacion->ci,
            $preafiliacion->personaPreafiliacion->complemento,
        ]));
        $matricula = $preafiliacion->personaPreafiliacion->matricula_seguro;
        $edad = Carbon::parse($preafiliacion->personaPreafiliacion->fecha_nacimiento)->age;
        $fechaNacimiento = Carbon::parse($preafiliacion->personaPreafiliacion->fecha_nacimiento)->format('d/m/Y');
        $tipoPreafiliacion = $preafiliacion->tipo_preafiliacion;
        $fechaFin = Carbon::parse($preafiliacion->fecha_fin)->format('d/m/Y');

        $institucion = 'APORTANTE VOLUNTARIO';

        if($preafiliacion->id_institucion !== null) {
            $nombreInst = Institucion::find($preafiliacion->id_institucion);
            $institucion = $nombreInst->nombre;
        }

        if ($preafiliacion->id_titular !== null) {
            $nombreTitular = implode(' ', array_filter([
                $preafiliacion->titularPreafiliacion->titularPersona->p_apellido,
                $preafiliacion->titularPreafiliacion->titularPersona->s_apellido,
                $preafiliacion->titularPreafiliacion->titularPersona->nombres
            ]));

            $matriculaTitular = $preafiliacion->titularPreafiliacion->titularPersona->matricula_seguro;

            if ($preafiliacion->titularPreafiliacion->tipo_titular) {
                $institucionTitular = TitularInstitucion::with(['titularInstitucionConInstitucion.codigosInstitucion'])
                    ->where('id_titular', $preafiliacion->id_titular)
                    ->where('estado', true)
                    ->orderBy('id', 'asc')
                    ->first();

                if ($institucionTitular->titularInstitucionConInstitucion->codigosInstitucion) {
                    $matriculaTitular = $matriculaTitular . ' - ' . $institucionTitular->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                        $institucionTitular->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular;
                }

                $institucion = $institucionTitular->titularInstitucionConInstitucion->nombre;
            }
        }

        $obs = true;
        if(trim(strip_tags($preafiliacion->observaciones)) === ""){
            $obs = false;
        }

        $observaciones = $preafiliacion->observaciones;

        $fechaHoraCreacion = Carbon::parse($preafiliacion->created_at)->format('d/m/Y H:i:s');
        $usuarioCreacion = $preafiliacion->usuarioCreador->name;

        //CODIGO QR

        $codigos = QRCodeService::generarQR();
        
        $cod_seguridad = $codigos['cod_seguridad'];
        $qrCode = $codigos['qrCode'];

        //FIN CODIGO QR

        $pdf = PDF::loadView(
            'Vigencia.Preafiliacion.pdfPreafiliacion',
            compact(
                'qrCode',
                'año',
                'hoy',
                'nroPreafiliacion',
                'fechaSolicitud',
                'fechaFin',
                'nombreCompleto',
                'carnet',
                'matricula',
                'edad',
                'tipoPreafiliacion',
                'institucion',
                'nombreTitular',
                'obs',
                'fechaNacimiento',
                'matriculaTitular',
                'fechaHoraCreacion',
                'observaciones',
                'usuarioCreacion'               
            )
        )
            ->setPaper('letter', 'portrait');

        QRCodeService::registrarPDF($pdf, $cod_seguridad, $preafiliacion->id_persona, 13, 'VIGENCIA DE DERECHOS');


        $nombre_pdf = 'documento_preafiliacion';
        // Para abrir pdf stream
        return $pdf->stream($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline'
        ]);
    }
}
