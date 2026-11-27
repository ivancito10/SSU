<?php

namespace App\Services;

use App\Models\Administracion\TipoAsegurado;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Vigencia\AseguradoSolicitante;
use App\Models\Vigencia\AutorizacionInterior;
use App\Models\Vigencia\AutorizacionIntEspecialidad;
use App\Models\Vigencia\AutorizacionIntServicio;
use App\Models\Vigencia\BajaInstitucional;
use App\Models\Vigencia\BajaMedica;
use App\Models\Vigencia\Convenio;
use App\Models\Vigencia\ConvenioEspecialidad;
use App\Models\Vigencia\ConvenioServicio;
use App\Models\Vigencia\HistorialAsegurado;
use App\Models\Vigencia\OrdenServicio;
use App\Models\Vigencia\SolicitudPMedica;
use App\Models\Vigencia\SolMedicaEspecialidad;
use App\Models\Vigencia\SolMedicaServicio;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ReportesVigencia
{

    public function reporteOrdenes($fechaInicio, $fechaFin)
    {
        $fechaInicioFormatted = Carbon::parse($fechaInicio)->startOfDay();
        $fechaFinFormatted = Carbon::parse($fechaFin)->endOfDay();

        $busqueda = new BusquedaClaveUnica();

        $datos = [];

        $ordenesServicio = OrdenServicio::whereBetween('fecha_recepcion', [$fechaInicioFormatted, $fechaFinFormatted])
            ->whereNotIn('estado', ['PENDIENTE', 'ANULADO'])
            ->with(['institucionConvenioOrdenServicio', 'personaOrdenServicio', 'especialistaOrdenServicio.personaEspecialista'])
            ->orderBy('fecha_recepcion', 'asc')
            ->get();

        foreach ($ordenesServicio as $key => $ordenServicio) {
            $clave = $ordenServicio->personaOrdenServicio->ci;
            if ($ordenServicio->personaOrdenServicio->complemento != null) {
                $clave .= $ordenServicio->personaOrdenServicio->complemento;
            }

            //$datosBusqueda = $busqueda->datosPorClaveUnicaReportes($clave);
            $datosBusqueda = $busqueda->datosPorIdReportes($ordenServicio->id_persona);
            $matriculaCodigo = $ordenServicio->personaOrdenServicio->matricula_seguro;

            /*if(!isset($datosBusqueda['id_tipo_asegurado'])){
                dd($ordenServicio);
            }*/
            //dd($datosBusqueda);

            if ($datosBusqueda['id_tipo_asegurado'] === 1 && $datosBusqueda['codigo_titular'] !== null) {
                $matriculaCodigo .= ' - ' . $datosBusqueda['codigo_titular'];
            }

            if (Str::contains($datosBusqueda['tipo_asegurado'], 'BENEFICIARIO') && $datosBusqueda['codigo_beneficiario'] !== null) {
                if ($datosBusqueda['tipo_asegurado'] !== 'BENEFICIARIO DEL INTERIOR') {
                    $matriculaCodigo .= ' - ' . $datosBusqueda['codigo_beneficiario'];
                }
            }

            $datos[] = [
                'fecha_recepcion' => Carbon::parse($ordenServicio->fecha_recepcion)->format('Y-m-d'),
                'nombre_completo' => implode(' ', array_filter([
                    $ordenServicio->personaOrdenServicio->p_apellido,
                    $ordenServicio->personaOrdenServicio->s_apellido,
                    $ordenServicio->personaOrdenServicio->nombres
                ])),
                'matricula' => $matriculaCodigo,
                'especialista' => 'DR(A). ' . implode(' ', array_filter([
                    $ordenServicio->especialistaOrdenServicio->personaEspecialista->p_apellido,
                    $ordenServicio->especialistaOrdenServicio->personaEspecialista->s_apellido,
                    $ordenServicio->especialistaOrdenServicio->personaEspecialista->nombres
                ])),
                'institucion' => $datosBusqueda['nombre_institucion'],
                'institucion_convenio' => $ordenServicio->institucionConvenioOrdenServicio->nombre_institucion,
                'servicio_institucion' => $ordenServicio->institucionConvenioOrdenServicio->servicio,
                'estudio' => $ordenServicio->estudio,
            ];
        }
        //dd($datos);
        return $datos;
    }

    public function reporteConveniosInterior($fechaInicio, $fechaFin)
    {
        $datos = [];

        $conveniosInterior = AutorizacionInterior::whereBetween('fecha_inicio', [$fechaInicio, $fechaFin])
            ->where('estado_validacion', 'VALIDADO')
            ->with([
                'seguroInteriorAutorizacionInterior.residenciaSeguroInterior.zonaResidencia.municipioZona.provinciaMunicipio.departamentoProvincia',
                'personaInteriorAutorizacionInterior',
            ])
            ->orderBy('fecha_inicio', 'asc')
            ->get();

        foreach ($conveniosInterior as $key => $convenioInterior) {
            $especialidades = 'AUTORIZAN ATENCIÓN MÉDICA EN ';

            $datosEsp = AutorizacionIntEspecialidad::where('id_autorizacion_interior', $convenioInterior->id)->with(['especialidadAutorizacionIntEspecialidad'])->get();
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

            $datosServ = AutorizacionIntServicio::where('id_autorizacion_interior', $convenioInterior->id)->with(['servicioAutorizacionIntServicio'])->get();
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
            if ($especialidades === '' && $servicios === '') {
                $liname = 'AUTORIZAN MEDICAMENTOS SEGÚN LA ';
            }


            if ($convenioInterior->medicamentos_liname && !$convenioInterior->medicamentos_extraliname) {
                $liname .= 'LINAME.';
            } elseif (!$convenioInterior->medicamentos_liname && $convenioInterior->medicamentos_extraliname) {
                $liname .= 'EXTRALINAME.';
            } elseif ($convenioInterior->medicamentos_liname && $convenioInterior->medicamentos_extraliname) {
                $liname .= 'LINAME Y EXTRALINAME.';
            } else {
                $liname = '';
            }

            $otrasLimitacionesTexto = strip_tags($convenioInterior->otras_limitaciones);

            $observacionesTexto = strip_tags($convenioInterior->observacion);

            if ($otrasLimitacionesTexto != ''  && $observacionesTexto == '') {
                $textoFinal = $otrasLimitacionesTexto;
            } elseif ($otrasLimitacionesTexto == '' && $observacionesTexto != '') {
                $textoFinal = $observacionesTexto;
            } elseif ($otrasLimitacionesTexto != '' && $observacionesTexto != '') {
                $textoFinal = $otrasLimitacionesTexto . ' ' . $observacionesTexto;
            } else {
                $textoFinal = '';
            }

            $datos[] = [
                'nombreCompleto' => implode(' ', array_filter([
                    $convenioInterior->personaInteriorAutorizacionInterior->p_apellido,
                    $convenioInterior->personaInteriorAutorizacionInterior->s_apellido,
                    $convenioInterior->personaInteriorAutorizacionInterior->nombres
                ])),
                'matricula' => $convenioInterior->personaInteriorAutorizacionInterior->matricula_seguro,
                'ciudad' => $convenioInterior->seguroInteriorAutorizacionInterior->residenciaSeguroInterior->zonaResidencia->municipioZona->provinciaMunicipio->departamentoProvincia->nombre,
                'fechaInicio' => $convenioInterior->fecha_inicio,
                'fechaFin' => $convenioInterior->fecha_fin,
                'tipoAutorizacion' => $especialidades . $servicios . ' ' . $liname . ' ' . $textoFinal,
            ];
        }

        //dd($datos);
        return $datos;
    }

    public function reporteConveniosEmergencia($fechaInicio, $fechaFin)
    {

        $conveniosEmergencia = Convenio::whereBetween('fecha_inicio', [$fechaInicio, $fechaFin])
            ->with(['personaConvenio', 'titularAutorizaConvenio.titularPersona'])
            ->whereNot('estado_validez', 'ANULADO')
            ->orderBy('fecha_inicio', 'asc')
            ->get();

        $datos = [];

        foreach ($conveniosEmergencia as $key => $convenioEmergencia) {
            $especialidades = 'AUTORIZAN ATENCIÓN MÉDICA EN ';

            $datosEsp = ConvenioEspecialidad::where('id_convenio', $convenioEmergencia->id)->with(['especialidadConvenioEspecialidad'])->get();
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

            $datosServ = ConvenioServicio::where('id_convenio', $convenioEmergencia->id)->with(['servicioConvenioServicio'])->get();
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
            if ($especialidades === '' && $servicios === '') {
                $liname = 'AUTORIZAN MEDICAMENTOS SEGÚN LA ';
            }

            if ($convenioEmergencia->medicamentos_liname != null && $convenioEmergencia->medicamentos_extraliname != null) {
                if ($convenioEmergencia->medicamentos_liname && !$convenioEmergencia->medicamentos_extraliname) {
                    $liname .= 'LINAME.';
                } elseif (!$convenioEmergencia->medicamentos_liname && $convenioEmergencia->medicamentos_extraliname) {
                    $liname .= 'EXTRALINAME.';
                } elseif ($convenioEmergencia->medicamentos_liname && $convenioEmergencia->medicamentos_extraliname) {
                    $liname .= 'LINAME Y EXTRALINAME.';
                } else {
                    $liname = '';
                }
            } else {
                $liname = '';
            }


            $otrasLimitacionesTexto = strip_tags($convenioEmergencia->otras_limitaciones);
            $observacionesTexto = strip_tags($convenioEmergencia->observacion);

            if ($otrasLimitacionesTexto != ''  && $observacionesTexto == '') {
                $textoFinal = $otrasLimitacionesTexto;
            } elseif ($otrasLimitacionesTexto == '' && $observacionesTexto != '') {
                $textoFinal = $observacionesTexto;
            } elseif ($otrasLimitacionesTexto != '' && $observacionesTexto != '') {
                $textoFinal = $otrasLimitacionesTexto . '. ' . $observacionesTexto;
            } else {
                $textoFinal = '';
            }

            $datos[] = [
                'nombreCompleto' => implode(' ', array_filter([
                    $convenioEmergencia->personaConvenio->p_apellido,
                    $convenioEmergencia->personaConvenio->s_apellido,
                    $convenioEmergencia->personaConvenio->nombres
                ])),
                'matricula' => $convenioEmergencia->personaConvenio->matricula_seguro,
                'titularResponsable' => implode(' ', array_filter([
                    $convenioEmergencia->titularAutorizaConvenio->titularPersona->p_apellido,
                    $convenioEmergencia->titularAutorizaConvenio->titularPersona->s_apellido,
                    $convenioEmergencia->titularAutorizaConvenio->titularPersona->nombres
                ])),
                'fechaInicio' => $convenioEmergencia->fecha_inicio,
                'fechaFin' => $convenioEmergencia->fecha_fin,
                'tipoAutorizacion' => $especialidades . $servicios . ' ' . $liname . ' ' . $textoFinal,
            ];
        }
        //dd($datos);
        return $datos;
    }

    public function reporteBajasInstitucionales($fechaInicio, $fechaFin)
    {
        set_time_limit(200000);
        $fechaInicioFormatted = Carbon::parse($fechaInicio)->startOfDay();
        $fechaFinFormatted = Carbon::parse($fechaFin)->endOfDay();

        $datos = [];

        $bajasInstitucionales = BajaInstitucional::whereBetween('created_at', [$fechaInicioFormatted, $fechaFinFormatted])
            ->with(['personaBajaInstitucional', 'motivoBajaBajaInstitucional'])
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($bajasInstitucionales as $key => $bajaInstitucional) {
            // $fecha_baja_asuss= $bajaInstitucional->fecha_baja_asuss;
            $clave = $bajaInstitucional->personaBajaInstitucional->ci;
            if ($bajaInstitucional->personaBajaInstitucional->complemento != null) {
                $clave .= $bajaInstitucional->personaBajaInstitucional->complemento;
            }

            //$datosBusqueda = $busqueda->datosPorClaveUnicaReportes($clave);
            $matriculaCodigo = $bajaInstitucional->personaBajaInstitucional->matricula_seguro;

            $institucionAsegurado = '';

            $historialAsegurado = HistorialAsegurado::where('id_persona', $bajaInstitucional->id_persona)
                ->whereIn('estado_vigencia', ['NO VIGENTE', 'CESANTE'])
                ->whereNot('fecha_baja', null)
                ->orderBy('id', 'desc')
                ->first();

            if ($historialAsegurado) {
                $tipoAsegurado = TipoAsegurado::find($historialAsegurado->id_tipo_asegurado);
                if ($tipoAsegurado) {
                    if ($tipoAsegurado->tipo_asegurado === 'TITULAR') {

                        $institucionAsegurado = 'APORTANTE VOLUNTARIO';

                        $datosTitularInstitucion = TitularInstitucion::where('id_titular', $bajaInstitucional->id_corresponde)
                            ->where('id_institucion', $bajaInstitucional->id_institucion_baja)
                            ->with(['titularInstitucionConInstitucion.codigosInstitucion'])
                            ->orderBy('id', 'desc')
                            ->first();

                        if ($datosTitularInstitucion) {
                            $institucionAsegurado = $datosTitularInstitucion->titularInstitucionConInstitucion->nombre;

                            if ($datosTitularInstitucion->titularInstitucionConInstitucion->codigosInstitucion !== null) {
                                $matriculaCodigo .= ' - ' . $datosTitularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                    $datosTitularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular;
                            }
                        }
                    }

                    if (Str::contains($tipoAsegurado->tipo_asegurado, 'BENEFICIARIO')) {
                        if ($tipoAsegurado->tipo_asegurado !== 'BENEFICIARIO DEL INTERIOR') {
                            $institucionAsegurado = 'BENEFICIARIO(A)';
                            $datosBeneficiario = Beneficiario::find($historialAsegurado->id_corresponde);
                            if ($datosBeneficiario) {
                                $datosTitularBeneficiarioInstitucion = TitularInstitucion::where('id_titular', $datosBeneficiario->id_titular)
                                    ->with(['titularInstitucionConInstitucion.codigosInstitucion'])
                                    ->orderBy('id', 'desc')
                                    ->first();

                                if ($datosTitularBeneficiarioInstitucion) {
                                    if ($datosTitularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion !== null) {
                                        $matriculaCodigo .= ' - ' . $datosTitularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                            $datosTitularBeneficiarioInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_beneficiario;
                                    }
                                }
                            }
                        }
                    }

                    if (Str::contains($tipoAsegurado->tipo_asegurado, 'ESTUDIANTE')) {
                        $institucionAsegurado = 'APORTES UMSA ESTUDIANTES';
                    }
                }
            }

            $datos[] = [
                'nombreCompleto' => implode(' ', array_filter([
                    $bajaInstitucional->personaBajaInstitucional->p_apellido,
                    $bajaInstitucional->personaBajaInstitucional->s_apellido,
                    $bajaInstitucional->personaBajaInstitucional->nombres
                ])),
                'matricula' => $matriculaCodigo,
                'institucion' => $institucionAsegurado,
                'fechaBajaInstitucion' => $bajaInstitucional->fecha_baja_inst,
                'fechaBajaSsu' => $bajaInstitucional->fecha_baja_ssu,
                'fechaRegistro' => $bajaInstitucional->created_at,
                'motivoBaja' => $bajaInstitucional->motivoBajaBajaInstitucional->motivo_baja,
                'observacion' => $bajaInstitucional->descripcion_motivo,
                'fechaBajaAsuss' => $bajaInstitucional->fecha_baja_asuss,
            ];
        }

        //dd($datos);
        return $datos;
    }

    public function reportePrestacionesInterior($fechaInicio, $fechaFin)
    {
        $datos = [];

        $busqueda = new BusquedaClaveUnica();

        $prestacionesInterior = SolicitudPMedica::whereBetween('fecha_inicio', [$fechaInicio, $fechaFin])
            //->where('estado_validacion', 'VALIDADO')
            ->whereNot('estado_validacion', 'ANULADO')
            ->with([
                'seguroInteriorSolicitudPMedica.residenciaSeguroInterior.zonaResidencia.municipioZona.provinciaMunicipio.departamentoProvincia'
            ])
            ->orderBy('fecha_inicio', 'asc')
            ->get();

        foreach ($prestacionesInterior as $key => $prestacionInterior) {

            $especialidades = 'AUTORIZAN ATENCIÓN MÉDICA EN ';

            $datosEsp = SolMedicaEspecialidad::where('id_solicitud_p_medica', $prestacionInterior->id)->with(['especialidadSolMedicaEspecialidad'])->get();
            if ($datosEsp->count() > 0) {
                if ($datosEsp->count() == 1) {
                    $especialidades .= $datosEsp[0]->especialidadSolMedicaEspecialidad->especialidad . '.';
                } else {
                    foreach ($datosEsp as $key => $datoEsp) {
                        if ($key == $datosEsp->count() - 1) {
                            $especialidades .= 'y ' . $datoEsp->especialidadSolMedicaEspecialidad->especialidad . '.';
                        } elseif ($key == $datosEsp->count() - 2) {
                            $especialidades .= $datoEsp->especialidadSolMedicaEspecialidad->especialidad . ' ';
                        } else {
                            $especialidades .= $datoEsp->especialidadSolMedicaEspecialidad->especialidad . ', ';
                        }
                    }
                }
            } else {
                $especialidades = '';
            }

            $servicios = ' ADEMÁS DE ';

            $datosServ = SolMedicaServicio::where('id_solicitud_p_medica', $prestacionInterior->id)->with(['servicioSolMedicaServicio'])->get();
            if ($datosServ->count() > 0) {
                if ($datosServ->count() == 1) {
                    $servicios .= $datosServ[0]->servicioSolMedicaServicio->servicio . ' SI SE REQUIERE.';
                } else {
                    foreach ($datosServ as $key => $datoServ) {
                        if ($key == $datosServ->count() - 1) {
                            $servicios .= 'y ' . $datoServ->servicioSolMedicaServicio->servicio . ' SI SE REQUIERE.';
                        } elseif ($key == $datosServ->count() - 2) {
                            $servicios .= $datoServ->servicioSolMedicaServicio->servicio . ' ';
                        } else {
                            $servicios .= $datoServ->servicioSolMedicaServicio->servicio . ', ';
                        }
                    }
                }
            } else {
                $servicios = '';
            }

            $liname = 'TAMBIÉN MEDICAMENTOS SEGÚN LA ';
            if ($especialidades === '' && $servicios === '') {
                $liname = 'AUTORIZAN MEDICAMENTOS SEGÚN LA ';
            }


            if ($prestacionInterior->medicamentos_liname && !$prestacionInterior->medicamentos_extraliname) {
                $liname .= 'LINAME.';
            } elseif (!$prestacionInterior->medicamentos_liname && $prestacionInterior->medicamentos_extraliname) {
                $liname .= 'EXTRALINAME.';
            } elseif ($prestacionInterior->medicamentos_liname && $prestacionInterior->medicamentos_extraliname) {
                $liname .= 'LINAME Y EXTRALINAME.';
            } else {
                $liname = '';
            }

            $otrasLimitacionesTexto = strip_tags($prestacionInterior->otras_limitaciones);

            $observacionesTexto = strip_tags($prestacionInterior->observacion);

            if ($otrasLimitacionesTexto != ''  && $observacionesTexto == '') {
                $textoFinal = $otrasLimitacionesTexto;
            } elseif ($otrasLimitacionesTexto == '' && $observacionesTexto != '') {
                $textoFinal = $observacionesTexto;
            } elseif ($otrasLimitacionesTexto != '' && $observacionesTexto != '') {
                $textoFinal = $otrasLimitacionesTexto . ' ' . $observacionesTexto;
            } else {
                $textoFinal = '';
            }

            $aseguradosSolicitantes = AseguradoSolicitante::where('id_solicitud_p_medica', $prestacionInterior->id)
                ->with(['personaAseguradoSolicitante'])
                ->orderBy('id', 'asc')
                ->get();

            foreach ($aseguradosSolicitantes as $key => $aseguradoSolicitante) {

                $clave = $aseguradoSolicitante->personaAseguradoSolicitante->ci;
                if ($aseguradoSolicitante->personaAseguradoSolicitante->complemento != null) {
                    $clave .= $aseguradoSolicitante->personaAseguradoSolicitante->complemento;
                }

                $datosBusqueda = $busqueda->datosPorClaveUnicaReportes($clave);
                $matriculaCodigo = $aseguradoSolicitante->personaAseguradoSolicitante->matricula_seguro;


                if ($datosBusqueda['id_tipo_asegurado'] === 1 && $datosBusqueda['codigo_titular'] !== null) {
                    $matriculaCodigo .= ' - ' . $datosBusqueda['codigo_titular'];
                }

                if (Str::contains($datosBusqueda['tipo_asegurado'], 'BENEFICIARIO') && $datosBusqueda['codigo_beneficiario'] !== null) {
                    if ($datosBusqueda['tipo_asegurado'] !== 'BENEFICIARIO DEL INTERIOR') {
                        $matriculaCodigo .= ' - ' . $datosBusqueda['codigo_beneficiario'];
                    }
                }

                $datos[] = [
                    'nombreCompleto' => implode(' ', array_filter([
                        $aseguradoSolicitante->personaAseguradoSolicitante->p_apellido,
                        $aseguradoSolicitante->personaAseguradoSolicitante->s_apellido,
                        $aseguradoSolicitante->personaAseguradoSolicitante->nombres
                    ])),
                    'matricula' => $matriculaCodigo,
                    'ciudad' => $prestacionInterior->seguroInteriorSolicitudPMedica->residenciaSeguroInterior->zonaResidencia->municipioZona->provinciaMunicipio->departamentoProvincia->nombre,
                    'fechaInicio' => $prestacionInterior->fecha_inicio,
                    'fechaFin' => $prestacionInterior->fecha_fin,
                    'tipoAutorizacion' => $especialidades . $servicios . ' ' . $liname . ' ' . $textoFinal,
                ];
            }
        }

        //dd($datos);
        return $datos;
    }

    public function reporteBajasMedicas($fechaInicio, $fechaFin)
    {
        $datos = [];

        $busqueda = new BusquedaClaveUnica();

        $bajasMedicas = BajaMedica::whereBetween('fecha_inicio', [$fechaInicio, $fechaFin])
            ->whereNotIn('estado_validacion', ['PENDIENTE', 'ANULADO'])
            ->with(['personaBajaMedica', 'personaEntregadoBajaMedica', 'especialistaBajaMedica.personaEspecialista'])
            ->orderBy('fecha_inicio', 'asc')
            ->get();

        foreach ($bajasMedicas as $key => $bajaMedica) {

            if ($bajaMedica->id_persona !== null) {
                $clave = $bajaMedica->personaBajaMedica->ci;
                if ($bajaMedica->personaBajaMedica->complemento != null) {
                    $clave .= $bajaMedica->personaBajaMedica->complemento;
                }

                $datosBusqueda = $busqueda->datosPorClaveUnicaReportes($clave);
                $matriculaCodigo = $bajaMedica->personaBajaMedica->matricula_seguro;

                //dd($datosBusqueda);

                if ($datosBusqueda['id_tipo_asegurado'] === 1 && $datosBusqueda['codigo_titular'] !== null) {
                    $matriculaCodigo .= ' - ' . $datosBusqueda['codigo_titular'];
                }
                
                if (Str::contains($datosBusqueda['tipo_asegurado'], 'BENEFICIARIO') && $datosBusqueda['codigo_beneficiario'] !== null) {
                    if ($datosBusqueda['tipo_asegurado'] !== 'BENEFICIARIO DEL INTERIOR') {
                        $matriculaCodigo .= ' - ' . $datosBusqueda['codigo_beneficiario'];
                    }
                }

                if ($datosBusqueda['id_tipo_asegurado'] === 14){
                    $matriculaCodigo .= ' - ' . $datosBusqueda['codigo_titular'];
                }

                if ($datosBusqueda['id_tipo_asegurado'] === 15){
                    $matriculaCodigo .= ' - ' . $datosBusqueda['codigo_beneficiario'];
                }

                
                $diasBaja = Carbon::parse($bajaMedica->fecha_fin)->diffInDays(Carbon::parse($bajaMedica->fecha_inicio)) + 1;

                $medicoTratante = '';

                if ($bajaMedica->id_especialista !== null) {
                    $medicoTratante = implode(' ', array_filter([
                        $bajaMedica->especialistaBajaMedica->personaEspecialista->p_apellido,
                        $bajaMedica->especialistaBajaMedica->personaEspecialista->s_apellido,
                        $bajaMedica->especialistaBajaMedica->personaEspecialista->nombres
                    ]));
                } else {
                    $medicoTratante = mb_strtoupper($bajaMedica->nombre_especialista);
                }

                $entregado = '';

                if ($bajaMedica->id_persona_entregado !== null) {
                    $entregado = implode(' ', array_filter([
                        $bajaMedica->personaEntregadoBajaMedica->p_apellido,
                        $bajaMedica->personaEntregadoBajaMedica->s_apellido,
                        $bajaMedica->personaEntregadoBajaMedica->nombres
                    ])) . ' EN FECHA: ' . Carbon::parse($bajaMedica->fecha_homologado)->format('d/m/Y');
                }


                $datos[] = [
                    'codigoBaja' => $bajaMedica->cod_baja_medica !== null ? $bajaMedica->cod_baja_medica : '',
                    'fecha_recepcion' => Carbon::parse($bajaMedica->fecha_recepcion)->format('Y-m-d'),
                    'nombre_completo' => implode(' ', array_filter([
                        $bajaMedica->personaBajaMedica->p_apellido,
                        $bajaMedica->personaBajaMedica->s_apellido,
                        $bajaMedica->personaBajaMedica->nombres
                    ])),
                    'matricula' => $matriculaCodigo,
                    'institucion' => $datosBusqueda['nombre_institucion'],
                    'dias_baja' => $diasBaja,
                    'fecha_inicio_baja' => $bajaMedica->fecha_inicio,
                    'fecha_fin_baja' => $bajaMedica->fecha_fin,
                    'medico_tratante' => 'DR(A). ' . $medicoTratante,
                    'diagnostico' => strip_tags($bajaMedica->diagnostico),
                    'entregado_a' => $entregado,
                    'observaciones' => strip_tags($bajaMedica->observacion),
                ];
            } else {
                $diasBaja = Carbon::parse($bajaMedica->fecha_fin)->diffInDays(Carbon::parse($bajaMedica->fecha_inicio)) + 1;

                $entregado = '';

                if ($bajaMedica->id_persona_entregado !== null) {
                    $entregado = implode(' ', array_filter([
                        $bajaMedica->personaEntregadoBajaMedica->p_apellido,
                        $bajaMedica->personaEntregadoBajaMedica->s_apellido,
                        $bajaMedica->personaEntregadoBajaMedica->nombres
                    ])) . ' EN FECHA: ' . Carbon::parse($bajaMedica->fecha_homologado)->format('d/m/Y');
                }

                $datos[] = [
                    'codigoBaja' => $bajaMedica->cod_baja_medica !== null ? $bajaMedica->cod_baja_medica : '',
                    'fecha_recepcion' => Carbon::parse($bajaMedica->fecha_recepcion)->format('Y-m-d'),
                    'nombre_completo' => $bajaMedica->nombre_persona,
                    'matricula' => $bajaMedica->matricula_persona,
                    'institucion' => $bajaMedica->nombre_institucion,
                    'dias_baja' => $diasBaja,
                    'fecha_inicio_baja' => $bajaMedica->fecha_inicio,
                    'fecha_fin_baja' => $bajaMedica->fecha_fin,
                    'medico_tratante' => 'DR(A). ' . $bajaMedica->nombre_especialista,
                    'diagnostico' => strip_tags($bajaMedica->diagnostico),
                    'entregado_a' => $entregado,
                    'observaciones' => strip_tags($bajaMedica->observacion),
                ];
            }
        }
        //dd($datos);
        return $datos;
    }
}
