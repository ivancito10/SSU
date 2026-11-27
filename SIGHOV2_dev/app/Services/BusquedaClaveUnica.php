<?php

namespace App\Services;

use App\Models\Administracion\Persona;
use App\Models\Administracion\Departamento;
use App\Models\Administracion\TipoAsegurado;

use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\BeneficiarioInstitucion;
use App\Models\Afiliacion\Estudiante;
use App\Models\Aportes\Institucion;
use App\Models\Vigencia\Convenio;
use App\Models\Vigencia\AutorizacionInterior;
use App\Models\Vigencia\HistorialAsegurado;
use App\Models\Vigencia\Preafiliacion;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;

class BusquedaClaveUnica
{
    public function datosPorClaveUnica($clave)
    {
        $datosPersona = [];
        $persona = Persona::where('clave_unica', $clave)->first();

        if ($persona) {
            if ($persona->afiliado == true && $persona->id_tipo_asegurado != 0) {
                $datosPersona['id'] = $persona->id;
                if ($persona->complemento == null) {
                    $datosPersona['ci'] = $persona->ci;
                } else {
                    $datosPersona['ci'] = $persona->ci . ' - ' . $persona->complemento;
                }
                $datosPersona['sexo'] = $persona->sexo == 'F' ? 'FEMENINO' : 'MASCULINO';
                $datosPersona['clave_unica'] = $persona->clave_unica;
                $datosPersona['matricula_seguro'] = $persona->matricula_seguro;
                if ($persona->p_apellido == null) {
                    $datosPersona['nombreCompleto'] = $persona->s_apellido . ' ' . $persona->nombres;
                } elseif ($persona->s_apellido == null) {
                    $datosPersona['nombreCompleto'] = $persona->p_apellido . ' ' . $persona->nombres;
                } else {
                    $datosPersona['nombreCompleto'] = $persona->p_apellido . ' ' . $persona->s_apellido . ' ' . $persona->nombres;
                }
                $datosPersona['nombres'] = $persona->nombres;
                $datosPersona['p_apellido'] = $persona->p_apellido;
                $datosPersona['s_apellido'] = $persona->s_apellido;
                $datosPersona['es_extranjero'] = $persona->es_extranjero;
                $datosPersona['grupo_sanguineo'] = $persona->grupo_sanguineo;
                $datosPersona['alergias'] = $persona->alergias;
                $datosPersona['es_extranjeroText'] = $persona->es_extranjero ? 'EXTRANJERO' : 'NACIONAL';
                $datosPersona['id_dept_exp'] = $persona->id_dept_exp ? $persona->id_dept_exp : null;
                if ($persona->id_dept_exp == null) {
                    $datosPersona['dept_exp'] = null;
                } else {
                    $departamentoExp = Departamento::find($persona->id_dept_exp);
                    $datosPersona['dept_exp'] = $departamentoExp->sigla;
                }

                $datosPersona['fecha_nacimiento'] = $persona->fecha_nacimiento ? $persona->fecha_nacimiento : null;
                $datosPersona['edad'] = $persona->fecha_nacimiento ? Carbon::parse($persona->fecha_nacimiento)->age : null;
                $datosPersona['id_tipo_asegurado'] = $persona->id_tipo_asegurado ? $persona->id_tipo_asegurado : null;

                if ($persona->id_tipo_asegurado == null) {
                    $datosPersona['tipo_asegurado'] = null;
                } else {
                    $tipoAsegurado = TipoAsegurado::find($persona->id_tipo_asegurado);
                    $datosPersona['tipo_asegurado'] = $tipoAsegurado->tipo_asegurado;
                }

                //PARA BUSCAR LOS DATOS POR TIPO DE AFILIADO
                if ($persona->id_tipo_asegurado == 1) {
                    $titular = Titular::where('id_persona', $persona->id)->orderBy('id', 'desc')->first();
                    if ($titular) {
                        $datosPersona['foto'] = $this->obtenerImagenBase64($titular->foto_nombre);
                        $datosPersona['estado_vigencia'] = $titular->estado_vigencia;
                        $titularInstitucion = TitularInstitucion::where('id_titular', $titular->id)->where('estado', true)->orderBy('id', 'asc')
                            ->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])
                            ->first();
                        if ($titularInstitucion) {
                            $datosPersona['nombre_institucion'] = $titularInstitucion->titularInstitucionConInstitucion->nombre;
                            $datosPersona['tipo_institucion'] = $titularInstitucion->tipo_institucion;
                            $datosPersona['codigo_titular'] = $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular : null;
                            $datosPersona['codigo_beneficiario'] = $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_beneficiario : null;
                        } else {
                            $datosPersona['nombre_institucion'] = 'APORTANTE VOLUNTARIO';
                            $datosPersona['tipo_institucion'] = 'VOLUNTARIO';
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        }
                    } else {
                        $datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }
                    //dd($datosPersona);
                } elseif (
                    $persona->id_tipo_asegurado == 2 || $persona->id_tipo_asegurado == 3 || $persona->id_tipo_asegurado == 4 ||
                    $persona->id_tipo_asegurado == 5 || $persona->id_tipo_asegurado == 6 || $persona->id_tipo_asegurado == 7 || $persona->id_tipo_asegurado == 12 || $persona->id_tipo_asegurado == 13
                ) {

                    $beneficiario = Beneficiario::where('id_persona', $persona->id)->orderBy('id', 'desc')->first();
                    if ($beneficiario) {
                        $datosPersona['foto'] = $this->obtenerImagenBase64($beneficiario->foto_nombre);
                        $datosPersona['estado_vigencia'] = $beneficiario->vigencia;

                        //PARA ENCONTRAR EL CODIGO INSTITUCIONAL
                        $titularBeneficiario = Titular::where('id', $beneficiario->id_titular)->first();
                        $titularBeneficiarioInstitucion = TitularInstitucion::where('id_titular', $titularBeneficiario->id)->where('estado', true)->orderBy('id', 'asc')
                            ->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])
                            ->first();
                        if ($titularBeneficiarioInstitucion) {
                            $datosPersona['nombre_institucion'] = $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->nombre;
                            $datosPersona['tipo_institucion'] = $titularBeneficiarioInstitucion->tipo_institucion;
                            $datosPersona['codigo_titular'] = $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular : null;
                            $datosPersona['codigo_beneficiario'] = $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_beneficiario : null;
                        } else {
                            $datosPersona['nombre_institucion'] = 'APORTANTE VOLUNTARIO';
                            $datosPersona['tipo_institucion'] = 'VOLUNTARIO';
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        }

                        //---------------------------------


                        $beneficiarioConRemuneracion = BeneficiarioInstitucion::where('id_beneficiario', $beneficiario->id)->where('estado', true)->orderBy('id', 'asc')
                            ->with(['institucionBeneficiarioInstitucion'])
                            ->first();
                        if ($beneficiarioConRemuneracion) {
                            $datosPersona['nombre_institucion'] = $beneficiarioConRemuneracion->institucionBeneficiarioInstitucion->nombre;
                            $datosPersona['tipo_institucion'] = 'BENEFICIARIO CON REMUNERACIÓN';
                        }
                    } else {
                        $datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }
                } elseif ($persona->id_tipo_asegurado == 8) {
                    $convenioEmergencia = Convenio::where('id_persona', $persona->id)->where('estado', true)->where('estado_validez', 'VIGENTE')->first();
                    if ($convenioEmergencia) {
                        $datosPersona['foto'] = $this->obtenerImagenBase64Convenio($convenioEmergencia->foto_nombre);
                        $datosPersona['estado_vigencia'] = 'VIGENTE';
                        $titularAutoriza = Titular::where('id', $convenioEmergencia->id_titular_autoriza)->with(['titularPersona'])->first();
                        if ($titularAutoriza) {
                            $nombreTitularAutoriza = '';
                            if ($titularAutoriza->titularPersona->p_apellido == null) {
                                $nombreTitularAutoriza = $titularAutoriza->titularPersona->s_apellido . ' ' . $titularAutoriza->titularPersona->nombres;
                            } elseif ($titularAutoriza->titularPersona->s_apellido == null) {
                                $nombreTitularAutoriza = $titularAutoriza->titularPersona->p_apellido . ' ' . $titularAutoriza->titularPersona->nombres;
                            } else {
                                $nombreTitularAutoriza = $titularAutoriza->titularPersona->p_apellido . ' ' . $titularAutoriza->titularPersona->s_apellido . ' ' . $titularAutoriza->titularPersona->nombres;
                            }
                            $datosPersona['nombre_institucion'] = $nombreTitularAutoriza;
                            $datosPersona['tipo_institucion'] = 'CONVENIO DE EMERGENCIA';
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        } else {
                            $datosPersona['nombre_institucion'] = null;
                            $datosPersona['tipo_institucion'] = 'CONVENIO DE EMERGENCIA';
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        }
                    } else {
                        $datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }
                } elseif ($persona->id_tipo_asegurado == 9 || $persona->id_tipo_asegurado == 10) {
                    $autorizacionInterior = AutorizacionInterior::where('id_persona_interior', $persona->id)->where('estado', true)
                        ->with(['seguroInteriorAutorizacionInterior'])
                        ->first();

                    if ($autorizacionInterior) {
                        $datosPersona['foto'] = $this->obtenerImagenBase64Convenio($autorizacionInterior->foto_nombre);
                        $datosPersona['estado_vigencia'] = 'VIGENTE';
                        $datosPersona['nombre_institucion'] = $autorizacionInterior->seguroInteriorAutorizacionInterior->nombre_seguro;
                        $datosPersona['tipo_institucion'] = 'CONVENIO DEL INTERIOR';
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    } else {
                        $datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }
                } elseif ($persona->id_tipo_asegurado == 11) {
                    $estudiante = Estudiante::where('id_persona', $persona->id)->where('estado_vigencia', true)->first();
                    if ($estudiante) {
                        $datosPersona['foto'] = $this->obtenerImagenBase64Estudiante($estudiante->foto_nombre);
                        $datosPersona['estado_vigencia'] = 'VIGENTE';
                        $datosPersona['nombre_institucion'] = 'UMSA - ESTUDIANTE';
                        $datosPersona['tipo_institucion'] = 'ESTUDIANTE';
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    } else {
                        $datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }
                } elseif ($persona->id_tipo_asegurado == 14) {
                    $preafiliado = Preafiliacion::with(['institucionPreafiliacion.codigosInstitucion'])
                        ->where('estado', true)
                        ->where('id_persona', $persona->id)
                        ->orderBy('id', 'desc')
                        ->first();

                    if ($preafiliado) {
                        $datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = 'VIGENTE';
                        $datosPersona['nombre_institucion'] = $preafiliado->institucionPreafiliacion ? $preafiliado->institucionPreafiliacion->nombre : 'APORTANTE VOLUNTARIO';
                        $datosPersona['tipo_institucion'] = 'PREAFILIACIÓN';
                        $datosPersona['codigo_titular'] = $preafiliado->institucionPreafiliacion ? ($preafiliado->institucionPreafiliacion->codigosInstitucion ?
                            $preafiliado->institucionPreafiliacion->codigosInstitucion->codigo . $preafiliado->institucionPreafiliacion->codigosInstitucion->tipo_titular : null) : null;
                        $datosPersona['codigo_beneficiario'] = $preafiliado->institucionPreafiliacion ? ($preafiliado->institucionPreafiliacion->codigosInstitucion ?
                            $preafiliado->institucionPreafiliacion->codigosInstitucion->codigo . $preafiliado->institucionPreafiliacion->codigosInstitucion->tipo_beneficiario : null) : null;;
                    } else {
                        $datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }
                } elseif ($persona->id_tipo_asegurado == 15) {
                    $preafiliado = Preafiliacion::with(['institucionPreafiliacion.codigosInstitucion'])
                        ->where('estado', true)
                        ->where('id_persona', $persona->id)
                        ->orderBy('id', 'desc')
                        ->first();

                    if ($preafiliado) {
                        $datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = 'VIGENTE';
                        $datosPersona['nombre_institucion'] = $preafiliado->institucionPreafiliacion ? $preafiliado->institucionPreafiliacion->nombre : 'APORTANTE VOLUNTARIO';
                        $datosPersona['tipo_institucion'] = 'PREAFILIACIÓN';
                        $datosPersona['codigo_titular'] = $preafiliado->institucionPreafiliacion ? ($preafiliado->institucionPreafiliacion->codigosInstitucion ?
                            $preafiliado->institucionPreafiliacion->codigosInstitucion->codigo . $preafiliado->institucionPreafiliacion->codigosInstitucion->tipo_titular : null) : null;
                        $datosPersona['codigo_beneficiario'] = $preafiliado->institucionPreafiliacion ? ($preafiliado->institucionPreafiliacion->codigosInstitucion ?
                            $preafiliado->institucionPreafiliacion->codigosInstitucion->codigo . $preafiliado->institucionPreafiliacion->codigosInstitucion->tipo_beneficiario : null) : null;;
                    } else {
                        $datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }
                } else {
                    $datosPersona = [];
                }
            } else {
                $datosPersona = [];
            }
        } else {
            $datosPersona = [];
        }

        return $datosPersona;
    }

    public function datosPorClaveUnicaNuevo($clave)
    {
        $datosPersona = [];
        $persona = Persona::where('clave_unica', $clave)->first();
        //dd($persona);
        if ($persona) {
            if ($persona->id_tipo_asegurado != 0) {
                $datosPersona['id'] = $persona->id;
                if ($persona->complemento == null) {
                    $datosPersona['ci'] = $persona->ci;
                } else {
                    $datosPersona['ci'] = $persona->ci . ' - ' . $persona->complemento;
                }
                $datosPersona['sexo'] = $persona->sexo == 'F' ? 'FEMENINO' : 'MASCULINO';
                $datosPersona['clave_unica'] = $persona->clave_unica;
                $datosPersona['matricula_seguro'] = $persona->matricula_seguro;
                if ($persona->p_apellido == null) {
                    $datosPersona['nombreCompleto'] = $persona->s_apellido . ' ' . $persona->nombres;
                } elseif ($persona->s_apellido == null) {
                    $datosPersona['nombreCompleto'] = $persona->p_apellido . ' ' . $persona->nombres;
                } else {
                    $datosPersona['nombreCompleto'] = $persona->p_apellido . ' ' . $persona->s_apellido . ' ' . $persona->nombres;
                }
                $datosPersona['nombres'] = $persona->nombres;
                $datosPersona['p_apellido'] = $persona->p_apellido;
                $datosPersona['s_apellido'] = $persona->s_apellido;
                $datosPersona['es_extranjero'] = $persona->es_extranjero;
                $datosPersona['grupo_sanguineo'] = $persona->grupo_sanguineo;
                $datosPersona['alergias'] = $persona->alergias;
                $datosPersona['es_extranjeroText'] = $persona->es_extranjero ? 'EXTRANJERO' : 'NACIONAL';
                $datosPersona['id_dept_exp'] = $persona->id_dept_exp ? $persona->id_dept_exp : null;
                if ($persona->id_dept_exp == null) {
                    $datosPersona['dept_exp'] = null;
                } else {
                    $departamentoExp = Departamento::find($persona->id_dept_exp);
                    $datosPersona['dept_exp'] = $departamentoExp->sigla;
                }

                $datosPersona['fecha_nacimiento'] = $persona->fecha_nacimiento ? $persona->fecha_nacimiento : null;
                $datosPersona['edad'] = $persona->fecha_nacimiento ? Carbon::parse($persona->fecha_nacimiento)->age : null;
                $datosPersona['id_tipo_asegurado'] = $persona->id_tipo_asegurado ? $persona->id_tipo_asegurado : null;

                if ($persona->id_tipo_asegurado == null) {
                    $datosPersona['tipo_asegurado'] = null;
                } else {
                    $tipoAsegurado = TipoAsegurado::find($persona->id_tipo_asegurado);
                    $datosPersona['tipo_asegurado'] = $tipoAsegurado->tipo_asegurado;
                }

                //PARA BUSCAR LOS DATOS POR TIPO DE AFILIADO
                if ($persona->id_tipo_asegurado == 1) {
                    $titular = Titular::where('id_persona', $persona->id)->orderBy('id', 'desc')->first();
                    if ($titular) {
                        $datosPersona['foto'] = $this->obtenerImagenBase64($titular->foto_nombre);
                        $datosPersona['estado_vigencia'] = $titular->estado_vigencia;
                        $titularInstitucion = TitularInstitucion::where('id_titular', $titular->id)->where('estado', true)->orderBy('id', 'asc')
                            ->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])
                            ->first();
                        if ($titularInstitucion) {
                            $datosPersona['nombre_institucion'] = $titularInstitucion->titularInstitucionConInstitucion->nombre;
                            $datosPersona['tipo_institucion'] = $titularInstitucion->tipo_institucion;
                            $datosPersona['codigo_titular'] = $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular : null;
                            $datosPersona['codigo_beneficiario'] = $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_beneficiario : null;
                        } else {
                            $datosPersona['nombre_institucion'] = 'APORTANTE VOLUNTARIO';
                            $datosPersona['tipo_institucion'] = 'VOLUNTARIO';
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        }
                    } else {
                        $datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }
                    //dd($datosPersona);
                } elseif (
                    $persona->id_tipo_asegurado == 2 || $persona->id_tipo_asegurado == 3 || $persona->id_tipo_asegurado == 4 ||
                    $persona->id_tipo_asegurado == 5 || $persona->id_tipo_asegurado == 6 || $persona->id_tipo_asegurado == 7 || $persona->id_tipo_asegurado == 12 || $persona->id_tipo_asegurado == 13
                ) {

                    $beneficiario = Beneficiario::where('id_persona', $persona->id)->orderBy('id', 'desc')->first();
                    if ($beneficiario) {
                        $datosPersona['foto'] = $this->obtenerImagenBase64($beneficiario->foto_nombre);
                        $datosPersona['estado_vigencia'] = $beneficiario->vigencia;

                        //PARA ENCONTRAR EL CODIGO INSTITUCIONAL
                        $titularBeneficiario = Titular::where('id', $beneficiario->id_titular)->first();
                        $titularBeneficiarioInstitucion = TitularInstitucion::where('id_titular', $titularBeneficiario->id)->where('estado', true)->orderBy('id', 'asc')
                            ->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])
                            ->first();
                        if ($titularBeneficiarioInstitucion) {
                            $datosPersona['nombre_institucion'] = $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->nombre;
                            $datosPersona['tipo_institucion'] = $titularBeneficiarioInstitucion->tipo_institucion;
                            $datosPersona['codigo_titular'] = $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular : null;
                            $datosPersona['codigo_beneficiario'] = $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_beneficiario : null;
                        } else {
                            $datosPersona['nombre_institucion'] = 'APORTANTE VOLUNTARIO';
                            $datosPersona['tipo_institucion'] = 'VOLUNTARIO';
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        }

                        //---------------------------------


                        $beneficiarioConRemuneracion = BeneficiarioInstitucion::where('id_beneficiario', $beneficiario->id)->where('estado', true)->orderBy('id', 'asc')
                            ->with(['institucionBeneficiarioInstitucion'])
                            ->first();
                        if ($beneficiarioConRemuneracion) {
                            $datosPersona['nombre_institucion'] = $beneficiarioConRemuneracion->institucionBeneficiarioInstitucion->nombre;
                            $datosPersona['tipo_institucion'] = 'BENEFICIARIO CON REMUNERACIÓN';
                        }
                    } else {
                        $datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }
                } elseif ($persona->id_tipo_asegurado == 8) {
                    $convenioEmergencia = Convenio::where('id_persona', $persona->id)->where('estado', true)->where('estado_validez', 'VIGENTE')->first();
                    if ($convenioEmergencia) {
                        $datosPersona['foto'] = $this->obtenerImagenBase64Convenio($convenioEmergencia->foto_nombre);
                        $datosPersona['estado_vigencia'] = 'VIGENTE';
                        $titularAutoriza = Titular::where('id', $convenioEmergencia->id_titular_autoriza)->with(['titularPersona'])->first();
                        if ($titularAutoriza) {
                            $nombreTitularAutoriza = '';
                            if ($titularAutoriza->titularPersona->p_apellido == null) {
                                $nombreTitularAutoriza = $titularAutoriza->titularPersona->s_apellido . ' ' . $titularAutoriza->titularPersona->nombres;
                            } elseif ($titularAutoriza->titularPersona->s_apellido == null) {
                                $nombreTitularAutoriza = $titularAutoriza->titularPersona->p_apellido . ' ' . $titularAutoriza->titularPersona->nombres;
                            } else {
                                $nombreTitularAutoriza = $titularAutoriza->titularPersona->p_apellido . ' ' . $titularAutoriza->titularPersona->s_apellido . ' ' . $titularAutoriza->titularPersona->nombres;
                            }
                            $datosPersona['nombre_institucion'] = $nombreTitularAutoriza;
                            $datosPersona['tipo_institucion'] = 'CONVENIO DE EMERGENCIA';
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        } else {
                            $datosPersona['nombre_institucion'] = null;
                            $datosPersona['tipo_institucion'] = 'CONVENIO DE EMERGENCIA';
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        }
                    } else {
                        $datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }
                } elseif ($persona->id_tipo_asegurado == 9 || $persona->id_tipo_asegurado == 10) {
                    $autorizacionInterior = AutorizacionInterior::where('id_persona_interior', $persona->id)->where('estado', true)
                        ->with(['seguroInteriorAutorizacionInterior'])
                        ->first();

                    if ($autorizacionInterior) {
                        $datosPersona['foto'] = $this->obtenerImagenBase64Convenio($autorizacionInterior->foto_nombre);
                        $datosPersona['estado_vigencia'] = 'VIGENTE';
                        $datosPersona['nombre_institucion'] = $autorizacionInterior->seguroInteriorAutorizacionInterior->nombre_seguro;
                        $datosPersona['tipo_institucion'] = 'CONVENIO DEL INTERIOR';
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    } else {
                        $datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }
                } elseif ($persona->id_tipo_asegurado == 11) {

                    $estudiante = Estudiante::where('id_persona', $persona->id)
                        // ->where('estado_vigencia', true)
                        ->first();
                    // dd($estudiante);
                    if ($estudiante) {
                        $datosPersona['foto'] = $this->obtenerImagenBase64Estudiante($estudiante->foto_nombre);
                        if ($estudiante->estado_vigencia === true && $estudiante->estado_validar == 'AFILIADO') {
                            $vigencia = 'VIGENTE';
                        } else {
                            $vigencia = 'NO VIGENTE';
                        }
                        $datosPersona['estado_vigencia'] = $vigencia;
                        $datosPersona['nombre_institucion'] = 'UMSA - ESTUDIANTE';
                        $datosPersona['tipo_institucion'] = 'ESTUDIANTE';
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    } else {
                        $datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }
                } elseif ($persona->id_tipo_asegurado == 14) {
                    $preafiliado = Preafiliacion::with(['institucionPreafiliacion.codigosInstitucion'])
                        ->where('estado', true)
                        ->where('id_persona', $persona->id)
                        ->orderBy('id', 'desc')
                        ->first();

                    if ($preafiliado) {
                        $datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = 'VIGENTE';
                        $datosPersona['nombre_institucion'] = $preafiliado->institucionPreafiliacion ? $preafiliado->institucionPreafiliacion->nombre : 'APORTANTE VOLUNTARIO';
                        $datosPersona['tipo_institucion'] = 'PREAFILIACIÓN';
                        $datosPersona['codigo_titular'] = $preafiliado->institucionPreafiliacion ? ($preafiliado->institucionPreafiliacion->codigosInstitucion ?
                            $preafiliado->institucionPreafiliacion->codigosInstitucion->codigo . $preafiliado->institucionPreafiliacion->codigosInstitucion->tipo_titular : null) : null;
                        $datosPersona['codigo_beneficiario'] = $preafiliado->institucionPreafiliacion ? ($preafiliado->institucionPreafiliacion->codigosInstitucion ?
                            $preafiliado->institucionPreafiliacion->codigosInstitucion->codigo . $preafiliado->institucionPreafiliacion->codigosInstitucion->tipo_beneficiario : null) : null;;
                    } else {
                        $datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }
                } elseif ($persona->id_tipo_asegurado == 15) {
                    $preafiliado = Preafiliacion::with(['institucionPreafiliacion.codigosInstitucion'])
                        ->where('estado', true)
                        ->where('id_persona', $persona->id)
                        ->orderBy('id', 'desc')
                        ->first();

                    if ($preafiliado) {
                        $datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = 'VIGENTE';
                        $datosPersona['nombre_institucion'] = $preafiliado->institucionPreafiliacion ? $preafiliado->institucionPreafiliacion->nombre : 'APORTANTE VOLUNTARIO';
                        $datosPersona['tipo_institucion'] = 'PREAFILIACIÓN';
                        $datosPersona['codigo_titular'] = $preafiliado->institucionPreafiliacion ? ($preafiliado->institucionPreafiliacion->codigosInstitucion ?
                            $preafiliado->institucionPreafiliacion->codigosInstitucion->codigo . $preafiliado->institucionPreafiliacion->codigosInstitucion->tipo_titular : null) : null;
                        $datosPersona['codigo_beneficiario'] = $preafiliado->institucionPreafiliacion ? ($preafiliado->institucionPreafiliacion->codigosInstitucion ?
                            $preafiliado->institucionPreafiliacion->codigosInstitucion->codigo . $preafiliado->institucionPreafiliacion->codigosInstitucion->tipo_beneficiario : null) : null;;
                    } else {
                        $datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }
                } else {
                    $datosPersona = [];
                }
            } else {
                $datosPersona = [];
            }
        } else {
            $datosPersona = [];
        }

        return $datosPersona;
    }
    // busqueda para obtener el ultimo registro de la persona, asi no este vigente:

    public function busquedaHistorialAsegurado($clave)
    {
        //dd('locazoi');
        $datosPersona = [];
        $persona = Persona::where('clave_unica', $clave)->first();
        // dd($persona);
        if ($persona) {
            // if ($persona->afiliado == true && $persona->id_tipo_asegurado != 0) {
            $datosPersona['id'] = $persona->id;
            if ($persona->complemento == null) {
                $datosPersona['ci'] = $persona->ci;
            } else {
                $datosPersona['ci'] = $persona->ci . ' - ' . $persona->complemento;
            }
            $datosPersona['sexo'] = $persona->sexo == 'F' ? 'FEMENINO' : 'MASCULINO';
            $datosPersona['clave_unica'] = $persona->clave_unica;
            $datosPersona['matricula_seguro'] = $persona->matricula_seguro;
            if ($persona->p_apellido == null) {
                $datosPersona['nombreCompleto'] = $persona->s_apellido . ' ' . $persona->nombres;
            } elseif ($persona->s_apellido == null) {
                $datosPersona['nombreCompleto'] = $persona->p_apellido . ' ' . $persona->nombres;
            } else {
                $datosPersona['nombreCompleto'] = $persona->p_apellido . ' ' . $persona->s_apellido . ' ' . $persona->nombres;
            }
            $datosPersona['nombres'] = $persona->nombres;
            $datosPersona['p_apellido'] = $persona->p_apellido;
            $datosPersona['s_apellido'] = $persona->s_apellido;
            $datosPersona['es_extranjero'] = $persona->es_extranjero;
            $datosPersona['grupo_sanguineo'] = $persona->grupo_sanguineo;
            $datosPersona['alergias'] = $persona->alergias;
            $datosPersona['es_extranjeroText'] = $persona->es_extranjero ? 'EXTRANJERO' : 'NACIONAL';
            $datosPersona['id_dept_exp'] = $persona->id_dept_exp ? $persona->id_dept_exp : null;
            if ($persona->id_dept_exp == null) {
                $datosPersona['dept_exp'] = null;
            } else {
                $departamentoExp = Departamento::find($persona->id_dept_exp);
                $datosPersona['dept_exp'] = $departamentoExp->sigla;
            }
            $datosPersona['fecha_nacimiento'] = $persona->fecha_nacimiento ? $persona->fecha_nacimiento : null;
            $datosPersona['edad'] = $persona->fecha_nacimiento ? Carbon::parse($persona->fecha_nacimiento)->age : null;
            $datosPersona['id_tipo_asegurado'] = $persona->id_tipo_asegurado ? $persona->id_tipo_asegurado : null;

            if ($persona->id_tipo_asegurado == null) {
                $datosPersona['tipo_asegurado'] = 'NO ASEGURADO';
            } else {
                $tipoAsegurado = TipoAsegurado::find($persona->id_tipo_asegurado);
                $datosPersona['tipo_asegurado'] = $tipoAsegurado->tipo_asegurado;
            }


            $historialAsegurado = HistorialAsegurado::where('id_persona', $persona->id)
                ->with('tipoAseguradoHistorialAsegurado')
                ->where('estado', true)
                ->first();
            //  dd($historialAsegurado);
            if ($historialAsegurado) {
                if ($historialAsegurado->id_tipo_asegurado == 1) {
                    $datosPersona['ultimo_tipo_poblacion'] = 'TITULAR';
                    $datosPersona['id_corresponde'] = $historialAsegurado->id_corresponde;
                    $datosPersona['id_historial_asegurado'] = $historialAsegurado->id;
                    $datosPersona['estado_vigencia'] = $historialAsegurado->estado_vigencia;

                    $titular = Titular::find($historialAsegurado->id_corresponde);
                    //dd($titular);
                    if ($titular) {
                        $datosPersona['foto'] = $this->obtenerImagenBase64($titular->foto_nombre);
                        if ($titular->tipo_titular === true) {
                            $titularInstitucion = TitularInstitucion::where('id_titular', $titular->id)
                                ->where('estado', true)
                                ->orderBy('id', 'asc')
                                ->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])
                                ->first();
                            ///dd($titularInstitucion);
                            if ($titularInstitucion) {
                                $datosPersona['nombre_institucion'] = $titularInstitucion->titularInstitucionConInstitucion->nombre;
                                $datosPersona['tipo_institucion'] = $titularInstitucion->tipo_institucion;
                                $datosPersona['codigo_titular'] = $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                    $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                    $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular : null;
                                $datosPersona['codigo_beneficiario'] = $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                    $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                    $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_beneficiario : null;
                            } else {
                                $datosPersona['foto'] = null;
                                $datosPersona['nombre_institucion'] = null;
                                $datosPersona['tipo_institucion'] = null;
                                $datosPersona['codigo_titular'] = null;
                                $datosPersona['codigo_beneficiario'] = null;
                            }
                        } else {
                            $datosPersona['nombre_institucion'] = 'APORTANTE VOLUNTARIO';
                            $datosPersona['tipo_institucion'] = 'VOLUNTARIO';
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        }
                    } else {
                        $datosPersona['foto'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;

                        //$datosPersona['con_remuneracion'] = $historialAsegurado->tipo_titular;
                        //$datosPersona['id_institucion_con_remuneracion'] = $historialAsegurado->tipo_titular;
                    }
                } elseif (
                    $historialAsegurado->id_tipo_asegurado == 2 || $historialAsegurado->id_tipo_asegurado == 3 || $historialAsegurado->id_tipo_asegurado == 4 ||
                    $historialAsegurado->id_tipo_asegurado == 5 || $historialAsegurado->id_tipo_asegurado == 6 || $historialAsegurado->id_tipo_asegurado == 7 ||
                    $historialAsegurado->id_tipo_asegurado == 12 || $historialAsegurado->id_tipo_asegurado == 13
                ) {
                    $datosPersona['ultimo_tipo_poblacion'] = $historialAsegurado->tipoAseguradoHistorialAsegurado->tipo_asegurado;
                    $datosPersona['id_corresponde'] = $historialAsegurado->id_corresponde;
                    $datosPersona['id_historial_asegurado'] = $historialAsegurado->id;
                    $datosPersona['estado_vigencia'] = $historialAsegurado->estado_vigencia;

                    $beneficiario = Beneficiario::find($historialAsegurado->id_corresponde);
                    if ($beneficiario) {
                        $datosPersona['foto'] = $this->obtenerImagenBase64($beneficiario->foto_nombre);

                        //PARA ENCONTRAR EL CODIGO INSTITUCIONAL
                        $titularBeneficiario = Titular::where('id', $beneficiario->id_titular)
                            // ->orderBy('id', 'asc')
                            ->first();
                        if ($titularBeneficiario->tipo_titular === true) {
                            $titularBeneficiarioInstitucion = TitularInstitucion::where('id_titular', $titularBeneficiario->id)
                                ->where('estado', true)
                                ->orderBy('id', 'asc')
                                ->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])
                                ->first();
                            if ($titularBeneficiarioInstitucion) {
                                $datosPersona['nombre_institucion'] = $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->nombre;
                                $datosPersona['tipo_institucion'] = $titularBeneficiarioInstitucion->tipo_institucion;
                                $datosPersona['codigo_titular'] = $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                    $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                    $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular : null;
                                $datosPersona['codigo_beneficiario'] = $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                    $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                    $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_beneficiario : null;
                            } else {
                                $datosPersona['nombre_institucion'] = null;
                                $datosPersona['tipo_institucion'] = null;
                                $datosPersona['codigo_titular'] = null;
                                $datosPersona['codigo_beneficiario'] = null;
                            }
                        } else {
                            $datosPersona['nombre_institucion'] = 'APORTANTE VOLUNTARIO';
                            $datosPersona['tipo_institucion'] = 'VOLUNTARIO';
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        }

                        //---------------------------------
                        // if ($beneficiario->con_remuneracin === true) {
                        //     $beneficiarioConRemuneracion = BeneficiarioInstitucion::where('id_beneficiario', $beneficiario->id)
                        //         ->where('estado', true)
                        //         ->orderBy('id', 'asc')
                        //         ->with(['institucionBeneficiarioInstitucion'])
                        //         ->first();
                        //     if ($beneficiarioConRemuneracion) {
                        //         $datosPersona['nombre_institucion_beneficiario'] = $beneficiarioConRemuneracion->institucionBeneficiarioInstitucion->nombre;
                        //         $datosPersona['tipo_institucion_beneficiario'] = 'BENEFICIARIO CON REMUNERACIÓN';
                        //     }
                        // }
                    } else {
                        $datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }
                } elseif ($historialAsegurado->id_tipo_asegurado == 11) {
                    $datosPersona['ultimo_tipo_poblacion'] = 'ESTUDIANTE';
                    $datosPersona['id_corresponde'] = $historialAsegurado->id_corresponde;
                    $datosPersona['id_historial_asegurado'] = $historialAsegurado->id;
                    $estudiante = Estudiante::where('id_persona', $persona->id)->where('estado_vigencia', true)->first();
                    if ($estudiante) {
                        $datosPersona['foto'] = $this->obtenerImagenBase64Estudiante($estudiante->foto_nombre);
                        $datosPersona['estado_vigencia'] = 'VIGENTE';
                        $datosPersona['nombre_institucion'] = 'UMSA - ESTUDIANTE';
                        $datosPersona['tipo_institucion'] = 'ESTUDIANTE';
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    } else {
                        $datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = 'NO VIGENTE';
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }
                } elseif ($historialAsegurado->id_tipo_asegurado == 8) {
                    $datosPersona['ultimo_tipo_poblacion'] = 'TITULAR CONVENIO';
                    $datosPersona['id_corresponde'] = $historialAsegurado->id_corresponde;
                    $datosPersona['id_historial_asegurado'] = $historialAsegurado->id;
                    $convenioEmergencia = Convenio::where('id_persona', $persona->id)->where('estado', true)->where('estado_validez', 'VIGENTE')->first();
                    if ($convenioEmergencia) {
                        $datosPersona['foto'] = $this->obtenerImagenBase64Convenio($convenioEmergencia->foto_nombre);
                        $datosPersona['estado_vigencia'] = 'VIGENTE';
                        $titularAutoriza = Titular::where('id', $convenioEmergencia->id_titular_autoriza)->with(['titularPersona'])->first();
                        if ($titularAutoriza) {
                            $nombreTitularAutoriza = '';
                            if ($titularAutoriza->titularPersona->p_apellido == null) {
                                $nombreTitularAutoriza = $titularAutoriza->titularPersona->s_apellido . ' ' . $titularAutoriza->titularPersona->nombres;
                            } elseif ($titularAutoriza->titularPersona->s_apellido == null) {
                                $nombreTitularAutoriza = $titularAutoriza->titularPersona->p_apellido . ' ' . $titularAutoriza->titularPersona->nombres;
                            } else {
                                $nombreTitularAutoriza = $titularAutoriza->titularPersona->p_apellido . ' ' . $titularAutoriza->titularPersona->s_apellido . ' ' . $titularAutoriza->titularPersona->nombres;
                            }
                            $datosPersona['nombre_institucion'] = $nombreTitularAutoriza;
                            $datosPersona['tipo_institucion'] = 'CONVENIO DE EMERGENCIA';
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        } else {
                            $datosPersona['nombre_institucion'] = null;
                            $datosPersona['tipo_institucion'] = 'CONVENIO DE EMERGENCIA';
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        }
                    } else {
                        $datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }
                } elseif ($historialAsegurado->id_tipo_asegurado == 9 || $historialAsegurado->id_tipo_asegurado == 10) {
                    //  dd('asdasd');
                    $datosPersona['ultimo_tipo_poblacion'] = 'INTERIOR';
                    $autorizacionInterior = AutorizacionInterior::where('id_persona_interior', $persona->id)->where('estado', true)
                        ->with(['seguroInteriorAutorizacionInterior'])
                        ->first();

                    if ($autorizacionInterior) {
                        $datosPersona['foto'] = $this->obtenerImagenBase64Convenio($autorizacionInterior->foto_nombre);
                        $datosPersona['estado_vigencia'] = 'VIGENTE';
                        $datosPersona['nombre_institucion'] = $autorizacionInterior->seguroInteriorAutorizacionInterior->nombre_seguro;
                        $datosPersona['tipo_institucion'] = 'CONVENIO DEL INTERIOR';
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    } else {
                        $datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }

                    $datosPersona['id_corresponde'] = $historialAsegurado->id_corresponde;
                    $datosPersona['id_historial_asegurado'] = $historialAsegurado->id;
                } elseif ($historialAsegurado->id_tipo_asegurado == 14) {
                    $datosPersona['ultimo_tipo_poblacion'] = 'PREAFILIADO TITULAR';
                    $datosPersona['id_corresponde'] = $historialAsegurado->id_corresponde;
                    $datosPersona['id_historial_asegurado'] = $historialAsegurado->id;
                    $preafiliado = Preafiliacion::with(['institucionPreafiliacion.codigosInstitucion'])
                        ->where('estado', true)
                        ->where('id_persona', $persona->id)
                        ->orderBy('id', 'desc')
                        ->first();

                    if ($preafiliado) {
                        $datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = 'VIGENTE';
                        $datosPersona['nombre_institucion'] = $preafiliado->institucionPreafiliacion ? $preafiliado->institucionPreafiliacion->nombre : 'APORTANTE VOLUNTARIO';
                        $datosPersona['tipo_institucion'] = 'PREAFILIACIÓN';
                        $datosPersona['codigo_titular'] = $preafiliado->institucionPreafiliacion ? ($preafiliado->institucionPreafiliacion->codigosInstitucion ?
                            $preafiliado->institucionPreafiliacion->codigosInstitucion->codigo . $preafiliado->institucionPreafiliacion->codigosInstitucion->tipo_titular : null) : null;
                        $datosPersona['codigo_beneficiario'] = $preafiliado->institucionPreafiliacion ? ($preafiliado->institucionPreafiliacion->codigosInstitucion ?
                            $preafiliado->institucionPreafiliacion->codigosInstitucion->codigo . $preafiliado->institucionPreafiliacion->codigosInstitucion->tipo_beneficiario : null) : null;;
                    } else {
                        $datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }
                } elseif ($historialAsegurado->id_tipo_asegurado == 15) {
                    $datosPersona['ultimo_tipo_poblacion'] = 'PREAFILIADO BENEFICIARIO';
                    $datosPersona['id_corresponde'] = $historialAsegurado->id_corresponde;
                    $datosPersona['id_historial_asegurado'] = $historialAsegurado->id;
                    $preafiliado = Preafiliacion::with(['institucionPreafiliacion.codigosInstitucion'])
                        ->where('estado', true)
                        ->where('id_persona', $persona->id)
                        ->orderBy('id', 'desc')
                        ->first();

                    if ($preafiliado) {
                        $datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = 'VIGENTE';
                        $datosPersona['nombre_institucion'] = $preafiliado->institucionPreafiliacion ? $preafiliado->institucionPreafiliacion->nombre : 'APORTANTE VOLUNTARIO';
                        $datosPersona['tipo_institucion'] = 'PREAFILIACIÓN';
                        $datosPersona['codigo_titular'] = $preafiliado->institucionPreafiliacion ? ($preafiliado->institucionPreafiliacion->codigosInstitucion ?
                            $preafiliado->institucionPreafiliacion->codigosInstitucion->codigo . $preafiliado->institucionPreafiliacion->codigosInstitucion->tipo_titular : null) : null;
                        $datosPersona['codigo_beneficiario'] = $preafiliado->institucionPreafiliacion ? ($preafiliado->institucionPreafiliacion->codigosInstitucion ?
                            $preafiliado->institucionPreafiliacion->codigosInstitucion->codigo . $preafiliado->institucionPreafiliacion->codigosInstitucion->tipo_beneficiario : null) : null;;
                    } else {
                        $datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }
                } else {
                    $datosPersona['ultimo_tipo_poblacion'] = null;
                    $datosPersona['id_corresponde'] =  null;
                    $datosPersona['id_historial_asegurado'] =  null;
                    $datosPersona['foto'] = null;
                    $datosPersona['estado_vigencia'] = null;
                    $datosPersona['nombre_institucion'] = null;
                    $datosPersona['tipo_institucion'] = null;
                    $datosPersona['codigo_titular'] = null;
                    $datosPersona['codigo_beneficiario'] = null;
                }
            } else {
                $datosPersona['ultimo_tipo_poblacion'] = null;
                $datosPersona['id_corresponde'] =  null;
                $datosPersona['id_historial_asegurado'] =  null;
                $datosPersona['foto'] = null;
                $datosPersona['estado_vigencia'] = null;
                $datosPersona['nombre_institucion'] = null;
                $datosPersona['tipo_institucion'] = null;
                $datosPersona['codigo_titular'] = null;
                $datosPersona['codigo_beneficiario'] = null;
            }
        } else {
            $datosPersona = [];
        }

        return $datosPersona;
    }

    public function datosPorClaveUnicaReportes($clave)
    {
        $datosPersona = [];
        $persona = Persona::where('clave_unica', $clave)->first();

        if ($persona) {
            if ($persona->afiliado == true && $persona->id_tipo_asegurado != 0) {
                //dd('entra');
                $datosPersona['id'] = $persona->id;
                if ($persona->complemento == null) {
                    $datosPersona['ci'] = $persona->ci;
                } else {
                    $datosPersona['ci'] = $persona->ci . ' - ' . $persona->complemento;
                }
                $datosPersona['sexo'] = $persona->sexo == 'F' ? 'FEMENINO' : 'MASCULINO';
                $datosPersona['clave_unica'] = $persona->clave_unica;
                $datosPersona['matricula_seguro'] = $persona->matricula_seguro;
                if ($persona->p_apellido == null) {
                    $datosPersona['nombreCompleto'] = $persona->s_apellido . ' ' . $persona->nombres;
                } elseif ($persona->s_apellido == null) {
                    $datosPersona['nombreCompleto'] = $persona->p_apellido . ' ' . $persona->nombres;
                } else {
                    $datosPersona['nombreCompleto'] = $persona->p_apellido . ' ' . $persona->s_apellido . ' ' . $persona->nombres;
                }
                $datosPersona['nombres'] = $persona->nombres;
                $datosPersona['p_apellido'] = $persona->p_apellido;
                $datosPersona['s_apellido'] = $persona->s_apellido;
                $datosPersona['es_extranjero'] = $persona->es_extranjero;
                $datosPersona['es_extranjeroText'] = $persona->es_extranjero ? 'EXTRANJERO' : 'NACIONAL';
                $datosPersona['id_dept_exp'] = $persona->id_dept_exp ? $persona->id_dept_exp : null;
                if ($persona->id_dept_exp == null) {
                    $datosPersona['dept_exp'] = null;
                } else {
                    $departamentoExp = Departamento::find($persona->id_dept_exp);
                    $datosPersona['dept_exp'] = $departamentoExp->sigla;
                }

                $datosPersona['fecha_nacimiento'] = $persona->fecha_nacimiento ? $persona->fecha_nacimiento : null;
                $datosPersona['edad'] = $persona->fecha_nacimiento ? Carbon::parse($persona->fecha_nacimiento)->age : null;
                $datosPersona['id_tipo_asegurado'] = $persona->id_tipo_asegurado ? $persona->id_tipo_asegurado : null;

                if ($persona->id_tipo_asegurado == null) {
                    $datosPersona['tipo_asegurado'] = null;
                } else {
                    $tipoAsegurado = TipoAsegurado::find($persona->id_tipo_asegurado);
                    $datosPersona['tipo_asegurado'] = $tipoAsegurado->tipo_asegurado;
                }

                //PARA BUSCAR LOS DATOS POR TIPO DE AFILIADO
                if ($persona->id_tipo_asegurado == 1) {
                    $titular = Titular::where('id_persona', $persona->id)->orderBy('id', 'desc')->first();
                    if ($titular) {
                        //$datosPersona['foto'] = $this->obtenerImagenBase64($titular->foto_nombre);
                        $datosPersona['estado_vigencia'] = $titular->estado_vigencia;
                        $titularInstitucion = TitularInstitucion::where('id_titular', $titular->id)->where('estado', true)->orderBy('id', 'asc')
                            ->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])
                            ->first();
                        if ($titularInstitucion) {
                            $datosPersona['nombre_institucion'] = $titularInstitucion->titularInstitucionConInstitucion->nombre;
                            $datosPersona['tipo_institucion'] = $titularInstitucion->tipo_institucion;
                            $datosPersona['codigo_titular'] = $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular : null;
                            $datosPersona['codigo_beneficiario'] = $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_beneficiario : null;
                        } else {
                            $datosPersona['nombre_institucion'] = 'APORTANTE VOLUNTARIO';
                            $datosPersona['tipo_institucion'] = 'VOLUNTARIO';
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        }
                    } else {
                        //$datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }
                    //dd($datosPersona);
                } elseif (
                    $persona->id_tipo_asegurado == 2 || $persona->id_tipo_asegurado == 3 || $persona->id_tipo_asegurado == 4 ||
                    $persona->id_tipo_asegurado == 5 || $persona->id_tipo_asegurado == 6 || $persona->id_tipo_asegurado == 7 ||
                    $persona->id_tipo_asegurado == 12 || $persona->id_tipo_asegurado == 13
                ) {

                    $beneficiario = Beneficiario::where('id_persona', $persona->id)->orderBy('id', 'desc')->first();
                    if ($beneficiario) {
                        //$datosPersona['foto'] = $this->obtenerImagenBase64($beneficiario->foto_nombre);
                        $datosPersona['estado_vigencia'] = $beneficiario->vigencia;

                        //PARA ENCONTRAR EL CODIGO INSTITUCIONAL
                        $titularBeneficiario = Titular::where('id', $beneficiario->id_titular)->first();
                        $titularBeneficiarioInstitucion = TitularInstitucion::where('id_titular', $titularBeneficiario->id)->where('estado', true)->orderBy('id', 'asc')
                            ->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])
                            ->first();
                        if ($titularBeneficiarioInstitucion) {
                            $datosPersona['nombre_institucion'] = $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->nombre;
                            $datosPersona['tipo_institucion'] = $titularBeneficiarioInstitucion->tipo_institucion;
                            $datosPersona['codigo_titular'] = $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular : null;
                            $datosPersona['codigo_beneficiario'] = $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_beneficiario : null;
                        } else {
                            $datosPersona['nombre_institucion'] = 'APORTANTE VOLUNTARIO';
                            $datosPersona['tipo_institucion'] = 'VOLUNTARIO';
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        }

                        //---------------------------------


                        $beneficiarioConRemuneracion = BeneficiarioInstitucion::where('id_beneficiario', $beneficiario->id)->where('estado', true)->orderBy('id', 'asc')
                            ->with(['institucionBeneficiarioInstitucion'])
                            ->first();
                        if ($beneficiarioConRemuneracion) {
                            $datosPersona['nombre_institucion'] = $beneficiarioConRemuneracion->institucionBeneficiarioInstitucion->nombre;
                            $datosPersona['tipo_institucion'] = 'BENEFICIARIO CON REMUNERACIÓN';
                        }
                    } else {
                        //$datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }
                } elseif ($persona->id_tipo_asegurado == 8) {
                    $convenioEmergencia = Convenio::where('id_persona', $persona->id)->where('estado', true)->where('estado_validez', 'VIGENTE')->first();
                    if ($convenioEmergencia) {
                        //$datosPersona['foto'] = $this->obtenerImagenBase64Convenio($convenioEmergencia->foto_nombre);
                        $datosPersona['estado_vigencia'] = 'VIGENTE';
                        $titularAutoriza = Titular::where('id', $convenioEmergencia->id_titular_autoriza)->with(['titularPersona'])->first();
                        if ($titularAutoriza) {
                            $nombreTitularAutoriza = '';
                            if ($titularAutoriza->titularPersona->p_apellido == null) {
                                $nombreTitularAutoriza = $titularAutoriza->titularPersona->s_apellido . ' ' . $titularAutoriza->titularPersona->nombres;
                            } elseif ($titularAutoriza->titularPersona->s_apellido == null) {
                                $nombreTitularAutoriza = $titularAutoriza->titularPersona->p_apellido . ' ' . $titularAutoriza->titularPersona->nombres;
                            } else {
                                $nombreTitularAutoriza = $titularAutoriza->titularPersona->p_apellido . ' ' . $titularAutoriza->titularPersona->s_apellido . ' ' . $titularAutoriza->titularPersona->nombres;
                            }
                            $datosPersona['nombre_institucion'] = $nombreTitularAutoriza;
                            $datosPersona['tipo_institucion'] = 'CONVENIO DE EMERGENCIA';
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        } else {
                            $datosPersona['nombre_institucion'] = null;
                            $datosPersona['tipo_institucion'] = 'CONVENIO DE EMERGENCIA';
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        }
                    } else {
                        //$datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }
                } elseif ($persona->id_tipo_asegurado == 9 || $persona->id_tipo_asegurado == 10) {
                    $autorizacionInterior = AutorizacionInterior::where('id_persona_interior', $persona->id)->where('estado', true)
                        ->with(['seguroInteriorAutorizacionInterior'])
                        ->first();

                    if ($autorizacionInterior) {
                        //$datosPersona['foto'] = $this->obtenerImagenBase64Convenio($autorizacionInterior->foto_nombre);
                        $datosPersona['estado_vigencia'] = 'VIGENTE';
                        $datosPersona['nombre_institucion'] = $autorizacionInterior->seguroInteriorAutorizacionInterior->nombre_seguro;
                        $datosPersona['tipo_institucion'] = 'CONVENIO DEL INTERIOR';
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    } else {
                        //$datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }
                } elseif ($persona->id_tipo_asegurado == 11) {
                    $estudiante = Estudiante::where('id_persona', $persona->id)->where('estado_vigencia', true)->first();
                    if ($estudiante) {
                        //$datosPersona['foto'] = $this->obtenerImagenBase64Estudiante($estudiante->foto_nombre);
                        $datosPersona['estado_vigencia'] = 'VIGENTE';
                        $datosPersona['nombre_institucion'] = 'UMSA - ESTUDIANTE';
                        $datosPersona['tipo_institucion'] = 'ESTUDIANTE';
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    } else {
                        //$datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }
                } else {
                    $datosPersona = [];
                }
            } elseif ($persona->afiliado == false && $persona->id_tipo_asegurado == 0) {
                //EN CASO QUE A LA PERSONA YA SE LE HAYA DADO DE BAJA Y SE NECESITEN LOS DATOS ANTERIORES
                $historialAsegurado = HistorialAsegurado::where('id_persona', $persona->id)->orderBy('id', 'desc')->first();
                //dd($historialAsegurado);
                if ($historialAsegurado) {

                    $datosPersona['id'] = $persona->id;
                    if ($persona->complemento == null) {
                        $datosPersona['ci'] = $persona->ci;
                    } else {
                        $datosPersona['ci'] = $persona->ci . ' - ' . $persona->complemento;
                    }
                    $datosPersona['sexo'] = $persona->sexo == 'F' ? 'FEMENINO' : 'MASCULINO';
                    $datosPersona['clave_unica'] = $persona->clave_unica;
                    $datosPersona['matricula_seguro'] = $persona->matricula_seguro;
                    if ($persona->p_apellido == null) {
                        $datosPersona['nombreCompleto'] = $persona->s_apellido . ' ' . $persona->nombres;
                    } elseif ($persona->s_apellido == null) {
                        $datosPersona['nombreCompleto'] = $persona->p_apellido . ' ' . $persona->nombres;
                    } else {
                        $datosPersona['nombreCompleto'] = $persona->p_apellido . ' ' . $persona->s_apellido . ' ' . $persona->nombres;
                    }
                    $datosPersona['nombres'] = $persona->nombres;
                    $datosPersona['p_apellido'] = $persona->p_apellido;
                    $datosPersona['s_apellido'] = $persona->s_apellido;
                    $datosPersona['es_extranjero'] = $persona->es_extranjero;
                    $datosPersona['es_extranjeroText'] = $persona->es_extranjero ? 'EXTRANJERO' : 'NACIONAL';
                    $datosPersona['id_dept_exp'] = $persona->id_dept_exp ? $persona->id_dept_exp : null;
                    if ($persona->id_dept_exp == null) {
                        $datosPersona['dept_exp'] = null;
                    } else {
                        $departamentoExp = Departamento::find($persona->id_dept_exp);
                        $datosPersona['dept_exp'] = $departamentoExp->sigla;
                    }

                    $datosPersona['fecha_nacimiento'] = $persona->fecha_nacimiento ? $persona->fecha_nacimiento : null;
                    $datosPersona['edad'] = $persona->fecha_nacimiento ? Carbon::parse($persona->fecha_nacimiento)->age : null;
                    $datosPersona['id_tipo_asegurado'] = $historialAsegurado->id_tipo_asegurado ? $historialAsegurado->id_tipo_asegurado : null;

                    if ($historialAsegurado->id_tipo_asegurado == null) {
                        $datosPersona['tipo_asegurado'] = null;
                    } else {
                        $tipoAsegurado = TipoAsegurado::find($historialAsegurado->id_tipo_asegurado);
                        $datosPersona['tipo_asegurado'] = $tipoAsegurado->tipo_asegurado;
                    }

                    //PARA BUSCAR LOS DATOS POR TIPO DE AFILIADO
                    if ($historialAsegurado->id_tipo_asegurado == 1) {
                        $titular = Titular::where('id', $historialAsegurado->id_corresponde)->first();
                        if ($titular) {
                            //$datosPersona['foto'] = $this->obtenerImagenBase64($titular->foto_nombre);
                            $datosPersona['estado_vigencia'] = $titular->estado_vigencia;
                            $titularInstitucion = TitularInstitucion::where('id_titular', $titular->id)->orderBy('id', 'asc')
                                ->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])
                                ->first();
                            if ($titularInstitucion) {
                                $datosPersona['nombre_institucion'] = $titularInstitucion->titularInstitucionConInstitucion->nombre;
                                $datosPersona['tipo_institucion'] = $titularInstitucion->tipo_institucion;
                                $datosPersona['codigo_titular'] = $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                    $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                    $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular : null;
                                $datosPersona['codigo_beneficiario'] = $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                    $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                    $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_beneficiario : null;
                            } else {
                                $datosPersona['nombre_institucion'] = 'APORTANTE VOLUNTARIO';
                                $datosPersona['tipo_institucion'] = 'VOLUNTARIO';
                                $datosPersona['codigo_titular'] = null;
                                $datosPersona['codigo_beneficiario'] = null;
                            }
                        } else {
                            //$datosPersona['foto'] = null;
                            $datosPersona['estado_vigencia'] = null;
                            $datosPersona['nombre_institucion'] = null;
                            $datosPersona['tipo_institucion'] = null;
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        }
                        //dd($datosPersona);
                    } elseif (
                        $historialAsegurado->id_tipo_asegurado == 2 || $historialAsegurado->id_tipo_asegurado == 3 || $historialAsegurado->id_tipo_asegurado == 4 ||
                        $historialAsegurado->id_tipo_asegurado == 5 || $historialAsegurado->id_tipo_asegurado == 6 || $historialAsegurado->id_tipo_asegurado == 7 ||
                        $historialAsegurado->id_tipo_asegurado == 12 || $historialAsegurado->id_tipo_asegurado == 13
                    ) {

                        $beneficiario = Beneficiario::where('id', $historialAsegurado->id_corresponde)->first();
                        if ($beneficiario) {
                            //$datosPersona['foto'] = $this->obtenerImagenBase64($beneficiario->foto_nombre);
                            $datosPersona['estado_vigencia'] = $beneficiario->vigencia;

                            //PARA ENCONTRAR EL CODIGO INSTITUCIONAL
                            $titularBeneficiario = Titular::where('id', $beneficiario->id_titular)->first();
                            $titularBeneficiarioInstitucion = TitularInstitucion::where('id_titular', $titularBeneficiario->id)->orderBy('id', 'asc')
                                ->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])
                                ->first();
                            if ($titularBeneficiarioInstitucion) {
                                $datosPersona['nombre_institucion'] = $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->nombre;
                                $datosPersona['tipo_institucion'] = $titularBeneficiarioInstitucion->tipo_institucion;
                                $datosPersona['codigo_titular'] = $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                    $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                    $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular : null;
                                $datosPersona['codigo_beneficiario'] = $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                    $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                    $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_beneficiario : null;
                            } else {
                                $datosPersona['nombre_institucion'] = 'APORTANTE VOLUNTARIO';
                                $datosPersona['tipo_institucion'] = 'VOLUNTARIO';
                                $datosPersona['codigo_titular'] = null;
                                $datosPersona['codigo_beneficiario'] = null;
                            }

                            //---------------------------------


                            $beneficiarioConRemuneracion = BeneficiarioInstitucion::where('id_beneficiario', $beneficiario->id)->orderBy('id', 'asc')
                                ->with(['institucionBeneficiarioInstitucion'])
                                ->first();
                            if ($beneficiarioConRemuneracion) {
                                $datosPersona['nombre_institucion'] = $beneficiarioConRemuneracion->institucionBeneficiarioInstitucion->nombre;
                                $datosPersona['tipo_institucion'] = 'BENEFICIARIO CON REMUNERACIÓN';
                            }
                        } else {
                            //$datosPersona['foto'] = null;
                            $datosPersona['estado_vigencia'] = null;
                            $datosPersona['nombre_institucion'] = null;
                            $datosPersona['tipo_institucion'] = null;
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        }
                    } elseif ($historialAsegurado->id_tipo_asegurado == 8) {
                        $convenioEmergencia = Convenio::where('id', $historialAsegurado->id_corresponde)->first();
                        if ($convenioEmergencia) {
                            //$datosPersona['foto'] = $this->obtenerImagenBase64Convenio($convenioEmergencia->foto_nombre);
                            $datosPersona['estado_vigencia'] = 'NO VIGENTE';
                            $titularAutoriza = Titular::where('id', $convenioEmergencia->id_titular_autoriza)->with(['titularPersona'])->first();
                            if ($titularAutoriza) {
                                $nombreTitularAutoriza = '';
                                if ($titularAutoriza->titularPersona->p_apellido == null) {
                                    $nombreTitularAutoriza = $titularAutoriza->titularPersona->s_apellido . ' ' . $titularAutoriza->titularPersona->nombres;
                                } elseif ($titularAutoriza->titularPersona->s_apellido == null) {
                                    $nombreTitularAutoriza = $titularAutoriza->titularPersona->p_apellido . ' ' . $titularAutoriza->titularPersona->nombres;
                                } else {
                                    $nombreTitularAutoriza = $titularAutoriza->titularPersona->p_apellido . ' ' . $titularAutoriza->titularPersona->s_apellido . ' ' . $titularAutoriza->titularPersona->nombres;
                                }
                                $datosPersona['nombre_institucion'] = $nombreTitularAutoriza;
                                $datosPersona['tipo_institucion'] = 'CONVENIO DE EMERGENCIA';
                                $datosPersona['codigo_titular'] = null;
                                $datosPersona['codigo_beneficiario'] = null;
                            } else {
                                $datosPersona['nombre_institucion'] = null;
                                $datosPersona['tipo_institucion'] = 'CONVENIO DE EMERGENCIA';
                                $datosPersona['codigo_titular'] = null;
                                $datosPersona['codigo_beneficiario'] = null;
                            }
                        } else {
                            //$datosPersona['foto'] = null;
                            $datosPersona['estado_vigencia'] = null;
                            $datosPersona['nombre_institucion'] = null;
                            $datosPersona['tipo_institucion'] = null;
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        }
                    } elseif ($historialAsegurado->id_tipo_asegurado == 9 || $historialAsegurado->id_tipo_asegurado == 10) {
                        $autorizacionInterior = AutorizacionInterior::where('id', $historialAsegurado->id_corresponde)
                            ->with(['seguroInteriorAutorizacionInterior'])
                            ->first();

                        if ($autorizacionInterior) {
                            //$datosPersona['foto'] = $this->obtenerImagenBase64Convenio($autorizacionInterior->foto_nombre);
                            $datosPersona['estado_vigencia'] = 'NO VIGENTE';
                            $datosPersona['nombre_institucion'] = $autorizacionInterior->seguroInteriorAutorizacionInterior->nombre_seguro;
                            $datosPersona['tipo_institucion'] = 'CONVENIO DEL INTERIOR';
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        } else {
                            //$datosPersona['foto'] = null;
                            $datosPersona['estado_vigencia'] = null;
                            $datosPersona['nombre_institucion'] = null;
                            $datosPersona['tipo_institucion'] = null;
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        }
                    } elseif ($historialAsegurado->id_tipo_asegurado == 11) {
                        $estudiante = Estudiante::where('id', $historialAsegurado->id_corresponde)->first();
                        if ($estudiante) {
                            //$datosPersona['foto'] = $this->obtenerImagenBase64Estudiante($estudiante->foto_nombre);
                            $datosPersona['estado_vigencia'] = 'NO VIGENTE';
                            $datosPersona['nombre_institucion'] = 'UMSA - ESTUDIANTE';
                            $datosPersona['tipo_institucion'] = 'ESTUDIANTE';
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        } else {
                            //$datosPersona['foto'] = null;
                            $datosPersona['estado_vigencia'] = null;
                            $datosPersona['nombre_institucion'] = null;
                            $datosPersona['tipo_institucion'] = null;
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        }
                    } elseif ($historialAsegurado->id_tipo_asegurado == 14 || $historialAsegurado->id_tipo_asegurado == 15) {
                        $preafiliadoTitular = Preafiliacion::where('id', $historialAsegurado->id_corresponde)->first();
                        if ($preafiliadoTitular) {
                            //$datosPersona['foto'] = $this->obtenerImagenBase64($titular->foto_nombre);
                            $datosPersona['estado_vigencia'] = $preafiliadoTitular->estado ? 'VIGENTE' : 'NO VIGENTE';
                            $institucionPreafiliado = Institucion::with(['codigosInstitucion'])->find($preafiliadoTitular->id_institucion);
                            $datosPersona['nombre_institucion'] = $institucionPreafiliado->nombre;
                            $datosPersona['tipo_institucion'] = $institucionPreafiliado->tipo_institucion;
                            $datosPersona['codigo_titular'] = $institucionPreafiliado->codigosInstitucion != null ?
                                $institucionPreafiliado->codigosInstitucion->codigo .
                                $institucionPreafiliado->codigosInstitucion->tipo_titular : null;
                            $datosPersona['codigo_beneficiario'] = $institucionPreafiliado->codigosInstitucion != null ?
                                $institucionPreafiliado->codigosInstitucion->codigo .
                                $institucionPreafiliado->codigosInstitucion->tipo_beneficiario : null;
                        } else {
                            //$datosPersona['foto'] = null;
                            $datosPersona['estado_vigencia'] = null;
                            $datosPersona['nombre_institucion'] = null;
                            $datosPersona['tipo_institucion'] = null;
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        }
                    } else {
                        $datosPersona = [];
                    }
                } else {
                    $datosPersona = [];
                }
            }
        } else {
            $datosPersona = [];
        }

        return $datosPersona;
    }

    public function datosPorIdReportes($id)
    {
        $datosPersona = [];
        $persona = Persona::where('id', $id)->first();

        if ($persona) {
            if ($persona->afiliado == true && $persona->id_tipo_asegurado != 0) {
                $datosPersona['id'] = $persona->id;
                if ($persona->complemento == null) {
                    $datosPersona['ci'] = $persona->ci;
                } else {
                    $datosPersona['ci'] = $persona->ci . ' - ' . $persona->complemento;
                }
                $datosPersona['sexo'] = $persona->sexo == 'F' ? 'FEMENINO' : 'MASCULINO';
                $datosPersona['clave_unica'] = $persona->clave_unica;
                $datosPersona['matricula_seguro'] = $persona->matricula_seguro;
                if ($persona->p_apellido == null) {
                    $datosPersona['nombreCompleto'] = $persona->s_apellido . ' ' . $persona->nombres;
                } elseif ($persona->s_apellido == null) {
                    $datosPersona['nombreCompleto'] = $persona->p_apellido . ' ' . $persona->nombres;
                } else {
                    $datosPersona['nombreCompleto'] = $persona->p_apellido . ' ' . $persona->s_apellido . ' ' . $persona->nombres;
                }
                $datosPersona['nombres'] = $persona->nombres;
                $datosPersona['p_apellido'] = $persona->p_apellido;
                $datosPersona['s_apellido'] = $persona->s_apellido;
                $datosPersona['es_extranjero'] = $persona->es_extranjero;
                $datosPersona['es_extranjeroText'] = $persona->es_extranjero ? 'EXTRANJERO' : 'NACIONAL';
                $datosPersona['id_dept_exp'] = $persona->id_dept_exp ? $persona->id_dept_exp : null;
                if ($persona->id_dept_exp == null) {
                    $datosPersona['dept_exp'] = null;
                } else {
                    $departamentoExp = Departamento::find($persona->id_dept_exp);
                    $datosPersona['dept_exp'] = $departamentoExp->sigla;
                }

                $datosPersona['fecha_nacimiento'] = $persona->fecha_nacimiento ? $persona->fecha_nacimiento : null;
                $datosPersona['edad'] = $persona->fecha_nacimiento ? Carbon::parse($persona->fecha_nacimiento)->age : null;
                $datosPersona['id_tipo_asegurado'] = $persona->id_tipo_asegurado ? $persona->id_tipo_asegurado : null;

                if ($persona->id_tipo_asegurado == null) {
                    $datosPersona['tipo_asegurado'] = null;
                } else {
                    $tipoAsegurado = TipoAsegurado::find($persona->id_tipo_asegurado);
                    $datosPersona['tipo_asegurado'] = $tipoAsegurado->tipo_asegurado;
                }

                //PARA BUSCAR LOS DATOS POR TIPO DE AFILIADO
                if ($persona->id_tipo_asegurado == 1) {
                    $titular = Titular::where('id_persona', $persona->id)->orderBy('id', 'desc')->first();
                    if ($titular) {
                        //$datosPersona['foto'] = $this->obtenerImagenBase64($titular->foto_nombre);
                        $datosPersona['estado_vigencia'] = $titular->estado_vigencia;
                        $titularInstitucion = TitularInstitucion::where('id_titular', $titular->id)->where('estado', true)->orderBy('id', 'asc')
                            ->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])
                            ->first();
                        if ($titularInstitucion) {
                            $datosPersona['nombre_institucion'] = $titularInstitucion->titularInstitucionConInstitucion->nombre;
                            $datosPersona['tipo_institucion'] = $titularInstitucion->tipo_institucion;
                            $datosPersona['codigo_titular'] = $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular : null;
                            $datosPersona['codigo_beneficiario'] = $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_beneficiario : null;
                        } else {
                            $datosPersona['nombre_institucion'] = 'APORTANTE VOLUNTARIO';
                            $datosPersona['tipo_institucion'] = 'VOLUNTARIO';
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        }
                    } else {
                        //$datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }
                    //dd($datosPersona);
                } elseif (
                    $persona->id_tipo_asegurado == 2 || $persona->id_tipo_asegurado == 3 || $persona->id_tipo_asegurado == 4 ||
                    $persona->id_tipo_asegurado == 5 || $persona->id_tipo_asegurado == 6 || $persona->id_tipo_asegurado == 7 ||
                    $persona->id_tipo_asegurado == 12 || $persona->id_tipo_asegurado == 13
                ) {

                    $beneficiario = Beneficiario::where('id_persona', $persona->id)->orderBy('id', 'desc')->first();
                    if ($beneficiario) {
                        //$datosPersona['foto'] = $this->obtenerImagenBase64($beneficiario->foto_nombre);
                        $datosPersona['estado_vigencia'] = $beneficiario->vigencia;

                        //PARA ENCONTRAR EL CODIGO INSTITUCIONAL
                        $titularBeneficiario = Titular::where('id', $beneficiario->id_titular)->first();
                        $titularBeneficiarioInstitucion = TitularInstitucion::where('id_titular', $titularBeneficiario->id)->where('estado', true)->orderBy('id', 'asc')
                            ->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])
                            ->first();
                        if ($titularBeneficiarioInstitucion) {
                            $datosPersona['nombre_institucion'] = $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->nombre;
                            $datosPersona['tipo_institucion'] = $titularBeneficiarioInstitucion->tipo_institucion;
                            $datosPersona['codigo_titular'] = $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular : null;
                            $datosPersona['codigo_beneficiario'] = $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_beneficiario : null;
                        } else {
                            $datosPersona['nombre_institucion'] = 'APORTANTE VOLUNTARIO';
                            $datosPersona['tipo_institucion'] = 'VOLUNTARIO';
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        }

                        //---------------------------------


                        $beneficiarioConRemuneracion = BeneficiarioInstitucion::where('id_beneficiario', $beneficiario->id)->where('estado', true)->orderBy('id', 'asc')
                            ->with(['institucionBeneficiarioInstitucion'])
                            ->first();
                        if ($beneficiarioConRemuneracion) {
                            $datosPersona['nombre_institucion'] = $beneficiarioConRemuneracion->institucionBeneficiarioInstitucion->nombre;
                            $datosPersona['tipo_institucion'] = 'BENEFICIARIO CON REMUNERACIÓN';
                        }
                    } else {
                        //$datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }
                } elseif ($persona->id_tipo_asegurado == 8) {
                    $convenioEmergencia = Convenio::where('id_persona', $persona->id)->where('estado', true)->where('estado_validez', 'VIGENTE')->first();
                    if ($convenioEmergencia) {
                        //$datosPersona['foto'] = $this->obtenerImagenBase64Convenio($convenioEmergencia->foto_nombre);
                        $datosPersona['estado_vigencia'] = 'VIGENTE';
                        $titularAutoriza = Titular::where('id', $convenioEmergencia->id_titular_autoriza)->with(['titularPersona'])->first();
                        if ($titularAutoriza) {
                            $nombreTitularAutoriza = '';
                            if ($titularAutoriza->titularPersona->p_apellido == null) {
                                $nombreTitularAutoriza = $titularAutoriza->titularPersona->s_apellido . ' ' . $titularAutoriza->titularPersona->nombres;
                            } elseif ($titularAutoriza->titularPersona->s_apellido == null) {
                                $nombreTitularAutoriza = $titularAutoriza->titularPersona->p_apellido . ' ' . $titularAutoriza->titularPersona->nombres;
                            } else {
                                $nombreTitularAutoriza = $titularAutoriza->titularPersona->p_apellido . ' ' . $titularAutoriza->titularPersona->s_apellido . ' ' . $titularAutoriza->titularPersona->nombres;
                            }
                            $datosPersona['nombre_institucion'] = $nombreTitularAutoriza;
                            $datosPersona['tipo_institucion'] = 'CONVENIO DE EMERGENCIA';
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        } else {
                            $datosPersona['nombre_institucion'] = null;
                            $datosPersona['tipo_institucion'] = 'CONVENIO DE EMERGENCIA';
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        }
                    } else {
                        //$datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }
                } elseif ($persona->id_tipo_asegurado == 9 || $persona->id_tipo_asegurado == 10) {
                    $autorizacionInterior = AutorizacionInterior::where('id_persona_interior', $persona->id)->where('estado', true)
                        ->with(['seguroInteriorAutorizacionInterior'])
                        ->first();

                    if ($autorizacionInterior) {
                        //$datosPersona['foto'] = $this->obtenerImagenBase64Convenio($autorizacionInterior->foto_nombre);
                        $datosPersona['estado_vigencia'] = 'VIGENTE';
                        $datosPersona['nombre_institucion'] = $autorizacionInterior->seguroInteriorAutorizacionInterior->nombre_seguro;
                        $datosPersona['tipo_institucion'] = 'CONVENIO DEL INTERIOR';
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    } else {
                        //$datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }
                } elseif ($persona->id_tipo_asegurado == 11) {
                    $estudiante = Estudiante::where('id_persona', $persona->id)->where('estado_vigencia', true)->first();
                    if ($estudiante) {
                        //$datosPersona['foto'] = $this->obtenerImagenBase64Estudiante($estudiante->foto_nombre);
                        $datosPersona['estado_vigencia'] = 'VIGENTE';
                        $datosPersona['nombre_institucion'] = 'UMSA - ESTUDIANTE';
                        $datosPersona['tipo_institucion'] = 'ESTUDIANTE';
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    } else {
                        //$datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }
                } elseif ($persona->id_tipo_asegurado === 14 || $persona->id_tipo_asegurado == 15) {
                    $preafiliado = Preafiliacion::where('id_persona', $persona->id)->where('estado', true)->orderBy('id', 'desc')->first();
                    if ($preafiliado) {
                        $datosPersona['estado_vigencia'] = 'VIGENTE';
                        if ($preafiliado->id_institucion !== null) {
                            $institucionPreafiliado = Institucion::with(['codigosInstitucion'])
                                ->find($preafiliado->id_institucion);
                            if ($institucionPreafiliado) {
                                $datosPersona['nombre_institucion'] = $institucionPreafiliado->nombre;
                                $datosPersona['tipo_institucion'] = 'PREAFILIADO';
                                $datosPersona['codigo_titular'] = $institucionPreafiliado->codigosInstitucion != null ?
                                    $institucionPreafiliado->codigosInstitucion->codigo .
                                    $institucionPreafiliado->codigosInstitucion->tipo_titular : null;
                                $datosPersona['codigo_beneficiario'] = $institucionPreafiliado->codigosInstitucion != null ?
                                    $institucionPreafiliado->codigosInstitucion->codigo .
                                    $institucionPreafiliado->codigosInstitucion->tipo_beneficiario : null;
                            }
                        } else {
                            $datosPersona['nombre_institucion'] = 'APORTANTE VOLUNTARIO';
                            $datosPersona['tipo_institucion'] = 'VOLUNTARIO';
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        }
                    }
                } else {
                    $datosPersona = [];
                }
            } else {
                //EN CASO QUE A LA PERSONA YA SE LE HAYA DADO DE BAJA Y SE NECESITEN LOS DATOS ANTERIORES
                $historialAsegurado = HistorialAsegurado::where('id_persona', $persona->id)->orderBy('id', 'desc')->first();
                //dd($historialAsegurado);
                if ($historialAsegurado) {
                    $datosPersona['id'] = $persona->id;
                    if ($persona->complemento == null) {
                        $datosPersona['ci'] = $persona->ci;
                    } else {
                        $datosPersona['ci'] = $persona->ci . ' - ' . $persona->complemento;
                    }
                    $datosPersona['sexo'] = $persona->sexo == 'F' ? 'FEMENINO' : 'MASCULINO';
                    $datosPersona['clave_unica'] = $persona->clave_unica;
                    $datosPersona['matricula_seguro'] = $persona->matricula_seguro;
                    if ($persona->p_apellido == null) {
                        $datosPersona['nombreCompleto'] = $persona->s_apellido . ' ' . $persona->nombres;
                    } elseif ($persona->s_apellido == null) {
                        $datosPersona['nombreCompleto'] = $persona->p_apellido . ' ' . $persona->nombres;
                    } else {
                        $datosPersona['nombreCompleto'] = $persona->p_apellido . ' ' . $persona->s_apellido . ' ' . $persona->nombres;
                    }
                    $datosPersona['nombres'] = $persona->nombres;
                    $datosPersona['p_apellido'] = $persona->p_apellido;
                    $datosPersona['s_apellido'] = $persona->s_apellido;
                    $datosPersona['es_extranjero'] = $persona->es_extranjero;
                    $datosPersona['es_extranjeroText'] = $persona->es_extranjero ? 'EXTRANJERO' : 'NACIONAL';
                    $datosPersona['id_dept_exp'] = $persona->id_dept_exp ? $persona->id_dept_exp : null;
                    if ($persona->id_dept_exp == null) {
                        $datosPersona['dept_exp'] = null;
                    } else {
                        $departamentoExp = Departamento::find($persona->id_dept_exp);
                        $datosPersona['dept_exp'] = $departamentoExp->sigla;
                    }

                    $datosPersona['fecha_nacimiento'] = $persona->fecha_nacimiento ? $persona->fecha_nacimiento : null;
                    $datosPersona['edad'] = $persona->fecha_nacimiento ? Carbon::parse($persona->fecha_nacimiento)->age : null;
                    $datosPersona['id_tipo_asegurado'] = $historialAsegurado->id_tipo_asegurado ? $historialAsegurado->id_tipo_asegurado : null;

                    if ($historialAsegurado->id_tipo_asegurado == null) {
                        $datosPersona['tipo_asegurado'] = null;
                    } else {
                        $tipoAsegurado = TipoAsegurado::find($historialAsegurado->id_tipo_asegurado);
                        $datosPersona['tipo_asegurado'] = $tipoAsegurado->tipo_asegurado;
                    }

                    //PARA BUSCAR LOS DATOS POR TIPO DE AFILIADO
                    if ($historialAsegurado->id_tipo_asegurado == 1) {
                        $titular = Titular::where('id', $historialAsegurado->id_corresponde)->first();
                        if ($titular) {
                            //$datosPersona['foto'] = $this->obtenerImagenBase64($titular->foto_nombre);
                            $datosPersona['estado_vigencia'] = $titular->estado_vigencia;
                            $titularInstitucion = TitularInstitucion::where('id_titular', $titular->id)->orderBy('id', 'asc')
                                ->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])
                                ->first();
                            if ($titularInstitucion) {
                                $datosPersona['nombre_institucion'] = $titularInstitucion->titularInstitucionConInstitucion->nombre;
                                $datosPersona['tipo_institucion'] = $titularInstitucion->tipo_institucion;
                                $datosPersona['codigo_titular'] = $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                    $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                    $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular : null;
                                $datosPersona['codigo_beneficiario'] = $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                    $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                    $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_beneficiario : null;
                            } else {
                                $datosPersona['nombre_institucion'] = 'APORTANTE VOLUNTARIO';
                                $datosPersona['tipo_institucion'] = 'VOLUNTARIO';
                                $datosPersona['codigo_titular'] = null;
                                $datosPersona['codigo_beneficiario'] = null;
                            }
                        } else {
                            //$datosPersona['foto'] = null;
                            $datosPersona['estado_vigencia'] = null;
                            $datosPersona['nombre_institucion'] = null;
                            $datosPersona['tipo_institucion'] = null;
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        }
                        //dd($datosPersona);
                    } elseif (
                        $historialAsegurado->id_tipo_asegurado == 2 || $historialAsegurado->id_tipo_asegurado == 3 || $historialAsegurado->id_tipo_asegurado == 4 ||
                        $historialAsegurado->id_tipo_asegurado == 5 || $historialAsegurado->id_tipo_asegurado == 6 || $historialAsegurado->id_tipo_asegurado == 7 ||
                        $historialAsegurado->id_tipo_asegurado == 12 || $historialAsegurado->id_tipo_asegurado == 13
                    ) {

                        $beneficiario = Beneficiario::where('id', $historialAsegurado->id_corresponde)->first();
                        if ($beneficiario) {
                            //$datosPersona['foto'] = $this->obtenerImagenBase64($beneficiario->foto_nombre);
                            $datosPersona['estado_vigencia'] = $beneficiario->vigencia;

                            //PARA ENCONTRAR EL CODIGO INSTITUCIONAL
                            $titularBeneficiario = Titular::where('id', $beneficiario->id_titular)->first();
                            $titularBeneficiarioInstitucion = TitularInstitucion::where('id_titular', $titularBeneficiario->id)->orderBy('id', 'asc')
                                ->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])
                                ->first();
                            if ($titularBeneficiarioInstitucion) {
                                $datosPersona['nombre_institucion'] = $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->nombre;
                                $datosPersona['tipo_institucion'] = $titularBeneficiarioInstitucion->tipo_institucion;
                                $datosPersona['codigo_titular'] = $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                    $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                    $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular : null;
                                $datosPersona['codigo_beneficiario'] = $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                    $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                    $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_beneficiario : null;
                            } else {
                                $datosPersona['nombre_institucion'] = 'APORTANTE VOLUNTARIO';
                                $datosPersona['tipo_institucion'] = 'VOLUNTARIO';
                                $datosPersona['codigo_titular'] = null;
                                $datosPersona['codigo_beneficiario'] = null;
                            }

                            //---------------------------------


                            $beneficiarioConRemuneracion = BeneficiarioInstitucion::where('id_beneficiario', $beneficiario->id)->orderBy('id', 'asc')
                                ->with(['institucionBeneficiarioInstitucion'])
                                ->first();
                            if ($beneficiarioConRemuneracion) {
                                $datosPersona['nombre_institucion'] = $beneficiarioConRemuneracion->institucionBeneficiarioInstitucion->nombre;
                                $datosPersona['tipo_institucion'] = 'BENEFICIARIO CON REMUNERACIÓN';
                            }
                        } else {
                            //$datosPersona['foto'] = null;
                            $datosPersona['estado_vigencia'] = null;
                            $datosPersona['nombre_institucion'] = null;
                            $datosPersona['tipo_institucion'] = null;
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        }
                    } elseif ($historialAsegurado->id_tipo_asegurado == 8) {
                        $convenioEmergencia = Convenio::where('id', $historialAsegurado->id_corresponde)->first();
                        if ($convenioEmergencia) {
                            //$datosPersona['foto'] = $this->obtenerImagenBase64Convenio($convenioEmergencia->foto_nombre);
                            $datosPersona['estado_vigencia'] = 'NO VIGENTE';
                            $titularAutoriza = Titular::where('id', $convenioEmergencia->id_titular_autoriza)->with(['titularPersona'])->first();
                            if ($titularAutoriza) {
                                $nombreTitularAutoriza = '';
                                if ($titularAutoriza->titularPersona->p_apellido == null) {
                                    $nombreTitularAutoriza = $titularAutoriza->titularPersona->s_apellido . ' ' . $titularAutoriza->titularPersona->nombres;
                                } elseif ($titularAutoriza->titularPersona->s_apellido == null) {
                                    $nombreTitularAutoriza = $titularAutoriza->titularPersona->p_apellido . ' ' . $titularAutoriza->titularPersona->nombres;
                                } else {
                                    $nombreTitularAutoriza = $titularAutoriza->titularPersona->p_apellido . ' ' . $titularAutoriza->titularPersona->s_apellido . ' ' . $titularAutoriza->titularPersona->nombres;
                                }
                                $datosPersona['nombre_institucion'] = $nombreTitularAutoriza;
                                $datosPersona['tipo_institucion'] = 'CONVENIO DE EMERGENCIA';
                                $datosPersona['codigo_titular'] = null;
                                $datosPersona['codigo_beneficiario'] = null;
                            } else {
                                $datosPersona['nombre_institucion'] = null;
                                $datosPersona['tipo_institucion'] = 'CONVENIO DE EMERGENCIA';
                                $datosPersona['codigo_titular'] = null;
                                $datosPersona['codigo_beneficiario'] = null;
                            }
                        } else {
                            //$datosPersona['foto'] = null;
                            $datosPersona['estado_vigencia'] = null;
                            $datosPersona['nombre_institucion'] = null;
                            $datosPersona['tipo_institucion'] = null;
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        }
                    } elseif ($historialAsegurado->id_tipo_asegurado == 9 || $historialAsegurado->id_tipo_asegurado == 10) {
                        $autorizacionInterior = AutorizacionInterior::where('id', $historialAsegurado->id_corresponde)
                            ->with(['seguroInteriorAutorizacionInterior'])
                            ->first();

                        if ($autorizacionInterior) {
                            //$datosPersona['foto'] = $this->obtenerImagenBase64Convenio($autorizacionInterior->foto_nombre);
                            $datosPersona['estado_vigencia'] = 'NO VIGENTE';
                            $datosPersona['nombre_institucion'] = $autorizacionInterior->seguroInteriorAutorizacionInterior->nombre_seguro;
                            $datosPersona['tipo_institucion'] = 'CONVENIO DEL INTERIOR';
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        } else {
                            //$datosPersona['foto'] = null;
                            $datosPersona['estado_vigencia'] = null;
                            $datosPersona['nombre_institucion'] = null;
                            $datosPersona['tipo_institucion'] = null;
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        }
                    } elseif ($historialAsegurado->id_tipo_asegurado == 11) {
                        $estudiante = Estudiante::where('id', $historialAsegurado->id_corresponde)->first();
                        if ($estudiante) {
                            //$datosPersona['foto'] = $this->obtenerImagenBase64Estudiante($estudiante->foto_nombre);
                            $datosPersona['estado_vigencia'] = 'NO VIGENTE';
                            $datosPersona['nombre_institucion'] = 'UMSA - ESTUDIANTE';
                            $datosPersona['tipo_institucion'] = 'ESTUDIANTE';
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        } else {
                            //$datosPersona['foto'] = null;
                            $datosPersona['estado_vigencia'] = null;
                            $datosPersona['nombre_institucion'] = null;
                            $datosPersona['tipo_institucion'] = null;
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        }
                    } elseif ($historialAsegurado->id_tipo_asegurado == 14 || $historialAsegurado->id_tipo_asegurado == 15) {
                        $preafiliado = Preafiliacion::where('id', $historialAsegurado->id_corresponde)->first();
                        if ($preafiliado) {
                            $datosPersona['estado_vigencia'] = 'NO VIGENTE';
                            if ($preafiliado->id_institucion !== null) {
                                $institucionPreafiliado = Institucion::with(['codigosInstitucion'])
                                    ->find($preafiliado->id_institucion);
                                if ($institucionPreafiliado) {
                                    $datosPersona['nombre_institucion'] = $institucionPreafiliado->nombre;
                                    $datosPersona['tipo_institucion'] = 'PREAFILIADO';
                                    $datosPersona['codigo_titular'] = $institucionPreafiliado->codigosInstitucion != null ?
                                        $institucionPreafiliado->codigosInstitucion->codigo .
                                        $institucionPreafiliado->codigosInstitucion->tipo_titular : null;
                                    $datosPersona['codigo_beneficiario'] = $institucionPreafiliado->codigosInstitucion != null ?
                                        $institucionPreafiliado->codigosInstitucion->codigo .
                                        $institucionPreafiliado->codigosInstitucion->tipo_beneficiario : null;
                                }
                            } else {
                                $datosPersona['nombre_institucion'] = 'APORTANTE VOLUNTARIO';
                                $datosPersona['tipo_institucion'] = 'VOLUNTARIO';
                                $datosPersona['codigo_titular'] = null;
                                $datosPersona['codigo_beneficiario'] = null;
                            }
                        }
                    } else {
                        $datosPersona = [];
                    }
                } else {
                    $datosPersona = [];
                }
            }
        } else {
            $datosPersona = [];
        }

        return $datosPersona;
    }

    private function obtenerImagenBase64($filename)
    {
        try {
            if ($filename) {
                $path = 'Afiliacion/FOTOS/' . $filename;
                $imageData = Storage::disk('ftp')->get($path);
                $fotito = base64_encode($imageData);

                return $fotito;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }

    private function obtenerImagenBase64Convenio($filename)
    {
        try {
            if ($filename) {
                $path = 'Vigencia/FOTOS/CONVENIO/' . $filename;
                $imageData = Storage::disk('ftp')->get($path);
                //dd($imageData);
                $fotito = base64_encode($imageData);
                return $fotito;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }

    private function obtenerImagenBase64Estudiante($filename)
    {
        try {
            if ($filename) {
                $path = 'Afiliacion/SSUE/FOTOS/' . $filename;
                $imageData = Storage::disk('ftp')->get($path);
                //dd($imageData);
                $fotito = base64_encode($imageData);
                return $fotito;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function busquedaHistorialAseguradoNuevo($clave)
    {
        $datosPersona = [];
        $persona = Persona::where('clave_unica', $clave)->first();
        //  dd($persona);
        if ($persona) {
            // primero datos personales 
            $datosPersona['id'] = $persona->id;
            if ($persona->complemento == null) {
                $datosPersona['ci'] = $persona->ci;
            } else {
                $datosPersona['ci'] = $persona->ci . ' - ' . $persona->complemento;
            }
            $datosPersona['sexo'] = $persona->sexo == 'F' ? 'FEMENINO' : 'MASCULINO';
            $datosPersona['clave_unica'] = $persona->clave_unica;
            $datosPersona['matricula_seguro'] = $persona->matricula_seguro;
            if ($persona->p_apellido == null) {
                $datosPersona['nombreCompleto'] = $persona->s_apellido . ' ' . $persona->nombres;
            } elseif ($persona->s_apellido == null) {
                $datosPersona['nombreCompleto'] = $persona->p_apellido . ' ' . $persona->nombres;
            } else {
                $datosPersona['nombreCompleto'] = $persona->p_apellido . ' ' . $persona->s_apellido . ' ' . $persona->nombres;
            }
            $datosPersona['nombres'] = $persona->nombres;
            $datosPersona['p_apellido'] = $persona->p_apellido;
            $datosPersona['s_apellido'] = $persona->s_apellido;
            $datosPersona['es_extranjero'] = $persona->es_extranjero;
            $datosPersona['grupo_sanguineo'] = $persona->grupo_sanguineo;
            $datosPersona['alergias'] = $persona->alergias;
            $datosPersona['es_extranjeroText'] = $persona->es_extranjero ? 'EXTRANJERO' : 'NACIONAL';
            $datosPersona['id_dept_exp'] = $persona->id_dept_exp ? $persona->id_dept_exp : null;
            if ($persona->id_dept_exp == null) {
                $datosPersona['dept_exp'] = null;
            } else {
                $departamentoExp = Departamento::find($persona->id_dept_exp);
                $datosPersona['dept_exp'] = $departamentoExp->sigla;
            }
            $datosPersona['fecha_nacimiento'] = $persona->fecha_nacimiento ? $persona->fecha_nacimiento : null;
            $datosPersona['edad'] = $persona->fecha_nacimiento ? Carbon::parse($persona->fecha_nacimiento)->age : null;
            $datosPersona['id_tipo_asegurado'] = $persona->id_tipo_asegurado ? $persona->id_tipo_asegurado : null;

            if ($persona->id_tipo_asegurado == null) {
                $datosPersona['tipo_asegurado'] = 'NO ASEGURADO';
            } else {
                $tipoAsegurado = TipoAsegurado::find($persona->id_tipo_asegurado);
                $datosPersona['tipo_asegurado'] = $tipoAsegurado->tipo_asegurado;
            }
            $historialAsegurado = HistorialAsegurado::where('id_persona', $persona->id)
                ->with('tipoAseguradoHistorialAsegurado')
                ->where('estado', true)
                ->first();
           // dd('dasd',$historialAsegurado);
            if ($historialAsegurado) {
                if ($historialAsegurado->id_tipo_asegurado == 1) {
                    $datosPersona['ultimo_tipo_poblacion'] = 'TITULAR';
                    $datosPersona['id_corresponde'] = $historialAsegurado->id_corresponde;
                    $datosPersona['id_historial_asegurado'] = $historialAsegurado->id;
                    if ($datosPersona['id_tipo_asegurado'] == null) {
                        $datosPersona['estado_vigencia'] = 'NO VIGENTE';
                    } else {
                        $datosPersona['estado_vigencia'] = $historialAsegurado->estado_vigencia;
                    }

                    $titular = Titular::find($historialAsegurado->id_corresponde);
                    //dd($titular);
                    if ($titular) {
                        $datosPersona['foto'] = $this->obtenerImagenBase64($titular->foto_nombre);
                        if ($titular->tipo_titular === true) {
                            $titularInstitucion = TitularInstitucion::where('id_titular', $titular->id)
                                ->where('estado', true)
                                ->orderBy('id', 'asc')
                                ->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])
                                ->first();
                            ///dd($titularInstitucion);
                            if ($titularInstitucion) {
                                $datosPersona['nombre_institucion'] = $titularInstitucion->titularInstitucionConInstitucion->nombre;
                                $datosPersona['tipo_institucion'] = $titularInstitucion->tipo_institucion;
                                $datosPersona['codigo_titular'] = $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                    $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                    $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular : null;
                                $datosPersona['codigo_beneficiario'] = $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                    $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                    $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_beneficiario : null;
                            } else {
                                $datosPersona['foto'] = null;
                                $datosPersona['nombre_institucion'] = null;
                                $datosPersona['tipo_institucion'] = null;
                                $datosPersona['codigo_titular'] = null;
                                $datosPersona['codigo_beneficiario'] = null;
                            }
                        } else {
                            $datosPersona['nombre_institucion'] = 'APORTANTE VOLUNTARIO';
                            $datosPersona['tipo_institucion'] = 'VOLUNTARIO';
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        }
                    } else {
                        $datosPersona['foto'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;

                        //$datosPersona['con_remuneracion'] = $historialAsegurado->tipo_titular;
                        //$datosPersona['id_institucion_con_remuneracion'] = $historialAsegurado->tipo_titular;
                    }
                } elseif (
                    $historialAsegurado->id_tipo_asegurado == 2 || $historialAsegurado->id_tipo_asegurado == 3 || $historialAsegurado->id_tipo_asegurado == 4 ||
                    $historialAsegurado->id_tipo_asegurado == 5 || $historialAsegurado->id_tipo_asegurado == 6 || $historialAsegurado->id_tipo_asegurado == 7 ||
                    $historialAsegurado->id_tipo_asegurado == 12 || $historialAsegurado->id_tipo_asegurado == 13
                ) {
                    $datosPersona['ultimo_tipo_poblacion'] = $historialAsegurado->tipoAseguradoHistorialAsegurado->tipo_asegurado;
                    $datosPersona['id_corresponde'] = $historialAsegurado->id_corresponde;
                    $datosPersona['id_historial_asegurado'] = $historialAsegurado->id;
                    if ($datosPersona['id_tipo_asegurado'] == null) {
                        $datosPersona['estado_vigencia'] = 'NO VIGENTE';
                    } else {
                        $datosPersona['estado_vigencia'] = $historialAsegurado->estado_vigencia;
                    }

                    $beneficiario = Beneficiario::find($historialAsegurado->id_corresponde);
                    if ($beneficiario) {
                        $datosPersona['foto'] = $this->obtenerImagenBase64($beneficiario->foto_nombre);

                        //PARA ENCONTRAR EL CODIGO INSTITUCIONAL
                        $titularBeneficiario = Titular::where('id', $beneficiario->id_titular)
                            // ->orderBy('id', 'asc')
                            ->first();
                        if ($titularBeneficiario->tipo_titular === true) {
                            $titularBeneficiarioInstitucion = TitularInstitucion::where('id_titular', $titularBeneficiario->id)
                                ->where('estado', true)
                                ->orderBy('id', 'asc')
                                ->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])
                                ->first();
                            if ($titularBeneficiarioInstitucion) {
                                $datosPersona['nombre_institucion'] = $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->nombre;
                                $datosPersona['tipo_institucion'] = $titularBeneficiarioInstitucion->tipo_institucion;
                                $datosPersona['codigo_titular'] = $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                    $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                    $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular : null;
                                $datosPersona['codigo_beneficiario'] = $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                    $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                    $titularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_beneficiario : null;
                            } else {
                                $datosPersona['nombre_institucion'] = null;
                                $datosPersona['tipo_institucion'] = null;
                                $datosPersona['codigo_titular'] = null;
                                $datosPersona['codigo_beneficiario'] = null;
                            }
                        } else {
                            $datosPersona['nombre_institucion'] = 'APORTANTE VOLUNTARIO';
                            $datosPersona['tipo_institucion'] = 'VOLUNTARIO';
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        }

                        //---------------------------------
                        // if ($beneficiario->con_remuneracin === true) {
                        //     $beneficiarioConRemuneracion = BeneficiarioInstitucion::where('id_beneficiario', $beneficiario->id)
                        //         ->where('estado', true)
                        //         ->orderBy('id', 'asc')
                        //         ->with(['institucionBeneficiarioInstitucion'])
                        //         ->first();
                        //     if ($beneficiarioConRemuneracion) {
                        //         $datosPersona['nombre_institucion_beneficiario'] = $beneficiarioConRemuneracion->institucionBeneficiarioInstitucion->nombre;
                        //         $datosPersona['tipo_institucion_beneficiario'] = 'BENEFICIARIO CON REMUNERACIÓN';
                        //     }
                        // }
                    } else {
                        $datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }
                } elseif ($historialAsegurado->id_tipo_asegurado == 11) {
                    $datosPersona['ultimo_tipo_poblacion'] = 'ESTUDIANTE';
                    $datosPersona['id_corresponde'] = $historialAsegurado->id_corresponde;
                    $datosPersona['id_historial_asegurado'] = $historialAsegurado->id;
                    $estudiante = Estudiante::where('id_persona', $persona->id)
                        ->where('estado_vigencia', true)
                        ->where('estado', true)
                        ->first();
                    //dd($estudiante);
                    if ($estudiante) {
                        $datosPersona['foto'] = $this->obtenerImagenBase64Estudiante($estudiante->foto_nombre);
                        $datosPersona['estado_vigencia'] = 'VIGENTE';
                        $datosPersona['nombre_institucion'] = 'UMSA - ESTUDIANTE';
                        $datosPersona['tipo_institucion'] = 'ESTUDIANTE';
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    } else {
                        $datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = 'NO VIGENTE';
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }
                } elseif ($historialAsegurado->id_tipo_asegurado == 8) {
                    $datosPersona['ultimo_tipo_poblacion'] = 'TITULAR CONVENIO';
                    $datosPersona['id_corresponde'] = $historialAsegurado->id_corresponde;
                    $datosPersona['id_historial_asegurado'] = $historialAsegurado->id;
                    $convenioEmergencia = Convenio::where('id_persona', $persona->id)->where('estado', true)->where('estado_validez', 'VIGENTE')->first();
                    if ($convenioEmergencia) {
                        $datosPersona['foto'] = $this->obtenerImagenBase64Convenio($convenioEmergencia->foto_nombre);
                        $datosPersona['estado_vigencia'] = 'VIGENTE';
                        $titularAutoriza = Titular::where('id', $convenioEmergencia->id_titular_autoriza)->with(['titularPersona'])->first();
                        if ($titularAutoriza) {
                            $nombreTitularAutoriza = '';
                            if ($titularAutoriza->titularPersona->p_apellido == null) {
                                $nombreTitularAutoriza = $titularAutoriza->titularPersona->s_apellido . ' ' . $titularAutoriza->titularPersona->nombres;
                            } elseif ($titularAutoriza->titularPersona->s_apellido == null) {
                                $nombreTitularAutoriza = $titularAutoriza->titularPersona->p_apellido . ' ' . $titularAutoriza->titularPersona->nombres;
                            } else {
                                $nombreTitularAutoriza = $titularAutoriza->titularPersona->p_apellido . ' ' . $titularAutoriza->titularPersona->s_apellido . ' ' . $titularAutoriza->titularPersona->nombres;
                            }
                            $datosPersona['nombre_institucion'] = $nombreTitularAutoriza;
                            $datosPersona['tipo_institucion'] = 'CONVENIO DE EMERGENCIA';
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        } else {
                            $datosPersona['nombre_institucion'] = null;
                            $datosPersona['tipo_institucion'] = 'CONVENIO DE EMERGENCIA';
                            $datosPersona['codigo_titular'] = null;
                            $datosPersona['codigo_beneficiario'] = null;
                        }
                    } else {
                        $datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }
                } elseif ($historialAsegurado->id_tipo_asegurado == 9 || $historialAsegurado->id_tipo_asegurado == 10) {
                    //  dd('asdasd');
                    $datosPersona['ultimo_tipo_poblacion'] = 'INTERIOR';
                    $autorizacionInterior = AutorizacionInterior::where('id_persona_interior', $persona->id)->where('estado', true)
                        ->with(['seguroInteriorAutorizacionInterior'])
                        ->first();

                    if ($autorizacionInterior) {
                        $datosPersona['foto'] = $this->obtenerImagenBase64Convenio($autorizacionInterior->foto_nombre);
                        $datosPersona['estado_vigencia'] = 'VIGENTE';
                        $datosPersona['nombre_institucion'] = $autorizacionInterior->seguroInteriorAutorizacionInterior->nombre_seguro;
                        $datosPersona['tipo_institucion'] = 'CONVENIO DEL INTERIOR';
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    } else {
                        $datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }

                    $datosPersona['id_corresponde'] = $historialAsegurado->id_corresponde;
                    $datosPersona['id_historial_asegurado'] = $historialAsegurado->id;
                } elseif ($historialAsegurado->id_tipo_asegurado == 14) {
                    $datosPersona['ultimo_tipo_poblacion'] = 'PREAFILIADO TITULAR';
                    $datosPersona['id_corresponde'] = $historialAsegurado->id_corresponde;
                    $datosPersona['id_historial_asegurado'] = $historialAsegurado->id;
                    $preafiliado = Preafiliacion::with(['institucionPreafiliacion.codigosInstitucion'])
                        ->where('estado', true)
                        ->where('id_persona', $persona->id)
                        ->orderBy('id', 'desc')
                        ->first();

                    if ($preafiliado) {
                        $datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = 'VIGENTE';
                        $datosPersona['nombre_institucion'] = $preafiliado->institucionPreafiliacion ? $preafiliado->institucionPreafiliacion->nombre : 'APORTANTE VOLUNTARIO';
                        $datosPersona['tipo_institucion'] = 'PREAFILIACIÓN';
                        $datosPersona['codigo_titular'] = $preafiliado->institucionPreafiliacion ? ($preafiliado->institucionPreafiliacion->codigosInstitucion ?
                            $preafiliado->institucionPreafiliacion->codigosInstitucion->codigo . $preafiliado->institucionPreafiliacion->codigosInstitucion->tipo_titular : null) : null;
                        $datosPersona['codigo_beneficiario'] = $preafiliado->institucionPreafiliacion ? ($preafiliado->institucionPreafiliacion->codigosInstitucion ?
                            $preafiliado->institucionPreafiliacion->codigosInstitucion->codigo . $preafiliado->institucionPreafiliacion->codigosInstitucion->tipo_beneficiario : null) : null;;
                    } else {
                        $datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }
                } elseif ($historialAsegurado->id_tipo_asegurado == 15) {
                    $datosPersona['ultimo_tipo_poblacion'] = 'PREAFILIADO BENEFICIARIO';
                    $datosPersona['id_corresponde'] = $historialAsegurado->id_corresponde;
                    $datosPersona['id_historial_asegurado'] = $historialAsegurado->id;
                    $preafiliado = Preafiliacion::with(['institucionPreafiliacion.codigosInstitucion'])
                        ->where('estado', true)
                        ->where('id_persona', $persona->id)
                        ->orderBy('id', 'desc')
                        ->first();

                    if ($preafiliado) {
                        $datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = 'VIGENTE';
                        $datosPersona['nombre_institucion'] = $preafiliado->institucionPreafiliacion ? $preafiliado->institucionPreafiliacion->nombre : 'APORTANTE VOLUNTARIO';
                        $datosPersona['tipo_institucion'] = 'PREAFILIACIÓN';
                        $datosPersona['codigo_titular'] = $preafiliado->institucionPreafiliacion ? ($preafiliado->institucionPreafiliacion->codigosInstitucion ?
                            $preafiliado->institucionPreafiliacion->codigosInstitucion->codigo . $preafiliado->institucionPreafiliacion->codigosInstitucion->tipo_titular : null) : null;
                        $datosPersona['codigo_beneficiario'] = $preafiliado->institucionPreafiliacion ? ($preafiliado->institucionPreafiliacion->codigosInstitucion ?
                            $preafiliado->institucionPreafiliacion->codigosInstitucion->codigo . $preafiliado->institucionPreafiliacion->codigosInstitucion->tipo_beneficiario : null) : null;;
                    } else {
                        $datosPersona['foto'] = null;
                        $datosPersona['estado_vigencia'] = null;
                        $datosPersona['nombre_institucion'] = null;
                        $datosPersona['tipo_institucion'] = null;
                        $datosPersona['codigo_titular'] = null;
                        $datosPersona['codigo_beneficiario'] = null;
                    }
                } else {
                    $datosPersona['ultimo_tipo_poblacion'] = null;
                    $datosPersona['id_corresponde'] =  null;
                    $datosPersona['id_historial_asegurado'] =  null;
                    $datosPersona['foto'] = null;
                    $datosPersona['estado_vigencia'] = null;
                    $datosPersona['nombre_institucion'] = null;
                    $datosPersona['tipo_institucion'] = null;
                    $datosPersona['codigo_titular'] = null;
                    $datosPersona['codigo_beneficiario'] = null;
                }
            } else {
                $datosPersona['ultimo_tipo_poblacion'] = null;
                $datosPersona['id_corresponde'] =  null;
                $datosPersona['id_historial_asegurado'] =  null;
                $datosPersona['foto'] = null;
                $datosPersona['estado_vigencia'] = null;
                $datosPersona['nombre_institucion'] = null;
                $datosPersona['tipo_institucion'] = null;
                $datosPersona['codigo_titular'] = null;
                $datosPersona['codigo_beneficiario'] = null;
            }

            // dd($datosPersona);
        } else {
            $datosPersona = [];
        }
        return $datosPersona;
    }
}
