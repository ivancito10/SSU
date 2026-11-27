<?php

namespace App\Livewire\Afiliacion\Ssue;

use App\Models\Afiliacion\Estudiante;
use App\Models\Administracion\Persona;
use App\Models\Administracion\Residencia;
use App\Models\Administracion\Departamento;
use App\Models\Administracion\Provincia;
use App\Models\Administracion\Municipio;
use App\Models\Administracion\Zona;
use App\Models\Administracion\EstadoCivil;
use App\Models\Afiliacion\Facultad;
use App\Models\Afiliacion\Carrera;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class SsueSeguimiento extends Component
{
    public $foto;
    public $fotito;
    //datos personales
    #[Locked]
    public $id_persona;
    public $nombres, $p_apellido, $s_apellido, $ci, $complemento, $fecha_nacimiento, $edad;
    public $es_extranjero, $extranjeroTexto;
    public $estado_civil, $id_estado_civil;
    #[Locked]
    public $id_expedido_ci, $expedido_ci;
    public $id_dep_nacimiento, $dep_nacimiento;
    public $id_pro_nacimiento, $pro_nacimiento;
    public $id_mun_nacimiento, $mun_nacimiento;
    public $departamento;
    public $provincias = [];
    public $municipios = [];
    public $id_tipo_asegurado = 11;
    public $matricula;
    public $sexo, $sexoTexto;
    //datos referenciales
    #[Locked]
    public $pdf_fotocopia;
    #[Locked]
    public $id_estudiante;
    public $estado_vigencia;
    public $estado_validar;
    public $telefono, $correo_institucional, $nombre_referencia, $telefono_referencia;
    public $alergias;
    public $observaciones;
    public $grupo_sanguineo;
    public $fecha_solicitud;
    public $fecha_afiliacion, $fecha_reafiliacion, $fecha_fin_vigencia;
    public $direccion, $zona, $zonaId, $zonasSelect;
    public $nro_vivienda, $id_residencia;
    // datos universitarios 
    public $registro_universitario, $gestion_ingreso, $semestre_actual;
    public $facultad, $id_facultad;
    public $carrera, $id_carrera, $idCarrera;
    public $carreras = [];
    public $idEst;
    #[Locked]
    public $pdf_matricula;
    #[Locked]
    public $pdf_boleta;
    #[Locked]
    public $pdf_formulario;
    #[Locked]
    public $pdf_certificado;

    public $pdf_tipo_sangre, $tieneNacimiento;
    public $d_latitud, $d_longitud;

    public $mostrarCarnet, $fecha_vencimiento, $clave_unica, $qrCode;
    public function render()
    {
        return view('livewire.Afiliacion.ssue.ssue-seguimiento');
    }

    public function mount()
    {
        $this->mostrarEstudiante($this->id_persona);
    }

    public function mostrarEstudiante($id_persona)
    {
        $userActual = User::join('administracion.persona as p', 'p.id', '=', 'administracion.users.id_persona')
            ->where('administracion.users.id', '=', auth()->user()->id)
            ->first();

        $this->id_persona = $userActual->id_persona;
        $personaActual = Persona::find($this->id_persona);
        $this->nombres = $personaActual->nombres;
        $this->p_apellido = $personaActual->p_apellido;
        $this->s_apellido = $personaActual->s_apellido;
        $this->clave_unica = $personaActual->clave_unica;
        $this->complemento = $personaActual->complemento;
        if ($this->complemento) {
            $this->ci = $personaActual->ci . ' - ' .  $this->complemento;
        } else {
            $this->ci = $personaActual->ci;
        }

        $this->fecha_nacimiento = $personaActual->fecha_nacimiento;
        $this->fecha_nacimiento = Carbon::parse($personaActual->fecha_nacimiento)->format('d/m/Y');
        $this->edad = Carbon::parse($personaActual->fecha_nacimiento)->age;

        $this->matricula = $personaActual->matricula_seguro;
        $this->sexo = $this->setSexoTexto($personaActual->sexo);
        $this->id_estado_civil = $personaActual->id_estado_civil;
        $estadoCivil = Estadocivil::find($this->id_estado_civil);
        $this->estado_civil = $estadoCivil->nombre;
        $this->es_extranjero = $this->setExtranjeroTexto($personaActual->es_extranjero);
        $this->tieneNacimiento = $personaActual->es_extranjero;
        $this->id_expedido_ci = $personaActual->id_dept_exp;
        $depExpedido = Departamento::find($this->id_expedido_ci);
        $this->expedido_ci = $depExpedido->sigla ?? ' ';
        $this->id_mun_nacimiento = $personaActual->id_nacimiento_municipio;
        if ($this->id_mun_nacimiento) {
            $munNacimiento = Municipio::find($this->id_mun_nacimiento);
            $this->mun_nacimiento = $munNacimiento->nombre;
            $this->id_pro_nacimiento = $munNacimiento->id_provincia;
            $provNacimiento = Provincia::find($this->id_pro_nacimiento);
            $this->pro_nacimiento = $provNacimiento->nombre;
            $this->id_dep_nacimiento = $provNacimiento->id_departamento;
            $depNacimiento = Departamento::find($this->id_dep_nacimiento);
            $this->dep_nacimiento = $depNacimiento->nombre;
        } else {
            $this->mun_nacimiento = ' Sin dato';
            $this->pro_nacimiento = ' Sin dato';
            $this->dep_nacimiento = ' Sin dato';
        }

        $this->grupo_sanguineo = $personaActual->grupo_sanguineo;
        $this->alergias = $personaActual->alergias ?: 'NINGUNO';


        $estudiante = Estudiante::where('id_persona', $this->id_persona)->where('estado', true)->first();
        $this->idEst = $estudiante->id;
        $this->telefono = $estudiante->telefono;
        $this->correo_institucional = $estudiante->correo_institucional;
        $this->nombre_referencia = $estudiante->nombre_referencia;
        $this->telefono_referencia = $estudiante->telefono_referencia;

        $this->estado_validar = $estudiante->estado_validar;

        $this->observaciones = $estudiante->observaciones;
        $this->fecha_solicitud = $estudiante->fecha_solicitud_afiliacion ? Carbon::parse($estudiante->fecha_solicitud_afiliacion)->Format('d/m/Y') : '';
        $fecha = $estudiante->fecha_reafiliacion ?? $estudiante->fecha_afiliacion;
        $this->fecha_afiliacion = $fecha ? Carbon::parse($fecha)->format('d/m/Y')  : 'NO AFILIADO AÚN';
        $this->fecha_vencimiento =   Carbon::parse($estudiante->fecha_fin_vigencia)->Format('d/m/Y');
        $this->fecha_reafiliacion = $estudiante->fecha_reafiliacion ? Carbon::parse($estudiante->fecha_reafiliacion)->translatedFormat('d/m/Y')  : '';
        $this->fecha_fin_vigencia = $estudiante->fecha_fin_vigencia ? Carbon::parse($estudiante->fecha_fin_vigencia)->translatedFormat('d/m/Y')  : '';
        $this->id_residencia = $estudiante->id_residencia;
        $residenciaActual = Residencia::find($this->id_residencia);
        $this->direccion = $residenciaActual->direccion;
        $this->nro_vivienda = $residenciaActual->nro_vivienda;
        $this->d_latitud = $residenciaActual->latitud;
        $this->d_longitud = $residenciaActual->longitud;
        $zonaActual = Zona::find($residenciaActual->id_zona);
        if ($zonaActual) {
            $this->zona = $zonaActual->nombre;
            $this->zonaId = $zonaActual->id;
        } else {
            $this->zona = 'SIN DATO';
        }

        $this->registro_universitario = $estudiante->registro_universitario;
        $this->gestion_ingreso = $estudiante->gestion;
        $this->semestre_actual = $estudiante->semestre;
        $this->id_carrera = $estudiante->id_carrera;
        $carreraActual = Carrera::find($this->id_carrera);
        if ($carreraActual) {
            $this->carrera = $carreraActual->nombre ?? ' ';
            $this->id_facultad = $carreraActual->id_facultad;
            if ($this->id_facultad) {
                $facultadActual = Facultad::find($this->id_facultad);
                $this->facultad = $facultadActual->nombre;
            } else {
                $this->facultad = '';
            }
        }
        $this->foto = $this->obtenerImagenBase64($estudiante->foto_nombre);
    }

    // funciona para mandar el texto del sexo
    private function setSexoTexto($sexo)
    {
        if ($sexo == 'F') {
            return 'FEMENINO';
        } elseif ($sexo == 'M') {
            return 'MASCULINO';
        } else {
            return 'No especificado';
        }
    }

    // funcion para el texto de la nacionalidad
    private function setExtranjeroTexto($es_extranjero)
    {
        if ($es_extranjero == false) {
            return 'NACIONAL';
        } elseif ($es_extranjero == true) {
            return 'EXTRANJERO';
        } else {
            return 'NO ESPECIFICADO';
        }
    }

    // Funcion para obtener imagenes desde la FTP 
    private function obtenerImagenBase64($filename)
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

    public function salir()
    {
        return redirect()->route('dashboard');
    }

    //Abrir modal fotocopia C.I
    public function fotocopia_ci()
    {
        // Archivos PDF en las vistas modal
        $this->mostrarFotocopia();
        $this->dispatch('modalFotocopiaCi');
    }
    public function descargaFotocopia()
    {
        try {
            return redirect()->route('archivo.descargarci', [
                'idEstudiante' => $this->idEst,
                'nombreEstudiante' => $this->p_apellido . ' ' . $this->s_apellido . ' ' . $this->nombres,
                'carnetEstudiante' => $this->ci,
            ]);
        } catch (\Throwable $th) {
            return null;
        }
    }
    //Funcion para descargar un Archivo de la FTP, con extension PDF
    public function mostrarFotocopia()
    {
        $path = 'Afiliacion/SSUE/DOCUMENTOS/' . 'Carnet-' . $this->idEst . '.pdf'; // Adjust path as needed

        $fileContents = Storage::disk('ftp')->get($path);
        $pdfBase64 = base64_encode($fileContents);
        // esto se envia al modal 
        $this->pdf_fotocopia = $pdfBase64;
    }

    //Abrir modal matricula
    public function matriculas()
    {
        // Archivos PDF en las vistas modal
        $this->mostrarMatricula();
        $this->dispatch('modalVerMatricula');
    }
    public function descargarMatricula()
    {
        try {
            return redirect()->route('archivo.descargarmatricula', [
                'idEstudiante' => $this->idEst,
                'nombreEstudiante' => $this->p_apellido . ' ' . $this->s_apellido . ' ' . $this->nombres,
                'carnetEstudiante' => $this->ci,
            ]);
        } catch (\Throwable $th) {
            return null;
        }
    }
    public function mostrarMatricula()
    {
        $path = 'Afiliacion/SSUE/DOCUMENTOS/' . 'Matricula-' . $this->idEst . '.pdf'; // Adjust path as needed
        //dd($path);
        $fileContents = Storage::disk('ftp')->get($path);
        $pdfBase64 = base64_encode($fileContents);
        $this->pdf_matricula = $pdfBase64;
    }

    //Abrir modal boleta
    public function boleta()
    {
        $this->mostrarBoleta();
        $this->dispatch('modalVerBoleta');
    }
    public function descargarBoleta()
    {
        try {
            return redirect()->route('archivo.descargarboleta', [
                'idEstudiante' => $this->idEst,
                'nombreEstudiante' => $this->p_apellido . ' ' . $this->s_apellido . ' ' . $this->nombres,
                'carnetEstudiante' => $this->ci,
            ]);
        } catch (\Throwable $th) {
            return null;
        }
    }
    public function mostrarBoleta()
    {
        $path = 'Afiliacion/SSUE/DOCUMENTOS/' . 'Boleta_Inscripcion-' . $this->idEst . '.pdf'; // Adjust path as needed
        //dd($path);
        $fileContents = Storage::disk('ftp')->get($path);
        $pdfBase64 = base64_encode($fileContents);
        $this->pdf_boleta = $pdfBase64;
    }

    //Abrir modal form de no afilaicion
    public function form_no_afiliacion()
    {
        // Archivos PDF en las vistas modal
        $this->mostrarFormulario();
        $this->dispatch('modalVerformulario');
    }

    public function descargarGestora()
    {
        try {
            return redirect()->route('archivo.descargarformnoafiliacion', [
                'idEstudiante' => $this->idEst,
                'nombreEstudiante' => $this->p_apellido . ' ' . $this->s_apellido . ' ' . $this->nombres,
                'carnetEstudiante' => $this->ci,
            ]);
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function mostrarFormulario()
    {
        $path = 'Afiliacion/SSUE/DOCUMENTOS/' . 'Certificado_Gestora-' . $this->idEst . '.pdf'; // Adjust path as needed
        $fileContents = Storage::disk('ftp')->get($path);
        $pdfBase64 = base64_encode($fileContents);
        $this->pdf_formulario = $pdfBase64;
    }

    //Abrir modal certificado_cajas 101
    public function certificado_cajas()
    {
        // Archivos PDF en las vistas modal
        $this->mostrarCertificado();
        $this->dispatch('modalVerCertificadoCajas');
    }
    public function descargarForm101()
    {
        try {
            return redirect()->route('archivo.descargarcertificadocajas', [
                'idEstudiante' => $this->idEst,
                'nombreEstudiante' => $this->p_apellido . ' ' . $this->s_apellido . ' ' . $this->nombres,
                'carnetEstudiante' => $this->ci,
            ]);
        } catch (\Throwable $th) {
            return null;
        }
    }
    public function mostrarCertificado()
    {
        $path = 'Afiliacion/SSUE/DOCUMENTOS/' . 'Certificado_Cajas-' . $this->idEst . '.pdf'; // Adjust path as needed
        //dd($path);
        $fileContents = Storage::disk('ftp')->get($path);
        $pdfBase64 = base64_encode($fileContents);
        $this->pdf_certificado = $pdfBase64;
    }

    //Abrir modal tipo_sangre
    public function tipo_sangre()
    {
        // Archivos PDF en las vistas modal
        $this->mostrarTipoSangre();

        $this->dispatch('modalTipoSangre');
    }
    public function descargarTipoSangre()
    {
        try {
            return redirect()->route('archivo.descargartiposangre', [
                'idEstudiante' => $this->idEst,
                'nombreEstudiante' => $this->p_apellido . ' ' . $this->s_apellido . ' ' . $this->nombres,
                'carnetEstudiante' => $this->ci,
            ]);
        } catch (\Throwable $th) {
            return null;
        }
    }
    public function mostrarTipoSangre()
    {
        $path = 'Afiliacion/SSUE/DOCUMENTOS/' . 'Tipo_Sangre-' . $this->idEst . '.pdf'; // Adjust path as needed
        //dd($path);
        $fileContents = Storage::disk('ftp')->get($path);
        $pdfBase64 = base64_encode($fileContents);
        $this->pdf_tipo_sangre = $pdfBase64;
    }

    public function AbrirModalUbicacionEstudiante()
    {
        $this->mostrarEstudiante($this->id_persona);
        // dd($this->d_latitud,$this->d_longitud);
        $this->dispatch('ModalAbrirUbicacionEstudiante');
        $this->skipRender();
    }
    public function BotonPrueba()
    {
        // Archivos PDF en las vistas modal
        $this->mostrarTipoSangre();

        $this->dispatch('modalTipoSangre');
    }


    public function generar_carnet_ssue()
    {
        //return redirect()->route('afiliacion.generar_carnet_ssue.umsa', ['id' => $this->id_persona]);
        $this->qrCode = base64_encode(QrCode::format('svg')
            ->size(300)
            ->color(
                3,
                125,
                155
            )
            ->margin(1) // Margen adicional
            ->backgroundColor(255, 255, 255)
            ->errorCorrection('H')
            ->generate($this->clave_unica));

        $this->mostrarCarnet = true;
        $this->dispatch('carnetRendered');
    }
    #[On('ocultarCarnet')]
    public function ocultarCarnet()
    {
        //dd('dfnhsdfbsd');
        $this->mostrarCarnet = false;
    }
}
