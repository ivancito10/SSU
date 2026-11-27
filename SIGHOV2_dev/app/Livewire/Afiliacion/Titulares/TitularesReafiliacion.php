<?php

namespace App\Livewire\Afiliacion\Titulares;

use App\Jobs\ProcesarAfiliacionTitularAsussJob;
use Livewire\Component;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\Estudiante;
use App\Services\TitularesGehma2;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Administracion\Residencia;
use App\Models\Administracion\Zona;
use App\Models\Aportes\Institucion;
use App\Models\Administracion\Persona;
use App\Models\Aportes\AporteInstitucional;
use App\Models\Administracion\Departamento;
use App\Models\Administracion\Provincia;
use App\Models\Administracion\Municipio;
use App\Models\Administracion\EstadoCivil;
use App\Models\Afiliacion\CodigoInstitucionSsu;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\Administracion\NotificacionUsuarios;
use Carbon\Carbon;
use App\Models\User;
use App\Services\GrupoFamiliarPersona;
use App\Services\TitularesAsuss;
use App\Mail\Afiliacion\AfiliacionTitularesMail;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\HCarnet;
use Illuminate\Support\Facades\Mail;
use App\Models\Vigencia\HistorialAsegurado;
use App\Services\VerificarDarBaja;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TitularesReafiliacion extends Component
{
    use WithFileUploads;

    //public $id_persona;
    public $idPersona;
    public $idTitular, $id_estado_civil,
        $estado_civil;
    public $estadoCivilSelect;
    public $zonasSelect;
    public  $fecha_ingreso, $cargo, $salario, $item, $carga_horaria, $titularidad;
    public $nombre,
        $ap_paterno,
        $ap_materno,
        $carnet,
        $complemento,
        $fecha_nacimiento, $matricula, $sexo, $sangre, $fecha_afiliacion, $fecha_reafiliacion,
        $fecha_reafiliacion_r, $estado_vigencia, $estado_requisitos, $grupo_sanguineo, $imageUrl,
        $edad, $departamentoExpedido, $tipo_documento;
    #[Validate]
    public $file_digital;
    #[Validate]
    public $foto;
    public $fotoAntigua;
    #[Validate]
    public $direccion, $nro_vivienda;
    public $zona, $zonaId;
    #[Validate]
    public $telefono;
    #[Validate]
    public $correo;
    #[Validate]
    public $nombre_referencia,  $telefono_referencia;
    public $alergias,
        $observacion,
        $id_residencia,
        $complemento_m,
        $nombre_completo,
        $tipo_asegurado = 0, // 1 para 'VOLUNTARIO' o 0 para 'INSTITUCIONAL'
        $ubicacion_select = 0;
    public $lista_instituciones,
        $instituciones = [],
        $institucion = '';
    public $codigo_titular_ver, $codigo_beneficiario_ver, $codigo_ver, $nombre_institucion;

    public $nacimiento_municipio, $departamento, $provincia, $municipio;
    public $permanente = false;
    public $fechaVencimientoCarnet;

    #[Validate]
    public $tipo_doc,
        $expedido_ci;

    public $latitud = '';
    public $longitud = '';
    public $esRentista = false;
    public $tipo_rentista, $today;
    public $fecha_afiliacion_real;
    public $tipo_titular;
    public $codigo_institucional, $tiene_institucion;

    public $fechaNacimientoReal;

    protected $listeners = ['recibirLonLat', 'guardarReafiliacion', 'addInstitucionReaf'];

    public function rules()
    {
        return [
            'direccion' => 'required|min:5',
            'zona' => 'required',
            'nro_vivienda' => 'required|min:1',
            'telefono' => 'required|min:6',

            'nombre_referencia' => 'required|min:2',
            'telefono_referencia' => 'required|min:6',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'file_digital' => 'required|mimes:pdf|max:51200',
            'id_estado_civil' => 'required',

        ];
    }

    public function render()
    {
        //$this->idPersona=$this->id_persona;
        //$aportes = $this->mostrarAportes($this->idPersona);
        //$persona = Persona::all();
        //$residencia = Residencia::all();
        //$zonasSelect = Zona::all();
        //$estadocivilSelect = EstadoCivil::all();
        //$this->departamento = Departamento::get();


        return view('livewire.Afiliacion.titulares.titulares-reafiliacion');
    }

    public function mount()
    {
        //dd($this->idTitular);
        $this->mostrarDatosTitular($this->idTitular);
        $this->estadoCivilSelect = EstadoCivil::all();
        $this->zonasSelect = Zona::orderBy('nombre', 'asc')->get();
        $this->fecha_reafiliacion_r = now()->format('d/m/Y'); // Formato YYYY-MM-DD
        $this->lista_instituciones = Institucion::where('estado', true)
            ->where('nombre', '!=', 'UNIVERSIDAD MAYOR DE SAN ANDRES')
            ->where(function ($q) {
                $q->where(function ($sub) {
                    $sub->where('nombre', 'like', 'UNIVERSIDAD CATOLICA BOLIVIANA SAN PABLO%')
                        ->where('nombre', 'not like', '%-%');
                })
                    ->orWhere('nombre', 'not like', 'UNIVERSIDAD CATOLICA BOLIVIANA SAN PABLO%');
            })
            ->with(['codigosInstitucion', 'claseAportanteInstitucion', 'institucionResidencia'])
            ->orderBy('nombre', 'asc')
            ->get();
        $this->titularVigenteFunction();
        //  $servicioGrupoFamiliar = new GrupoFamiliarPersona();
        //  $listadoFamiliares = $servicioGrupoFamiliar->encontrarPersona(9);
        //  dd($listadoFamiliares);
    }

    public function recibirLonLat($d_lat, $d_lng)
    {
        $this->latitud = $d_lat;
        $this->longitud = $d_lng;
        $this->ubicacion_select = 1;
    }

    public function mostrarDatosTitular($idTitular)
    {
        if ($idTitular) {
            $titular = Titular::where('id', $idTitular)->with('titularPersona')->first();
            $this->idPersona = $titular->titularPersona->id;
            $this->fecha_afiliacion = Carbon::parse($titular->fecha_afiliacion)->format('d/m/Y');
            $this->fecha_afiliacion_real = $titular->fecha_afiliacion;

            // $this->tipo_titular = $titular->tipo_titular ? 'INSTITUCIONAL' : 'VOLUNTARIO';
            $this->fotoAntigua = $this->obtenerImagenBase64($titular->foto_nombre);
            //$this->fecha_reafiliacion = $titular->fecha_reafiliacion;
            $this->estado_vigencia = $titular->estado_vigencia;
            $this->estado_requisitos = $titular->estado_requisitos;
            $this->telefono = $titular->telefono;
            $this->correo = $titular->correo;
            $this->nombre_referencia = $titular->nombre_referencia;
            $this->telefono_referencia = $titular->telefono_referencia;

            $this->observacion = $titular->observacion ? $titular->observacion : 'NO';
            $this->nacimiento_municipio = $titular->titularPersona->id_nacimiento_municipio;
            if ($this->nacimiento_municipio) {
                $municipioActual = Municipio::find($titular->titularPersona->id_nacimiento_municipio);
                $this->municipio = $municipioActual->nombre;
                $provinciaActual = Provincia::find($municipioActual->id_provincia);
                $this->provincia = $provinciaActual->nombre;
                $departamentoActual = Departamento::find($provinciaActual->id_departamento);
                $this->departamento = $departamentoActual->nombre;
            } else {
                $this->municipio = 'No se encontraron datos';
                $this->provincia = 'No se encontraron datos';
                $this->departamento = 'No se encontraron datos';
            }


            //$personaActual = Persona::find($titular->id_persona);
            $this->nombre = $titular->titularPersona->nombres;
            $this->ap_paterno = $titular->titularPersona->p_apellido;
            $this->ap_materno = $titular->titularPersona->s_apellido;
            $this->carnet = $titular->titularPersona->complemento ? $titular->titularPersona->ci . ' - ' . $titular->titularPersona->complemento : $titular->titularPersona->ci;
            $this->fecha_nacimiento = Carbon::parse($titular->titularPersona->fecha_nacimiento)->format('d/m/Y');
            $this->fechaNacimientoReal = $titular->titularPersona->fecha_nacimiento;
            //dd($this->fecha_nacimiento);
            $this->matricula = $titular->titularPersona->matricula_seguro;
            $this->sexo = $titular->titularPersona->sexo;
            $this->sangre =  $titular->titularPersona->grupo_sanguineo;
            if (is_null($titular->titularPersona->alergias)) {
                $this->alergias = 'NINGUNO';
            } else {
                $this->alergias =  $titular->titularPersona->alergias;
            }

            $this->complemento_m = $titular->titularPersona->complemento;
            if ($this->ap_paterno == null) {
                $this->nombre_completo = $this->ap_materno . ' ' . $this->nombre;
            } elseif ($this->ap_materno == null) {
                $this->nombre_completo = $this->ap_paterno . ' ' . $this->nombre;
            } else {
                $this->nombre_completo = $this->ap_paterno . ' ' . $this->ap_materno . ' ' . $this->nombre;
            }

            $this->id_estado_civil = $titular->titularPersona->id_estado_civil;
            $this->edad = Carbon::parse($titular->titularPersona->fecha_nacimiento)->age;
            //dd($this->edad); 
            $id_persona = $titular->titularPersona->id;

            $expedidoActual = Departamento::find($titular->titularPersona->id_dept_exp);
            if ($expedidoActual) {
                $this->departamentoExpedido = $expedidoActual->sigla;
            } else {
                $this->departamentoExpedido = '';
            }
            $this->tipo_documento = $titular->titularPersona->es_extranjero ? 'EXTRANJERO' : 'NACIONAL';


            /*if ($personaActual->es_extranjero == true) {
                $this->tipo_documento = 'EXTRANJERO';
            } else {
                $this->tipo_documento = 'NACIONAL';
            }*/

            $estadocivilActual = EstadoCivil::find($titular->titularPersona->id_estado_civil);
            $this->estado_civil = $estadocivilActual->nombre;

            $residenciaActual = Residencia::find($titular->id_residencia);
            $this->direccion = $residenciaActual->direccion;
            $this->nro_vivienda = $residenciaActual->nro_vivienda;

            $zonaActual = Zona::find($residenciaActual->id_zona);
            $this->zona = $zonaActual->nombre;
            $this->zonaId = $zonaActual->id;

            //$institucionTit = TitularInstitucion::where('id_titular', $idTitular)->get();
            //return $titular;
            $carnetTitular = HCarnet::where('id_persona', $this->idPersona)
                ->orderBy('id', 'desc')
                ->first();

            if ($carnetTitular) {
                $fechaVencimiento = $carnetTitular->fecha_vencimiento;
                $this->fechaVencimientoCarnet = Carbon::parse($fechaVencimiento)->Format('d /m/ Y ');
            } else {
                $this->fechaVencimientoCarnet = 'SIN DATO';
            }
        }
    }

    // Funcion para obtener imagenes desde la FTP
    private function obtenerImagenBase64($filename)
    {
        try {
            if ($filename) {
                $path = 'Afiliacion/FOTOS/' . $filename;
                $imageData = Storage::disk('ftp')->get($path);
                //dd($imageData);
                $fotito = base64_encode($imageData);
                //dd($imageData);
                return $fotito;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function atras()
    {
        return redirect()->route('afiliacion.titulares.index');
    }

    public function VerCodigos()
    {
        $registro = CodigoInstitucionSsu::where('id_institucion', $this->institucion)->first();
        if ($registro) {
            $this->codigo_titular_ver = $registro->codigo . $registro->tipo_titular;
            $this->codigo_beneficiario_ver = $registro->codigo . $registro->tipo_beneficiario;
        } else {
            $this->codigo_titular_ver = '';
            $this->codigo_beneficiario_ver = '';
        }
    }
    public function titularVigenteFunction()
    {
        $titularVigente = Titular::where('id_persona', $this->idPersona)
            ->whereIn('estado_vigencia', ['VIGENTE', 'CESANTE'])
            ->where('estado', true)
            ->first();
        if ($titularVigente) {
            $this->dispatch('esTitular');
        }
    }
    public function OcultarAgregar()
    {
        if ($this->tipo_asegurado == true) {
            $tipo = 1;
        } else {
            $tipo = 0;
        }
        //return $this->tipo_asegurado;
    }

    public function guardarReafiliacion()
    {
        //dd('JOLA', $this->instituciones, $this->idTitular);
        $this->validate();
        DB::beginTransaction();
        // try {
        $existeResidencia = Residencia::where('direccion', $this->direccion)
            ->where('nro_vivienda', $this->nro_vivienda)
            ->where('id_zona', $this->zonaId)
            ->first();
        if ($existeResidencia) {
            $residenciaId = $existeResidencia->id;
        } else {
            $residenciaNueva = Residencia::create([
                'direccion' => $this->direccion,
                'nro_vivienda' => $this->nro_vivienda,
                'id_zona' => $this->zonaId,
                'latitud' => $this->latitud,
                'longitud' => $this->longitud,
                'id_user_created' => auth()->user()->id,
            ]);
            $residenciaId = $residenciaNueva->id;
        }
        $existePersona = Persona::find($this->idPersona);
        $esEstudiante = Estudiante::where('id_persona', $existePersona->id)
            ->where('estado_vigencia', true)
            ->where('estado', true)
            ->exists();

        $esBeneficiario = Beneficiario::where('id_persona', $existePersona->id)
            ->whereIn('vigencia', ['VIGENTE', 'CESANTE'])
            ->where('estado', true)
            ->exists();

        if ($esEstudiante) {
            $this->dispatch('esEstudiante');
            return;
        } elseif ($esBeneficiario) {
            $this->dispatch('esBeneficiario');
            return;
        }
        $existePersona->update([
            'id_estado_civil' => $this->id_estado_civil,
            'id_tipo_asegurado' => 1,
            'alergias' => $this->alergias,
            'id_user_updated' => auth()->user()->id,
            'afiliado' => true,
        ]);
        $titularReafiliado = Titular::create([
            'estado_requisitos' => true,
            'telefono_referencia' => $this->telefono_referencia,
            'nombre_referencia' => $this->nombre_referencia,
            'observacion' => $this->observacion,
            'fecha_afiliacion' => $this->fecha_afiliacion_real,
            'fecha_reafiliacion' => carbon::now(),
            'id_persona' => $this->idPersona,
            'telefono' => $this->telefono,
            'correo' => $this->correo,
            'estado' => true,
            'permanente' => $this->permanente,
            'id_residencia' => $residenciaId,
            'tipo_rentista' => $this->tipo_rentista,
            'id_user_created' => auth()->user()->id,
        ]);
        $this->tiene_institucion = !$this->tipo_asegurado;
        if ($this->tiene_institucion && empty($this->instituciones)) {
            DB::rollBack();
            $this->dispatch('institucionNoIngresada');
            return;
        }
        // to inactvar a titular
        $titularPasado = Titular::where('id', $this->idTitular)
            ->where('estado', true)
            ->first();
        //dd($titularPasado);
        $titularPasado->update(['fecha_baja' => Carbon::now(), 'estado_vigencia' => 'NO VIGENTE', 'estado' => false]);

        // TO INACTIVAR DUD INTITUCIONES PASADAS
        $InactivarInstitucionesPasadas = TitularInstitucion::where('id_titular', $this->idTitular)
            ->get();
        foreach ($InactivarInstitucionesPasadas as $institucion) {
            $institucion->estado = false;
            $institucion->save();
        }
        // para inactivar a beneficiarios
        $listadoFamiliares = $this->grupoFamiliar($titularPasado->id);
        // $listado =  $this->grupoFamiliar($titularPasado->id);
        // dd($listadoFamiliares);
        if (!empty($listadoFamiliares)) {
            foreach ($listadoFamiliares as $familiar) {
                $actualizarBeneficiario = Beneficiario::where('id', $familiar->id)
                    ->where('id_titular', $titularPasado->id)
                    ->orderBy('id', 'desc')
                    ->first();
                // dd($actualizarBeneficiario);
                if ($actualizarBeneficiario) {

                    //PARA LA TABLA HISTORIAL 
                    $actualizacionHistorialBeneficiario = HistorialAsegurado::where('id_persona', $familiar->id_persona)
                        ->orderBy('id', 'desc')
                        ->first();
                    // dd($actualizacionHistorialBeneficiario);
                    if ($actualizacionHistorialBeneficiario) {
                        $actualizacionHistorialBeneficiario->estado_vigencia = 'NO VIGENTE';
                        $actualizacionHistorialBeneficiario->fecha_baja_reafiliacion = Carbon::now()->format('Y-m-d');
                        $actualizacionHistorialBeneficiario->id_user_baja_reafiliacion = auth()->user()->id;
                        $actualizacionHistorialBeneficiario->save();
                    }
                    //MODIFICADO RECIEN EN LA TABLA BENEFICIARIO
                    $actualizarBeneficiario->vigencia = 'NO VIGENTE';
                    $actualizarBeneficiario->fecha_baja = Carbon::now()->format('Y-m-d');
                    $actualizarBeneficiario->id_user_updated = auth()->user()->id;
                    $actualizarBeneficiario->save();
                    //PARA ACTUALIZAR A LA PERSONA
                    $actualizarPersonaBeneficiario = Persona::where('id', $familiar->id_persona)->first();
                    // dd($actualizarPersonaBeneficiario);
                    if ($actualizarPersonaBeneficiario) {
                        $actualizarPersonaBeneficiario->afiliado = false;
                        $actualizarPersonaBeneficiario->id_tipo_asegurado = 0;
                        $actualizarPersonaBeneficiario->id_user_updated = auth()->user()->id;
                        $actualizarPersonaBeneficiario->save();
                        //SERVICIO DE REPLICA
                        //  $ciServicio = $actualizarPersonaBeneficiario->ci;
                        //  if ($actualizarPersonaBeneficiario->complemento != null) {
                        //      $ciServicio .= '-' . $actualizarPersonaBeneficiario->complemento;
                        //  }
                        //  $motivoServicio = 3;
                        //  if ($this->id_motivo_baja == 6) {
                        //      $motivoServicio = 4;
                        //  }
                        //  $bajas = [];
                        //  $bajas[] = [
                        //      'ci' => $ciServicio,
                        //      'id_estado_afiliado' => $motivoServicio,
                        //  ];
                        //  $regBajasGehma2 = new VerificarDarBaja();
                        //  $regBajasGehma2->bajasGehma2(['bajas' => $bajas]);
                    }

                    //ACTUALIZAR USUARIO
                    $actualizarUsuario = User::where('id_persona',  $familiar->id_persona)->first();
                    if ($actualizarUsuario) {
                        $this->darBajaUsuario($actualizarUsuario->id);
                    }
                }
            }
        }
        //dd($listadoFamiliares);

        // FIN DE INACTIVACIONES
        $nombre_foto = $titularReafiliado->id . '-T.png';

        if ($this->foto) {
            $url = config('services.rembg.url');
            $imagePath = $this->foto->getRealPath();
            $imageName = 'foto_' . uniqid() . '.png'; // nombre único

            try {
                // Enviamos la imagen al contenedor rembg
                $response = Http::timeout(90)->attach(
                    'image',
                    file_get_contents($imagePath),
                    basename($imagePath)
                )->post($url);
                if ($response->successful() && strlen($response->body()) > 1000) {

                    // Si rembg funcionó, usamos la imagen sin fondo
                    $imageWithoutBg = $response->body();
                } else {
                    // Si falla, usamos la imagen original
                    throw new \Exception('Respuesta vacía o errónea desde rembg');
                }
            } catch (\Exception $e) {
                // Si rembg falla, usamos la imagen original
                $imageWithoutBg = file_get_contents($imagePath);
            }

            // Codificamos para base64 si necesitas usarlo en otro lugar
            $imageBase64_asuss = base64_encode($imageWithoutBg);
            $imageBase64 = 'data:image/' . $this->foto->extension() . ';base64,' . $imageBase64_asuss;

            // Guardamos en FTP
            Storage::disk('ftp')->put('Afiliacion/FOTOS/' . $nombre_foto, $imageWithoutBg);
        }

        $nombre_file = $titularReafiliado->id . '.pdf';
        $this->file_digital->storeAs('Afiliacion/FILES_PERSONALES/TITULARES', $nombre_file, 'ftp');

        $titularReafiliado->update(['foto_nombre' => $nombre_foto]);
        if ($this->instituciones) {
            foreach ($this->instituciones as $institucion) {
                TitularInstitucion::create([
                    'id_titular' => $titularReafiliado->id,
                    'id_institucion' => $institucion['id_institucion'],
                    'estado' => 1,
                    'fecha_ingreso' => $institucion['fecha_ingreso'],
                    'cargo_actual' => $institucion['cargo'],
                    'salario' => $institucion['salario'],
                    'tipo_institucion' => $institucion['tipo_institucion'],
                    'item' => $institucion['item'],
                    'carga_horaria' => $institucion['carga_horaria'],
                    'titularidad' => $institucion['titularidad'],
                    'id_user_created' => auth()->user()->id,
                ]);
            }
            $titularReafiliado->update(['tipo_titular' => 1]);
        } else {
            $titularReafiliado->update(['tipo_titular' => 0]);
        }
        //PARA LA TABLA DE HISTORIAL ASEGURADO
        $existeHistorialAsegurado = HistorialAsegurado::where('id_persona', $existePersona->id)->orderBy('id', 'desc')->first();

        if ($existeHistorialAsegurado) {
            $existeHistorialAsegurado->estado = false;
            $existeHistorialAsegurado->estado_vigencia = 'NO VIGENTE';
            $existeHistorialAsegurado->fecha_baja_reafiliacion = Carbon::now()->format('Y-m-d');
            $existeHistorialAsegurado->id_user_baja_reafiliacion = auth()->user()->id;
            $existeHistorialAsegurado->save();
            HistorialAsegurado::create([
                'estado' => true,
                'estado_vigencia' => 'VIGENTE',
                'id_tipo_asegurado' => 1,
                'fecha_inicio' => Carbon::now()->format('Y-m-d'),
                'id_persona' => $existePersona->id,
                'id_corresponde' => $titularReafiliado->id,
                'id_user_created' => auth()->user()->id,
            ]);
        } else {
            HistorialAsegurado::create([
                'estado' => true,
                'estado_vigencia' => 'VIGENTE',
                'id_tipo_asegurado' => 1,
                'fecha_inicio' => Carbon::now()->format('Y-m-d'),
                'id_persona' => $existePersona->id,
                'id_corresponde' => $titularReafiliado->id,
                'id_user_created' => auth()->user()->id,
            ]);
        }

        // FIN TABLA DE HISTORIAL ASEGURADO

        //inicio de NOTIFICACIONES 
        $user = User::where('id_persona', $this->idPersona)->first();
        //asignacion de rol
        $user->assignRole('Asegurado Afiliado');
        $user->syncRoles(['Asegurado Afiliado']);
        //
        $nuevaNotificacion = NotificacionUsuarios::create([
            'estado' => 'NO LEIDO',
            'prioridades' => 3,
            'id_usu_emisor' => auth()->user()->id,
            'id_usu_receptor' => $user->id, // para que sea a los encargados de aportes
            'fecha_hora_enviada' => Carbon::now(),
            'asunto' => 'AFILIACIÓN - REAFILIACIÓN DE ASEGURADO',
            'mensaje' => 'Bienvenido nuevamente al Seguro Social Universitario de La Paz, su afiliación fue realizada exitosamente.',
        ]);
        $this->js('window.open("/Administracion/usuario/repetir/reafiliacion/' . $titularReafiliado->id . '", "_blank")');

        DB::commit();

        //------------------------------------------------------- NUEVA INSERCION DE DATOS ASUSS -------------------------------------------------------  

        /*$servicioLoginAsuss = new LoginAsussService();

                $servicioAsussTitular = new AfiliacionTitularAsussService($servicioLoginAsuss);*/

        $arrayDatosOriginales = [
            'codigo_entidad' => '',
            'fecha_ingreso_trabajo' => $this->tipo_asegurado ? null : $this->instituciones[0]['fecha_ingreso'],
            'puesto_empleado' => $this->tipo_asegurado ? null : $this->instituciones[0]['cargo'],
            'salario' => $this->tipo_asegurado ? null : $this->instituciones[0]['salario'],
            'departamento' => 'LA PAZ',
            'municipio' => 'NUESTRA SEÑORA DE LA PAZ',
            'fecha_inicio' => Carbon::parse($this->fecha_afiliacion_real)->format('Y-m-d'),
            'tipo_asegurado' => 'TRABAJADOR',
            'matricula' => $this->matricula,
            'tipo_documento' => 'CI',
            'numero_documento' => implode('-', array_filter([$this->carnet, $this->complemento])),
            'fecha_nacimiento' => $this->fechaNacimientoReal,
            'genero' => $this->sexo,
            'estado_civil' => $this->id_estado_civil,
            'foto_persona' => $imageBase64 ?? '',
            'id_municipio_nacimiento_persona' => $this->nacimiento_municipio,
            'id_persona' => $this->idPersona,
            'id_tipo_asegurado' => 1,
            'id_corresponde' => $titularReafiliado->id,
            'id_titular' => null,
            'id_institucion' => $this->tipo_asegurado ? null : $this->instituciones[0]['id_institucion'],
            'id_user_created' => $titularReafiliado->id_user_created,
        ];

        //dd($arrayDatosOriginales);

        //$servicioAsussTitular->afiliarTitularAsuss($arrayDatosOriginales);

        //ProcesarAfiliacionTitularAsussJob::dispatch($arrayDatosOriginales);  ---------------------------------------------DESCOMENTAR PARA QUE FUNCIONE EL ASUSS

        //------------------------------------------------------- FIN NUEVA INSERCION DE DATOS ASUSS -------------------------------------------------------  











        // TODO: comentado por errores de afiliacion (bladimir) 24/03/2025
        $insercionGehma = new TitularesGehma2();
        $resputestaReplica = $insercionGehma->insercionTitularGehma2($titularReafiliado->id);
        $this->dispatch('titularReafiliado');
        return redirect()->route('afiliacion.titulares.index');
        //   } catch (\Throwable $e) {
        //       DB::rollBack();
        //       $trace = collect($e->getTrace())
        //           ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));
        //       Log::error("Afiliacion:error al guardar reafiliacion", [
        //           'USUARIO_ID' => auth()->user()->id ?? null,
        //           'ERROR'      => $e->getMessage() ?? '',
        //           'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
        //           'LINEA'      => $trace['line'] ?? $e->getLine(),
        //           'FECHA_HORA' => now(),
        //       ]);
        //       return redirect()->route('afiliacion.titulares.index')->with('evento', 'TitularNoRegistrado');
        //   }


    }

    // mostrar grupo familiar
    public function grupoFamiliar($idTitular)
    {
        if ($idTitular) {
            $grupoFamiliar = Beneficiario::where('id_titular', $idTitular)->where('estado', true)
                ->with([
                    'beneficiarioPersona',
                    'beneficiarioPersona.departamentoPersona',
                    'beneficiarioPersona.estadoCivilPersona',
                    'beneficiarioPersona.tipoAseguradoPersona'
                ])
                ->get();

            //dd($grupoFamiliar->isEmpty());
            if (!$grupoFamiliar->isEmpty()) {
                return $grupoFamiliar;
            } else {
                return [];
            }
        } else {
            return [];
        }
    }

    public function darBajaUsuario($idUsuario)
    {
        $usuario = User::find($idUsuario);
        if ($usuario) {
            $usuario->estado = false;
            $usuario->syncRoles([]);
            $usuario->syncPermissions([]);
            $usuario->id_user_updated = auth()->user()->id;
            $usuario->save();
        }
    }

    #[On('verAportesTitular')]
    public function mostrarAportes($idPersona)
    {
        $currentYear = now()->year;
        $currentMonth = now()->month;
        // Calculamos los valores para los meses y gestión
        $lastMonth = $currentMonth == 1 ? 12 : $currentMonth - 1;
        $secondLastMonth = $currentMonth == 1 ? 11 : ($currentMonth == 2 ? 12 : $currentMonth - 2);
        $lastMonthYear = $currentMonth == 1 ? $currentYear - 1 : $currentYear;
        $secondLastMonthYear = $currentMonth == 1 || $currentMonth == 2 ? $currentYear - 1 : $currentYear;
        $res = AporteInstitucional::join('aportes.mes as m', 'm.id', '=', 'aporte_i.id_mes')
            ->join('aportes.institucion as ins', 'ins.id', '=', 'aporte_i.id_institucion')
            ->join('aportes.clase_aportante as ca', 'ca.id', '=', 'ins.id_clase_aportante')
            ->where('aporte_i.id_persona', $this->id_persona)
            ->where(function ($query) use ($currentYear, $currentMonth, $lastMonth, $secondLastMonth, $lastMonthYear, $secondLastMonthYear) {
                $query
                    ->where(function ($query) use ($currentYear, $currentMonth) {
                        $query->where('aporte_i.gestion', $currentYear)->where('m.numero', $currentMonth);
                    })
                    ->orWhere(function ($query) use ($currentYear, $lastMonth) {
                        $query->where('aporte_i.gestion', $currentYear)->where('m.numero', $lastMonth);
                    })
                    ->orWhere(function ($query) use ($currentYear, $secondLastMonth) {
                        $query->where('aporte_i.gestion', $currentYear)->where('m.numero', $secondLastMonth);
                    })
                    ->orWhere(function ($query) use ($lastMonthYear, $currentMonth) {
                        $query->where('aporte_i.gestion', $lastMonthYear)->where('m.numero', 12)->whereRaw('EXTRACT(month FROM now()) = 1');
                    })
                    ->orWhere(function ($query) use ($secondLastMonthYear, $currentMonth) {
                        $query->where('aporte_i.gestion', $secondLastMonthYear)->where('m.numero', 11)->whereRaw('EXTRACT(month FROM now()) = 2');
                    });
            })
            ->select('aporte_i.id as id', 'aporte_i.id_persona', 'aporte_i.id_institucion', 'aporte_i.gestion', 'aporte_i.id_mes', 'aporte_i.item', 'aporte_i.cargo', 'aporte_i.total_ganado', 'aporte_i.total_aporte', 'aporte_i.tipo_trabajador', 'm.mes', 'ins.nombre as nombre_institucion', 'ca.nombre as clase_aportante')
            ->get();
        return $res;
    }
    //#[On('addInstitucionReaf')]
    public function addInstitucionReaf()
    {
        try {
            $nombre_institucion = Institucion::join('aportes.clase_aportante as cap', 'cap.id', '=', 'aportes.institucion.id_clase_aportante')
                ->select('aportes.institucion.nombre', 'cap.tipo')
                ->where('aportes.institucion.id', $this->institucion)
                ->first();
            if ($this->tipo_rentista == null) {
                if ($nombre_institucion->tipo == 'RENTISTA JUBILADO') {
                    $this->esRentista = true;
                } elseif ($nombre_institucion->tipo == 'RENTISTA POR SORTEO') {
                    $this->esRentista = true;
                }
            }

            if (!empty($this->institucion) && !empty($this->fecha_ingreso)  && !empty($this->cargo) && !empty($this->salario)  && !empty($this->carga_horaria) && !empty($this->titularidad)) {
                $this->instituciones[] = [
                    'id_institucion' => $this->institucion,
                    'institucion' => $nombre_institucion->nombre,
                    'tipo_institucion' => $nombre_institucion->tipo,
                    'codigo_titular_ver' => $this->codigo_titular_ver,
                    'codigo_beneficiario_ver' => $this->codigo_beneficiario_ver,
                    'fecha_ingreso' => $this->fecha_ingreso,
                    'carga_horaria' => $this->carga_horaria,
                    'item' => $this->item,
                    'titularidad' => $this->titularidad,
                    'cargo' => $this->cargo,
                    'salario' => $this->salario,
                ];
                $this->resetearVarInstituciones();
            } else {
                $this->dispatch('errorAddInstNuevo');
            }
        } catch (\Throwable $e) {
            $this->dispatch('errorNuevo');

            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Afiliacion:error en add institucion reafiliacion", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    public function resetearVarInstituciones()
    {
        $this->reset(
            'institucion',
            'codigo_titular_ver',
            'codigo_beneficiario_ver',
            'fecha_ingreso',
            'cargo',
            'salario',
            'item',
            'carga_horaria',
            'titularidad'
        );
    }

    // esto es para ver los aportes en funcion
    public function verAportesIns()
    {
        $this->dispatch('modalVerAportesIns');
    }

    public function verAportesVol()
    {
        $this->dispatch('modalVerAportesVol');
    }

    public function cancelarReafiliacion()
    {
        $this->dispatch('titularCancelado');
        return redirect()->route('afiliacion.titulares.index');
    }
}
