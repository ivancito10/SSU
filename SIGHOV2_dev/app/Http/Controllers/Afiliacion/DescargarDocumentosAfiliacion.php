<?php

namespace App\Http\Controllers\Afiliacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class DescargarDocumentosAfiliacion extends Controller
{
    public function descargarArchivoCi($idEstudiante, $nombreEstudiante, $carnetEstudiante)
    {
        $path = 'Afiliacion/SSUE/DOCUMENTOS/Carnet-' . $idEstudiante . '.pdf';
        $name = 'CI - ' . $nombreEstudiante . ' - ' . $carnetEstudiante . '.pdf';

        if (!Storage::disk('ftp')->exists($path) || $idEstudiante === null) {
            abort(404, 'Archivo no encontrado.');
        }

        return Storage::disk('ftp')->download($path, $name);
    }

    public function descargarArchivoMatricula($idEstudiante, $nombreEstudiante, $carnetEstudiante)
    {
        $path = 'Afiliacion/SSUE/DOCUMENTOS/Matricula-' . $idEstudiante . '.pdf';
        $name = 'MATRICULA - ' . $nombreEstudiante . ' - ' . $carnetEstudiante . '.pdf';

        if (!Storage::disk('ftp')->exists($path) || $idEstudiante === null) {
            abort(404, 'Archivo no encontrado.');
        }

        return Storage::disk('ftp')->download($path, $name);
    }

    public function descargarArchivoBoleta($idEstudiante, $nombreEstudiante, $carnetEstudiante)
    {
        $path = 'Afiliacion/SSUE/DOCUMENTOS/Boleta_Inscripcion-' . $idEstudiante . '.pdf';
        $name = 'BOLETA INSCRIPCION - ' . $nombreEstudiante . ' - ' . $carnetEstudiante . '.pdf';

        if (!Storage::disk('ftp')->exists($path) || $idEstudiante === null) {
            abort(404, 'Archivo no encontrado.');
        }

        return Storage::disk('ftp')->download($path, $name);
    }

    public function descargarArchivoFormularioNoAfiliacion($idEstudiante, $nombreEstudiante, $carnetEstudiante)
    {
        $path = 'Afiliacion/SSUE/DOCUMENTOS/Certificado_Gestora-' . $idEstudiante . '.pdf';
        $name = 'CERTIFICADO GESTORA - ' . $nombreEstudiante . ' - ' . $carnetEstudiante . '.pdf';

        if (!Storage::disk('ftp')->exists($path) || $idEstudiante === null) {
            abort(404, 'Archivo no encontrado.');
        }

        return Storage::disk('ftp')->download($path, $name);
    }

    public function descargarArchivoCertificadoCajas($idEstudiante, $nombreEstudiante, $carnetEstudiante)
    {
        $path = 'Afiliacion/SSUE/DOCUMENTOS/Certificado_Cajas-' . $idEstudiante . '.pdf';
        $name = 'FORMULARIO 101 - ' . $nombreEstudiante . ' - ' . $carnetEstudiante . '.pdf';

        if (!Storage::disk('ftp')->exists($path) || $idEstudiante === null) {
            abort(404, 'Archivo no encontrado.');
        }

        return Storage::disk('ftp')->download($path, $name);
    }

    public function descargarArchivoTipoSangre($idEstudiante, $nombreEstudiante, $carnetEstudiante)
    {
        $path = 'Afiliacion/SSUE/DOCUMENTOS/Tipo_Sangre-' . $idEstudiante . '.pdf';
        $name = 'DOC SANGRE - ' . $nombreEstudiante . ' - ' . $carnetEstudiante . '.pdf';

        if (!Storage::disk('ftp')->exists($path) || $idEstudiante === null) {
            abort(404, 'Archivo no encontrado.');
        }

        return Storage::disk('ftp')->download($path, $name);
    }

    // prueba para ver archivos en un iframe sin base64 jji 07/05
    //  public function ver($nombre)
    //  {
    //      // Obtener el contenido del archivo desde el FTP
    //      $contenido = Storage::disk('ftp')->get("Afiliacion/SSUE/DOCUMENTOS/$nombre");
    //
    //      // Servir el archivo como respuesta
    //      return response($contenido, 200, [
    //          'Content-Type' => 'application/pdf',
    //          'Content-Disposition' => 'inline; filename="' . $nombre . '"',
    //          'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
    //          'Pragma' => 'no-cache',
    //          'Expires' => 'Fri, 01 Jan 1990 00:00:00 GMT',
    //      ]);
    //  }
}
