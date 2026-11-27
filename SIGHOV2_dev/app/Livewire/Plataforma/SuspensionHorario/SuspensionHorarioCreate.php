<?php

namespace App\Livewire\Plataforma\SuspensionHorario;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Plataforma\Especialista;
use App\Models\Plataforma\Dia;
use App\Models\Plataforma\Horario;
use App\Models\Plataforma\AsignacionHorario;
use App\Models\Plataforma\Consultorio;
use App\Models\Plataforma\Agenda;
use App\Models\Plataforma\Motivo;
use App\Models\Plataforma\SuspensionHorario;
use App\Models\Administracion\Especialidad;
use App\Models\Administracion\Persona;
use App\Models\Administracion\Departamento;
use Illuminate\Support\Facades\Storage;
use App\Services\GrupoFamiliarPersona;
use App\Services\ListadoAsegurados;
use App\Services\ReplicarAgendaS1;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;

class SuspensionHorarioCreate extends Component
{
    public function render()
    {
        return view('livewire.Plataforma.suspension-horario.suspension-horario-create');
    }

    public $idMedico;
    #[Validate]
    public $fecha_suspension;

    //DATOS ESPECIALISTA

    public $nombres;
    public $carnet;
    public $especialidad;
    public $grado_academico;
    public $fecha_contrato_inicio;
    public $fecha_contrato_fin;
    public $permanente;
    public $foto;

    //SWITCHES PARA LAS FUNCIONES

    public $switchMotivo;
    public $switchTurno;
    public $variosTurnos;
    public $switchPersona;
    public $switchMultiplesResultados;
    public $switchSeleccionado;
    public $switchMostrarHorarios;

    // PARA LOS DATOS DE LA PERSONA SELECCIONADA

    public $matriculaPersona;
    public $carnetPersona;
    public $complementoPersona;
    public $nombrePersona;
    public $primerApellidoPersona;
    public $segundoApellidoPersona;

    public $existePersona;
    #[Validate]
    public $persona;

    //PARA EL RESTO VIEJITOOOO

    #[Validate]
    public $idMotivoSuspension;
    public $motivos;
    #[Validate]
    public $turnoSeleccionado;
    public $turnos;
    #[Validate]
    public $observacion;
    public $turnoLiteral;
    #[Validate]
    public $horaInicio;
    #[Validate]
    public $horaFin;
    public $horariosInicio;
    public $horariosFin;

    public $horaFinHorarioSeleccionado;
    public $intervaloHorarioSeleccionado;

    public $nro_suspension;
    public $nro_suspension_gestion;

    public function rules()
    {
        $rules = [
            'fecha_suspension' => 'required|date',
            'turnoSeleccionado' => 'required',
            'idMotivoSuspension' => 'required',
            'horaInicio' => 'required',
            'horaFin' => 'required',
        ];

        if (!($this->idMotivoSuspension === "1" || $this->idMotivoSuspension === "2" || $this->idMotivoSuspension === "3")) {
            $rules['observacion'] = 'required';
        }

        return $rules;
    }

    public function mount()
    {
        $this->switchMotivo = false;
        $this->fecha_suspension = Carbon::now()->format('Y-m-d');
        $this->mostrarEspecialista($this->idMedico);
        $this->verificarEspecialistaHabilitado();
        $this->nuevoNumeroSuspension();
    }

    public function nuevoNumeroSuspension()
    {
        $gestionActual = Carbon::now()->format('Y');

        $ultimaSuspension = SuspensionHorario::whereYear('created_at', $gestionActual)
            ->orderBy('nro_suspension', 'desc')
            ->first();

        $this->nro_suspension = $ultimaSuspension ? $ultimaSuspension->nro_suspension + 1 : 1;
        $this->nro_suspension_gestion = $this->nro_suspension . '/' . $gestionActual;
    }

