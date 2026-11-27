<?php

namespace App\Services\Migracion\Afiliacion;

use App\Models\Administracion\Zona;
use Illuminate\Support\Facades\DB;

class MigracionZona
{

    public function migrarZona($nombre_zona, $id_municipio)
    {
        //DB::beginTransaction();
        //try {
            $nombreZonaMayus = mb_strtoupper($nombre_zona);
            $zonaExiste = Zona::where('nombre', $nombreZonaMayus)->first();

            if ($zonaExiste) {
                return $zonaExiste->id;
            } else {
                $nuevaZona = Zona::create([
                    'nombre' => mb_strtoupper($nombre_zona),
                    'id_municipio' => $id_municipio,
                    'id_user_created' => auth()->user()->id,
                ]);

                if ($nuevaZona) {
                    //DB::commit();
                    return $nuevaZona->id;
                }

                //DB::rollBack();
                return 0;
            }
        //} catch (\Throwable $th) {
        //    DB::rollBack();
        //    return 0;
        //}
    }
}
