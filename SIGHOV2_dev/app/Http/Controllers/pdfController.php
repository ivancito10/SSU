<?php

namespace App\Http\Controllers;

use App\Models\Aportes\Comprobante;
use App\Models\Aportes\FormaPago;
use App\Models\Aportes\AporteVoluntario;
use App\Models\Aportes\Institucion;
use App\Models\Aportes\TasaInteres;
use App\Models\Aportes\ContratoVoluntario;
use DomPDF;
use Carbon\Carbon;
use App\Helpers\NumberToWords;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use App\Mail\Aportes\PlanillasIntitucionalesLiquidadaMailable;
use App\Mail\Aportes\LiquidacionAporteVoluntarioMailable;
use Illuminate\Support\Facades\Mail;
use App\Services\QRCodeService;
use Illuminate\Support\Facades\Log;
class pdfController extends Controller
{
    //ESTA FUNCION PARA EL PDF INSTITUCIONAL
    public function generatePDF($rowId)
    {  
        $d_comprobante = Comprobante::where('id', $rowId)->with('institucionComprobante.personaRepresentanteInstitucion')->first();
        $id_per = $d_comprobante->institucionComprobante->personaRepresentanteInstitucion->id;
        // return response()->json($d_comprobante->institucionComprobante->personaRepresentanteInstitucion->id);
        // $d_comprobante=Comprobante::find($rowId);
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

        $fechaQR= $comprobante->created_at; 
        $codigos = QRCodeService::generarQR($fechaQR);
        $cod_seguridad = $codigos['cod_seguridad'];
        // dd($cod_seguridad);

        $qrCode = $codigos['qrCode'];
        $pdf = DomPDF::loadView('Aportes.pdf.comprobantePdf', compact(
            'comprobante',
            'numeroEnteroEnLetras',
            'numeroDecimalEnLetras',
            'parte_decimal',
            'qrCode'
        ))
            ->setPaper('letter', 'portrait');
        QRCodeService::registrarPDF($pdf, $cod_seguridad, $id_per, 5, 'APORTES');
        // $saludito='hola mundo';

        // Aqui GUARDA en el FTP
        // Aqui se genera el registro del pdf copia para la institucion en el ftp para l
        $pdf_copy = DomPDF::loadView('Aportes.pdf.comprobanteCopiaPdf', compact(
            'comprobante',
            'numeroEnteroEnLetras',
            'numeroDecimalEnLetras',
            'parte_decimal',
            'qrCode',
        ))->setPaper('A4', 'portrait');
           
         
        $pdfContent = $pdf_copy->output();
        $ftpPath = 'Aportes/Comprobantes/' . $d_comprobante->nro_registro_comprobante.'-'.$d_comprobante->gestion . '.pdf';
        Storage::disk('ftp')->put($ftpPath, $pdfContent);

        // mail 
        // $inst = Institucion::find($d_comprobante->id_institucion);
        // $asunto = 'Sistema Sighov2 - Unidad de Aportes';
        // $email = $inst->correo;
        // $nombre_comprobante=$d_comprobante->nro_registro_comprobante.'-'.$d_comprobante->gestion; 
        // $correo = new PlanillasIntitucionalesLiquidadaMailable($asunto, $inst, $d_comprobante->id_mes, $d_comprobante->gestion, $d_comprobante, $nombre_comprobante);
        
        // try {
            
        //     //dd($email,$correo);
        //     Mail::to($email)->send($correo);
        //     // Log de envio exitoso (opcional)
        //     Log::info('Correo enviado correctamente a: ' . $email . ' | Institución: ' . $inst->nombre); 
                     
        // } catch (\Exception $e) {
        //     // Log del error (opcional)
        //     Log::error('No se pudo enviar el correo: ' . $e->getMessage());
        // }
        

        //download // stream
        return $pdf->stream($comprobante->comprobanteFormaPago->institucionComprobante->acronimo . ' - ' . $comprobante->comprobanteFormaPago->mesComprobante->mes . ' - ' . $comprobante->comprobanteFormaPago->gestion . ' - ' . $comprobante->comprobanteFormaPago->institucionComprobante->claseAportanteInstitucion->tipo . '.pdf', [
            'Attachment' => false, // Para abrir en el navegador en lugar de descargar
            'Content-Disposition' => 'inline', // Para asegurar que se abra en una nueva pestaña
        ]);
    }

