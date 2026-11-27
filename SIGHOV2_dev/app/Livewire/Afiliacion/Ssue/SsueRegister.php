<?php

namespace App\Livewire\Afiliacion\Ssue;

use App\Livewire\Afiliacion\EstudiantesSsue\HistorialMatriculas;
use App\Models\Afiliacion\Estudiante;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\Beneficiario;
use App\Models\User;
use App\Models\Administracion\Persona;
use App\Models\Administracion\Residencia;
use App\Models\Administracion\Departamento;
use App\Models\Administracion\Provincia;
use App\Models\Administracion\Municipio;
use App\Models\Administracion\Zona;
use App\Models\Administracion\NotificacionUsuarios;
use App\Models\Administracion\RolUsuario;
use App\Models\Administracion\Role;

use App\Models\Administracion\EstadoCivil;
use App\Models\Afiliacion\Facultad;
use App\Models\Afiliacion\Carrera;
use App\Models\Afiliacion\DocumentosEstudiante;
use App\Models\Vigencia\HistorialAsegurado;

use Carbon\Carbon;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Livewire\Component;
use App\Mail\Afiliacion\AfiliacionEstudiantesMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Afiliacion\HistorialMatriculaEstudiante;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class SsueRegister extends Component
{
    use WithFileUploads;
    #[Validate]
    public $foto;
    //datos personales
    #[Locked]
    public $id_persona;
    public $nombres, $p_apellido, $s_apellido, $ci, $complemento, $fecha_nacimiento, $fecha_nacimiento_literal, $edad;
    public $sin_doc_sangre = false;
    #[Validate]
    public $es_extranjero, $extranjeroTexto;
    #[Validate]
    public $estado_civil, $id_estado_civil, $estadosCiviles;
    #[Validate]  #[Locked]
    public $id_expedido_ci;
    #[Validate]
    public  $expedido_ci, $id_dep_nacimiento, $dep_nacimiento;
    #[Validate]
    public $id_pro_nacimiento, $pro_nacimiento;
    #[Validate]
    public $id_mun_nacimiento, $mun_nacimiento;
    #[Validate]
    public $departamento;
    public $provincias = [];
    public $municipios = [];
    public $id_tipo_asegurado = 11;
    #[Validate]
    public $matricula;
    #[Validate]
    public $sexo = false;

    //datos referenciales
    #[Locked]
    public $id_estudiante;
    public $estado_vigencia = true;
    #[Validate]
    public $telefono, $correo_institucional, $nombre_referencia, $telefono_referencia;
    public $alergias;
    public $observacion;
    #[Validate]
    public $grupo_sanguineo;
    #[Validate]
    public $fecha_solicitud, $fecha_solicitud_literal;
    public $fecha_afiliacion, $fecha_reafiliacion;
    public $afiliacion, $afiliacion_txt;
    #[Validate]
    public $direccion;
    #[Validate]
    public $zona;
    public $zonaId, $zonasSelect;
    #[Validate]
    public $nro_vivienda, $id_residencia;

    // datos universitarios 
    #[Validate]
    public $registro_universitario;
    //#[Validate]
    public $gestion_ingreso, $semestre_actual;
    #[Validate]
    public $facultad, $id_facultad;
    #[Validate]
    public $carrera, $id_carrera, $idCarrera, $file_tipo_sangre, $file_fotocopiaCarnet, $file_matricula, $file_boletaInscripcion, $file_certificadoCajas, $file_enteGestor;

    public $dep_nacimiento_nombre, $pro_nacimiento_nombre, $mun_nacimiento_nombre, $valor;

    //SWITHC PARA LA VALIDACION DE DATOS VACIOS O NULOS
    public $fecha_nacimientoSwitch;
    public $es_extranjeroSwitch, $tiene_grupo_sanguineo;
    public $expedido_ciSwitch;
    public $matriculaSwitch;
    public $id_estado_civilSwitch;
    public $switch_ru, $swith_gestion;
    public $datosLugarNacimiento = false;

    // para la ubicacion
    public $latitud;
    public $longitud;
    public $ubicacion_select;

    public $carreras = [];

    public $fechaNacimientoMaximoConvenio;

    protected $listeners = ['registrarEstudiante'];

    public function rules()
    {
        return [
            'direccion' => 'required|min:5',
            'zona' => 'required',
            'nro_vivienda' => 'required|min:2',
            'telefono' => 'required|min:6|max:9',
            'correo_institucional' => 'required|email',
            'nombre_referencia' => 'required|min:2',
            'telefono_referencia' => 'required|min:6|max:9',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'es_extranjero' => 'required',
            'id_estado_civil' => 'required',
            'matricula' => 'required',
            //'expedido_ci' => 'required',
            //  'mun_nacimiento' => 'required',
            // 'pro_nacimiento' => 'required',
            // 'dep_nacimiento' => 'required',
            // 'grupo_sanguineo' => 'required',
            'registro_universitario' => 'required|min:4|max:10',
            'gestion_ingreso' => 'required|integer|min:1900|max:' . date('Y'),
            'semestre_actual' => 'required',
            'id_carrera' => 'required',
            //'facultad' => 'required',
            // 'file_tipo_sangre' => 'required|mimes:pdf|max:51200',
            'file_fotocopiaCarnet' => 'required|mimes:pdf|max:51200',
            'file_matricula' => 'required|mimes:pdf|max:51200',
            'file_boletaInscripcion' => 'required|mimes:pdf|max:51200',
            'file_certificadoCajas' => 'required|mimes:pdf|max:51200',
            'file_enteGestor' => 'required|mimes:pdf|max:51200',
            //'file_digital'=> 'required',


        ];
    }

    public function render()
    {
        return view('livewire.Afiliacion.ssue.ssue-register');
    }

    public function mount()
    {
        //$this->esExtranjeroFunction();
        $this->mostrarEstudiante();
        if ($this->fecha_nacimiento != null) {
            //dd($this->fecha_nacimiento);
            if ($this->sexo != null) {
                if ($this->sexo = 'F') {
                    $this->sexo = true;
                } elseif ($this->sexo = 'M') {
                    $this->sexo = false;
                }
            }
            $this->edad = Carbon::parse($this->fecha_nacimiento)->age;
            $this->GenerarMatricula();
        }
        $this->fecha_solicitud = Carbon::now()->format('Y-m-d');
        $this->fecha_solicitud_literal = Carbon::parse($this->fecha_solicitud)->format('d/m/Y');
        $this->zonasSelect = Zona::orderBy('nombre', 'asc')->get();
        $this->departamento = Departamento::get();
        $this->estadosCiviles = EstadoCivil::all();
        $this->carrera = Carrera::orderBy('nombre', 'asc')->get();
        $this->fechaNacimientoMaximoConvenio = Carbon::now()->subYears(38)->format('Y-m-d');
    }
    public function esExtranjeroFunction()
    {
        $this->valor = $this->es_extranjero;
        //  $valor2 = $this->tipo_doc_1;

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

    public function ObtenerProvincia()
    {
        $this->provincias = Provincia::where('id_departamento', '=', $this->dep_nacimiento)->get();
    }

    public function ObtenerMunicipio()
    {
        $this->municipios = Municipio::where('id_provincia', '=', $this->pro_nacimiento)->get();
    }

    // obtener matricula 
    public function GenerarMatricula()
    {
        $nombre = $this->nombres;
        $p_apellido = $this->p_apellido;
        $s_apellido = $this->s_apellido;
        $fecha_nacimiento = Carbon::parse($this->fecha_nacimiento);
        $sexo = $this->sexo ? $this->sexo : false;
        $fechaNacimiento = $fecha_nacimiento;

        $anioNacimiento = $fechaNacimiento->format('Y');
        $mesNacimiento = $fechaNacimiento->format('m');
        $diaNacimiento = $fechaNacimiento->format('d');
        $sexoEsMasculino = $sexo === false || $sexo === 'M';
        $sexoEsFemenino = $sexo === true || $sexo === 'F';

        if (strlen($p_apellido) == 0) {
            $apellido1 = substr($s_apellido, 0, 1);
            $apellido2 = substr($s_apellido, 1, 1);
        }
        if (strlen($s_apellido) == 0) {
            $apellido1 = substr($p_apellido, 0, 1);
            $apellido2 = substr($p_apellido, 1, 1);
        }
        if (strlen($p_apellido) != 0 && strlen($s_apellido) != 0) {
            $apellido1 = substr($p_apellido, 0, 1);
            $apellido2 = substr($s_apellido, 0, 1);
        }
        $nombre1 = substr($nombre, 0, 1);
        $matricula = '';
        $matricula .= substr($anioNacimiento, -2);
        if ($sexoEsMasculino) {
            $matricula .= str_pad($mesNacimiento, 2, '0', STR_PAD_LEFT);
        } elseif ($sexoEsFemenino) {
            $matricula .= str_pad(50 + $mesNacimiento, 2, '0', STR_PAD_LEFT);
        }
        $matricula .= $diaNacimiento;
        $matricula .= $apellido1;
        $matricula .= $apellido2;
        $matricula .= $nombre1;
        $this->matricula = $matricula;
    }

    // para comprimir archivos con ghotsScript
    public function comprimirPDF($archivoTemporal, $nombreSalida)
    {
        // Guardar archivo original temporal
        $rutaOriginal = $archivoTemporal->storeAs('temp', uniqid() . '_original.pdf', 'local');
        $rutaCompletaOriginal = storage_path('app/' . $rutaOriginal);
        $rutaCompletaComprimido = storage_path('app/temp/' . $nombreSalida);
        // Verificar si el archivo original se guardó correctamente
        if (!file_exists($rutaCompletaOriginal)) {
            throw new \Exception("El archivo original no se guardó: $rutaCompletaOriginal");
        }
        // Detectar sistema operativo
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Ruta Ghostscript en Windows
            $gsPath = '"C:\Program Files\gs\gs10.05.1\bin\gswin64c.exe"';
        } else {
            // Comando estándar en Linux (se asume que Ghostscript está en el PATH)
            $gsPath = 'gs';
        }
        // Comando Ghostscript con compresión moderada
        $comando = "$gsPath -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 " .
            "-dPDFSETTINGS=/ebook -dNOPAUSE -dQUIET -dBATCH " .
            "-sOutputFile=\"$rutaCompletaComprimido\" \"$rutaCompletaOriginal\"";
        exec($comando, $output, $return_var);
        // Validar ejecución
        if ($return_var !== 0) {
            throw new \Exception("Ghostscript falló. Código: $return_var\nSalida:\n" . implode("\n", $output));
        }
        // Validar compresión
        if (!file_exists($rutaCompletaComprimido)) {
            throw new \Exception("No se pudo comprimir el archivo: $nombreSalida");
        }
        // Eliminar original
        Storage::delete($rutaOriginal);
        // Retornar ruta del archivo comprimido
        return $rutaCompletaComprimido;
    }


    //Aqui registra estudiante
    public function registrarEstudiante()
    {
        //dd('entra aqui?');
        $this->validate();
        DB::beginTransaction();
        try {

            $userActual = User::join('administracion.persona as p', 'p.id', '=', 'administracion.users.id_persona')
                ->where('administracion.users.id', '=', auth()->user()->id)
                ->first();
            $this->id_persona = $userActual->id_persona;

            $personaActual = Persona::find($this->id_persona);

            // dd($personaActual);
            if ($personaActual) {
                $es_titular = Titular::where('id_persona', $personaActual->id)
                    ->where(function ($query) {
                        $query->where('estado_vigencia', 'VIGENTE')
                            ->orWhere('estado_vigencia', 'CESANTE');
                    })
                    ->where('estado', true)
                    ->orderBy('id', 'DESC')
                    ->first();
                $es_estudiante = Estudiante::where('id_persona', $personaActual->id)
                    ->where('estado_vigencia', true)
                    ->where('estado', true)
                    ->orderBy('id', 'DESC')
                    ->first();
                // dd('dsdsa', $es_estudiante);
                $es_beneficiario = Beneficiario::where('id_persona', $personaActual->id)
                    ->where(function ($query) {
                        $query->where('vigencia', 'VIGENTE')
                            ->orWhere('vigencia', 'CESANTE');
                    })
                    ->where('estado', true)
                    ->orderBy('id', 'DESC')
                    ->first();
                if ($es_titular) {
                    $this->dispatch('esTitular');
                } elseif ($es_estudiante) {
                    $this->dispatch('esEstudiante');
                } elseif ($es_beneficiario) {
                    $this->dispatch('esBeneficiario');
                } else {
                    $personaActual->update([
                        'sexo' => $this->sexo == true ? 'F' : 'M',
                        //'afiliado' => true,
                        'matricula_seguro' => $this->matricula,
                        'id_tipo_asegurado' => $this->id_tipo_asegurado,
                        'es_extranjero' => $this->es_extranjero,
                        'id_estado_civil' => $this->id_estado_civil,
                        'id_dept_exp' => $this->expedido_ci,
                        'id_nacimiento_municipio' => $this->mun_nacimiento,
                        'id_user_updated' => auth()->user()->id,
                        'grupo_sanguineo' => $this->grupo_sanguineo,
                        'alergias' => $this->alergias,
                    ]);
                    // dd($personaActual);
                    if ($this->fecha_nacimientoSwitch == false) {
                        $personaActual->update([
                            'fecha_nacimiento' => $this->fecha_nacimiento,
                            'id_user_updated' => auth()->user()->id,
                        ]);
                    }

                    $existeResidencia = Residencia::where('direccion', $this->direccion)->where('nro_vivienda', $this->nro_vivienda)
                        ->where('id_zona', $this->zona)
                        ->first();
                    // dd($existeResidencia);
                    if ($existeResidencia) {
                        $residenciaId = $existeResidencia->id;
                    } else {
                        $nuevaResidencia = Residencia::create([
                            'direccion' => $this->direccion,
                            'nro_vivienda' => $this->nro_vivienda,
                            'id_zona' => $this->zona,
                            'latitud' => $this->latitud,
                            'longitud' => $this->longitud,
                            'id_user_created' => auth()->user()->id,
                        ]);
                        $residenciaId = $nuevaResidencia->id;
                    }
                    //dd($residenciaId);

                    $existeEstudiante = Estudiante::where('id_persona', $this->id_persona)
                        ->orderBy('id', 'desc')
                        ->first();
                    //dd($existeEstudiante);
                    if ($existeEstudiante) {
                        $existeEstudiante->estado = false;
                        $existeEstudiante->estado_vigencia = false;
                        //  $existeEstudiante->id_user_updated = auth()->user()->id;
                        $existeEstudiante->save();
                        $docExiste = DocumentosEstudiante::where('id_estudiante', $existeEstudiante->id)
                            ->orderBy('id', 'desc')
                            ->first();
                        if ($docExiste) {
                            $docExiste->activo = false;
                            $docExiste->id_user_updated = auth()->user()->id;
                            $docExiste->save();
                        }
                    }
                    $datosEstudiante = [
                        'registro_universitario' => $this->registro_universitario,
                        'gestion' => $this->gestion_ingreso,
                        'semestre' => $this->semestre_actual,
                        'id_carrera' => $this->id_carrera,
                        'telefono_referencia' => $this->telefono_referencia,
                        'nombre_referencia' => $this->nombre_referencia,
                        'observacion' => $this->observacion,
                        'fecha_solicitud_afiliacion' => $this->fecha_solicitud,
                        'id_persona' => $this->id_persona,
                        'telefono' => $this->telefono,
                        'correo_institucional' => $this->correo_institucional,
                        'estado' => true,
                        'estado_validar' => 'PENDIENTE',
                        'estado_vigencia' => false,
                        'reafiliacion' => false,
                        'id_residencia' => $residenciaId,
                        // 'afiliacion' => true,
                        'id_user_created' => auth()->user()->id,
                    ];
                    //  dd($existeEstudiante);
                    if ($existeEstudiante) {
                        $datosEstudiante['fecha_afiliacion'] = $existeEstudiante->fecha_afiliacion;
                        $datosEstudiante['afiliacion'] = false;
                    } else {
                        $datosEstudiante['afiliacion'] = true;
                    }
                    //dd($datosEstudiante, $existeEstudiante);
                }
            }
            $estudianteNuevo = Estudiante::create($datosEstudiante);
            //dd($estudianteNuevo);
            $this->crearDocsEstudianteNuevo($estudianteNuevo, $residenciaId, $personaActual);
            DB::commit();
            $this->dispatch('estudianteRegistrado');
            return redirect()->route('afiliacion.ssue.seguimiento');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('estudianteCancelado');
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));
            Log::error("Afiliacion:error al crear estudiante", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    private function crearRegistroEstudiante($residenciaId, $personaActual)
    {
        try {

            //dd('entre');
            $estudiante = Estudiante::create([
                'registro_universitario' => $this->registro_universitario,
                'gestion' => $this->gestion_ingreso,
                'semestre' => $this->semestre_actual,
                'id_carrera' => $this->id_carrera,

                'telefono_referencia' => $this->telefono_referencia,
                'nombre_referencia' => $this->nombre_referencia,
                'observacion' => $this->observacion,
                'fecha_solicitud_afiliacion' => $this->fecha_solicitud,
                'id_persona' => $this->id_persona,
                'telefono' => $this->telefono,
                'correo_institucional' => $this->correo_institucional,
                'estado' => true,
                'estado_validar' => 'PENDIENTE',
                'estado_vigencia' => false,
                'id_residencia' => $residenciaId,
                'afiliacion' => true,
                'id_user_created' => auth()->user()->id,
            ]);
            $estudianteId = $estudiante->id;

            $nombre_foto = $estudianteId . '-E.jpg';
            $imagePath = $this->foto->getRealPath();
            $imageData = file_get_contents($imagePath);
            $imageBase64_asuss = base64_encode($imageData);
            $imageBase64 = 'data:image/' . $this->foto->extension() . ';base64,' . base64_encode($imageData);
            $this->foto->storeAs('Afiliacion/SSUE/FOTOS', $nombre_foto, 'ftp');
            //ftp files
            $nombre_file_sangre = "Tipo_Sangre-" . $estudiante->id . '.pdf';
            if ($this->tiene_grupo_sanguineo == false) {
                if ($this->file_tipo_sangre) {
                    $rutaComprimida = $this->comprimirPDF($this->file_tipo_sangre, $nombre_file_sangre);
                    Storage::disk('ftp')->put('Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_sangre, file_get_contents($rutaComprimida));
                    unlink($rutaComprimida);
                } else {
                    $this->sin_doc_sangre = true;
                    $personaActual->update([
                        'grupo_sanguineo' => null,
                    ]);
                    $file_sangre_por_defecto = public_path('docs/afiliacion/SIN_TIPO_SANGRE.pdf');
                    Storage::disk('ftp')->put(
                        'Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_sangre,
                        file_get_contents($file_sangre_por_defecto)
                    );
                }
            }
            // para documentos
            $nombre_file_carnet = "Carnet-" . $estudiante->id . '.pdf';
            $rutaComprimida = $this->comprimirPDF($this->file_fotocopiaCarnet, $nombre_file_carnet);
            Storage::disk('ftp')->put('Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_carnet, file_get_contents($rutaComprimida));
            unlink($rutaComprimida);

            $nombre_file_matricula = "Matricula-" . $estudiante->id . '.pdf';
            $rutaComprimida = $this->comprimirPDF($this->file_matricula, $nombre_file_matricula);
            Storage::disk('ftp')->put('Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_matricula, file_get_contents($rutaComprimida));
            unlink($rutaComprimida);

            $nombre_file_boleta = "Boleta_Inscripcion-" . $estudiante->id . '.pdf';
            $rutaComprimida = $this->comprimirPDF($this->file_boletaInscripcion, $nombre_file_boleta);
            Storage::disk('ftp')->put('Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_boleta, file_get_contents($rutaComprimida));
            unlink($rutaComprimida);

            $nombre_file_cajas = "Certificado_Cajas-" . $estudiante->id . '.pdf';
            $rutaComprimida = $this->comprimirPDF($this->file_certificadoCajas, $nombre_file_cajas);
            Storage::disk('ftp')->put('Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_cajas, file_get_contents($rutaComprimida));
            unlink($rutaComprimida);

            $nombre_file_gestora = "Certificado_Gestora-" . $estudiante->id . '.pdf';
            $rutaComprimida = $this->comprimirPDF($this->file_enteGestor, $nombre_file_gestora);
            Storage::disk('ftp')->put('Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_gestora, file_get_contents($rutaComprimida));
            unlink($rutaComprimida);

            $documentoNuevo = DocumentosEstudiante::create([
                'id_estudiante' => $estudianteId,
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
                $documentoNuevo->update([
                    'estado_tipo_sangre' => 4,
                ]);
            }
            $estudiante->update([
                'foto_nombre' => $nombre_foto,
            ]);
            //MAIL
            // $datos_estudiante = Estudiante::join('administracion.persona as per', 'per.id', '=', 'afiliacion.estudiante.id_persona')
            //     ->where('afiliacion.estudiante.id', '=', $estudianteId)->first();
            // $asunto = "Sistema Sighov2 - Unidad de Afiliación";
            // $email = $this->correo_institucional;
            // $correo = new AfiliacionEstudiantesMail($asunto, $datos_estudiante, $imageBase64);
            // try {
            //     Mail::to($email)->send($correo);
            // } catch (\Exception $e) {
            //     // Log del error (opcional)
            //     ::error('No se pudo enviar el correo: ' . $e->getMessage());
            // }

            // NOTIFICACIONES
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
                    'mensaje' => 'El estudiante: ' . $personaActual->p_apellido . ' ' . $personaActual->s_apellido . ' ' . $personaActual->nombres . ' hizo su preregistro,
                        debe verificar los datos validarlos, en caso que haya un error haga la observacion correspondiente.',
                ]);
            }
            $this->crearHistorialAsegurado($personaActual, $estudiante);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('estudianteCancelado');
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));
            Log::error("Afiliacion:error en funcion crear estudiante", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    private function crearDocsEstudianteNuevo($estudiante, $residenciaId, $personaActual)
    {
        try {
            $estudianteId = $estudiante->id;

            $nombre_foto = $estudianteId . '-E.jpg';
            $imagePath = $this->foto->getRealPath();
            $imageData = file_get_contents($imagePath);
            $imageBase64_asuss = base64_encode($imageData);
            $imageBase64 = 'data:image/' . $this->foto->extension() . ';base64,' . base64_encode($imageData);
            $this->foto->storeAs('Afiliacion/SSUE/FOTOS', $nombre_foto, 'ftp');
            //ftp files
            $nombre_file_sangre = "Tipo_Sangre-" . $estudiante->id . '.pdf';
            if ($this->tiene_grupo_sanguineo == false) {
                if ($this->file_tipo_sangre) {
                    $rutaComprimida = $this->comprimirPDF($this->file_tipo_sangre, $nombre_file_sangre);
                    Storage::disk('ftp')->put('Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_sangre, file_get_contents($rutaComprimida));
                    unlink($rutaComprimida);
                } else {
                    $this->sin_doc_sangre = true;
                    $personaActual->update([
                        'grupo_sanguineo' => null,
                    ]);
                    $file_sangre_por_defecto = public_path('docs/afiliacion/SIN_TIPO_SANGRE.pdf');
                    Storage::disk('ftp')->put(
                        'Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_sangre,
                        file_get_contents($file_sangre_por_defecto)
                    );
                }
            }
            // para documentos
            $nombre_file_carnet = "Carnet-" . $estudiante->id . '.pdf';
            $rutaComprimida = $this->comprimirPDF($this->file_fotocopiaCarnet, $nombre_file_carnet);
            Storage::disk('ftp')->put('Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_carnet, file_get_contents($rutaComprimida));
            unlink($rutaComprimida);

            $nombre_file_matricula = "Matricula-" . $estudiante->id . '.pdf';
            $rutaComprimida = $this->comprimirPDF($this->file_matricula, $nombre_file_matricula);
            Storage::disk('ftp')->put('Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_matricula, file_get_contents($rutaComprimida));
            unlink($rutaComprimida);

            $nombre_file_boleta = "Boleta_Inscripcion-" . $estudiante->id . '.pdf';
            $rutaComprimida = $this->comprimirPDF($this->file_boletaInscripcion, $nombre_file_boleta);
            Storage::disk('ftp')->put('Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_boleta, file_get_contents($rutaComprimida));
            unlink($rutaComprimida);

            $nombre_file_cajas = "Certificado_Cajas-" . $estudiante->id . '.pdf';
            $rutaComprimida = $this->comprimirPDF($this->file_certificadoCajas, $nombre_file_cajas);
            Storage::disk('ftp')->put('Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_cajas, file_get_contents($rutaComprimida));
            unlink($rutaComprimida);

            $nombre_file_gestora = "Certificado_Gestora-" . $estudiante->id . '.pdf';
            $rutaComprimida = $this->comprimirPDF($this->file_enteGestor, $nombre_file_gestora);
            Storage::disk('ftp')->put('Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_gestora, file_get_contents($rutaComprimida));
            unlink($rutaComprimida);

            $documentoNuevo = DocumentosEstudiante::create([
                'id_estudiante' => $estudianteId,
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
                $documentoNuevo->update([
                    'estado_tipo_sangre' => 4,
                ]);
            }
            $estudiante->update([
                'foto_nombre' => $nombre_foto,
            ]);
            //MAIL
            // $datos_estudiante = Estudiante::join('administracion.persona as per', 'per.id', '=', 'afiliacion.estudiante.id_persona')
            //     ->where('afiliacion.estudiante.id', '=', $estudianteId)->first();
            // $asunto = "Sistema Sighov2 - Unidad de Afiliación";
            // $email = $this->correo_institucional;
            // $correo = new AfiliacionEstudiantesMail($asunto, $datos_estudiante, $imageBase64);
            // try {
            //     Mail::to($email)->send($correo);
            // } catch (\Exception $e) {
            //     // Log del error (opcional)
            //     ::error('No se pudo enviar el correo: ' . $e->getMessage());
            // }

            // NOTIFICACIONES
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
                    'mensaje' => 'El estudiante: ' . $personaActual->p_apellido . ' ' . $personaActual->s_apellido . ' ' . $personaActual->nombres . ' hizo su preregistro,
                        debe verificar los datos validarlos, en caso que haya un error haga la observacion correspondiente.',
                ]);
            }
            $this->crearHistorialAsegurado($personaActual, $estudiante);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('estudianteCancelado');
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));
            Log::error("Afiliacion:error en funcion crear estudiante", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }
    private function crearHistorialAsegurado($persona, $estudiante)
    {
        $existeHistorialAsegurado = HistorialAsegurado::where('id_persona', $persona->id)->orderBy('id', 'desc')->first();
        if ($existeHistorialAsegurado) {
            $existeHistorialAsegurado->estado = false;
            $existeHistorialAsegurado->estado_vigencia = 'NO VIGENTE';
            if ($existeHistorialAsegurado->fecha_baja == null) {
                $existeHistorialAsegurado->fecha_baja = Carbon::now()->format('Y-m-d');
            }
            $existeHistorialAsegurado->id_user_updated = auth()->user()->id;
            $existeHistorialAsegurado->save();

            HistorialAsegurado::create([
                'estado' => true,
                'estado_vigencia' => 'VIGENTE',
                'id_tipo_asegurado' => 11,
                'fecha_inicio' => Carbon::now()->format('Y-m-d'),
                'id_persona' => $persona->id,
                'id_corresponde' => $estudiante->id,
                'id_user_created' => auth()->user()->id,
            ]);
        } else {
            HistorialAsegurado::create([
                'estado' => true,
                'estado_vigencia' => 'VIGENTE',
                'id_tipo_asegurado' => 11,
                'fecha_inicio' => Carbon::now()->format('Y-m-d'),
                'id_persona' => $persona->id,
                'id_corresponde' => $estudiante->id,
                'id_user_created' => auth()->user()->id,
            ]);
        }
    }
    public function cancelarEstudiante()
    {
        $this->dispatch('estudianteCancelado');
        return redirect()->route('dashboard');
    }

    public function mostrarEstudiante()
    {
        $userActual = User::join('administracion.persona as p', 'p.id', '=', 'administracion.users.id_persona')
            ->where('administracion.users.id', '=', auth()->user()->id)
            ->first();
        $this->id_persona = $userActual->id_persona;

        $personaActual = Persona::find($this->id_persona);
        //ESTOS DATOS SI O SI SE TIENEN
        $this->nombres = $personaActual->nombres;
        $this->p_apellido = $personaActual->p_apellido;
        $this->s_apellido = $personaActual->s_apellido;
        $this->ci = $personaActual->ci;
        $this->complemento = $personaActual->complemento;

        //ESTOS DATOS POSIBLEMENTE SI, POSIBLMENTE NO. PARA SABER SI SI O SI NO ME HAGO SWITCHS
        $this->fecha_nacimientoSwitch = $personaActual->fecha_nacimiento ? true : false;
        $this->fecha_nacimiento = $personaActual->fecha_nacimiento;
        if ($personaActual->sexo == 'F') {
            $this->sexo = true;
        } elseif ($personaActual->sexo == 'M') {
            $this->sexo = false;
        } elseif ($personaActual->sexo == null) {
            $this->sexo = false;
        } else {
            $this->sexo = false;
        }
        //dd($this->sexoBen);
        //DATOS DEL FORMULARIO QUE PODRIAN O NO SER NULOS
        if (is_null($personaActual->es_extranjero)) {
            $this->es_extranjeroSwitch = false;
        } else {
            $this->es_extranjeroSwitch = true;
        }

        if ($personaActual->grupo_sanguineo &&  $personaActual->grupo_sanguineo != 'NINGUNO') {
            $this->tiene_grupo_sanguineo = true;
        } else {
            $this->tiene_grupo_sanguineo = false;
        }
        $this->es_extranjero = $personaActual->es_extranjero;
        $this->expedido_ciSwitch = $personaActual->id_dept_exp ? true : false;
        $this->expedido_ci = $personaActual->id_dept_exp;
        $this->matriculaSwitch = $personaActual->matricula_seguro ? true : false;
        $this->matricula = $personaActual->matricula_seguro;
        $this->grupo_sanguineo = $personaActual->grupo_sanguineo;
        $this->alergias = $personaActual->alergias;
        if ($personaActual->id_nacimiento_municipio) {
            $this->datosLugarNacimiento = true;
            $this->mun_nacimiento = $personaActual->id_nacimiento_municipio;
            $mun = Municipio::find($personaActual->id_nacimiento_municipio);
            $this->mun_nacimiento_nombre = $mun->nombre;
            $this->pro_nacimiento = $mun->id_provincia;
            $prov = Provincia::find($mun->id_provincia);
            $this->pro_nacimiento_nombre = $prov->nombre;
            $this->dep_nacimiento = $prov->id_departamento;
            $dept = Departamento::find($prov->id_departamento);
            $this->dep_nacimiento_nombre = $dept->nombre;
            //dd($this->mun_nacimiento, $this->pro_nacimiento, $this->dep_nacimiento);
        }

        $this->id_estado_civilSwitch = $personaActual->id_estado_civil ? true : false;
        $this->id_estado_civil = $personaActual->id_estado_civil;


        $estudianteAntiguo = Estudiante::where('id_persona', $this->id_persona)
            ->orderBy('id', 'desc')
            ->first();
        if ($estudianteAntiguo) {
            $this->registro_universitario = $estudianteAntiguo->registro_universitario;
            $this->gestion_ingreso = $estudianteAntiguo->gestion;
            $this->semestre_actual = $estudianteAntiguo->semestre;
            $this->id_carrera = $estudianteAntiguo->id_carrera;
            // $this->afiliacion = $estudianteAntiguo->afiliacion ? 'NUEVO' : 'ANTIGUO';
            $afili =  $estudianteAntiguo->afiliacion;
            if ($afili == null || $afili == true) {
                $this->afiliacion_txt = 'ANTIGUO';
            }
            // dd( $this->afiliacion_txt );
            $this->obtenerFacultad();
        } else {
            $this->afiliacion_txt = 'NUEVO';
        }

        $tiene_matriculas = HistorialMatriculaEstudiante::where('id_persona', $this->id_persona)
            ->first();
        if ($tiene_matriculas) {
            $this->registro_universitario = $tiene_matriculas->registro_universitario;
            $this->switch_ru = true;
            $this->gestion_ingreso = $tiene_matriculas->gestion_ingreso;
            $this->swith_gestion = true;
        } else {
            $this->switch_ru = false;
            $this->swith_gestion = false;
        }
        // dd($tiene_matriculas);
        //NECESITAMOS CALCULAR LA EDAD EN ESE PUNTO?
        //$this->edad=Carbon::parse($this->fecha_nacimiento)->age;
    }
}
