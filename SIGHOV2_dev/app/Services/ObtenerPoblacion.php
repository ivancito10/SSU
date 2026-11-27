<?php

namespace App\Services;

use App\Models\Administracion\Persona;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\BeneficiarioInstitucion;
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
use Illuminate\Support\Str;
use App\Services\BusquedaClaveUnica;
use Carbon\Carbon;

class ObtenerPoblacion
{
    public function obtenerPoblacionAsegurada()
    {
        $poblacionAsegurada = [];

        $personasAfiliadas = Persona::with(['tipoAseguradoPersona'])
            ->whereNot('id_tipo_asegurado', 0)
            ->where('afiliado', true)
            ->get();

        foreach ($personasAfiliadas as $personaAfiliada) {
            $codigoInstitucional = null;
            $tipoAsegurado = $personaAfiliada->tipoAseguradoPersona->tipo_asegurado ?? null;

            if ($tipoAsegurado === 'TITULAR') {
                $personaTitular = $this->buscarTitularPorPersona($personaAfiliada->id);
                $codigoInstitucional = $this->obtenerCodigoInstitucionalTitular($personaTitular);
            } elseif (Str::contains($tipoAsegurado, 'BENEFICIARIO') && $tipoAsegurado !== 'BENEFICIARIO DEL INTERIOR') {
                $personaBeneficiario = $this->buscarBeneficiarioPorPersona($personaAfiliada->id);
                $beneficiarioTitular = $personaBeneficiario ? $this->buscarTitularPorId($personaBeneficiario->id_titular) : null;
                $codigoInstitucional = $this->obtenerCodigoInstitucionalTitular($beneficiarioTitular);
            }

            $poblacionAsegurada[] = [
                'id_persona' => $personaAfiliada->id,
                'nombres' => $personaAfiliada->nombres,
                'p_apellido' => $personaAfiliada->p_apellido,
                's_apellido' => $personaAfiliada->s_apellido,
                'ci' => $personaAfiliada->ci,
                'complemento' => $personaAfiliada->complemento,
                'matricula_seguro' => $personaAfiliada->matricula_seguro,
                'codigo_institucional' => $codigoInstitucional,
                'id_tipo_asegurado' => $personaAfiliada->id_tipo_asegurado,
                'tipo_asegurado' => $tipoAsegurado
            ];
        }

        return $poblacionAsegurada;
    }

    private function buscarTitularPorPersona($idPersona)
    {
        return Titular::where('id_persona', $idPersona)
            ->whereIn('estado_vigencia', ['VIGENTE', 'CESANTE'])
            ->where('estado', true)
            ->first();
    }

    private function buscarTitularPorId($idTitular)
    {
        return Titular::where('id', $idTitular)
            ->whereIn('estado_vigencia', ['VIGENTE', 'CESANTE'])
            ->where('estado', true)
            ->first();
    }

    private function buscarBeneficiarioPorPersona($idPersona)
    {
        return Beneficiario::where('id_persona', $idPersona)
            ->whereIn('vigencia', ['VIGENTE', 'CESANTE'])
            ->where('estado', true)
            ->first();
    }

    private function obtenerCodigoInstitucionalTitular($titular)
    {
        if (!$titular || !$titular->tipo_titular) {
            return null;
        }
        $titularInstitucion = TitularInstitucion::with(['titularInstitucionConInstitucion.codigosInstitucion'])
            ->where('estado', true)
            ->where('id_titular', $titular->id)
            ->orderBy('id', 'asc')
            ->first();

        if ($titularInstitucion && $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion) {
            $codigo = $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion;
            return $codigo->codigo . $codigo->tipo_titular;
        }
        return null;
    }

