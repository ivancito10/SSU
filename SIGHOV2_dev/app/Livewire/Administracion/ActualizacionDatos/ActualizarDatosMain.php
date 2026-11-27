<?php

namespace App\Livewire\Administracion\ActualizacionDatos;

use App\Models\Administracion\Persona;
use App\Models\Administracion\Municipio;
use App\Models\Administracion\Provincia;
use App\Models\Administracion\Departamento;
use App\Models\Administracion\EstadoCivil;
use App\Models\User;

use Illuminate\Support\Carbon;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Livewire\Component;

class ActualizarDatosMain extends Component
{
    public $estadoCivilSelect, $departamentos;
    public $ci_buscado, $comp_buscado, $persona_existe, $multiples_resultados = false;
    public $datos_persona;
    #[Validate]
    public $nombres, $ci, $fecha_nacimiento, $observaciones;

    public $id_persona, $complemento, $p_apellido, $s_apellido,  $sexo_lit, $es_extranjero, $sexo, $id_dept_exp;
    public  $matricula_seguro, $id_estado_civil, $id_nacimiento_municipio, $grupo_sanguineo, $alergias;
    public $provincias, $municipios, $id_provincia, $id_departamento;
    public $requiere_validacion = false;

    public function render()
    {
        return view('livewire.Administracion.actualizacion-datos.actualizar-datos-main');
    }

    public function mount()
    {

        $this->estadoCivilSelect = EstadoCivil::all();
        $this->departamentos = Departamento::get();
        $this->provincias = Provincia::all();
        $this->municipios = Municipio::all();
    }

    public function buscarPersona()
    {
        $this->resetValidation();
        $this->resetearVariablesBoleanas();
        $this->resetearVariablesLocales();

        $carnet = trim($this->ci_buscado);
        //($carnet);
        // $carnet = $carnet === '' ? null : mb_strtoupper(preg_replace('/\s+/', '', $carnet));
        $comp = trim($this->comp_buscado);
        // $comp = $comp === '' ? null : mb_strtoupper(preg_replace('/\s+/', '', $comp));
        if (($carnet === null || $carnet === '')  && ($comp === null || $comp === '')) {
            // dd('nerte');
            $this->dispatch('porfavorIngreseDAtos');
            //dd('asdas');
            return;
        }


        $this->datos_persona = Persona::query()
            ->when($carnet, function ($query) use ($carnet) {
                return $query->where('ci', 'LIKE', '%' . $carnet . '%');
            })
            ->when($comp, function ($query) use ($comp) {
                return $query->where('complemento', 'LIKE', '%' . $comp . '%');
            })
            ->with(['tipoAseguradoPersona'])
            ->get();


        if (count($this->datos_persona) == 1) {

            $this->persona_existe = true;
            $this->mostrarDatosPersonales($this->datos_persona->first()->id);
        } elseif (count($this->datos_persona) > 1) {
            $this->multiples_resultados = true;
            $this->persona_existe = false;
        } else {
            $this->persona_existe = false;
            $this->dispatch('personaNoExiste');
        }
    }
    public function seleccionarPersona($id_persona)
    {
        $this->persona_existe = true;
        $this->multiples_resultados = false;
        $this->mostrarDatosPersonales($id_persona);
        $this->restearDAtosBusqueda();
    }

    public function mostrarDatosPersonales($id_persona)
    {
        $persona = Persona::with('departamentoPersona', 'estadoCivilPersona', 'municipioPersona', 'tipoAseguradoPersona')
            ->find($id_persona);
        $this->id_persona = $persona->id;
        $this->nombres = $persona->nombres;
        $this->p_apellido = $persona->p_apellido;
        $this->s_apellido = $persona->s_apellido;
        $this->ci = $persona->ci;
        $this->complemento = $persona->complemento;

        $this->es_extranjero = (int) ($persona->es_extranjero ?? 0);
        $this->id_dept_exp = $persona->id_dept_exp;

        $this->sexo_lit = $persona->sexo;
        // dd($this->sexo_lit);
        if ($this->sexo_lit != null) {

            if ($this->sexo_lit == 'F') {
                $this->sexo = true;
            } elseif ($this->sexo_lit == 'M') {
                // dd('sda');
                $this->sexo = false;
            }
        }
        // dd($this->sexo_lit, $this->sexo);
        $this->fecha_nacimiento = $persona->fecha_nacimiento;
        $this->matricula_seguro = $persona->matricula_seguro;
        $this->id_estado_civil = $persona->id_estado_civil;
        //dd($this->id_nacimiento_municipio);
        $this->id_nacimiento_municipio = $persona->id_nacimiento_municipio;
        // dd($this->id_nacimiento_municipio);

        if ($this->id_nacimiento_municipio) {
            $municipio_nacimiento = Municipio::find($this->id_nacimiento_municipio);
            $this->id_provincia = $municipio_nacimiento->id_provincia;
            // dd($this->id_provincia);
            if ($this->id_provincia) {
                $provincia_nacimiento = Provincia::find($this->id_provincia);
                //dd($provincia_nacimiento);
                $this->id_departamento = $provincia_nacimiento->id_departamento;
                $departamento_nacimiento = Departamento::find($this->id_departamento);
                //dd( $departamento_nacimiento);
                //$this->id_departamento = $departamento_nacimiento->id_departamento;
            }
        }
        $this->grupo_sanguineo = $persona->grupo_sanguineo;
        $this->alergias = $persona->alergias;

        //dd('aqui llegue', $persona);
    }

