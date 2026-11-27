<?php

namespace App\Livewire\Afiliacion\EstudiantesSsue;

use App\Jobs\ProcesarAfiliacionEstudianteAsussJob;
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
use App\Models\Afiliacion\HCarnet;
use App\Services\DatosEstudiantes;
use App\Models\Administracion\NotificacionUsuarios;
use App\Models\Vigencia\HistorialAsegurado;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use App\Mail\Afiliacion\AfiliacionSolicitudMail;
use App\Mail\Afiliacion\AfiliacionEstudianteConfirmado;
use App\Services\EstudiantesGehma2;
use App\Services\Migracion\Afiliacion\MigrarEstudiante;
use App\Services\TitularesAsuss;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Locked;


class EstudiantesMain extends Component
{

    public $foto;
    public $fotito;
    public $pdf_ver;
    //datos personales

    public $id_persona;

    public $nombres, $p_apellido, $s_apellido, $ci, $complemento, $fecha_nacimiento;
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
    public $matricula, $edad;
    public $sexo, $sexoTexto;

    //datos referenciales
    #[Locked]
    public $idEst;
    public $estado_vigencia;
    public $estado_validar;
    public $telefono, $correo_institucional, $nombre_referencia, $telefono_referencia;
    public $alergias;
    public $observaciones;
    public $grupo_sanguineo;
    public $fecha_solicitud;
    public $fecha_afiliacion, $fecha_reafiliacion;
    public $direccion, $zona, $zonaId, $zonasSelect;
    public $nro_vivienda, $id_residencia;

    // datos universitarios 
    public $registro_universitario, $gestion_ingreso, $semestre_actual;
    public $facultad, $id_facultad;
    public $carrera, $id_carrera, $idCarrera;

    public $carreras = [];
    public $d;

    #[Locked]
    public $pdf_fotocopia;
    #[Locked]
    public $pdf_matricula;
    #[Locked]
    public $pdf_tipo_sangre;
    #[Locked]
    public $pdf_certificado;
    #[Locked]
    public $pdf_formulario;
    #[Locked]
    public $pdf_boleta;
    public $indefinido = false;

    public $fecha_emision_r, $fecha_vencimiento_r, $fecha_emision, $fecha_vencimiento;
    public $fecha_emision_formatted, $fecha_vencimiento_formatted;
    public $d_latitud, $d_longitud;
    public $user_created, $fecha_created, $tieneEdicion = false, $user_edited, $fecha_edicion;


    protected $listeners = ['ValidarRegistroEstudiante', 'guardarObservacion'];

    public function render()
    {

        // Paso 2 foto. enviar el filename de la foto a una funcion 
        //$this->obtenerImagenBase64($this->foto);

        // Archivos PDF en las vistas modal
        $this->mostrarFotocopia();
        $this->mostrarMatricula();
        $this->mostrarBoleta();
        $this->mostrarFormularioGestora();
        $this->mostrarCertificado101();
        $this->mostrarTipoSangre();

        // LLAMANDO AL SERVICIO PARA LOS DATOS DE LOS ESTUDIANTES 
        /*try {

            $d = new DatosEstudiantes();

            $datos_est =  $d->insercionEstudiantes();
            //dd("todo bien ");
        } catch (\Throwable $th) {
            throw $th;
            //dd("todo mal ");
        }*/

        //$nombre_pdf='boris.pdf';
        //$this->obtenerPdfBase64($nombre_pdf);
        return view('livewire.Afiliacion.estudiantes-ssue.estudiantes-main');
    }

    public function mount()
    {
        //$migrarEst = new MigrarEstudiante();
        //$migrarEst->migrarEstudiante();

        //$this->mostrarEstudiante($this->idEst);
    }

