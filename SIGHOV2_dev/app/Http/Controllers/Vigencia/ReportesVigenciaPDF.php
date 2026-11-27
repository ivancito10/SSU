<?php

namespace App\Http\Controllers\Vigencia;

use App\Http\Controllers\Controller;
use App\Models\Administracion\Persona;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Estudiante;
use App\Models\Afiliacion\HCarnet;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Vigencia\AutorizacionInterior;
use App\Models\Vigencia\AutorizacionIntEspecialidad;
use App\Models\Vigencia\AutorizacionIntServicio;
use App\Models\Vigencia\Convenio;
use App\Models\Vigencia\ConvenioEspecialidad;
use App\Models\Vigencia\ConvenioServicio;
use App\Services\BusquedaClaveUnica;
use App\Models\Vigencia\Preafiliacion;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReportesVigenciaPDF extends Controller
{
    //
    public function reportesvigenciapdf($id)
    {
        $matriculaTitular = null;
        $ciTitular = null;
        $nombreTitular = null;
        $institucionTitular = null;
        $telefonoTitular = null;
        $institucionesTitular = null;
        $limitaciones = null;

        $datosPersona = Persona::find($id);
        $codigo_qr = $datosPersona->clave_unica;
       // dd($codigo_qr);
        $busqueda = new BusquedaClaveUnica();
        $datos = $busqueda->datosPorClaveUnicaNuevo($codigo_qr);

        //dd($datos);
        if ($datos == []) {
            //dd($datos);
            $codigo_qr = null;
        } else {
            //dd($codigo_qr);
            //dd($datos);
            $id_persona = $datos['id'];
            $ci = $datos['ci'];
            $matricula_seguro = $datos['matricula_seguro'];
            $nombreCompleto = implode(' ', array_filter([$datos['p_apellido'], $datos['s_apellido'], $datos['nombres']]));
            $edad = Carbon::parse($datos['fecha_nacimiento'])->age;
            $sexo = $datos['sexo'];
            $fecha_nacimiento = Carbon::parse($datos['fecha_nacimiento'])->format('d/m/Y');
            $tipo_asegurado = $datos['tipo_asegurado'];
            $foto = $datos['foto'];
            $estado_vigencia = $datos['estado_vigencia'];
            $institucion = $datos['nombre_institucion'];

            if ($datos['tipo_asegurado'] == 'TITULAR') {
                if ($datos['codigo_titular'] != null) {
                    $matricula_seguro .= '-' . $datos['codigo_titular'];
                }

                $datosTitular = Titular::where('id_persona', $datos['id'])->with(['titularResidencia.zonaResidencia'])->orderBy('id', 'desc')->first();
                if ($datosTitular->permanente) {
                    $color_asegurado = "#e0241c";
                } else {
                    $color_asegurado = "#001875";
                }
                if ($datosTitular->tipo_rentista == 'TITULAR-SORTEO') {
                    $color_asegurado = "#839192";
                }
                $datosTitularInstitucion = TitularInstitucion::where('id_titular', $datosTitular->id)->where('estado', true)
                    ->with(['titularInstitucionConInstitucion'])->orderBy('id', 'asc')->get();
                // dd($datosTitularInstitucion,$datosTitular->id);
                if (count($datosTitularInstitucion) > 0) {
                    foreach ($datosTitularInstitucion as $key => $datosInstitucion) {
                        $institucionesTitular[] = $datosInstitucion->titularInstitucionConInstitucion->nombre;
                    }
                } else {
                    $institucionesTitular[] = 'APORTANTE VOLUNTARIO';
                }

                $telefono = $datosTitular->telefono ? $datosTitular->telefono : 'NO REGISTRADO';
                $nombre_referencia = $datosTitular->nombre_referencia ? $datosTitular->nombre_referencia : 'NO REGISTRADO';
                $telefono_referencia = $datosTitular->telefono_referencia ? $datosTitular->telefono_referencia : 'NO REGISTRADO';
                $direccion = $datosTitular->titularResidencia->direccion;
                $zona = $datosTitular->titularResidencia->zonaResidencia->nombre;

                $datosCarnet = HCarnet::where('id_persona', $datos['id'])->orderBy('id', 'desc')->first();
                $fecha_emision = $datosCarnet ? Carbon::parse($datosCarnet->fecha_emision)->format('d/m/Y') : 'SIN FECHA';
                $fecha_vencimiento = $datosCarnet ? Carbon::parse($datosCarnet->fecha_vencimiento)->format('d/m/Y') : ' SIN FECHA';
                $estado_carnet = 'CARNET CADUCO';
                if ($datosCarnet) {
                    if (Carbon::now()->lte(Carbon::parse($datosCarnet->fecha_vencimiento))) {
                        $estado_carnet = 'CARNET VIGENTE';
                    }
                }
            }

            if (Str::startsWith($datos['tipo_asegurado'], 'BENEFICIARIO')) {
                if ($datos['tipo_asegurado'] != 'BENEFICIARIO DEL INTERIOR') {
                    if ($datos['codigo_beneficiario'] != null) {
                        $matricula_seguro .= '-' . $datos['codigo_beneficiario'];
                    }

                    $datosBeneficiario = Beneficiario::where('id_persona', $datos['id'])->with(['beneficiarioResidencia.zonaResidencia'])->first();
                    if ($datosBeneficiario->permanente) {
                        $color_asegurado = "#d4cd00";
                    } else {
                        $color_asegurado = "#001875";
                    }

                    $telefono = $datosBeneficiario->telefono ? $datosBeneficiario->telefono : 'NO REGISTRADO';
                    $nombre_referencia = $datosBeneficiario->nombre_referencia ? $datosBeneficiario->nombre_referencia : 'NO REGISTRADO';
                    $telefono_referencia = $datosBeneficiario->telefono_referencia ? $datosBeneficiario->telefono_referencia : 'NO REGISTRADO';
                    $direccion = $datosBeneficiario->beneficiarioResidencia->direccion;
                    $zona = $datosBeneficiario->beneficiarioResidencia->zonaResidencia->nombre;

                    $datosCarnet = HCarnet::where('id_persona', $datos['id'])->orderBy('id', 'desc')->first();
                    $fecha_emision = $datosCarnet ? Carbon::parse($datosCarnet->fecha_emision)->format('d/m/Y') : 'SIN FECHA';
                    $fecha_vencimiento = $datosCarnet ? Carbon::parse($datosCarnet->fecha_vencimiento)->format('d/m/Y') : 'SIN FECHA';
                    $estado_carnet = 'CARNET CADUCO';
                    if ($datosCarnet) {
                        if (Carbon::now()->lte(Carbon::parse($datosCarnet->fecha_vencimiento))) {
                            $estado_carnet = 'CARNET VIGENTE';
                        }
                    }

                    $informacionTitular = Titular::find($datosBeneficiario->id_titular);
                    $personaTitular = Persona::find($informacionTitular->id_persona);

                    $datosBeneficiarioTitular = $busqueda->datosPorClaveUnica($personaTitular->clave_unica);
                    $ciTitular = $personaTitular->ci;
                    if ($personaTitular->complemento != null) {
                        $ciTitular .= $personaTitular->complemento;
                    }
                    $nombreTitular = implode(' ', array_filter([
                        $personaTitular->p_apellido,
                        $personaTitular->s_apellido,
                        $personaTitular->nombres
                    ]));
                    if ($personaTitular) {
                        $matriculaTitular = $personaTitular->matricula_seguro;
                    } else {
                        $matriculaTitular = "";
                    }

                    $institucionTitular = $datosBeneficiarioTitular['nombre_institucion'];
                    $telefonoTitular = $informacionTitular->telefono;
                } else {
                    $color_asegurado = "#ff7700";
                    $datosConvenio = AutorizacionInterior::where('id_persona_interior', $datos['id'])->orderBy('id', 'desc')->first();
                    $telefono = $datosConvenio->telefono ? $datosConvenio->telefono : 'NO REGISTRADO';
                    $nombre_referencia = $datosConvenio->datos_referencia ? $datosConvenio->datos_referencia : 'NO REGISTRADO';
                    $telefono_referencia = $datosConvenio->telefono_referencia ? $datosConvenio->telefono_referencia : 'NO REGISTRADO';
                    $direccion = $datosConvenio->direccion_referencia ? $datosConvenio->direccion_referencia : 'NO REGISTRADO';
                    $zona = 'NO REGISTRADO';
                    $fecha_emision = Carbon::parse($datosConvenio->fecha_inicio)->format('d/m/Y');
                    $fecha_vencimiento = Carbon::parse($datosConvenio->fecha_fin)->format('d/m/Y');
                    if (Carbon::now()->lte(Carbon::parse($datosConvenio->fecha_fin))) {
                        $estado_carnet = 'CONVENIO VIGENTE';
                    } else {
                        $estado_carnet = 'CONVENIO CADUCO';
                    }
                    $limitaciones = $this->limitacionesConvenios($datosConvenio->id);
                }
            }

            if ($datos['tipo_asegurado'] == 'TITULAR DEL INTERIOR') {
                $color_asegurado = "#01ac01";
                $datosConvenio = AutorizacionInterior::where('id_persona_interior', $datos['id'])->orderBy('id', 'desc')->first();
                $telefono = $datosConvenio->telefono ? $datosConvenio->telefono : 'NO REGISTRADO';
                $nombre_referencia = $datosConvenio->datos_referencia ? $datosConvenio->datos_referencia : 'NO REGISTRADO';
                $telefono_referencia = $datosConvenio->telefono_referencia ? $datosConvenio->telefono_referencia : 'NO REGISTRADO';
                $direccion = $datosConvenio->direccion_referencia ? $datosConvenio->direccion_referencia : 'NO REGISTRADO';
                $zona = 'NO REGISTRADO';
                $fecha_emision = Carbon::parse($datosConvenio->fecha_inicio)->format('d/m/Y');
                $fecha_vencimiento = Carbon::parse($datosConvenio->fecha_fin)->format('d/m/Y');
                if (Carbon::now()->lte(Carbon::parse($datosConvenio->fecha_fin))) {
                    $estado_carnet = 'CONVENIO VIGENTE';
                } else {
                    $estado_carnet = 'CONVENIO CADUCO';
                }
                $limitaciones = $this->limitacionesConvenios($datosConvenio->id);
            }

            if ($datos['tipo_asegurado'] == 'TITULAR CONVENIO') {
                $color_asegurado = "#01ac01";
                $datosConvenioEmergencia = Convenio::where('id_persona', $datos['id'])->orderBy('id', 'desc')->first();
                $telefono = 'NO REGISTRADO';
                $nombre_referencia = 'NO REGISTRADO';
                $telefono_referencia = 'NO REGISTRADO';
                $direccion = 'NO REGISTRADO';
                $zona = 'NO REGISTRADO';
                $fecha_emision = Carbon::parse($datosConvenioEmergencia->fecha_inicio)->format('d/m/Y');
                $fecha_vencimiento = Carbon::parse($datosConvenioEmergencia->fecha_fin)->format('d/m/Y');
                if (Carbon::now()->lte(Carbon::parse($datosConvenioEmergencia->fecha_fin))) {
                    $estado_carnet = 'CONVENIO VIGENTE';
                } else {
                    $estado_carnet = 'CONVENIO CADUCO';
                }
                $limitaciones = $this->limitacionesConveniosEmergencia($datosConvenioEmergencia->id);
            }

            if ($datos['tipo_asegurado'] == 'ESTUDIANTE') {
                try {
                    $color_asegurado = "#00b9ef";
                    $datosEstudiante = Estudiante::where('id_persona', $datos['id'])->with(['estudianteResidencia.zonaResidencia'])->first();
                    $telefono = $datosEstudiante->telefono ? $datosEstudiante->telefono : 'NO REGISTRADO';
                    $nombre_referencia = $datosEstudiante->nombre_referencia ? $datosEstudiante->nombre_referencia : 'NO REGISTRADO';
                    $telefono_referencia = $datosEstudiante->telefono_referencia ? $datosEstudiante->telefono_referencia : 'NO REGISTRADOS';
                    $direccion = $datosEstudiante->estudianteResidencia->direccion;
                    $zona = $datosEstudiante->estudianteResidencia->zonaResidencia->nombre;
                    $fecha_emision = $datosEstudiante->estado_validar === 'AFILIADO' && $datosEstudiante->estado_vigencia ? Carbon::parse($datosEstudiante->fecha_afiliacion)->format('d/m/Y') : 'NO AFILIADO';
                    $fecha_vencimiento = $datosEstudiante->estado_validar === 'AFILIADO' && $datosEstudiante->estado_vigencia ? Carbon::parse($datosEstudiante->fecha_fin_vigencia)->format('d/m/Y') : 'NO AFILIADO';
                    if ($datosEstudiante->estado_vigencia) {
                        if (Carbon::now()->lte(Carbon::parse($datosEstudiante->fecha_fin_vigencia))) {
                            if ($datosEstudiante->estado_validar === 'AFILIADO') {
                                $estado_carnet = 'VIGENTE';
                            } else {
                                $estado_carnet = 'NO TIENE CARNET';
                            }
                        } else {
                            $estado_carnet = 'CARNET CADUCO';
                        }
                    } else {
                        $estado_carnet = 'CARNET CADUCO';
                    }
                } catch (\Throwable $th) {
                    $color_asegurado = "#00b9ef";
                    $datosEstudiante = Estudiante::where('id_persona', $datos['id'])->first();
                    $telefono = $datosEstudiante->telefono ? $datosEstudiante->telefono : 'NO REGISTRADO';
                    $nombre_referencia = $datosEstudiante->nombre_referencia ? $datosEstudiante->nombre_referencia : 'NO REGISTRADO';
                    $telefono_referencia = $datosEstudiante->telefono_referencia ? $datosEstudiante->telefono_referencia : 'NO REGISTRADO';
                    $direccion = 'NO REGISTRADO';
                    $zona = 'NO REGISTRADO';
                    $fecha_emision = 'SIN FECHA';
                    $fecha_vencimiento = 'SIN FECHA';
                    $estado_carnet = 'NO TIENE CARNET';
                }
            }
            if ($datos['tipo_asegurado'] == 'PREAFILIADO TITULAR') {
                try {
                    $color_asegurado = "#697285";
                    $datosPreafiliacion = Preafiliacion::with('titularPreafiliacion', 'tipoConvenioPreafiliacion', 'institucionPreafiliacion')
                        ->where('id_persona', $datos['id'])
                        ->where('estado', true)
                        ->first();
                    $telefono = $datosPreafiliacion->telefono ?? '';
                    $nombre_referencia = '';
                    $telefono_referencia = '';
                    $direccion = 'NO REGISTRADO';
                    $zona = 'NO REGISTRADO';
                    $fecha_emision = 'SIN FECHA';
                    $fecha_vencimiento = 'SIN FECHA';
                    $estado_carnet = 'NO TIENE CARNET';
                } catch (\Throwable $th) {
                    $telefono = '';
                    $nombre_referencia = '';
                    $telefono_referencia = '';
                    $direccion = 'NO REGISTRADO';
                    $zona = 'NO REGISTRADO';
                    $fecha_emision = 'SIN FECHA';
                    $fecha_vencimiento = 'SIN FECHA';
                    $estado_carnet = 'NO TIENE CARNET';
                }
            }
            if ($datos['tipo_asegurado'] == 'PREAFILIADO BENEFICIARIO') {
                try {
                    $color_asegurado = "#697285";
                    $datosPreafiliacion = Preafiliacion::with('titularPreafiliacion', 'tipoConvenioPreafiliacion', 'institucionPreafiliacion')
                        ->where('id_persona', $datos['id'])
                        ->where('estado', true)
                        ->first();
                    $telefono = $datosPreafiliacion->telefono ?? '';
                    $nombre_referencia = '';
                    $telefono_referencia = '';
                    $direccion = 'NO REGISTRADO';
                    $zona = 'NO REGISTRADO';
                    $fecha_emision = 'SIN FECHA';
                    $fecha_vencimiento = 'SIN FECHA';
                    $estado_carnet = 'NO TIENE CARNET';
                } catch (\Throwable $th) {
                    $telefono = '';
                    $nombre_referencia = '';
                    $telefono_referencia = '';
                    $direccion = 'NO REGISTRADO';
                    $zona = 'NO REGISTRADO';
                    $fecha_emision = 'SIN FECHA';
                    $fecha_vencimiento = 'SIN FECHA';
                    $estado_carnet = 'NO TIENE CARNET';
                }
            }
            $codigo_qr = null;
        }



        // resources/views/Vigencia/PoblacionAsegurada/reportesPDF.blade.php
        $fecha_registro = Carbon::now()->format('d-m-Y');
        $nombre_usuario = auth()->user()->name;
        $vista_final = view('Vigencia.PoblacionAsegurada.reportesPDF', compact(
            'id',
            'fecha_registro',
            'nombre_usuario',
            'nombreCompleto',
            'ci',
            'matricula_seguro',
            'fecha_nacimiento',
            'edad',
            'sexo',
            'foto',
            'tipo_asegurado',
            'nombre_referencia',
            'estado_vigencia',
            'institucionesTitular',
            'estado_carnet',
            'institucion',
            'color_asegurado',
            'fecha_emision',
            'fecha_vencimiento',
            'telefono_referencia',
            'direccion',
            'zona',
            'matriculaTitular',
            'ciTitular',
            'nombreTitular',
            'institucionTitular',
            'telefonoTitular',
            'limitaciones',


            // 'datosConvenio', 
            // 'datosGerente',
            // 'especialidades',
            // 'servicios',
            // 'liname',
            // 'otrasLimitacionesTexto',
            // 'observacionesTexto'
        ));
        $pdf = Pdf::loadHTML($vista_final); // Las dimensiones deben estar en puntos (1 cm = 28.35 puntos)
        $pdf->setPaper('letter', 'portrait'); // Las dimensiones deben estar en puntos (1 cm = 28.35 puntos)
        return $pdf->stream('pdf_convenio_interior.pdf');
    }

    public function limitacionesConvenios($idConvenio)
    {
        $convenio = AutorizacionInterior::find($idConvenio);
        $especialidades = 'AUTORIZAN ATENCIÓN MÉDICA EN ';

        $datosEsp = AutorizacionIntEspecialidad::where('id_autorizacion_interior', $idConvenio)->with(['especialidadAutorizacionIntEspecialidad'])->get();
        if ($datosEsp->count() > 0) {
            if ($datosEsp->count() == 1) {
                $especialidades .= $datosEsp[0]->especialidadAutorizacionIntEspecialidad->especialidad . '.';
            } else {
                foreach ($datosEsp as $key => $datoEsp) {
                    if ($key == $datosEsp->count() - 1) {
                        $especialidades .= 'y ' . $datoEsp->especialidadAutorizacionIntEspecialidad->especialidad . '.';
                    } elseif ($key == $datosEsp->count() - 2) {
                        $especialidades .= $datoEsp->especialidadAutorizacionIntEspecialidad->especialidad . ' ';
                    } else {
                        $especialidades .= $datoEsp->especialidadAutorizacionIntEspecialidad->especialidad . ', ';
                    }
                }
            }
        } else {
            $especialidades = '';
        }

        $servicios = ' ADEMÁS DE ';

        $datosServ = AutorizacionIntServicio::where('id_autorizacion_interior', $idConvenio)->with(['servicioAutorizacionIntServicio'])->get();
        if ($datosServ->count() > 0) {
            if ($datosServ->count() == 1) {
                $servicios .= $datosServ[0]->servicioAutorizacionIntServicio->servicio . ' SI SE REQUIERE.';
            } else {
                foreach ($datosServ as $key => $datoServ) {
                    if ($key == $datosServ->count() - 1) {
                        $servicios .= 'y ' . $datoServ->servicioAutorizacionIntServicio->servicio . ' SI SE REQUIERE.';
                    } elseif ($key == $datosServ->count() - 2) {
                        $servicios .= $datoServ->servicioAutorizacionIntServicio->servicio . ' ';
                    } else {
                        $servicios .= $datoServ->servicioAutorizacionIntServicio->servicio . ', ';
                    }
                }
            }
        } else {
            $servicios = '';
        }

        $liname = 'TAMBIÉN MEDICAMENTOS SEGÚN LA ';
        if ($convenio->medicamentos_liname != null && $convenio->medicamentos_extraliname != null) {
            if ($convenio->medicamentos_liname && !$convenio->medicamentos_extraliname) {
                $liname .= 'LINAME.';
            } elseif (!$convenio->medicamentos_liname && $convenio->medicamentos_extraliname) {
                $liname .= 'EXTRALINAME.';
            } elseif ($convenio->medicamentos_liname && $convenio->medicamentos_extraliname) {
                $liname .= 'LINAME Y EXTRALINAME.';
            } else {
                $liname = '';
            }
        } else {
            $liname = '';
        }


        $otrasLimitacionesTexto = strip_tags($convenio->otras_limitaciones);
        $observacionesTexto = strip_tags($convenio->observacion);

        if ($otrasLimitacionesTexto != ''  && $observacionesTexto == '') {
            $textoFinal = $otrasLimitacionesTexto . '.';
        } elseif ($otrasLimitacionesTexto == '' && $observacionesTexto != '') {
            $textoFinal = $observacionesTexto . '.';
        } elseif ($otrasLimitacionesTexto != '' && $observacionesTexto != '') {
            $textoFinal = $otrasLimitacionesTexto . '. ' . $observacionesTexto . '.';
        } else {
            $textoFinal = '';
        }

        return $especialidades . $servicios . ' ' . $liname . ' ' . $textoFinal;
    }

    public function limitacionesConveniosEmergencia($idConvenio)
    {
        $convenio = Convenio::find($idConvenio);
        $especialidades = 'AUTORIZAN ATENCIÓN MÉDICA EN ';

        $datosEsp = ConvenioEspecialidad::where('id_convenio', $idConvenio)->with(['especialidadConvenioEspecialidad'])->get();
        if ($datosEsp->count() > 0) {
            if ($datosEsp->count() == 1) {
                $especialidades .= $datosEsp[0]->especialidadConvenioEspecialidad->especialidad . '.';
            } else {
                foreach ($datosEsp as $key => $datoEsp) {
                    if ($key == $datosEsp->count() - 1) {
                        $especialidades .= 'y ' . $datoEsp->especialidadConvenioEspecialidad->especialidad . '.';
                    } elseif ($key == $datosEsp->count() - 2) {
                        $especialidades .= $datoEsp->especialidadConvenioEspecialidad->especialidad . ' ';
                    } else {
                        $especialidades .= $datoEsp->especialidadConvenioEspecialidad->especialidad . ', ';
                    }
                }
            }
        } else {
            $especialidades = '';
        }

        $servicios = ' ADEMÁS DE ';

        $datosServ = ConvenioServicio::where('id_convenio', $idConvenio)->with(['servicioConvenioServicio'])->get();
        if ($datosServ->count() > 0) {
            if ($datosServ->count() == 1) {
                $servicios .= $datosServ[0]->servicioConvenioServicio->servicio . ' SI SE REQUIERE.';
            } else {
                foreach ($datosServ as $key => $datoServ) {
                    if ($key == $datosServ->count() - 1) {
                        $servicios .= 'y ' . $datoServ->servicioConvenioServicio->servicio . ' SI SE REQUIERE.';
                    } elseif ($key == $datosServ->count() - 2) {
                        $servicios .= $datoServ->servicioConvenioServicio->servicio . ' ';
                    } else {
                        $servicios .= $datoServ->servicioConvenioServicio->servicio . ', ';
                    }
                }
            }
        } else {
            $servicios = '';
        }

        $liname = 'TAMBIÉN MEDICAMENTOS SEGÚN LA ';
        if ($convenio->medicamentos_liname != null && $convenio->medicamentos_extraliname != null) {
            if ($convenio->medicamentos_liname && !$convenio->medicamentos_extraliname) {
                $liname .= 'LINAME.';
            } elseif (!$convenio->medicamentos_liname && $convenio->medicamentos_extraliname) {
                $liname .= 'EXTRALINAME.';
            } elseif ($convenio->medicamentos_liname && $convenio->medicamentos_extraliname) {
                $liname .= 'LINAME Y EXTRALINAME.';
            } else {
                $liname = '';
            }
        } else {
            $liname = '';
        }


        $otrasLimitacionesTexto = strip_tags($convenio->otras_limitaciones);
        $observacionesTexto = strip_tags($convenio->observacion);

        if ($otrasLimitacionesTexto != ''  && $observacionesTexto == '') {
            $textoFinal = $otrasLimitacionesTexto . '.';
        } elseif ($otrasLimitacionesTexto == '' && $observacionesTexto != '') {
            $textoFinal = $observacionesTexto . '.';
        } elseif ($otrasLimitacionesTexto != '' && $observacionesTexto != '') {
            $textoFinal = $otrasLimitacionesTexto . '. ' . $observacionesTexto . '.';
        } else {
            $textoFinal = '';
        }

        return $especialidades . $servicios . ' ' . $liname . ' ' . $textoFinal;
    }
}
