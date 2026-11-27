<?php

namespace App\Livewire\Aportes\Institucion\Certificado;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Aportes\Institucion;
use App\Models\Aportes\ClaseAportante;
use App\Models\Administracion\Persona;
use App\Services\ValidarPersona;
use App\Services\ConexionAsuss;
use Livewire\Attributes\Validate;
use App\Models\Aportes\Certificado;
use App\Models\Administracion\Departamento;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;



class CertificadoMain extends Component
{
    public $idInst;
    public $institucion=null, $nit=null, $nombre=null, $ap_paterno,
     $ap_materno, $ci , $complemento ,$fecha_nacimiento;
    public $tipo_certificado= true, $nro_cite=null;
    public $estado_api = null ;
    public $tipo_doc, $existePersona, $complemento_m, $personaid, $cod_seguridad;

    protected $listeners = ['guardar_no_afi', 'guardar_afi'];

    public function render()
    {
        return view('livewire.Aportes.institucion.certificado.certificado-main');
    }
    public $departamento;
    public function mount(){
        $this->departamento = Departamento::get();
    }

    // modal para la vista principal de listados de instituciones
    public function registrarCertificado(){
        $this->dispatch('modalRegistro');
    }
    
    public function registrarInstitucion(){
        $this->institucion ='';
        $this->nit = '';
        $this->nombre = '';
        $this->ap_paterno ='';
        $this->ap_materno ='';
        $this->ci = '';
        $this->complemento ='';
        $this->fecha_nacimiento ='';
        $this->funcion_nro_cite_no_afi();
        $this->dispatch('modalRegistrarInstitucion');
        
        $this->skipRender();

    
    }

    // agregarCertificado
    #[On('agregarCertificado')]
    public function agregarCertificado($data)
    {
        $this->idInst= $data;
        $this->mostrarInstitucion($this->idInst);
        $this->funcion_nro_cite_afi();
        $this->dispatch('modalRegistrarCertificado');
        $this->skipRender();
        
    }
    public function mostrarInstitucion($instId)
    { 
        if ($instId) {
            $institucion = Institucion::find($instId);

            $this->institucion = $institucion->nombre;
            $this->nit = $institucion->nit;
            $tipoAportante = ClaseAportante::find($institucion->id_clase_aportante);
            

            $representante = Persona::find($institucion->id_persona);
            $this->nombre=$representante->nombres;
            $this->ap_paterno=$representante->p_apellido;
            $this->ap_materno=$representante->s_apellido;
            $this->ci=$representante->ci;
            $this->fecha_nacimiento=$representante->fecha_nacimiento;
            $this->complemento=$representante->complemento;
            
            return $institucion;
        }
    }

    public function search_persona()
    {
        if ($this->complemento == '') {
            $this->complemento = null;
        }
        try{
            $this->existePersona = Persona::where('ci', $this->ci)
                ->where('complemento', $this->complemento)
                ->orderBy('id', 'desc')
                ->first();

            if ($this->existePersona != null) {
                $this->estado_api = 1;
                $this->nombre = $this->existePersona->nombres;
                $this->ap_paterno = $this->existePersona->p_apellido;
                $this->ap_materno = $this->existePersona->s_apellido;
                $this->fecha_nacimiento = $this->existePersona->fecha_nacimiento;
                //$this->fecha_nacimiento = mb_strtoupper(Carbon::parse($this->existePersona->fecha_nacimiento)->translatedFormat('d \d\e F \d\e Y'));
                $this->ci = $this->existePersona->ci;
                $this->complemento_m = $this->existePersona->complemento;
            } else {
                $respuesta = new ValidarPersona();
                $dato = $respuesta->buscarPersona($this->ci, $this->complemento);

                if (isset($dato['datosPersonaEnFormatoJson'])) {
                    $this->ci = $dato['datosPersonaEnFormatoJson']['numeroDocumento'];
                    $this->complemento_m = $dato['datosPersonaEnFormatoJson']['Complemento'];
                    $this->ap_paterno = $dato['datosPersonaEnFormatoJson']['primerApellido'];
                    $this->ap_materno = $dato['datosPersonaEnFormatoJson']['segundoApellido'];
                    $this->nombre = $dato['datosPersonaEnFormatoJson']['nombres'];
                    $this->fecha_nacimiento = $dato['datosPersonaEnFormatoJson']['fechaNacimiento'];
                    //$this->fecha_nacimiento = mb_strtoupper(Carbon::parse($dato['datosPersonaEnFormatoJson']['fechaNacimiento'])->translatedFormat('d \d\e F \d\e Y'));
                    $this->estado_api = 1;
                } else {
                    $this->estado_api = 0;
                    $this->nombre = '';
                    $this->ap_paterno = '';
                    $this->ap_materno = '';
                    $this->fecha_nacimiento = '';
                    $this->ci = '';
                    $this->complemento_m = '';
                }
            }
            
        } catch (\Throwable $th) {
            $this->dispatch('noExisteUsuario');
        } 
        $this->skipRender();
    }
    public $cod_certificado_na, $nro_cite_m_na, $nro_cite_no_afi;

