<?php

namespace App\Services\InteroperabilidadAsuss;

use App\Models\Administracion\ControlRegistroAsuss;

class ControlRegistrosAsussService
{
    public function registrarControl(array $datos)
    {
        ControlRegistroAsuss::create([
            'id_persona' => $datos['id_persona'],
            'id_tipo_asegurado' => $datos['id_tipo_asegurado'],
            'id_corresponde' => $datos['id_corresponde'],
            'id_titular' => $datos['id_titular'],
            'id_institucion' => $datos['id_institucion'],
            'respuesta_contrastacion_json' => $datos['respuesta_contrastacion_json'],
            'respuesta_insercion_json' => $datos['respuesta_insercion_json'],
            'registro_exitoso' => null,
            'afiliacion' => $datos['afiliacion'],
            'id_user_created' => $datos['id_user_created'],
        ]);
    }
}
