<?php

namespace App\Livewire\Plataforma\AdministracionFeriados;

use App\Models\Plataforma\AdministracionFeriados;
use App\Services\VencimientoCarnet;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Plataforma\Dia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AdministracionFeriadosMain extends Component
{
    public $dia, $descripcion, $estado, $fecha, $dias, $id_dia;
    public $id_feriado;

    public function render()
    {
        return view('livewire.Plataforma.administracion-feriados.administracion-feriados-main');
    }

    public function mount()
    {
        // $this->VacacionPasada();
        //$carnetsVencidos = new VencimientoCarnet();
        //dd($carnetsVencidos->verificarVencimientoCarnet(15485, '2025-06-27'));

    }

    #[On('VacacionPasada')]
    public function VacacionPasada()
    {
        $vacacionesActualizar = AdministracionFeriados::get();
        foreach ($vacacionesActualizar as $vacacion) {
            // $fechaActualizar = $vacacionesActualizar->pluck('fecha')->toArray();
            if ($vacacion->fecha < now()) {
                $vacacion->update([
                    'estado' => false,
                ]);
            }
        }
    }

    #[On('verFeriado')]
    public function verFeriado($data)
    {
        $this->mostrarFeriado($data);
        $this->dispatch('abrirModalVerFeriados');
    }

    #[On('editarFeriado')]
    public function editarFeriado($data)
    {
        $this->id_feriado = $data;
        $this->dispatch('abrirmodalEditarFeriados');
        $this->mostrarFeriado($data);
    }

    #[On('cambiarEstadoFeriado')]
    public function cambiarEstadoFeriado($data)
    {
        $this->id_feriado = $data;
        $feriado = AdministracionFeriados::find($data);
        if ($feriado) {
            $this->dispatch($feriado->estado ? 'preguntaInhabilitarEstadoFeriado' : 'preguntaHabilitarEstadoFeriado');
        }
    }

    #[On('guardarCambioEstadoFeriado')]
    public function guardarCambioEstadoFeriado()
    {
        try {
            $feriado = AdministracionFeriados::find($this->id_feriado);
            if ($feriado) {
                $feriado->estado = !$feriado->estado;
                $feriado->id_user_updated = auth()->user()->id;
                $feriado->save();
                $this->dispatch('estadoCambiado');
                $this->dispatch('pg:eventRefresh-FeriadosTable');
            }
        } catch (\Throwable $e) {
            $this->dispatch('estadoNoCambiado');

            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al cambiar estado", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    public function mostrarFeriado($id)
    {
        $feriado = AdministracionFeriados::find($id);
        if ($feriado) {
            $this->dia = $feriado->diaAdministracionFeriados?->nombre;
            $this->fecha = $feriado->fecha;
            $this->dia = $feriado->nombre_dia;
            $this->descripcion = $feriado->descripcion;
            $this->estado = $feriado->estado;
        }
    }

    #[On('obtenerNombreDia')]
    public function obtenerNombreDia()
    {
        return $this->dia = $this->obtenerDatosDias($this->fecha)['nombre_dia'];
    }

    public function obtenerDatosDias($fecha = null)
    {
        Carbon::setLocale('es');
        $fechaFormatear =  Carbon::parse($fecha);
        return [
            'nombre_dia' => mb_strtoupper($fechaFormatear->translatedFormat('l'), 'UTF-8'),
            'nro_dia' => $fechaFormatear->isoFormat('E'),
        ];
    }

    #[On('modificarFeriado')]
    public function modificarFeriado()
    {
        try {
            $feriado = AdministracionFeriados::where('id', $this->id_feriado)
                ->first();
            $existeFeriado = AdministracionFeriados::where('fecha', $this->fecha)
                ->where('descripcion', $this->descripcion)
                ->exists();
            if ($existeFeriado) {
                $this->dispatch('feriadoYaExiste');
                return;
            }
            if ($feriado) {
                if (!empty($this->fecha)  && !empty($this->descripcion)) {
                    $feriado->fecha = $this->fecha;
                    $feriado->nro_dia =  $this->obtenerDatosDias($this->fecha)['nro_dia'];
                    $feriado->nombre_dia = $this->obtenerDatosDias($this->fecha)['nombre_dia'];
                    $feriado->id_user_updated = auth()->user()->id;
                    $feriado->descripcion = strtoupper($this->descripcion);
                    $feriado->save();
                    $this->dispatch('feriadoEditado');
                    $this->dispatch('pg:eventRefresh-FeriadosTable');
                } else {
                    $this->dispatch('faltaDatos');
                }
            }
        } catch (\Throwable $e) {
            $this->dispatch('feriadooNoRegistrado');

            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al obtener modificar feriado", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }
}
