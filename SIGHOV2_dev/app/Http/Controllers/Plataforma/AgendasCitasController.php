<?php

namespace App\Http\Controllers\Plataforma;

use App\Http\Controllers\Controller;
use App\Models\Plataforma\Agenda;
use App\Models\Plataforma\Especialista;
use App\Models\Administracion\Especialidad;
use App\Models\Administracion\Persona;
use App\Models\Plataforma\AsignacionHorario;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DateTime;

class AgendasCitasController extends Controller
{
    public function generarPdfAgendaEspecialista($idEsp, $fecha, $turno)
    {
        //dd($idEsp, $fecha, $turno);

        $esp = Especialista::find($idEsp);
        $id_especialidad = $esp->id_especialidad;
        $id_persona = $esp->id_persona;

        $persona = Persona::find($id_persona);
        if ($persona->p_apellido == null) {
            $nombres = $persona->s_apellido . ' ' . $persona->nombres;
        } elseif ($persona->s_apellido == null) {
            $nombres = $persona->p_apellido . ' ' . $persona->nombres;
        } else {
            $nombres = $persona->p_apellido . ' ' . $persona->s_apellido . ' ' . $persona->nombres;
        }
        $especialidad = Especialidad::find($id_especialidad);
        $especialidad = $especialidad->especialidad;
        $fecha_hoy = Carbon::parse($fecha)->format('Y-m-d');

        $date = Carbon::parse($fecha);
        $diaSemanaNumerico = $date->dayOfWeek;
        // dd($diaSemanaNumerico);
        $consulta = [];
        $consulta_tarde = [];



        if ($turno == 1) {
            //CONSULTA AGENDA PARA LA MAÑANA
            $consulta = Agenda::where('fecha_agenda', $fecha_hoy)
                ->where('anulacion_ficha', false)
                ->whereHas('asignacionHorarioAgenda', function ($query) use ($idEsp, $diaSemanaNumerico) {
                    $query->where('id_especialista', $idEsp)
                        ->where('id_dia', $diaSemanaNumerico)
                        ->whereHas('horarioAsignacionHorario', function ($subQuery) {
                            $subQuery->where('turno', 'MAÑANA');
                        });
                })
                ->with([
                    'asignacionHorarioAgenda',
                    'asignacionHorarioAgenda.horarioAsignacionHorario',
                    'asignacionHorarioAgenda.consultorioAsignacionHorario',
                    'servicioPlataformaAgenda',
                    'personaAgenda',
                    'personaAgenda.tipoAseguradoPersona'
                ])
                ->orderBy('hora_agenda', 'asc')
                ->get();

            //dd($consulta);        
            /*$consulta = AsignacionHorario::where('asignacion_horario.id_especialista', $idEsp)
                ->join('plataforma.horario as h', 'asignacion_horario.id_horario', '=', 'h.id')
                ->join('plataforma.consultorio as c', 'asignacion_horario.id_consultorio', '=', 'c.id')
                ->join('plataforma.sedes as s', 'c.id_sedes', '=', 's.id')
                ->join('plataforma.agenda as a', 'a.id_asignacion_horario', '=', 'asignacion_horario.id')
                ->join('plataforma.servicios_plataforma as sp', 'a.id_servicio_plataforma', '=', 'sp.id')
                ->join('administracion.persona as p', 'a.id_persona', '=', 'p.id')
                ->join('administracion.tipo_asegurado as ta', 'p.id_tipo_asegurado', '=', 'ta.id')
                ->join('administracion.users as u', 'a.id_user_created', '=', 'u.id')
                ->where('asignacion_horario.id_dia', $diaSemanaNumerico)
                ->where('h.turno', 'MAÑANA')
                ->where('a.fecha_agenda', $fecha_hoy)
                ->select(
                    'asignacion_horario.id',
                    'h.turno',
                    'a.fecha_agenda',
                    'a.hora_agenda',
                    'a.anulacion_ficha',
                    'a.id_suspension_horario',
                    'a.motivo_anulacion',
                    'sp.nombre_servicio',
                    'p.nombres',
                    'p.p_apellido',
                    'p.s_apellido',
                    'p.matricula_seguro',
                    'c.numero_consultorio',
                    's.ubicacion',
                    's.piso',
                    'h.hora_inicio',
                    'h.hora_fin',
                    'h.turno',
                    'ta.tipo_asegurado',
                    'a.created_at',
                    'u.name'
                )
                ->orderBy('a.hora_agenda', 'asc')
                ->get();*/
            // Aqui ver hora_inicio  turno mañana
            if (count($consulta) > 0) {
                $hinicio = $consulta->first()->asignacionHorarioAgenda->horarioAsignacionHorario->hora_inicio;

                $hfin = $consulta->first()->asignacionHorarioAgenda->horarioAsignacionHorario->hora_fin;
                $turno_registrado = $consulta->first()->asignacionHorarioAgenda->horarioAsignacionHorario->turno;
            } else {
                $hinicio = null;
                $hfin = null;
                $turno_registrado = null;
            }



            //dd($hinicio);

        } elseif ($turno == 2) {

            $consulta = Agenda::where('fecha_agenda', $fecha_hoy)
                ->where('anulacion_ficha', false)
                ->whereHas('asignacionHorarioAgenda', function ($query) use ($idEsp, $diaSemanaNumerico) {
                    $query->where('id_especialista', $idEsp)
                        ->where('id_dia', $diaSemanaNumerico)
                        ->whereHas('horarioAsignacionHorario', function ($subQuery) {
                            $subQuery->where('turno', 'TARDE');
                        });
                })
                ->with([
                    'asignacionHorarioAgenda',
                    'asignacionHorarioAgenda.horarioAsignacionHorario',
                    'asignacionHorarioAgenda.consultorioAsignacionHorario',
                    'servicioPlataformaAgenda',
                    'personaAgenda',
                    'personaAgenda.tipoAseguradoPersona'
                ])
                ->orderBy('hora_agenda', 'asc')
                ->get();

            //CONSULTA AGENDA PARA LA TARDE
            /*$consulta = AsignacionHorario::where('asignacion_horario.id_especialista', $idEsp)
                ->join('plataforma.horario as h', 'asignacion_horario.id_horario', '=', 'h.id')
                ->join('plataforma.consultorio as c', 'asignacion_horario.id_consultorio', '=', 'c.id')
                ->join('plataforma.sedes as s', 'c.id_sedes', '=', 's.id')
                ->join('plataforma.agenda as a', 'a.id_asignacion_horario', '=', 'asignacion_horario.id')
                ->join('plataforma.servicios_plataforma as sp', 'a.id_servicio_plataforma', '=', 'sp.id')
                ->join('administracion.persona as p', 'a.id_persona', '=', 'p.id')
                ->join('administracion.tipo_asegurado as ta', 'p.id_tipo_asegurado', '=', 'ta.id')
                ->join('administracion.users as u', 'a.id_user_created', '=', 'u.id')
                ->where('asignacion_horario.id_dia', $diaSemanaNumerico)
                ->where('h.turno', 'TARDE')
                ->where('a.fecha_agenda', $fecha)
                ->select(
                    'asignacion_horario.id',
                    'h.turno',
                    'a.fecha_agenda',
                    'a.hora_agenda',
                    'a.anulacion_ficha',
                    'a.id_suspension_horario',
                    'a.motivo_anulacion',
                    'sp.nombre_servicio',
                    'p.nombres',
                    'p.p_apellido',
                    'p.s_apellido',
                    'p.matricula_seguro',
                    'c.numero_consultorio',
                    's.ubicacion',
                    's.piso',
                    'h.hora_inicio',
                    'h.hora_fin',
                    'h.turno',
                    'ta.tipo_asegurado',
                    'a.created_at',
                    'u.name'
                )
                ->orderBy('a.hora_agenda', 'asc')
                ->get();*/
            if (count($consulta) > 0) {
                $hinicio = $consulta->first()->asignacionHorarioAgenda->horarioAsignacionHorario->hora_inicio;

                $hfin = $consulta->first()->asignacionHorarioAgenda->horarioAsignacionHorario->hora_fin;
                $turno_registrado = $consulta->first()->asignacionHorarioAgenda->horarioAsignacionHorario->turno;
            } else {
                $hinicio = null;
                $hfin = null;
                $turno_registrado = null;
            }
        } elseif ($turno == 3) {

            $consulta = Agenda::where('fecha_agenda', $fecha_hoy)
                ->where('anulacion_ficha', false)
                ->whereHas('asignacionHorarioAgenda', function ($query) use ($idEsp, $diaSemanaNumerico) {
                    $query->where('id_especialista', $idEsp)
                        ->where('id_dia', $diaSemanaNumerico)
                        ->whereHas('horarioAsignacionHorario', function ($subQuery) {
                            $subQuery->where('turno', 'NOCHE');
                        });
                })
                ->with([
                    'asignacionHorarioAgenda',
                    'asignacionHorarioAgenda.horarioAsignacionHorario',
                    'asignacionHorarioAgenda.consultorioAsignacionHorario',
                    'servicioPlataformaAgenda',
                    'personaAgenda',
                    'personaAgenda.tipoAseguradoPersona'
                ])
                ->orderBy('hora_agenda', 'asc')
                ->get();

            //CONSULTA AGENDA PARA LA NOCHE
            /*$consulta = AsignacionHorario::where('asignacion_horario.id_especialista', $idEsp)
                ->join('plataforma.horario as h', 'asignacion_horario.id_horario', '=', 'h.id')
                ->join('plataforma.consultorio as c', 'asignacion_horario.id_consultorio', '=', 'c.id')
                ->join('plataforma.sedes as s', 'c.id_sedes', '=', 's.id')
                ->join('plataforma.agenda as a', 'a.id_asignacion_horario', '=', 'asignacion_horario.id')
                ->join('plataforma.servicios_plataforma as sp', 'a.id_servicio_plataforma', '=', 'sp.id')
                ->join('administracion.persona as p', 'a.id_persona', '=', 'p.id')
                ->join('administracion.tipo_asegurado as ta', 'p.id_tipo_asegurado', '=', 'ta.id')
                ->join('administracion.users as u', 'a.id_user_created', '=', 'u.id')
                ->where('asignacion_horario.id_dia', $diaSemanaNumerico)
                ->where('h.turno', 'NOCHE')
                ->where('a.fecha_agenda', $fecha)
                ->select(
                    'asignacion_horario.id',
                    'h.turno',
                    'a.fecha_agenda',
                    'a.hora_agenda',
                    'a.anulacion_ficha',
                    'a.id_suspension_horario',
                    'a.motivo_anulacion',
                    'sp.nombre_servicio',
                    'p.nombres',
                    'p.p_apellido',
                    'p.s_apellido',
                    'p.matricula_seguro',
                    'c.numero_consultorio',
                    's.ubicacion',
                    's.piso',
                    'h.hora_inicio',
                    'h.hora_fin',
                    'h.turno',
                    'ta.tipo_asegurado',
                    'a.created_at',
                    'u.name'
                )
                ->orderBy('a.hora_agenda', 'asc')
                ->get();*/
            if (count($consulta) > 0) {
                $hinicio = $consulta->first()->asignacionHorarioAgenda->horarioAsignacionHorario->hora_inicio;

                $hfin = $consulta->first()->asignacionHorarioAgenda->horarioAsignacionHorario->hora_fin;
                $turno_registrado = $consulta->first()->asignacionHorarioAgenda->horarioAsignacionHorario->turno;
            } else {
                $hinicio = null;
                $hfin = null;
                $turno_registrado = null;
            }
        }


        $fecha_registro = Carbon::now()->format('d/m/Y');
        $nombre_usuario = auth()->user()->name;

        // Para abrir el blade vista del pdf con variables ya definidas
        $pdf = PDF::loadView(
            'Plataforma.AgendaEspecialista.pdfAgendaEspecialista',
            compact('nombres', 'especialidad', 'fecha_hoy', 'turno_registrado', 'hinicio', 'hfin', 'consulta', 'fecha_registro', 'nombre_usuario')
        )
            ->setPaper('letter', 'portrait');

        $nombre_pdf = "agenda_especialista";
        // Para abrir el pdf
        return $pdf->stream($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline'
        ]);
    }
    public function generarBoucher($id_asig_hor, $fecha)
    {
        $horarioMedico = AsignacionHorario::where('id', $id_asig_hor)
            ->with('horarioAsignacionHorario')
            ->firstOrFail();

        $horas = [];
        $inicio    = new DateTime($horarioMedico->horarioAsignacionHorario->hora_inicio);
        $fin       = new DateTime($horarioMedico->horarioAsignacionHorario->hora_fin);
        $intervalo = (int) $horarioMedico->horarioAsignacionHorario->intervalo;

        while ($inicio < $fin) {
            $horas[] = $inicio->format('H:i');
            $inicio->modify("+{$intervalo} minutes");
        }

        $fecha_hoy     = Carbon::parse($fecha)->format('Y-m-d');
        $fecha_boucher = Carbon::parse($fecha)->format('d-m-Y');

        $agendados = Agenda::where('fecha_agenda', $fecha_hoy)
            ->where('anulacion_ficha', false)
            ->where('id_asignacion_horario', $id_asig_hor)
            ->with([
                'asignacionHorarioAgenda',
                'asignacionHorarioAgenda.horarioAsignacionHorario',
                'asignacionHorarioAgenda.consultorioAsignacionHorario',
                'servicioPlataformaAgenda',
                'personaAgenda',
                'personaAgenda.tipoAseguradoPersona',
                'suspensionHorarioAgenda',
                'personaAgenda.autorizacionInteriorPersonaInteriorTitular.seguroInteriorAutorizacionInterior',
                'personaAgenda.convenioPersona'
            ])
            ->orderBy('hora_agenda', 'asc')
            ->get();
        // dd($agendados[0]->personaAgenda->convenioPersona);
        // dd($agendados[0]->personaAgenda);

        // if (!isset($agendados[0]->personaAgenda->convenioPersona)) {
        //     dd('No hay registros en convenioPersona');
        // } else {
        //     dd($agendados[0]->personaAgenda->convenioPersona);
        // }

        $agendadosPorHora = $agendados->keyBy(function ($a) {
            return Carbon::parse($a->hora_agenda)->format('H:i');
        });

        $aseguradoToCodigo = function ($persona) {
            if (!$persona) return '-';

            $tipo = $persona->id_tipo_asegurado;

            // 1) Casos existentes
            if ($tipo === null) return '-';
            if ($tipo === 1)    return 'T';
            if (in_array($tipo, [2, 3, 4, 5, 6, 7, 12, 13])) return 'B';
            if ($tipo === 11)   return 'E';
            if (in_array($tipo, [14, 15])) return 'PR';
            if ($tipo === 8) return 'C';
            if (in_array($tipo, [9, 10])) {
                $sigla = optional(
                    optional($persona->autorizacionInteriorPersonaInteriorTitular)->sortByDesc('id')->first()
                )->seguroInteriorAutorizacionInterior->sigla_seguro ?? null;

                return $sigla ?: 'C';
            }

            return '-';
        };

        $slots = [];
        foreach ($horas as $h) {
            $row = [
                'hora'        => $h,
                'estado'      => 'disponible',
                'paciente'    => null,
                'matricula'   => null,
                'tipo_codigo' => null,
                'suspension'  => null,
            ];

            if (isset($agendadosPorHora[$h])) {
                $a = $agendadosPorHora[$h];

                if ($a->personaAgenda === null) {
                    $row['estado']     = 'suspension';
                    $row['suspension'] = optional($a->suspensionHorarioAgenda)->observacion;
                } else {
                    $p = $a->personaAgenda;
                    $row['estado']      = 'ocupado';
                    $row['paciente']    = trim(sprintf('%s %s %s', $p->p_apellido, $p->s_apellido, $p->nombres));
                    $row['matricula']   = $p->matricula_seguro;
                    $row['tipo_codigo'] = $aseguradoToCodigo($p);
                }
            }

            $slots[] = $row;
        }

        $idEspecialista = $horarioMedico->id_especialista
            ?? optional($agendados->first())->asignacionHorarioAgenda->id_especialista;

        $datos_especialista = $idEspecialista
            ? Especialista::with('personaEspecialista', 'especialidadEspecialista','asignacionHorarioEspecialista.consultorio','asignacionHorarioEspecialista.consultorio.consultorioSedes')
            ->where('id', $idEspecialista)->first()
            : null;
        // dd($datos_especialista);
        // dd($datos_especialista->asignacionHorarioEspecialista[0]->consultorio->numero_consultorio);

        $usuario = auth()->user()->name ?? 'Sistema';

        $pdf = PDF::loadView(
            'Plataforma.AgendaEspecialista.pdfBoucherAgenda',
            [
                'slots'           => $slots,
                'datos_especialista' => $datos_especialista,
                'fecha_boucher'   => $fecha_boucher,
                'usuario'         => $usuario,
                'horarioMedico'   => $horarioMedico,
            ]
        )->setPaper('letter', 'portrait');

        return $pdf->stream('boucher_agenda_especialista.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline',
        ]);
    }

    public function generarBoucher_ant($id_asig_hor, $fecha)
    {
        // dd($id_asig_hor,$fecha);
        $horarioMedico = AsignacionHorario::where('id', $id_asig_hor)->with('horarioAsignacionHorario')->first();


        $horas = [];
        $inicio = new DateTime($horarioMedico->horarioAsignacionHorario->hora_inicio);
        $fin = new DateTime($horarioMedico->horarioAsignacionHorario->hora_fin);

        while ($inicio < $fin) {
            $horas[] = $inicio->format('H:i');
            $inicio->modify("+{$horarioMedico->horarioAsignacionHorario->intervalo} minutes");
        }


        $fecha_hoy = Carbon::parse($fecha)->format('Y-m-d');
        $fecha_boucher = Carbon::parse($fecha)->format('d-m-Y');
        $agendados = Agenda::where('fecha_agenda', $fecha_hoy)
            ->where('anulacion_ficha', false)
            ->where('id_asignacion_horario', $id_asig_hor)
            ->with([
                'asignacionHorarioAgenda',
                'asignacionHorarioAgenda.horarioAsignacionHorario',
                'asignacionHorarioAgenda.consultorioAsignacionHorario',
                'servicioPlataformaAgenda',
                'personaAgenda',
                'personaAgenda.tipoAseguradoPersona',
                'suspensionHorarioAgenda'
            ])
            ->orderby('hora_agenda', 'asc')
            ->get();
        //  dd($horas);
        // dd($agendados);
        $datos_especialista = Especialista::with('personaEspecialista', 'especialidadEspecialista')->where('id', $agendados[0]->asignacionHorarioAgenda->id_especialista)->first();
        $usuario = auth()->user()->name;
        $customPaper = array(0, 0, 226.77, 850);
        // dd($agendados);
        $pdf = PDF::loadView('Plataforma.AgendaEspecialista.pdfBoucherAgenda', ['agendados' => $agendados, 'datos_especialista' => $datos_especialista, 'fecha_boucher' => $fecha_boucher, 'usuario' => $usuario])
            ->setPaper('later', 'portrait');
        return $pdf->stream('boucher_agenda_especialista.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline'
        ]);
    }


    public function listaHoras($horaInicio, $horaFin, $inter, $fecha, $id_asig_hor)
    {
        $horaActual = Carbon::parse($fecha)->isToday() ? Carbon::now()->format('H:i') : '00:00';

        $horas = [];
        $inicio = Carbon::createFromFormat('H:i:s', $horaInicio);
        $fin = Carbon::createFromFormat('H:i:s', $horaFin);

        $hayFichaExtra = Agenda::with(['suspensionHorarioAgenda', 'servicioPlataformaAgenda'])
            ->where('fecha_agenda', $fecha)
            ->where('ficha_extra', true)
            ->where('id_asignacion_horario', $id_asig_hor)
            ->orderBy('hora_agenda', 'desc')
            ->first();

        if ($hayFichaExtra) {
            $fin = Carbon::createFromFormat('H:i:s', $hayFichaExtra->hora_agenda)->addMinutes($inter);
        }

        // horitas disponiblesssss
        while ($inicio->lt($fin)) {
            $horaActual = $inicio->format('H:i');
            $estadoHora = Agenda::with(['suspensionHorarioAgenda', 'servicioPlataformaAgenda'])
                ->where('fecha_agenda', $fecha)
                ->where('hora_agenda', $inicio->format('H:i:s'))
                ->whereNot('anulacion_ficha', true)
                ->where('id_asignacion_horario', $id_asig_hor)
                ->orderBy('id', 'desc')
                ->first();


            $inicio->addMinutes($inter);
        }
        dd($horas);
        return $horas;
    }
}