    private function obtenerCodigoInstitucionalTitularBeneficiario($titular)
    {
        if (!$titular || !$titular->tipo_titular) {
            return null;
        }
        $titularInstitucion = TitularInstitucion::with(['titularInstitucionConInstitucion.codigosInstitucion'])
            ->where('estado', true)
            ->where('id_titular', $titular->id)
            ->orderBy('id', 'asc')
            ->first();

        if ($titularInstitucion && $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion) {
            $codigo = $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion;
            return $codigo->codigo . $codigo->tipo_beneficiario;
        }
        return null;
    }

    public function obtenerDatosAfiliacionId($idPersona)
    {
        $datosPersona = [];
        $datosAfiliacion = [];
        $datosCarnetAfiliado = [];
        $persona = Persona::with([
            'tipoAseguradoPersona',
            'estadoCivilPersona',
            'municipioPersona.provinciaMunicipio.departamentoProvincia',
            'departamentoPersona'
        ])->find($idPersona);

        if ($persona) {

            if ($persona->tipoAseguradoPersona->tipo_asegurado === 'TITULAR') {
                $datosAfiliacion = $this->datosComoTitular($idPersona);
            }

            if (Str::contains($persona->tipoAseguradoPersona->tipo_asegurado, 'BENEFICIARIO')) {
                if ($persona->tipoAseguradoPersona->tipo_asegurado === 'BENEFICIARIO DEL INTERIOR') {
                    $datosAfiliacion = $this->datosComoConvenioInterior($idPersona);
                } else {
                    $datosAfiliacion = $this->datosComoBeneficiario($idPersona);
                }
            }

            if ($persona->tipoAseguradoPersona->tipo_asegurado === 'TITULAR DEL INTERIOR') {
                $datosAfiliacion = $this->datosComoConvenioInterior($idPersona);
            }

            if ($persona->tipoAseguradoPersona->tipo_asegurado === 'TITULAR CONVENIO') {
                $datosAfiliacion = $this->datosComoConvenioEmergencia($idPersona);
            }

            if ($persona->tipoAseguradoPersona->tipo_asegurado === 'ESTUDIANTE') {
                $datosAfiliacion = $this->datosComoEstudiante($idPersona);
            }

            $carnetAfiliado = HCarnet::where('id_persona', $idPersona)->orderBy('id', 'desc')->first();

            if ($carnetAfiliado) {
                $datosCarnetAfiliado = [
                    'fecha_emision' => $carnetAfiliado->fecha_emision,
                    'fecha_vencimiento' => $carnetAfiliado->fecha_vencimiento,
                    'motivo' => $carnetAfiliado->motivo,
                    'indefinido' => $carnetAfiliado->indefinido
                ];
            }


            $datosPersona = [
                'id_persona' => $persona->id,
                'nombres' => $persona->nombres,
                'p_apellido' => $persona->p_apellido,
                's_apellido' => $persona->s_apellido,
                'ci' => $persona->ci,
                'complemento' => $persona->complemento,
                'matricula_seguro' => $persona->matricula_seguro,
                'sexo' => $persona->sexo,
                'fecha_nacimiento' => $persona->fecha_nacimiento,
                'id_estado_civil' => $persona->id_estado_civil,
                'estado_civil' => $persona->estadoCivilPersona->nombre,
                'id_municipio_nacimiento' => $persona->id_municipio_nacimiento,
                'municipio_nacimiento' => $persona->id_municipio_nacimiento ? $persona->municipioPersona->nombre : null,
                'provincia_nacimiento' => $persona->id_municipio_nacimiento ? $persona->municipioPersona->provinciaMunicipio->nombre : null,
                'departamento_nacimiento' => $persona->id_municipio_nacimiento ? $persona->municipioPersona->provinciaMunicipio->departamentoProvincia->nombre : null,
                'id_dept_exp' => $persona->id_dept_exp,
                'dept_exp' => $persona->id_dept_exp ? $persona->departamentoPersona->nombre : null,
                'sigla_dept_exp' => $persona->id_dept_exp ? $persona->departamentoPersona->sigla : null,
                'id_tipo_asegurado' => $persona->id_tipo_asegurado,
                'tipo_asegurado' => $persona->tipoAseguradoPersona->tipo_asegurado,
                'es_extranjero' => $persona->es_extranjero,
                'afiliado' => $persona->afiliado,
                'clave_unica' => $persona->clave_unica,
                'grupo_sanguineo' => $persona->grupo_sanguineo,
                'alergias' => $persona->alergias,
                'datos_carnet_afiliado' => $datosCarnetAfiliado,
                'datos_afiliacion' => $datosAfiliacion
            ];
        }
        return $datosPersona;
    }

