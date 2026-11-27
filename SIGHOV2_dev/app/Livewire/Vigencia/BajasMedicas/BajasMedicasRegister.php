<?php


namespace App\Livewire\Vigencia\BajasMedicas;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Administracion\Persona;
use App\Models\Administracion\TipoAsegurado;
use App\Models\Vigencia\BajaMedica;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Aportes\Institucion;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Plataforma\Especialista;
use App\Models\Administracion\Especialidad;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Storage;
use function PHPUnit\Framework\isEmpty;


class BajasMedicasRegister extends Component
{

    public $idAfiliado;
    // para mostrar los datos del afiliado
    public $matricula, $ci, $complemento,
        $nombreCompleto, $tipo_asegurado, $fecha_nacimiento, $sexo, $edadPersona, $codigoInstitucional;
    #[Validate]
    public $cite, $foto, $estado_vigencia,
        $id_institucion, $institucion;
    #[Validate]
    public  $fecha_inicio, $fecha_fin,  $diagnostico, $cod_baja_medica, $dias_diferencia;
    #[Validate]
    public $id_especialidad, $id_especialista;
    public $especialidadSelect;
    public $medicos;

    protected $listeners = ['guardarBaja'];

    public function rules()
    {
        return [
            'cite' => 'required',
            'fecha_recepcion' => 'required|date',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'dias_diferencia' => 'required',
            'id_especialidad' => 'required',
            'id_especialista' => 'required',

        ];
    }

    public function render()
    {
        return view('livewire.Vigencia.bajas-medicas.bajas-medicas-register');
    }

    public $fecha_recepcion;
    public function mount()
    {
        $this->especialidadSelect = Especialidad::orderBy('especialidad', 'asc')->get();
        $this->mostrarPersona($this->idAfiliado);
        $this->fecha_recepcion = Carbon::now()->format('Y-m-d');
        $this->diasBaja();
    }

    public $id_esp, $especialistaSelect = [];
    public function buscarEspecialista($id)
    {
        $this->id_esp = $id;
        $this->especialistaSelect = Especialista::where('id_especialidad', $id)->with('personaEspecialista')->get();
    }




