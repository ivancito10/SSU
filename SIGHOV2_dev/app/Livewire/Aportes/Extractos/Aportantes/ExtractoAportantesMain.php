<?php

namespace App\Livewire\Aportes\Extractos\Aportantes;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Administracion\Persona;
use App\Models\Aportes\AporteInstitucional;
use App\Models\Aportes\AporteVoluntario;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;

class ExtractoAportantesMain extends Component
{
    public $aportanteId;
    public $modalExtractoForm;
    public $fecha_inicial;
    public $fecha_final;

    public $id_persona;
    public $nombres;
    public $p_apellido;
    public $s_apellido;
    public $carnet;

    public function mostrarAportante($aportanteId)
    {
        if ($aportanteId) {
            $datosAportante = Persona::find($aportanteId);
            $this->nombres = $datosAportante->nombres;
            $this->p_apellido = $datosAportante->p_apellido;
            $this->s_apellido = $datosAportante->s_apellido;
            $this->carnet = $datosAportante->ci;

            return $datosAportante;
        }
    }
    #[On('verextractoModal')]
    public function verExtracto($datos)
    {
        $this->aportanteId = $datos['aportanteId'];
        $this->modal = $datos['modalExtractoForm'];
        $this->dispatch('openModalVerExtracto');
    }
    #[On('verExtractoPdf')]
    public function verExtractoPdf()
    {
        //dd('hjola');
        //$persona = 
        //$aportesInstitucionales = AporteInstitucional::where('id_persona', $this->aportanteId)->limit('10')->get();
        //dd($aportesInstitucionales);


        $mesInicial = Carbon::parse($this->fecha_inicial)->format('m');
        $gestionInicial = Carbon::parse($this->fecha_inicial)->format('Y');
        $mesFinal = Carbon::parse($this->fecha_final)->format('m');
        $gestionFinal = Carbon::parse($this->fecha_final)->format('Y');
        $fechaInicio =  Carbon::parse($this->fecha_inicial)->format('Y-m-d');
        $fechaFin =  Carbon::parse($this->fecha_final)->format('Y-m-d');
        $aportId = $this->aportanteId;
        try {

            $aportesInstitucionales = AporteInstitucional::where('id_persona', $aportId)
                //->whereBetween('gestion', [$gestionInicial, $gestionFinal])
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->with('mesAporteInstitucional')
                ->with('institucionAporteInstitucional')
                // ->orderBy('created_at')
                ->get();

            $aportesVoluntarios = AporteVoluntario::whereBetween('created_at', [$fechaInicio, $fechaFin])
                //whereBetween('gestion', [$gestionInicial, $gestionFinal])
                // ->whereBetween('id_mes', [$mesInicial, $mesFinal])

                ->whereHas('contratoVoluntarioAporteVoluntario', function ($contrato) use ($aportId) {
                    $contrato->where('id_persona', $aportId);
                })
                ->with('mesAporteVoluntario')
                //  ->orderBy('created_at')
                ->get();
            //dd($aportesInstitucionales, $aportesVoluntarios, $gestionInicial, $gestionFinal, $mesInicial, $mesFinal);
            /*$consulta = Persona::join('aportes.aporte_i as api', 'api.id_persona', '=', 'administracion.persona.id')
                ->join('aportes.institucion as ins', 'ins.id', '=', 'api.id_institucion')
                ->join('aportes.mes as mes', 'mes.id', '=', 'api.id_mes')
                ->where('api.created_at', '>', $this->fecha_inicial)
                ->where('api.created_at', '<=', $this->fecha_final)
                ->where('api.id_persona', '=', $this->aportanteId)
                ->select('administracion.persona.nombres', 
                    'administracion.persona.p_apellido', 
                    'administracion.persona.s_apellido', 
                    'administracion.persona.ci', 
                    'administracion.persona.complemento', 
                    'administracion.persona.fecha_nacimiento', 
                    'api.tipo_trabajador', 'api.dias_trabajados', 
                    'api.total_ganado', 'api.total_aporte', 'api.gestion', 
                    'mes.mes', 'ins.nombre as institucion', 'api.carga_horaria',
                    'api.item', 'api.cargo', 'api.area', 'api.created_at')
                ->get();
            //$consulta = Persona::all();
            //dd($consulta);*/



            if ($aportesInstitucionales->count() == 0 && $aportesVoluntarios->count() == 0) {
                $this->dispatch('noExistenRegistros');
            } else {
                $this->dispatch('DescargaExitosaExtracto');

                $this->js('window.open("' . route('aportes.extractoaportantes.pdf', [
                    'id' => $this->aportanteId,
                    'fecha_inicial' => $this->fecha_inicial,
                    'fecha_final' => $this->fecha_final
                ]) . '", "_blank")');
            }
        } catch (\Throwable $th) {
            $this->dispatch('DescargaerroaExtracto');
        }
    }
    public function render()
    {
        $aportante = $this->mostrarAportante($this->aportanteId);
        return view('livewire.Aportes.extractos.aportantes.extracto-aportantes-main', ['aportante' => $aportante]);
    }
}
