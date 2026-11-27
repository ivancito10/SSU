<?php

namespace App\Livewire\Vigencia\PoblacionAsegurada;

use Livewire\Component;
use App\Services\BusquedaClaveUnica;
use Carbon\Carbon;
use Illuminate\Support\Str;

use App\Models\Afiliacion\HCarnet;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Administracion\Persona;
use App\Models\Afiliacion\Carrera;
use App\Models\Afiliacion\Facultad;
use App\Services\HashRutasService;

use App\Models\Afiliacion\Estudiante;
use App\Models\Afiliacion\Titular;
use App\Models\Aportes\ContratoVoluntario;
use App\Models\Vigencia\AutorizacionInterior;
use App\Models\Vigencia\AutorizacionIntEspecialidad;
use App\Models\Vigencia\AutorizacionIntServicio;
use App\Models\Vigencia\BajaInstitucional;
use App\Models\Vigencia\HCarnetInterior;
use App\Models\Vigencia\Convenio;
use App\Models\Vigencia\ConvenioEspecialidad;
use App\Models\Vigencia\ConvenioServicio;
use App\Models\Vigencia\HistorialAsegurado;
use App\Models\Vigencia\Preafiliacion;
use App\Services\ListadoAsegurados;
use App\Services\ObtenerPoblacion;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\Else_;

class PoblacionAseguradaBusqueda extends Component
{
    //GENERAAL 
    public $datos = [];
    public $institucionesTitular = [];
    // DATOS PERSONALES
    public $estado_vigencia, $matricula_seguro, $ci, $edad, $nombreCompleto, $nombres, $p_apellido, $s_apellido;
    public $sexo, $fecha_nacimiento, $tipo_asegurado, $ultimo_tipo_poblacion, $alergias, $grupo_sanguineo, $foto, $institucion;
    public $color_asegurado, $color_texto;
    public $id_persona;

    // DATOS REFENRECIALES

    public $telefono, $telefono_referencia, $direccion, $nro_vivienda, $zona, $observaciones, $tieneDatosRerenciales = false;
    public $fecha_emision;
    public $fecha_vencimiento;

    public $nombre_referencia;
    public $estado_carnet;

    //DATOS TITULAR CUANDO ES BENEFICIARIO
    public $ciTitular;
    public $nombreTitular;
    public $matriculaTitular;
    public $institucionTitular;
    public $telefonoTitular;

    //LIMITACIONES DE CONVENIOS
    public $limitaciones;
    public $listaPersonas;

    //PARA LA BUSQUEDA NUEVA
    public $busquedaExistosa = false;
    public $multiplesResultados = false;
    public $noExiste = false;
    public $existePersona;
    public $carnetBusqueda;
    public $complementoBusqueda;
    public $matriculaBusqueda;
    public $nombresBusqueda;
    public $pApellidoBusqueda;
    public $sApellidoBusqueda;
    // datos baja institucional
    public $tieneBajaInstitucional = false, $titularidad, $tipo_rentista, $fecha_afiliacion, $fecha_ingreso, $es_institucional, $vigencia_titular;
    public $fecha_inicio_contrato, $fecha_fin_contrato, $dias_vigencia_contrato;
    public $datosBaja = [], $titularDependientePreafiliacion;
    public $nombreDependiente, $ciDependiente, $matriculaDependiente, $validez_preafiliacon, $fecha_fin_prafiliacion, $fecha_inicio_prafiliacion;
    public $institucion_preafiliado;
    // datos de convenio 
    public $datosInterior, $datosEmergencia;
    public $carrera, $facultad, $mensaje_estado_estudiante;
    public $mostrarImprimir;
    public $poblacion_actual, $buscador_poblacion;
    public function render()
    {
        return view('livewire.Vigencia.poblacion-asegurada.poblacion-asegurada-busqueda');
    }

    // public function mount()
    // {
    //     $hashRuta = $this->ruta_hash_pdf(1);
    //     dd($hashRuta);
    // }

