<?php

namespace App\Livewire\Aportes\Externos\Aporte;

use Livewire\Component;
use Carbon\Carbon;

use App\Models\Aportes\Institucion;
use App\Models\Aportes\Comprobante;
use App\Models\Aportes\Mes;
use App\Models\Aportes\ClaseAportante;
use App\Models\Aportes\FormaPago;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;

class AporteFormaPagoExt extends Component
{
    public $compId;
    use WithFileUploads;
    #[Validate]
    public $file_digital_pago;
    #[Validate]
    public $fecha_pago, $nombre_institucion, $nit, 
    $planilla_adicional, $beneficio_social, $reintegro, $retroactivo, 
    $nombre_mes, $gestion,  $importe, $aporte_a_liquidar, 
    $total_compensacion, $salario_cotizable_m;
    #[Validate]
    public  $tipo_pago, $nro_pago_forma, $total_forma_pago, $monto_excedente, $excedente_descripcion, $monto_deduccion_r, $deduccion_descripcion_r;

    public function render()
    {
        return view('livewire.aportes.externos.aporte.aporte-forma-pago-ext');
    }
    public function mount(){
        $this->mostrarComprobante($this->compId);
        $this->fecha_pago = Carbon::now()->format('Y-m-d'); 
    }
    public function rules()
    {
        return [
            'fecha_pago' => 'required',
            'tipo_pago' => 'required',
            'nro_pago_forma' => 'required',
            'total_forma_pago' => 'required',
            'file_digital_pago' => 'required|file|mimes:pdf',
        ];
    }
    public function mostrarComprobante($compId){
        if ($compId) {
            
            $comprobante = Comprobante::find($compId);
            $id_institucion = $comprobante->id_institucion;
            $id_mes = $comprobante->id_mes;
            $this->gestion = $comprobante->gestion;
            $mes = Mes::find($id_mes);
            $this->nombre_mes = $mes->mes;
            
            $this->planilla_adicional = $comprobante->planilla_adicional;
            $this->beneficio_social = $comprobante->beneficio_social;
            $this->reintegro = $comprobante->reintegro;
            $this->retroactivo = $comprobante->retroactivo;
            $this->salario_cotizable_m = number_format($comprobante->total_salario_cotizable, 2, ',', '.');
            $this->total_compensacion = $comprobante->total_compensacion;
 
            $institucionActual = Institucion::find($id_institucion);
            $this->nombre_institucion = $institucionActual->nombre;
            $this->nit = $institucionActual->nit;

            $claseActual = ClaseAportante::find($institucionActual->id_clase_aportante);
            if($claseActual){
                $tasa_clase = $claseActual->porcentaje;
            }
            // monto de importe
            $this->importe = number_format(($comprobante->total_salario_cotizable * $tasa_clase) , 2, ',', '.');
            
             // monto a liquidar 
             $this->aporte_a_liquidar = number_format(($comprobante->total_salario_cotizable * $tasa_clase) + ($comprobante->total_compensacion), 2, ',', '.');
            return $comprobante;
        }
    }
    #[On('registrar_forma_de_pago_ext')]
    public function registrar_forma_de_pago_ext()
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
            $datos_compro_ant = Comprobante::where('id_institucion', '=', $this->compId)
            ->where('id_estado', '=', 4)
            ->orderby('id', 'desc')
            ->first();
            if ($datos_compro_ant != null) {
                $nro_compro_anterior = $datos_compro_ant->nro_registro_comprobante;
                $total_compro_anterior = $datos_compro_ant->total_comprobante;
            } else {
                $nro_compro_anterior = 0;
                $total_compro_anterior = 0;
            }

            $ComprobanteOtros = Comprobante::find($this->compId);
            if ($ComprobanteOtros) {
                $ComprobanteOtros->monto_demasia = $this->monto_excedente;
                $ComprobanteOtros->demasia_descripcion = $this->excedente_descripcion;
                $ComprobanteOtros->monto_deduccion = $this->monto_deduccion_r;
                $ComprobanteOtros->deduccion_descripcion = $this->deduccion_descripcion_r;
                $ComprobanteOtros->id_estado = 3;
                $ComprobanteOtros->id_user_updated = auth()->user()->id;
                $ComprobanteOtros->save();
            }
            if ($this->total_forma_pago == null || $this->tipo_pago == null || $this->nro_pago_forma == null || $this->fecha_pago == null) {
                throw new \Exception('Error forzado despues de la importacion.');
            }

            $forma_de_pago = FormaPago::create([
                'id_comprobante' => $this->compId,
                'fecha_pago' => $this->fecha_pago,
                'tipo_pago' => $this->tipo_pago,
                'nro_pago' => $this->nro_pago_forma,
                'total_forma_pago' => $this->total_forma_pago,
                'nro_compro_anterior' => $nro_compro_anterior,
                'total_compro_anterior' => $total_compro_anterior,
                'id_persona_caja' => auth()->user()->id,
                'id_user_created' => auth()->user()->id,
            ]);
            $id_forma_de_pago = $forma_de_pago->id;

            if ($this->file_digital_pago) {
                //guardamos en el FT CI
                $nombre_file = 'FP_' .$id_forma_de_pago.  '.pdf';
                $this->file_digital_pago->storeAs('Aportes/FormaPago/', $nombre_file, 'ftp');
            }
            DB::commit();
            $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionalesExterno');
            $this->dispatch('guardado_ext_forma_pago');
            return redirect()->route('aportes.aportesinstitucionalesExternos.index');
            $this->reset();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('no_guardado_ext_forma_pago');
        }
    }

    public function atras()
    {
        return redirect()->route('aportes.aportesinstitucionalesExternos.index');
    }

    public function link_preliquidacion(){
        return redirect()->route('aportes.externos.aportes.preliquidacion', ['id' => $this->compId]);
    }

}
