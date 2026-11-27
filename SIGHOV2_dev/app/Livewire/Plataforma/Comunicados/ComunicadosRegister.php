<?php

namespace App\Livewire\Plataforma\Comunicados;

use App\Services\ListadoUnidades;
use App\Models\Plataforma\Comunicados;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\On;

class ComunicadosRegister extends Component
{
    public $permanente_registrar = false, $fecha_inicio, $fecha_fin, $id_unidad, $nombre_unidad;
    public $unidades = [];
    public $mensaje_texto, $mensaje;

    public function render()
    {
        return view('livewire.Plataforma.comunicados.comunicados-register');
    }
    public function abrirModalDeComunicados()
    {
        $this->dispatch('abrirModalComunicados');
    }
    public function mount()
    {
        $this->obtenerUnidades();
    }
    public function obtenerUnidades()
    {
        $datosUnidades = new ListadoUnidades();
        $this->unidades = collect($datosUnidades->listadoUnidades())
            ->map(function ($item) {
                return [
                    'id' => $item['id'],
                    'nombre' => $item['nombre']
                ];
            })
            ->sortBy('nombre') // ordena ascendentemente por nombre
            ->values()          // reindexa los elementos (opcional pero recomendable)
            ->toArray();
        //dd($this->unidades);
    }
    public function obtenerNombreUnidad($dato)
    {
        $unidadSeleccionada = collect($this->unidades)
            ->where('id', $dato)
            ->first();
        return $unidadSeleccionada['nombre'] ?? null;
    }
    #[On('registrarComunicado')]
    public function registrarComunicado($mensaje)
    {
        $this->mensaje = $mensaje;
        try {
            if (!empty($this->id_unidad) && !empty($this->mensaje)) {
                if ($this->permanente_registrar) {
                    if (!$this->fecha_inicio) {
                        $this->dispatch('permanenteSinFechaIni');
                        return;
                    }
                } else {
                    if (!$this->fecha_inicio || !$this->fecha_fin) {
                        $this->dispatch('temporalSinFechas');
                        return;
                    }
                    if ($this->fecha_fin < $this->fecha_inicio) {
                        $this->dispatch('fechaFinalMenor');
                        return;
                    }
                }
                $com =  Comunicados::create([
                    'permanente' => $this->permanente_registrar,
                    'fecha_inicio' => $this->fecha_inicio,
                    'fecha_fin' => $this->fecha_fin,
                    'estado' => true,
                    'mensaje' => $this->mensaje,
                    'id_unidad' => $this->id_unidad,
                    'nombre_unidad' => $this->obtenerNombreUnidad($this->id_unidad),
                    'id_user_created' => auth()->user()->id,
                ]);
                $this->dispatch('comunicadoRegistrado');
                $this->dispatch('pg:eventRefresh-ComunicadosTable');
                $this->resetearVariables();
            } else {
                $this->dispatch('sinnDAtos');
            }
        } catch (\Throwable $e) {
            $this->dispatch('NoRegistrado');

            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al registrar comunicado", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }
    public function resetearVariables()
    {
        $this->fecha_inicio = null;
        $this->fecha_fin = null;
        $this->mensaje = '';
        $this->mensaje_texto = '';
        $this->id_unidad = null;
        $this->nombre_unidad = null;
    }
}