    public function datosComoTitular($idPersona)
    {
        $datosAfiliacionTitular = [];
        $datosInstitucionTitular = [];
        $codigoTipoTitular = null;
        $datosTitular = Titular::where('id_persona', $idPersona)
            ->whereIn('estado_vigencia', ['VIGENTE', 'CESANTE'])
            ->where('estado', true)
            ->first();

        if ($datosTitular) {

            if ($datosTitular->tipo_titular) {
                $titularesInstitucion = TitularInstitucion::with(['titularInstitucionConInstitucion.codigosInstitucion'])
                    ->where('id_titular', $datosTitular->id)
                    ->where('estado', true)
                    ->orderBy('id', 'asc')
                    ->get();

                if (count($titularesInstitucion) > 0) {

                    $codigoTipoTitular = $titularesInstitucion->first()->titularInstitucionConInstitucion->codigosInstitucion ?
                        $titularesInstitucion->first()->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                        $titularesInstitucion->first()->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular :
                        null;

                    foreach ($titularesInstitucion as $key => $titularInstitucion) {
                        $datosInstitucionTitular[] = [
                            'id_titular' => $titularInstitucion->id_titular,
                            'id_institucion' => $titularInstitucion->id_institucion,
                            'institucion' => $titularInstitucion->titularInstitucionConInstitucion->nombre,
                            'codigo_institucional' => $codigoTipoTitular,
                            'estado' => $titularInstitucion->estado
                        ];
                    }
                }
            }

            $datosAfiliacionTitular = [
                'id_persona' => $datosTitular->id_persona,
                'id_titular' => $datosTitular->id,
                'estado_vigencia' => $datosTitular->estado_vigencia,
                'telefono_referencia' => $datosTitular->telefono_referencia,
                'nombre_referencia' => $datosTitular->nombre_referencia,
                'foto_nombre' => $datosTitular->foto_nombre,
                'observacion' => $datosTitular->observacion,
                'fecha_afiliacion' => $datosTitular->fecha_afiliacion,
                'telefono' => $datosTitular->telefono,
                'estado' => $datosTitular->estado,
                'tipo_titular' => $datosTitular->tipo_titular,
                'tipo_rentista' => $datosTitular->tipo_rentista,
                'permanente' => $datosTitular->permanente,
                'codigo_institucional' => $this->obtenerCodigoInstitucionalTitular($datosTitular),
                'datos_institucion' => $datosInstitucionTitular
            ];
        }

        return $datosAfiliacionTitular;
    }

