<?php

namespace App\Livewire\Afiliacion\EstudiantesSsue;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Log;



class EstudiantesReportes extends Component
{
    #[Validate]
    public $tipo_reporte, $fecha_inicio, $fecha_fin, $estado_validar, $formato_reporte = false;

    public function render()
    {
        return view('livewire.Afiliacion.estudiantes-ssue.estudiantes-reportes');
    }


    public function rules()
    {
        $rules = [
            'tipo_reporte' => 'required',
            'estado_validar' => 'required_if:tipo_reporte,registros',
            'fecha_inicio' => 'nullable|date|before_or_equal:fecha_fin',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        ];
        if ($this->tipo_reporte == 'registros') {
            $rules['fecha_inicio'] .= '|required';
            $rules['fecha_fin'] .= '|required';
        }
        if ($this->tipo_reporte == 'reporte_umsa' && $this->formato_reporte == true) {
            $rules['fecha_inicio'] .= '|required';
            $rules['fecha_fin'] .= '|required';
        }

        return $rules;
    }

    #[On('generarReporteEstudiantes')]
    public function generarReporteEstudiantes()
    {
        $this->validate();
        try {

            if ($this->tipo_reporte == 'reporte_umsa' && $this->formato_reporte == false) {
                $url2 = route('pdf.generateReporteEstudianteUmsa', []);
                $this->js("window.open('{$url2}', '_blank');");
                $this->dispatch('reporteGeneradoExitosoEst');
                $this->dispatch('cerrarmodalReportesEstudiantes');
            } else {
                // dd($this->fecha_inicio, $this->fecha_fin, $this->tipo_reporte, $this->estado_validar);

                $url_general = route('reporte.generateReporteEstudiantes', [
                    'fecha_inicio' => $this->fecha_inicio,
                    'fecha_fin' => $this->fecha_fin,
                    'tipo_reporte' => $this->tipo_reporte,
                    'estado' => $this->estado_validar ?? 'TODOS',
                ]);
                $this->dispatch('reporteGeneradoExitosoEst');
                $this->dispatch('cerrarmodalReportesEstudiantes');
                $this->js("window.location.href = '{$url_general}'");
            }

            // $this->resetearForm();


        } catch (\Throwable $e) {
            $this->dispatch('ErrorGeneralEst');
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
    #[On('generarReporteExcel')]
    public function generarReporteExcel()
    {
        $url2 = route('pdf.generateReporteEstudianteUmsaExcel', []);
        $this->js("window.open('{$url2}', '_blank');");
        $this->dispatch('reporteGeneradoExitoso');
        $this->resetearForm();
    }

    public function resetearForm()
    {
        $this->reset('estado_validar', 'tipo_reporte', 'fecha_inicio', 'fecha_fin');
    }
}
