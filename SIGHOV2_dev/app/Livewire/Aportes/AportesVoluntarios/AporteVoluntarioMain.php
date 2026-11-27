<?php

namespace App\Livewire\Aportes\AportesVoluntarios;

use Livewire\Component;

use App\Models\Administracion\Persona;
use App\Models\Aportes\Mes;
use App\Models\Aportes\AporteVoluntario;
use App\Models\Aportes\ContratoVoluntario;
use App\Models\Aportes\Comprobante;
use App\Models\Aportes\FormaPago;
use App\Models\Aportes\Ufv;
use App\Models\Users;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AporteVoluntarioMain extends Component
{
    public $volId;
    public $contId;

    public $contratoVoluntarioId;
    public $personaId;

    public $montoDemasia;
    public $demasiaDescripcion;
    public $montoDeduccion;
    public $deduccionDescripcion;
    public $nroComproAnterior;
    public $totalComproAnterior;
    public $fechaPago;
    public $tipoPago;
    public $nroFormaPago;
    public $montoTotalPago;
    public $totalGanado; //Y
    public $totalAporte; //Y
    public $nroComprobante;
    public $nroComprobanteGestion; //Y
    public $fechaLiquidacion;
    public $mesId; //Y
    public $mesNombre; //Y
    public $gestion; //Y

    public $nombres; //Y
    public $pApellido; //Y
    public $sApellido; //Y
    public $ci; //Y
    public $complemento; //Y
    public $fechaNacimiento; //Y

    public $nombresP; //Y
    public $pApellidoP; //Y
    public $sApellidoP; //Y
    public $ciP; //Y
    public $complementoP; //Y
    public $fechaNacimientoP;

    public $montoDemasiaP;
    public $montoDeduccionP;
    public $fechaPagoP;
    public $totalGanadoP;
    public $totalAporteP;
    public $totalAporteActualizadoP;
    public $montoTotalPagoP;
    public $fecha_pago_m;
    

    public $verLiquidacion = false;

    #[On('vistaAporteVoluntario')]
    public function vistaAporteVoluntario($voluntarioId)
    {
        $this->volId = $voluntarioId;
        $this->dispatch('openModalAportantesVoluntarios');
    }

    #[On('preliquidacion')]
    public function preliquidacion($idContrato)
    {
        $this->contId = $idContrato;
        $this->dispatch('openModalPreliquidacion');
    }

    public function render()
    {
        if ($this->contId) {
            $this->reset('montoTotalPagoP', 'totalAporteP', 'totalAporteActualizadoP', 'totalGanadoP', 'montoDeduccionP', 'montoDemasiaP', 'fechaPagoP');
            $this->preLiquidar($this->contId);
        }
        $this->mostrarAporte($this->volId);
        return view('livewire.Aportes.aportes-voluntarios.aporte-voluntario-main');
    }

    public function preLiquidar($idCont)
    {
        $contrato = ContratoVoluntario::where('id', '=', $idCont)->first();
        $persona = Persona::where('id', '=', $contrato->id_persona)->first();
        $this->nombresP = $persona->nombres;
        $this->pApellidoP = $persona->p_apellido;
        $this->sApellidoP = $persona->s_apellido;
        $this->ciP = $persona->ci;
        $this->complementoP = $persona->complemento;
        $this->fechaNacimientoP = $persona->fecha_nacimiento;
    }

    public function calcularPreliq()
    {
        if ($this->fechaPagoP) {
            $ufvFechaPago = Ufv::where('fecha', '=', $this->fechaPagoP)
                ->where('estado', '=', true)
                ->first();

            if ($ufvFechaPago) {
                if ($this->montoDemasiaP == '') {
                    $this->montoDemasiaP = 0;
                }
                if ($this->montoDeduccionP == '') {
                    $this->montoDeduccionP = 0;
                }
                $this->montoTotalPagoP = round($ufvFechaPago->valor * 500, 2);
                $this->totalAporteP = $this->montoTotalPagoP;
                $this->totalAporteActualizadoP = round($this->montoTotalPagoP - $this->montoDeduccionP + $this->montoDemasiaP, 2);
                $this->totalGanadoP = round($this->totalAporteP * 10, 2);
                $this->verLiquidacion = true;
            } else {
                $this->dispatch('noExisteUfv');
            }
        } else {
            $this->dispatch('sinFechaPago');
        }

        $this->skipRender();
    }

    public function mostrarAporte($aporteId)
    {
        if ($aporteId) {
            $comprobante = Comprobante::where('id_aporte_v', '=', $aporteId)->first();

            $this->fechaLiquidacion = $comprobante->fecha_liquidacion;
            $this->montoDemasia = $comprobante->monto_demasia;
            $this->demasiaDescripcion = $comprobante->demasia_descripcion;
            $this->montoDeduccion = $comprobante->monto_deduccion;
            $this->deduccionDescripcion = $comprobante->deduccion_descripcion;
            

            $formaPago = FormaPago::where('id_comprobante', '=', $comprobante->id)->first();
            $nroCompAnt = $formaPago->nro_compro_anterior;
            $this->totalComproAnterior = $formaPago->total_compro_anterior;
            $this->fechaPago = $formaPago->fecha_pago;

            if($nroCompAnt == 0){
                $this->nroComproAnterior = 0;
            }elseif($nroCompAnt != 0){
                $comprobante_ant = Comprobante::where('nro_registro_comprobante',$nroCompAnt)->first();
                $this->nroComproAnterior = $nroCompAnt.'/'.$comprobante_ant->gestion;
            }

            $this->fecha_pago_m = Carbon::parse($formaPago->fecha_pago)->format('d/m/Y');
            $this->tipoPago = $formaPago->tipo_pago;
            $this->nroFormaPago = $formaPago->nro_pago;
            $this->montoTotalPago = $formaPago->total_forma_pago;

            //Aporte Voluntario y relaciones

            $aporteVoluntario = AporteVoluntario::where('id', '=', $aporteId)->first();
            //dd($aporteVoluntario);
            $this->totalGanado = $aporteVoluntario->total_ganado;
            $this->totalAporte = $aporteVoluntario->total_aporte;
            $this->gestion = $aporteVoluntario->gestion;
            $this->mesId = $aporteVoluntario->id_mes;
            $mes = Mes::find($this->mesId);
            $this->mesNombre = $mes->mes;
            $this->contratoVoluntarioId = $aporteVoluntario->id_contrato_voluntario;
            $contrato = ContratoVoluntario::find($this->contratoVoluntarioId);
            $this->personaId = $contrato->id_persona;
            $persona = Persona::find($this->personaId);
            $this->nombres = $persona->nombres;
            $this->pApellido = $persona->p_apellido;
            $this->sApellido = $persona->s_apellido;
            $this->ci = $persona->ci;
            $this->complemento = $persona->complemento;
            $this->fechaNacimiento = Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y') ;

            $this->nroComprobante = $comprobante->nro_registro_comprobante . '/' . $this->gestion;
        }
    }
    // abrirAporteVoluntario
    #[On('abrirAporteVoluntario')]
    public function abrirAporteVoluntario($data)
    {
        $this->volId= $data;
        $this->dispatch('modalAporteVoluntario');
        
    }
    
   
}
