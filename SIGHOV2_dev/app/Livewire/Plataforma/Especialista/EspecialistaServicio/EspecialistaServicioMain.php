<?php

namespace App\Livewire\Plataforma\Especialista\EspecialistaServicio;

use App\Models\Administracion\Especialidad;
use App\Models\Administracion\Persona;
use App\Models\Administracion\Departamento;
use App\Models\Plataforma\Agenda;
use App\Models\Plataforma\AsignacionHorario;
use App\Models\Plataforma\Consultorio;
use App\Models\Plataforma\EspecialistaHabilitadoServicio;
use App\Models\Plataforma\ServicioPlataforma;
use App\Models\Plataforma\Dia;
use App\Models\Plataforma\Especialista;
use App\Models\Plataforma\Horario;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;

use Livewire\Component;

class EspecialistaServicioMain extends Component
{
    public $idEsp;
    use WithFileUploads;
    public $servicios;
    public $dias;
    public $especialidad_ver, $sigla_ver, $fecha_registro, $estado;
    #[Validate]
    public $fecha_inicio, $fecha_fin;
    public $permanente_enter = true;
    #[Validate]
    public $id_servicio, $id_dia;
    public $dia, $consultorio;
    public $horarioSelected = [];
    public $servicioSelected = [];
    public $asignacion = [];
    public $horarios = [];
    public $consultorios = [];


    //VARIABLES PARA AGREGAR SERVICIO

    public $fecha_inicio_servicio;
    public $id_dia_servicio;
    public $fecha_fin_servicio;
    public $id_servicio_servicio;
    public $permanente_enter_servicio;

    //VARIABLES PARA AGREGAR HORARIO

    public $fecha_inicio_horario;
    public $fecha_fin_horario;
    public $estado_horario;
    public $id_dia_horario;
    public $id_horario_horario;
    public $id_consultorio_horario;
    public $permanente_horario;



    // para mostrar datos del especialista
    public $nombre_completo, $ci_complemento, $expedido, $fecha_nacimiento, $matricula_seguro;
    public $foto, $grado_academico, $es_permanente, $fotografia, $contrato_desde, $contrato_hasta;
    public $especialidad, $sigla;
    public $permanente_tipo_asegurado = true;


    protected $listeners = ['registrarServicio'];

    public function rules()
    {
        return [
            'fecha_inicio' => $this->permanente_enter ? 'nullable| date' : 'required|date',
            'fecha_fin' => $this->permanente_enter ? 'nullable| date' : 'required|date',
            'id_servicio' => 'required',
            'id_dia' => 'required',
        ];
    }

    public function render()
    {
        return view('livewire.Plataforma.especialista.especialista-servicio.especialista-servicio-main');
    }

    public function mount()
    {
        //dd('sdfsdfsd');
        //dd($this->servicioSelected);
        $this->permanente_horario = false;
        $this->permanente_enter_servicio = false;
        $this->mostrarEspecialista($this->idEsp);
        $this->servicios = ServicioPlataforma::where('estado', true)->get();
        $this->dias = Dia::all();
        $this->mostrarServicios($this->idEsp);
        $this->horarios = Horario::where('estado', true)->orderBy('hora_inicio', 'asc')->get();
        $this->consultorios = Consultorio::with(['consultorioSedes'])->where('estado', true)->orderBy('id_sedes', 'asc')->get();
        $this->actualizarEstadosHorariosServicios();
    }

