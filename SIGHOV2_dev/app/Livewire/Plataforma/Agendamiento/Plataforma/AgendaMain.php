<?php

namespace App\Livewire\Plataforma\Agendamiento\Plataforma;

use App\Models\Plataforma\Agenda;
use App\Models\Plataforma\AsignacionHorario;
use App\Models\Plataforma\Dia;
use App\Models\Plataforma\Sedes;
use App\Models\Plataforma\Consultorio;
use App\Models\Plataforma\ServicioPlataforma;
use App\Models\Plataforma\AnulacionAgenda;
use App\Models\Plataforma\Especialista;
use App\Models\Administracion\Persona;
use App\Models\Administracion\Especialidad;
use App\Models\User;
use App\Services\BusquedaClaveUnica;
use Livewire\Attributes\On;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;


class AgendaMain extends Component
{
    public $id_agenda;
    public $nombreCompleto, $complemento, $ci, $matricula;
    public $fecha_agenda, $hora_agenda, $servicio, $nombreDia;
    public $nro_consultorio, $piso, $ubicacion;
    public $especialista, $especialidadNom;
    public $institucion;
    public $fechaCreado;
    public $nombreUsuarioCreado;

    public $usuarioCreadorAgenda;
    public $fechaCreacionAgenda;
    public $usuarioAnulaAgenda;
    public $fechaAnulacionAgenda;
    public $usuarioSuspendeAgenda;
    public $fechaSuspensionAgenda;
    public $motivoAnulacionAgenda;
    public $motivoSuspensionAgenda;
    public $nombre_equipo, $ip, $servicio_agenda, $nombre_equipo_anulado, $ip_anulado, $fecha_creacion;

    public function render()
    {
        return view('livewire.Plataforma.agendamiento.plataforma.agenda-main');
    }

    public function redirectAgendamiento()
    {
        //$this->dispatch('lista_asegurados');
        return redirect()->route('plataforma.agendamiento.plataforma.create');
    }

