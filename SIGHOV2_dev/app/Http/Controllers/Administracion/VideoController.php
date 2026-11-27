<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function obtenerVideo($filename)
    {
        try {
            $path = 'Administracion/Videos/'. $filename;
            if (!Storage::disk('ftp')->exists($path)) {
                abort(404);
            }
            $tempFile = tempnam(sys_get_temp_dir(), 'ftp_video_');
            file_put_contents($tempFile, Storage::disk('ftp')->get($path));
            return response()->file($tempFile, [
                'Content-Type' => 'video/mp4',
                'Cache-Control' => 'no-cache',
            ]);
        } catch (\Throwable $th) {
            abort(500, 'Error al cargar el video');
        }
    }
}
