<?php

namespace App\Livewire\Aportes\TasaInteres;

use Livewire\Component;
use App\Services\PoblacionAsegurada;
use App\Models\Aportes\TasaInteres;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TasaInteresMain extends Component
{
    public $idTasa;
    protected $listeners = [ 'actualizar','guardar'];

    public function render()
    {

        // Auth::user()->givePermissionTo(['menu.ufv', 'lista.ufv','registro.ufv', 'editar.ufv','habilitar_desabilitar.ufv']);
        // Auth::user()->givePermissionTo(['menu.tasa_interes', 'lista.tasa_interes', 'registro.tasa_interes', 'editar.tasa_interes', 'habilitar_desabilitar.tasa_interes']);

        // Auth::user()->givePermissionTo(['menu.instituciones','lista.instituciones','registro.instituciones','editar.instituciones','habilitar_desabilitar.instituciones','editar_representante.instituciones',
        // 'ver.instituciones','validar.instituciones']);
        
        $tasa = $this->mostrarTasa($this->idTasa);
        return view('livewire.Aportes.tasa-interes.tasa-interes-main', ['esp'=>$tasa]);
    }
    public $valor, $fecha;
    public function mostrarTasa($id){
        if($id){
            $esp = TasaInteres::find($id);
            $this->valor = $esp->valor;
            $this->fecha = $esp->fecha;

            return $esp;
        }
    }
    
    public function guardar(){
        DB::beginTransaction();
        try {
            
                // $this->validate([
                //     'valor' => 'required|numeric|max:11.99999',
                //     'fecha' => 'required|date',
                // ]);
                if($this->valor != null && $this->fecha != null){
 
                    $existeTasaInteres = TasaInteres::where('fecha', '=', $this->fecha)->where('estado', '=', true)->first();
                    if($existeTasaInteres == null){
                        $tasaInteres =TasaInteres::create([
                            'valor' => $this->valor,
                            'fecha' => $this->fecha,
                            'id_user_created' => auth()->user()->id,
                        ]);
                        if ($tasaInteres) {
                            $this->dispatch('pg:eventRefresh-TasaInteresTable');
                            $this->dispatch('guardarTasa');
                        } 
                    } else {
                        $this->reset(['valor', 'fecha']);
                        $this->dispatch('tasaExiste');
                        $this->skipRender();
                    }
                    DB::commit();
                    $this->reset(['valor', 'fecha']);
                     
                }else{
                    $this->dispatch('completarDatos');
                }

            } 
            
            catch (\Throwable $th) {
                DB::rollBack();
                $this->reset(['valor', 'fecha']);
                $this->dispatch('canceladoTasa');            
    
            }
            
    }

    public function registrar(){
        
        $this->dispatch('modalRegistrar');
    }

      // editarTasa
      #[On('editarTasa')]
      public function editarTasa($data)
      {
          $this->idTasa = $data;
          $this->dispatch('modalEditarTasa');
          
      }

       // Actualizar registro
    public function actualizar(){
        try{
            if($this->idTasa){
                $esp = TasaInteres::find($this->idTasa);
                if ($esp) {
                    $esp->valor = $this->valor;
                    $esp->fecha = $this->fecha;
                    $esp->id_user_updated = auth()->user()->id;
                    $esp->save();
                }
                $this->dispatch('pg:eventRefresh-TasaInteresTable');
                $this->dispatch('modificadoTasa');
            }

        
        } catch (\Throwable $th) {
            $this->dispatch('error');
        }

        
    }
  
      // Cambiar estado 
      #[On('cambiarEstado')]
      public function cambiarEstado($data)
      {
        try{
            $esp = TasaInteres::find($data);
            if ($esp) {
                $estadoActual = $esp->estado;
                
                $esp->estado = !$estadoActual;
                $esp->save();
  
                $this->dispatch('pg:eventRefresh-TasaInteresTable');
                if($esp->estado == true){
                    $this->dispatch('activo');
                }else{
                    $this->dispatch('inactivo');
                }
                
            }

        
        } catch (\Throwable $th) {
            $this->dispatch('error');
        }
      }
}
