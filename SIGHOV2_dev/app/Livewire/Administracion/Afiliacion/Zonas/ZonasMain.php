<?php

namespace App\Livewire\Administracion\Afiliacion\Zonas;

use App\Jobs\ReplicarAgendasWebApk;
use Livewire\Component;
use App\Models\Administracion\Departamento;
use App\Models\Administracion\LinkServicio;
use App\Models\Administracion\Provincia;
use App\Models\Administracion\Municipio;
use App\Models\Administracion\Zona;
use App\Models\Plataforma\Agenda;
use App\Services\ActualizacionDatosSegipS1;
use App\Services\ActualizacionesPlataforma;
use App\Services\ObtenerPoblacion;
use App\Services\ReplicaPreafiliacion;
use App\Services\ReplicarAgendaS1;
use App\Services\RespuestaApisService;
use App\Services\ServiciosCachecito;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\On;

class ZonasMain extends Component
{
    public $idZona;
    public $departamentoEdit;
    public $provinciaEdit;
    public $municipioEdit;
    public $zonaEdit;

    protected $listeners = ['actualizarZona'];

    public function render()
    {
        $this->mostrarZona($this->idZona);
        return view('livewire.Administracion.afiliacion.zonas.zonas-main');
    }

    public function mount()
    {
        //$dato = new ObtenerPoblacion();
        //dd($dato->obtenerPoblacionAsegurada());
        //dd($dato->obtenerDatosAfiliacionId(8762));
        //dd($dato->obtenerDatosAfiliacionId(1));    
        /*$esp = new ServiciosCachecito();
        dd($esp->listaEspecialistasHabilitados());*/
        /*$dato = new ReplicarAgendaS1();
        $dato->replicarAgendaWebApk();*/
        /*$dato = new ActualizacionesPlataforma();
        $dato->actualizacionEspecialistaHabilitadoServicio();*/
        /*$idPreafiliacion = 8;
        $dato = new ReplicaPreafiliacion();
        dd($dato->enviarDatosPreafiliacion($idPreafiliacion));*/
        /*$datos = [
            'ciAntiguo' => '9868420',
            'ciNuevo' => '9868421',
            'nombres' => 'BORIS ORLANDO',
            'p_apellido' => 'ROQUE',
            's_apellido' => 'HUANCA',
            'fecha_nacimiento' => '2000-04-29',
            'sexo' => 'M',
            'matricula_seguro' => '000429RHB',
        ];

        $dato = new ActualizacionDatosSegipS1();
        $dato->datosActualizados($datos);*/


        /*$datosContrastadosSegip = [
            'ciAntiguo' => '83643991',
            'ciNuevo' => '836439912',
            'nombres' => 'JAVIER JAVIER',
            'p_apellido' => 'TICONA',
            's_apellido' => 'TARQUI',
            'fecha_nacimiento' => '1990-02-23',
            'sexo' => 'M',
            'matricula_seguro' => '930223TTJ',
        ];
        //dd($datosContrastadosSegip);
        $dato = new ActualizacionDatosSegipS1();
        $dato->enviarDatosActualizados($datosContrastadosSegip);*/
        /*set_time_limit(24000);*/


        //POR SI ACASO HABILITAR ESTOOOOOO

        /*set_time_limit(24000);
        $agendas = Agenda::whereNot('anulacion_ficha', true)
            //->whereBetween('created_at', [$diaAnteriorInicio, $diaAnteriorFin])
            ->where('id_servicio_plataforma', 2)
            ->where('replicado', false)
            //->where('id', 1108)
            //->whereBetween('id', [1113, 1116])
            ->whereNot('id_persona', null)
            ->orderBy('id', 'desc')
            ->get();
        //dd($agendas);
        $agendamientoPruba = new ReplicarAgendasWebApk();

        foreach ($agendas as $key => $agenda) {
            $agendamientoPruba->replicarAgenda($agenda->id, null, 'UMSA');
        }*/


        /*$agendaVarias = Agenda::where('anulacion_ficha', false)
            ->where('replicado', false)
            ->whereNot('id_persona', null)
            ->where('id_suspension_horario', null)
            ->orderBy('id', 'desc')->limit('100')->get();
        //dd($agendaVarias);
        $probarReplicasAgenda = new ReplicarAgendasWebApk();
        $cont = 0;

        foreach ($agendaVarias as $key => $agendita) {
            //dd($agendita);
            $idAgenda = $agendita->id;
            $codigoInstitucional = null;
            $institucion = 'sdasdasdasdasdasd';
            // $probarReplicasAgenda->replicarAgenda($agendita->id, null, 'UMSA');
            try {
                $datosReplica = [];

                $agenda = Agenda::with([
                    'personaAgenda.tipoAseguradoPersona',
                    'asignacionHorarioAgenda.especialistaAsignacionHorario.especialidadEspecialista',
                    'servicioPlataformaAgenda'
                ])
                    ->whereNot('anulacion_ficha', true)
                    ->find($idAgenda);
                // dd($agenda);
                if ($agenda) {
                    $datosReplica = [
                        'agenda' => [
                            'id_agenda' => $idAgenda,
                            'ci_paciente' => implode('-', array_filter([$agenda->personaAgenda->ci, $agenda->personaAgenda->complemento])),
                            'ci_medico' => implode('-', array_filter([
                                $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->ci,
                                $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->complemento
                            ])),
                            'hora' => $agenda->hora_agenda,
                            'fecha' => $agenda->fecha_agenda,
                            'matricula' => $agenda->personaAgenda->matricula_seguro,
                            'nombre_asegurado' => implode(' ', array_filter([
                                $agenda->personaAgenda->p_apellido,
                                $agenda->personaAgenda->s_apellido,
                                $agenda->personaAgenda->nombres
                            ])),
                            'edad' => Carbon::parse($agenda->personaAgenda->fecha_nacimiento)->age,
                            'sexo' => $agenda->personaAgenda->sexo,
                            'tipo_asegurado' => $agenda->personaAgenda->tipoAseguradoPersona->tipo_asegurado,
                            'especialidad' => $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->especialidadEspecialista->especialidad,
                            'id_user_created_s2' => $agenda->id_user_created,
                            'id_user_updated_s2' => $agenda->id_user_updated,
                            'estado_agenda' => 'AGENDADO'
                        ],
                        'paciente' => [
                            'nombres' => $agenda->personaAgenda->nombres,
                            'ap_Paterno' => $agenda->personaAgenda->p_apellido,
                            'ap_Materno' => $agenda->personaAgenda->s_apellido,
                            'fecha_nacimiento' => $agenda->personaAgenda->fecha_nacimiento,
                            'genero' => $agenda->personaAgenda->sexo,
                            'numero_identificacion' => implode('-', array_filter([$agenda->personaAgenda->ci, $agenda->personaAgenda->complemento])),
                            'telefono1' => '0',
                            'tipo_asegurado' => $agenda->personaAgenda->id_tipo_asegurado,
                            'matricula' => $agenda->personaAgenda->matricula_seguro,
                            'codigo' => $codigoInstitucional,
                            'institucion' => $institucion
                        ],
                        'medico' => [
                            'nombres' => $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->nombres,
                            'ap_Paterno' => $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->p_apellido,
                            'ap_Materno' => $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->s_apellido,
                            'fecha_nacimiento' => $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->fecha_nacimiento,
                            'genero' => $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->sexo,
                            'numero_identificacion' => implode('-', array_filter([
                                $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->ci,
                                $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->complemento
                            ])),
                            'telefono1' => '0'
                        ]
                    ];
                }
                //dd($datosReplica);
                //return $datosReplica;

                if ($datosReplica !== []) {
                    $agendaReplicada = $this->enviarDatosReplicaAgenda($datosReplica);
                    // dd($agendaReplicada,$idAgenda);
                    $cont++;
                    //if ($agendaReplicada) {
                    //dd($agendaReplicada);
                    $agendaRep = Agenda::find($idAgenda);
                    $agendaRep->replicado = 1;
                    $agendaRep->id_usuario_anula = 200;
                    $agendaRep->save();
                    // Agenda::where('id', $idAgenda)->update(['replicado' => true]);
                    //dd(Agenda::where('id', $idAgenda)->update(['replicado' => true]));
                    //}
                }
            } catch (\Throwable $th) {
                //throw $th;
                dd($th);
            }
        }
        dd($cont);*/
    }

