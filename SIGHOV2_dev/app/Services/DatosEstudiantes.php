<?php

namespace App\Services;


use App\Models\Administracion\LinkServicio;
use Illuminate\Support\Facades\Http;
use App\Models\Administracion\Persona;
use App\Models\Afiliacion\HistorialMatriculaEstudiante;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\Afiliacion\AfiliacionSolicitudMail;
use Carbon\Carbon;
use DB;

class DatosEstudiantes
{

    public function insercionEstudiantes()
    {

        //Obtencion de datos desde la API del Tavin

        $urlEstudiantesUmsa = LinkServicio::where('nombre_servicio', 'DATOS ESTUDIANTES UMSA')->where('estado', true)->first();
        $urlEstUmsa = $urlEstudiantesUmsa->link_servicio;
        $respuesta = Http::get($urlEstUmsa);
        $datos = $respuesta->json();
      
        // DB::beginTransaction();
        // try {
            foreach ($datos as $dato) {

                if ($dato['complemento'] == '') {
                    $dato['complemento'] = null;
                }

                if ($dato['complemento'] == null) {
                    $clave_unica = $dato['ci'];
                } else {
                    $clave_unica = $dato['ci'] . $dato['complemento'];
                }

                $personaExiste = Persona::where('ci', $dato['ci'])->where('complemento', $dato['complemento'])->first();
               
                if ($personaExiste) {
                    $personaEstudianteId = $personaExiste->id;
                    $personaEstudianteCi = $personaExiste->ci;
                    $arrayPersonaEstudianteNombres = array_filter(['nombres'=>
                        $personaExiste->nombres,'p_apellido'=>
                        $personaExiste->p_apellido ?? null,'s_apellido'=>
                        $personaExiste->s_apellido ?? null,
                    ]);
                } else {


                    $estudianteCreado = Persona::create([
                        'ci' => $dato['ci'],
                        'complemento' => $dato['complemento'],
                        'nombres' => $dato['nombres'],
                        'p_apellido' => $dato['apellido_paterno'],
                        's_apellido' => $dato['apellido_materno'],
                        'es_extranjero' => $dato['tipo_documento'],
                        'sexo' => $dato['sexo'],
                        'afiliado' => false,
                        'id_tipo_asegurado' => 0,
                        'fecha_nacimiento' => $dato['fecha_nacimiento'],
                        'id_estado_civil' => $dato['estado_civil'],
                        'clave_unica' => $clave_unica,
                        'id_user_created' => auth()->user()->id,
                    ]);
                    $personaEstudianteId = $estudianteCreado->id;
                    $personaEstudianteCi = $estudianteCreado->ci;

                    $arrayPersonaEstudianteNombres = array_filter(['nombres'=>
                        $estudianteCreado->nombres, 'p_apellido'=>
                        $estudianteCreado->p_apellido ?? null,'s_apellido'=>
                        $estudianteCreado->s_apellido ?? null,
                    
                    ]);
                    
                    //PARA LA CREACION DE USUARIOS

                    $usuario = User::where('id_persona', $personaEstudianteId)->where('estado', true)->first();
                    
                    $usuarioNuevo = $this->generarUsuario(
                        $estudianteCreado->nombres,
                        $estudianteCreado->p_apellido,
                        $estudianteCreado->s_apellido
                    );

                    if (!$usuario) {

                        $arrayNombres = array_filter([
                            $estudianteCreado->p_apellido ?? null,
                            $estudianteCreado->s_apellido ?? null,
                            $estudianteCreado->nombres,
                        ]);

                        $numeroInvertido = (int)strrev((string) $personaEstudianteId);
                        $resultado = $numeroInvertido + 3;
                        $user = User::create([
                            'name' => implode(' ', $arrayNombres),
                            'email' => $usuarioNuevo . '-est-' . $resultado,
                            'id_persona' => $personaEstudianteId,
                            'estado' => true,
                            'password' => Hash::make($estudianteCreado->ci),
                            'id_user_created' => auth()->user()->id,
                        ]);
                         //asignacion de rol
                        $user->assignRole('Estudiante SSUE');
                        $user->syncRoles(['Estudiante SSUE']);

                        $asunto = "Sistema Sighov2 - Unidad de Afiliación SSUE";
                       // dd($asunto, $arrayPersonaEstudianteNombres, $user->email, $personaEstudianteCi);
                        $correo = new AfiliacionSolicitudMail($asunto, $arrayPersonaEstudianteNombres, $user->email, $personaEstudianteCi);
                        Mail::to($dato['correo_institucional'])->send($correo);

                       
                    }
                }

                foreach ($dato['datos_academicos'] as $datoAcademico) {

                    foreach ($datoAcademico['listado_matriculas'] as $matricula) {

                        $existeHistorial = HistorialMatriculaEstudiante::where('id_persona', $personaEstudianteId)
                            ->where('registro_universitario', $datoAcademico['registro_universitario'])
                            ->where('nro_matricula', $matricula['nro_matricula'])
                            ->where('gestion_matricula', $matricula['gestion'])
                            ->where('carrera', $datoAcademico['carrera'])
                            ->where('facultad', $datoAcademico['facultad'])
                            ->where('gestion_ingreso', $datoAcademico['gestion_ingreso'])
                            ->where('tipo_estudiante', $dato['tipo_estudiante'])
                            ->first();
                        //  dd($existeHistorial);
                        if (!$existeHistorial) {

                            HistorialMatriculaEstudiante::create([
                                'id_persona' => $personaEstudianteId,
                                'registro_universitario' => $datoAcademico['registro_universitario'],
                                'nro_matricula' => $matricula['nro_matricula'],
                                'gestion_matricula' => $matricula['gestion'],
                                'carrera' => $datoAcademico['carrera'],
                                'facultad' => $datoAcademico['facultad'],
                                'gestion_ingreso' => $datoAcademico['gestion_ingreso'],
                                'tipo_estudiante' => $dato['tipo_estudiante'],
                                'id_user_created' => auth()->user()->id,
                            ]);
                        }
                    }
                }
            }
        //     DB::commit();
        // } catch (\Throwable $th) {
        //     DB::rollBack();
        // }
    }

    public function generarUsuario($nombresEst, $p_apellidoEst, $s_apellidoEst)
    {

        // Convertir a mayúsculas para asegurar consistencia
        $nombres = str_replace(' ', '', mb_strtolower($nombresEst));
        $p_apellido = str_replace(' ', '', mb_strtolower($p_apellidoEst));
        $s_apellido = str_replace(' ', '', mb_strtolower($s_apellidoEst));

        $email = '';

        if ($p_apellido == '') {
            $email = substr($nombres, 0, 1) . $s_apellido . substr($s_apellido, 0, 1);
        } elseif ($s_apellido == '') {
            $email = substr($nombres, 0, 1) . $p_apellido . substr($p_apellido, 0, 1);
        } else {
            $email = substr($nombres, 0, 1) . $p_apellido . substr($s_apellido, 0, 1);
        }

        return $email;
    }
}
