<?php

namespace App\Livewire\Administracion\Dashboard\Afiliacion;

use Livewire\Component;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Estudiante;
use App\Models\Afiliacion\TitularInstitucion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AfiliacionDashboardMain extends Component
{   
    public $afiliadosPorMes = [];
    public $afiliadosPorAnio = [];
    //
    public $cantidadTitulares;
    public $cantidadBeneficiarios;
    public $cantidadEstudiantes;
    public $totalAfiliados;

    //
    public $afiliadosPorInstitucion = [];

    public function mount()
    {
        $this->afiliadosPorAnio = $this->obtenerEstadisticasAfiliacionPorAnio();
        $this->totalAfiliadosHoy();
        $this->afiliadosPorInstitucion = $this->obtenerInstitucionesMasAfiliados();
    }

    private function obtenerEstadisticasAfiliacionPorAnio()
    {
        $currentYear = Carbon::now()->year;
        $afiliados = Estudiante::selectRaw('EXTRACT(YEAR FROM fecha_afiliacion) as anio, COUNT(*) as total')
            ->whereBetween('fecha_afiliacion', [Carbon::now()->subYears(5)->startOfYear(), Carbon::now()->endOfYear()])
            ->groupBy('anio')
            ->orderBy('anio')
            ->pluck('total', 'anio');

        $estadisticas = [];
        for ($i = $currentYear - 4; $i <= $currentYear; $i++) {
            $estadisticas[$i] = $afiliados[$i] ?? 0;
        }

        return $estadisticas;
    }

    private function obtenerInstitucionesMasAfiliados(){
        $datos = DB::table('afiliacion.titular_institucion as ti') // Esquema afiliacion
            ->leftJoin('afiliacion.beneficiario as b', 'ti.id_titular', '=', 'b.id_titular') // Relaciona beneficiarios con titulares
            ->join('aportes.institucion as i', 'ti.id_institucion', '=', 'i.id') // Relaciona con Institución en esquema aportes
            ->join('afiliacion.titular as titul', 'titul.id', '=', 'ti.id_titular')
            ->select(
                'i.nombre as nombre_institucion', // Nombre de la institución en vez del ID
                DB::raw('COUNT(DISTINCT ti.id_titular) as total_titulares'),
                DB::raw('COUNT(b.id_titular) as total_beneficiarios'),
                DB::raw('COUNT(DISTINCT ti.id_titular) + COUNT(b.id_titular) as total_afiliados')
            )
            ->where('b.vigencia','VIGENTE')
            ->where('titul.estado_vigencia','VIGENTE')
            ->groupBy('i.nombre')
            ->orderBy('total_afiliados', 'DESC') // Ordenar por cantidad de afiliados
            ->get();
        //dd($datos);
        return $datos;
    }

    private function totalAfiliadosHoy(){
        $this->cantidadTitulares = Titular::where('estado_vigencia', 'VIGENTE')
            ->distinct('id_persona') 
            ->count('id_persona');   

        $this->cantidadBeneficiarios = Beneficiario::where('estado_requisitos', true)
            ->where('vigencia', 'VIGENTE')
            ->distinct('id_persona') 
            ->count('id_persona');   

        $this->cantidadEstudiantes = Estudiante::where('estado', true)
            ->where('estado_vigencia', true)
            ->count();

        $this->totalAfiliados = $this->cantidadTitulares + $this->cantidadBeneficiarios + $this->cantidadEstudiantes;
    }
    public function render()
    {
        return view('livewire.Administracion.dashboard.afiliacion.afiliacion-dashboard-main');
    }
}
