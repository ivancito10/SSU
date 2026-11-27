<?php

namespace App\Services;

use App\Services\ValidarPersona;

use App\Models\Vigencia\BajaMedica;
use App\Models\Administracion\LinkServicio;
use App\Models\Administracion\Persona;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class RegistroBajasMedicas
{
    public function registrarDatos()
    {
        //try {
        set_time_limit(12000);
        // $link_servicio = LinkServicio::where('nombre_servicio', 'REGISTRO BAJAS SIGHOV2')->first();

        // $linkcito = $link_servicio->link_servicio;

        //$linkcito = 'http://192.168.1.30:8000/api/bajamedica';

        //$response = Http::get($linkcito);
        //  $datos = $response->json();
        //dd($datos);
        DB::beginTransaction();
        if (!empty($datos)) {
            //dd($datos);
            foreach ($datos as $key => $dato) {
                /*if($dato['carnet_paciente'] != '' && is_numeric($dato['carnet_paciente']) && ctype_digit($dato['carnet_paciente'])){
                       echo 'es cheeee: ' . $dato['carnet_paciente'];
                        
                    } else {
                        echo 'no es cheeee: ' . $dato['carnet_paciente'];
                    }*/

                if ($dato['carnet_paciente'] != '' && is_numeric($dato['carnet_paciente']) && ctype_digit($dato['carnet_paciente'])) {

                    $personaExiste = Persona::where('ci', $dato['carnet_paciente'])
                        ->where('complemento', $dato['complemento_paciente'])
                        ->first();


                    if ($personaExiste) {
                        BajaMedica::create([
                            'fecha_recepcion' => Carbon::now(),
                            'estado_validacion' => 'PENDIENTE',
                            'estado_registro' => true,

                            'cod_baja_medica' => $dato['cod_baja'],
                            'fecha_inicio' => $dato['fecha_inicio'],
                            'fecha_fin' => $dato['fecha_fin'],
                            'diagnostico' => $dato['diagnostico'],
                            'id_especialidad' => $dato['id_especialidad'],
                            'nombre_institucion' => mb_strtoupper($dato['institucion']),
                            'nombre_especialista' => mb_strtoupper($dato['nombre_especialista']),
                            'id_persona' => $personaExiste->id,
                            'id_baja_medica_s' => $dato['id'],
                            'id_user_created' => auth()->user()->id,
                        ]);

                       // Http::put($linkcito . '/' . $dato['id']);
                    } else {
                        /*$validar = new ValidarPersona();
                            $nuevaPersona = $validar->buscarPersona($dato['carnet_paciente'], $dato['complemento_paciente']);
                            if ($nuevaPersona['codigoRespuesta'] == 2) {
                                if($nuevaPersona['datosPersonaEnFormatoJson']['Complemento'] == null){
                                    $clave_unica = $nuevaPersona['datosPersonaEnFormatoJson']['numeroDocumento'];
                                } else {
                                    $clave_unica = $nuevaPersona['datosPersonaEnFormatoJson']['numeroDocumento'].$nuevaPersona['datosPersonaEnFormatoJson']['Complemento'];
                                }
                                $crearPersona = Persona::create([
                                    'ci' => $nuevaPersona['datosPersonaEnFormatoJson']['numeroDocumento'],
                                    'complemento' => $nuevaPersona['datosPersonaEnFormatoJson']['Complemento'],
                                    'p_apellido' => $nuevaPersona['datosPersonaEnFormatoJson']['primerApellido'],
                                    's_apellido' => $nuevaPersona['datosPersonaEnFormatoJson']['segundoApellido'],
                                    'nombres' => $nuevaPersona['datosPersonaEnFormatoJson']['nombres'],
                                    'fecha_nacimiento' => $nuevaPersona['datosPersonaEnFormatoJson']['fechaNacimiento'],
                                    'estado_asuss' => true,
                                    'clave_unica' => $clave_unica,
                                    'id_user_created' => auth()->user()->id,
                                ]);
        
                                BajaMedica::create([
                                    'fecha_recepcion' => Carbon::now()->format('Y-m-d'),
                                    'estado_validacion' => 'PENDIENTE',
                                    'estado_registro' => true,
                                    'cod_baja_medica' => $dato['cod_baja'],
                                    'fecha_inicio' => $dato['fecha_inicio'],
                                    'fecha_fin' => $dato['fecha_fin'],
                                    'diagnostico' => $dato['diagnostico'],
                                    'id_especialidad' => $dato['id_especialidad'],
                                    'nombre_institucion' => $dato['institucion'],
                                    'nombre_especialista' => $dato['nombre_especialista'],
                                    'id_persona' => $crearPersona->id,
                                    'id_baja_medica_s' => $dato['id'],
                                    'id_user_created' => auth()->user()->id,
                                ]);
        
                                Http::put($linkcito . '/' . $dato['id']);
                            } else {*/
                        BajaMedica::create([
                            'fecha_recepcion' => Carbon::now()->format('Y-m-d'),
                            'estado_validacion' => 'PENDIENTE',
                            'estado_registro' => true,
                            'cod_baja_medica' => $dato['cod_baja'],
                            'fecha_inicio' => $dato['fecha_inicio'],
                            'fecha_fin' => $dato['fecha_fin'],
                            'diagnostico' => $dato['diagnostico'],
                            'id_especialidad' => $dato['id_especialidad'],
                            'nombre_institucion' => mb_strtoupper($dato['institucion']),
                            'nombre_especialista' => mb_strtoupper($dato['nombre_especialista']),
                            'nombre_persona' => mb_strtoupper($dato['nombre_paciente']),
                            'ci_persona' => $dato['carnet_paciente'],
                            'complemento_persona' => mb_strtoupper($dato['complemento_paciente']),
                            'matricula_persona' => mb_strtoupper($dato['matricula']),
                            'genero_persona' => mb_strtoupper($dato['genero']),
                            'fecha_nacimiento_persona' => $dato['fecha_nacimiento'],
                            'id_baja_medica_s' => $dato['id'],
                            'id_user_created' => auth()->user()->id,
                        ]);

                       // Http::put($linkcito . '/' . $dato['id']);
                        /*}*/
                    }
                }
            }
            DB::commit();
        } else {
            DB::rollBack();
        }
        //} catch (\Throwable $th) {
        //    DB::rollBack();
        //}       
    }
}
