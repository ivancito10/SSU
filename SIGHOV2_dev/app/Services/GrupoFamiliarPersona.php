<?php

namespace App\Services;

use App\Models\Administracion\Persona;
use App\Models\Vigencia\Convenio;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Estudiante;
use App\Models\Vigencia\AutorizacionInterior;
use App\Models\Vigencia\Preafiliacion;
use Illuminate\Support\Str;

class GrupoFamiliarPersona
{
    public $titularGrupoFamiliar = [];
    public $beneficiarioGrupoFamiliar = [];
    public $convenioGrupoFamiliar = [];
    public $datosPersonaEstudiante = [];
    public $preafiliadoTitularGrupoFamiliar = [];

    public function encontrarPersona($idPersona)
    {
        $persona = Persona::where('id', $idPersona)->with(['tipoAseguradoPersona'])->first();

        $datosAfiliado = [];
        //dd($persona);
        if ($persona->tipoAseguradoPersona->tipo_asegurado == 'TITULAR') {

            $datosAfiliado = $this->encontrarTitular($persona->id);
            // dd($datosAfiliado);
            return $datosAfiliado;
        } elseif ($persona->tipoAseguradoPersona->tipo_asegurado == 'PREAFILIADO TITULAR') {
            $datosAfiliado = $this->encontrarPreafiliadoTitular($persona->id);
            return $datosAfiliado;
        } elseif (Str::contains($persona->tipoAseguradoPersona->tipo_asegurado, 'BENEFICIARIO')) {
            if ($persona->tipoAseguradoPersona->tipo_asegurado == 'BENEFICIARIO DEL INTERIOR') {
                $datosAfiliado = $this->encontrarConvenioInterior($persona->id);
            } else {
                if ($persona->tipoAseguradoPersona->tipo_asegurado == 'PREAFILIADO BENEFICIARIO') {
                    $datosAfiliado = $this->encontrarPreafiliadoBeneficiario($persona->id);
                } else {
                    $datosAfiliado = $this->encontrarBeneficiario($persona->id);
                }
            }
            return $datosAfiliado;
        } elseif (
            $persona->tipoAseguradoPersona->tipo_asegurado == 'TITULAR DEL INTERIOR' ||
            $persona->tipoAseguradoPersona->tipo_asegurado == 'BENEFICIARIO DEL INTERIOR'
        ) {
            $datosAfiliado = $this->encontrarConvenioInterior($persona->id);
            return $datosAfiliado;
        } elseif ($persona->tipoAseguradoPersona->tipo_asegurado == 'ESTUDIANTE') {
            $datosAfiliado = $this->encontrarEstudiante($persona->id);
            return $datosAfiliado;
        } elseif ($persona->tipoAseguradoPersona->tipo_asegurado == 'TITULAR CONVENIO') {
            $datosAfiliado = $this->encontrarTitularConvenioEmergencia($persona->id);
            return $datosAfiliado;
        } else {
            return [];
        }
    }

    public function encontrarTitular($idPersona)
    {
        $titular = Titular::with(['titularPersona'])
            ->where('id_persona', $idPersona)
            ->whereIn('estado_vigencia', ['VIGENTE', 'CESANTE'])
            ->where('estado', true)
            ->whereHas('titularPersona', function ($comprobarPersona) {
                $comprobarPersona->whereNot('id_tipo_asegurado', 0)
                    ->where('afiliado', true);
            })
            ->first();

        if ($titular) {
            $personaTitular = $this->busquedaPoblacion($idPersona);
            //dd($personaTitular[0]->id);
            $this->titularGrupoFamiliar[0]['id'] = $personaTitular[0]->id;
            $this->titularGrupoFamiliar[0]['ci'] = $personaTitular[0]->ci;
            $this->titularGrupoFamiliar[0]['complemento'] = $personaTitular[0]->complemento;
            $this->titularGrupoFamiliar[0]['matricula_seguro'] = $personaTitular[0]->matricula_seguro;
            $this->titularGrupoFamiliar[0]['nombres'] = $personaTitular[0]->nombres;
            $this->titularGrupoFamiliar[0]['p_apellido'] = $personaTitular[0]->p_apellido;
            $this->titularGrupoFamiliar[0]['s_apellido'] = $personaTitular[0]->s_apellido;
            $this->titularGrupoFamiliar[0]['fecha_nacimiento'] = $personaTitular[0]->fecha_nacimiento;
            $this->titularGrupoFamiliar[0]['id_tipo_asegurado'] = $personaTitular[0]->id_tipo_asegurado;
            $this->titularGrupoFamiliar[0]['tipo_asegurado'] = $personaTitular[0]->tipo_asegurado;
            $this->titularGrupoFamiliar[0]['id_estado_civil'] = $personaTitular[0]->id_estado_civil;
            $this->titularGrupoFamiliar[0]['estado_civil'] = $personaTitular[0]->nombre;
            $this->titularGrupoFamiliar[0]['id_expedido'] = $personaTitular[0]->id_dept_exp;
            $this->titularGrupoFamiliar[0]['expedido'] = $personaTitular[0]->sigla;
            $this->titularGrupoFamiliar[0]['sexo'] = $personaTitular[0]->sexo;

            $grupoFam = Beneficiario::with(['beneficiarioPersona'])
                ->where('id_titular', $titular->id)
                ->whereIn('vigencia', ['VIGENTE', 'CESANTE'])
                ->where('estado', true)
                ->orderBy('id', 'desc') // 14/03/25 añadi esto porque me daba todos los registros de sus beneficarios.Atte:liz
                ->whereHas('beneficiarioPersona', function ($comprobarPersona) {
                    $comprobarPersona->whereNot('id_tipo_asegurado', 0)
                        ->where('afiliado', true);
                })
                ->get();
            //dd($grupoFam);
            if (isset($grupoFam->toArray()[0])) {
                //dd('significa que tiene grupo');
                foreach ($grupoFam as $key => $fam) {

                    $personaBen = $this->busquedaPoblacion($fam->id_persona);
                    //dd($personaBen, $fam->id_persona, $grupoFam);
                    //dd($this->titularGrupoFamiliar);
                    $this->titularGrupoFamiliar[$key + 1]['id'] = $personaBen[0]->id;
                    $this->titularGrupoFamiliar[$key + 1]['ci'] = $personaBen[0]->ci;
                    $this->titularGrupoFamiliar[$key + 1]['complemento'] = $personaBen[0]->complemento;
                    $this->titularGrupoFamiliar[$key + 1]['matricula_seguro'] = $personaBen[0]->matricula_seguro;
                    $this->titularGrupoFamiliar[$key + 1]['nombres'] = $personaBen[0]->nombres;
                    $this->titularGrupoFamiliar[$key + 1]['p_apellido'] = $personaBen[0]->p_apellido;
                    $this->titularGrupoFamiliar[$key + 1]['s_apellido'] = $personaBen[0]->s_apellido;
                    $this->titularGrupoFamiliar[$key + 1]['fecha_nacimiento'] = $personaBen[0]->fecha_nacimiento;
                    $this->titularGrupoFamiliar[$key + 1]['id_tipo_asegurado'] = $personaBen[0]->id_tipo_asegurado;
                    $this->titularGrupoFamiliar[$key + 1]['tipo_asegurado'] = $personaBen[0]->tipo_asegurado;
                    $this->titularGrupoFamiliar[$key + 1]['id_estado_civil'] = $personaBen[0]->id_estado_civil;
                    $this->titularGrupoFamiliar[$key + 1]['estado_civil'] = $personaBen[0]->nombre;
                    $this->titularGrupoFamiliar[$key + 1]['id_expedido'] = $personaBen[0]->id_dept_exp;
                    $this->titularGrupoFamiliar[$key + 1]['expedido'] = $personaBen[0]->sigla;
                    $this->titularGrupoFamiliar[$key + 1]['sexo'] = $personaBen[0]->sexo;
                }
                return $this->titularGrupoFamiliar;
            } else {
                return $this->titularGrupoFamiliar;
            }

            //$this->titularGrupoFamiliar[]
        } else {
            return [];
        }
    }

