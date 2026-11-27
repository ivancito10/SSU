<?php

namespace App\Livewire\Aportes\Externos\Aporte;

use Livewire\Component;
use App\Models\Aportes\Comprobante;
use App\Models\Aportes\Institucion;
use App\Models\Aportes\FormaPago;
use App\Models\Aportes\ClaseAportante;
use App\Models\Aportes\Mes;
use App\Models\Aportes\Ufv;
use App\Models\Aportes\TasaInteres;
use App\Models\Administracion\Residencia;
use App\Models\Administracion\Zona;
use App\Models\Administracion\Estado;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\NumberToWords;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class AportePreliquidacionExt extends Component
{
    public $compId;
    public $nombre_institucion, $nit, $tgn, $nombre_mes, $gestion, $total_salario_cotizable_m, $estado_comprobante;

    public $tipo_clase, $tasa_clase;

    public $id_mes, $subtotal_liquidacion_m;
    public $reintegro;
    public $planilla_adicional;
    public $beneficio_social;
    public $total_salario_cotizable;
    public $presenta_planilla = false;

    public $monto_demasia = 0, $monto_deduccion = 0,
     $demasia_descripcion = '', $deduccion_descripcion = '',
     $monto_demasia_m = 0;

    public $total_compensacion = 0;
    public $tasa_multas = 0.1;
    public $tasa_multa_no_planilla = 0.01;

    public $fecha_pago, $fecha_pago_m = null, $numero_mes, $fecha_inicio_plazo, $fecha_fin_plazo, $salario_cotizable,
    $valor_ufv, $valor_ufv_liq, $tasa, $dias_mora, $subtotal_recargos, $factor_activo, $importe_aporte_patronal,
    $multas_no_planilla, $multas_sin_intereses, $intereses,$subtotal_liquidacion, $importe_actualizacion_aporte, $actualizacion_aporte,
    $numeroEnteroEnLetras, $descripcion, $ministerio, $subtotal_deducciones, $total_a_b_c, $parte_decimal, 
    $numeroDecimalEnLetras, $totalLiteral, $m_tasa_clase, $m_tasa, $m_total_a_b_c, $m_subtotal_deducciones, $m_ministerio,
    $m_monto_deduccion, $m_subtotal_recargos, $m_multas_no_planilla, $m_multas_sin_intereses, $m_intereses, $m_monto_demasia, 
    $m_total_salario_cotizable;
    
    public $estado_alerta= 0;

    public $m_actualizacion_aporte, 
    $m_importe_actualizacion_aporte, $m_subtotal_liquidacion, $m_importe_aporte_patronal;

    public $abrir_comprobante = 0, $cant_eventuales, $cant_permanentes,
    $actividad, $nro_empleador, $telefono, $direccion, $vivienda, $zona, $importe_aporte_patronal_m;

    public $retroactivo;
    public function render()
    {
        return view('livewire.Aportes.Externos.aporte.aporte-preliquidacion-ext');
    }
    public function mount(){
        $this->mostrarComprobante($this->compId);
        $this->fecha_pago = Carbon::now()->format('Y-m-d'); // Formato compatible con input date

    }

    public function mostrarComprobante($compId)
    {
        if ($compId) {
            $comprobante = Comprobante::find($compId);
            $id_institucion = $comprobante->id_institucion;
            $this->id_mes = $comprobante->id_mes;
            $this->gestion = $comprobante->gestion;

            $this->cant_eventuales = $comprobante->cantidad_eventuales;
            $this->cant_permanentes = $comprobante->cantidad_permanentes;


            $this->salario_cotizable = $comprobante->total_salario_cotizable;
            $this->beneficio_social = $comprobante->beneficio_social;
            $this->planilla_adicional = $comprobante->planilla_adicional;
            $this->reintegro = $comprobante->reintegro;
            $this->retroactivo = $comprobante->retroactivo;
            $this->total_salario_cotizable = $comprobante->total_salario_cotizable;
            $this->total_salario_cotizable_m = number_format($comprobante->total_salario_cotizable, 2, ',', '.');
            $this->subtotal_liquidacion_m = number_format($comprobante->subtotal_liquidacion, 2, ',', '.');
            $id_estado = $comprobante->id_estado;
            $this->total_compensacion = $comprobante->total_compensacion;
            $this->importe_aporte_patronal = $comprobante->importe_aporte_patronal;
            $this->importe_aporte_patronal_m =number_format( $comprobante->importe_aporte_patronal, 2, ',', '.');

           
            $institucionActual = Institucion::find($id_institucion);
            $this->nombre_institucion = $institucionActual->nombre;
            $this->nit = $institucionActual->nit;
            $this->tgn = $institucionActual->tgn;
            $this->actividad = $institucionActual->actividad_empleador;
            $this->nro_empleador = $institucionActual->nro_empleador;
            $this->telefono = $institucionActual->telefono;
            $id_residencia = $institucionActual->id_residencia;

            $resiActual = Residencia::find($id_residencia);
            $this->direccion = $resiActual->direccion;
            $this->vivienda = $resiActual->nro_vivienda;
            $id_zona = $resiActual->id_zona;

            $zonaActual = Zona::find($id_zona);
            $this->zona = $zonaActual->nombre;

            $mesActual = Mes::find($comprobante->id_mes);
            $this->nombre_mes = $mesActual->mes;
            $this->numero_mes = $mesActual->numero;

            $claseActual = ClaseAportante::find($institucionActual->id_clase_aportante);
            
            $this->tipo_clase = $claseActual->tipo;
            $this->tasa_clase = $claseActual->porcentaje;
            

            return $comprobante;
        }
    }

    public function pre_liquidacion()
    {
        $this->abrir_comprobante = 1;

        $this->estado_alerta = 1;
        $this->tasa = 0;
        $this->dias_mora = 0;
        $this->subtotal_recargos = 0;
        $this->factor_activo = 1;
        $this->fecha_pago_m = Carbon::parse($this->fecha_pago)->format('d/m/Y');

        
        

        if ($this->fecha_pago != null) {
                if($this->monto_demasia == null){
                    $this->monto_demasia = 0;
                }
                if($this->monto_deduccion == null){
                    $this->monto_deduccion = 0;
                }
            // fecha inicio y fin de plazos de pago
            $this->fecha_inicio_plazo = Carbon::createFromDate($this->gestion, $this->numero_mes, 1)->format('Y-m-d');
            $this->fecha_fin_plazo = Carbon::createFromDate($this->gestion, $this->numero_mes + 1, 1)->endOfMonth()->format('Y-m-d');
            //cambia el orden en formato de las fechas
            $this->fecha_inicio_plazo = Carbon::parse($this->fecha_inicio_plazo);
            $this->fecha_fin_plazo = Carbon::parse($this->fecha_fin_plazo);
            $this->fecha_pago = Carbon::parse($this->fecha_pago);
            // Determina si la fecha de pago esta en el limite de fin de plazo y fecha liquidacion
            // de ser asi, se calcula los DIAS_MORA, el FACTOR ACTIVO y TASA DE INTERES
            $this->valor_ufv = $this->obtenerUFV($this->fecha_fin_plazo);
            $this->valor_ufv_liq = $this->obtenerUFV($this->fecha_pago);


            if (!$this->planilla_adicional && !$this->beneficio_social && !$this->reintegro && !$this->retroactivo) {

                // Aqui se calculan las multas
                if (!($this->fecha_pago >= $this->fecha_inicio_plazo && $this->fecha_pago <= $this->fecha_fin_plazo)) {
                    if ($this->valor_ufv != null && $this->valor_ufv_liq != null) {
                        $this->dias_mora = $this->fecha_pago->diffInDays($this->fecha_fin_plazo);
                        $this->factor_activo = round($this->valor_ufv / $this->valor_ufv_liq, 6);

                        //$this->factor_activo=round($this->valor_ufv/ 2.46204,6);
                        $this->tasa = $this->obtenerTasaInteres() / 100;
                        //$this-> tasa = 0.1078;

                    } else {
                        $this->dispatch('AlertUFVExt');
                        $this->estado_alerta = 2;
                        //$this->inicializarValores();
                        $this->abrir_comprobante = 0;
                    }
                }
            }
            $this->importe_aporte_patronal = round($this->importe_aporte_patronal, 2);
            
            $this->actualizacion_aporte = round($this->salario_cotizable - ($this->salario_cotizable * $this->factor_activo), 2);
            $this->importe_actualizacion_aporte = round($this->actualizacion_aporte * $this->tasa_clase, 2);
            //SubTotal(A)
            $this->subtotal_liquidacion = round($this->importe_actualizacion_aporte + $this->importe_aporte_patronal + $this->monto_demasia + $this->total_compensacion, 2);

            $this->intereses = round((($this->tasa * $this->subtotal_liquidacion) / 365) * $this->dias_mora, 2);
            $this->multas_sin_intereses = round($this->intereses * $this->tasa_multas, 2);
            $this->multas_no_planilla = 0;

            if (!$this->presenta_planilla) {
                $this->multas_no_planilla = round($this->subtotal_liquidacion * $this->tasa_multa_no_planilla, 2);
            }
            //SubTotal(B)
            $this->subtotal_recargos = round($this->intereses + $this->multas_sin_intereses + $this->multas_no_planilla, 2);
            //-------Hasta arriba todo bieeeen-----
            //SubTotal(C)
            if ($this->tgn == true) {
                $this->descripcion = 'Menos el 5% para el Ministerio de Salud';
                $this->ministerio = round(($this->importe_aporte_patronal * 0.05), 2);
            }

            $this->subtotal_deducciones = $this->ministerio + $this->monto_deduccion;
            //TOTAL 
            $this->total_a_b_c = round($this->subtotal_liquidacion +  $this->subtotal_recargos - $this->subtotal_deducciones, 2);

            //Convertir monto a literal
            $numero_formateado = number_format($this->total_a_b_c, 2, '.', '');
            $partes = explode('.', $numero_formateado);
            $this->parte_decimal = isset($partes[1]) ? $partes[1] : '';
            $this->numeroEnteroEnLetras = NumberToWords::convertir(floor($this->total_a_b_c));
            $this->numeroDecimalEnLetras = NumberToWords::convertir($this->parte_decimal);
            $this->totalLiteral = $this->numeroEnteroEnLetras . $this->parte_decimal . '/100 BOLIVIANOS.';

            //Para mostrar en la vista formateado en miles y decimales
            $this->m_total_salario_cotizable = number_format($this->salario_cotizable, 2, ',', '.');
            $this->m_importe_aporte_patronal = number_format($this->importe_aporte_patronal, 2, ',', '.');
            $this->m_monto_demasia = number_format($this->monto_demasia, 2, ',', '.');
            $this->m_actualizacion_aporte = number_format($this->actualizacion_aporte, 2, ',', '.');
            $this->m_importe_actualizacion_aporte = number_format($this->importe_actualizacion_aporte, 2, ',', '.');
            $this->m_subtotal_liquidacion = number_format($this->subtotal_liquidacion, 2, ',', '.');
            $this->m_intereses = number_format($this->intereses, 2, ',', '.');
            $this->m_multas_sin_intereses = number_format($this->multas_sin_intereses, 2, ',', '.');
            $this->m_multas_no_planilla = number_format($this->multas_no_planilla, 2, ',', '.');
            $this->m_subtotal_recargos = number_format($this->subtotal_recargos, 2, ',', '.');
            $this->m_monto_deduccion = number_format($this->monto_deduccion, 2, ',', '.');
            $this->m_ministerio = number_format($this->ministerio, 2, ',', '.');
            $this->m_subtotal_deducciones = number_format($this->subtotal_deducciones, 2, ',', '.');
            $this->m_total_a_b_c = number_format($this->total_a_b_c, 2, ',', '.');
            $this->m_tasa = number_format($this->tasa * 100, 2) . '%';
            $this->m_tasa_clase = number_format($this->tasa_clase * 100, 0) . '%';
            
            
        } else {
            $this->dispatch('IntroduzcaFecha');
            $this->estado_alerta = 2;
            $this->abrir_comprobante = 0;
            
        }
        $this->fecha_pago = null;
        
    }

    public function obtenerTasaInteres()
    {
        $obtener_tasa = TasaInteres::where('estado', true)
            ->orderBy('id', 'desc')
            ->first();

        $dato_valor = $obtener_tasa->valor;
        return $dato_valor;
    }

    public function obtenerUFV($fecha)
    {
        /* Para obtener el valor de la UFV de la fecha indicada */
        $obtener_ufv = Ufv::where('fecha', $fecha)
            ->where('estado', true)
            ->first();
        if ($obtener_ufv) {
            return $obtener_ufv->valor;
        } else {
            return null;
        }
    }

    public function atras()
    {
        return redirect()->route('aportes.aportesinstitucionalesExternos.index');
    }
}
