<?php

namespace App\Livewire\Plataforma\Agendamiento\Plataforma;

use Livewire\Component;

use App\Models\Administracion\Persona;
use App\Models\Administracion\TipoAsegurado;
use App\Models\Administracion\Especialidad;
use App\Models\Administracion\Servicio;

use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\TitularInstitucion;

use App\Models\Aportes\Institucion;
use App\Models\Plataforma\AdministracionFeriados;
use App\Models\Plataforma\Especialista;
use App\Models\Plataforma\AsignacionHorario;
use App\Models\Plataforma\Dia;
use App\Models\Plataforma\Horario;
use App\Models\Plataforma\Consultorio;
use App\Models\Plataforma\Agenda;
use App\Models\Plataforma\DiasHabilitadosAgenda;
use App\Models\Plataforma\Motivo;

use App\Models\Vigencia\AutorizacionInterior;
use App\Models\Vigencia\AutorizacionIntEspecialidad;
use App\Models\Vigencia\AutorizacionIntServicio;
use App\Models\Vigencia\Convenio;
use App\Models\Vigencia\ConvenioEspecialidad;
use App\Models\Vigencia\ConvenioServicio;
use App\Models\Vigencia\SeguroInterior;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Carbon\Carbon;
use Illuminate\Support\Str;

use App\Services\ListadoAsegurados;
use App\Services\GrupoFamiliarPersona;
use App\Services\ReplicarAgendaS1;
use Illuminate\Http\Request;
use Livewire\Attributes\On;

use App\Services\VencimientoCarnet;
use Illuminate\Support\Facades\Log;

class AgendaNuevo extends Component
{
    public $idAsegurado, $id_especialidad, $id_especialista, $fechaAgenda;

    #[Locked]
    public $matricula,
        $ci,
        $complemento,
        $carnet,
        $nombreCompleto,
        $nombres,
        $p_apellido,
        $s_apellido,
        $tipo_asegurado,
        $institucion,
        $foto_nombre,
        $estado_vigencia,
        $nombre_seguro,
        $nombreTitularAutoriza,
        $sexo,
        $edad,
        $codigo_institucional,
        // Para las especialidades
        $especialidad,
        // Para el especialista
        $nombres_e,
        $p_apellido_e,
        $s_apellido_e,
        $nombre_completo_e,
        $matricula_seguro_e,
        $foto_e,
        $especialidad_e,
        //Para el horario
        $asignacion_horario;

    public $horasDisponibles = [];
    public $variosTurnos;
    public $turnos;
    public $turnoSolo;
    public $turnoSeleccionado = null;
    public $asignacionHora;
    public $idAsignacionHorario;
    public $horaSelect;
    public $datosImpresion = [];
    public $mostrarBoton = false;
    public $busquedaCarnet, $busquedaComplemento, $busquedaMatricula;
    public $horaActual;
    public $intervaloActual;

    public $identificadorTipoAsegurado;
    public $edadGeriatria;
    public $edadPediatria;

    public $verConsultorio;
    public $verUbicacion, $verPiso;
    public $verDireccion;
    public $lista_especialistas;
    public $especialidades;

    public $limitacionesConvenio;

    //PRA LA LIMITACION DE FECHAS CARNALITOOO
    public $fechaLimiteInferior;
    public $fechaLimiteSuperior;

    public function render()
    {
        return view('livewire.Plataforma.agendamiento.plataforma.agenda-nuevo');
    }

    public function mount()
    {
        $this->edadGeriatria = 49;
        $this->edadPediatria = 18;
        $this->fechaAgenda = Carbon::now()->addDays(3)->format('Y-m-d');
        $this->calculoFechasLimite();
        $this->lista_especialistas = collect();
        $this->especialidades = collect();
    }

    #[On('personaSeleccionada')]
    public function personaSeleccionada($data)
    {
        $this->idAsegurado = $data;

        //$this->calculoFechasLimite();
        $this->mostrarDatosPersona($this->idAsegurado);
        $this->mostrarEspecialidades();

        /*if ($this->id_especialidad !== null) {
            $this->mostrarEspecialistas();
        }*/

        $this->dispatch('cerrarModalAsegurados');
    }

    public function mostrarDatosPersona($idPersona)
    {

        //try {
        $datosP = new ListadoAsegurados();
        $datosPersona = $datosP->busquedaAfiliadoPorIdKiosko($idPersona);


        $this->ci = $datosPersona['ci'];
        $this->complemento = $datosPersona['complemento'];
        if ($this->complemento === null) {
            $this->carnet = $this->ci;
        } else {
            $this->carnet = $this->ci . '-' . $this->complemento;
        }
        $this->nombreCompleto = $datosPersona['p_apellido'] . ' ' . $datosPersona['s_apellido'] . ' ' . $datosPersona['nombres'];
        $this->nombres = $datosPersona['nombres'];
        $this->p_apellido = $datosPersona['p_apellido'];
        $this->s_apellido = $datosPersona['s_apellido'];
        $this->sexo = $datosPersona['sexo'];
        $this->edad = Carbon::parse($datosPersona['fecha_nacimiento'])->age;
        $this->tipo_asegurado = $datosPersona['tipo_asegurado'];
        $this->institucion = $datosPersona['nombre_institucion'];
        $this->foto_nombre = $datosPersona['foto'];
        $this->estado_vigencia = $datosPersona['estado_vigencia'];
        $this->codigo_institucional = $datosPersona['codigo_institucional'];
        $this->matricula = $datosPersona['matricula_seguro'];
        /*if ($this->codigo_institucional === null) {
            $this->matricula = $datosPersona['matricula_seguro'];
        } else {
            $this->matricula = $datosPersona['matricula_seguro'] . ' - ' . $datosPersona['codigo_institucional'];
        }*/

        //PARA DEFINIR SI ES AFILIADO, CONVENIO O ESTUDIANTE
        $arraycitoTipoAsegurado = [
            'TITULAR' => 1,
            'PREAFILIADO TITULAR' => 1,
            'PREAFILIADO BENEFICIARIO' => 1,
            'BENEFICIARIO DEL INTERIOR' => 2,
            'TITULAR DEL INTERIOR' => 2,
            'TITULAR CONVENIO' => 2,
        ];

        if (isset($arraycitoTipoAsegurado[$datosPersona['tipo_asegurado']])) {
            $interior = $datosPersona['tipo_asegurado'] !== 'TITULAR CONVENIO' ? true : false;
            $this->limitacionesConvenio = $this->buscarConvenioPersona($idPersona, $interior);
            //dd($this->limitacionesConvenio, $idPersona, $datosPersona['ci']);
            $this->identificadorTipoAsegurado = $arraycitoTipoAsegurado[$datosPersona['tipo_asegurado']];
        } elseif (Str::contains($datosPersona['tipo_asegurado'], 'BENEFICIARIO')) {
            $this->limitacionesConvenio = null;
            $this->identificadorTipoAsegurado = 1;
        } else {
            $this->limitacionesConvenio = null;
            $this->identificadorTipoAsegurado = 3;
        }
        /*} catch (\Throwable $e) {

            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al mostrar datos persona", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }*/
    }

