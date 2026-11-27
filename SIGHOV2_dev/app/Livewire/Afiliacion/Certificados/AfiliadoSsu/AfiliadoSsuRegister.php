<?php

namespace App\Livewire\Afiliacion\Certificados\AfiliadoSsu;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Administracion\Persona;
use App\Models\Afiliacion\CertificadoAfiliacion;
use App\Models\Afiliacion\Titular;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Administracion\DocumentoPDF;
use App\Models\Administracion\TipoAsegurado;
use Illuminate\Support\Facades\Log;

class AfiliadoSsuRegister extends Component
{
    public $idPersona;
    public $ci, $matricula;
    public $nombres;
    public $p_apellido;
    public $s_apellido;
    public $fecha_nacimiento, $sexo;
    public $fecha_hoy;
    public $numero_cite;
    public $codigo_seguridad = "";
    public $motivo;
    public $estado;
    public $tipo_asegurado;

    public $nombreImagen;
    public $imagenPath;
    public $contenidoImagen;
    public $foto;
    public $sexoTexto;
    protected $listeners = ['registerCertificado', 'guardarCertificado'];

    public function render()
    {
        return view('livewire.Afiliacion.certificados.afiliado-ssu.afiliado-ssu-register');
    }

    #[On('registerCertificado')]
    public function registerCertificado($data)
    {
        $this->idPersona = $data;

        if ($this->idPersona) {
            $persona = Persona::with('historialAseguradoPersona')->find($this->idPersona);
            $historialAsegurado = $persona->historialAseguradoPersona()->orderBy('id', 'desc')->first();
           // dd($historialAsegurado->estado_vigencia);
            if ($historialAsegurado->estado_vigencia != 'VIGENTE') {
                $this->dispatch('afiliacionNoVigente');
                return;
            }
            $this->ci = $persona->ci;
            $this->nombres = $persona->nombres . ' ' . $persona->p_apellido . ' ' . $persona->s_apellido;
            $this->p_apellido = $persona->p_apellido;
            $this->s_apellido = $persona->s_apellido;
            $this->fecha_nacimiento = Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y');
            $this->matricula = $persona->matricula_seguro;
            $this->sexo = $persona->sexo;

            if ($persona->sexo == 'F') {
                $this->sexoTexto = 'FEMENINO';
            } elseif ($persona->sexo == 'M') {
                $this->sexoTexto =  'MASCULINO';
            } else {
                return 'NO ESPECIFICADO';
            }
            // dd($this)
            $id_tipo_asegurado = $persona->id_tipo_asegurado;
            $tipoAsegurado = TipoAsegurado::find($id_tipo_asegurado);
            $this->tipo_asegurado = $tipoAsegurado->tipo_asegurado;
            $this->sexo = $persona->sexo;

            //   $titular = Titular::where('id_persona', $this->idPersona)->first();
            //   $this->nombreImagen = $titular->foto_nombre;

            //   $this->imagenPath = 'Afiliacion/FOTOS/' . $this->nombreImagen;
            //   $disk = Storage::disk('ftp')->get($this->imagenPath);

            //   $this->foto = base64_encode($disk);

            /* if ($disk->exists($this->imagenPath)) {
                $this->contenidoImagen = $disk->url($this->imagenPath);
                $this->foto = base64_encode($this->contenidoImagen);
            } else {
                throw new \Exception("El archivo no existe en el servidor FTP");
            }  */
        }
        $this->fecha_hoy = Carbon::now();

        $this->dispatch('abrirModalRegister');
        $this->skipRender();
    }


    public function funcion_cite()
    {
        /*  $currentYear = Carbon::now()->year;
        $obtener_nro = CertificadoAfiliacion::orderBy('nro_cite', 'desc')->first();
        if ($obtener_nro == null || Carbon::parse($obtener_nro->created_at)->year != $currentYear) {
            return 1;
        } else {
            return $obtener_nro->nro_cite + 1;
        } */

        $ultimoAnio = Carbon::now()->year;
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
    public function guardarCertificado()
    {
        $cod1 =  $this->funcion_cite() . $this->ci;
        $cod2 = Crypt::encrypt($cod1);
        // Truncar a un rango de 15 caracteres
        $codigo_seguridad = substr($cod2, 0, 15);

        $this->numero_cite = $this->obtenerCite();

        try {
            $nuevoCertificado = CertificadoAfiliacion::create([
                'nro_cite' =>  $this->funcion_cite(),
                'gestion' => $this->fecha_hoy->year,
                'fecha_emision' => $this->fecha_hoy->format('Y/m/d'),
                'fecha_caducidad' => $this->fecha_hoy->addDay(15)->format('Y/m/d'),
                'codigo_seguridad' => $codigo_seguridad,
                'cod_certificado' => 'CERT.SSU-LP/AFL/' . $this->funcion_cite() . '/' . $this->fecha_hoy->year,
                'id_persona' => $this->idPersona,
                'motivo' => $this->motivo,
                'estado' => true,
                'id_user_created' => auth()->user()->id,
            ]);
            //guardar para verificar documento
            $fechaVigenciaInicio = now();
            $fechaVigenciaFin = $fechaVigenciaInicio->copy()->addDays(15);

            $documentoPdf = [
                'id_tipo_documento'     => 2, // 2 = AFILIACION SSU
                'codigo_seguridad'      => $codigo_seguridad,
                'modulo'                => 'AFILIACION', // O el módulo correspondiente
                'fecha_generado'        => now(),
                'cantidad_paginas'      => 1, //siempre sera 1 este certificado 
                'pertenece_a'           => $this->idPersona, // ID del certificado o dato relevante
                'fecha_vigencia_inicio' => $fechaVigenciaInicio, // O la fecha que corresponda
                'fecha_vigencia_fin'    => $fechaVigenciaFin,  // Si aplica
                //'fecha_vigencia_fin'    => $fechaVigenciaFin,
                'indefinido'            => false,  // true o false según corresponda
                'id_usuario_created'    => auth()->user()->id,
            ];
            //dd($documentoPdf);
            $pdfCreado = DocumentoPDF::create($documentoPdf);

            if ($nuevoCertificado) {
                $nuevoCertificadoId = $nuevoCertificado->id;
                $this->dispatch('pg:eventRefresh-CertificadoAfiliacion');
                $this->dispatch('certificadoCreado');
                $this->js('window.open("/afiliacion/certificados/afiliacionssu/' . $nuevoCertificadoId . '", "_blank")');
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
            dd('no se registro');
        }
        $this->motivo = "";
    }

    public function obtenerCite()
    {
        $ultimoCertificado = CertificadoAfiliacion::orderBy('created_at', 'desc')->first();

        // Si no hay certificados registrados, iniciar en 1
        if (!$ultimoCertificado) {
            return 1;
        }

        // Obtener la gestion actual
        $anioActual = Carbon::now()->year;

        // Obtener la gestion del ultimo certificado registrado
        $anioUltimoCertificado = Carbon::parse($ultimoCertificado->created_at)->year;

        // Si el ultimo certificado es de otra gestion, reiniciar el contador a 1

        if ($anioUltimoCertificado < $anioActual) {
            return 1;
        }

        // Obtener el ultimo numero de cite registrado y sumar 1
        $ultimoNumeroCite = $ultimoCertificado->nro_cite;

        return $ultimoNumeroCite + 1;
    }
}
