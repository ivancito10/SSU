<?php

namespace App\Livewire\Aportes\BajasMedicas;

use Livewire\Attributes\On;
use Livewire\Component;
use Carbon\Carbon;
use App\Models\Aportes\Comprobante;
use App\Models\Aportes\AporteInstitucional;
use App\Models\Aportes\PlanillaBajaMedica;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;

class BajasRegister extends Component
{

    public $apInstiId;
    public $modalBajaMedica;
    public $compId;
    public $comprobanteId;
    public $aportanteId;

    //DATOS PERSONA APORTANTE
    public $nombres;
    public $p_apellido;
    public $s_apellido;
    public $matricula;
    public $item;
    public $cargo;
    public $institucion;

    public $mes;
    public $gestion;

    //DATOS DE BAJA
    public $total_ganado;
    public $total_ganado_dia;
    public $total_aporte;
    #[Validate]
    public $tipo_baja, $fecha_inicio, $fecha_final, $dias_baja;
    public $monto_baja_des;
    public $monto_baja, $total_comprobante;
    public $reintegro, $planilla_adicional, $beneficio_social, $retroactivo, $salario_cotizable_m, $importe, $total_compensacion, $es_baja_continua = false;
    public $continuidad = false;

    public function render()
    {
        return view('livewire.Aportes.bajas-medicas.bajas-register');
    }

    #[On('agregarBajaPlanilla')]
    public function agregarBajaPlanilla($data)
    {
        $this->mostrarBajaPlanilla($data);
        $this->dispatch('modalAbrirBajaPlanilla');
    }


    #[On('bajas')]
    public function mostrarBajaPlanilla($data)
    {
        //dd($data);
        // try {
        $this->apInstiId = $data;
        //  $this->modalBajaMedica = $data['modalBajaMedica'];

        $datos_aportante = AporteInstitucional::join('administracion.persona as p', 'p.id', '=', 'aportes.aporte_i.id_persona')
            ->where('aportes.aporte_i.id', '=', $data)
            ->first();
        // dd($datos_aportante);
        $this->nombres = $datos_aportante->nombres ?? '';
        $this->p_apellido = $datos_aportante->p_apellido ?? '';
        $this->s_apellido = $datos_aportante->s_apellido ?? '';
        $this->matricula = $datos_aportante->matricula_seguro ?? '';
        $this->item = $datos_aportante->item ?? '';
        $this->cargo = $datos_aportante->cargo ?? '';
        $this->institucion = $datos_aportante->id_institucion ?? '';
        $this->total_ganado = $datos_aportante->total_ganado ?? '';
        $this->total_ganado_dia = $datos_aportante->total_ganado_dia ?? '';
        $this->total_aporte = $datos_aportante->total_aporte ?? '';

        // $this->dispatch('openModalBajasMedicas');
        //  } catch (\Throwable $th) {
        //      $this->dispatch('error');
        //  }
    }

