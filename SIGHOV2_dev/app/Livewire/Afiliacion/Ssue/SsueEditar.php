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
use App\Models\Afiliacion\DocumentosEstudiante;
use App\Models\Administracion\NotificacionUsuarios;
use App\Models\Administracion\RolUsuario;
use App\Models\Administracion\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use App\Models\Vigencia\HistorialAsegurado;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class SsueEditar extends Component
{
    use WithFileUploads;
    #[Validate]
    public $foto;
    public $fotoAntigua;
    public $fotito;
    //datos personales
    #[Locked]
    public $id_persona;
    public $nombres, $p_apellido, $s_apellido, $ci, $complemento, $fecha_nacimiento;
    public $es_extranjero;
    public $estado_civil, $id_estado_civil;
    #[Locked]
    public $id_expedido_ci, $expedido_ci;
    #[Validate]
    public $id_dep_nacimiento, $dep_nacimiento;
    #[Validate]
    public $id_pro_nacimiento, $pro_nacimiento;
    #[Validate]
    public $id_mun_nacimiento, $mun_nacimiento;
    public $departamento;
    public $provincias = [];
    public $municipios = [];
    public $id_tipo_asegurado = 11;
    public $matricula;
    public $sexo;
    public $edad;
    public $sin_doc_sangre = false;

    //datos referenciales
    #[Locked]
    public $id_estudiante;
    public $estado_vigencia;
    public $estado_validar;
    #[Validate]
    public $telefono, $correo_institucional, $nombre_referencia, $telefono_referencia;
    public $alergias;
    public $observacion;
    public $grupo_sanguineo;
    public $fecha_solicitud;
    public $fecha_afiliacion, $fecha_reafiliacion;
    #[Validate]
    public $direccion, $zona, $zonaId, $zonasSelect;
    #[Validate]
    public $nro_vivienda, $id_residencia;
    public $observaciones;

    // datos universitarios 
    #[Validate]
    public $registro_universitario, $gestion_ingreso, $semestre_actual;
    public $facultad, $id_facultad;
    public $carrera, $idCarrera;
    #[Validate]
    public $id_carrera;

    public $carreras = [];
    protected $listeners = ['actualizarEstudiante'];
    #[Validate]
    public $file_tipo_sangre, $file_fotocopiaCarnet, $file_matricula, $file_boletaInscripcion, $file_certificadoCajas, $file_enteGestor;
    #[Validate]
    public $file_tipo_sangre2, $file_fotocopiaCarnet2, $file_matricula2, $file_boletaInscripcion2, $file_certificadoCajas2, $file_enteGestor2;

    // Documentos estudiantil
    public $estado_carnet;
    public $estado_matricula;
    public $estado_boleta;
    public $estado_certificado_cajas;
    public $estado_tipo_sangre;
    public $estado_certificado_gestora;

    //Switches para saber el estado original de los documentos que se tenia en la base de datos
    public $estado_carnetOriginal;
    public $estado_matriculaOriginal;
    public $estado_boletaOriginal;
    public $estado_certificado_cajasOriginal;
    public $estado_tipo_sangreOriginal;
    public $estado_certificado_gestoraOriginal;

    //Ubicacion
    public $latitud;
    public $longitud;
    public $ubicacion_select;
    public $extranjeroSwitch, $datosNacimiento = false, $tieneDocumentos, $valor, $today;


    //render
    public function render()
    {
        return view('livewire.Afiliacion.ssue.ssue-editar');
    }

    //mount
    public function mount()
    {

        //$this->esExtranjeroFunction();
        $this->mostrarEstudiante();
        $this->zonasSelect = Zona::orderBy('nombre', 'asc')->get();
        $this->departamento = Departamento::all();
        $this->carrera = Carrera::orderBy('nombre', 'asc')->get();
        $this->today = now()->format('d/m/Y');
    }
    public function rules()
    {
        return [
            'correo_institucional' => 'required|email',
            'nombre_referencia' => 'required|min:2',
            'telefono_referencia' => 'required|min:6|max:9',
            'foto' => $this->fotoAntigua ? 'nullable' : 'required|image|mimes:jpg,jpeg,png|max:2000',
            'telefono' => 'required|min:6|max:9',
            'nro_vivienda' => 'required|min:2',
            'id_carrera' => 'required|min:1',
            'direccion' => 'required|min:5',
            'semestre_actual' => 'required',
            'zonaId' => 'required',
            // 'file_tipo_sangre' => $this->estado_tipo_sangreOriginal === 3 ? 'required|mimes:pdf|max:51200' : 'nullable',
            'file_fotocopiaCarnet' => $this->estado_carnetOriginal === 3 ? 'required|mimes:pdf|max:2000' : 'nullable',
            'file_matricula' => $this->estado_matriculaOriginal === 3 ? 'required|mimes:pdf|max:2000' : 'nullable',
            'file_boletaInscripcion' => $this->estado_boletaOriginal === 3 ? 'required|mimes:pdf|max:2000' : 'nullable',
            'file_certificadoCajas' => $this->estado_certificado_cajasOriginal === 3 ? 'required|mimes:pdf|max:2000' : 'nullable',
            'file_enteGestor' => $this->estado_certificado_gestoraOriginal === 3 ? 'required|mimes:pdf|max:2000' : 'nullable',

            //'file_tipo_sangre2' => !$this->tieneDocumentos ? 'required|mimes:pdf|max:2000' : 'nullable',
            'file_fotocopiaCarnet2' => !$this->tieneDocumentos ? 'required|mimes:pdf|max:2000' : 'nullable',
            'file_matricula2' => !$this->tieneDocumentos ? 'required|mimes:pdf|max:2000' : 'nullable',
            'file_boletaInscripcion2' => !$this->tieneDocumentos ? 'required|mimes:pdf|max:2000' : 'nullable',
            'file_certificadoCajas2' => !$this->tieneDocumentos ? 'required|mimes:pdf|max:2000' : 'nullable',
            'file_enteGestor2' => !$this->tieneDocumentos ? 'required|mimes:pdf|max:2000' : 'nullable',

            'id_dep_nacimiento' => !$this->es_extranjero  ? 'required' : 'nullable',
            'id_pro_nacimiento' => !$this->es_extranjero  ? 'required' : 'nullable',
            'id_mun_nacimiento' => !$this->es_extranjero  ? 'required' : 'nullable',

            'registro_universitario' => 'required|min:4|max:10',
            'gestion_ingreso' => 'required|integer|min:1900|max:' . date('Y'),

        ];
    }
    public function obtenerFacultad()
    {
        $carrera = Carrera::find($this->id_carrera);
        if ($carrera) {
            $this->id_facultad = $carrera->id_facultad;
            $facultadActual = Facultad::find($this->id_facultad);
            $this->facultad = $facultadActual->nombre;
        }
    }
    public function mostrarEstudiante()
    {
        try {
            $userActual = User::join('administracion.persona as p', 'p.id', '=', 'administracion.users.id_persona')
                ->where('administracion.users.id', '=', auth()->user()->id)
                ->first();
            $this->id_persona = $userActual->id_persona;

            $personaActual = Persona::find($this->id_persona);
            $this->nombres = $personaActual->nombres;
            $this->p_apellido = $personaActual->p_apellido;
            $this->s_apellido = $personaActual->s_apellido;
            $this->ci = $personaActual->ci;
            $this->fecha_nacimiento = $personaActual->fecha_nacimiento;
            $this->fecha_nacimiento = Carbon::parse($personaActual->fecha_nacimiento)->format('d/m/Y');
            $this->grupo_sanguineo = $personaActual->grupo_sanguineo;
            $this->alergias = $personaActual->alergias ?: 'NINGUNO';
            // if ($personaActual->alergias == null) {
            //     $this->alergias = 'NINGUNO';
            // } else {
            //     $this->alergias = $personaActual->alergias;
            // }

            $this->edad = Carbon::parse($personaActual->fecha_nacimiento)->age;
            $this->complemento = $personaActual->complemento;
            $this->matricula = $personaActual->matricula_seguro;
            $this->sexo = $this->setSexoTexto($personaActual->sexo);
            $this->id_estado_civil = $personaActual->id_estado_civil;
            $estadoCivil = Estadocivil::find($this->id_estado_civil);
            $this->estado_civil = $estadoCivil->nombre;
            $this->es_extranjero = $personaActual->es_extranjero == false ? 0 : 1;

            //$this->extranjeroSwitch = $personaActual->es_extranjero ? true : false;
            $this->id_expedido_ci = $personaActual->id_dept_exp;
            $depExpedido = Departamento::find($this->id_expedido_ci);
            $this->expedido_ci = $depExpedido->sigla;
            $this->id_mun_nacimiento = $personaActual->id_nacimiento_municipio;
            if ($this->id_mun_nacimiento) {
                $this->datosNacimiento = true;
                $munNacimiento = Municipio::find($this->id_mun_nacimiento);
                $this->mun_nacimiento = $munNacimiento->nombre;
                $this->id_pro_nacimiento = $munNacimiento->id_provincia;
                $provNacimiento = Provincia::find($this->id_pro_nacimiento);
                $this->pro_nacimiento = $provNacimiento->nombre;
                $this->id_dep_nacimiento = $provNacimiento->id_departamento;
                $depNacimiento = Departamento::find($this->id_dep_nacimiento);
                $this->dep_nacimiento = $depNacimiento->nombre;
                $this->provincias = Provincia::all();
                $this->municipios = Municipio::all();
            }
            //  dd($this->mun_nacimiento, $this->id_pro_nacimiento, $this->id_dep_nacimiento);
            $estudiante = Estudiante::where('id_persona', $this->id_persona)->where('estado', true)->first();
            $this->id_estudiante = $estudiante->id;
            $this->telefono = $estudiante->telefono;
            $this->correo_institucional = $estudiante->correo_institucional;
            $this->nombre_referencia = $estudiante->nombre_referencia;
            $this->telefono_referencia = $estudiante->telefono_referencia;
            $this->estado_validar = $estudiante->estado_validar;
            $this->observaciones = $estudiante->observaciones;
            $this->observacion = $estudiante->observacion;
            $this->fecha_solicitud = $estudiante->fecha_solicitud_afiliacion ? Carbon::parse($estudiante->fecha_solicitud_afiliacion)->Format('d/m/ Y') : '';
            $this->fecha_afiliacion = $estudiante->fecha_afiliacion ? Carbon::parse($estudiante->fecha_afiliacion)->Format('d/m/ Y') : 'NO AFILIADO AÚN';
            $this->fecha_reafiliacion = $estudiante->fecha_reafiliacion ? Carbon::parse($estudiante->fecha_reafiliacion)->Format('d/m/ Y')  : '';
            $this->id_residencia = $estudiante->id_residencia;
            $residenciaActual = Residencia::find($this->id_residencia);
            $this->direccion = $residenciaActual->direccion;
            $this->nro_vivienda = $residenciaActual->nro_vivienda;
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
            if ($estudiante->id_carrera) {
                $this->id_carrera = $estudiante->id_carrera;
                $carreraActual = Carrera::find($this->id_carrera);
                $this->carrera = $carreraActual->nombre;
                $this->id_facultad = $carreraActual->id_facultad;
                $facultadActual = Facultad::find($this->id_facultad);
                $this->facultad = $facultadActual->nombre;
            }
            //dd($estudiante->foto_nombre);

            $this->fotoAntigua = $this->obtenerImagenBase64($estudiante->foto_nombre);

            $doc = DocumentosEstudiante::where('id_estudiante', $this->id_estudiante)->where('activo', true)->first();
            if ($doc) {
                $this->tieneDocumentos = true;
                $this->estado_carnet = $doc->estado_carnet;
                $this->estado_matricula = $doc->estado_matricula;
                $this->estado_boleta = $doc->estado_boleta;
                $this->estado_certificado_gestora = $doc->estado_certificado_gestora;
                $this->estado_certificado_cajas = $doc->estado_certificado_cajas;
                $this->estado_tipo_sangre = $doc->estado_tipo_sangre;
                //Switches para saber el estado original de los documentos 
                $this->estado_carnetOriginal = $doc->estado_carnet;
                $this->estado_matriculaOriginal = $doc->estado_matricula;
                $this->estado_boletaOriginal = $doc->estado_boleta;
                $this->estado_certificado_gestoraOriginal = $doc->estado_certificado_gestora;
                $this->estado_certificado_cajasOriginal = $doc->estado_certificado_cajas;
                $this->estado_tipo_sangreOriginal = $doc->estado_tipo_sangre;
            }
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Afiliacion:error al mostrar estudiante", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    public function esExtranjeroFunction()
    {
        $this->valor = $this->es_extranjero;
        //  $valor2 = $this->tipo_doc_1;

    }
    // funciona para mandar el texto del sexo
    private function setSexoTexto($sexo)
    {
        if ($sexo == 'F') {
            return 'FEMENINO';
        } elseif ($sexo == 'M') {
            return 'MASCULINO';
        } else {
            return 'NO ESPECIFICADO';
        }
    }

    public function ObtenerProvincia()
    {
        $this->provincias = Provincia::where('id_departamento', '=', $this->id_dep_nacimiento)->get();
    }

    public function ObtenerMunicipio()
    {
        $this->municipios = Municipio::where('id_provincia', '=', $this->id_pro_nacimiento)->get();
    }

    // funcion para el texto de la nacionalidad
    private function setExtranjeroTexto($es_extranjero)
    {
        if ($es_extranjero == false) {
            return 'NACIONAL';
        } elseif ($es_extranjero == true) {
            return 'EXTRANJERO';
        } else {
            return 'No especificado';
        }
    }

    public function recibirLonLat($d_lat, $d_lng)
    {
        $this->latitud = $d_lat;
        $this->longitud = $d_lng;
        $this->ubicacion_select = 1;
    }

    // para comprimir archivos con ghotsScript
    public function comprimirPDF($archivoTemporal, $nombreSalida)
    {
        // Guardar archivo original temporal
        $rutaOriginal = $archivoTemporal->storeAs('temp', uniqid() . '_original.pdf', 'local');
        $rutaCompletaOriginal = storage_path('app/' . $rutaOriginal);
        $rutaCompletaComprimido = storage_path('app/temp/' . $nombreSalida);
        // Verificar si el archivo original se guardó correctamente
        try {
            if (!file_exists($rutaCompletaOriginal)) {
                throw new \Exception("El archivo original no se guardó: $rutaCompletaOriginal");
            }
            $gsPath = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'
                ? '"C:\Program Files\gs\gs10.05.1\bin\gswin64c.exe"'
                : 'gs';

            $comando = "$gsPath -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 " .
                "-dPDFSETTINGS=/ebook -dNOPAUSE -dQUIET -dBATCH " .
                "-sOutputFile=\"$rutaCompletaComprimido\" \"$rutaCompletaOriginal\"";
            exec($comando, $output, $return_var);
            if ($return_var !== 0 || !file_exists($rutaCompletaComprimido)) {
                throw new \Exception("Ghostscript falló o no generó archivo válido.");
            }
            return $rutaCompletaComprimido;
        } finally {
            // Limpiar el archivo original aunque falle
            Storage::delete($rutaOriginal);
        }
    }

    // ACTUALIZAR AL ESTUDIANTE
    public function actualizarEstudiante()
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $estudiante = Estudiante::find($this->id_estudiante);
            $personaId = $estudiante->id_persona;
            $documentosEstudiante = DocumentosEstudiante::where('id_estudiante', $estudiante->id)->first();
            $personaActual = Persona::find($personaId);
            if ($estudiante) {
                $estudiante->telefono_referencia = $this->telefono_referencia;
                $estudiante->nombre_referencia = $this->nombre_referencia;
                $personaActual->update([
                    'id_tipo_asegurado' => 11,
                    'alergias' => $this->alergias,
                    'grupo_sanguineo' => $this->grupo_sanguineo,
                    'es_extranjero' => $this->es_extranjero,
                    'id_nacimiento_municipio' => $this->id_mun_nacimiento,
                ]);
                $estudiante->telefono = $this->telefono;
                $estudiante->correo_institucional = $this->correo_institucional;
                $estudiante->registro_universitario = $this->registro_universitario;
                $estudiante->gestion = $this->gestion_ingreso;
                $estudiante->semestre = $this->semestre_actual;
                $estudiante->id_carrera = $this->id_carrera;
                $estudiante->fecha_solicitud_afiliacion = now();
                $estudiante->estado_validar = 'PENDIENTE';
                $estudiante->save();
                $nombre_foto = $estudiante->id . '-E.jpg';
                if ($this->foto) {
                    $imagePath = $this->foto->getRealPath();
                    $imageData = file_get_contents($imagePath);
                    $imageBase64_asuss = base64_encode($imageData);
                    $imageBase64 = 'data:image/' . $this->foto->extension() . ';base64,' . base64_encode($imageData);
                    $this->foto->storeAs('Afiliacion/SSUE/FOTOS', $nombre_foto, 'ftp');
                    $estudiante->update([
                        'foto_nombre' => $nombre_foto,
                    ]);
                }
                $existeResidencia = Residencia::where('direccion', $this->direccion)
                    ->where('nro_vivienda', $this->nro_vivienda)
                    ->where('id_zona', $this->zonaId)
                    ->first();
                if ($existeResidencia != null) {
                    $residenciaId = $existeResidencia->id;
                } else {
                    $residencia = Residencia::create([
                        'direccion' => $this->direccion,
                        'nro_vivienda' => $this->nro_vivienda,
                        'id_zona' => $this->zonaId,
                        'id_user_created' => auth()->user()->id,
                    ]);
                    $residenciaId = $residencia->id;
                }
                if ($residenciaId != $estudiante->id_residencia) {
                    $estudiante->update([
                        'id_residencia' => $residenciaId,
                        'id_user_updated' => auth()->user()->id,
                    ]);
                }
                if ($documentosEstudiante) {
                    if ($this->estado_tipo_sangre == 2 && $this->estado_tipo_sangreOriginal == 3) {
                        $nombre_file_sangre = "Tipo_Sangre-" . $estudiante->id . '.pdf';
                        // Guardar temporal en local
                        $archivoTemporal = $this->file_tipo_sangre;
                        $rutaComprimida = $this->comprimirPDF($archivoTemporal, $nombre_file_sangre);
                        // Subir al FTP
                        if (!file_exists($rutaComprimida)) {
                            throw new \Exception("Archivo comprimido aún no está listo o es inválido.");
                        }
                        Storage::disk('ftp')->put('Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_sangre, file_get_contents($rutaComprimida));
                        // Actualizar datos
                        $documentosEstudiante->file_tipo_sangre = $nombre_file_sangre;
                        $documentosEstudiante->estado_tipo_sangre = 2;
                        $documentosEstudiante->id_user_updated = auth()->user()->id;
                        $documentosEstudiante->save();
                        // Eliminar archivo temporal comprimido
                        unlink($rutaComprimida);
                    }
                    if ($this->estado_tipo_sangre == 4) {
                        $personaActual->update([
                            'grupo_sanguineo' => 'NINGUNO',
                        ]);
                    }

                    if ($this->estado_carnet == 2 && $this->estado_carnetOriginal == 3) {
                        $nombre_file_carnet = "Carnet-" . $estudiante->id . '.pdf';
                        $archivoTemporal = $this->file_fotocopiaCarnet;
                        $rutaComprimida = $this->comprimirPDF($archivoTemporal, $nombre_file_carnet);
                        if (!file_exists($rutaComprimida)) {
                            throw new \Exception("Archivo comprimido aún no está listo o es inválido.");
                        }
                        Storage::disk('ftp')->put('Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_carnet, file_get_contents($rutaComprimida));
                        $documentosEstudiante->file_carnet = $nombre_file_carnet;
                        $documentosEstudiante->estado_carnet = 2;
                        $documentosEstudiante->id_user_updated = auth()->user()->id;
                        $documentosEstudiante->save();
                        unlink($rutaComprimida);
                    }

                    if ($this->estado_matricula == 2 && $this->estado_matriculaOriginal == 3) {
                        $nombre_file_matricula = "Matricula-" . $estudiante->id . '.pdf';
                        $archivoTemporal = $this->file_matricula;
                        $rutaComprimida = $this->comprimirPDF($archivoTemporal, $nombre_file_matricula);
                        if (!file_exists($rutaComprimida)) {
                            throw new \Exception("Archivo comprimido aún no está listo o es inválido.");
                        }
                        Storage::disk('ftp')->put('Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_matricula, file_get_contents($rutaComprimida));
                        $documentosEstudiante->file_matricula = $nombre_file_matricula;
                        $documentosEstudiante->estado_matricula = 2;
                        $documentosEstudiante->id_user_updated = auth()->user()->id;
                        $documentosEstudiante->save();
                        unlink($rutaComprimida);
                    }

                    if ($this->estado_boleta == 2 && $this->estado_boletaOriginal == 3) {
                        $nombre_file_boleta = "Boleta_Inscripcion-" . $estudiante->id . '.pdf';
                        $archivoTemporal = $this->file_boletaInscripcion;
                        $rutaComprimida = $this->comprimirPDF($archivoTemporal, $nombre_file_boleta);
                        if (!file_exists($rutaComprimida)) {
                            throw new \Exception("Archivo comprimido aún no está listo o es inválido.");
                        }
                        Storage::disk('ftp')->put('Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_boleta, file_get_contents($rutaComprimida));
                        $documentosEstudiante->file_boleta = $nombre_file_boleta;
                        $documentosEstudiante->estado_boleta = 2;
                        $documentosEstudiante->id_user_updated = auth()->user()->id;
                        $documentosEstudiante->save();
                        unlink($rutaComprimida);
                    }

                    if ($this->estado_certificado_cajas == 2 && $this->estado_certificado_cajasOriginal == 3) {
                        $nombre_file_cajas = "Certificado_Cajas-" . $estudiante->id . '.pdf';
                        $archivoTemporal = $this->file_certificadoCajas;
                        $rutaComprimida = $this->comprimirPDF($archivoTemporal, $nombre_file_cajas);
                        if (!file_exists($rutaComprimida)) {
                            throw new \Exception("Archivo comprimido aún no está listo o es inválido.");
                        }
                        Storage::disk('ftp')->put('Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_cajas, file_get_contents($rutaComprimida));
                        $documentosEstudiante->file_certificado_cajas = $nombre_file_cajas;
                        $documentosEstudiante->estado_certificado_cajas = 2;
                        $documentosEstudiante->id_user_updated = auth()->user()->id;
                        $documentosEstudiante->save();
                        unlink($rutaComprimida);
                    }

                    if ($this->estado_certificado_gestora == 2 && $this->estado_certificado_gestoraOriginal == 3) {
                        $nombre_file_gestora = "Certificado_Gestora-" . $estudiante->id . '.pdf';
                        $archivoTemporal = $this->file_enteGestor;
                        $rutaComprimida = $this->comprimirPDF($archivoTemporal, $nombre_file_gestora);
                        if (!file_exists($rutaComprimida)) {
                            throw new \Exception("Archivo comprimido aún no está listo o es inválido.");
                        }
                        Storage::disk('ftp')->put('Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_gestora, file_get_contents($rutaComprimida));
                        $documentosEstudiante->file_certificado_gestora = $nombre_file_gestora;
                        $documentosEstudiante->estado_certificado_gestora = 2;
                        $documentosEstudiante->id_user_updated = auth()->user()->id;
                        $documentosEstudiante->save();
                    }
                } else {
                    $nombre_file_sangre = "Tipo_Sangre-" . $estudiante->id . '.pdf';
                    if ($this->file_tipo_sangre2) {
                        $rutaComprimida = $this->comprimirPDF($this->file_tipo_sangre2, $nombre_file_sangre);
                        Storage::disk('ftp')->put('Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_sangre, file_get_contents($rutaComprimida));
                        unlink($rutaComprimida);
                    } else {
                        $this->sin_doc_sangre = true;
                        $personaActual->update([
                            'grupo_sanguineo' => 'NINGUNO',
                        ]);
                        $file_sangre_por_defecto = public_path('docs/afiliacion/SIN_TIPO_SANGRE.pdf');
                        Storage::disk('ftp')->put(
                            'Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_sangre,
                            file_get_contents($file_sangre_por_defecto)
                        );
                    }

                    $nombre_file_carnet = "Carnet-" . $estudiante->id . '.pdf';
                    $rutaComprimida = $this->comprimirPDF($this->file_fotocopiaCarnet2, $nombre_file_carnet);
                    Storage::disk('ftp')->put('Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_carnet, file_get_contents($rutaComprimida));
                    unlink($rutaComprimida);

                    $nombre_file_matricula = "Matricula-" . $estudiante->id . '.pdf';
                    $rutaComprimida = $this->comprimirPDF($this->file_matricula2, $nombre_file_matricula);
                    Storage::disk('ftp')->put('Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_matricula, file_get_contents($rutaComprimida));
                    unlink($rutaComprimida);

                    $nombre_file_boleta = "Boleta_Inscripcion-" . $estudiante->id . '.pdf';
                    $rutaComprimida = $this->comprimirPDF($this->file_boletaInscripcion2, $nombre_file_boleta);
                    Storage::disk('ftp')->put('Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_boleta, file_get_contents($rutaComprimida));
                    unlink($rutaComprimida);

                    $nombre_file_cajas = "Certificado_Cajas-" . $estudiante->id . '.pdf';
                    $rutaComprimida = $this->comprimirPDF($this->file_certificadoCajas2, $nombre_file_cajas);
                    Storage::disk('ftp')->put('Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_cajas, file_get_contents($rutaComprimida));
                    unlink($rutaComprimida);

                    $nombre_file_gestora = "Certificado_Gestora-" . $estudiante->id . '.pdf';
                    $rutaComprimida = $this->comprimirPDF($this->file_enteGestor2, $nombre_file_gestora);
                    Storage::disk('ftp')->put('Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_gestora, file_get_contents($rutaComprimida));
                    unlink($rutaComprimida);

                    $documentos = DocumentosEstudiante::create([
                        'id_estudiante' => $estudiante->id,
                        'file_carnet' => $nombre_file_carnet,
                        'estado_carnet' => 2, // 1 afiliado, 2 actualizado, 3 observado
                        'file_matricula' => $nombre_file_matricula,
                        'estado_matricula' => 2,
                        'file_boleta' => $nombre_file_boleta,
                        'estado_boleta' => 2,
                        'file_certificado_gestora' => $nombre_file_gestora,
                        'estado_certificado_gestora' => 2,
                        'file_certificado_cajas' => $nombre_file_cajas,
                        'estado_certificado_cajas' => 2,
                        'file_tipo_sangre' => $nombre_file_sangre,
                        'estado_tipo_sangre' => 2,
                        'activo' => true,
                        'id_user_created' => auth()->user()->id,
                    ]);
                    if ($this->sin_doc_sangre == true) {
                        $documentos->update([
                            'estado_tipo_sangre' => 4,
                        ]);
                    }
                }

                // NOTIFICACIONES
                $id_persona = $estudiante->id_persona;
                $personaActual = Persona::find($id_persona);
                $rol = Role::where('name', 'Auxiliar Afiliacion SSUE')->first();
                $receptores = RolUsuario::where('role_id', $rol->id)->get();
                foreach ($receptores as $receptor) {
                    $nuevaNotificacion = NotificacionUsuarios::create([
                        'estado' => 'NO LEIDO',
                        'prioridades' => 1,
                        'id_usu_emisor' => auth()->user()->id,
                        'id_usu_receptor' =>  $receptor->model_id,
                        'fecha_hora_enviada' => Carbon::now(),
                        'asunto' => 'AFILIACION SSUE - PREREGISTRO',
                        'mensaje' => 'El estudiante: ' . $personaActual->p_apellido . ' ' . $personaActual->s_apellido . ' ' . $personaActual->nombres . ' modifico sus campos respectivos,
                  debe verificar los datos modificados, en caso que haya un error haga la observacion correspondiente. Att. El administrador',
                    ]);
                }

                DB::commit();
                $this->dispatch('estudianteRegistrado');
                $this->dispatch('pg:eventRefresh-EstudianteTable');
                return redirect()->route('dashboard');
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->dispatch('estudianteCancelado');

            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Afiliacion:error al actualizar estudiante", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    public function cancelarEstudiante()
    {
        $this->dispatch('estudianteCancelado');
        return redirect()->route('dashboard');
    }

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

    //Funciones para cambiar estados de los documentos
    public function estadoCarnet()
    {
        $this->estado_carnet = 2;
    }

    public function estadoMatricula()
    {
        $this->estado_matricula = 2;
    }

    public function estadoBoleta()
    {
        $this->estado_boleta = 2;
    }

    public function estadoCertificadoGestora()
    {
        $this->estado_certificado_gestora = 2;
    }

    public function estadoCertificadoCajas()
    {
        $this->estado_certificado_cajas = 2;
    }

    public function estadoTipoSangre()
    {
        $this->estado_tipo_sangre = 2;
    }
}