    public function funcion_nro_cite_no_afi(){
            
            $anio = Carbon::now()->format('Y');
            $currentYear = Carbon::now()->year;
            // Aqui se obtiene el ultimo cite de tipo certificado NO AFILIADO
            
            $afiliado_nro = Certificado::where('tipo_certificado', false)
            ->whereYear('created_at', $currentYear)
            ->orderBy('cite', 'desc')
            ->first();
            
            // Para validar que se reseetee el conteo cada que termine el año
            if (!$afiliado_nro) {
                $this->nro_cite_no_afi = 1;
                $this->nro_cite_m_na = $this->nro_cite_no_afi.'/'.$anio;
                $this->cod_certificado_na = 'CERT-NO-AFI-'.$this->nro_cite_no_afi.'-'.$currentYear;

            } else {
                $this->nro_cite_no_afi = $afiliado_nro->cite + 1;
                $this->nro_cite_m_na = $this->nro_cite_no_afi.'/'.$anio;
                $this->cod_certificado_na = 'CERT-NO-AFI-'.$this->nro_cite_no_afi.'-'.$currentYear;
            }
        $this->skipRender();


    }

    public $cod_certificado_a, $nro_cite_m_a, $nro_cite_afi;

    public function funcion_nro_cite_afi(){
        //dd('ver funcion  del cite esta mal ...');
        $anio = Carbon::now()->format('Y');
        $currentYear = Carbon::now()->year;
        
        $hoy = Carbon::today();
        $primerDiaDelAno = Carbon::createFromDate($hoy->year, 1, 1);
        // Aqui se obtiene el ultimo cite de tipo certificado AFILIADO
        
            $afiliado_nro_true = Certificado::where('tipo_certificado', true)
            ->whereYear('created_at', $currentYear)
            ->orderBy('cite', 'desc')
            ->first();

            // Para validar que se reseetee el conteo cada que termine el año
            if (!$afiliado_nro_true) {
                $this->nro_cite_afi = 1;
                $this->nro_cite_m_a = $this->nro_cite_afi.'/'.$anio;
                $this->cod_certificado_a = 'CERT-AFI-'.$this->nro_cite_afi.'-'.$currentYear;
                
            } else {
                $this->nro_cite_afi = $afiliado_nro_true->cite +1;
                $this->nro_cite_m_a = $this->nro_cite_afi.'/'.$anio;
                $this->cod_certificado_a = 'CERT-AFI-'.$this->nro_cite_afi.'-'.$currentYear;

            }
        $this->skipRender();


    }



