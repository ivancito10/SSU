<?php

namespace App\Livewire\Aportes\AportesInstitucionales;

use Livewire\Component;
use App\Models\Aportes\Comprobante;
use App\Models\Aportes\Institucion;
use App\Models\Aportes\FormaPago;
use App\Models\Aportes\ClaseAportante;
use App\Models\Aportes\Mes;
use App\Services\Tesoreria\RecibosCajaService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On;

class AportesFormaPago extends Component
{

    #[Validate]
    public $compId;
    public $id_comp, $nro_compro_anterior_m, $total_compro_anterior, $nombre_institucion, $tipo_clase, $tgn, $nro_compro_anterior;
    public $fecha_liquidado;
    public $fecha_liquidado_ant;
    public $reintegro_ant;
    public $planilla_adicional_ant;
    public $beneficio_social_ant;
    public $beneficio_social;
    public $planilla_adicional;
    public $reintegro;
    public $retroactivo;
    public $nombre_mes, $gestion, $fecha_pago, $tipo_pago, $nro_pago_forma, $total_forma_pago;

    public $monto_excedente, $monto_deduccion_r, $excedente_descripcion, $deduccion_descripcion_r, $importe;
    public $id_mes, $importe_aporte_patronal,  $nit, $anio_sistema, $m_nro_registro;
    public  $ids_recibos = [];
    public  $recibos_seleccionados = [];

    public function render()
    {
        return view('livewire.Aportes.aportes-institucionales.aportes-forma-pago');
    }



    public function mount()
    {
        $this->mostrarComprobante($this->compId);
        $this->mostrarFormaPago($this->compId);
    }
    public function rules()
    {
        return [
            'fecha_pago' => 'required',
            'tipo_pago' => 'required',
            'nro_pago_forma' => 'required',
            'total_forma_pago' => 'required',
        ];
    }
    public $nro_pago, $salario_cotizable_m, $aporte_a_liquidar,
        $total_compensacion, $valor_tgn, $forma_pago_c_m, $nombre_clase, $tasa_clase;
    public function mostrarComprobante($compId)
    {

        if ($compId) {
            $this->id_comp = $compId;
            $comprobante = Comprobante::find($compId);

            if ($comprobante) {
                $this->id_mes = $comprobante->id_mes;
                $this->gestion = $comprobante->gestion;
                $this->salario_cotizable_m = number_format($comprobante->total_salario_cotizable, 2, ',', '.');

                $this->beneficio_social = $comprobante->beneficio_social;
                $this->planilla_adicional = $comprobante->planilla_adicional;
                $this->reintegro = $comprobante->reintegro;
                $this->retroactivo = $comprobante->retroactivo;
                $this->monto_deduccion_r = $comprobante->monto_deduccion;
                $this->monto_excedente = $comprobante->monto_demasia;


                $this->fecha_liquidado = $comprobante->fecha_liquidacion;
                $id_estado = $comprobante->id_estado;
                $this->total_compensacion = $comprobante->total_compensacion;


                $this->importe_aporte_patronal = $comprobante->importe_aporte_patronal;
            }


            $institucionActual = Institucion::find($comprobante->id_institucion);
            if ($institucionActual) {
                $this->nombre_institucion = $institucionActual->nombre;
                $this->nit = $institucionActual->nit;
                $this->tgn = $institucionActual->tgn;
            }

            $claseActual = ClaseAportante::find($institucionActual->id_clase_aportante);
            if ($claseActual) {
                $this->nombre_clase = $claseActual->nombre;
                $this->tipo_clase = $claseActual->tipo;
                $this->tasa_clase = $claseActual->porcentaje;
            }
            $mesActual = Mes::find($comprobante->id_mes);
            if ($mesActual) {
                $this->nombre_mes = $mesActual->mes;
            }
            // monto de importe
            $this->importe = number_format(($comprobante->importe_aporte_patronal), 2, ',', '.');


            if ($this->tgn == true) {
                $this->valor_tgn = number_format($comprobante->importe_aporte_patronal * 0.05, 2, ',', '.');
                // monto a liquidar 
                $this->aporte_a_liquidar = number_format(($comprobante->importe_aporte_patronal) + ($comprobante->total_compensacion) - ($comprobante->importe_aporte_patronal * 0.05), 2, ',', '.');
            } elseif ($this->tgn == false) {
                $this->valor_tgn = 0;
                // monto a liquidar 
                $this->aporte_a_liquidar = number_format(($comprobante->importe_aporte_patronal) + ($comprobante->total_compensacion), 2, ',', '.');
            }


            $this->anio_sistema = Carbon::now()->year;
            $this->m_nro_registro = $this->obtenerUltimoNroComprobante() . ' / ' . $this->anio_sistema;
            // dd($this->m_nro_registro);


            return $comprobante;
        }
    }
    public function mostrarFormaPago($data)
    {
        $comprobante = Comprobante::find($data);
        // OJO AQUI
        $datos_compro_ant = Comprobante::where('id_institucion', '=', $comprobante->id_institucion)
            ->where('id_estado', '=', 4)
            ->orderby('id', 'desc')
            ->first();
        // dd($datos_compro_ant);
        if ($datos_compro_ant != null) {
            $this->nro_compro_anterior = $datos_compro_ant->nro_registro_comprobante;
            $this->nro_compro_anterior_m = $datos_compro_ant->nro_registro_comprobante . '/' . $datos_compro_ant->gestion;
            $this->total_compro_anterior = $datos_compro_ant->total_comprobante;
            $this->fecha_liquidado_ant = Carbon::parse($datos_compro_ant->fecha_liquidacion)->format('d/m/Y');
            $this->beneficio_social_ant = $datos_compro_ant->beneficio_social;
            $this->planilla_adicional_ant = $datos_compro_ant->planilla_adicional;
            $this->reintegro_ant = $datos_compro_ant->reintegro;
        } else {
            $this->nro_compro_anterior = 0;
            $this->total_compro_anterior = 0;
            $this->fecha_liquidado = '';
            $this->beneficio_social = '';
            $this->planilla_adicional = '';
            $this->reintegro = '';
        }
    }

