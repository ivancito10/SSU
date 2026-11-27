<?php

namespace App\Livewire\Aportes\AportesVoluntarios\AportantesVoluntarios;

use Livewire\Component;
use App\Models\Administracion\Residencia;
use App\Models\Administracion\Zona;
use App\Models\Aportes\ContratoVoluntario;
use App\Models\Administracion\Persona;
use Carbon\Carbon;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class AporteVoluntarioEdit extends Component
{
    #[Validate]
    public $id_contrato;
    public $telefono1;
    public $telefono2;

    public $ci;
    public $nombre;
    public $primerApellido;
    public $segundoApellido;
    public $fechaNacimiento;
    public $complemento;
    public $descripcionContrato, $direccion, $numero, $zona, $zonaId, $correo, $estado, $personaId,
    $renovarFechaContrato, $contratoAnterior, $fechaInicioContrato, $fechaFinContrato;

    public $fecha_i_m, $fecha_f_m;

    public $zonasSelected = [] ;
    public function render()
    {
        return view('livewire.Aportes.aportes-voluntarios.aportantes-voluntarios.aporte-voluntario-edit');
    }
    
    public function mount(){
        $this->zonasSelected = Zona::orderBy('nombre', 'asc')->get();
        //$this->contratoId = $this->id_contrato;
        $this->mostrarContrato($this->id_contrato);
    }
    public function rules()
    {
        return [
            'direccion' => 'required',
            'numero' => 'required',
            'zonaId' => 'required',
            'telefono1' => 'required',
        ];
    }
    public function mostrarContrato($contratoId)
    {
        if ($contratoId) {
            
            $contrato = ContratoVoluntario::find($contratoId);
            //$this->direccion = $contrato->direccion;
            $this->telefono1 = $contrato->telefono_1;
            $this->telefono2 = $contrato->telefono_2;
            $this->fecha_i_m = Carbon::parse($contrato->fecha_inicio_contrato)->format('d/m/Y');
            $this->fecha_f_m = Carbon::parse($contrato->fecha_fin_contrato)->format('d/m/Y');
            //$this->fechaInicioContrato = $contrato->fecha_inicio_contrato;
            //$this->fechaFinContrato = $contrato->fecha_fin_contrato;
            $this->descripcionContrato = $contrato->descripcion_contrato;
            //$this->numero = $contrato->numero;

            $residencia = Residencia::find($contrato->id_residencia);
            $this->direccion = $residencia->direccion;
            $this->numero = $residencia->nro_vivienda;

            $zonaActual = Zona::find($residencia->id_zona);
            $this->zona = $zonaActual->nombre;
            $this->zonaId = $zonaActual->id;
            
            $this->correo = $contrato->correo;
            $this->estado = $contrato->estado;
            $this->personaId = $contrato->id_persona;

            $persona = Persona::find($contrato->id_persona);
            $this->ci = $persona->ci;
            $this->nombre = $persona->nombres;
            $this->primerApellido = $persona->p_apellido;
            $this->segundoApellido = $persona->s_apellido;
            $this->fechaNacimiento = Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y');
            $this->complemento = $persona->complemento;


            $this->contratoAnterior = ContratoVoluntario::find($this->id_contrato);
            $fechaDentrodeSemanaAnterior = Carbon::parse($contrato->fecha_fin_contrato)
                ->subWeek()
                ->lte(Carbon::now());
            $mayorQueFechaActual = Carbon::parse($contrato->fecha_fin_contrato)->lt(Carbon::now());

            if ($fechaDentrodeSemanaAnterior || $mayorQueFechaActual) {
                $this->renovarFechaContrato = true;
            } else {
                $this->renovarFechaContrato = false;
            }

            return $contrato;
        }
    }

    #[On('renovarContrato')]
    public function renovarContrato()
    {
        
        $this->validate();
        DB::beginTransaction();
        try {
            
            if ($this->correo == null || $this->correo == '') {
                $this->correo = 'aportes@ssulapaz.org';
            }
            $this->telefono1 = $this->telefono1 ?: null;
            $this->telefono2 = $this->telefono2 ?: null;
           
             
            // if ($this->renovarFechaContrato == true) {
                
                $contratoAnterior = ContratoVoluntario::find($this->id_contrato);
                $contratoAnterior->estado = false;
                $contratoAnterior->save();

                $nuevaResidencia = Residencia::create([
                    'direccion' => $this->direccion,
                    'nro_vivienda' => $this->numero,
                    'id_zona' => $this->zonaId,
                    'id_user_created' => auth()->user()->id,
                ]);
                
                $contrato = ContratoVoluntario::create([
                    'id_persona' => $this->personaId,
                    'telefono_1' => $this->telefono1,
                    'telefono_2' => $this->telefono2,
                    'correo' => $this->correo,
                    'fecha_inicio_contrato' => $this->fechaInicioContrato,
                    'fecha_fin_contrato' => $this->fechaFinContrato,
                    'descripcion_contrato' => $this->descripcionContrato,
                    'id_clase_aportante' => 5,
                    'id_residencia' => $nuevaResidencia->id,
                    'estado' => true,
                    'id_user_created' => auth()->user()->id,
                ]);
                DB::commit();

                $id_nuevo_contrato = $contrato->id;
                if ($id_nuevo_contrato) {
                    
                    $this->dispatch('guardado');
                    $this->dispatch('pg:eventRefresh-AportanteVoluntariLista');
                    return redirect()->route('aportes.aportantesinstitucionales.index');

                } else {
                    $this->dispatch('contratoCancelado');
                }

            // } elseif($this->renovarFechaContrato == false) {
                
            //     $contrato = ContratoVoluntario::find($this->id_contrato);
            //     if ($contrato) {
            //         $contrato->telefono_1 = $this->telefono1;
            //         $contrato->telefono_2 = $this->telefono2;
            //         $contrato->correo = $this->correo;
            //         $contrato->descripcion_contrato = $this->descripcionContrato;
                    
            //         $contrato->id_user_updated = auth()->user()->id;
            //         $contrato->save();

            //         $residenciaActualizar = Residencia::find($contrato->id_residencia);
            //         $residenciaActualizar->direccion = $this->direccion;
            //         $residenciaActualizar->nro_vivienda = $this->numero;
            //         $residenciaActualizar->id_zona = $this->zonaId;
            //         $residenciaActualizar->id_user_updated = auth()->user()->id;
            //         $residenciaActualizar->save();

            //         DB::commit();

            //         $this->dispatch('guardado');
            //         $this->dispatch('pg:eventRefresh-AportanteVoluntariLista');
            //         return redirect()->route('aportes.aportantesinstitucionales.index');
                    
            //     } else {
            //         $this->dispatch('contratoCancelado');
            //     }
            // }
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('error');
        }
    }

    
    public function atras()
    {
        return redirect()->route('aportes.aportantesinstitucionales.index');
    }
}