    public function rules()
    {
        return [
            //'institucionNuevaId' => 'required',
            'fecha_inicio' => 'required',
            'dias_baja' => 'required',
            'tipo_baja' => 'required',
            'fecha_final' => 'required',

        ];
    }
    #[On('GuardarFuncionBaja')]
    public function GuardarFuncionBaja()
    {

        //dd($this->continuidad);
        $this->validate();
        // dd('jpa');
        DB::beginTransaction();
        // try {
        if ($this->tipo_baja == 'ENFERMEDAD') {
            $tipo_baja_des = 'ENFERMEDAD';
        } elseif ($this->tipo_baja == 'MATERNIDAD') {
            $tipo_baja_des = 'MATERNIDAD';
        } else {
            $tipo_baja_des = 'ACCIDENTE LABORAL';
        }
        //  if ($this->fecha_inicio != null && $this->tipo_baja != 0) {

        $r_baja_medica = PlanillaBajaMedica::create([
            'id_aporte_i' => $this->apInstiId,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_final,
            'continuidad' => $this->continuidad,
            'tipo' => $tipo_baja_des,
            'dias_baja' => $this->dias_baja,
            'monto_baja_medica' => round($this->monto_baja, 2),
            'id_user_created' => auth()->user()->id,
        ]);
        DB::commit();
        $this->reset('tipo_baja', 'fecha_inicio', 'fecha_final', 'dias_baja', 'monto_baja_des', 'monto_baja');
        $this->dispatch('pg:eventRefresh-BajasMedicasRegistro');
        $this->dispatch('BajaMedicaRegistrado');
        $this->dispatch('modalCerrarBajaPlanilla');
        // } else {
        //     DB::rollBack();
        //     $this->reset('tipo_baja', 'fecha_inicio', 'fecha_final', 'dias_baja', 'monto_baja_des', 'monto_baja');
        //     $this->dispatch('error');
        //     $this->dispatch('modalCerrarBajaPlanilla');
        // }
        //   } catch (\Throwable $th) {
        //       DB::rollBack();
        //       $this->tipo_baja = '';
        //       $this->fecha_inicio = '';
        //       $this->dias_baja = 0;
        //       $this->monto_baja_des = '';
        //       $this->monto_baja = 0;
        //       $this->dispatch('error');
        //   }
    }

    public function CalcularMonto()
    {
        // try {
        if (($this->dias_baja <= 3 && $this->tipo_baja == 'ENFERMEDAD') || ($this->dias_baja <= 3 && $this->tipo_baja == 'ACCIDENTE')) {
            $this->fecha_final = Carbon::parse($this->fecha_inicio)
                ->addDays($this->dias_baja - 1)
                ->format('Y-m-d');
            $this->monto_baja_des = 0;
            $this->monto_baja = 0;
        } else {
            if ($this->tipo_baja == 'ENFERMEDAD') {
                if ($this->continuidad) {
                    $this->fecha_final = Carbon::parse($this->fecha_inicio)
                        ->addDays($this->dias_baja - 1)
                        ->format('Y-m-d');
                    $this->monto_baja = $this->total_ganado_dia * ($this->dias_baja) * 0.75;
                    $this->monto_baja_des = number_format($this->monto_baja, 2, '.', ',') . ' Bs.';
                } else {
                    $this->fecha_final = Carbon::parse($this->fecha_inicio)
                        ->addDays($this->dias_baja - 1)
                        ->format('Y-m-d');
                    $this->monto_baja = $this->total_ganado_dia * ($this->dias_baja - 3) * 0.75;
                    $this->monto_baja_des = number_format($this->monto_baja, 2, '.', ',') . ' Bs.';
                }
            } elseif ($this->tipo_baja == 'ACCIDENTE') {
                $this->fecha_final = Carbon::parse($this->fecha_inicio)
                    ->addDays($this->dias_baja - 1)
                    ->format('Y-m-d');
                // $this->monto_baja = $this->total_ganado_dia * ($this->dias_baja - 3) * 0.90;   
                $this->monto_baja = $this->total_ganado_dia * ($this->dias_baja) * 0.90;
                $this->monto_baja_des = number_format($this->monto_baja, 2, '.', ',') . ' Bs.';
            } else {
                $this->fecha_final = Carbon::parse($this->fecha_inicio)
                    ->addDays($this->dias_baja - 1)
                    ->format('Y-m-d');
                $this->monto_baja = $this->total_ganado_dia * $this->dias_baja * 0.90;
                $this->monto_baja_des = number_format($this->monto_baja, 2, '.', ',') . ' Bs.';
            }
        }
        //  dd($this->monto_baja, $this->monto_baja_des, $this->tipo_baja, $this->continuidad);
        //  $this->SkipRender();
        // } catch (\Throwable $th) {
        //    $this->reset('tipo_baja', 'fecha_inicio', 'dias_baja', 'monto_baja_des', 'monto_baja');

        // $this->SkipRender();
        // $this->dispatch('error');
        // }
    }
}
