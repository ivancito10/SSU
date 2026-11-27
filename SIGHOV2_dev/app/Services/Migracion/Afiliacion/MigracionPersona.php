<?php

namespace App\Services\Migracion\Afiliacion;

use App\Models\Administracion\Persona;
use Carbon\Carbon;

class MigracionPersona
{
    public function migrarPersona(
        $ci,
        $complemento,
        $nombres,
        $p_apellido,
        $s_apellido,
        $sexo,
        $afiliado,
        $id_nacimiento_municipio,
        $fecha_nacimiento,
        $id_tipo_asegurado,
        $es_extranjero,
        $id_estado_civil,
        $id_dept_exp,
        $alergias,
        $grupo_sanguineo,
    ) {
        $clave_unica = $ci;
        //DB::beginTransaction();
        //try {

        $existePersona = Persona::where('clave_unica', $ci)->first();
        if ($existePersona) {
            return $existePersona->id;
        } else {

            $matricula_seguro = null;
            if ($fecha_nacimiento !== null && $sexo !== null) {
                $matricula_seguro = $this->generarMatricula($nombres, $p_apellido, $s_apellido, $fecha_nacimiento, $sexo);
            }

            //if ($matricula_seguro != null) {
                $nuevaPersona = Persona::create([
                    'ci' => $ci,
                    'complemento' => $complemento,
                    'nombres' => $nombres,
                    'p_apellido' => $p_apellido,
                    's_apellido' => $s_apellido,
                    'sexo' => $sexo,
                    'afiliado' => $afiliado,
                    'matricula_seguro' => $matricula_seguro,
                    'id_nacimiento_municipio' => $id_nacimiento_municipio,
                    'fecha_nacimiento' => $fecha_nacimiento,
                    'id_tipo_asegurado' => $id_tipo_asegurado,
                    'es_extranjero' => $es_extranjero,
                    'id_estado_civil' => $id_estado_civil,
                    'id_dept_exp' => $id_dept_exp,
                    'alergias' => $alergias,
                    'grupo_sanguineo' => $grupo_sanguineo,
                    'clave_unica' => $clave_unica,
                    'id_user_created' => auth()->user()->id,
                ]);

                if ($nuevaPersona) {
                    //DB::commit();
                    //dd('aqui');
                    return $nuevaPersona->id;
                } else {
                    //DB::rollBack();
                    //dd('fallo');
                    return 0;
                }
            //} else {
            //    dd('sabe?');
            //    //DB::rollBack();
            //    return 0;
            //}
        }
        //} catch (\Throwable $th) {
        //    DB::rollBack();
        //    return 0;
        //}
    }

    public function generarMatricula($nombre, $p_apellido, $s_apellido, $fecha_nacimiento, $sexo)
    {
        //try {
        $fechaNacimiento = Carbon::parse($fecha_nacimiento);
        $anioNacimiento = $fechaNacimiento->format('y');
        $mesNacimiento = $fechaNacimiento->format('m');
        $diaNacimiento = $fechaNacimiento->format('d');
        $mesNacimiento = $sexo === false || $sexo === 'M' ? $mesNacimiento : str_pad(50 + $mesNacimiento, 2, '0', STR_PAD_LEFT);

        if (empty($p_apellido)) {
            $apellido1 = substr($s_apellido, 0, 1);
            $apellido2 = substr($s_apellido, 1, 1);
        } elseif (empty($s_apellido)) {
            $apellido1 = substr($p_apellido, 0, 1);
            $apellido2 = substr($p_apellido, 1, 1);
        } else {
            $apellido1 = substr($p_apellido, 0, 1);
            $apellido2 = substr($s_apellido, 0, 1);
        }

        $nombre1 = substr($nombre, 0, 1);

        return $anioNacimiento . $mesNacimiento . $diaNacimiento . $apellido1 . $apellido2 . $nombre1;
        //} catch (\Throwable $th) {
        //    return null;
        //}
    }
}
