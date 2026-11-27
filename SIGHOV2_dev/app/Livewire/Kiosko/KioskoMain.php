<?php

namespace App\Livewire\Kiosko;

use App\Http\Controllers\Plataforma\PrintController;
use App\Models\Plataforma\Especialista;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use App\Models\Plataforma\DiasHabilitadosAgenda;
use App\Models\Plataforma\AsignacionHorario;
use App\Models\Plataforma\Motivo;
use App\Models\Plataforma\Agenda;
use App\Models\Administracion\Especialidad;
use App\Models\Administracion\LinkServicio;
use App\Models\Afiliacion\Estudiante;
use App\Models\Plataforma\AdministracionFeriados;
use App\Models\User;
use App\Services\ListadoAsegurados;
use App\Services\GrupoFamiliarPersona;
use App\Services\BusquedaClaveUnica;
use App\Services\ReplicarAgendaS1;
use App\Services\ServiciosCachecito;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\VencimientoCarnet;
use Illuminate\Support\Facades\Log;

class KioskoMain extends Component
{
    public $vigencia = 'VIGENTE';
    public $fotografia;
    public $foto_seguro;
    public $var = 1;

    //Para la busqueda
    public $carnetBusqueda, $complementoBusqueda, $matriculaBusqueda, $resultadoBusqueda;

    //Para los datos de los asegurados
    public $id_persona;
    public $ci, $carnet;
    public $complemento;
    public $matricula_seguro;
    public $nombres;
    public $p_apellido;
    public $s_apellido;
    public $sexo;
    public $sexoText;
    public $fecha_nacimiento;
    public $fechaNacimientoText;
    public $tipo_asegurado;
    public $estado_vigencia;
    public $nombre_institucion;
    public $codigo_institucional;
    public $institucion_beneficiario_remuneracion;
    public $identificadorTipoAsegurado;

    public $nombreCompleto;
    public $carnetCompleto;
    public $matriculaInstitucion;
    public $edad;

    //Dias seleccionados
    public $fechasElegibles;
    public $fechaIngresada;
    public $fechaE;
    public $tieneAgendados;

    //Especialidades
    public $especialidadesDisponibles;
    public $especialidadSelected;

    //Especialista
    public $asignaciones = [];
    public $listaHorarios;
    public $horariosDisponibles;
    public $nombreEspecialista;
    public $nombreCompletoEspecialista;
    public $turnoAsigHorario;
    public $consultorioSelected, $especialistaSinCupo = true;

    //Horario
    public $idAsignacionHorario;
    public $horaSelect;
    public $horasDisponibles;
    // qr 
    public $codigo_qr;
    public $datos = [];

    //Nuevas validacion
    public $edadGeriatria;
    public $edadPediatria;
    public $diaElegido;

    public $horaActualParaVerificacion;
    public $switchMismoDia;

    public $idAsignacionHorarioSeleccionado;
    public $idEspecialistaSeleccionado;

    public $horaSeleccionada;
    public $especialidadLiteral;

    public $busquedaEspecialidad;

    public $especialidadesSinFiltrar;
    public $especialidadBusqueda;
    // protected $listeners = ['registrarAgenda'];

    protected ServiciosCachecito $servicioEspecialidades;

    public function __construct()
    {
        $this->servicioEspecialidades = new ServiciosCachecito();
    }

    public function render()
    {
        return view('livewire.Kiosko.kiosko-main');
    }

    public function mount()
    {
        $this->tieneAgendados = false;
        $this->especialidadesDisponibles = [];
        $this->fechasElegibles = [];
        $this->horariosDisponibles = [];
        $this->listaHorarios = [];
        $this->especialidadesSinFiltrar = [];
        $this->edadGeriatria = 49;
        $this->edadPediatria = 18;
    }

    #[On('volverAInicio')]
    public function volverAInicio()
    {
        $this->codigo_qr = null;
        $this->fechasElegibles = [];
        $this->especialidadesDisponibles = [];
        $this->fechaIngresada = null;
        $this->horariosDisponibles = [];
        $this->especialidadLiteral = null;
        $this->nombreCompletoEspecialista = null;
        $this->especialidadBusqueda = null;
        $this->especialidadesSinFiltrar = [];
        $this->listaHorarios = [];
        $this->dispatch('focusear');
        $this->reset('carnetBusqueda', 'complementoBusqueda', 'matriculaBusqueda', 'resultadoBusqueda');
        $this->var = 1;
    }
    // 1.INGRESO
    // busqueda por escanner
    public function buscarPorQr()
    {
        $busqueda = new BusquedaClaveUnica();
        $this->datos = $busqueda->datosPorClaveUnica($this->codigo_qr);
        if ($this->datos) {
            $id = $this->datos['id'];
            $this->seleccionarPersona($id);
        } else {
            $this->dispatch('alertaNoAsegurado');
            //$this->reset('codigo_qr');
        }
    }

