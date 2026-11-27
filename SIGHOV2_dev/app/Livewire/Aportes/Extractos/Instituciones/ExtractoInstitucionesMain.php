<?php

namespace App\Livewire\Aportes\Extractos\Instituciones;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Aportes\Institucion;
use App\Models\Aportes\Comprobante;
use App\Models\Aportes\ClaseAportante;
use Carbon\Carbon;

class ExtractoInstitucionesMain extends Component
{
    public $modal;
    public $instId;
    public $nombre_institucion;

    public $tipoclaseaportante;

    public $nit;
    public $institucion;
    public $fecha_inicial;
    public $fecha_final;

    public function render()
    {
        $institucion = $this->mostrarInstitucion($this->instId);
        return view('livewire.Aportes.extractos.instituciones.extracto-instituciones-main', ['institucion' => $institucion]);
    }
    //Logica para mostrar una sola institucion por ID
    public function mostrarInstitucion($instId)
    {
        if ($instId) {
            $institucion = Institucion::find($instId);
            $this->nombre_institucion = $institucion->nombre;
            $this->nit = $institucion->nit;

            $tipoAportante = ClaseAportante::find($institucion->id_clase_aportante);
            $this->tipoclaseaportante = $tipoAportante->tipo;

            return $institucion;
        }
    }
    #[On('verExtractoPdfInstitucion')]
    public function verExtractoPdfInstitucion()
    {
        try {
            /*$aportes = Institucion::join('aportes.comprobante as comprobantes', 'aportes.institucion.id', '=', 'comprobantes.id_institucion')
            ->join('administracion.zona as zonas', 'zonas.id', '=', 'aportes.institucion.id_zona')
            ->join('aportes.clase_aportante as clase_aportantes', 'clase_aportantes.id', '=', 'aportes.institucion.id_clase_aportante')
            ->join('aportes.mes as meses', 'meses.id', '=', 'comprobantes.id_mes')
            ->where('aportes.institucion.id', $this->instId)
            ->whereBetween('comprobantes.created_at', [$this->fecha_inicial, $this->fecha_final])
            ->select(
                'aportes.institucion.nombre',
                'aportes.institucion.id_zona',
                'zonas.nombre as zona_nombre',
                'aportes.institucion.id_clase_aportante',
                'clase_aportantes.nombre as clase_aportante_nombre',
                'comprobantes.id_mes',
                'meses.mes'
            )
            ->get();*/

            $mesInicial = Carbon::parse($this->fecha_inicial)->format('m');
            $gestionInicial = Carbon::parse($this->fecha_inicial)->format('Y');
            $mesFinal = Carbon::parse($this->fecha_final)->format('m');
            $gestionFinal = Carbon::parse($this->fecha_final)->format('Y');
            $fechaInicio =  Carbon::parse($this->fecha_inicial)->format('Y-m-d');
            $fechaFin =  Carbon::parse($this->fecha_final)->format('Y-m-d');
            $aportesInstitucion = Comprobante::where('id_institucion', $this->instId)
                // ->whereBetween('gestion', [$gestionInicial, $gestionFinal])
                // ->whereBetween('id_mes', [$mesInicial, $mesFinal])
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->whereNot('fecha_liquidacion', null)
                ->with([
                    'institucionComprobante',
                    'mesComprobante',
                    'institucionComprobante.claseAportanteInstitucion',
                    'institucionComprobante.institucionResidencia',
                    'institucionComprobante.institucionResidencia.zonaResidencia'
                ])
                ->get();
           // dd($aportesInstitucion);
            if ($aportesInstitucion->count() == 0) {
                $this->dispatch('noExistenRegs');
            } else {
                $this->dispatch('DescargaExitosaExtracto');
                // return $this->redirectRoute('aportes.extractoinstituciones.pdf', [
                //     'id' => $this->instId,
                //     'fecha_inicial' => $this->fecha_inicial,
                //     'fecha_final' => $this->fecha_final,
                // ]);

                $this->js('window.open("' . route('aportes.extractoinstituciones.pdf', [
                    'id' => $this->instId,
                    'fecha_inicial' => $this->fecha_inicial,
                    'fecha_final' => $this->fecha_final
                ]) . '", "_blank")');
            }
        } catch (\Throwable $th) {
            $this->dispatch('DescargaerroaExtracto');
        }
    }

    #[On('vistaExtractoInsti')]
    public function verInstitucion($datos)
    {
        $this->instId = $datos['institucionId'];
        $this->modal = $datos['modalVer'];
        $this->dispatch('openModalVer');
    }
    // extractoInstitucion
    #[On('extractoInstitucion')]
    public function extractoInstitucion($data)
    {
        $this->instId = $data;
        //$this->mostrarInstitucion($this->instId);
        $this->dispatch('modalAbrirInstitucion');
    }
}
