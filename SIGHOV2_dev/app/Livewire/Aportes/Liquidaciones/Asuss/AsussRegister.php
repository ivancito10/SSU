<?php
namespace App\Livewire\Aportes\Liquidaciones\Asuss;

use Livewire\Component;
use App\Models\Aportes\Mes;
use App\Models\Aportes\FormaPago;
use App\Models\Aportes\LiquidacionMinisterioAsuss;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;

class AsussRegister extends Component
{
    protected $listeners = ['guardarAsuss'];
    public $id_mes;
    public $idAsuss;
    public $gestion;
    public $numero_mes;
    public $fecha_liquidacion;
    public $startDate;
    public $endDate;
    public $estado=true; 

    public function render()
    {
        $meses = Mes::get();
        $this->gestion = Carbon::now()->year;
        return view('livewire.Aportes.liquidaciones.asuss.asuss-register', compact('meses' ));
    
    }
 
    public function guardarAsuss(){
        DB::beginTransaction();
        try {
            // Verificar que los campos mes, gestion, y fecha_liquidacion estén presentes
            if (!$this->id_mes || !$this->gestion || !$this->fecha_liquidacion) {
               $this->dispatch('datosImcompletos'); // Puedes crear un evento para notificar al usuario
                return;
        }
             // se obtiene el numero del mes
                $this->numero_mes = Mes::where('id', $this->id_mes)->value('numero');
                $startDate = Carbon::create($this->gestion, $this->numero_mes, 1, 0, 0, 0);
                $endDate = $startDate->copy()->endOfMonth();
                //dd($startDate, $endDate, $this->fecha_liquidacion,$this->estado);

                //Realizar la consulta
                $rentista  = FormaPago::whereBetween('fecha_pago', [$startDate, $endDate])
                    ->join('aportes.comprobante as c', 'c.id', '=', 'aportes.forma_pago.id_comprobante')
                    ->join('aportes.institucion as i', 'i.id', '=', 'c.id_institucion')
                    ->join('aportes.clase_aportante as ca', 'ca.id', '=', 'i.id_clase_aportante')
                    ->join('aportes.mes as m', 'm.id', '=', 'c.id_mes')
                    ->where('ca.nombre', 'like', 'RENTISTA')
                    ->where('c.id_estado', '=', 4)
                    ->select(
                        'forma_pago.fecha_pago',
                        'c.id as compId',
                        'c.nro_registro_comprobante',
                        'c.total_comprobante',
                        'c.cantidad_eventuales',
                        'c.cantidad_permanentes',
                        'c.gestion',
                        'c.total_salario_cotizable',
                        'c.total_comprobante',
                        'c.id_mes',
                        'c.fecha_liquidacion',
                        'm.mes',
                        'i.nombre as nombre_institucion',
                        'i.id_clase_aportante',
                        'ca.nombre as nombre_clase_aportante',

                    )
                    ->get();
                // para patronal 
                $patronal  = FormaPago::whereBetween('fecha_pago', [$startDate, $endDate])
                    ->join('aportes.comprobante as c', 'c.id', '=', 'aportes.forma_pago.id_comprobante')
                    ->join('aportes.institucion as i', 'i.id', '=', 'c.id_institucion')
                    ->join('aportes.clase_aportante as ca', 'ca.id', '=', 'i.id_clase_aportante')
                    ->join('aportes.mes as m', 'm.id', '=', 'c.id_mes')
                    ->where('ca.nombre', 'like', 'PATRONAL')
                    ->where('c.id_estado', '=', 4)
                    ->select(
                        'forma_pago.fecha_pago',
                        'c.id as compId',
                        'c.nro_registro_comprobante',
                        'c.total_comprobante',
                        'c.cantidad_eventuales',
                        'c.cantidad_permanentes',
                        'c.gestion',
                        'c.total_salario_cotizable',
                        'c.total_comprobante',
                        'c.id_mes',
                        'c.fecha_liquidacion',
                        'm.mes',
                        'i.nombre as nombre_institucion',
                        'i.id_clase_aportante',
                        'ca.nombre as nombre_clase_aportante',

                    )
                    ->get();
                // PARA TRASPASO
                $traspaso  = FormaPago::whereBetween('fecha_pago', [$startDate, $endDate])
                    ->join('aportes.comprobante as c', 'c.id', '=', 'aportes.forma_pago.id_comprobante')
                    ->join('aportes.institucion as i', 'i.id', '=', 'c.id_institucion')
                    ->join('aportes.clase_aportante as ca', 'ca.id', '=', 'i.id_clase_aportante')
                    ->join('aportes.mes as m', 'm.id', '=', 'c.id_mes')
                    ->where('ca.nombre', 'like', 'TRASPASO')
                    ->where('c.id_estado', '=', 4)
                    ->select(
                        'forma_pago.fecha_pago',
                        'c.id as compId',
                        'c.nro_registro_comprobante',
                        'c.total_comprobante',
                        'c.cantidad_eventuales',
                        'c.cantidad_permanentes',
                        'c.gestion',
                        'c.total_salario_cotizable',
                        'c.total_comprobante',
                        'c.id_mes',
                        'c.fecha_liquidacion',
                        'm.mes',
                        'i.nombre as nombre_institucion',
                        'i.id_clase_aportante',
                        'ca.nombre as nombre_clase_aportante',

                    )
                    ->get();
                // PARA VOLUNTARIOS
                $voluntarios  = FormaPago::whereBetween('fecha_pago', [$startDate, $endDate])
                    ->join('aportes.comprobante as c', 'c.id', '=', 'aportes.forma_pago.id_comprobante')
                    ->join('aportes.aporte_v as v', 'v.id', '=', 'c.id_aporte_v')
                    ->join('aportes.contrato_voluntario as con','con.id','=','v.id_contrato_voluntario')
                    ->join('administracion.persona as p', 'p.id', '=', 'con.id_persona')
                    ->join('aportes.mes as m', 'm.id', '=', 'c.id_mes')
                    ->where('c.id_estado', '=', 4)
                    ->select(
                        'forma_pago.fecha_pago',
                        'c.id as compId',
                        'v.id',
                        'p.nombres',
                        'p.p_apellido',
                        'p.s_apellido',
                        'c.nro_registro_comprobante',
                        'c.total_comprobante',
                        'c.cantidad_eventuales',
                        'c.cantidad_permanentes',
                        'c.gestion',
                        'c.total_salario_cotizable',
                        'c.total_comprobante',
                        'c.id_mes',
                        'c.fecha_liquidacion',
                        'm.mes',
                    )
                    ->get();
                // calcular nro_instituciones_activos
                $nroInstitucionesActivosTraspaso= $traspaso->count(); 
                $nroInstitucionesActivosPatronal= $patronal->count();
                $nroInstitucionesActivos= $nroInstitucionesActivosTraspaso + $nroInstitucionesActivosPatronal;

                // calcular el nro de instituciones pasivas
                $nroInstitucionesActivosPasivos= $rentista->count(); 

                // calcular el numero de trabajadore activos
                $nroTrabajadoresActivos= $patronal->sum(function ($item) 
                { return $item->cantidad_permanentes + $item->cantidad_eventuales; }) + $traspaso->sum(function ($item) {
                return $item->cantidad_permanentes + $item->cantidad_eventuales; });

                // calcular el numero de trabajdartoers pasivos
                $nroTrabajadoresPasivos= $rentista->sum(function ($item)
                {return $item->cantidad_permanentes + $item->cantidad_eventuales; });

                // calcular el numero de trabajdartoers voluntarios
                $nroTrabajadoresVoluntarios= $voluntarios->sum(function ($item) 
                {return $item->cantidad_permanentes + $item->cantidad_eventuales; });
            
                // Calcular totales
                $nroCotizaciones = $rentista->sum(function ($item) {
                                    return $item->cantidad_permanentes + $item->cantidad_eventuales;
                                }) + $patronal->sum(function ($item) {
                                    return $item->cantidad_permanentes + $item->cantidad_eventuales;
                                }) + $traspaso->sum(function ($item) {
                                    return $item->cantidad_permanentes + $item->cantidad_eventuales;
                                }) + $voluntarios->sum(function ($item) {
                                    return $item->cantidad_permanentes + $item->cantidad_eventuales;
                                });

                $totalGeneral = round($rentista->sum('total_salario_cotizable') +
                    $patronal->sum('total_salario_cotizable') +
                    $traspaso->sum('total_salario_cotizable') +
                    $voluntarios->sum('total_salario_cotizable'), 2);

                $totalAporte = round(
                    $rentista->sum('total_comprobante') +
                    $patronal->sum('total_comprobante') +
                    $traspaso->sum('total_comprobante') +
                    $voluntarios->sum('total_comprobante'),2);

                $tasa05 = round($rentista->sum(function ($item) {
                    return $item->total_comprobante * 0.005;
                }) + $patronal->sum(function ($item) {
                    return $item->total_comprobante * 0.005;
                }) + $traspaso->sum(function ($item) {
                    return $item->total_comprobante * 0.005;
                }) + $voluntarios->sum(function ($item) {
                    return $item->total_comprobante * 0.005;
                }), 2);
                //dd('estoes',$nroCotizaciones,$totalGeneral, $totalAporte, $tasa05);
            
             $existeAsuss= LiquidacionMinisterioAsuss::where('id_mes',$this->id_mes)
             ->where('gestion',$this->gestion) 
             ->where('estado',true) 
             ->first();
             if($existeAsuss){
                //dd('existe yaaaa',$existeAsuss);
                $existeAsuss->update([
                    'nro_instituciones_activos'=> $nroInstitucionesActivos,
                    'nro_instituciones_pasivos' =>$nroInstitucionesActivosPasivos,
                    'nro_trabajadores_activos'=> $nroTrabajadoresActivos,
                    'nro_trabajadores_pasivos' => $nroTrabajadoresPasivos,
                    'nro_trabajadores_voluntarios' => $nroTrabajadoresVoluntarios,
                    'total_general'=> $totalGeneral,
                    'total_aporte' => $totalAporte,
                    'tasa' => $tasa05,
                    'id_mes'=> $this->id_mes,
                    'gestion'=>$this->gestion,
                    'cotizacion'=> $nroCotizaciones,
                    'fecha_liquidacion'=>$this->fecha_liquidacion,
                    'id_user_updated'=>auth()->user()->id,
                ]);
               
                $this->idAsuss=$existeAsuss->id;
               // dd($this->idAsuss);
               $this->dispatch('pg:eventRefresh-AsussTable');
                $this->dispatch('LiquidacionCorrectaAsuss');
                
                return $this->redirectRoute('aportes.liquidacion.asuss.pdf', [
                    'id'=>$this->idAsuss,
                    'mes'=>$this->numero_mes,
                    'gestion'=> $this->gestion,
                    'fechaLiquidacion'=>$this->fecha_liquidacion
                ]);

             }else{
                $asussNew = LiquidacionMinisterioAsuss::create([
                    'nro_instituciones_activos'=> $nroInstitucionesActivos,
                    'nro_instituciones_pasivos' =>$nroInstitucionesActivosPasivos,
                    'nro_trabajadores_activos'=> $nroTrabajadoresActivos,
                    'nro_trabajadores_pasivos' => $nroTrabajadoresPasivos,
                    'nro_trabajadores_voluntarios' => $nroTrabajadoresVoluntarios,
                    'total_general'=> $totalGeneral,
                    'total_aporte' => $totalAporte,
                    'tasa' => $tasa05,
                    'cotizacion'=> $nroCotizaciones,
                    'id_mes'=> $this->id_mes,
                    'gestion'=>$this->gestion,
                    'fecha_liquidacion'=>$this->fecha_liquidacion,
                    'estado' =>$this->estado,
                    'id_user_created'=>auth()->user()->id,
                ]);
                $this->idAsuss=$asussNew->id;
                //dd($this->idAsuss);
             }
             DB::commit();  

            $this->dispatch('pg:eventRefresh-AsussTable');

            // para que se descargue el pdf de agenda especialista  
            $this->js('window.open("' . route('aportes.liquidacion.asuss.pdf',
            ['id' => $this->idAsuss, 'mes' => $this->numero_mes, 'gestion'=> $this->gestion, 
            'fechaLiquidacion'=>$this->fecha_liquidacion ]) . '", "_blank")'); 

            /* $this->dispatch('LiquidacionCorrectaAsuss');
                return $this->redirectRoute('aportes.liquidacion.asuss.pdf', [
                    'id'=>$this->idAsuss,
                    'mes'=>$this->numero_mes,
                    'gestion'=> $this->gestion,
                    'fechaLiquidacion'=>$this->fecha_liquidacion
                ]);  */ 

        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            $this->dispatch('LiquidacionIncorrectaAsuss');
        }  
        
        //$idNewAsuss = $asussNew->id;

        //dd($idNewAsuss);
    }  
}