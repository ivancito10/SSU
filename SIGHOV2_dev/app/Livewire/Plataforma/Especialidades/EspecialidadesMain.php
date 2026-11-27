<?php

namespace App\Livewire\Plataforma\Especialidades;

use Livewire\Component;
use App\Models\Administracion\Especialidad;
use App\Models\Plataforma\EspecialidadHabilitadoServicio;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On;


class EspecialidadesMain extends Component
{
    public $idEsp, $idServicio;
    public $especialidad, $sigla, $fecha_registro, $estado;
    protected $listeners = ['save', 'actualizar', 'habilitarServicio', 'deshabilitarServicio', 'actualizarEstado'];

    public $serviciosSelected = [];
    public $serviciosLista = [];
    public $asignacion = [];

    public $especialidadImagenologia;

    /*
    public function rules()
    {
      return [
      'especialidad' => 'required|min:5',
      'sigla' => 'required',
      'especialidad_ver'=> 'required',
      'sigla_ver' => 'required',

      
      ];
    }*/

    public function mount(){
        $this->especialidadImagenologia = false;
    }

    public function render()
    {
        return view('livewire.Plataforma.especialidades.especialidades-main');
    }

    public function abrirModal()
    {
        $this->reset(['especialidad', 'sigla']);
        $this->dispatch('modalAgregarEspecialidad');
    }


    // verEspecialidad
    #[On('verEspecialidad')]
    public function verEspecialidad($data)
    {
        $this->idEsp = $data;
        $this->mostrarEspecialidad($this->idEsp);
        $this->serviciosLista = $this->mostrarServiciosLista($this->idEsp);
        $this->dispatch('modalVerEspecialidad');
    }

    public function mostrarEspecialidad($id)
    {
        if ($id) {
            $esp = Especialidad::find($id);
            $this->especialidad = $esp->especialidad;
            $this->sigla = $esp->sigla;
            $this->fecha_registro = Carbon::parse($esp->created_at)->locale('es')->translatedFormat('d \d\e F \d\e Y');
            $this->estado = $esp->estado;
            $this->especialidadImagenologia = $esp->especialidad_imagenologia;
            return $esp;
        }
    }

    //para la parte de motrar las especialidades
    public function mostrarServiciosLista($idEsp)
    {
        if ($idEsp) {
            $asignacion = EspecialidadHabilitadoServicio::where('id_especialidad', $this->idEsp)
                ->with('servicioEspecialidadHabilitadoServicio')
                ->where('estado', true)
                ->get();
            return $asignacion;
        } else {
            $this->serviciosLista = []; // Asegúrate de limpiar la lista si no hay datos
        }
    }

    public function mostrarServicios($idEsp)
    {
        if ($idEsp) {
            $this->asignacion = EspecialidadHabilitadoServicio::where('id_especialidad', $this->idEsp)
                ->join('plataforma.servicios_plataforma', 'plataforma.especialidad_habilitado_servicio.id_servicio', '=', 'servicios_plataforma.id')
                ->select('especialidad_habilitado_servicio.*', 'servicios_plataforma.nombre_servicio as nombreServicio', 'servicios_plataforma.sigla as siglaServicio')
                ->get();
            // dd($this->asignacion);
            return $this->asignacion;
        }
    }

    // editarEspecialidad
    #[On('editarEspecialidad')]
    public function editarEspecialidad($data)
    {
        $this->idEsp = $data;
        $this->mostrarEspecialidad($this->idEsp);
        $this->dispatch('modalEditarEspecialidad');
    }