    public function enviarDatosReplicaAgenda($data)
    {
        //dd($data);
        $url = LinkServicio::where('nombre_servicio', 'REPLICA AGENDA SIGHO1')->first();
        $urlSigho1 = $url->link_servicio;
        $respuesta = Http::post($urlSigho1, $data);
        //$id_user_created = auth()->user()->id;
        $replica = new RespuestaApisService();
        $replica->guardarRespuesta(
            $data,
            $respuesta,
            "SIGHO V1",
            "CONSULTA EXTERNA - SIGHO V1",
            "PLATAFORMA - SIGHO V2 - AGENDA",
            1
        );
        $validacionExito = $respuesta->status() === 200 ? true : false;
        return $validacionExito;
        /*$this->respuestaInsercion = $respuesta->json();
        return $this->respuestaInsercion;*/
    }

    public function redirectToForm()
    {
        return redirect()->route('administracion.zonas.create');
    }

    public function mostrarZona($zonaId)
    {
        try {
            $zona = Zona::find($zonaId);
            $this->zonaEdit = $zona->nombre;
            $idMunicipio = $zona->id_municipio;
            $municipio = Municipio::find($idMunicipio);
            $this->municipioEdit = $municipio->nombre;
            $idProvincia = $municipio->id_provincia;
            $provincia = Provincia::find($idProvincia);
            $this->provinciaEdit = $provincia->nombre;
            $idDepartamento = $provincia->id_departamento;
            $departamento = Departamento::find($idDepartamento);
            $this->departamentoEdit = $departamento->nombre;
        } catch (\Throwable $th) {
            $this->idZona = '';
            $this->departamentoEdit = '';
            $this->provinciaEdit = '';
            $this->municipioEdit = '';
            $this->zonaEdit = '';
        }
    }

