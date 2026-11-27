<?php

namespace App\Livewire\Plataforma\SuspencionVacacion;

use App\Models\Plataforma\Agenda;
use App\Models\Plataforma\AsignacionHorario;
use Livewire\Component;
use Livewire\Attributes\On;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SuspensionVacacionVerAgenda extends Component
{
    public $tiene_agendas;
    public $agenda;
    public $fechaInicio;
    public $fechaFin;

    public function render()
    {
        return view('livewire.Plataforma.suspencion-vacacion.suspension-vacacion-ver-agenda');
    }

    #[On('verAgendadosEspecialista')]
    public function verAgendadosEspecialista($id_especialista, $fecha_inicio, $fecha_fin)
    {
        $this->mostrarAgendas($id_especialista, $fecha_inicio, $fecha_fin);
    }

    public function mostrarAgendas($id_especialista, $fecha_inicio, $fecha_fin)
    {
        try {
            $idEspecialista = $id_especialista;
            $this->fechaInicio = Carbon::parse($fecha_inicio)->format('d/m/Y');
            $this->fechaFin = Carbon::parse($fecha_fin)->format('d/m/Y');
            $this->tiene_agendas = false;
            $asignacionHorario = AsignacionHorario::where('id_especialista', $idEspecialista)
                ->get();
            if ($asignacionHorario->isNotEmpty()) {
                // Extraer todos los IDs en un array
                $ids_asignacionHorario = $asignacionHorario->pluck('id')->toArray();
                // dd($ids_asignacionHorario);
                $existe_agenda = Agenda::whereIn('id_asignacion_horario', $ids_asignacionHorario)
                    ->whereBetween('fecha_agenda', [$fecha_inicio, $fecha_fin])
                    ->where('fecha_anulacion', null)
                    ->get();
                if ($existe_agenda->isNotEmpty()) {
                    $ids_agenda = $existe_agenda->pluck('id')->toArray();
                    if ($ids_agenda) {
                        $this->agenda = Agenda::with(['personaAgenda', 'asignacionHorarioAgenda', 'servicioPlataformaAgenda', 'suspensionHorarioAgenda.motivoSuspensionHorario'])
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
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error en mostrarAgendas", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }
}