    // busqueda por registro Manual 
    public function de1IngresoA2Busqueda()
    {
        $this->var = 2;
    }
    // 2.BUSQUEDA
    public function de2BusquedaA1Ingreso()
    {
        $this->var = 1;
        $this->codigo_qr = null;
        $this->dispatch('focusear');
        $this->reset('carnetBusqueda', 'complementoBusqueda', 'matriculaBusqueda', 'resultadoBusqueda');
    }

    public function busquedaAsegurado()
    {
        $buscar = new ListadoAsegurados();
        if ($this->carnetBusqueda == null && $this->complementoBusqueda == null && $this->matriculaBusqueda == null) {
            $this->dispatch('faltaDatosBusqueda');
        } else {
            $this->resultadoBusqueda = $buscar->busquedaAfiliadoKiosko($this->carnetBusqueda, $this->complementoBusqueda, $this->matriculaBusqueda);
            //dd($this->resultadoBusqueda);
            if ($this->resultadoBusqueda === []) {
                $this->dispatch('sinResultados');
                return;
            }
        }
    }

    public function seleccionarPersona($idPersona)
    {
        $this->var = 3;
        $this->id_persona = $idPersona;
        $busquedaA = new ListadoAsegurados();
        $resultado = $busquedaA->busquedaAfiliadoPorIdKiosko($idPersona);
        if ($resultado['complemento']) {
            $this->carnet =  $resultado['ci'] . ' - ' . $resultado['complemento'];
        } else {
            $this->carnet = $resultado['ci'];
        }
        $this->matricula_seguro = $resultado['matricula_seguro'] . ' ' . $resultado['codigo_institucional'];
        $this->nombres = $resultado['nombres'];
        $this->p_apellido = $resultado['p_apellido'];
        $this->s_apellido = $resultado['s_apellido'];
        $this->sexo = $resultado['sexo'];
        $this->fecha_nacimiento = $resultado['fecha_nacimiento'];
        $this->fechaNacimientoText = Carbon::createFromFormat('Y-m-d', $this->fecha_nacimiento)->translatedFormat('d \\d\\e F \\d\\e Y');
        $this->tipo_asegurado = $resultado['tipo_asegurado'];
        $this->estado_vigencia = $resultado['estado_vigencia'];
        $this->nombre_institucion = $resultado['nombre_institucion'];
        //$this->codigo_institucional = $resultado['codigo_institucional'];
        $this->institucion_beneficiario_remuneracion = $resultado['institucion_beneficiario_remuneracion'];
        $this->fotografia = $resultado['foto'];

        $this->nombreCompleto = $this->p_apellido . ' ' . $this->s_apellido . ' ' . $this->nombres;
        if ($this->complemento == null) {
            $this->carnetCompleto = $this->ci;
        } else {
            $this->carnetCompleto = $this->ci . '-' . $this->complemento;
        }
        if ($this->codigo_institucional == null) {
            $this->matriculaInstitucion = $this->matricula_seguro;
        } else {
            $this->matriculaInstitucion = $this->matricula_seguro . ' - ' . $this->codigo_institucional;
        }

        if ($this->sexo == 'M') {
            $this->sexoText = 'MASCULINO';
        } else {
            $this->sexoText = 'FEMENINO';
        }

        $this->identificadorTipoAsegurado = $this->definirTipoAsegurado($resultado['tipo_asegurado']);

        $this->edad = Carbon::parse($this->fecha_nacimiento)->age;

        if ($this->identificadorTipoAsegurado === 2) {
            $this->dispatch('noPuedeAccederConvenio');
            $this->de3VigenciaA2Busqueda();
        }
    }