    public function actualizarZona()
    {
        try {
            if ($this->zonaEdit != '') {
                $zonaActualizar = Zona::find($this->idZona);
                $zonaActualizar->nombre = $this->zonaEdit;
                $zonaActualizar->id_user_updated = auth()->user()->id;
                $zonaActualizar->save();
                $this->dispatch('zonaActualizada');
                $this->dispatch('pg:eventRefresh-ZonasRegistro');
            } else {
                $this->dispatch('datoIncompleto');
            }
        } catch (\Throwable $th) {
            $this->dispatch('zonaNoActualizada');
        }
    }

    #[On('editarZonas')]
    public function editarZonas($data)
    {
        $this->idZona = $data;
        $this->dispatch('abrirModalEditar');
    }

    #[On('habilitar')]
    public function habilitar($rowId)
    {
        try {
            $zona = Zona::find($rowId);
            if ($zona) {
                $estadoActual = $zona->estado;

                $zona->estado = !$estadoActual;
                $zona->save();
                $this->dispatch('pg:eventRefresh-ZonasRegistro');
                $this->dispatch('ZonaHabilitada');
            }
        } catch (\Throwable $th) {
            $this->dispatch('ZonaNoActualizada');
        }
    }

    #[On('deshabilitar')]
    public function deshabilitar($rowId)
    {
        try {
            $zona = Zona::find($rowId);
            if ($zona) {
                $estadoActual = $zona->estado;
                $zona->estado = !$estadoActual;
                $zona->save();
                $this->dispatch('pg:eventRefresh-ZonasRegistro');
                $this->dispatch('ZonaDeshabilitada');
            }
        } catch (\Throwable $th) {
            $this->dispatch('ZonaNoActualizada');
        }
    }
}
