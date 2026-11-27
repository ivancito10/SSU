<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Administracion\Manual;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ManualController extends Controller
{
    /**
     * Sirve el manual PDF en línea desde el servidor FTP (stream sin cargar todo en Base64)
     */
    public function ver(int $id)
    {
        $manual = Manual::findOrFail($id);

        if (empty($manual->ruta_ftp)) {
            abort(404, 'El manual no tiene un archivo asociado.');
        }

        try {
            $disk = Storage::disk('ftp');

            if (!$disk->exists($manual->ruta_ftp)) {
                abort(404, 'El archivo del manual no se encontró en el servidor FTP.');
            }

            $stream = $disk->readStream($manual->ruta_ftp);
            if ($stream === false) {
                abort(500, 'No se pudo abrir el archivo en el servidor FTP.');
            }

            $nombre = $manual->nombre_archivo ?: ('manual_' . $manual->id . '.pdf');

            return response()->stream(function () use ($stream) {
                fpassthru($stream);
                if (is_resource($stream)) {
                    fclose($stream);
                }
            }, 200, [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $nombre . '"',
                'Cache-Control'       => 'private, max-age=300',
            ]);
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error("ManualController@ver error FTP manual #{$id}: " . $e->getMessage());
            abort(500, 'Error al leer el manual desde el servidor FTP.');
        }
    }

    /**
     * Descarga el manual PDF directamente desde el servidor FTP
     */
    public function descargar(int $id)
    {
        $manual = Manual::findOrFail($id);

        if (empty($manual->ruta_ftp)) {
            abort(404, 'El manual no tiene un archivo asociado.');
        }

        try {
            $disk = Storage::disk('ftp');

            if (!$disk->exists($manual->ruta_ftp)) {
                abort(404, 'El archivo del manual no se encontró en el servidor FTP.');
            }

            $stream = $disk->readStream($manual->ruta_ftp);
            if ($stream === false) {
                abort(500, 'No se pudo abrir el archivo en el servidor FTP.');
            }

            $nombre = $manual->nombre_archivo ?: ('manual_' . $manual->id . '.pdf');

            return response()->streamDownload(function () use ($stream) {
                fpassthru($stream);
                if (is_resource($stream)) {
                    fclose($stream);
                }
            }, $nombre, [
                'Content-Type' => 'application/pdf',
            ]);
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error("ManualController@descargar error FTP manual #{$id}: " . $e->getMessage());
            abort(500, 'Error al descargar el manual desde el servidor FTP.');
        }
    }
}
