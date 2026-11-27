<?php

namespace App\Livewire\Afiliacion\Titulares;

use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Attributes\Validate;

use App\Models\Aportes\Institucion;


class TitularesReportes extends Component
{
    #[Validate]
    public $tipo_reporte;
    #[Validate]
    public $fecha_inicio, $fecha_fin;
    #[Validate]
    public $id_institucion;
    public $instituciones;
    public function render()
    {
        return view('livewire.Afiliacion.titulares.titulares-reportes');
    }

    public function updatedTipoReporte()
    {
        //dd('sdf');
        if ($this->tipo_reporte == 'institucion') {
            $this->instituciones = Institucion::where('estado', true)
                ->where('nombre', '!=', 'UNIVERSIDAD MAYOR DE SAN ANDRES')
                ->where(function ($q) {
                    $q->where(function ($sub) {
                        $sub->where('nombre', 'like', 'UNIVERSIDAD CATOLICA BOLIVIANA SAN PABLO%')
                            ->where('nombre', 'not like', '%-%');
                    })
                        ->orWhere('nombre', 'not like', 'UNIVERSIDAD CATOLICA BOLIVIANA SAN PABLO%');
                })
                // ->with(['codigosInstitucion', 'claseAportanteInstitucion', 'institucionResidencia'])
                ->orderBy('nombre', 'asc')
                ->get();
        }
    }

    public function rules()
    {
        return [
            'tipo_reporte' => 'required',
            'id_institucion' => 'required_if:tipo_reporte,institucion',
            'fecha_inicio' => 'required|date| before_or_equal:fecha_fin',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ];
    }

    #[On('generarReporte')]
    public function generarReporte()
    {

        $this->validate();
        try {
            if ($this->tipo_reporte == 'institucion') {
                $institucion = $this->id_institucion;
            } else {
                $institucion = 0;
            }

            $url = route('pdf.reportesTitular', [
                'id_institucion' => $institucion,
                'tipo_reporte' => $this->tipo_reporte,
                'fecha_inicio' => $this->fecha_inicio,
                'fecha_fin' => $this->fecha_fin,
            ]);

            /*   return redirect()->route('pdf.reportesTitular', [
                'id_institucion' => $institucion,
                'tipo_reporte' => $this->tipo_reporte,
                'fecha_inicio' => $this->fecha_inicio,
                'fecha_fin' => $this->fecha_fin,
            ]); */

            $this->dispatch('reporteGeneradoExitoso');
            $this->dispatch('cerrarmodalTitularesReportes');
            
            //$this->js("window.open('{$url}', '_blank');");

            $this->js("window.location.href = '{$url}'");
            $this->resetVariables();
        } catch (\Throwable $e) {
            $this->dispatch('ErrorGeneral');

            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error('Afiliacion:error al generar reporte', [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR' => $e->getMessage() ?? '',
                'ARCHIVO' => $trace['file'] ?? $e->getFile(),
                'LINEA' => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);

            return;
        }
    }

    public function resetVariables()
    {
        $this->reset(
            'tipo_reporte',
            'id_institucion',
            'fecha_inicio',
            'fecha_fin',
        );
    }
}
