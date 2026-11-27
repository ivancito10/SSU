<?php

namespace App\Livewire\Plataforma\AgendaEspecialista;

use Livewire\Component;
use Livewire\Attributes\On;
use Carbon\Carbon;

use App\Models\Plataforma\Agenda;
use App\Models\Plataforma\Especialista;
use App\Models\Plataforma\AsignacionHorario;
use App\Models\Administracion\Especialidad;
use App\Models\Administracion\Persona;
use App\Models\Administracion\Departamento;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AgendaEspecialistaMain extends Component
{
    public $agenda = [];
    public $agendaLista = [];
    public $idEsp;

    public function render()
    {
        return view('livewire.Plataforma.agenda-especialista.agenda-especialista-main');
    }

    public function mount()
    {
        $this->mostrarEspecialista($this->idEsp);
    }

    public $nombres, $matricula, $ci, $complemento, $genero,
        $fecha_nacimiento, $expedido, $fecha_inicio, $fecha_fin,
        $permanente, $doc_especialidad, $grado, $fotografia, $es_extranjero;

    public function mostrarEspecialista($id)
    {   
        try{
            if ($id) {
                $esp = Especialista::find($id);
                $this->fecha_inicio = $esp->fecha_contrato_inicio;
                $this->fecha_fin = $esp->fecha_contrato_fin;
                $this->permanente = $esp->permanente;
                $this->grado = $esp->grado_academico;
                $this->fotografia = $this->obtenerImagenBase64($esp->foto);;
                $id_especialidad = $esp->id_especialidad;
                $id_persona = $esp->id_persona;

                $persona = Persona::find($id_persona);
                $this->nombres = $persona->nombres . ' ' . $persona->p_apellido . ' ' . $persona->s_apellido;
                $this->matricula = $persona->matricula_seguro;
                $this->ci = $persona->ci;
                $this->complemento = $persona->complemento;
                $this->es_extranjero = $persona->es_extranjero;

                $this->genero = $persona->sexo;
                $this->fecha_nacimiento = $persona->fecha_nacimiento;
                $id_dep_exp = $persona->id_dept_exp;

                $departamento = Departamento::find($id_dep_exp);
                
                if ($departamento->sigla != null) {
                    $this->expedido = $departamento->sigla;
                }

                $especialidad = Especialidad::find($id_especialidad);
                $this->doc_especialidad = $especialidad->especialidad;
            }
        }catch(\Throwable $e){
            Log::error("Error al mostrar especialista", [
                'usuario_id' => auth()->user()->id ?? null,
                'error'   => $e->getMessage() ?? '',
                'archivo'    => $e->getFile() ?? '',
                'linea'    => $e->getLine() ?? '',
                'fecha_hora'   => now(),
            ]);
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



    // citas del especialista
    public $fecha, $f_cita, $diaLiteral,  $diaNumero, $mesNumeral, $mesLiteral, $anio, $texto, $fecha_texto;

    public function calendario()
    {
        $this->f_cita = $this->fecha;

        $date = Carbon::parse($this->fecha);

        $this->diaNumero = $date->day;
        //$this->diaLiteral = $date->dayName;
        $dias = ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'];
        $diaLiteral = $dias[$date->dayOfWeek];

        $this->mesNumeral = $date->format('m');
        $this->mesLiteral = strtoupper($date->monthName);
        $mesTresLetras = substr($this->mesLiteral, 0, 3);
        $this->anio = $date->year;

        $this->texto = $this->diaNumero . '.' . $mesTresLetras . '.' . $this->anio;
        $this->fecha_texto = $diaLiteral . ' ' . $this->diaNumero . '.' . $this->mesNumeral . '.' . $this->anio;

        $this->agendaLista = $this->agendaFuncion($this->idEsp, $this->fecha);
        $this->fecha = ' ';
        $this->dispatch('modalCitas');
        $this->skipRender();
    }


    public function agendaFuncion($idEspecialista, $fecha)
    {
        if ($idEspecialista) {

            $this->agenda = Agenda::where('agenda.fecha_agenda', $fecha)
                ->join('plataforma.asignacion_horario as ah', 'agenda.id_asignacion_horario', '=', 'ah.id')
                ->join('plataforma.servicios_plataforma as sp', 'agenda.id_servicios_plataforma', '=', 'sp.id')
                ->join('administracion.persona as p', 'agenda.id_persona', '=', 'p.id')
                ->where('ah.id_especialista', $idEspecialista)
                ->orderBy('agenda.hora_agenda', 'asc')
                ->select('agenda.hora_agenda', 'sp.nombre_servicio', 'p.nombres', 'p.p_apellido')
                ->get();
        }
    }

    #[On('agendaEspecialista')]
    public function agendaEspecialista($data)
    {
        $this->idEsp = $data;
        $this->dispatch('modalCalendario');
    }
}