    public function ObtenerProvincia()
    {
        $this->id_provincia = null;
        $this->id_nacimiento_municipio = null;
        if (empty($this->id_departamento)) {
            $this->provincias = [];
            $this->requiere_validacion = false;
            return;
        }

        $this->provincias = Provincia::where('id_departamento', '=', $this->id_departamento)->get();
        $this->requiere_validacion = true;
    }
    public function ObtenerMunicipio()
    {
        //dd('sdsa');
        $this->id_nacimiento_municipio = null;
        if (empty($this->id_provincia)) {
            $this->municipios = [];
            $this->requiere_validacion = false;
            return;
        }
        $this->municipios = Municipio::where('id_provincia', '=', $this->id_provincia)->get();
        $this->requiere_validacion = true;
        $this->resetValidation('id_nacimiento_municipio');
    }

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
        //dd($sexoEsMasculino, $sexoEsFemenino);
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
            // dd('entra aqui');
            $matricula .= str_pad($mesNacimiento, 2, '0', STR_PAD_LEFT);
        } elseif ($sexoEsFemenino) {
            // dd(' o aqui');
            $matricula .= str_pad(50 + $mesNacimiento, 2, '0', STR_PAD_LEFT);
        }
        $matricula .= $diaNacimiento;
        $matricula .= $apellido1;
        $matricula .= $apellido2;
        $matricula .= $nombre1;

        $this->matricula_seguro = $matricula;
        //$this->skipRender();
    }

    public function restearDAtosBusqueda()
    {
        $this->reset('ci_buscado', 'comp_buscado');
    }
    public function resetearVariablesBoleanas()
    {
        $this->reset('persona_existe', 'multiples_resultados');
    }
    public function resetearVariablesLocales()
    {
        $this->reset(
            'nombres',
            'p_apellido',
            's_apellido',
            'ci',
            'complemento',
            'es_extranjero',
            'id_dept_exp',
            'sexo',
            'fecha_nacimiento',
            'matricula_seguro',
            'id_estado_civil',
            'id_departamento',
            'id_provincia',
            'id_nacimiento_municipio',
            'grupo_sanguineo',
            'alergias',
            'observaciones',
            'requiere_validacion'
        );
    }

    public function rules()
    {
        return [
            'nombres' => 'required',
            'ci' => 'required',
            'observaciones' => 'required|min:10',
            // 'fecha_nacimiento' => 'required',
            // 'id_departamento' => 'required_if:es_extranjero,0',
            // 'id_provincia' => 'required_if:es_extranjero,0',
            'id_provincia' => 'required_if:requiere_validacion,true',
            'id_nacimiento_municipio' => 'required_if:requiere_validacion,true'
        ];
    }

    #[On('actualizarDatos')]
    public function actualizarDatos()
    {
        //dd($this->requiere_validacion);
        $this->validate();
        // dd('jij', $this->id_persona);
        DB::beginTransaction();
        try {
            $personaActualizada = Persona::find($this->id_persona);
            if ($personaActualizada) {
                $personaActualizada->nombres = $this->nombres;
                $personaActualizada->p_apellido = $this->p_apellido;
                $personaActualizada->s_apellido = $this->s_apellido;
                $this->ci = trim($this->ci);
                $this->ci = str_replace(' ', '', $this->ci) ?: null;
                $personaActualizada->ci = $this->ci;
                $this->complemento = trim($this->complemento);
                $this->complemento = str_replace(' ', '', $this->complemento) ?: null;
                $personaActualizada->complemento = $this->complemento;
                $personaActualizada->clave_unica = $this->ci . $this->complemento;
                $personaActualizada->id_user_updated = auth()->user()->id;
                $personaActualizada->observaciones = $this->observaciones;

                // dd($this->es_extranjero);
                if ($this->es_extranjero == 1) {
                    $personaActualizada->es_extranjero = true;
                    $personaActualizada->id_nacimiento_municipio = null;
                } else {
                    $personaActualizada->es_extranjero = false;
                    $personaActualizada->id_nacimiento_municipio = $this->id_nacimiento_municipio;
                }
                $personaActualizada->id_dept_exp = $this->id_dept_exp;
                // dd($this->sexo);
                if ($this->sexo == true) {
                    $personaActualizada->sexo = 'F';
                } else {
                    $personaActualizada->sexo = 'M';
                }
                $personaActualizada->fecha_nacimiento = $this->fecha_nacimiento;
                if ($this->fecha_nacimiento != null) {
                    $personaActualizada->matricula_seguro = $this->matricula_seguro;
                } else {
                    $personaActualizada->matricula_seguro = null;
                }

                $personaActualizada->id_estado_civil = $this->id_estado_civil;
                $personaActualizada->grupo_sanguineo = $this->grupo_sanguineo;
                $personaActualizada->alergias = $this->alergias;
                $camposAfectados = [
                    'nombres',
                    'p_apellido',
                    's_apellido'
                ];
                $usuario_actualizado = $personaActualizada->isDirty($camposAfectados);
                // dd($usuario_actualizado);
                $this->actualizarUsuario($personaActualizada);
                // dd($personaActualizada);
                $personaActualizada->save();
                DB::commit();
                //dd($this->usuario_actualizado);
                if ($usuario_actualizado === true) {
                    $this->dispatch('usuarioModificado');
                } else {
                    $this->dispatch('datosActualizados');
                }
                $this->persona_existe = false;
            }
        } catch (\Throwable $e) {

            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al actualizar datos", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);

            // dd('error');
            $this->dispatch('errorSistemas');
            DB::rollBack();
        }
    }


    public function actualizarUsuario($personaActualizada)
    {
        // dd('sdasd');
        try {
            $usuario = User::where('id_persona', $personaActualizada->id)->first();
            // dd($usuario);
            if ($usuario) {
                //dd('aqui entra?');
                $email_usuario = $usuario->email;
                $posicion_guion = strpos($email_usuario, '-');
                $posicion_arroba = strpos($email_usuario, '@');

                if ($posicion_guion !== false) {
                    $posicion = $posicion_guion;
                } elseif ($posicion_arroba !== false) {
                    $posicion = $posicion_arroba;
                } else {
                    $posicion = false;
                }

                $resto_usuario =  substr($email_usuario, $posicion);

                $usuario->name = $this->nombres . ' ' . $this->p_apellido . ' ' . $this->s_apellido;
                $nombre_email = $this->GenerarUsuario($personaActualizada);
                $usuario->email = $nombre_email  . $resto_usuario;
                $usuario->id_user_updated = auth()->user()->id;

                // dd($usuario);
                // dd($posicion, $nombre_email, $resto_usuario, $usuario->email);
                $usuario->save();
            }
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al actualizar datos de usuario", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
            $this->dispatch('errorSistemas');
        }
    }

    public function GenerarUsuario($persona_encontrada)
    {
        // Convertir a mayúsculas para asegurar consistencia
        $nombres = str_replace(' ', '', mb_strtolower($persona_encontrada->nombres));
        $p_apellido = str_replace(' ', '', mb_strtolower($persona_encontrada->p_apellido));
        $s_apellido = str_replace(' ', '', mb_strtolower($persona_encontrada->s_apellido));

        // Inicializar el email
        $email = '';

        if (!empty($p_apellido) == true && !empty($s_apellido) == true) {
            $email = substr($nombres, 0, 1) . $p_apellido . substr($s_apellido, 0, 1);
        }

        if (!empty($p_apellido) == false && !empty($s_apellido) == true) {
            $email = substr($nombres, 0, 1) . $s_apellido . substr($s_apellido, 0, 1);
        }

        if (!empty($p_apellido) == true && !empty($s_apellido) == false) {
            $email = substr($nombres, 0, 1) . $p_apellido . substr($p_apellido, 0, 1);
        }

        return $email;
    }

    public function cancelatDatosActualizados()
    {
        $this->dispatch('accionCancelada');
        $this->persona_existe = false;
        $this->restearDAtosBusqueda();
    }
}