    public function mostrarEspecialista($idEsp)
    {
        if ($idEsp) {
            $datosEspecialista = Especialista::where('id', $idEsp)
                ->with('personaEspecialista', 'especialidadEspecialista')
                ->first();

            $this->fecha_contrato_inicio = $datosEspecialista->fecha_contrato_inicio;
            $this->fecha_contrato_fin = $datosEspecialista->fecha_contrato_fin;
            $this->permanente = $datosEspecialista->permanente;
            $this->grado_academico = $datosEspecialista->grado_academico;
            $this->foto = $this->obtenerImagenBase64($datosEspecialista->foto);
            $this->nombres = trim("{$datosEspecialista->personaEspecialista->nombres} {$datosEspecialista->personaEspecialista->p_apellido} {$datosEspecialista->personaEspecialista->s_apellido}");
            $this->carnet = $datosEspecialista->personaEspecialista->ci . ($datosEspecialista->personaEspecialista->complemento ? " {$datosEspecialista->personaEspecialista->complemento}" : '');
            $this->especialidad = $datosEspecialista->especialidadEspecialista->especialidad;
        }
    }

    private function obtenerImagenBase64($idFoto)
    {
        try {
            if ($idFoto) {
                $path = 'Plataforma/Especialista/Fotos/' . $idFoto;

                $imageData = Storage::disk('ftp')->get($path);

                $foto = base64_encode($imageData);
                return $foto;
            } else {
                return null;
            }
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error en obtener imagen suspencion horario", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
            return null;
        }
    }

    public function verificarEspecialistaHabilitado()
    {
        $this->switchTurno = false;
        $this->switchMotivo = false;
        $this->persona = null;
        $this->switchSeleccionado = false;
        $this->idMotivoSuspension = null;
        $this->switchPersona = false;
        $this->switchMultiplesResultados = false;
        $this->switchSeleccionado = false;
        $this->switchMostrarHorarios = false;

        $diaFechaSuspension = Carbon::parse($this->fecha_suspension)->dayOfWeek;

        $especialistaDisponible = Especialista::with(['suspensionVacacionEspecialista'])
            ->where('id', $this->idMedico)
            ->where('estado', true)
            ->first();

        if ($especialistaDisponible) {
            //PARA CARGAR LOS MOTIVOS
            $this->mostrarTurnos();
            $this->switchTurno = true;

            if ($especialistaDisponible->suspensionVacacionEspecialista) {
                if ($especialistaDisponible->suspensionVacacionEspecialista
                    ->where('estado', 'VACACION ACTIVA')
                    ->where('fecha_inicio', '<=', $this->fecha_suspension)
                    ->where('fecha_fin', '>=', $this->fecha_suspension)
                    ->first()
                ) {
                    //dd('TIENE VACACION');
                    $this->switchTurno = false;
                    $this->switchMotivo = false;
                    $this->dispatch('tieneVacacion');
                }
            }

            $existeHorarioAsignado = AsignacionHorario::where('estado', true)
                ->where('id_dia', $diaFechaSuspension)
                ->where('id_especialista', $this->idMedico)
                ->where(function ($query) {
                    $query->whereNull('fecha_inicio')
                        ->orWhereDate('fecha_inicio', '<=', $this->fecha_suspension);
                })
                ->where(function ($query) {
                    $query->whereNull('fecha_fin')
                        ->orWhereDate('fecha_fin', '>=', $this->fecha_suspension);
                })
                ->first();

            if (!$existeHorarioAsignado) {
                //dd('NO TIENE HORARIO');
                $this->switchTurno = false;
                $this->switchMotivo = false;
                $this->dispatch('noTieneHorario');
            }
        }
    }

    public function mostrarTurnos()
    {
        $diaFechaSuspension = Carbon::parse($this->fecha_suspension)->dayOfWeek;

        $turnosAsignados = AsignacionHorario::with(['horarioAsignacionHorario'])
            ->where('id_especialista', $this->idMedico)
            ->where(function ($query) {
                $query->whereNull('fecha_inicio')
                    ->orWhereDate('fecha_inicio', '<=', $this->fecha_suspension);
            })
            ->where(function ($query) {
                $query->whereNull('fecha_fin')
                    ->orWhereDate('fecha_fin', '>=', $this->fecha_suspension);
            })
            ->where('id_dia', $diaFechaSuspension)
            ->where('estado', true)
            ->get();

        if (count($turnosAsignados) > 0) {
            if (count($turnosAsignados) < 2) {
                //dd($turnosAsignados);
                $this->turnos = $turnosAsignados;
                $this->turnoSeleccionado = $turnosAsignados->first()->id;
                $this->turnoLiteral = AsignacionHorario::with(['horarioAsignacionHorario'])->find($this->turnoSeleccionado)->horarioAsignacionHorario->turno;
                $this->variosTurnos = false;
                $this->motivos = Motivo::all();
                $this->switchMotivo = true;
                //dd($this->turnoSeleccionado);
            } else {
                $this->turnos = $turnosAsignados;
                $this->variosTurnos = true;
            }
        }
    }

