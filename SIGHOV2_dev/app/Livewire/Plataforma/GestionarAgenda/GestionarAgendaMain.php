<?php

namespace App\Livewire\Plataforma\GestionarAgenda;

use App\Models\Plataforma\CantidadHorario;
use App\Models\Plataforma\DiasHabilitadosAgenda;
use Livewire\Component;
use App\Models\Plataforma\ServicioPlataforma;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;

class GestionarAgendaMain extends Component
{
    public $servicios, $nro_dias, $estado, $servicioNombre, $hoy, $hoy_boolean, $id_servicio;
    public $id_gestion_agenda, $cantidad_turnos;
    public $turnos = [];
    public $hora_inicio, $hora_fin, $porcentaje;
    public function render()
    {
        return view('livewire.Plataforma.gestionar-agenda.gestionar-agenda-main');
    }
    public function mount()
    {
        $this->servicios = ServicioPlataforma::orderBy('id', 'asc')->get();
    }
    public function registrarGestionAgenda()
    {
        return redirect()->route('plataforma.gestionar_agenda.create');
    }

    #[On('verGestionAgenda')]
    public function verGestionAgenda($data)
    {
        $this->id_gestion_agenda = $data;
        $this->dispatch('abrirModalVerGestionarAgenda');
        $this->mostrarGestionAgenda();
    }
    public function mostrarGestionAgenda()
    {
        $gestionAgenda = DiasHabilitadosAgenda::with('servicioPlataforma')->find($this->id_gestion_agenda);
        if ($gestionAgenda) {
            $this->id_servicio = $gestionAgenda->id_servicio_plataforma;
            $this->servicioNombre = $gestionAgenda->servicioPlataforma->nombre_servicio;
            $this->nro_dias = $gestionAgenda->nro_dias;
            $this->hoy_boolean = $gestionAgenda->hoy;
            $this->hoy = $gestionAgenda->hoy ? 'HOY' : 'MAÑANA';
            $this->estado = $gestionAgenda->estado;
            $this->cantidad_turnos = $gestionAgenda->cantidad_turnos;
            $this->turnos = CantidadHorario::where('id_dias_habilitados_agenda', $gestionAgenda->id)
                // ->where('estado', true)
                ->where('eliminado', false)
                ->orderBy('id', 'asc')
                ->get();
            //dd($turnos);
        }
    }

    // Cambiar estado 
    #[On('cambiarEstadoGestionAgenda')]
    public function cambiarEstadoGestionAgenda($data)
    {
        $this->id_gestion_agenda = $data;
        $gestionServicios = DiasHabilitadosAgenda::find($this->id_gestion_agenda);
        if ($gestionServicios) {
            $this->estado = $gestionServicios->estado;
        }
        $this->dispatch('confirmarCambioEstadoGestionAgenda');
    }

    #[On('actualizarEstadoGestionAgenda')]
    public function actualizarEstadoGestionAgenda($confirmado = null)
    {
        try {
            if ($confirmado) {
                $gestionServicios = DiasHabilitadosAgenda::find($this->id_gestion_agenda);
                if ($gestionServicios) {
                    $estadoActual = $gestionServicios->estado;
                    $gestionServicios->estado = !$estadoActual;
                    $gestionServicios->save();
                    $cantidadHorario = CantidadHorario::where('id_dias_habilitados_agenda', $gestionServicios->id)
                        ->get();
                    foreach ($cantidadHorario as $horario) {
                        $horario->estado = !$estadoActual;
                        $horario->save();
                    }
                    $this->dispatch('pg:eventRefresh-GestionAgendaTable');
                }
            }
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error en actualizarEstadoGestionAgenda", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }
}