    public function datosComoTitularNoVigente($idPersona)
    {
        // dd('entra');
        $datosAfiliacionTitular = [];
        $datosInstitucionTitular = [];
        $codigoTipoTitular = null;
        $datosTitular = Titular::where('id_persona', $idPersona)
            ->whereIn('estado_vigencia', ['NO VIGENTE'])
            //->where('estado', true)
            ->first();
        // dd($datosTitular);
        if ($datosTitular) {
            if ($datosTitular->tipo_titular) {
                $titularesInstitucion = TitularInstitucion::with(['titularInstitucionConInstitucion.codigosInstitucion'])
                    ->where('id_titular', $datosTitular->id)
                    // ->where('estado', true)
                    //->orderBy('id', 'asc')
                    ->first();
                //dd('sdas', $titularesInstitucion);
            }
            $datosAfiliacionTitular = [
                'id_persona' => $datosTitular->id_persona,
                'id_titular' => $datosTitular->id,
                'estado_vigencia' => $datosTitular->estado_vigencia,
                'telefono_referencia' => $datosTitular->telefono_referencia,
                'nombre_referencia' => $datosTitular->nombre_referencia,
                'foto_nombre' => $datosTitular->foto_nombre,
                'observacion' => $datosTitular->observacion,
                'fecha_afiliacion' => $datosTitular->fecha_afiliacion,
                'telefono' => $datosTitular->telefono,
                'estado' => $datosTitular->estado,
                'tipo_titular' => $datosTitular->tipo_titular,
                'tipo_rentista' => $datosTitular->tipo_rentista,
                'permanente' => $datosTitular->permanente,
                'codigo_institucional' => '',
                'nombre_institucion' => $titularesInstitucion->titularInstitucionConInstitucion->nombre,
            ];
            //  dd($datosAfiliacionTitular);
        }

        return $datosAfiliacionTitular;
    }

    public function datosComoBeneficiario($idPersona)
    {
        $datosAfiliacionBeneficiario = [];
        $datosInstitucionBeneficiario = [];
        $codigoTipoBeneficiario = null;
        $datosBeneficiario = Beneficiario::where('id_persona', $idPersona)
            ->whereIn('vigencia', ['VIGENTE', 'CESANTE'])
            ->where('estado', true)
            ->first();

        if ($datosBeneficiario) {

            if ($datosBeneficiario->con_remuneracion) {
                $beneficiariosInstitucion = BeneficiarioInstitucion::with(['institucionBeneficiarioInstitucion.codigosInstitucion'])
                    ->where('id_beneficiario', $datosBeneficiario->id)
                    ->where('estado', true)
                    ->orderBy('id', 'asc')
                    ->get();

                if (count($beneficiariosInstitucion) > 0) {

                    foreach ($beneficiariosInstitucion as $key => $beneficiarioInstitucion) {
                        $datosInstitucionBeneficiario[] = [
                            'id_beneficiario' => $beneficiarioInstitucion->id_beneficiario,
                            'id_institucion' => $beneficiarioInstitucion->id_institucion,
                            'institucion' => $beneficiarioInstitucion->institucionBeneficiarioInstitucion->nombre,
                            'estado' => $beneficiarioInstitucion->estado
                        ];
                    }
                }
            }

            $titular = $this->buscarTitularPorId($datosBeneficiario->id_titular);


            if ($titular) {
                $idTitularPersona = $titular->id_persona;
                $codigoTipoBeneficiario = $this->obtenerCodigoInstitucionalTitularBeneficiario($titular);
            }

            $datosAfiliacionBeneficiario = [
                'id_persona' => $datosBeneficiario->id_persona,
                'id_beneficiario' => $datosBeneficiario->id,
                'id_titular' => $datosBeneficiario->id_titular,
                'estado_vigencia' => $datosBeneficiario->vigencia,
                'telefono_referencia' => $datosBeneficiario->telefono_referencia,
                'nombre_referencia' => $datosBeneficiario->nombre_referencia,
                'foto_nombre' => $datosBeneficiario->foto_nombre,
                'observacion' => $datosBeneficiario->observacion,
                'fecha_afiliacion' => $datosBeneficiario->fecha_afiliacion,
                'fecha_reafiliacion' => $datosBeneficiario->fecha_reafiliacion,
                'telefono' => $datosBeneficiario->telefono,
                'estado' => $datosBeneficiario->estado,
                'permanente' => $datosBeneficiario->permanente,
                'con_remuneracion' => $datosBeneficiario->con_remuneracion,
                'datos_institucion' => $datosInstitucionBeneficiario,
                'codigo_institucional' => $codigoTipoBeneficiario,
                'datos_titular' => $this->obtenerDatosAfiliacionId($idTitularPersona),
            ];
        }
        return $datosAfiliacionBeneficiario;
    }

