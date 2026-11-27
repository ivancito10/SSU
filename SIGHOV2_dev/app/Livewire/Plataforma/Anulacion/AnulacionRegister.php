<?php

namespace App\Livewire\Plataforma\Anulacion;

use App\Models\Administracion\Persona;
use App\Models\Plataforma\Agenda;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Estudiante;
use App\Models\Plataforma\AsignacionHorario;
use App\Models\Plataforma\Especialista;
use App\Models\Plataforma\Horario;
use App\Models\Vigencia\Convenio;
use App\Models\Vigencia\AutorizacionInterior;
use App\Models\Administracion\Especialidad;
use App\Models\Administracion\TipoAsegurado;
use Illuminate\Support\Facades\Log;
use App\Services\ListadoAsegurados;
use App\Services\ReplicarAgendaS1;
use App\Services\SocketService;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Carbon\Carbon;
use Livewire\Attributes\Validate;
use Livewire\Component;

class AnulacionRegister extends Component
{
    public $idAgenda;

    //DATOS PERRSONA
    public $foto;
    public $estado_vigencia;
    public $edad;
    public $matricula;
    public $nombre;
    public $carnet;
    public $sexo;
    public $fecha_nacimiento;
    public $codigoInstitucional;
    public $nombreInsPatronal;
    public $tipoAsegurado;

    //DATOS AGENDA
    public $fechaAgenda;
    public $horaAgenda;
    public $especialidad;
    public $turno;
    public $especialista;
    public $servicioPlataforma;
    public $nroConsultorio;
    public $piso;
    public $ubicacion;
    public $ip;
    public $usuarioCreador;
    public $createdAtFecha;
    public $createdAtHora;


    public function render()
    {
        return view('livewire.Plataforma.anulacion.anulacion-register');
    }

    public function mount()
    {
        //dd($this->idAgenda);
        $this->datosAgenda($this->idAgenda);
    }

    public function datosAgenda($idAgenda)
    {   
        //try{
            $datosAgenda = Agenda::with([
                'servicioPlataformaAgenda',
                'usuarioCreador',
                'asignacionHorarioAgenda.consultorioAsignacionHorario.consultorioSedes.residenciaSedes.zonaResidencia',
                'asignacionHorarioAgenda.especialistaAsignacionHorario.especialidadEspecialista',
                'asignacionHorarioAgenda.especialistaAsignacionHorario.personaEspecialista',
                'asignacionHorarioAgenda.horarioAsignacionHorario'
            ])
                ->find($idAgenda);

            if ($datosAgenda) {
                $datos = new ListadoAsegurados();
                $persona = $datos->busquedaAfiliadoPorIdParaBajaInstitucionalNuevo($datosAgenda->id_persona);

                $this->foto = $persona['foto'];
                $this->estado_vigencia = $persona['estado_vigencia'];
                $this->edad = Carbon::parse($persona['fecha_nacimiento'])->age;
                $this->matricula = $persona['matricula_seguro'];
                $this->nombre = implode(' ', array_filter([$persona['p_apellido'], $persona['s_apellido'], $persona['nombres']]));
                $this->carnet = implode(' - ', array_filter([$persona['ci'], $persona['complemento']]));
                $this->sexo = $persona['sexo'] === 'F' ? 'FEMENINO' : 'MASCULINO';
                $this->fecha_nacimiento = Carbon::parse($persona['fecha_nacimiento'])->format('d/m/Y');
                $this->codigoInstitucional = $persona['instituciones'][0]['codigo_institucional'];
                $this->nombreInsPatronal = $persona['instituciones'];
                $this->tipoAsegurado = $persona['tipo_asegurado'];

                $this->fechaAgenda = Carbon::parse($datosAgenda->fecha_agenda)->format('d/m/Y');
                $this->horaAgenda = Carbon::parse($datosAgenda->hora_agenda)->format('H:i');
                $this->especialidad = $datosAgenda->asignacionHorarioAgenda->especialistaAsignacionHorario->especialidadEspecialista->especialidad;
                $this->turno = $datosAgenda->asignacionHorarioAgenda->horarioAsignacionHorario->turno;

                $gradoAcademico = $datosAgenda->asignacionHorarioAgenda->especialistaAsignacionHorario->grado_academico === 'DOCTORA'
                    ? 'DRA.'
                    : ($datosAgenda->asignacionHorarioAgenda->especialistaAsignacionHorario->grado_academico === 'DOCTOR' ? 'DR.' : 'LIC.');

                $nombreEspecialista = implode(' ', array_filter([
                    $datosAgenda->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->p_apellido,
                    $datosAgenda->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->s_apellido,
                    $datosAgenda->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->nombres
                ]));

                $this->especialista = $gradoAcademico . ' ' . $nombreEspecialista;

                $this->servicioPlataforma = $datosAgenda->servicioPlataformaAgenda->nombre_servicio;
                $this->nroConsultorio = $datosAgenda->asignacionHorarioAgenda->consultorioAsignacionHorario->numero_consultorio;
                $this->piso = $datosAgenda->asignacionHorarioAgenda->consultorioAsignacionHorario->consultorioSedes->piso;
                $this->ubicacion = $datosAgenda->asignacionHorarioAgenda->consultorioAsignacionHorario->consultorioSedes->ubicacion;
                $this->ip = $datosAgenda->ip;
                $this->usuarioCreador = $datosAgenda->usuarioCreador->name;
                $this->createdAtFecha = Carbon::parse($datosAgenda->created_at)->format('d/m/Y');
                $this->createdAtHora = Carbon::parse($datosAgenda->created_at)->format('H:i');
            }
        /*}catch(\Throwable $e){
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al obtener datos agenda", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }*/
    }

    #[On('anulacionConfirmada')]
    public function anulacionConfirmada($motivo)
    {
        try{
            $anulacionSighov1 = new ReplicarAgendaS1();
            $socketService = new SocketService();
            if ($motivo !== null || trim($motivo) !== '') {
                $motivo = $motivo;

                $anularAgenda = Agenda::find($this->idAgenda);

                if ($anularAgenda) {
                    $anularAgenda->fecha_anulacion = Carbon::now();
                    $anularAgenda->motivo_anulacion = $motivo;
                    $anularAgenda->anulacion_ficha = true;
                    $anularAgenda->clave_unica = $anularAgenda->clave_unica . '-A-' . Carbon::now()->toTimeString();
                    $anularAgenda->id_usuario_anula = auth()->user()->id;
                    $anularAgenda->id_user_updated = auth()->user()->id;
                    $anularAgenda->save();

                    $this->dispatch('anulacionExitosa');
                    // PARA ACTUALIZAR LA APK EN TIEMPO REAL

                    $socketService->emision_anulacion_socket($anularAgenda);
                    //PARA ANULAR LA AGENDA SIGHO V1
                    $anulacionSighov1->enviarDatosAnulacionAgenda($anularAgenda->id);
                    return redirect()->route('plataforma.anulaciones.index');
                }
            } else {
                $this->dispatch('motivoNoSeleccionado');
            }
        }catch(\Throwable $e){
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al obtener imagen", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    public function volver()
    {
        return redirect()->route('plataforma.anulaciones.selection');
    }
}
