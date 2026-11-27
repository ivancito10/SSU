<?php

namespace App\Http\Controllers\Afiliacion;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Administracion\Persona;
use App\Models\Afiliacion\HCarnet;
use App\Models\Afiliacion\Estudiante;
use App\Services\QRCodeService;
use Carbon\Carbon;
use DomPDF;
use SnappyPDF;
use Illuminate\Http\Request;
//

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReporteEstudianteUmsaExport;


class ReportesEstudiantesController extends Controller
{

    public function reporteMensualUmsa()
    {
        //dd('este es el snappy de la umsa');
        ini_set('memory_limit', '10024M');
        ini_set('max_execution_time', 12000);

        $estudiantes_registrados = [];
        $count = 0;

        // $fechaCarbon = '2025-05-30';
        // $fecha = Carbon::parse($fechaCarbon);

        $fecha = Carbon::now();
        $fecha_registro = $fecha;

        $userActual = User::join('administracion.persona as p', 'p.id', '=', 'administracion.users.id_persona')
            ->where('administracion.users.id', '=', auth()->user()->id)
            ->first();
        $id_persona = $userActual->id_persona;
        $personaUsuario = Persona::find($id_persona);
        $nombre_usuario = $personaUsuario->nombres . ' ' . $personaUsuario->p_apellido . ' ' . $personaUsuario->s_apellido;

        $año = $fecha->year;
        $mesNombre =  mb_strtoupper($fecha->locale('es')->translatedFormat('F'));

        /* $estudiantes_registrados = Estudiante::join('persona', 'estudiante.id_persona', '=', 'persona.id')
            ->with(
                'personaEstudiante',
                'carreraEstudiante.facultadCarrera',
            )
            ->where('estudiante.created_at', '<=', $fecha_registro)
            //      ->where('estado_validar', $estado_validar_mostrar)
            ->where('estado_vigencia', true)
            ->where('estado', true)
            ->orderBy('persona.p_apellido', 'asc')
            ->orderBy('persona.s_apellido', 'asc')
            ->orderBy('persona.nombres', 'asc')
            ->get(); */

        $sub = Estudiante::selectRaw('id_persona, MAX(id) as max_id')
            ->where('created_at', '<=', $fecha_registro)
            ->groupBy('id_persona');


        $estudiantes_registrados = Estudiante::query()
            ->joinSub($sub, 'ultimos', function ($join) {
                $join->on('estudiante.id', '=', 'ultimos.max_id');
            })
            ->join('persona as p', 'estudiante.id_persona', '=', 'p.id')
            ->leftJoin('afiliacion.carrera as c', 'estudiante.id_carrera', '=', 'c.id')
            ->with([
                'personaEstudiante',
                'carreraEstudiante.facultadCarrera'
            ])
            ->where('p.afiliado', true)
            ->where('p.id_tipo_asegurado', 11)
            // ->where('estudiante.estado_vigencia', '!=', false)
            // ->where('estudiante.afiliacion', '!=', false)
            ->select(
                'estudiante.*',
                'p.p_apellido',
                'p.s_apellido',
                'p.nombres'
            )
            ->orderBy('p.p_apellido', 'asc')
            ->orderBy('p.s_apellido', 'asc')
            ->orderBy('p.nombres', 'asc')
            ->get();
        //dd($estudiantes_registrados);
        $pdf = SnappyPdf::loadView('Afiliacion.Afiliacion.reporteEstudianteUmsa', compact(
            'count',
            'fecha_registro',
            'año',
            'mesNombre',
            'estudiantes_registrados',
            'nombre_usuario',
        ))
            ->setPaper('legal', 'portrait')
            ->setOption('footer-html', view('Aportes.pdf.footer')->render())
            //  ->setOption('footer-html', url('/pdf-footer'))
            ->setOption('footer-spacing', 2);
        $nombreReporte = 'reporteUmsa' . ' ' . $mesNombre . '-' . $año . '.pdf';
        return $pdf->stream($nombreReporte);
    }


    public function reportesEstudiantes($fecha_inicio, $fecha_fin, $tipo_reporte, $estado_validar)
    {


        $datosGenerales = $this->obtenerDatosGeneralesEstudiantes($fecha_inicio, $fecha_fin, $estado_validar);
        $datosCreador = $this->obtenerUsuarioCreador($datosGenerales->pluck('id_persona'));

        $datosCreadorIndexed = $datosCreador->keyBy('id_persona');
        $datosFinales = $datosGenerales->map(function ($general) use ($datosCreadorIndexed) {
            $creador = $datosCreadorIndexed[$general['id_persona']] ?? [];
            return [
                ...$general,
                ...$creador,
            ];
        });

        // dd($datosFinales, $datosGenerales, $datosCreador);

        $fechaInicioFormatted = Carbon::parse($fecha_inicio)->format('d/m/Y');
        $fechaFinFormatted = Carbon::parse($fecha_fin)->format('d/m/Y');

        $name = 'ReporteEstudiantes' . '-' . $tipo_reporte . '-' . $fecha_inicio . '-' . $fecha_fin;
        return Excel::download(
            new ReporteEstudianteUmsaExport(
                $datosFinales,
                $tipo_reporte,
                $fechaInicioFormatted,
                $fechaFinFormatted,
                $estado_validar,
            ),
            $name . '.xlsx'
        );
    }