    public function buscarConvenioPersona($idPersona, $interior)
    {

        if ($interior) {
            $convenios = AutorizacionInterior::where('id_persona_interior', $idPersona)
                ->where('estado', true)
                ->where('estado_validacion', 'VALIDADO')
                ->get();

            $limitacionesConveniosPersona = [];

            foreach ($convenios as $key => $convenio) {

                $otrasLimitacionesTexto = $convenio->otras_limitaciones;
                if ($otrasLimitacionesTexto == '<p></p>') {
                    $otrasLimitacionesTexto = null;
                }

                $observacionesTexto = $convenio->observacion;
                if ($observacionesTexto == '<p></p>') {
                    $observacionesTexto = null;
                }

                $limitacionesConveniosPersona[] = [
                    'cod_interior' => 'C/IN/' . $convenio->cod_interior . '/' . Carbon::parse($convenio->created_at)->format('Y'),
                    'limitaciones' => $this->limitacionesConvenio($convenio->id, $interior),
                    'otras_limitaciones' => $otrasLimitacionesTexto,
                    'observaciones' => $observacionesTexto
                ];
            }
            return $limitacionesConveniosPersona;
        } else {
            $conveniosEmergencia = Convenio::where('id_persona', $idPersona)
                ->where('estado_validez', 'VIGENTE')
                ->where('estado', true)
                ->get();

            $limitacionesConveniosPersona = [];

            foreach ($conveniosEmergencia as $key => $convenioEmergencia) {

                $otrasLimitacionesTexto = $convenioEmergencia->otras_limitaciones;
                if ($otrasLimitacionesTexto == '<p></p>') {
                    $otrasLimitacionesTexto = null;
                }

                $observacionesTexto = $convenioEmergencia->observacion;
                if ($observacionesTexto == '<p></p>') {
                    $observacionesTexto = null;
                }

                $limitacionesConveniosPersona[] = [
                    'cod_interior' => 'C/IN/' . $convenioEmergencia->cod_convenio . '/' . Carbon::parse($convenioEmergencia->created_at)->format('Y'),
                    'limitaciones' => $this->limitacionesConvenio($convenioEmergencia->id, $interior),
                    'otras_limitaciones' => $otrasLimitacionesTexto,
                    'observaciones' => $observacionesTexto
                ];
            }
            return $limitacionesConveniosPersona;
        }
    }

