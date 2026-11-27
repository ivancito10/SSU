<?php

namespace App\Livewire\Vigencia\SolicitudPrestacionMedica;

use Livewire\Component;
use App\Models\Vigencia\SolicitudPMedica;
use App\Models\Vigencia\AseguradoSolicitante;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Vigencia\SeguroInterior;
use App\Models\Vigencia\SolMedicaEspecialidad;
use App\Models\Vigencia\SolMedicaServicio;
use App\Models\Administracion\Especialidad;
use App\Models\Administracion\Servicio;
use App\Models\Administracion\Persona;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Carbon\Carbon;
use DB;


class SolicitudPrestacionMedicaEdit extends Component
{

    public $idSolicitud;

    //DATOS DE LA SOLICITUD
    public $cod_cite;
    #[Validate]
    public $cite;
    #[Validate]
    public $fecha_inicio;
    #[Validate]
    public $fecha_fin;
    public $otras_limitaciones;
    #[Validate]
    public $id_seguro_interior;
    public $observacion;
    public $medicamentos_liname = false;
    public $medicamentos_extraliname = false;
    #[Validate]
    public $nro_hoja_de_ruta;
    #[Validate]
    public $fecha_solicitud_hr;
    public $estado_validacion;

    //DATOS DEL SEGURO
    public $nombre_seguro;

    //DATOS DE LOS ASEGURADOS SOLICITANTES
    public $asegurados_solicitantes;
    public $datos_persona_grupo_familiar = [];
    public $solicitantesAgregados = [];

    //DATOS DE ASEGURADO SOLICITANTE PRINCIPAL
    public $foto;
    public $nombreCompleto;
    public $institucion;
    public $carnet;
    public $matricula_seguro;
    public $estado_vigencia;
    public $tipo_asegurado;
    public $edadPersona;
    public $codigoInstitucional;
    public $sexo;
    public $fecha_nacimiento;


    //DATOS GENERALES
    public $seguros = [];
    public $listaEspecialidades = [];
    public $listaServicios = [];

    //DATOS SELECCIONADOS
    #[Validate]
    public $especialidades = [];
    #[Validate]
    public $servicios = [];
    

    protected $listeners = ['registrarSolicitud', 'agregarSolicitante', 'quitarSolicitante'];

    public function rules()
    {
        return [
        'nro_hoja_de_ruta' => 'required',
        'fecha_solicitud_hr' => 'required|date',
        'cite' => 'required',
        'id_seguro_interior' => 'required',
        'fecha_inicio' => 'required',
        'fecha_fin' => 'required',
        'especialidades' => 'required',
        'servicios' => 'required',

        ];
    }

    public function render()
    {
        return view('livewire.Vigencia.solicitud-prestacion-medica.solicitud-prestacion-medica-edit');
    }

    public function mount(){
        $this->seguros = SeguroInterior::where('estado', true)->get();
        $this->listaEspecialidades = Especialidad::all();
        $this->listaServicios = Servicio::all();
        $this->mostrarSolicitud($this->idSolicitud);
    }

