<?php

namespace App\Livewire\Plataforma\Agendamiento\Plataforma;

use Livewire\Component;
use App\Models\Aportes\Institucion;
use App\Models\Vigencia\SeguroInterior;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

class ReportesAgenda extends Component
{
    #[Validate]
    public $tipo_asegurado, $instituciones = [], $segurosInterior = [];
    public $mostrarListado = false, $esEstudiante = false;
    #[Validate]
    public $fecha_inicio, $fecha_fin, $id_dato_institucion, $id_dato_convenio;


    public function render()
    {
        return view('livewire.Plataforma.agendamiento.plataforma.reportes-agenda');
    }

    public function updatedTipoAsegurado()
    {
        if ($this->tipo_asegurado == 'TITULAR' || $this->tipo_asegurado == 'BENEFICIARIO') {
            $this->mostrarListado =  true;
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
            //dd($instituciones);
        } elseif ($this->tipo_asegurado == 'CONVENIO') {
            $this->mostrarListado =  false;
            $this->segurosInterior = SeguroInterior::orderBy('nombre_seguro', 'asc')->get();
        } else {
            $this->esEstudiante = true;
            $this->mostrarListado =  false;
        }
    }

    public function resetVariables()
    {
        $this->reset(
            'tipo_asegurado',
            'mostrarListado',
            'id_dato_institucion',
            'id_dato_convenio',
            'fecha_inicio',
            'fecha_fin',
            'esEstudiante'
        );
    }

    public function rules()
    {
        return [
            'tipo_asegurado' => 'required',
            //  'id_dato_institucion' => 'required_if:mostrarListado,true',
            //  'id_dato_convenio' => 'required_if:mostrarListado,false',
            'id_dato_institucion' => 'required_if:tipo_asegurado,TITULAR,BENEFICIARIO',
            'id_dato_convenio' => 'required_if:tipo_asegurado,CONVENIO',
            'fecha_inicio' => 'required|date| before_or_equal:fecha_fin',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ];
    }


    #[On('descargarExcelAgendasPlataforma')]
    public function descargarExcelAgendasPlataforma()
    {
        //dd('sdasd');

        $this->validate();
        if ($this->tipo_asegurado == 'ESTUDIANTES') {
            $id_instituciones = 0;
        } elseif ($this->tipo_asegurado == 'CONVENIO') {
            $id_instituciones = $this->id_dato_convenio;
        } else {
            $id_instituciones = $this->id_dato_institucion;
        }
        // dd($this->fecha_inicio, $this->fecha_fin, $this->tipo_asegurado, $id_instituciones);
        $url = route('pdf.generateReporteAgendas', [
            'tipo_asegurado' => $this->tipo_asegurado,
            'id_institucion' => $id_instituciones,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
        ]);
        $this->js("window.open('{$url}', '_blank');");
        $this->dispatch('reporteGeneradoExito');
        $this->dispatch('cerrarmodalLiquidacionAgendasReportes');

        $this->resetVariables();
    }
}