    public function mostrarEstudiante($idEst)
    {
        if ($idEst) {
            $estudiante = Estudiante::with('userCreated', 'userUpdated')
                ->find($idEst);
            $this->telefono = $estudiante->telefono;
            $this->correo_institucional = $estudiante->correo_institucional;
            $this->nombre_referencia = $estudiante->nombre_referencia;
            $this->telefono_referencia = $estudiante->telefono_referencia;
            $this->user_created = $estudiante->userCreated->name;
            $this->fecha_created = $estudiante->created_at;
            $this->estado_validar = $estudiante->estado_validar;
            if ($estudiante->id_user_updated != null) {
                $this->tieneEdicion = true;
                $this->user_edited = $estudiante->userUpdated->name;
                $this->fecha_edicion = $estudiante->updated_at;
            }

            $this->observaciones = $estudiante->observacion;
            if ($this->observaciones == null) {
                $this->observaciones = 'NO CUENTA CON OBSERVACIONES';
            }
            $this->fecha_solicitud = $estudiante->fecha_solicitud_afiliacion ? Carbon::parse($estudiante->fecha_solicitud_afiliacion)->translatedFormat('d/m/Y') : 'Sin dato';
            $this->fecha_afiliacion = $estudiante->fecha_afiliacion ? Carbon::parse($estudiante->fecha_afiliacion)->translatedFormat('d/m/Y') : 'Sin dato';
            $this->fecha_reafiliacion = $estudiante->fecha_reafiliacion ? Carbon::parse($estudiante->fecha_reafiliacion)->translatedFormat('d/m/Y') : '';
            $this->id_residencia = $estudiante->id_residencia;

            $residenciaActual = Residencia::find($this->id_residencia);
            if ($residenciaActual) {
                $this->direccion = $residenciaActual->direccion;
                $this->nro_vivienda = $residenciaActual->nro_vivienda;
                $zonaActual = Zona::find($residenciaActual->id_zona);
                if ($zonaActual) {
                    $this->zona = $zonaActual->nombre;
                    $this->zonaId = $zonaActual->id;
                } else {
                    $this->zona = 'SIN DATO';
                }
                $this->d_latitud = $residenciaActual->latitud;
                $this->d_longitud = $residenciaActual->longitud;
            }

            $this->registro_universitario = $estudiante->registro_universitario;
            $this->gestion_ingreso = $estudiante->gestion;
            $this->semestre_actual = $estudiante->semestre;
            $this->id_carrera = $estudiante->id_carrera;
            $carreraActual = Carrera::find($this->id_carrera);
            if ($carreraActual) {
                $this->carrera = $carreraActual->nombre;
                $this->id_facultad = $carreraActual->id_facultad;
                $facultadActual = Facultad::find($this->id_facultad);
                $this->facultad = $facultadActual->nombre;
            } else {
                $this->carrera = 'Sin dato';
                $this->facultad = 'Sin dato';
            }


            // Paso 1 foto. obtener el filename de la foto
            $this->foto = $this->obtenerImagenBase64($estudiante->foto_nombre);
            $this->id_persona = $estudiante->id_persona;


            $personaActual = Persona::find($this->id_persona);
            $this->nombres = $personaActual->nombres;
            $this->p_apellido = $personaActual->p_apellido;
            $this->s_apellido = $personaActual->s_apellido;
            $this->fecha_nacimiento = $personaActual->fecha_nacimiento;
            $this->edad = Carbon::parse($personaActual->fecha_nacimiento)->age;
            $this->fecha_nacimiento = Carbon::parse($personaActual->fecha_nacimiento)->translatedFormat('d/m/Y');
            $this->grupo_sanguineo = $personaActual->grupo_sanguineo;
            $this->alergias = $personaActual->alergias;
            if ($this->alergias == null) {
                $this->alergias = 'NINGUNO';
            }
            $this->complemento = $personaActual->complemento;
            if ($this->complemento == null) {
                $this->ci = $personaActual->ci;
            } else {
                $this->ci = $personaActual->ci . ' - ' . $personaActual->complemento;
            }
            $this->matricula = $personaActual->matricula_seguro;
            $this->sexo = $this->setSexoTexto($personaActual->sexo);
            $this->id_estado_civil = $personaActual->id_estado_civil;
            $estadoCivil = Estadocivil::find($this->id_estado_civil);
            $this->estado_civil = $estadoCivil->nombre;
            $this->es_extranjero = $this->setExtranjeroTexto($personaActual->es_extranjero);
            $this->id_expedido_ci = $personaActual->id_dept_exp;
            if ($this->id_expedido_ci) {
                $depExpedido = Departamento::find($this->id_expedido_ci);
                $this->expedido_ci = $depExpedido->sigla;
            }
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
                $this->mun_nacimiento = 'Sin dato';
                $this->pro_nacimiento = 'Sin dato';
                $this->dep_nacimiento = 'Sin dato';
            }


            //return $estudiante;
            // dd($personaActual);
        }
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
    // Paso 3 foto. obtenemos el ilename en formato fotografia
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