    public function mostrarEspecialista($idEsp)
    {
        if ($idEsp) {
            $especialista = Especialista::find($idEsp);
            $this->grado_academico = $especialista->grado_academico;
            $this->es_permanente = $especialista->permanente;
            //dd($this->es_permanente);
            $this->contrato_desde = Carbon::parse($especialista->fecha_contrato_inicio)->format('d/m/Y');
            $this->contrato_hasta = Carbon::parse($especialista->fecha_contrato_fin)->format('d/m/Y');
            $this->fotografia = $this->obtenerImagenBase64($especialista->foto);
            $id_especialidad = $especialista->id_especialidad;
            $id_persona = $especialista->id_persona;
            $persona = Persona::find($id_persona);
            $this->nombre_completo = $persona->nombres . ' ' . $persona->p_apellido . ' ' . $persona->s_apellido;
            $ci = $persona->ci;
            $complemento = $persona->complemento;
            $this->fecha_nacimiento = Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y');
            $this->matricula_seguro = $persona->matricula_seguro;
            if ($complemento == null) {
                $this->ci_complemento = $ci;
            } else {
                $this->ci_complemento = $ci . ' ' . $complemento;
            }
            $id_dept_exp = $persona->id_dept_exp;
            $departamentos = Departamento::find($id_dept_exp);

            //Esta parte daba error por la migracion de especialistas
            if ($departamentos != null) {
                $this->expedido = $departamentos->nombre;
            }


            $especialidad = Especialidad::find($id_especialidad);
            $this->especialidad = $especialidad->especialidad;
        }
    }
    // Funcion para obtener imagenes desde la FTP
    private function obtenerImagenBase64($filename)
    {
        try {
            if ($filename) {
                $path = 'Plataforma/Especialista/Fotos/' . $filename;
                $imageData = Storage::disk('ftp')->get($path);

                $fotito = base64_encode($imageData);
                return $fotito;
            } else {
                return null;
            }
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al obtener la imagen", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
            return null;
        }
    }