    public function limitacionesConvenio($idConvenio, $interior)
    {
        try {
            if ($interior) {
                $especialidades = 'AUTORIZAN ATENCIÓN MÉDICA EN ';

                $datosEsp = AutorizacionIntEspecialidad::where('id_autorizacion_interior', $idConvenio)->with(['especialidadAutorizacionIntEspecialidad'])->get();
                if ($datosEsp->count() > 0) {
                    if ($datosEsp->count() == 1) {
                        $especialidades .= $datosEsp[0]->especialidadAutorizacionIntEspecialidad->especialidad . '.';
                    } else {
                        foreach ($datosEsp as $key => $datoEsp) {
                            if ($key == $datosEsp->count() - 1) {
                                $especialidades .= 'y ' . $datoEsp->especialidadAutorizacionIntEspecialidad->especialidad . '.';
                            } elseif ($key == $datosEsp->count() - 2) {
                                $especialidades .= $datoEsp->especialidadAutorizacionIntEspecialidad->especialidad . ' ';
                            } else {
                                $especialidades .= $datoEsp->especialidadAutorizacionIntEspecialidad->especialidad . ', ';
                            }
                        }
                    }
                } else {
                    $especialidades = '';
                }

                $servicios = ' ADEMÁS DE ';

                $datosServ = AutorizacionIntServicio::where('id_autorizacion_interior', $idConvenio)->with(['servicioAutorizacionIntServicio'])->get();
                if ($datosServ->count() > 0) {
                    if ($datosServ->count() == 1) {
                        $servicios .= $datosServ[0]->servicioAutorizacionIntServicio->servicio . ' SI SE REQUIERE.';
                    } else {
                        foreach ($datosServ as $key => $datoServ) {
                            if ($key == $datosServ->count() - 1) {
                                $servicios .= 'y ' . $datoServ->servicioAutorizacionIntServicio->servicio . ' SI SE REQUIERE.';
                            } elseif ($key == $datosServ->count() - 2) {
                                $servicios .= $datoServ->servicioAutorizacionIntServicio->servicio . ' ';
                            } else {
                                $servicios .= $datoServ->servicioAutorizacionIntServicio->servicio . ', ';
                            }
                        }
                    }
                } else {
                    $servicios = '';
                }

                return $especialidades . $servicios;
            } else {

                $especialidades = 'AUTORIZAN ATENCIÓN MÉDICA EN ';

                $datosEsp = ConvenioEspecialidad::where('id_convenio', $idConvenio)->with(['especialidadConvenioEspecialidad'])->get();
                if ($datosEsp->count() > 0) {
                    if ($datosEsp->count() == 1) {
                        $especialidades .= $datosEsp[0]->especialidadConvenioEspecialidad->especialidad . '.';
                    } else {
                        foreach ($datosEsp as $key => $datoEsp) {
                            if ($key == $datosEsp->count() - 1) {
                                $especialidades .= 'y ' . $datoEsp->especialidadConvenioEspecialidad->especialidad . '.';
                            } elseif ($key == $datosEsp->count() - 2) {
                                $especialidades .= $datoEsp->especialidadConvenioEspecialidad->especialidad . ' ';
                            } else {
                                $especialidades .= $datoEsp->especialidadConvenioEspecialidad->especialidad . ', ';
                            }
                        }
                    }
                } else {
                    $especialidades = '';
                }

                $servicios = ' ADEMÁS DE ';

                $datosServ = ConvenioServicio::where('id_convenio', $idConvenio)->with(['servicioConvenioServicio'])->get();
                if ($datosServ->count() > 0) {
                    if ($datosServ->count() == 1) {
                        $servicios .= $datosServ[0]->servicioConvenioServicio->servicio . ' SI SE REQUIERE.';
                    } else {
                        foreach ($datosServ as $key => $datoServ) {
                            if ($key == $datosServ->count() - 1) {
                                $servicios .= 'y ' . $datoServ->servicioConvenioServicio->servicio . ' SI SE REQUIERE.';
                            } elseif ($key == $datosServ->count() - 2) {
                                $servicios .= $datoServ->servicioConvenioServicio->servicio . ' ';
                            } else {
                                $servicios .= $datoServ->servicioConvenioServicio->servicio . ', ';
                            }
                        }
                    }
                } else {
                    $servicios = '';
                }

                return $especialidades . $servicios;
            }
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error en limitaciones Convenio", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
            return null;
        }
    }

    /*public function calculoFechasLimite()
    {
        $cantidadDiasHabilitados = DiasHabilitadosAgenda::where('estado', true)
            ->where('id_servicio_plataforma', 1)
            ->first();

        //dd($cantidadDiasHabilitados);

        $fechaHoyCalculoFechas = Carbon::now();

        if ($cantidadDiasHabilitados) {
            $nroDias = $cantidadDiasHabilitados->nro_dias;
            $fechas = [];
            $diasAgregados = 0;
            $i = $cantidadDiasHabilitados->hoy ? 0 : 1;
            //$this->fechaAgenda = $cantidadDiasHabilitados->hoy ? Carbon::now()->format('Y-m-d') : Carbon::now()->addDays(1)->format('Y-m-d');
            $this->fechaAgenda = $cantidadDiasHabilitados->hoy ? $fechaHoyCalculoFechas->format('Y-m-d') : $fechaHoyCalculoFechas->addDays(1)->format('Y-m-d');

            //PARA MANIPULAR FECHAS --SOLO SIRVE EN PLATAFORMA WEB
            if ($fechaHoyCalculoFechas->dayOfWeek === 6) {
                //$nroDias = $nroDias + 1;
                $nroDias = 3;
            }

            if ($fechaHoyCalculoFechas->dayOfWeek === 5) {
                //$nroDias = $nroDias + 2;
                $nroDias = 4;
            }

            while ($diasAgregados < $nroDias) {

                $nuevaFecha = Carbon::now()->addDays($i)->format('Y-m-d');
                $existeFeriado = AdministracionFeriados::where('estado', true)
                    ->where('fecha', $nuevaFecha)
                    ->first();

                $fechas[] = $nuevaFecha;

                if (!$existeFeriado) {
                    $diasAgregados++;
                }

                $i++;
            }

            //dd($fechas);

            $verificacionSabado = Carbon::parse(end($fechas));

            if ($verificacionSabado->dayOfWeek === 6) {

                $feriado = true;
                while ($feriado) {
                    $nuevaFecha = $verificacionSabado->addDay();
                    $fechas[] = $nuevaFecha->format('Y-m-d');
                    //dd($nuevaFecha);
                    $existeFeriado = AdministracionFeriados::where('estado', true)
                        ->where('fecha', $nuevaFecha)
                        ->exists();
                    //dd($existeFeriado);
                    $esDomingo = $nuevaFecha->dayOfWeek === 0 ? true : false;
                    if (!$existeFeriado && !$esDomingo) {
                        $feriado = false;
                    }
                }
            }
            dd($fechas);

            $this->fechaLimiteInferior = $fechas[0];
            $this->fechaLimiteSuperior = end($fechas);
        }
    }*/

