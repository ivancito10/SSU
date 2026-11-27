<?php

namespace App\Services;

use App\Models\User;
use App\Models\Administracion\Persona;
use App\Models\Vigencia\Convenio;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Estudiante;
use Illuminate\Support\Facades\Cache;
use App\Models\Vigencia\AutorizacionInterior;

class PoblacionAsegurada
{
    public $titularGrupoFamiliar;
    public $beneficiarioGrupoFamiliar;
    public $convenioGrupoFamiliar;

    public function encontrarUsuario($idUser)
    {
        $usuario = User::find($idUser);
        $persona = Persona::where('id', $usuario->id_persona)->with(['tipoAseguradoPersona'])->first();
        //dd($persona->nombres);
        if ($persona->id_tipo_asegurado == 1) {
            dd($this->encontrarTitular($persona->id));
        } elseif ($persona->id_tipo_asegurado == 2 || $persona->id_tipo_asegurado == 3 || $persona->id_tipo_asegurado == 4 || $persona->id_tipo_asegurado == 5 || $persona->id_tipo_asegurado == 6 || $persona->id_tipo_asegurado == 7) {
            dd($this->encontrarBeneficiario($persona->id));
        } elseif ($persona->id_tipo_asegurado == 9 || $persona->id_tipo_asegurado == 10) {
            dd($this->encontrarConvenioInterior($persona->id));
        } elseif ($persona->id_tipo_asegurado == 11) {
            dd($this->encontrarEstudiante($persona->id));
        } elseif ($persona->id_tipo_asegurado == 8) {
            dd($this->encontrarTitularConvenioEmergencia($persona->id));
        } else {
            dd('El usuario no se encuentra afiliado');
        }
    }

    public function encontrarTitular($idPersona)
    {
        $titular = Titular::where('id_persona', $idPersona)
            ->whereIn('estado_vigencia', ['VIGENTE', 'CESANTE'])
            ->first();

        if ($titular) {
            
            $personaTitular = $this->cachePoblacion($idPersona);

            $this->titularGrupoFamiliar[] = $personaTitular;

            $grupoFam = Beneficiario::where('id_titular', $titular->id)
                ->whereIn('vigencia', ['VIGENTE', 'CESANTE'])
                ->get();
            if (isset($grupoFam->toArray()[0])) {
                //dd('significa que tiene grupo');
                foreach ($grupoFam as $key => $fam) {
                    $personaBen = $this->cachePoblacion($fam->id_persona);
                    $this->titularGrupoFamiliar[] = $personaBen;
                }
                return $this->titularGrupoFamiliar;
            } else {
                return $this->titularGrupoFamiliar;
            }

            //$this->titularGrupoFamiliar[]
        } else {
            dd('el usuario no esta vigente');
        }
    }

    public function encontrarBeneficiario($idPersona)
    {
        $ben = Beneficiario::where('id_persona', $idPersona)
            ->whereIn('vigencia', ['VIGENTE', 'CESANTE'])
            ->first();

        if ($ben) {
            $titular = Titular::where('id', $ben->id_titular)
                ->whereIn('estado_vigencia', ['VIGENTE', 'CESANTE'])
                ->first();
            if ($titular) {                
                $personaTitular = $this->cachePoblacion($titular->id_persona);

                $this->beneficiarioGrupoFamiliar[] = $personaTitular;

                $grupoFam = Beneficiario::where('id_titular', $titular->id)
                    ->whereIn('vigencia', ['VIGENTE', 'CESANTE'])
                    ->get();
                if (isset($grupoFam->toArray()[0])) {
                    //dd('significa que tiene grupo');
                    foreach ($grupoFam as $key => $fam) {
                        $personaBen = $this->cachePoblacion($fam->id_persona);

                        $this->beneficiarioGrupoFamiliar[] = $personaBen;
                    }
                    return $this->beneficiarioGrupoFamiliar;
                } else {
                    return $this->beneficiarioGrupoFamiliar;
                }
            } else {
                dd('no tiene titular, raro che');
            }
        } else {
            dd('El beneficiario no esta vigente');
        }
    }

