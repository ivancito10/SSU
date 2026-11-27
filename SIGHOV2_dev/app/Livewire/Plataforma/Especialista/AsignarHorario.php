<?php

namespace App\Livewire\Plataforma\Especialista;

use App\Models\Plataforma\Especialista;
use App\Models\Plataforma\Dia;
use App\Models\Plataforma\Horario;
use App\Models\Plataforma\AsignacionHorario;
use App\Models\Plataforma\Consultorio;
use App\Models\Administracion\Especialidad;
use App\Models\Administracion\Persona;
use App\Models\Administracion\Departamento;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AsignarHorario extends Component
{
    protected $listeners = ['asignarHorario', 'editarHorario'];
    // protected $listeners = ['editarHorario'];
    // public $id_horarioAsig;
    public $idEsp;
    public $horarios;
    #[Validate]
    public $fecha_inicioAsig, $fecha_finAsig, $estadoAsig;
    #[Validate]
    public $id_diaAsig, $id_horarioAsig, $id_especialistaAsig, $id_consultorioAsig;

    public function rules()
    {
        return [
            'fecha_inicioAsig' => 'required|date',
            'fecha_finAsig' => 'required|date',
            'id_diaAsig' => 'required',
            'id_consultorioAsig' => 'required',
            'id_horarioAsig' => 'required',
        ];
    }

    public function render()
    {
        $horario = Horario::where('estado', '=', true)->get();
        $consultorio = Consultorio::join('plataforma.sedes', 'consultorio.id_sedes', '=', 'sedes.id')->select('consultorio.*', 'sedes.piso', 'sedes.ubicacion')->where('consultorio.estado', '=', true)->get();

        $dia = Dia::get();

        $esp = $this->mostrarEspecialista($this->idEsp);
        $horarios = $this->mostrarHorario($this->idEsp);

        return view('livewire.Plataforma.especialista.asignar-horario', ['esp' => $esp], compact('dia', 'horario', 'consultorio', 'horarios',));
    }

    // para mostrar datos del esepcailista
    public $nombres, $matricula, $ci, $complemento, $carnet, $sexo, $fecha_nacimiento, $expedido, $fecha_inicio, $fecha_fin, $permanente, $doc_especialidad, $grado, $fotografia, $es_extranjero;

    public function mostrarEspecialista($idEsp)
    {
        if ($idEsp) {
            $esp = Especialista::find($idEsp);
            $this->fecha_inicio = $esp->fecha_contrato_inicio;
            $this->fecha_inicioAsig  = $esp->fecha_contrato_inicio;
            $this->fecha_fin = $esp->fecha_contrato_fin;
            // $this->fecha_finAsig = $esp->fecha_contrato_f11in;
            $this->permanente = $esp->permanente;
            if ($this->permanente == true) {
                $this->fecha_inicioAsig =
                    Carbon::now()->toDateString();
            }
            $this->grado = $esp->grado_academico;
            $this->fotografia = $this->obtenerImagenBase64($esp->foto);
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

            $this->es_extranjero = $persona->es_extranjero;

            $this->sexo = $persona->sexo;
            $this->fecha_nacimiento = $persona->fecha_nacimiento;
            $id_dep_exp = $persona->id_dept_exp;

            $departamento = Departamento::find($id_dep_exp);
            $this->expedido = $departamento->sigla;

            $especialidad = Especialidad::find($id_especialidad);
            $this->doc_especialidad = $especialidad->especialidad;
            return $esp;
        }
    }

    private function obtenerImagenBase64($idFoto)
    {
        try {
            if ($idFoto) {
                $path = 'Plataforma/Especialista/Fotos/' . $idFoto;

                $imageData = Storage::disk('ftp')->get($path);

                $foto = base64_encode($imageData);
                return $foto;
            } else {
                return null;
            }
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al obtener la imagen en asignar horario", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
            return null;
        }
    }
    // realizar registro de la asignacion de horario


    public function asignarHorario()
    {
        //dd('Es este botón?????????????');
        $this->validate();
        DB::beginTransaction();
        try {

            $repiteAsignacion = AsignacionHorario::where('id_especialista', $this->idEsp)
                ->where('id_horario', $this->id_horarioAsig)
                ->where('id_dia', $this->id_diaAsig)
                ->where('fecha_inicio', $this->fecha_inicioAsig)
                ->where('fecha_fin', $this->fecha_finAsig)
                ->where('id_consultorio', $this->id_consultorioAsig)
                ->first();

            //validar!!!!
            // $repiteAsignacion=AsignacionHorario::where('id_horario',$this->id_horarioAsig)
            // ->first();
            if ($repiteAsignacion) {
                $this->dispatch('yaExisteHorario');
            } else {
                /*$nuevaAsignacion = AsignacionHorario::create([
                'fecha_inicio' => $this->fecha_inicioAsig,
                'fecha_fin' => $this->fecha_finAsig,
                'estado' => true,
                'id_dia' => $this->id_diaAsig,
                'id_horario' => $this->id_horarioAsig,
                'id_especialista' => $this->idEsp,
                'id_consultorio' => $this->id_consultorioAsig,
                'id_user_created' => auth()->user()->id,
            ]);
            $this->dispatch('registroExitoso');*/

                $repiteAsignacion = AsignacionHorario::where('id_especialista', $this->idEsp)
                    ->where('id_horario', $this->id_horarioAsig)
                    ->where('id_dia', $this->id_diaAsig)
                    ->where('estado', true)
                    ->first();
                if ($repiteAsignacion) {
                    $this->dispatch('yaExisteHorario');
                } else {
                    $turnoSeleccionado = Horario::find($this->id_horarioAsig);

                    $repiteTurno = AsignacionHorario::join('plataforma.horario as ho', 'ho.id', '=', 'asignacion_horario.id_horario')
                        ->where('asignacion_horario.id_especialista', $this->idEsp)
                        ->where('asignacion_horario.id_dia', $this->id_diaAsig)
                        ->where('ho.turno', $turnoSeleccionado->turno)
                        ->where('asignacion_horario.estado', true)
                        ->first();
                    if ($repiteTurno) {
                        $this->dispatch('yaExisteTurno');
                    } else {
                        $dentroIntervalo = AsignacionHorario::join('plataforma.horario as ho', 'ho.id', '=', 'asignacion_horario.id_horario')
                            ->where('asignacion_horario.id_especialista', $this->idEsp)
                            ->where('asignacion_horario.id_dia', $this->id_diaAsig)
                            ->where('ho.turno', $turnoSeleccionado->turno)
                            ->where('asignacion_horario.estado', true)
                            ->first();

                        if ($dentroIntervalo) {
                            $enIntervalo = Carbon::parse($this->fecha_inicioAsig)->between(Carbon::parse($dentroIntervalo->fecha_inicio), Carbon::parse($dentroIntervalo->fecha_fin));
                            $this->dispatch('yaExisteIntervalo');
                        } else {
                            if ($this->id_diaAsig == 7) {
                                for ($i = 1; $i <= 5; $i++) {
                                    // Verificar si ya existe un horario con el mismo día, horario, especialista y fechas
                                    $existeHorario = AsignacionHorario::where('id_dia', $i)
                                        ->where('id_horario', $this->id_horarioAsig)
                                        ->where('id_especialista', $this->idEsp)
                                        ->where(function ($query) {
                                            $query->where('fecha_inicio', $this->fecha_inicioAsig)
                                                ->where('fecha_fin', $this->fecha_finAsig);
                                        })
                                        ->exists(); // Esto devuelve true si existe un registro que coincide.

                                    if (!$existeHorario) {
                                        // Si no existe, crea el nuevo horario
                                        AsignacionHorario::create([
                                            'fecha_inicio' => $this->fecha_inicioAsig,
                                            'fecha_fin' => $this->fecha_finAsig,
                                            'estado' => true,
                                            'id_dia' => $i,
                                            'id_horario' => $this->id_horarioAsig,
                                            'id_especialista' => $this->idEsp,
                                            'id_consultorio' => $this->id_consultorioAsig,
                                            'id_user_created' => auth()->user()->id,
                                        ]);
                                    } else {
                                        // Opcional: Manejar el caso en que ya exista el horario con las mismas fechas
                                        session()->flash('message', "El horario ya existe para el día {$i} en el rango de fechas indicado.");
                                    }
                                }
                            }

                            // if ($this->id_diaAsig == 7) {

                            //     for ($i = 1; $i <= 5; $i++) {
                            //         $nuevaAsignacion = AsignacionHorario::create([
                            //             'fecha_inicio' => $this->fecha_inicioAsig,
                            //             'fecha_fin' => $this->fecha_finAsig,
                            //             'estado' => true,
                            //             'id_dia' => $i,
                            //             'id_horario' => $this->id_horarioAsig,
                            //             'id_especialista' => $this->idEsp,
                            //             'id_consultorio' => $this->id_consultorioAsig,
                            //             'id_user_created' => auth()->user()->id,
                            //         ]);
                            //     }
                            // }

                            $nuevaAsignacion = AsignacionHorario::create([
                                'fecha_inicio' => $this->fecha_inicioAsig,
                                'fecha_fin' => $this->fecha_finAsig,
                                'estado' => true,
                                'id_dia' => $this->id_diaAsig,
                                'id_horario' => $this->id_horarioAsig,
                                'id_especialista' => $this->idEsp,
                                'id_consultorio' => $this->id_consultorioAsig,
                                'id_user_created' => auth()->user()->id,
                            ]);
                            DB::commit();
                            $this->dispatch('registroExitoso');
                        }
                    }
                }
            }
            /*$this->fecha_inicioAsig = '';
            $this->fecha_finAsig = '';
            $this->id_diaAsig = '';
            $this->id_horarioAsig = '';
            $this->id_consultorioAsig = '';*/
        } catch (\Throwable $e) {
            $this->fecha_inicioAsig = '';
            $this->fecha_finAsig = '';
            $this->id_diaAsig = '';
            $this->id_horarioAsig = '';
            $this->id_consultorioAsig = '';
            DB::rollBack();
            $this->dispatch('yaExisteHorario');

            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error en asignar horario", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }

        // dd($nuevaAsignacion);
    }
    public function verificarHorario() {}
    // funcion para mostrar en la tabla
    public function mostrarHorario($idEsp)
    {
        // dd('muestra?',$this->idEsp);
        if ($idEsp) {
            $this->horarios = AsignacionHorario::where('id_especialista', $this->idEsp)
                ->join('plataforma.dia', 'plataforma.asignacion_horario.id_dia', '=', 'dia.id')
                ->join('plataforma.consultorio', 'asignacion_horario.id_consultorio', '=', 'consultorio.id')
                ->join('plataforma.sedes', 'consultorio.id_sedes', '=', 'sedes.id')
                ->join('plataforma.horario', 'plataforma.asignacion_horario.id_horario', '=', 'horario.id')
                ->select('asignacion_horario.*', 'dia.nombre as dia_nombre', 'consultorio.numero_consultorio', 'sedes.piso', 'sedes.ubicacion', 'horario.hora_inicio', 'horario.hora_fin', 'horario.intervalo', 'horario.numero_paciente', 'horario.turno')
                ->orderBy('id_dia', 'asc')
                ->get();
            //dd($this->horarios);
            return $this->horarios;
        }
    }

    public function ValidarHorario()
    {
        try {
            //code...

            $repiteAsignacion = AsignacionHorario::where('id_especialista', $this->idEsp)
                ->where('id_horario', $this->id_horarioAsig)
                ->where('id_dia', $this->id_diaAsig)
                ->where('estado', true)
                ->first();
            if ($repiteAsignacion) {
                $this->dispatch('yaExisteHorario');
            } else {
                $turnoSeleccionado = Horario::find($this->id_horarioAsig);

                $repiteTurno = AsignacionHorario::join('plataforma.horario as ho', 'ho.id', '=', 'asignacion_horario.id_horario')
                    ->where('asignacion_horario.id_especialista', $this->idEsp)
                    ->where('asignacion_horario.id_dia', $this->id_diaAsig)
                    ->where('ho.turno', $turnoSeleccionado->turno)
                    ->where('asignacion_horario.estado', true)
                    ->first();
                if ($repiteTurno) {
                    $this->dispatch('yaExisteTurno');
                } else {
                    $dentroIntervalo = AsignacionHorario::join('plataforma.horario as ho', 'ho.id', '=', 'asignacion_horario.id_horario')
                        ->where('asignacion_horario.id_especialista', $this->idEsp)
                        ->where('asignacion_horario.id_dia', $this->id_diaAsig)
                        ->where('ho.turno', $turnoSeleccionado->turno)
                        ->where('asignacion_horario.estado', true)
                        ->first();

                    if ($dentroIntervalo) {
                        $enIntervalo = Carbon::parse($this->fecha_inicioAsig)->between(Carbon::parse($dentroIntervalo->fecha_inicio), Carbon::parse($dentroIntervalo->fecha_fin));
                        $this->dispatch('yaExisteIntervalo');
                    } /*else {
                    dd('jajajajajaja');
                }*/
                }
            }
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error en validadr horario", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    // boton cerrar
    public function cancel()
    {
        return redirect()->route('plataforma.especialistas.index');
    }
    public $numero_consultorio_edit;
    public $piso_edit;
    public $ubicacion_edit;
    public $hora_inicio_edit;
    public $hora_fin_edit;
    public $intervalo_edit;
    public $numero_paciente_edit;

    // TODO: Editar servicio+
    public $id_Agignacionhorario_edit;
    public $idEsp_edit;
    public $id_horarioAsig_edit;
    public $id_diaAsig_edit;
    public $fecha_inicioAsig_edit;
    public $fecha_finAsig_edit;
    public $id_consultorioAsig_edit;

    public function editarHorario()
    {
        //dd('Y este, que tal?');
        try {
            //code...

            $bajar_horario = AsignacionHorario::where('id', $this->id_Agignacionhorario_edit)->update([
                'estado' => false
            ]);
            if ($bajar_horario) {
                $nuevaAsignacion = AsignacionHorario::create([
                    'fecha_inicio' => $this->fecha_inicioAsig_edit,
                    'fecha_fin' => $this->fecha_finAsig_edit,
                    'estado' => true,
                    'id_dia' => $this->id_diaAsig_edit,
                    'id_horario' => $this->id_horarioAsig_edit,
                    'id_especialista' => $this->idEsp_edit,
                    'id_consultorio' => $this->id_consultorioAsig_edit,
                    'id_user_created' => auth()->user()->id,
                ]);
                $this->dispatch('editarAsignarHorario_exitoso');
                $this->dispatch('registroExitoso');
            }
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error en editar horario", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }
    public function editar_servicio($dato)
    {
        try {
            //code...

            // dd("asd", $dato);
            // $this->numero_consultorio_edit = $dato->numero_consultorio;
            // $this->piso_edit = $dato->piso;
            // $this->ubicacion_edit = $dato['ubicacion'];
            // $this->hora_inicio_edit = $dato['hora_inicio'];
            // $this->hora_fin_edit = $dato['hora_fin'];
            // $this->intervalo_edit = $dato['intervalo'];
            // $this->numero_paciente_edit = $dato['numero_paciente'];
            // $this->fecha_inicioAsig
            // dd($dato['fecha_inicio']);
            $this->id_Agignacionhorario_edit = $dato['id'];
            $this->idEsp_edit = $dato['id_especialista'];
            $this->id_horarioAsig_edit = $dato['id_horario'];
            $this->id_diaAsig_edit = $dato['id_dia'];
            $this->fecha_inicioAsig_edit = $dato['fecha_inicio'];
            $this->fecha_finAsig_edit = $dato['fecha_fin'];
            $this->id_consultorioAsig_edit = $dato['id_consultorio'];
            // dd($this->fecha_inicioAsig, $this->fecha_finAsig);
            // $this->id_horarioAsig = $id;
            $this->dispatch('editarAsignarHorario');
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error en especifica el error", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }
}
