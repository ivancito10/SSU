<?php

namespace App\Services\Migracion\Afiliacion;

use App\Models\Administracion\Persona;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Migracion\MigracionTitular;
use App\Models\Migracion\AfiliacionAfiliados;
use App\Models\User;
use App\Models\Vigencia\HistorialAsegurado;
use App\Services\Migracion\Afiliacion\MigracionZona;
use App\Services\Migracion\Afiliacion\MigracionResidencia;
use App\Services\Migracion\Afiliacion\MigracionPersona;
use App\Services\Migracion\Afiliacion\MigracionInstitucion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; 

class MigrarTitular
{

    public function migrarTitular()
    {
        set_time_limit(12000);
        $datos = MigracionTitular::where('migrado_s2', null)->get();
        //$datos = MigracionTitular::limit(15)->get();
        $zonas = new MigracionZona();
        $residencias = new MigracionResidencia();
        $personas = new MigracionPersona();
        $instituciones = new MigracionInstitucion();
        //dd($datos);

        foreach ($datos as $key => $dato) {

            if (!is_int($dato->telefono_institucion)) {
                $dato->telefono_institucion = 0;
            }

            if (is_null($dato->nro_empleador)) {
                $dato->nro_empleador = 0;
            }

            if (is_null($dato->fecha_afiliacion_intitucion)) {
                $dato->fecha_afiliacion_intitucion = '2000-01-01';
            }

            if (is_null($dato->telefono_referencia) || !is_int($dato->telefono_referencia)) {
                $dato->telefono_referencia = 0;
            }

            if ($dato->id_dept_exp == 0) {
                $dato->id_dept_exp = 1;
            }

            if ($dato->telefono == "" || $dato->telefono == null || !is_int($dato->telefono)) {
                $dato->telefono = 0;
            }

            if ($dato->salario == "" || $dato->salario == null || !is_int($dato->salario)) {
                $dato->salario = 0;
            }

            if ($dato->id_estado_civil == "" || $dato->id_estado_civil == 0) {
                $dato->id_estado_civil = 1;
            }

            if ($dato->nit == "" || $dato->nit == null || !is_int($dato->nit)) {
                $dato->nit = 0;
            }

            if ($dato->actividad_empleador == null) {
                $dato->actividad_empleador = "";
            }

            if(Str::contains($dato->grupo_sanguineo, 'SIN GRUPO')){
                $dato->grupo_sanguineo = null;
            }

            

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
                1,
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

            $exitoInstituciones = $instituciones->migrarInstitucion(
                $dato->nit,
                $dato->nro_empleador,
                $dato->actividad_empleador,
                $dato->acronimo,
                $dato->fecha_afiliacion_intitucion,
                $dato->correo_institucion,
                $dato->telefono_institucion,
                $dato->nombre,
                $dato->tgn,
                $dato->publica,
                $dato->estado_institucion,
                $dato->id_institucion
            );

            if ($exitoPersonas != 0 && $exitoResidencias != 0 && $exitoInstituciones != 0) {
                $titularMigrado = Titular::create([
                    'id_persona' => $exitoPersonas,
                    'id_residencia' => $exitoResidencias,
                    'estado_vigencia' => 'VIGENTE',
                    //'foto_nombre' => $dato->id_afiliado_foto . '-T.png',
                    'estado_requisitos' => $dato->estado_requisitos,
                    'telefono_referencia' => $dato->telefono_referencia,
                    'nombre_referencia' => $dato->nombre_referencia,
                    'observacion' => $dato->observacion,
                    'fecha_afiliacion' => Carbon::parse($dato->fecha_afiliacion)->format('Y-m-d'),
                    'telefono' => $dato->telefono,
                    'correo' => null, //DIJIMOS QUE TODO NULO
                    'permanente' => false,
                    'estado' => true,
                    'tipo_titular' => true,
                    'migrado' => true,
                    'id_user_created' => auth()->user()->id,
                    //'tipo_rentista' => '', //NO ESTOY SEGURO,
                ]);

                if ($titularMigrado) {

                    $titularMigrado->update([
                        'foto_nombre' => $titularMigrado->id.'-T.png'
                    ]);

                    $titularInstitucionMigrado = TitularInstitucion::create([
                        'id_titular' => $titularMigrado->id,
                        'id_institucion' => $exitoInstituciones,
                        'estado' => $dato->estado,
                        'fecha_ingreso' => $dato->fecha_ingreso,
                        'cargo_actual' => $dato->cargo_actual,
                        'salario' => $dato->salario,
                        'tipo_institucion' => 'PATRONAL',
                        'id_user_created' => auth()->user()->id,
                    ]);

                    $idUsuario = $this->usuarioNuevo($exitoPersonas);

                    $this->transferirImagen($titularMigrado->id, $dato->id_afiliado_foto, $idUsuario);

                    $this->registrarHistorial(
                        Carbon::parse($dato->fecha_afiliacion)->format('Y-m-d'),
                        $exitoPersonas,
                        'VIGENTE',
                        1,
                        $titularMigrado->id
                    );

                    //update migrado gehma 2
                    $afiliado_g2 = AfiliacionAfiliados::find($dato->id_afiliado_foto);

                    if ($afiliado_g2) {
                        $afiliado_g2->migrado_s2 = 1;
                        $afiliado_g2->save();
                    }
                }
            }
        }
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

                $destino = 'Afiliacion/FOTOS/' . $idTupla . '-T.png';
                $destinoUsuario = 'Administracion/Usuarios/Perfil/' . $idUser . '-U.png';
                Storage::disk('ftp')->put($destino, $imageData);
                Storage::disk('ftp')->put($destinoUsuario, $imageData);
            }
        } catch (\Throwable $th) {
            return;
        }
    }

    public function usuarioNuevo($idPersona)
    {
        // creacion de usuario

        $existeUsuario = User::with('personaUser')->where('id_persona', $idPersona)->first();

        if (!$existeUsuario) {
            $persona_encontrada = Persona::where('id', $idPersona)->first();
            $nuevoUsuario = $this->generarUsuario($persona_encontrada);
            $numeroInvertido = (int)strrev((string)$idPersona);
            $resultado = $numeroInvertido + 3;

            $user = User::create([
                'name' => $persona_encontrada->nombres . ' ' . $persona_encontrada->p_apellido . ' ' . $persona_encontrada->s_apellido,
                'email' => $nuevoUsuario . '-' . $resultado,
                'id_persona' => $idPersona,
                'id_user_created' => auth()->user()->id,
                'estado' => true,
                'password' => Hash::make($persona_encontrada->ci),
            ]);
        } else {
            $user = $existeUsuario;
        }

        $nombre_foto = $user->id . '-U.png';
        $user->update([
            'imagen' => $nombre_foto
        ]);

        //asignacion de rol
        $user->assignRole('Asegurado Afiliado');
        $user->syncRoles(['Asegurado Afiliado']);
        //

        return $user->id;
    }

    public function generarUsuario($persona_encontrada)
    {

        // Convertir a mayúsculas para asegurar consistencia
        $nombres = str_replace(' ', '', mb_strtolower($persona_encontrada->nombres));
        $p_apellido = str_replace(' ', '', mb_strtolower($persona_encontrada->p_apellido));
        $s_apellido = str_replace(' ', '', mb_strtolower($persona_encontrada->s_apellido));

        // Inicializar el email
        $email = '';

        if (!empty($p_apellido) == true && !empty($s_apellido) == true) {
            $email = substr($nombres, 0, 1) . $p_apellido . substr($s_apellido, 0, 1);
        }

        if (!empty($p_apellido) == false && !empty($s_apellido) == true) {
            $email = substr($nombres, 0, 1) . $s_apellido . substr($s_apellido, 0, 1);
        }

        if (!empty($p_apellido) == true && !empty($s_apellido) == false) {
            $email = substr($nombres, 0, 1) . $p_apellido . substr($p_apellido, 0, 1);
        }

        return $email;
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
}