    public function mostrarSolicitud($idSol){
        $solicitud = SolicitudPMedica::where('id', $idSol)
            ->with(['seguroInteriorSolicitudPMedica'])
            ->first();
        $this->cod_cite = $solicitud->cod_cite;
        $this->cite = $this->cod_cite. '/'. Carbon::parse($solicitud->created_at)->format('Y');
        $this->fecha_inicio = $solicitud->fecha_inicio;
        $this->fecha_fin = $solicitud->fecha_fin;
        $this->otras_limitaciones = $solicitud->otras_limitaciones;
        $this->id_seguro_interior = $solicitud->id_seguro_interior;
        $this->observacion = $solicitud->observacion;
        $this->medicamentos_liname = $solicitud->medicamentos_liname;
        $this->medicamentos_extraliname = $solicitud->medicamentos_extraliname;
        $this->nro_hoja_de_ruta = $solicitud->nro_hoja_de_ruta;
        $this->fecha_solicitud_hr = $solicitud->fecha_solicitud_hr;
        $this->estado_validacion = $solicitud->estado_validacion;

        //INFORMACION DEL SEGURO
        $this->nombre_seguro = $solicitud->seguroInteriorSolicitudPMedica->nombre_seguro;

        //LOGICA PARA LAS ESPECIALIDADES Y SERVICIOS
        $especialidadesSeleccionadas = SolMedicaEspecialidad::where('id_solicitud_p_medica', $idSol)->get();
        $serviciosSeleccionados = SolMedicaServicio::where('id_solicitud_p_medica', $idSol)->get();

        if(!$especialidadesSeleccionadas->isEmpty()){
            foreach ($especialidadesSeleccionadas as $key => $especialidadSeleccionada) {
                $this->especialidades[] = $especialidadSeleccionada->id_especialidad; 
            }
            
        }

        if(!$serviciosSeleccionados->isEmpty()){
            foreach ($serviciosSeleccionados as $key => $servicioSeleccionado){
                $this->servicios[] = $servicioSeleccionado->id_servicio;
            }
        }

        //DATOS DE LOS ASEGURADOS SOLICITANTES
        $this->asegurados_solicitantes = AseguradoSolicitante::where('id_solicitud_p_medica', $solicitud->id)
            ->with(['personaAseguradoSolicitante', 'personaAseguradoSolicitante.tipoAseguradoPersona'])
            ->orderBy('id','asc')
            ->get();
        //dd($this->asegurados_solicitantes);
        //dd($this->asegurados_solicitantes, $this->asegurados_solicitantes->slice(1));

        // LOGICA PARA EL GRUPO FAMILIAR QUE YA ESTABA AGREGADO

        $otrosSolicitantes = $this->asegurados_solicitantes->slice(1);

        foreach ($otrosSolicitantes as $key => $otroSolicitante) {
            $this->solicitantesAgregados[] = [
                'id' => $otroSolicitante->personaAseguradoSolicitante->id,
                'nombres' => $otroSolicitante->personaAseguradoSolicitante->nombres,
                'p_apellido' => $otroSolicitante->personaAseguradoSolicitante->p_apellido,
                's_apellido' => $otroSolicitante->personaAseguradoSolicitante->s_apellido,
                'ci' => $otroSolicitante->personaAseguradoSolicitante->ci,
                'complemento' => $otroSolicitante->personaAseguradoSolicitante->complemento,
                'matricula_seguro' => $otroSolicitante->personaAseguradoSolicitante->matricula_seguro,
                'tipo_asegurado' => $otroSolicitante->personaAseguradoSolicitante->tipoAseguradoPersona->tipo_asegurado,
            ];
        }

        // FIN LOGICA PARA EL GRUPO FAMILIAR QUE YA ESTABA AGREGADO

        $solicitantePrincipal = $this->asegurados_solicitantes->first();
        $this->edadPersona = Carbon::parse($solicitantePrincipal->personaAseguradoSolicitante->fecha_nacimiento)->age;
        $this->sexo = $solicitantePrincipal->personaAseguradoSolicitante->sexo == 'F' ? 'FEMENINO' : 'MASCULINO';
        $this->fecha_nacimiento = $solicitantePrincipal->personaAseguradoSolicitante->fecha_nacimiento;
        $esTitular = Titular::where('id_persona', $solicitantePrincipal->personaAseguradoSolicitante->id)->first();
        if($esTitular){
            $this->foto = $this->obtenerImagenBase64($esTitular->foto_nombre);
            $this->matricula_seguro = $solicitantePrincipal->personaAseguradoSolicitante->matricula_seguro;
            if($solicitantePrincipal->personaAseguradoSolicitante->p_apellido == null){
                $this->nombreCompleto = $solicitantePrincipal->personaAseguradoSolicitante->s_apellido . ' ' . $solicitantePrincipal->personaAseguradoSolicitante->nombres; 
            } elseif ($solicitantePrincipal->personaAseguradoSolicitante->s_apellido == null){
                $this->nombreCompleto = $solicitantePrincipal->personaAseguradoSolicitante->p_apellido . ' ' . $solicitantePrincipal->personaAseguradoSolicitante->nombres; 
            } else {
                $this->nombreCompleto = $solicitantePrincipal->personaAseguradoSolicitante->p_apellido . ' ' . $solicitantePrincipal->personaAseguradoSolicitante->s_apellido . ' ' . $solicitantePrincipal->personaAseguradoSolicitante->nombres; 
            }

            if($solicitantePrincipal->personaAseguradoSolicitante->complemento == null){
                $this->carnet = $solicitantePrincipal->personaAseguradoSolicitante->ci;
            } else {
                $this->carnet = $solicitantePrincipal->personaAseguradoSolicitante->ci . ' - ' . $solicitantePrincipal->personaAseguradoSolicitante->complemento;
            }

            

            $datosInstitucionTitular = TitularInstitucion::where('id_titular', $esTitular->id)
                ->where('estado', true)
                ->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])
                ->first();
            
            if($datosInstitucionTitular){
                $this->institucion = $datosInstitucionTitular->titularInstitucionConInstitucion->nombre;
                if($datosInstitucionTitular->titularInstitucionConInstitucion->codigosInstitucion != null){
                    $this->codigoInstitucional = $datosInstitucionTitular->titularInstitucionConInstitucion->codigosInstitucion->codigo . 
                    $datosInstitucionTitular->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular;
                } else {
                    $this->codigoInstitucional = null;
                }
                
            } else {
                $this->institucion = 'APORTANTE VOLUNTARIO';
                $this->codigoInstitucional = null;
            }