    public function calculoFechasLimite()
    {
        $configDias = DiasHabilitadosAgenda::where('estado', true)
            ->where('id_servicio_plataforma', 1)
            ->first();

        if (!$configDias) {
            return;
        }

        $nroDiasRequeridos = (int) $configDias->nro_dias;

        $feriados = AdministracionFeriados::where('estado', true)
            ->pluck('fecha')
            ->toArray();

        $fechaActual = Carbon::now();
        if (!$configDias->hoy) {
            $fechaActual->addDay();
        }

        $this->fechaAgenda = $fechaActual->format('Y-m-d');

        $fechas = [];
        $diasHabilesAgregados = 0;

        while ($diasHabilesAgregados < $nroDiasRequeridos) {
            $fechaStr = $fechaActual->format('Y-m-d');
            $esFeriado = in_array($fechaStr, $feriados);

            $fechas[] = $fechaStr;

            if (!$esFeriado) {
                $diasHabilesAgregados++;
            }

            $fechaActual->addDay();
        }

        $ultimaFecha = Carbon::parse(end($fechas));

        while ($this->esDiaInvalidoParaLimiteSuperior($ultimaFecha, $feriados)) {
            $ultimaFecha->addDay();
            $fechas[] = $ultimaFecha->format('Y-m-d');
        }

        //dd($fechas);

        $this->fechaLimiteInferior = $fechas[0];
        $this->fechaLimiteSuperior = end($fechas);
    }

    private function esDiaInvalidoParaLimiteSuperior(Carbon $fecha, array $feriados): bool
    {
        $esFinDeSemana = $fecha->isWeekend(); 
        $esFeriado = in_array($fecha->format('Y-m-d'), $feriados);

        return $esFinDeSemana || $esFeriado;
    }

    public function cambiarFecha()
    {
        //Para vigencia de carnets

        $verficacionCarnet = new VencimientoCarnet();
        $respuestaVerificacionCarnet = $verficacionCarnet->verificarVencimientoCarnet($this->idAsegurado, $this->fechaAgenda);

        if (!$respuestaVerificacionCarnet['vigente']) {
            $this->dispatch('carnetCaduco', fechaVencimiento: Carbon::parse($respuestaVerificacionCarnet['fecha_vencimiento'])->format('d/m/Y'));
            //return;
        }

        // Fin vigencia de carnet


        if ($this->id_especialista !== null) {
            $this->mostrarEspecialistas();
            //dd($this->lista_especialistas);
            if ($this->lista_especialistas->isEmpty()) {
                $this->dispatch('cambioFechaNoHayEspecialistas');
            }
        }

        if ($this->id_especialidad !== null) {
            $this->mostrarEspecialidades();
            //dd($this->especialidades);
            if ($this->especialidades->isEmpty()) {
                $this->dispatch('cambioFechaNoHayEspecialidades');
            }
        }
    }

    public function mostrarEspecialidades()
    {
        //dd($this->id_especialidad);
        if ($this->idAsegurado !== null) {
            $especialidades = Especialidad::with('especialidadHabilitadoServicioEspecialidad')
                ->where('estado', true)
                ->where('especialidad_imagenologia', false)
                ->orderBy('especialidad', 'asc')
                ->get();
            //dd($especialidades);
            $this->especialidades = $especialidades->filter(function ($especialidad) {
                $habilitado = $especialidad->especialidadHabilitadoServicioEspecialidad->filter(function ($servicio) {
                    return $servicio->id_servicio === 1 && $servicio->estado &&
                        ($servicio->permanente || ($servicio->fecha_inicio <= $this->fechaAgenda && $servicio->fecha_fin >= $this->fechaAgenda));
                })->isNotEmpty();

                if (!$habilitado) {
                    return false;
                }

                if ($this->edad > $this->edadGeriatria && $especialidad->especialidad == 'GERIATRÍA') {
                    return true;
                }

                if ($this->edad < $this->edadPediatria && str_contains($especialidad->especialidad, 'PEDI')) {
                    return true;
                }

                if ($this->sexo == 'F' && $especialidad->especialidad == 'GINECOLOGÍA Y OBSTETRICIA') {
                    return true;
                }

                /*if ($this->sexo == 'M' && $especialidad->especialidad == 'UROLOGÍA') {
                    return true;
                }*/

                //return !in_array($especialidad->especialidad, ['GERIATRÍA', 'PEDIATRÍA', 'GINECOLOGÍA', 'UROLOGÍA']);
                $arrayEspecialidadesRestringidas = [
                    'GERIATRÍA',
                    'PEDIATRÍA',
                    'GINECOLOGÍA Y OBSTETRICIA',
                    'CARDIOLOGÍA PEDIATRICA',
                    'ECOCARDIOGRAFIA PEDIATRICA',
                    'CIRUGÍA PEDIATRICA',
                    'NEUMOLOGIA PEDIATRICA',
                    'TRAUMATOLOGIA ORTOPEDIA PEDIATRICA',
                    'NEFROLOGIA PEDIATRICA',
                    'GASTROENTEROLOGIA PEDIATRICA',
                    'HEMATOLOGIA PEDIATRICA',
                    'ENDOCRINOLOGIA PEDIATRICA',
                    'TERAPIA INTENSIVA PEDIÁTRICA',
                    'CIRUGIA PEDIATRICA',
                ];

                return !in_array($especialidad->especialidad, $arrayEspecialidadesRestringidas);
            });

            if (
                $this->id_especialidad !== null &&
                !$this->especialidades->contains('id', $this->id_especialidad)
            ) {
                $this->mostrarEspecialistas();
                $this->id_especialidad = null;
            }
        }
    }

