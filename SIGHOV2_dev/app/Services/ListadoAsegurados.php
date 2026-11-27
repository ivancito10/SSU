<?php

namespace App\Services;

use App\Models\Administracion\Persona;
use App\Models\Administracion\TipoAsegurado;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\BeneficiarioInstitucion;
use App\Models\Afiliacion\CodigoInstitucionSsu;
use App\Models\Afiliacion\Estudiante;
use App\Models\Afiliacion\HCarnet;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Aportes\Institucion;
use App\Models\Vigencia\AutorizacionInterior;
use App\Models\Vigencia\Convenio;
use App\Models\Vigencia\Preafiliacion;
use App\Models\Vigencia\SeguroInterior;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ListadoAsegurados
{
    public function listadoAfiliados()
    {
        $personas = Persona::where('afiliado', true)->whereNot('id_tipo_asegurado', 0)->get();
        $listado = [];
        foreach ($personas as $key => $persona) {
            $listado[$key]['ci'] = $persona->ci;
            $listado[$key]['complemento'] = $persona->complemento;
            $listado[$key]['matricula_seguro'] = $persona->matricula_seguro;
            $listado[$key]['nombres'] = $persona->nombres;
            $listado[$key]['p_apellido'] = $persona->p_apellido;
            $listado[$key]['s_apellido'] = $persona->s_apellido;
            $listado[$key]['sexo'] = $persona->sexo;
            $listado[$key]['fecha_nacimiento'] = $persona->fecha_nacimiento;
            $tipo_asegurado = TipoAsegurado::find($persona->id_tipo_asegurado);
            $listado[$key]['tipo_asegurado'] = $tipo_asegurado->tipo_asegurado;
            if ($persona->id_tipo_asegurado == 1) {
                $datosTitular = Titular::where('id_persona', $persona->id)
                    ->where('estado_vigencia', 'VIGENTE')
                    ->orderBy('id', 'desc')
                    ->first();

                $institucionTitular = TitularInstitucion::where('id_titular', $datosTitular->id)
                    ->where('estado', true)
                    ->orderBy('id', 'asc')
                    ->first();
                if ($institucionTitular) {
                    $datosInstitucion = Institucion::find($institucionTitular->id_institucion);
                    $listado[$key]['nombre_institucion'] = $datosInstitucion->nombre;
                    $codigoInstitucionTitular = CodigoInstitucionSsu::where('id_institucion', $institucionTitular->id_institucion)->first();
                    $listado[$key]['codigo_institucional'] = ($codigoInstitucionTitular != null) ? $codigoInstitucionTitular->codigo.$codigoInstitucionTitular->tipo_titular : null;
                    $listado[$key]['institucion_beneficiario_remuneracion'] = null;
                } else {
                    $listado[$key]['nombre_institucion'] = 'AFILIADO VOLUNTARIO';
                    $listado[$key]['codigo_institucional'] = null;
                    $listado[$key]['institucion_beneficiario_remuneracion'] = null;
                }
            } elseif (
                $persona->id_tipo_asegurado == 2 || $persona->id_tipo_asegurado == 3 || $persona->id_tipo_asegurado == 4 || $persona->id_tipo_asegurado == 5 || $persona->id_tipo_asegurado == 6 || $persona->id_tipo_asegurado == 7
                || $persona->id_tipo_asegurado == 12 || $persona->id_tipo_asegurado == 13
            ) {
                $datosBeneficiario = Beneficiario::where('id_persona', $persona->id)
                    ->where('vigencia', 'VIGENTE')
                    ->orderBy('id', 'desc')
                    ->first();
                $institucionTitular = TitularInstitucion::where('id_titular', $datosBeneficiario->id_titular)
                    ->where('estado', true)
                    ->orderBy('id', 'asc')
                    ->first();
                if ($institucionTitular) {
                    $datosInstitucion = Institucion::find($institucionTitular->id_institucion);
                    $listado[$key]['nombre_institucion'] = $datosInstitucion->nombre;
                    $codigoInstitucionTitular = CodigoInstitucionSsu::where('id_institucion', $institucionTitular->id_institucion)->first();
                    $listado[$key]['codigo_institucional'] = ($codigoInstitucionTitular != null) ? $codigoInstitucionTitular->codigo.$codigoInstitucionTitular->tipo_beneficiario : null;
                    $esRemuneracion = BeneficiarioInstitucion::where('id_beneficiario', $datosBeneficiario->id)
                        ->where('id_titular', $datosBeneficiario->id_titular)
                        ->where('estado', true)
                        ->orderBy('id', 'desc')
                        ->first();
                    if ($esRemuneracion) {
                        $institucionRemuneracion = Institucion::find($esRemuneracion->id_institucion);
                        $listado[$key]['institucion_beneficiario_remuneracion'] = $institucionRemuneracion->nombre;
                    } else {
                        $listado[$key]['institucion_beneficiario_remuneracion'] = null;
                    }
                } else {
                    $listado[$key]['nombre_institucion'] = 'AFILIADO VOLUNTARIO';
                    $listado[$key]['codigo_institucional'] = null;
                    $listado[$key]['institucion_beneficiario_remuneracion'] = null;
                }
            } elseif ($persona->id_tipo_asegurado == 8) {
                $listado[$key]['nombre_institucion'] = 'CONVENIO EMERGENCIA';
                $listado[$key]['codigo_institucional'] = null;
                $listado[$key]['institucion_beneficiario_remuneracion'] = null;
            } elseif ($persona->id_tipo_asegurado == 9 || $persona->id_tipo_asegurado == 10) {
                $convenioInterior = AutorizacionInterior::where('id_persona_interior', $persona->id)
                    ->where('estado', true)
                    ->orderBy('id', 'desc')
                    ->first();
                $seguro_interior = SeguroInterior::find($convenioInterior->id_seguro_interior);
                $listado[$key]['nombre_institucion'] = $seguro_interior->nombre_seguro;
                $listado[$key]['codigo_institucional'] = null;
                $listado[$key]['institucion_beneficiario_remuneracion'] = null;
            } elseif ($persona->id_tipo_asegurado == 11) {
                $listado[$key]['nombre_institucion'] = 'APORTE UMSA ESTUDIANTE';
                $listado[$key]['codigo_institucional'] = null;
                $listado[$key]['institucion_beneficiario_remuneracion'] = null;
            } elseif ($persona->id_tipo_asegurado == 14) {
                $datosPreafiliado = Preafiliacion::with(['institucionPreafiliacion.codigosInstitucion'])
                    ->where('estado', true)
                    ->where('id_persona', $persona->id)
                    ->orderBy('id', 'desc')
                    ->first();

                $listado[$key]['nombre_institucion'] = $datosPreafiliado->institucionPreafiliacion ? $datosPreafiliado->institucionPreafiliacion->nombre : 'AFILIADO VOLUNTARIO';
                $listado[$key]['codigo_institucional'] = $datosPreafiliado->institucionPreafiliacion->codigosInstitucion ?
                    $datosPreafiliado->institucionPreafiliacion->codigosInstitucion->codigo.$datosPreafiliado->institucionPreafiliacion->codigosInstitucion->tipo_titular : null;
                $listado[$key]['institucion_beneficiario_remuneracion'] = null;
            } elseif ($persona->id_tipo_asegurado == 15) {
                $datosPreafiliado = Preafiliacion::with(['institucionPreafiliacion.codigosInstitucion'])
                    ->where('estado', true)
                    ->where('id_persona', $persona->id)
                    ->orderBy('id', 'desc')
                    ->first();

                $listado[$key]['nombre_institucion'] = $datosPreafiliado->institucionPreafiliacion ? $datosPreafiliado->institucionPreafiliacion->nombre : 'AFILIADO VOLUNTARIO';
                $listado[$key]['codigo_institucional'] = $datosPreafiliado->institucionPreafiliacion->codigosInstitucion ?
                    $datosPreafiliado->institucionPreafiliacion->codigosInstitucion->codigo.$datosPreafiliado->institucionPreafiliacion->codigosInstitucion->tipo_beneficiario : null;
                $listado[$key]['institucion_beneficiario_remuneracion'] = null;
            }
        }

        //dd($listado);
        return $listado;
    }

    public $datosObtenidos = [];

    public function busquedaAfiliado($carnet, $complemento, $matricula)
    {
        if ($carnet == '') {
            $carnet = null;
        }
        if ($complemento == '') {
            $complemento = null;
        }
        if ($matricula == '') {
            $matricula = null;
        }

        if ($matricula == null && $complemento == null) {
            $personas = Persona::where('afiliado', true)
                ->whereNot('id_tipo_asegurado', 0)
                ->where('ci', 'like', "$carnet%")
                ->get();
            //dd($personas);
            foreach ($personas as $key => $persona) {
                $this->datosObtenidos[$key]['id_persona'] = $persona->id;
                $this->datosObtenidos[$key]['ci'] = $persona->ci;
                $this->datosObtenidos[$key]['complemento'] = $persona->complemento;
                $this->datosObtenidos[$key]['matricula_seguro'] = $persona->matricula_seguro;
                $this->datosObtenidos[$key]['nombres'] = $persona->nombres;
                $this->datosObtenidos[$key]['p_apellido'] = $persona->p_apellido;
                $this->datosObtenidos[$key]['s_apellido'] = $persona->s_apellido;
                $this->datosObtenidos[$key]['sexo'] = $persona->sexo;
                $this->datosObtenidos[$key]['fecha_nacimiento'] = $persona->fecha_nacimiento;
                $tipo_asegurado = TipoAsegurado::find($persona->id_tipo_asegurado);
                $this->datosObtenidos[$key]['tipo_asegurado'] = $tipo_asegurado->tipo_asegurado;
                if ($persona->id_tipo_asegurado == 1) {
                    $datosTitular = Titular::where('id_persona', $persona->id)
                        ->where('estado_vigencia', 'VIGENTE')
                        ->orderBy('id', 'desc')
                        ->first();

                    $institucionTitular = TitularInstitucion::where('id_titular', $datosTitular->id)
                        ->where('tipo_institucion', 'PATRONAL')
                        ->where('estado', true)
                        ->first();
                    if ($institucionTitular) {
                        $datosInstitucion = Institucion::find($institucionTitular->id_institucion);
                        $this->datosObtenidos[$key]['nombre_institucion'] = $datosInstitucion->nombre;
                        $codigoInstitucionTitular = CodigoInstitucionSsu::where('id_institucion', $institucionTitular->id_institucion)->first();
                        $this->datosObtenidos[$key]['codigo_institucional'] = ($codigoInstitucionTitular != null) ? $codigoInstitucionTitular->codigo.$codigoInstitucionTitular->tipo_titular : null;
                        $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                    } else {
                        $this->datosObtenidos[$key]['nombre_institucion'] = 'AFILIADO VOLUNTARIO';
                        $this->datosObtenidos[$key]['codigo_institucional'] = null;
                        $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                    }
                } elseif (
                    $persona->id_tipo_asegurado == 2 || $persona->id_tipo_asegurado == 3 || $persona->id_tipo_asegurado == 4 || $persona->id_tipo_asegurado == 5 || $persona->id_tipo_asegurado == 6 || $persona->id_tipo_asegurado == 7
                    || $persona->id_tipo_asegurado == 12 || $persona->id_tipo_asegurado == 13
                ) {
                    $datosBeneficiario = Beneficiario::where('id_persona', $persona->id)
                        ->where('vigencia', 'VIGENTE')
                        ->orderBy('id', 'desc')
                        ->first();
                    $institucionTitular = TitularInstitucion::where('id_titular', $datosBeneficiario->id_titular)
                        ->where('tipo_institucion', 'PATRONAL')
                        ->where('estado', true)
                        ->first();
                    if ($institucionTitular) {
                        $datosInstitucion = Institucion::find($institucionTitular->id_institucion);
                        $this->datosObtenidos[$key]['nombre_institucion'] = $datosInstitucion->nombre;
                        $codigoInstitucionTitular = CodigoInstitucionSsu::where('id_institucion', $institucionTitular->id_institucion)->first();
                        $this->datosObtenidos[$key]['codigo_institucional'] = ($codigoInstitucionTitular != null) ? $codigoInstitucionTitular->codigo.$codigoInstitucionTitular->tipo_beneficiario : null;
                        $esRemuneracion = BeneficiarioInstitucion::where('id_beneficiario', $datosBeneficiario->id)
                            ->where('id_titular', $datosBeneficiario->id_titular)
                            ->where('estado', true)
                            ->orderBy('id', 'desc')
                            ->first();
                        if ($esRemuneracion) {
                            $institucionRemuneracion = Institucion::find($esRemuneracion->id_institucion);
                            $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = $institucionRemuneracion->nombre;
                        } else {
                            $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                        }
                    } else {
                        $this->datosObtenidos[$key]['nombre_institucion'] = 'AFILIADO VOLUNTARIO';
                        $this->datosObtenidos[$key]['codigo_institucional'] = null;
                        $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                    }
                } elseif ($persona->id_tipo_asegurado == 8) {
                    $this->datosObtenidos[$key]['nombre_institucion'] = 'CONVENIO EMERGENCIA';
                    $this->datosObtenidos[$key]['codigo_institucional'] = null;
                    $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                } elseif ($persona->id_tipo_asegurado == 9 || $persona->id_tipo_asegurado == 10) {
                    //dd($persona);
                    $convenioInterior = AutorizacionInterior::where('id_persona_interior', $persona->id)
                        ->where('estado', true)
                        ->orderBy('id', 'desc')
                        ->first();
                    $seguro_interior = SeguroInterior::find($convenioInterior->id_seguro_interior);
                    $this->datosObtenidos[$key]['nombre_institucion'] = $seguro_interior->nombre_seguro;
                    $this->datosObtenidos[$key]['codigo_institucional'] = null;
                    $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                } elseif ($persona->id_tipo_asegurado == 11) {
                    $this->datosObtenidos[$key]['nombre_institucion'] = 'APORTE UMSA ESTUDIANTE';
                    $this->datosObtenidos[$key]['codigo_institucional'] = null;
                    $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                } elseif ($persona->id_tipo_asegurado == 14) {
                    $datosPreafiliado = Preafiliacion::with(['institucionPreafiliacion.codigosInstitucion'])
                        ->where('estado', true)
                        ->where('id_persona', $persona->id)
                        ->orderBy('id', 'desc')
                        ->first();

                    $listado[$key]['nombre_institucion'] = $datosPreafiliado->institucionPreafiliacion ? $datosPreafiliado->institucionPreafiliacion->nombre : 'AFILIADO VOLUNTARIO';
                    $listado[$key]['codigo_institucional'] = $datosPreafiliado->institucionPreafiliacion->codigosInstitucion ?
                        $datosPreafiliado->institucionPreafiliacion->codigosInstitucion->codigo.$datosPreafiliado->institucionPreafiliacion->codigosInstitucion->tipo_titular : null;
                    $listado[$key]['institucion_beneficiario_remuneracion'] = null;
                } elseif ($persona->id_tipo_asegurado == 15) {
                    $datosPreafiliado = Preafiliacion::with(['institucionPreafiliacion.codigosInstitucion'])
                        ->where('estado', true)
                        ->where('id_persona', $persona->id)
                        ->orderBy('id', 'desc')
                        ->first();

                    $listado[$key]['nombre_institucion'] = $datosPreafiliado->institucionPreafiliacion ? $datosPreafiliado->institucionPreafiliacion->nombre : 'AFILIADO VOLUNTARIO';
                    $listado[$key]['codigo_institucional'] = $datosPreafiliado->institucionPreafiliacion->codigosInstitucion ?
                        $datosPreafiliado->institucionPreafiliacion->codigosInstitucion->codigo.$datosPreafiliado->institucionPreafiliacion->codigosInstitucion->tipo_beneficiario : null;
                    $listado[$key]['institucion_beneficiario_remuneracion'] = null;
                }
            }

            return $this->datosObtenidos;
        } elseif ($matricula == null && $complemento != null) {
            $personas = Persona::where('afiliado', true)
                ->whereNot('id_tipo_asegurado', 0)
                ->where('ci', 'like', "$carnet%")
                ->where('complemento', 'like', "$complemento%")
                ->orWhere('matricula_seguro', 'like', "$carnet%")
                ->get();
            //dd($personas);
            foreach ($personas as $key => $persona) {
                $this->datosObtenidos[$key]['id_persona'] = $persona->id;
                $this->datosObtenidos[$key]['ci'] = $persona->ci;
                $this->datosObtenidos[$key]['complemento'] = $persona->complemento;
                $this->datosObtenidos[$key]['matricula_seguro'] = $persona->matricula_seguro;
                $this->datosObtenidos[$key]['nombres'] = $persona->nombres;
                $this->datosObtenidos[$key]['p_apellido'] = $persona->p_apellido;
                $this->datosObtenidos[$key]['s_apellido'] = $persona->s_apellido;
                $this->datosObtenidos[$key]['sexo'] = $persona->sexo;
                $this->datosObtenidos[$key]['fecha_nacimiento'] = $persona->fecha_nacimiento;
                $tipo_asegurado = TipoAsegurado::find($persona->id_tipo_asegurado);
                $this->datosObtenidos[$key]['tipo_asegurado'] = $tipo_asegurado->tipo_asegurado;
                if ($persona->id_tipo_asegurado == 1) {
                    $datosTitular = Titular::where('id_persona', $persona->id)
                        ->where('estado_vigencia', 'VIGENTE')
                        ->orderBy('id', 'desc')
                        ->first();

                    $institucionTitular = TitularInstitucion::where('id_titular', $datosTitular->id)
                        ->where('tipo_institucion', 'PATRONAL')
                        ->where('estado', true)
                        ->first();
                    if ($institucionTitular) {
                        $datosInstitucion = Institucion::find($institucionTitular->id_institucion);
                        $this->datosObtenidos[$key]['nombre_institucion'] = $datosInstitucion->nombre;
                        $codigoInstitucionTitular = CodigoInstitucionSsu::where('id_institucion', $institucionTitular->id_institucion)->first();
                        $this->datosObtenidos[$key]['codigo_institucional'] = ($codigoInstitucionTitular != null) ? $codigoInstitucionTitular->codigo.$codigoInstitucionTitular->tipo_titular : null;
                        $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                    } else {
                        $this->datosObtenidos[$key]['nombre_institucion'] = 'AFILIADO VOLUNTARIO';
                        $this->datosObtenidos[$key]['codigo_institucional'] = null;
                        $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                    }
                } elseif (
                    $persona->id_tipo_asegurado == 2 || $persona->id_tipo_asegurado == 3 || $persona->id_tipo_asegurado == 4 || $persona->id_tipo_asegurado == 5 || $persona->id_tipo_asegurado == 6 || $persona->id_tipo_asegurado == 7
                    || $persona->id_tipo_asegurado == 12 || $persona->id_tipo_asegurado == 13
                ) {
                    $datosBeneficiario = Beneficiario::where('id_persona', $persona->id)
                        ->where('vigencia', 'VIGENTE')
                        ->orderBy('id', 'desc')
                        ->first();
                    $institucionTitular = TitularInstitucion::where('id_titular', $datosBeneficiario->id_titular)
                        ->where('tipo_institucion', 'PATRONAL')
                        ->where('estado', true)
                        ->first();
                    if ($institucionTitular) {
                        $datosInstitucion = Institucion::find($institucionTitular->id_institucion);
                        $this->datosObtenidos[$key]['nombre_institucion'] = $datosInstitucion->nombre;
                        $codigoInstitucionTitular = CodigoInstitucionSsu::where('id_institucion', $institucionTitular->id_institucion)->first();
                        $this->datosObtenidos[$key]['codigo_institucional'] = ($codigoInstitucionTitular != null) ? $codigoInstitucionTitular->codigo.$codigoInstitucionTitular->tipo_beneficiario : null;
                        $esRemuneracion = BeneficiarioInstitucion::where('id_beneficiario', $datosBeneficiario->id)
                            ->where('id_titular', $datosBeneficiario->id_titular)
                            ->where('estado', true)
                            ->orderBy('id', 'desc')
                            ->first();
                        if ($esRemuneracion) {
                            $institucionRemuneracion = Institucion::find($esRemuneracion->id_institucion);
                            $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = $institucionRemuneracion->nombre;
                        } else {
                            $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                        }
                    } else {
                        $this->datosObtenidos[$key]['nombre_institucion'] = 'AFILIADO VOLUNTARIO';
                        $this->datosObtenidos[$key]['codigo_institucional'] = null;
                        $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                    }
                } elseif ($persona->id_tipo_asegurado == 8) {
                    $this->datosObtenidos[$key]['nombre_institucion'] = 'CONVENIO EMERGENCIA';
                    $this->datosObtenidos[$key]['codigo_institucional'] = null;
                    $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                } elseif ($persona->id_tipo_asegurado == 9 || $persona->id_tipo_asegurado == 10) {
                    $convenioInterior = AutorizacionInterior::where('id_persona_interior', $persona->id)
                        ->where('estado', true)
                        ->orderBy('id', 'desc')
                        ->first();
                    $seguro_interior = SeguroInterior::find($convenioInterior->id_seguro_interior);
                    $this->datosObtenidos[$key]['nombre_institucion'] = $seguro_interior->nombre_seguro;
                    $this->datosObtenidos[$key]['codigo_institucional'] = null;
                    $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                } elseif ($persona->id_tipo_asegurado == 11) {
                    $this->datosObtenidos[$key]['nombre_institucion'] = 'APORTE UMSA ESTUDIANTE';
                    $this->datosObtenidos[$key]['codigo_institucional'] = null;
                    $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                } elseif ($persona->id_tipo_asegurado == 14) {
                    $datosPreafiliado = Preafiliacion::with(['institucionPreafiliacion.codigosInstitucion'])
                        ->where('estado', true)
                        ->where('id_persona', $persona->id)
                        ->orderBy('id', 'desc')
                        ->first();

                    $listado[$key]['nombre_institucion'] = $datosPreafiliado->institucionPreafiliacion ? $datosPreafiliado->institucionPreafiliacion->nombre : 'AFILIADO VOLUNTARIO';
                    $listado[$key]['codigo_institucional'] = $datosPreafiliado->institucionPreafiliacion->codigosInstitucion ?
                        $datosPreafiliado->institucionPreafiliacion->codigosInstitucion->codigo.$datosPreafiliado->institucionPreafiliacion->codigosInstitucion->tipo_titular : null;
                    $listado[$key]['institucion_beneficiario_remuneracion'] = null;
                } elseif ($persona->id_tipo_asegurado == 15) {
                    $datosPreafiliado = Preafiliacion::with(['institucionPreafiliacion.codigosInstitucion'])
                        ->where('estado', true)
                        ->where('id_persona', $persona->id)
                        ->orderBy('id', 'desc')
                        ->first();

                    $listado[$key]['nombre_institucion'] = $datosPreafiliado->institucionPreafiliacion ? $datosPreafiliado->institucionPreafiliacion->nombre : 'AFILIADO VOLUNTARIO';
                    $listado[$key]['codigo_institucional'] = $datosPreafiliado->institucionPreafiliacion->codigosInstitucion ?
                        $datosPreafiliado->institucionPreafiliacion->codigosInstitucion->codigo.$datosPreafiliado->institucionPreafiliacion->codigosInstitucion->tipo_beneficiario : null;
                    $listado[$key]['institucion_beneficiario_remuneracion'] = null;
                }
            }

            return $this->datosObtenidos;
        } elseif ($carnet == null && $complemento == null) {
            $personas = Persona::where('afiliado', true)
                ->whereNot('id_tipo_asegurado', 0)
                ->where('matricula_seguro', 'like', "$matricula%")
                ->get();
            //dd($personas);
            foreach ($personas as $key => $persona) {
                $this->datosObtenidos[$key]['id_persona'] = $persona->id;
                $this->datosObtenidos[$key]['ci'] = $persona->ci;
                $this->datosObtenidos[$key]['complemento'] = $persona->complemento;
                $this->datosObtenidos[$key]['matricula_seguro'] = $persona->matricula_seguro;
                $this->datosObtenidos[$key]['nombres'] = $persona->nombres;
                $this->datosObtenidos[$key]['p_apellido'] = $persona->p_apellido;
                $this->datosObtenidos[$key]['s_apellido'] = $persona->s_apellido;
                $this->datosObtenidos[$key]['sexo'] = $persona->sexo;
                $this->datosObtenidos[$key]['fecha_nacimiento'] = $persona->fecha_nacimiento;
                $tipo_asegurado = TipoAsegurado::find($persona->id_tipo_asegurado);
                $this->datosObtenidos[$key]['tipo_asegurado'] = $tipo_asegurado->tipo_asegurado;
                if ($persona->id_tipo_asegurado == 1) {
                    $datosTitular = Titular::where('id_persona', $persona->id)
                        ->where('estado_vigencia', 'VIGENTE')
                        ->orderBy('id', 'desc')
                        ->first();

                    $institucionTitular = TitularInstitucion::where('id_titular', $datosTitular->id)
                        ->where('tipo_institucion', 'PATRONAL')
                        ->where('estado', true)
                        ->first();
                    if ($institucionTitular) {
                        $datosInstitucion = Institucion::find($institucionTitular->id_institucion);
                        $this->datosObtenidos[$key]['nombre_institucion'] = $datosInstitucion->nombre;
                        $codigoInstitucionTitular = CodigoInstitucionSsu::where('id_institucion', $institucionTitular->id_institucion)->first();
                        $this->datosObtenidos[$key]['codigo_institucional'] = ($codigoInstitucionTitular != null) ? $codigoInstitucionTitular->codigo.$codigoInstitucionTitular->tipo_titular : null;
                        $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                    } else {
                        $this->datosObtenidos[$key]['nombre_institucion'] = 'AFILIADO VOLUNTARIO';
                        $this->datosObtenidos[$key]['codigo_institucional'] = null;
                        $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                    }
                } elseif (
                    $persona->id_tipo_asegurado == 2 || $persona->id_tipo_asegurado == 3 || $persona->id_tipo_asegurado == 4 || $persona->id_tipo_asegurado == 5 || $persona->id_tipo_asegurado == 6 || $persona->id_tipo_asegurado == 7
                    || $persona->id_tipo_asegurado == 12 || $persona->id_tipo_asegurado == 13
                ) {
                    $datosBeneficiario = Beneficiario::where('id_persona', $persona->id)
                        ->where('vigencia', 'VIGENTE')
                        ->orderBy('id', 'desc')
                        ->first();
                    $institucionTitular = TitularInstitucion::where('id_titular', $datosBeneficiario->id_titular)
                        ->where('tipo_institucion', 'PATRONAL')
                        ->where('estado', true)
                        ->first();
                    if ($institucionTitular) {
                        $datosInstitucion = Institucion::find($institucionTitular->id_institucion);
                        $this->datosObtenidos[$key]['nombre_institucion'] = $datosInstitucion->nombre;
                        $codigoInstitucionTitular = CodigoInstitucionSsu::where('id_institucion', $institucionTitular->id_institucion)->first();
                        $this->datosObtenidos[$key]['codigo_institucional'] = ($codigoInstitucionTitular != null) ? $codigoInstitucionTitular->codigo.$codigoInstitucionTitular->tipo_beneficiario : null;
                        $esRemuneracion = BeneficiarioInstitucion::where('id_beneficiario', $datosBeneficiario->id)
                            ->where('id_titular', $datosBeneficiario->id_titular)
                            ->where('estado', true)
                            ->orderBy('id', 'desc')
                            ->first();
                        if ($esRemuneracion) {
                            $institucionRemuneracion = Institucion::find($esRemuneracion->id_institucion);
                            $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = $institucionRemuneracion->nombre;
                        } else {
                            $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                        }
                    } else {
                        $this->datosObtenidos[$key]['nombre_institucion'] = 'AFILIADO VOLUNTARIO';
                        $this->datosObtenidos[$key]['codigo_institucional'] = null;
                        $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                    }
                } elseif ($persona->id_tipo_asegurado == 8) {
                    $this->datosObtenidos[$key]['nombre_institucion'] = 'CONVENIO EMERGENCIA';
                    $this->datosObtenidos[$key]['codigo_institucional'] = null;
                    $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                } elseif ($persona->id_tipo_asegurado == 9 || $persona->id_tipo_asegurado == 10) {
                    $convenioInterior = AutorizacionInterior::where('id_persona_interior', $persona->id)
                        ->where('estado', true)
                        ->orderBy('id', 'desc')
                        ->first();
                    $seguro_interior = SeguroInterior::find($convenioInterior->id_seguro_interior);
                    $this->datosObtenidos[$key]['nombre_institucion'] = $seguro_interior->nombre_seguro;
                    $this->datosObtenidos[$key]['codigo_institucional'] = null;
                    $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                } elseif ($persona->id_tipo_asegurado == 11) {
                    $this->datosObtenidos[$key]['nombre_institucion'] = 'APORTE UMSA ESTUDIANTE';
                    $this->datosObtenidos[$key]['codigo_institucional'] = null;
                    $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                } elseif ($persona->id_tipo_asegurado == 14) {
                    $datosPreafiliado = Preafiliacion::with(['institucionPreafiliacion.codigosInstitucion'])
                        ->where('estado', true)
                        ->where('id_persona', $persona->id)
                        ->orderBy('id', 'desc')
                        ->first();

                    $listado[$key]['nombre_institucion'] = $datosPreafiliado->institucionPreafiliacion ? $datosPreafiliado->institucionPreafiliacion->nombre : 'AFILIADO VOLUNTARIO';
                    $listado[$key]['codigo_institucional'] = $datosPreafiliado->institucionPreafiliacion->codigosInstitucion ?
                        $datosPreafiliado->institucionPreafiliacion->codigosInstitucion->codigo.$datosPreafiliado->institucionPreafiliacion->codigosInstitucion->tipo_titular : null;
                    $listado[$key]['institucion_beneficiario_remuneracion'] = null;
                } elseif ($persona->id_tipo_asegurado == 15) {
                    $datosPreafiliado = Preafiliacion::with(['institucionPreafiliacion.codigosInstitucion'])
                        ->where('estado', true)
                        ->where('id_persona', $persona->id)
                        ->orderBy('id', 'desc')
                        ->first();

                    $listado[$key]['nombre_institucion'] = $datosPreafiliado->institucionPreafiliacion ? $datosPreafiliado->institucionPreafiliacion->nombre : 'AFILIADO VOLUNTARIO';
                    $listado[$key]['codigo_institucional'] = $datosPreafiliado->institucionPreafiliacion->codigosInstitucion ?
                        $datosPreafiliado->institucionPreafiliacion->codigosInstitucion->codigo.$datosPreafiliado->institucionPreafiliacion->codigosInstitucion->tipo_beneficiario : null;
                    $listado[$key]['institucion_beneficiario_remuneracion'] = null;
                }
            }

            return $this->datosObtenidos;
        } elseif ($carnet != null && $matricula != null && $complemento == null) {
            $personas = Persona::where('afiliado', true)
                ->whereNot('id_tipo_asegurado', 0)
                ->where('ci', 'like', "$carnet%")
                ->orWhere('ci', 'like', "$matricula%")
                ->orWhere('matricula_seguro', 'like', "$carnet%")
                ->orWhere('matricula_seguro', 'like', "$matricula%")
                ->get();
            //dd($personas);
            foreach ($personas as $key => $persona) {
                $this->datosObtenidos[$key]['id_persona'] = $persona->id;
                $this->datosObtenidos[$key]['ci'] = $persona->ci;
                $this->datosObtenidos[$key]['complemento'] = $persona->complemento;
                $this->datosObtenidos[$key]['matricula_seguro'] = $persona->matricula_seguro;
                $this->datosObtenidos[$key]['nombres'] = $persona->nombres;
                $this->datosObtenidos[$key]['p_apellido'] = $persona->p_apellido;
                $this->datosObtenidos[$key]['s_apellido'] = $persona->s_apellido;
                $this->datosObtenidos[$key]['sexo'] = $persona->sexo;
                $this->datosObtenidos[$key]['fecha_nacimiento'] = $persona->fecha_nacimiento;
                $tipo_asegurado = TipoAsegurado::find($persona->id_tipo_asegurado);
                $this->datosObtenidos[$key]['tipo_asegurado'] = $tipo_asegurado->tipo_asegurado;
                if ($persona->id_tipo_asegurado == 1) {
                    $datosTitular = Titular::where('id_persona', $persona->id)
                        ->where('estado_vigencia', 'VIGENTE')
                        ->orderBy('id', 'desc')
                        ->first();

                    $institucionTitular = TitularInstitucion::where('id_titular', $datosTitular->id)
                        ->where('tipo_institucion', 'PATRONAL')
                        ->where('estado', true)
                        ->first();
                    if ($institucionTitular) {
                        $datosInstitucion = Institucion::find($institucionTitular->id_institucion);
                        $this->datosObtenidos[$key]['nombre_institucion'] = $datosInstitucion->nombre;
                        $codigoInstitucionTitular = CodigoInstitucionSsu::where('id_institucion', $institucionTitular->id_institucion)->first();
                        $this->datosObtenidos[$key]['codigo_institucional'] = ($codigoInstitucionTitular != null) ? $codigoInstitucionTitular->codigo.$codigoInstitucionTitular->tipo_titular : null;
                        $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                    } else {
                        $this->datosObtenidos[$key]['nombre_institucion'] = 'AFILIADO VOLUNTARIO';
                        $this->datosObtenidos[$key]['codigo_institucional'] = null;
                        $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                    }
                } elseif (
                    $persona->id_tipo_asegurado == 2 || $persona->id_tipo_asegurado == 3 || $persona->id_tipo_asegurado == 4 || $persona->id_tipo_asegurado == 5 || $persona->id_tipo_asegurado == 6 || $persona->id_tipo_asegurado == 7
                    || $persona->id_tipo_asegurado == 12 || $persona->id_tipo_asegurado == 13
                ) {
                    $datosBeneficiario = Beneficiario::where('id_persona', $persona->id)
                        ->where('vigencia', 'VIGENTE')
                        ->orderBy('id', 'desc')
                        ->first();
                    $institucionTitular = TitularInstitucion::where('id_titular', $datosBeneficiario->id_titular)
                        ->where('tipo_institucion', 'PATRONAL')
                        ->where('estado', true)
                        ->first();
                    if ($institucionTitular) {
                        $datosInstitucion = Institucion::find($institucionTitular->id_institucion);
                        $this->datosObtenidos[$key]['nombre_institucion'] = $datosInstitucion->nombre;
                        $codigoInstitucionTitular = CodigoInstitucionSsu::where('id_institucion', $institucionTitular->id_institucion)->first();
                        $this->datosObtenidos[$key]['codigo_institucional'] = ($codigoInstitucionTitular != null) ? $codigoInstitucionTitular->codigo.$codigoInstitucionTitular->tipo_beneficiario : null;
                        $esRemuneracion = BeneficiarioInstitucion::where('id_beneficiario', $datosBeneficiario->id)
                            ->where('id_titular', $datosBeneficiario->id_titular)
                            ->where('estado', true)
                            ->orderBy('id', 'desc')
                            ->first();
                        if ($esRemuneracion) {
                            $institucionRemuneracion = Institucion::find($esRemuneracion->id_institucion);
                            $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = $institucionRemuneracion->nombre;
                        } else {
                            $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                        }
                    } else {
                        $this->datosObtenidos[$key]['nombre_institucion'] = 'AFILIADO VOLUNTARIO';
                        $this->datosObtenidos[$key]['codigo_institucional'] = null;
                        $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                    }
                } elseif ($persona->id_tipo_asegurado == 8) {
                    $this->datosObtenidos[$key]['nombre_institucion'] = 'CONVENIO EMERGENCIA';
                    $this->datosObtenidos[$key]['codigo_institucional'] = null;
                    $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                } elseif ($persona->id_tipo_asegurado == 9 || $persona->id_tipo_asegurado == 10) {
                    $convenioInterior = AutorizacionInterior::where('id_persona_interior', $persona->id)
                        ->where('estado', true)
                        ->orderBy('id', 'desc')
                        ->first();
                    $seguro_interior = SeguroInterior::find($convenioInterior->id_seguro_interior);
                    $this->datosObtenidos[$key]['nombre_institucion'] = $seguro_interior->nombre_seguro;
                    $this->datosObtenidos[$key]['codigo_institucional'] = null;
                    $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                } elseif ($persona->id_tipo_asegurado == 11) {
                    $this->datosObtenidos[$key]['nombre_institucion'] = 'APORTE UMSA ESTUDIANTE';
                    $this->datosObtenidos[$key]['codigo_institucional'] = null;
                    $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                } elseif ($persona->id_tipo_asegurado == 14) {
                    $datosPreafiliado = Preafiliacion::with(['institucionPreafiliacion.codigosInstitucion'])
                        ->where('estado', true)
                        ->where('id_persona', $persona->id)
                        ->orderBy('id', 'desc')
                        ->first();

                    $listado[$key]['nombre_institucion'] = $datosPreafiliado->institucionPreafiliacion ? $datosPreafiliado->institucionPreafiliacion->nombre : 'AFILIADO VOLUNTARIO';
                    $listado[$key]['codigo_institucional'] = $datosPreafiliado->institucionPreafiliacion->codigosInstitucion ?
                        $datosPreafiliado->institucionPreafiliacion->codigosInstitucion->codigo.$datosPreafiliado->institucionPreafiliacion->codigosInstitucion->tipo_titular : null;
                    $listado[$key]['institucion_beneficiario_remuneracion'] = null;
                } elseif ($persona->id_tipo_asegurado == 15) {
                    $datosPreafiliado = Preafiliacion::with(['institucionPreafiliacion.codigosInstitucion'])
                        ->where('estado', true)
                        ->where('id_persona', $persona->id)
                        ->orderBy('id', 'desc')
                        ->first();

                    $listado[$key]['nombre_institucion'] = $datosPreafiliado->institucionPreafiliacion ? $datosPreafiliado->institucionPreafiliacion->nombre : 'AFILIADO VOLUNTARIO';
                    $listado[$key]['codigo_institucional'] = $datosPreafiliado->institucionPreafiliacion->codigosInstitucion ?
                        $datosPreafiliado->institucionPreafiliacion->codigosInstitucion->codigo.$datosPreafiliado->institucionPreafiliacion->codigosInstitucion->tipo_beneficiario : null;
                    $listado[$key]['institucion_beneficiario_remuneracion'] = null;
                }
            }

            return $this->datosObtenidos;
        } elseif ($carnet != null && $matricula != null && $complemento != null) {
            $personas = Persona::where('afiliado', true)
                ->whereNot('id_tipo_asegurado', 0)
                ->where('ci', 'like', "$carnet%")
                ->where('complemento', 'like', "$complemento%")
                ->orWhere('ci', 'like', "$matricula%")
                ->orWhere('matricula_seguro', 'like', "$carnet%")
                ->orWhere('matricula_seguro', 'like', "$matricula%")
                ->get();
            //dd($personas);
            foreach ($personas as $key => $persona) {
                $this->datosObtenidos[$key]['id_persona'] = $persona->id;
                $this->datosObtenidos[$key]['ci'] = $persona->ci;
                $this->datosObtenidos[$key]['complemento'] = $persona->complemento;
                $this->datosObtenidos[$key]['matricula_seguro'] = $persona->matricula_seguro;
                $this->datosObtenidos[$key]['nombres'] = $persona->nombres;
                $this->datosObtenidos[$key]['p_apellido'] = $persona->p_apellido;
                $this->datosObtenidos[$key]['s_apellido'] = $persona->s_apellido;
                $this->datosObtenidos[$key]['sexo'] = $persona->sexo;
                $this->datosObtenidos[$key]['fecha_nacimiento'] = $persona->fecha_nacimiento;
                $tipo_asegurado = TipoAsegurado::find($persona->id_tipo_asegurado);
                $this->datosObtenidos[$key]['tipo_asegurado'] = $tipo_asegurado->tipo_asegurado;
                if ($persona->id_tipo_asegurado == 1) {
                    $datosTitular = Titular::where('id_persona', $persona->id)
                        ->where('estado_vigencia', 'VIGENTE')
                        ->orderBy('id', 'desc')
                        ->first();

                    $institucionTitular = TitularInstitucion::where('id_titular', $datosTitular->id)
                        ->where('tipo_institucion', 'PATRONAL')
                        ->where('estado', true)
                        ->first();
                    if ($institucionTitular) {
                        $datosInstitucion = Institucion::find($institucionTitular->id_institucion);
                        $this->datosObtenidos[$key]['nombre_institucion'] = $datosInstitucion->nombre;
                        $codigoInstitucionTitular = CodigoInstitucionSsu::where('id_institucion', $institucionTitular->id_institucion)->first();
                        $this->datosObtenidos[$key]['codigo_institucional'] = ($codigoInstitucionTitular != null) ? $codigoInstitucionTitular->codigo.$codigoInstitucionTitular->tipo_titular : null;
                        $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                    } else {
                        $this->datosObtenidos[$key]['nombre_institucion'] = 'AFILIADO VOLUNTARIO';
                        $this->datosObtenidos[$key]['codigo_institucional'] = null;
                        $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                    }
                } elseif (
                    $persona->id_tipo_asegurado == 2 || $persona->id_tipo_asegurado == 3 || $persona->id_tipo_asegurado == 4 || $persona->id_tipo_asegurado == 5 || $persona->id_tipo_asegurado == 6 || $persona->id_tipo_asegurado == 7
                    || $persona->id_tipo_asegurado == 12 || $persona->id_tipo_asegurado == 13
                ) {
                    $datosBeneficiario = Beneficiario::where('id_persona', $persona->id)
                        ->where('vigencia', 'VIGENTE')
                        ->orderBy('id', 'desc')
                        ->first();
                    $institucionTitular = TitularInstitucion::where('id_titular', $datosBeneficiario->id_titular)
                        ->where('tipo_institucion', 'PATRONAL')
                        ->where('estado', true)
                        ->first();
                    if ($institucionTitular) {
                        $datosInstitucion = Institucion::find($institucionTitular->id_institucion);
                        $this->datosObtenidos[$key]['nombre_institucion'] = $datosInstitucion->nombre;
                        $codigoInstitucionTitular = CodigoInstitucionSsu::where('id_institucion', $institucionTitular->id_institucion)->first();
                        $this->datosObtenidos[$key]['codigo_institucional'] = ($codigoInstitucionTitular != null) ? $codigoInstitucionTitular->codigo.$codigoInstitucionTitular->tipo_beneficiario : null;
                        $esRemuneracion = BeneficiarioInstitucion::where('id_beneficiario', $datosBeneficiario->id)
                            ->where('id_titular', $datosBeneficiario->id_titular)
                            ->where('estado', true)
                            ->orderBy('id', 'desc')
                            ->first();
                        if ($esRemuneracion) {
                            $institucionRemuneracion = Institucion::find($esRemuneracion->id_institucion);
                            $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = $institucionRemuneracion->nombre;
                        } else {
                            $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                        }
                    } else {
                        $this->datosObtenidos[$key]['nombre_institucion'] = 'AFILIADO VOLUNTARIO';
                        $this->datosObtenidos[$key]['codigo_institucional'] = null;
                        $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                    }
                } elseif ($persona->id_tipo_asegurado == 8) {
                    $this->datosObtenidos[$key]['nombre_institucion'] = 'CONVENIO EMERGENCIA';
                    $this->datosObtenidos[$key]['codigo_institucional'] = null;
                    $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                } elseif ($persona->id_tipo_asegurado == 9 || $persona->id_tipo_asegurado == 10) {
                    $convenioInterior = AutorizacionInterior::where('id_persona_interior', $persona->id)
                        ->where('estado', true)
                        ->orderBy('id', 'desc')
                        ->first();
                    $seguro_interior = SeguroInterior::find($convenioInterior->id_seguro_interior);
                    $this->datosObtenidos[$key]['nombre_institucion'] = $seguro_interior->nombre_seguro;
                    $this->datosObtenidos[$key]['codigo_institucional'] = null;
                    $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                } elseif ($persona->id_tipo_asegurado == 11) {
                    $this->datosObtenidos[$key]['nombre_institucion'] = 'APORTE UMSA ESTUDIANTE';
                    $this->datosObtenidos[$key]['codigo_institucional'] = null;
                    $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                } elseif ($persona->id_tipo_asegurado == 14) {
                    $datosPreafiliado = Preafiliacion::with(['institucionPreafiliacion.codigosInstitucion'])
                        ->where('estado', true)
                        ->where('id_persona', $persona->id)
                        ->orderBy('id', 'desc')
                        ->first();

                    $listado[$key]['nombre_institucion'] = $datosPreafiliado->institucionPreafiliacion ? $datosPreafiliado->institucionPreafiliacion->nombre : 'AFILIADO VOLUNTARIO';
                    $listado[$key]['codigo_institucional'] = $datosPreafiliado->institucionPreafiliacion->codigosInstitucion ?
                        $datosPreafiliado->institucionPreafiliacion->codigosInstitucion->codigo.$datosPreafiliado->institucionPreafiliacion->codigosInstitucion->tipo_titular : null;
                    $listado[$key]['institucion_beneficiario_remuneracion'] = null;
                } elseif ($persona->id_tipo_asegurado == 15) {
                    $datosPreafiliado = Preafiliacion::with(['institucionPreafiliacion.codigosInstitucion'])
                        ->where('estado', true)
                        ->where('id_persona', $persona->id)
                        ->orderBy('id', 'desc')
                        ->first();

                    $listado[$key]['nombre_institucion'] = $datosPreafiliado->institucionPreafiliacion ? $datosPreafiliado->institucionPreafiliacion->nombre : 'AFILIADO VOLUNTARIO';
                    $listado[$key]['codigo_institucional'] = $datosPreafiliado->institucionPreafiliacion->codigosInstitucion ?
                        $datosPreafiliado->institucionPreafiliacion->codigosInstitucion->codigo.$datosPreafiliado->institucionPreafiliacion->codigosInstitucion->tipo_beneficiario : null;
                    $listado[$key]['institucion_beneficiario_remuneracion'] = null;
                }
            }

            return $this->datosObtenidos;
        } else {
            return [];
        }
    }

    public function busquedaAfiliadoPorId($idPersonita)
    {
        $persona = Persona::find($idPersonita);
        //dd($personas);
        $this->datosObtenidos['id_persona'] = $persona->id;
        $this->datosObtenidos['ci'] = $persona->ci;
        $this->datosObtenidos['complemento'] = $persona->complemento;
        $this->datosObtenidos['matricula_seguro'] = $persona->matricula_seguro;
        $this->datosObtenidos['nombres'] = $persona->nombres;
        $this->datosObtenidos['p_apellido'] = $persona->p_apellido;
        $this->datosObtenidos['s_apellido'] = $persona->s_apellido;
        $this->datosObtenidos['sexo'] = $persona->sexo;
        $this->datosObtenidos['fecha_nacimiento'] = $persona->fecha_nacimiento;
        $tipo_asegurado = TipoAsegurado::find($persona->id_tipo_asegurado);
        $this->datosObtenidos['tipo_asegurado'] = $tipo_asegurado->tipo_asegurado;
        if ($persona->id_tipo_asegurado == 1) {
            //dd($persona->id);
            $datosTitular = Titular::where('id_persona', $persona->id)
                //->where('estado_vigencia', 'VIGENTE')
                ->orderBy('id', 'desc')
                ->first();

            //if ($datosTitular) {
            $institucionTitular = TitularInstitucion::where('id_titular', $datosTitular->id)
                //->where('tipo_institucion', 'PATRONAL')
                ->where('estado', true)
                ->orderBy('id', 'asc')
                ->first();
            if ($institucionTitular) {
                $datosInstitucion = Institucion::find($institucionTitular->id_institucion);
                $this->datosObtenidos['nombre_institucion'] = $datosInstitucion->nombre;
                $codigoInstitucionTitular = CodigoInstitucionSsu::where('id_institucion', $institucionTitular->id_institucion)->first();
                if ($codigoInstitucionTitular) {
                    $this->datosObtenidos['codigo_institucional'] = ($codigoInstitucionTitular != null) ? $codigoInstitucionTitular->codigo.$codigoInstitucionTitular->tipo_titular : null;
                    $this->datosObtenidos['institucion_beneficiario_remuneracion'] = null;
                } else {
                    $this->datosObtenidos['codigo_institucional'] = null;
                    $this->datosObtenidos['institucion_beneficiario_remuneracion'] = null;
                }
            } else {
                $this->datosObtenidos['nombre_institucion'] = 'AFILIADO VOLUNTARIO';
                $this->datosObtenidos['codigo_institucional'] = null;
                $this->datosObtenidos['institucion_beneficiario_remuneracion'] = null;
            }
            //}
        } elseif (
            $persona->id_tipo_asegurado == 2 ||
            $persona->id_tipo_asegurado == 3 ||
            $persona->id_tipo_asegurado == 4 ||
            $persona->id_tipo_asegurado == 5 ||
            $persona->id_tipo_asegurado == 6 ||
            $persona->id_tipo_asegurado == 7 ||
            $persona->id_tipo_asegurado == 12 ||
            $persona->id_tipo_asegurado == 13
        ) {
            $datosBeneficiario = Beneficiario::where('id_persona', $persona->id)
                //->where('vigencia', 'VIGENTE')
                ->orderBy('id', 'desc')
                ->first();
            $institucionTitular = TitularInstitucion::where('id_titular', $datosBeneficiario->id_titular)
                //->where('tipo_institucion', 'PATRONAL')
                ->where('estado', true)
                ->orderBy('id', 'asc')
                ->first();
            if ($institucionTitular) {
                $datosInstitucion = Institucion::find($institucionTitular->id_institucion);
                $this->datosObtenidos['nombre_institucion'] = $datosInstitucion->nombre;
                $codigoInstitucionTitular = CodigoInstitucionSsu::where('id_institucion', $institucionTitular->id_institucion)->first();
                if ($codigoInstitucionTitular) {
                    $this->datosObtenidos['codigo_institucional'] = ($codigoInstitucionTitular != null) ? $codigoInstitucionTitular->codigo.$codigoInstitucionTitular->tipo_beneficiario : null;
                } else {
                    $this->datosObtenidos['codigo_institucional'] = null;
                }

                $esRemuneracion = BeneficiarioInstitucion::where('id_beneficiario', $datosBeneficiario->id)
                    ->where('id_titular', $datosBeneficiario->id_titular)
                    ->where('estado', true)
                    ->orderBy('id', 'desc')
                    ->first();
                if ($esRemuneracion) {
                    $institucionRemuneracion = Institucion::find($esRemuneracion->id_institucion);
                    $this->datosObtenidos['institucion_beneficiario_remuneracion'] = $institucionRemuneracion->nombre;
                } else {
                    $this->datosObtenidos['institucion_beneficiario_remuneracion'] = null;
                }
            } else {
                $this->datosObtenidos['nombre_institucion'] = 'AFILIADO VOLUNTARIO';
                $this->datosObtenidos['codigo_institucional'] = null;
                $this->datosObtenidos['institucion_beneficiario_remuneracion'] = null;
            }
        } elseif ($persona->id_tipo_asegurado == 8) {
            $this->datosObtenidos['nombre_institucion'] = 'CONVENIO EMERGENCIA';
            $this->datosObtenidos['codigo_institucional'] = null;
            $this->datosObtenidos['institucion_beneficiario_remuneracion'] = null;
        } elseif ($persona->id_tipo_asegurado == 9 || $persona->id_tipo_asegurado == 10) {
            //dd($persona);
            $convenioInterior = AutorizacionInterior::where('id_persona_interior', $persona->id)
                //->where('estado', true)
                ->orderBy('id', 'desc')
                ->first();
            $seguro_interior = SeguroInterior::find($convenioInterior->id_seguro_interior);
            $this->datosObtenidos['nombre_institucion'] = $seguro_interior->nombre_seguro;
            $this->datosObtenidos['codigo_institucional'] = null;
            $this->datosObtenidos['institucion_beneficiario_remuneracion'] = null;
        } elseif ($persona->id_tipo_asegurado == 11) {
            $this->datosObtenidos['nombre_institucion'] = 'APORTE UMSA ESTUDIANTE';
            $this->datosObtenidos['codigo_institucional'] = null;
            $this->datosObtenidos['institucion_beneficiario_remuneracion'] = null;
        } elseif ($persona->id_tipo_asegurado == 14) {
            $datosPreafiliado = Preafiliacion::with(['institucionPreafiliacion.codigosInstitucion'])
                ->where('estado', true)
                ->where('id_persona', $persona->id)
                ->orderBy('id', 'desc')
                ->first();

            $this->datosObtenidos['nombre_institucion'] = $datosPreafiliado->institucionPreafiliacion ? $datosPreafiliado->institucionPreafiliacion->nombre : 'AFILIADO VOLUNTARIO';
            $this->datosObtenidos['codigo_institucional'] = $datosPreafiliado->institucionPreafiliacion ? ($datosPreafiliado->institucionPreafiliacion->codigosInstitucion ?
                $datosPreafiliado->institucionPreafiliacion->codigosInstitucion->codigo.$datosPreafiliado->institucionPreafiliacion->codigosInstitucion->tipo_titular : null) : null;

            $this->datosObtenidos['institucion_beneficiario_remuneracion'] = null;
        } elseif ($persona->id_tipo_asegurado == 15) {
            $datosPreafiliado = Preafiliacion::with(['institucionPreafiliacion.codigosInstitucion'])
                ->where('estado', true)
                ->where('id_persona', $persona->id)
                ->orderBy('id', 'desc')
                ->first();

            $this->datosObtenidos['nombre_institucion'] = $datosPreafiliado->institucionPreafiliacion ? $datosPreafiliado->institucionPreafiliacion->nombre : 'AFILIADO VOLUNTARIO';
            $this->datosObtenidos['codigo_institucional'] = $datosPreafiliado->institucionPreafiliacion ? ($datosPreafiliado->institucionPreafiliacion->codigosInstitucion ?
                $datosPreafiliado->institucionPreafiliacion->codigosInstitucion->codigo.$datosPreafiliado->institucionPreafiliacion->codigosInstitucion->tipo_beneficiario : null) : null;
            $this->datosObtenidos['institucion_beneficiario_remuneracion'] = null;
        }

        return $this->datosObtenidos;
    }

    public function busquedaAfiliadoPorIdParaBajaInstitucional($idPersonita)
    {
        $persona = Persona::where('id', $idPersonita)->with(['tipoAseguradoPersona'])->first();

        $this->datosObtenidos['id_persona'] = $persona->id;
        $this->datosObtenidos['ci'] = $persona->ci;
        $this->datosObtenidos['complemento'] = $persona->complemento;
        $this->datosObtenidos['matricula_seguro'] = $persona->matricula_seguro;
        $this->datosObtenidos['nombres'] = $persona->nombres;
        $this->datosObtenidos['p_apellido'] = $persona->p_apellido;
        $this->datosObtenidos['s_apellido'] = $persona->s_apellido;
        $this->datosObtenidos['sexo'] = $persona->sexo;
        $this->datosObtenidos['fecha_nacimiento'] = $persona->fecha_nacimiento;
        $this->datosObtenidos['tipo_asegurado'] = $persona->tipoAseguradoPersona->tipo_asegurado;
        $this->datosObtenidos['id_tipo_asegurado'] = $persona->id_tipo_asegurado;
        if ($persona->tipoAseguradoPersona->tipo_asegurado == 'TITULAR') {

            $datosTitular = Titular::where('id_persona', $persona->id)
                ->orderBy('id', 'desc')
                ->first();
            $this->datosObtenidos['estado_vigencia'] = $datosTitular->estado_vigencia;
            $liga = 'Afiliacion/FOTOS/';
            $this->datosObtenidos['foto'] = $this->obtenerImagenBase64($datosTitular->foto_nombre, $liga);
            $institucionTitular = TitularInstitucion::where('id_titular', $datosTitular->id)
                ->where('tipo_institucion', 'PATRONAL')
                ->where('estado', true)
                ->first();
            if ($institucionTitular) {
                $datosInstitucion = Institucion::find($institucionTitular->id_institucion);
                $this->datosObtenidos['nombre_institucion'] = $datosInstitucion->nombre;
                $codigoInstitucionTitular = CodigoInstitucionSsu::where('id_institucion', $institucionTitular->id_institucion)->first();
                $this->datosObtenidos['codigo_institucional'] = ($codigoInstitucionTitular != null) ? $codigoInstitucionTitular->codigo.$codigoInstitucionTitular->tipo_titular : null;
                $this->datosObtenidos['institucion_beneficiario_remuneracion'] = null;
            } else {
                $this->datosObtenidos['nombre_institucion'] = 'AFILIADO VOLUNTARIO';
                $this->datosObtenidos['codigo_institucional'] = null;
                $this->datosObtenidos['institucion_beneficiario_remuneracion'] = null;
            }

            ///DATOS AFILIACION Y CARNET

            $this->datosObtenidos['fecha_reafiliacion'] = $datosTitular->fecha_reafiliacion != null ? $datosTitular->fecha_reafiliacion : $datosTitular->fecha_afiliacion;

            $datosCarnet = HCarnet::where('id_persona', $datosTitular->id_persona)->orderBy('id', 'desc')->first();

            if ($datosCarnet) {
                if ($datosCarnet->indefinido) {
                    $this->datosObtenidos['vencimiento_carnet'] = 'INDEFINIDO';
                } else {
                    $this->datosObtenidos['vencimiento_carnet'] = $datosCarnet->fecha_vencimiento;
                }
            } else {
                $this->datosObtenidos['vencimiento_carnet'] = null;
            }
        } elseif ($persona->tipoAseguradoPersona->tipo_asegurado == 'PREAFILIADO TITULAR') {
            $datosPreafiliado = Preafiliacion::with(['institucionPreafiliacion.codigosInstitucion'])
                ->where('estado', true)
                ->where('id_persona', $persona->id)
                ->orderBy('id', 'desc')
                ->first();

            $this->datosObtenidos['nombre_institucion'] = $datosPreafiliado->institucionPreafiliacion ? $datosPreafiliado->institucionPreafiliacion->nombre : 'AFILIADO VOLUNTARIO';
            $this->datosObtenidos['codigo_institucional'] = $datosPreafiliado->institucionPreafiliacion->codigosInstitucion ?
                $datosPreafiliado->institucionPreafiliacion->codigosInstitucion->codigo.$datosPreafiliado->institucionPreafiliacion->codigosInstitucion->tipo_beneficiario : null;
            $this->datosObtenidos['institucion_beneficiario_remuneracion'] = null;
        } elseif (Str::contains($persona->tipoAseguradoPersona->tipo_asegurado, 'BENEFICIARIO')) {
            if ($persona->tipoAseguradoPersona->tipo_asegurado != 'BENEFICIARIO DEL INTERIOR') {
                $datosBeneficiario = Beneficiario::where('id_persona', $persona->id)
                    ->orderBy('id', 'desc')
                    ->first();
                $this->datosObtenidos['estado_vigencia'] = $datosBeneficiario->vigencia;
                $liga = 'Afiliacion/FOTOS/';
                $this->datosObtenidos['foto'] = $this->obtenerImagenBase64($datosBeneficiario->foto_nombre, $liga);
                $institucionTitular = TitularInstitucion::where('id_titular', $datosBeneficiario->id_titular)
                    ->where('estado', true)
                    ->orderBy('id', 'asc')
                    ->first();
                if ($institucionTitular) {
                    $datosInstitucion = Institucion::find($institucionTitular->id_institucion);
                    $this->datosObtenidos['nombre_institucion'] = $datosInstitucion->nombre;
                    $codigoInstitucionTitular = CodigoInstitucionSsu::where('id_institucion', $institucionTitular->id_institucion)->first();
                    $this->datosObtenidos['codigo_institucional'] = ($codigoInstitucionTitular != null) ? $codigoInstitucionTitular->codigo.$codigoInstitucionTitular->tipo_beneficiario : null;
                    $esRemuneracion = BeneficiarioInstitucion::where('id_beneficiario', $datosBeneficiario->id)
                        ->where('id_titular', $datosBeneficiario->id_titular)
                        ->where('estado', true)
                        ->orderBy('id', 'desc')
                        ->first();
                    if ($esRemuneracion) {
                        $institucionRemuneracion = Institucion::find($esRemuneracion->id_institucion);
                        $this->datosObtenidos['institucion_beneficiario_remuneracion'] = $institucionRemuneracion->nombre;
                    } else {
                        $this->datosObtenidos['institucion_beneficiario_remuneracion'] = null;
                    }
                } else {
                    $this->datosObtenidos['nombre_institucion'] = 'AFILIADO VOLUNTARIO';
                    $this->datosObtenidos['codigo_institucional'] = null;
                    $this->datosObtenidos['institucion_beneficiario_remuneracion'] = null;
                }

                ///DATOS AFILIACION Y CARNET

                $this->datosObtenidos['fecha_reafiliacion'] = $datosBeneficiario->fecha_reafiliacion != null ? $datosBeneficiario->fecha_reafiliacion : $datosBeneficiario->fecha_afiliacion;

                $datosCarnet = HCarnet::where('id_persona', $datosBeneficiario->id_persona)->orderBy('id', 'desc')->first();

                if ($datosCarnet) {
                    if ($datosCarnet->indefinido) {
                        $this->datosObtenidos['vencimiento_carnet'] = 'INDEFINIDO';
                    } else {
                        $this->datosObtenidos['vencimiento_carnet'] = $datosCarnet->fecha_vencimiento;
                    }
                } else {
                    $this->datosObtenidos['vencimiento_carnet'] = null;
                }
            } else {
                if ($persona->tipoAseguradoPersona->tipo_asegurado == 'PREAFILIADO BENEFICIARIO') {
                    $datosPreafiliado = Preafiliacion::with(['institucionPreafiliacion.codigosInstitucion'])
                        ->where('estado', true)
                        ->where('id_persona', $persona->id)
                        ->orderBy('id', 'desc')
                        ->first();

                    $this->datosObtenidos['nombre_institucion'] = $datosPreafiliado->institucionPreafiliacion ? $datosPreafiliado->institucionPreafiliacion->nombre : 'AFILIADO VOLUNTARIO';
                    $this->datosObtenidos['codigo_institucional'] = $datosPreafiliado->institucionPreafiliacion->codigosInstitucion ?
                        $datosPreafiliado->institucionPreafiliacion->codigosInstitucion->codigo.$datosPreafiliado->institucionPreafiliacion->codigosInstitucion->tipo_beneficiario : null;
                    $this->datosObtenidos['institucion_beneficiario_remuneracion'] = null;
                } else {
                    $convenioInterior = AutorizacionInterior::where('id_persona_interior', $persona->id)
                        ->orderBy('id', 'desc')
                        ->first();
                    $liga = 'Vigencia/FOTOS/CONVENIO/';
                    $this->datosObtenidos['foto'] = $this->obtenerImagenBase64($convenioInterior->foto_nombre, $liga);
                    if ($convenioInterior->estado == true) {
                        $this->datosObtenidos['estado_vigencia'] = 'VIGENTE';
                    } else {
                        $this->datosObtenidos['estado_vigencia'] = 'NO VIGENTE';
                    }

                    $seguro_interior = SeguroInterior::find($convenioInterior->id_seguro_interior);
                    $this->datosObtenidos['nombre_institucion'] = $seguro_interior->nombre_seguro;
                    $this->datosObtenidos['codigo_institucional'] = null;
                    $this->datosObtenidos['institucion_beneficiario_remuneracion'] = null;

                    ///DATOS AFILIACION Y CARNET

                    $this->datosObtenidos['fecha_reafiliacion'] = null;
                    $this->datosObtenidos['vencimiento_carnet'] = null;
                }
            }
        } elseif (Str::contains($persona->tipoAseguradoPersona->tipo_asegurado, 'CONVENIO')) {
            $convenioEmergencia = Convenio::where('id_persona', $persona->id)
                ->orderBy('id', 'desc')
                ->first();
            $liga = 'Vigencia/FOTOS/CONVENIO/';
            $this->datosObtenidos['foto'] = $this->obtenerImagenBase64($convenioEmergencia->foto_nombre, $liga);
            if ($convenioEmergencia->estado == true) {
                $this->datosObtenidos['estado_vigencia'] = 'VIGENTE';
            } else {
                $this->datosObtenidos['estado_vigencia'] = 'NO VIGENTE';
            }
            $this->datosObtenidos['nombre_institucion'] = 'CONVENIO EMERGENCIA';
            $this->datosObtenidos['codigo_institucional'] = null;
            $this->datosObtenidos['institucion_beneficiario_remuneracion'] = null;

            ///DATOS AFILIACION Y CARNET

            $this->datosObtenidos['fecha_reafiliacion'] = null;
            $this->datosObtenidos['vencimiento_carnet'] = null;
        } elseif (Str::contains($persona->tipoAseguradoPersona->tipo_asegurado, 'DEL INTERIOR')) {
            //dd($persona);
            $convenioInterior = AutorizacionInterior::where('id_persona_interior', $persona->id)
                ->orderBy('id', 'desc')
                ->first();
            $liga = 'Vigencia/FOTOS/CONVENIO/';
            $this->datosObtenidos['foto'] = $this->obtenerImagenBase64($convenioInterior->foto_nombre, $liga);
            if ($convenioInterior->estado == true) {
                $this->datosObtenidos['estado_vigencia'] = 'VIGENTE';
            } else {
                $this->datosObtenidos['estado_vigencia'] = 'NO VIGENTE';
            }

            $seguro_interior = SeguroInterior::find($convenioInterior->id_seguro_interior);
            $this->datosObtenidos['nombre_institucion'] = $seguro_interior->nombre_seguro;
            $this->datosObtenidos['codigo_institucional'] = null;
            $this->datosObtenidos['institucion_beneficiario_remuneracion'] = null;

            ///DATOS AFILIACION Y CARNET

            $this->datosObtenidos['fecha_reafiliacion'] = null;
            $this->datosObtenidos['vencimiento_carnet'] = null;
        } elseif ($persona->tipoAseguradoPersona->tipo_asegurado == 'ESTUDIANTE') {
            $estudiante = Estudiante::where('id_persona', $persona->id)
                ->orderBy('id', 'desc')
                ->first();
            $liga = 'Afiliacion/SSUE/FOTOS/';
            $this->datosObtenidos['foto'] = $this->obtenerImagenBase64($estudiante->foto_nombre, $liga);
            if ($estudiante->estado_vigencia == true) {
                $this->datosObtenidos['estado_vigencia'] = 'VIGENTE';
            } else {
                $this->datosObtenidos['estado_vigencia'] = 'NO VIGENTE';
            }
            $this->datosObtenidos['nombre_institucion'] = 'APORTE UMSA ESTUDIANTE';
            $this->datosObtenidos['codigo_institucional'] = null;
            $this->datosObtenidos['institucion_beneficiario_remuneracion'] = null;
            ///DATOS AFILIACION Y CARNET

            $this->datosObtenidos['fecha_reafiliacion'] = $estudiante->fecha_reafiliacion != null ? $estudiante->fecha_reafiliacion : $estudiante->fecha_afiliacion;
            $this->datosObtenidos['vencimiento_carnet'] = null;
        } elseif ($persona->id_tipo_asegurado == 0) {
            $this->datosObtenidos['foto'] = null;
            $this->datosObtenidos['estado_vigencia'] = 'NO AFILIADO O NO VIGENTE';
            $this->datosObtenidos['nombre_institucion'] = null;
            $this->datosObtenidos['codigo_institucional'] = null;
            $this->datosObtenidos['institucion_beneficiario_remuneracion'] = null;
            $this->datosObtenidos['fecha_reafiliacion'] = null;
            $this->datosObtenidos['vencimiento_carnet'] = null;
        }

        return $this->datosObtenidos;
    }

    private function obtenerImagenBase64($idFoto, $liga)
    {
        try {
            if ($idFoto) {
                $path = $liga.$idFoto;
                $imageData = Storage::disk('ftp')->get($path);
                // dd($imageData);
                $foto_nombre = base64_encode($imageData);

                return $foto_nombre;
            } else {
                $imageDefecto = public_path('images/default/user_default.jpg');

                // dd($imageDefecto);
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function busquedaAfiliadoPorIdParaBajaInstitucionalNuevo($idPersonita)
    {
        $persona = Persona::where('id', $idPersonita)->with(['tipoAseguradoPersona'])->first();

        $this->datosObtenidos = [
            'id_persona' => $persona->id,
            'ci' => $persona->ci,
            'complemento' => $persona->complemento,
            'matricula_seguro' => $persona->matricula_seguro,
            'nombres' => $persona->nombres,
            'p_apellido' => $persona->p_apellido,
            's_apellido' => $persona->s_apellido,
            'sexo' => $persona->sexo,
            'fecha_nacimiento' => $persona->fecha_nacimiento,
            'tipo_asegurado' => $persona->tipoAseguradoPersona->tipo_asegurado,
            'id_tipo_asegurado' => $persona->id_tipo_asegurado,
        ];

        $liga = 'Afiliacion/FOTOS/';
        $tipoAsegurado = $persona->tipoAseguradoPersona->tipo_asegurado;

        if ($tipoAsegurado == 'TITULAR') {
            $datosTitular = Titular::where('id_persona', $persona->id)->where('estado', true)->first();
            $this->datosObtenidos['id_corresponde'] = $datosTitular->id;
            $this->datosObtenidos['estado_vigencia'] = $datosTitular->estado_vigencia;
            $this->datosObtenidos['foto'] = $this->obtenerImagenBase64($datosTitular->foto_nombre, $liga);
            $institucionesTitular = TitularInstitucion::where('id_titular', $datosTitular->id)->where('estado', true)->get();
            $this->paraInstituciones($institucionesTitular, 'tipo_titular');
            $this->paraDatosAfiliacion($datosTitular);
        } elseif ($tipoAsegurado == 'PREAFILIADO TITULAR') {
            $this->paraPreafiliadoTitular($persona);
        } elseif (Str::contains($tipoAsegurado, 'BENEFICIARIO')) {
            $this->paraBeneficiario($persona, $tipoAsegurado);
        } elseif (Str::contains($tipoAsegurado, 'CONVENIO')) {
            $this->paraConvenioEmergencia($persona);
        } elseif (Str::contains($tipoAsegurado, 'DEL INTERIOR')) {
            $this->paraConvenioInterior($persona);
        } elseif ($tipoAsegurado == 'ESTUDIANTE') {
            $this->paraEstudiantes($persona);
        } elseif ($persona->id_tipo_asegurado == 0) {
            $this->paraNoAfiliado();
        }

        return $this->datosObtenidos;
    }

    private function paraInstituciones($institucionesTitular, $tipo)
    {
        $this->datosObtenidos['instituciones'] = [];
        foreach ($institucionesTitular as $institucionTitular) {
            $datosInstitucion = Institucion::find($institucionTitular->id_institucion);
            $codigoInstitucionTitular = CodigoInstitucionSsu::where('id_institucion', $institucionTitular->id_institucion)->first();
            $this->datosObtenidos['instituciones'][] = [
                'id_institucion' => $datosInstitucion->id,
                'nombre_institucion' => $datosInstitucion->nombre,
                'codigo_institucional' => $codigoInstitucionTitular ? $codigoInstitucionTitular->codigo.$codigoInstitucionTitular->$tipo : null,
                'institucion_beneficiario_remuneracion' => null,
            ];
        }

        if (empty($this->datosObtenidos['instituciones'])) {
            $this->datosObtenidos['instituciones'][] = [
                'id_institucion' => null,
                'nombre_institucion' => 'AFILIADO VOLUNTARIO',
                'codigo_institucional' => null,
                'institucion_beneficiario_remuneracion' => null,
            ];
        }
    }

    private function paraDatosAfiliacion($datos)
    {
        $this->datosObtenidos['fecha_reafiliacion'] = $datos->fecha_reafiliacion ?? $datos->fecha_afiliacion;
        $datosCarnet = HCarnet::where('id_persona', $datos->id_persona)->orderBy('id', 'desc')->first();
        $this->datosObtenidos['vencimiento_carnet'] = $datosCarnet ? ($datosCarnet->indefinido ? 'INDEFINIDO' : $datosCarnet->fecha_vencimiento) : null;
    }

    private function paraBeneficiario($persona, $tipoAsegurado)
    {
        if ($tipoAsegurado == 'PREAFILIADO BENEFICIARIO') {
            $this->paraPreafiliadoBeneficiario($persona);
        } else {
            if ($tipoAsegurado != 'BENEFICIARIO DEL INTERIOR') {
                $datosBeneficiario = Beneficiario::where('id_persona', $persona->id)->where('estado', true)->first();
                $this->datosObtenidos['id_corresponde'] = $datosBeneficiario->id;
                $this->datosObtenidos['estado_vigencia'] = $datosBeneficiario->vigencia;
                $this->datosObtenidos['foto'] = $this->obtenerImagenBase64($datosBeneficiario->foto_nombre, 'Afiliacion/FOTOS/');
                $institucionesTitular = TitularInstitucion::where('id_titular', $datosBeneficiario->id_titular)->where('estado', true)->orderBy('id', 'asc')->get();
                $this->paraInstituciones($institucionesTitular, 'tipo_beneficiario');
                $this->paraDatosAfiliacion($datosBeneficiario);
            } else {
                $this->paraConvenioInterior($persona);
            }
        }
    }

    private function paraConvenioEmergencia($persona)
    {
        $convenioEmergencia = Convenio::where('id_persona', $persona->id)->orderBy('id', 'desc')->first();
        $this->datosObtenidos['foto'] = $this->obtenerImagenBase64($convenioEmergencia->foto_nombre, 'Vigencia/FOTOS/CONVENIO/');
        $this->datosObtenidos['id_corresponde'] = $convenioEmergencia->id;
        $this->datosObtenidos['estado_vigencia'] = $convenioEmergencia->estado ? 'VIGENTE' : 'NO VIGENTE';
        $this->datosObtenidos['instituciones'][] = [
            'id_institucion' => null,
            'nombre_institucion' => 'CONVENIO EMERGENCIA',
            'codigo_institucional' => null,
            'institucion_beneficiario_remuneracion' => null,
        ];
        $this->datosObtenidos['fecha_reafiliacion'] = null;
        $this->datosObtenidos['vencimiento_carnet'] = null;
    }

    private function paraPreafiliadoTitular($persona)
    {
        $datosPreafiliado = Preafiliacion::with(['institucionPreafiliacion.codigosInstitucion'])
            ->where('estado', true)
            ->where('id_persona', $persona->id)
            ->orderBy('id', 'desc')
            ->first();
        $this->datosObtenidos['foto'] = null;
        $this->datosObtenidos['id_corresponde'] = $datosPreafiliado->id;
        $this->datosObtenidos['estado_vigencia'] = 'VIGENTE';
        $this->datosObtenidos['instituciones'][] = [
            'id_institucion' => $datosPreafiliado->institucionPreafiliacion ? $datosPreafiliado->institucionPreafiliacion->id : null,
            'nombre_institucion' => $datosPreafiliado->institucionPreafiliacion ? $datosPreafiliado->institucionPreafiliacion->nombre : null,
            'codigo_institucional' => $datosPreafiliado->institucionPreafiliacion ? ($datosPreafiliado->institucionPreafiliacion->codigosInstitucion ?
                $datosPreafiliado->institucionPreafiliacion->codigosInstitucion->codigo.$datosPreafiliado->institucionPreafiliacion->codigosInstitucion->tipo_titular : null) : null,
            'institucion_beneficiario_remuneracion' => null,
        ];
        $this->datosObtenidos['fecha_reafiliacion'] = $datosPreafiliado->fecha_inicio;
        $this->datosObtenidos['vencimiento_carnet'] = $datosPreafiliado->fecha_fin;
    }

    private function paraPreafiliadoBeneficiario($persona)
    {
        $datosPreafiliado = Preafiliacion::with(['institucionPreafiliacion.codigosInstitucion'])
            ->where('estado', true)
            ->where('id_persona', $persona->id)
            ->orderBy('id', 'desc')
            ->first();
        $this->datosObtenidos['foto'] = null;
        $this->datosObtenidos['id_corresponde'] = $datosPreafiliado->id;
        $this->datosObtenidos['estado_vigencia'] = 'VIGENTE';
        $this->datosObtenidos['instituciones'][] = [
            'id_institucion' => $datosPreafiliado->institucionPreafiliacion ? $datosPreafiliado->institucionPreafiliacion->id : null,
            'nombre_institucion' => $datosPreafiliado->institucionPreafiliacion ? $datosPreafiliado->institucionPreafiliacion->nombre : null,
            'codigo_institucional' => $datosPreafiliado->institucionPreafiliacion ? ($datosPreafiliado->institucionPreafiliacion->codigosInstitucion ?
                $datosPreafiliado->institucionPreafiliacion->codigosInstitucion->codigo.$datosPreafiliado->institucionPreafiliacion->codigosInstitucion->tipo_beneficiario : null) : null,
            'institucion_beneficiario_remuneracion' => null,
        ];
        $this->datosObtenidos['fecha_reafiliacion'] = $datosPreafiliado->fecha_inicio;
        $this->datosObtenidos['vencimiento_carnet'] = $datosPreafiliado->fecha_fin;
    }

    private function paraConvenioInterior($persona)
    {
        $convenioInterior = AutorizacionInterior::where('id_persona_interior', $persona->id)->orderBy('id', 'desc')->first();
        $this->datosObtenidos['foto'] = $this->obtenerImagenBase64($convenioInterior->foto_nombre, 'Vigencia/FOTOS/CONVENIO/');
        $this->datosObtenidos['id_corresponde'] = $convenioInterior->id;
        $this->datosObtenidos['estado_vigencia'] = $convenioInterior->estado ? 'VIGENTE' : 'NO VIGENTE';
        $seguro_interior = SeguroInterior::find($convenioInterior->id_seguro_interior);
        $this->datosObtenidos['instituciones'][] = [
            'id_institucion' => null,
            'nombre_institucion' => $seguro_interior->nombre_seguro,
            'codigo_institucional' => null,
            'institucion_beneficiario_remuneracion' => null,
        ];
        $this->datosObtenidos['fecha_reafiliacion'] = null;
        $this->datosObtenidos['vencimiento_carnet'] = null;
    }

    private function paraEstudiantes($persona)
    {
        $estudiante = Estudiante::where('id_persona', $persona->id)->with('personaEstudiante')->orderBy('id', 'desc')->first();
        $this->datosObtenidos['foto'] = $this->obtenerImagenBase64($estudiante->foto_nombre, 'Afiliacion/SSUE/FOTOS/');
        $this->datosObtenidos['id_corresponde'] = $estudiante->id;
        $this->datosObtenidos['estado_vigencia'] = $estudiante->personaEstudiante->afiliado ? 'VIGENTE' : 'NO VIGENTE';
        $this->datosObtenidos['instituciones'][] = [
            'id_institucion' => null,
            'nombre_institucion' => 'APORTE UMSA ESTUDIANTE',
            'codigo_institucional' => null,
            'institucion_beneficiario_remuneracion' => null,
        ];
        $this->datosObtenidos['fecha_reafiliacion'] = $estudiante->fecha_reafiliacion ?? $estudiante->fecha_afiliacion;
        $this->datosObtenidos['vencimiento_carnet'] = null;
    }

    private function paraNoAfiliado()
    {
        $this->datosObtenidos['foto'] = null;
        $this->datosObtenidos['id_corresponde'] = null;
        $this->datosObtenidos['estado_vigencia'] = 'NO AFILIADO O NO VIGENTE';
        $this->datosObtenidos['instituciones'][] = [
            'id_institucion' => null,
            'nombre_institucion' => null,
            'codigo_institucional' => null,
            'institucion_beneficiario_remuneracion' => null,
        ];
        $this->datosObtenidos['fecha_reafiliacion'] = null;
        $this->datosObtenidos['vencimiento_carnet'] = null;
    }

    // FUNCIONES PARA KIOSKO -----------------------------
    public $fue_asegurado = false;

    public function procesarTitular($persona, $key)
    {
        $datosTitular = Titular::where('id_persona', $persona->id)
            ->orderBy('id', 'desc')
            ->first();
        if ($datosTitular) {
            $this->datosObtenidos[$key]['estado_vigencia'] = $datosTitular->estado_vigencia;
            $institucionTitular = TitularInstitucion::where('id_titular', $datosTitular->id)
                ->where('estado', true)
                ->first();
            if ($institucionTitular) {
                $datosInstitucion = Institucion::find($institucionTitular->id_institucion);
                $this->datosObtenidos[$key]['nombre_institucion'] = $datosInstitucion->nombre;
                $codigoInstitucionTitular = CodigoInstitucionSsu::where('id_institucion', $institucionTitular->id_institucion)->first();
                $this->datosObtenidos[$key]['codigo_institucional'] = ($codigoInstitucionTitular != null) ? $codigoInstitucionTitular->codigo.$codigoInstitucionTitular->tipo_titular : null;
                $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
            } else {
                $this->datosObtenidos[$key]['nombre_institucion'] = 'AFILIADO VOLUNTARIO';
                $this->datosObtenidos[$key]['codigo_institucional'] = null;
            }
        } else {
            return [];
        }
    }

    public function procesarBeneficiarios($persona, $key)
    {
        $datosBeneficiario = Beneficiario::where('id_persona', $persona->id)
            ->orderBy('id', 'desc')
            ->first();
        if ($datosBeneficiario) {
            $this->datosObtenidos[$key]['estado_vigencia'] = $datosBeneficiario->vigencia;
            $institucionTitular = TitularInstitucion::where('id_titular', $datosBeneficiario->id_titular)
                // ->where('tipo_institucion', 'PATRONAL')
                ->where('estado', true)
                ->first();
            if ($institucionTitular) {
                $datosInstitucion = Institucion::find($institucionTitular->id_institucion);
                $this->datosObtenidos[$key]['nombre_institucion'] = $datosInstitucion->nombre;
                $codigoInstitucionTitular = CodigoInstitucionSsu::where('id_institucion', $institucionTitular->id_institucion)->first();
                $this->datosObtenidos[$key]['codigo_institucional'] = ($codigoInstitucionTitular != null) ? $codigoInstitucionTitular->codigo.$codigoInstitucionTitular->tipo_beneficiario : null;
                $esRemuneracion = BeneficiarioInstitucion::where('id_beneficiario', $datosBeneficiario->id)
                    ->where('id_titular', $datosBeneficiario->id_titular)
                    ->where('estado', true)
                    ->orderBy('id', 'desc')
                    ->first();
                if ($esRemuneracion) {
                    $institucionRemuneracion = Institucion::find($esRemuneracion->id_institucion);
                    $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = $institucionRemuneracion->nombre;
                } else {
                    $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
                }
            } else {
                $this->datosObtenidos[$key]['nombre_institucion'] = 'AFILIADO VOLUNTARIO';
                $this->datosObtenidos[$key]['codigo_institucional'] = null;
                $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
            }
        } else {
            return [];
        }
    }

    public function procesarEstudiante($persona, $key)
    {
        $estudiante = Estudiante::where('id_persona', $persona->id)
            ->orderBy('id', 'desc')
            ->first();
        if ($estudiante) {
            if ($estudiante->estado_vigencia == true) {
                $this->datosObtenidos[$key]['estado_vigencia'] = 'VIGENTE';
            } else {
                $this->datosObtenidos[$key]['estado_vigencia'] = 'NO VIGENTE';
            }
            $this->datosObtenidos[$key]['nombre_institucion'] = 'APORTE UMSA ESTUDIANTE';
            $this->datosObtenidos[$key]['codigo_institucional'] = null;
            $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
        } else {
            return [];
        }
    }

    public function procesarTitularConvenio8($persona, $key)
    {
        $convenioEmergencia = Convenio::where('id_persona', $persona->id)
            ->orderBy('id', 'desc')
            ->first();
        if ($convenioEmergencia) {
            if ($convenioEmergencia->estado == true) {
                $this->datosObtenidos[$key]['estado_vigencia'] = 'VIGENTE';
            } else {
                $this->datosObtenidos[$key]['estado_vigencia'] = 'NO VIGENTE';
            }
            $this->datosObtenidos[$key]['nombre_institucion'] = 'CONVENIO EMERGENCIA';
            $this->datosObtenidos[$key]['codigo_institucional'] = null;
            $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
        } else {
            return [];
        }
    }

    public function procesarTitularBeneficiarioInterior($persona, $key)
    {
        $convenioInterior = AutorizacionInterior::where('id_persona_interior', $persona->id)
            ->orderBy('id', 'desc')
            ->first();
        if ($convenioInterior) {
            if ($convenioInterior->estado == true) {
                $this->datosObtenidos[$key]['estado_vigencia'] = 'VIGENTE';
            } else {
                $this->datosObtenidos[$key]['estado_vigencia'] = 'NO VIGENTE';
            }

            $seguro_interior = SeguroInterior::find($convenioInterior->id_seguro_interior);
            $this->datosObtenidos[$key]['nombre_institucion'] = $seguro_interior->nombre_seguro;
            $this->datosObtenidos[$key]['codigo_institucional'] = null;
            $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
        } else {
            return [];
        }
    }

    public function procesarNoAsegurado($persona, $key)
    {
        $this->datosObtenidos[$key]['estado_vigencia'] = 'NO AFILIADO O NO VIGENTE';
        $this->datosObtenidos[$key]['nombre_institucion'] = null;
        $this->datosObtenidos[$key]['codigo_institucional'] = null;
        $this->datosObtenidos[$key]['institucion_beneficiario_remuneracion'] = null;
    }

    public function cargarDatos($persona)
    {
        return [
            'id_persona' => $persona->id,
            'ci' => $persona->ci,
            'complemento' => $persona->complemento,
            'matricula_seguro' => $persona->matricula_seguro,
            'nombres' => $persona->nombres,
            'p_apellido' => $persona->p_apellido,
            's_apellido' => $persona->s_apellido,
            'sexo' => $persona->sexo,
            'fecha_nacimiento' => $persona->fecha_nacimiento,
            'tipo_asegurado' => optional(TipoAsegurado::find($persona->id_tipo_asegurado))->tipo_asegurado,
        ];
    }

    // funcon principal
    public function busquedaAfiliadoKiosko($carnet, $complemento, $matricula)
    {
        if ($carnet == '') {
            $carnet = null;
        }
        if ($complemento == '') {
            $complemento = null;
        }
        if ($matricula == '') {
            $matricula = null;
        }

        //dd($carnet, $complemento, $matricula);

        if ($matricula == null && $complemento == null) {
            //dd('matricula null complemento null');
            $personas = Persona::where('ci', 'like', "$carnet%")
                ->whereNot('id_tipo_asegurado', 0)
                ->where('afiliado', true)
                ->get();
            foreach ($personas as $key => $persona) {
                $this->datosObtenidos[$key] = $this->cargarDatos($persona);
                if ($persona->id_tipo_asegurado == 1) {
                    $this->procesarTitular($persona, $key);
                } elseif ($persona->id_tipo_asegurado == 2 || $persona->id_tipo_asegurado == 3 || $persona->id_tipo_asegurado == 4 || $persona->id_tipo_asegurado == 5 || $persona->id_tipo_asegurado == 6 || $persona->id_tipo_asegurado == 7 || $persona->id_tipo_asegurado == 12 || $persona->id_tipo_asegurado == 13) {
                    $this->procesarBeneficiarios($persona, $key);
                } elseif ($persona->id_tipo_asegurado == 8) {
                    $this->procesarTitularConvenio8($persona, $key);
                } elseif ($persona->id_tipo_asegurado == 9 || $persona->id_tipo_asegurado == 10) {
                    $this->procesarTitularBeneficiarioInterior($persona, $key);
                } elseif ($persona->id_tipo_asegurado == 11) {
                    $this->procesarEStudiante($persona, $key);
                } elseif ($persona->id_tipo_asegurado == 0) {
                    $this->procesarNoAsegurado($persona, $key);
                }
            }

            return $this->datosObtenidos;
        } elseif ($matricula == null && $complemento != null) {
            //dd('matricula null complemento no null');
            $personas = Persona::where('ci', 'like', "$carnet%")
                ->where('complemento', 'like', "$complemento%")
                ->whereNot('id_tipo_asegurado', 0)
                ->where('afiliado', true)
                //->orWhere('matricula_seguro', 'like', "$carnet%")
                ->get();
            foreach ($personas as $key => $persona) {
                $this->datosObtenidos[$key] = $this->cargarDatos($persona);
                if ($persona->id_tipo_asegurado == 1) {
                    $this->procesarTitular($persona, $key);
                } elseif ($persona->id_tipo_asegurado == 2 || $persona->id_tipo_asegurado == 3 || $persona->id_tipo_asegurado == 4 || $persona->id_tipo_asegurado == 5 || $persona->id_tipo_asegurado == 6 || $persona->id_tipo_asegurado == 7 || $persona->id_tipo_asegurado == 12 || $persona->id_tipo_asegurado == 13) {
                    $this->procesarBeneficiarios($persona, $key);
                } elseif ($persona->id_tipo_asegurado == 8) {
                    $this->procesarTitularConvenio8($persona, $key);
                } elseif ($persona->id_tipo_asegurado == 9 || $persona->id_tipo_asegurado == 10) {
                    $this->procesarTitularBeneficiarioInterior($persona, $key);
                } elseif ($persona->id_tipo_asegurado == 11) {
                    $this->procesarEStudiante($persona, $key);
                } elseif ($persona->id_tipo_asegurado == 0) {
                    $this->procesarNoAsegurado($persona, $key);
                }
            }

            return $this->datosObtenidos;
        } elseif ($carnet == null && $complemento == null) {
            //dd('carnet null complemento null');
            $personas = Persona::where('matricula_seguro', 'like', "$matricula%")
                ->whereNot('id_tipo_asegurado', 0)
                ->where('afiliado', true)
                ->get();
            foreach ($personas as $key => $persona) {
                $this->datosObtenidos[$key] = $this->cargarDatos($persona);
                if ($persona->id_tipo_asegurado == 1) {
                    $this->procesarTitular($persona, $key);
                } elseif ($persona->id_tipo_asegurado == 2 || $persona->id_tipo_asegurado == 3 || $persona->id_tipo_asegurado == 4 || $persona->id_tipo_asegurado == 5 || $persona->id_tipo_asegurado == 6 || $persona->id_tipo_asegurado == 7 || $persona->id_tipo_asegurado == 12 || $persona->id_tipo_asegurado == 13) {
                    $this->procesarBeneficiarios($persona, $key);
                } elseif ($persona->id_tipo_asegurado == 8) {
                    $this->procesarTitularConvenio8($persona, $key);
                } elseif ($persona->id_tipo_asegurado == 9 || $persona->id_tipo_asegurado == 10) {
                    $this->procesarTitularBeneficiarioInterior($persona, $key);
                } elseif ($persona->id_tipo_asegurado == 11) {
                    $this->procesarEStudiante($persona, $key);
                } elseif ($persona->id_tipo_asegurado == 0) {
                    $this->procesarNoAsegurado($persona, $key);
                }
            }

            return $this->datosObtenidos;
        } elseif ($carnet != null && $matricula != null && $complemento == null) {
            //dd('carnet no null matricula no null complemento null');
            $personas = Persona::where('ci', 'like', "$carnet%")
                //->orWhere('ci', 'like', "$matricula%")
                //->orWhere('matricula_seguro', 'like', "$carnet%")
                ->where('matricula_seguro', 'like', "$matricula%")
                ->whereNot('id_tipo_asegurado', 0)
                ->where('afiliado', true)
                ->get();
            foreach ($personas as $key => $persona) {
                $this->datosObtenidos[$key] = $this->cargarDatos($persona);
                if ($persona->id_tipo_asegurado == 1) {
                    $this->procesarTitular($persona, $key);
                } elseif ($persona->id_tipo_asegurado == 2 || $persona->id_tipo_asegurado == 3 || $persona->id_tipo_asegurado == 4 || $persona->id_tipo_asegurado == 5 || $persona->id_tipo_asegurado == 6 || $persona->id_tipo_asegurado == 7 || $persona->id_tipo_asegurado == 12 || $persona->id_tipo_asegurado == 13) {
                    $this->procesarBeneficiarios($persona, $key);
                } elseif ($persona->id_tipo_asegurado == 8) {
                    $this->procesarTitularConvenio8($persona, $key);
                } elseif ($persona->id_tipo_asegurado == 9 || $persona->id_tipo_asegurado == 10) {
                    $this->procesarTitularBeneficiarioInterior($persona, $key);
                } elseif ($persona->id_tipo_asegurado == 11) {
                    $this->procesarEStudiante($persona, $key);
                } elseif ($persona->id_tipo_asegurado == 0) {
                    $this->procesarNoAsegurado($persona, $key);
                }
            }

            return $this->datosObtenidos;
        } elseif ($carnet != null && $matricula != null && $complemento != null) {
            //dd('carnet no null matricula no null complemento no null');
            $personas = Persona::where('ci', 'like', "$carnet%")
                ->where('complemento', 'like', "$complemento%")
                ->orWhere('ci', 'like', "$matricula%")
                ->orWhere('matricula_seguro', 'like', "$carnet%")
                ->orWhere('matricula_seguro', 'like', "$matricula%")
                ->get();
            foreach ($personas as $key => $persona) {
                $this->datosObtenidos[$key] = $this->cargarDatos($persona);
                if ($persona->id_tipo_asegurado == 1) {
                    $this->procesarTitular($persona, $key);
                } elseif ($persona->id_tipo_asegurado == 2 || $persona->id_tipo_asegurado == 3 || $persona->id_tipo_asegurado == 4 || $persona->id_tipo_asegurado == 5 || $persona->id_tipo_asegurado == 6 || $persona->id_tipo_asegurado == 7 || $persona->id_tipo_asegurado == 12 || $persona->id_tipo_asegurado == 13) {
                    $this->procesarBeneficiarios($persona, $key);
                } elseif ($persona->id_tipo_asegurado == 8) {
                    $this->procesarTitularConvenio8($persona, $key);
                } elseif ($persona->id_tipo_asegurado == 9 || $persona->id_tipo_asegurado == 10) {
                    $this->procesarTitularBeneficiarioInterior($persona, $key);
                } elseif ($persona->id_tipo_asegurado == 11) {
                    $this->procesarEStudiante($persona, $key);
                } elseif ($persona->id_tipo_asegurado == 0) {
                    $this->procesarNoAsegurado($persona, $key);
                }
            }

            return $this->datosObtenidos;
        } else {
            //dd('raro');
            return [];
        }
    }

    //  PRA BUSCAR POR ID EN KIOSKO
    public function busquedaAfiliadoPorIdKiosko($idPersonita)
    {
        $persona = Persona::find($idPersonita);
        $this->datosObtenidos['id_persona'] = $persona->id;
        $this->datosObtenidos['ci'] = $persona->ci;
        $this->datosObtenidos['complemento'] = $persona->complemento;
        $this->datosObtenidos['matricula_seguro'] = $persona->matricula_seguro;
        $this->datosObtenidos['nombres'] = $persona->nombres;
        $this->datosObtenidos['p_apellido'] = $persona->p_apellido;
        $this->datosObtenidos['s_apellido'] = $persona->s_apellido;
        $this->datosObtenidos['sexo'] = $persona->sexo;
        $this->datosObtenidos['fecha_nacimiento'] = $persona->fecha_nacimiento;
        $tipo_asegurado = TipoAsegurado::find($persona->id_tipo_asegurado);
        $this->datosObtenidos['tipo_asegurado'] = $tipo_asegurado->tipo_asegurado;
        if ($persona->id_tipo_asegurado == 1) {
            $datosTitular = Titular::where('id_persona', $persona->id)
                ->where('estado', true)
                ->first();
            $this->datosObtenidos['estado_vigencia'] = $datosTitular->estado_vigencia;
            $liga = 'Afiliacion/FOTOS/';
            //dd($datosTitular->foto_nombre);
            $this->datosObtenidos['foto'] = $this->obtenerImagenBase64($datosTitular->foto_nombre, $liga);
            $institucionTitular = TitularInstitucion::where('id_titular', $datosTitular->id)
                ->where('estado', true)
                ->orderBy('id', 'asc')
                ->first();
            if ($institucionTitular) {
                $datosInstitucion = Institucion::find($institucionTitular->id_institucion);
                $this->datosObtenidos['nombre_institucion'] = $datosInstitucion->nombre;
                $codigoInstitucionTitular = CodigoInstitucionSsu::where('id_institucion', $institucionTitular->id_institucion)->first();
                $this->datosObtenidos['codigo_institucional'] = ($codigoInstitucionTitular != null) ? $codigoInstitucionTitular->codigo.$codigoInstitucionTitular->tipo_titular : null;
                $this->datosObtenidos['institucion_beneficiario_remuneracion'] = null;
            } else {
                $this->datosObtenidos['nombre_institucion'] = 'AFILIADO VOLUNTARIO';
                $this->datosObtenidos['codigo_institucional'] = null;
                $this->datosObtenidos['institucion_beneficiario_remuneracion'] = null;
            }
        } elseif (
            $persona->id_tipo_asegurado == 2 || $persona->id_tipo_asegurado == 3 || $persona->id_tipo_asegurado == 4 || $persona->id_tipo_asegurado == 5 || $persona->id_tipo_asegurado == 6 || $persona->id_tipo_asegurado == 7
            || $persona->id_tipo_asegurado == 12 || $persona->id_tipo_asegurado == 13
        ) {
            $datosBeneficiario = Beneficiario::where('id_persona', $persona->id)
                ->orderBy('id', 'desc')
                ->first();
            $this->datosObtenidos['estado_vigencia'] = $datosBeneficiario->vigencia;
            $liga = 'Afiliacion/FOTOS/';
            $this->datosObtenidos['foto'] = $this->obtenerImagenBase64($datosBeneficiario->foto_nombre, $liga);
            $institucionTitular = TitularInstitucion::where('id_titular', $datosBeneficiario->id_titular)
                //  ->where('tipo_institucion', 'PATRONAL')
                ->where('estado', true)
                ->first();
            if ($institucionTitular) {
                $datosInstitucion = Institucion::find($institucionTitular->id_institucion);
                $this->datosObtenidos['nombre_institucion'] = $datosInstitucion->nombre;
                $codigoInstitucionTitular = CodigoInstitucionSsu::where('id_institucion', $institucionTitular->id_institucion)->first();
                $this->datosObtenidos['codigo_institucional'] = ($codigoInstitucionTitular != null) ? $codigoInstitucionTitular->codigo.$codigoInstitucionTitular->tipo_beneficiario : null;
                $esRemuneracion = BeneficiarioInstitucion::where('id_beneficiario', $datosBeneficiario->id)
                    ->where('id_titular', $datosBeneficiario->id_titular)
                    ->where('estado', true)
                    ->orderBy('id', 'desc')
                    ->first();
                if ($esRemuneracion) {
                    $institucionRemuneracion = Institucion::find($esRemuneracion->id_institucion);
                    $this->datosObtenidos['institucion_beneficiario_remuneracion'] = $institucionRemuneracion->nombre;
                } else {
                    $this->datosObtenidos['institucion_beneficiario_remuneracion'] = null;
                }
            } else {
                $this->datosObtenidos['nombre_institucion'] = 'AFILIADO VOLUNTARIO';
                $this->datosObtenidos['codigo_institucional'] = null;
                $this->datosObtenidos['institucion_beneficiario_remuneracion'] = null;
            }
        } elseif ($persona->id_tipo_asegurado == 8) {
            $convenioEmergencia = Convenio::where('id_persona', $persona->id)
                ->orderBy('id', 'desc')
                ->first();
            $liga = 'Vigencia/FOTOS/CONVENIO/';
            $this->datosObtenidos['foto'] = $this->obtenerImagenBase64($convenioEmergencia->foto_nombre, $liga);
            if ($convenioEmergencia->estado == true) {
                $this->datosObtenidos['estado_vigencia'] = 'VIGENTE';
            } else {
                $this->datosObtenidos['estado_vigencia'] = 'NO VIGENTE';
            }
            $this->datosObtenidos['nombre_institucion'] = 'CONVENIO EMERGENCIA';
            $this->datosObtenidos['codigo_institucional'] = null;
            $this->datosObtenidos['institucion_beneficiario_remuneracion'] = null;
        } elseif ($persona->id_tipo_asegurado == 9 || $persona->id_tipo_asegurado == 10) {
            //dd($persona);
            $convenioInterior = AutorizacionInterior::where('id_persona_interior', $persona->id)
                ->orderBy('id', 'desc')
                ->first();
            $liga = 'Vigencia/FOTOS/CONVENIO/';
            $this->datosObtenidos['foto'] = $this->obtenerImagenBase64($convenioInterior->foto_nombre, $liga);
            if ($convenioInterior->estado == true) {
                $this->datosObtenidos['estado_vigencia'] = 'VIGENTE';
            } else {
                $this->datosObtenidos['estado_vigencia'] = 'NO VIGENTE';
            }

            $seguro_interior = SeguroInterior::find($convenioInterior->id_seguro_interior);
            $this->datosObtenidos['nombre_institucion'] = $seguro_interior->nombre_seguro;
            $this->datosObtenidos['codigo_institucional'] = null;
            $this->datosObtenidos['institucion_beneficiario_remuneracion'] = null;
        } elseif ($persona->id_tipo_asegurado == 11) {
            $estudiante = Estudiante::where('id_persona', $persona->id)
                ->orderBy('id', 'desc')
                ->first();
            //dd($estudiante, $persona->id);
            $liga = 'Afiliacion/SSUE/FOTOS/';
            //dd($estudiante);
            $this->datosObtenidos['foto'] = $this->obtenerImagenBase64($estudiante->foto_nombre, $liga);
            if ($estudiante->estado_vigencia == true) {
                $this->datosObtenidos['estado_vigencia'] = 'VIGENTE';
            } else {
                $this->datosObtenidos['estado_vigencia'] = 'NO VIGENTE';
            }
            $this->datosObtenidos['nombre_institucion'] = 'APORTE UMSA ESTUDIANTE';
            $this->datosObtenidos['codigo_institucional'] = null;
            $this->datosObtenidos['institucion_beneficiario_remuneracion'] = null;
        } elseif ($persona->id_tipo_asegurado == 14) {
            $datosPreafiliado = Preafiliacion::with(['institucionPreafiliacion.codigosInstitucion'])
                ->where('estado', true)
                ->where('id_persona', $persona->id)
                ->orderBy('id', 'desc')
                ->first();

            $this->datosObtenidos['foto'] = null;
            $this->datosObtenidos['estado_vigencia'] = 'VIGENTE';
            $this->datosObtenidos['nombre_institucion'] = $datosPreafiliado->institucionPreafiliacion ? $datosPreafiliado->institucionPreafiliacion->nombre : 'AFILIADO VOLUNTARIO';
            $this->datosObtenidos['codigo_institucional'] = $datosPreafiliado->institucionPreafiliacion ? ($datosPreafiliado->institucionPreafiliacion->codigosInstitucion ?
                $datosPreafiliado->institucionPreafiliacion->codigosInstitucion->codigo.$datosPreafiliado->institucionPreafiliacion->codigosInstitucion->tipo_titular : null) : null;
            $this->datosObtenidos['institucion_beneficiario_remuneracion'] = null;
        } elseif ($persona->id_tipo_asegurado == 15) {
            $datosPreafiliado = Preafiliacion::with(['institucionPreafiliacion.codigosInstitucion'])
                ->where('estado', true)
                ->where('id_persona', $persona->id)
                ->orderBy('id', 'desc')
                ->first();

            $this->datosObtenidos['foto'] = null;
            $this->datosObtenidos['estado_vigencia'] = 'VIGENTE';
            $this->datosObtenidos['nombre_institucion'] = $datosPreafiliado->institucionPreafiliacion ? $datosPreafiliado->institucionPreafiliacion->nombre : 'AFILIADO VOLUNTARIO';
            $this->datosObtenidos['codigo_institucional'] = $datosPreafiliado->institucionPreafiliacion ? ($datosPreafiliado->institucionPreafiliacion->codigosInstitucion ?
                $datosPreafiliado->institucionPreafiliacion->codigosInstitucion->codigo.$datosPreafiliado->institucionPreafiliacion->codigosInstitucion->tipo_titular : null) : null;
            $this->datosObtenidos['institucion_beneficiario_remuneracion'] = null;
        } elseif ($persona->id_tipo_asegurado == 0) {
            $this->datosObtenidos['foto'] = null;
            $this->datosObtenidos['estado_vigencia'] = 'NO AFILIADO O NO VIGENTE';
            $this->datosObtenidos['nombre_institucion'] = null;
            $this->datosObtenidos['codigo_institucional'] = null;
            $this->datosObtenidos['institucion_beneficiario_remuneracion'] = null;
        }

        return $this->datosObtenidos;
    }
    //
}