    public function actualizar()
    {
        try {
            // Verificar si los campos 'especialidad' y 'sigla' tienen datos válidos
            if (!empty($this->especialidad) && !empty($this->sigla)) {

                // Verificar si la especialidad con la sigla ya existe
                $existeEspecialidad = Especialidad::where('especialidad', trim($this->especialidad))
                    ->where('sigla', $this->sigla)
                    ->where('estado', true)
                    ->first();
                //dd($existeEspecialidad->especialidad_imagenologia, $this->especialidadImagenologia);
                // Si no existe una especialidad similar, proceder con la actualización
                //dd($existeEspecialidad->especialidad_imagenologia, $existeEspecialidad->especialidad_imagenologia !== $this->especialidadImagenologia, $this->especialidadImagenologia);
                if (!$existeEspecialidad || $existeEspecialidad->especialidad_imagenologia !== $this->especialidadImagenologia) {
                    $esp = Especialidad::find($this->idEsp);
                    if ($esp) {
                        // Actualizar los campos de la especialidad
                        $esp->especialidad = strtoupper($this->especialidad);
                        $esp->sigla = strtoupper($this->sigla);
                        $esp->especialidad_imagenologia = $this->especialidadImagenologia;
                        $esp->id_user_updated = auth()->user()->id;
                        $esp->save();

                        // Notificar éxito y actualizar la tabla
                        $this->dispatch('pg:eventRefresh-EspecialidadesTable');
                        $this->dispatch('especialidadActualizada');
                    } else {
                        // Si no se encuentra la especialidad por el ID
                        $this->dispatch('especialidadNoEncontrada');
                    }
                } else {
                    // Si ya existe una especialidad similar
                    $this->dispatch('especialidadYaExiste');
                }
            } else {
                // Si no se introdujeron datos validos
                $this->reset(['especialidad', 'sigla']);
                $this->dispatch('noDatosIntroducidos');
            }
            $this->reset(['especialidad', 'sigla_ver']);
        } catch (\Throwable $e) {
            // En caso de error, notificar y limpiar campos
            $this->reset(['especialidad', 'sigla']);
            $this->dispatch('errorActualizar');

            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("error al Actualizar especialista", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }


    /*
    public function save()
    {
        if($this->especialidad != null || $this->especialidad != ''){
            if($this->sigla != null || $this->sigla != ''){
         
                $esp = Especialidad::create([
                    'especialidad' => $this->especialidad,
                    'sigla' => $this->sigla,
                    'estado' => true,
                    'id_user_created' => auth()->user()->id,
                ]);
        
                $espId = $esp->id;
        
                $this->especialidad = null;
                $this->sigla = null;
                $this->dispatch('pg:eventRefresh-EspecialidadesTable');
                $this->dispatch('guardado');
            } else {
                $this->especialidad = null;
                $this->sigla = null;
                $this->dispatch('noDatosIntroducidos');
            }
        } else {
            $this->especialidad = null;
            $this->sigla = null;
            $this->dispatch('noDatosIntroducidos');
        }        
    }*/

    public function save()
    {
        //dd($this->especialidadImagenologia);
        try {
            // Verificar si los campos 'especialidad' y 'sigla' tienen datos válidos
            if (!empty($this->especialidad) && !empty($this->sigla)) {

                // Verificar si la especialidad con la sigla ya existe
                $existeEspecialidad = Especialidad::where('especialidad', trim($this->especialidad))
                    ->exists();

                if (!$existeEspecialidad) {
                    // Si no existe, crear una nueva especialidad
                    $esp = Especialidad::create([
                        'especialidad' => strtoupper($this->especialidad),
                        'sigla' => strtoupper($this->sigla),
                        'estado' => true,
                        'id_user_created' => auth()->user()->id,
                        'especialidad_imagenologia' => $this->especialidadImagenologia,
                    ]);

                    // Limpiar los campos después de guardar
                    $this->reset(['especialidad', 'sigla']);
                    $this->especialidadImagenologia = false;
                    // Notificar éxito y actualizar la tabla
                    $this->dispatch('pg:eventRefresh-EspecialidadesTable');
                    $this->dispatch('especialidadGuardado');
                } else {
                    // Si ya existe, notificar que no se puede guardar
                    $this->reset(['especialidad', 'sigla']);
                    $this->dispatch('especialidadYaExiste');
                }
            } else {
                // Notificar si no se introdujeron datos válidos
                $this->reset(['especialidad', 'sigla']);
                $this->dispatch('noDatosIntroducidos');
            }
        } catch (\Throwable $e) {
            // En caso de error, notificar y limpiar campos
            $this->reset(['especialidad', 'sigla']);
            $this->dispatch('errorGuardar');

            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("error al Actualizar especialista", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);

        }
    }

    // Cambiar estado 
    #[On('cambiarEstado')]
    public function cambiarEstado($data)
    {
        $this->idEsp = $data;
        $esp = Especialidad::find($this->idEsp);
        if ($esp) {
            $this->estado = $esp->estado;
        }
        $this->dispatch('confirmarCambioEstado');
    }

    public function actualizarEstado($confirmado = null)
    {
        if ($confirmado) {
            $esp = Especialidad::find($this->idEsp);
            if ($esp) {
                $estadoActual = $esp->estado;
                $esp->estado = !$estadoActual;
                $esp->save();
                $this->dispatch('pg:eventRefresh-EspecialidadesTable');
            }
        }
    }

    // --------para ver servicio a especialidad
    #[On('verServicio')]
    public function verServicio($data)
    {
        $this->idEsp = $data;
        if ($this->idEsp) {
            $this->serviciosSelected = $this->mostrarServicios($this->idEsp);
        } else {
            $this->serviciosSelected = [];
        }
        $this->dispatch('modalVerAsignacionEspecialidad');
    }

    // para habilitar un servicio
    public function habilitarServicio($dato)
    {
        //dd($dato, $id_especialidad, $id_servicio);

        $servicioActual = EspecialidadHabilitadoServicio::find($dato);
        //dd($servicioActual);
        if ($servicioActual) {
            $servicioActual->estado = false;
            $servicioActual->id_user_updated = auth()->user()->id;
            $servicioActual->save();
            $this->dispatch('servicioDeshabilitado');
        }
    }

    // para deshabilitar un servicio 
    public function deshabilitarServicio($dato, $id_especialidad, $id_servicio)
    {
        $existeOtroServicioHabilitado = EspecialidadHabilitadoServicio::where('id_especialidad', $id_especialidad)
            ->where('id_servicio', $id_servicio)
            ->where('estado', true)
            ->first();

        if (!$existeOtroServicioHabilitado) {
            $servicioActual = EspecialidadHabilitadoServicio::find($dato);
            if ($servicioActual) {
                $this->servicioActual->$servicioActual->estado = true;
                $servicioActual->id_user_updated = auth()->user()->id;
                $servicioActual->save();
                $this->dispatch('servicioHabilitado');
            }
        } else {
            $this->dispatch('servicioHabilitadoError');
        }
    }
}
