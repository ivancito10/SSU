<?php

namespace App\Services\Migracion\Afiliacion;

use App\Models\Administracion\Persona;
use App\Models\Administracion\TipoAsegurado;
use App\Models\Afiliacion\Estudiante;
use App\Models\Migracion\AfiliacionAfiliados;
use App\Models\Migracion\MigracionEstudiante;
use App\Models\User;
use App\Models\Vigencia\HistorialAsegurado;
use App\Services\Migracion\Afiliacion\MigracionZona;
use App\Services\Migracion\Afiliacion\MigracionResidencia;
use App\Services\Migracion\Afiliacion\MigracionPersona;
use App\Services\Migracion\Afiliacion\MigracionInstitucion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MigrarEstudiante
{

    public function migrarEstudiante()
    {
        set_time_limit(12000);
        $datos = MigracionEstudiante::where('migrado_s2', null)->get();
        //$datos = MigracionEstudiante::limit(15)->get();
        $zonas = new MigracionZona();
        $residencias = new MigracionResidencia();
        $personas = new MigracionPersona();
        //dd($datos);



        foreach ($datos as $key => $dato) {

            if (is_null($dato->telefono_referencia) || !is_int($dato->telefono_referencia)) {
                $dato->telefono_referencia = 0;
            }

            if ($dato->id_dept_exp == 0) {
                $dato->id_dept_exp = 1;
            }

            if ($dato->telefono == "" || $dato->telefono == null || !is_int($dato->telefono)) {
                $dato->telefono = 0;
            }

            if ($dato->id_estado_civil == "" || $dato->id_estado_civil == 0) {
                $dato->id_estado_civil = 1;
            }

            if ($dato->ru == null || !is_int($dato->ru)) {
                $dato->ru = 0;
            }

            $tipoAseg = TipoAsegurado::where('tipo_asegurado', 'like', '%ESTUDIANTE%')->first();

            $exitoPersonas = $personas->migrarPersona(
                $dato->ci,
                $dato->complemento,
                $dato->nombres,
                $dato->primer_apellido,
                $dato->segundo_apellido,
                $dato->sexo,
                true,
                69,
                $dato->fecha_nacimiento,
                $tipoAseg->id,
                false,
                $dato->id_estado_civil,
                $dato->id_dept_exp,
                $dato->alergias,
                $dato->grupo_sanguineo
            );

            $exitoZonas = $zonas->migrarZona(
                $dato->zona,
                69
            );

            $exitoResidencias = 0;

            if ($exitoZonas != 0) {
                $exitoResidencias = $residencias->migrarResidencia(
                    $dato->direccion,
                    0,
                    $exitoZonas,
                    $dato->latitud,
                    $dato->longitud
                );
            }

            if ($exitoPersonas != 0 && $exitoResidencias != 0) {
                $estudianteMigrado = Estudiante::create([
                    'id_persona' => $exitoPersonas,
                    'registro_universitario' => $dato->ru,
                    'foto_nombre' => $dato->id_afiliado_foto . '.png',
                    'fecha_afiliacion' => Carbon::parse($dato->fecha_afiliacion)->format('Y-m-d'),
                    'telefono_referencia' => $dato->telefono_referencia,
                    'nombre_referencia' => $dato->nombre_referencia,
                    'estado_validar' => 'VALIDADO',
                    'telefono' => $dato->telefono,
                    'estado' => true,
                    'estado_vigencia' => true,
                    'afiliacion' => true,
                    'id_residencia' => $exitoResidencias,
                    'migrado' => true,
                    'id_user_created' => auth()->user()->id,
                ]);

                if ($estudianteMigrado) {
                    $idUsuario = $this->usuarioNuevo($exitoPersonas);

                    $this->transferirImagen($estudianteMigrado->id, $dato->id_afiliado_foto, $idUsuario);

                    $this->registrarHistorial(
                        Carbon::parse($dato->fecha_afiliacion)->format('Y-m-d'),
                        $exitoPersonas,
                        'VIGENTE',
                        $tipoAseg->id,
                        $estudianteMigrado->id
                    );

                    //UPDATE EN LA TABLA ORIGINAL
                    $afiliado_g2 = AfiliacionAfiliados::find($dato->id_afiliado_foto);

                    if ($afiliado_g2) {
                        $afiliado_g2->migrado_s2 = 1;
                        $afiliado_g2->save();
                    }
                }
            }
        }
    }

    public function registrarHistorial($fecha_inicio, $id_persona, $estado_vigencia, $id_tipo_asegurado, $id_correponde)
    {
        $existeHistorialAsegurado = HistorialAsegurado::where('id_persona', $id_persona)->orderBy('id', 'desc')->first();
        if ($existeHistorialAsegurado) {
            $existeHistorialAsegurado->estado = false;
            $existeHistorialAsegurado->estado_vigencia = 'NO VIGENTE';
            if ($existeHistorialAsegurado->fecha_baja == null) {
                $existeHistorialAsegurado->fecha_baja = Carbon::now()->format('Y-m-d');
            }
            $existeHistorialAsegurado->id_user_updated = auth()->user()->id;
            $existeHistorialAsegurado->save();
        }

        HistorialAsegurado::create([
            'estado' => true,
            'estado_vigencia' => $estado_vigencia,
            'id_tipo_asegurado' => $id_tipo_asegurado,
            'fecha_inicio' => $fecha_inicio,
            'id_persona' => $id_persona,
            'id_corresponde' => $id_correponde,
            'id_user_created' => auth()->user()->id,
        ]);
    }

    public function transferirImagen($idTupla, $filename, $idUser)
    {
        try {
            if ($filename) {
                $path = 'FOTOS/' . $filename . '.jpg';
                $imageData = Storage::disk('ftp')->get($path);

                if ($imageData == null) {
                    $path = 'FOTOS/default.png';
                    $imageData = Storage::disk('ftp')->get($path);
                }

                $destino = 'Afiliacion/SSUE/FOTOS/' . $idTupla . '.png';
                $destinoUsuario = 'Administracion/Usuarios/Perfil/' . $idUser . '-U.png';
                Storage::disk('ftp')->put($destino, $imageData);
                Storage::disk('ftp')->put($destinoUsuario, $imageData);
            }
        } catch (\Throwable $th) {
            return;
        }
    }

    public function usuarioNuevo($personaEstudianteId)
    {
        $usuario = User::with('personaUser')->where('id_persona', $personaEstudianteId)->where('estado', true)->first();

        if (!$usuario) {

            $datosPersona = Persona::find($personaEstudianteId);

            $arrayNombres = array_filter([
                $datosPersona->p_apellido ?? null,
                $datosPersona->s_apellido ?? null,
                $datosPersona->nombres,
            ]);

            $usuarioNuevo = $this->generarUsuario(
                $datosPersona->nombres,
                $datosPersona->p_apellido,
                $datosPersona->s_apellido
            );

            $numeroInvertido = (int)strrev((string) $personaEstudianteId);
            $resultado = $numeroInvertido + 3;
            $user = User::create([
                'name' => implode(' ', $arrayNombres),
                'email' => $usuarioNuevo . '-est-' . $resultado,
                'id_persona' => $personaEstudianteId,
                'estado' => true,
                'password' => Hash::make($datosPersona->ci),
                'id_user_created' => auth()->user()->id,
            ]);
            //asignacion de rol

        } else {
            $user = $usuario;
        }

        $nombre_foto = $user->id . '-U.png';
        $user->update([
            'imagen' => $nombre_foto
        ]);

        $user->assignRole('Estudiante SSUE');
        $user->syncRoles(['Estudiante SSUE']);

        return $user->id;
    }

    public function generarUsuario($nombresEst, $p_apellidoEst, $s_apellidoEst)
    {
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