    public function buscarAsegurado()
    {
        //dd("aqui");
        // $this->mostrarImprimir = true;
        $this->busquedaExistosa = false;
        $this->multiplesResultados = false;
        $this->noExiste = false;
        $carnetBusqueda = trim($this->carnetBusqueda) == '' ? null : mb_strtoupper(trim($this->carnetBusqueda));
        $complementoBusqueda = trim($this->complementoBusqueda) == '' ? null : mb_strtoupper(trim($this->complementoBusqueda));
        $matriculaBusqueda = trim($this->matriculaBusqueda) == '' ? null : mb_strtoupper(trim($this->matriculaBusqueda));
        $nombresBusqueda = trim($this->nombresBusqueda) == '' ? null : mb_strtoupper(trim($this->nombresBusqueda));
        $pApellidoBusqueda = trim($this->pApellidoBusqueda) == '' ? null : mb_strtoupper(trim($this->pApellidoBusqueda));
        $sApellidoBusqueda = trim($this->sApellidoBusqueda) == '' ? null : mb_strtoupper(trim($this->sApellidoBusqueda));

        $this->existePersona = Persona::query()
            ->when($carnetBusqueda, function ($query) use ($carnetBusqueda) {
                return $query->where('ci', 'LIKE', '%' . $carnetBusqueda . '%');
            })
            ->when($complementoBusqueda, function ($query) use ($complementoBusqueda) {
                return $query->where('complemento', 'LIKE', '%' . $complementoBusqueda . '%');
            })
            ->when($matriculaBusqueda, function ($query) use ($matriculaBusqueda) {
                return $query->where('matricula_seguro', 'LIKE', '%' . $matriculaBusqueda . '%');
            })
            ->when($nombresBusqueda, function ($query) use ($nombresBusqueda) {
                return $query->where('nombres', 'LIKE', '%' . $nombresBusqueda . '%');
            })
            ->when($pApellidoBusqueda, function ($query) use ($pApellidoBusqueda) {
                return $query->where('p_apellido', 'LIKE', '%' . $pApellidoBusqueda . '%');
            })
            ->when($sApellidoBusqueda, function ($query) use ($sApellidoBusqueda) {
                return $query->where('s_apellido', 'LIKE', '%' . $sApellidoBusqueda . '%');
            })
            // ->whereNot('id_tipo_asegurado', 0)
            // ->where('afiliado', true)
            ->with(['tipoAseguradoPersona'])
            ->get();

        if (count($this->existePersona) > 1) {
            //  dd($this->existePersona);
            $this->multiplesResultados = true;
        } elseif (count($this->existePersona) == 1) {
            $this->search_qr($this->existePersona[0]->clave_unica);
        } else {
            $this->dispatch('noAseguradoRegular');
            $this->existePersona = [];
        }
    }