    public function obtenerUltimoNroComprobante()
    {
        $currentYear = Carbon::now()->year;
        $ultimoRegistro = Comprobante::whereNotNull('nro_registro_comprobante')
            //where('id_estado', 4)
            //  ->whereYear('fecha_liquidacion', $currentYear) // Aseguramos que la fecha sea del año actual
            //->orderByRaw("EXTRACT(YEAR FROM fecha_liquidacion) DESC") // Ordenar por año (descendente)
            //->orderBy('nro_registro_comprobante', 'desc') // Luego por el número de comprobante (descendente)
            ->orderBy('id', 'desc')
            ->first();
        // dd($ultimoRegistro->nro_registro_comprobante);
        if (!$ultimoRegistro) {
            return 1;
        }
        return $ultimoRegistro->nro_registro_comprobante + 1;
    }

    public function link_preliquidacion()
    {
        return redirect()->route('aportes.aporte_institucional.preliquidacion', ['id' => $this->compId]);
    }

    #[On('RegistrarFromaDePago')]
    public function RegistrarFromaDePago()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            if ($this->monto_excedente == null) {
                $this->monto_excedente = 0;
            }
            if ($this->monto_deduccion_r == null) {
                $this->monto_deduccion_r = 0;
            }

            $ComprobanteOtros = Comprobante::find($this->compId);
            if ($ComprobanteOtros) {
                $ComprobanteOtros->monto_demasia = $this->monto_excedente;
                $ComprobanteOtros->demasia_descripcion = $this->excedente_descripcion;
                $ComprobanteOtros->monto_deduccion = $this->monto_deduccion_r;
                $ComprobanteOtros->deduccion_descripcion = $this->deduccion_descripcion_r;
                $ComprobanteOtros->id_estado = 3;
                $ComprobanteOtros->fecha_registrado = Carbon::now();
                $ComprobanteOtros->id_user_updated = auth()->user()->id;
                $ComprobanteOtros->save();
            }