    /*private function obtenerPdfBase64($filename)
    {
       
        if ($filename) {
            $path = 'Afiliacion/SSUE/DOCUMENTOS/' . $filename;
            $imageData = Storage::disk('ftp')->get($path);
           
            $this->pdf_ver = 'data:application/pdf;base64,'.base64_encode($imageData);
         
        }

        return null;
    }*/

    // PARA ESCUCHAR MODALS ----
    #[On('verEstudiante')]
    public function verEstudiante($data)
    {
        $this->mostrarEstudiante($data);
        $this->idEst = $data;
        // dd($this->idEst);
        $this->dispatch('modalVerEstudiante');
    }

    // habilitarEstudiante   agregarObservacion

    #[On('habilitarEstudiante')]
    public function habilitarEstudiante($data)
    {
        $this->idEst = $data;
        $this->dispatch('ConfirmacionHabilitarEstudiante');
    }

    public function AbrirModalUbicacionEstudiante()
    {
        // dd($this->d_latitud,$this->d_longitud);
        // $this->d_latitud = $this->latitudRegistered;
        // $this->d_longitud = $this->longitudRegistered;
        $this->dispatch('ModalAbrirUbicacionEstudiante');
        // dd($this->latitudRegistered,$this->longitudRegistered);
        //$this->dispatch('inputUpdated', [$this->latitudRegistered, $this->longitudRegistered]);
        $this->skipRender();
    }

