<?php

namespace App\Livewire\Vigencia\BajasInstitucionales;

use App\Jobs\ProcesarBajaBeneficiarioAsussJob;
use App\Jobs\ProcesarBajaEstudianteAsussJob;
use App\Jobs\ProcesarBajaTitularAsussJob;
use App\Models\Administracion\Persona;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Estudiante;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\User;
use App\Models\Vigencia\BajaInstitucional;
use App\Models\Vigencia\HistorialAsegurado;
use App\Models\Vigencia\MotivoBaja;
use App\Services\GrupoFamiliarPersona;
use App\Services\ListadoAsegurados;
use App\Services\VerificarDarBaja;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;

class BajasInstitucionalesRegister extends Component
{
    public $busquedaExistosa = false;

    public $multiplesResultados = false;

    public $carnetBusqueda;

    public $complementoBusqueda;

    public $matriculaBusqueda;

    public $existePersona = [];

    public $motivosBaja = [];

    public $datosAfiliacionPersona;

    public $foto;

    public $estado_vigencia;

    public $edad;

    public $matricula;

    public $codigoInstitucional;

    public $nombreInsPatronal;

    public $nombre;

    public $carnet;

    public $sexo;

    public $fecha_nacimiento;

    public $tipoAsegurado;

    public $id_tipo_asegurado;

    #[Validate]
    public $fecha_baja_inst;

    public $fecha_baja_ssu;

    public $id_persona;

    #[Validate]
    public $id_motivo_baja;

    public $id_user_created;

    public $descripcion_motivo;

    #[Validate]
    public $nro_documento_referencia;

    public $baja_definitiva;

    public $noExiste = false;

    public $fechaRegistro;

    public $fechaReafiliacion;

    public $vencimientoCarnet;

    //PARA LA NUEVA BUSQUEDA

    public $pApellidoBusqueda;

    public $sApellidoBusqueda;

    public $nombresBusqueda;

    //PARA AGREGAR INSTITUCION

    public $idInstitucionBaja;

    public $numeroInstituciones;

    public $idCorresponde;

    protected $listeners = ['registrarBaja'];

    public function rules()
    {
        return [
            'fecha_baja_inst' => 'required',
            'id_motivo_baja' => 'required',
            'nro_documento_referencia' => 'required',
            'idInstitucionBaja' => 'required',
        ];
    }

    public function render()
    {
        return view('livewire.Vigencia.bajas-institucionales.bajas-institucionales-register');
    }

    public function mount()
    {
        $this->baja_definitiva = false;
        $this->motivosBaja = MotivoBaja::all();
        $this->fechaRegistro = Carbon::now()->format('Y-m-d');
    }

    public function atras()
    {
        return redirect()->route('vigencia.bajasinstitucionales.index');
    }

    public function cancelar()
    {
        $this->dispatch('cancelarRegistro');

        return redirect()->route('vigencia.bajasinstitucionales.index');
    }

    public function darBajaUsuario($idUsuario)
    {
        $usuario = User::find($idUsuario);
        if ($usuario) {
            $usuario->estado = false;
            if ($this->tipoAsegurado != 'ESTUDIANTE') {
                $usuario->syncRoles([]);
                $usuario->syncPermissions([]);
            }
            $usuario->id_user_updated = auth()->user()->id;
            $usuario->save();
        }
    }

    public function buscarAsegurado()
    {
        $this->busquedaExistosa = false;
        $this->multiplesResultados = false;
        $this->noExiste = false;
        $carnetBusqueda = trim($this->carnetBusqueda) == '' ? null : mb_strtoupper(trim($this->carnetBusqueda));
        $complementoBusqueda = trim($this->complementoBusqueda) == '' ? null : mb_strtoupper(trim($this->complementoBusqueda));
        $matriculaBusqueda = trim($this->matriculaBusqueda) == '' ? null : mb_strtoupper(trim($this->matriculaBusqueda));
        $nombresBusqueda = trim($this->nombresBusqueda) == '' ? null : mb_strtoupper(trim($this->nombresBusqueda));
        $pApellidoBusqueda = trim($this->pApellidoBusqueda) == '' ? null : mb_strtoupper(trim($this->pApellidoBusqueda));
        $sApellidoBusqueda = trim($this->sApellidoBusqueda) == '' ? null : mb_strtoupper(trim($this->sApellidoBusqueda));

        //dd($carnetBusqueda, $complementoBusqueda, $matriculaBusqueda, $nombresBusqueda, $pApellidoBusqueda, $sApellidoBusqueda);

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
            ->whereNotIn('id_tipo_asegurado', [0, 8, 9, 10])
            ->where('afiliado', true)
            ->with(['tipoAseguradoPersona'])
            ->get();

        if (count($this->existePersona) > 1) {
            $this->multiplesResultados = true;
        } elseif (count($this->existePersona) == 1) {
            $this->seleccionarPersona($this->existePersona[0]->id);
        } else {
            $this->dispatch('noAseguradoRegular');
            $this->existePersona = [];
        }
    }

