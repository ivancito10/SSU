<?php

namespace App\Http\Controllers\Aportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aportes\PlanillaBajaMedica;
use App\Models\Aportes\Comprobante;
use App\Models\Aportes\Institucion;
use App\Models\Aportes\Mes;
use App\Services\QRCodeService;
use App\Models\User;
use DomPDF;
use Illuminate\Support\Facades\Storage;

class BajasMedicasController extends Controller
{
    //
    public function PDfBajasMedicas($id)
    {
        $datos_comprobante = Comprobante::find($id);
        $datos_institucion = Institucion::find($datos_comprobante->id_institucion);
        $datos_mes = Mes::find($datos_comprobante->id_mes);
        $usuario = auth()->user()->name;
       // dd($usuario);

        $datos_bajas = PlanillaBajaMedica::join('aportes.aporte_i as api', 'api.id', '=', 'aportes.planilla_baja_medica.id_aporte_i')
            ->join('administracion.persona as pe', 'api.id_persona', '=', 'pe.id')
            ->where('api.id_mes', '=', $datos_comprobante->id_mes)
            ->where('api.gestion', '=', $datos_comprobante->gestion)
            ->where('api.id_institucion', '=', $datos_comprobante->id_institucion)
            ->get();

        $codigos = QRCodeService::generarQR();
        $cod_seguridad = $codigos['cod_seguridad'];
        $qrCode = $codigos['qrCode'];

        $pdf = Dompdf::loadView('Aportes.BajasMedicas.bajasmedicasPDF', compact(
            'datos_bajas',
            'datos_comprobante',
            'datos_institucion',
            'datos_mes',
            'qrCode',
            'usuario'
        ))
            ->setPaper('letter', 'landscape');

        $id_per1 = auth()->user()->id;
        $id_per2 = User::find($id_per1);
        $id_persona = $id_per2->id_persona;

        QRCodeService::registrarPDF($pdf, $cod_seguridad, $id_persona, 14, 'APORTES');


        /*         Para guardar el pdf en ftp */
        $pdfContent = $pdf->output();
        $fileName =  $datos_comprobante->id . '.pdf';
        $filePath = 'Aportes/Liquidaciones_Bajas_Medicas/' . $fileName;
        Storage::disk('ftp')->put($filePath, $pdfContent);

        // Para descargar pdf
        return $pdf->stream($datos_comprobante->id . 'bajasmedicaspdf.pdf', [
            'Attachment' => false,  // Para abrir en el navegador en lugar de descargar
            'Content-Disposition' => 'inline'  // Para asegurar que se abra en una nueva pestaña
        ]);
    }
}
