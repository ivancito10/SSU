<?php

namespace App\Livewire\Plataforma\Agendamiento\Web;

use Livewire\Component;
use App\Models\Administracion\Especialidad;
use App\Models\Administracion\Persona;
use App\Models\Administracion\TipoAsegurado;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Estudiante;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Afiliacion\CodigoInstitucionSsu;
use App\Models\Aportes\Institucion;
use App\Models\Vigencia\AutorizacionInterior;
use App\Models\Vigencia\Convenio;
use App\Models\Plataforma\DiasHabilitadosAgenda;
use App\Models\Plataforma\Especialista;
use App\Models\Plataforma\Agenda;
use App\Models\Administracion\LinkServicio;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Plataforma\AsignacionHorario;
use App\Services\ListadoAsegurados;
use App\Services\GrupoFamiliarPersona;
use App\Services\DatosAgenda;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use App\Events\VerificarCachePoblacionAsegurada;
use App\Models\Plataforma\AdministracionFeriados;
use App\Models\Plataforma\CantidadHorario;
use App\Models\Plataforma\DemandaInsatisfecha;
use App\Models\Plataforma\Motivo;
use App\Models\Vigencia\BajaInstitucional;
use App\Services\AnulacionAgendaService;
use App\Services\ReplicarAgendaS1;
use App\Services\SocketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Services\VencimientoCarnet;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Locked;

class WebMain extends Component
{
    use WithPagination;

    public $fechasElegibles = [];
    protected $poblacion_asegurada_cache;
    protected $especialidades_cache;
    protected $especialistas_cache;
    protected $horarios_cache;

    //DATOS PERSONA
    public $fecha_nacimiento;
    public $nombre_completo;
    public $matricula;
    public $tipo_asegurado;
    public $foto_nombre;
    public $edad;
    public $sexo;
    public $excepcion;
    public $identificadorTipoAsegurado;
    public $idPersonaTitular;

    //PARA LA BUSQUEDA
    public $especialidadBusqueda;

    //DATOS AFIRMADO
    public $especialidadLiteral;
    public $especialistaLiteral;

    //AGENDA COMO TAL
    public $fechaElegida = '';
    public $diaElegido;
    public $especialidadesDisponibles = [];
    public $especialistasDisponibles = [];
    public $horariosDisponibles = [];
    public $listaHorarios = [];
    public $horaActualParaVerificacion;
    public $switchMismoDia;
    public $especialistaElegido;
    public $horarioElegido;
    public $horaElegida;
    public $edadGeriatria;
    public $edadPediatria;
    public $dia_semana;

    //DATOS POR SI YA TENIA AGENDA
    public $idAgendaExistente;
    public $fotoAgendaExistente;
    public $nombreCompletoAgendaExistente;
    public $matriculaAgendaExistente;
    public $edadAgendaExistente;
    public $tipoAseguradoAgendaExistente;
    public $especialidadAgendaExistente;
    public $especialistaAgendaExistente;
    public $fechaAgendaExistente;
    public $horaAgendaExistente;
    public $horaInicioAgendaExistente;
    public $horaFinAgendaExistente;
    public $turnoAgendaExistente;
    public $ubicacionAgendaExistente;
    public $servicioPlataformaAgendaExistente;
    public $nroConsultorioAgendaExistente;
    public $pisoAgendaExistente;
    public $direccionAgendaExistente;
    public $nroViviendaAgendaExistente;
    public $nombreZonaAgendaExistente;
    public $fechaRegistroAgendaExistente;
    public $fotoEspecialistaAgendaExistente;

    //DATOS POR SI YA TENIA AGENDA (beneficiario)
    public $idAgendaExistenteBeneficiario;
    public $fotoAgendaExistenteBeneficiario;
    public $nombreCompletoAgendaExistenteBeneficiario;
    public $matriculaAgendaExistenteBeneficiario;
    public $edadAgendaExistenteBeneficiario;
    public $tipoAseguradoAgendaExistenteBeneficiario;
    public $especialidadAgendaExistenteBeneficiario;
    public $especialistaAgendaExistenteBeneficiario;
    public $fechaAgendaExistenteBeneficiario;
    public $horaAgendaExistenteBeneficiario;
    public $horaInicioAgendaExistenteBeneficiario;
    public $horaFinAgendaExistenteBeneficiario;
    public $turnoAgendaExistenteBeneficiario;
    public $ubicacionAgendaExistenteBeneficiario;
    public $servicioPlataformaAgendaExistenteBeneficiario;
    public $nroConsultorioAgendaExistenteBeneficiario;
    public $pisoAgendaExistenteBeneficiario;
    public $direccionAgendaExistenteBeneficiario;
    public $nroViviendaAgendaExistenteBeneficiario;
    public $nombreZonaAgendaExistenteBeneficiario;
    public $fechaRegistroAgendaExistenteBeneficiario;
    public $fotoEspecialistaAgendaExistenteBeneficiario;

    //PARA LA BUSQUEDA
    public $especialidadesSinFiltrar = [];

    //PASOS
    public $paso = 1;

    //PARA EL GRUPO FAMILIAR
    public $switchSeleccionoOtroFamiliar;
    public $idPersonaGrupoFamiliar;
    public $personasGrupoFamiliar = [];
    public $switchEsTitular;
    public $switchTieneGrupoFamiliar;
    public $switchMostrarBotonGrupo;

    public $switchDentroDelRango;
    public $horaFinDisponibilidadHorario;
    public $demandaSwitch;

    //PARA LA DEMANDA
    public $idEspecialidadDemanda;
    public $idEspecialistaDemanda;
    public $idAsignacionHorarioDemanda;

    //para actualizar el horario en tiempo teal
    public $horariosElegibles = [];
    public $pasoAEspecialidadesSwitch = false;

    //PARA MOSTRAR SI ES SUSPENSION
    public $suspensionAgenda = false;
    public $suspensionAgendaBeneficiario = false;
    public $motivoSuspension;
    public $usuarioCreaSuspension;
    public $fechaSuspensionCreada;
    public $motivoSuspensionBeneficiario;
    public $usuarioCreaSuspensionBeneficiario;
    public $fechaSuspensionCreadaBeneficiario;

    public $iconosEspecialidades = [
        "MEDICINA GENERAL" => "bi bi-hospital",
        "MEDICINA INTERNA" => "bi bi-hospital",
        "PEDIATRÍA" => "bi bi-emoji-smile",
        "GINECOLOGÍA Y OBSTETRICIA" => "bi bi-gender-female",
        "ODONTOLOGÍA" => "bi bi-tooth",
        "ODONT. PREVENTIVA" => "bi bi-tooth",
        "ODONTOLOGIA 2" => "bi bi-tooth",
        "NEUROLOGÍA INFANTIL" => "bi bi-magic",
        "NEUROLOGÍA CLINICA" => "bi bi-magic",
        "NEUROCIRUGÍA" => "bi bi-magic",
        "CARDIOLOGÍA" => "bi bi-heart-pulse",
        "CARDIOLOGÍA PEDIATRICA" => "bi bi-heart-pulse",
        "PREVENCION CARDIOVASCULAR" => "bi bi-heart-pulse",
        "CIRUG. CARDIOVASCULAR" => "bi bi-heart-pulse",
        "CIRUGIA VASCULAR" => "bi bi-heart-pulse",
        "DERMATOLOGÍA" => "bi bi-droplet",
        "OFTALMOLOGÍA" => "bi bi-eye",
        "OFTALMOLOGIA QUIRURGICA" => "bi bi-eye",
        "GASTROENTEROLOGIA" => "bi bi-cup-straw",
        "OTORRINOLARINGOLOGÍA" => "bi bi-ear",
        "GERIATRÍA" => "bi bi-person-walking",
        "PROCTOLOGÍA" => "bi bi-droplet-half",
        "TRAUMATOLOGÍA" => "bi bi-bandaid",
        "TRAUMATOLOGIA ORTOPEDIA PEDIATRICA" => "bi bi-bandaid",
        "CIRUGÍA" => "bi bi-scissors",
        "CIRUGÍA GENERAL" => "bi bi-scissors",
        "CIRUGÍA PEDIATRICA" => "bi bi-scissors",
        "CIRUGIA MAXILO FACIAL" => "bi bi-scissors",
        "CIRUGIA MAXILOFACIAL" => "bi bi-scissors",
        "CIRUGIA PLASTICA Y RECONSTRUCTIVA" => "bi bi-scissors",
        "CIRUGIA 2" => "bi bi-scissors",
        "UROLOGÍA" => "bi bi-droplet",
        "NUTRICION" => "bi bi-apple",
        "MEDICINA PREVENTIVA" => "bi bi-shield-check",
        "MEDICINA FAMILIAR" => "bi bi-house-heart",
        "MEDICINA FAMILIAR Y PREVENTIVA" => "bi bi-house-heart",
        "NEFROLOGIA" => "bi bi-droplet",
        "REUMATOLOGÍA" => "bi bi-bandaid",
        "EMERGENCIAS" => "bi bi-exclamation-triangle",
        "EMERGENCIA" => "bi bi-exclamation-triangle",
        "IMAGENOLOGIA" => "bi bi-camera",
        "ECOGRAFIA" => "bi bi-camera",
        "ECOCARDIOGRAFIA" => "bi bi-camera",
        "ECOCARDIOGRAFIA PEDIATRICA" => "bi bi-camera",
        "MAMOGRAFIA" => "bi bi-camera",
        "LABORATORIO" => "bi bi-flask",
        "FARMACIA" => "bi bi-capsule",
        "PSIQUIATRIA" => "bi bi-brain",
        "PSICOLOGIA" => "bi bi-brain",
        "TRABAJO SOCIAL" => "bi bi-people",
        "OBSTETRICIA" => "bi bi-gender-female",
        "ONCOLOGIA" => "bi bi-virus",
        "ONCOLOGIA  GENERAL" => "bi bi-virus",
        "ONCOLOGIA QUIRURGICA" => "bi bi-virus",
        "ONCOHEMATOLOGIA" => "bi bi-virus",
        "TRANSFUCIONAL" => "bi bi-droplet",
        "HEMATOLOGIA" => "bi bi-droplet",
        "HEMATOLOGIA " => "bi bi-droplet",
        "EPIDEMIOLOGIA" => "bi bi-graph-up",
        "FISIATRIA" => "bi bi-body-text",
        "INFECTOLOGIA" => "bi bi-virus",
        "COVID-19" => "bi bi-virus",
        "ANESTESIOLOGIA" => "bi bi-cloud-slash",
        "TERAPIA INTENSIVA" => "bi bi-activity",
        "TERAPIA DEL DOLOR" => "bi bi-emoji-frown",
        "INMUNOALERGOLOGÍA" => "bi bi-shield-plus",
        "CAMPAÑA SALUD" => "bi bi-megaphone",
        "TRIAGE" => "bi bi-list-check",
        "P. PAPILOMA HUMANO" => "bi bi-virus",
        "MEDICINA DEL TRABAJO" => "bi bi-briefcase",
        "ESPECIALIDAD DE PRUEBA" => "bi bi-question-circle",
        "PRUEBA" => "bi bi-question-circle",
        "OTROS" => "bi bi-three-dots",
    ];