    public function mostrarMotivos()
    {
        $this->switchPersona = false;
        $this->persona = null;
        $this->switchSeleccionado = false;
        $this->idMotivoSuspension = null;
        $this->motivos = Motivo::all();
        $this->switchMotivo = true;
    }

    public function mostrarHorarios()
    {
        $this->switchPersona = false;
        $this->persona = null;
        $this->switchSeleccionado = false;
        $this->switchMostrarHorarios = true;

        if ($this->idMotivoSuspension === "1" || $this->idMotivoSuspension === "2" || $this->idMotivoSuspension === "3") {
            $this->switchPersona = true;
            $this->switchMostrarHorarios = false;
        }

        $horarioAsignado = AsignacionHorario::with(['horarioAsignacionHorario'])->find($this->turnoSeleccionado);
        $this->horariosInicio = $this->listarHoras(
            $horarioAsignado->horarioAsignacionHorario->hora_inicio,
            $horarioAsignado->horarioAsignacionHorario->hora_fin,
            $horarioAsignado->horarioAsignacionHorario->intervalo
        );

        $this->horaFinHorarioSeleccionado = $horarioAsignado->horarioAsignacionHorario->hora_fin;
        $this->intervaloHorarioSeleccionado = $horarioAsignado->horarioAsignacionHorario->intervalo;

        $this->horariosFin = $this->listarHorasFinales(
            $horarioAsignado->horarioAsignacionHorario->hora_inicio,
            $horarioAsignado->horarioAsignacionHorario->hora_fin,
            $horarioAsignado->horarioAsignacionHorario->intervalo
        );
        //dd($horas);
        //dd(AsignacionHorario::with(['horarioAsignacionHorario'])->find($this->turnoSeleccionado));
    }

    public function buscarPersona()
    {
        $this->switchMultiplesResultados = false;
        $this->switchSeleccionado = false;
        $this->persona = null;

        $carnetBusqueda = trim($this->carnetPersona) == '' ? null : mb_strtoupper(trim($this->carnetPersona));
        $complementoBusqueda = trim($this->complementoPersona) == '' ? null : mb_strtoupper(trim($this->complementoPersona));
        $matriculaBusqueda = trim($this->matriculaPersona) == '' ? null : mb_strtoupper(trim($this->matriculaPersona));
        $nombresBusqueda = trim($this->nombrePersona) == '' ? null : mb_strtoupper(trim($this->nombrePersona));
        $pApellidoBusqueda = trim($this->primerApellidoPersona) == '' ? null : mb_strtoupper(trim($this->primerApellidoPersona));
        $sApellidoBusqueda = trim($this->segundoApellidoPersona) == '' ? null : mb_strtoupper(trim($this->segundoApellidoPersona));

        if (
            !($carnetBusqueda === null && $complementoBusqueda === null && $matriculaBusqueda === null && $nombresBusqueda === null &&
                $pApellidoBusqueda === null && $sApellidoBusqueda === null)
        ) {
            $this->existePersona = Persona::query()
                ->when($carnetBusqueda, function ($query) use ($carnetBusqueda) {
                    return $query->where('ci', 'LIKE', '%' . $carnetBusqueda . '%');
                })
                ->when($complementoBusqueda, function ($query) use ($complementoBusqueda) {
                    return $query->where('complemento', 'LIKE', '%' . $complementoBusqueda . '%');
                })
                ->when($matriculaBusqueda, function ($query) use ($matriculaBusqueda) {
                    return $query->where('matricula_seguro', 'LIKE', '%' . $matriculaBusqueda . '%');
                })
                ->when($nombresBusqueda, function ($query) use ($nombresBusqueda) {
                    return $query->where('nombres', 'LIKE', '%' . $nombresBusqueda . '%');
                })
                ->when($pApellidoBusqueda, function ($query) use ($pApellidoBusqueda) {
                    return $query->where('p_apellido', 'LIKE', '%' . $pApellidoBusqueda . '%');
                })
                ->when($sApellidoBusqueda, function ($query) use ($sApellidoBusqueda) {
                    return $query->where('s_apellido', 'LIKE', '%' . $sApellidoBusqueda . '%');
                })
                ->whereNot('id_tipo_asegurado', 0)
                ->where('afiliado', true)
                ->with(['tipoAseguradoPersona'])
                ->get();

            if (count($this->existePersona) > 0) {
                if (count($this->existePersona) > 1) {
                    $this->switchMultiplesResultados = true;
                } else {
                    $this->persona = $this->existePersona->first();
                    $this->switchSeleccionado = true;
                    $this->switchPersona = false;
                    $this->switchMostrarHorarios = true;
                }
            } else {
                $this->dispatch('noSeEncontraronDatos');
            }
        }

        $this->carnetPersona = null;
        $this->complementoPersona = null;
        $this->matriculaPersona = null;
        $this->nombrePersona = null;
        $this->primerApellidoPersona = null;
        $this->segundoApellidoPersona = null;
    }

