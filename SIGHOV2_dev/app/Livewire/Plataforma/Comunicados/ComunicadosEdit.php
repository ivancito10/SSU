<?php

namespace App\Livewire\Plataforma\Comunicados;

use Livewire\Component;
use App\Models\Administracion\Comunicado;
use App\Services\ListadoUnidades;
use Livewire\Attributes\On;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ComunicadosEdit extends Component
{
    public $permanente, $fecha_inicio, $fecha_fin, $mensaje, $id_unidad, $nombre_unidad;
    public $permanenteMostrar, $estadoMostrar, $fecha_inicio_mostrar, $fecha_fin_mostrar, $nombre_unidad_mostrar;
    public $id_comunicado, $estado, $mensaje_texto;
    public $unidades = [];
    public function render()
    {
        return view('livewire.Plataforma.comunicados.comunicados-edit');
    }
    public function mount()
    {
        $this->obtenerUnidades();
        $this->mostrarComunicado();
        $this->id_comunicado = $this->id_comunicado;
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
            })->toArray();
        // dd($this->unidades);
    }
    public function obtenerNombreUnidad($dato)
    {
        $unidadSeleccionada = collect($this->unidades)
            ->where('id', $dato)
            ->first();
        return $unidadSeleccionada['nombre'] ?? null;
    }

    public function mostrarComunicado()
    {
        $comunicado = Comunicado::find($this->id_comunicado);
        if ($comunicado) {
            $this->permanenteMostrar = $comunicado->permanente ?  'PERMANENTE' : 'TEMPORAL';
            $this->estadoMostrar = $comunicado->estado;
            $this->fecha_inicio = $comunicado->fecha_inicio;
            $this->fecha_fin = $comunicado->fecha_fin;
            $this->mensaje = $comunicado->mensaje;
            $this->mensaje_texto = $comunicado->mensaje;
            $this->nombre_unidad = $comunicado->nombre_unidad;
            $this->id_unidad = $comunicado->id_unidad;
            $this->permanente = $comunicado->permanente;
        }
    }

    #[On('editandoComunicado')]
    public function editandoComunicado($mensajeEditado)
    {
        $this->mensaje = $mensajeEditado;
        try {
            if (!empty($this->id_unidad) && !empty($this->mensaje)) {
                if ($this->permanente) {
                    if ($this->fecha_fin) {
                        $this->dispatch('borrarFechaFin');
                        return;
                    }
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
                $comunicado = Comunicado::find($this->id_comunicado);
                if ($comunicado) {
                    $comunicado->mensaje = $this->mensaje;
                    $comunicado->permanente = $this->permanente;
                    $comunicado->fecha_inicio = $this->fecha_inicio;
                    $comunicado->id_unidad =   $this->id_unidad;
                    $comunicado->nombre_unidad = $this->obtenerNombreUnidad($this->id_unidad);
                    $comunicado->fecha_fin = $this->fecha_fin ?: null;
                    $comunicado->save();
                    $this->dispatch('comunicadoEditado');
                    $this->dispatch('pg:eventRefresh-ComunicadosTable');
                }

                $this->resetearVariables();
                return redirect()->route('plataforma.comunicados.index');
            } else {
                $this->dispatch('sinnDAtos');
            }
        } catch(\Throwable $e){
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al editar comunicado", [
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
        $this->id_unidad = null;
        $this->nombre_unidad = null;
    }
    public function atras()
    {
        return redirect()->route('plataforma.comunicados.index');
    }

    public function cancelarEdicionComunicado()
    {
        return redirect()->route('plataforma.comunicados.index');
    }
}