    public function search_qr($claveUnica)
    {
        //dd($claveUnica);
        // $this->mostrarImprimir = true;
        $this->busquedaExistosa = true;
        $this->resetearVariables();

        $busqueda = new BusquedaClaveUnica();
        $buscar = new ObtenerPoblacion();

        $this->datos = $busqueda->busquedaHistorialAseguradoNuevo($claveUnica);
      //  dd($this->datos);
        $this->institucionesTitular = [];
        $this->limitaciones = null;
        //dd($this->datos);
        if ($this->datos == []) {
            $this->dispatch('noAfiliadoNoVigente');
            $this->busquedaExistosa = false;
            $claveUnica = null;
        } else {
            $this->obtenerDatosPersonales($this->datos);
            // ---  DATOS DEL CARNET  
            $this->obtenerDatosCarnet($this->datos['id']);
            // ----- DATOS DE LA BAJA INSTITUCIONAL
            $tieneBajaInstitucional = BajaInstitucional::with('institucionBajaInstitucional', 'motivoBajaBajaInstitucional')
                ->where('id_persona', $this->datos['id'])->get();
            if ($tieneBajaInstitucional->isNotEmpty()) {
                $this->tieneBajaInstitucional = true;
                $this->obtenerDatosBaja($tieneBajaInstitucional);
            } else {
                $this->tieneBajaInstitucional = false;
            }
            // DATOS PARA TITULAR 
            $tipo_asegurado_actual = $this->datos['tipo_asegurado'];
            $ultima_poblacion = $this->datos['ultimo_tipo_poblacion'];
            // dd($tipo_asegurado_actual, $ultima_poblacion);
            if ($this->tipo_asegurado != 'NO ASEGURADO' && $this->datos['id_corresponde']) {
                $this->mostrarImprimir = true;
            }
            $this->poblacion_actual = $tipo_asegurado_actual !== 'NO ASEGURADO'
                ? $tipo_asegurado_actual
                : $ultima_poblacion;

            //dd($tipo_asegurado_actual, $ultima_poblacion, $this->poblacion_actual);
            $this->buscador_poblacion = $this->poblacion_actual;
            if ($this->buscador_poblacion === 'TITULAR DEL INTERIOR' || $this->buscador_poblacion === 'BENEFICIARIO DEL INTERIOR') {
                $this->buscador_poblacion = 'INTERIOR';
            } elseif (Str::startsWith($this->buscador_poblacion, 'BENEFICIARIO')) {
                $this->buscador_poblacion = 'BENEFICIARIOS';
            }
            // dd($tipo_asegurado_actual, $ultima_poblacion, $this->poblacion_actual, $this->buscador_poblacion);
            if ($this->datos['id_corresponde']) {
                switch ($this->buscador_poblacion) {
                    case 'TITULAR':
                        $this->mostrarDatosTitular($this->datos['id_corresponde']);
                        break;
                    case 'ESTUDIANTE':
                        $this->mostrarDatosEstudiante($this->datos['id_corresponde']);
                        break;
                    case 'PREAFILIADO TITULAR':
                        $this->mostrarDatosPreafiliado($this->datos['id']);
                        break;
                    case 'PREAFILIADO BENEFICIARIO':
                        $this->mostrarDatosPreafiliado($this->datos['id']);
                        break;

                    case 'INTERIOR':
                        $this->mostrarDatosInterior($this->datos['id']);
                        break;
                    case 'TITULAR CONVENIO':
                        $this->mostrarDatosConvenio($this->datos['id']);
                        break;

                    case 'BENEFICIARIOS':
                        $this->mostrarDatosBeneficiarios($this->datos['id']);
                        break;
                }
            }
        }
    }

    public  function resetearVariables()
    {
        $this->reset(
            'tieneBajaInstitucional',
            'datosEmergencia',
            'datosInterior',
            'datosBaja',
            'tieneDatosRerenciales',
            'es_institucional',
            'institucionesTitular',
            'mostrarImprimir',
            'color_asegurado'
        );
        $this->carnetBusqueda = null;
        $this->matriculaBusqueda = null;
        $this->complementoBusqueda = null;
        $this->nombresBusqueda = null;
        $this->pApellidoBusqueda = null;
        $this->sApellidoBusqueda = null;
        $this->grupo_sanguineo = null;
        $this->alergias = null;
        $this->telefono = null;
        $this->nombre_referencia =  null;
        $this->telefono_referencia =  null;
        $this->direccion =  null;
        $this->nro_vivienda =  null;
        $this->zona = null;
        $this->observaciones =  null;
        $this->facultad = null;
        $this->carrera = null;
        $this->mensaje_estado_estudiante = null;
        $this->fecha_afiliacion = null;
        $this->fecha_ingreso = null;
    }

    public function obtenerDatosPersonales($modelo)
    {
        // dd($modelo);
        $this->id_persona = $modelo['id'];
        if ($modelo['id_dept_exp'] == 10) {
            $this->ci = $modelo['ci'];
        } else {
            $this->ci = $modelo['ci'] . ' ' . $modelo['dept_exp'];
        }
        $this->matricula_seguro = $modelo['matricula_seguro'];

        $this->nombreCompleto = implode(' ', array_filter([$modelo['p_apellido'], $modelo['s_apellido'], $modelo['nombres']]));
        $this->edad = Carbon::parse($modelo['fecha_nacimiento'])->age;
        $this->sexo = $modelo['sexo'];
        $this->fecha_nacimiento = Carbon::parse($modelo['fecha_nacimiento'])->format('d/m/Y');
        $this->tipo_asegurado = $modelo['tipo_asegurado'];
        $this->ultimo_tipo_poblacion = $modelo['ultimo_tipo_poblacion'];
        $this->foto = $modelo['foto'] ?? '';
        $this->estado_vigencia = $modelo['estado_vigencia'] ?? 'NO VIGENTE';
        $this->alergias = $modelo['alergias'];
        $this->grupo_sanguineo = $modelo['grupo_sanguineo'] ?? '';
        $this->institucion = $modelo['nombre_institucion'] ?? '';
    }

