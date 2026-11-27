<?php

namespace App\Services\Migracion\Vigencia;

use App\Models\Administracion\Persona;
use App\Models\Migracion\AseguradosConvenio;
use App\Models\Migracion\ConveniosInterior;
use App\Models\User;
use App\Models\Vigencia\AutorizacionInterior;
use App\Models\Vigencia\HistorialAsegurado;
use App\Services\Migracion\Afiliacion\MigracionPersona;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MigrarConvenioInterior
{
    public function migrarConvenioInterior()
    {
        set_time_limit(12000);
        //$datos = MigracionConvenioInterior::all();
        $datos = ConveniosInterior::where('migracion_s2', null)->get();
        $personas = new MigracionPersona();
        //dd($datos);

        foreach ($datos as $key => $dato) {

            if(!is_int($dato->telefono_referencia)){
                $dato->telefono_referencia = 0;
            }

            if(!is_int($dato->telefono)){
                $dato->telefono = 0;
            }
            
            
            $exitoPersonas = $personas->migrarPersona(
                $dato->numero_documento,
                null,
                $dato->nombres,
                $dato->apellido_paterno,
                $dato->apellido_materno,
                $dato->sexo,
                true,
                69,
                $dato->fecha_nacimiento,
                9,
                false,
                1,
                1,
                null,
                null,
            );

            if($exitoPersonas != 0){

                $convenioMigrado = AutorizacionInterior::create([
                    'cod_interior' => $this->nuevoNumeroCite(),
                    'fecha_inicio' => Carbon::parse($dato->fecha_inicio)->format('Y-m-d'),
                    'fecha_fin' => Carbon::parse($dato->fecha_fin)->format('Y-m-d'),
                    'id_seguro_interior' => $dato->id_seguro_interior,
                    'otras_limitaciones' => $dato->otras_limitaciones,
                    'id_persona_interior' => $exitoPersonas,
                    'id_persona_titular' => $exitoPersonas,
                    'observacion' => 'CORRESPONDE A: '.$dato->cod_interior,
                    'medicamentos_liname' => false,
                    'medicamentos_extraliname' => false,
                    'estado' => true,
                    'estado_validacion' => 'VALIDADO',
                    'fecha_validacion' => Carbon::now(),
                    'direccion_referencia' => $dato->direccion_referencia,
                    'telefono_referencia' => $dato->telefono_referencia,
                    'datos_referencia' => $dato->dato_referencia,
                    'telefono' => $dato->telefono,
                    'id_user_created' => auth()->user()->id,
                ]);

                if($convenioMigrado){
                    $convenioMigrado->update([
                        'foto_nombre' => $convenioMigrado->id.'-CI.png'
                    ]);

                    $idUsuario = $this->usuarioNuevo($exitoPersonas);

                    $this->transferirImagen($convenioMigrado->id, $idUsuario);

                    $this->registrarHistorial(
                        Carbon::parse($dato->fecha_inicio)->format('Y-m-d'),
                        $exitoPersonas,
                        'VIGENTE',
                        9,
                        $convenioMigrado->id
                    );

                    $afiliado_g2 = AseguradosConvenio::find($dato->id_asegurado_convenio);

                    if ($afiliado_g2) {
                        $afiliado_g2->migracion_s2 = 1;
                        $afiliado_g2->save();
                    }
                }
            }
        }
    }

    public function nuevoNumeroCite()
    {
        $autorizacionAnterior = AutorizacionInterior::orderBy('id', 'desc')->first();
        if ($autorizacionAnterior) {
            $ultimaGestion = Carbon::parse($autorizacionAnterior->created_at)->format('Y');
            if ($ultimaGestion == Carbon::now()->format('Y')) {
                return $autorizacionAnterior->cod_interior + 1;
                //$this->cod_interior_m = 'C/IN/'.$this->cod_interior . '/' . $ultimaGestion;
                //dd($this->cite);
            } else {
                //$ultimaGestion = Carbon::now()->format('Y');
                return 1;
                //$this->cod_interior_m = 'C/IN/'.$this->cod_interior . '/' . $ultimaGestion;
                //dd($this->cite);
            }
        } else {
            return 1;
            //$this->cod_interior_m = 'C/IN/'.$this->cod_interior . '/' . Carbon::now()->format('Y');
        }
    }

    public function transferirImagen($idTupla, $idUser)
    {
        try {
            if ($idTupla) {
                $path = 'FOTOS/default.png';
                $imageData = Storage::disk('ftp')->get($path);

                $destino = 'Vigencia/FOTOS/CONVENIO/' . $idTupla . '-CI.png';
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
                'email' => $nuevoUsuario . '-conv-' . $resultado,
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
