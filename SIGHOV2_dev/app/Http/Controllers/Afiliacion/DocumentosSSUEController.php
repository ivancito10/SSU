<?php

namespace App\Http\Controllers\Afiliacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DomPDF;
class DocumentosSSUEController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function obtenerFormulario101($id)
    {
          
        

        $pdf = DomPDF::loadView('Afiliacion.Ssue.Documentos.Formulario101')->setPaper('letter', 'portrait');

        $pdfContent = $pdf->output();
        $ftpPath = 'Afiliacion/SSUE/DOCUMENTOS/' .$id. '.pdf';
        Storage::disk('ftp')->put($ftpPath, $pdfContent);

        return $pdf->stream('PRUEBA.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline'
        ]);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