    public function obtenerDatosBaja($datosBajaInsitucional)
    {
        // dd($datosBajaInsitucional);
        if ($datosBajaInsitucional->isNotEmpty()) {
            foreach ($datosBajaInsitucional as $key => $datos) {
                // dd($this->datosBaja);
                if ($datos->id_tipo_asegurado == 1) {
                    $this->datosBaja[] = [
                        'institucion_baja' => $datos->institucionBajaInstitucional?->nombre ?: 'VOLUNTARIO',
                        'fecha_baja_inst' => $datos->fecha_baja_inst ?: '',
                        'fecha_baja_ssu' => $datos->fecha_baja_ssu ?: '',
                        'motivo_baja' => $datos->motivoBajaBajaInstitucional->motivo_baja ?: '',
                        'descripcion_motivo' => $datos->descripcion_motivo ?: '',
                        'nro_documento' => $datos->nro_documento_referencia ?: 'SIN DATO',
                    ];
                } elseif ($datos->id_tipo_asegurado == 11) {
                    $this->datosBaja[] = [
                        'institucion_baja' => $datos->institucionBajaInstitucional?->nombre ?: 'UMSA ESTUDIANTE',
                        'fecha_baja_inst' => $datos->fecha_baja_inst ?: '',
                        'fecha_baja_ssu' => $datos->fecha_baja_ssu ?: '',
                        'motivo_baja' => $datos->motivoBajaBajaInstitucional->motivo_baja ?: '',
                        'descripcion_motivo' => $datos->descripcion_motivo ?: '',
                        'nro_documento' => $datos->nro_documento_referencia ?: 'SIN DATO',
                    ];
                } else {
                    $this->datosBaja[] = [
                        'institucion_baja' => $datos->institucionBajaInstitucional?->nombre ?: '',
                        'fecha_baja_inst' => $datos->fecha_baja_inst ?: '',
                        'fecha_baja_ssu' => $datos->fecha_baja_ssu ?: '',
                        'motivo_baja' => $datos->motivoBajaBajaInstitucional->motivo_baja ?: '',
                        'descripcion_motivo' => $datos->descripcion_motivo ?: '',
                        'nro_documento' => $datos->nro_documento_referencia ?: 'SIN DATO',
                    ];
                }
            }
            //dd($this->datosBaja);
        }
    }

    public function obtenerDatosReferenciales($modelo, $relacionWith)
    {
        // dd('entr');
        if ($modelo) {
            $this->tieneDatosRerenciales = true;
            $this->telefono = $modelo->telefono ?? 'SIN DATO';
            $this->nombre_referencia = $modelo->nombre_referencia ?? 'SIN DATO';
            $this->telefono_referencia = $modelo->telefono_referencia ?? 'SIN DATO';
            $this->direccion = $modelo->{$relacionWith}->direccion ?? 'SIN DATO';
            $this->nro_vivienda = $modelo->{$relacionWith}->nro_vivienda ?? 'SIN DATO';
            $this->zona = $modelo->{$relacionWith}->zonaResidencia->nombre ?? 'SIN DATO';
            $this->observaciones = $modelo->observacion;
            // $this->observaciones = $modelo->observacion ?? $modelo->observaciones ?? 'SIN OBSERVACIONES';
            // dd($this->tieneDatosRerenciales);
        }
        $this->observaciones = 'SIN OBSERVACIONES';
    }

