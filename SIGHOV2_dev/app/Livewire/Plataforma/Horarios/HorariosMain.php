<?php

namespace App\Livewire\Plataforma\Horarios;

use App\Models\Plataforma\Horario;

use Livewire\Attributes\On;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class HorariosMain extends Component
{
    public $idHorario;
    public $hora_inicio, $hora_fin, $intervalo, $numero_paciente, $estado, $turno;
    protected $listeners = ['editHorario'];

    public function render()
    {
        return view('livewire.Plataforma.horarios.horarios-main');
    }
    // para ver los datos del horario
    public function mostrarHorario($idHorario)
    {
        if ($this->idHorario) {
            $horario = Horario::find($idHorario);
            $this->hora_inicio = Carbon::parse($horario->hora_inicio)->format('H:i');
            $this->hora_fin = Carbon::parse($horario->hora_fin)->format('H:i');
            $this->intervalo = $horario->intervalo;
            $this->estado = $horario->estado;
            $this->numero_paciente = $horario->numero_paciente;
            $this->turno = $horario->turno;
        }
    }
    // para CALCULAR  el numero de pacientes
    public function calcularNumeroPacientes()
    {
        try {
            $inicio = new \DateTime($this->hora_inicio);
            $fin = new \DateTime($this->hora_fin);
            $intervaloMinutos = $this->intervalo;
            if ($inicio >= $fin) {
                $numeroPacientes = 0;
            } else {
                // Calculamos el número de pacientes
                $intervaloTotal = $fin->diff($inicio);
                $minutosTotales = ($intervaloTotal->h * 60) + $intervaloTotal->i;
                $numeroPacientes = intval($minutosTotales / $intervaloMinutos);
            }
            $this->numero_paciente = $numeroPacientes;
            $this->skipRender();
        } catch (\Throwable $e) {
            $this->dispatch('horarioNoRegistrado');
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al calcularNumeroPacientes", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }
    // para editar el horario
    public function editHorario()
    {
        try {
            $existeHorario = Horario::where('hora_inicio', $this->hora_inicio)
                ->where('hora_fin', $this->hora_fin)
                ->where('intervalo', $this->intervalo)
                ->first();

            if (!$existeHorario) {
                $horarioActual = Horario::find($this->idHorario);
                if ($horarioActual) {
                    $horarioActual->hora_inicio = $this->hora_inicio;
                    $horarioActual->hora_fin = $this->hora_fin;
                    $horarioActual->intervalo = $this->intervalo;
                    $horarioActual->numero_paciente = $this->numero_paciente;
                    $horarioActual->turno = $this->turno;

                    $horarioActual->id_user_updated = auth()->user()->id;
                    $horarioActual->save();
                    $this->dispatch('horarioEditado');
                    $this->dispatch('pg:eventRefresh-HorariosTable');
                }
            } else {
                $this->dispatch('horarioEditadoDuplicado');
            }
        } catch (\Throwable $e) {
            $this->dispatch('horarioNoRegistrado');

            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error en al editar horario", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }


    // para escuchar del table 
    #[On('verHorario')]
    public function verHorario($data)
    {
        $this->idHorario = $data;
        $this->mostrarHorario($data);
        $this->dispatch('modalVerHorario');
    }

    // para eeditar
    #[On('editarHorario')]
    public function editarHorario($data)
    {
        $this->idHorario = $data;
        $this->mostrarHorario($data);
        $this->dispatch('modalEditarHorario');
    }

    // Cambiar estado 
    #[On('cambiarEstadoHorario')]
    public function cambiarEstadoHorario($data)
    {
        //dd("llega");
        $this->idHorario = $data;
        $esp = Horario::find($this->idHorario);
        if ($esp) {
            $this->estado = $esp->estado;
        }
        $this->dispatch('confirmarCambioEstado');
    }

    #[On('actualizarEstado')]
    public function actualizarEstado($confirmado = null)
    {
        try {
            if ($confirmado) {
                $esp = Horario::find($this->idHorario);
                if ($esp) {
                    $estadoActual = $esp->estado;
                    $esp->estado = !$estadoActual;
                    $esp->save();
                    $this->dispatch('pg:eventRefresh-HorariosTable');
                }
            }
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error en actualizarEstado", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    // Cambiar estado 
    // #[On('cambiarEstadoHorario')]
    // public function cambiarEstadoHorario($data)
    // {
    //     $horario = Horario::find($data);
    //     if ($horario) {
    //         $estadoActual = $horario->estado;
    //         //dd($estadoActual);
    //         $horario->estado = !$estadoActual;
    //         $horario->save();

    //         $this->dispatch('pg:eventRefresh-HorariosTable');
    //         if ($horario->estado == true) {
    //             $this->dispatch('activo');
    //         } else {
    //             $this->dispatch('inactivo');
    //         }
    //     }
    // }


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

    public function asignarNuevoTurnoycalcularNumeroPacientes()
    {
        $this->asignarTurno();
        $this->calcularNumeroPacientes();
    }
}
