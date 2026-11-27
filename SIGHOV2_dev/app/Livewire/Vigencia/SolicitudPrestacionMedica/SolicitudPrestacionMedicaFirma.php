<?php

namespace App\Livewire\Vigencia\SolicitudPrestacionMedica;

use App\Models\Vigencia\AseguradoSolicitante;
use Livewire\Component;

use App\Models\Vigencia\SolicitudPMedica;
use App\Models\Vigencia\SeguroInterior;
use App\Models\Vigencia\SolMedicaEspecialidad;
use App\Models\Vigencia\SolMedicaServicio;

use App\Models\Administracion\Persona;
use App\Models\Administracion\TipoAsegurado;

use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Afiliacion\Beneficiario;

use App\Models\Aportes\Institucion;
use App\Models\User;
use App\Models\Administracion\NotificacionUsuarios;


use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use App\Services\VerificacionFirmaDigital;
use Carbon\Carbon;

class SolicitudPrestacionMedicaFirma extends Component
{
    use WithFileUploads;

    public $idSolicitud;

    //DATOS DE LA SOLICITUD COMO TAL

    public $cod_cite;
    public $fecha_inicio;
    public $fecha_fin;
    public $otras_limitaciones;
    public $id_seguro_interior;
    public $observacion;
    public $medicamentos_liname;
    public $medicamentos_extraliname;
    public $estado;
    public $nro_hoja_de_ruta;
    public $fecha_solicitud_hr;
    public $estado_validacion;
    public $fecha_validacion;
    public $motivo_anulacion;
    public $fecha_anulacion;

    public $fecha_creacion;

    //OTROS DATOS REFERENTES

    public $nombre_seguro;
    public $diasVigencia;

    public $nombreCompleto;
    public $matricula;
    public $ci;
    public $complemento; 
    public $edadPersona;
    public $tipo_asegurado;
    public $estado_vigencia;
    public $foto; 
    public $institucion;
    public $especialidades = [];
    public $servicios = [];
    public $solicitantesAgregados = [];
    public $datosValidacion = [];

    public $documentoFirmado;
    public $switchValidacion;

    public $firmado_por;
    public $fecha_firmado;

    public $codigoInstitucional;
    public $sexo;
    public $fecha_nacimiento;


    protected $listeners = ['guardarPdfFirmado'];

    public function render()
    {
        return view('livewire.Vigencia.solicitud-prestacion-medica.solicitud-prestacion-medica-firma');
    }

    public function mount(){
        $this->mostrarSolicitud($this->idSolicitud);
    }