    private function definirTipoAsegurado($tipoAsegurado)
    {
        $arraycitoTipoAsegurado = [
            'TITULAR' => 1,
            'BENEFICIARIO DEL INTERIOR' => 2,
            'TITULAR DEL INTERIOR' => 2,
            'TITULAR CONVENIO' => 2,
            'PREAFILIADO TITULAR' => 2,
            'PREAFILIADO BENEFICIARIO' => 2,
        ];

        if (isset($arraycitoTipoAsegurado[$tipoAsegurado])) {
            return $arraycitoTipoAsegurado[$tipoAsegurado];
        } elseif (Str::contains($tipoAsegurado, 'BENEFICIARIO')) {
            return 1;
        } else {
            return 3;
        }
    }

    // 3.VIGENCIA
    public function de3VigenciaA2Busqueda()
    {
        $this->var = 2;
        $this->codigo_qr = null;
        $this->carnetBusqueda = null;
        $this->complementoBusqueda = null;
        $this->matriculaBusqueda = null;
        $this->resultadoBusqueda = null;
    }

    //PARA VALIDAR LAS FECHAS DISPONIBLES
    public function obtenerFechasDisponibles()
    {

        try {
            $fechasDisponibles = DiasHabilitadosAgenda::where('estado', true)
                ->where('id_servicio_plataforma', 3)
                ->first();

            $this->fechasElegibles = [];
            $diasHabilitadosAgenda = $fechasDisponibles->nro_dias;
            $agregarHoy = $fechasDisponibles->hoy;
            $fecha = Carbon::now();
            if (!$agregarHoy) {
                $fecha->addDay();
            }
            while (count($this->fechasElegibles) < $diasHabilitadosAgenda) {

                $this->fechasElegibles[] = [
                    'fecha' => $fecha->format('Y-m-d'),
                    'fecha_literal' => mb_strtoupper($fecha->isoFormat('dddd')),
                    'feriado' => $this->verificarFeriado($fecha->format('Y-m-d')),
                    'nro_dia' => $fecha->dayOfWeek,
                    'nombre_dia' => mb_strtoupper($fecha->isoFormat('dddd')),
                ];

                $fecha->addDay();
            }
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al obtener fechas disponibles", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    public function verificarFeriado($fechaSeleccionada)
    {
        $feriado = AdministracionFeriados::where('fecha', $fechaSeleccionada)
            ->where('estado', true)
            ->exists();

        if (Carbon::parse($fechaSeleccionada)->dayOfWeek === 0) {
            return true;
        }

        return $feriado;
    }

    public function verificarEstudiante($idPersona)
    {

        return Estudiante::where('id_persona', $idPersona)
            ->where('estado', true)
            ->where('estado_vigencia', true)
            ->where('estado_validar', 'AFILIADO')
            ->where('fecha_fin_vigencia', '>=', Carbon::now())
            ->exists();
    }

    public function de3VigenciaA4SelectFecha()
    {
        $estudianteHabilitado = $this->identificadorTipoAsegurado === 3 ? $this->verificarEstudiante($this->id_persona) : true;

        if ($estudianteHabilitado) {
            $this->obtenerFechasDisponibles();
            $this->var = 4;
        } else {
            $this->dispatch('estudianteRegularizar');
            return;
        }
    }


    // 4. SELECCIONAR FECHA 
    public function de4SelectFechaA3Vigencia()
    {
        $this->fechasElegibles = [];
        $this->var = 3;
    }

    //VERIFICAR GRUPO FAMILIAR AGENDADO

    public function verificarGrupoFamiliarAgendado($fechaAgenda)
    {
        $familiarAgendado = 0;
        $grupoFamiliarServicio = new GrupoFamiliarPersona();
        $familiares = $grupoFamiliarServicio->encontrarPersona($this->id_persona);
        foreach ($familiares as $key => $familiar) {
            $existeAgenda = Agenda::where('fecha_agenda', $fechaAgenda)
                //->where('confirmado', true)
                ->where('id_persona', $familiar['id'])
                ->whereNot('anulacion_ficha', true)
                ->orderBy('id', 'desc')
                ->first();

            if ($existeAgenda) {
                if ($existeAgenda->id_persona === $this->id_persona) {
                    $familiarAgendado = 1;
                }

                if ($existeAgenda->id_persona !== $this->id_persona) {
                    $familiarAgendado = 2;
                }
            }
        }
        return $familiarAgendado;
    }

    //PARA OBTENER LAS ESPECIALIDADES HABILITADAS

    public function obtenerEspecialidades()
    {
        $especialidadesPorFecha = $this->servicioEspecialidades->listaEspecialidadesHabilitadasPorFecha($this->fechaIngresada, 3);

        foreach ($especialidadesPorFecha as $especialidadPorFechita) {

            if (
                (
                    ($especialidadPorFechita['fecha_inicio'] <= $this->fechaIngresada) ||
                    ($especialidadPorFechita['permanente'])
                ) &&
                (
                    ($especialidadPorFechita['fecha_fin'] >= $this->fechaIngresada) ||
                    ($especialidadPorFechita['permanente'])
                )
            ) {
                $nombreEspecialidadcita = strtoupper($especialidadPorFechita['especialidad']);

                if ($nombreEspecialidadcita === 'GERIATRÍA' && $this->edad <= $this->edadGeriatria) {
                    continue;
                }

                if (str_contains($nombreEspecialidadcita, 'PEDI') && $this->edad >= $this->edadPediatria) {
                    continue;
                }

                if ($nombreEspecialidadcita === 'GINECOLOGÍA Y OBSTETRICIA' && $this->sexo === 'M') {
                    continue;
                }

                /*if ($nombreEspecialidadcita === 'UROLOGÍA' && $this->sexo === 'F') {
                    continue;
                }*/

                $this->especialidadesDisponibles[] = [
                    'id_especialidad' => $especialidadPorFechita['id_especialidad'],
                    'especialidad' => $especialidadPorFechita['especialidad']
                ];
            }
        }
        usort($this->especialidadesDisponibles, function ($a, $b) {
            return strcmp($a['especialidad'], $b['especialidad']);
        });
        $this->especialidadesSinFiltrar = $this->especialidadesDisponibles;
    }

    public function fechaSeleccionadaAgenda($fechaElegida)
    {
        if (!$this->verificarFeriado($fechaElegida)) {

            $this->fechaIngresada = $fechaElegida;
            $this->diaElegido = Carbon::parse($fechaElegida)->dayOfWeek;

            //Para vigencia de carnets

            $verficacionCarnet = new VencimientoCarnet();
            $respuestaVerificacionCarnet = $verficacionCarnet->verificarVencimientoCarnet($this->id_persona, $this->fechaIngresada);

            if (!$respuestaVerificacionCarnet['vigente']) {
                $this->dispatch('carnetCaduco', fechaVencimiento: Carbon::parse($respuestaVerificacionCarnet['fecha_vencimiento'])->format('d/m/Y'));
                return;
            }

            // Fin vigencia de carnet

            //VERIFICACION DE GRUPO FAMILIAR AGENDADO

            $resultado = $this->verificarGrupoFamiliarAgendado($this->fechaIngresada);

            switch ($resultado) {
                case 1:
                    $this->dispatch('ustedEstaAgendado');
                    break;
                case 2:
                    $this->dispatch('existeAgendadoGrupoFamiliar');
                    break;
                default:
                    $this->obtenerEspecialidades();
                    $this->var = 5;
                    break;
            }

            //FIN VERIFICACION

            /*if ($this->verificarGrupoFamiliarAgendado($this->fechaIngresada) === 1) {
                $this->dispatch('ustedEstaAgendado');
            } elseif ($this->verificarGrupoFamiliarAgendado($this->fechaIngresada) === 2) {
                $this->dispatch('existeAgendadoGrupoFamiliar');
            } else {
                $this->obtenerEspecialidades();
                $this->var = 5;
            }*/
        }
    }
    /// 5. SELECCIONAR ESPECIALIDAD

    public function de5SelecEspecialidadA4SelectFecha()
    {
        $this->especialidadBusqueda = null;
        $this->especialidadesSinFiltrar = [];
        $this->especialidadesDisponibles = [];
        $this->fechaIngresada = null;
        $this->var = 4;
    }

    public function buscarEspecialidades()
    {
        $busqueda = trim($this->especialidadBusqueda);

        if ($busqueda === '') {
            $this->especialidadesDisponibles = $this->especialidadesSinFiltrar;
            return;
        }

        $this->especialidadesDisponibles = array_values(array_filter($this->especialidadesSinFiltrar, function ($nombreEspecialidad) use ($busqueda) {
            return mb_stripos($nombreEspecialidad['especialidad'], $busqueda) !== false;
        }));
    }

    //OBTENER LOS ESPECIALISTAS
    public function obtenerEspecialistas($idEspecialidad)
    {
        //dd('asdasdasd');
        try {
            //dd('asdasdasd');
            $this->especialidadLiteral = Especialidad::find($idEspecialidad)->especialidad;
            $idEspecialidad = (int) $idEspecialidad;


            $especialistasPorFecha = collect($this->servicioEspecialidades->listaEspecialistasHabilitadosPorFechaKiosco($this->fechaIngresada, 3, $idEspecialidad));
            $horariosPorFecha = collect($this->servicioEspecialidades->listaHorarioPorFecha($this->fechaIngresada));



            $especialistasDisponibles = [];
            $especialistasDisponibles = $especialistasPorFecha
                ->filter(function ($especialista) use ($idEspecialidad) {
                    if ($especialista['id_especialidad'] !== $idEspecialidad) {
                        return false;
                    }

                    $fechaValida = (($especialista['fecha_contrato_inicio'] <= $this->fechaIngresada || $especialista['permanente'])
                        && ($especialista['fecha_contrato_fin'] >= $this->fechaIngresada || $especialista['permanente']));

                    if (!$fechaValida) {
                        return false;
                    }

                    if (empty($especialista['especialistaHabilitado'])) {
                        return false;
                    }

                    if ($this->identificadorTipoAsegurado === 1 && !$especialista['afiliados']) {
                        return false;
                    }

                    if ($this->identificadorTipoAsegurado === 2 && !$especialista['convenios']) {
                        return false;
                    }

                    if ($this->identificadorTipoAsegurado === 3 && !$especialista['estudiantes']) {
                        return false;
                    }

                    if (!empty($especialista['vacacionesEspecialista']) && $this->tieneVacacionesActivas($especialista['vacacionesEspecialista'], $this->fechaIngresada)) {
                        return false;
                    }

                    //if ($especialista)

                    return true;
                })
                ->flatMap(function ($especialista) {
                    return collect($especialista['especialistaHabilitado'])
                        ->filter(function ($espHabServ) use ($especialista) {
                            return $espHabServ['id_dia'] === $this->diaElegido
                                && (
                                    ($espHabServ['fecha_inicio'] <= $this->fechaIngresada || $espHabServ['permanente']) &&
                                    ($espHabServ['fecha_fin'] >= $this->fechaIngresada || $espHabServ['permanente'])
                                );
                        })
                        ->map(function () use ($especialista) {
                            return [
                                'id_especialista' => $especialista['id_especialista'],
                                'id_especialidad' => $especialista['id_especialidad'],
                                'foto' => $especialista['foto'],
                                'nombres' => implode(' ', array_filter([
                                    $especialista['p_apellido'],
                                    $especialista['s_apellido'],
                                    $especialista['nombres']
                                ])),
                                'grado_academico' => $especialista['grado_academico'] === 'DOCTORA'
                                    ? 'DRA.'
                                    : ($especialista['grado_academico'] === 'DOCTOR' ? 'DR.' : 'LIC.'),
                                'sexo' => $especialista['sexo'],

                            ];
                        });
                })
                ->values()
                ->all();
            //dd($especialistasDisponibles, $this->diaElegido, $this->fechaIngresada);

            foreach ($especialistasDisponibles as $especialistaDisponible) {

                foreach ($horariosPorFecha as $horario) {
                    if (
                        $horario['id_dia'] === $this->diaElegido
                        && (($horario['fecha_inicio'] <= $this->fechaIngresada) &&
                            ($horario['fecha_fin'] >= $this->fechaIngresada || $horario['fecha_fin'] === null))
                        && $horario['id_especialista'] === $especialistaDisponible['id_especialista']
                    ) {

                        $verificacionAgendas = Agenda::where('id_asignacion_horario', $horario['id_asignacion_horario'])
                            ->where('fecha_agenda', $this->fechaIngresada)
                            ->whereNot('anulacion_ficha', true)
                            ->where('ficha_extra', false)
                            ->get()
                            ->groupBy('id_asignacion_horario');

                        $agendasVerificadas = $verificacionAgendas->get($horario['id_asignacion_horario'], collect());
                        $ocupadas = $agendasVerificadas->where('id_suspension_horario', null)->count();
                        $suspendidas = $agendasVerificadas->where('id_suspension_horario', '!=', null)->count();

                        $this->horariosDisponibles[] = [
                            "id_asignacion_horario" => $horario['id_asignacion_horario'],
                            'id_especialista_asignacion' => $horario['id_especialista'],
                            'fecha_inicio' => $horario['fecha_inicio'],
                            'fecha_fin' => $horario['fecha_fin'],
                            'id_dia' => $horario['id_dia'],
                            'id_horario' => $horario['id_horario'],
                            'hora_inicio' => $horario['hora_inicio'],
                            'hora_fin' => $horario['hora_fin'],
                            'intervalo' => $horario['intervalo'],
                            'numero_paciente' => $horario['numero_paciente'],
                            'turno' => $horario['turno'],
                            'nro_fichas_disponibles' => $horario['numero_paciente'] - ($ocupadas + $suspendidas),
                            'nro_fichas_ocupadas' => $ocupadas,
                            'nro_fichas_suspendidas' => $suspendidas,
                            'id_consultorio' => $horario['id_consultorio'],
                            'numero_consultorio' => $horario['numero_consultorio'],
                            'id_sedes' => $horario['id_sedes'],
                            'ubicacion' => $horario['ubicacion'],
                            'piso' => $horario['piso'],
                            'id_residencia' => $horario['id_residencia'],
                            'direccion' => $horario['direccion'],
                            'nro_vivienda' => $horario['nro_vivienda'],
                            'id_zona' => $horario['id_zona'],
                            'zona' => $horario['zona'],
                            'id_especialista_especialista' => $especialistaDisponible['id_especialista'],
                            'id_especialidad_especialista' => $especialistaDisponible['id_especialidad'],
                            'nombres' => $especialistaDisponible['nombres'],
                            'foto' => $especialistaDisponible['foto'],
                            'grado_academico' => $especialistaDisponible['grado_academico'],
                            'sexo' => $especialistaDisponible['sexo'],
                        ];
                    }
                }
            }

            //dd($this->horariosDisponibles);

            if ($this->horariosDisponibles == []) {
                //dd('clooasos');
                $this->dispatch('sinEspecialistas');
                //$this->de6SelecDoctorA5SelectEspecilidad();
                //dd($this->var);
            } else {
                $this->var = 6;
            }
        } catch (\Throwable $e) {

            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error en especialidad seleccionada", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    private function tieneVacacionesActivas(array $vacaciones, $fecha)
    {
        foreach ($vacaciones as $key => $vacacion) {
            if (
                $vacacion['estado'] === 'VACACION ACTIVA' &&
                $fecha >= $vacacion['fecha_inicio_vacacion'] &&
                $fecha <= $vacacion['fecha_fin_vacacion']
            ) {
                return true;
            }
        }
        return false;
    }

    public function especialidadSeleccionada($idEspecialidad)
    {
        $this->obtenerEspecialistas($idEspecialidad);
        //$this->var = 6;
    }

    // 6. SELECCIONAR ESPECIALISTA
    public function de6SelecDoctorA5SelectEspecilidad()
    {
        $this->especialidadBusqueda = null;
        $this->especialidadesDisponibles = $this->especialidadesSinFiltrar;
        $this->horariosDisponibles = [];
        $this->especialidadLiteral = null;
        $this->var = 5;
        //dd($this->var);
    }

    //PARA OBTENER LOS HORARIOS
    public function obtenerHorariosEspecialista($idAsignacionHorario, $idEspecialista)
    {
        $especialistaNombreCompleto = Especialista::with(['personaEspecialista'])->find($idEspecialista);
        $this->nombreCompletoEspecialista = implode(' ', array_filter([
            $especialistaNombreCompleto->personaEspecialista->p_apellido,
            $especialistaNombreCompleto->personaEspecialista->s_apellido,
            $especialistaNombreCompleto->personaEspecialista->nombres
        ]));
        $servicioEspecialidades = new ServiciosCachecito();

        $horariosElegibles = collect($servicioEspecialidades->listaHorarioPorFecha($this->fechaIngresada))
            ->first(function ($horitas) use ($idAsignacionHorario, $idEspecialista) {
                return $horitas['id_asignacion_horario'] == $idAsignacionHorario
                    && $horitas['id_especialista'] == $idEspecialista;
            });

        //dd($horariosElegibles);

        $this->listaHorarios = $this->listaHorasAgendaKiosko(
            $horariosElegibles['hora_inicio'],
            $horariosElegibles['hora_fin'],
            $horariosElegibles['intervalo'],
            $this->fechaIngresada,
            $horariosElegibles['id_asignacion_horario']
        );
    }


    public function listaHorasAgendaKiosko($horaInicio, $horaFin, $inter, $fechaAgenda, $idAsignacionHorario)
    {
        $horas = [];
        $inicio = Carbon::createFromFormat('H:i:s', $horaInicio);
        $fin = Carbon::createFromFormat('H:i:s', $horaFin);

        // horitas disponiblesssss
        while ($inicio->lt($fin)) {
            $horaActual = $inicio->format('H:i');
            $estadoHora = Agenda::with(['suspensionHorarioAgenda', 'servicioPlataformaAgenda'])
                ->where('fecha_agenda', $fechaAgenda)
                ->whereNot('anulacion_ficha', true)
                ->where('hora_agenda', $inicio->format('H:i:s'))
                ->where('id_asignacion_horario', $idAsignacionHorario)
                ->orderBy('id', 'desc')
                ->first();

            $horas[] = $this->procesarHoraAgendaKiosko($estadoHora, $horaActual);
            $inicio->addMinutes($inter);
        }

        return $horas;
    }

    public function procesarHoraAgendaKiosko($estadoHora, $horaActual)
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
            'horario_disponible' => true,
            'fuera_rango' => false,
        ];

        if ($estadoHora) {
            $hora['id_agenda'] = $estadoHora->id;
            if ($estadoHora->anulacion_ficha) {
                return $hora;
            } else {
                if ($estadoHora->id_persona) {
                    if ($estadoHora->id_suspension_horario) {
                        $hora['estado'] = 'SUSPENDIDO';
                        $hora['observacion'] = $estadoHora->suspensionHorarioAgenda->observacion;
                    } else {
                        $hora['estado'] = 'OCUPADO';
                    }
                } else {
                    $hora['estado'] = $estadoHora->id_suspension_horario ? 'SUSPENDIDO' : 'OCUPADO';
                    $hora['observacion'] = $estadoHora->suspensionHorarioAgenda->observacion ?? null;
                }
            }
            if ((Carbon::now())->gt(Carbon::parse($horaActual))) {
                //dd('si es mayor la hora actual', Carbon::parse($horaActual), Carbon::now(), Carbon::parse($horaActual)->gt(Carbon::now()));
                $hora['fuera_rango'] = true;
            }
        }

        return $hora;
    }

    public function especialistaSeleccionado($idAsignacionHorario, $idEspecialista)
    {
        $this->idAsignacionHorarioSeleccionado = $idAsignacionHorario;
        $this->idEspecialistaSeleccionado = $idEspecialista;
        $this->obtenerHorariosEspecialista($idAsignacionHorario, $idEspecialista);
        $this->var = 7;
    }
    // 7. SELECCIONAR HORARIO

    public function seleccionarHorario($horaSeleccionada)
    {
        $this->horaSeleccionada = $horaSeleccionada;
        $horaActualParaVerificacion = Carbon::now();
        $switchMismoDia = Carbon::now()->format('Y-m-d') === $this->fechaIngresada;

        if ($switchMismoDia && ($horaActualParaVerificacion->gt(Carbon::parse($horaSeleccionada)))) {
            $this->dispatch('horaYaPasada');
        } else {
            $this->dispatch('preguntarGuardarAgenda', hora: $this->horaSeleccionada);
        }
    }

    #[On('agendaKioskoConfirmada')]
    public function agendaKioskoConfirmada(Request $request)
    {

        $replica = new ReplicarAgendaS1();

        $horaC = Carbon::parse($this->horaSeleccionada)->format('H');
        $minutosC = Carbon::parse($this->horaSeleccionada)->format('i');
        $diaC = Carbon::parse($this->horaSeleccionada)->format('d');
        $mesC = Carbon::parse($this->horaSeleccionada)->format('m');
        $gestionC = Carbon::parse($this->horaSeleccionada)->format('Y');
        $clave_unica = $this->idAsignacionHorarioSeleccionado . $horaC . $minutosC . $diaC . $mesC . $gestionC;

        $verificarAgenda = Agenda::where('clave_unica', $clave_unica)->orderBy('id', 'desc')->first();

        if ($this->existeGrupoFamiliarAgendado($this->fechaIngresada, $this->id_persona) === 1) {
            $this->dispatch('ustedEstaAgendado');
            return;
        }

        if ($this->existeGrupoFamiliarAgendado($this->fechaIngresada, $this->id_persona) === 2) {
            $this->dispatch('existeAgendadoGrupoFamiliar');
            return;
        }

        if ($verificarAgenda) {
            $this->especialistaSeleccionado($this->idAsignacionHorarioSeleccionado, $this->idEspecialistaSeleccionado);
            $this->dispatch('agendaExistente');
            return;
        } else {

            if ($this->existeGrupoFamiliarAgendado($this->fechaIngresada, $this->id_persona) === 1) {
                $this->dispatch('ustedEstaAgendado');
                return;
            }

            if ($this->existeGrupoFamiliarAgendado($this->fechaIngresada, $this->id_persona) === 2) {
                $this->dispatch('existeAgendadoGrupoFamiliar');
                return;
            }

            $nuevaAgendaCiosko = Agenda::create([
                'fecha_agenda' => $this->fechaIngresada,
                'hora_agenda' => $this->horaSeleccionada,
                'confirmado' => true,
                'id_servicio_plataforma' => 3,
                'id_persona' => $this->id_persona,
                'id_asignacion_horario' => $this->idAsignacionHorarioSeleccionado,
                'clave_unica' => $clave_unica,
                'ip' => $request->ip(),
                'nombre_equipo' => gethostname(),
                'id_user_created' => 1,
            ]);

            if ($nuevaAgendaCiosko) {
                $replica->replicarAgenda($nuevaAgendaCiosko->id, $this->codigo_institucional, $this->nombre_institucion);
                $this->especialistaSeleccionado($this->idAsignacionHorarioSeleccionado, $this->idEspecialistaSeleccionado);
                if ($request->ip() === '192.168.2.11') {
                    $this->imprimirFichaKiosco($nuevaAgendaCiosko->id);
                }
                //$this->dispatch('imprimirAgenda', idAgenda: $nuevaAgendaCiosko->id);
                $this->dispatch('registroExistoso');
            } else {
                $this->dispatch('errorRegistro');
            }
        }
    }

    public function existeGrupoFamiliarAgendado($fechaSeleccionada, $idPersona)
    {
        $familiarAgendado = 0;
        $grupoFamiliarServicio = new GrupoFamiliarPersona();
        $familiares = $grupoFamiliarServicio->encontrarPersona($idPersona);

        $persona = collect($familiares)->where('id', $idPersona);
        $grupoFamiliar = $persona->pluck('id')->all();

        if (empty($grupoFamiliar)) {
            return false;
        }

        $existe = Agenda::where('fecha_agenda', $fechaSeleccionada)
            ->whereIn('id_persona', $grupoFamiliar)
            ->where('ficha_extra', false)
            ->whereNot('anulacion_ficha', true)
            ->orderBy('id', 'desc')
            ->first();

        if ($existe) {
            if ($existe->id_persona === $this->id_persona) {
                $familiarAgendado = 1;
            }

            if ($existe->id_persona !== $this->id_persona) {
                $familiarAgendado = 2;
            }
        }

        return $familiarAgendado;
    }

    public function de7ElegirHorarioA6SelecDoctor()
    {
        $this->nombreCompletoEspecialista = null;
        $this->listaHorarios = [];
        $this->var = 6;
    }

    public function imprimirFichaKiosco($idAgenda)
    {
        //dd('entra');
        try {
            $respuesta = new PrintController();
            $response = $respuesta->impresionAgendaCiosko($idAgenda);
            //dd($response);
            if ($response->isOk()) {
                $data = $response->getData(true);
                if ($data['success']) {
                    $escposData = $data['escpos_data'];
                    //dd($escposData);
                    $this->enviarFichaImpresion($escposData);
                    //session()->flash('message', 'Generando ficha para impresión...');
                } else {
                    //session()->flash('error', 'Error al generar datos de ficha: ' . $data['message']);
                }
            } else {
                //session()->flash('error', 'Error en la respuesta del servidor al generar ficha. Código: ' . $response->getStatusCode());
            }
        } catch (\Throwable $th) {
            //dd($th);
            //dd($th);//session()->flash('error', 'Error inesperado al intentar generar la ficha: ' . $th->getMessage());
        }
    }

    public function enviarFichaImpresion($escposBase64)
    {
        try {
            $url = LinkServicio::where('nombre_servicio', 'SERVIDOR DE IMPRESION')->first();
            $response = Http::timeout(10)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url->link_servicio, [
                    'escpos_data' => $escposBase64
                ]);
            //dd($url);

            /*if ($response->successful() && $response->json('success')) {
                session()->flash('message', 'Ficha enviada a impresión correctamente.');
            } else {
                session()->flash('error', 'Error en la impresión: ' . $response->json('message'));
            }*/
        } catch (\Throwable $th) {
            //sdd($th);
        }
    }
}