    public function mostrarAgenda($id_agenda)
    {
        try {
            if ($id_agenda) {
                $agenda = Agenda::with(['usuarioCreador', 'usuarioAnula', 'suspensionHorarioAgenda.usuarioCreador', 'suspensionHorarioAgenda.motivoSuspensionHorario'])->find($id_agenda);
                if ($agenda) {
                    $this->fecha_agenda = $agenda->fecha_agenda;
                    $this->hora_agenda = $agenda->hora_agenda;
                    $this->fechaCreado = Carbon::parse($agenda->created_at)->format('d/m/Y H:i:s');

                    $usuarioNombre = User::find($agenda->id_user_created);

                    $this->nombreUsuarioCreado = $usuarioNombre->name;

                    $id_persona = $agenda->id_persona;
                    $persona = Persona::find($id_persona);
                    if ($persona) {
                        $this->nombreCompleto = $persona->nombres . ' ' . $persona->p_apellido  . ' ' . $persona->s_apellido;

                        $this->complemento = $persona->complemento;
                        if (is_null($this->complemento)) {
                            $this->ci = $persona->ci;
                        } else {
                            $this->ci = $persona->ci . '-' . $persona->complemento;
                        }

                        $datosBusqueda = new BusquedaClaveUnica();
                        $datosAfiliado = $datosBusqueda->datosPorClaveUnica($persona->clave_unica);
                        //dd($datosAfiliado);
                        if ($datosAfiliado != []) {
                            $this->institucion = $datosAfiliado['nombre_institucion'];
                            $this->matricula = $persona->matricula_seguro;
                            /*     if ($datosAfiliado['tipo_asegurado'] == 'TITULAR') {
                                if ($datosAfiliado['codigo_titular'] != null) {
                                    $this->matricula = $persona->matricula_seguro . ' - ' . $datosAfiliado['codigo_titular'];
                                } else {
                                    $this->matricula = $persona->matricula_seguro;
                                }
                            } elseif ($datosAfiliado['tipo_asegurado'] == 'PREAFILIADO TITULAR') {
                                if ($datosAfiliado['codigo_titular'] != null) {
                                    $this->matricula = $persona->matricula_seguro . ' - ' . $datosAfiliado['codigo_titular'];
                                } else {
                                    $this->matricula = $persona->matricula_seguro;
                                }
                            } elseif (Str::contains($datosAfiliado['tipo_asegurado'], 'BENEFICIARIO')) {
                                if ($datosAfiliado['codigo_beneficiario'] != null) {
                                    $this->matricula = $persona->matricula_seguro . ' - ' . $datosAfiliado['codigo_beneficiario'];
                                } else {
                                    $this->matricula = $persona->matricula_seguro;
                                }
                            } */
                        }
                    }
                    $servicio_plataforma_id = $agenda->id_servicio_plataforma;
                    $servicioPlataforma = ServicioPlataforma::find($servicio_plataforma_id);
                    if ($servicioPlataforma) {
                        $this->servicio = $servicioPlataforma->nombre_servicio;
                    } else {
                        $this->servicio = 'SERVICIO NO ENCONTRADO';
                    }
                    $asignacion_horario_id = $agenda->id_asignacion_horario;
                    $asignacionHorario = AsignacionHorario::find($asignacion_horario_id);
                    $dia_id = $asignacionHorario->id_dia;
                    $dia = Dia::find($dia_id);
                    $this->nombreDia = $dia->nombre;
                    $consultorio_id = $asignacionHorario->id_consultorio;
                    $consultorio = Consultorio::find($consultorio_id);
                    $this->nro_consultorio = $consultorio->numero_consultorio;
                    $sedes_id = $consultorio->id_sedes;
                    $sedes = Sedes::find($sedes_id);
                    $this->piso = $sedes->piso;
                    $this->ubicacion = $sedes->ubicacion;

                    $especialista_id = $asignacionHorario->id_especialista;
                    $especialista = Especialista::find($especialista_id);
                    $persona_id = $especialista->id_persona;
                    $personaEsp = Persona::find($persona_id);
                    $this->especialista = $personaEsp->nombres . ' ' . $personaEsp->p_apellido . ' ' . $personaEsp->s_apellido;

                    $especialidad_id = $especialista->id_especialidad;
                    $especialidad = Especialidad::find($especialidad_id);
                    $this->especialidadNom = $especialidad->especialidad;

                    //PARA EL SEGUIMIENTO
                    $this->usuarioCreadorAgenda = $agenda->usuarioCreador ? $agenda->usuarioCreador->name : null;
                    //$this->fechaCreacionAgenda = $agenda->created_at ? Carbon::parse($agenda->created_at)->format('d/m/Y H:i:s') : null;
                    $this->fechaCreacionAgenda = $agenda->created_at ?: null;
                    $this->nombre_equipo = $agenda->nombre_equipo;
                    $this->ip =  $agenda->ip;

                    $this->usuarioAnulaAgenda = $agenda->usuarioAnula ? $agenda->usuarioAnula->name : null;
                    //  $this->fechaAnulacionAgenda = $agenda->fecha_anulacion ? Carbon::parse($agenda->fecha_anulacion)->format('d/m/Y H:i:s') : null;
                    $this->fechaAnulacionAgenda = $agenda->fecha_anulacion ?: null;
                    $this->motivoAnulacionAgenda = $agenda->motivo_anulacion ? $agenda->motivo_anulacion : null;
                    $detalle_anulacion = AnulacionAgenda::find($agenda->id);
                    $this->ip_anulado = $detalle_anulacion?->ip ?: 'SIN DATO';
                    $this->nombre_equipo_anulado = $detalle_anulacion?->nombre_equipo ?: 'SIN DATO';


                    $this->usuarioSuspendeAgenda = $agenda->suspensionHorarioAgenda ? $agenda->suspensionHorarioAgenda->usuarioCreador->name : null;
                    $this->fechaSuspensionAgenda = $agenda->suspensionHorarioAgenda ? Carbon::parse($agenda->suspensionHorarioAgenda->fecha_suspension)->format('d/m/Y') : null;
                    $this->motivoSuspensionAgenda = $agenda->suspensionHorarioAgenda ? $agenda->suspensionHorarioAgenda->motivoSuspensionHorario->nombre_motivo : null;
                }
            }
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al mostrar agenda", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }


    #[On('verAgenda')]
    public function verAgenda($data)
    {
        $this->mostrarAgenda($data);
        $this->id_agenda = $data;
        $this->dispatch('modalVerAgendaTabla');
    }

    public function abrirModalReportesAgendas()
    {
        $this->dispatch('modalReportesAgendas');
    }
}