    public function encontrarBeneficiario($idPersona)
    {
        //dd($idPersona);
        $ben = Beneficiario::with(['beneficiarioPersona'])
            ->where('id_persona', $idPersona)
            ->whereIn('vigencia', ['VIGENTE', 'CESANTE'])
            ->where('estado', true)
            ->whereHas('beneficiarioPersona', function ($comprobarPersona) {
                $comprobarPersona->whereNot('id_tipo_asegurado', 0)
                    ->where('afiliado', true);
            })
            ->first();
        //dd($ben);
        if ($ben) {
            $titular = Titular::with(['titularPersona'])
                ->where('id', $ben->id_titular)
                ->whereIn('estado_vigencia', ['VIGENTE', 'CESANTE'])
                ->where('estado', true)
                ->whereHas('titularPersona', function ($comprobarPersona) {
                    $comprobarPersona->whereNot('id_tipo_asegurado', 0)
                        ->where('afiliado', true);
                })
                ->orderBy('id', 'desc')
                ->first();

            if ($titular) {
                $personaTitular = $this->busquedaPoblacion($titular->id_persona);

                $this->beneficiarioGrupoFamiliar[0]['id'] = $personaTitular[0]->id;
                $this->beneficiarioGrupoFamiliar[0]['ci'] = $personaTitular[0]->ci;
                $this->beneficiarioGrupoFamiliar[0]['complemento'] = $personaTitular[0]->complemento;
                $this->beneficiarioGrupoFamiliar[0]['matricula_seguro'] = $personaTitular[0]->matricula_seguro;
                $this->beneficiarioGrupoFamiliar[0]['nombres'] = $personaTitular[0]->nombres;
                $this->beneficiarioGrupoFamiliar[0]['p_apellido'] = $personaTitular[0]->p_apellido;
                $this->beneficiarioGrupoFamiliar[0]['s_apellido'] = $personaTitular[0]->s_apellido;
                $this->beneficiarioGrupoFamiliar[0]['fecha_nacimiento'] = $personaTitular[0]->fecha_nacimiento;
                $this->beneficiarioGrupoFamiliar[0]['id_tipo_asegurado'] = $personaTitular[0]->id_tipo_asegurado;
                $this->beneficiarioGrupoFamiliar[0]['tipo_asegurado'] = $personaTitular[0]->tipo_asegurado;
                $this->beneficiarioGrupoFamiliar[0]['id_estado_civil'] = $personaTitular[0]->id_estado_civil;
                $this->beneficiarioGrupoFamiliar[0]['estado_civil'] = $personaTitular[0]->nombre;
                $this->beneficiarioGrupoFamiliar[0]['id_expedido'] = $personaTitular[0]->id_dept_exp;
                $this->beneficiarioGrupoFamiliar[0]['expedido'] = $personaTitular[0]->sigla;
                $this->beneficiarioGrupoFamiliar[0]['sexo'] = $personaTitular[0]->sexo;

                $grupoFam = Beneficiario::with(['beneficiarioPersona'])
                    ->where('id_titular', $titular->id)
                    ->whereIn('vigencia', ['VIGENTE', 'CESANTE'])
                    ->where('estado', true)
                    ->whereHas('beneficiarioPersona', function ($comprobarPersona) {
                        $comprobarPersona->whereNot('id_tipo_asegurado', 0)
                            ->where('afiliado', true);
                    })
                    ->get();
                if (isset($grupoFam->toArray()[0])) {
                    //dd('significa que tiene grupo');
                    //dd($grupoFam);
                    foreach ($grupoFam as $key => $fam) {
                        $personaBen = $this->busquedaPoblacion($fam->id_persona);
                        //dd($fam->id_persona);
                        $this->beneficiarioGrupoFamiliar[$key + 1]['id'] = $personaBen[0]->id;
                        $this->beneficiarioGrupoFamiliar[$key + 1]['ci'] = $personaBen[0]->ci;
                        $this->beneficiarioGrupoFamiliar[$key + 1]['complemento'] = $personaBen[0]->complemento;
                        $this->beneficiarioGrupoFamiliar[$key + 1]['matricula_seguro'] = $personaBen[0]->matricula_seguro;
                        $this->beneficiarioGrupoFamiliar[$key + 1]['nombres'] = $personaBen[0]->nombres;
                        $this->beneficiarioGrupoFamiliar[$key + 1]['p_apellido'] = $personaBen[0]->p_apellido;
                        $this->beneficiarioGrupoFamiliar[$key + 1]['s_apellido'] = $personaBen[0]->s_apellido;
                        $this->beneficiarioGrupoFamiliar[$key + 1]['fecha_nacimiento'] = $personaBen[0]->fecha_nacimiento;
                        $this->beneficiarioGrupoFamiliar[$key + 1]['id_tipo_asegurado'] = $personaBen[0]->id_tipo_asegurado;
                        $this->beneficiarioGrupoFamiliar[$key + 1]['tipo_asegurado'] = $personaBen[0]->tipo_asegurado;
                        $this->beneficiarioGrupoFamiliar[$key + 1]['id_estado_civil'] = $personaBen[0]->id_estado_civil;
                        $this->beneficiarioGrupoFamiliar[$key + 1]['estado_civil'] = $personaBen[0]->nombre;
                        $this->beneficiarioGrupoFamiliar[$key + 1]['id_expedido'] = $personaBen[0]->id_dept_exp;
                        $this->beneficiarioGrupoFamiliar[$key + 1]['expedido'] = $personaBen[0]->sigla;
                        $this->beneficiarioGrupoFamiliar[$key + 1]['sexo'] = $personaBen[0]->sexo;
                    }
                    return $this->beneficiarioGrupoFamiliar;
                } else {
                    return $this->beneficiarioGrupoFamiliar;
                }
            } else {
                return [];
            }
        } else {
            return [];
        }
    }

