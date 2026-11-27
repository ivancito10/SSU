<?php

namespace App\Http\Controllers\Aportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administracion\Persona;
use App\Models\Aportes\AporteInstitucional;
use App\Models\Aportes\AporteVoluntario;
use App\Models\User;
use App\Services\QRCodeService;
use DomPDF;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Administracion\DocumentoPDF;

class ExtractosAportantesController extends Controller
{
    public function ExtractoAportante($id_persona, $fecha_inicial, $fecha_final)
    {
        //$variablecita = 'huevada';

        //$fecha_inicial=$fecha_inicial;
        //$fecha_final=$fecha_final;
        //$id_persona=$id;
        //dd($fecha_inicial);
        $datosExtracto = [];
        $datosPersona = Persona::find($id_persona);
        //dd($datosPersona);
        if ($datosPersona->p_apellido == null) {
            $nombreCompleto = $datosPersona->s_apellido . ' ' . $datosPersona->nombres;
        } elseif ($datosPersona->s_apellido == null) {
            $nombreCompleto = $datosPersona->p_apellido . ' ' . $datosPersona->nombres;
        } else {
            $nombreCompleto = $datosPersona->p_apellido . ' ' . $datosPersona->s_apellido . ' ' . $datosPersona->nombres;
        }

        $fechaNacimiento = mb_strtoupper(Carbon::parse($datosPersona->fecha_nacimiento)->translatedFormat('d \\d\\e F \\d\\e Y'));
        $edad = Carbon::parse($datosPersona->fecha_nacimiento)->age;

        if ($datosPersona->complemento == null) {
            $carnet = $datosPersona->ci;
        } else {
            $carnet = $datosPersona->ci . '-' . $datosPersona->complemento;
        }

        $fechaInicioExtracto = mb_strtoupper(Carbon::parse($fecha_inicial)->translatedFormat('d \\d\\e F \\d\\e Y'));
        $fechaFinExtracto = mb_strtoupper(Carbon::parse($fecha_final)->translatedFormat('d \\d\\e F \\d\\e Y'));

        $mesInicial = Carbon::parse($fecha_inicial)->format('m');
        $gestionInicial = Carbon::parse($fecha_inicial)->format('Y');
        $mesFinal = Carbon::parse($fecha_final)->format('m');
        $gestionFinal = Carbon::parse($fecha_final)->format('Y');
        //dd($mesInicial, $gestionInicial);
        $fechaInicio =  Carbon::parse($fecha_inicial)->format('Y-m-d');
        $fechaFin =  Carbon::parse($fecha_final)->format('Y-m-d');

        $aportesInstitucionales = AporteInstitucional::where('id_persona', $id_persona)
            //  ->whereBetween('gestion', [$gestionInicial, $gestionFinal])
            //  ->whereBetween('id_mes', [$mesInicial, $mesFinal])
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->where('id_institucion' ,'!=',[2,6])
            ->with('mesAporteInstitucional')
            ->with('institucionAporteInstitucional')
            ->orderBy('created_at')
            ->get();

        //dd($aportesInstitucionales);

        if ($aportesInstitucionales->count() > 0) {
            foreach ($aportesInstitucionales as $aporteInstitucional) {
                $datosExtracto[] = [
                    'mes' => $aporteInstitucional->mesAporteInstitucional->mes,
                    'gestion' => $aporteInstitucional->gestion,
                    'salario_cotizable' => $aporteInstitucional->total_ganado,
                    'aporte' => $aporteInstitucional->total_aporte,
                    'tipo_aporte' => $aporteInstitucional->reintegro_incr_salarial ? 'REINTEGRO INCREMENTO SALARIAL' : ($aporteInstitucional->beneficio_social ? 'BENEFICIO SOCIAL' : ($aporteInstitucional->planilla_adicional ? 'PLANILLA ADICIONAL' : 'PATRONAL')),
                    'tipo_trabajador' => $aporteInstitucional->tipo_trabajador,
                    'institucion' => $aporteInstitucional->institucionAporteInstitucional->nombre,
                    'cargo' => $aporteInstitucional->cargo,
                    'area' => $aporteInstitucional->area,
                ];
            }
        }


        $aportesVoluntarios = AporteVoluntario::whereBetween('created_at', [$fechaInicio, $fechaFin])
            //whereBetween('gestion', [$gestionInicial, $gestionFinal])
            // ->whereBetween('id_mes', [$mesInicial, $mesFinal])
            ->whereHas('contratoVoluntarioAporteVoluntario', function ($contrato) use ($id_persona) {
                $contrato->where('id_persona', $id_persona);
            })
            ->with('mesAporteVoluntario')
            ->get();

        //dd($aportesVoluntarios);

        if ($aportesVoluntarios->count() > 0) {
            foreach ($aportesVoluntarios as $aporteVoluntario) {

                $datosExtracto[] = [
                    'mes' => $aporteVoluntario->mesAporteVoluntario->mes,
                    'gestion' => $aporteVoluntario->gestion,
                    'salario_cotizable' => $aporteVoluntario->total_ganado,
                    'aporte' => $aporteVoluntario->total_aporte,
                    'tipo_aporte' => 'APORTE VOLUNTARIO',
                    'tipo_trabajador' => '---',
                    'institucion' => '---',
                    'cargo' => '---',
                    'area' => '---',
                ];
            }
        }

        $usuario = User::where('id', auth()->user()->id)
            ->with('personaUser')
            ->first();

        //dd($user);
        if ($usuario->personaUser->p_apellido == null) {
            $nombreUsuario = $usuario->personaUser->s_apellido . ' ' . $usuario->personaUser->nombres;
        } elseif ($usuario->personaUser->s_apellido == null) {
            $nombreUsuario = $usuario->personaUser->p_apellido . ' ' . $usuario->personaUser->nombres;
        } else {
            $nombreUsuario = $usuario->personaUser->p_apellido . ' ' . $usuario->personaUser->s_apellido . ' ' . $usuario->personaUser->nombres;
        }

        $fechaActual = Carbon::now()->format('d-m-Y');

        //dd($datosExtracto);

        //dd($aportesInstitucionales->concat($aportesVoluntarios));



        /*$consulta=Persona::join('aportes.aporte_i as api','api.id_persona','=','administracion.persona.id')
        ->join('aportes.institucion as ins','ins.id','=','api.id_institucion')
        ->join('aportes.mes as mes','mes.id','=','api.id_mes')
        ->where('api.created_at','>',$fecha_inicial)
        ->where('api.created_at','<=',$fecha_final)
        ->where('api.id_persona','=', $id_persona)
        ->select('administracion.persona.nombres',
        'administracion.persona.p_apellido',
        'administracion.persona.s_apellido',
        'administracion.persona.ci',
        'administracion.persona.complemento',
        'administracion.persona.fecha_nacimiento',
        'api.tipo_trabajador',
        'api.dias_trabajados',
        'api.total_ganado',
        'api.total_aporte',
        'api.gestion',
        'mes.mes',
        'ins.nombre as institucion',
        'api.carga_horaria',
        'api.item',
        'api.cargo',
        'api.area',
        'api.created_at'
        )
        ->get();*/

        //* GENERAR EL CÓDIGO DE VERIFICACIÓN
        $codigos = QRCodeService::generarQR();
        $cod_seguridad = $codigos['cod_seguridad'];
        $qrCode = $codigos['qrCode'];
        $pdf = DomPDF::loadView('Aportes.Extractos.Personas.ExtractosAportantesPdf', compact(
            'nombreCompleto',
            'fechaNacimiento',
            'edad',
            'carnet',
            'fechaInicioExtracto',
            'fechaFinExtracto',
            'nombreUsuario',
            'datosExtracto',
            'qrCode',
        ))
            ->setPaper('letter', 'landscape');
        // Registramos el PDF
        QRCodeService::registrarPDF($pdf, $cod_seguridad, $id_persona, 7, 'APORTES');
        return $pdf->stream('EXTRACTO - ' . $nombreCompleto . ' - ' . $fechaActual . '.pdf', [
            'Attachment' => false,  // Para abrir en el navegador en lugar de descargar
            'Content-Disposition' => 'inline'  // Para asegurar que se abra en una nueva pestaña
        ]);
    }
}
