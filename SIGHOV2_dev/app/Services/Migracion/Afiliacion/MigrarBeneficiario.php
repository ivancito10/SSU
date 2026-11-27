<?php

namespace App\Services\Migracion\Afiliacion;

use App\Models\Administracion\Persona;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Titular;
use App\Models\Migracion\AfiliacionAfiliados;
use App\Models\Migracion\MigracionBeneficiario;
use App\Models\User;
use App\Models\Vigencia\HistorialAsegurado;
use App\Services\Migracion\Afiliacion\MigracionZona;
use App\Services\Migracion\Afiliacion\MigracionResidencia;
use App\Services\Migracion\Afiliacion\MigracionPersona;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MigrarBeneficiario
{

    public function migrarBeneficiario()
    {

        set_time_limit(12000);

        //$datos = MigracionBeneficiario::limit(5)->get();
        $datos = MigracionBeneficiario::where('migrado_s2', null)->get();
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
                $dato->id_tipo_asegurado,
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
                $clave = $dato->DocIdentificacion;
                $encontrarTitular = Titular::whereHas('titularPersona', function ($query) use ($clave) {
                    $query->where('clave_unica', $clave);
                })->orderBy('id', 'asc')->first();

                if ($encontrarTitular) {
                    $beneficiarioMigrado = Beneficiario::create([
                        'fecha_afiliacion' => Carbon::parse($dato->fecha_afiliacion)->format('Y-m-d'),
                        //'foto_nombre' => $dato->id_afiliado_foto . '-B.png',
                        'observacion' => $dato->observaciones,
                        'vigencia' => 'VIGENTE',
                        'telefono_referencia' => $dato->telefono_referencia,
                        'nombre_referencia' => $dato->nombre_referencia,
                        'telefono' => $dato->telefono,
                        'correo' => null,
                        'estado_requisitos' => $dato->estado_requisitos,
                        'id_titular' => $encontrarTitular->id,
                        'id_persona' => $exitoPersonas,
                        'id_residencia' => $exitoResidencias,
                        'con_remuneracion' => false,
                        'permanente' => false,
                        'migrado' => true,
                        'id_user_created' => auth()->user()->id,
                    ]);

                    if ($beneficiarioMigrado) {

                        $beneficiarioMigrado->update([
                            'foto_nombre' => $beneficiarioMigrado->id . '-B.png'
                        ]);

                        $idUsuario = $this->usuarioNuevo($exitoPersonas);

                        $this->transferirImagen($beneficiarioMigrado->id, $dato->id_afiliado_foto, $idUsuario);

                        $this->registrarHistorial(
                            Carbon::parse($dato->fecha_afiliacion)->format('Y-m-d'),
                            $exitoPersonas,
                            'VIGENTE',
                            $dato->id_tipo_asegurado,
                            $beneficiarioMigrado->id
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

                $destino = 'Afiliacion/FOTOS/' . $idTupla . '-B.png';
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
}