    public function encontrarConvenioInterior($idPersona)
    {
        $convenio = AutorizacionInterior::with(['personaInteriorAutorizacionInterior'])
            ->where('id_persona_interior', $idPersona)
            ->where('estado', true)
            ->whereHas('personaInteriorAutorizacionInterior', function ($comprobarPersona) {
                $comprobarPersona->whereNot('id_tipo_asegurado', 0)
                    ->where('afiliado', true);
            })
            ->first();

        if ($convenio) {
            if ($convenio->id_persona_interior == $convenio->id_persona_titular) {
                $personaTitularInterior = $this->busquedaPoblacion($convenio->id_persona_interior);

                $this->convenioGrupoFamiliar[0]['id'] = $personaTitularInterior[0]->id;
                $this->convenioGrupoFamiliar[0]['ci'] = $personaTitularInterior[0]->ci;
                $this->convenioGrupoFamiliar[0]['complemento'] = $personaTitularInterior[0]->complemento;
                $this->convenioGrupoFamiliar[0]['matricula_seguro'] = $personaTitularInterior[0]->matricula_seguro;
                $this->convenioGrupoFamiliar[0]['nombres'] = $personaTitularInterior[0]->nombres;
                $this->convenioGrupoFamiliar[0]['p_apellido'] = $personaTitularInterior[0]->p_apellido;
                $this->convenioGrupoFamiliar[0]['s_apellido'] = $personaTitularInterior[0]->s_apellido;
                $this->convenioGrupoFamiliar[0]['fecha_nacimiento'] = $personaTitularInterior[0]->fecha_nacimiento;
                $this->convenioGrupoFamiliar[0]['id_tipo_asegurado'] = $personaTitularInterior[0]->id_tipo_asegurado;
                $this->convenioGrupoFamiliar[0]['tipo_asegurado'] = $personaTitularInterior[0]->tipo_asegurado;
                $this->convenioGrupoFamiliar[0]['id_estado_civil'] = $personaTitularInterior[0]->id_estado_civil;
                $this->convenioGrupoFamiliar[0]['estado_civil'] = $personaTitularInterior[0]->nombre;
                $this->convenioGrupoFamiliar[0]['id_expedido'] = $personaTitularInterior[0]->id_dept_exp;
                $this->convenioGrupoFamiliar[0]['expedido'] = $personaTitularInterior[0]->sigla;
                $this->convenioGrupoFamiliar[0]['sexo'] = $personaTitularInterior[0]->sexo;
                //dd($personaTitularInterior);
                $grupoFam = AutorizacionInterior::with(['personaInteriorAutorizacionInterior'])
                    ->where('id_persona_titular', $convenio->id_persona_titular)
                    ->whereNot('id_persona_interior', $convenio->id_persona_titular)
                    ->where('estado', true)
                    ->whereHas('personaInteriorAutorizacionInterior', function ($comprobarPersona) {
                        $comprobarPersona->whereNot('id_tipo_asegurado', 0)
                            ->where('afiliado', true);
                    })
                    ->get();
                //dd($grupoFam);
                if (isset($grupoFam->toArray()[0])) {
                    foreach ($grupoFam as $key => $fam) {
                        $personaBenInterior = $this->busquedaPoblacion($fam->id_persona_interior);
                        $this->convenioGrupoFamiliar[$key + 1]['id'] = $personaBenInterior[0]->id;
                        $this->convenioGrupoFamiliar[$key + 1]['ci'] = $personaBenInterior[0]->ci;
                        $this->convenioGrupoFamiliar[$key + 1]['complemento'] = $personaBenInterior[0]->complemento;
                        $this->convenioGrupoFamiliar[$key + 1]['matricula_seguro'] = $personaBenInterior[0]->matricula_seguro;
                        $this->convenioGrupoFamiliar[$key + 1]['nombres'] = $personaBenInterior[0]->nombres;
                        $this->convenioGrupoFamiliar[$key + 1]['p_apellido'] = $personaBenInterior[0]->p_apellido;
                        $this->convenioGrupoFamiliar[$key + 1]['s_apellido'] = $personaBenInterior[0]->s_apellido;
                        $this->convenioGrupoFamiliar[$key + 1]['fecha_nacimiento'] = $personaBenInterior[0]->fecha_nacimiento;
                        $this->convenioGrupoFamiliar[$key + 1]['id_tipo_asegurado'] = $personaBenInterior[0]->id_tipo_asegurado;
                        $this->convenioGrupoFamiliar[$key + 1]['tipo_asegurado'] = $personaBenInterior[0]->tipo_asegurado;
                        $this->convenioGrupoFamiliar[$key + 1]['id_estado_civil'] = $personaBenInterior[0]->id_estado_civil;
                        $this->convenioGrupoFamiliar[$key + 1]['estado_civil'] = $personaBenInterior[0]->nombre;
                        $this->convenioGrupoFamiliar[$key + 1]['id_expedido'] = $personaBenInterior[0]->id_dept_exp;
                        $this->convenioGrupoFamiliar[$key + 1]['expedido'] = $personaBenInterior[0]->sigla;
                        $this->convenioGrupoFamiliar[$key + 1]['sexo'] = $personaBenInterior[0]->sexo;
                    }
                    return $this->convenioGrupoFamiliar;
                } else {
                    return $this->convenioGrupoFamiliar;
                }
            } else {
                $conv = AutorizacionInterior::with(['personaInteriorAutorizacionInterior'])
                    ->where('id_persona_interior', $convenio->id_persona_titular)
                    ->where('estado', true)
                    ->orderBy('id', 'desc')
                    ->whereHas('personaInteriorAutorizacionInterior', function ($comprobarPersona) {
                        $comprobarPersona->whereNot('id_tipo_asegurado', 0)
                            ->where('afiliado', true);
                    })
                    ->first();

                if ($conv) {
                    //dd('entra aqui1' );
                    $titularInterior = $this->busquedaPoblacion($conv->id_persona_interior);
                    $this->convenioGrupoFamiliar[0]['id'] = $titularInterior[0]->id;
                    $this->convenioGrupoFamiliar[0]['ci'] = $titularInterior[0]->ci;
                    $this->convenioGrupoFamiliar[0]['complemento'] = $titularInterior[0]->complemento;
                    $this->convenioGrupoFamiliar[0]['matricula_seguro'] = $titularInterior[0]->matricula_seguro;
                    $this->convenioGrupoFamiliar[0]['nombres'] = $titularInterior[0]->nombres;
                    $this->convenioGrupoFamiliar[0]['p_apellido'] = $titularInterior[0]->p_apellido;
                    $this->convenioGrupoFamiliar[0]['s_apellido'] = $titularInterior[0]->s_apellido;
                    $this->convenioGrupoFamiliar[0]['fecha_nacimiento'] = $titularInterior[0]->fecha_nacimiento;
                    $this->convenioGrupoFamiliar[0]['id_tipo_asegurado'] = $titularInterior[0]->id_tipo_asegurado;
                    $this->convenioGrupoFamiliar[0]['tipo_asegurado'] = $titularInterior[0]->tipo_asegurado;
                    $this->convenioGrupoFamiliar[0]['id_estado_civil'] = $titularInterior[0]->id_estado_civil;
                    $this->convenioGrupoFamiliar[0]['estado_civil'] = $titularInterior[0]->nombre;
                    $this->convenioGrupoFamiliar[0]['id_expedido'] = $titularInterior[0]->id_dept_exp;
                    $this->convenioGrupoFamiliar[0]['expedido'] = $titularInterior[0]->sigla;
                    $this->convenioGrupoFamiliar[0]['sexo'] = $titularInterior[0]->sexo;

                    $grupoFam = AutorizacionInterior::with(['personaInteriorAutorizacionInterior'])
                        ->where('id_persona_titular', $conv->id_persona_titular)
                        ->whereNot('id_persona_interior', $conv->id_persona_titular)
                        ->where('estado', true)
                        ->whereHas('personaInteriorAutorizacionInterior', function ($comprobarPersona) {
                            $comprobarPersona->whereNot('id_tipo_asegurado', 0)
                                ->where('afiliado', true);
                        })
                        ->get();
                    if (isset($grupoFam->toArray()[0])) {
                        foreach ($grupoFam as $key => $fam) {
                            $personaBenInterior = $this->busquedaPoblacion($fam->id_persona_interior);
                            $this->convenioGrupoFamiliar[$key + 1]['id'] = $personaBenInterior[0]->id;
                            $this->convenioGrupoFamiliar[$key + 1]['ci'] = $personaBenInterior[0]->ci;
                            $this->convenioGrupoFamiliar[$key + 1]['complemento'] = $personaBenInterior[0]->complemento;
                            $this->convenioGrupoFamiliar[$key + 1]['matricula_seguro'] = $personaBenInterior[0]->matricula_seguro;
                            $this->convenioGrupoFamiliar[$key + 1]['nombres'] = $personaBenInterior[0]->nombres;
                            $this->convenioGrupoFamiliar[$key + 1]['p_apellido'] = $personaBenInterior[0]->p_apellido;
                            $this->convenioGrupoFamiliar[$key + 1]['s_apellido'] = $personaBenInterior[0]->s_apellido;
                            $this->convenioGrupoFamiliar[$key + 1]['fecha_nacimiento'] = $personaBenInterior[0]->fecha_nacimiento;
                            $this->convenioGrupoFamiliar[$key + 1]['id_tipo_asegurado'] = $personaBenInterior[0]->id_tipo_asegurado;
                            $this->convenioGrupoFamiliar[$key + 1]['tipo_asegurado'] = $personaBenInterior[0]->tipo_asegurado;
                            $this->convenioGrupoFamiliar[$key + 1]['id_estado_civil'] = $personaBenInterior[0]->id_estado_civil;
                            $this->convenioGrupoFamiliar[$key + 1]['estado_civil'] = $personaBenInterior[0]->nombre;
                            $this->convenioGrupoFamiliar[$key + 1]['id_expedido'] = $personaBenInterior[0]->id_dept_exp;
                            $this->convenioGrupoFamiliar[$key + 1]['expedido'] = $personaBenInterior[0]->sigla;
                            $this->convenioGrupoFamiliar[$key + 1]['sexo'] = $personaBenInterior[0]->sexo;
                        }
                        return $this->convenioGrupoFamiliar;
                    } else {
                        return $this->convenioGrupoFamiliar;
                    }
                } else {
                    //dd('entra aqui2' );
                    $grupoFam = AutorizacionInterior::with(['personaInteriorAutorizacionInterior'])
                        ->where('id_persona_interior', $convenio->id_persona_interior)
                        ->where('estado', true)
                        ->whereHas('personaInteriorAutorizacionInterior', function ($comprobarPersona) {
                            $comprobarPersona->whereNot('id_tipo_asegurado', 0)
                                ->where('afiliado', true);
                        })
                        ->get();

                    if (isset($grupoFam->toArray()[0])) {
                        foreach ($grupoFam as $key => $fam) {
                            $personaBenInterior = $this->busquedaPoblacion($fam->id_persona_interior);
                            $this->convenioGrupoFamiliar[$key + 1]['id'] = $personaBenInterior[0]->id;
                            $this->convenioGrupoFamiliar[$key + 1]['ci'] = $personaBenInterior[0]->ci;
                            $this->convenioGrupoFamiliar[$key + 1]['complemento'] = $personaBenInterior[0]->complemento;
                            $this->convenioGrupoFamiliar[$key + 1]['matricula_seguro'] = $personaBenInterior[0]->matricula_seguro;
                            $this->convenioGrupoFamiliar[$key + 1]['nombres'] = $personaBenInterior[0]->nombres;
                            $this->convenioGrupoFamiliar[$key + 1]['p_apellido'] = $personaBenInterior[0]->p_apellido;
                            $this->convenioGrupoFamiliar[$key + 1]['s_apellido'] = $personaBenInterior[0]->s_apellido;
                            $this->convenioGrupoFamiliar[$key + 1]['fecha_nacimiento'] = $personaBenInterior[0]->fecha_nacimiento;
                            $this->convenioGrupoFamiliar[$key + 1]['id_tipo_asegurado'] = $personaBenInterior[0]->id_tipo_asegurado;
                            $this->convenioGrupoFamiliar[$key + 1]['tipo_asegurado'] = $personaBenInterior[0]->tipo_asegurado;
                            $this->convenioGrupoFamiliar[$key + 1]['id_estado_civil'] = $personaBenInterior[0]->id_estado_civil;
                            $this->convenioGrupoFamiliar[$key + 1]['estado_civil'] = $personaBenInterior[0]->nombre;
                            $this->convenioGrupoFamiliar[$key + 1]['id_expedido'] = $personaBenInterior[0]->id_dept_exp;
                            $this->convenioGrupoFamiliar[$key + 1]['expedido'] = $personaBenInterior[0]->sigla;
                            $this->convenioGrupoFamiliar[$key + 1]['sexo'] = $personaBenInterior[0]->sexo;
                        }
                        return $this->convenioGrupoFamiliar;
                    } else {
                        return [];
                    }
                }
            }
        } else {
            return [];
        }
    }