    public function seleccionarPersona($idPersona)
    {
        $idPersona = (int) $idPersona;
        $this->persona = Persona::with(['tipoAseguradoPersona'])->find($idPersona);
        $this->switchSeleccionado = true;
        $this->switchMultiplesResultados = false;
        $this->switchPersona = false;
        $this->switchMostrarHorarios = true;
    }

    public function eliminarPersonaSeleccionada()
    {
        $this->persona = null;
        $this->switchSeleccionado = false;
        $this->switchPersona = true;
        $this->switchMostrarHorarios = false;
    }

    public function listarHoras($horaInicio, $horaFin, $inter)
    {
        try {
            $horaActual = Carbon::parse($this->fecha_suspension)->isToday() ? Carbon::now()->format('H:i') : '00:00';
            $horas = [];
            $inicio = Carbon::createFromFormat('H:i:s', $horaInicio);
            $fin = Carbon::createFromFormat('H:i:s', $horaFin);

            // horitas disponiblesssss
            while ($inicio->lt($fin)) {
                $horaActual = $inicio->format('H:i');
                $estadoHora = Agenda::with(['suspensionHorarioAgenda', 'servicioPlataformaAgenda'])
                    ->where('fecha_agenda', $this->fecha_suspension)
                    ->where('hora_agenda', $inicio->format('H:i:s'))
                    ->whereNot('anulacion_ficha', true)
                    ->where('id_asignacion_horario', $this->turnoSeleccionado)
                    ->orderBy('id', 'desc')
                    ->first();

                $horas[] = $this->procesarHora($estadoHora, $horaActual);
                $inicio->addMinutes($inter);
            }
            //dd($horas);
            return $horas;
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al listar Horas", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);

            return null;
        }
    }

    public function procesarHora($estadoHora, $horaActual)
    {
        $hora = [
            'hora' => $horaActual,
            'matricula' => null,
            'asegurado' => null,
            'institucion' => null,
            'tipo_asegurado' => null,
            'estado' => 'LIBRE',
            'observacion' => null,
            'motivo_suspension' => null,
            'servicio' => null,
            'ficha_extra' => false,
        ];

        if ($estadoHora) {
            $hora['id_agenda'] = $estadoHora->id;
            if ($estadoHora->anulacion_ficha) {
                return $hora;
            } else {


                if ($estadoHora->id_persona) {


                    $busquedaPersonaId = new ListadoAsegurados();
                    $personaOcupado = $busquedaPersonaId->busquedaAfiliadoPorId($estadoHora->id_persona);

                    $hora['matricula'] = $personaOcupado['codigo_institucional']
                        ? $personaOcupado['matricula_seguro'] . ' - ' . $personaOcupado['codigo_institucional']
                        : $personaOcupado['matricula_seguro'];

                    $hora['asegurado'] = $personaOcupado['p_apellido'] . ' ' . $personaOcupado['s_apellido'] . ' ' . $personaOcupado['nombres'];
                    $hora['institucion'] = $personaOcupado['nombre_institucion'];
                    $hora['tipo_asegurado'] = $personaOcupado['tipo_asegurado'];
                    $hora['servicio'] = $estadoHora->servicioPlataformaAgenda->nombre_servicio ?? null;

                    if ($estadoHora->id_suspension_horario) {
                        $hora['estado'] = 'SUSPENDIDO';
                        $hora['observacion'] = $estadoHora->suspensionHorarioAgenda->observacion;
                        $hora['motivo_suspension'] = Motivo::find($estadoHora->suspensionHorarioAgenda->id_motivo)->nombre_motivo ?? null;
                    } else {
                        $hora['estado'] = 'OCUPADO';
                    }

                    if ($estadoHora->ficha_extra) {
                        $hora['ficha_extra'] = true;
                    }
                } else {
                    $hora['estado'] = $estadoHora->id_suspension_horario ? 'SUSPENDIDO' : 'OCUPADO';
                    $hora['observacion'] = $estadoHora->suspensionHorarioAgenda->observacion ?? null;
                    $hora['motivo_suspension'] = $estadoHora->id_suspension_horario
                        ? Motivo::find($estadoHora->suspensionHorarioAgenda->id_motivo)->nombre_motivo ?? null
                        : null;
                    $hora['servicio'] = $estadoHora->servicioPlataformaAgenda->nombre_servicio ?? null;

                    if ($estadoHora->ficha_extra) {
                        $hora['ficha_extra'] = true;
                    }
                }
            }
        }
        return $hora;
    }

