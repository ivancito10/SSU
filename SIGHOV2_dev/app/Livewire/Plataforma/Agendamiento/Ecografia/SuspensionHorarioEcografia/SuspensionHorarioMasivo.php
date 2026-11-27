<?php

namespace App\Livewire\Plataforma\Agendamiento\Ecografia\SuspensionHorarioEcografia;

use App\Models\Plataforma\AgendaEcografia;
use App\Models\Plataforma\AsignacionHorario;
use App\Models\Plataforma\Motivo;
use App\Models\Plataforma\SuspensionHorarioEcografia;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class SuspensionHorarioMasivo extends Component
{   
    public $motivos;
    #[Validate]
    public $descripcion;
    #[Validate]
    public $fechaSuspension;
    #[Validate]
    public $idMotivoSuspension;
    #[Validate]
    public $horaInicioSuspension;
    #[Validate]
    public $horaFinSuspension;

    public $switchValidado;

    public $id_especialista_suspension;
    public $id_asignacion_horario_suspension;
    public $id_dia_suspension;

    public function render()
    {
        return view('livewire.Plataforma.agendamiento.ecografia.suspension-horario-ecografia.suspension-horario-masivo');
    }

    public function mount()
    {
        $this->switchValidado = false;
        $this->fechaSuspension = Carbon::now()->format('Y-m-d');
        $this->motivos = Motivo::whereNotIn('id', [1, 2, 3])->get();
    }

    public function rules()
    {
        return [
            'descripcion' => 'required',
            'fechaSuspension' => 'required|date',
            'idMotivoSuspension' => 'required',
            'horaInicioSuspension' => 'required',
            'horaFinSuspension' => 'required',
        ];
    }

    public function validarSeleccion()
    {
        $this->validate();
        try {
            $this->horaInicioSuspension = Carbon::parse($this->horaInicioSuspension)->format('H:i:s');
            $this->horaFinSuspension = Carbon::parse($this->horaFinSuspension)->format('H:i:s');

            //$this->validate();

            $especialistasSuspensionMasiva = [];

            $diaSuspension = Carbon::parse($this->fechaSuspension)->dayOfWeek;

            $asignacionesHorarios = AsignacionHorario::with([
                'horarioAsignacionHorario',
                'especialistaAsignacionHorario.personaEspecialista',
                'especialistaAsignacionHorario.especialidadEspecialista'
            ])
                ->where(function ($quericito) {
                    $quericito->whereNull('fecha_inicio')
                        ->orWhereDate('fecha_inicio', '<=', $this->fechaSuspension);
                })
                ->where(function ($otroQuericito) {
                    $otroQuericito->whereNull('fecha_fin')
                        ->orWhereDate('fecha_fin', '>=', $this->fechaSuspension);
                })
                ->where('id_dia', $diaSuspension)
                ->where('estado', true)
                ->whereHas('especialistaAsignacionHorario', function ($vacacion) {
                    $vacacion->whereDoesntHave('suspensionVacacionEspecialista')
                        ->orWhereHas('suspensionVacacionEspecialista', function ($otroQuery) {
                            $otroQuery->whereNot('estado', 'VACACION ACTIVA');
                        });
                })
                /*->whereHas('horarioAsignacionHorario', function ($horitas) {
                $horitas->where(function ($queryHoraInicio) {
                    $queryHoraInicio->whereTime('hora_inicio', '>=', $this->horaInicioSuspension)
                        ->whereTime('hora_inicio', '<=', $this->horaFinSuspension);
                })->orWhere(function ($queryHoraFin) {
                    $queryHoraFin->whereTime('hora_fin', '>=', $this->horaInicioSuspension)
                        ->whereTime('hora_fin', '<=', $this->horaFinSuspension);
                })->orWhere(function ($queryAmbos) {
                    $queryAmbos->whereTime('hora_inicio', '>=', $this->horaInicioSuspension)
                        ->whereTime('hora_fin', '<=', $this->horaFinSuspension);
                });
            })*/
                ->whereHas('horarioAsignacionHorario', function ($horitas) {
                    $horitas->whereTime('hora_inicio', '<', $this->horaFinSuspension)
                        ->whereTime('hora_fin', '>', $this->horaInicioSuspension);
                })
                ->get();

            foreach ($asignacionesHorarios as $key => $asignacionHorario) {
                $especialistasSuspensionMasiva[] = [
                    'id_especialista' => $asignacionHorario->id_especialista,
                    'id_asignacion_horario' => $asignacionHorario->id,
                    'id_dia' => $diaSuspension,
                    'nombre_especialista' => implode(' ', array_filter([
                        $asignacionHorario->especialistaAsignacionHorario->personaEspecialista->p_apellido,
                        $asignacionHorario->especialistaAsignacionHorario->personaEspecialista->s_apellido,
                        $asignacionHorario->especialistaAsignacionHorario->personaEspecialista->nombres,
                    ])),
                    'carnet_especialista' => implode(' - ', array_filter([
                        $asignacionHorario->especialistaAsignacionHorario->personaEspecialista->ci,
                        $asignacionHorario->especialistaAsignacionHorario->personaEspecialista->complemento,
                    ])),
                    'especialidad' => $asignacionHorario->especialistaAsignacionHorario->especialidadEspecialista->especialidad,
                    'estado' => true,
                    'especialista_imagenologia' => $asignacionHorario->especialistaAsignacionHorario->especialista_imagenologia,
                ];
            }

            $this->dispatch('suspensionMasiva', esp: $especialistasSuspensionMasiva);
            $this->switchValidado = true;
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error en validarSeleccion - suspencion masiva", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    #[On('especialistaSeleccionado')]
    public function especialistaSeleccionado($id_especialista, $id_asignacion_horario, $id_dia)
    {
        try {
            $this->id_especialista_suspension = null;
            $this->id_asignacion_horario_suspension = null;
            $this->id_dia_suspension = null;

            $existeAgenda = AgendaEcografia::where('fecha_agenda', $this->fechaSuspension)
                ->whereBetween('hora_agenda', [$this->horaInicioSuspension, $this->horaFinSuspension])
                ->where('id_asignacion_horario', $id_asignacion_horario)
                ->whereNot('anulacion_ficha', true)
                ->get();

            //dd($existeAgenda);

            if (count($existeAgenda) === 0) {
                $this->id_especialista_suspension = $id_especialista;
                $this->id_asignacion_horario_suspension = $id_asignacion_horario;
                $this->id_dia_suspension = $id_dia;
                $this->dispatch('preguntarRegistroSuspension');
            } else {
                $this->dispatch('tieneAgendasYOSuspensiones');
            }
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error en especialistaSeleccionado", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    #[On('registrarSuspension')]
    public function registrarSuspension(Request $request)
    {
        try {

            $horariosASuspender = [];

            $existeAgenda = AgendaEcografia::where('fecha_agenda', $this->fechaSuspension)
                ->whereBetween('hora_agenda', [$this->horaInicioSuspension, $this->horaFinSuspension])
                ->where('id_asignacion_horario', $this->id_asignacion_horario_suspension)
                ->whereNot('anulacion_ficha', true)
                ->get();

            if (count($existeAgenda) === 0) {

                $asignacionHorario = AsignacionHorario::with(['horarioAsignacionHorario'])
                    ->where('id', $this->id_asignacion_horario_suspension)
                    ->where('id_especialista', $this->id_especialista_suspension)
                    ->where('id_dia', $this->id_dia_suspension)
                    ->first();

                if ($asignacionHorario) {

                    try {
                        $numeroHorariosASuspender = Carbon::parse($this->horaFinSuspension)->diffInMinutes($this->horaInicioSuspension) / $asignacionHorario->horarioAsignacionHorario->intervalo;
                        $horaInicial = Carbon::parse($this->horaInicioSuspension);

                        //REGISTRO DE UNA SOLA SUSPENSION POR ESPECIALISTA
                        $nroSuspension = $this->nuevoNumeroSuspension();

                        $horaInicialHorario = Carbon::parse($asignacionHorario->horarioAsignacionHorario->hora_inicio);

                        if ($horaInicial->lt($horaInicialHorario)) {
                            $horaInicial = $horaInicialHorario;
                        }

                        $nuevaSuspension = SuspensionHorarioEcografia::create([
                            'fecha_suspension' => $this->fechaSuspension,
                            'observacion' => $this->descripcion,
                            'estado' => true,
                            'id_motivo' => $this->idMotivoSuspension,
                            'id_especialista' => $this->id_especialista_suspension,
                            'nro_suspension' => $nroSuspension,
                            'id_user_created' => auth()->user()->id,
                        ]);

                        //dd($nuevaSuspension);

                        //FIN REGISTRO DE UNA SOLA SUSPENSION POR ESPECIALISTA

                        if ($nuevaSuspension) {
                            for ($i = 0; $i < $numeroHorariosASuspender; $i++) {
                                if ($horaInicial->lt(Carbon::parse($asignacionHorario->horarioAsignacionHorario->hora_fin))) {
                                    $this->registrarSuspensionAgenda(
                                        $request->ip(),
                                        $this->fechaSuspension,
                                        $horaInicial,
                                        $this->id_asignacion_horario_suspension,
                                        $nuevaSuspension->id,
                                        Motivo::find($this->idMotivoSuspension)->nombre_motivo
                                    );
                                    $horariosASuspender[] = [Carbon::parse($horaInicial)->format('H:i:s')];
                                    $horaInicial = $horaInicial->addMinutes($asignacionHorario->horarioAsignacionHorario->intervalo);
                                }
                            }
                            $this->dispatch('suspensionCreada');
                        } else {
                            $this->dispatch('errorCreacionAgendaAlSuspender');
                        }

                        //PARA ACTUALIZAR LA TABLA 
                        $this->dispatch('actualizarEstadoEspecialista', [
                            'id_especialista' => $this->id_especialista_suspension,
                            'id_asignacion_horario' => $this->id_asignacion_horario_suspension,
                            'id_dia' => $this->id_dia_suspension,
                        ]);
                        //FIN PARA ACTUALIZAR LA TABLA

                    } catch (\Throwable $th) {
                        $this->dispatch('errorCreacionAgendaAlSuspender');
                    }
                }
            } else {
                $this->id_especialista_suspension = null;
                $this->id_asignacion_horario_suspension = null;
                $this->id_dia_suspension = null;
                $this->dispatch('tieneAgendasYOSuspensiones');
            }
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error en registrarSuspension", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    public function registrarSuspensionAgenda(
        $ip,
        $fechaSuspension,
        $horaInicio,
        $idAsignacionHorarioSuspension,
        $idSuspension,
        $motivoSuspension
    ) {
        try {
            //$suspensionHorarioReplica = new ReplicarAgendaS1();

            $horaC = Carbon::parse($horaInicio)->format('H');
            $minutosC = Carbon::parse($horaInicio)->format('i');
            $diaC = Carbon::parse($fechaSuspension)->format('d');
            $mesC = Carbon::parse($fechaSuspension)->format('m');
            $gestionC = Carbon::parse($fechaSuspension)->format('Y');
            $clave_unica = $idAsignacionHorarioSuspension . $horaC . $minutosC . $diaC . $mesC . $gestionC;

            $existeAgenda = AgendaEcografia::where('clave_unica', $clave_unica)
                ->first();

            if ($existeAgenda) {
                $this->dispatch('existeAgenda');
            } else {
                $nuevaAgenda = AgendaEcografia::create([
                    'fecha_agenda' => $fechaSuspension,
                    'hora_agenda' => $horaInicio,
                    'confirmado' => true,
                    'id_servicio_plataforma' => 1,
                    'clave_unica' => $clave_unica,
                    'ficha_extra' => false,
                    'id_persona' => null,
                    'id_asignacion_horario' => $idAsignacionHorarioSuspension,
                    'anulacion_ficha' => false,
                    'ip' => $ip,
                    'nombre_equipo' => gethostname(),
                    'id_suspension_horario_ecografia' => $idSuspension,
                    'id_user_created' => auth()->user()->id,
                ]);

                //dd($nuevaAgenda);

                //s$suspensionHorarioReplica->replicarAgendaSuspendida($nuevaAgenda->id, $motivoSuspension);

                if (!$nuevaAgenda) {
                    $this->dispatch('errorCreacionAgendaAlSuspender');
                }
            }
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error en registrarSuspensionAgenda", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    public function nuevoNumeroSuspension()
    {
        $gestionActual = Carbon::now()->format('Y');

        $ultimaSuspension = SuspensionHorarioEcografia::whereYear('created_at', $gestionActual)
            ->orderBy('nro_suspension', 'desc')
            ->first();

        $nro_suspension = $ultimaSuspension ? $ultimaSuspension->nro_suspension + 1 : 1;

        return $nro_suspension;
    }

    public function volver()
    {
        return redirect()->route('plataforma.suspension-horario-ecografia.index');
    }
}