    public function render()
    {

        return view('livewire.Plataforma.agendamiento.web.web-main');
    }

    public function mount()
    {
        $this->switchSeleccionoOtroFamiliar = false;
        $this->pasoAEspecialidadesSwitch = false;
        $this->switchEsTitular = false;
        $this->switchMostrarBotonGrupo = true;
        $this->suspensionAgenda = false;
        $this->suspensionAgendaBeneficiario = false;
        $this->edadGeriatria = 49;
        $this->edadPediatria = 18;
        $this->demandaSwitch = false;
        $this->obtenerFechasDisponibles();
        $this->obtenerDatosPersona();
        $this->porcentajeHorario();
    }

    #[On('porcentajeHorario')]
    public function porcentajeHorario()
    {
        $horarios = CantidadHorario::where('eliminado', false)
            ->where('estado', true)
            ->whereHas('diasHabilitadosAgendaCantidadHorario', function ($dhaCantidadHorario) {
                $dhaCantidadHorario->where('id_servicio_plataforma', 2);
            })
            ->with(['diasHabilitadosAgendaCantidadHorario'])
            ->orderBy('id', 'asc')->get();

        $this->switchDentroDelRango = false;
        $horasDisponibles = [];
        foreach ($horarios as $key => $horario) {
            $horasDisponibles[] = Carbon::parse($horario->hora_inicio)->format('H:i') . ' - ' . Carbon::parse($horario->hora_fin)->format('H:i');
            if ((Carbon::now()->gte(Carbon::parse($horario->hora_inicio))) && (Carbon::now()->lte(Carbon::parse($horario->hora_fin)))) {
                $this->switchDentroDelRango = true;
                $this->horaFinDisponibilidadHorario = $horario->hora_fin;
            }
        }

        if (!Auth::user()->hasPermissionTo('super-admin-only')) {
            if (!$this->switchDentroDelRango) {
                $this->dispatch('fueraDeRango', horasDisponibles: $horasDisponibles);
            }
        }
    }

    /*#[On('verificarHora')]
    public function verificarHora()
    {
        $this->porcentajeHorario();
    }*/

    public function verificarVigenciaDiaSeleccionado($idPersona, $fechaSeleccionada)
    {
        $idTipoAsegurado = Persona::where('id_persona', $idPersona)->value('id_tipo_asegurado');

        //dd($idTipoAsegurado);

        $ultimaBaja = BajaInstitucional::where('id_persona', $idPersona)
            ->orderBy('fecha_baja_ssu', 'desc')
            ->first();

        if (!$ultimaBaja) {
            return false;
        }

        $fechaBaja = $ultimaBaja->fecha_baja_ssu;

        if ($fechaSeleccionada >= $fechaBaja) {
            if ($fechaSeleccionada === $fechaBaja) {
                return true;
            }
            return $idTipoAsegurado === 0;
        }
        return false;
    }