    public function actualizarHorasFinales()
    {
        $this->horaFin = null;

        $horaInicioSeleccionada = Carbon::parse($this->horaInicio)->format('H:i:s');

        $this->horariosFin = $this->listarHorasFinales(
            $horaInicioSeleccionada,
            $this->horaFinHorarioSeleccionado,
            $this->intervaloHorarioSeleccionado
        );
    }

    public function listarHorasFinales($horaInicio, $horaFin, $inter)
    {
        $horaActual = Carbon::parse($this->fecha_suspension)->isToday() ? Carbon::now()->format('H:i') : '00:00';

        $horas = [];
        $inicio = Carbon::createFromFormat('H:i:s', $horaInicio);
        $fin = Carbon::createFromFormat('H:i:s', $horaFin);

        // horitas disponiblesssss
        while ($inicio->lte($fin)) {
            $horaActual = $inicio->format('H:i');
            $estadoHora = Agenda::with(['suspensionHorarioAgenda', 'servicioPlataformaAgenda'])
                ->where('fecha_agenda', $this->fecha_suspension)
                ->where('hora_agenda', $inicio->format('H:i:s'))
                ->whereNot('anulacion_ficha', true)
                ->where('id_asignacion_horario', $this->turnoSeleccionado)
                ->orderBy('id', 'desc')
                ->first();
            $horas[] = $this->procesarHoraFinal($estadoHora, $horaActual);
            $inicio->addMinutes($inter);
        }
        //dd($horas);
        array_shift($horas);
        //dd($horas);
        /*$encontrarNulito = array_search(null, $horas, true);
        if ($encontrarNulito !== false) {
            $horas = array_slice($horas, 0, $encontrarNulito + 1);
        }*/

        if (!empty($horas) && end($horas) !== null) {
            $horas[key($horas)]['horario_excluido'] = true;
        }
        //dd($horas);
        $contador = 0;
        foreach ($horas as $key => $hora) {
            if (isset($hora['horario_excluido']) && $hora['horario_excluido'] === true) {
                $contador++;
                if ($contador === 2) {
                    $horas = array_slice($horas, 0, $key);
                    break;
                }
            }
        }

        //dd($horas);
        return $horas;
    }