    public function obtenerEstudiantes($fecha_inicio, $fecha_fin, $estado_validar)
    {
        $ultimoRegistro = Estudiante::selectRaw('id_persona, MAX(id) as max_id')
            ->whereDate('created_at', '>=', $fecha_inicio)
            ->whereDate('created_at', '<=', $fecha_fin)
            ->when($estado_validar !== 'TODOS', function ($query) use ($estado_validar) {
                $query->where('estado_validar', $estado_validar);
            })
            ->groupBy('id_persona');
        // dd($ultimoRegistro);
        $estudiantes_registrados = Estudiante::query()
            ->joinSub($ultimoRegistro, 'ultimos', function ($join) {
                $join->on('estudiante.id', '=', 'ultimos.max_id');
            })
            ->join('persona as p', 'estudiante.id_persona', '=', 'p.id')
            ->where('p.afiliado', true)
            ->where('p.id_tipo_asegurado', 11)
            //->get();
            ->pluck('p.id');
        // dd($estudiantes_registrados);
        return $estudiantes_registrados;
    }

    public function obtenerDatosGeneralesEstudiantes($fecha_inicio, $fecha_fin, $estado_validar)
    {

        $ultimoRegistro = Estudiante::selectRaw('id_persona, MAX(id) as max_id')
            ->whereDate('created_at', '>=', $fecha_inicio)
            ->whereDate('created_at', '<=', $fecha_fin)
            ->when($estado_validar !== 'TODOS', function ($query) use ($estado_validar) {
                $query->where('estado_validar', $estado_validar);
            })
            ->groupBy('id_persona');

        $estudiantes = Estudiante::query()
            ->joinSub($ultimoRegistro, 'ultimos', function ($join) {
                $join->on('estudiante.id', '=', 'ultimos.max_id');
            })
            ->join('persona as p', 'estudiante.id_persona', '=', 'p.id')
            ->where('p.afiliado', true)
            ->where('p.id_tipo_asegurado', 11)
            ->with([
                'personaEstudiante',
                'carreraEstudiante.facultadCarrera'
            ])
            ->orderBy('p.p_apellido', 'asc')
            ->orderBy('p.s_apellido', 'asc')
            ->orderBy('p.nombres', 'asc')
            ->get();
        // dd($estudiantes);
        $datos = $estudiantes->map(function ($estudiante) {
            //dd('entro');
            if ($estudiante->personaEstudiante->complemento != null) {
                $carnet = $estudiante->personaEstudiante->ci . '-' . $estudiante->personaEstudiante->complemento;
            } else {
                $carnet = $estudiante->personaEstudiante->ci;
            }

            return [
                'id_estudiante' => $estudiante->id,
                'id_persona' => $estudiante->id_persona,
                'nombres' => $estudiante->personaEstudiante->nombres,
                'p_apellido' => $estudiante->personaEstudiante->p_apellido,
                's_apellido' => $estudiante->personaEstudiante->s_apellido,
                'matricula_asegurado' => $estudiante->personaEstudiante->matricula_seguro ?? '',
                'ci' => $carnet,
                'fecha_nacimiento' => Carbon::parse($estudiante->personaEstudiante->fecha_nacimiento)->format('d/m/Y'),
                'edad' =>  Carbon::parse($estudiante->personaEstudiante->fecha_nacimiento)->age,
                'sexo' => $estudiante->personaEstudiante->sexo === 'F' ? 'FEMENINO' : 'MASCULINO',
                'estado' => $estudiante->estado_validar,
                'esAntiguo' => $estudiante->afiliacion ? 'Nuevo' : 'Antiguo',
                'carrera' => $estudiante->carreraEstudiante?->nombre ?? 'SIN ACTUALIZAR',
                'facultad' => $estudiante->carreraEstudiante?->facultadCarrera?->nombre ?? 'SIN ACTUALIZAR',
                'fecha_ingreso' => $estudiante->fecha_afiliacion ? Carbon::parse($estudiante->fecha_afiliacion)->format('d/m/Y') : '',
                //'fecha_afiliacion' => $estudiante->fecha_reafiliacion ? Carbon::parse($estudiante->fecha_reafiliacion)->format('d/m/Y') : Carbon::parse($estudiante->fecha_afiliacion)->format('d/m/Y'),
                'fecha_afiliacion' => ($fecha = $estudiante->fecha_reafiliacion ?? $estudiante->fecha_afiliacion)
                    ? Carbon::parse($fecha)->format('d/m/Y')
                    : '',
            ];
        });
        //dd($datos);
        return $datos;
    }

    public function obtenerUsuarioCreador($idsPersonas)
    {
        $usuarios = User::with('usuarioCreador')
            ->whereIn('id_persona', $idsPersonas)
            ->get();
        $datos = $usuarios->map(function ($user) {
            if ($user->id_user_created == 2) {
                $nombreCreador = 'MIGRADO';
            } else {
                $nombreCreador = $user->usuarioCreador->name;
            }
            return [
                'id_persona' => $user->id_persona,
                'name_creador' => $nombreCreador,
            ];
        });
        return $datos;
    }
}
