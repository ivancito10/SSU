<?php

namespace App\Livewire\Plataforma\Especialista;

use Livewire\Component;
use Livewire\Attributes\On;

use App\Models\Plataforma\Especialista;
use App\Models\Plataforma\AsignacionHorario;
use App\Models\Plataforma\EspecialistaHabilitadoServicio;
use App\Models\Administracion\Especialidad;
use App\Models\Administracion\Persona;
use App\Models\Administracion\Departamento;
use App\Models\Plataforma\Dia;
use App\Models\Plataforma\Horario;
use App\Models\Plataforma\Consultorio;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Carbon\Carbon;


class EspecialistaMain extends Component
{

    public $idEspecialista;
    use WithFileUploads;
    #[Validate]
    public $foto_nueva;
    public $serviciosSelected = [];
    public $asignacionHorarioSelected = [];
    public $asignacion = [];
    public $horarios = [];
    public $consultorio = [];
    public $horario = [];
    public $dia = [], $tipoDoc, $estado;
    public $checkAfiliado, $checkConvenio, $checkSsue;
    public $fecha_inicio_m, $fecha_fin_m;
    public $estadoEspecialista;

    public $especialistaImagenologia;


    protected $listeners = ['actualizar', 'guardarActualizarHorario'];

    public function render()
    {
        return view('livewire.Plataforma.especialista.especialista-main');
    }

    public function mount()
    {
        $this->actualizarEstadosHorariosServicios();
        //$this->mostrarHorario($this->idEspecialista);
        //$this->mostrarEspecialista($this->idEspecialista);
    }

    // para redireccionar a especialista Create
    public function registrarEspecialista()
    {
        return redirect()->route('plataforma.especialistas.create');
    }

