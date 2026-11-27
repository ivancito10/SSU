<?php

namespace App\Livewire\Aportes\AportesVoluntarios;

use Livewire\Component;
use App\Models\Aportes\AporteVoluntario;
use App\Models\Aportes\ContratoVoluntario;
use App\Models\Aportes\Comprobante;
use App\Models\Aportes\FormaPago;
use App\Models\Aportes\Mes;
use App\Models\Aportes\Ufv;
use App\Models\Administracion\Zona;
use App\Models\Administracion\Persona;
use Carbon\Carbon;
use Livewire\Attributes\On;
use App\Mail\Aportes\LiquidacionAporteVoluntarioMailable;
use Illuminate\Support\Facades\Mail;
use App\Models\Administracion\DocumentoPDF;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use App\Services\Tesoreria\RecibosCajaService;

class AporteVoluntarioNuevo extends Component
{
    #[Validate]
    public $idContratoVol;
    public $aportanteVoluntarioId;

    //Para la forma de pago
    #[Validate]
    public $fechaPago;
    #[Validate]
    public $tipoPago;
    #[Validate]
    public $nroFormaPago;
    #[Validate]
    public $montoTotalPago;

    public $totalAporteActualizado;

    //Para la liquidacion
    public $totalGanado;
    public $totalAporte;
    #[Validate]
    public $mesId;
    #[Validate]
    public $gestion;

    //Atributos tabla Comprobante
    public $idComprobante;
    public $nroComprobante;
    public $nroComprobanteGestion;
    public $fechaLiquidacion;
    public $cantidadPermanentes = 1;
    public $factorActual = 1;
    public $subTotalLiquidacion;
    public $tasaInteres;
    public $importeMultaInteres = 0;
    public $importeMultaNoPlanilla = 0;
    public $montoDemasia = 0;
    public $demasiaDescripcion;
    public $montoDeduccion = 0;
    public $deduccionDescripcion;
    public $montoTotalComprobante;
    //public $totalComprobante;
    public $idAporteV;
    public $idEstado = 4;
    public $subTotalRecargos = 0;
    public $subTotalDeducciones = 0;
    public $importeActualizacionAporte = 0;



    public $nroComproAnterior, $nroComproAnterior_m;
    public $totalComproAnterior, $fechaLiquidacion_ant;
    public  $ids_recibos = [];
    public  $recibos_seleccionados = [];
    protected $listeners = ['crearNuevoAporte'];

    public function render()
    {
        $this->gestion = Carbon::now()->year;
        $this->nroComprobante = $this->obtenerNroComprobante();
        $this->nroComprobanteGestion = $this->nroComprobante . ' / ' . $this->gestion;
        if ($this->obtenerComprobanteAnterior() != null) {
            $this->nroComproAnterior = $this->obtenerComprobanteAnterior()->nro_registro_comprobante;
            $this->nroComproAnterior_m = $this->obtenerComprobanteAnterior()->nro_registro_comprobante . '/' . $this->obtenerComprobanteAnterior()->gestion;
            $this->totalComproAnterior = $this->obtenerComprobanteAnterior()->total_comprobante;
            $this->fechaLiquidacion_ant = Carbon::parse($this->obtenerComprobanteAnterior()->fecha_liquidacion)->format('d/m/Y');
        } else {
            $this->nroComproAnterior = 0;
            $this->totalComproAnterior = 0;
        }
        $zonas = Zona::all();
        $persona = Persona::find(ContratoVoluntario::find($this->idContratoVol)->id_persona);
        $meses = Mes::all();
        return view('livewire.Aportes.aportes-voluntarios.aporte-voluntario-nuevo', compact('zonas', 'persona', 'meses'));
    }

    public function rules()
    {
        return [
            'fechaPago' => 'required',
            'montoTotalPago' => 'required',
            'tipoPago' => 'required',
            'nroFormaPago' => 'required',
            'fechaLiquidacion' => 'required',
            'mesId' => 'required',
            'gestion' => 'required',
        ];
    }

    public function calculoTotalAporte()
    {
        //if (empty($this->fechaPago)) {
        //    return; 
        //}
        //
        //
        //try {
        //    $fecha = Carbon::createFromFormat('Y-m-d', $this->fechaPago);
        //    $primeraUfv = Ufv::orderBy('id', 'asc')->first();
        //    if($fecha->format('Y') < Carbon::parse($primeraUfv->fecha)->format('Y')){
        //        return;
        //    }   
        //} catch (\Exception $e) {
        //    return;
        //}

        if ($this->montoDeduccion == null) {
            $this->montoDeduccion = 0;
        }
        if ($this->montoDemasia == null) {
            $this->montoDemasia = 0;
        }

        $ufvFechaPago = Ufv::where('fecha', '=', $this->fechaPago)
            ->where('estado', '=', true)
            ->first();

        if ($ufvFechaPago) {
            $this->montoTotalPago = round($ufvFechaPago->valor * 500, 2);

            $this->totalAporte = $this->montoTotalPago;
            $this->totalAporteActualizado = round($this->montoTotalPago - $this->montoDeduccion + $this->montoDemasia, 2);
            $this->totalGanado = round($this->totalAporte * 10, 2);
        } else {
            $this->dispatch('noExisteUfv');
        }
    }

