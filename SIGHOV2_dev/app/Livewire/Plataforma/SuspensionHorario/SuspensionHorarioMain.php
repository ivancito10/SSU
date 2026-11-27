<?php

namespace App\Livewire\Plataforma\SuspensionHorario;

use App\Models\Plataforma\Agenda;
use App\Models\Plataforma\SuspensionHorario;
use App\Services\ReplicarAgendaS1;
use App\Services\ServiciosCachecito;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;

class SuspensionHorarioMain extends Component
{
    public $idSuspension;
    public $foto;
    public $permanente;
    public $nombres;
    public $carnet;
    public $especialidad;
    public $grado_academico;
    public $fecha_contrato_inicio;
    public $fecha_contrato_fin;

    public $estado;
    public $nombreCreador;
    public $created_at;

    public $fecha_suspension;
    public $turno;
    public $motivoSuspension;
    public $observacion;
    public $switchPersona;
    public $nombrePersona;
    public $matriculaPersona;

    public $rango;
    public $switchAnulado;
    public $nombreAnula;
    public $updated_at;

    public function render()
    {
        return view('livewire.Plataforma.suspension-horario.suspension-horario-main');
    }

    public function mount()
    {

        //$datitos = new ServiciosCachecito();
        //dd($datitos->obtenerPersonas());
        //dd($datitos->listaEspecialidadesHabilitadas());
        //dd($datitos->listaEspecialistasHabilitados());
        //dd($datitos->listaHorarios());
        //$datitos->actualizarDatosComprobanteRaquelita();
    }

    public function agregarSuspencion()
    {
        $this->dispatch('ModalListadoEspecialidades');
    }

    public function agregarSuspensionMasiva()
    {
        return redirect()->route('plataforma.suspension-horario.masivo');
    }

    #[On('verSuspension')]
    public function verSuspension($data)
    {
        $this->idSuspension = $data;
        $this->mostrarSuspension($this->idSuspension);
        $this->dispatch('modalVerSuspension');
    }

