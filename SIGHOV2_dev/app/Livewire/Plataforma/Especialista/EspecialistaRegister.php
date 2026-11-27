<?php

namespace App\Livewire\Plataforma\Especialista;

use App\Models\Plataforma\Especialista;
use App\Models\Administracion\Departamento;
use App\Models\Administracion\Municipio;
use App\Models\Administracion\Provincia;
use App\Models\Administracion\Especialidad;
use App\Models\Administracion\Persona;
use App\Models\Administracion\EstadoCivil;
use App\Services\ValidarPersona;
use App\Services\BeneficiariosAsuss;
use App\Services\BeneficiariosGehma2;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class EspecialistaRegister extends Component
{
    public $personaId;
    #[Validate]
    public $ci, $complemento, $nombres, $p_apellido, $s_apellido, $matricula_seguro, $estado_asuss, $id_estado_civil;
    #[Validate]
    public $departamentos = [], $id_dept_exp, $fecha_nacimiento, $es_extranjero, $sexo = false, $dept_exp;
    public $ci_persona, $complemento_persona;
    #[Validate]
    public $fecha_contrato_inicio, $fecha_contrato_fin, $permanente, $grado_academico;
    #[Validate]
    public $id_especialidad, $especialidades = [], $seleccionarEspecialidad = null;
    public $encontrado;
    public $datosPersona, $buscarComplemento, $buscarCarnet, $estadoBusqueda, $grados_academicos = [];

    public $ciBusqueda;
    public $complementoBusqueda;
    public $personaExisteEnBd;
    public $fecha_nacimientoSwitch, $es_extranjeroSwitch, $id_dept_expSwitch, $matricula_seguroSwitch, $id_estado_civilSwitch;
    public $estadosCiviles = [];

    public $checkAfiliado, $checkConvenio, $checkSsue;

    use WithFileUploads;
    #[Validate]
    public $foto_doctor;

    public $buscadoSwitch;

    public $especialistaImagenologia;

    public $especialistaImagenologiaMostrar;

    protected $listeners = ['registroEspecialista'];

    public function rules()
    {
        return [
            'fecha_contrato_inicio' => $this->permanente ? 'nullable|date' : 'required|date',
            'fecha_contrato_fin' => $this->permanente ? 'nullable|date' : 'required|date',
            'id_especialidad' => 'required',
            'grado_academico' => 'required',
            // 'foto_doctor' => 'image|mimes:jpg,jpeg,png|max:2048',
            'nombres' => 'required',
            //'p_apellido' => 'required',
            //'s_apellido' => 'required',
            'ci' => 'required',
            'id_dept_exp' => 'required',
            'es_extranjero' => 'required',
            'fecha_nacimiento' => 'required|date|before_or_equal:' . now()->subYears(15)->toDateString(), // Se asegura de que la fecha sea mayor a 15 años
            'matricula_seguro' => 'required',
            'id_estado_civil' => 'required',
            'especialistaImagenologia' => 'required',
        ];
    }

    public function render()
    {
        return view('livewire.Plataforma.especialista.especialista-register');
    }

    public function mount()
    {
        $this->buscadoSwitch = false;
        $this->especialistaImagenologia = false;
        $this->departamentos = Departamento::all();
        $this->especialidades = Especialidad::orderBy('especialidad', 'asc')->get();
        $this->estadosCiviles = EstadoCivil::all();
    }

    // aqui se realiza la conulta de la persona en bd o asuss

    public function buscaBD()
    {
        // aqui validara asi ponga full espacios 
        if (empty(trim($this->ciBusqueda))) {
            $this->dispatch('ciInvalido');
            return;
        }
        // aqui elimino espacio en caso de poner ej: 1 1 1 
        $this->ciBusqueda = preg_replace('/\s+/', '', $this->ciBusqueda);

        if ($this->complementoBusqueda == '') {
            $this->complementoBusqueda = null;
        }

        if (!$this->validarCarnet($this->ciBusqueda)) {
            $this->dispatch('ciInvalido');
            return;
        }
        try {
            if ($this->complementoBusqueda == '') {
                $this->complementoBusqueda = null;
            }

            $personaBD = Persona::where('ci', $this->ciBusqueda)
                ->where('complemento', $this->complementoBusqueda)
                ->first();

            if ($personaBD) {
                $this->estadoBusqueda = 2;
                $this->personaExisteEnBd = true;
                $this->personaId = $personaBD->id;
                $this->ci = $personaBD->ci;
                $this->complemento = $personaBD->complemento;
                $this->nombres = $personaBD->nombres;
                $this->p_apellido = $personaBD->p_apellido;
                $this->s_apellido = $personaBD->s_apellido;

                $this->fecha_nacimiento = $personaBD->fecha_nacimiento;
                $this->fecha_nacimientoSwitch = $personaBD->fecha_nacimiento ? true : false;

                if ($personaBD->sexo == 'F') {
                    $this->sexo = true;
                } elseif ($personaBD->sexo == 'M') {
                    $this->sexo = false;
                } elseif ($personaBD->sexo == null) {
                    $this->sexo = false;
                } else {
                    $this->sexo = false;
                }
                $this->es_extranjero = $personaBD->es_extranjero;
                if (is_null($personaBD->es_extranjero)) {
                    $this->es_extranjeroSwitch = false;
                } else {
                    $this->es_extranjeroSwitch = true;
                }

                $this->id_dept_expSwitch = $personaBD->id_dept_exp ? true : false;
                $this->id_dept_exp = $personaBD->id_dept_exp;
                $this->matricula_seguroSwitch = $personaBD->matricula_seguro ? true : false;
                $this->matricula_seguro = $personaBD->matricula_seguro;

                $this->estado_asuss = $personaBD->estado_asuss;

                $this->id_estado_civilSwitch = $personaBD->id_estado_civil ? true : false;
                $this->id_estado_civil = $personaBD->id_estado_civil;
            } else {

                $respuesta = new ValidarPersona();
                $dato = $respuesta->buscarPersona($this->ciBusqueda, $this->complementoBusqueda);

                if (isset($dato['datosPersonaEnFormatoJson'])) {
                    $this->estadoBusqueda = 1;
                    $this->personaExisteEnBd = true;
                    $this->ci = $dato['datosPersonaEnFormatoJson']['numeroDocumento'];
                    $this->complemento = $dato['datosPersonaEnFormatoJson']['Complemento'];
                    $this->p_apellido = $dato['datosPersonaEnFormatoJson']['primerApellido'];
                    $this->s_apellido = $dato['datosPersonaEnFormatoJson']['segundoApellido'];
                    $this->nombres = $dato['datosPersonaEnFormatoJson']['nombres'];

                    $this->fecha_nacimiento = $dato['datosPersonaEnFormatoJson']['fechaNacimiento'];
                    $this->fecha_nacimientoSwitch = true;
                    $this->sexo = false;
                    $this->es_extranjeroSwitch = false;
                    $this->id_dept_expSwitch = false;
                    $this->matricula_seguroSwitch = false;
                    $this->id_estado_civilSwitch = false;

                    $this->estado_asuss = true;
                } else {
                    $this->estadoBusqueda = 3;
                    $this->personaExisteEnBd = false;
                    // $this->nombres = '';
                    // $this->p_apellido = '';
                    // $this->s_apellido = '';
                    $this->fecha_nacimiento = '';
                    $this->ci = $this->ciBusqueda;
                    $this->complemento = $this->complementoBusqueda;
                    $this->estado_asuss = false;

                    $this->fecha_nacimientoSwitch = false;
                    $this->sexo = false;
                    $this->es_extranjeroSwitch = false;
                    $this->id_dept_expSwitch = false;
                    $this->matricula_seguroSwitch = false;
                    $this->matricula_seguro = '';
                    $this->id_estado_civilSwitch = false;
                }
            }

            if ($this->fecha_nacimiento != null && $this->nombres != null && $this->p_apellido != null && $this->s_apellido != null) {
                $this->GenerarMatricula();
            }
            $this->gradoAcademico();
        } catch (\Throwable $e) {
            $this->estadoBusqueda = 3;
            $this->personaExisteEnBd = false;
            $this->nombres = '';
            $this->p_apellido = '';
            $this->s_apellido = '';
            $this->fecha_nacimiento = '';
            $this->ci = $this->ciBusqueda;
            $this->complemento = $this->complementoBusqueda;
            $this->estado_asuss = false;
            $this->fecha_nacimientoSwitch = false;
            $this->sexo = false;
            $this->es_extranjeroSwitch = false;
            $this->id_dept_expSwitch = false;
            $this->matricula_seguroSwitch = false;
            $this->matricula_seguro = '';
            $this->id_estado_civilSwitch = false;

            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error en buscar persona en especialista register", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
        $this->buscadoSwitch = true;
    }

    private function validarCarnet($carnet)
    {
        // Eliminar espacios en blanco al inicio y al final con trim
        $carnet = trim($carnet);
        // Validar carnet
        return !(empty($carnet) || preg_match('/^0+$/', $carnet) || preg_match('/^\d+$/', $carnet) && strlen($carnet) === 1 || strlen($carnet) < 4);
    }
    // registro de especialista
    public function gradoAcademico()
    {

        if ($this->sexo == true) {
            $this->grados_academicos = ['DOCTORA', 'LICENCIADA'];
        } else {
            $this->grados_academicos = ['DOCTOR', 'LICENCIADO'];
        }
        //dd($this->grados_academicos);
    }

    public function verificarDuplicidad()
    {
        if ($this->personaId != null) {
            $buscarEspecialista = Especialista::where('id_persona', $this->personaId)
                ->where('id_especialidad', $this->id_especialidad)
                ->first();

            if ($buscarEspecialista) {
                $this->dispatch('especialidadExiste');
            }
        }
    }

    public function especialistaPerteneceImagenologia($idEspecialidad)
    {
        $perteneceImagenologia = false;
        $especialidadImagenologia = Especialidad::find($idEspecialidad);
        if ($especialidadImagenologia) {
            $perteneceImagenologia = $especialidadImagenologia->especialidad_imagenologia;
        }
        return $perteneceImagenologia;
    }

    #[On('mostrarPerteneceImagenologia')]
    public function mostrarPerteneceImagenologia($idEspecialidad) {
        //dd('entra loco', $idEspecialidad);
        $this->especialistaImagenologiaMostrar = $this->especialistaPerteneceImagenologia($idEspecialidad);
    }

    public function registroEspecialista()
    {
        $this->validate();
        // dd($this->checkAfiliado, $this->checkConvenio, $this->checkSsue);
        //para crear persona 
        DB::beginTransaction();
        try {
            if ($this->estadoBusqueda == 2) {
                $personaExiste = Persona::find($this->personaId);
                $personaExiste->update([
                    'fecha_nacimiento' => $this->fecha_nacimiento,
                    'es_extranjero' => $this->es_extranjero,
                    'id_dept_exp' => $this->id_dept_exp,
                    'sexo' => $this->sexo === true ? 'F' : 'M',
                    'matricula_seguro' => $this->matricula_seguro,
                    'id_estado_civil' => $this->id_estado_civil,
                    'id_user_updated' => auth()->user()->id,
                ]);
            } else {
                if ($this->complemento == null) {
                    $clave_unica = $this->ci;
                } else {
                    $clave_unica = $this->ci . $this->complemento;
                }

                $nuevaPersona = Persona::create([
                    'ci' => $this->ci,
                    'complemento' => $this->complemento,
                    'nombres' => $this->nombres,
                    'p_apellido' => $this->p_apellido,
                    's_apellido' => $this->s_apellido,
                    'fecha_nacimiento' => $this->fecha_nacimiento,
                    'matricula_seguro' => $this->matricula_seguro,
                    'es_extranjero' => $this->es_extranjero,
                    'id_dept_exp' => $this->id_dept_exp,
                    'sexo' => $this->sexo === true ? 'F' : 'M',
                    'clave_unica' => $clave_unica,
                    'id_user_created' => auth()->user()->id,
                ]);
                $this->personaId = $nuevaPersona->id;
            }
            // Verifica si ya existe un especialista con el mismo id_persona y id_especialidad
            $repiteEspecialidad = Especialista::where('id_persona', $this->personaId)
                ->where('id_especialidad', $this->id_especialidad)
                ->first();
            if ($repiteEspecialidad) {
                $this->dispatch('especialidadExiste');
            } else {

                $especialista = Especialista::create([
                    'fecha_contrato_inicio' => $this->permanente ? null : $this->fecha_contrato_inicio,
                    'fecha_contrato_fin' => $this->permanente ? null : $this->fecha_contrato_fin,
                    'permanente' => $this->permanente,
                    'estado' => true,
                    'id_persona' => $this->personaId,
                    'id_especialidad' => $this->id_especialidad,
                    'grado_academico' => $this->grado_academico,
                    'afiliados' => $this->checkAfiliado,
                    'estudiantes' => $this->checkSsue,
                    'convenios' => $this->checkConvenio,
                    'especialista_imagenologia' => $this->especialistaPerteneceImagenologia($this->id_especialidad),
                    'id_user_created' => auth()->user()->id,

                ]);

                if ($especialista) {
                    // Verifica si se ha proporcionado una foto
                    if ($this->foto_doctor) {
                        $nombreFoto = $especialista->id . '.jpg';
                        $this->foto_doctor->storeAs('Plataforma/Especialista/Fotos', $nombreFoto, 'ftp');
                        $especialista->update([
                            'foto' => $nombreFoto,
                        ]);
                    } else {
                        $especialista->update([
                            'foto' => null,
                        ]);
                    }
                }
            }

            DB::commit();
            //$this->buscadoSwitch = false;
            $this->dispatch('especialistaRegistrado');
            return redirect()->route('plataforma.especialistas.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->dispatch('yaExisteHorario');

            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error en buscar persona en especialista register", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    public function validarFechaNacimiento()
    {
        $hoy = Carbon::now();
        if ($this->fecha_nacimiento == $hoy->toDateString()) {
            $this->dispatch('FechaNacimientoInvalidaHoy');
            $this->fecha_nacimiento = null;
            return;
        }
        if ($this->fecha_nacimiento  > $hoy->toDateString()) {
            $this->dispatch('FechaNacimientoInvalidaManana');
            $this->fecha_nacimiento = null;
            return;
        }
        $fechaNacimiento = Carbon::parse($this->fecha_nacimiento);
        $edad = $hoy->diffInYears($fechaNacimiento);
        if ($edad < 18) {
            $this->dispatch('FechaNacimientoInvalidaMenorEdad');
            $this->fecha_nacimiento = null;
            return;
        }
        $this->generarMatricula();
    }


    public function cancelarEspecializacion()
    {
        return redirect()->route('plataforma.especialistas.index');
    }

    public function generarMatricula()
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

        $this->matricula_seguro = $matricula;
    }

    public function generarMatriculayGradoAcad()
    {
        $this->generarMatricula();
        $this->gradoAcademico();
    }
}