    public $fecha_solicitud = null, $fecha_validez, $fecha_solicitud_register, $fecha_validez_register;
    public function funcion_fecha(){

        // Convertir la fecha a Y-m-d
        $this->fecha_solicitud_register = Carbon::parse($this->fecha_solicitud)->format('Y-m-d');

        // Asegúrate de que $this->fecha_solicitud tenga un valor válido
        $fecha_inicial = Carbon::parse($this->fecha_solicitud);
        // Sumamos 15 días a la fecha inicial
        $this->fecha_validez = $fecha_inicial->addDays(15)->format('d/m/Y');

        $this->fecha_validez_register = $fecha_inicial->addDays(15)->format('Y-m-d');
        $this->skipRender();

    }
    public $expedido_ci;
    public function guardar_no_afi(){
       
        DB::beginTransaction();
        try{

            if ( $this->institucion == null || $this->nit == null || $this->fecha_solicitud == null || $this->expedido_ci == null || $this->tipo_doc == null) {
           
                $this->dispatch('completar_datos');
            }else{

            

            $clave_unica_generado = $this->ci.$this->complemento;

            $existePersona = Persona::where('clave_unica', $clave_unica_generado)
            ->first();

            if ($existePersona != null) {

                $persona = Persona::find($existePersona->id);
                if ($persona) {
                    $persona->id_dept_exp = $this->expedido_ci;
                    $persona->es_extranjero = $this->tipo_doc;
                }
                $persona->save();

                $personaId = $persona->id;
 
            } else {

                if($this->complemento == null){
                    $clave_unica = $this->ci;
                } else {
                    $clave_unica = $this->ci.$this->complemento;
                }

                $persona = Persona::create([
                    'p_apellido' => $this->ap_paterno,
                    's_apellido' => $this->ap_materno,
                    'nombres' => $this->nombre,
                    'ci' => $this->ci,
                    'complemento' => $this->complemento,
                    'fecha_nacimiento' => $this->fecha_nacimiento,
                    'afiliado' => false,
                    'id_tipo_asegurado' => 0,
                    'es_extranjero' => $this->tipo_doc,
                    'id_dept_exp' => $this->expedido_ci,
                    'clave_unica' => $clave_unica,
                    'id_user_created' => auth()->user()->id,
                ]);
                
                $personaId = $persona->id;
            }

            // Paso 1: Obtener la fecha y hora actual
            $fechaHoraActual = date('Y-m-d H:i:s');

            $textoParaHash = $fechaHoraActual ;

            // Paso 3: Generar el hash SHA-256 completo
            $cod_seguridad = hash('sha256', $textoParaHash);

            $certificado = Certificado::create([
                'cite' => $this->nro_cite_no_afi,
                'id_persona' => $personaId,
                'id_institucion' => $this->idInst,
                'nombre_institucion' => $this->institucion,
                'nit' => $this->nit,
                'fecha_solicitud' => $this->fecha_solicitud_register,
                'fecha_validez' => $this->fecha_validez_register,
                'cod_certificado' => $this->cod_certificado_na,
                'cod_seguridad' => $cod_seguridad,
                'tipo_certificado' => false,
                'id_user_created' => auth()->user()->id,
            ]);
            
            DB::commit();

            // para que se descargue el pdf certificado   
            $this->js('window.open("/aportes/pdf_certificado/' .$certificado->id. '", "_blank")');

            $this->dispatch('pg:eventRefresh-CertificadoRegistro');
            $this->dispatch('guardado');
            
            // Una vez guardado limpiamos todos los datos
            $this->nro_cite =null;
            $this->personaid='';
            $this->tipo_certificado = true;
            $this->cod_seguridad='';
            $this->institucion = null;
            $this->nit=null;
            $this->fecha_solicitud =null;
            $this->fecha_validez ='';
            $this->tipo_doc =null;
            $this->expedido_ci ='';
        }
            //dd($documentoPdf);
            //DocumentoPDF::create($documentoPdf);

        } catch (\Throwable $th) {
            
           DB::rollBack();
           $this->dispatch('error');
        }

        $this->skipRender();
    }

