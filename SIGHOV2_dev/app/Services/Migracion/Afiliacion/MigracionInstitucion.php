<?php

namespace App\Services\Migracion\Afiliacion;

use App\Models\Administracion\Residencia;
use App\Models\Afiliacion\CodigoInstitucionSsu;
use App\Models\Aportes\Institucion;
use Illuminate\Support\Facades\DB;
use App\Models\Migracion\AportesIntituciones;
use App\Services\Migracion\Afiliacion\MigracionZona;
use App\Services\Migracion\Afiliacion\MigracionResidencia;
class MigracionInstitucion
{
    public function migrarInstitucion(
        $nit,
        $nro_empleador,
        $actividad_empleador,
        $acronimo,
        $fecha_afiliacion,
        $correo,
        $telefono,
        $nombre_institucion,
        $tgn,
        $publica,
        $estado,
        $id_institucion
    ) {
        $insercionZona = new MigracionZona();
        $insercionResidencia = new MigracionResidencia();
        //DB::beginTransaction();
        //try {

            if($nombre_institucion == 'A. F. P. FUTURO DE BOLIVIA' || $nombre_institucion == 'A.F.P. PREVISION B.B.V.'){
                $nombre_institucion = 'GESTORA PUBLICA DE LA SEGURIDAD SOCIAL DE LARGO PLAZO';
                $acronimo = 'GESTORA';
            }

            //CREACION DE ZONAS Y DEMAS 
            
            $exitoZonas = $insercionZona->migrarZona(
                'CENTRO',
                69
            );

            $exitoResidencias = 1;
        
            if ($exitoZonas != 0) {
                
                $existeResidencia = Residencia::where('direccion', 'AV. 6 DE AGOSTO')
                    ->where('nro_vivienda', 0)
                    ->where('id_zona', $exitoZonas)
                    ->where('latitud', '-16.5120')
                    ->where('longitud', '-68.1310')
                    ->first();

                if($existeResidencia){
                    $exitoResidencias = $existeResidencia->id;
                } else {
                    $exitoResidencias = $insercionResidencia->migrarResidencia(
                        'AV. 6 DE AGOSTO',
                        0,
                        $exitoZonas,
                        '-16.5120',
                        '-68.1310'
                    );
                }
            }
            
            $existeInstitucion = Institucion::where('nombre', $nombre_institucion)->first();

            if($existeInstitucion){
                return $existeInstitucion->id;
            } else {
                $nuevaInstitucion = Institucion::create([
                    'nit' => $nit,
                    'nro_empleador' => $nro_empleador,
                    'actividad_empleador' => $actividad_empleador,
                    'acronimo' => $acronimo,
                    'fecha_afiliacion' => $fecha_afiliacion,
                    'correo' => $correo,
                    'telefono' => $telefono,
                    'nombre' => $nombre_institucion,
                    'tgn' => $tgn,
                    'publica' => $publica,
                    'estado' => $estado,
                    'id_clase_aportante' => 1,
                    'estado_validacion' => false,
                    'id_user_created' => auth()->user()->id,

                    'id_persona' => 1,
                    'id_residencia' => $exitoResidencias,
                    'id_usuario_responsable' => 1,  

                ]);
                //insersion id en institucion gehma 2

                    $institucion = AportesIntituciones::find($id_institucion);

                    if ($institucion) {
                        $institucion->id_institucion_s2 = $nuevaInstitucion->id;
                        $institucion->save();

                    } 
                

                if ($nuevaInstitucion) {
                    //DB::commit();
                    return $nuevaInstitucion->id;
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
