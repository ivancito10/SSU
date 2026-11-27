<?php

namespace App\Livewire\Plataforma\AgendaEspecialista;

use Livewire\Component;

use App\Models\Plataforma\Agenda;
use App\Models\Plataforma\Especialista;
use App\Models\Plataforma\AsignacionHorario;
use App\Models\Administracion\Especialidad;
use App\Models\Administracion\Persona;
use App\Models\Administracion\Departamento;
use Livewire\Attributes\On;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EspecialistaCalendario extends Component
{

    public $idEsp;
    public $listeners = ['fechaSeleccionada'];
    public $datosEsp;
    // mostrar especialista
    public $nombres, $matricula, $ci, $complemento, $genero, $idEspecialidad, $carnet,
        $fecha_nacimiento, $expedido, $fecha_inicio, $fecha_fin,
        $permanente, $doc_especialidad, $grado, $fotografia, $es_extranjero;
    // fecha seleccinada
    public $consulta;
    public $fecha, $turno_m, $hora_inicio_m, $hora_fin_m, $nro_consultorio_m, $ubicacion_m, $piso_m, $hinicio_m, $hfin_m, $diaSemanaNumerico, $f_cita, $diaLiteral,  $diaNumero, $mesNumeral, $mesLiteral, $anio, $texto, $fecha_texto;
    public $consulta_tarde;
    public $turno_t, $hora_inicio_t, $hora_fin_t, $nro_consultorio_t, $ubicacion_t, $piso_t, $hinicio_t, $hfin_t;
    public $consulta_noche;
    public $turno_n, $hora_inicio_n, $hora_fin_n, $nro_consultorio_n, $ubicacion_n, $piso_n, $hinicio_n, $hfin_n;

    public $fecha_seleccionada;
    // para verificar si hay agenda por cada fecha
    public $agenda_por_fecha;

    public function render()
    {
        return view('livewire.Plataforma.agenda-especialista.especialista-calendario');
    }

    public function mount()
    {
        $this->mostrarEspecialista($this->idEsp);
        $this->obtenerAgendaPorFecha($this->idEsp);
    }



    public function mostrarEspecialista($id)
    {
        if ($id) {
            $esp = Especialista::find($id);
            $this->fecha_inicio = $esp->fecha_contrato_inicio;
            $this->fecha_fin = $esp->fecha_contrato_fin;
            $this->permanente = $esp->permanente;
            $this->grado = $esp->grado_academico;
            $this->fotografia = $this->obtenerImagenBase64($esp->foto);
            $id_especialidad = $esp->id_especialidad;
            $id_persona = $esp->id_persona;

            $persona = Persona::find($id_persona);
            if ($persona->p_apellido == null) {
                $this->nombres = $persona->s_apellido . ' ' . $persona->nombres;
            } elseif ($persona->s_apellido == null) {
                $this->nombres = $persona->p_apellido . ' ' . $persona->nombres;
            } else {
                $this->nombres = $persona->p_apellido . ' ' . $persona->s_apellido . ' ' . $persona->nombres;
            }
            $this->matricula = $persona->matricula_seguro;
            $this->ci = $persona->ci;
            $this->complemento = $persona->complemento;
            if ($persona->complemento == null) {
                $this->carnet = $persona->ci;
            } else {
                $this->carnet = $persona->ci . ' - ' . $persona->complemento;
            }
            $this->es_extranjero = $persona->es_extranjero;

            $this->genero = $persona->sexo;
            $this->fecha_nacimiento = $persona->fecha_nacimiento;
            $id_dep_exp = $persona->id_dept_exp;

            $departamento = Departamento::find($id_dep_exp);

            if ($departamento != null) {
                $this->expedido = $departamento->sigla;
            }


            $especialidad = Especialidad::find($id_especialidad);
            $this->idEspecialidad = $especialidad->id;
            $this->doc_especialidad = $especialidad->especialidad;
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
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al obtener imagen", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
            return null;
        }
    }

    public function fechaSeleccionada($dato)
    {
        try {
            $this->fecha_seleccionada = $dato;
            $this->turno_m = null;
            $this->turno_t = null;
            $this->turno_n = null;

            $date = Carbon::parse($dato);
            $this->diaNumero = $date->day;
            $diaSemanaNumerico = $date->dayOfWeek;
            //dd($diaSemanaNumerico);
            $this->diaNumero = $date->day;
            //$this->diaLiteral = $date->dayName;
            $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
            $this->diaLiteral = $dias[$date->dayOfWeek];

            $this->mesLiteral = strtoupper($date->monthName);
            $mesTresLetras = substr($this->mesLiteral, 0, 3);
            $this->anio = $date->year;
            $this->texto =  $this->diaNumero . '/' . $mesTresLetras . '/' . $this->anio;


            // Aca obtenemos la consulta de las agendas para tal fecha
            if ($this->idEsp) {

                //CONSULTA AGENDA PARA LA MAÑANA
                $this->consulta = AsignacionHorario::where('asignacion_horario.id_especialista', $this->idEsp)
                    ->join('plataforma.horario as h', 'asignacion_horario.id_horario', '=', 'h.id')
                    ->join('plataforma.consultorio as c', 'asignacion_horario.id_consultorio', '=', 'c.id')
                    ->join('plataforma.sedes as s', 'c.id_sedes', '=', 's.id')
                    ->join('plataforma.agenda as a', 'a.id_asignacion_horario', '=', 'asignacion_horario.id')
                    ->join('plataforma.servicios_plataforma as sp', 'a.id_servicio_plataforma', '=', 'sp.id')
                    ->join('administracion.persona as p', 'a.id_persona', '=', 'p.id')
                    ->join('administracion.tipo_asegurado as ta', 'p.id_tipo_asegurado', '=', 'ta.id')
                    ->join('administracion.users as u', 'a.id_user_created', '=', 'u.id')
                    ->where('asignacion_horario.id_dia', $diaSemanaNumerico)
                    ->where('h.turno', 'MAÑANA')
                    ->where('a.fecha_agenda', $date->format('Y-m-d'))
                    ->select(
                        'asignacion_horario.id',
                        'h.turno',
                        'a.fecha_agenda',
                        'a.hora_agenda',
                        'a.anulacion_ficha',
                        'a.id_suspension_horario',
                        'a.motivo_anulacion',
                        'sp.nombre_servicio',
                        'p.nombres',
                        'p.p_apellido',
                        'p.s_apellido',
                        'p.matricula_seguro',
                        'c.numero_consultorio',
                        's.ubicacion',
                        's.piso',
                        'h.hora_inicio',
                        'h.hora_fin',
                        'h.turno',
                        'ta.tipo_asegurado',
                        'a.created_at',
                        'u.name'
                    )
                    ->orderBy('a.hora_agenda', 'asc')
                    ->get();

                //CONSULTA AGENDA PARA LA TARDE
                $this->consulta_tarde = AsignacionHorario::where('asignacion_horario.id_especialista', $this->idEsp)
                    ->join('plataforma.horario as h', 'asignacion_horario.id_horario', '=', 'h.id')
                    ->join('plataforma.consultorio as c', 'asignacion_horario.id_consultorio', '=', 'c.id')
                    ->join('plataforma.sedes as s', 'c.id_sedes', '=', 's.id')
                    ->join('plataforma.agenda as a', 'a.id_asignacion_horario', '=', 'asignacion_horario.id')
                    ->join('plataforma.servicios_plataforma as sp', 'a.id_servicio_plataforma', '=', 'sp.id')
                    ->join('administracion.persona as p', 'a.id_persona', '=', 'p.id')
                    ->join('administracion.tipo_asegurado as ta', 'p.id_tipo_asegurado', '=', 'ta.id')
                    ->join('administracion.users as u', 'a.id_user_created', '=', 'u.id')
                    ->where('asignacion_horario.id_dia', $diaSemanaNumerico)
                    ->where('h.turno', 'TARDE')
                    ->where('a.fecha_agenda', $date->format('Y-m-d'))
                    ->select(
                        'asignacion_horario.id',
                        'h.turno',
                        'a.fecha_agenda',
                        'a.hora_agenda',
                        'a.anulacion_ficha',
                        'a.id_suspension_horario',
                        'a.motivo_anulacion',
                        'sp.nombre_servicio',
                        'p.nombres',
                        'p.p_apellido',
                        'p.s_apellido',
                        'p.matricula_seguro',
                        'c.numero_consultorio',
                        's.ubicacion',
                        's.piso',
                        'h.hora_inicio',
                        'h.hora_fin',
                        'h.turno',
                        'ta.tipo_asegurado',
                        'a.created_at',
                        'u.name'
                    )
                    ->orderBy('a.hora_agenda', 'asc')
                    ->get();

                //CONSULTA AGENDA PARA LA NOCHE
                $this->consulta_noche = AsignacionHorario::where('asignacion_horario.id_especialista', $this->idEsp)
                    ->join('plataforma.horario as h', 'asignacion_horario.id_horario', '=', 'h.id')
                    ->join('plataforma.consultorio as c', 'asignacion_horario.id_consultorio', '=', 'c.id')
                    ->join('plataforma.sedes as s', 'c.id_sedes', '=', 's.id')
                    ->join('plataforma.agenda as a', 'a.id_asignacion_horario', '=', 'asignacion_horario.id')
                    ->join('plataforma.servicios_plataforma as sp', 'a.id_servicio_plataforma', '=', 'sp.id')
                    ->join('administracion.persona as p', 'a.id_persona', '=', 'p.id')
                    ->join('administracion.tipo_asegurado as ta', 'p.id_tipo_asegurado', '=', 'ta.id')
                    ->join('administracion.users as u', 'a.id_user_created', '=', 'u.id')
                    ->where('asignacion_horario.id_dia', $diaSemanaNumerico)
                    ->where('h.turno', 'NOCHE')
                    ->where('a.fecha_agenda', $date->format('Y-m-d'))
                    ->select(
                        'asignacion_horario.id',
                        'asignacion_horario.id',
                        'h.turno',
                        'a.fecha_agenda',
                        'a.hora_agenda',
                        'a.anulacion_ficha',
                        'a.id_suspension_horario',
                        'a.motivo_anulacion',
                        'sp.nombre_servicio',
                        'p.nombres',
                        'p.p_apellido',
                        'p.s_apellido',
                        'p.matricula_seguro',
                        'c.numero_consultorio',
                        's.ubicacion',
                        's.piso',
                        'h.hora_inicio',
                        'h.hora_fin',
                        'h.turno',
                        'ta.tipo_asegurado',
                        'a.created_at',
                        'u.name'
                    )
                    ->orderBy('a.hora_agenda', 'asc')
                    ->get();

                if ($this->consulta && $this->consulta->isNotEmpty()) {
                    $this->ubicacion_m = $this->consulta->first()->ubicacion;
                    $this->piso_m = $this->consulta->first()->piso;
                    $this->nro_consultorio_m = $this->consulta->first()->numero_consultorio;
                    $this->hinicio_m = $this->consulta->first()->hora_inicio;
                    $this->hfin_m = $this->consulta->first()->hora_fin;
                    $this->turno_m = $this->consulta->first()->turno;
                }

                if ($this->consulta_tarde && $this->consulta_tarde->isNotEmpty()) {

                    $this->ubicacion_t = $this->consulta_tarde->first()->ubicacion;
                    $this->piso_t = $this->consulta_tarde->first()->piso;
                    $this->nro_consultorio_t = $this->consulta_tarde->first()->numero_consultorio;
                    $this->hinicio_t = $this->consulta_tarde->first()->hora_inicio;
                    $this->hfin_t = $this->consulta_tarde->first()->hora_fin;
                    $this->turno_t = $this->consulta_tarde->first()->turno;
                }

                if ($this->consulta_noche && $this->consulta_noche->isNotEmpty()) {

                    $this->ubicacion_n = $this->consulta_noche->first()->ubicacion;
                    $this->piso_n = $this->consulta_noche->first()->piso;
                    $this->nro_consultorio_n = $this->consulta_noche->first()->numero_consultorio;
                    $this->hinicio_n = $this->consulta_noche->first()->hora_inicio;
                    $this->hfin_n = $this->consulta_noche->first()->hora_fin;
                    $this->turno_n = $this->consulta_noche->first()->turno;
                }

                if ($this->consulta->isNotEmpty() || $this->consulta_tarde->isNotEmpty() || $this->consulta_noche->isNotEmpty()) {
                    //dd($this->consulta, $this->turno_m, $this->consulta_tarde, $this->turno_t, $this->consulta_noche, $this->turno_n);
                    $this->dispatch('openModal');
                } else {
                    $this->dispatch('sinRegistro');
                    $this->dispatch('renderizar');
                }
            }
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error en fecha seleccionada", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    public function verificarExistenciaAgenda($idEsp, $fecha, $turno)
    {
        $turnoLiteral = $turno === 1 ? 'MAÑANA' : ($turno === 2 ? 'TARDE' : 'NOCHE');
        $fecha_hoy = Carbon::parse($fecha)->format('Y-m-d');
        $date = Carbon::parse($fecha);
        $diaSemanaNumerico = $date->dayOfWeek;

        $consulta = Agenda::where('fecha_agenda', $fecha_hoy)
            ->whereNot('anulacion_ficha', true)
            ->whereHas('asignacionHorarioAgenda', function ($query) use ($idEsp, $diaSemanaNumerico, $turnoLiteral) {
                $query->where('id_especialista', $idEsp)
                    ->where('id_dia', $diaSemanaNumerico)
                    ->whereHas('horarioAsignacionHorario', function ($subQuery) use ($turnoLiteral) {
                        $subQuery->where('turno', $turnoLiteral);
                    });
            })
            ->with([
                'asignacionHorarioAgenda',
                'asignacionHorarioAgenda.horarioAsignacionHorario',
                'asignacionHorarioAgenda.consultorioAsignacionHorario',
                'servicioPlataformaAgenda',
                'personaAgenda',
                'personaAgenda.tipoAseguradoPersona'
            ])
            ->orderBy('hora_agenda', 'asc')
            ->get();

        return count($consulta) > 0;
    }

    // funcion para imprimir agenda turno mañana
    public function imprimir_agenda_mañana()
    {
        $turno = 1;
        if ($this->verificarExistenciaAgenda($this->idEsp, $this->fecha_seleccionada, $turno)) {
            // para que se descargue el pdf de agenda especialista  
            $this->js('window.open("' . route('pdf.generate.agenda', ['idEsp' => $this->idEsp, 'fecha' => $this->fecha_seleccionada, 'turno' => $turno]) . '", "_blank")');
            //$this->js('window.open("/plataforma/pdf_agenda_especialista/' .$this->idEsp. '", "_blank")');
            $this->skipRender();
            $this->dispatch('ReporteGenerado');
        } else {
            $this->skipRender();
            $this->dispatch('noExistenAgendasNoAnuladas');
        }
    }
    // funcion para imprimir agenda turno tarde
    public function imprimir_agenda_tarde()
    {
        $turno = 2;
        if ($this->verificarExistenciaAgenda($this->idEsp, $this->fecha_seleccionada, $turno)) {
            // para que se descargue el pdf de agenda especialista  
            $this->js('window.open("' . route('pdf.generate.agenda', ['idEsp' => $this->idEsp, 'fecha' => $this->fecha_seleccionada, 'turno' => $turno]) . '", "_blank")');
            //$this->js('window.open("/plataforma/pdf_agenda_especialista/' .$this->idEsp. '", "_blank")');
            $this->skipRender();
            $this->dispatch('ReporteGenerado');
        } else {
            $this->skipRender();
            $this->dispatch('noExistenAgendasNoAnuladas');
        }
    }
    // funcion para imprimir agenda turno noche 
    public function imprimir_agenda_noche()
    {
        $turno = 3;
        if ($this->verificarExistenciaAgenda($this->idEsp, $this->fecha_seleccionada, $turno)) {
            // para que se descargue el pdf de agenda especialista  
            $this->js('window.open("' . route('pdf.generate.agenda', ['idEsp' => $this->idEsp, 'fecha' => $this->fecha_seleccionada, 'turno' => $turno]) . '", "_blank")');
            //$this->js('window.open("/plataforma/pdf_agenda_especialista/' .$this->idEsp. '", "_blank")');
            $this->skipRender();
            $this->dispatch('ReporteGenerado');
        } else {
            $this->skipRender();
            $this->dispatch('noExistenAgendasNoAnuladas');
        }
    }

    // boton cerrar
    public function atras()
    {
        return redirect()->route('plataforma.agenda-especialista.index');
    }

    public function verificarExistenciaAgendaBoucher($idAsignacionHorario, $fecha)
    {
        $fecha_hoy = Carbon::parse($fecha)->format('Y-m-d');
        $agendados = Agenda::where('fecha_agenda', $fecha_hoy)
            ->whereNot('anulacion_ficha', true)
            ->where('id_asignacion_horario', $idAsignacionHorario)
            ->with([
                'asignacionHorarioAgenda',
                'asignacionHorarioAgenda.horarioAsignacionHorario',
                'asignacionHorarioAgenda.consultorioAsignacionHorario',
                'servicioPlataformaAgenda',
                'personaAgenda',
                'personaAgenda.tipoAseguradoPersona',
                'suspensionHorarioAgenda'
            ])
            ->orderby('hora_agenda', 'asc')
            ->get();
        if (count($agendados) > 0) {
            $datos_especialista = Especialista::with('personaEspecialista', 'especialidadEspecialista')
                ->where('id', $agendados[0]->asignacionHorarioAgenda->id_especialista)
                ->first();
        }
        return count($agendados) > 0 && $datos_especialista;
    }

    public function imprimir_boucher_mañana()
    {

        if ((count($this->consulta) > 0) && ($this->verificarExistenciaAgendaBoucher($this->consulta[0]->id, $this->fecha_seleccionada))) {
            $this->js('window.open("' . route('pdf.boucher.agenda', ['idEsp' => $this->consulta[0]->id, 'fecha' => $this->fecha_seleccionada]) . '", "_blank")');
            $this->dispatch('BoucherGenerado');
            $this->skipRender();
        } else {
            $this->skipRender();
            $this->dispatch('noExistenAgendasNoAnuladas');
        }
    }
    public function imprimir_boucher_tarde()
    {
        if ((count($this->consulta_tarde) > 0) && ($this->verificarExistenciaAgendaBoucher($this->consulta_tarde[0]->id, $this->fecha_seleccionada))) {
            $this->verificarExistenciaAgendaBoucher($this->consulta_tarde[0]->id, $this->fecha_seleccionada);
            $this->js('window.open("' . route('pdf.boucher.agenda', ['idEsp' => $this->consulta_tarde[0]->id, 'fecha' => $this->fecha_seleccionada]) . '", "_blank")');
            $this->dispatch('BoucherGenerado');
            $this->skipRender();
        } else {
            $this->skipRender();
            $this->dispatch('noExistenAgendasNoAnuladas');
        }
    }
    public function imprimir_boucher_noche()
    {
        if ((count($this->consulta_noche) > 0) && ($this->verificarExistenciaAgendaBoucher($this->consulta_noche[0]->id, $this->fecha_seleccionada))) {
            $this->js('window.open("' . route('pdf.boucher.agenda', ['idEsp' => $this->consulta_noche[0]->id, 'fecha' => $this->fecha_seleccionada]) . '", "_blank")');
            $this->dispatch('BoucherGenerado');
            $this->skipRender();
        } else {
            $this->skipRender();
            $this->dispatch('noExistenAgendasNoAnuladas');
        }
    }
    // idEsp es id_ especialista

    public function obtenerAgendaPorFecha($id_especialista)
    {
        $asignacion = AsignacionHorario::where('id_especialista', $id_especialista)
            // ->get();
            ->pluck('id');
        //dd($asignacion);
        $this->agenda_por_fecha = Agenda::whereIn('id_asignacion_horario', $asignacion)
            //->where('anulacion_ficha', false)
            ->distinct()
            ->pluck('fecha_agenda')->toArray();
        //  dd($this->agenda_por_fecha);
    }
}