    public function mostrarEspecialistas()
    {
        //dd($this->id_especialidad);
        $this->horasDisponibles = [];
        $this->variosTurnos = null;
        $this->turnoSeleccionado = null;
        $this->id_especialista = null;

        //Para vigencia de carnets

        $verficacionCarnet = new VencimientoCarnet();
        $respuestaVerificacionCarnet = $verficacionCarnet->verificarVencimientoCarnet($this->idAsegurado, $this->fechaAgenda);

        if (!$respuestaVerificacionCarnet['vigente']) {
            $this->dispatch('carnetCaduco', fechaVencimiento: Carbon::parse($respuestaVerificacionCarnet['fecha_vencimiento'])->format('d/m/Y'));
            //return;
        }

        // Fin vigencia de carnet

        if (!$this->fechaAgenda) {
            return;
        }

        $diaSemana = Carbon::parse($this->fechaAgenda)->dayOfWeek;

        $lista_especialistas = Especialista::with([
            'personaEspecialista',
            'suspensionVacacionEspecialista',
            'especialistaHabilitadoEspecialista'
        ])
            ->where('id_especialidad', $this->id_especialidad)
            ->where('estado', true)
            ->where(function ($query) {
                /*$query->whereDoesntHave('suspensionVacacionEspecialista')
                    ->orWhereHas('suspensionVacacionEspecialista', function ($otroQuery) {
                        $otroQuery->whereNot('estado', 'VACACION ACTIVA');
                            //->whereNot('fecha_inicio', '>', $this->fechaAgenda)
                            //->whereNot('fecha_fin', '<', $this->fechaAgenda); 
                    });*/
                $query->whereDoesntHave('suspensionVacacionEspecialista', function ($otroQuery) {
                    $otroQuery->where('estado', 'VACACION ACTIVA')
                        ->whereDate('fecha_inicio', '<=', $this->fechaAgenda)
                        ->whereDate('fecha_fin', '>=', $this->fechaAgenda);
                });
            })
            ->get();

        $this->lista_especialistas = $lista_especialistas->filter(function ($especialista) use ($diaSemana) {
            $habilitado = $especialista->especialistaHabilitadoEspecialista->filter(function ($servicio) use ($diaSemana) {
                return $servicio->id_servicio === 1 && $servicio->estado &&
                    ($servicio->permanente || ($servicio->fecha_inicio <= $this->fechaAgenda && $servicio->fecha_fin >= $this->fechaAgenda)) &&
                    $diaSemana === $servicio->id_dia;
            })->isNotEmpty();

            if (!$habilitado) {
                return false;
            }

            if ($this->identificadorTipoAsegurado === 1 && $especialista->afiliados) {
                return true;
            }

            if ($this->identificadorTipoAsegurado === 2 && $especialista->convenios) {
                return true;
            }

            if ($this->identificadorTipoAsegurado === 3 && $especialista->estudiantes) {
                return true;
            }

            return false;
        });

        if (
            $this->id_especialista !== null &&
            !$this->lista_especialistas->contains('id', $this->id_especialista)
        ) {
            $this->id_especialista = null;
        }
    }

    public function datosEspecialista()
    {
        //try {
        $this->horasDisponibles = [];
        $this->turnos = null;
        $this->turnoSeleccionado = null;
        $this->variosTurnos = null;

        $especialista = Especialista::where('id', $this->id_especialista)
            ->where('estado', true)
            ->with('especialidadEspecialista', 'personaEspecialista')
            ->first();

        $this->especialidad_e = $especialista->especialidadEspecialista->especialidad;
        $this->nombres_e = $especialista->personaEspecialista->nombres;
        $this->p_apellido_e = $especialista->personaEspecialista->p_apellido;
        $this->s_apellido_e = $especialista->personaEspecialista->s_apellido;
        $this->nombre_completo_e = $this->p_apellido_e . ' ' . $this->s_apellido_e . ' ' . $this->nombres_e;
        //$this->matricula_seguro_e = $especialista->personaEspecialista->matricula_seguro;
        $this->foto_e = $this->obtenerFotoEspecialista($especialista->foto);

        $diaAgenda = Carbon::parse($this->fechaAgenda)->dayOfWeek;

        $this->asignacionHora = AsignacionHorario::with('horarioAsignacionHorario')
            ->where('id_especialista', $this->id_especialista)
            ->where(function ($query) {
                $query->whereNull('fecha_inicio')
                    ->orWhereDate('fecha_inicio', '<=', $this->fechaAgenda);
            })
            ->where(function ($query) {
                $query->whereNull('fecha_fin')
                    ->orWhereDate('fecha_fin', '>=', $this->fechaAgenda);
            })
            ->where('id_dia', $diaAgenda)
            ->where('estado', true)
            ->get();

        //dd($this->asignacionHora);

        if ($this->asignacionHora->isNotEmpty()) {
            $this->variosTurnos = $this->asignacionHora->count() > 1 ? 1 : 2;

            if ($this->variosTurnos === 1) {
                $this->turnos = $this->asignacionHora->toArray();
                //dd($this->turnos);
            } else {
                $this->turnoSolo = $this->asignacionHora[0]->horarioAsignacionHorario->turno;
                $this->turnoSeleccionado = $this->asignacionHora[0]->id;

                // Verificación de agenda 
                $verificacionAgenda = new GrupoFamiliarPersona();
                $grupoFam = $verificacionAgenda->encontrarPersona($this->idAsegurado);
                //dd($grupoFam);
                $countAgendas = Agenda::with([
                    'asignacionHorarioAgenda.especialistaAsignacionHorario.personaEspecialista',
                    'asignacionHorarioAgenda.especialistaAsignacionHorario.especialidadEspecialista',
                    'personaAgenda.tipoAseguradoPersona'
                ])
                    ->where('fecha_agenda', $this->fechaAgenda)
                    ->whereNot('anulacion_ficha', true)
                    ->whereIn('id_persona', collect($grupoFam)->pluck('id'))
                    ->where('ficha_extra', false)
                    ->orderBy('id', 'desc')
                    ->get();

                if (count($countAgendas) === 0) {
                    $this->horarioDisponible();
                } else {
                    //dd($countAgendas);
                    $datosAgenditaExistente = $countAgendas
                        ->first();
                    //dd($datosAgenditaExistente);
                    $arrayAgendaExistente = [
                        'nombre_completo' => implode(' ', array_filter([
                            $datosAgenditaExistente->personaAgenda->p_apellido,
                            $datosAgenditaExistente->personaAgenda->s_apellido,
                            $datosAgenditaExistente->personaAgenda->nombres,
                        ])),
                        'matricula' => $datosAgenditaExistente->personaAgenda->matricula_seguro,
                        'tipo_asegurado' => $datosAgenditaExistente->personaAgenda->tipoAseguradoPersona->tipo_asegurado,
                        'especialidad' => $datosAgenditaExistente->asignacionHorarioAgenda->especialistaAsignacionHorario->especialidadEspecialista->especialidad,
                        'especialista' => implode(' ', array_filter([
                            $datosAgenditaExistente->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->p_apellido,
                            $datosAgenditaExistente->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->nombres,
                        ])),
                        'fecha_agenda' => Carbon::parse($datosAgenditaExistente->fecha_agenda)->format('d/m/Y'),
                        'hora' => Carbon::parse($datosAgenditaExistente->hora_agenda)->format('H:i')
                    ];

                    //dd($arrayAgendaExistente);

                    $this->mostrarBoton = true;
                    $this->horarioDisponibleAfter();
                    $this->dispatch('alertaAgendaExistente', datosAgendaExistenteArray: $arrayAgendaExistente);
                }
            }
        } else {
            $this->dispatch('noTieneAgenda');
        }
        /*} catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al obtener imagen", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }*/
    }

