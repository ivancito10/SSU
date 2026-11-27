<?php

namespace App\Livewire\Plataforma\ControlHorario;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Plataforma\ControlHorario;
use Illuminate\Support\Facades\Log;

class ControlHorarioRegister extends Component
{
    public $hora_inicio, $hora_fin, $posicion, $porcentaje;
    public function render()
    {
        return view('livewire.Plataforma.control-horario.control-horario-register');
    }

    public function abrirModalDeControlHorarios()
    {
        $this->dispatch('abrirModalControlHorario');
    }

    #[On('registrarControlHorario')]
    public function registrarControlHorario()
    {
        if ($this->hora_fin <= $this->hora_inicio) {
            $this->dispatch('finMenorInicio');
            return;
        }
        if ($this->porcentaje > 100) {
            $this->dispatch('mayor100');
            return;
        }
        try {
            $existeControlHorario = ControlHorario::where('hora_inicio', $this->hora_inicio)
                ->where('hora_fin', $this->hora_fin)
                ->first();
            if (!$existeControlHorario) {
                if (!empty($this->hora_inicio) && !empty($this->hora_fin) && !empty($this->posicion) && !empty($this->porcentaje)) {
                    $dato = ControlHorario::create([
                        'hora_inicio' => $this->hora_inicio,
                        'hora_fin' => $this->hora_fin,
                        'posicion' => $this->posicion,
                        'porcentaje' => $this->porcentaje,
                        'estado' => 'true',
                        'id_user_created' => auth()->user()->id,
                    ]);
                    $this->reseteoVariables();
                    $this->dispatch('controlHorarioCreado');
                    $this->dispatch('pg:eventRefresh-ControlHorariosTable');
                } else {
                    $this->dispatch('ingresaDatosControlHorario');
                }
            } else {
                $this->dispatch('controlHorarioExiste');
            }
        } catch (\Throwable $e) {
            $this->dispatch('errorCatchControlHorario');
            
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al registrar control horario", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }
    public function reseteoVariables()
    {
        $this->hora_inicio = null;
        $this->hora_fin = null;
        $this->posicion = null;
        $this->porcentaje = null;
    }
}
