<?php

namespace App\Http\Controllers;
use App\Models\Aportes\Ufv;
use App\Models\Aportes\TasaInteres;
use Carbon\Carbon;

use Illuminate\Http\Request;

class AportesPruebasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $mes_pago=2;
        $anio_pago=2024;
        $fecha_liquidacion='2024-5-24';
        $total_salario_cotizable= 156945.94;
        $valor_ufv=1;
        $factor_activo=1;
        $tasa_liquidacion=0.1;
        $actualizacion_aporte=0;
        $importe_actualizacion_aporte=0;
        $importe_aporte_patronal=0;

        $dias_mora=0;
        $tasa_intereses=0;
        $tasa_multas=0.1;
        $tasa_multa_no_planilla=0.01;

        $intereses=0;
        $multas_sin_intereses=0;
        $multas_no_planilla=0;
        $subtotal_liquidacion=0;
        $subtotal_recargos=0;
        $presenta_planilla=true;


        
        /* Para calcular Liquidacion de aportes */
        $fecha_inicio_plazo = date("Y-m-d", strtotime("{$anio_pago}-{$mes_pago}-01"));
        $fecha_fin_plazo = date("Y-m-t", strtotime("+1 month", strtotime($fecha_inicio_plazo)));

        $fecha_inicio_plazo = Carbon::createFromFormat('Y-m-d', $fecha_inicio_plazo);
        $fecha_fin_plazo = Carbon::createFromFormat('Y-m-d', $fecha_fin_plazo);
        $fecha_liquidacion = Carbon::createFromFormat('Y-m-d', $fecha_liquidacion);
        
        echo "Fecha liquidacion: $fecha_liquidacion<br>";
        echo "Fecha fin plazo: $fecha_fin_plazo<br>";


        if (!($fecha_liquidacion >= $fecha_inicio_plazo && $fecha_liquidacion <= $fecha_fin_plazo)) {
            $valor_ufv=$this->obtenerUFV($fecha_fin_plazo);
            $dias_mora=$fecha_liquidacion->diffInDays($fecha_fin_plazo);
            $factor_activo=round($valor_ufv/$this->obtenerUFV($fecha_liquidacion),6);
            $tasa_intereses=$this->obtenerTasaInteres($fecha_liquidacion)/100;
        } 
                   
        $importe_aporte_patronal=round($total_salario_cotizable*$tasa_liquidacion,2);
        $actualizacion_aporte=round($total_salario_cotizable-($total_salario_cotizable*$factor_activo),2);
        $importe_actualizacion_aporte=round($actualizacion_aporte*$tasa_liquidacion,2);
        $subtotal_liquidacion=round($importe_actualizacion_aporte+$importe_aporte_patronal,2);

        $intereses=round((($tasa_intereses*$subtotal_liquidacion)/365)*$dias_mora,2);
        $multas_sin_intereses=round($intereses*$tasa_multas,2);
        if(!$presenta_planilla){
            $multas_no_planilla=round($subtotal_liquidacion*$tasa_multa_no_planilla,2);
        }
        $subtotal_recargos=round($intereses+$multas_sin_intereses+$multas_no_planilla,2);

        $total=$subtotal_liquidacion+$subtotal_recargos;

        echo "Total salario corizable: $total_salario_cotizable<br>";
        echo "Importe aporte patronal: $importe_aporte_patronal<br>";
        echo "Factor activo: $factor_activo<br>";
        echo "Actualizacion aporte: $actualizacion_aporte<br>";
        echo "Importe actualizacion aporte: $importe_actualizacion_aporte<br>";
        echo "Sub total liquidacion: $subtotal_liquidacion<br>";
        echo "===============================================<br>";
        echo "Tasa intereses: $tasa_intereses<br>";
        echo "Dias de mora : $dias_mora<br>";
        echo "Intereses: $intereses<br>";
        echo "Multas sin intereses: $multas_sin_intereses<br>";
        echo "Multas no planillas: $multas_no_planilla<br>";
        echo "Sub total recargos: $subtotal_recargos<br>";
        echo "===============================================<br>";
        echo "Total: $total<br>";
    }


    /**
     * Show the form for creating a new resource.
     */
    public function obtenerTasaInteres($fecha){
        /* Para obtener el valor de tasa de interes */
        $obtener_tasa=TasaInteres::where('fecha',$fecha)->first();
        if ($obtener_tasa) {
            return $obtener_tasa->valor;
        } else {
            return 1;
        }
    }

    public function obtenerUFV($fecha){
        /* Para obtener el valor de la UFV de la fecha indicada */
        $obtener_ufv=Ufv::where('fecha', $fecha)->first();
        if ($obtener_ufv) {
            return $obtener_ufv->valor;
        } else {
            return 1;
        }
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}