    public function mostrarPersona($id)
    {
        if ($id) {
            $personaAfiliada = Persona::find($id);
            if ($personaAfiliada->p_apellido == null) {
                $this->nombreCompleto = $personaAfiliada->s_apellido . ' ' . $personaAfiliada->nombres;
            } elseif ($personaAfiliada->s_apellido == null) {
                $this->nombreCompleto = $personaAfiliada->p_apellido . ' ' . $personaAfiliada->nombres;
            } else {
                $this->nombreCompleto = $personaAfiliada->p_apellido . ' ' . $personaAfiliada->s_apellido . ' ' . $personaAfiliada->nombres;
            }

            $this->matricula = $personaAfiliada->matricula_seguro;
            if ($personaAfiliada->complemento == null) {
                $this->ci = $personaAfiliada->ci;
            } else {
                $this->ci = $personaAfiliada->ci . ' - ' . $personaAfiliada->complemento;
            }

            $this->complemento = $personaAfiliada->complemento;

            $this->fecha_nacimiento = $personaAfiliada->fecha_nacimiento;
            $this->edadPersona = Carbon::parse($personaAfiliada->fecha_nacimiento)->age;
            $this->sexo = $personaAfiliada->sexo == 'F' ? 'FEMENINO' : 'MASCULINO';

            $tipoAsegurado = TipoAsegurado::find($personaAfiliada->id_tipo_asegurado);
            $this->tipo_asegurado = $tipoAsegurado->tipo_asegurado;



            $esTitular = Titular::where('id_persona', $id)->where('estado', true)->first();
            if ($esTitular) {
                $this->estado_vigencia = $esTitular->estado_vigencia;

                $this->foto = $this->obtenerImagenBase64($esTitular->foto_nombre);


                $titularInstitucion = TitularInstitucion::where('id_titular', $esTitular->id)
                    ->where('estado', true)
                    ->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])
                    ->first();
                if ($titularInstitucion) {
                    $inst = Institucion::find($titularInstitucion->id_institucion);
                    $this->institucion = $inst->nombre;
                    $this->id_institucion = $inst->id;
                    if ($titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null) {
                        $this->codigoInstitucional = $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                            $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular;
                    } else {
                        $this->codigoInstitucional = null;
                    }
                } else {
                    $this->institucion = 'APORTANTE VOLUNTARIO';
                    $this->codigoInstitucional = null;
                }
            } else {
                $esBeneficiario = Beneficiario::where('id_persona', $id)->where('estado', true)->first();
                $this->foto = $this->obtenerImagenBase64($esBeneficiario->foto_nombre);
                $this->estado_vigencia = $esBeneficiario->vigencia;

                $titularBen = Titular::find($esBeneficiario->id_titular);
                $titularInstBen = TitularInstitucion::where('id_titular', $titularBen->id)
                    ->where('estado', true)
                    ->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])
                    ->first();
                if ($titularInstBen) {
                    $inst = Institucion::find($titularInstBen->id_institucion);
                    $this->institucion = $inst->nombre;
                    $this->id_institucion = $inst->id;
                    if ($titularInstBen->titularInstitucionConInstitucion->codigosInstitucion != null) {
                        $this->codigoInstitucional = $titularInstBen->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                            $titularInstBen->titularInstitucionConInstitucion->codigosInstitucion->tipo_beneficiario;
                    } else {
                        $this->codigoInstitucional = null;
                    }
                } else {
                    $this->institucion = 'APORTANTE VOLUNTARIO';
                    $this->codigoInstitucional = null;
                }
            }
            return $personaAfiliada;
        }
    }

    private function obtenerImagenBase64($idFoto)
    {
        try {
            if ($idFoto) {
                $path = 'Afiliacion/FOTOS/' . $idFoto;


                $imageData = Storage::disk('ftp')->get($path);
                //dd($imageData);
                $foto = base64_encode($imageData);
                // dd($this->foto);
                return $foto;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function verificarFechasBajasMedicas()
    {
        $fechaIncioFormateada = Carbon::parse($this->fecha_inicio)->format('Y-m-d');
        $fechaFinFormateada = Carbon::parse($this->fecha_fin)->format('Y-m-d');
        // dd($fechaIncioFormateada);
        $bajaVigente = BajaMedica::where('id_persona', $this->idAfiliado)
            ->whereNull('id_user_anulado')
            // ->whereNotNull('id_user_validado') preguntar si debe estar validado
            ->where('fecha_inicio', '<=', $fechaFinFormateada)
            ->where('fecha_fin', '>=', $fechaIncioFormateada)
            ->first();
        if ($bajaVigente) {
            $this->dispatch(
                'bajaActiva',
                fechaInicio: Carbon::parse($bajaVigente->fecha_inicio)->format('d/m/Y'),
                fechaFin: Carbon::parse($bajaVigente->fecha_fin)->format('d/m/Y')
            );
            $this->reset(['fecha_inicio', 'fecha_fin', 'dias_diferencia']);
            return;
        }
        $this->diasBaja();
    }

    public function updatedFechaInicio()
    {
        $this->diasBaja();
        /* if ($this->fecha_inicio && $this->fecha_fin) {
            $this->verificarFechasBajasMedicas();
        } */
    }

    public function updatedFechaFin()
    {
        $this->diasBaja();
        /*  if ($this->fecha_inicio && $this->fecha_fin) {
            $this->verificarFechasBajasMedicas();
        } */
    }


    public function diasBaja()
    {
        if ($this->fecha_inicio !== null && $this->fecha_fin !== null) {
            $start = Carbon::parse($this->fecha_inicio);
            $end = Carbon::parse($this->fecha_fin);
            $this->dias_diferencia = $start->diffInDays($end) + 1;
        } else {
            $this->dias_diferencia = 0;
        }
    }


    public function guardarBaja($diagnostico)
    {
        $this->validate();
        //dd($diagnostico);
        //$this->diagnostico = $diagnostico;
        $datos = [
            'cod_baja_medica' => $this->cite,
            'fecha_recepcion' => $this->fecha_recepcion,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'id_especialidad' => $this->id_especialidad,
            'id_especialista' => $this->id_especialista,
            'estado_validacion' => 'PENDIENTE',
            'estado_registro' => true,
            'diagnostico' => $diagnostico,
            'id_persona' => $this->idAfiliado,
            'nombre_persona' => $this->nombreCompleto,
            'id_institucion' => $this->id_institucion,

            'id_user_created' => auth()->user()->id,
        ];
        //dd($datos);
        $baja_medica = BajaMedica::create($datos);


        $baja_medicaId = $baja_medica->id;
        $this->cite = '';
        $this->fecha_recepcion = '';
        $this->fecha_inicio = '';
        $this->fecha_fin = '';
        $this->id_especialidad = '';
        $this->id_especialista = '';
        $this->diagnostico = '';

        $this->dispatch('guardado');
        return redirect()->route('vigencia.bajasmedicas.index');
    }

    public function obtenerEspecialista()
    {
        $this->medicos = Especialista::with('personaEspecialista')
            ->where('id_especialidad', '=', $this->id_especialidad)
            ->get();
    }

    // boton cerrar
    public function cancel()
    {
        return redirect()->route('vigencia.bajasmedicas.index');
    }

    // FUNCION ATRAS
    public function atras()
    {
        return redirect()->route('vigencia.bajasmedicas.index');
    }
}
