<?php

namespace App\Services\Migracion\Plataforma;

use App\Models\Administracion\Especialidad;
use App\Models\Migracion\MedicosSighov1;
use App\Models\Plataforma\Especialista;
use App\Services\Migracion\Afiliacion\MigracionPersona;
use Carbon\Carbon;

class MigrarEspecialistas
{

    public function migrarEspecialista()
    {

        set_time_limit(12000);

        //$datos = MedicosSighov1::all();
        $datos = MedicosSighov1::whereNotIn('numero_identificacion', ['12345', '123456','54321'])->get();
        $personas = new MigracionPersona();

        //dd($datos);

        foreach ($datos as $key => $dato) {

            $nombres = mb_strtoupper($dato->nombres);
            $ap_Paterno = mb_strtoupper($dato->ap_Paterno);
            $ap_Materno = mb_strtoupper($dato->ap_Materno);

            if($dato->nombres === null || $nombres === ""){
                $nombres = null;
            }

            if($dato->ap_Paterno === null || $ap_Paterno === ""){
                $ap_Paterno = null;
            }

            if($dato->ap_Materno === null || $ap_Materno === ""){
                $ap_Materno = null;
            }

            $exitoPersonas = $personas->migrarPersona(
                $dato->numero_identificacion,
                null,
                $nombres,
                $ap_Paterno,
                $ap_Materno,
                null,
                false,
                null,
                $dato->fecha_nacimiento,
                0,
                false,
                1,
                null,
                null,
                null,
            );

            $idEspecialidad = 0;
            //PARA LA ESPECIALIDAD

            $existeEspecialidad = Especialidad::where('especialidad', mb_strtoupper($dato->especialidad))->first();

            if ($existeEspecialidad) {
                $idEspecialidad = $existeEspecialidad->id;
                if ($existeEspecialidad->estado === false) {
                    $existeEspecialidad->estado = true;
                    $existeEspecialidad->save();
                }
            } else {
                $nuevaEspecialidad = Especialidad::create([
                    'especialidad' => mb_strtoupper($dato->especialidad),
                    'sigla' => $dato->especialidad,
                    'estado' => true,
                    'id_user_created' => auth()->user()->id,
                ]);
                if ($nuevaEspecialidad) {
                    $idEspecialidad = $nuevaEspecialidad->id;
                }
            }

            //

            if ($exitoPersonas != 0 && $idEspecialidad != 0) {

                $especialistaMigrado = Especialista::create([
                    'fecha_contrato_inicio' => '2025-01-02',
                    'fecha_contrato_fin' => '2025-12-31',
                    'foto' => null,
                    'permanente' => null,
                    'estado' => true,
                    'id_especialidad' => $idEspecialidad,
                    'id_persona' => $exitoPersonas,
                    'id_user_created' => auth()->user()->id,
                    'grado_academico' => 'DOCTOR',
                ]);
            }
        }
    }
}