            $this->estado_vigencia = $esTitular->estado_vigencia;
            $this->tipo_asegurado = $solicitantePrincipal->personaAseguradoSolicitante->tipoAseguradoPersona->tipo_asegurado;

            //PARA EL GRUPO FAMILIAR

            $grupo_familiar = Beneficiario::where('id_titular', $esTitular->id)
                ->whereIn('vigencia', ['VIGENTE', 'CESANTE'])
                ->get();
            if ($grupo_familiar) {
                foreach ($grupo_familiar as $key => $familiar) {
                    $personaBen = Persona::join('administracion.tipo_asegurado as ta', 'ta.id', '=', 'persona.id_tipo_asegurado')
                        ->select('persona.*', 'ta.tipo_asegurado as tipoAsegurado')
                        ->where('persona.id', $familiar->id_persona)
                        ->first();
                    if($personaBen){
                        $this->datos_persona_grupo_familiar[] = $personaBen;
                    }
                }
            }
            // FIN GRUPO FAMILIAR

        } else {
            $esBeneficiario = Beneficiario::where('id_persona', $solicitantePrincipal->personaAseguradoSolicitante->id)->first();
            $this->foto = $this->obtenerImagenBase64($esBeneficiario->foto_nombre);
            $this->matricula_seguro = $solicitantePrincipal->personaAseguradoSolicitante->matricula_seguro;
            if($solicitantePrincipal->personaAseguradoSolicitante->p_apellido == null){
                $this->nombreCompleto = $solicitantePrincipal->personaAseguradoSolicitante->s_apellido . ' ' . $solicitantePrincipal->personaAseguradoSolicitante->nombres; 
            } elseif ($solicitantePrincipal->personaAseguradoSolicitante->s_apellido == null){
                $this->nombreCompleto = $solicitantePrincipal->personaAseguradoSolicitante->p_apellido . ' ' . $solicitantePrincipal->personaAseguradoSolicitante->nombres; 
            } else {
                $this->nombreCompleto = $solicitantePrincipal->personaAseguradoSolicitante->p_apellido . ' ' . $solicitantePrincipal->personaAseguradoSolicitante->s_apellido . ' ' . $solicitantePrincipal->personaAseguradoSolicitante->nombres; 
            }

            if($solicitantePrincipal->personaAseguradoSolicitante->complemento == null){
                $this->carnet = $solicitantePrincipal->personaAseguradoSolicitante->ci;
            } else {
                $this->carnet = $solicitantePrincipal->personaAseguradoSolicitante->ci . ' - ' . $solicitantePrincipal->personaAseguradoSolicitante->complemento;
            }

            $datosInstitucionTitular = TitularInstitucion::where('id_titular', $esBeneficiario->id_titular)
                ->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])
                ->first();
            
            if($datosInstitucionTitular){
                $this->institucion = $datosInstitucionTitular->titularInstitucionConInstitucion->nombre;
                $this->institucion = $datosInstitucionTitular->titularInstitucionConInstitucion->nombre;
                if($datosInstitucionTitular->titularInstitucionConInstitucion->codigosInstitucion != null){
                    $this->codigoInstitucional = $datosInstitucionTitular->titularInstitucionConInstitucion->codigosInstitucion->codigo . 
                    $datosInstitucionTitular->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular;
                } else {
                    $this->codigoInstitucional = null;
                }
                
            } else {
                $this->institucion = 'APORTANTE VOLUNTARIO';
                $this->codigoInstitucional = null;
            }

            $this->estado_vigencia = $esBeneficiario->vigencia;
            $this->tipo_asegurado = $solicitantePrincipal->personaAseguradoSolicitante->tipoAseguradoPersona->tipo_asegurado;

            //PARA EL GRUPO FAMILIAR

            $grupo_familiar = Beneficiario::where('id_titular', $esBeneficiario->id_titular)
                ->whereIn('vigencia', ['VIGENTE', 'CESANTE'])
                ->whereNot('id', $esBeneficiario->id)
                ->get();
            if ($grupo_familiar) {
                foreach ($grupo_familiar as $key => $familiar) {
                    $personaBen = Persona::join('administracion.tipo_asegurado as ta', 'ta.id', '=', 'persona.id_tipo_asegurado')
                        ->select('persona.*', 'ta.tipo_asegurado as tipoAsegurado')
                        ->where('persona.id', $familiar->id_persona)
                        ->first();
                    if($personaBen){
                        $this->datos_persona_grupo_familiar[] = $personaBen;
                    }
                }
            }

            // FIN GRUPO FAMILIAR
        }
    }

    public function agregarSolicitante($idPersona)
    {
        if(!in_array($idPersona, array_column($this->solicitantesAgregados, 'id'))){
            $datosPersona = Persona::join('administracion.tipo_asegurado as ta', 'ta.id', '=', 'administracion.persona.id_tipo_asegurado')
                ->select('administracion.persona.*', 'ta.tipo_asegurado')
                ->where('administracion.persona.id', $idPersona)
                ->first();
        
            $this->solicitantesAgregados[] = [
                'id' => $datosPersona->id,
                'nombres' => $datosPersona->nombres,
                'p_apellido' => $datosPersona->p_apellido,
                's_apellido' => $datosPersona->s_apellido,
                'ci' => $datosPersona->ci,
                'complemento' => $datosPersona->complemento,
                'matricula_seguro' => $datosPersona->matricula_seguro,
                'tipo_asegurado' => $datosPersona->tipo_asegurado,
            ];
        }
        
    }

    public function quitarSolicitante($idPersona)
    {
        try {
            $nuevoArray = [];
            foreach ($this->solicitantesAgregados as $sol) {
                if ($sol['id'] != $idPersona) {
                    $nuevoArray[] = $sol;
                }
            }
            $this->solicitantesAgregados = $nuevoArray;
            $this->dispatch('solRetirado');
        } catch (\Throwable $th) {
            $this->dispatch('solNoRetirado');
        }
    }

    public function registrarSolicitud($otraslimitaciones, $observacion)
    {   
        
        $this->otras_limitaciones = $otraslimitaciones;
        $this->observacion = $observacion;
        $this->validate();
        DB::beginTransaction();
        try {
            $solicitudModificada = SolicitudPMedica::find($this->idSolicitud);
            $solicitudModificada->update([
                'fecha_inicio' => $this->fecha_inicio,
                'fecha_fin' => $this->fecha_fin,
                'otras_limitaciones' => $this->otras_limitaciones,
                'id_seguro_interior' => $this->id_seguro_interior,
                'observacion' => $this->observacion,
                'medicamentos_liname' => $this->medicamentos_liname,
                'medicamentos_extraliname' => $this->medicamentos_extraliname,
                'nro_hoja_de_ruta' => $this->nro_hoja_de_ruta,
                'fecha_solicitud_hr' => $this->fecha_solicitud_hr,
                'id_user_updated' => auth()->user()->id,
            ]);

            $asegSoli = $this->asegurados_solicitantes->first();
            AseguradoSolicitante::where('id_solicitud_p_medica', $this->idSolicitud)->delete();
            AseguradoSolicitante::create([
                'id_solicitud_p_medica' => $this->idSolicitud,
                'id_asegurado_solicitante' => $asegSoli->id_asegurado_solicitante,
                'id_asegurado_titular' => $asegSoli->id_asegurado_titular,
                'id_user_created' => auth()->user()->id,
                'id_user_updated' => auth()->user()->id,
            ]);

            if(!empty($this->solicitantesAgregados)){
                foreach ($this->solicitantesAgregados as $solicitanteAgregado) {
                    AseguradoSolicitante::create([
                        'id_solicitud_p_medica' => $this->idSolicitud,
                        'id_asegurado_solicitante' => $solicitanteAgregado['id'],
                        'id_asegurado_titular' => $asegSoli->id_asegurado_titular,
                        'id_user_created' => auth()->user()->id,
                        'id_user_updated' => auth()->user()->id,
                    ]);
                }
            }

            SolMedicaEspecialidad::where('id_solicitud_p_medica', $this->idSolicitud)->delete();
            if(!empty($this->especialidades)){
                foreach ($this->especialidades as $especialidad) { 
                    SolMedicaEspecialidad::create([
                        'id_solicitud_p_medica' => $this->idSolicitud,
                        'id_especialidad' => $especialidad,
                        'id_user_created' => auth()->user()->id,
                        'id_user_updated' => auth()->user()->id,
                    ]);
                }
            }
            
            SolMedicaServicio::where('id_solicitud_p_medica', $this->idSolicitud)->delete();
            if(!empty($this->servicios)){
                foreach ($this->servicios as $servicio) { 
                    SolMedicaServicio::create([
                        'id_solicitud_p_medica' => $this->idSolicitud,
                        'id_servicio' => $servicio,
                        'id_user_created' => auth()->user()->id,
                        'id_user_updated' => auth()->user()->id,
                    ]);
                }
            }
            DB::commit();
            $this->dispatch('guardado');
            return redirect()->route('vigencia.solprestacionesmedicas.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('noGuardado');
        }        
    }

    public function atras(){
        return redirect()->route('vigencia.solprestacionesmedicas.index');
    }

    private function obtenerImagenBase64($idFoto)
    {
        try {
            if ($idFoto) {
                $path = 'Afiliacion/FOTOS/' . $idFoto;
    
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

    public function cancelarSolicitud(){
        return redirect()->route('vigencia.solprestacionesmedicas.index');
    }

    


}
