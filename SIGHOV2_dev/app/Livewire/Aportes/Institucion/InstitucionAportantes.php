<?php

namespace App\Livewire\Aportes\Institucion;

use App\Models\Aportes\AporteInstitucional;
use App\Models\Aportes\ClaseAportante;
use App\Models\Aportes\Institucion;
use App\Models\Aportes\Mes;
use Livewire\Attributes\On;
use Livewire\Component;

class InstitucionAportantes extends Component
{
    public $nombre;

    public $acronimo;

    public $num_empleador;

    public $nit;

    public $tipoclaseaportante;

    public $id_inst;

    public $publica;

    public $actividad_empleador;

    public function render()
    {
        return view('livewire.Aportes.institucion.institucion-aportantes');
    }

    public $mes_id;

    public $gestiones = [];

    public $anio;

    public $meses = [];

    public function rules()
    {
        return [
            'mes_id' => 'required|integer',
            'anio' => 'required|integer',
        ];
    }

    public function mostrarInstitucion($instId)
    {
        if ($instId) {
            $institucion = Institucion::find($instId);

            $this->nombre = $institucion->nombre;
            $this->acronimo = $institucion->acronimo;
            $this->num_empleador = $institucion->nro_empleador;
            $this->nit = $institucion->nit;
            $this->actividad_empleador = $institucion->actividad_empleador;

            $tipoAportante = ClaseAportante::find($institucion->id_clase_aportante);
            $this->tipoclaseaportante = $tipoAportante->tipo;
            $this->id_inst = $institucion->id;

            return $institucion;
        }
    }

    #[On('verificarAfiliacion')]
    public function verificarAfiliacion($id)
    {
        //set_time_limit(130000);
        $this->meses = Mes::all();
        $this->reset('mes_id', 'anio');
        $this->gestiones = $this->obtenerGestiones();
        // dd($this->gestiones);

        $this->mostrarInstitucion($id);
        $this->dispatch('abrirModalReportesAfiliados');
        //dd($id_institucion);
    }

    public function generarReporteAfiliados()
    {
       
        //dd($this->mes_id, 'hola', $this->anio, $this->id_inst);
        $this->validate();
        $url = route('aportes.afiliadosPorInstitucion.excel', [
            'id_institucion' => $this->id_inst,
            'id_mes' => $this->mes_id,
            'gestion' => $this->anio,
        ]);
        $this->js("window.open('{$url}', '_blank');");
        $this->dispatch('excelExitoso');
    }

    public function obtenerGestiones()
    {
        return AporteInstitucional::select('gestion')
            ->distinct()
            ->orderBy('gestion', 'desc')
            ->pluck('gestion');
    }
}