    public function obtenerFechasDisponibles()
    {
        try {
            $fechasDisponibles = DiasHabilitadosAgenda::where('estado', true)
                ->where('id_servicio_plataforma', 2)
                ->first();

            $this->fechasElegibles = [];
            $diasHabilitadosAgenda = $fechasDisponibles->nro_dias;
            $agregarHoy = $fechasDisponibles->hoy;
            $fecha = Carbon::now();
            if (!$agregarHoy) {
                $fecha->addDay();
            }
            while (count($this->fechasElegibles) < $diasHabilitadosAgenda) {
                //if ($fecha->dayOfWeek !== 0) {

                $this->fechasElegibles[] = [
                    'fecha' => $fecha->format('Y-m-d'),
                    'fecha_literal' => mb_strtoupper($fecha->isoFormat('dddd')),
                    'feriado' => $this->verificarFeriado($fecha->format('Y-m-d')),
                    'nro_dia' => $fecha->dayOfWeek,
                ];
                //}

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

    protected function obtenerPoblacionAseguradaCache()
    {
        $this->poblacion_asegurada_cache = Cache::get('poblacion_asegurada');
    }

    protected function obtenerEspecialidadesCache()
    {
        $this->especialidades_cache = Cache::get('especialidades');
    }

    protected function obtenerEspecialistasCache()
    {
        $this->especialistas_cache = Cache::get('especialistas');
        //dd($this->especialistas_cache);
    }

    protected function obtenerHorariosCache()
    {
        $this->horarios_cache = Cache::get('horarios');
    }

    public function seleccionarGrupoFamiliar()
    {
        $this->switchSeleccionoOtroFamiliar = true;
        $this->switchMostrarBotonGrupo = false;
        $this->obtenerPoblacionAseguradaCache();
        $this->personasGrupoFamiliar = collect($this->poblacion_asegurada_cache)->where('id_persona_titular', auth()->user()->id_persona);
    }

    public function obtenerDatosPersona()
    {
        try {
            $this->obtenerPoblacionAseguradaCache();
            $persona = collect($this->poblacion_asegurada_cache)->firstWhere('id_persona', auth()->user()->id_persona);
            // dd($persona);
            if (!$persona) {
                //$this->porcentajeHorario();
                $this->dispatch('noHayDatosPersona');
                return;
            }

            $this->fecha_nacimiento = $persona['fecha_nacimiento'];
            $this->edad = Carbon::parse($persona['fecha_nacimiento'])->age;
            $this->nombre_completo = implode(' ', array_filter([$persona['nombres'], $persona['p_apellido'], $persona['s_apellido']]));
            $this->matricula = $persona['matricula_seguro'];
            $this->tipo_asegurado = $persona['tipo_asegurado'];
            $this->sexo = $persona['sexo'];
            $this->excepcion = $persona['excepcion'];
            $this->identificadorTipoAsegurado = $this->definirTipoAsegurado($persona['tipo_asegurado']);
            $this->idPersonaTitular = $persona['id_persona_titular'];
            $this->foto_nombre = $this->obtenerImagenBase64($persona['foto'], $persona['liga']);
            // dd($this->foto_nombre,$persona);
            if ($persona['id_persona'] === $persona['id_persona_titular']) {
                $this->switchEsTitular = true;
            }

            if (count(collect($this->poblacion_asegurada_cache)->where('id_persona_titular', auth()->user()->id_persona)) > 1) {
                $this->switchTieneGrupoFamiliar = true;
            }

            //dd($this->idPersonaTitular);   //ESTO ES PARA QUE YA NO APAREZCA EL BOTON DE ELEGIR OTRO FAMILIAR SI ES QUE YA TIENE UNA AGENDA ALGUIEN
            if ($this->existeGrupoFamiliarAgendado(Carbon::now()->addDay(), $this->idPersonaTitular)) {
                $this->switchTieneGrupoFamiliar = false;
            }
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al obtener datos persona", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    public function obtenerDatosOtroPersonaGrupoFamiliar()
    {

        if ($this->idPersonaGrupoFamiliar !== "") {
            $this->obtenerPoblacionAseguradaCache();
            $persona = collect($this->poblacion_asegurada_cache)->firstWhere('id_persona', $this->idPersonaGrupoFamiliar);
            $this->fecha_nacimiento = $persona['fecha_nacimiento'];
            $this->edad = Carbon::parse($persona['fecha_nacimiento'])->age;
            $this->nombre_completo = implode(' ', array_filter([$persona['nombres'], $persona['p_apellido'], $persona['s_apellido']]));
            $this->matricula = $persona['matricula_seguro'];
            $this->tipo_asegurado = $persona['tipo_asegurado'];
            $this->sexo = $persona['sexo'];
            $this->excepcion = $persona['excepcion'];
            $this->identificadorTipoAsegurado = $this->definirTipoAsegurado($persona['tipo_asegurado']);
            $this->idPersonaTitular = $persona['id_persona_titular'];
            $this->foto_nombre = $this->obtenerImagenBase64($persona['foto'], $persona['liga']);

            $this->dispatch('familiarSeleccionado', nombreFamiliar: $this->nombre_completo);
        } else {
            $this->dispatch('sinFamiliarSeleccionado');
        }
    }

    public function obtenerFotoOtraAgendaBeneficiario($idPersona)
    {
        $this->obtenerPoblacionAseguradaCache();
        $persona = collect($this->poblacion_asegurada_cache)->firstWhere('id_persona', $idPersona);
        //dd($this->obtenerImagenBase64($persona['foto'], $persona['liga']));
        return $this->obtenerImagenBase64($persona['foto'], $persona['liga']);
    }

    public function fechaSeleccionada($fecha)
    {
        try {

            //$this->verificarVigenciaDiaSeleccionado($fecha)

            if ($this->idPersonaGrupoFamiliar === "") {
                $this->dispatch('sinFamiliarSeleccionado');
                return;
            }

            $this->pasoAEspecialidadesSwitch = true;
            $existe = collect($this->fechasElegibles)->contains('fecha', $fecha);
            if ($existe && Carbon::parse($fecha)->dayOfWeek !== 0) {
                $this->fechaElegida = Carbon::parse($fecha)->format('Y-m-d');
                $this->diaElegido = Carbon::parse($fecha)->dayOfWeek;

                //Para vigencia en general

                /*$idPersonaVerificar = $this->switchSeleccionoOtroFamiliar ? $this->idPersonaGrupoFamiliar : auth()->user()->id_persona;
                $this->verificarVigenciaDiaSeleccionado($idPersonaVerificar, $fecha);*/


                //Para vigencia de carnets

                $verficacionCarnet = new VencimientoCarnet();
                $respuestaVerificacionCarnet = $verficacionCarnet->verificarVencimientoCarnet(
                    $this->switchSeleccionoOtroFamiliar ? $this->idPersonaGrupoFamiliar : auth()->user()->id_persona,
                    $this->fechaElegida
                );

                if (!$respuestaVerificacionCarnet['vigente']) {
                    $this->dispatch('carnetCaduco', fechaVencimiento: Carbon::parse($respuestaVerificacionCarnet['fecha_vencimiento'])->format('d/m/Y'));
                    $this->pasoAEspecialidadesSwitch = false;
                    return;
                }

                // Fin vigencia de carnet
                switch ($this->diaElegido) {
                    case Carbon::SUNDAY:
                        $this->dia_semana = 'DOMINGO';
                        break;
                    case Carbon::MONDAY:
                        $this->dia_semana = 'LUNES';
                        break;
                    case Carbon::TUESDAY:
                        $this->dia_semana = 'MARTES';
                        break;
                    case Carbon::WEDNESDAY:
                        $this->dia_semana = 'MIÉRCOLES';
                        break;
                    case Carbon::THURSDAY:
                        $this->dia_semana = 'JUEVES';
                        break;
                    case Carbon::FRIDAY:
                        $this->dia_semana = 'VIERNES';
                        break;
                    case Carbon::SATURDAY:
                        $this->dia_semana = 'SÁBADO';
                        break;
                    default:
                        $this->dia_semana = 'DÍA DESCONOCIDO';
                        break;
                }

                if ($this->verificarFeriado($this->fechaElegida)) {
                    $this->paso = 2;
                    $this->retroceder();
                    $this->dispatch('fechaFeriado');
                    return;
                }

                if ($this->existeGrupoFamiliarAgendado($this->fechaElegida, $this->idPersonaTitular)) {


                    if ($this->agendaPropia($this->fechaElegida, auth()->user()->id_persona)) {
                        //dd(auth()->user()->id_persona);
                        $this->mostrarDatosAgenditaPropia($this->fechaElegida, auth()->user()->id_persona);
                        $this->paso = 2;
                        $this->retroceder();
                        $this->dispatch('agendoElMismo');
                        return;
                    } else {
                        //dd('logico');
                        if ($this->idPersonaTitular === auth()->user()->id_persona) {
                            //dd('2');
                            $this->mostrarDatosAgenditaBeneficiario(
                                $this->fechaElegida,
                                $this->existeGrupoFamiliarAgendadoId($this->fechaElegida, $this->idPersonaTitular)
                            );
                            $this->paso = 2;
                            $this->retroceder();
                            $this->dispatch('agendoElMismoBeneficiario');
                            return;
                        } else {
                            //dd('3');
                            $this->paso = 2;
                            $this->retroceder();
                            $this->dispatch('existeAgendadoGrupoFamiliar');
                            return;
                        }
                        /*$this->paso = 2;
                        $this->retroceder();
                        $this->dispatch('agendoElMismo');
                        return;*/
                    }
                    /*$this->paso = 2;
                    $this->retroceder();
                    $this->dispatch('existeAgendadoGrupoFamiliar');
                    return;*/
                }

                $this->obtenerEspecialidadesCache();
                $especialidadesPorFecha = collect($this->especialidades_cache)->sortBy('especialidad');

                foreach ($especialidadesPorFecha as $especialidadPorFechita) {

                    if (
                        (
                            ($especialidadPorFechita['fecha_inicio'] <= $this->fechaElegida) ||
                            ($especialidadPorFechita['permanente'])
                        ) &&
                        (
                            ($especialidadPorFechita['fecha_fin'] >= $this->fechaElegida) ||
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
                $this->especialidadesSinFiltrar = $this->especialidadesDisponibles;
                $this->paso = 2;
            } else {
                $this->dispatch('fechaInexistenteOFueraDeLasFechasDisponibles');
            }
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error en fecha seleccionada", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }



    public function buscarEspecialidad()
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

    public function especialidadSeleccionada($idEspecialidad)
    {
        try {
            $this->obtenerEspecialistasCache();
            $this->obtenerHorariosCache();

            //dd($this->especialistas_cache);
            //$this->especialidadLiteral = Especialidad::find($idEspecialidad)->especialidad;
            $especialidad = collect($this->especialidadesSinFiltrar)->firstWhere('id_especialidad', $idEspecialidad);
            $this->especialidadLiteral = $especialidad ? $especialidad['especialidad'] : null;
            $horariosPorFecha = collect($this->horarios_cache);
            $especialistasPorFecha = collect($this->especialistas_cache);
            $especialistasDisponibles = [];

            $especialistasDisponibles = $especialistasPorFecha
                ->filter(function ($especialista) use ($idEspecialidad) {
                    if ($especialista['id_especialidad'] !== $idEspecialidad) {
                        return false;
                    }

                    $fechaValida = (($especialista['fecha_contrato_inicio'] <= $this->fechaElegida || $especialista['permanente'])
                        && ($especialista['fecha_contrato_fin'] >= $this->fechaElegida || $especialista['permanente']));

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

                    if (!empty($especialista['vacacionesEspecialista']) && $this->tieneVacacionesActivas($especialista['vacacionesEspecialista'], $this->fechaElegida)) {
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
                                    ($espHabServ['fecha_inicio'] <= $this->fechaElegida || $espHabServ['permanente']) &&
                                    ($espHabServ['fecha_fin'] >= $this->fechaElegida || $espHabServ['permanente'])
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
            //dd ($especialistasDisponibles);

            foreach ($especialistasDisponibles as $especialistaDisponible) {

                foreach ($horariosPorFecha as $horario) {
                    if (
                        $horario['id_dia'] === $this->diaElegido
                        && (($horario['fecha_inicio'] <= $this->fechaElegida) &&
                            ($horario['fecha_fin'] >= $this->fechaElegida || $horario['fecha_fin'] === null))
                        && $horario['id_especialista'] === $especialistaDisponible['id_especialista']
                    ) {

                        $verificacionAgendas = Agenda::where('id_asignacion_horario', $horario['id_asignacion_horario'])
                            ->where('fecha_agenda', $this->fechaElegida)
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

            if ($this->horariosDisponibles === []) {
                $this->dispatch('noHayEspecialistasDisponibles');
            } else {
                $this->paso = 3;
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

    public function especialistaSeleccionado($idEspecialista, $idAsignacionHorario)
    {
        $this->especialistaElegido = $idEspecialista;
        $this->horarioElegido = $idAsignacionHorario;

        $especialistaMostrar = collect($this->horariosDisponibles)->firstWhere('id_especialista_asignacion', $idEspecialista);
        $this->especialistaLiteral = $especialistaMostrar ? $especialistaMostrar['grado_academico'] . ' ' . $especialistaMostrar['nombres'] : null;

        $this->obtenerHorariosCache();
        $this->horariosElegibles = collect($this->horarios_cache)
            ->first(function ($horitas) use ($idAsignacionHorario, $idEspecialista) {
                return $horitas['id_asignacion_horario'] == $idAsignacionHorario
                    && $horitas['id_especialista'] == $idEspecialista;
            });

        //dd($horariosElegibles);

        $this->listaHorarios = $this->listaHorasAgendaWeb(
            $this->horariosElegibles['hora_inicio'],
            $this->horariosElegibles['hora_fin'],
            $this->horariosElegibles['intervalo'],
            $this->fechaElegida,
            $this->horariosElegibles['id_asignacion_horario']
        );

        if ($this->listaHorarios === []) {
            $this->dispatch('noHayHorasDisponibles');
            return;
        } else {
            //dd('sigue entrando aqui');
            $this->paso = 4;
        }
    }

    public function cantidadHorarioDisponible()
    {
        $cantidadHorarios = CantidadHorario::where('eliminado', false)
            ->where('estado', true)
            ->whereHas('diasHabilitadosAgendaCantidadHorario', function ($dhaCantidadHorario) {
                $dhaCantidadHorario->where('id_servicio_plataforma', 2);
            })
            ->with(['diasHabilitadosAgendaCantidadHorario'])
            ->orderBy('id', 'asc')->get();

        $porcentaje = 0;

        foreach ($cantidadHorarios as $key => $cantidadHorario) {
            $horasDisponibles[] = Carbon::parse($cantidadHorario->hora_inicio)->format('H:i') . ' - ' . Carbon::parse($cantidadHorario->hora_fin)->format('H:i');
            if ((Carbon::now()->gte(Carbon::parse($cantidadHorario->hora_inicio))) && (Carbon::now()->lte(Carbon::parse($cantidadHorario->hora_fin)))) {
                $porcentaje = $cantidadHorario->porcentaje;
            }
        }

        return $porcentaje;
    }

    public function revisarHoras(array $horas, $porcentaje)
    {
        $numeroHorarios = count($horas);

        $numeroHorariosDisponibles = (int) ceil(($numeroHorarios * $porcentaje) / 100);

        foreach ($horas as $key => &$hora) {
            if (($key + 1) > $numeroHorariosDisponibles) {
                $hora['horario_disponible'] = false;
            }
        }
        unset($hora);

        return $horas;
    }

    public function listaHorasAgendaWeb($horaInicio, $horaFin, $inter, $fechaAgenda, $idAsignacionHorario)
    {
        try {
            $this->demandaSwitch = false;
            $this->horaActualParaVerificacion = Carbon::now()->format('H:i');
            $this->switchMismoDia = Carbon::now()->format('Y-m-d') === $this->fechaElegida;

            $procentajeHorarioDisponible = $this->cantidadHorarioDisponible();

            $horas = [];
            $inicio = Carbon::createFromFormat('H:i:s', $horaInicio);
            $fin = Carbon::createFromFormat('H:i:s', $horaFin);

            // horitas disponiblesssss
            while ($inicio->lt($fin)) {
                $horaActual = $inicio->format('H:i');
                $estadoHora = Agenda::with(['suspensionHorarioAgenda'])
                    ->where('fecha_agenda', $fechaAgenda)
                    ->where('hora_agenda', $inicio->format('H:i:s'))
                    ->where('id_asignacion_horario', $idAsignacionHorario)
                    ->orderBy('id', 'desc')
                    ->first();

                $horas[] = $this->procesarHoraAgendaWeb($estadoHora, $horaActual);
                $inicio->addMinutes($inter);
            }

            //return $horas;
            $existenHoras = $this->revisarHoras($horas, $procentajeHorarioDisponible);
            $cantidadAgendasLibres = 0;

            foreach ($existenHoras as $existeHora) {
                if ($existeHora['estado'] === 'LIBRE') {
                    $cantidadAgendasLibres++;
                }
            }

            if ($cantidadAgendasLibres === 0) {
                $this->demandaSwitch = true;
            }

            return $existenHoras;
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al listar horas agenda web", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    public function procesarHoraAgendaWeb($estadoHora, $horaActual)
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
        ];

        if ($estadoHora) {
            $hora['id_agenda'] = $estadoHora->id;
            if ($estadoHora->anulacion_ficha) {
                return $hora;
            } else {
                if ($estadoHora->id_persona) {
                    /*if ($estadoHora->anulacion_ficha) {
                        return $hora;
                    }*/

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
        }
        return $hora;
    }

    public function guardarAgenda($horaSeleccionada)
    {
        //dd('entra');
        if (!Carbon::parse($this->horaFinDisponibilidadHorario)->lt(Carbon::now())) {
            $this->horaElegida = $horaSeleccionada;

            if ($this->horaElegida < Carbon::now()->format('H:i') && $this->fechaElegida === Carbon::now()->format('Y-m-d')) {
                //dd('pasa?');
                $this->dispatch('horaYaPasada');
                return;
            }

            $this->dispatch('preguntarGuardarAgenda', hora: $this->horaElegida);
        } else {
            $this->paso = 2;
            $this->retroceder();
            $this->switchDentroDelRango = false;
            $this->dispatch('agendaNoDisponible');
        }
    }

    public function noPermitirAgenda()
    {
        $this->paso = 2;
        $this->retroceder();
        $this->switchDentroDelRango = false;
        $this->dispatch('agendaNoDisponible');
    }

    #[On('agendaWebConfirmada')]
    public function agendaWebConfirmada(Request $request)
    {
        //dd('entra');
        try {
            $horaC = Carbon::parse($this->horaElegida)->format('H');
            $minutosC = Carbon::parse($this->horaElegida)->format('i');
            $diaC = Carbon::parse($this->fechaElegida)->format('d');
            $mesC = Carbon::parse($this->fechaElegida)->format('m');
            $gestionC = Carbon::parse($this->fechaElegida)->format('Y');
            $clave_unica = $this->horarioElegido . $horaC . $minutosC . $diaC . $mesC . $gestionC;

            $verificarAgenda = Agenda::where('clave_unica', $clave_unica)->first();
            if ($this->existeGrupoFamiliarAgendado($this->fechaElegida, $this->idPersonaTitular)) {
                $this->paso = 2;
                $this->retroceder();
                $this->dispatch('existeAgendadoGrupoFamiliar');
                return;
            }

            if ($verificarAgenda) {
                $this->especialistaSeleccionado($this->especialistaElegido, $this->horarioElegido);
                //$this->actualizarHorarios();
                $this->dispatch('agendaExistente');
                return;
            } else {

                if ($this->existeGrupoFamiliarAgendado($this->fechaElegida, $this->idPersonaTitular)) {
                    $this->paso = 2;
                    $this->retroceder();
                    $this->dispatch('existeAgendadoGrupoFamiliar');
                    return;
                }

                $idPersonaGuardar = $this->switchSeleccionoOtroFamiliar ? $this->idPersonaGrupoFamiliar : auth()->user()->id_persona;
                $obsRegistro = null;

                if ($this->idPersonaGrupoFamiliar === null && $this->switchSeleccionoOtroFamiliar) {
                    $idPersonaGuardar = auth()->user()->id_persona;
                    $obsRegistro = 'El usuario dio click en el botón para seleccionar otro asegurado de su grupo familiar pero no seleccionó ninguna opción';
                }
                //dd($idPersonaGuardar, $this->switchSeleccionoOtroFamiliar, $this->idPersonaGrupoFamiliar, auth()->user()->id);        
                if ($this->horaElegida === null) {
                    $this->dispatch('vuelvaAIntentarlo');
                }

                if ($idPersonaGuardar !== null) {
                    $nuevaAgendaWeb = Agenda::create([
                        'fecha_agenda' => $this->fechaElegida,
                        'hora_agenda' => $this->horaElegida,
                        'confirmado' => true,
                        'id_servicio_plataforma' => 2,
                        'id_persona' => $idPersonaGuardar,
                        'id_asignacion_horario' => $this->horarioElegido,
                        'clave_unica' => $clave_unica,
                        'observacion' => $obsRegistro,
                        'ip' => $request->ip(),
                        'nombre_equipo' => gethostname(),
                        'id_user_created' => auth()->user()->id,
                    ]);

                    $fechaConfirmada = $this->fechaElegida;

                    if ($nuevaAgendaWeb) {
                        //$this->especialistaSeleccionado($this->especialistaElegido, $this->horarioElegido);
                        $this->actualizarHorarios();
                        /*$this->paso = 2;
                        $this->retroceder();*/


                        $this->listaHorarios = [];
                        $this->horariosDisponibles = [];
                        $this->especialidadesDisponibles = [];
                        $this->horaElegida = null;
                        $this->especialistaElegido = null;
                        $this->horarioElegido = null;
                        $this->fechaElegida = null;
                        $this->diaElegido = null;
                        $this->especialidadLiteral = null;
                        $this->especialistaLiteral = null;
                        $this->pasoAEspecialidadesSwitch = false;
                        $this->paso = 1;

                        $this->switchTieneGrupoFamiliar = false;
                        //dd('entra paso: ', $this->paso);
                        $this->mostrarAgendaConfirmada($fechaConfirmada, $idPersonaGuardar);
                        //dd('entra paso: ', $this->paso);

                        //$this->dispatch('registroExistoso');

                    } else {

                        //dd($nuevaAgendaWeb, 'entra al error');
                        $this->dispatch('errorRegistro');
                    }
                } else {
                    dd('entra aqui');
                    ///dd('entra al 2 error');
                    $this->dispatch('errorRegistro');
                }
            }
        } catch (\Throwable $e) {
            //dd('entra al catch', $e);
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error en agenda web confirmada", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    private function obtenerImagenBase64($idFoto, $liga)
    {
        try {
            if ($idFoto) {
                $path = $liga . $idFoto;
                $imageData = Storage::disk('ftp')->get($path);
                $foto_nombre = base64_encode($imageData);
                if ($foto_nombre === '') {
                    return null;
                } else {
                    return $foto_nombre;
                }
            } else {
                return null;
            }
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al obtener imagen", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
            return null;
        }
    }

    //LOGICA PARA LOS ATRASES

    public function retroceder()
    {
        try {
            if ($this->paso === 4) {

                $this->listaHorarios = [];
                $this->horaElegida = null;
                $this->especialistaElegido = null;
                $this->horarioElegido = null;
                $this->especialistaLiteral = null;
                $this->paso = 3;
                return;
            }

            if ($this->paso === 3) {
                $this->listaHorarios = [];
                $this->horariosDisponibles = [];
                $this->horaElegida = null;
                $this->especialistaElegido = null;
                $this->horarioElegido = null;
                $this->especialidadLiteral = null;
                $this->paso = 2;
                return;
            }

            if ($this->paso === 2) {

                $this->listaHorarios = [];
                $this->horariosDisponibles = [];
                $this->especialidadesDisponibles = [];
                $this->horaElegida = null;
                $this->especialistaElegido = null;
                $this->horarioElegido = null;
                $this->fechaElegida = null;
                $this->diaElegido = null;
                $this->especialidadLiteral = null;
                $this->especialistaLiteral = null;
                $this->pasoAEspecialidadesSwitch = false;
                $this->paso = 1;
                return;
            }
        } catch (\Throwable $th) {
            //dd($th);
        }
    }

    private function definirTipoAsegurado($tipoAsegurado)
    {
        $arraycitoTipoAsegurado = [
            'TITULAR' => 1,
            'BENEFICIARIO DEL INTERIOR' => 2,
            'TITULAR DEL INTERIOR' => 2,
            'TITULAR CONVENIO' => 2,
        ];

        if (isset($arraycitoTipoAsegurado[$tipoAsegurado])) {
            return $arraycitoTipoAsegurado[$tipoAsegurado];
        } elseif (Str::contains($tipoAsegurado, 'BENEFICIARIO')) {
            return 1;
        } else {
            return 3;
        }
    }

    public function existeGrupoFamiliarAgendado($fechaSeleccionada, $idPersonaTitular)
    {
        $this->obtenerPoblacionAseguradaCache();
        $persona = collect($this->poblacion_asegurada_cache)->where('id_persona_titular', $idPersonaTitular);
        $grupoFamiliar = $persona->pluck('id_persona')->all();

        if (empty($grupoFamiliar)) {
            return false;
        }

        $existe = Agenda::where('fecha_agenda', $fechaSeleccionada)
            ->whereIn('id_persona', $grupoFamiliar)
            ->where('ficha_extra', false)
            ->whereNot('anulacion_ficha', true)
            ->exists();

        return $existe;
    }

    public function existeGrupoFamiliarAgendadoId($fechaSeleccionada, $idPersonaTitular)
    {
        $this->obtenerPoblacionAseguradaCache();
        $persona = collect($this->poblacion_asegurada_cache)->where('id_persona_titular', $idPersonaTitular);
        $grupoFamiliar = $persona->pluck('id_persona')->all();

        if (empty($grupoFamiliar)) {
            return false;
        }

        $existe = Agenda::where('fecha_agenda', $fechaSeleccionada)
            ->whereIn('id_persona', $grupoFamiliar)
            ->where('ficha_extra', false)
            ->whereNot('anulacion_ficha', true)
            ->orderBy('id', 'desc')
            ->first();

        return $existe->id_persona;
    }

    public function agendaPropia($fechaSeleccionada, $idPersona)
    {
        $existe = Agenda::where('fecha_agenda', $fechaSeleccionada)
            ->where('id_persona', $idPersona)
            ->where('ficha_extra', false)
            ->whereNot('anulacion_ficha', true)
            ->exists();

        return $existe;
    }

    public function mostrarDatosAgenditaPropia($fechaSeleccionada, $idPersona)
    {
        $agendaExistente = Agenda::where('fecha_agenda', $fechaSeleccionada)
            ->where('id_persona', $idPersona)
            ->where('ficha_extra', false)
            ->whereNot('anulacion_ficha', true)
            ->with([
                'servicioPlataformaAgenda',
                'suspensionHorarioAgenda.motivoSuspensionHorario',
                'suspensionHorarioAgenda.usuarioCreador',
                'asignacionHorarioAgenda.consultorioAsignacionHorario.consultorioSedes.residenciaSedes.zonaResidencia',
                'asignacionHorarioAgenda.horarioAsignacionHorario',
                'asignacionHorarioAgenda.especialistaAsignacionHorario.personaEspecialista',
                'asignacionHorarioAgenda.especialistaAsignacionHorario.especialidadEspecialista',
                'asignacionHorarioAgenda.horarioAsignacionHorario'
            ])
            ->orderBy('id', 'desc')
            ->first();

        if ($agendaExistente) {
            //PREVIAMENTE CARGADOS
            $this->fotoEspecialistaAgendaExistente = $this->obtenerImagenBase64Doctores(
                $agendaExistente->asignacionHorarioAgenda->especialistaAsignacionHorario->id
            );
            $this->fotoAgendaExistente = $this->foto_nombre;
            $this->nombreCompletoAgendaExistente = $this->nombre_completo;
            $this->edadAgendaExistente = $this->edad;
            $this->matriculaAgendaExistente = $this->matricula;
            $this->tipoAseguradoAgendaExistente = $this->tipo_asegurado;
            //ESTOS LOS TENGO QUE SACAR PORQUE SI
            $this->idAgendaExistente = $agendaExistente->id;
            $this->especialidadAgendaExistente = $agendaExistente->asignacionHorarioAgenda->especialistaAsignacionHorario->especialidadEspecialista->especialidad;
            $this->especialistaAgendaExistente = implode(' ', array_filter([
                $agendaExistente->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->p_apellido,
                $agendaExistente->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->s_apellido,
                $agendaExistente->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->nombres
            ]));
            $this->fechaAgendaExistente = Carbon::parse($agendaExistente->fecha_agenda)->format('d/m/Y');
            $this->horaAgendaExistente = Carbon::parse($agendaExistente->hora_agenda)->format('H:i');
            $this->horaInicioAgendaExistente = Carbon::parse($agendaExistente->asignacionHorarioAgenda->horarioAsignacionHorario->hora_inicio)->format('H:i');
            $this->horaFinAgendaExistente = Carbon::parse($agendaExistente->asignacionHorarioAgenda->horarioAsignacionHorario->hora_fin)->format('H:i');
            $this->turnoAgendaExistente = $agendaExistente->asignacionHorarioAgenda->horarioAsignacionHorario->turno;
            $this->nroConsultorioAgendaExistente = $agendaExistente->asignacionHorarioAgenda->consultorioAsignacionHorario->numero_consultorio;
            $this->ubicacionAgendaExistente = $agendaExistente->asignacionHorarioAgenda->consultorioAsignacionHorario->consultorioSedes->ubicacion;
            $this->pisoAgendaExistente = $agendaExistente->asignacionHorarioAgenda->consultorioAsignacionHorario->consultorioSedes->piso;
            $this->direccionAgendaExistente = $agendaExistente->asignacionHorarioAgenda->consultorioAsignacionHorario->consultorioSedes->residenciaSedes->direccion;
            $this->nroViviendaAgendaExistente = $agendaExistente->asignacionHorarioAgenda->consultorioAsignacionHorario->consultorioSedes->residenciaSedes->nro_vivienda;
            $this->nombreZonaAgendaExistente = $agendaExistente->asignacionHorarioAgenda->consultorioAsignacionHorario->consultorioSedes->residenciaSedes->zonaResidencia->nombre;
            $this->servicioPlataformaAgendaExistente = $agendaExistente->servicioPlataformaAgenda->nombre_servicio;
            $this->fechaRegistroAgendaExistente = Carbon::parse($agendaExistente->created_at)->format('d/m/Y');
            if ($agendaExistente->id_suspension_horario != null) {
                $this->suspensionAgenda = true;
                $this->motivoSuspension = $agendaExistente->suspensionHorarioAgenda->motivoSuspensionHorario->nombre_motivo;
                $this->usuarioCreaSuspension = $agendaExistente->suspensionHorarioAgenda->usuarioCreador->name;
                $this->fechaSuspensionCreada = Carbon::parse($agendaExistente->suspensionHorarioAgenda->created_at)->format('d/m/Y H:i:s');
            }
        }
    }

    public function mostrarDatosAgenditaBeneficiario($fechaSeleccionada, $idPersona)
    {
        $agendaExistente = Agenda::where('fecha_agenda', $fechaSeleccionada)
            ->where('id_persona', $idPersona)
            ->where('ficha_extra', false)
            ->whereNot('anulacion_ficha', true)
            ->with([
                'servicioPlataformaAgenda',
                'suspensionHorarioAgenda.motivoSuspensionHorario',
                'suspensionHorarioAgenda.usuarioCreador',
                'asignacionHorarioAgenda.consultorioAsignacionHorario.consultorioSedes.residenciaSedes.zonaResidencia',
                'asignacionHorarioAgenda.horarioAsignacionHorario',
                'asignacionHorarioAgenda.especialistaAsignacionHorario.personaEspecialista',
                'asignacionHorarioAgenda.especialistaAsignacionHorario.especialidadEspecialista',
                'asignacionHorarioAgenda.horarioAsignacionHorario',
                'personaAgenda.tipoAseguradoPersona',
            ])
            ->orderBy('id', 'desc')
            ->first();

        //dd($agendaExistente);

        if ($agendaExistente) {
            //PREVIAMENTE CARGADOS
            $this->fotoEspecialistaAgendaExistenteBeneficiario = $this->obtenerImagenBase64Doctores(
                $agendaExistente->asignacionHorarioAgenda->especialistaAsignacionHorario->id
            );
            $this->fotoAgendaExistenteBeneficiario = $this->obtenerFotoOtraAgendaBeneficiario($idPersona);
            $nombreCompletoBeneficiario = implode(' ', array_filter([
                $agendaExistente->personaAgenda->nombres,
                $agendaExistente->personaAgenda->p_apellido,
                $agendaExistente->personaAgenda->s_apellido
            ]));
            $this->nombreCompletoAgendaExistenteBeneficiario = $nombreCompletoBeneficiario;
            $this->edadAgendaExistenteBeneficiario = Carbon::parse($agendaExistente->personaAgenda->fecha_nacimiento)->age;
            $this->matriculaAgendaExistenteBeneficiario = $agendaExistente->personaAgenda->matricula_seguro;
            $this->tipoAseguradoAgendaExistenteBeneficiario = $agendaExistente->personaAgenda->tipoAseguradoPersona->tipo_asegurado;
            //ESTOS LOS TENGO QUE SACAR PORQUE SI
            $this->idAgendaExistenteBeneficiario = $agendaExistente->id;
            $this->idAgendaExistente = $agendaExistente->id;
            $this->especialidadAgendaExistenteBeneficiario = $agendaExistente->asignacionHorarioAgenda->especialistaAsignacionHorario->especialidadEspecialista->especialidad;
            $this->especialistaAgendaExistenteBeneficiario = implode(' ', array_filter([
                $agendaExistente->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->p_apellido,
                $agendaExistente->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->s_apellido,
                $agendaExistente->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->nombres
            ]));
            $this->fechaAgendaExistenteBeneficiario = Carbon::parse($agendaExistente->fecha_agenda)->format('d/m/Y');
            $this->horaAgendaExistenteBeneficiario = Carbon::parse($agendaExistente->hora_agenda)->format('H:i');
            $this->horaInicioAgendaExistenteBeneficiario = Carbon::parse($agendaExistente->asignacionHorarioAgenda->horarioAsignacionHorario->hora_inicio)->format('H:i');
            $this->horaFinAgendaExistenteBeneficiario = Carbon::parse($agendaExistente->asignacionHorarioAgenda->horarioAsignacionHorario->hora_fin)->format('H:i');
            $this->turnoAgendaExistenteBeneficiario = $agendaExistente->asignacionHorarioAgenda->horarioAsignacionHorario->turno;
            $this->nroConsultorioAgendaExistenteBeneficiario = $agendaExistente->asignacionHorarioAgenda->consultorioAsignacionHorario->numero_consultorio;
            $this->ubicacionAgendaExistenteBeneficiario = $agendaExistente->asignacionHorarioAgenda->consultorioAsignacionHorario->consultorioSedes->ubicacion;
            $this->pisoAgendaExistenteBeneficiario = $agendaExistente->asignacionHorarioAgenda->consultorioAsignacionHorario->consultorioSedes->piso;
            $this->direccionAgendaExistenteBeneficiario = $agendaExistente->asignacionHorarioAgenda->consultorioAsignacionHorario->consultorioSedes->residenciaSedes->direccion;
            $this->nroViviendaAgendaExistenteBeneficiario = $agendaExistente->asignacionHorarioAgenda->consultorioAsignacionHorario->consultorioSedes->residenciaSedes->nro_vivienda;
            $this->nombreZonaAgendaExistenteBeneficiario = $agendaExistente->asignacionHorarioAgenda->consultorioAsignacionHorario->consultorioSedes->residenciaSedes->zonaResidencia->nombre;
            $this->servicioPlataformaAgendaExistenteBeneficiario = $agendaExistente->servicioPlataformaAgenda->nombre_servicio;
            $this->fechaRegistroAgendaExistenteBeneficiario = Carbon::parse($agendaExistente->created_at)->format('d/m/Y');
            if ($agendaExistente->id_suspension_horario != null) {
                $this->suspensionAgendaBeneficiario = true;
                $this->motivoSuspensionBeneficiario = $agendaExistente->suspensionHorarioAgenda->motivoSuspensionHorario->nombre_motivo;
                $this->usuarioCreaSuspensionBeneficiario = $agendaExistente->suspensionHorarioAgenda->usuarioCreador->name;
                $this->fechaSuspensionCreadaBeneficiario = Carbon::parse($agendaExistente->suspensionHorarioAgenda->created_at)->format('d/m/Y H:i:s');
            }
        }
    }

    private function obtenerImagenBase64Doctores($idFoto)
    {
        try {
            if ($idFoto) {
                $path = 'Plataforma/Especialista/Fotos/' . $idFoto . '.jpg';
                $imageData = Storage::disk('ftp')->get($path);
                $foto_medico = base64_encode($imageData);
                return $foto_medico;
            } else {
                return null;
            }
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al obtener imagen doctores", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
            return null;
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

    #[On('anularAgendaExistente')]
    public function anularAgendaExistente(Request $request, string $motivo)
    {

        $anulacionSighov1 = new ReplicarAgendaS1();
        $anulacionAgendaService = new AnulacionAgendaService();
        try {

            DB::beginTransaction();
            $anularAgenda = Agenda::find($this->idAgendaExistente);
            if ($anularAgenda) {
                $anularAgenda->fecha_anulacion = Carbon::now();
                $anularAgenda->motivo_anulacion = $motivo;
                $anularAgenda->anulacion_ficha = true;
                $anularAgenda->ip = $request->ip();
                $anularAgenda->nombre_equipo = gethostname();
                $anularAgenda->clave_unica = $anularAgenda->clave_unica . '-A-' . Carbon::now()->format('H:i:s');
                $anularAgenda->id_usuario_anula = auth()->user()->id;
                $anularAgenda->id_user_updated = auth()->user()->id;
                $anularAgenda->save();
                // PARA ACTUALIZAR LA APK EN TIEMPO REAL
                // $socketService->emision_anulacion_socket($anularAgenda);
                // PARA GUARDAR LA ANULACION+
                // dd($anularAgenda);
                $mesajeAnular = $anulacionAgendaService->bloquear_anular_agenda($anularAgenda);
                // dd($mesajeAnular['mensaje']);
                if ($mesajeAnular['estado_anulacion']) {
                    $this->dispatch('limiteAnulaciones', detail: $mesajeAnular['mensaje']);
                    DB::rollBack();
                    // return;
                } else {
                    $this->dispatch('anulacionExitosa', detail: $mesajeAnular['mensaje']);
                    // return;
                    DB::commit();
                }

                $this->obtenerPoblacionAseguradaCache();
                if (count(collect($this->poblacion_asegurada_cache)->where('id_persona_titular', auth()->user()->id_persona)) > 1) {

                    $this->switchTieneGrupoFamiliar = true;
                }
                //PARA ANULAR LA AGENDA SIGHO V1
                //ACTUALIZACION: YA NO SE ANULA DIRECTAMENTE DESDE EL WEB MAIN
                //ACTUALIZACION 2: SE VOLVIOI A PONER PARA EVITAR QUE NO SE ANULEN FICHAS QUE SE HICIERON EN OTRO PERIODO
                if ($anularAgenda->replicado) {
                    $anulacionSighov1->enviarDatosAnulacionAgenda($anularAgenda->id);
                }
            } else {
                //dd('se entro aqui');
            }
            // $this->dispatch('anulacionExitosa');
        } catch (\Throwable $e) {
            DB::rollBack();
            dd([
                'usuario_id' => auth()->user()->id ?? null,
                'error'   => $e->getMessage() ?? '',
                'archivo'    => $e->getFile() ?? '',
                'linea'    => $e->getLine() ?? '',
                'fecha_hora'   => now(),
            ]);
            $this->dispatch('errorRegistroAnulacion');
            Log::error("Error al anular agenda existente", [
                'usuario_id' => auth()->user()->id ?? null,
                'error'   => $e->getMessage() ?? '',
                'archivo'    => $e->getFile() ?? '',
                'linea'    => $e->getLine() ?? '',
                'fecha_hora'   => now(),
            ]);
        }
    }

    public function anularAgendaAnterior()
    {
        $this->dispatch('preguntarAnularAgenda');
    }

    public function demandaInsatisfecha($idEspecialidad, $idEspecialista, $idAsignacionHorario)
    {
        $idPersona = $this->switchSeleccionoOtroFamiliar ? $this->idPersonaGrupoFamiliar : auth()->user()->id_persona;
        $this->idEspecialidadDemanda = $idEspecialidad;
        $this->idEspecialistaDemanda = $idEspecialista;
        $this->idAsignacionHorarioDemanda = $idAsignacionHorario;
        $existeDemandaRegistrada = DemandaInsatisfecha::where('id_persona', $idPersona)
            ->first();
        if ($existeDemandaRegistrada) {
            $esHoy = Carbon::parse($existeDemandaRegistrada->fecha_demanda_insatisfecha)->isSameDay(Carbon::today());
            if ($esHoy) {
                //dd('entrar a demanda');
                $this->idEspecialidadDemanda = null;
                $this->idEspecialistaDemanda = null;
                $this->idAsignacionHorarioDemanda = null;
                $this->dispatch('yaRegistroDemandaHoy');
                return;
            }
        }

        $this->dispatch('confirmarDemandaInsatisfecha');
        //dd($idPersona, $idEspecialidad, $idEspecialista);
    }

    #[On('registrarDemandaInsatisfecha')]
    public function registrarDemandaInsatisfecha(Request $request)
    {
        try {
            $idPersona = $this->switchSeleccionoOtroFamiliar ? $this->idPersonaGrupoFamiliar : auth()->user()->id_persona;
            $nuevaDemandaInsatisfecha = DemandaInsatisfecha::create([
                'fecha_demanda_insatisfecha' => Carbon::now(),
                'id_persona' => $idPersona,
                'id_especialista' => $this->idEspecialistaDemanda,
                'id_especialidad' => $this->idEspecialidadDemanda,
                'id_asignacion_horario' => $this->idAsignacionHorarioDemanda,
                'ip_equipo' => $request->ip(),
                'nombre_equipo' => gethostname(),
                'id_user_created' => auth()->user()->id
            ]);

            $this->idEspecialidadDemanda = null;
            $this->idEspecialistaDemanda = null;
            $this->idAsignacionHorarioDemanda = null;

            if ($nuevaDemandaInsatisfecha) {
                $this->dispatch('demandaRegistradaCorrectamente');
            } else {
                $this->dispatch('demandaNoRegistrada');
            }
        } catch (\Throwable $e) {
            //throw $th;
            $this->idEspecialidadDemanda = null;
            $this->idEspecialistaDemanda = null;
            $this->idAsignacionHorarioDemanda = null;
            $this->dispatch('demandaNoRegistrada');

            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al registrar Demanda Insatisfecha", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    public function actualizarHorarios()
    {

        $this->listaHorarios = $this->listaHorasAgendaWeb(
            $this->horariosElegibles['hora_inicio'],
            $this->horariosElegibles['hora_fin'],
            $this->horariosElegibles['intervalo'],
            $this->fechaElegida,
            $this->horariosElegibles['id_asignacion_horario']
        );
    }

    #[On('volverInicio')]
    public function volverInicio()
    {
        return redirect()->route('dashboard');
    }

    public function mostrarAgendaConfirmada($fechaElegida, $idUser)
    {
        $this->mostrarDatosAgenditaPropia($fechaElegida, $idUser);
        $this->dispatch('mostrarAgendaRegistrada', [
            'fecha' => $this->fechaAgendaExistente,
            'hora' => $this->horaAgendaExistente,
            'especialista' => $this->especialistaAgendaExistente,
            'consultorio' => $this->nroConsultorioAgendaExistente,
            'ubicacion' => $this->ubicacionAgendaExistente,
        ]);
        return;
    }
























    /*public function list()
    {

        $especialidad = Especialidad::where('especialidad', 'like', '%' . strtoupper($this->search) . '%')
            ->orWhere('sigla', 'like', '%' . strtoupper($this->search) . '%')
            ->orderBy('id', 'desc')
            ->paginate(5);
        return $especialidad;
    }

    public $nombre_completo, $matricula, $fecha_nacimiento,
        $fot, $institucion, $estado_vigencia, $tipo_asegurado, $cod_institucional, $nombre_titular;

    public function mostrarAsegurado()
    {
        $usuario = User::find(auth()->user()->id);
        $id_persona = $usuario->id_persona;
        $nuevaPersona = collect($this->poblacion_asegurada_cache)->firstWhere('id_persona', $id_persona);

        //  dd($nuevaPersona);
        //$nuevaPersona = Persona::find($id_persona);
        $this->nombre_completo = $nuevaPersona['nombres'] . ' ' . $nuevaPersona['p_apellido'] . ' ' . $nuevaPersona['s_apellido'];
        $this->matricula = $nuevaPersona['matricula_seguro'];
        $this->fecha_nacimiento = $this->calcularEdad($nuevaPersona['fecha_nacimiento']);

        // $tipoAsegurado = TipoAsegurado::find($nuevaPersona->id_tipo_asegurado);
        $this->tipo_asegurado = $nuevaPersona['tipo_asegurado'];

        $esTitular = Titular::where('id_persona', $id_persona)->where('estado', true)->first();

        $esBeneficiario = Beneficiario::where('id_persona', $id_persona)->where('estado', true)->first();

        $esConvenio = Convenio::where('id_persona', $id_persona)->where('estado', true)->first();

        $esAutorizacionInterior = AutorizacionInterior::where('id_persona_interior', $id_persona)->where('estado', true)->first();

        if ($esTitular) {
            $this->estado_vigencia = $esTitular->estado_vigencia;

            $this->foto = $this->obtenerImagenBase64($esTitular->foto_nombre);


            $titularInstitucion = TitularInstitucion::where('id_titular', $esTitular->id)
                ->where('estado', true)
                ->first();
            if ($titularInstitucion) {
                $inst = Institucion::find($titularInstitucion->id_institucion);
                $this->institucion = $inst->nombre;
                $id_institucion = $inst->id;

                $codInst = CodigoInstitucionSsu::where('id_institucion', $id_institucion)->first();
                $this->cod_institucional = $codInst->codigo . '-' . $codInst->tipo_titular;
            } else {
                $this->institucion = 'APORTANTE VOLUNTARIO';
            }
        } elseif ($esBeneficiario) {

            $this->foto = $this->obtenerImagenBase64($esBeneficiario->foto_nombre);
            $this->estado_vigencia = $esBeneficiario->vigencia;

            $titularBen = Titular::find($esBeneficiario->id_titular);
            $titularInstBen = TitularInstitucion::where('id_titular', $titularBen->id)
                ->where('estado', true)
                ->first();
            if ($titularInstBen) {
                $inst = Institucion::find($titularInstBen->id_institucion);
                $this->institucion = $inst->nombre;
                $id_institucion = $inst->id;

                $codInst = CodigoInstitucionSsu::where('id_institucion', $id_institucion)->first();
                $this->cod_institucional = $codInst->codigo . '-' . $codInst->tipo_beneficiario;
            } else {
                $this->institucion = 'APORTANTE VOLUNTARIO';
            }
        } elseif ($esConvenio) {

            $this->foto = $this->obtenerImagenBase64Convenio($esConvenio->foto_nombre);

            $titularRep = Titular::find($esConvenio->id_titular_autoriza);
            $id_titular_persona = $titularRep->id_persona;

            $titularPersona = Persona::find($id_titular_persona);
            $this->nombre_titular = $titularPersona->nombres . ' ' . $titularPersona->p_apellido . ' ' . $titularPersona->s_apellido;
        } elseif ($esAutorizacionInterior) {

            $this->foto = $this->obtenerImagenBase64Autorizacion($esAutorizacionInterior->foto_nombre);

            $titularPersona = Persona::find($esAutorizacionInterior->id_persona_titular);
            $this->nombre_titular = $titularPersona->nombres . ' ' . $titularPersona->p_apellido . ' ' . $titularPersona->s_apellido;
        } else {
            $esEstudiante = Estudiante::where('id_persona', $id_persona)->where('estado', true)->first();
            $this->foto = $this->obtenerImagenBase64Estudiante($esEstudiante->foto_nombre);
            $this->estado_vigencia = $esEstudiante->estado_validar;
            $this->institucion = 'UNIVERSIDAD MAYOR DE SAN ANDRÉS';
        }
    }
    private function obtenerImagenBase64($idFoto)
    {
        try {
            if ($idFoto) {
                $path = 'Afiliacion/FOTOS/' . $idFoto;

                $imageData = Storage::disk('ftp')->get($path);
                //dd($imageData);
                $foto = base64_encode($imageData);
                return $foto;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }

    private function obtenerImagenBase64Estudiante($idFoto)
    {
        try {
            if ($idFoto) {
                $path = 'Afiliacion/SSUE/FOTOS/' . $idFoto;

                $imageData = Storage::disk('ftp')->get($path);

                $foto = base64_encode($imageData);
                return $foto;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }

    private function obtenerImagenBase64Convenio($idFoto)
    {
        try {
            if ($idFoto) {
                $path = 'Vigencia/FOTOS/CONVENIO/' . $idFoto;

                $imageData = Storage::disk('ftp')->get($path);

                $foto = base64_encode($imageData);
                return $foto;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }
    private function obtenerImagenBase64Autorizacion($idFoto)
    {
        try {
            if ($idFoto) {
                $path = 'Vigencia/FOTOS/CONVENIO/' . $idFoto;

                $imageData = Storage::disk('ftp')->get($path);

                $foto = base64_encode($imageData);
                return $foto;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }

    private function obtenerImagenBase64Doctores($idFoto)
    {
        try {
            if ($idFoto) {
                $path = 'Plataforma/Especialista/Fotos/' . $idFoto . '.jpg';

                $imageData = Storage::disk('ftp')->get($path);

                $foto_medico = base64_encode($imageData);
                return $foto_medico;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function calcularEdad($dato)
    {
        $fechaNacimiento = Carbon::parse($dato);
        $edad = $fechaNacimiento->age;
        return $edad;
    }
    public $id_agenda_select;
    public $id_agenda_select_clave_unica;
    public function select_date($fecha)
    {
        $this->fecha_select = Carbon::parse($fecha);
        $agendado = $this->VergificarAgendaGrupoFamiliar($this->fecha_select);

        // dd("fecha select", $fecha);
        // dd($agendado);
        if ($agendado === 0) {
            // dd("no hay agenda");
            $this->fecha_text = Carbon::parse($fecha)->translatedFormat('j \\de F \\de Y');
            $this->paso = 2;
        } else {
            // dd("hay agenda");
            $this->id_agenda_select = $agendado->id;
            $this->id_agenda_select_clave_unica = $agendado->clave_unica;
            $datos_agenda = Agenda::where('id', $agendado->id)->first();

            $datosagendaService = new DatosAgenda;
            $detalle_agenda_servicio = $datosagendaService->extraerDatosAgenda($datos_agenda->id);
            $this->detalles_agenda_servicio = $detalle_agenda_servicio;
            // dd($this->detalles_agenda_servicio);
            $this->hay_agenda = 1;
            $this->dispatch('AgendaExistente');
        }
    }

    public function VergificarAgendaGrupoFamiliar($fecha)
    {
        $grupoFamiliar = new GrupoFamiliarPersona();
        $grupoFam = $grupoFamiliar->encontrarPersona(auth()->user()->id_persona);
        // dd($grupoFam);
        $valor = "";
        $personaAgendada = 0;
        foreach ($grupoFam as $grupo) {
            $agendaVerificar = Agenda::where('id_persona', $grupo['id'])->where('fecha_agenda', $fecha)->first();
            if ($agendaVerificar) {
                $personaAgendada = $agendaVerificar;
            }
        }
        return $personaAgendada;
    }

    public function funcion_especialidad($idEsp)
    {
        $this->id_especialidad_select = $idEsp;
        $this->paso = 3;
        $especialidad = Especialidad::find($idEsp);
        $this->especialidad_text = $especialidad->especialidad;

        $this->lista_especialistas = AsignacionHorario::with(['horarioAsignacionHorario', 'especialistaAsignacionHorario.especialidadEspecialista', 'especialistaAsignacionHorario.personaEspecialista'])
            ->whereHas('especialistaAsignacionHorario.especialidadEspecialista', function ($query) use ($idEsp) {
                $query->where('id', $idEsp)->where('estado', true);
            })
            ->whereHas('horarioAsignacionHorario', function ($query) use ($idEsp) {
                $query->where('id_dia', Carbon::parse($this->fecha_select)->dayOfWeek)->where('estado', true);
            })
            ->get()
            ->map(function ($especialista) {
                $foto_es = $this->obtenerImagenBase64Doctores($especialista->id_especialista);
                $especialista->foto_base = $foto_es;
                $horarios_obtenidos = $this->nro_disp_susp($especialista->id, $this->fecha_select);
                $especialista->nro_suspendidos = $horarios_obtenidos['suspendidos'];
                $especialista->nro_ocupados = $horarios_obtenidos['ocupados'];
                $especialista->nro_libres = $horarios_obtenidos['libres'];
                return $especialista;
            });
        //dd($this->lista_especialistas);

        //         $this->lista_especialistas = Especialista::with('personaEspecialista')
        //         ->where('id_especialidad', $idEsp)
        //         ->where('estado', true)
        //         ->get()
        //         ->map(function ($especialista) {
        //             $foto_es=$this->obtenerImagenBase64Doctores($especialista->id);
        //             $especialista->foto_base = $foto_es;
        //             return $especialista;
        //         });

        //    dd($this->lista_especialistas);
    }

    public function Selectdoctor($dato)
    {
        
        $this->doctor_select = $dato['especialista_asignacion_horario']['persona_especialista']['p_apellido'] . ' ' . $dato['especialista_asignacion_horario']['persona_especialista']['s_apellido'] . ' ' . $dato['especialista_asignacion_horario']['persona_especialista']['nombres'];
        $this->id_horario_especialista = $dato['id'];
        $this->horario_lista = $this->listaHoras($dato['horario_asignacion_horario']['hora_inicio'], $dato['horario_asignacion_horario']['hora_fin'], $dato['horario_asignacion_horario']['intervalo'], $dato['id']);
        // dd($this->horario_lista);
    }
    public function listaHoras($horaInicio, $horaFin, $inter, $id_asignacion_horario)
    {
        $horas = [];
        $inicio = Carbon::createFromFormat('H:i:s', $horaInicio);
        $fin = Carbon::createFromFormat('H:i:s', $horaFin);
        $count = 0;
        while ($inicio->lt($fin)) {
            $horas[$count]['hora'] = $inicio->format('H:i');
            $estadoHora = Agenda::with('suspensionHorarioAgenda', 'servicioPlataformaAgenda')
                ->where('fecha_agenda', $this->fecha_select)
                ->where('hora_agenda', $inicio->format('H:i'))
                ->where('id_asignacion_horario', $id_asignacion_horario)
                ->first();
            //dd($estadoHora->servicioPlataformaAgenda->nombre_servicio);
            //dd($estadoHora,$this->fecha,$inicio->format('H:i'),$this->id_asignacion_especialista);
            if ($estadoHora) {
                if ($estadoHora->id_suspension_horario == true) {
                    $horas[$count]['estado'] = 'SUSPENDIDO';
                    $horas[$count]['observacion'] = $estadoHora->servicioPlataformaAgenda->nombre_servicio;
                } else {
                    $horas[$count]['estado'] = 'OCUPADO';
                    $horas[$count]['observacion'] = $estadoHora->servicioPlataformaAgenda->nombre_servicio;
                }
            } else {
                $horas[$count]['estado'] = 'LIBRE';
                $horas[$count]['observacion'] = '';
            }
            $count++;
            $inicio->addMinutes($inter);
        }

        return $horas;
    }

    public function nro_disp_susp($id_asignacion_horario, $fecha)
    {
        $hora = AsignacionHorario::with('horarioAsignacionHorario')
            ->where('id', $id_asignacion_horario)
            ->first();

        $inicio = Carbon::createFromFormat('H:i:s', $hora->horarioAsignacionHorario->hora_inicio);
        $fin = Carbon::createFromFormat('H:i:s', $hora->horarioAsignacionHorario->hora_fin);
        $count = 0;
        $nro_ocupados = 0;
        $nro_ocupados_suspendidos = 0;
        $nro_libres = 0;
        $horario_estado = [];
        //  dd($hora,$nro_ocupados,$hora->horarioAsignacionHorario->numero_paciente,$fecha, $inicio->format('H:i'), $id_asignacion_horario);
        for ($i = 0; $i < $hora->horarioAsignacionHorario->numero_paciente; $i++) {
            $estadoHora = Agenda::with('suspensionHorarioAgenda')
                ->where('fecha_agenda', $fecha)
                ->where('hora_agenda', $inicio->format('H:i'))
                ->where('id_asignacion_horario', $id_asignacion_horario)
                ->whereNot('anulacion_ficha', true)
                ->first();
            if ($estadoHora) {
                if ($estadoHora->id_suspension_horario == true) {
                    $nro_ocupados_suspendidos = $nro_ocupados_suspendidos + 1;
                }
                $nro_ocupados = $nro_ocupados + 1;
            } else {
                $nro_libres = $nro_libres + 1;
            }
            $count++;
            $inicio->addMinutes($hora->horarioAsignacionHorario->intervalo);
        }
        $horario_estado['suspendidos'] = $nro_ocupados_suspendidos;
        $horario_estado['ocupados'] = $nro_ocupados;
        $horario_estado['libres'] = $nro_libres;


        return $horario_estado;
    }

    //FUNCIONES PARA APIAdmision
    public function obtenerCIMedico($id_asignacion_horario)
    {
        // Realizar la consulta directamente para obtener el CI del médico
        $ciMedico = DB::table('plataforma.asignacion_horario as ah')
            ->join('plataforma.especialista as e', 'ah.id_especialista', '=', 'e.id')
            ->join('administracion.persona as p', 'e.id_persona', '=', 'p.id')
            ->where('ah.id', $id_asignacion_horario)
            ->value('p.ci'); // Obtener solo el campo 'ci' de la tabla persona

        if (!$ciMedico) {
            return response()->json(['error' => 'CI del médico no encontrado'], 404);
        }

        return $ciMedico;
    }

    public function obtenerCIPaciente($id_persona)
    {
        // Realizar la consulta directamente para obtener el CI del paciente
        $ciPaciente = DB::table('administracion.persona')
            ->where('id', $id_persona)
            ->value('ci'); // Obtener solo el campo 'ci' de la tabla persona

        if (!$ciPaciente) {
            return response()->json(['error' => 'CI del paciente no encontrado'], 404);
        }

        return $ciPaciente;
    }

    public function obtenerEspecialidadMedico($id_asignacion_horario)
    {
        // Realizar la consulta directamente para obtener la especialidad del médico
        $especialidad = DB::table('plataforma.asignacion_horario as ah')
            ->join('plataforma.especialista as e', 'ah.id_especialista', '=', 'e.id')
            ->join('administracion.especialidad as es', 'e.id_especialidad', '=', 'es.id')
            ->where('ah.id', $id_asignacion_horario)
            ->value('es.especialidad'); // Obtener solo el campo 'especialidad' de la tabla especialidad

        if (!$especialidad) {
            return response()->json(['error' => 'Especialidad no encontrada'], 404);
        }

        return $especialidad;
    }

    public function obtenerNombreDia($id_asignacion_horario)
    {
        // Realizar la consulta directamente para obtener el nombre del día
        $nombreDia = DB::table('plataforma.asignacion_horario as ah')
            ->join('plataforma.dia as d', 'ah.id_dia', '=', 'd.id')
            ->where('ah.id', $id_asignacion_horario)
            ->value('d.nombre'); // Obtener solo el campo 'nombre' de la tabla dia

        if (!$nombreDia) {
            return response()->json(['error' => 'Día no encontrado'], 404);
        }

        return $nombreDia;
    }

    ///fin Funciones para APIAdmision

    public function GuardarAgenda($hora)
    {
        $grupoFamiliar = new GrupoFamiliarPersona();
        $grupoFam = $grupoFamiliar->encontrarPersona(auth()->user()->id_persona);

        $datosPersonaAfiliadaServicio = new ListadoAsegurados();
        $datosPersonaAfiliada = $datosPersonaAfiliadaServicio->busquedaAfiliadoPorId(auth()->user()->id_persona);
        $datosPersonaDetallesServicio = new ListadoAsegurados();
        $datosPersonaDetalles = $datosPersonaDetallesServicio->busquedaAfiliadoPorIdKiosko(auth()->user()->id_persona);
        // dd($grupoFam,$datosPersonaAfiliada,$datosPersonaDetalles);

        $horaC = Carbon::parse($hora)->format('H');
        $minutosC = Carbon::parse($hora)->format('i');
        $diaC = Carbon::parse($this->fecha_select)->format('d');
        $mesC = Carbon::parse($this->fecha_select)->format('m');
        $gestionC = Carbon::parse($this->fecha_select)->format('Y');
        $clave_unica = $this->id_horario_especialista . $horaC . $minutosC . $diaC . $mesC . $gestionC;

        $existeAgenda = Agenda::where('clave_unica', $clave_unica)
            ->first();

        if ($existeAgenda) {
            $this->dispatch('agendaYaExiste');
        } else {

            $dataAgenda = [
                'fecha_agenda' => $this->fecha_select,
                'hora_agenda' => $hora,
                'confirmado' => true,
                'id_servicio_plataforma' => 2,
                'clave_unica' => $clave_unica,
                'id_persona' => auth()->user()->id_persona,
                'id_asignacion_horario' => $this->id_horario_especialista,
                'anulacion_ficha' => false,
                'ficha_extra' => false,
                'id_user_created' => auth()->user()->id,
            ];
            $dataAPIAdmision = [
                'ci_especialista' => $this->obtenerCIMedico($dataAgenda['id_asignacion_horario']),  // Llama usando $this->
                'ci_paciente' => $this->obtenerCIPaciente($dataAgenda['id_persona']),
                'especialidad' => $this->obtenerEspecialidadMedico($dataAgenda['id_asignacion_horario']),
                'id_Dia' => $this->obtenerNombreDia($dataAgenda['id_asignacion_horario']),
                'Fecha_Programada' => Carbon::now()->format('Y-m-d'),
                'Hora_Programada' => $dataAgenda['hora_agenda'],
                'nom_user' => "SIGHOV2",
                'ip' => request()->ip(),
                'Fecha' => Carbon::now()->format('Y-m-d'),
                'id_Tipo_Ficha' => 3
            ];
            // Crear el registro usando la variable $dataAgenda
            //dd($dataAPIAdmision);
            //$urlAPIAdmision = LinkServicio::where('nombre_servicio', 'AGENDAMIENTO GEHMA2')->first();
            //dd($urlAPIAdmision);
            //$url_APIAdmision = $urlAPIAdmision->link_servicio;
            //dd($url_APIAdmision);
            //$respuesta = Http::post($url_APIAdmision, $dataAPIAdmision)->json();
            //dd($respuesta);
            //agendar despeus de recibir 'success'
            $nuevaAgenda = Agenda::create($dataAgenda);

            $this->dispatch('RegistroExistoso');
            $this->redirectRoute('plataforma.agenda-web.index');
            // dd($this->fecha_select,$this->id_especialidad_select,$this->id_horario_especialista,auth()->user()->id_persona);  
        }
    }
    #On[AnularFichaAgenda]
    // #[On('anularFichaAgenda_alert')]
    // #[On('anularFicha_alert')]
    public function anularFicha_alert()
    {
        $this->dispatch('anularFicha_alert');
    }
    #[On('anularFichaAgenda_process')]
    public function anularFichaAgenda_process($motivo_anulacion)
    {
        $hora_actual = Carbon::now()->format('H:i:s');
        $nueva_clave_unica = $this->id_agenda_select_clave_unica . '-A-' . $hora_actual;
        // dd("llegamos bro", $motivo_anulacion, $nueva_clave_unica,$hora_actual);
        $agenda = Agenda::where('id', $this->id_agenda_select)
            ->update([
                'anulacion_ficha' => true,
                'fecha_anulacion' => Carbon::now()->format('Y-m-d'),
                'id_user_updated' => auth()->user()->id,
                'motivo_anulacion' => $motivo_anulacion,
                'clave_unica' => $nueva_clave_unica
            ]);
        $this->dispatch('AnulacionExistoso');
    }
    public function cancel()
    {
        return redirect()->route('dashboard');
    }

    public function cancelar()
    {
        return redirect()->route('dashboard');
    }

    public function volverPasoUno()
    {
        $this->paso = 1;
    }

    public function volverPasoDos()
    {
        $this->paso = 2;
    }

    public function volverPasoTres()
    {
        $this->paso = 3;
    }*/
}