    public function mostrarSolicitud($id)
    {
        if ($id) {
            $solicitud = SolicitudPMedica::find($id);
            $this->cod_cite = $solicitud->cod_cite . '/' . Carbon::parse($solicitud->created_at)->format('Y');
            $this->fecha_inicio = $solicitud->fecha_inicio;
            $this->fecha_fin = $solicitud->fecha_fin;
            $this->otras_limitaciones = $solicitud->otras_limitaciones ? $solicitud->otras_limitaciones : 'NO SE REGISTRA NINGUNA LIMITACIÓN';
            $seguroInterior = SeguroInterior::find($solicitud->id_seguro_interior);
            $this->id_seguro_interior = $solicitud->id_seguro_interior;
            $this->nombre_seguro = $seguroInterior->nombre_seguro;
            $this->observacion = $solicitud->observacion ? $solicitud->observacion : 'NO SE REGISTRA NINGUNA OBSERVACIÓN';
            //dd($this->observacion);
            $this->medicamentos_liname = $solicitud->medicamentos_liname;
            $this->medicamentos_extraliname = $solicitud->medicamentos_extraliname;
            $this->nro_hoja_de_ruta = $solicitud->nro_hoja_de_ruta;
            $this->fecha_solicitud_hr = Carbon::parse($solicitud->fecha_solicitud_hr)->format('Y-m-d');
            $this->estado_validacion = $solicitud->estado_validacion;
            $this->fecha_validacion = Carbon::parse($solicitud->fecha_validacion)->format('d/m/Y');
            $this->motivo_anulacion = $solicitud->motivo_anulacion;
            $this->fecha_anulacion = $solicitud->fecha_anulacion;
            $this->fecha_creacion = Carbon::parse($solicitud->created_at)->format('d/m/Y');

            $this->diasVigencia = (Carbon::parse($this->fecha_fin)->diffInDays($this->fecha_inicio)) + 1;


            $personaSol = AseguradoSolicitante::where('id_solicitud_p_medica', $solicitud->id) 
                ->orderBy('id', 'asc')
                ->first();
            $personaSolicitante = Persona::find($personaSol->id_asegurado_solicitante);
            //dd($personaSolicitante);
            if($personaSolicitante->p_apellido == null){
                $this->nombreCompleto = $personaSolicitante->s_apellido . ' ' . $personaSolicitante->nombres;
            } elseif($personaSolicitante->s_apellido == null) {
                $this->nombreCompleto = $personaSolicitante->p_apellido . ' ' . $personaSolicitante->nombres;
            } else {
                $this->nombreCompleto = $personaSolicitante->p_apellido . ' ' . $personaSolicitante->s_apellido . ' ' . $personaSolicitante->nombres;
            }
            
            $this->matricula = $personaSolicitante->matricula_seguro;
            $this->complemento = $personaSolicitante->complemento;
            $this->edadPersona = Carbon::parse($personaSolicitante->fecha_nacimiento)->age;
            $this->sexo = $personaSolicitante->sexo == 'F' ? 'FEMENINO' : 'MASCULINO';
            $this->fecha_nacimiento = $personaSolicitante->fecha_nacimiento;

            $tAsegurado = TipoAsegurado::find($personaSolicitante->id_tipo_asegurado);
            $this->tipo_asegurado = $tAsegurado->tipo_asegurado;
            if($personaSolicitante->complemento == null){
                $this->ci = $personaSolicitante->ci;
            } else {
                $this->ci = $personaSolicitante->ci. ' - ' . $personaSolicitante->complemento;
            }
            
            

            $esTitular = Titular::where('id_persona', $personaSolicitante->id)
                ->where('estado', true)
                ->first();
            if ($esTitular) {
                $this->estado_vigencia = $esTitular->estado_vigencia;

                $this->foto = $this->obtenerImagenBase64($esTitular->foto_nombre);

                $titularInstitucion = TitularInstitucion::where('id_titular', $esTitular->id)
                    ->where('estado', true)
                    ->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])
                    ->first();
                if ($titularInstitucion) {
                    $inst = Institucion::find($titularInstitucion->id_institucion);
                    $this->institucion = $inst->nombre;
                    $id_institucion = $inst->id;
                    if($titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null){
                        $this->codigoInstitucional = $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo . 
                        $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular;
                    } else {
                        $this->codigoInstitucional = null;
                    }
                } else {
                    $this->institucion = 'APORTANTE VOLUNTARIO';
                    $this->codigoInstitucional = null;
                }
            } else {
                $esBeneficiario = Beneficiario::where('id_persona', $personaSolicitante->id)
                    ->where('estado', true)
                    ->first();
                $this->foto = $this->obtenerImagenBase64($esBeneficiario->foto_nombre);
                $this->estado_vigencia = $esBeneficiario->vigencia;

                $titularBen = Titular::find($esBeneficiario->id_titular);
                $titularInstBen = TitularInstitucion::where('id_titular', $titularBen->id)
                    ->where('estado', true)
                    ->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])
                    ->first();
                if ($titularInstBen) {
                    $inst = Institucion::find($titularInstBen->id_institucion);
                    $this->institucion = $inst->nombre;
                    $id_institucion = $inst->id;
                    if($titularInstBen->titularInstitucionConInstitucion->codigosInstitucion != null){
                        $this->codigoInstitucional = $titularInstBen->titularInstitucionConInstitucion->codigosInstitucion->codigo . 
                        $titularInstBen->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular;
                    } else {
                        $this->codigoInstitucional = null;
                    }
                } else {
                    $this->institucion = 'APORTANTE VOLUNTARIO';
                    $this->codigoInstitucional = null;
                }
            }

            $esp = SolMedicaEspecialidad::join('administracion.especialidad', 'solmedica_especialidad.id_especialidad', '=', 'especialidad.id')
                ->where('id_solicitud_p_medica', $solicitud->id)
                ->get();
            if (isset($esp->toArray()[0])) {
                $this->especialidades = $esp;
            } else {
                $this->especialidades = null;
            }

            //dd($this->especialidades);
            $serv = SolMedicaServicio::join('administracion.servicio', 'solmedica_servicio.id_servicio', '=', 'servicio.id')
                ->where('id_solicitud_p_medica', $solicitud->id)
                ->get();

            if (isset($serv->toArray()[0])) {
                $this->servicios = $serv;
            } else {
                $this->servicios = null;
            }
            //dd($this->servicios);

            $solicitanteAdd = AseguradoSolicitante::join('administracion.persona', 'asegurado_solicitante.id_asegurado_solicitante', '=', 'persona.id')
                ->join('administracion.tipo_asegurado', 'persona.id_tipo_asegurado', '=', 'tipo_asegurado.id')
                ->where('asegurado_solicitante.id_solicitud_p_medica', $solicitud->id)
                ->orderBy('asegurado_solicitante.id', 'asc')
                ->skip(1)
                ->get();

            if (isset($solicitanteAdd->toArray()[0])) {
                $this->solicitantesAgregados = $solicitanteAdd;
            } else {
                $this->solicitantesAgregados = null;
            }
        }
    }

    private function obtenerImagenBase64($idFoto)
    {
        try {
            if ($idFoto) {
                $path = 'Afiliacion/FOTOS/' . $idFoto;
    
                $imageData = Storage::disk('ftp')->get($path);
                //dd($imageData);
                $foto = base64_encode($imageData);
                return $foto;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
        
    }

    public function verificarFirma(){
        if($this->documentoFirmado != null){
            $documento = base64_encode(file_get_contents($this->documentoFirmado->getRealPath()));
            //dd($documento);
            $verificacionFirmaDigital = new VerificacionFirmaDigital();
            $this->datosValidacion = $verificacionFirmaDigital->verificarDocumento($documento);
            if($this->datosValidacion == []){
                $this->switchValidacion = false;
                $this->dispatch('noFirmado');
            } else {
                $this->firmado_por = $this->datosValidacion[0]['certificado']['nombreSignatario'];
                $this->fecha_firmado = $this->datosValidacion[0]['fechaFirma'];
                $this->switchValidacion = true;
                $this->dispatch('firmado');
            }
        } else {
            $this->dispatch('sinDocumento');
        }
        
        //dd($this->datosValidacion);
    }

    public function guardarPdfFirmado(){

        //try {
            $solicitud = SolicitudPMedica::where('id', $this->idSolicitud)->with(['seguroInteriorSolicitudPMedica'])->first();
            $documento_nombre = $solicitud->id . '-VALIDADO.pdf';
            
            $solicitud->documento_nombre = $documento_nombre;
            $solicitud->estado_validacion = 'VALIDADO';
            $solicitud->fecha_validacion = Carbon::now();
            $solicitud->id_user_validado = auth()->user()->id;
            $solicitud->firmado_por = $this->firmado_por;
            $solicitud->fecha_firmado = $this->fecha_firmado;
            $solicitud->id_user_updated = auth()->user()->id;
            $solicitud->save();
            
            $this->documentoFirmado->storeAs('Vigencia/DOCUMENTOS/SOLICITUDES', $documento_nombre, 'ftp');

            NotificacionUsuarios::create([
                    'estado' => 'NO LEIDO',
                    'prioridades' => 1,
                    'id_usu_emisor' => auth()->user()->id,
                    'id_usu_receptor' => $solicitud->seguroInteriorSolicitudPMedica->id_usuario_responsable,
                    'fecha_hora_enviada' => Carbon::now(),
                    'asunto' => 'VIGENCIA DE DERECHOS - NUEVA SOLICITUD DE PRESTACIONES MÉDICAS',
                    'mensaje' => 'Se registró y validó una nueva solicitud de prestaciones médicas con codigo CITE: VVDD/GS/'.$solicitud->cod_cite.'/'.
                        Carbon::parse($solicitud->created_at)->format('Y'). ' para su consideración.',
            ]);

            $this->dispatch('guardado');
            return redirect()->route('vigencia.solprestacionesmedicas.index');
        //} catch (\Throwable $th) {
        //    $this->dispatch('noGuardado');
        //}
    }

    public function atras(){
        return redirect()->route('vigencia.solprestacionesmedicas.index');
    }


}