    // Aqui se guarda y se registra al ASUSS
    public function ValidarRegistroEstudiante()
    {
        DB::beginTransaction();
        try {
            $est = Estudiante::find($this->idEst);
            $id_estudiante = $est->id;
            $estAntiguo = Estudiante::where('id_persona', $est->id_persona)->whereNot('id', $this->idEst)->orderBy('id', 'desc')->first();
            $documentosEstudiante = DocumentosEstudiante::where('id_estudiante', $this->idEst)->where('activo', true)->first();
            //dd($documentosEstudiante);
            if ($est && $documentosEstudiante) {
                $est->estado_validar = 'VALIDADO';
                ///$est->estado_vigencia = true;
                // $est->fecha_afiliacion = Carbon::now()->format('Y-m-d');
                // if ($estAntiguo) {
                //     $est->fecha_reafiliacion = Carbon::now()->format('Y-m-d');
                // }
                $est->id_user_updated = auth()->user()->id;
                $est->save();

                $documentosEstudiante->estado_carnet = 1;
                $documentosEstudiante->estado_matricula = 1;
                $documentosEstudiante->estado_boleta = 1;
                $documentosEstudiante->estado_certificado_gestora = 1;
                $documentosEstudiante->estado_certificado_cajas = 1;
                $documentosEstudiante->estado_tipo_sangre = 1;
                $documentosEstudiante->id_user_updated = auth()->user()->id;
                $documentosEstudiante->save();

                //Inicio de Notificación
                $user = User::where('id_persona', $est->id_persona)->first();
                $nuevaNotificacion = NotificacionUsuarios::create([
                    'estado' => 'NO LEIDO',
                    'prioridades' => 2,
                    'id_usu_emisor' => auth()->user()->id,
                    'id_usu_receptor' => $user->id, // para que sea a los encargados de aportes
                    'fecha_hora_enviada' => Carbon::now(),
                    'asunto' => 'AFILIACIÓN - DATOS VALIDADOS',
                    'mensaje' => 'Tus datos fueron validados, debes pasar por la unidad de afiliación para entregar los datos en fisico.',
                ]);
                //  fin de  notificacion 


                DB::commit();
                $this->dispatch('validado');
                $this->dispatch('pg:eventRefresh-EstudianteTable');
            } else {
                throw new \Exception('Error forzado despues de la importacion.');
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->dispatch('noValidado');

            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Afiliacion:error al validar registro estudiante", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    #[On('agregarObservacion')]
    public function agregarObservacion($data)
    {
        $this->idEst = $data;
        $this->dispatch('alertaObservacion');
    }

    public function guardarObservacion($data, $options)
    {
        try {
            $est = Estudiante::find($this->idEst);

            if ($est) {
                $est->estado_validar = 'OBSERVADO';
                $est->observacion = $data;
                $est->save();
                //Inicio de Notificación
                $user = User::where('id_persona', $est->id_persona)->first();
                $nuevaNotificacion = NotificacionUsuarios::create([
                    'estado' => 'NO LEIDO',
                    'prioridades' => 2,
                    'id_usu_emisor' => auth()->user()->id,
                    'id_usu_receptor' => $user->id, // para que sea a los encargados de aportes
                    'fecha_hora_enviada' => Carbon::now(),
                    'asunto' => 'AFILIACIÓN - ESTUDIANTE OBSERVADO',
                    'mensaje' => 'Sus datos fueron observados, verifique en el sistema.',
                ]);
                //  fin de  notificacion 
                $this->dispatch('guardado');
                $this->dispatch('pg:eventRefresh-EstudianteTable');
            }

            $doc = DocumentosEstudiante::where('id_estudiante', $this->idEst)->first();
            /* AQUI PONLE 1  */
            if ($doc) {
                if ($options['options']['option1']) {
                    //$doc->estado_carnet = !$options['options']['option1'];
                    $doc->estado_carnet = 3;
                }
                if ($options['options']['option2']) {
                    //$doc->estado_matricula = !$options['options']['option2']; 
                    $doc->estado_matricula = 3;
                }
                if ($options['options']['option3']) {
                    //$doc->estado_boleta = !$options['options']['option3']; 
                    $doc->estado_boleta = 3;
                }
                if ($options['options']['option4']) {
                    //$doc->estado_certificado_gestora = !$options['options']['option4'];
                    $doc->estado_certificado_gestora = 3;
                }
                if ($options['options']['option5']) {
                    //$doc->estado_certificado_cajas = !$options['options']['option5'];
                    $doc->estado_certificado_cajas = 3;
                }
                if ($options['options']['option6']) {
                    //$doc->estado_tipo_sangre = !$options['options']['option6'];
                    $doc->estado_tipo_sangre = 3;
                }
                $doc->save();
                $this->dispatch('guardado');
            }
        } catch (\Throwable $th) {
            $this->dispatch('noValidado');
        }
    }
    //para dar vig
    #[On('darVigencia')]
    public function darVigencia($data)
    {
        $this->idEst = $data;
        $this->dispatch('ConfirmacionVigenciaEstudiante');
    }
    #[On('guardarVigencia')]
    public function guardarVigencia(Request $request)
    {
        DB::beginTransaction();
        try {
            $datos_estudiante = Estudiante::find($this->idEst);
            $datos_estudiante_pasado = Estudiante::where('id_persona', $datos_estudiante->id_persona)
                ->where('estado', false)
                ->orderBy('id', 'desc')
                ->first();
            // dd($datos_estudiante_pasado, $datos_estudiante);
            $foto_recuperada = $this->obtenerImagenBase64($datos_estudiante->foto_nombre);
            $documentosEstudiante = DocumentosEstudiante::where('id_estudiante', $this->idEst)->where('activo', true)->first();
            //dd($this->idEst,$documentosEstudiante);
            $datos_persona = Persona::find($datos_estudiante->id_persona);
            //dd($datos_estudiante);
            if ($datos_estudiante && $documentosEstudiante) {
                $datos_estudiante->estado_validar = 'AFILIADO';
                $datos_estudiante->estado_vigencia = true;
                $datos_estudiante->estado = true;
                $datos_estudiante->ip_afiliado = $request->ip();
                if ($datos_estudiante_pasado) {
                    // dd($datos_estudiante_pasado);
                    $datos_estudiante->fecha_reafiliacion = Carbon::now()->format('Y-m-d');
                    $datos_estudiante->afiliacion = false;
                    if ($datos_estudiante_pasado->fecha_afiliacion == null) {
                        $datos_estudiante->fecha_afiliacion = Carbon::now()->format('Y-m-d');
                    } else {
                        $datos_estudiante->fecha_afiliacion = $datos_estudiante_pasado->fecha_afiliacion;
                    }
                } else {
                    $datos_estudiante->fecha_afiliacion = Carbon::now()->format('Y-m-d');
                }
                $datos_estudiante->fecha_fin_vigencia = Carbon::createFromDate(Carbon::now()->year + 1, 3, 31)->format('Y-m-d');
                $datos_estudiante->id_user_updated = auth()->user()->id;
                $datos_estudiante->save();
                $datos_persona->update([
                    'id_tipo_asegurado' => 11,
                    'afiliado' => true,
                ]);
                //MAIL
                $datos_estudiante = Estudiante::join('administracion.persona as per', 'per.id', '=', 'afiliacion.estudiante.id_persona')
                    ->where('afiliacion.estudiante.id', '=', $this->idEst)->first();
                $asunto = "Sistema Sighov2 - Unidad de Afiliación";
                $email = $datos_estudiante->correo_institucional;
                $imageBase64 = $foto_recuperada;
                $correo = new AfiliacionEstudianteConfirmado($asunto, $datos_estudiante, $imageBase64, $datos_estudiante);
                //dd($this->correo_institucional,$correo,$email,$datos_estudiante);
                /*       try {
                Mail::to($email)->send($correo); //comentado temp 09/04/25
            } catch (\Exception $e) {
                 Log del error (opcional)
                Log::error('No se pudo enviar el correo: ' . $e->getMessage());
            } */
                //Inicio de Notificación
                $user = User::where('id_persona', $datos_estudiante->id_persona)->first();
                $nuevaNotificacion = NotificacionUsuarios::create([
                    'estado' => 'NO LEIDO',
                    'prioridades' => 3,
                    'id_usu_emisor' => auth()->user()->id,
                    'id_usu_receptor' => $user->id,
                    'fecha_hora_enviada' => Carbon::now(),
                    'asunto' => 'AFILIACIÓN - VIGENCIA ACTIVA',
                    'mensaje' => 'Bienvenido al Seguro Social Universitario, usted fue afiliado con éxito.',
                ]);
                //  fin de  notificacion 
                //ASUSS - INSERCIÓN DE DATOS 
                //$insercion = new TitularesAsuss();
                //$insercionAsuss = $insercion->insercionEstudianteAsuss($this->idEst, $foto_recuperada);
                //$estudianteCodigoAsuss = Estudiante::find($this->idEst);
                //
                //
                //if ($insercionAsuss != []) {
                //    dd($insercionAsuss, 'if');
                //   $estudianteCodigoAsuss->codigo_respuesta_asuss = $insercionAsuss[0]['estado_almacenamiento']['codigo'];
                // } else {
                // //    dd($insercionAsuss, 'else');
                //    $estudianteCodigoAsuss->codigo_respuesta_asuss = 504; //Error, el Asuss devuelve un array vacio
                //  }
                //  $estudianteCodigoAsuss->save();
                //INSERSION GEHMA2
                // $replica_estudiante=new EstudiantesGehma2();
                // $respuesta=$replica_estudiante->insercionEstudiantesGehma2($this->idEst);
                // dd($respuesta);

                // TODO: comentado por errores de afiliacion (bladimir) 24/03/2025
                //INSERSION GEHMA2
                //BORRRAR COMENTADO $replica_estudiante = new EstudiantesGehma2();
                //BORRRAR COMENTADO $respuesta = $replica_estudiante->insercionEstudiantesGehma2($this->idEst);
                //PARA LA TABLA DE HISTORIAL ASEGURADO
                $existeHistorialAsegurado = HistorialAsegurado::where('id_persona', $datos_estudiante->id_persona)->orderBy('id', 'desc')->first();
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
                        'id_tipo_asegurado' => $this->id_tipo_asegurado,
                        'fecha_inicio' => Carbon::now()->format('Y-m-d'),
                        'id_persona' => $datos_estudiante->id_persona,
                        'id_corresponde' => $this->idEst,
                        'id_user_created' => auth()->user()->id,
                    ]);
                } else {
                    HistorialAsegurado::create([
                        'estado' => true,
                        'estado_vigencia' => 'VIGENTE',
                        'id_tipo_asegurado' => $this->id_tipo_asegurado,
                        'fecha_inicio' => Carbon::now()->format('Y-m-d'),
                        'id_persona' => $datos_estudiante->id_persona,
                        'id_corresponde' => $this->idEst,
                        'id_user_created' => auth()->user()->id,
                    ]);
                }
                $this->dispatch('vigente');
                $this->dispatch('pg:eventRefresh-EstudianteTable');
                DB::commit();
                // FIN TABLA DE HISTORIAL ASEGURADO

                //------------------------------------------------------- NUEVA INSERCION DE DATOS ASUSS -------------------------------------------------------

                $arrayDatosOriginales = [
                    'codigo_entidad' => '',
                    'fecha_ingreso_trabajo' => '',
                    'puesto_empleado' => '',
                    'salario' => '',
                    'departamento' => 'LA PAZ',
                    'municipio' => 'NUESTRA SEÑORA DE LA PAZ',
                    'fecha_inicio' => '',
                    'tipo_asegurado' => 'ESTUDIANTE',
                    'matricula' => '',
                    'tipo_documento' => 'CI',
                    'numero_documento' => '',
                    'fecha_nacimiento' => '',
                    'genero' => '',
                    'estado_civil' => '',
                    'foto_persona' => '',
                    'id_municipio_nacimiento_persona' => '',
                    'id_persona' => $datos_estudiante->id_persona,
                    'id_tipo_asegurado' => 11,
                    'id_corresponde' => $this->idEst,
                    'id_titular' => null,
                    'id_institucion' => null,
                    'id_user_created' => $datos_estudiante->id_user_created,
                ];

                //ProcesarAfiliacionEstudianteAsussJob::dispatch($arrayDatosOriginales);   -----------------------------------DESCOMENTAR PARA QUE EL ASUSS FUNCIONE

                //------------------------------------------------------- FIN NUEVA INSERCION DE DATOS ASUSS -------------------------------------------------------

            }
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('regCancelado');
        }
    }