    public function horarioDisponibleAfter()
    {
        $turnoHorario = AsignacionHorario::with('horarioAsignacionHorario')
            ->where('id', $this->turnoSeleccionado)
            ->first();
        $this->idAsignacionHorario = $turnoHorario->id;
        $this->intervaloActual = $turnoHorario->horarioAsignacionHorario->intervalo;



        $this->horasDisponibles = $this->listaHoras(
            $turnoHorario->horarioAsignacionHorario->hora_inicio,
            $turnoHorario->horarioAsignacionHorario->hora_fin,
            $turnoHorario->horarioAsignacionHorario->intervalo
        );

        $this->verConsultorio = $turnoHorario->consultorioAsignacionHorario->numero_consultorio;
        //fañta
        $this->verPiso = $turnoHorario->consultorioAsignacionHorario->consultorioSedes->piso;
        $this->verUbicacion = $turnoHorario->consultorioAsignacionHorario->consultorioSedes->ubicacion;
        $this->verDireccion = $turnoHorario->consultorioAsignacionHorario->consultorioSedes->residenciaSedes->direccion . ' ' .
            $turnoHorario->consultorioAsignacionHorario->consultorioSedes->residenciaSedes->nro_vivienda . ' ZONA ' .
            $turnoHorario->consultorioAsignacionHorario->consultorioSedes->residenciaSedes->zonaResidencia->nombre;
    }

    public function horarioDisponible()
    {
        //try {
        $this->mostrarBoton = true;
        $verificacionAgenda = new GrupoFamiliarPersona();
        $grupoFamIds = collect($verificacionAgenda->encontrarPersona($this->idAsegurado))->pluck('id');

        $countAgendas = Agenda::with([
            'asignacionHorarioAgenda.especialistaAsignacionHorario.personaEspecialista',
            'asignacionHorarioAgenda.especialistaAsignacionHorario.especialidadEspecialista',
            'personaAgenda.tipoAseguradoPersona'
        ])
            ->where('fecha_agenda', $this->fechaAgenda)
            ->whereNot('anulacion_ficha', true)
            ->whereIn('id_persona', $grupoFamIds)
            ->where('ficha_extra', false)
            ->orderBy('id', 'desc')
            ->get();

        if (count($countAgendas) === 0) {
            $turnoHorario = AsignacionHorario::with('horarioAsignacionHorario', 'consultorioAsignacionHorario.consultorioSedes.residenciaSedes.zonaResidencia')
                ->find($this->turnoSeleccionado);

            if ($turnoHorario) {
                $this->idAsignacionHorario = $turnoHorario->id;
                $this->intervaloActual = $turnoHorario->horarioAsignacionHorario->intervalo;
                $this->horasDisponibles = $this->listaHoras(
                    $turnoHorario->horarioAsignacionHorario->hora_inicio,
                    $turnoHorario->horarioAsignacionHorario->hora_fin,
                    $turnoHorario->horarioAsignacionHorario->intervalo
                );
                $this->verConsultorio = $turnoHorario->consultorioAsignacionHorario->numero_consultorio;
                $this->verUbicacion = $turnoHorario->consultorioAsignacionHorario->consultorioSedes->ubicacion;
                $this->verDireccion = $turnoHorario->consultorioAsignacionHorario->consultorioSedes->residenciaSedes->direccion . ' ' .
                    $turnoHorario->consultorioAsignacionHorario->consultorioSedes->residenciaSedes->nro_vivienda . ' ZONA ' .
                    $turnoHorario->consultorioAsignacionHorario->consultorioSedes->residenciaSedes->zonaResidencia->nombre;
            }
        } else {
            $datosAgenditaExistente = $countAgendas
                ->first();
            //dd($datosAgenditaExistente);
            $arrayAgendaExistente = [
                'nombre_completo' => implode(' ', array_filter([
                    $datosAgenditaExistente->personaAgenda->p_apellido,
                    $datosAgenditaExistente->personaAgenda->s_apellido,
                    $datosAgenditaExistente->personaAgenda->nombres,
                ])),
                'matricula' => $datosAgenditaExistente->personaAgenda->matricula_seguro,
                'tipo_asegurado' => $datosAgenditaExistente->personaAgenda->tipoAseguradoPersona->tipo_asegurado,
                'especialidad' => $datosAgenditaExistente->asignacionHorarioAgenda->especialistaAsignacionHorario->especialidadEspecialista->especialidad,
                'especialista' => implode(' ', array_filter([
                    $datosAgenditaExistente->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->p_apellido,
                    $datosAgenditaExistente->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->nombres,
                ])),
                'fecha_agenda' => Carbon::parse($datosAgenditaExistente->fecha_agenda)->format('d/m/Y'),
                'hora' => Carbon::parse($datosAgenditaExistente->hora_agenda)->format('H:i')
            ];

            //dd($arrayAgendaExistente);

            $this->horarioDisponibleAfter();
            $this->dispatch('alertaAgendaExistente', datosAgendaExistenteArray: $arrayAgendaExistente);
        }
        /*} catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error en horario disponible", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }*/
    }

