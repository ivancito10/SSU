<?php

namespace App\Livewire\Administracion\Dashboard\Vigencia;

use App\Models\Vigencia\SolicitudPMedica;
use Carbon\Carbon;
use Livewire\Component;

class VigenciaDashboardMain extends Component
{   
    public $prestMedicasMes = [];
    public function mount()
    {
        $this->prestMedicasMes = $this->prestacionesMedicasMes();
        $this->obtenerPrestMedPorSeguro();
    }

    public function render()
    {
        return view('livewire.Administracion.dashboard.vigencia.vigencia-dashboard-main');
    }

    private function prestacionesMedicasMes()
    {
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
            7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        $ultimosMeses = [];
        for ($i = 5; $i >= 0; $i--) {
            $fecha = Carbon::now()->subMonths($i);
            $ultimosMeses[$fecha->format('n')] = $meses[$fecha->format('n')]; // Guarda el número y nombre del mes
        }

        $datos = SolicitudPMedica::selectRaw("EXTRACT(MONTH FROM fecha_inicio) as mes, COUNT(*) as total")
            ->where('fecha_inicio', '>=', Carbon::now()->subMonths(5)->startOfMonth()) // Últimos 6 meses
            ->groupBy('mes')
            ->orderByRaw("MIN(fecha_inicio)")
            ->pluck('total', 'mes')
            ->toArray();

        $estadisticas = [];
        foreach ($ultimosMeses as $numMes => $nombreMes) {
            $estadisticas[$nombreMes] = $datos[$numMes] ?? 0; // Si no hay datos, se coloca 0
        }

        return $estadisticas;
    }
    private function obtenerPrestMedPorSeguro(){
        $datos = SolicitudPMedica::join('vvdd.seguro_interior','id_seguro_interior','vvdd.seguro_interior.id')
            ->select('vvdd.seguro_interior.nombre_seguro')
            ->get();
        //dd($datos);
    }


}