    // -----------------------------------------------------------
    //Abrir modal fotocopia C.I
    public function fotocopia_ci()
    {
        $this->mostrarFotocopia();
        $this->dispatch('modalFotocopiaCi');
        $this->skipRender();
    }

    public function mostrarFotocopia()
    {
        $path = 'Afiliacion/SSUE/DOCUMENTOS/' . 'Carnet-' . $this->idEst . '.pdf'; // Adjust path as needed
        $fileContents = Storage::disk('ftp')->get($path);
        $pdfBase64 = base64_encode($fileContents);
        $this->pdf_fotocopia = $pdfBase64;
    }

    public function descargarCarnet()
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

    //Abrir modal matricula
    public function matriculas()
    {
        $this->dispatch('modalVerMatricula');
        $this->skipRender();
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
        $this->dispatch('modalVerBoleta');
        $this->skipRender();
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
        $path = 'Afiliacion/SSUE/DOCUMENTOS/' . 'Boleta_Inscripcion-' . $this->idEst . '.pdf';
        //dd($path);
        $fileContents = Storage::disk('ftp')->get($path);
        $pdfBase64 = base64_encode($fileContents);
        $this->pdf_boleta = $pdfBase64;
    }

    //Abrir modal form de no afilaicion
    public function form_gestora()
    {
        $this->dispatch('modalVerformulario');
        $this->skipRender();
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
    public function mostrarFormularioGestora()
    {
        $path = 'Afiliacion/SSUE/DOCUMENTOS/' . 'Certificado_Gestora-' . $this->idEst . '.pdf'; // Adjust path as needed
        $fileContents = Storage::disk('ftp')->get($path);
        $pdfBase64 = base64_encode($fileContents);
        $this->pdf_formulario = $pdfBase64;
    }

    //Abrir modal certificado_cajas_101 - formulario 101
    public function certificado_cajas_101()
    {
        $this->dispatch('modalVerCertificadoCajas');
        $this->skipRender();
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
    public function mostrarCertificado101()
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
        $this->dispatch('modalTipoSangre');
        $this->skipRender();
    }
    public function mostrarTipoSangre()
    {
        $path = 'Afiliacion/SSUE/DOCUMENTOS/' . 'Tipo_Sangre-' . $this->idEst . '.pdf'; // Adjust path as needed
        //dd($path);
        $fileContents = Storage::disk('ftp')->get($path);
        $pdfBase64 = base64_encode($fileContents);
        $this->pdf_tipo_sangre = $pdfBase64;
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


    public function crear_h_carnet()
    {
        // Validaciones previas
        if ($this->indefinido) {
            if (is_null($this->fecha_emision_r)) {
                $this->dispatch('indefinidoSinEmision'); // Disparar la alerta en frontend
                $this->resetFormCarnet(); // Limpiar variables
                return; // Detener ejecución
            }
            if (!is_null($this->fecha_vencimiento_r)) {
                $this->dispatch('indefinidoConVencimiento'); // Disparar la alerta en frontend
                $this->resetFormCarnet(); // Limpiar variables
                return; // Detener ejecución
            }
        } else {
            if (is_null($this->fecha_emision_r) || is_null($this->fecha_vencimiento_r)) {
                $this->dispatch('normalSinFechas'); // Disparar alerta en frontend
                $this->resetFormCarnet(); // Limpiar variables
                return;
            }

            if ($this->fecha_vencimiento_r <= $this->fecha_emision_r) {
                $this->dispatch('fechasInvalidas'); // Disparar alerta en frontend
                $this->resetFormCarnet(); // Limpiar variables
                return;
            }
        }

        // Intentar crear el carnet
        DB::beginTransaction();
        try {
            $r_carnet = HCarnet::create([
                'fecha_emision' => $this->fecha_emision_r,
                'fecha_vencimiento' => $this->indefinido ? null : $this->fecha_vencimiento_r,
                'id_persona' => $this->id_persona,
                'indefinido' => $this->indefinido,
                'id_user_created' => auth()->user()->id,
            ]);

            if (!$r_carnet) {
                DB::rollBack();
                $this->dispatch('errorCreacion'); // Notificar error
                $this->resetFormCarnet(); // Limpiar variables
                return;
            }

            DB::commit();
            $this->dispatch('RegistroCarnetCorrecto'); // Notificar éxito
            $this->js('window.open("/afiliacion/estudiante/carnet_pdf/' . $this->idEst . '", "_blank")');
            $this->resetFormCarnet(); // Limpiar variables después de éxito

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('errorCreacion'); // Notificar error en frontend
            $this->resetFormCarnet(); // Limpiar variables
        }
    }
    public function resetFormCarnet()
    {
        $this->fecha_emision_r = null;
        $this->fecha_vencimiento_r = null;
        $this->indefinido = false; // O el valor predeterminado que necesitas
    }

    public function verificarFotoEstudiante($data)
    {
        $id_estidiante = $data;
        $estudiante = Estudiante::where('id', $id_estidiante)->get();
        // dd($estudiante[0]->foto_nombre);
        $nombre_foto_afiliado = $estudiante[0]->foto_nombre;
        $foto_afiliado = null;
        // $foto_afiliado = 'data:image/png;base64,' . $foto_afiliado;
        $foto_afiliado = $this->obtenerImagenBase64($nombre_foto_afiliado);
        return $foto_afiliado;
        // dd($foto_afiliado);
    }

    public function recarnetizacion()
    {
        $this->js('window.open("/afiliacion/estudiante/carnet_pdf/' . $this->idEst . '", "_blank")');
        $this->dispatch('RegistroCarnetCorrecto');
    }
    public function ver_afiliado($idEstudiante)
    {
        //dd( $this->id_persona);
        $idPersona = $this->id_persona;
        $this->dispatch('modalVerAfiliadoEstudiante');
        $this->dispatch('setPersonaId', $idPersona);
        $this->skipRender();
    }

    #[On('carnetizacion')]
    public function carnetizacion($data)
    {
        // dd($data);
        $this->fecha_emision_r = Carbon::now()->toDateString();
        $foto_afiliado_verificar = $this->verificarFotoEstudiante($data);

        if ($foto_afiliado_verificar != null) {
            $this->mostrarEstudiante($data);
            $carnet = HCarnet::where('id_persona', $this->id_persona)->orderby('id', 'desc')->first();
            // $this->js('window.open("/afiliacion/estudiante/carnet_pdf/'.$data . '", "_blank")');
            if ($carnet) {
                $this->fecha_emision = $carnet->fecha_emision;
                $this->fecha_emision_formatted = Carbon::parse($this->fecha_emision)->Format('d /m/ Y ');
                $this->fecha_vencimiento = $carnet->fecha_vencimiento;
                $this->fecha_vencimiento_formatted = Carbon::parse($this->fecha_vencimiento)->Format('d /m/ Y ');
            }
            $this->idEst = $data;
            $this->dispatch('modalCarnetizacionEst');
        } else {
            $this->dispatch('sinFotoAfiliado');
        }
    }

    public function agregarNuevoEstudiante()
    {
        return redirect()->route('afiliacion.ssue.create');
    }


    // public $url;
    // public function ver_formulario_101()
    // {
    //     dd("asd");
    //     ini_set('memory_limit', '2024M');
    //     $base64 = $this->pdf_certificado;
    //     // Decodificar
    //     $contenidoPDF = base64_decode($base64);

    //     // Nombre único
    //     $nombreArchivo = 'temp_pdf_' . uniqid() . '.pdf';

    //     // Guardar en public/temp/
    //     file_put_contents(public_path("temp/{$nombreArchivo}"), $contenidoPDF);

    //     // Generar URL accesible
    //     $this->url = asset("temp/{$nombreArchivo}");
    //     //$this->dispatch('modalVerCertificadoCajas');
    //     dd($this->url);
    // }


    #[On('reafiliarEstudiante')]
    public function reafiliarEstudiante($data)
    {
        // dd('fsdf');
        $this->idEst = $data;
        $this->dispatch('reafiliarEstudianteAlert');
    }

    #[On('guardarReafiliacionEstudiante')]
    public function guardarReafiliacionEstudiante()
    {
        $estudiantePasado = Estudiante::find($this->idEst);
        //dd($estudiantePasado);
        if ($estudiantePasado) {
            // verificar
            // $estudiantePasado->estado = false;
            $estudiantePasado->estado_vigencia = false;
            $estudiantePasado->reafiliacion = true;
            $estudiantePasado->fecha_baja = now();
            $estudiantePasado->id_user_baja = auth()->user()->id;
            $estudiantePasado->save();

            $historial_pasado = HistorialAsegurado::where('id_persona', $estudiantePasado->id_persona)
                ->orderBy('id', 'desc')
                ->first();
            if ($historial_pasado) {
                $historial_pasado->fecha_baja_reafiliacion = now();
                // $historial_pasado->estado = false;
                $historial_pasado->estado_vigencia = 'NO VIGENTE';
                $historial_pasado->id_user_baja_reafiliacion = auth()->user()->id;
                $historial_pasado->save();
            }
            // para activar usuario 
            $usuarioEstudiante = User::where('id_persona', $estudiantePasado->id_persona)->first();
            if ($usuarioEstudiante && $usuarioEstudiante->estado == false) {
                $usuarioEstudiante->estado = true;
                $usuarioEstudiante->syncRoles([6]);
                $usuarioEstudiante->save();
            }
            // dd($estudiantePasado->id_persona, 'dasd', $usuarioEstudiante);
        }
        $this->dispatch('estudianteListoReafiliar');
        $this->dispatch('pg:eventRefresh-EstudianteTable');
    }

    public function abrirModalReportesEstudiantes()
    {
        //dd('dasda');
        $this->dispatch('modalReportesEstudiantes');
    }
}
