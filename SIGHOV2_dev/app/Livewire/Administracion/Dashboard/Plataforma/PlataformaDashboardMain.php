<?php

namespace App\Livewire\Administracion\Dashboard\Plataforma;

use App\Models\Plataforma\Agenda;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PlataformaDashboardMain extends Component
{   
    public $agendasUlt5Dias;
    public function render()
    {
        return view('livewire.Administracion.dashboard.plataforma.plataforma-dashboard-main');
    }
    public function mount(){
        $this->agendasUlt5Dias = $this->agendasRegistradas5UltDias();
    }

    public function agendasRegistradas5UltDias(){
        $fechas = [];
        $dia = Carbon::today();

        while (count($fechas) < 5) {
            if (!$dia->isWeekend()) { 
                $fechas[] = $dia->toDateString();
            }
            $dia->subDay();
        }
        $fechas = array_reverse($fechas); 

        $datos = Agenda::select('fecha_agenda', DB::raw('COUNT(*) as total'))
            ->whereIn('fecha_agenda', $fechas)
            ->groupBy('fecha_agenda')
            ->orderBy('fecha_agenda')
            ->pluck('total', 'fecha_agenda')
            ->mapWithKeys(function ($total, $fecha) {
                return [Carbon::parse($fecha)->format('d/m/Y') => $total];
            })
            ->toArray();

        return $datos;
    }
}
