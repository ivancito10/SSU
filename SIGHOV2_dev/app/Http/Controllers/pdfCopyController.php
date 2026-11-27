<?php

namespace App\Http\Controllers;

use App\Models\Aportes\Comprobante;
use App\Models\Aportes\FormaPago;
use App\Models\Aportes\ContratoVoluntario;
use App\Models\Aportes\AporteVoluntario;
use App\Models\Aportes\TasaInteres;
use Illuminate\Support\Facades\Storage;
use DomPDF;
use App\Services\QRCodeService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Helpers\NumberToWords;
use App\Mail\Aportes\LiquidacionAporteVoluntarioMailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class pdfCopyController extends Controller
{
    public function generatecopiaPDF($rowId)
    {
        $comprobante = FormaPago::where('id_comprobante', $rowId)
            ->with([
                'comprobanteFormaPago',
                'comprobanteFormaPago.institucionComprobante',
                'comprobanteFormaPago.mesComprobante',
                'comprobanteFormaPago.usuarioCreador',
                'comprobanteFormaPago.usuarioCreador.personaUser',
                'comprobanteFormaPago.institucionComprobante.claseAportanteInstitucion',
                'comprobanteFormaPago.institucionComprobante.institucionResidencia',
                'comprobanteFormaPago.institucionComprobante.institucionResidencia.zonaResidencia'
            ])
            ->first();

        //dd($comprobante);
        if ($comprobante) {
            $comprobante->comprobanteFormaPago->fecha_liquidacion = Carbon::parse($comprobante->comprobanteFormaPago->fecha_liquidacion)->translatedFormat('d \d\e F \d\e Y');
            $comprobante->fecha_pago = Carbon::parse($comprobante->fecha_pago)->translatedFormat('d \d\e F \d\e Y');
            $numero = $comprobante->total_forma_pago;
        }
        $numero_formateado = number_format($numero, 2, '.', '');
        $partes = explode('.', $numero_formateado);
        $parte_decimal = isset($partes[1]) ? $partes[1] : '';
        $numeroEnteroEnLetras = NumberToWords::convertir(floor($numero));
        $numeroDecimalEnLetras = NumberToWords::convertir($parte_decimal);

        $fechaQR = $comprobante->created_at;
        $codigos = QRCodeService::generarQR($fechaQR);
        $cod_seguridad = $codigos['cod_seguridad'];
        // dd($cod_seguridad);

        $qrCode = $codigos['qrCode'];
        $pdf = DomPDF::loadView('Aportes.pdf.comprobanteCopiaPdf', compact(
            'comprobante',
            'numeroEnteroEnLetras',
            'numeroDecimalEnLetras',
            'parte_decimal',
            'qrCode'
        ))->setPaper('A4', 'portrait');
        // QRCodeService::registrarPDF($pdf, $cod_seguridad, $id_per, 5, 'APORTES');

        return $pdf->stream('COPIA' . '-' . $comprobante->comprobanteFormaPago->institucionComprobante->acronimo . ' - ' . $comprobante->comprobanteFormaPago->mesComprobante->mes . ' - ' . $comprobante->comprobanteFormaPago->gestion . ' - ' . $comprobante->comprobanteFormaPago->institucionComprobante->claseAportanteInstitucion->tipo . '.pdf', [
            'Attachment' => false,  // Para abrir en el navegador en lugar de descargar
            'Content-Disposition' => 'inline'  // Para asegurar que se abra en una nueva pestaña
        ]);
    }

    // COPIA DEL COMPROBANTE PARA VOLUNTARIOS
    public function generatecopiaPDFVoluntario($rowId)
    {
        //dd($rowId);
        $d_comprobante = Comprobante::where('id_aporte_v', '=', $rowId)->first();
        //$d_comprobante=Comprobante::find($rowId);
        $comprobanteVoluntario = FormaPago::whereHas('comprobanteFormaPago', function ($aporteV) use ($d_comprobante) {
            $aporteV->where('id', $d_comprobante->id);
        })
            ->with([
                'comprobanteFormaPago',
                'comprobanteFormaPago.aporteVComprobante',
                'comprobanteFormaPago.aporteVComprobante.contratoVoluntarioAporteVoluntario',
                'comprobanteFormaPago.mesComprobante',
                'comprobanteFormaPago.aporteVComprobante.contratoVoluntarioAporteVoluntario.claseAportanteContratoVoluntario',
                'comprobanteFormaPago.aporteVComprobante.contratoVoluntarioAporteVoluntario.personaContratoVoluntario',
                'comprobanteFormaPago.aporteVComprobante.contratoVoluntarioAporteVoluntario.residenciaContratoVoluntario',
                'comprobanteFormaPago.aporteVComprobante.contratoVoluntarioAporteVoluntario.residenciaContratoVoluntario.zonaResidencia',
                'comprobanteFormaPago.usuarioCreador.personaUser'
            ])
            ->first();
        //dd($comprobanteVoluntario);    
        //dd($comprobanteVoluntario,$d_comprobante,$rowId);

        $ultimaTasaInteres = TasaInteres::orderBy('id', 'desc')->first();
        $numero = $comprobanteVoluntario->total_forma_pago;
        $numero_formateado = number_format($numero, 2, '.', '');
        $partes = explode('.', $numero_formateado);
        $parte_decimal = isset($partes[1]) ? $partes[1] : '';
        $numeroEnteroEnLetras = NumberToWords::convertir(floor($numero));
        $numeroDecimalEnLetras = NumberToWords::convertir($parte_decimal);

        $fechaQR = $d_comprobante->created_at;
        $codigos = QRCodeService::generarQR($fechaQR);
        $cod_seguridad = $codigos['cod_seguridad'];
        $qrCode = $codigos['qrCode'];
        $pdf = DomPDF::loadView('Aportes.AportesVoluntarios.comprobanteCopyVoluntarioPDF', compact(
            'comprobanteVoluntario',
            'numeroEnteroEnLetras',
            'numeroDecimalEnLetras',
            'parte_decimal',
            'ultimaTasaInteres',
            'qrCode'
        ))
            ->setPaper('letter', 'portrait');

        // Aqui se genera el registro del pdf copia del volunatrio  para el ftp
        $pdf_copy = DomPDF::loadView('Aportes.AportesVoluntarios.comprobanteCopyVoluntarioPDF', compact(
            'comprobanteVoluntario',
            'numeroEnteroEnLetras',
            'numeroDecimalEnLetras',
            'parte_decimal',
            'ultimaTasaInteres',
            'qrCode'
        ))->setPaper('letter', 'portrait');
        $pdfContent = $pdf_copy->output();
        $ftpPath = 'Aportes/Comprobantes/' . $d_comprobante->id . '.pdf';
        Storage::disk('ftp')->put($ftpPath, $pdfContent);


        $datos_persona = ContratoVoluntario::with('personaContratoVoluntario')->find($comprobanteVoluntario->comprobanteFormaPago->aporteVComprobante->contratoVoluntarioAporteVoluntario->id);
        $asunto = 'Sistema Sighov2 - Unidad de Aportes';
        $email = $datos_persona->correo;
        //$correo = new LiquidacionAporteVoluntarioMailable($asunto, $d_comprobante, $datos_persona);
        try {
          //  Mail::to($email)->send($correo);
        } catch (\Exception $e) {
            // Log del error (opcional)
            Log::error('No se pudo enviar el correo: ' . $e->getMessage());
        }



        return $pdf->stream($comprobanteVoluntario->comprobanteFormaPago->aporteVComprobante->contratoVoluntarioAporteVoluntario->personaContratoVoluntario->p_apellido . ' ' .
            $comprobanteVoluntario->comprobanteFormaPago->aporteVComprobante->contratoVoluntarioAporteVoluntario->personaContratoVoluntario->s_apellido . ' ' .
            $comprobanteVoluntario->comprobanteFormaPago->aporteVComprobante->contratoVoluntarioAporteVoluntario->personaContratoVoluntario->nombres  . ' - ' .
            $comprobanteVoluntario->comprobanteFormaPago->mesComprobante->mes . ' - ' .
            $comprobanteVoluntario->comprobanteFormaPago->gestion . '.pdf', [
            'Attachment' => false, // Para abrir en el navegador en lugar de descargar
            'Content-Disposition' => 'inline', // Para asegurar que se abra en una nueva pestaña
        ]);
    }
}