    public function agendar($horaSeleccionada)
    {

        $this->horaActual = Carbon::now()->format('H:i');
        if (!Carbon::parse($this->fechaAgenda)->isToday()) {
            $this->horaActual = '00:00';
        }

        if ($horaSeleccionada < $this->horaActual) {
            $this->dispatch('horaPasada');
            return;
        }

        $this->horaSelect = $horaSeleccionada;

        $existeAgenda = Agenda::where('fecha_agenda', $this->fechaAgenda)
            ->where('hora_agenda', $horaSeleccionada)
            //->where('confirmado', true)
            ->whereNot('anulacion_ficha', true)
            ->where('id_asignacion_horario', $this->idAsignacionHorario)
            ->orderBy('id', 'desc')
            ->first();

        if ($existeAgenda) {
            $this->horarioDisponible();
            $this->dispatch('agendaYaExiste');
        } else {
            $this->horarioDisponible();
            $this->dispatch('crearAgendaNueva');
        }
    }

    //FORMULA DE LA CLAVE UNICA PARA EVITAR DUPLICIDAD DE AGENDAS
    #[On('agendaConfirmar')]
    public function agendaConfirmar(Request $request)
    {
        //dd($this->horasDisponibles);
        //try {
        $replica = new ReplicarAgendaS1();

        $horaC = Carbon::parse($this->horaSelect)->format('H');
        $minutosC = Carbon::parse($this->horaSelect)->format('i');
        $diaC = Carbon::parse($this->fechaAgenda)->format('d');
        $mesC = Carbon::parse($this->fechaAgenda)->format('m');
        $gestionC = Carbon::parse($this->fechaAgenda)->format('Y');
        $clave_unica = $this->idAsignacionHorario . $horaC . $minutosC . $diaC . $mesC . $gestionC;

        $this->mostrarBoton = true;

        $verificacionAgenda = new GrupoFamiliarPersona();
        $grupoFamIds = collect($verificacionAgenda->encontrarPersona($this->idAsegurado))->pluck('id');

        $countAgendas = Agenda::with([
            'asignacionHorarioAgenda.especialistaAsignacionHorario.personaEspecialista',
            'asignacionHorarioAgenda.especialistaAsignacionHorario.especialidadEspecialista',
            'personaAgenda.tipoAseguradoPersona'
        ])
            ->where('fecha_agenda', $this->fechaAgenda)
            ->whereNot('anulacion_ficha', true)
            ->whereIn('id_persona', $grupoFamIds)
            ->where('ficha_extra', false)
            ->orderBy('id', 'desc')
            ->get();

        if (count($countAgendas) === 0) {
            $nuevaAgenda = Agenda::create([
                'fecha_agenda' => $this->fechaAgenda,
                'hora_agenda' => $this->horaSelect,
                'confirmado' => true,
                'id_servicio_plataforma' => 1,
                'id_persona' => $this->idAsegurado,
                'id_asignacion_horario' => $this->idAsignacionHorario,
                'anulacion_ficha' => false,
                'ficha_extra' => false,
                'clave_unica' => $clave_unica,
                'ip' => $request->ip(),
                'nombre_equipo' => gethostname(),
                'id_user_created' => auth()->user()->id,
            ]);
            $replica->replicarAgenda($nuevaAgenda->id, $this->codigo_institucional, $this->institucion);
            $this->horarioDisponibleAfter();
            $this->dispatch($nuevaAgenda ? 'agendaCreada' : 'agendaNoCreada');
        } else {

            $datosAgenditaExistente = $countAgendas
                ->first();
            //dd($datosAgenditaExistente);
            $arrayAgendaExistente = [
                'nombre_completo' => implode(' ', array_filter([
                    $datosAgenditaExistente->personaAgenda->p_apellido,
                    $datosAgenditaExistente->personaAgenda->s_apellido,
                    $datosAgenditaExistente->personaAgenda->nombres,
                ])),
                'matricula' => $datosAgenditaExistente->personaAgenda->matricula_seguro,
                'tipo_asegurado' => $datosAgenditaExistente->personaAgenda->tipoAseguradoPersona->tipo_asegurado,
                'especialidad' => $datosAgenditaExistente->asignacionHorarioAgenda->especialistaAsignacionHorario->especialidadEspecialista->especialidad,
                'especialista' => implode(' ', array_filter([
                    $datosAgenditaExistente->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->p_apellido,
                    $datosAgenditaExistente->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->nombres,
                ])),
                'fecha_agenda' => Carbon::parse($datosAgenditaExistente->fecha_agenda)->format('d/m/Y'),
                'hora' => Carbon::parse($datosAgenditaExistente->hora_agenda)->format('H:i')
            ];

            $this->horarioDisponibleAfter();
            $this->dispatch('alertaAgendaExistente', datosAgendaExistenteArray: $arrayAgendaExistente);
        }
        //
        /*} catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al confirmar agenda", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }*/
    }

