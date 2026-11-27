<?php

namespace App\Livewire\Afiliacion\Manuales;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class ManualesMain extends Component
{
    public $pdf_vista;
    public function render()
    {
        return view('livewire.Afiliacion.manuales.manuales-main');
    }

    public function adobeExpressManual()
    {
        $this->dispatch('mostrarAdobeExpressManualPdf');
        //$this->mostrarArchivoPdf();
    }
    public function estudiatesAntiguosManual()
    {
        $this->dispatch('mostrarEstudianteAntiguoManualPdf');
        //$this->mostrarArchivoPdf();
    }
    public function estudiatesNuevosManual()
    {
        $this->dispatch('mostrarEstudianteNuevoManualPdf');
        //$this->mostrarArchivoPdf();
    }
    //  public function mostrarArchivoPdf()
    //  {
    //      try {
    //          $path = 'Afiliacion/MANUALES/MANUAL_USO-ADOBE_EXPRESS_compressed.pdf';
    //          $fileContents = Storage::disk('ftp')->get($path);
    //          $pdfBase64 = base64_encode($fileContents);
    //          $this->pdf_vista = $pdfBase64;
    //      } catch (\Throwable $th) {
    //          return null;
    //      }
    //  }
}