    public function guardar_afi(){
        DB::beginTransaction();
        try{

            if ($this->institucion == null || $this->nit == null || $this->fecha_solicitud == null ) {
           
                $this->dispatch('completar_datos');
            }
            else{

            
            $clave_unica_generado = $this->ci.$this->complemento;

            $existePersona = Persona::where('clave_unica', $clave_unica_generado)
            ->first();

            //dd($existePersona,$this->complemento,$this->nombre,$this->ap_paterno,$this->ap_materno, $this->fecha_nacimiento  );

            if ($existePersona != null) {
                
                $personaId = $existePersona->id;
                //dd($personaId);
            } else {

                if($this->complemento == null){
                    $clave_unica = $this->ci;
                } else {
                    $clave_unica = $this->ci.$this->complemento;
                }
                //dd('se creará nuevo');

                $persona = Persona::create([
                    'p_apellido' => $this->ap_paterno,
                    's_apellido' => $this->ap_materno,
                    'nombres' => $this->nombre,
                    'ci' => $this->ci,
                    'complemento' => $this->complemento,
                    'fecha_nacimiento' => $this->fecha_nacimiento,
                    'afiliado' => false,
                    'id_tipo_asegurado' => 0,
                    'clave_unica' => $clave_unica,
                    'id_user_created' => auth()->user()->id,
                ]);
                
                $personaId = $persona->id;
            }

            // Paso 1: Obtener la fecha y hora actual
            $fechaHoraActual = date('Y-m-d H:i:s');

            $textoParaHash = $fechaHoraActual ;

            // Paso 3: Generar el hash SHA-256 completo
            $cod_seguridad = hash('sha256', $textoParaHash);
            
            $certificado = Certificado::create([
                'cite' => $this->nro_cite_afi,
                'id_persona' => $personaId,
                'id_institucion' => $this->idInst,
                'nombre_institucion' => $this->institucion,
                'nit' => $this->nit,
                'fecha_solicitud' =>  $this->fecha_solicitud_register,
                'fecha_validez' => $this->fecha_validez_register,
                'cod_certificado' => $this->cod_certificado_a,
                'cod_seguridad' => $cod_seguridad,
                'tipo_certificado' => true,
                'id_user_created' => auth()->user()->id,
            ]);
                                              

            DB::commit();

            // para que se descargue el pdf comprobante   
            $this->js('window.open("/aportes/pdf_certificado/' .$certificado->id. '", "_blank")');

            $this->dispatch('pg:eventRefresh-CertificadoRegistro');
            $this->dispatch('guardado');
            
            // Una vez guardado limpiamos todos los datos
            $this->nro_cite =null;
            $this->personaid='';
            $this->tipo_certificado = true;
            $this->cod_seguridad='';
            $this->institucion = null;
            $this->nit=null;
            $this->fecha_solicitud =null;
            $this->fecha_validez ='';
        }


        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('error');
        }

        $this->skipRender();
    }

    #[On('pdfCertificado')]
    public function pdfCertificado($data)
    {
        $this->mostrarArchivoPdf($data);
        $this->dispatch('modalVerCertificado');
        
        //$this->js('window.open("/aportes/pdf_certificado_vista/' .$data. '", "_blank")');
    }
    public $pdf_vista;
    public function mostrarArchivoPdf($id)
    {
        try {
            if($id){
                $certificado = Certificado::find($id); 
                $cod_certificado = $certificado->cod_certificado;

                //$path = 'Aportes/Certificados/' . $id . '.pdf';
                $path = 'Aportes/Certificados/' . 'CE_' . $id . '_' . $cod_certificado . '.pdf';
                //dd($this->idTit); // Adjust path as needed
                $fileContents = Storage::disk('ftp')->get($path);
                $pdfBase64 = base64_encode($fileContents);
                //dd($pdfBase64);
                $this->pdf_vista = $pdfBase64;
            }
            
        } catch (\Throwable $th) {
            return null;
        }
    }
    

}