    public function obtenerDatosCarnet($id_persona)
    {
        $datosCarnet = HCarnet::where('id_persona', $this->datos['id'])
            ->orderBy('id', 'desc')
            ->first();
        // dd($datosCarnet);
        if ($datosCarnet) {
            $this->fecha_emision = Carbon::parse($datosCarnet->fecha_emision)->format('d/m/Y');

            if ($datosCarnet->indefinido == true) {
                $this->fecha_vencimiento = 'INDEFINIDO';
                $this->estado_carnet = 'VIGENTE';
            } else {
                $this->fecha_vencimiento = Carbon::parse($datosCarnet->fecha_vencimiento)->format('d/m/Y');
                if ($datosCarnet->fecha_vencimiento <= now()) {
                    $this->estado_carnet = 'CADUCO';
                } else {
                    $this->estado_carnet = 'VIGENTE';
                }
            }
        } else {
            $this->fecha_emision = 'SIN DATO';
            $this->fecha_vencimiento = 'SIN DATO';
            $this->estado_carnet = 'SIN DATO';
        }
    }

    public function mostrarDatosTitular($id_corresponde)
    {
        // dd($id_corresponde);
        $datosTitular = Titular::with(['titularResidencia.zonaResidencia'])
            ->find($this->datos['id_corresponde']);
        //dd($datosTitular);
        $this->institucionesTitular = [];
        $this->fecha_ingreso = $datosTitular->fecha_afiliacion ?? '';
        $this->fecha_afiliacion = $datosTitular?->fecha_reafiliacion ?? $datosTitular?->fecha_afiliacion;
        $this->tipo_rentista = $datosTitular ? $datosTitular->tipo_rentista : 'null';
        if ($datosTitular->tipo_titular === true) {
            $this->es_institucional = true;
            $this->institucionesTitular = [];

            $datosTitularInstitucion = TitularInstitucion::with(['titularInstitucionConInstitucion'])
                ->where('id_titular', $datosTitular->id)
                ->orderBy('estado', 'desc')
                ->orderBy('id', 'asc')
                ->get();
            //dd($datosTitularInstitucion);
            if ($datosTitularInstitucion->isNotEmpty()) {

                foreach ($datosTitularInstitucion as $key => $datosInstitucion) {
                    $this->institucionesTitular[] = [
                        'nombre' => $datosInstitucion->titularInstitucionConInstitucion->nombre,
                        'estado' => $datosInstitucion->estado,
                        'titularidad' =>  $datosInstitucion->titularidad ?: '',
                    ];
                }
            }
        } else {
            $this->es_institucional = false;
            $this->institucionesTitular[] = [
                'nombre' => 'APORTANTE VOLUNTARIO',
                'estado' => '',
                'titularidad' => '',
            ];
            $datosContratovoluntario = ContratoVoluntario::where('id_persona', $this->datos['id'])
                ->orderBy('id', 'desc')
                ->first();
            //dd($datosContratovoluntario);
            if ($datosContratovoluntario) {
                $this->fecha_inicio_contrato = $datosContratovoluntario->fecha_inicio_contrato;
                $this->fecha_fin_contrato = $datosContratovoluntario->fecha_fin_contrato;
                $fechaFin = Carbon::parse($this->fecha_fin_contrato);
                $hoy = Carbon::today();
                $this->dias_vigencia_contrato = max(0, $hoy->diffInDays($fechaFin, false));
            } else {
                $this->fecha_inicio_contrato = '';
                $this->fecha_fin_contrato = '';
                $this->dias_vigencia_contrato = 'SIN DATO';
            }
            // dd($this->dias_vigencia_contrato);
        }
        $this->obtenerDatosReferenciales($datosTitular, 'titularResidencia');
        if ($this->datos['estado_vigencia'] !== 'NO VIGENTE') {
            /* if ($this->datos['codigo_titular'] != null) {
                            $this->matricula_seguro .= '-' . $this->datos['codigo_titular'];
                        } */
            if ($datosTitular->permanente) {
                $this->color_asegurado = "#e0241c";
                $this->color_texto = "#FFFFFF";
            } else {
                $this->color_asegurado = "#236cb6";
                $this->color_texto = "#FFFFFF";
            }
            if ($datosTitular->tipo_rentista == 'TITULAR-SORTEO') {
                $this->color_asegurado = "#839192";
                $this->color_texto = "#FFFFFF";
            }
        } else {
            //$this->tieneBajaInstitucional = true;
            $this->color_asegurado = "#e5f0f9";
            $this->color_texto = "#000000";
        }
    }
    public function mostrarDatosEstudiante($id_corresponde)
    {
        $this->color_asegurado = "#00b9ef";
        $this->color_texto = "#FFFFFF";
        $datosEstudiante = Estudiante::with(['estudianteResidencia.zonaResidencia'])
            ->find($this->datos['id_corresponde']);
        // dd($datosEstudiante);
        $this->obtenerDatosReferenciales($datosEstudiante, 'estudianteResidencia');
        $id_carrera = $datosEstudiante->id_carrera ?? null;
        $datoCarrera = $id_carrera ? Carrera::find($id_carrera) : null;

        $this->carrera = $datoCarrera->nombre ?? 'SIN ACTUALIZAR';
        if ($datoCarrera && $datoCarrera->id_facultad) {
            $facultadDato = Facultad::find($datoCarrera->id_facultad);
            $facultadDato = Facultad::find($datoCarrera->id_facultad);
            $this->facultad = $facultadDato->nombre;
        } else {
            $this->facultad = 'SIN ACTUALIZAR';
        }

        $this->fecha_ingreso = $datosEstudiante->fecha_afiliacion ?? '';
        $this->fecha_afiliacion = $datosEstudiante?->fecha_reafiliacion ?? $datosEstudiante?->fecha_afiliacion ?? '';
        $this->fecha_emision = $datosEstudiante->estado_validar === 'AFILIADO' && $datosEstudiante->estado_vigencia ? Carbon::parse($datosEstudiante->fecha_afiliacion)->format('d/m/Y') : 'NO AFILIADO';
        $this->fecha_vencimiento = $datosEstudiante->estado_validar === 'AFILIADO' && $datosEstudiante->estado_vigencia ? Carbon::parse($datosEstudiante->fecha_fin_vigencia)->format('d/m/Y') : 'NO AFILIADO';
        if ($datosEstudiante->estado_vigencia) {
            if (Carbon::now()->lte(Carbon::parse($datosEstudiante->fecha_fin_vigencia))) {
                if ($datosEstudiante->estado_validar === 'AFILIADO') {
                    $this->estado_carnet = 'VIGENTE';
                } else {
                    $this->estado_carnet = 'PENDIENTE';
                }
            } else {
                $this->estado_carnet = 'CADUCO';
            }
        } else {
            $this->estado_carnet = ' SIN VIGENCIA';
        }

        if ($this->datos['tipo_asegurado'] != 'NO ASEGURADO' && $datosEstudiante->estado_validar != 'AFILIADO') {
            $this->mensaje_estado_estudiante = 'En proceso de afiliación — Estado del trámite: ' . $datosEstudiante->estado_validar;
        }
    }