    public function encontrarEstudiante($idPersona)
    {
        $estudiante = Estudiante::with(['personaEstudiante'])
            ->where('id_persona', $idPersona)
            ->where('estado_vigencia', true)
            ->where('estado', true)
            ->whereHas('personaEstudiante', function ($comprobarPersona) {
                $comprobarPersona->whereNot('id_tipo_asegurado', 0)
                    ->where('afiliado', true);
            })
            ->first();

        if ($estudiante) {
            $personaEstudiante = $this->busquedaPoblacion($idPersona);

            $this->datosPersonaEstudiante[0]['id'] = $personaEstudiante[0]->id;
            $this->datosPersonaEstudiante[0]['ci'] = $personaEstudiante[0]->ci;
            $this->datosPersonaEstudiante[0]['complemento'] = $personaEstudiante[0]->complemento;
            $this->datosPersonaEstudiante[0]['matricula_seguro'] = $personaEstudiante[0]->matricula_seguro;
            $this->datosPersonaEstudiante[0]['nombres'] = $personaEstudiante[0]->nombres;
            $this->datosPersonaEstudiante[0]['p_apellido'] = $personaEstudiante[0]->p_apellido;
            $this->datosPersonaEstudiante[0]['s_apellido'] = $personaEstudiante[0]->s_apellido;
            $this->datosPersonaEstudiante[0]['fecha_nacimiento'] = $personaEstudiante[0]->fecha_nacimiento;
            $this->datosPersonaEstudiante[0]['id_tipo_asegurado'] = $personaEstudiante[0]->id_tipo_asegurado;
            $this->datosPersonaEstudiante[0]['tipo_asegurado'] = $personaEstudiante[0]->tipo_asegurado;
            $this->datosPersonaEstudiante[0]['id_estado_civil'] = $personaEstudiante[0]->id_estado_civil;
            $this->datosPersonaEstudiante[0]['estado_civil'] = $personaEstudiante[0]->nombre;
            $this->datosPersonaEstudiante[0]['id_expedido'] = $personaEstudiante[0]->id_dept_exp;
            $this->datosPersonaEstudiante[0]['expedido'] = $personaEstudiante[0]->sigla;
            $this->datosPersonaEstudiante[0]['sexo'] = $personaEstudiante[0]->sexo;
            return $this->datosPersonaEstudiante;
        } else {
            return [];
        }
    }