    public function procesarHoraFinal($estadoHora, $horaActual)
    {
        $hora = [
            'hora' => $horaActual,
            'matricula' => null,
            'asegurado' => null,
            'institucion' => null,
            'tipo_asegurado' => null,
            'estado' => 'LIBRE',
            'observacion' => null,
            'motivo_suspension' => null,
            'servicio' => null,
            'ficha_extra' => false,
        ];

        /*if ($estadoHora) {
            $hora['id_agenda'] = $estadoHora->id;

            if ($estadoHora->id_persona) {
                if ($estadoHora->anulacion_ficha) {
                    return $hora;
                }

                $busquedaPersonaId = new ListadoAsegurados();
                $personaOcupado = $busquedaPersonaId->busquedaAfiliadoPorId($estadoHora->id_persona);

                $hora['matricula'] = $personaOcupado['codigo_institucional']
                    ? $personaOcupado['matricula_seguro'] . ' - ' . $personaOcupado['codigo_institucional']
                    : $personaOcupado['matricula_seguro'];

                $hora['asegurado'] = $personaOcupado['p_apellido'] . ' ' . $personaOcupado['s_apellido'] . ' ' . $personaOcupado['nombres'];
                $hora['institucion'] = $personaOcupado['nombre_institucion'];
                $hora['tipo_asegurado'] = $personaOcupado['tipo_asegurado'];
                $hora['servicio'] = $estadoHora->servicioPlataformaAgenda->nombre_servicio ?? null;

                if ($estadoHora->id_suspension_horario) {
                    $hora['estado'] = 'SUSPENDIDO';
                    $hora['observacion'] = $estadoHora->suspensionHorarioAgenda->observacion;
                    $hora['motivo_suspension'] = Motivo::find($estadoHora->suspensionHorarioAgenda->id_motivo)->nombre_motivo ?? null;
                } else {
                    $hora['estado'] = 'OCUPADO';
                }

                if ($estadoHora->ficha_extra) {
                    $hora['ficha_extra'] = true;
                }
            } else {
                $hora['estado'] = $estadoHora->id_suspension_horario ? 'SUSPENDIDO' : 'OCUPADO';
                $hora['observacion'] = $estadoHora->suspensionHorarioAgenda->observacion ?? null;
                $hora['motivo_suspension'] = $estadoHora->id_suspension_horario
                    ? Motivo::find($estadoHora->suspensionHorarioAgenda->id_motivo)->nombre_motivo ?? null
                    : null;
                $hora['servicio'] = $estadoHora->servicioPlataformaAgenda->nombre_servicio ?? null;

                if ($estadoHora->ficha_extra) {
                    $hora['ficha_extra'] = true;
                }
            }
            $hora['horario_excluido'] = true;
        } else {
            $hora['horario_excluido'] = false;
        }*/

        if ($estadoHora) {
            $hora['horario_excluido'] = true;
        } else {
            $hora['horario_excluido'] = false;
        }

        return $hora;
    }

    public function preguntarGuardarSuspension()
    {
        $this->dispatch('preguntarGuardar');
    }

    #[On('guardarAnulacion')]
    public function guardarAnulacion(Request $request)
    {
        try {
            $suspensionHorarioReplica = new ReplicarAgendaS1();
            //dd($this->horaInicio, $this->intervaloHorarioSeleccionado, Carbon::parse($this->horaInicio)->addMinutes($this->intervaloHorarioSeleccionado)->format('H:i'));

            $horaInicio = Carbon::parse($this->horaInicio);
            $horaFin = Carbon::parse($this->horaFin);
            $switchAgenditaCreada = true;
            //dd($horaInicio, $horaFin);

            $this->validate();

            $nuevaSuspension = SuspensionHorario::create([
                'fecha_suspension' => $this->fecha_suspension,
                'observacion' => $this->observacion,
                'estado' => true,
                'id_motivo' => $this->idMotivoSuspension,
                'id_especialista' => $this->idMedico,
                'nro_suspension' => $this->nro_suspension,
                'id_user_created' => auth()->user()->id,
            ]);

            if (($this->idMotivoSuspension === "1" || $this->idMotivoSuspension === "2" || $this->idMotivoSuspension === "3") && $this->persona === null) {
                $this->dispatch('sinPersona');
                return;
            } else {
                while ($horaInicio->lt($horaFin)) {

                    $horaC = Carbon::parse($horaInicio)->format('H');
                    $minutosC = Carbon::parse($horaInicio)->format('i');
                    $diaC = Carbon::parse($this->fecha_suspension)->format('d');
                    $mesC = Carbon::parse($this->fecha_suspension)->format('m');
                    $gestionC = Carbon::parse($this->fecha_suspension)->format('Y');
                    $clave_unica = $this->turnoSeleccionado . $horaC . $minutosC . $diaC . $mesC . $gestionC;

                    $existeAgenda = Agenda::where('clave_unica', $clave_unica)
                        ->first();

                    if ($existeAgenda) {
                        $this->dispatch('existeAgenda');
                    } else {

                        if ($nuevaSuspension) {
                            $nuevaAgenda = Agenda::create([
                                'fecha_agenda' => $this->fecha_suspension,
                                'hora_agenda' => $horaInicio,
                                'confirmado' => true,
                                'id_servicio_plataforma' => 1,
                                'clave_unica' => $clave_unica,
                                'ficha_extra' => false,
                                'id_persona' => $this->persona ? $this->persona->id : null,
                                'id_asignacion_horario' => $this->turnoSeleccionado,
                                'anulacion_ficha' => false,
                                'ip' => $request->ip(),
                                'nombre_equipo' => gethostname(),
                                'id_suspension_horario' => $nuevaSuspension->id,
                                'id_user_created' => auth()->user()->id,
                            ]);

                            $suspensionHorarioReplica->replicarAgendaSuspendida($nuevaAgenda->id, Motivo::find($this->idMotivoSuspension)->nombre_motivo);

                            if (!$nuevaAgenda) {
                                $switchAgenditaCreada = false;
                            }
                        }
                    }
                    $horaInicio->addMinutes($this->intervaloHorarioSeleccionado)->format('H:i');
                }

                if ($switchAgenditaCreada) {
                    $this->dispatch('registroExistoso');
                    return redirect()->route('plataforma.suspension-horario.index');
                }
            }
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al guardar anulacion", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
            return null;
        }
    }


























































