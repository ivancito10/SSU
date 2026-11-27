<?php 

namespace App\Services;

use App\Models\Afiliacion\HCarnet;
use Carbon\Carbon;

class VencimientoCarnet
{

public function verificarVencimientoCarnet($idPersona, $fechaComprobacion)
{
    $carnet = HCarnet::where('id_persona', $idPersona)->orderBy('id', 'desc')->first();

    if (!$carnet) {
        return [
            'vigente' => true,
            'fecha_vencimiento' => null,
        ];
    }

    if ($carnet->indefinido) {
        return [
            'vigente' => true,
            'fecha_vencimiento' => $carnet->fecha_vencimiento,
        ];
    }

    $fechaVencimiento = $carnet->fecha_vencimiento;
    $vigente = $fechaVencimiento >= $fechaComprobacion;

    return [
        'vigente' => $vigente,
        'fecha_vencimiento' => $fechaVencimiento,
    ];
}
}