    public function datosComoConvenioInterior($idPersona)
    {

        $datosAfiliacionConvenioInterior = [];

        $datosConvenioInterior = AutorizacionInterior::with(['seguroInteriorAutorizacionInterior'])
            ->where('id_persona_interior', $idPersona)
            ->where('estado', true)
            ->where('estado_validacion', 'VALIDADO')
            ->first();

        if ($datosConvenioInterior) {

            $listaEspecialidades = [];
            $listaServicios = [];

            $especialidades = AutorizacionIntEspecialidad::with(['especialidadAutorizacionIntEspecialidad'])
                ->where('id_autorizacion_interior', $datosConvenioInterior->id)
                ->get();

            if (count($especialidades) > 0) {
                foreach ($especialidades as $key => $especialidad) {
                    $listaEspecialidades[] = [
                        'id_especialidad' => $especialidad->id_especialidad,
                        'especialidad' => $especialidad->especialidadAutorizacionIntEspecialidad->especialidad
                    ];
                }
            }

            $servicios = AutorizacionIntServicio::with(['servicioAutorizacionIntServicio'])
                ->where('id_autorizacion_interior', $datosConvenioInterior->id)
                ->get();

            if (count($servicios) > 0) {
                foreach ($servicios as $key => $servicio) {
                    $listaServicios[] = [
                        'id_especialidad' => $servicio->id_servicio,
                        'especialidad' => $servicio->servicioAutorizacionIntServicio->servicio
                    ];
                }
            }

            $datosAfiliacionConvenioInterior = [
                'id_convenio_interior' => $datosConvenioInterior->id,
                'cod_interior' => $datosConvenioInterior->cod_interior,
                'created_at' => $datosConvenioInterior->created_at,
                'fecha_inicio' => $datosConvenioInterior->fecha_inicio,
                'fecha_fin' => $datosConvenioInterior->fecha_fin,
                'direccion_referencia' => $datosConvenioInterior->direccion_referencia,
                'telefono_referencia' => $datosConvenioInterior->telefono_referencia,
                'datos_referencia' => $datosConvenioInterior->datos_referencia,
                'telefono' => $datosConvenioInterior->telefono,
                'id_seguro_interior' => $datosConvenioInterior->id_seguro_interior,
                'nombre_seguro' => $datosConvenioInterior->seguroInteriorAutorizacionInterior->nombre_seguro,
                'otras_limitaciones' => $datosConvenioInterior->otras_limitaciones,
                'foto_nombre' => $datosConvenioInterior->foto_nombre,
                'observacion' => $datosConvenioInterior->observacion,
                'especialidades' => $listaEspecialidades,
                'servicios' => $listaServicios,
                'medicamentos_liname' => $datosConvenioInterior->medicamentos_liname,
                'medicamentos_extraliname' => $datosConvenioInterior->medicamentos_extraliname,
                'estado' => $datosConvenioInterior->estado,
                'nro_hoja_de_ruta' => $datosConvenioInterior->nro_hoja_de_ruta,
                'fecha_solicitud_hr' => $datosConvenioInterior->fecha_solicitud_hr,
                'cod_cite_int' => $datosConvenioInterior->cod_cite_int,
                'estado_validacion' => $datosConvenioInterior->estado_validacion
            ];
        }

        return $datosAfiliacionConvenioInterior;
    }

