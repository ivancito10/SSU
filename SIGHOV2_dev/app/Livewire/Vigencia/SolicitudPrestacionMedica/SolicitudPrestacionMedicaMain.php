<?php

namespace App\Livewire\Vigencia\SolicitudPrestacionMedica;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;
use App\Models\Vigencia\SolicitudPMedica;
use App\Models\Vigencia\SeguroInterior;
use App\Models\Vigencia\AseguradoSolicitante;
use App\Models\Vigencia\SolMedicaEspecialidad;
use App\Models\Vigencia\SolMedicaServicio;

use App\Models\Aportes\Institucion;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Administracion\Persona;
use App\Models\Administracion\TipoAsegurado;
use App\Models\Administracion\Especialidad;
use App\Models\Administracion\Servicio;
use App\Models\User;
use PDF;

use Carbon\Carbon;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Services\VerificacionFirmaDigital;
use App\Services\VerificarDarBaja;

class SolicitudPrestacionMedicaMain extends Component
{
    use WithFileUploads;
    #[Locked]
    public $idSol;
    protected $listeners = ['guardarValidacion', 'guardarAnulacion', 'actualizarPrestacionMedica', 'guardarPdfFirmado'];

    public $estado_vigencia, $especialidades = [], $servicios = [],
    $id_seguro_interior;

    #[Locked]
    public $cod_cite,
        $solicitantesAgregados,
        $usuarioNombre,
        $fecha_creacion,
        $fecha_inicio,
        $fecha_fin,
        $otras_limitaciones,
        $estado_validacion,
        $fecha_validacion,
        $observacion,
        $medicamentos_liname,
        $medicamentos_extraliname,
        $nro_hoja_de_ruta,
        $fecha_solicitud_hr,
        $nombreCompleto,
        $matricula,
        $complemento,
        $tipo_asegurado,
        $foto,
        $ci,
        $nombre_seguro,
        $institucion,
        $personaVal,
        $personaAnulado,
        $motivo_anulacion,
        $fecha_anulacion,
        $id_user_created,
        //para el editar
        $datos_persona_grupo_familiar = [];
    
    public $seguros = [];
    public $especialidadesSelect = [];
    public $serviciosSelect = [];
    public $documentoFirmado;
    public $diasVigencia; 
    public $edadPersona;
    public $codigoInstitucional;
    public $sexo;
    public $fecha_nacimiento;
    public $fechaInicio, $fechaFin;

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

            $usuarioReg = User::find($solicitud->id_user_created);
            $usuarioDatos = Persona::find($usuarioReg->id_persona);
            $this->usuarioNombre = $usuarioDatos->p_apellido . ' ' . $usuarioDatos->s_apellido . ' ' . $usuarioDatos->nombres;
            //VALIDACION
            //dd($solicitud->id_user_validado);
            if ($solicitud->id_user_validado != null) {
                $usuarioValidado = User::find($solicitud->id_user_validado);
                $personaValidado = Persona::find($usuarioValidado->id_persona);
                $this->personaVal = $personaValidado->p_apellido . ' ' . $personaValidado->s_apellido . ' ' . $personaValidado->nombres;
            } else {
                $this->personaVal = null;
            }

            //ANULACION
            //dd($solicitud->id_user_anulado);
            if ($solicitud->id_user_anulado != null) {
                $usuarioAnula = User::find($solicitud->id_user_anulado);
                $personaAnulado = Persona::find($usuarioAnula->id_persona);
                $this->personaAnulado = $personaAnulado->p_apellido . ' ' . $personaAnulado->s_apellido . ' ' . $personaAnulado->nombres;
            } else {
                $this->personaAnulado = null;
            }

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
                        $titularInstBen->titularInstitucionConInstitucion->codigosInstitucion->tipo_beneficiario;
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

    public function render()
    {
        //$verificacionFirmaDigital = new VerificacionFirmaDigital();
        //$verificacionFirmaDigital->verificarDocumento("");
        return view('livewire.Vigencia.solicitud-prestacion-medica.solicitud-prestacion-medica-main');
    }

