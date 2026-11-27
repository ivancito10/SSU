<?php

namespace App\Livewire\Afiliacion\Beneficiarios;

use App\Models\Administracion\TipoAsegurado;
use App\Models\Aportes\Institucion;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;

use Livewire\Component;

class BeneficiariosReportes extends Component
{

    #[Validate]
    public $tipo_reporte;
    #[Validate]
    public $fecha_inicio, $fecha_fin;
    #[Validate]
    public $id_institucion, $id_tipo_asegurado;
    public $instituciones, $tipos_asegurados;


    public function render()
    {
        return view('livewire.Afiliacion.beneficiarios.beneficiarios-reportes');
    }

    public function mount()
    {
        $this->instituciones = Institucion::where('estado', true)
            ->where('nombre', '!=', 'UNIVERSIDAD MAYOR DE SAN ANDRES')
            ->where(function ($q) {
                $q->where(function ($sub) {
                    $sub->where('nombre', 'like', 'UNIVERSIDAD CATOLICA BOLIVIANA SAN PABLO%')
                        ->where('nombre', 'not like', '%-%');
                })
                    ->orWhere('nombre', 'not like', 'UNIVERSIDAD CATOLICA BOLIVIANA SAN PABLO%');
            })
            ->orderBy('nombre', 'asc')
            ->get();

        $this->tipos_asegurados = TipoAsegurado::whereIn('id', [2, 3, 4, 5, 6, 7, 12, 13])
            ->orderBy('tipo_asegurado', 'asc')
            ->get();
    }

    public function rules()
    {
        return [
            'tipo_reporte' => 'required',
            'id_institucion' => 'required_if:tipo_reporte,institucion',
            'id_tipo_asegurado' => 'required_if:tipo_reporte,tipoBeneficiarios',
            'fecha_inicio' => 'required|date| before_or_equal:fecha_fin',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ];
    }

    #[On('generarReporteBeneficiarios')]
    public function generarReporteBeneficiarios()
    {
        $this->validate();
        try {
            if ($this->tipo_reporte == 'institucion') {
                $institucion = $this->id_institucion;
            } else {
                $institucion = 0;
            }

            if ($this->tipo_reporte == 'tipoBeneficiarios') {
                $id_tipo_asegurado = $this->id_tipo_asegurado;
            } else {
                $id_tipo_asegurado = 0;
            }

            $url = route('pdf.reportesBeneficiarios', [
                'tipo_reporte' => $this->tipo_reporte,
                'id_institucion' => $institucion,
                'id_tipo_asegurado' => $id_tipo_asegurado,
                'fecha_inicio' => $this->fecha_inicio,
                'fecha_fin' => $this->fecha_fin,
            ]);

            $this->dispatch('reporteGeneradoExitosoBen');
            $this->dispatch('cerrarmodalBeneficiariosReportes');
            $this->js("window.location.href = '{$url}'");
            $this->resetVariablesBen();
        } catch (\Throwable $e) {
            $this->dispatch('ErrorGeneralBen');

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

    public function resetVariablesBen()
    {
        $this->reset(
            'tipo_reporte',
            'id_institucion',
            'id_tipo_asegurado',
            'fecha_inicio',
            'fecha_fin',
        );
    }
}