    public function registrarServicio()
    {
        $this->validate();
        try {
            if ($this->id_dia == 7) {
                //todos los dias
                $diasHabiles = [1, 2, 3, 4, 5];

                if ($this->id_servicio == 4) {
                    $servicios = [1, 2, 3]; // Plataforma, Web, Kiosko
                    foreach ($servicios as $servicio) {
                        $this->guardarServiciosParaDias($servicio, $diasHabiles);
                    }
                } else {
                    $this->guardarServiciosParaDias($this->id_servicio, $diasHabiles);
                }

                $this->dispatch('registroExitoso');
            } else {
                //un solo dia
                if ($this->id_servicio == 4) {
                    //todos los servicios
                    $servicios = [1, 2, 3]; // Plataforma, Web, Kiosko
                    foreach ($servicios as $servicio) {
                        $existeServicio = EspecialistaHabilitadoServicio::where('id_servicio', $servicio)
                            ->where('id_especialista', $this->idEsp)
                            ->where('id_dia', $this->id_dia)
                            ->first();

                        if ($existeServicio) {
                            $this->procesarServicioExistente($existeServicio);
                        } else {
                            EspecialistaHabilitadoServicio::create([
                                'fecha_inicio' => $this->fecha_inicio,
                                'fecha_fin' => $this->fecha_fin,
                                'permanente' => $this->permanente_enter,
                                'estado' => true,
                                'id_servicio' => $servicio,
                                'id_dia' => $this->id_dia,
                                'id_especialista' => $this->idEsp,
                                'id_user_created' => auth()->user()->id,
                            ]);
                            $this->dispatch('registroExitoso');
                        }
                    }
                }
                //si no es todos los servicios
                else {
                    $existeServicio = EspecialistaHabilitadoServicio::where('id_servicio', $this->id_servicio)
                        ->where('id_especialista', $this->idEsp)
                        ->where('id_dia', $this->id_dia)
                        ->first();

                    if ($existeServicio) {
                        $this->procesarServicioExistente($existeServicio);
                    } else {
                        EspecialistaHabilitadoServicio::create([
                            'fecha_inicio' => $this->fecha_inicio,
                            'fecha_fin' => $this->fecha_fin,
                            'permanente' => $this->permanente_enter,
                            'estado' => true,
                            'id_servicio' => $this->id_servicio,
                            'id_dia' => $this->id_dia,
                            'id_especialista' => $this->idEsp,
                            'id_user_created' => auth()->user()->id,
                        ]);
                        $this->dispatch('registroExitoso');
                    }
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
        }
    }

    // Método para guardar los servicios (incluyendo TODO con Plataforma, Web y Kiosko)
    private function guardarServiciosParaDias($id_servicio, $diasHabiles)
    {
        foreach ($diasHabiles as $dia) {
            $existeServicio = EspecialistaHabilitadoServicio::where('id_servicio', $id_servicio)
                ->where('id_especialista', $this->idEsp)
                ->where('id_dia', $dia)
                ->first();

            if ($existeServicio) {
                $this->procesarServicioExistente($existeServicio);
            } else {
                EspecialistaHabilitadoServicio::create([
                    'fecha_inicio' => $this->fecha_inicio,
                    'fecha_fin' => $this->fecha_fin,
                    'permanente' => $this->permanente_enter,
                    'estado' => true,
                    'id_servicio' => $id_servicio,
                    'id_dia' => $dia,
                    'id_especialista' => $this->idEsp,
                    'id_user_created' => auth()->user()->id,
                ]);
            }
        }
    }

    /*private function procesarServicioExistente($existeServicio)
    {
        if ($existeServicio->permanente && $this->permanente_enter) {
            if ($existeServicio->estado) {
                $this->yaExisteServicio();
            } else {
                $existeServicio->estado = true;
                $existeServicio->id_user_updated = auth()->user()->id;
                $existeServicio->save();
                $this->dispatch('registroExitoso');
            }
        } elseif ($existeServicio->permanente == true && $this->permanente_enter == false) {
            if ($existeServicio->estado == true) {
                $existeServicio->permanente = false;
                $existeServicio->fecha_inicio = $this->fecha_inicio;
                $existeServicio->fecha_fin = $this->fecha_fin;
                $existeServicio->id_user_updated = auth()->user()->id;
                $existeServicio->save();
                $this->dispatch('registroExitoso');
            } else {
                $existeServicio->estado = true;
                $existeServicio->permanente = false;
                $existeServicio->fecha_inicio = $this->fecha_inicio;
                $existeServicio->fecha_fin = $this->fecha_fin;
                $existeServicio->id_user_updated = auth()->user()->id;
                $existeServicio->save();
                $this->dispatch('registroExitoso');
            }
        } elseif (!$existeServicio->permanente && $this->permanente_enter) {
            if ($existeServicio->estado) {
                $existeServicio->permanente = true;
                $existeServicio->fecha_inicio = null;
                $existeServicio->fecha_fin = null;
                $existeServicio->id_user_updated = auth()->user()->id;
                $existeServicio->save();
                $this->dispatch('registroExitoso');
            } else {
                $existeServicio->estado = true;
                $existeServicio->permanente = true;
                $existeServicio->fecha_inicio = null;
                $existeServicio->fecha_fin = null;
                $existeServicio->id_user_updated = auth()->user()->id;
                $existeServicio->save();
                $this->dispatch('registroExitoso');
            }
        } elseif (!$existeServicio->permanente && !$this->permanente_enter) {
            if ($existeServicio->estado) {
                $this->yaExisteServicio();
            } else {
                $existeServicio->estado = true;
                $existeServicio->id_user_updated = auth()->user()->id;
                $existeServicio->save();
                $this->dispatch('registroExitoso');
            }
        }
    }*/

    public function yaExisteServicio()
    {
        $this->dispatch('servicioExiste');
    }

    public function cancelarServicio()
    {
        return redirect()->route('plataforma.especialistas.index');
    }

    public function mostrarServicios($idEsp)
    {
        if ($idEsp) {

            $this->horarioSelected = AsignacionHorario::where('id_especialista', $idEsp)
                ->where('estado', true)
                ->whereDate('fecha_inicio', '<=', Carbon::now()->format('Y-m-d'))
                ->where(function ($query) {
                    $query->whereNull('fecha_fin')
                        ->orWhereDate('fecha_fin', '>=', Carbon::now()->format('Y-m-d'));
                })
                ->with(['consultorioAsignacionHorario.consultorioSedes.residenciaSedes.zonaResidencia', 'diaAsignacionHorario', 'horarioAsignacionHorario'])
                ->orderBy('id_dia', 'asc')
                ->get();


            $this->servicioSelected = EspecialistaHabilitadoServicio::where('id_especialista', $idEsp)
                ->where('estado', true)
                //->whereDate('fecha_inicio', '<=', Carbon::now()->format('Y-m-d'))
                ->where(function ($query) {
                    $query->whereNull('fecha_inicio')
                        ->orWhereDate('fecha_inicio', '<=', Carbon::now()->format('Y-m-d'));
                })
                ->where(function ($query) {
                    $query->whereNull('fecha_fin')
                        ->orWhereDate('fecha_fin', '>=', Carbon::now()->format('Y-m-d'));
                })
                ->with(['servicioEspecialistaHabilitadoServicio'])
                ->orderBy('id_dia', 'asc')
                ->get();
        }
    }

    public function abrirHorario()
    {
        $this->dispatch('idEspe', title: $this->idEsp);
        $this->dispatch('agregarHorarioModal');
    }

    public function abrirServicio()
    {
        $this->dispatch('idEspe', title: $this->idEsp);
        $this->dispatch('agregarServicioModal');
    }

    #[On('guardarNuevoServicio')]
    public function guardarNuevoServicio()
    {
        $todosDias = $this->id_dia_servicio === '7' ? true : false;
        $todosServicios = $this->id_servicio_servicio === '4' ? true : false;
        $servicios = [];
        $dias = [];

        DB::beginTransaction();
        try {
            if ($todosServicios && $todosDias) {
                $servicios = range(1, 3);
                $dias = range(1, 5);
            } elseif (!$todosServicios && $todosDias) {
                $servicios = [$this->id_servicio_servicio];
                $dias = range(1, 5);
            } elseif ($todosServicios && !$todosDias) {
                $servicios = range(1, 3);
                $dias = [$this->id_dia_servicio];
            } else {
                $servicios = [$this->id_servicio_servicio];
                $dias = [$this->id_dia_servicio];
            }

            foreach ($servicios as $servicio) {
                foreach ($dias as $dia) {
                    $this->registrarServicito($servicio, $dia);
                }
            }

            DB::commit();
            $this->permanente_enter_servicio = false;
            $this->fecha_inicio_servicio = null;
            $this->fecha_fin_servicio = null;
            $this->id_servicio_servicio = null;
            $this->id_dia_servicio = null;
            $this->dispatch('limpiarServicio');
            $this->dispatch('registroExitoso');
            $this->dispatch('pg:eventRefresh-VistaServiciosTable');
            $this->mostrarServicios($this->idEsp);
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->dispatch('mensajeError');

            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al guardar nuevo servicio", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    public function registrarServicito($servicio, $dia)
    {
        //dd($this->permanente_enter_servicio);
        $existiaAntes = EspecialistaHabilitadoServicio::where('id_especialista', $this->idEsp)
            ->where('id_servicio', $servicio)
            ->where('id_dia', $dia)
            ->where('estado', true)
            ->first();

        if ($existiaAntes) {
            $existiaAntes->estado = false;
            $existiaAntes->id_user_updated = auth()->user()->id;
            $existiaAntes->save();
        }

        EspecialistaHabilitadoServicio::create([
            'fecha_inicio' => $this->permanente_enter_servicio ? null : $this->fecha_inicio_servicio,
            'fecha_fin' => $this->permanente_enter_servicio ? null : $this->fecha_fin_servicio,
            'permanente' => $this->permanente_enter_servicio,
            'estado' => true,
            'id_servicio' => $servicio,
            'id_dia' => $dia,
            'id_especialista' => $this->idEsp,
            'id_user_created' => auth()->user()->id,
        ]);
    }

    #[On('validacionesNuevoHorario')]
    public function validacionesNuevoHorario()
    {
        //VALIDACIONES LOCOOO

        //dd($this->permanente_horario, $this->fecha_fin_horario, $this->fecha_fin_horario, $this->id_dia_horario, $this->id_consultorio_horario, $this->id_horario_horario);

        /*if($this->permanente_horario === null){
            $this->dispatch('faltaPermanente');
        }*/

        if ($this->fecha_inicio_horario === null) {
            //dd($this->fecha_inicio_horario);
            $this->dispatch('faltaFechaInicio');
            return;
        }

        if ($this->permanente_horario === false && $this->fecha_fin_horario === null) {
            $this->dispatch('faltaFechaFin');
            return;
        }

        if ($this->id_dia_horario === null) {
            $this->dispatch('faltaDia');
            return;
        }

        if ($this->id_consultorio_horario === null) {
            $this->dispatch('faltaConsultorio');
            return;
        }

        if ($this->id_horario_horario === null) {
            $this->dispatch('faltaHorario');
            return;
        }

        $existeAsignacionSimilar = AsignacionHorario::where('id_especialista', $this->idEsp)
            ->where('id_dia', $this->id_dia_horario)
            ->where('estado', true)
            ->with(['horarioAsignacionHorario'])
            ->first();

        if ($this->id_dia_horario === '7') {
            $existeAsignacionSimilar = AsignacionHorario::where('id_especialista', $this->idEsp)
                ->whereIn('id_dia', [1, 2, 3, 4, 5])
                ->where('estado', true)
                ->with(['horarioAsignacionHorario'])
                ->first();
        }

        $horarioSeleccionado = Horario::find($this->id_horario_horario);

        if ($existeAsignacionSimilar) {
            if ($existeAsignacionSimilar->horarioAsignacionHorario->turno === $horarioSeleccionado->turno) {
                if (!$existeAsignacionSimilar->permanente) {
                    if (Carbon::parse($existeAsignacionSimilar->fecha_fin)->gte(Carbon::parse($this->fecha_inicio_horario))) {
                        //dd('pregunta antes de registrar pues ya existe un horario asignado con fechas similares y en el mismo turno');
                        $this->dispatch('turnoExistente');
                    } else {
                        //dd('se registra porque no tiene conflicto con las fechas de inicio y fin');
                        $this->guardarNuevoHorario();
                    }
                } else {
                    /*dd('se crearia un registro nuevo porque el horario previo es permanente, no tiene problemas con las fechas de inicio y fin. Sin
                        embargo, se debe hacer la pregunta pues esta activo y cambiar ello sería otro mambo, tambien sería agregarle la fecha fin para poder darle
                        de baja cuando corresponda.  Su fecha fin sería la fecha de inicio del nuevo registro');*/
                    $this->dispatch('turnoExistente');
                }
            } else {
                //dd('se registra nomas porque no pertenece al mismo turno');
                $this->guardarNuevoHorario();
            }
        } else {
            //dd('no existe nada similar, por ende se registra nomás');
            $this->guardarNuevoHorario();
        }
    }

    //#[On('guardarNuevoHorario')]
    public function guardarNuevoHorario()
    {
        //$this->validacionesNuevoHorario();

        $todosDias = $this->id_dia_horario === '7' ? true : false;
        $dias = [];

        try {
            DB::beginTransaction();
            if ($todosDias) {
                $dias = range(1, 5);
            } else {
                $dias = [$this->id_dia_horario];
            }

            $existeRegistroAnterior = 0;

            foreach ($dias as $dia) {
                $asignacionExistente = AsignacionHorario::with(['especialistaAsignacionHorario'])
                    ->where('id_dia', $dia)
                    ->where(function ($otroQuericito) {
                        $otroQuericito->whereNull('fecha_fin')
                            ->orWhereDate('fecha_fin', '>=', $this->fecha_inicio_horario);
                    })
                    ->where('estado', true)
                    ->where('id_horario', $this->id_horario_horario)
                    ->where('id_consultorio', $this->id_consultorio_horario)
                    ->whereHas('especialistaAsignacionHorario', function ($quericitoEspecialista) {
                        $quericitoEspecialista->where('estado', true);
                    })
                    ->first();

                if ($asignacionExistente) {
                    $existeRegistroAnterior++;
                }
            }

            if ($existeRegistroAnterior === 0) {
                foreach ($dias as $dia) {
                    $this->registrarHorario($dia);
                }
            } else {
                //$this->id_horario_horario = null;
                //$this->id_consultorio_horario = null;
                $this->dispatch('existeOtroEspecialistaConMismoHorarioMismoConsultorio');
                return;
            }

            DB::commit();
            $this->limpiarVariablesHorario();
            $this->dispatch('registroExitoso');
            $this->dispatch('pg:eventRefresh-VistaHorariosNuevosTable');
            $this->actualizarEstadosHorariosServicios();
            $this->mostrarServicios($this->idEsp);
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->dispatch('mensajeError');

            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al guardar nuevo horario", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    public function registrarHorario($dia)
    {
        AsignacionHorario::create([

            'permanente' => $this->permanente_horario,
            'fecha_inicio' => $this->fecha_inicio_horario,
            'fecha_fin' => $this->permanente_horario ? null : $this->fecha_fin_horario,
            'estado' => true,
            'id_dia' => $dia,
            'id_especialista' => $this->idEsp,
            'id_horario' => $this->id_horario_horario,
            'id_consultorio' => $this->id_consultorio_horario,
            'id_user_created' => auth()->user()->id,
        ]);
    }

    #[On('guardarNuevoHorarioConActualizaciones')]
    public function guardarNuevoHorarioConActualizaciones()
    {
        try {
            $todosDias = $this->id_dia_horario === '7' ? true : false;
            $dias = [];

            //DB::beginTransaction();
            //try {
            if ($todosDias) {
                $dias = range(1, 5);
            } else {
                $dias = [$this->id_dia_horario];
            }


            foreach ($dias as $dia) {
                $asignacionHorarioExistente = AsignacionHorario::where('id_especialista', $this->idEsp)
                    ->where('id_dia', $dia)
                    ->where('estado', true)
                    ->first();

                $horarioSeleccionado = Horario::find($this->id_horario_horario);

                if ($asignacionHorarioExistente) {
                    if ($asignacionHorarioExistente->horarioAsignacionHorario->turno === $horarioSeleccionado->turno) {
                        if (!$asignacionHorarioExistente->permanente) {
                            if (Carbon::parse($asignacionHorarioExistente->fecha_fin)->gte(Carbon::parse($this->fecha_inicio_horario))) {
                                //dd('pregunta antes de registrar pues ya existe un horario asignado con fechas similares y en el mismo turno');
                                $asignacionHorarioExistente->fecha_fin = $this->fecha_inicio_horario;
                                if ($this->fecha_inicio_horario === Carbon::now()->format('Y-m-d')) {
                                    $asignacionHorarioExistente->estado = false;
                                }
                                $asignacionHorarioExistente->id_user_updated = auth()->user()->id;
                                $asignacionHorarioExistente->save();
                            }
                        } else {
                            /*dd('se crearia un registro nuevo porque el horario previo es permanente, no tiene problemas con las fechas de inicio y fin. Sin
                                    embargo, se debe hacer la pregunta pues esta activo y cambiar ello sería otro mambo, tambien sería agregarle la fecha fin para poder darle
                                    de baja cuando corresponda.  Su fecha fin sería la fecha de inicio del nuevo registro');*/
                            $asignacionHorarioExistente->fecha_fin = $this->fecha_inicio_horario;
                            if ($this->fecha_inicio_horario === Carbon::now()->format('Y-m-d')) {
                                $asignacionHorarioExistente->estado = false;
                            }
                            $asignacionHorarioExistente->id_user_updated = auth()->user()->id;
                            $asignacionHorarioExistente->save();
                        }
                    }
                }
            }
            $this->guardarNuevoHorario();
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al guardar nuevo horario con actualizaciones", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }



    public function limpiarVariablesHorario()
    {
        $this->dispatch('limpiarHorario');
        $this->permanente_horario = false;
        $this->fecha_inicio_horario = null;
        $this->fecha_fin_horario = null;
        $this->id_horario_horario = null;
        $this->id_dia_horario = null;
        $this->id_consultorio_horario = null;
    }

    public function actualizarEstadosHorariosServicios()
    {
        $horariosAsignados = AsignacionHorario::where('id_especialista', $this->idEsp)
            ->where('estado', true)
            ->where('fecha_fin', '<', Carbon::now()->format('Y-m-d'))
            ->get();

        foreach ($horariosAsignados as $horario) {
            $horario->estado = false;
            $horario->id_user_updated = auth()->user()->id;
            $horario->save();
        }

        $serviciosAsignados = EspecialistaHabilitadoServicio::where('id_especialista', $this->idEsp)
            ->where('estado', true)
            ->where('fecha_fin', '<', Carbon::now()->format('Y-m-d'))
            ->get();

        foreach ($serviciosAsignados as $servicio) {
            $servicio->estado = false;
            $servicio->id_user_updated = auth()->user()->id;
            $servicio->save();
        }
    }

    //NUEVA FUNCION PARA VERIFICAR SI HAY AGENDAS EN ESE HORARIO

    public function verificarAgendasAsignacionHorario($idAsignacionHorario)
    {
        return Agenda::where('id_asignacion_horario', $idAsignacionHorario)
            ->with('personaAgenda')
            ->whereDate('fecha_agenda', '>=', Carbon::today())
            ->where('anulacion_ficha', false)
            ->get()
            ->toArray();
    }

    //FIN FUNCION PARA VERIFICAR SI HAY AGENDAS EN ESE HORARIO

    public function validacionActivarHorario($idAsignacionHorario)
    {
        $lanzarAlertaExisteActivo = false;
        $asignacionHorarioActivar = AsignacionHorario::with('horarioAsignacionHorario')->find($idAsignacionHorario);
        $turnoAsignacionHorarioActivar = $asignacionHorarioActivar->horarioAsignacionHorario->turno;
        $permanenteAsignacionHorarioActivar = $asignacionHorarioActivar->permanente;
        $fechaInicioAsignacionHorarioActivar = $asignacionHorarioActivar->fecha_inicio;
        $diaAsignacionHorarioActivar = $asignacionHorarioActivar->id_dia;
        if (!$asignacionHorarioActivar->estado) {
            if ($permanenteAsignacionHorarioActivar) {
                $lanzarAlertaExisteActivo = AsignacionHorario::with('horarioAsignacionHorario')
                    ->where('id_especialista', $asignacionHorarioActivar->id_especialista)
                    ->where('estado', true)
                    ->where('id_dia', $diaAsignacionHorarioActivar)
                    ->whereHas('horarioAsignacionHorario', function ($query) use ($turnoAsignacionHorarioActivar) {
                        $query->where('turno', $turnoAsignacionHorarioActivar);
                    })
                    ->exists();
            } else {
                //dd('entra aca');
                $lanzarAlertaExisteActivo = AsignacionHorario::with('horarioAsignacionHorario')
                    ->where('id_especialista', $asignacionHorarioActivar->id_especialista)
                    ->where('estado', true)
                    ->where('id_dia', $diaAsignacionHorarioActivar)
                    ->where(function ($query) use ($fechaInicioAsignacionHorarioActivar) {
                        $query->where('fecha_fin', '>', $fechaInicioAsignacionHorarioActivar)
                            ->orWhere('permanente', true);
                    })
                    ->whereHas('horarioAsignacionHorario', function ($query) use ($turnoAsignacionHorarioActivar) {
                        $query->where('turno', $turnoAsignacionHorarioActivar);
                    })
                    ->exists();
            }
        }
        //dd($asignacionHorarioActivar, $turnoAsignacionHorarioActivar, $permanenteAsignacionHorarioActivar);
        return $lanzarAlertaExisteActivo;
    }

    #[On('cambiarEstadoHorario')]
    public function cambiarEstadoHorario($data)
    {
        //dd('loco');
        $horarioEstado = AsignacionHorario::find($data);
        if ($horarioEstado) {

            if ($this->validacionActivarHorario($horarioEstado->id)) {
                $this->dispatch('alertaAsignacionActiva');
                return;
            }


            if ($horarioEstado->estado && $this->verificarAgendasAsignacionHorario($data) != []) {
                //dd($this->verificarAgendasAsignacionHorario($data));
                $this->dispatch('alertaAgendasEnHorario', agendasAunExistentes: $this->verificarAgendasAsignacionHorario($data));
                return;
            }

            DB::beginTransaction();
            try {
                $horarioEstado->estado = !$horarioEstado->estado;
                $horarioEstado->id_user_updated = auth()->user()->id;
                $horarioEstado->save();
                DB::commit();
                $this->dispatch('estadoActualizado');
                $this->dispatch('pg:eventRefresh-VistaHorariosNuevosTable');
            } catch (\Throwable $th) {
                DB::rollBack();
                $this->dispatch('mensajeError');
            }
        }
    }

    #[On('cambiarEstadoServicio')]
    public function cambiarEstadoServicio($data)
    {
        $horarioServicio = EspecialistaHabilitadoServicio::find($data);

        if ($horarioServicio) {
            DB::beginTransaction();
            try {
                $horarioServicio->estado = !$horarioServicio->estado;
                $horarioServicio->id_user_updated = auth()->user()->id;
                $horarioServicio->save();
                DB::commit();
                $this->dispatch('estadoActualizado');
                $this->dispatch('pg:eventRefresh-VistaServiciosTable');
            } catch (\Throwable $e) {
                DB::rollBack();
                $this->dispatch('mensajeError');

                $trace = collect($e->getTrace())
                    ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

                Log::error("Error al cambiar estado servicio", [
                    'USUARIO_ID' => auth()->user()->id ?? null,
                    'ERROR'      => $e->getMessage() ?? '',
                    'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                    'LINEA'      => $trace['line'] ?? $e->getLine(),
                    'FECHA_HORA' => now(),
                ]);
            }
        }
    }
}