    public function encontrarTitularConvenioEmergencia($idPersona)
    {
        $convenioEmergencia = Convenio::with(['personaConvenio'])
            ->where('id_persona', $idPersona)
            ->where('estado', true)
            ->whereHas('personaConvenio', function ($comprobarPersona) {
                $comprobarPersona->whereNot('id_tipo_asegurado', 0)
                    ->where('afiliado', true);
            })
            ->first();
        //dd($convenioEmergencia);
        if ($convenioEmergencia) {
            $personaEmergencia = $this->busquedaPoblacion($idPersona);
            //dd(count($personaEmergencia) > 0);
            if (count($personaEmergencia) > 0) {
                $datosConveniosEmergencia[0]['id'] = $personaEmergencia[0]->id;
                $datosConveniosEmergencia[0]['ci'] = $personaEmergencia[0]->ci;
                $datosConveniosEmergencia[0]['complemento'] = $personaEmergencia[0]->complemento;
                $datosConveniosEmergencia[0]['matricula_seguro'] = $personaEmergencia[0]->matricula_seguro;
                $datosConveniosEmergencia[0]['nombres'] = $personaEmergencia[0]->nombres;
                $datosConveniosEmergencia[0]['p_apellido'] = $personaEmergencia[0]->p_apellido;
                $datosConveniosEmergencia[0]['s_apellido'] = $personaEmergencia[0]->s_apellido;
                $datosConveniosEmergencia[0]['fecha_nacimiento'] = $personaEmergencia[0]->fecha_nacimiento;
                $datosConveniosEmergencia[0]['id_tipo_asegurado'] = $personaEmergencia[0]->id_tipo_asegurado;
                $datosConveniosEmergencia[0]['tipo_asegurado'] = $personaEmergencia[0]->tipo_asegurado;
                $datosConveniosEmergencia[0]['id_estado_civil'] = $personaEmergencia[0]->id_estado_civil;
                $datosConveniosEmergencia[0]['estado_civil'] = $personaEmergencia[0]->nombre;
                $datosConveniosEmergencia[0]['id_expedido'] = $personaEmergencia[0]->id_dept_exp;
                $datosConveniosEmergencia[0]['expedido'] = $personaEmergencia[0]->sigla;
                $datosConveniosEmergencia[0]['sexo'] = $personaEmergencia[0]->sexo;

                return $datosConveniosEmergencia;
            } else {
                return [];
            }
        } else {
            return [];
        }
    }