/* public $fecha_inicio_plazo;
public $fecha_fin_plazo;
public $valor_ufv=1;
public $dias_mora=0;
public $tasa_intereses=0;
public $importe_aporte_patronal=0;
public $actualizacion_aporte=0;
public $importe_actualizacion_aporte=0;
public $subtotal_liquidacion=0;
public $intereses=0;
public $multas_sin_intereses=0;
public $presenta_planilla=true;
public $multas_no_planilla=0;
public $subtotal_recargos=0;


        $this->fecha_inicio_plazo = date("Y-m-d", strtotime("{$this->gestion}-{$this->id_mes}-01"));
        $this->fecha_fin_plazo = date("Y-m-t", strtotime("+1 month", strtotime($this->fecha_inicio_plazo)));

        $this->fecha_inicio_plazo = Carbon::createFromFormat('Y-m-d', $this->fecha_inicio_plazo);
        $this->fecha_fin_plazo = Carbon::createFromFormat('Y-m-d', $this->fecha_fin_plazo);
        $this->dato_fecha_liquidacion = Carbon::createFromFormat('Y-m-d', $this->dato_fecha_liquidacion);
        


        if (!($this->dato_fecha_liquidacion >= $this->fecha_inicio_plazo && $this->dato_fecha_liquidacion <= $this->fecha_fin_plazo)) {
            $this->valor_ufv=$this->obtenerUFV($this->fecha_fin_plazo);
            $this->dias_mora=$this->dato_fecha_liquidacion->diffInDays($this->fecha_fin_plazo);
            $this->factor_activo=round($this->valor_ufv/$this->obtenerUFV($this->dato_fecha_liquidacion),6);
            $this->tasa_intereses=$this->obtenerTasaInteres($this->dato_fecha_liquidacion)/100;
        } 
                   
        $this->importe_aporte_patronal=round($this->total_salario_cotizable*$this->tasa_clase,2);
        $this->actualizacion_aporte=round($this->total_salario_cotizable-($this->total_salario_cotizable*$this->factor_activo),2);
        $this->importe_actualizacion_aporte=round($this->actualizacion_aporte*$this->tasa_liquidacion,2);
        $this->subtotal_liquidacion=round($this->importe_actualizacion_aporte+$this->importe_aporte_patronal,2);

        $this->intereses=round((($this->tasa_intereses*$this->subtotal_liquidacion)/365)*$this->dias_mora,2);
        $this->multas_sin_intereses=round($this->intereses*$this->tasa_multas,2);
        if(!$this->presenta_planilla){
            $this->multas_no_planilla=round($this->subtotal_liquidacion*$this->tasa_multa_no_planilla,2);
        }
        $this->subtotal_recargos=round($this->intereses+$this->multas_sin_intereses+$this->multas_no_planilla,2); */

/* 
        <script>
    function verificarInputs() {
        var numero = document.getElementById('numero').value;
        var checkbox = document.getElementById('checkbox').checked;
        var fecha = document.getElementById('fecha').value;

        if (numero && checkbox && fecha) {
            Livewire.emit('ejecutarPreLiquidacion');
        } else {
            alert('Por favor, complete todos los campos.');
        }
    }
</script>

@if(!empty($resultados))
        <div class="mt-4">
            <h5>Resultados</h5>
            <!-- Tabla con resultados -->
        </div>
    @endif */