    public function datosComoConvenioEmergencia($idPersona)
    {
        $datosAfiliacionConvenioEmergencia = [];

        $datosConvenioEmergencia = Convenio::with(['titularAutorizaConvenio.titularPersona', 'tipoConvenioConvenio'])
            ->where('id_persona', $idPersona)
            ->where('estado', true)
            ->where('estado_validez', 'VIGENTE')
            ->first();

        if ($datosConvenioEmergencia) {

            $listaEspecialidades = [];
            $listaServicios = [];

            $especialidades = ConvenioEspecialidad::with(['especialidadConvenioEspecialidad'])
                ->where('id_convenio', $datosConvenioEmergencia->id)
                ->get();

            if (count($especialidades) > 0) {
                foreach ($especialidades as $key => $especialidad) {
                    $listaEspecialidades[] = [
                        'id_especialidad' => $especialidad->id_especialidad,
                        'especialidad' => $especialidad->especialidadConvenioEspecialidad->especialidad
                    ];
                }
            }

            $servicios = ConvenioServicio::with(['servicioConvenioServicio'])
                ->where('id_convenio', $datosConvenioEmergencia->id)
                ->get();

            if (count($servicios) > 0) {
                foreach ($servicios as $key => $servicio) {
                    $listaServicios[] = [
                        'id_especialidad' => $servicio->id_servicio,
                        'especialidad' => $servicio->servicioConvenioServicio->servicio
                    ];
                }
            }

            $datosTitularAutoriza = [];

            $titular = $this->buscarTitularPorId($datosConvenioEmergencia->id_titular_autoriza);

            if ($titular) {
                $datosTitularAutoriza = $this->obtenerDatosAfiliacionId($titular->id_persona);
            }

            $datosAfiliacionConvenioEmergencia = [
                'id_convenio_emergencia' => $datosConvenioEmergencia->id,
                'fecha_inicio' => $datosConvenioEmergencia->fecha_inicio,
                'fecha_fin' => $datosConvenioEmergencia->fecha_fin,
                'otras_limitaciones' => $datosConvenioEmergencia->otras_limitaciones,
                'id_persona' => $datosConvenioEmergencia->id_persona,
                'id_titular_autoriza' => $datosConvenioEmergencia->id_titular_autoriza,
                'id_tipo_convenio' => $datosConvenioEmergencia->id_tipo_convenio,
                'tipo_convenio' => $datosConvenioEmergencia->tipoConvenioConvenio->tipo_convenio,
                'foto_nombre' => $datosConvenioEmergencia->foto_nombre,
                'observacion' => $datosConvenioEmergencia->observacion,
                'medicamentos_liname' => $datosConvenioEmergencia->medicamentos_liname,
                'medicamentos_extraliname' => $datosConvenioEmergencia->medicamentos_extraliname,
                'estado' => $datosConvenioEmergencia->estado,
                'nro_hoja_de_ruta' => $datosConvenioEmergencia->nro_hoja_de_ruta,
                'fecha_solicitud_hr' => $datosConvenioEmergencia->fecha_solicitud_hr,
                'estado_validez' => $datosConvenioEmergencia->estado_validez,
                'cod_convenio' => $datosConvenioEmergencia->cod_convenio,
                'created_at' => $datosConvenioEmergencia->created_at,
                'nro_documento_respaldo' => $datosConvenioEmergencia->nro_documento_respaldo,
                'datos_titular_autoriza' => $datosTitularAutoriza,
                'telefono' => '',
                'telefono_referencia' => '',
                'nombre_referencia' => ''
            ];
        }

        return $datosAfiliacionConvenioEmergencia;
    }

