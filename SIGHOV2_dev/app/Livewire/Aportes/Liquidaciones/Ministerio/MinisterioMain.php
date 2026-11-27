<?php

namespace App\Livewire\Aportes\Liquidaciones\Ministerio;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Storage;


class MinisterioMain extends Component
{
    public function render()
    {
        return view('livewire.Aportes.liquidaciones.ministerio.ministerio-main');
    }
    #[On('pdfMinisterio')]
    public function pdfMinisterio($data)
    {
        $this->mostrarArchivoPdf($data);
        $this->dispatch('mostrarMinisterioPdf');
    }

    public $pdf_vista;
    public function mostrarArchivoPdf($id)
    {
        try {
            if($id){
                
                $path = 'Aportes/Liquidaciones/MINISTERIO/' .  $id  . '.pdf';
                
                $fileContents = Storage::disk('ftp')->get($path);
                $pdfBase64 = base64_encode($fileContents);
                $this->pdf_vista = $pdfBase64;
            }
            
        } catch (\Throwable $th) {
            return null;
        }
    }
}
