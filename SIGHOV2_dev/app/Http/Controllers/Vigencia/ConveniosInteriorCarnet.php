<?php

namespace App\Http\Controllers\Vigencia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
class ConveniosInteriorCarnet extends Controller
{
    public function ConvenioInteriorCarnet($id_prestacion)
    {
      $ejemplo='adsfsdfsd';
        // Para abrir el blade vista del pdf con variables ya definidas
        $pdf = PDF::loadView(
            'Vigencia.Convenios.Interior.carnetInteriorPdf',
            compact(
               'ejemplo'
            )
        )
            ->setPaper('letter', 'portrait');


        $nombre_pdf = 'convenio_interior_carnet';
        // Para abrir pdf stream
        return $pdf->stream($nombre_pdf . '.pdf', [
            'Attachment' => false,  
            'Content-Disposition' => 'inline'
        ]);
    }
}