    public function mostrarDatosPreafiliado($id_persona)
    {
        $this->color_asegurado = "#e5f0f9";
        $this->color_texto = "#000000";
        $datosPreafiliacion = Preafiliacion::with('titularPreafiliacion', 'tipoConvenioPreafiliacion', 'institucionPreafiliacion')
            ->where('id_persona', $this->datos['id'])
            ->where('estado', true)
            ->first();
        //dd($datosPreafiliacion);
        if ($datosPreafiliacion) {
            $this->fecha_inicio_prafiliacion = $datosPreafiliacion->fecha_inicio;
            $this->fecha_fin_prafiliacion = $datosPreafiliacion->fecha_fin;
            $this->observaciones = $datosPreafiliacion->observaciones;
            $fechaFin = Carbon::parse($this->fecha_fin_contrato);
            $hoy = Carbon::today();
            $this->dias_vigencia_contrato = max(0, $hoy->diffInDays($fechaFin, false));
            $this->validez_preafiliacon = max(0, $hoy->diffInDays($fechaFin, false));
            $this->institucion_preafiliado = $this->datos['nombre_institucion'] ?: '';
            $datosTitularDependiente = Titular::where('id', $datosPreafiliacion->id_titular)
                ->first();
            if ($datosTitularDependiente) {
                $data = new ListadoAsegurados();
                $this->titularDependientePreafiliacion = $data->busquedaAfiliadoPorId($datosTitularDependiente->id_persona);
                // dd($this->titularDependientePreafiliacion);
                // DATOS TITULAR DEPENDIENTE DE BENEFICIARIO CON PREAFILIACON
                $this->nombreDependiente = $this->titularDependientePreafiliacion['nombres'] . ' ' . $this->titularDependientePreafiliacion['p_apellido'] . ' ' . $this->titularDependientePreafiliacion['s_apellido'];
                $this->ciDependiente = $this->titularDependientePreafiliacion['ci'];
                $this->matriculaDependiente = $this->titularDependientePreafiliacion['matricula_seguro'];
                $this->institucionesTitular[] = [
                    'nombre' => $this->titularDependientePreafiliacion['nombre_institucion'],
                    'estado' =>  '',
                    'titularidad' => '',
                ];
            }
        }
    }