    public function seleccionarPersona($idPersona)
    {
        $this->busquedaExistosa = true;
        $this->carnetBusqueda = null;
        $this->matriculaBusqueda = null;
        $this->complementoBusqueda = null;
        $this->nombresBusqueda = null;
        $this->pApellidoBusqueda = null;
        $this->sApellidoBusqueda = null;

        $datos = new ListadoAsegurados();
        $datosAfiliado = $datos->busquedaAfiliadoPorIdParaBajaInstitucionalNuevo($idPersona);

        //dd($datosAfiliado);

        if ($datosAfiliado['estado_vigencia'] == 'VIGENTE') {
            $this->id_persona = $datosAfiliado['id_persona'];
            $this->id_tipo_asegurado = $datosAfiliado['id_tipo_asegurado'];
            $this->foto = $datosAfiliado['foto'];
            $this->idCorresponde = $datosAfiliado['id_corresponde'];
            $this->estado_vigencia = $datosAfiliado['estado_vigencia'];
            $this->edad = Carbon::parse($datosAfiliado['fecha_nacimiento'])->age;
            $this->matricula = $datosAfiliado['matricula_seguro'];
            $this->codigoInstitucional = $datosAfiliado['instituciones'][0]['codigo_institucional'];
            $this->nombreInsPatronal = $datosAfiliado['instituciones'];
            $this->numeroInstituciones = count($datosAfiliado['instituciones']);
            if ($this->numeroInstituciones === 1) {

                $this->idInstitucionBaja = $datosAfiliado['instituciones'][0]['id_institucion'];
                if ($datosAfiliado['tipo_asegurado'] === 'ESTUDIANTE') {
                    $this->idInstitucionBaja = 100;
                }

                if ($datosAfiliado['instituciones'][0]['nombre_institucion'] === 'AFILIADO VOLUNTARIO') {
                    $this->idInstitucionBaja = 100;
                }
            }

            if ($datosAfiliado['p_apellido'] == null) {
                $this->nombre = $datosAfiliado['s_apellido'] . ' ' . $datosAfiliado['nombres'];
            } elseif ($datosAfiliado['s_apellido'] == null) {
                $this->nombre = $datosAfiliado['p_apellido'] . ' ' . $datosAfiliado['nombres'];
            } else {
                $this->nombre = $datosAfiliado['p_apellido'] . ' ' . $datosAfiliado['s_apellido'] . ' ' . $datosAfiliado['nombres'];
            }

            if ($datosAfiliado['complemento'] == null) {
                $this->carnet = $datosAfiliado['ci'];
            } else {
                $this->carnet = $datosAfiliado['ci'] . ' - ' . $datosAfiliado['complemento'];
            }

            $this->sexo = $datosAfiliado['sexo'] == 'F' ? 'FEMENINO' : 'MASCULINO';
            $this->fecha_nacimiento = $datosAfiliado['fecha_nacimiento'];
            $this->tipoAsegurado = $datosAfiliado['tipo_asegurado'];
            $this->fechaReafiliacion = $datosAfiliado['fecha_reafiliacion'];
            $this->vencimientoCarnet = $datosAfiliado['vencimiento_carnet'];
        } else {
            $this->busquedaExistosa = false;
            $this->dispatch('noVigente');
        }
    }

    public function calcularCesantia()
    {

        if ($this->baja_definitiva) {
            $this->fecha_baja_ssu = Carbon::parse($this->fecha_baja_inst)->format('Y-m-d');
        } else {
            $this->fecha_baja_ssu = Carbon::parse($this->fecha_baja_inst)->addDays(60)->format('Y-m-d');
        }
        //dd($this->fecha_baja_ssu);
    }