    public function encontrarConvenioInterior($idPersona)
    {
        $convenio = AutorizacionInterior::where('id_persona_interior', $idPersona)->where('estado', true)->first();

        if ($convenio) {
            if ($convenio->id_persona_interior == $convenio->id_persona_titular) {
                
                $personaTitularInterior = $this->cachePoblacion($convenio->id_persona_interior);

                $this->convenioGrupoFamiliar[] = $personaTitularInterior;
                //dd($personaTitularInterior);
                $grupoFam = AutorizacionInterior::where('id_persona_titular', $convenio->id_persona_titular)
                    ->whereNot('id_persona_interior', $convenio->id_persona_titular)
                    ->where('estado', true)
                    ->get();
                //dd($grupoFam);
                if (isset($grupoFam->toArray()[0])) {
                    foreach ($grupoFam as $key => $fam) {
                        
                        $personaBenInterior = $this->cachePoblacion($fam->id_persona_interior);
                        $this->convenioGrupoFamiliar[] = $personaBenInterior;
                    }
                    return $this->convenioGrupoFamiliar;
                } else {
                    return $this->convenioGrupoFamiliar;
                }
            } else {
                $conv = AutorizacionInterior::where('id_persona_interior', $convenio->id_persona_titular)
                    ->where('estado', true)
                    ->first();

                if ($conv) {
                    
                    $titularInterior = $this->cachePoblacion($conv->id_persona_interior);

                    $this->convenioGrupoFamiliar[] = $titularInterior;
                    $grupoFam = AutorizacionInterior::where('id_persona_titular', $conv->id_persona_titular)
                        ->whereNot('id_persona_interior', $conv->id_persona_titular)
                        ->where('estado', true)
                        ->get();
                    if (isset($grupoFam->toArray()[0])) {
                        foreach ($grupoFam as $key => $fam) {
                            
                            $personaBenInterior = $this->cachePoblacion($fam->id_persona_interior);
                            $this->convenioGrupoFamiliar[] = $personaBenInterior;
                        }
                        return $this->convenioGrupoFamiliar;
                    } else {
                        return $this->convenioGrupoFamiliar;
                    }
                } else {
                    $grupoFam = AutorizacionInterior::where('id_persona_titular', $convenio->id_persona_titular)
                        ->where('estado', true)
                        ->get();

                    if (isset($grupoFam->toArray()[0])) {
                        foreach ($grupoFam as $key => $fam) {
                            
                            $personaBenInterior = $this->cachePoblacion($fam->id_persona_interior);
                            $this->convenioGrupoFamiliar[] = $personaBenInterior;
                        }
                        return $this->convenioGrupoFamiliar;
                    } else {
                        dd('El beneficiario del interior no se encuentra vigente');
                    }
                }
            }
        } else {
            dd('El usuario del interior no se encuentra vigente');
        }
    }

    public function encontrarEstudiante($idPersona)
    {
        $estudiante = Estudiante::where('id_persona', $idPersona)->where('estado_vigencia', true)->first();

        if ($estudiante) {
            
            $personaEstudiante = $this->cachePoblacion($idPersona);

            return $personaEstudiante;
        } else {
            dd('El estudiante no se encuentra afiliado');
        }
    }

    public function encontrarTitularConvenioEmergencia($idPersona)
    {
        $convenioEmergencia = Convenio::where('id_persona', $idPersona)->where('estado', true)->first();
        if ($convenioEmergencia) {
            
            $personaEmergencia = $this->cachePoblacion($idPersona);
            return $personaEmergencia;
        } else {
            dd('El titular convenio no se encuentra afiliado');
        }
    }

    public function cachePoblacion($busqueda){
        //Cache
        $nombre_cache = 'DatosPersonas';
        if (Cache::has($nombre_cache)) {
            //dd("hay");
            $personas_datos = Cache::get($nombre_cache);
        } else {
            //dd("no hay");
            $personas_datos = Persona::join('administracion.tipo_asegurado as ta', 'ta.id', '=', 'persona.id_tipo_asegurado')->select('persona.id', 'persona.ci', 'persona.complemento', 'persona.nombres', 'persona.p_apellido', 'persona.s_apellido', 'persona.fecha_nacimiento', 'persona.matricula_seguro', 'persona.id_tipo_asegurado', 'ta.tipo_asegurado')->get();

            Cache::put($nombre_cache, $personas_datos, 60);
        }
        //

        return $personas_datos->where('id', $busqueda);
    }
}
