<?php

namespace App\Livewire\Afiliacion\Ssue;

use App\Models\Afiliacion\Estudiante;
use App\Models\Administracion\Persona;
use App\Models\Administracion\TipoAsegurado;
use App\Models\Administracion\Departamento;
use App\Models\Administracion\Provincia;
use App\Models\Administracion\Municipio;
use App\Models\Administracion\Zona;
use App\Models\Administracion\EstadoCivil;
use App\Models\Afiliacion\Facultad;
use App\Models\Afiliacion\Carrera;
use App\Models\Afiliacion\Beneficiario;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

use Livewire\Component;

class SsueNoAutorizado extends Component
{
    //DATOS PARA ESTUDIANTE
    public $foto;
    public $tipo;
    public $fotito;
    //datos personales
    #[Locked]
    public $id_persona;
    public $nombreCompleto, $nombres, $p_apellido, $s_apellido, $ci, $complemento, $fecha_nacimiento, $fecha_nacimiento_var, $edad, $sexo;
    public $estado_civil, $dept_exp, $tipo_asegurado;
    public $matricula;
    //datos referenciales
    #[Locked]
    public $pdf_fotocopia;
    #[Locked]
    public $id_estudiante;
    public $estado_vigencia;
    public $estado_validar;
    public $telefono, $correo_institucional, $nombre_referencia, $telefono_referencia;
    public $alergias;
    public $observacion;
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
    public $idEst;

    // DATOS PARA BENEFICIARIO
    public $id_beneficiario;
    public $nombreBen;


    public function render()
    {
        return view('livewire.Afiliacion.ssue.ssue-no-autorizado');
    }

    public function mount()
    {
        $this->mostrarDatos($this->id_persona);
    }

    public function mostrarDatos($id_persona)
    {
        $userActual = User::join('administracion.persona as p', 'p.id', '=', 'administracion.users.id_persona')
            ->where('administracion.users.id', '=', auth()->user()->id)
            ->first();

        $this->id_persona = $userActual->id_persona;
        $personaActual = Persona::find($this->id_persona);
        $this->nombres = $personaActual->nombres;
        $this->p_apellido = $personaActual->p_apellido;
        $this->s_apellido = $personaActual->s_apellido;
        $this->nombreCompleto = $personaActual->nombres . ' ' . $personaActual->p_apellido . ' ' . $personaActual->s_apellido;
        $complemento = $personaActual->complemento;
        if ($complemento) {
            $this->ci = $personaActual->ci . ' - ' . $personaActual->complemento;
        } else {
            $this->ci = $personaActual->ci;
        }
        $id_dept_exp = $personaActual->id_dept_exp;
        $deptExpedido = Departamento::find($id_dept_exp);
        $this->dept_exp = $deptExpedido->sigla ?? ' ';
        $id_tipo_asegurado = $personaActual->id_tipo_asegurado;
        $tipoAsegurado = TipoAsegurado::find($id_tipo_asegurado);
        $this->tipo_asegurado = $tipoAsegurado->tipo_asegurado;
        $this->sexo = $this->setSexoTexto($personaActual->sexo);
        $this->fecha_nacimiento = $personaActual->fecha_nacimiento;
        $this->fecha_nacimiento_var = Carbon::parse($this->fecha_nacimiento)->Format('d /m/ Y ');
        $this->edad = Carbon::parse($personaActual->fecha_nacimiento)->age;
        $this->complemento = $personaActual->complemento;
        $this->matricula = $personaActual->matricula_seguro;
        $id_estado_civil = $personaActual->id_estado_civil;
        $estadoCivil = EstadoCivil::find($id_estado_civil);
        $this->estado_civil = $estadoCivil->nombre;

        $estudiante = Estudiante::where('id_persona', $this->id_persona)
            ->where('estado', true)
            //->where('estado_vigencia', true)
            ->first();
        //dd($estudiante);
        if ($estudiante) {
            $this->tipo = 'ESTUDIANTE';
            $this->fecha_solicitud = $estudiante->fecha_solicitud_afiliacion;
            // Paso 1. obtener el filename de la foto
            $this->foto = $this->obtenerImagenBase64($estudiante->foto_nombre);
        } else {
            $beneficiario = Beneficiario::where('id_persona', $this->id_persona)
                ->where('estado', true)
                ->where('vigencia', '=', 'VIGENTE')
                ->first();

            if ($beneficiario) {
                $this->tipo = 'BENEFICIARIO';
                $this->fecha_afiliacion = Carbon::parse($this->fecha_afiliacion)->Format('d /m/ Y ');
                // Paso 1. obtener el filename de la foto
                $this->foto = $this->obtenerImagenBase64Beneficiario($beneficiario->foto_nombre);
            }
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

    private function obtenerImagenBase64Beneficiario($filename)
    {
        try {
            if ($filename) {
                $path = 'Afiliacion/FOTOS/' . $filename;
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

    public function seguimiento()
    {
        return redirect()->route('afiliacion.ssue.seguimiento');
    }
    public function atras()
    {
        return redirect()->route('dashboard');
    }
}
