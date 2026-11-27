<?php

namespace App\Livewire\Aportes\Liquidaciones\Asuss;
use Livewire\Attributes\On;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class AsussMain extends Component
{
    public function render()
    {
        return view('livewire.Aportes.liquidaciones.asuss.asuss-main');
    }
    #[On('pdfAsuss')]
    public function pdfAsuss($data)
    {
        $this->mostrarArchivoPdf($data);
        $this->dispatch('mostrarAsussPdf');
    }
    public $pdf_vista;
    public function mostrarArchivoPdf($id)
    {
        try {
            if($id){
                
                $path = 'Aportes/Liquidaciones/ASUSS/' .  $id  . '.pdf';
                
                $fileContents = Storage::disk('ftp')->get($path);
                $pdfBase64 = base64_encode($fileContents);
                $this->pdf_vista = $pdfBase64;
            }
            
        } catch (\Throwable $th) { 
            return null;
        }
    }
    
   
}