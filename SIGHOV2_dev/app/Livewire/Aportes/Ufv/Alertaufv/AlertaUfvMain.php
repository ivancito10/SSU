<?php

namespace App\Livewire\Aportes\Ufv\Alertaufv;

use Livewire\Component;
use App\Models\Aportes\Ufv;
use Carbon\Carbon;

class AlertaUfvMain extends Component
{

    public $estado = 1; 
    public function render()
    {
        
        return view('livewire.Aportes.Ufv.AlertaUfv.alerta-ufv-main');
    }
    public function mount()
    {
        $hoy = Carbon::now(); // Incluye fecha y hora actual
        $fechaFormateada = $hoy->format('Y-m-d'); 

        $ufv = Ufv::where('fecha', $fechaFormateada)->first();
        if($ufv == null){
           $this->dispatch('abrirModalBloqueo');
           
        }
    }
    public function enlace_ufv(){
        $this->dispatch('cerrar_modal'); // Evento para cerrar el modal

        // Aquii logica para enviar directo a la ruta, pero se genra un bucle y nunca se cierra
        //if($this->estado == 0){
        //   return redirect()->route('aportes.ufv.index'); 
        //   
        //}else{
        //    return redirect()->route('aportes.ufv.index'); 
        //    $this->estado = 0;
        //}
        
    }
}
