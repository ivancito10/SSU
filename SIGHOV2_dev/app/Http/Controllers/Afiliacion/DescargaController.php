<?php

namespace App\Http\Controllers\Afiliacion;

use App\Http\Controllers\Controller;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Administracion\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DescargaController extends Controller
{
    public function downloadFoto($filename)
    {
        // dd('asdsa');
        $path = 'Afiliacion/FOTOS/' . $filename;
        /* $titular = Titular::find($filename);
        $persona = Persona::find($titular->id_persona);
        $ci_persona = $persona->ci;
        $nombre = $ci_persona . '- FOTO'; */
        // $nombre = $ci_persona . '.jpg';
        if (!Storage::disk('ftp')->exists($path)) {
            abort(404);
        }

        return Storage::disk('ftp')->download($path, 'FOTO-RECUPERADA.jpg');
    }


    public function downloadFilePersonal($ruta, $row)
    {
        //dd('sdas', $ruta, $row);
        if ($ruta == 'Titulares') {
            $datoAEncontrar = Titular::find($row);
            $nombre_ruta = 'TITULARES';
        } else {
            $datoAEncontrar = Beneficiario::find($row);
            $nombre_ruta = 'BENEFICIARIOS';
        }

        $persona = Persona::find($datoAEncontrar->id_persona);
        $nombreCompleto = $persona->nombres . ' ' . $persona->p_apellido . ' ' . $persona->s_apellido;
        // dd($persona, $titular, $nombreCompleto);
        $path = 'Afiliacion/FILES_PERSONALES/' . $nombre_ruta . '/' . $row . '.pdf';
        //dd($path);
        $name = $nombreCompleto . '- FILE DIGITAL.pdf';
        if (! Storage::disk('ftp')->exists($path)) {
            abort(404);
            // $this->dispatch('archivoNoEncontrado');
        }
        return Storage::disk('ftp')->download($path, $name);
        // dd($file);
    }

    public function verFilePersonal($ruta, $row)
    {

        try {
            if ($ruta == 'Titulares') {
                $nombre_ruta = 'TITULARES';
            } else {
                $nombre_ruta = 'BENEFICIARIOS';
            }

            $path = 'Afiliacion/FILES_PERSONALES/' . $nombre_ruta . '/' . $row . '.pdf';

            if (!Storage::disk('ftp')->exists($path)) {
                abort(404, 'El archivo digital no existe.');
            }

            $fileContents = Storage::disk('ftp')->get($path);

            return response($fileContents, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $row . '.pdf"',
                //  'Cache-Control' => 'private, max-age=86400'
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
            ]);
        } catch (\Throwable $th) {
            abort(500, 'Error al recuperar el archivo digital.');
        }
    }
}