    public function mount(){
        $this->seguros = SeguroInterior::where('estado', true)->get();
        $this->especialidadesSelect = Especialidad::all();
        $this->serviciosSelect = Servicio::all();

        $bajas = new VerificarDarBaja();
        $bajas->verificarDarBaja();
        $bajas->verificarConvenios();
        $bajas->bajaMayores25();
        $bajas->bajaMayores19();
    }

    #[On('verSolicitud')]
    public function verSolicitud($data)
    {
        $this->idSol = $data;
        $this->mostrarSolicitud($this->idSol);
        $this->dispatch('modalVerSolicitud');
    }


    //VERSION ANTIGUA
    /*#[On('validarSolicitud')]
    public function validarSolicitud($data)
    {
        $this->idSol = $data;
        $this->dispatch('alertaFirmarDigitalmente');
    }*/

    //NUEVA VERSION
    #[On('validarSolicitud')]
    public function validarSolicitud($data)
    {
        $this->idSol = $data;
        $this->dispatch('verificarFirmaModal');
        
    }

    public function guardarPdfFirmado(){
        dd('probando que entre', $this->documentoFirmado);
    }

    public $datosValidacion = [];
    public function verificarFirma(){
        $documento = base64_encode(file_get_contents($this->documentoFirmado->getRealPath()));
        //dd($documento);
        $verificacionFirmaDigital = new VerificacionFirmaDigital();
        $this->datosValidacion = $verificacionFirmaDigital->verificarDocumento($documento);
        //dd($this->datosValidacion);
        $this->skipRender();
    }

    #[On('anularSolicitud')]
    public function anularSolicitud($data)
    {
        $this->idSol = $data;
        $this->dispatch('alertaAnular');
    }

    public function guardarValidacion()
    {
        //dd($this->idBaja);
        try {
            $solicitud = SolicitudPMedica::find($this->idSol);

            if ($solicitud) {
                $solicitud->estado_validacion = 'VALIDADO';
                $solicitud->fecha_validacion = Carbon::now();
                $solicitud->id_user_validado = auth()->user()->id;
                $solicitud->save();

                $this->dispatch('pg:eventRefresh-SolicitudesTable');
                $this->dispatch('guardado');
            }
        } catch (\Throwable $th) {
            $this->dispatch('noValidado');
        }
    }

    public function guardarAnulacion($data)
    {
        try {
            $solicitud = SolicitudPMedica::find($this->idSol);

            if ($solicitud) {
                $solicitud->estado_validacion = 'ANULADO';
                $solicitud->motivo_anulacion = $data;
                $solicitud->id_user_anulado = auth()->user()->id;
                $solicitud->fecha_anulacion = Carbon::now();
                $solicitud->save();

                $this->dispatch('pg:eventRefresh-SolicitudesTable');
                $this->dispatch('guardado');
            }
        } catch (\Throwable $th) {
            $this->dispatch('noValidado');
        }
    }


        // Para el pdf 
    #[On('prestacionPdf')]
    public function prestacionPdf($data){
            
        $solicitudValidada = SolicitudPMedica::find($data);
        if($solicitudValidada){
            if($solicitudValidada->estado_validacion == 'VALIDADO'){
                $gestionCreado = Carbon::parse($solicitudValidada->created_at)->format('Y');
                //$this->js('window.open("prestacion_medicaPdf/'.$data . '", "_blank")');
                $this->descargarArchivo($solicitudValidada->documento_nombre, $solicitudValidada->cod_cite, $gestionCreado);
            } else {
                $this->js('window.open("prestacion_medicaPdf/'.$data . '", "_blank")');
            } 
        } else {
            $this->js('window.open("prestacion_medicaPdf/'.$data . '", "_blank")');
        }        
    }

    public function descargarArchivo($nombrePdf, $codCite, $gestionCreado)
    {
        try {
            return redirect()->route('archivo.descargar', [
                'nombrePdf' => $nombrePdf,
                'codCite' => $codCite,
                'gestionCreado' => $gestionCreado,
            ]);
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function generarPDF()
    {
        set_time_limit(12000);
        if ($this->fechaInicio != null && $this->fechaFin != null) {
            $this->js('window.open("/vigencia/PrestacionMedica/' . $this->fechaInicio . '/' . $this->fechaFin . '")');
        } else {
            $this->dispatch('completeDatos');
            $this->fechaInicio = null;
            $this->fechaFin = null;
        }
        $this->skipRender();
    }
}