    public function mostrarDatosInterior($id_persona)
    {
        $buscar = new ObtenerPoblacion();
        $this->color_asegurado = "#01ac01";
        $this->color_texto = "#FFFFFF";
        $this->datosInterior = $buscar->obtenerTodosLosConveniosInterior($this->datos['id']);
        // dd($this->datosInterior);
        $datosConvenio = AutorizacionInterior::where('id', $this->datos['id_corresponde'])
            ->orderBy('id', 'desc')
            ->first();
        //dd($datosConvenio);
        $this->fecha_emision = Carbon::parse($datosConvenio->fecha_inicio)->format('d/m/Y');
        $this->fecha_vencimiento = Carbon::parse($datosConvenio->fecha_fin)->format('d/m/Y');
        if (Carbon::now()->lte(Carbon::parse($datosConvenio->fecha_fin))) {
            $this->estado_carnet = 'VIGENTE';
        } else {
            $this->estado_carnet = 'CADUCO';
        }
        if ($datosConvenio->telefono !== null || $datosConvenio->datos_referencia !== null || $datosConvenio->telefono_referencia !== null || $datosConvenio->direccion_referencia !== null) {
            $this->tieneDatosRerenciales = true;
            // $this->obtenerDatosReferenciales($datosConvenio, '');
            $this->telefono = $datosConvenio->telefono ? $datosConvenio->telefono : 'NO REGISTRADO';
            $this->nombre_referencia = $datosConvenio->datos_referencia ? $datosConvenio->datos_referencia : 'NO REGISTRADO';
            $this->telefono_referencia = $datosConvenio->telefono_referencia ? $datosConvenio->telefono_referencia : 'NO REGISTRADO';
            $this->direccion = $datosConvenio->direccion_referencia ? $datosConvenio->direccion_referencia : 'NO REGISTRADO';
            $this->zona = 'NO REGISTRADO';
        }
    }