    public function mostrarSuspension($idSuspension)
    {
        try {
            $this->switchPersona = false;
            $this->switchAnulado = false;
            $datosSuspension = SuspensionHorario::with([
                'especialistaSuspensionHorario.personaEspecialista',
                'especialistaSuspensionHorario.especialidadEspecialista',
                'motivoSuspensionHorario',
                'usuarioCreador',
                'usuarioEditor'

            ])
                ->find($idSuspension);

            if ($datosSuspension) {
                $this->foto = $this->obtenerImagenBase64($datosSuspension->especialistaSuspensionHorario->foto);
                $this->permanente = $datosSuspension->especialistaSuspensionHorario->permanente;
                $this->nombres = implode(' ', array_filter([
                    $datosSuspension->especialistaSuspensionHorario->personaEspecialista->p_apellido,
                    $datosSuspension->especialistaSuspensionHorario->personaEspecialista->s_apellido,
                    $datosSuspension->especialistaSuspensionHorario->personaEspecialista->nombres,
                ]));
                $this->carnet = $datosSuspension->especialistaSuspensionHorario->personaEspecialista->complemento ?
                    $datosSuspension->especialistaSuspensionHorario->personaEspecialista->ci . ' - ' .
                    $datosSuspension->especialistaSuspensionHorario->personaEspecialista->complemento :
                    $datosSuspension->especialistaSuspensionHorario->personaEspecialista->ci;

                $this->especialidad = $datosSuspension->especialistaSuspensionHorario->especialidadEspecialista->especialidad;
                $this->grado_academico = $datosSuspension->especialistaSuspensionHorario->grado_academico;
                $this->fecha_contrato_inicio = $datosSuspension->especialistaSuspensionHorario->fecha_contrato_inicio;
                $this->fecha_contrato_fin = $datosSuspension->especialistaSuspensionHorario->fecha_contrato_fin;

                $this->estado = $datosSuspension->estado;
                $this->nombreCreador = $datosSuspension->usuarioCreador->name;
                $this->created_at = Carbon::parse($datosSuspension->created_at)->format('d/m/Y');

                $this->fecha_suspension = Carbon::parse($datosSuspension->fecha_suspension)->format('Y-m-d');
                $this->motivoSuspension = $datosSuspension->motivoSuspensionHorario->nombre_motivo;
                $this->observacion = $datosSuspension->observacion ? $datosSuspension->observacion : $datosSuspension->motivoSuspensionHorario->nombre_motivo;
                $datosAgenda = Agenda::where('id_suspension_horario', $idSuspension)
                    ->with(['asignacionHorarioAgenda', 'asignacionHorarioAgenda.horarioAsignacionHorario', 'personaAgenda'])
                    ->orderBy('hora_agenda', 'asc')
                    ->get();

                if (count($datosAgenda) > 0) {
                    $primeraAgenda = $datosAgenda->first();
                    $ultimaAgenda = $datosAgenda->last();
                    if ($primeraAgenda->id_persona) {
                        $this->switchPersona = true;
                        $this->nombrePersona = implode(' ', array_filter([
                            $primeraAgenda->personaAgenda->p_apellido,
                            $primeraAgenda->personaAgenda->s_apellido,
                            $primeraAgenda->personaAgenda->nombres,
                        ]));

                        $this->matriculaPersona = $primeraAgenda->personaAgenda->matricula_seguro;
                    }

                    $this->turno = $primeraAgenda->asignacionHorarioAgenda->horarioAsignacionHorario->turno;
                    if (count($datosAgenda) > 1) {
                        $this->rango = Carbon::parse($primeraAgenda->hora_agenda)->format('H:i') . ' - ' . Carbon::parse($ultimaAgenda->hora_agenda)
                            ->addMinutes($ultimaAgenda->asignacionHorarioAgenda->horarioAsignacionHorario->intervalo)->format('H:i');
                    } else {
                        $this->rango = Carbon::parse($primeraAgenda->hora_agenda)->format('H:i') . ' - ' . Carbon::parse($primeraAgenda->hora_agenda)
                            ->addMinutes($primeraAgenda->asignacionHorarioAgenda->horarioAsignacionHorario->intervalo)->format('H:i');
                    }
                }

                if ($datosSuspension->id_user_updated !== null) {
                    $this->switchAnulado = true;
                    $this->nombreAnula = $datosSuspension->usuarioEditor->name;
                    $this->updated_at = Carbon::parse($datosSuspension->updated_at)->format('d/m/Y');
                }
            }
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error en mostrarSuspension", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
        //dd($this->datosSuspension);
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

            Log::error("Error en obtenerImagenBase64- suspencion horario", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
            return null;
        }
    }

    #[On('anularSuspension')]
    public function anularSuspension($data)
    {
        $this->idSuspension = $data;
        $this->dispatch('preguntarAnular');
    }

    #[On('guardarAnulacion')]
    public function guardarAnulacion($data)
    {
        try {
            //code...

            $anulacionSighov1 = new ReplicarAgendaS1();
            $suspension = SuspensionHorario::find($this->idSuspension);

            if ($suspension) {
                $suspension->estado = false;
                $suspension->id_user_updated = auth()->user()->id;
                $suspension->save();
            }

            $agendas = Agenda::where('id_suspension_horario', $this->idSuspension)
                ->get();

            foreach ($agendas as $key => $agenda) {
                $agenda->fecha_anulacion = Carbon::now();
                $agenda->motivo_anulacion = $data;
                $agenda->anulacion_ficha = true;
                $agenda->clave_unica = $agenda->clave_unica . '-A-' . Carbon::now()->format('H:i:s');
                $agenda->id_usuario_anula = auth()->user()->id;
                $agenda->id_user_updated = auth()->user()->id;
                $agenda->save();

                //PARA ANULAR LA AGENDA SIGHO V1
                $anulacionSighov1->enviarDatosAnulacionAgenda($agenda->id);
            }
            $this->dispatch('pg:eventRefresh-SuspensionHorariosTable');
            $this->dispatch('anuladoCorrectamente');
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error en guardarAnulacion- suspencion horario", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }
}
