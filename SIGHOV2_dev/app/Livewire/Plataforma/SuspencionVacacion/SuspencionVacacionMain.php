<?php

namespace App\Livewire\Plataforma\SuspencionVacacion;

use App\Models\Plataforma\Especialista;
use App\Models\Plataforma\SuspensionVacacion;
use App\Models\Administracion\Especialidad;
use App\Models\Administracion\Departamento;

use App\Models\Administracion\Persona;
use App\Models\Plataforma\AsignacionHorario;
use App\Models\Plataforma\Agenda;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\On;

class SuspencionVacacionMain extends Component
{
    public $idEsp;
    public $fotografia, $permanente;
    public $nombres, $ci, $complemento, $matricula, $expedido, $sexo,
        $es_extranjero, $fecha_nacimiento;
    public $doc_especialidad, $grado, $fecha_fin, $fecha_inicio;
    public $fechaInicioV, $fechaFinV, $estadoV, $observacionV, $nro_vacacion;
    public $vacaciones = [];
    public $vacaciones_esp = [];
    public $fecha_inicio_vacacion, $fecha_fin_vacacion;
    public $estadoEspecialista;

    protected $listeners = ['registrarVacacion', 'guardarAnulacionVacacion'];
    public function render()
    {
        return view('livewire.Plataforma.suspencion-vacacion.suspencion-vacacion-main');
    }

    public function mount()
    {
        $this->actualizarVacacionesVencidas();
    }

    // actualizar vacacion a vencida ,
    public function actualizarVacacionesVencidas()
    {
        $hoy = now()->format('Y-m-d');
        // Seleccionamos todas las vacaciones activas que ya han vencido
        $vacacionesVencidas = SuspensionVacacion::where('fecha_fin', '<', $hoy)
            ->where('estado', 'VACACION ACTIVA')
            ->get();
        foreach ($vacacionesVencidas as $vacacion) {
            // Actualiza todos los activos a vencidos, pero no los anulados
            $vacacion->estado = 'VACACION VENCIDA';
            $vacacion->save();
        }
    }