    public function mostrarDatosConvenio($id_persona)
    {
        $buscar = new ObtenerPoblacion();
        $this->datosEmergencia = $buscar->obtenerTodosLosConveniosEmergencia($this->datos['id']);
        $this->color_asegurado = "#01ac01";
        $this->color_texto = "#FFFFFF";
        $datosConvenioEmergencia = Convenio::where('id_persona', $this->datos['id'])->orderBy('id', 'desc')->first();
        //dd($datosConvenioEmergencia);
        $this->telefono = 'NO REGISTRADO';
        $this->nombre_referencia = 'NO REGISTRADO';
        $this->telefono_referencia = 'NO REGISTRADO';
        $this->direccion = 'NO REGISTRADO';
        $this->zona = 'NO REGISTRADO';

        $this->fecha_emision = Carbon::parse($datosConvenioEmergencia->fecha_inicio)->format('d/m/Y');
        $this->fecha_vencimiento = Carbon::parse($datosConvenioEmergencia->fecha_fin)->format('d/m/Y');
        if (Carbon::now()->lte(Carbon::parse($datosConvenioEmergencia->fecha_fin))) {
            $this->estado_carnet = 'VIGENTE';
        } else {
            $this->estado_carnet = 'CADUCO';
        }
        $this->limitaciones = $this->limitacionesConveniosEmergencia($datosConvenioEmergencia->id);
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

    public function mostrarDatosBeneficiarios($id_persona)
    {
        $busqueda = new BusquedaClaveUnica();
        $buscar = new ObtenerPoblacion();
        $datosBeneficiario = Beneficiario::where('id_persona', $id_persona)
            ->with(['beneficiarioResidencia.zonaResidencia'])
            ->orderBy('id', 'desc')
            ->first();
        //dd($datosBeneficiario);
        if ($datosBeneficiario->permanente) {
            $this->color_asegurado = "#d4cd00";
            $this->color_texto = "#FFFFFF";
        } else {
            $this->color_asegurado = "#236cb6"; // azul
            $this->color_texto = "#FFFFFF";
        }
        $this->fecha_ingreso = $datosBeneficiario->fecha_afiliacion ?? '';
        $this->fecha_afiliacion = $datosBeneficiario->fecha_reafiliacion ?: $datosBeneficiario->fecha_afiliacion;


        $this->obtenerDatosReferenciales($datosBeneficiario, 'beneficiarioResidencia');

        $informacionTitular = Titular::with('titularPersona')
            ->find($datosBeneficiario->id_titular);
        //dd($informacionTitular);
        $this->ciTitular = $informacionTitular->titularPersona->ci;
        if ($informacionTitular->titularPersona->complemento != null) {
            $this->ciTitular .= $informacionTitular->titularPersona->complemento;
        }
        $this->nombreTitular = implode(' ', array_filter([
            $informacionTitular->titularPersona->p_apellido,
            $informacionTitular->titularPersona->s_apellido,
            $informacionTitular->titularPersona->nombres
        ]));
        $this->matriculaTitular = $informacionTitular->titularPersona->matricula_seguro;
        $this->vigencia_titular = $informacionTitular->estado_vigencia;
        $this->telefonoTitular = $informacionTitular->telefono;
        if ($informacionTitular->tipo_titular === true) {
            $this->es_institucional = true;
            $this->institucionesTitular = [];

            $datosTitularInstitucion = TitularInstitucion::with(['titularInstitucionConInstitucion'])
                ->where('id_titular', $informacionTitular->id)
                ->orderBy('estado', 'desc')
                ->orderBy('id', 'asc')
                ->get();
            // dd($datosTitularInstitucion);
            if ($datosTitularInstitucion->isNotEmpty()) {

                foreach ($datosTitularInstitucion as $key => $datosInstitucion) {
                    $this->institucionesTitular[] = [
                        'nombre' => $datosInstitucion->titularInstitucionConInstitucion->nombre,
                        'estado' => $datosInstitucion->estado,
                        'titularidad' =>  $datosInstitucion->titularidad ?: '',
                    ];
                }
            }
        } else {
            $this->es_institucional = false;
            $this->institucionesTitular[] = [
                'nombre' => 'APORTANTE VOLUNTARIO',
                'estado' => '',
                'titularidad' => '',
            ];
            $datosContratovoluntario = ContratoVoluntario::where('id_persona', $this->datos['id'])
                ->orderBy('id', 'desc')
                ->first();
            //dd($datosContratovoluntario);
            if ($datosContratovoluntario) {
                $this->fecha_inicio_contrato = $datosContratovoluntario->fecha_inicio_contrato;
                $this->fecha_fin_contrato = $datosContratovoluntario->fecha_fin_contrato;
                $fechaFin = Carbon::parse($this->fecha_fin_contrato);
                $hoy = Carbon::today();
                $this->dias_vigencia_contrato = max(0, $hoy->diffInDays($fechaFin, false));
            } else {
                $this->fecha_inicio_contrato = '';
                $this->fecha_fin_contrato = '';
                $this->dias_vigencia_contrato = 'SIN DATO';
            }
            // dd($this->dias_vigencia_contrato);
        }
    }



    public function verReportes()
    {
        // vigencia/poblacionAsegurada/reportesPdf/{row}

        $this->js('window.open("reportesPdf/' . $this->id_persona . '", "_blank")');
    }

    public function ruta_hash_pdf($id_persona)
    {
        $hashRutaService = new HashRutasService();
        return $hashRutaService->url_hash($id_persona, 'vigencia.pdf_tapa_hc.reporte', 'id_persona');
    }
    public function imprimir_tapa_hc()
    {
        $ruta = $this->ruta_hash_pdf($this->id_persona);
        // para que se descargue el pdf certificado   
        $this->js('window.open("' . $ruta . '", "_blank")');
    }
}
