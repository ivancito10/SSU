<?php

namespace App\Livewire\Plataforma\Especialidades\EspecialidadServicio;

use Livewire\Component;
use App\Models\Administracion\Especialidad;
use App\Models\Plataforma\ServicioPlataforma;
use App\Models\Plataforma\EspecialidadHabilitadoServicio;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On;
use DB;

class EspecialidadServicioMain extends Component
{
    public $idEsp;
    public $servicios;
    public $especialidad, $sigla, $fecha_registro, $estado;
    #[Validate]
    public $fecha_inicio, $fecha_fin, $estadoServicio, $permanenteServicio;
    public  $permanente = false;
    public $serviciosSelected = [];
    public $asignacion = [];
    #[Validate]
    public $id_servicio;
    protected $listeners = ['asignarServicioEspecialidad', 'guardarServicioEspecialidad', 'habilitarServicioActual'];

    public function rules()
    {
        return [
            'fecha_inicio' => $this->permanente ? 'nullable|date' : 'required|date',
            'fecha_fin' => $this->permanente ? 'nullable|date' : 'required|date',
            'id_servicio' => 'required',
        ];
    }

    public function render()
    {
        $this->serviciosSelected = $this->mostrarServicios($this->idEsp)
            ->sortByDesc('id')
            ->values();
        return view('livewire.Plataforma.especialidades.especialidad-servicio.especialidad-servicio-main');
    }

    public function mount()
    {
        $this->mostrarEspecialidad($this->idEsp);
        $this->servicios = ServicioPlataforma::all();
    }

    public function mostrarEspecialidad($id)
    {
        if ($id) {
            $esp = Especialidad::find($id);
            $this->especialidad = $esp->especialidad;
            $this->sigla = $esp->sigla;
            $this->fecha_registro = $esp->created_at;
            $this->estado = $esp->estado;
        }
    }

    //- ------------- para registrar servicio 
    public function asignarServicioEspecialidadOriginal()
    {
        $this->validate();
        $existeServicio = EspecialidadHabilitadoServicio::where('id_servicio', $this->id_servicio)
            ->where('id_especialidad', $this->idEsp)
            ->where('permanente', $this->permanente)
            ->first();

        if ($existeServicio) {
            if ($existeServicio->estado == true) {
                $this->dispatch('servicioExiste');
            } else {
                if ($this->permanente == true && $existeServicio->permanente == true) {
                    $existeServicio->estado = true;
                    $existeServicio->id_user_updated = auth()->user()->id;
                    $existeServicio->save();

                    $existeOtroServicio = EspecialidadHabilitadoServicio::where('id_servicio', $this->id_servicio)
                        ->where('id_especialidad', $this->idEsp)
                        ->where('permanente', !$this->permanente)
                        ->where('estado', true)
                        ->first();

                    if ($existeOtroServicio) {
                        $existeOtroServicio->estado = false;
                        $existeOtroServicio->id_user_updated = auth()->user()->id;
                        $existeOtroServicio->save();
                    }

                    $this->dispatch('registroExitoso');
                } elseif ($this->permanente == false && $existeServicio->permanente == false) {
                    $existeServicio->fecha_inicio = $this->fecha_inicio;
                    $existeServicio->fecha_fin = $this->fecha_fin;
                    $existeServicio->estado = true;
                    $existeServicio->id_user_updated = auth()->user()->id;
                    $existeServicio->save();
                    $this->dispatch('registroExitoso');

                    $existeOtroServicio = EspecialidadHabilitadoServicio::where('id_servicio', $this->id_servicio)
                        ->where('id_especialidad', $this->idEsp)
                        ->where('permanente', !$this->permanente)
                        ->where('estado', true)
                        ->first();

                    if ($existeOtroServicio) {
                        $existeOtroServicio->estado = false;
                        $existeOtroServicio->id_user_updated = auth()->user()->id;
                        $existeOtroServicio->save();
                    }
                }
            }
        } else {

            $existeAlgunOtroServicio = EspecialidadHabilitadoServicio::where('id_servicio', $this->id_servicio)
                ->where('id_especialidad', $this->idEsp)
                ->where('estado', true)
                ->first();

            if ($existeAlgunOtroServicio) {
                $existeAlgunOtroServicio->estado = false;
                $existeAlgunOtroServicio->id_user_updated = auth()->user()->id;
                $existeAlgunOtroServicio->save();
            }

            EspecialidadHabilitadoServicio::create([
                'fecha_inicio' => $this->fecha_inicio,
                'fecha_fin' => $this->fecha_fin,
                'permanente' => $this->permanente,
                'estado' => true,
                'id_servicio' => $this->id_servicio,
                'id_especialidad' => $this->idEsp,
                'id_user_created' => auth()->user()->id,
            ]);

            $this->dispatch('guardarAsignacionServicio');
        }
    }

    public function asignarServicioEspecialidad()
    {
        $this->validate();
        $this->dispatch('guardarAsignacionServicio');
    }

