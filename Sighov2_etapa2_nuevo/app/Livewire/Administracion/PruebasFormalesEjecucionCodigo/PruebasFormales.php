<?php

namespace App\Livewire\Administracion\PruebasFormalesEjecucionCodigo;

use App\Jobs\PruebaColaJob;
use App\Services\ActualizacionStockReal;
use App\Services\AjusteMovimientosPostInventario;
use App\Services\PruebasGenerales\CorreccionesFarmaciaService;
use App\Services\ReportesContabilidad\EstadoCuentas121;
use App\Services\ReportesContabilidad\EstadoCuentas122Materiales;
use App\Services\ServicioPersonaInstitucion;
use Livewire\Component;

class PruebasFormales extends Component
{
    public $idMedicamento;

    public function render()
    {
        return view('livewire.Administracion.pruebas-formales-ejecucion-codigo.pruebas-formales');
    }

    public function mount()
    {
        //$this->mostrarResultadosEstadoCuenta121();
        //$this->mostrarResultadosEstadoCuenta122Materiales();
    }

    /*public function ejecutarActualizacionStockReal(){
        set_time_limit(24000);
        $actualizacion = new ActualizacionStockReal();
        $actualizacion->actualizarStockRealMovimiento();
    }

    public function ejecutarAjusteMovimientoPostInventario(){
        set_time_limit(24000);
        $pruebasIniciales = new AjusteMovimientosPostInventario();
        $pruebasIniciales->corregirCierresAlmacen();
    }

    public function ejecutarAjusteMovimientoRecetasPorInventario(){
        set_time_limit(24000);
        $pruebasIniciales = new AjusteMovimientosPostInventario();
        $pruebasIniciales->corregirRecetasCaducadas();
    }

    public function ejecutarAjusteMovimientoRecetasPorInventarioEntregados(){
        set_time_limit(24000);
        $pruebasIniciales = new AjusteMovimientosPostInventario();
        $pruebasIniciales->corregirRecetasEntregadas();
    }*/

    public function verIdInstitucion()
    {
        $datosInstitucionPersona = new ServicioPersonaInstitucion();
        $datosInstitucion = $datosInstitucionPersona->personaInstitucion(2053);

        $idInstitucion = $datosInstitucion['instituciones'] ?? [];

        dd($idInstitucion);
    }

    public function testJobs()
    {
        PruebaColaJob::dispatch();
        dd('ok');
        // return;
    }
    public function modalPdfPosicion()
    {
        // dd('ok');
        $this->dispatch('abrirModalPdfPosicion');
        // $this->emit('abrirModalPdfPosicion');
    }

    public function mostrarResultadosEstadoCuenta121()
    {
        $funcion = new EstadoCuentas121();
        $funcion->generarEstadoCuenta(2026);
    }

    public function mostrarResultadosEstadoCuenta122Materiales()
    {
        /*$funcion = new EstadoCuentas122Materiales();
        $funcion->generarEstadoCuenta(2026);*/
    }

    public function recalculoMovimientosAlmFarm()
    {
        $this->idMedicamento = (int) $this->idMedicamento;
        if ($this->idMedicamento !== null && is_int($this->idMedicamento)) {
            //dd('entra');
            $funcion = new CorreccionesFarmaciaService();
            $funcion->resultados($this->idMedicamento);
        }        
    }
}