    public function registrarBaja()
    {
        //dd($this->id_persona);
        //dd($this->numeroInstituciones);
        $this->calcularCesantia();

        //dd($this->idInstitucionBaja);
        $this->validate();

        //$servicioGrupoFamiliar = new GrupoFamiliarPersona();

        //$listadoFamiliares = $servicioGrupoFamiliar->encontrarPersona($this->id_persona);

        //dd($listadoFamiliares);

        if ($this->idInstitucionBaja === 100) {
            $this->idInstitucionBaja = null;
        }

        //DB::beginTransaction();

        try {

            $bajaInstitucional = BajaInstitucional::create([
                'fecha_baja_inst' => $this->fecha_baja_inst,
                'fecha_baja_ssu' => $this->fecha_baja_ssu,
                'id_persona' => $this->id_persona,
                'id_motivo_baja' => $this->id_motivo_baja,
                'descripcion_motivo' => $this->descripcion_motivo,
                'nro_documento_referencia' => $this->nro_documento_referencia,
                'baja_definitiva' => $this->baja_definitiva,
                'registro_tratado' => $this->baja_definitiva,
                'id_tipo_asegurado' => $this->id_tipo_asegurado,
                'id_corresponde' => $this->idCorresponde,
                'id_institucion_baja' => $this->idInstitucionBaja,
                'id_user_created' => auth()->user()->id,
            ]);

            if ($bajaInstitucional) {

                if ($this->numeroInstituciones > 1 && $this->tipoAsegurado == 'TITULAR') {

                    if ($this->baja_definitiva == true) {
                        $institucionTitularBaja = TitularInstitucion::where('id_titular', $this->idCorresponde)
                            ->where('id_institucion', $this->idInstitucionBaja)
                            ->first();

                        if ($institucionTitularBaja) {
                            $institucionTitularBaja->estado = false;
                            $institucionTitularBaja->fecha_baja = $this->fecha_baja_ssu;
                            $institucionTitularBaja->id_user_updated = auth()->user()->id;
                            $institucionTitularBaja->save();
                        }
                        //DB::commit();
                        $this->dispatch('bajaRegistrada');

                        return redirect()->route('vigencia.bajasinstitucionales.index');
                    } else {
                        //DB::commit();
                        $this->dispatch('bajaRegistrada');

                        return redirect()->route('vigencia.bajasinstitucionales.index');
                    }
                } elseif ($this->numeroInstituciones <= 1 || $this->tipoAsegurado !== 'TITULAR') {
                    if ($this->tipoAsegurado == 'TITULAR') {

                        $datosTitular = Titular::where('id_persona', $this->id_persona)
                            ->where('estado_vigencia', 'VIGENTE')
                            ->orderBy('id', 'desc')
                            ->first();

                        if ($datosTitular) {
                            if ($this->baja_definitiva == true) {

                                //PARA LA TABLA HISTORIAL

                                $actualizacionHistorial = HistorialAsegurado::where('id_tipo_asegurado', $this->id_tipo_asegurado)
                                    ->where('id_corresponde', $datosTitular->id)
                                    ->where('id_persona', $this->id_persona)
                                    ->orderBy('id', 'desc')
                                    ->first();

                                if ($actualizacionHistorial) {
                                    $actualizacionHistorial->estado_vigencia = 'NO VIGENTE';
                                    $actualizacionHistorial->fecha_cesantia = $this->fecha_baja_inst;
                                    $actualizacionHistorial->fecha_baja = $this->fecha_baja_ssu;
                                    $actualizacionHistorial->id_user_updated = auth()->user()->id;
                                    $actualizacionHistorial->save();
                                }

                                //PARA EL GRUPO FAMILIAR

                                $servicioGrupoFamiliar = new GrupoFamiliarPersona();

                                $listadoFamiliares = $servicioGrupoFamiliar->encontrarPersona($this->id_persona);
                                //dd($listadoFamiliares);

                                if ($listadoFamiliares != []) {
                                    if (count($listadoFamiliares) > 1) {
                                        foreach ($listadoFamiliares as $familiar) {
                                            if ($familiar['id'] != $this->id_persona) {

                                                $actualizarBeneficiario = Beneficiario::where('id_persona', $familiar['id'])
                                                    ->where('id_titular', $datosTitular->id)
                                                    ->with('beneficiarioPersona')
                                                    ->orderBy('id', 'desc')
                                                    ->first();

                                                if ($actualizarBeneficiario) {

                                                    $bajaInstitucionalBeneficiario = BajaInstitucional::create([
                                                        'fecha_baja_inst' => $this->fecha_baja_inst,
                                                        'fecha_baja_ssu' => $this->fecha_baja_ssu,
                                                        'id_persona' => $familiar['id'],
                                                        'id_motivo_baja' => $this->id_motivo_baja,
                                                        'descripcion_motivo' => $this->descripcion_motivo . ' (BENEFICIARIO)',
                                                        'nro_documento_referencia' => $this->nro_documento_referencia,
                                                        'baja_definitiva' => $this->baja_definitiva,
                                                        'registro_tratado' => $this->baja_definitiva,
                                                        'id_tipo_asegurado' => $actualizarBeneficiario->beneficiarioPersona->id_tipo_asegurado,
                                                        'id_corresponde' => $actualizarBeneficiario->id,
                                                        'id_institucion_baja' => $this->idInstitucionBaja,
                                                        'id_user_created' => auth()->user()->id,
                                                    ]);

                                                    if ($bajaInstitucionalBeneficiario) {
                                                        //PARA LA TABLA HISTORIAL

                                                        $actualizacionHistorialBeneficiario = HistorialAsegurado::where('id_tipo_asegurado', $familiar['id_tipo_asegurado'])
                                                            ->where('id_corresponde', $actualizarBeneficiario->id)
                                                            ->where('id_persona', $familiar['id'])
                                                            ->orderBy('id', 'desc')
                                                            ->first();

                                                        if ($actualizacionHistorialBeneficiario) {
                                                            $actualizacionHistorialBeneficiario->estado_vigencia = 'NO VIGENTE';
                                                            $actualizacionHistorialBeneficiario->fecha_cesantia = $this->fecha_baja_inst;
                                                            $actualizacionHistorialBeneficiario->fecha_baja = $this->fecha_baja_ssu;
                                                            $actualizacionHistorialBeneficiario->id_user_updated = auth()->user()->id;
                                                            $actualizacionHistorialBeneficiario->save();
                                                        }

                                                        //MODIFICADO RECIEN EN LA TABLA BENEFICIARIO

                                                        $actualizarBeneficiario->vigencia = 'NO VIGENTE';
                                                        $actualizarBeneficiario->fecha_baja = $this->fecha_baja_ssu;
                                                        $actualizarBeneficiario->id_user_updated = auth()->user()->id;
                                                        $actualizarBeneficiario->save();

                                                        //PARA ACTUALIZAR A LA PERSONA

                                                        $actualizarPersonaBeneficiario = Persona::where('id', $familiar['id'])->first();

                                                        if ($actualizarPersonaBeneficiario) {
                                                            $actualizarPersonaBeneficiario->afiliado = false;
                                                            $actualizarPersonaBeneficiario->id_tipo_asegurado = 0;
                                                            $actualizarPersonaBeneficiario->id_user_updated = auth()->user()->id;
                                                            $actualizarPersonaBeneficiario->save();
                                                        }

                                                        //PARA EL USUARIO

                                                        $actualizarUsuario = User::where('id_persona', $familiar['id'])->first();

                                                        if ($actualizarUsuario) {
                                                            $this->darBajaUsuario($actualizarUsuario->id);
                                                        }

                                                        /*if ($actualizarUsuario) {
                                                            $actualizarUsuario->estado = false;
                                                            $actualizarUsuario->id_user_updated = auth()->user()->id;
                                                            $actualizarUsuario->save();
                                                        }*/

                                                        //SERVICIO DE REPLICA

                                                        $ciServicio = $actualizarPersonaBeneficiario->ci;

                                                        if ($actualizarPersonaBeneficiario->complemento != null) {
                                                            $ciServicio .= '-' . $actualizarPersonaBeneficiario->complemento;
                                                        }

                                                        $motivoServicio = 3;

                                                        if ($this->id_motivo_baja == 6) {
                                                            $motivoServicio = 4;
                                                        }

                                                        $bajas = [];
                                                        $bajas[] = [
                                                            'ci' => $ciServicio,
                                                            'id_estado_afiliado' => $motivoServicio,
                                                        ];

                                                        $regBajasGehma2 = new VerificarDarBaja();
                                                        $regBajasGehma2->bajasGehma2(['bajas' => $bajas]);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }

                                //PARA DAR DE BAJA A LAS INSTITUCIONES

                                $institucionesTitular = TitularInstitucion::where('id_titular', $datosTitular->id)->get();

                                if (count($institucionesTitular) > 0) {
                                    foreach ($institucionesTitular as $institucionTitular) {
                                        $institucionTitular->estado = false;
                                        $institucionTitular->fecha_baja = $this->fecha_baja_ssu;
                                        $institucionTitular->id_user_updated = auth()->user()->id;
                                        $institucionTitular->save();
                                    }
                                }

                                //FIN PARA DAR DE BAJA A LAS INSTITUCIONES

                                $datosTitular->estado_vigencia = 'NO VIGENTE';
                                $datosTitular->fecha_baja = $this->fecha_baja_ssu;
                                $datosTitular->id_user_updated = auth()->user()->id;
                                $datosTitular->save();

                                //PARA ACTUALIZAR A LA PERSONA
                                $actualizarPersona = Persona::where('id', $this->id_persona)->first();

                                if ($actualizarPersona) {
                                    $actualizarPersona->afiliado = false;
                                    $actualizarPersona->id_tipo_asegurado = 0;
                                    $actualizarPersona->id_user_updated = auth()->user()->id;
                                    $actualizarPersona->save();
                                }

                                //PARA EL USUARIO

                                $actualizarUsuario = User::where('id_persona', $this->id_persona)->first();

                                if ($actualizarUsuario) {
                                    $this->darBajaUsuario($actualizarUsuario->id);
                                }

                                /*if ($actualizarUsuario) {
                                    $actualizarUsuario->estado = false;
                                    $actualizarUsuario->id_user_updated = auth()->user()->id;
                                    $actualizarUsuario->save();
                                }*/

                                //SERVICIO DE REPLICA

                                $ciServicio = $actualizarPersona->ci;

                                if ($actualizarPersona->complemento != null) {
                                    $ciServicio .= '-' . $actualizarPersona->complemento;
                                }

                                $motivoServicio = 3;

                                if ($this->id_motivo_baja == 6) {
                                    $motivoServicio = 4;
                                }

                                $bajas = [];
                                $bajas[] = [
                                    'ci' => $ciServicio,
                                    'id_estado_afiliado' => $motivoServicio,
                                ];

                                $regBajasGehma2 = new VerificarDarBaja();
                                $regBajasGehma2->bajasGehma2(['bajas' => $bajas]);
                            } else {
                                if ($datosTitular->estado_vigencia == 'VIGENTE') {

                                    $actualizacionHistorial = HistorialAsegurado::where('id_tipo_asegurado', $this->id_tipo_asegurado)
                                        ->where('id_corresponde', $datosTitular->id)
                                        ->where('id_persona', $this->id_persona)
                                        ->orderBy('id', 'desc')
                                        ->first();

                                    if ($actualizacionHistorial) {
                                        $actualizacionHistorial->estado_vigencia = 'CESANTE';
                                        $actualizacionHistorial->fecha_cesantia = $this->fecha_baja_inst;
                                        $actualizacionHistorial->fecha_baja = $this->fecha_baja_ssu;
                                        $actualizacionHistorial->id_user_updated = auth()->user()->id;
                                        $actualizacionHistorial->save();
                                    }

                                    //PARA EL GRUPO FAMILIAR

                                    $servicioGrupoFamiliar = new GrupoFamiliarPersona();

                                    $listadoFamiliares = $servicioGrupoFamiliar->encontrarPersona($this->id_persona);

                                    if ($listadoFamiliares != []) {
                                        if (count($listadoFamiliares) > 1) {
                                            foreach ($listadoFamiliares as $familiar) {
                                                if ($familiar['id'] != $this->id_persona) {

                                                    $actualizarBeneficiario = Beneficiario::where('id_persona', $familiar['id'])
                                                        ->where('id_titular', $datosTitular->id)
                                                        ->with('beneficiarioPersona')
                                                        ->orderBy('id', 'desc')
                                                        ->first();

                                                    if ($actualizarBeneficiario) {

                                                        $bajaInstitucionalBeneficiario = BajaInstitucional::create([
                                                            'fecha_baja_inst' => $this->fecha_baja_inst,
                                                            'fecha_baja_ssu' => $this->fecha_baja_ssu,
                                                            'id_persona' => $familiar['id'],
                                                            'id_motivo_baja' => $this->id_motivo_baja,
                                                            'descripcion_motivo' => $this->descripcion_motivo . ' (BENEFICIARIO)',
                                                            'nro_documento_referencia' => $this->nro_documento_referencia,
                                                            'baja_definitiva' => $this->baja_definitiva,
                                                            'registro_tratado' => $this->baja_definitiva,
                                                            'id_tipo_asegurado' => $actualizarBeneficiario->beneficiarioPersona->id_tipo_asegurado,
                                                            'id_corresponde' => $actualizarBeneficiario->id,
                                                            'id_institucion_baja' => $this->idInstitucionBaja,
                                                            'id_user_created' => auth()->user()->id,
                                                        ]);

                                                        if ($bajaInstitucionalBeneficiario) {

                                                            $actualizarBeneficiario->vigencia = 'CESANTE';
                                                            $actualizarBeneficiario->fecha_baja = $this->fecha_baja_ssu;
                                                            $actualizarBeneficiario->id_user_updated = auth()->user()->id;
                                                            $actualizarBeneficiario->save();

                                                            //PARA LA TABLA HISTORIAL

                                                            $actualizacionHistorialBeneficiario = HistorialAsegurado::where('id_tipo_asegurado', $familiar['id_tipo_asegurado'])
                                                                ->where('id_corresponde', $actualizarBeneficiario->id)
                                                                ->where('id_persona', $familiar['id'])
                                                                ->orderBy('id', 'desc')
                                                                ->first();

                                                            if ($actualizacionHistorialBeneficiario) {
                                                                $actualizacionHistorialBeneficiario->estado_vigencia = 'CESANTE';
                                                                $actualizacionHistorialBeneficiario->fecha_cesantia = $this->fecha_baja_inst;
                                                                $actualizacionHistorialBeneficiario->fecha_baja = $this->fecha_baja_ssu;
                                                                $actualizacionHistorialBeneficiario->id_user_updated = auth()->user()->id;
                                                                $actualizacionHistorialBeneficiario->save();
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    //PARA DAR DE BAJA A LAS INSTITUCIONES

                                    /*$institucionesTitular = TitularInstitucion::where('id_titular', $datosTitular->id)->get();

                                    if (count($institucionesTitular) > 0) {
                                        foreach ($institucionesTitular as $institucionTitular) {
                                            $institucionTitular->estado = false;
                                            $institucionTitular->id_user_updated = auth()->user()->id;
                                            $institucionTitular->save();
                                        }
                                    }*/

                                    //FIN PARA DAR DE BAJA A LAS INSTITUCIONES

                                    $datosTitular->estado_vigencia = 'CESANTE';
                                    $datosTitular->fecha_baja = $this->fecha_baja_ssu;
                                    $datosTitular->id_user_updated = auth()->user()->id;
                                    $datosTitular->save();
                                }
                            }

                            //------------------------------------------------------- NUEVA BAJA DE DATOS ASUSS (TITULARES) -------------------------------------------------------  

                            /*$servicioLoginAsuss = new LoginAsussService();

                            $servicioAsussTitular = new AfiliacionTitularAsussService($servicioLoginAsuss);*/
                            if (empty($actualizarPersona)) {
                                $actualizarPersona = Persona::find($this->id_persona);
                            }

                            $arrayDatosOriginales = [
                                'tipo_documento' => 'CI',
                                'numero_documento' => implode('-', array_filter([$actualizarPersona->ci, $actualizarPersona->complemento])),
                                'fecha_nacimiento' => $actualizarPersona->fecha_nacimiento,
                                'codigo_entidad' => '',
                                'fecha_fin' => $bajaInstitucional->fecha_baja_inst,
                                'fecha_fin_cesantia' => $bajaInstitucional->fecha_baja_ssu,
                                'tipo_motivo' => $bajaInstitucional->id_motivo_baja,
                                'descripcion_motivo' => $bajaInstitucional->id_motivo_baja,
                                'id_persona' => $actualizarPersona->id,
                                'id_tipo_asegurado' => $bajaInstitucional->id_tipo_asegurado,
                                'id_corresponde' => $bajaInstitucional->id_corresponde,
                                'id_titular' => null,
                                'id_institucion' => $bajaInstitucional->id_institucion_baja,
                                'id_user_created' => $bajaInstitucional->id_user_created,
                            ];

                            //dd($arrayDatosOriginales);

                            //$servicioAsussTitular->afiliarTitularAsuss($arrayDatosOriginales);

                            //ProcesarBajaTitularAsussJob::dispatch($arrayDatosOriginales); -------------------------------------------------- DESCOMENTAR PARA QUE EL ASUSS FUNCIONE

                            //------------------------------------------------------- FIN NUEVA BAJA DE DATOS ASUSS (TITULARES) -------------------------------------------------------  
                        }
                    } elseif (Str::contains($this->tipoAsegurado, 'BENEFICIARIO')) {
                        if ($this->tipoAsegurado != 'BENEFICIARIO DEL INTERIOR') {

                            $datosBeneficiario = Beneficiario::where('id_persona', $this->id_persona)
                                ->where('vigencia', 'VIGENTE')
                                ->orderBy('id', 'desc')
                                ->first();

                            if ($datosBeneficiario) {
                                if ($this->baja_definitiva == true) {
                                    $datosBeneficiario->vigencia = 'NO VIGENTE';
                                    $datosBeneficiario->fecha_baja = $this->fecha_baja_ssu;
                                    $datosBeneficiario->id_user_updated = auth()->user()->id;
                                    $datosBeneficiario->save();

                                    //PARA LA TABLA HISTORIAL

                                    $actualizacionHistorial = HistorialAsegurado::where('id_tipo_asegurado', $this->id_tipo_asegurado)
                                        ->where('id_corresponde', $datosBeneficiario->id)
                                        ->where('id_persona', $this->id_persona)
                                        ->orderBy('id', 'desc')
                                        ->first();

                                    if ($actualizacionHistorial) {
                                        $actualizacionHistorial->estado_vigencia = 'NO VIGENTE';
                                        $actualizacionHistorial->fecha_cesantia = $this->fecha_baja_inst;
                                        $actualizacionHistorial->fecha_baja = $this->fecha_baja_ssu;
                                        $actualizacionHistorial->id_user_updated = auth()->user()->id;
                                        $actualizacionHistorial->save();
                                    }

                                    //PARA ACTUALIZAR A LA PERSONA
                                    $actualizarPersona = Persona::where('id', $this->id_persona)->first();

                                    if ($actualizarPersona) {
                                        $actualizarPersona->afiliado = false;
                                        $actualizarPersona->id_tipo_asegurado = 0;
                                        $actualizarPersona->id_user_updated = auth()->user()->id;
                                        $actualizarPersona->save();
                                    }

                                    //PARA EL USUARIO

                                    $actualizarUsuario = User::where('id_persona', $this->id_persona)->first();

                                    if ($actualizarUsuario) {
                                        $this->darBajaUsuario($actualizarUsuario->id);
                                    }

                                    //SERVICIO DE REPLICA

                                    $ciServicio = $actualizarPersona->ci;

                                    if ($actualizarPersona->complemento != null) {
                                        $ciServicio .= '-' . $actualizarPersona->complemento;
                                    }

                                    $motivoServicio = 3;

                                    if ($this->id_motivo_baja == 6) {
                                        $motivoServicio = 4;
                                    }

                                    $bajas = [];
                                    $bajas[] = [
                                        'ci' => $ciServicio,
                                        'id_estado_afiliado' => $motivoServicio,
                                    ];

                                    $regBajasGehma2 = new VerificarDarBaja();
                                    $regBajasGehma2->bajasGehma2(['bajas' => $bajas]);
                                } else {
                                    $datosBeneficiario->vigencia = 'CESANTE';
                                    $datosBeneficiario->fecha_baja = $this->fecha_baja_ssu;
                                    $datosBeneficiario->id_user_updated = auth()->user()->id;
                                    $datosBeneficiario->save();

                                    $actualizacionHistorial = HistorialAsegurado::where('id_tipo_asegurado', $this->id_tipo_asegurado)
                                        ->where('id_corresponde', $datosBeneficiario->id)
                                        ->where('id_persona', $this->id_persona)
                                        ->orderBy('id', 'desc')
                                        ->first();

                                    if ($actualizacionHistorial) {
                                        $actualizacionHistorial->estado_vigencia = 'CESANTE';
                                        $actualizacionHistorial->fecha_cesantia = $this->fecha_baja_inst;
                                        $actualizacionHistorial->fecha_baja = $this->fecha_baja_ssu;
                                        $actualizacionHistorial->id_user_updated = auth()->user()->id;
                                        $actualizacionHistorial->save();
                                    }
                                }
                            }

                            //------------------------------------------------------- NUEVA BAJA DE DATOS ASUSS (BENEFICIARIO) -------------------------------------------------------  

                            /*$servicioLoginAsuss = new LoginAsussService();

                            $servicioAsussTitular = new AfiliacionTitularAsussService($servicioLoginAsuss);*/

                            if (empty($actualizarPersona)) {
                                $actualizarPersona = Persona::find($this->id_persona);
                            }

                            $arrayDatosOriginales = [
                                'tipo_documento' => 'CI',
                                'numero_documento' => implode('-', array_filter([$actualizarPersona->ci, $actualizarPersona->complemento])),
                                'fecha_nacimiento' => $actualizarPersona->fecha_nacimiento,
                                'codigo_entidad' => '',
                                'fecha_fin' => $bajaInstitucional->fecha_baja_inst,
                                'fecha_fin_cesantia' => $bajaInstitucional->fecha_baja_ssu,
                                'tipo_motivo' => $bajaInstitucional->id_motivo_baja,
                                'descripcion_motivo' => $bajaInstitucional->id_motivo_baja,
                                'id_persona' => $actualizarPersona->id,
                                'id_tipo_asegurado' => $bajaInstitucional->id_tipo_asegurado,
                                'id_corresponde' => $bajaInstitucional->id_corresponde,
                                'id_titular' => $datosBeneficiario->id_titular,
                                'id_institucion' => $bajaInstitucional->id_institucion_baja,
                                'id_user_created' => $bajaInstitucional->id_user_created,
                            ];

                            //dd($arrayDatosOriginales);

                            //$servicioAsussTitular->afiliarTitularAsuss($arrayDatosOriginales);

                            //ProcesarBajaBeneficiarioAsussJob::dispatch($arrayDatosOriginales); -------------------------------------------------- DESCOMENTAR PARA QUE EL ASUSS FUNCIONE

                            //------------------------------------------------------- FIN NUEVA BAJA DE DATOS ASUSS (BENEFICIARIO) ------------------------------------------------------- 
                        }
                    } elseif ($this->tipoAsegurado == 'ESTUDIANTE') {
                        //dd('entra');
                        $datosEstudiante = Estudiante::where('id_persona', $this->id_persona)
                            ->where('estado', true)
                            ->orderBy('id', 'desc')
                            ->first();

                        //dd($datosEstudiante);

                        if ($datosEstudiante) {
                            $datosEstudiante->estado_vigencia = false;
                            // hola Doris gay, estoy aumentando esta linea porque es la nueva variable que cree jij psdta: gay :D
                            $datosEstudiante->reafiliacion = true;
                            $datosEstudiante->fecha_baja = $this->fecha_baja_ssu;
                            $datosEstudiante->id_user_updated = auth()->user()->id;
                            $datosEstudiante->save();

                            //PARA LA TABLA HISTORIAL

                            $actualizacionHistorial = HistorialAsegurado::where('id_tipo_asegurado', $this->id_tipo_asegurado)
                                ->where('id_corresponde', $datosEstudiante->id)
                                ->where('id_persona', $this->id_persona)
                                ->orderBy('id', 'desc')
                                ->first();

                            //dd($actualizacionHistorial);

                            if ($actualizacionHistorial) {
                                //dd($actualizacionHistorial);
                                $actualizacionHistorial->estado_vigencia = 'NO VIGENTE';
                                $actualizacionHistorial->fecha_cesantia = $this->fecha_baja_inst;
                                $actualizacionHistorial->fecha_baja = $this->fecha_baja_ssu;
                                $actualizacionHistorial->id_user_updated = auth()->user()->id;
                                $actualizacionHistorial->save();
                            }

                            //dd($actualizacionHistorial);

                            //PARA ACTUALIZAR A LA PERSONA
                            $actualizarPersona = Persona::find($this->id_persona);

                            if ($actualizarPersona) {
                                $actualizarPersona->id_tipo_asegurado = 0;
                                $actualizarPersona->afiliado = false;
                                $actualizarPersona->id_user_updated = auth()->user()->id;
                                $actualizarPersona->save();
                            }

                            //dd($actualizarPersona);

                            //PARA EL USUARIO

                            $actualizarUsuario = User::where('id_persona', $this->id_persona)->first();

                            if ($actualizarUsuario) {
                                $this->darBajaUsuario($actualizarUsuario->id);
                            }

                            //SERVICIO PARA GEHMA2

                            $ciServicio = $actualizarPersona->ci;

                            if ($actualizarPersona->complemento != null) {
                                $ciServicio .= '-' . $actualizarPersona->complemento;
                            }

                            $motivoServicio = 3;

                            if ($this->id_motivo_baja == 6) {
                                $motivoServicio = 4;
                            }

                            $bajas = [];
                            $bajas[] = [
                                'ci' => $ciServicio,
                                'id_estado_afiliado' => $motivoServicio,
                            ];

                            $regBajasGehma2 = new VerificarDarBaja();
                            $regBajasGehma2->bajasGehma2(['bajas' => $bajas]);

                            //------------------------------------------------------- NUEVA BAJA DE DATOS ASUSS (ESTUDIANTE) -------------------------------------------------------  

                            /*$servicioLoginAsuss = new LoginAsussService();

                            $servicioAsussTitular = new AfiliacionTitularAsussService($servicioLoginAsuss);*/

                            if (empty($actualizarPersona)) {
                                $actualizarPersona = Persona::find($this->id_persona);
                            }

                            $arrayDatosOriginales = [
                                'tipo_documento' => 'CI',
                                'numero_documento' => implode('-', array_filter([$actualizarPersona->ci, $actualizarPersona->complemento])),
                                'fecha_nacimiento' => $actualizarPersona->fecha_nacimiento,
                                'codigo_entidad' => '',
                                'fecha_fin' => $bajaInstitucional->fecha_baja_inst,
                                'fecha_fin_cesantia' => $bajaInstitucional->fecha_baja_ssu,
                                'tipo_motivo' => $bajaInstitucional->id_motivo_baja,
                                'descripcion_motivo' => $bajaInstitucional->id_motivo_baja,
                                'id_persona' => $actualizarPersona->id,
                                'id_tipo_asegurado' => $bajaInstitucional->id_tipo_asegurado,
                                'id_corresponde' => $bajaInstitucional->id_corresponde,
                                'id_titular' => null,
                                'id_institucion' => null,
                                'id_user_created' => $bajaInstitucional->id_user_created,
                            ];

                            //dd($arrayDatosOriginales);

                            //$servicioAsussTitular->afiliarTitularAsuss($arrayDatosOriginales);

                            //ProcesarBajaEstudianteAsussJob::dispatch($arrayDatosOriginales); -------------------------------------------------- DESCOMENTAR PARA QUE EL ASUSS FUNCIONE

                            //------------------------------------------------------- FIN NUEVA BAJA DE DATOS ASUSS (ESTUDIANTE) ------------------------------------------------------- 
                        }
                    }

                    //DB::commit();
                    $this->dispatch('bajaRegistrada');

                    return redirect()->route('vigencia.bajasinstitucionales.index');
                }
            } else {
                DB::rollBack();
                $this->dispatch('bajaNoRegistrada');
            }
        } catch (\Throwable $th) {
            dd($th);
            DB::rollBack();
            $this->dispatch('bajaNoRegistrada');
        }
    }
}
