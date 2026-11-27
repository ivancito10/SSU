<?php

namespace App\Livewire\Plataforma\Horarios;

use App\Models\Plataforma\Horario;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Livewire\Component;

class HorariosRegister extends Component
{
    public $idHorario;
    public $hora_inicio, $hora_fin, $intervalo, $numero_paciente, $estado, $turno;
    protected $listeners = ['registrarHorario'];

    public function render()
    {
        return view('livewire.Plataforma.horarios.horarios-register');
    }

    // evento para abrir modal del horario
    public function abrirModalDeHorario()
    {
        $this->hora_inicio = '';
        $this->hora_fin = '';
        $this->estado = '';
        $this->intervalo = '';
        $this->numero_paciente = '';
        $this->turno = '';
        $this->dispatch('abrirModalHorario');
    }

    // para CALCULAR  el numero de pacientes
    public function numeroPacientes()
    {
        if ($this->hora_inicio && $this->hora_fin && $this->intervalo) {
            try {
                $horaInicio = Carbon::createFromFormat('H:i', $this->hora_inicio);
                $horaFin = Carbon::createFromFormat('H:i', $this->hora_fin);

                if ($horaInicio->gt($horaFin)) {
                    $this->numero_paciente = 0;
                } else {
                    $diferenciaEnMinutos = $horaFin->diffInMinutes($horaInicio);
                    $this->numero_paciente = intdiv($diferenciaEnMinutos, $this->intervalo);
                }
            } catch (\Exception $e) {
                $this->numero_paciente = 0;
            }
        } else {
            $this->numero_paciente = 0;
        }
        $this->skipRender();
    }

    // para determinar el numero 
    public function asignarTurno()
    {
        // Determinar la parte del día
        if (Carbon::parse($this->hora_inicio)->format('H') >= 5 && Carbon::parse($this->hora_inicio)->format('H') < 12) {
            $this->turno = 'MAÑANA';
        } elseif (Carbon::parse($this->hora_inicio)->format('H') >= 12 && Carbon::parse($this->hora_inicio)->format('H') < 18) {
            $this->turno = 'TARDE';
        } else {
            $this->turno = 'NOCHE';
        }
    }

    // funcion para crear el  horario
    public function registrarHorario()
    {
        $this->numeroPacientes();
        try {
            if (Carbon::parse($this->hora_fin)->format('H:i') > Carbon::parse($this->hora_inicio)->format('H:i')) {
                // Verificar si ya existe un horario con los mismos parámetros
                $existeHorario = Horario::where([
                    ['hora_inicio', '=', $this->hora_inicio],
                    ['hora_fin', '=', $this->hora_fin],
                    ['estado', '=', true],
                    ['intervalo', '=', $this->intervalo],
                    ['turno', '=', $this->turno],
                    ['numero_paciente', '=', $this->numero_paciente]
                ])->exists();

                if (!$existeHorario) {
                    Horario::create([
                        'hora_inicio' => $this->hora_inicio,
                        'hora_fin' => $this->hora_fin,
                        'estado' => true,
                        'turno' => $this->turno,
                        'intervalo' => $this->intervalo,
                        'numero_paciente' => $this->numero_paciente,
                        'id_user_created' => auth()->user()->id,
                    ]);

                    // Disparar eventos
                    $this->dispatch('pg:eventRefresh-HorariosTable');
                    $this->dispatch('horarioRegistrado');
                } else {
                    $this->yaExisteHorario();
                }
            } else {
                $this->dispatch('horarioMayor');
                // session()->flash('error', 'La hora de fin debe ser mayor que la hora de inicio.');
            }
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error en registrarHorario", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
            $this->dispatch('horarioNoRegistrado');
        }
    }

    public function yaExisteHorario()
    {
        $this->dispatch('horarioExiste');
    }
}
