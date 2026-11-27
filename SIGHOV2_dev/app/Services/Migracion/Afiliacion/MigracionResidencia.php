<?php

namespace App\Services\Migracion\Afiliacion;

use App\Models\Administracion\Residencia;
use Illuminate\Support\Facades\DB;

class MigracionResidencia
{

    public function migrarResidencia($direccion, $nro_vivienda, $id_zona, $latitud, $longitud){
        //DB::beginTransaction();
        //try {
            $nuevaResidencia = Residencia::create([
                'direccion' => $direccion, 
                'nro_vivienda' => $nro_vivienda,
                'id_zona' => $id_zona,
                'latitud' => $longitud,
                'longitud' => $latitud,
                'id_user_created' => auth()->user()->id,
            ]);
    
            if($nuevaResidencia){
                //DB::commit();
                return $nuevaResidencia->id;
            }
            //DB::rollBack();
            return 0;
        //} catch (\Throwable $th) {
        //    DB::rollBack();
        //    return 0;
        //}
        
    }
    
}
