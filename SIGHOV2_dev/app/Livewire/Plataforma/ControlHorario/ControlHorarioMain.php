<?php

namespace App\Livewire\Plataforma\ControlHorario;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Plataforma\ControlHorario;
use Illuminate\Support\Facades\Log;

class ControlHorarioMain extends Component
{
    public $estadoMostrar, $hora_inicio, $hora_fin, $posicion, $porcentaje, $id_control_horario, $estado;
    public function render()
    {
        return view('livewire.Plataforma.control-horario.control-horario-main');
    }
    #[On('verControlHorario')]
    public function verControlHorario($data)
    {
        $this->dispatch('abrirmodalVerControlHorarios');
        $this->mostrarControlHorario($data);
    }

    public function mostrarControlHorario($data)
    {
        $controlHorario = ControlHorario::find($data);
        if ($controlHorario) {
            $this->porcentaje = $controlHorario->porcentaje;
            $this->posicion = $controlHorario->posicion;
            $this->estadoMostrar = $controlHorario->estado;
            $this->hora_fin = $controlHorario->hora_fin;
            $this->hora_inicio = $controlHorario->hora_inicio;
        }
    }

    #[On('editarControlHorario')]
    public function editarControlHorario($data)
    {
        $this->id_control_horario = $data;
        $this->dispatch('abrirmodalEditarControlHorarios');
        $this->mostrarControlHorario($data);
    }
    #[On('editandoControlHorario')]
    public function editandoControlHorario()
    {
        if ($this->hora_fin <= $this->hora_inicio) {
            $this->dispatch('finMenorInicioEditar');
            return;
        }
        if ($this->porcentaje > 100) {
            $this->dispatch('mayor100Editar');
            return;
        }
        try {
            $existeControlHorario = ControlHorario::where('hora_inicio', $this->hora_inicio)
                ->where('hora_fin', $this->hora_fin)
                ->first();
            if (!empty($this->hora_inicio) && !empty($this->hora_fin) && !empty($this->posicion) && !empty($this->porcentaje)) {
                $controlHorario = ControlHorario::find($this->id_control_horario);
                if (!$existeControlHorario) {
                    if ($controlHorario) {
                        $controlHorario->hora_inicio = $this->hora_inicio;
                        $controlHorario->hora_fin = $this->hora_fin;
                        $controlHorario->porcentaje = $this->porcentaje;
                        $controlHorario->posicion = $this->posicion;
                        $controlHorario->save();
                        $this->dispatch('controlHorarioEditado');
                        $this->dispatch('pg:eventRefresh-ControlHorariosTable');
                    }
                } else {
                    $this->dispatch('controlHorarioExisteEditar');
                }
            } else {
                $this->dispatch('ingresaDatosControlHorarioEditar');
            }
        } catch (\Throwable $e) {
            $this->dispatch('errorCatchControlHorarioEditar');
            
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al editar control horario", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    #[On('cambiarEstadoControlHorario')]
    public function cambiarEstadoControlHorario($data)
    {
        $this->id_control_horario = $data;
        $controlHorario = ControlHorario::find( $this->id_control_horario);
        if ($controlHorario) {
            $this->estado = $controlHorario->estado;
        }
        $this->dispatch('cambiarEstadoControlHorarios');
    }

    #[On('actualizarEstado')]
    public function actualizarEstado($confirmado = null)
    {
        if ($confirmado) {
            $controlHorario = ControlHorario::find($this->id_control_horario);
            if ($controlHorario) {
                $estadoActual = $controlHorario->estado;
                $controlHorario->estado = !$estadoActual;
                $controlHorario->save();
                $this->dispatch('pg:eventRefresh-ControlHorariosTable');
            }
        }
    }
}