    // para mostrar los datos del especalista
    public function mostrarEspecialista($idEsp)
    {
        if ($idEsp) {
            $esp = Especialista::find($idEsp);

            if ($esp) {
                $this->fecha_inicio = $esp->fecha_contrato_inicio;
                $this->fecha_fin = $esp->fecha_contrato_fin;
                $this->permanente = $esp->permanente;
                $this->grado = $esp->grado_academico;
                $this->fotografia = $this->obtenerImagenBase64($esp->foto);
                $id_especialidad = $esp->id_especialidad;
                $id_persona = $esp->id_persona;

                $persona = Persona::find($id_persona);
                $this->nombres = $persona->nombres . ' ' . $persona->p_apellido . ' ' .
                    $persona->s_apellido;
                $this->matricula = $persona->matricula_seguro;
                // $this->ci = $persona->ci;
                $this->complemento = $persona->complemento;
                if (is_null($this->complemento)) {
                    $this->ci = $persona->ci;
                } else {
                    $this->ci = $persona->ci . ' - ' . $persona->complemento;
                }
                $this->es_extranjero = $persona->es_extranjero;

                $this->sexo = $persona->sexo;
                $this->fecha_nacimiento = $persona->fecha_nacimiento;
                $id_dep_exp = $persona->id_dept_exp;

                $departamento = Departamento::find($id_dep_exp);
                if ($departamento) {
                    $this->expedido = $departamento->sigla;
                } else {
                    $this->expedido = '';
                }

                $this->estadoEspecialista = $esp->estado;

                $especialidad = Especialidad::find($id_especialidad);
                $this->doc_especialidad = $especialidad->especialidad;
            }
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

            Log::error("Error en obtenerImagenBase64", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
            return null;
        }
    }
    // -----para registrar la vacacion

    public function registrarVacacion()
    {
        // Validación inicial para campos obligatorios
        if (empty($this->fecha_inicio_vacacion) || empty($this->fecha_fin_vacacion) || empty($this->nro_vacacion)) {
            $this->dispatch('errorRegistroVacacion');
            $this->resetearRegistroVacacion();
            return;
        }
        DB::beginTransaction();
        try {
            $vacacion = SuspensionVacacion::create([
                'fecha_inicio' => $this->fecha_inicio_vacacion,
                'fecha_fin' => $this->fecha_fin_vacacion,
                'nro_registro_vacacion_rrhh' => $this->nro_vacacion,
                'estado' => 'VACACION ACTIVA',
                'id_especialista' => $this->idEsp,
                'id_user_created' => auth()->user()->id,
            ]);
            $this->resetearRegistroVacacion();
            DB::commit();
            $this->dispatch('registroExitosoVacacion');
            $this->dispatch('pg:eventRefresh-VacacionEspecialistaTable');
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->dispatch('errorRegistroVacacion');

            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error en registrarVacacion", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    public function resetearRegistroVacacion()
    {
        $this->fecha_inicio_vacacion = '';
        $this->fecha_fin_vacacion = '';
        $this->nro_vacacion = '';
    }

    //---- apra que los modals abran
    // agregar vacacion
    #[On('AgregarSuspencion')]
    public function AgregarSuspencion($data)
    {
        $this->idEsp = $data;
        $comprobarEspecialista = Especialista::find($this->idEsp);
        if ($comprobarEspecialista) {
            if ($comprobarEspecialista->estado) {
                $this->mostrarEspecialista($this->idEsp);
                $this->dispatch('modalAgregarHorario');
            } else {
                $this->dispatch('epecialistaNoHabilitado');
            }
        }
    }
    // Ver Vacacion
    #[On('verVacaciones')]
    public function verVacaciones($data)
    {
        $this->idEsp = $data;
        $this->mostrarEspecialista($this->idEsp);
        if ($this->idEsp) {
            $this->vacaciones_esp = $this->mostrarVacacion($this->idEsp);
        } else {
            $this->vacaciones_esp = [];
        }
        $this->dispatch('modalVerVacacion');
    }

    //--- para mostrar la vacacion
    public function mostrarVacacion($idEsp)
    {
        if ($idEsp) {
            $this->vacaciones = SuspensionVacacion::where('id_especialista', $this->idEsp)
                ->select('suspension_vacacion.*')
                ->orderBy('id', 'desc')
                ->get();
            // dd($this->vacaciones);
            return $this->vacaciones;
        }
    }

    // editarEspecialista
    #[On('AnularSuspencion')]
    public function AnularSuspencion($data)
    {
        $this->idEsp = $data;
        $this->mostrarEspecialista($this->idEsp);
        $this->mostrarUltimaVacacion($this->idEsp);

        $this->dispatch('modalAnulacionVacacion');
    }
    public $nroU, $finVacacionU, $inicioVacacionU, $estadoU;
    public $observacionU;

    public function mostrarUltimaVacacion($idEsp)
    {
        $ultimaVacacion = SuspensionVacacion::where('id_especialista', $this->idEsp)
            ->orderBy('created_at', 'desc')
            ->first();
        $this->nroU = $ultimaVacacion->nro_registro_vacacion_rrhh;
        $this->inicioVacacionU = $ultimaVacacion->fecha_inicio;
        $this->finVacacionU = $ultimaVacacion->fecha_fin;
        $this->estadoU = $ultimaVacacion->estado;
    }

    public function guardarAnulacionVacacion()
    {
        $ultimaVacacion = SuspensionVacacion::where('id_especialista', $this->idEsp)
            ->orderBy('created_at', 'desc')
            ->first();
        if ($ultimaVacacion) {
            $ultimaVacacion->estado = 'VACACION ANULADA';
            $ultimaVacacion->observacion = $this->observacionU;
            $ultimaVacacion->id_user_anulacion = auth()->user()->id;
            $ultimaVacacion->save();

            $this->dispatch('pg:eventRefresh-VacacionEspecialistaTable');
        }
    }
    // FUNCTION PARA MOSTRAR LOS AGENDADOS
    public $tiene_agendas, $agenda, $count = 0;

    public function verAgendados($data)
    {
        $this->resetearDatosAgenda();
        $id_especialista = $data[0];
        $fecha_inicio = $data[1];
        $fecha_fin = $data[2];
        $this->mostrarAgendas($id_especialista, $fecha_inicio, $fecha_fin);
        $this->dispatch('modalVerAgendados');
        $this->dispatch('verAgendadosEspecialista', id_especialista: $id_especialista, fecha_inicio: $fecha_inicio, fecha_fin: $fecha_fin);
        $this->skipRender();
    }

    public function mostrarAgendas($id_especialista, $fecha_inicio, $fecha_fin)
    {   
        try{
            $idEspecialista = $id_especialista;
            $fechaInicio = $fecha_inicio;
            $fechaFin = $fecha_fin;
            $this->tiene_agendas = false;
            $asignacionHorario = AsignacionHorario::where('id_especialista', $idEspecialista)
                ->get();
            if ($asignacionHorario->isNotEmpty()) {
                // Extraer todos los IDs en un array
                $ids_asignacionHorario = $asignacionHorario->pluck('id')->toArray();
                // dd($ids_asignacionHorario);
                $existe_agenda = Agenda::whereIn('id_asignacion_horario', $ids_asignacionHorario)
                    ->whereBetween('fecha_agenda', [$fechaInicio, $fechaFin])
                    ->where('fecha_anulacion', null)
                    ->get();
                if ($existe_agenda->isNotEmpty()) {
                    $ids_agenda = $existe_agenda->pluck('id')->toArray();
                    if ($ids_agenda) {
                        $this->agenda = Agenda::with(['personaAgenda', 'asignacionHorarioAgenda', 'servicioPlataformaAgenda'])
                            ->whereIn('id', $ids_agenda)
                            ->get();
                        $this->tiene_agendas = true;
                    } else {
                        $this->tiene_agendas = false;
                    }
                } else {
                    $this->tiene_agendas = false;
                }
            } else {
                $this->tiene_agendas = false;
            }
        }catch(\Throwable $e){
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al mostrar agendas", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
        

        //dd($this->agenda);
    }

    public function resetearDatosAgenda()
    {
        $this->reset('agenda');
    }

    #[On('editarVacacion')]
    public function editarVacacion($data)
    {
        $this->idEsp = $data;
        $this->mostrarEspecialista($this->idEsp);
        $this->mostrarUltimaVacacion($this->idEsp);

        $this->dispatch('abrirModalEditarVacacion');
    }

    #[On('guardarEdicionVacacion')]
    public function guardarEdicionVacacion()
    {
        $ultimaVacacion = SuspensionVacacion::where('id_especialista', $this->idEsp)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($ultimaVacacion) {
            try {
                $ultimaVacacion->fecha_fin = $this->finVacacionU;
                $ultimaVacacion->id_user_updated = auth()->user()->id;
                $ultimaVacacion->save();
                $this->dispatch('edicionVacacionExitosa');
                
            } catch (\Throwable $e) {
                $this->dispatch('edicionVacacionErronea');

                $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

                Log::error("Error en guardarEdicionVacacion", [
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
