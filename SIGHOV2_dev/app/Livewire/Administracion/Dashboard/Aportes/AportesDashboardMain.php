<?php

namespace App\Livewire\Administracion\Dashboard\Aportes;

use Livewire\Component;
use App\Models\Aportes\AporteInstitucional;
use App\Models\Aportes\AporteVoluntario;
use App\Models\Aportes\Comprobante;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AportesDashboardMain extends Component
{   
    public $instituciones = [];
    public $aportes = [];
    public $aportesPorMes = [];

    //Grafica para mostrar AportantesPorInstitucion
    public $institucionAportantes = [];
    public $cantidadAportantes = [];
    public $mesAnteriorAportes;

    public $topPersonasAportantes;

    public function mount()
    {   
        $this->obtenerTopInstituciones();
        $this->obtenerAportesPorMes();
        $this->obtenerAportantesPorInstitucion();
        $this->obtenerTopPersonasAportantesV();
    }

    private function obtenerAportesPorMes()
    {
        $aportes = Comprobante::select('id_mes', DB::raw('SUM(total_comprobante) as total'))
            ->groupBy('id_mes')
            ->orderBy('id_mes')
            ->pluck('total', 'id_mes');

        $this->aportesPorMes = array_fill(1, 12, null);
        foreach ($aportes as $mes => $total) {
            $this->aportesPorMes[$mes] = $total;
        }
    }

    private function obtenerTopInstituciones()
    {
        $resultados = AporteInstitucional::select('id_institucion', DB::raw('SUM(total_aporte) as total'))
            ->groupBy('id_institucion')
            ->orderByDesc('total')
            ->limit(10)
            ->with('institucionAporteInstitucional')
            ->get();

        foreach ($resultados as $resultado) {
            $this->instituciones[] = $resultado->institucionAporteInstitucional->nombre ?? 'Desconocido';
            $this->aportes[] = $resultado->total;
        }
    }

    private function obtenerAportantesPorInstitucion()
    {
        $mesesEnEspanol = [
            '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo',
            '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio',
            '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre',
            '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
        ];
        
        $mesAnterior = Carbon::now()->subMonth()->format('m'); // Obtiene el número del mes anterior (ej. '01' para enero)
        $this->mesAnteriorAportes = $mesesEnEspanol[$mesAnterior];
        $anioActual = Carbon::now()->year; // Obtiene el año actual

        // 🔹 Obtener datos filtrados por mes anterior
        $datos = AporteInstitucional::join('aportes.institucion', 'aporte_i.id_institucion', '=', 'aportes.institucion.id')
            ->whereRaw("EXTRACT(MONTH FROM aporte_i.fecha_ingreso) = ?", [$mesAnterior])
            ->whereRaw("EXTRACT(YEAR FROM aporte_i.fecha_ingreso) = ?", [$anioActual]) // Filtra solo el año actual
            ->select('aportes.institucion.nombre as nombre_institucion', DB::raw('count(distinct aporte_i.id_persona) as cantidad_aportantes'))
            ->groupBy('aportes.institucion.nombre')
            ->get();

        // 🔹 Convertir datos para la vista de Livewire
        $this->institucionAportantes = $datos->pluck('nombre_institucion')->toArray();
        $this->cantidadAportantes = $datos->pluck('cantidad_aportantes')->toArray();
    }
    private function obtenerTopPersonasAportantesV(){
        $datos = AporteVoluntario::join('aportes.contrato_voluntario', 'aportes.aporte_v.id_contrato_voluntario', '=', 'aportes.contrato_voluntario.id')
            ->join('administracion.persona', 'aportes.contrato_voluntario.id_persona', '=', 'administracion.persona.id')
            ->select(
                DB::raw("CONCAT(administracion.persona.nombres, ' ', administracion.persona.p_apellido, ' ', administracion.persona.s_apellido) AS nombre_aportante"),
                DB::raw("SUM(aportes.aporte_v.total_aporte) AS total_aportado")
            )
            ->groupBy('administracion.persona.id', 'administracion.persona.nombres', 'administracion.persona.p_apellido', 'administracion.persona.s_apellido')
            ->orderByDesc('total_aportado') // 🔹 Ordena de mayor a menor aporte
            ->limit(5) // 🔹 Solo devuelve las 5 personas con más aportes
            ->pluck('total_aportado', 'nombre_aportante'); // 🔹 Formato clave-valor para usar en gráficos
        $this->topPersonasAportantes = $datos;
    }

    public function render()
    {
        return view('livewire.Administracion.dashboard.aportes.aportes-dashboard-main');
    }
}