    /*public function AbrirModal()
    {

        if ($this->hora_inicio_es == null || $this->hora_fin_es == null  || $this->intervalo_es == null) {
            $this->dispatch('sinHorario');
            return;
        } else {
            $this->horasDisponibles = $this->listaHorasVer($this->hora_inicio_es, $this->hora_fin_es, $this->intervalo_es);
        }

        //  dd($this->horasDisponibles,$this->id_asignacion_especialista);

        $this->dispatch('AbrilModal');
    }*/


    /*public function GuardarAnulacion($horario)
    {
        $this->countd = 0;

        DB::beginTransaction();
        try {
            $horaC = Carbon::parse($horario)->format('H');
            $minutosC = Carbon::parse($horario)->format('i');
            $diaC = Carbon::parse($this->fecha)->format('d');
            $mesC = Carbon::parse($this->fecha)->format('m');
            $gestionC = Carbon::parse($this->fecha)->format('Y');
            $clave_unica = $this->id_asignacion_especialista . $horaC . $minutosC . $diaC . $mesC . $gestionC;
            $existeAgenda = Agenda::where('clave_unica', $clave_unica)
                ->first();
            // dd($existeAgenda);
            //dd($this->motivo,$horario,$this->id_asignacion_especialista,$this->fecha);
            if ($existeAgenda) {
                $this->obetenerHorario();
                $this->dispatch('agendaYaExiste');
            } else {
                $nuevaSuspension = SuspensionHorario::create([
                    'fecha_suspension' => $this->fecha,
                    'observacion' => "Suspendido",
                    'estado' => true,
                    'id_motivo' => $this->motivo,
                    'id_user_created' => auth()->user()->id,
                ]);
                $horaC = Carbon::parse($horario)->format('H');
                $minutosC = Carbon::parse($horario)->format('i');
                $diaC = Carbon::parse($this->fecha)->format('d');
                $mesC = Carbon::parse($this->fecha)->format('m');
                $gestionC = Carbon::parse($this->fecha)->format('Y');
                $clave_unica = $this->id_asignacion_especialista . $horaC . $minutosC . $diaC . $mesC . $gestionC;
                $nuevaAgenda = Agenda::create([
                    'fecha_agenda' => $this->fecha,
                    'hora_agenda' => $horario,
                    'confirmado' => true,
                    'id_servicio_plataforma' => 1,
                    'clave_unica' => $clave_unica,
                    'id_persona' => $this->id_persona_asegurada,
                    'id_asignacion_horario' => $this->id_asignacion_especialista,
                    'anulacion_ficha' => false,
                    'ficha_extra' => false,
                    'id_suspension_horario' => $nuevaSuspension->id,
                    'id_user_created' => auth()->user()->id,
                ]);
                DB::commit();
                $this->dispatch('AgendaRegistrada');
                $this->obetenerHorario();
            }

            // throw new \Exception('Error forzado despues de la importacion.');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($e->getMessage() === 'Ya existe una planilla con estas características.') {
                $this->dispatch('agendaYaExiste');
            } else {
                $this->dispatch('agendaYaExiste');
            }
        }
    }*/

    public function volver()
    {
        return redirect()->route('plataforma.suspension-horario.index');
    }
}