    public function rules()
    {
        return [
            'foto_nueva' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    // verEspecialista
    #[On('verEspecialista')]
    public function verEspecialista($data)
    {
        //$this->reset(['fecha_inicio', 'fecha_fin','fecha_nacimiento']);
        //dd($this->fecha_inicio,$this->fecha_fin,$this->fecha_nacimiento);
        $this->mostrarEspecialista($data);

        $this->idEspecialista = $data;
        $this->serviciosSelected = $this->mostrarServicios($this->idEspecialista);
      //  dd($this->serviciosSelected);
        $this->asignacionHorarioSelected = $this->mostrarHorariosVer($this->idEspecialista);
        $this->dispatch('modalVerEspecialista');
    }

    public $nombres, $matricula, $ci, $complemento, $genero, $carnet,
        $fecha_nacimiento, $expedido, $fecha_inicio, $fecha_fin,
        $permanente, $doc_especialidad, $grado, $fotografia, $es_extranjero;

    public function mostrarEspecialista($id)
    {
        //$this->reset(['fecha_inicio', 'fecha_fin','fecha_nacimiento']);
        if ($id) {
            $esp = Especialista::find($id);
            $this->fecha_inicio_m = Carbon::parse($esp->fecha_contrato_inicio)->format('d/m/Y');
            $this->fecha_fin_m = Carbon::parse($esp->fecha_contrato_fin)->format('d/m/Y');
            $this->fecha_inicio = $esp->fecha_contrato_inicio;
            $this->fecha_fin = $esp->fecha_contrato_fin;
            //dd($this->fecha_inicio);
            $this->permanente = $esp->permanente;
            $this->grado = $esp->grado_academico;
            $this->fotografia = $this->obtenerImagenBase64($esp->foto);
            $this->checkAfiliado = $esp->afiliados;
            $this->checkConvenio = $esp->convenios;
            $this->checkSsue = $esp->estudiantes;
            $this->estadoEspecialista = $esp->estado;

            $this->especialistaImagenologia = $esp->especialista_imagenologia;    //PARA IMAGENOLOGIA

            $id_especialidad = $esp->id_especialidad;
            $id_persona = $esp->id_persona;

            $persona = Persona::find($id_persona);
            $this->nombres = $persona->nombres . ' ' . $persona->p_apellido . ' ' . $persona->s_apellido;
            $this->matricula = $persona->matricula_seguro;
            $this->ci = $persona->ci;
            $this->complemento = $persona->complemento;
            if ($persona->complemento == null) {
                $this->carnet = $persona->ci;
            } else {
                $this->carnet = $persona->ci . ' - ' . $persona->complemento;
            }

            $es_extranjero = $persona->es_extranjero;
            if ($es_extranjero == true) {
                $this->tipoDoc = 'EXTRANJERO';
            } else {
                $this->tipoDoc = 'NACIONAL';
            }

            $this->genero = $persona->sexo;
            $this->fecha_nacimiento = Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y');
            $id_dep_exp = $persona->id_dept_exp;

            $departamento = Departamento::find($id_dep_exp);

            //Esta parte deaba error por la migracion de especialistas
            if ($departamento != null) {
                $this->expedido = $departamento->sigla;
            }


            $especialidad = Especialidad::find($id_especialidad);
            $this->doc_especialidad = $especialidad->especialidad;
            //dd($this->fecha_inicio,$this->fecha_fin,$this->fecha_nacimiento);
        }
    }

    // Funcion para obtener imagenes desde la FTP
    private function obtenerImagenBase64($filename)
    {
        try {
            if ($filename) {
                $path = 'Plataforma/Especialista/Fotos/' . $filename;
                $imageData = Storage::disk('ftp')->get($path);

                $fotito = base64_encode($imageData);
                return $fotito;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }

    //-- para mostrar en la tabla 
    public function mostrarServicios($idEspecialista)
    {
        //dd('entra aqui');
        if ($idEspecialista) {

            $asignacion = EspecialistaHabilitadoServicio::with(['diaEspecialistaHabilitadoServicio', 'servicioEspecialistaHabilitadoServicio'])
                ->where('id_especialista', $idEspecialista)
                ->where('estado', true)
                ->orderBy('id_dia', 'asc')
                ->get();
            return $asignacion;
        }
    }

    public function mostrarHorariosVer($idEspecialista)
    {
        if ($idEspecialista) {
            $asignacion = AsignacionHorario::with(['consultorioAsignacionHorario.consultorioSedes', 'diaAsignacionHorario', 'horarioAsignacionHorario'])
                ->where('id_especialista', $idEspecialista)
                ->where('estado', true)
                ->orderBy('id_dia', 'asc')
                ->get();
            return $asignacion;
        }
    }

    // para habilitar un servicio
    public function habilitarServicio($dato)
    {
        $servicioActual = EspecialistaHabilitadoServicio::find($dato);
        //dd($servicioActual);
        if ($servicioActual) {
            $servicioActual->estado = false;
            $servicioActual->id_user_updated = auth()->user()->id;
            $servicioActual->save();
        }
        $this->dispatch('servicioEspDeshabilitado');
    }

    // para deshabilitar un servicio 
    public function deshabilitarServicio($dato)
    {
        $servicioActual = EspecialistaHabilitadoServicio::find($dato);
        if ($servicioActual) {
            $servicioActual->estado = true;
            $servicioActual->id_user_updated = auth()->user()->id;
            $servicioActual->save();
        }
        $this->dispatch('servicioEspHabilitado');
    }

    // editarEspecialista
    #[On('editarEspecialista')]
    public function editarEspecialista($data)
    {
        $this->idEspecialista = $data;
        $this->mostrarEspecialista($this->idEspecialista);
        $this->dispatch('modalEditarEspecialista');
    }

    // Cambiar estado 
    #[On('cambiarEstado')]
    public function cambiarEstado($data)
    {
        //dd("llega");
        $this->idEspecialista = $data;
        $esp = Especialista::find($this->idEspecialista);
        if ($esp) {
            $this->estado = $esp->estado;
        }
        $this->dispatch('confirmarCambioEstado');
    }

    #[On('actualizarEstado')]
    public function actualizarEstado($confirmado = null)
    {
        if ($confirmado) {
            $esp = Especialista::find($this->idEspecialista);
            if ($esp) {
                $estadoActual = $esp->estado;
                $esp->estado = !$estadoActual;
                $esp->save();
                $this->dispatch('pg:eventRefresh-EspecialistaTable');
            }
        }
    }

    // Actualizar registro
    public function actualizar()
    {
        //dd($this->permanente, $this->fecha_inicio, $this->fecha_fin, $this->checkAfiliado, $this->checkConvenio, $this->checkSsue);
        if ($this->permanente == true) {
            $esp = Especialista::find($this->idEspecialista);
            if ($esp) {
                $esp->permanente = true;
                $esp->fecha_contrato_inicio = null;
                $esp->fecha_contrato_fin = null;
                $esp->afiliados = $this->checkAfiliado;
                $esp->convenios = $this->checkConvenio;
                $esp->estudiantes = $this->checkSsue;
                $esp->id_user_updated = auth()->user()->id;
                $esp->save();
            }
            $this->dispatch('pg:eventRefresh-EspecialistaTable');
            $this->dispatch('guardado');
        } else {
            if (empty($this->fecha_inicio) || empty($this->fecha_inicio)) {
                $this->dispatch('sinFechas');
                return;
            } elseif ($this->fecha_fin >= $this->fecha_inicio) {
                $esp = Especialista::find($this->idEspecialista);
                if ($esp) {
                    $esp->permanente = false;
                    $esp->fecha_contrato_inicio = $this->fecha_inicio;
                    $esp->fecha_contrato_fin = $this->fecha_fin;
                    $esp->afiliados = $this->checkAfiliado;
                    $esp->convenios = $this->checkConvenio;
                    $esp->estudiantes = $this->checkSsue;
                    $esp->id_user_updated = auth()->user()->id;
                    $esp->save();
                }
                $this->dispatch('pg:eventRefresh-EspecialistaTable');
                $this->dispatch('guardado');
            } else {
                $this->dispatch('errorFechaFinMayor');
                return;
            }
        }
    }

    #[On('actualizarAgenda')]
    public function actualizarAgenda($data)
    {
        //$this->reset(['fecha_inicio', 'fecha_fin', 'fecha_nacimiento']);
        $this->idEspecialista = $data;
        $this->mostrarEspecialista($data);
        $this->horarios = $this->mostrarHorario($data);
        //dd('entra a actualizarAgenda');
        $this->dispatch('modalVerHorarios');
    }

    public function mostrarHorario($idEspecialista)
    {
        if ($idEspecialista) {
            $horarios = AsignacionHorario::where('id_especialista', $idEspecialista)
                ->join('plataforma.dia', 'plataforma.asignacion_horario.id_dia', '=', 'dia.id')
                ->join('plataforma.consultorio', 'asignacion_horario.id_consultorio', '=', 'consultorio.id')
                ->join('plataforma.sedes', 'consultorio.id_sedes', '=', 'sedes.id')
                ->join('plataforma.horario', 'plataforma.asignacion_horario.id_horario', '=', 'horario.id')
                ->select(
                    'asignacion_horario.*',
                    'dia.nombre as dia_nombre',
                    'consultorio.numero_consultorio',
                    'sedes.piso',
                    'sedes.ubicacion',
                    'horario.hora_inicio',
                    'horario.hora_fin',
                    'horario.intervalo',
                    'horario.numero_paciente',
                    'horario.turno'
                )
                ->where('estado', true)
                ->orderBy('id_dia', 'asc')
                ->get();
            return $horarios;
        }
    }

    public function actualizarHorario($esp, $asigh)
    {
        $this->idEspecialista = $esp;
        //$this->dia = Dia::all();
        $this->consultorio = Consultorio::with(['consultorioSedes'])
            ->where('estado', '=', true)
            ->get();
        $this->horario = Horario::where('estado', '=', true)->get();
        $this->dispatch('modalModificarHorario');
    }

    public  $fecha_finAsig, $fecha_inicioAsig, $id_diaAsig, $id_consultorioAsig, $id_horarioAsig;

    public function guardarActualizarHorario()
    {
        /* dd($this->idEspecialista, $this->fecha_finAsig, $this->fecha_inicioAsig, 
        $this->id_diaAsig, $this->id_consultorioAsig, $this->id_horarioAsig);
         */

        $nuevaAsignacion = AsignacionHorario::create([
            'fecha_inicio' => $this->fecha_inicioAsig,
            'fecha_fin' => $this->fecha_finAsig,
            'estado' => true,
            'id_dia' => $this->id_diaAsig,
            'id_horario' => $this->id_horarioAsig,
            'id_especialista' => $this->idEspecialista,
            'id_consultorio' => $this->id_consultorioAsig,
            'id_user_created' => auth()->user()->id,
        ]);
        $nuevo = $nuevaAsignacion->id;

        $this->fecha_inicioAsig = "";
        $this->fecha_finAsig = "";
        $this->id_diaAsig = "";
        $this->id_horarioAsig = "";
        $this->id_consultorioAsig = "";

        $this->dispatch('guardado');
        $this->skipRender();
    }

    public function actualizarEstadosHorariosServicios()
    {
        $horariosAsignados = AsignacionHorario::where('estado', true)
            ->where('fecha_fin', '<', Carbon::now()->format('Y-m-d'))
            ->get();

        foreach ($horariosAsignados as $horario) {
            $horario->estado = false;
            $horario->id_user_updated = auth()->user()->id;
            $horario->save();
        }

        $serviciosAsignados = EspecialistaHabilitadoServicio::where('estado', true)
            ->where('fecha_fin', '<', Carbon::now()->format('Y-m-d'))
            ->get();

        foreach ($serviciosAsignados as $servicio) {
            $servicio->estado = false;
            $servicio->id_user_updated = auth()->user()->id;
            $servicio->save();
        }
    }
}