    //FUNCION PARA OBTENER IMAGENES
    private function obtenerImagenBase64($idFoto, $liga)
    {
        try {
            if ($idFoto) {
                $path = $liga . $idFoto;

                $imageData = Storage::disk('ftp')->get($path);
                $this->foto_nombre = base64_encode($imageData);
                return $this->foto_nombre;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }

    //FUNCION PARA OBTENER IMAGENES DE ESPECIALISTAS
    private function obtenerFotoEspecialista($idFoto)
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
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function listaHoras($horaInicio, $horaFin, $inter)
    {
        $this->horaActual = Carbon::parse($this->fechaAgenda)->isToday() ? Carbon::now()->format('H:i') : '00:00';

        $horas = [];
        $inicio = Carbon::createFromFormat('H:i:s', $horaInicio);
        $fin = Carbon::createFromFormat('H:i:s', $horaFin);

        $hayFichaExtra = Agenda::with(['suspensionHorarioAgenda', 'servicioPlataformaAgenda'])
            ->where('fecha_agenda', $this->fechaAgenda)
            ->where('ficha_extra', true)
            ->where('id_asignacion_horario', $this->turnoSeleccionado)
            ->orderBy('hora_agenda', 'desc')
            ->first();

        if ($hayFichaExtra) {
            $fin = Carbon::createFromFormat('H:i:s', $hayFichaExtra->hora_agenda)->addMinutes($inter);
        }

        // horitas disponiblesssss
        while ($inicio->lt($fin)) {
            $horaActual = $inicio->format('H:i');
            $estadoHora = Agenda::with(['suspensionHorarioAgenda', 'servicioPlataformaAgenda'])
                ->where('fecha_agenda', $this->fechaAgenda)
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
    }

    public function procesarHora($estadoHora, $horaActual)
    {
        //try {
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
            'persona_asociada' => true,
        ];

        if ($estadoHora) {
            $hora['id_agenda'] = $estadoHora->id;
            if ($estadoHora->anulacion_ficha) {
                return $hora;
            } else {
                if ($estadoHora->id_persona) {

                    $busquedaPersonaId = new ListadoAsegurados();
                    $personaOcupado = $busquedaPersonaId->busquedaAfiliadoPorId($estadoHora->id_persona);
                    //dd($personaOcupado);

                    /*$hora['matricula'] = $personaOcupado['codigo_institucional']
                        ? $personaOcupado['matricula_seguro'] . ' - ' . $personaOcupado['codigo_institucional']
                        : $personaOcupado['matricula_seguro'];*/

                    $hora['matricula'] = $personaOcupado['matricula_seguro'];

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
                    $hora['persona_asociada'] = false;
                }
            }
        }
        return $hora;
        /*} catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al procesar hora", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
            return null;
        }*/
    }

    public function fichaExtra(Request $request)
    {
        //PARA OBTENER EL NUMERO DE ARRAY QUE CORRESPONDE OJITO EH JAJAJAJAJAJJ 
        //dd($this->idAsegurado);
        $replica = new ReplicarAgendaS1();

        //try {
        $hayFichaExtra = Agenda::where('fecha_agenda', $this->fechaAgenda)
            ->where('ficha_extra', true)
            ->where('id_asignacion_horario', $this->turnoSeleccionado)
            ->orderBy('hora_agenda', 'desc')
            ->value('hora_agenda');

        if ($hayFichaExtra) {
            $ultimaHora = Carbon::createFromFormat('H:i:s', $hayFichaExtra)
                ->addMinutes($this->intervaloActual);
        } else {
            $ultimaHora = Carbon::createFromFormat('H:i', end($this->horasDisponibles)['hora'])
                ->addMinutes($this->intervaloActual);
        }

        //$this->mostrarBoton = true;

        $horaC = Carbon::parse($ultimaHora)->format('H');
        $minutosC = Carbon::parse($ultimaHora)->format('i');
        $diaC = Carbon::parse($this->fechaAgenda)->format('d');
        $mesC = Carbon::parse($this->fechaAgenda)->format('m');
        $gestionC = Carbon::parse($this->fechaAgenda)->format('Y');
        $clave_unica = $this->idAsignacionHorario . $horaC . $minutosC . $diaC . $mesC . $gestionC;

        $existeAgenda = Agenda::where('fecha_agenda', $this->fechaAgenda)
            ->where('ficha_extra', true)
            ->where('confirmado', true)
            ->where('id_persona', $this->idAsegurado)
            ->where('id_asignacion_horario', $this->idAsignacionHorario)
            ->exists();

        if ($existeAgenda) {
            $this->horarioDisponibleAfter();
            $this->dispatch('fichaExtraExiste');
        } else {
            $nuevaAgenda = Agenda::create([
                'fecha_agenda' => $this->fechaAgenda,
                'hora_agenda' => $ultimaHora,
                'confirmado' => true,
                'id_servicio_plataforma' => 1,
                'id_persona' => $this->idAsegurado,
                'id_asignacion_horario' => $this->idAsignacionHorario,
                'anulacion_ficha' => false,
                'ficha_extra' => true,
                'clave_unica' => $clave_unica,
                'ip' => $request->ip(),
                'nombre_equipo' => gethostname(),
                'id_user_created' => auth()->user()->id,
            ]);

            $replica->replicarAgenda($nuevaAgenda->id, $this->codigo_institucional, $this->institucion);
            $this->horarioDisponibleAfter();
            $this->dispatch($nuevaAgenda ? 'agendaCreada' : 'agendaNoCreada');
        }
        /*} catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al registrar Ficha Extra", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }*/
    }

    public function redirigirAgendamientoEcografia()
    {
        return redirect()->route('plataforma.agendamiento.ecografia.agenda-ecografia.create');
    }

    public function volver()
    {
        return redirect()->route('plataforma.agendamiento.plataforma.index');
    }

    public function fichaAgenda($id_agenda)
    {
        //dd('entra al boton', $id_agenda);

        $this->js('window.open("' . route('pdf.generar.fichaagenda', ['idAgenda' => $id_agenda]) . '", "_blank")');
    }
}