    //ESTA FUNCION PARA EL PDF VOLUNTARIO
    public function generatePDFVoluntario($rowId)
    {


        $d_comprobante = Comprobante::where('id_aporte_v', '=', $rowId)->first();
        if ($d_comprobante == null) {
            $d_comprobante = Comprobante::where('id', '=', $rowId)->first();
        }
        //$d_comprobante = Comprobante::find($rowId);
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
        //dd($comprobanteVoluntario);
        $numero = $comprobanteVoluntario->total_forma_pago;
        $numero_formateado = number_format($numero, 2, '.', '');
        $partes = explode('.', $numero_formateado);
        $parte_decimal = isset($partes[1]) ? $partes[1] : '';
        $numeroEnteroEnLetras = NumberToWords::convertir(floor($numero));
        $numeroDecimalEnLetras = NumberToWords::convertir($parte_decimal);

        $fechaActual = Carbon::now()->format('d/m/Y');

        $codigos = QRCodeService::generarQR();
        $cod_seguridad = $codigos['cod_seguridad'];
        $qrCode = $codigos['qrCode'];
        $pdf = DomPDF::loadView('Aportes.AportesVoluntarios.comprobanteVoluntarioPDF', compact('comprobanteVoluntario', 'numeroEnteroEnLetras', 'numeroDecimalEnLetras', 'parte_decimal', 'ultimaTasaInteres', 'qrCode'))
            ->setPaper('letter', 'portrait');
        // QRCodeService::registrarPDF($pdf, $cod_seguridad, $id_per, 5, 'APORTES');
        // Aqui se genera el registro del pdf copia del volunatrio  para el ftp
        $pdf_copy = DomPDF::loadView('Aportes.AportesVoluntarios.comprobanteCopyVoluntarioPDF', compact('comprobanteVoluntario', 'numeroEnteroEnLetras', 'numeroDecimalEnLetras', 'parte_decimal', 'ultimaTasaInteres', 'qrCode'))->setPaper('letter', 'portrait');
        $pdfContent = $pdf_copy->output();
        //$ftpPath = 'Aportes/Comprobantes/' . $d_comprobante->id . '.pdf';
        $ftpPath = 'Aportes/Comprobantes/' . $d_comprobante->nro_registro_comprobante.'-'.$d_comprobante->gestion . '.pdf';
        Storage::disk('ftp')->put($ftpPath, $pdfContent);
        $nombre_comprobante=$d_comprobante->nro_registro_comprobante.'-'.$d_comprobante->gestion;


        $datos_persona = ContratoVoluntario::with('personaContratoVoluntario')->find($comprobanteVoluntario->comprobanteFormaPago->aporteVComprobante->contratoVoluntarioAporteVoluntario->id);
        $id_per = $datos_persona->id_persona;
        // return response()->json($datos_persona);
        QRCodeService::registrarPDF($pdf, $cod_seguridad, $id_per, 5, 'APORTES');
       
        $asunto = 'Sistema Sighov2 - Unidad de Aportes';
        $email = $datos_persona->correo;
        $correo = new LiquidacionAporteVoluntarioMailable($asunto, $d_comprobante, $datos_persona,$nombre_comprobante);
        try {
            Mail::to($email)->send($correo);
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

    public function qrcode()
    {
        //  // Generar el código QR con estilo personalizado
        //  $qrCode = QrCode::size(120)

        //  ->generate('https://wa.me/+59173260450?text='.urlencode("Hola Seguro Social Universitario La Paz..."));

        // // Retornar la vista con el código QR
        // return view('qrcode', ['qrCode' => $qrCode]);

        // Configura tus credenciales y el número de teléfono de destino
        // Número de teléfono de destino
        // Número de teléfono en el formato correcto

        // Número de teléfono en el formato correcto (debe incluir el código del país, pero sin el '+')
        // $phoneNumber = '59177231837'; // Número sin el '+'

        // // Mensaje a enviar
        // $message = 'Hola soy el seguro social universitario';

        // // URL de la API de WhatsApp Business
        // $url = 'https://graph.facebook.com/v13.0/342450712284951/messages'; // Reemplaza YOUR_PHONE_NUMBER_ID con el ID de tu número de teléfono
        // $token = 'EAAGxnUHD4RIBO3VJLodPNdpvfZAB57H9IAFHQzcciA6oVMaGy9O9NCUzZAGfAFXDa6hswQSxBApIYYftgRJIuZBUP04XPuKKZA1RzFG8GWYyYzGR2FgFZAwZAaQWuYXCYZCcD2ei73S56ytJgOP3DLWf5B3B1gVoU9dI9yBOsMTUwPf6G7qO3DtnKQmCcm0bg2fIRKdPGwy9Jr74cIZAgecZD'; // Reemplaza con tu token de acceso

        // // Crear un cliente HTTP
        // $client = new Client();

        // try {
        //     // Enviar la solicitud
        //     $response = $client->post($url, [
        //         'headers' => [
        //             'Authorization' => 'Bearer ' . $token,
        //             'Content-Type' => 'application/json',
        //         ],
        //         'json' => [
        //             'messaging_product' => 'whatsapp',
        //             'to' => $phoneNumber,
        //             'type' => 'text',
        //             'text' => [
        //                 'body' => $message,
        //             ],
        //         ],
        //     ]);

        //     // Retornar la respuesta de la API de WhatsApp
        //     return response()->json(json_decode($response->getBody()->getContents()), 200);
        // } catch (\Exception $e) {
        //     // Manejar y retornar el error
        //     return response()->json(['error' => $e->getMessage()], 500);
        // }
        // Número de teléfono al que se enviará el mensaje
        $phoneNumber = '59178837737'; // Número sin el '+'

        // Mensaje a enviar
        $message = 'Hola soy el seguro social universitario';

        // URL de la API de WhatsApp Business para enviar mensajes
        $url = 'https://graph.facebook.com/v13.0/342450712284951/messages'; // Reemplaza YOUR_PHONE_NUMBER_ID con el ID de tu número de teléfono
        $token = 'EAAGxnUHD4RIBO3VJLodPNdpvfZAB57H9IAFHQzcciA6oVMaGy9O9NCUzZAGfAFXDa6hswQSxBApIYYftgRJIuZBUP04XPuKKZA1RzFG8GWYyYzGR2FgFZAwZAaQWuYXCYZCcD2ei73S56ytJgOP3DLWf5B3B1gVoU9dI9yBOsMTUwPf6G7qO3DtnKQmCcm0bg2fIRKdPGwy9Jr74cIZAgecZD'; // Reemplaza con tu token de acceso

        // Crear un cliente HTTP
        $client = new Client();

        try {
            // Enviar la solicitud para enviar el mensaje
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'messaging_product' => 'whatsapp',
                    'to' => $phoneNumber,
                    'type' => 'text',
                    'text' => [
                        'body' => $message,
                    ],
                ],
            ]);

            // Retornar la respuesta de la API de WhatsApp
            return response()->json(json_decode($response->getBody()->getContents()), 200);
        } catch (\Exception $e) {
            // Manejar y retornar el error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
