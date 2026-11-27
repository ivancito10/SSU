<?php

namespace App\Http\Controllers\Aportes;

use App\Models\Aportes\Institucion;
use App\Exports\ReporteAvisoBajaExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReporteAvisoBaja extends Controller
{
    public function generarReporteAvisoBaja($fecha_inicio, $fecha_fin)
    {
        // dd('asdasd');
        $institucion_user = Institucion::where('estado_validado', true)
            ->where('id_usuario_aviso_baja', auth()->user()->id)
            ->first();
        $nombre_institucion = $institucion_user->nombre;
        $id_institucion = $institucion_user->id;
        $fecha_formateada_inicio = Carbon::parse($fecha_inicio)->format('d-m-Y');
        $fecha_formateada_fin = Carbon::parse($fecha_fin)->format('d-m-Y');
        $nombreArchivo = "Aviso baja_" . $nombre_institucion . "_" . $fecha_formateada_inicio . " al " . $fecha_formateada_fin . ".xlsx";
        // dd($nombre_institucion);
        return Excel::download(new ReporteAvisoBajaExport($fecha_inicio, $fecha_fin, $id_institucion, $nombre_institucion), $nombreArchivo);
    }
}
