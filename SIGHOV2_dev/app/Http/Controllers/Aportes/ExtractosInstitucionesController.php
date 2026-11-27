<?php

namespace App\Http\Controllers\Aportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aportes\Institucion;
use App\Models\Aportes\Comprobante;
use App\Models\Administracion\Residencia;
use App\Models\User;
use DomPDF;
use Carbon\Carbon;
use App\Services\QRCodeService;

class ExtractosInstitucionesController extends Controller
{
    public function ExtractoInstitucion($id_institucion, $fecha_inicial, $fecha_final){
       
        $datosInstitucion = Institucion::where('id', $id_institucion)
            ->with(['institucionResidencia', 'institucionResidencia.zonaResidencia', 'claseAportanteInstitucion'])
            ->first();

        
        $nombreInstitucion = $datosInstitucion->nombre;
        $nroEmpleador = $datosInstitucion->nro_empleador;
        $nit = $datosInstitucion->nit;
        $direccion = $datosInstitucion->institucionResidencia->direccion;
        $nroVivienda = $datosInstitucion->institucionResidencia->nro_vivienda;
        $telefono = $datosInstitucion->telefono;
        $zona = $datosInstitucion->institucionResidencia->zonaResidencia->nombre;
        $claseAportante = $datosInstitucion->claseAportanteInstitucion->tipo;
        $actividadEmpleador = $datosInstitucion->actividad_empleador;
        $id_persona= $datosInstitucion->id_persona;

        $fechaInicioExtracto = mb_strtoupper(Carbon::parse($fecha_inicial)->translatedFormat('d \\d\\e F \\d\\e Y'));
        $fechaFinExtracto = mb_strtoupper(Carbon::parse($fecha_final)->translatedFormat('d \\d\\e F \\d\\e Y'));

        $mesInicial = Carbon::parse($fecha_inicial)->format('m');
        $gestionInicial = Carbon::parse($fecha_inicial)->format('Y');
        $mesFinal = Carbon::parse($fecha_final)->format('m');
        $gestionFinal = Carbon::parse($fecha_final)->format('Y');


        $aportesInstitucion = Comprobante::where('id_institucion', $id_institucion)
            ->whereBetween('gestion', [$gestionInicial, $gestionFinal])
            ->whereBetween('id_mes', [$mesInicial, $mesFinal])
            ->whereNot('fecha_liquidacion', null)
            ->with(['institucionComprobante', 'mesComprobante', 
                'institucionComprobante.claseAportanteInstitucion', 
                'institucionComprobante.institucionResidencia',
                'institucionComprobante.institucionResidencia.zonaResidencia'])
            ->get();
        
       
        $usuario = User::where('id', auth()->user()->id)
            ->with('personaUser')
            ->first();

        
        if($usuario->personaUser->p_apellido == null){
            $nombreUsuario = $usuario->personaUser->s_apellido.' '.$usuario->personaUser->nombres;
        } elseif($usuario->personaUser->s_apellido == null){
            $nombreUsuario = $usuario->personaUser->p_apellido.' '.$usuario->personaUser->nombres;
        } else {
            $nombreUsuario = $usuario->personaUser->p_apellido.' '.$usuario->personaUser->s_apellido.' '.$usuario->personaUser->nombres;
        }
        
        $fechaActual = Carbon::now()->format('d-m-Y');


        //$aportesInstitucion = Institucion::where('id', $id_institucion)->get();

        /*$consulta = Institucion::join('aportes.comprobante as comprobantes', 'aportes.institucion.id', '=', 'comprobantes.id_institucion')
            ->join('administracion.zona as zonas', 'zonas.id', '=', 'aportes.institucion.id_zona')
            ->join('aportes.clase_aportante as clase_aportantes', 'clase_aportantes.id', '=', 'aportes.institucion.id_clase_aportante')
            ->join('aportes.mes as meses', 'meses.id', '=', 'comprobantes.id_mes')
            ->where('aportes.institucion.id', $id_institucion)
            ->whereBetween('comprobantes.created_at', [$fecha_inicial, $fecha_final])
            ->select(
            'aportes.institucion.nombre as nombre_institucion',
            'aportes.institucion.id_zona',
            'zonas.nombre as zona_nombre',
            'aportes.institucion.id_clase_aportante',
            'clase_aportantes.nombre as clase_aportante_nombre',
            'comprobantes.id_mes',
            'comprobantes.fecha_liquidacion',
            'comprobantes.nro_registro_comprobante',
            'comprobantes.cantidad_eventuales',
            'comprobantes.cantidad_permanentes',
            'meses.mes',
            'comprobantes.total_salario_cotizable',
            'comprobantes.importe_aporte_patronal',
            'comprobantes.tasa_interes',
            'comprobantes.dias_mora',
            'comprobantes.importe_intereses',
            'comprobantes.importe_multa_interes',
            'comprobantes.importe_multa_no_planilla',
            'comprobantes.subtotal_recargos',
            'comprobantes.monto_bajas_medicas',
            'comprobantes.total_comprobante',
            'comprobantes.gestion',
            'comprobantes.created_at',
            'meses.mes'
            )
            ->get();
      
        */
        //* GENERAR EL CÓDIGO DE VERIFICACIÓN
        $codigos = QRCodeService::generarQR();
        $cod_seguridad = $codigos['cod_seguridad'];
        $qrCode = $codigos['qrCode'];

        $pdf = DomPDF::loadView('Aportes.Extractos.Instituciones.ExtractosInstitucionesPdf', 
            compact('qrCode','nombreInstitucion', 'nroEmpleador', 'nit', 
                'direccion', 'nroVivienda', 'telefono', 
                'zona', 'claseAportante', 'actividadEmpleador', 'fechaInicioExtracto', 'fechaFinExtracto',  
                'aportesInstitucion', 'nombreUsuario'))
        ->setPaper('letter', 'landscape');


        QRCodeService::registrarPDF($pdf, $cod_seguridad, $id_persona, 8, 'APORTES');
        return $pdf->stream('EXTRACTO - '.$nombreInstitucion.' - '.$fechaActual.'.pdf', [
            'Attachment' => false,  // Para abrir en el navegador en lugar de descargar
            'Content-Disposition' => 'inline'  // Para asegurar que se abra en una nueva pestaña
        ]);

    }

    
}