    public function encontrarPreafiliadoTitular($idPersona)
    {
        $preafiliado = Preafiliacion::with(['institucionPreafiliacion.codigosInstitucion', 'personaPreafiliacion'])
            ->where('estado', true)
            ->where('id_persona', $idPersona)
            ->orderBy('id', 'desc')
            ->whereHas('personaPreafiliacion', function ($comprobarPersona) {
                $comprobarPersona->whereNot('id_tipo_asegurado', 0)
                    ->where('afiliado', true);
            })
            ->first();

        if ($preafiliado) {
            $personaPreafiliada = $this->busquedaPoblacion($idPersona);
            //dd($personaTitular[0]->id);
            $this->preafiliadoTitularGrupoFamiliar[0]['id'] = $personaPreafiliada[0]->id;
            $this->preafiliadoTitularGrupoFamiliar[0]['ci'] = $personaPreafiliada[0]->ci;
            $this->preafiliadoTitularGrupoFamiliar[0]['complemento'] = $personaPreafiliada[0]->complemento;
            $this->preafiliadoTitularGrupoFamiliar[0]['matricula_seguro'] = $personaPreafiliada[0]->matricula_seguro;
            $this->preafiliadoTitularGrupoFamiliar[0]['nombres'] = $personaPreafiliada[0]->nombres;
            $this->preafiliadoTitularGrupoFamiliar[0]['p_apellido'] = $personaPreafiliada[0]->p_apellido;
            $this->preafiliadoTitularGrupoFamiliar[0]['s_apellido'] = $personaPreafiliada[0]->s_apellido;
            $this->preafiliadoTitularGrupoFamiliar[0]['fecha_nacimiento'] = $personaPreafiliada[0]->fecha_nacimiento;
            $this->preafiliadoTitularGrupoFamiliar[0]['id_tipo_asegurado'] = $personaPreafiliada[0]->id_tipo_asegurado;
            $this->preafiliadoTitularGrupoFamiliar[0]['tipo_asegurado'] = $personaPreafiliada[0]->tipo_asegurado;
            $this->preafiliadoTitularGrupoFamiliar[0]['id_estado_civil'] = $personaPreafiliada[0]->id_estado_civil;
            $this->preafiliadoTitularGrupoFamiliar[0]['estado_civil'] = $personaPreafiliada[0]->nombre;
            $this->preafiliadoTitularGrupoFamiliar[0]['id_expedido'] = $personaPreafiliada[0]->id_dept_exp;
            $this->preafiliadoTitularGrupoFamiliar[0]['expedido'] = $personaPreafiliada[0]->sigla;
            $this->preafiliadoTitularGrupoFamiliar[0]['sexo'] = $personaPreafiliada[0]->sexo;

            /*$grupoFam = Beneficiario::where('id_titular', $titular->id)
                ->whereIn('vigencia', ['VIGENTE', 'CESANTE'])
                ->where('estado', true) // 14/03/25 añadi esto porque me daba todos los registros de sus beneficarios.Atte:liz
                ->get();*/

            /*if (isset($grupoFam->toArray()[0])) {
                //dd('significa que tiene grupo');
                foreach ($grupoFam as $key => $fam) {
                    $personaBen = $this->busquedaPoblacion($fam->id_persona);
                    $this->titularGrupoFamiliar[$key + 1]['id'] = $personaBen[0]->id;
                    $this->titularGrupoFamiliar[$key + 1]['ci'] = $personaBen[0]->ci;
                    $this->titularGrupoFamiliar[$key + 1]['complemento'] = $personaBen[0]->complemento;
                    $this->titularGrupoFamiliar[$key + 1]['matricula_seguro'] = $personaBen[0]->matricula_seguro;
                    $this->titularGrupoFamiliar[$key + 1]['nombres'] = $personaBen[0]->nombres;
                    $this->titularGrupoFamiliar[$key + 1]['p_apellido'] = $personaBen[0]->p_apellido;
                    $this->titularGrupoFamiliar[$key + 1]['s_apellido'] = $personaBen[0]->s_apellido;
                    $this->titularGrupoFamiliar[$key + 1]['fecha_nacimiento'] = $personaBen[0]->fecha_nacimiento;
                    $this->titularGrupoFamiliar[$key + 1]['id_tipo_asegurado'] = $personaBen[0]->id_tipo_asegurado;
                    $this->titularGrupoFamiliar[$key + 1]['tipo_asegurado'] = $personaBen[0]->tipo_asegurado;
                    $this->titularGrupoFamiliar[$key + 1]['id_estado_civil'] = $personaBen[0]->id_estado_civil;
                    $this->titularGrupoFamiliar[$key + 1]['estado_civil'] = $personaBen[0]->nombre;
                    $this->titularGrupoFamiliar[$key + 1]['id_expedido'] = $personaBen[0]->id_dept_exp;
                    $this->titularGrupoFamiliar[$key + 1]['expedido'] = $personaBen[0]->sigla;
                    $this->titularGrupoFamiliar[$key + 1]['sexo'] = $personaBen[0]->sexo;
                }
                return $this->titularGrupoFamiliar;
            } else {*/
            return $this->preafiliadoTitularGrupoFamiliar;
            //}

            //$this->titularGrupoFamiliar[]
        } else {
            return [];
        }
    }