            $forma_de_pago = FormaPago::create([
                'id_comprobante' => $this->compId,
                'fecha_pago' => $this->fecha_pago,
                'tipo_pago' => $this->tipo_pago,
                'nro_pago' => $this->nro_pago_forma,
                'total_forma_pago' => $this->total_forma_pago,
                'nro_compro_anterior' => $this->nro_compro_anterior,
                'total_compro_anterior' => $this->total_compro_anterior,
                'id_persona_caja' => auth()->user()->id,
                'id_user_created' => auth()->user()->id,
            ]);
            $servicio = new RecibosCajaService();
            // dd($this->ids_recibos, $this->compId);
            $respuesta = $servicio->asignarComprobante($this->ids_recibos, $this->compId);
            // dd($respuesta);
            DB::commit();
            $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionales');
            $this->dispatch('SeRegistroFormaDePago');
            redirect()->route('aportes.aportesinstitucionales.index');

            $this->reset();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('NoSeRegistroFormaDePago');
        }
    }

    public function atras()
    {
        return redirect()->route('aportes.aportesinstitucionales.index');
    }
    public function agregar_recibo_caja()
    {
        $this->dispatch('modalListaRecibos');
    }
    #[On('guardarReciboOficial')]
    public function guardarReciboOficial($data)
    {
        $servicio = new RecibosCajaService();
        $recibo = $servicio->reciboSeleccionado($data);
        if (!$recibo) {
            $this->dispatch('conexionRecibo');
            $this->dispatch('modalListaRecibosQuitar');
            return;
        }
        // Verifica que no esté ya agregado
        $existe = collect($this->recibos_seleccionados)->contains('id', $recibo['id']);
        if (!$existe) {
            $this->ids_recibos[] = $recibo['id'];
            $this->recibos_seleccionados[] = $recibo;
        } else {
            // dd($existe,'vino para mostrar alerta');
            // $this->dispatch('reciboYaAgregado');
            $this->dispatch('reciboYaAgregado');
            return;
        }
        // llenado de campos
        $this->llenarFormaPago($recibo);
        // dd($this->recibos_seleccionados);

        // $this->total_forma_pago=$data;
        // $this->dispatch('registroTotalPago');
        $this->dispatch('modalListaRecibosQuitar');
    }
    public function llenarFormaPago($recibo)
    {

        if (count($this->recibos_seleccionados) > 0) {
            $this->fecha_pago = $this->recibos_seleccionados[0]['fecha_recibo'];
            // dd($this->recibos_seleccionados[0]['recibo_oficial_caja_detalle_recibo'][0]['tipo_deposito']);
            $tipo_pago = $this->recibos_seleccionados[0]['recibo_oficial_caja_detalle_recibo'][0]['tipo_deposito'];
            if ($tipo_pago == 'DEPOSITO BANCARIO' || $tipo_pago == 'TRANSFERENCIA') {
                $tipo_pago = 'BANCO';
                // $this->nro_pago_forma = $this->recibos_seleccionados[0]['recibo_oficial_caja_detalle_recibo'];
            }
            $this->tipo_pago = $tipo_pago;
            $nro_documento = '';
            $total_depositado = 0;

            foreach ($this->recibos_seleccionados as $recibo) {
                // Obtener los detalles del recibo
                $detalles_recibos = $recibo['recibo_oficial_caja_detalle_recibo'] ?? [];

                // Extraer solo los números de depósito válidos (no nulos o vacíos)
                $nros_deposito = collect($detalles_recibos)
                    ->pluck('nro_deposito')
                    ->filter() // elimina null, '', 0, etc.
                    ->implode('-');

                // Concatenar solo si existen depósitos
                if (!empty($nros_deposito)) {
                    $nro_documento = $nro_documento
                        ? $nro_documento . '-' . $nros_deposito
                        : $nros_deposito;
                }
                // Sumar el total depositado
                $total_depositado += $recibo['total_recibo'];
            }
            $this->total_forma_pago = $total_depositado;
            $this->nro_pago_forma = $nro_documento ?? null;
        }
    }
    public function eliminarRecibo($id_recibo)
    {
        $this->dispatch('eliminarRecibo');
        $this->recibos_seleccionados = array_values(
            array_filter(
                $this->recibos_seleccionados,
                fn($recibo) => $recibo['id'] != $id_recibo
            )
        );
        $this->fecha_pago = $this->recibos_seleccionados[0]['fecha_recibo'] ?? null;
        if (count($this->recibos_seleccionados) == 0) {
            $this->total_forma_pago = null;
            $this->nro_pago_forma = null;
            $this->tipo_pago = null;
        } else {

            $this->llenarFormaPago($this->recibos_seleccionados[0]);
        }
    }
}
