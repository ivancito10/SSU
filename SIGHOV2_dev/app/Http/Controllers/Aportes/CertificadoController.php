<?php

namespace App\Http\Controllers\Aportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use App\Models\Aportes\Certificado;
use App\Models\Administracion\Persona;
use App\Models\Administracion\Departamento;
use App\Models\Administracion\DocumentoPDF;
use App\Services\QRCodeService;
use App\Models\User;
use DomPDF;

class CertificadoController extends Controller
{
    public function pdf_certificado($id)
    {

        $datos_certificado = Certificado::find($id);
        $nombre_insti = $datos_certificado->nombre_institucion;
        $nit = $datos_certificado->nit;
        $tipo_certificado = $datos_certificado->tipo_certificado;
        $cod_seg = $datos_certificado->cod_seguridad;
        $cod_certificado = $datos_certificado->cod_certificado;
        $fecha_rec = $datos_certificado->fecha_solicitud;
        $fecha_sol = Carbon::parse($fecha_rec)->format('d/m/Y');
        $id_per = $datos_certificado->id_persona;
        $id_usuario = $datos_certificado->id_user_created;

        $datos_persona = Persona::find($id_per);
        $nombre_persona = $datos_persona->nombres;
        $ap_paterno = $datos_persona->p_apellido;
        $ap_materno = $datos_persona->s_apellido;
        $carnet = $datos_persona->ci;
        $complemento = $datos_persona->complemento;
        $exped = $datos_persona->id_dept_exp;

        $datos_departamento = Departamento::find($exped);
        $sigla = $datos_departamento->sigla;

        $datos_usuario_registro = User::with('personaUser')->find($id_usuario);
        $datos_usuario = Persona::find($datos_usuario_registro->id_persona);
        $nombre_usuario = $datos_usuario->nombres . ' ' . $datos_usuario->p_apellido . ' ' . $datos_usuario->s_apellido;

        //$datos_usuario_modificador=User::with('personaUser')->find(auth()->user()->id);
        //

        //MENSAJE ANTIGUO DEL QR
        /*
        $mensajeQR = 'El presente corresponde al Código de Certificado: ' . $cod_certificado . '|| en Fecha solicitud: ' . $fecha_rec . ' ||, con Código de Seguridad : ' . $cod_seg . ' || || Emitido por el encargado: ' . $nombre_usuario . ' || Verificado y validado por el SSU LP - Sistema SIGHO v2.';
        $qrCode = base64_encode(QrCode::format('svg')
            ->size(80)
            ->color(3, 125, 155)
            ->backgroundColor(255, 255, 255)
            ->errorCorrection('H')
            ->generate($mensajeQR));
        */
        $fecha_registro = Carbon::now()->format('d-m-Y');
        $fecha = Carbon::now()->translatedFormat('d \d\e F \d\e Y');
        //MENSAJE NUEVO DEL QR   
        $codigos = QRCodeService::generarQR();
        $cod_seguridad = $codigos['cod_seguridad'];
        $qrCode = $codigos['qrCode'];
        // Para abrir el blade vista del pdf con variables ya definidas
        $pdf = DomPDF::loadView(
            'Aportes.Institucion.Certificados.pdfCertificado',
            compact(
                'fecha',
                'nombre_insti',
                'nit',
                'fecha_sol',
                'nombre_persona',
                'ap_paterno',
                'ap_materno',
                'carnet',
                'complemento',
                'cod_seg',
                'tipo_certificado',
                'sigla',
                'nombre_usuario',
                'cod_certificado',
                'qrCode',
                'fecha_registro'
            )
        )
            ->setPaper('letter', 'portrait');

        //logica para enviar documento a tabla tipo Documento
        $tipoDoc = null;
        if ($tipo_certificado) {
            $tipoDoc = 3;
        } else {
            $tipoDoc = 4;
        }
        QRCodeService::registrarPDF($pdf, $cod_seguridad, $id_per, $tipoDoc, 'APORTES');
        //dd($documentoPdf);


        //Guardar el pdf en el ftp
        //$pdfContent = $pdf->output();
        //$ftpPath = 'Administracion/Usuarios/Credenciales/' . $datos_usuario_registro->personaUser->nombres.'.pdf';
        //   Storage::disk('ftp')->put($ftpPath, $pdfContent);

        /*  Para guardar el pdf en ftp */
        $pdfContent = $pdf->output();
        $fileName = 'CE_' . $id . '_' . $cod_certificado . '.pdf';
        $filePath = 'Aportes/Certificados/' . $fileName;
        Storage::disk('ftp')->put($filePath, $pdfContent);


        $nombre_pdf = 'pdf_certificado';
        // Para descargar pdf
        return $pdf->stream($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline'
        ]);
    }

    /* aqui noooo */
    public function pdf_certificado_vista($id)
    {

        $datos_certificado = Certificado::find($id);
        $nombre_insti = $datos_certificado->nombre_institucion;
        $nit = $datos_certificado->nit;
        $tipo_certificado = $datos_certificado->tipo_certificado;
        $cod_seg = $datos_certificado->cod_seguridad;
        $cod_certificado = $datos_certificado->cod_certificado;
        $fecha_rec = $datos_certificado->fecha_solicitud;
        $fecha_sol = Carbon::parse($fecha_rec)->format('d/m/Y');
        $id_per = $datos_certificado->id_persona;
        $id_usuario = $datos_certificado->id_user_created;

        $datos_persona = Persona::find($id_per);
        $nombre_persona = $datos_persona->nombres;
        $ap_paterno = $datos_persona->p_apellido;
        $ap_materno = $datos_persona->s_apellido;
        $carnet = $datos_persona->ci;
        $complemento = $datos_persona->complemento;
        $exped = $datos_persona->id_dept_exp;

        $datos_departamento = Departamento::find($exped);
        $sigla = $datos_departamento->sigla;

        $datos_usuario_registro = User::with('personaUser')->find($id_usuario);
        $datos_usuario = Persona::find($datos_usuario_registro->id_persona);
        $nombre_usuario = $datos_usuario->nombres . ' ' . $datos_usuario->p_apellido . ' ' . $datos_usuario->s_apellido;

        //$datos_usuario_modificador=User::with('personaUser')->find(auth()->user()->id);
        //
        $mensajeQR = 'El presente corresponde al Código de Certificado: ' . $cod_certificado . '|| en Fecha solicitud: ' . $fecha_rec . ' ||, con Código de Seguridad : ' . $cod_seg . ' || || Emitido por el encargado: ' . $nombre_usuario . ' || Verificado y validado por el SSU LP - Sistema SIGHO v2.';
        $qrCode = base64_encode(QrCode::format('svg')
            ->size(80)
            ->color(3, 125, 155)
            ->backgroundColor(255, 255, 255)
            ->errorCorrection('H')
            ->generate($mensajeQR));

        $fecha = Carbon::now()->translatedFormat('d \d\e F \d\e Y');

        // fecha registro
        $fecha_registro = Carbon::now()->translatedFormat('d \d\e F \d\e Y H:i:s');

        // Para abrir el blade vista del pdf con variables ya definidas
        $pdf = DomPDF::loadView(
            'Aportes.institucion.certificados.pdfCertificado',
            compact(
                'fecha',
                'nombre_insti',
                'nit',
                'fecha_sol',
                'nombre_persona',
                'ap_paterno',
                'ap_materno',
                'carnet',
                'complemento',
                'cod_seg',
                'tipo_certificado',
                'sigla',
                'nombre_usuario',
                'cod_certificado',
                'qrCode',
                'fecha_registro'
            )
        )
            ->setPaper('letter', 'portrait');



        $nombre_pdf = 'pdf_certificado';
        // Para abrir pdf
        return $pdf->stream($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline'
        ]);
    }
}