    public function datosComoEstudiante($idPersona)
    {
        $datosAfiliacionEstudiante = [];


        $datosEstudiante = Estudiante::with(['carreraEstudiante.facultadCarrera'])
            ->where('id_persona', $idPersona)
            ->where('estado', true)
            //->where('estado_vigencia', true)
            //->whereIn('estado_validar', ['AFILIADO'])
            ->first();


        if ($datosEstudiante) {
            $datosAfiliacionEstudiante = [
                'id_persona' => $datosEstudiante->id_persona,
                'id_estudiante' => $datosEstudiante->id,
                'id_carrera' => $datosEstudiante->id_carrera,
                'carrera' => $datosEstudiante->carreraEstudiante->nombre,
                'facultad' => $datosEstudiante->carreraEstudiante->facultadCarrera->nombre,
                'semestre' => $datosEstudiante->semestre,
                'registro_universitario' => $datosEstudiante->registro_universitario,
                'gestion' => $datosEstudiante->gestion,
                'foto_nombre' => $datosEstudiante->foto_nombre,
                'observacion' => $datosEstudiante->observacion,
                'fecha_afiliacion' => Carbon::parse($datosEstudiante->fecha_afiliacion)->format('Y-m-d'),
                'fecha_solicitud_afiliacion' => $datosEstudiante->fecha_solicitud_afiliacion,
                'telefono_referencia' => $datosEstudiante->telefono_referencia,
                'nombre_referencia' => $datosEstudiante->nombre_referencia,
                'estado_validar' => $datosEstudiante->estado_validar,
                'telefono' => $datosEstudiante->telefono,
                'correo_institucional' => $datosEstudiante->correo_institucional,
                'fecha_reafiliacion' => $datosEstudiante->fecha_reafiliacion,
                'estado' => $datosEstudiante->estado,
                'estado_vigencia' => $datosEstudiante->estado_vigencia,
                'fecha_baja' => $datosEstudiante->fecha_baja,
                'fecha_fin_vigencia' => $datosEstudiante->fecha_fin_vigencia
            ];
        }
        return $datosAfiliacionEstudiante;
    }

    public function obtenerTodosLosConveniosInterior($idPersona)
    {
        $datosAutorizacion = AutorizacionInterior::with(['seguroInteriorAutorizacionInterior'])
            ->where('id_persona_interior', $idPersona)
            ->orderBy('id', 'desc')
            ->get();
        if ($datosAutorizacion->isEmpty()) {
            return [];
        }
        return $datosAutorizacion->map(function ($item) {
            return [
                'id_autorizacion' => $item->id,
                'cod_cite' => $item->cod_cite_int,
                'fecha_inicio' => $item->fecha_inicio,
                'fecha_fin' => $item->fecha_fin,
                'estado' => $item->estado,
                'medicamentos_liname' => $item->medicamentos_liname,
                'medicamentos_extraliname' => $item->medicamentos_extraliname,
                'id_seguro_interior' => $item->id_seguro_interior,
                'departamento_seguro' => $item->seguroInteriorAutorizacionInterior->nombre_seguro,
                'observaciones' => $item->observacion,
                'otras_limitaciones' => $item->otras_limitaciones,


            ];
        })->toArray();
    }

    public function obtenerTodosLosConveniosEmergencia($idPersona)
    {

        $datosEmergencias = Convenio::with(['titularAutorizaConvenio.titularPersona'])
            ->where('id_persona', $idPersona)
            ->orderBy('id', 'desc')
            ->get();
        if ($datosEmergencias->isEmpty()) {
            return [];
        }

        return $datosEmergencias->map(function ($item) {
            $buscar = new BusquedaClaveUnica();
            $datosTitular = $buscar->datosPorClaveUnica($item->titularAutorizaConvenio->titularPersona->ci);
            return [
                'id_convenio_emergencia' => $item->id,
                'fecha_inicio' => $item->fecha_inicio,
                'fecha_fin' => $item->fecha_fin,
                'estado' => $item->estado,
                'limitaciones' => $item->otras_limitaciones,
                'observaciones' => $item->observacion,
                'medicamentos_liname' => $item->medicamentos_liname,
                'medicamentos_extraliname' => $item->medicamentos_extraliname,
                'nombreAutoriza' => $item->titularAutorizaConvenio->titularPersona->nombres . ' ' . $item->titularAutorizaConvenio->titularPersona->p_apellido . ' ' . $item->titularAutorizaConvenio->titularPersona->s_apellido,
                'ciAutoriza' => $item->titularAutorizaConvenio->titularPersona->ci,
                'matriculaAutoriza' => $item->titularAutorizaConvenio->titularPersona->matricula_seguro,
                'codigoAutoriza' => $datosTitular['codigo_titular'] ?? null,
                'institucionAutoriza' => $datosTitular['nombre_institucion'] ?? null,
            ];
        })->toArray();
    }
}