    public function obtenerNroComprobante()
    {
        $currentYear = Carbon::now()->year;
        $ultimoRegistro = Comprobante::where('id_estado', 4)
            ->whereYear('fecha_liquidacion', $currentYear) // Aseguramos que la fecha sea del año actual
            ->orderByRaw("EXTRACT(YEAR FROM fecha_liquidacion) DESC") // Ordenar por año (descendente)
            ->orderBy('nro_registro_comprobante', 'desc') // Luego por el número de comprobante (descendente)
            ->first();
        if (!$ultimoRegistro) {
            return 1;
        }
        return $ultimoRegistro->nro_registro_comprobante + 1;
    }

    public function obtenerComprobanteAnterior()
    {
        $aportev = AporteVoluntario::where('id_contrato_voluntario', '=', $this->idContratoVol)
            ->orderBy('id', 'desc')
            ->first();
        if ($aportev) {
            $comprobanteAnterior = Comprobante::where('id_aporte_v', '=', $aportev->id)
                ->orderBy('id', 'desc')
                ->first();
            if ($comprobanteAnterior) {
                return $comprobanteAnterior;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function crearNuevoAporte()
    {
        $validated = $this->validate();
        DB::beginTransaction();

        try {
            $aporteVoluntario = AporteVoluntario::create([
                'total_ganado' => $this->totalGanado,
                'total_aporte' => $this->totalAporte,
                'id_contrato_voluntario' => $this->idContratoVol,
                'gestion' => $this->gestion,
                'id_mes' => $this->mesId,
                'afiliado' => false,
                'id_user_created' => auth()->user()->id,
            ]);

            $this->idAporteV = $aporteVoluntario->id;
            $this->montoTotalComprobante = $this->totalAporte + $this->montoDemasia;
            $this->montoTotalComprobante = $this->montoTotalComprobante - $this->montoDeduccion;

            $this->subTotalLiquidacion = $this->totalAporte + $this->montoDemasia;

            //Para obtener la Tasa de Interes

            $comprobante = Comprobante::create([
                'nro_registro_comprobante' => $this->nroComprobante,
                'gestion' => $this->gestion,
                'fecha_liquidacion' => $this->fechaLiquidacion,
                'cantidad_permanentes' => $this->cantidadPermanentes,
                'factor_actual' => $this->factorActual,
                'total_salario_cotizable' => $this->totalGanado,
                'importe_aporte_patronal' => $this->totalAporte,
                'subtotal_liquidacion' => $this->subTotalLiquidacion,
                'importe_multa_interes' => $this->importeMultaInteres,
                'importe_multa_no_planilla' => $this->importeMultaNoPlanilla,
                'monto_demasia' => $this->montoDemasia,
                'demasia_descripcion' => $this->demasiaDescripcion,
                'monto_deduccion' => $this->montoDeduccion,
                'deduccion_descripcion' => $this->deduccionDescripcion,
                'monto_total_comprobante' => $this->montoTotalComprobante,
                'total_comprobante' => $this->montoTotalComprobante,
                'id_aporte_v' => $this->idAporteV,
                'id_mes' => $this->mesId,
                'id_estado' => $this->idEstado,
                'subtotal_recargos' => $this->subTotalRecargos,
                'subtotal_deducciones' => $this->subTotalDeducciones,
                'importe_actualzacion_aporte' => $this->importeActualizacionAporte,
                'total_compensacion' => 0,
                'id_user_created' => auth()->user()->id,
            ]);

            $this->idComprobante = $comprobante->id;

            // Actualizamos el aporte_v con id_comprobante  
            aporteVoluntario::where('id', $this->idAporteV)
                ->update(['id_comp' => $this->idComprobante]);

            $formaPago = FormaPago::create([
                'id_comprobante' => $this->idComprobante,
                'fecha_pago' => $this->fechaPago,
                'tipo_pago' => $this->tipoPago,
                'nro_pago' => $this->nroFormaPago,
                'nro_compro_anterior' => $this->nroComproAnterior,
                'total_compro_anterior' => $this->totalComproAnterior,
                'total_forma_pago' => $this->totalAporteActualizado,
                'id_user_created' => auth()->user()->id,
                'id_persona_caja' => auth()->user()->id,
            ]);
            //dd($formaPago);
            if ($formaPago) {
                //dd($this->idContratoVol);
                $DatosPersona = ContratoVoluntario::find($this->idContratoVol);
                $id_persona = $DatosPersona->id_persona;
                /*$this->redirectRoute('pdf.generatecopia.voluntario', [
                    'rowId'=>$this->idAporteV
                ]);*/
                $fechaHoraActual = date('Y-m-d H:i:s');

                $textoParaHash = $fechaHoraActual;

                // Paso 3: Generar el hash SHA-256 completo
                $cod_seguridad = hash('sha256', $textoParaHash);

                //logica para verificar PDF en la BD
                $documentoPdf = [
                    'id_tipo_documento'     => 6, // 6 = pago de aportes voluntarios
                    'codigo_seguridad'      => $cod_seguridad,
                    'modulo'                => 'APORTES', // O el módulo correspondiente
                    'fecha_generado'        => now(),
                    'cantidad_paginas'      => 1, //como 
                    'pertenece_a'           => $id_persona, // ID del certificado o dato relevante
                    'fecha_vigencia_inicio' => now(), // O la fecha que corresponda
                    'fecha_vigencia_fin'    => null,  // Si aplica
                    //'fecha_vigencia_fin'    => $fechaVigenciaFin,
                    'indefinido'            => false,  // true o false según corresponda
                    'id_usuario_created'    => auth()->user()->id,
                ];
                //dd($documentoPdf);
                $pdfCreado = DocumentoPDF::create($documentoPdf);

                $servicio = new RecibosCajaService();
                // dd($this->ids_recibos, $this->compId);
                $respuesta = $servicio->asignarComprobante($this->ids_recibos, $this->idComprobante);
                // dd($this->fechaPago);
                //dd($this->idComprobante);
                DB::commit();

                $this->js('window.open("/aportes/pdf/voluntarios/' . $this->idComprobante . '", "_blank")');

                //$this->dispatch('aporteVoluntarioCreado');
                return redirect()->route('aportes.aportevoluntario.index')->with('evento', 'aporteVoluntarioCreado');
            } else {
                DB::rollBack();
                $this->dispatch('aporteVoluntarioCancelado');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            // dd($th->getMessage());
            $this->dispatch('error');
        }
    }

    public function cancelarAporte()
    {
        return redirect()->route('aportes.aportevoluntario.index')->with('evento', 'aporteVoluntarioCancelado');
    }

    public function atras()
    {
        return redirect()->route('aportes.aportevoluntario.index');
    }
    public function agregar_recibo_caja()
    {
        // dd('modal bro');
        $this->dispatch('modalListaRecibosVoluntario');
    }
    #[On('guardarReciboOficialVoluntarios')]
    public function guardarReciboOficialVoluntarios($data)
    {
        // dd($data);
        $servicio = new RecibosCajaService();
        $recibo = $servicio->reciboSeleccionado($data);
        if (!$recibo) {
            $this->dispatch('conexionRecibo');
            $this->dispatch('modalListaRecibosVoluntarioQuitar');
            return;
        }
        // Verifica que no esté ya agregado
        $existe = collect($this->recibos_seleccionados)->contains('id', $recibo['id']);

        if (!$existe) {
            $this->ids_recibos[] = $recibo['id'];
            $this->recibos_seleccionados[] = $recibo;
        }else{
            $this->dispatch('reciboYaAgregadoVoluntario');
            return;
            // $this->dispatch('modalListaRecibosVoluntarioQuitar');
        }
        // llenado de campos
        $this->llenarFormaPago($recibo);
        // dd($this->recibos_seleccionados);

        // $this->total_forma_pago=$data;
        // $this->dispatch('registroTotalPago');
        $this->dispatch('modalListaRecibosVoluntarioQuitar');
    }
    public function llenarFormaPago($recibo)
    {
        if (count($this->recibos_seleccionados) > 0) {
            $this->fechaPago = $this->recibos_seleccionados[0]['fecha_recibo'];
            $this->calculoTotalAporte();
            // dd($this->recibos_seleccionados[0]['recibo_oficial_caja_detalle_recibo'][0]['tipo_deposito']);
            $tipo_pago = $this->recibos_seleccionados[0]['recibo_oficial_caja_detalle_recibo'][0]['tipo_deposito'];
            if ($tipo_pago == 'DEPOSITO BANCARIO' || $tipo_pago == 'TRANSFERENCIA') {
                $tipo_pago = 'BANCO';
                // $this->nro_pago_forma = $this->recibos_seleccionados[0]['recibo_oficial_caja_detalle_recibo'];
            }
            $this->tipoPago = $tipo_pago;
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
            //$this->montoTotalPago = $total_depositado;
            $this->nroFormaPago = $nro_documento ?? null;
        }
    }
    public function eliminarRecibo($id_recibo)
    {
        $this->dispatch('eliminarReciboVoluntario');
        $this->recibos_seleccionados = array_values(
            array_filter(
                $this->recibos_seleccionados,
                fn($recibo) => $recibo['id'] != $id_recibo
            )
        );
        $this->fechaPago = $this->recibos_seleccionados[0]['fecha_recibo'] ?? null;
        if (count($this->recibos_seleccionados) == 0) {
            $this->montoTotalPago = null;
            $this->nroFormaPago = null;
            $this->tipoPago = null;
            $this->fechaPago = null;
            $this->totalGanado = null;
            $this->totalAporte=null;
            $this->totalAporteActualizado=null;
        } else {
            $this->llenarFormaPago($this->recibos_seleccionados[0]);
        }
    }
}
