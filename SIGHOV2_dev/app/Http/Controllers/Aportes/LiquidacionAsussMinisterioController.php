<?php

namespace App\Http\Controllers\Aportes;

use App\Models\Aportes\LiquidacionMinisterioAsuss;
use App\Models\Aportes\FormaPago;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Helpers\NumberToWords;
use App\Models\Administracion\Persona;
use App\Services\QRCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use DomPDF;

class LiquidacionAsussMinisterioController extends Controller
{
    //
    public function LquidacionPDFAsuss($id,$mes,$gestion,$fechaLiquidacion)
    {   
        $id = $id;
        $mes = $mes;
        $gestion = $gestion;
        $fechaLiquidacion=$fechaLiquidacion;

        Carbon::setLocale('es');
        // Obtén la fecha de hoy en el formato deseado
        $fechaTexto = Carbon::now()->translatedFormat('j \d\e F \d\e Y');
       
        //dd($mes,$gestion,$fechaLiquidacion);
        // Crear las fechas de inicio y fin del mes especificado
        $startDate = Carbon::create($gestion, $mes, 1, 0, 0, 0);
        $endDate = $startDate->copy()->endOfMonth();

        $fecha = Carbon::parse($fechaLiquidacion);
        $mesActual = $fecha->month;
        $gestionActual = $fecha->year;
        // Array de meses en español
        $meses_es = [
            1 => 'ENERO',
            2 => 'FEBRERO',
            3 => 'MARZO',
            4 => 'ABRIL',
            5 => 'MAYO',
            6 => 'JUNIO',
            7 => 'JULIO',
            8 => 'AGOSTO',
            9 => 'SEPTIEMBRE',
            10 => 'OCTUBRE',
            11 => 'NOVIEMBRE',
            12 => 'DICIEMBRE'
        ];

        // Obtener el literal del mes
        $mes_literal = $meses_es[$mes];
        $mes_literalHoy = $meses_es[$mesActual];
        //dd($mes, $mes_literal);

        //dd($mesActual, $mes_literalHoy);
        //Realizar la consulta
        $rentista  = FormaPago::whereBetween('fecha_pago', [$startDate, $endDate])
            ->join('aportes.comprobante as c', 'c.id', '=', 'aportes.forma_pago.id_comprobante')
            ->join('aportes.institucion as i', 'i.id', '=', 'c.id_institucion')
            ->join('aportes.clase_aportante as ca', 'ca.id', '=', 'i.id_clase_aportante')
            ->join('aportes.mes as m', 'm.id', '=', 'c.id_mes')
            ->where('ca.nombre', 'like', 'RENTISTA')
            ->select(
                'forma_pago.fecha_pago',
                'c.id as compId',
                'c.nro_registro_comprobante',
                'c.total_comprobante',
                'c.cantidad_eventuales',
                'c.cantidad_permanentes',
                'c.gestion',
                'c.total_salario_cotizable',
                'c.total_comprobante',
                'c.id_mes',
                'c.fecha_liquidacion',
                'm.mes',
                'i.nombre as nombre_institucion',
                'i.id_clase_aportante',
                'ca.nombre as nombre_clase_aportante',
            )
            ->get();
        // para patronal 
        $patronal  = FormaPago::whereBetween('fecha_pago', [$startDate, $endDate])
            ->join('aportes.comprobante as c', 'c.id', '=', 'aportes.forma_pago.id_comprobante')
            ->join('aportes.institucion as i', 'i.id', '=', 'c.id_institucion')
            ->join('aportes.clase_aportante as ca', 'ca.id', '=', 'i.id_clase_aportante')
            ->join('aportes.mes as m', 'm.id', '=', 'c.id_mes')
            ->where('ca.nombre', 'like', 'PATRONAL')
            ->select(
                'forma_pago.fecha_pago',
                'c.id as compId',
                'c.nro_registro_comprobante',
                'c.total_comprobante',
                'c.cantidad_eventuales',
                'c.cantidad_permanentes',
                'c.gestion',
                'c.total_salario_cotizable',
                'c.total_comprobante',
                'c.id_mes',
                'c.fecha_liquidacion',
                'm.mes',
                'i.nombre as nombre_institucion',
                'i.id_clase_aportante',
                'ca.nombre as nombre_clase_aportante',

            )
            ->get();
        // PARA TGN
        $tgn  = FormaPago::whereBetween('fecha_pago', [$startDate, $endDate])
            ->join('aportes.comprobante as c', 'c.id', '=', 'aportes.forma_pago.id_comprobante')
            ->join('aportes.institucion as i', 'i.id', '=', 'c.id_institucion')
            ->join('aportes.clase_aportante as ca', 'ca.id', '=', 'i.id_clase_aportante')
            ->join('aportes.mes as m', 'm.id', '=', 'c.id_mes')
            ->where('ca.nombre', 'like', 'TRASPASO')
            ->select(
                'forma_pago.fecha_pago',
                'c.id as compId',
                'c.nro_registro_comprobante',
                'c.total_comprobante',
                'c.cantidad_eventuales',
                'c.cantidad_permanentes',
                'c.gestion',
                'c.total_salario_cotizable',
                'c.total_comprobante',
                'c.id_mes',
                'c.fecha_liquidacion',
                'm.mes',
                'i.nombre as nombre_institucion',
                'i.id_clase_aportante',
                'ca.nombre as nombre_clase_aportante',

            )
            ->get();
        // PARA VOLUNTARIOS
        $voluntarios  = FormaPago::whereBetween('fecha_pago', [$startDate, $endDate])
            ->join('aportes.comprobante as c', 'c.id', '=', 'aportes.forma_pago.id_comprobante')
            ->join('aportes.aporte_v as v', 'v.id', '=', 'c.id_aporte_v')
            ->join('aportes.contrato_voluntario as con','con.id','=','v.id_contrato_voluntario')
            ->join('administracion.persona as p', 'p.id', '=', 'con.id_persona')
            ->join('aportes.mes as m', 'm.id', '=', 'c.id_mes')
            ->select(
                'forma_pago.fecha_pago',
                'c.id as compId',
                'v.id',
                'p.nombres',
                'p.p_apellido',
                'p.s_apellido',
                'c.nro_registro_comprobante',
                'c.total_comprobante',
                'c.cantidad_eventuales',
                'c.cantidad_permanentes',
                'c.gestion',
                'c.total_salario_cotizable',
                'c.total_comprobante',
                'c.id_mes',
                'c.fecha_liquidacion',
                'm.mes',
            )
            ->get();

            $asuss = LiquidacionMinisterioAsuss::where('id', $id)
                    ->select(
                    'id',
                    'nro_instituciones_activos',
                    'nro_instituciones_pasivos',
                    'nro_trabajadores_activos',
                    'nro_trabajadores_pasivos',
                    'nro_trabajadores_voluntarios',
                    'total_general',
                    'total_aporte',
                    'tasa',
                    'cotizacion')
                    ->first();

        // dd($mes, $gestion, $startDate);
        $fechaActual = Carbon::now()->format('d/m/Y');
        
        $codigos = QRCodeService::generarQR();
        $cod_seguridad = $codigos['cod_seguridad'];
        $qrCode = $codigos['qrCode'];

       // Aqui abrimos el pdf y lo guardamos en el FTP
        $pdf = DomPDF::loadView('Aportes.Liquidaciones.Asuss.asussPdf',
         compact('rentista', 'patronal', 'tgn', 'voluntarios','mes_literal',
          'mes_literalHoy','fechaLiquidacion','asuss','gestion','fechaActual','gestionActual','fechaTexto','qrCode'))
            ->setPaper('A4', 'portrait');

        $pdfContent = $pdf->output();
        $ftpPath = 'Aportes/Liquidaciones/ASUSS/' . $id . '.pdf';
        Storage::disk('ftp')->put($ftpPath, $pdfContent);
        
        $id_per1 = auth()->user()->id; 
        $id_per2 = User::find($id_per1); 
        $id_persona = $id_per2->id_persona;
        QRCodeService::registrarPDF($pdf, $cod_seguridad, $id_persona, 9, 'APORTES');

        return $pdf->stream('ASUSS'.'-'.$mes.'-'.$gestion.'-'.'.pdf', [
            'Attachment' => false,  // Para abrir en el navegador en lugar de descargar
            'Content-Disposition' => 'inline'  // Para asegurar que se abra en una nueva pestaña
        ]);
    }