    public function guardarServicioEspecialidad($confirmado = null)
    {
        $contador = 0;
        if ($this->id_servicio == 4) {
            foreach ($this->servicios->slice(0, -1) as $servicio) {
                $existeServicioPermanente = EspecialidadHabilitadoServicio::where('id_servicio', $servicio->id)
                    ->where('id_especialidad', $this->idEsp)
                    ->where('permanente', true)
                    ->first();
                $existeServicioTemporal = EspecialidadHabilitadoServicio::where('id_servicio', $servicio->id)
                    ->where('id_especialidad', $this->idEsp)
                    ->where('permanente', false)
                    ->where('estado', true)
                    ->first();
                //dd($existeServicioPermanente, $existeServicioTemporal);
                if ($existeServicioPermanente && $this->permanente) {
                    if (!$existeServicioPermanente->estado && ($existeServicioTemporal == null || !$existeServicioTemporal->estado)) {
                        $existeServicioPermanente->estado = true;
                        $existeServicioPermanente->id_user_updated = auth()->user()->id;
                        $existeServicioPermanente->save();
                    }
                } elseif ($existeServicioTemporal && !$this->permanente) {
                    $existeServicioTemporal->estado = true;
                    $existeServicioTemporal->fecha_inicio = $this->fecha_inicio;
                    $existeServicioTemporal->fecha_fin = $this->fecha_fin;
                    $existeServicioTemporal->id_user_updated = auth()->user()->id;
                    $existeServicioTemporal->save();
                } elseif ($existeServicioPermanente == null || !$existeServicioPermanente->estado) {
                    EspecialidadHabilitadoServicio::create([
                        'fecha_inicio' => $this->permanente ? null : $this->fecha_inicio,
                        'fecha_fin' => $this->permanente ? null : $this->fecha_fin,
                        'permanente' => $this->permanente,
                        'estado' => true,
                        'id_servicio' => $servicio->id,
                        'id_especialidad' => $this->idEsp,
                        'id_user_created' => auth()->user()->id,
                    ]);
                } else {
                    $contador++;
                }
            }
            if ($contador == 3) {
                $this->dispatch('servicioExistente');
            } else {
                $this->dispatch('registroExitoso');
            }
        } else {
            $existeServicio = EspecialidadHabilitadoServicio::where('id_servicio', $this->id_servicio)
                ->where('id_especialidad', $this->idEsp)
                ->where('estado', true)
                ->first();

            if ($existeServicio) {
                $this->permanenteServicio = $existeServicio->permanente;
                $this->dispatch('servicioExiste');
            } else {
                if ($confirmado) {
                    $existeServicioPermanente = EspecialidadHabilitadoServicio::where('id_servicio', $this->id_servicio)
                        ->where('id_especialidad', $this->idEsp)
                        ->where('permanente', true)
                        ->first();

                    if ($existeServicioPermanente && $this->permanente) {
                        $existeServicioPermanente->estado = true;
                        $existeServicioPermanente->id_user_updated = auth()->user()->id;
                        $existeServicioPermanente->save();
                    } else {
                        EspecialidadHabilitadoServicio::create([
                            'fecha_inicio' => $this->permanente ? null : $this->fecha_inicio,
                            'fecha_fin' => $this->permanente ? null : $this->fecha_fin,
                            'permanente' => $this->permanente,
                            'estado' => true,
                            'id_servicio' => $this->id_servicio,
                            'id_especialidad' => $this->idEsp,
                            'id_user_created' => auth()->user()->id,
                        ]);
                    }
                    $this->dispatch('registroExitoso');
                }
            }
        }
    }

    public function cancelarServicio()
    {
        return redirect()->route('plataforma.especialidades.index');
    }

    ///--- para la parte de motrar las especialidades
    public function mostrarServicios($idEsp)
    {
        if ($idEsp) {
            $asignacion = EspecialidadHabilitadoServicio::where('id_especialidad', $this->idEsp)
                ->where('estado', true)
                ->with('servicioEspecialidadHabilitadoServicio')
                ->get();
            // dd($this->asignacion);
            return $asignacion;
        }
    }

    public $idServicioSeleccionado;
    // para habilitar un servicio
    public function habilitarServicio($dato)
    {
        $this->idServicioSeleccionado = $dato;
        $this->dispatch('deshabilitarServicioSeleccionado');
    }

    public function habilitarServicioActual($confirmado = null)
    {
        if ($confirmado) {
            $servicioActual = EspecialidadHabilitadoServicio::find($this->idServicioSeleccionado);
            if ($servicioActual) {
                $servicioActual->estado = false;
                $servicioActual->id_user_updated = auth()->user()->id;
                $servicioActual->save();
            }
        }
    }
    // para deshabilitar un servicio 
    public function deshabilitarServicio($dato, $id_servicio)
    {
        //dd($this->idEsp, $dato, $id_servicio);
        $existeOtroServicioHabilitado = EspecialidadHabilitadoServicio::where('id_especialidad', $this->especialidad)
            ->where('id_servicio', $id_servicio)
            ->where('estado', true)
            ->first();

        if (!$existeOtroServicioHabilitado) {
            $servicioActual = EspecialidadHabilitadoServicio::find($dato);
            if ($servicioActual) {
                $servicioActual->estado = true;
                $servicioActual->id_user_updated = auth()->user()->id;
                $servicioActual->save();
                $this->dispatch('servicioHabilitado');
            }
        } else {
            $this->dispatch('servicioHabilitadoError');
        }
    }
}
