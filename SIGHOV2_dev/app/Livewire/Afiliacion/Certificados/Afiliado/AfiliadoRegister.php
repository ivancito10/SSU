<?php

namespace App\Livewire\Afiliacion\Certificados\Afiliado;

use Livewire\Component;
use App\Models\User;
use App\Models\Administracion\Persona;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\CertificadoAfiliacion;
use App\Models\Afiliacion\CodigoInstitucionSsu;
use App\Models\Afiliacion\Estudiante;
use App\Models\Administracion\Departamento;
use App\Models\Administracion\TipoAsegurado;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Aportes\Institucion;
use App\Models\Aportes\ClaseAportante;
use App\Services\GrupoFamiliarPersona;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class AfiliadoRegister extends Component
{
    public $idUsuario;
    public $nombre, $p_apellido, $s_apellido, $matricula, $foto, $fotoTit,
        $m_cite, $anio, $motivo, $fecha_caducidad = "", $fecha_emision, $ci,
        $datosFamiliaAfiliado = [];
    protected $listeners = ['guardarCertificado', 'f_atras'];
    public $idPersona;
    public $codigoTitular, $codigoBeneficiario, $tipo_titular;
    public $nombreInstitucionTit, $cargoTit, $salarioTit, $fechaIngresoTit, $clase_aportante;
    public $tipo, $existe_datos, $idInsti;
    public $texto = "", $if = 0;
    public $nombre_tit, $paterno_tit, $materno_tit, $matricula_tit, $foto_tit,
        $codigoBeneficiario_m, $codigoTitular_m;
    public $nombreCompleto, $expedido, $extranjero, $aseguradoTipo, $permantente;
    public $nombreCompletoTit, $expedidoTit, $extranjeroTit,  $permantenteTit, $ci_tit, $aseguradoTipoTit;
    public $propio, $tieneGrupoFamiliar = false, $datosPropios;
    public function render()
    {
        return view('livewire.Afiliacion.certificados.afiliado.afiliado-register');
    }

    public function mount()
    {
        $this->mostrargrupo();
        $this->fecha_emision = Carbon::now()->format('Y-m-d');
        $this->funcion_fecha();
    }



    public function mostrargrupo()
    {
        // dd('asdasd');
        $id_usuario = auth()->user()->id;

        $usuarioActual = User::find($id_usuario);
        $id_persona = $usuarioActual->id_persona;
        // dd($id_persona);
        // Validar que la persona sea titular para mostrar su grupo familiar
        $titular = Titular::where('id_persona', $id_persona)
            ->where('estado_vigencia', '=', 'VIGENTE')->first();
        $beneficiario = Beneficiario::where('id_persona', $id_persona)
            ->where('vigencia', '=', 'VIGENTE')->first();
        $estudiante = Estudiante::where('id_persona', $id_persona)
            ->where('estado_vigencia', true)->first();
        //dd($titular, $beneficiario, $estudiante);

        if ($titular) {
            $this->tipo = 'TITULAR';
            $familiaAfiliado = new GrupoFamiliarPersona();
            $this->datosFamiliaAfiliado = $familiaAfiliado->encontrarPersona($id_persona);
            // dd($this->datosFamiliaAfiliado);
            $cantidad = count($this->datosFamiliaAfiliado);
            if ($cantidad > 1) {
                $this->tieneGrupoFamiliar = true;
            }
            // dd($familiaAfiliado);
        } elseif ($beneficiario) {
            $this->tipo = 'BENEFICIARIO';
            //dd($this->tipo);
            $personaBen = Persona::where('id', $id_persona)->get();
            $this->datosFamiliaAfiliado = $personaBen;
        } elseif ($estudiante) {
            $this->tipo = 'ESTUDIANTE';
            $personaEstudiante = Persona::where('id', $id_persona)->get();
            $this->datosFamiliaAfiliado = $personaEstudiante;
            //dd($this->datosFamiliaAfiliado);
        } else {

            $this->datosFamiliaAfiliado = [];
        }
        if (!empty($this->datosFamiliaAfiliado)) {
            //dd('dfsd');
            $this->idPersona = $this->datosFamiliaAfiliado[0]['id'];
            $this->mostrarPersona();
        }
        //dd($this->datosFamiliaAfiliado);

        // mostrar el nro cite con año actual
        $this->anio = Carbon::now()->format('Y');
        $this->m_cite = $this->funcion_cite() . ' / ' . $this->anio;
    }

    public function mostrarPersona()
    {
        //dd($this->idPersona);
        if ($this->idPersona) {

            $personaActual = Persona::find($this->idPersona);
            $complemento = $personaActual->complemento;
            if ($complemento != null) {
                $this->ci = $personaActual->ci . ' - ' . $personaActual->complemento;
            } else {
                $this->ci = $personaActual->ci;
            }
            $this->nombre = $personaActual->nombres;
            $this->p_apellido = $personaActual->p_apellido;
            $this->s_apellido = $personaActual->s_apellido;

            $this->nombreCompleto = $personaActual->nombres . ' ' . $personaActual->p_apellido . ' ' . $personaActual->s_apellido;
            $this->matricula = $personaActual->matricula_seguro;
            $idExpedido = $personaActual->id_dept_exp;

            if ($idExpedido) {
                $expedidoDep = Departamento::find($idExpedido);
                if ($expedidoDep->id != 10) {
                    $this->expedido = $expedidoDep->sigla;
                } else {
                    $this->expedido = '';
                }
            } else {
                $this->expedido = '';
            }

            $this->extranjero = $personaActual->es_extranjero ? 'EXTRANJERO' : 'NACIONAL';
            $idTipoAsegurado = $personaActual->id_tipo_asegurado;
            $asegurado_tipo = TipoAsegurado::find($idTipoAsegurado);
            $this->aseguradoTipo = $asegurado_tipo->tipo_asegurado;

            $titular = Titular::where('id_persona', $this->idPersona)
                ->where('estado_vigencia', 'VIGENTE')
                ->where('estado', true)->first();
            $beneficiario = Beneficiario::where('id_persona', $this->idPersona)
                ->where('vigencia', 'VIGENTE')
                ->where('estado', true)
                ->first();
            if ($titular != null) {
                // dd('es titula');
                $this->texto = 'TITULAR';
                $this->permantente = $titular->permanente ? 'PERMANENTE' : 'EVENTUAL';
                $this->fotoTit = $this->obtenerImagenBase64($titular->foto_nombre);
                $this->if = 1;

                $titularInstitucion = TitularInstitucion::where('id_titular', $titular->id)
                    ->orderby('id', 'asc')
                    ->where('estado', true)
                    //->where('tipo_institucion', 'PATRONAL')
                    ->first();
                // dd($titularInstitucion);
                if ($titularInstitucion) {
                    $this->tipo_titular = 'INSTITUCIONAL';
                    $idInstiTit = $titularInstitucion->id_institucion;
                    // dd( $idInstiTit);
                    $institucionTit = Institucion::find($idInstiTit);
                    if ($institucionTit) {
                        $codigo = CodigoInstitucionSsu::where('id_institucion', $institucionTit->id)->first();
                        if ($codigo) {
                            $this->codigoTitular_m = $codigo->codigo . $codigo->tipo_titular;
                        } else {
                            $this->codigoTitular = '';
                            $this->codigoBeneficiario = '';
                        }
                    }
                } else {

                    $this->codigoTitular = '';
                    $this->codigoBeneficiario = '';
                    $this->tipo_titular = 'VOLUNTARIO';
                }
                $this->mostrarInstitucion($titular->id);
            } elseif ($beneficiario != null) {
                // dd('entra qui ');
                $this->texto = 'BENEFICIARIO';
                // dd($this->texto);
                $this->mostrarTitular($beneficiario->id_titular);

                $this->foto = $this->obtenerImagenBase64($beneficiario->foto_nombre);
                $this->if = 2; // cuando es para un beneficiaro

                $titularInstitucion = TitularInstitucion::where('id_titular', $beneficiario->id_titular)
                    //->where('tipo_institucion', 'PATRONAL')
                    ->orderby('id', 'asc')
                    ->where('estado', true)
                    ->first();
                if ($titularInstitucion) {
                    $this->tipo_titular = 'INSTITUCIONAL';
                    $idInstiTit = $titularInstitucion->id_institucion;

                    $institucionTit = Institucion::find($idInstiTit);
                    if ($institucionTit) {
                        $this->nombreInstitucionTit = $institucionTit->nombre;
                        $codigo = CodigoInstitucionSsu::where('id_institucion', $institucionTit->id)->first();
                        if ($codigo) {
                            $this->codigoBeneficiario_m = $codigo->codigo . $codigo->tipo_beneficiario;
                        } else {
                            $this->codigoTitular = '';
                            $this->codigoBeneficiario = '';
                        }
                    }
                } else {

                    $this->codigoTitular = '';
                    $this->codigoBeneficiario = '';
                    $this->tipo_titular = 'VOLUNTARIO';
                }
            }
            //dd($this->texto);
        }
        //$this->skipRender();
    }

    public function mostrarInstitucion($id_tit)
    {

        $titularInstitucion = TitularInstitucion::where('id_titular', $id_tit)
            // ->where('tipo_institucion', 'PATRONAL')
            ->orderby('id', 'asc')
            ->where('estado', true)
            ->first();
        if ($titularInstitucion) {
            $this->tipo_titular = 'INSTITUCIONAL';
            $this->cargoTit = $titularInstitucion->cargo_actual;
            $this->salarioTit = $titularInstitucion->salario;
            //$this->fechaIngresoTit=$titularInstitucion->fecha_ingreso;
            $this->fechaIngresoTit = Carbon::parse($titularInstitucion->fecha_ingreso)->format('d/m/Y');
            $idInstiTit = $titularInstitucion->id_institucion;

            $institucionTit = Institucion::find($idInstiTit);
            $id_clase_aportante = $institucionTit->id_clase_aportante;
            if ($institucionTit) {
                $this->idInsti = $institucionTit->id;
                $this->nombreInstitucionTit = $institucionTit->nombre;
                //dd($this->nombreInstitucionTit);
                $clase_aportante = ClaseAportante::find($id_clase_aportante);
                $this->clase_aportante = $clase_aportante->nombre;


                $codigo = CodigoInstitucionSsu::where('id_institucion', $this->idInsti)->first();
                if ($codigo) {
                    $this->codigoTitular = $codigo->codigo . $codigo->tipo_titular;
                    $this->codigoBeneficiario = $codigo->codigo . $codigo->tipo_beneficiario;
                } else {
                    $this->codigoTitular = '';
                    $this->codigoBeneficiario = '';
                }
            }
        } else {
            $this->cargoTit = '';
            $this->salarioTit = '';
            $this->nombreInstitucionTit = '';
            $this->codigoTitular = '';
            $this->codigoBeneficiario = '';
            $this->tipo_titular = 'VOLUNTARIO';
        }
    }
    public $foto_titular_ben;

    public function mostrarTitular($id_tit)
    {
        if ($id_tit) {
            $titular = Titular::find($id_tit);
            $this->foto_titular_ben = $this->obtenerImagenBase64($titular->foto_nombre);


            $persona = Persona::find($titular->id_persona);

            $this->nombreCompletoTit = $persona->nombres . ' ' . $persona->p_apellido . ' ' . $persona->s_apellido;
            $this->matricula_tit = $persona->matricula_seguro;
            $complemento = $persona->complemento;
            if ($complemento != null) {
                $this->ci_tit = $persona->ci . ' - ' . $persona->complemento;
            } else {
                $this->ci_tit = $persona->ci;
            }
            $idExpedido = $persona->id_dept_exp;
            if ($idExpedido) {
                $expedidoDep = Departamento::find($idExpedido);
                $this->expedidoTit = $expedidoDep->sigla;
            } else {
                $this->expedidoTit = '';
            }

            $this->extranjeroTit = $persona->es_extranjero ? 'EXTRANJERO' : 'NACIONAL';
            $idTipoAsegurado = $persona->id_tipo_asegurado;
            $asegurado_tipo = TipoAsegurado::find($idTipoAsegurado);
            $this->aseguradoTipoTit = $asegurado_tipo->tipo_asegurado;

            $titularInstitucion = TitularInstitucion::where('id_titular', $id_tit)
                // ->where('tipo_institucion', 'PATRONAL')
                ->orderBy('id', 'ASC')
                ->where('estado', true)
                ->first();
            //  dd($titularInstitucion);
            if ($titularInstitucion) {
                //dd('sdfd');
                $this->tipo_titular = 'INSTITUCIONAL';
                $this->cargoTit = $titularInstitucion->cargo_actual;
                $this->salarioTit = $titularInstitucion->salario;
                $idInstiTit = $titularInstitucion->id_institucion;
                // dd($idInstiTit);
                $institucionTit = Institucion::find($idInstiTit);
                // dd($institucionTit);
                if ($institucionTit) {
                    $this->idInsti = $institucionTit->id;
                    $this->nombreInstitucionTit = $institucionTit->nombre;
                    //dd($this->nombreInstitucionTit);
                    // dd($this->idInsti);
                    $codigo = CodigoInstitucionSsu::where('id_institucion', $this->idInsti)->first();
                    if ($codigo) {
                        $this->codigoTitular = $codigo->codigo . $codigo->tipo_titular;
                        $this->codigoBeneficiario = $codigo->codigo . $codigo->tipo_beneficiario;
                    } else {
                        $this->codigoTitular = '';
                        $this->codigoBeneficiario = '';
                    }
                }
            } else {
                $this->cargoTit = '';
                $this->salarioTit = '';
                $this->nombreInstitucionTit = '';
                $this->codigoTitular = '';
                $this->codigoBeneficiario = '';
                $this->tipo_titular = 'VOLUNTARIO';
            }
        }
    }

    private function obtenerImagenBase64($filename)
    {
        try {
            if ($filename) {
                $path = 'Afiliacion/FOTOS/' . $filename;
                $imageData = Storage::disk('ftp')->get($path);
                $datoFoto = base64_encode($imageData);
                return $datoFoto;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function funcion_cite()
    {
        $ultimoAnio = Carbon::now()->year;
        //$obtener_nro = CertificadoAfiliacion::orderBy('nro_cite', 'desc')->first();
        // dd($obtener_nro);
        //dd(Carbon::parse($obtener_nro->created_at)->year, $ultimoAnio);
        /* if ($obtener_nro == null || Carbon::parse($obtener_nro->created_at)->year != $ultimoAnio) {
            return 1;
        } else {
            return $obtener_nro->nro_cite + 1;
        } */

        $ultimo = CertificadoAfiliacion::whereYear('created_at', $ultimoAnio)
            ->orderBy('nro_cite', 'desc')
            ->first();
        // dd($ultimo);
        if (!$ultimo) {
            return 1;
        } else {
            return $ultimo->nro_cite + 1;
        }
    }

    public function funcion_fecha()
    {
        $this->fecha_caducidad = Carbon::parse($this->fecha_emision)->addDays(15)->format('Y-m-d');
    }

    public function guardarCertificado()
    {

        try {
            $persona = Persona::with('historialAseguradoPersona')->find($this->idPersona);
            $historialAsegurado = $persona->historialAseguradoPersona()->latest()->first();
            // dd($historialAsegurado->estado_vigencia);
            if ($historialAsegurado->estado_vigencia != 'VIGENTE') {
                $this->dispatch('afiliacionNoVigente');
                return;
            }
            if ($this->nombre == null || $this->p_apellido == null  || $this->fecha_emision == null) {
                $this->dispatch('no_registra');
            } else {
                $cod1 =  $this->funcion_cite() . $this->ci;
                $cod2 = Crypt::encrypt($cod1);
                // Truncar a un rango de 15 caracteres
                $codigo_seguridad = substr($cod2, 0, 15);


                // Aqui validacion de si es titular o beneficiario
                $titular = Titular::where('id_persona', $this->idPersona)->first();
                $beneficiario = Beneficiario::where('id_persona', $this->idPersona)->first();
                // Validar si es titular 
                if ($titular != null) {

                    $certificado = CertificadoAfiliacion::create([
                        'nro_cite' => $this->funcion_cite(),
                        'gestion' => $this->anio,
                        'fecha_emision' => $this->fecha_emision,
                        'fecha_caducidad' => $this->fecha_caducidad,
                        'codigo_seguridad' => $codigo_seguridad,
                        'cod_certificado' => 'CERT.SSU-LP/AFL/' . $this->funcion_cite() . '/' . $this->anio,
                        'id_persona' => $this->idPersona,
                        'motivo' => $this->motivo,
                        'estado' => true,
                        'id_user_created' => auth()->user()->id,
                    ]);
                    $id_certificado = $certificado->id;
                    if ($certificado) {
                        $this->js('window.open("/afiliacion/certificados/titular_download/' . $id_certificado . '", "_blank")');
                        return redirect()->route('afiliacion.certificados.afiliado.index')->with('evento', 'creado');
                    }
                } elseif ($beneficiario != null) {
                    // Validar si es beneficiario

                    $certificado = CertificadoAfiliacion::create([
                        'nro_cite' => $this->funcion_cite(),
                        'gestion' => $this->anio,
                        'fecha_emision' => $this->fecha_emision,
                        'fecha_caducidad' => $this->fecha_caducidad,
                        'codigo_seguridad' => $codigo_seguridad,
                        'cod_certificado' => 'CERT.SSU-LP/AFL/' . $this->funcion_cite() . '/' . $this->anio,
                        'id_persona' => $this->idPersona,
                        'motivo' => $this->motivo,
                        'estado' => true,
                        'id_user_created' => auth()->user()->id,
                    ]);

                    $id_certificado = $certificado->id;

                    if ($certificado) {
                        $this->js('window.open("/afiliacion/certificados/beneficiario_download/' . $id_certificado . '", "_blank")');
                        return redirect()->route('afiliacion.certificados.afiliado.index')->with('evento', 'creado');
                    }
                } else {
                    // cuando la persona no pertenece
                    $this->dispatch('no_registra');
                }
            }
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Afiliacion:error al guardar certificado", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    public function f_atras()
    {
        return redirect()->route('afiliacion.certificados.afiliado.index');
    }
}