    //----------------------------------------------------------------------------------------------------
    public function LquidacionPDFMinisterio($id, $mes, $gestion, $fechaLiquidacion)
    {
        $id = $id;
        $mes = $mes;
        $gestion = $gestion;
        $fechaLiquidacion = $fechaLiquidacion;
        Carbon::setLocale('es');
        // Obtén la fecha de hoy en el formato deseado
        $fechaTexto = Carbon::now()->translatedFormat('j \d\e F \d\e Y');

        //dd($mes,$gestion,$fechaLiquidacion);
        // Crear las fechas de inicio y fin del mes especificado
        $startDate = Carbon::create($gestion, $mes, 1, 0, 0, 0);
        $endDate = $startDate->copy()->endOfMonth();

        $fecha = Carbon::parse($fechaLiquidacion);
        $mesActual = $fecha->month;
        $gestionActual = $fecha->year;

        // Array de meses en español
        $meses_es = [
            1 => 'ENERO',
            2 => 'FEBRERO',
            3 => 'MARZO',
            4 => 'ABRIL',
            5 => 'MAYO',
            6 => 'JUNIO',
            7 => 'JULIO',
            8 => 'AGOSTO',
            9 => 'SEPTIEMBRE',
            10 => 'OCTUBRE',
            11 => 'NOVIEMBRE',
            12 => 'DICIEMBRE'
        ];

        // Obtener el literal del mes
        $mes_literal = $meses_es[$mes];
        $mes_literalHoy = $meses_es[$mesActual];
        //dd($mesActual, $mes_literalHoy);
        //Realizar la consulta
        $rentista  = FormaPago::whereBetween('fecha_pago', [$startDate, $endDate])
            ->join('aportes.comprobante as c', 'c.id', '=', 'aportes.forma_pago.id_comprobante')
            ->join('aportes.institucion as i', 'i.id', '=', 'c.id_institucion')
            ->join('aportes.clase_aportante as ca', 'ca.id', '=', 'i.id_clase_aportante')
            ->join('aportes.mes as m', 'm.id', '=', 'c.id_mes')
            ->where('ca.nombre', 'like', 'RENTISTA')
            ->where('c.id_estado', '=', 4)
            ->select(
                'forma_pago.fecha_pago',
                'c.id as compId',
                'c.nro_registro_comprobante',
                'c.total_comprobante',
                'c.cantidad_eventuales',
                'c.cantidad_permanentes',
                'c.gestion',
                'c.total_salario_cotizable',
                'c.total_comprobante',
                'c.id_mes',
                'c.fecha_liquidacion',
                'm.mes',
                'i.nombre as nombre_institucion',
                'i.id_clase_aportante',
                'ca.nombre as nombre_clase_aportante',

            )
            ->get();
        // para patronal 
        $patronal  = FormaPago::whereBetween('fecha_pago', [$startDate, $endDate])
            ->join('aportes.comprobante as c', 'c.id', '=', 'aportes.forma_pago.id_comprobante')
            ->join('aportes.institucion as i', 'i.id', '=', 'c.id_institucion')
            ->join('aportes.clase_aportante as ca', 'ca.id', '=', 'i.id_clase_aportante')
            ->join('aportes.mes as m', 'm.id', '=', 'c.id_mes')
            ->where('ca.nombre', 'like', 'PATRONAL')
            ->where('c.id_estado', '=', 4)
            ->select(
                'forma_pago.fecha_pago',
                'c.id as compId',
                'c.nro_registro_comprobante',
                'c.total_comprobante',
                'c.cantidad_eventuales',
                'c.cantidad_permanentes',
                'c.gestion',
                'c.total_salario_cotizable',
                'c.total_comprobante',
                'c.id_mes',
                'c.fecha_liquidacion',
                'm.mes',
                'i.nombre as nombre_institucion',
                'i.id_clase_aportante',
                'ca.nombre as nombre_clase_aportante',

            )
            ->get();
        // PARA TRASPASO
        $traspaso  = FormaPago::whereBetween('fecha_pago', [$startDate, $endDate])
            ->join('aportes.comprobante as c', 'c.id', '=', 'aportes.forma_pago.id_comprobante')
            ->join('aportes.institucion as i', 'i.id', '=', 'c.id_institucion')
            ->join('aportes.clase_aportante as ca', 'ca.id', '=', 'i.id_clase_aportante')
            ->join('aportes.mes as m', 'm.id', '=', 'c.id_mes')
            ->where('ca.nombre', 'like', 'TRASPASO')
            ->where('c.id_estado', '=', 4)
            ->select(
                'forma_pago.fecha_pago',
                'c.id as compId',
                'c.nro_registro_comprobante',
                'c.total_comprobante',
                'c.cantidad_eventuales',
                'c.cantidad_permanentes',
                'c.gestion',
                'c.total_salario_cotizable',
                'c.total_comprobante',
                'c.id_mes',
                'c.fecha_liquidacion',
                'm.mes',
                'i.nombre as nombre_institucion',
                'i.id_clase_aportante',
                'ca.nombre as nombre_clase_aportante',

            )
            ->get();
        // PARA TGN
        $tgn  = FormaPago::whereBetween('fecha_pago', [$startDate, $endDate])
            ->join('aportes.comprobante as c', 'c.id', '=', 'aportes.forma_pago.id_comprobante')
            ->join('aportes.institucion as i', 'i.id', '=', 'c.id_institucion')
            ->join('aportes.clase_aportante as ca', 'ca.id', '=', 'i.id_clase_aportante')
            ->join('aportes.mes as m', 'm.id', '=', 'c.id_mes')
            ->where('i.tgn', true)
            ->where('c.id_estado', '=', 4)
            ->select(
                'forma_pago.fecha_pago',
                'c.id as compId',
                'c.nro_registro_comprobante',
                'c.total_comprobante',
                'c.cantidad_eventuales',
                'c.cantidad_permanentes',
                'c.gestion',
                'c.total_salario_cotizable',
                'c.total_comprobante',
                'c.id_mes',
                'c.fecha_liquidacion',
                'c.ministerio',
                'm.mes',
                'i.nombre as nombre_institucion',
                'i.id_clase_aportante',

            )
            ->get();
        //dd($tgn);
        // PARA VOLUNTARIOS
        $voluntarios  = FormaPago::whereBetween('fecha_pago', [$startDate, $endDate])
            ->join('aportes.comprobante as c', 'c.id', '=', 'aportes.forma_pago.id_comprobante')
            ->join('aportes.aporte_v as v', 'v.id', '=', 'c.id_aporte_v')
            ->join('aportes.contrato_voluntario as con','con.id','=','v.id_contrato_voluntario')
            ->join('administracion.persona as p', 'p.id', '=', 'con.id_persona')
            ->join('aportes.mes as m', 'm.id', '=', 'c.id_mes')
            ->where('c.id_estado', '=', 4)
            ->select(
                'forma_pago.fecha_pago',
                'c.id as compId',
                'v.id',
                'p.nombres',
                'p.p_apellido',
                'p.s_apellido',
                'c.nro_registro_comprobante',
                'c.total_comprobante',
                'c.cantidad_eventuales',
                'c.cantidad_permanentes',
                'c.gestion',
                'c.total_salario_cotizable',
                'c.total_comprobante',
                'c.id_mes',
                'c.fecha_liquidacion',
                'm.mes',
            )
            ->get();

        $asuss = LiquidacionMinisterioAsuss::where('id', $id)
            ->select(
                'id',
                'nro_instituciones_activos',
                'nro_instituciones_pasivos',
                'nro_trabajadores_activos',
                'nro_trabajadores_pasivos',
                'nro_trabajadores_voluntarios',
                'total_general',
                'total_aporte',
                'tasa',
                'cotizacion'
            )
            ->first();
        // calculo de total ministerio
        $totalMinisterio=$tgn->sum('ministerio');
        $fechaActual = Carbon::now()->format('d/m/Y');

        $codigos = QRCodeService::generarQR();
        $cod_seguridad = $codigos['cod_seguridad'];
        $qrCode = $codigos['qrCode'];

        // Aqui abrimos el pdf y lo guardamos en el FTP
        $pdf = DomPDF::loadView('Aportes.Liquidaciones.Ministerio.ministerioPdf',
         compact('rentista', 'patronal', 'traspaso', 'tgn', 'voluntarios',
          'mes_literal', 'mes_literalHoy', 'asuss','totalMinisterio','fechaActual'
          ,'gestion', 'gestionActual','fechaTexto','qrCode'))
            ->setPaper('A4', 'portrait');

        $pdfContent = $pdf->output();
        $ftpPath = 'Aportes/Liquidaciones/MINISTERIO/' . $id. '.pdf';
        Storage::disk('ftp')->put($ftpPath, $pdfContent);

        $id_per1 = auth()->user()->id;
        $id_per2 = User::find($id_per1); 
        $id_persona = $id_per2->id_persona;

        QRCodeService::registrarPDF($pdf, $cod_seguridad, $id_persona, 10, 'APORTES');
        

        return $pdf->stream('MINISTERIO'.'-'.$mes.'-'.$gestion.'-'.'.pdf', [
            'Attachment' => false,  // Para abrir en el navegador en lugar de descargar
            'Content-Disposition' => 'inline'  // Para asegurar que se abra en una nueva pestaña
        ]);
    }
}
