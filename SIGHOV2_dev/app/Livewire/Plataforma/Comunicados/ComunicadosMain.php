<?php

namespace App\Livewire\Plataforma\Comunicados;

use App\Models\Administracion\Comunicado;
use Livewire\Component;
use App\Services\ListadoUnidades;
use Livewire\Attributes\On;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ComunicadosMain extends Component
{
    public $permanente, $fecha_inicio, $fecha_fin, $mensaje, $id_unidad, $nombre_unidad;
    public $permanenteMostrar, $estadoMostrar, $fecha_inicio_mostrar, $fecha_fin_mostrar, $nombre_unidad_mostrar;
    public $id_comunicado, $estado, $mensaje_texto;
    public $unidades = [];
    public function render()
    {
        return view('livewire.Plataforma.comunicados.comunicados-main');
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
        // dd($this->unidades);
    }
    public function obtenerNombreUnidad($dato)
    {
        $unidadSeleccionada = collect($this->unidades)
            ->where('id', $dato)
            ->first();
        return $unidadSeleccionada['nombre'] ?? null;
    }

    #[On('verComunicado')]
    public function verComunicado($data)
    {
        $this->mostrarComunicado($data);
        $this->dispatch('abrirModalVerComunidados');
    }

    public function mostrarComunicado($dato)
    {   
        try{
            $comunicado = Comunicado::find($dato);
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
        }catch(\Throwable $e){
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al mostrar comunicado", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    #[On('cambiarEstado')]
    public function cambiarEstado($data)
    {
        $this->id_comunicado = $data;
        $comunicado = Comunicado::find($data);
        if ($comunicado) {
            $this->estado = $comunicado->estado;
        }
        $this->dispatch('cambiarEstadoComunicado');
    }
    #[On('actualizarEstado')]
    public function actualizarEstado($confirmado = null)
    {
        if ($confirmado) {
            $comunicado = Comunicado::find($this->id_comunicado);
            if ($comunicado) {
                $estadoActual = $comunicado->estado;
                $comunicado->estado = !$estadoActual;
                $comunicado->save();
                $this->dispatch('pg:eventRefresh-ComunicadosTable');
            }
        }
    }
}