    public function encontrarPreafiliadoBeneficiario($idPersona)
    {
        $preafiliado = Preafiliacion::with(['institucionPreafiliacion.codigosInstitucion', 'personaPreafiliacion'])
            ->where('estado', true)
            ->where('id_persona', $idPersona)
            ->orderBy('id', 'desc')
            ->whereHas('personaPreafiliacion', function ($comprobarPersona) {
                $comprobarPersona->whereNot('id_tipo_asegurado', 0)
                    ->where('afiliado', true);
            })
            ->first();

        if ($preafiliado) {
            $titular = Titular::with(['titularPersona'])
                ->where('id', $preafiliado->id_titular)
                ->whereIn('estado_vigencia', ['VIGENTE', 'CESANTE'])
                ->where('estado', true)
                ->whereHas('titularPersona', function ($comprobarPersona) {
                    $comprobarPersona->whereNot('id_tipo_asegurado', 0)
                        ->where('afiliado', true);
                })
                ->first();
            if ($titular) {
                $personaTitular = $this->busquedaPoblacion($titular->id_persona);
                $this->beneficiarioGrupoFamiliar[0]['id'] = $personaTitular[0]->id;
                $this->beneficiarioGrupoFamiliar[0]['ci'] = $personaTitular[0]->ci;
                $this->beneficiarioGrupoFamiliar[0]['complemento'] = $personaTitular[0]->complemento;
                $this->beneficiarioGrupoFamiliar[0]['matricula_seguro'] = $personaTitular[0]->matricula_seguro;
                $this->beneficiarioGrupoFamiliar[0]['nombres'] = $personaTitular[0]->nombres;
                $this->beneficiarioGrupoFamiliar[0]['p_apellido'] = $personaTitular[0]->p_apellido;
                $this->beneficiarioGrupoFamiliar[0]['s_apellido'] = $personaTitular[0]->s_apellido;
                $this->beneficiarioGrupoFamiliar[0]['fecha_nacimiento'] = $personaTitular[0]->fecha_nacimiento;
                $this->beneficiarioGrupoFamiliar[0]['id_tipo_asegurado'] = $personaTitular[0]->id_tipo_asegurado;
                $this->beneficiarioGrupoFamiliar[0]['tipo_asegurado'] = $personaTitular[0]->tipo_asegurado;
                $this->beneficiarioGrupoFamiliar[0]['id_estado_civil'] = $personaTitular[0]->id_estado_civil;
                $this->beneficiarioGrupoFamiliar[0]['estado_civil'] = $personaTitular[0]->nombre;
                $this->beneficiarioGrupoFamiliar[0]['id_expedido'] = $personaTitular[0]->id_dept_exp;
                $this->beneficiarioGrupoFamiliar[0]['expedido'] = $personaTitular[0]->sigla;
                $this->beneficiarioGrupoFamiliar[0]['sexo'] = $personaTitular[0]->sexo;

                $grupoFam = Beneficiario::with(['beneficiarioPersona'])
                    ->where('id_titular', $titular->id)
                    ->whereIn('vigencia', ['VIGENTE', 'CESANTE'])
                    ->where('estado', true)
                    ->whereHas('beneficiarioPersona', function ($comprobarPersona) {
                        $comprobarPersona->whereNot('id_tipo_asegurado', 0)
                            ->where('afiliado', true);
                    })
                    ->get();

                $grupoFamPreafiliados = Preafiliacion::with(['institucionPreafiliacion.codigosInstitucion', 'personaPreafiliacion'])
                    ->where('estado', true)
                    ->where('id_titular', $titular->id)
                    ->whereHas('personaPreafiliacion', function ($comprobarPersona) {
                        $comprobarPersona->whereNot('id_tipo_asegurado', 0)
                            ->where('afiliado', true);
                    })
                    ->get();

                if (isset($grupoFam->toArray()[0])) {
                    //dd('significa que tiene grupo');

                    foreach ($grupoFam as $key => $fam) {
                        $personaBen = $this->busquedaPoblacion($fam->id_persona);
                        $this->beneficiarioGrupoFamiliar[$key + 1]['id'] = $personaBen[0]->id;
                        $this->beneficiarioGrupoFamiliar[$key + 1]['ci'] = $personaBen[0]->ci;
                        $this->beneficiarioGrupoFamiliar[$key + 1]['complemento'] = $personaBen[0]->complemento;
                        $this->beneficiarioGrupoFamiliar[$key + 1]['matricula_seguro'] = $personaBen[0]->matricula_seguro;
                        $this->beneficiarioGrupoFamiliar[$key + 1]['nombres'] = $personaBen[0]->nombres;
                        $this->beneficiarioGrupoFamiliar[$key + 1]['p_apellido'] = $personaBen[0]->p_apellido;
                        $this->beneficiarioGrupoFamiliar[$key + 1]['s_apellido'] = $personaBen[0]->s_apellido;
                        $this->beneficiarioGrupoFamiliar[$key + 1]['fecha_nacimiento'] = $personaBen[0]->fecha_nacimiento;
                        $this->beneficiarioGrupoFamiliar[$key + 1]['id_tipo_asegurado'] = $personaBen[0]->id_tipo_asegurado;
                        $this->beneficiarioGrupoFamiliar[$key + 1]['tipo_asegurado'] = $personaBen[0]->tipo_asegurado;
                        $this->beneficiarioGrupoFamiliar[$key + 1]['id_estado_civil'] = $personaBen[0]->id_estado_civil;
                        $this->beneficiarioGrupoFamiliar[$key + 1]['estado_civil'] = $personaBen[0]->nombre;
                        $this->beneficiarioGrupoFamiliar[$key + 1]['id_expedido'] = $personaBen[0]->id_dept_exp;
                        $this->beneficiarioGrupoFamiliar[$key + 1]['expedido'] = $personaBen[0]->sigla;
                        $this->beneficiarioGrupoFamiliar[$key + 1]['sexo'] = $personaBen[0]->sexo;
                    }

                    $ultimoValorArrayAnterior = count($grupoFam) + 1;
                    foreach ($grupoFamPreafiliados as $keycito => $preaf) {
                        $personaPreaf = $this->busquedaPoblacion($preaf->id_persona);
                        $this->beneficiarioGrupoFamiliar[$ultimoValorArrayAnterior + $keycito]['id'] = $personaPreaf[0]->id;
                        $this->beneficiarioGrupoFamiliar[$ultimoValorArrayAnterior + $keycito]['ci'] = $personaPreaf[0]->ci;
                        $this->beneficiarioGrupoFamiliar[$ultimoValorArrayAnterior + $keycito]['complemento'] = $personaPreaf[0]->complemento;
                        $this->beneficiarioGrupoFamiliar[$ultimoValorArrayAnterior + $keycito]['matricula_seguro'] = $personaPreaf[0]->matricula_seguro;
                        $this->beneficiarioGrupoFamiliar[$ultimoValorArrayAnterior + $keycito]['nombres'] = $personaPreaf[0]->nombres;
                        $this->beneficiarioGrupoFamiliar[$ultimoValorArrayAnterior + $keycito]['p_apellido'] = $personaPreaf[0]->p_apellido;
                        $this->beneficiarioGrupoFamiliar[$ultimoValorArrayAnterior + $keycito]['s_apellido'] = $personaPreaf[0]->s_apellido;
                        $this->beneficiarioGrupoFamiliar[$ultimoValorArrayAnterior + $keycito]['fecha_nacimiento'] = $personaPreaf[0]->fecha_nacimiento;
                        $this->beneficiarioGrupoFamiliar[$ultimoValorArrayAnterior + $keycito]['id_tipo_asegurado'] = $personaPreaf[0]->id_tipo_asegurado;
                        $this->beneficiarioGrupoFamiliar[$ultimoValorArrayAnterior + $keycito]['tipo_asegurado'] = $personaPreaf[0]->tipo_asegurado;
                        $this->beneficiarioGrupoFamiliar[$ultimoValorArrayAnterior + $keycito]['id_estado_civil'] = $personaPreaf[0]->id_estado_civil;
                        $this->beneficiarioGrupoFamiliar[$ultimoValorArrayAnterior + $keycito]['estado_civil'] = $personaPreaf[0]->nombre;
                        $this->beneficiarioGrupoFamiliar[$ultimoValorArrayAnterior + $keycito]['id_expedido'] = $personaPreaf[0]->id_dept_exp;
                        $this->beneficiarioGrupoFamiliar[$ultimoValorArrayAnterior + $keycito]['expedido'] = $personaPreaf[0]->sigla;
                        $this->beneficiarioGrupoFamiliar[$ultimoValorArrayAnterior + $keycito]['sexo'] = $personaPreaf[0]->sexo;
                    }
                    return $this->beneficiarioGrupoFamiliar;
                } else {
                    return $this->beneficiarioGrupoFamiliar;
                }
            } else {
                return [];
            }
        } else {
            return [];
        }
    }

    public function busquedaPoblacion($busqueda)
    {
        $personas_datos = Persona::join('administracion.tipo_asegurado as ta', 'ta.id', '=', 'persona.id_tipo_asegurado')
            ->leftJoin('administracion.estado_civil as ec', 'ec.id', '=', 'persona.id_estado_civil')
            ->leftJoin('administracion.departamento as dep_exp', 'dep_exp.id', '=', 'persona.id_dept_exp')
            ->select(
                'persona.id',
                'persona.ci',
                'persona.complemento',
                'persona.nombres',
                'persona.p_apellido',
                'persona.s_apellido',
                'persona.sexo',
                'persona.fecha_nacimiento',
                'persona.matricula_seguro',
                'persona.id_tipo_asegurado',
                'persona.id_estado_civil',
                'persona.id_dept_exp',
                'ta.tipo_asegurado',
                'ec.nombre',
                'dep_exp.sigla'
            )
            ->where('persona.id', $busqueda)
            ->where('persona.afiliado', true)
            ->get();

        return $personas_datos;
    }
}
