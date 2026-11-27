<?php

namespace App\Livewire\Administracion\Dashboard\Plataforma;
// Administracion/Dashboard/Plataforma
use App\Models\Administracion\Persona;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Plataforma\Agenda;
use App\Models\Afiliacion\Titular;
use App\Models\Plataforma\AsignacionHorario;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Services\AnulacionAgendaService;
use App\Services\ReplicarAgendaS1;
use App\Services\SocketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgendaAsegurado extends Component
{
    public $nombreCompleto, $matricula, $tipoAsegurado, $agendasTitular;
    public $tieneGrupoFamiliar, $datosBeneficiarios, $datosAgendaBeneficiario;
    public $agenda_por_fecha, $agenda_datos;

    // datos de agenda
    public $edad, $fecha_cita, $dia_agenda, $tipo_ficha, $creacion_ficha, $hora_agenda,
        $ubicacion_ficha, $piso, $consultorio, $especialidad_agenda, $especialista_agenda, $horario, $turno;
    public $fotoEspecialistaAgendaExistente, $foto_nombre;
    public function render()
    {
        return view('livewire.Administracion.dashboard.plataforma.agenda-asegurado');
    }
    public function mount()
    {
        $this->funcionPrincipal(auth()->user()->id);
        $this->obtenerAgendaPorFecha(auth()->user()->id);
    }

    public function funcionPrincipal($id)
    {
        $user = User::where('id', $id)->first();
        $datos = $this->obtenerDatosPersonales($user->id_persona);
        $this->nombreCompleto = $datos['nombreCompleto'];
        $this->matricula = $datos['matricula'];
        $this->tipoAsegurado = $datos['tipo_asegurado'];
        $this->agendasTitular = $this->obtenerDatosAgenda($user->id_persona);
        //dd($this->agendasTitular);
        $titular = Titular::where('id_persona', $user->id_persona)
            ->orderBy('id', 'desc')
            ->first();
        if ($titular) {
            $beneficiarios = Beneficiario::where('id_titular', $titular->id)
                ->where('estado', true)
                ->orderBy('id', 'desc')
                ->get();

            if ($beneficiarios->isNotEmpty()) {
                $this->tieneGrupoFamiliar = true;
                $this->datosBeneficiarios = $beneficiarios->map(
                    function ($ben) {
                        $datosBen = $this->obtenerDatosPersonales($ben->id_persona);
                        $datosAgenda = $this->obtenerDatosAgenda($ben->id_persona);
                        //dd($datosAgenda);
                        return [
                            'nombreBeneficiario' => $datosBen['nombreCompleto'],
                            'matriculaBeneficiario' => $datosBen['matricula'],
                            'agendas' => $datosAgenda,
                        ];
                    }
                );
                //dd($this->datosBeneficiarios);
            } else {
                $this->tieneGrupoFamiliar = false;
            }
        }
    }
    public function obtenerDatosPersonales($id_persona)
    {
        $datos_personales = Persona::where('id', $id_persona)
            ->with('tipoAseguradoPersona')
            ->first();
        $nombreCompleto = $datos_personales->nombres . ' ' . $datos_personales->p_apellido . ' ' . $datos_personales->s_apellido;
        $matricula = $datos_personales->matricula_seguro;
        $tipo_asegurado = $datos_personales->tipoAseguradoPersona->tipo_asegurado;
        return [
            'nombreCompleto' => $nombreCompleto,
            'matricula' => $matricula,
            'tipo_asegurado' => $tipo_asegurado,
        ];
    }

    public function obtenerDatosAgenda($id_persona)
    {
        $agendas = Agenda::where('id_persona', $id_persona)
            ->with('servicioPlataformaAgenda', 'asignacionHorarioAgenda.especialistaAsignacionHorario.personaEspecialista', 'asignacionHorarioAgenda.especialistaAsignacionHorario.especialidadEspecialista')
            ->limit(10)
            ->orderBy('fecha_agenda', 'desc')
            ->get();
        $datos = $agendas->map(function ($agenda) {
            $esSuspension = $agenda->id_suspension_horario !== null ? true : false;
            return [
                'id_agenda' => $agenda->id,
                'fecha' => $agenda->fecha_agenda,
                'especialista' => $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->nombres . ' ' . $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->p_apellido . ' ' . $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->s_apellido,
                'especialidad' => $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->especialidadEspecialista->especialidad,
                'tipo' => $agenda->servicioPlataformaAgenda->nombre_servicio,
                'hora' => $agenda->hora_agenda,
                'estado' => $agenda->anulacion_ficha ? 'ANULADO' : 'AGENDADO',
                'esSuspension' => $esSuspension,
            ];
        });
        // dd($datos);
        return $datos;
    }


    public function obtenerDatosAgendaPorFecha($id_persona, $fecha)
    {
        $agendas = Agenda::where('id_persona', $id_persona)
            ->with(
                'servicioPlataformaAgenda',
                'personaAgenda.tipoAseguradoPersona',
                'asignacionHorarioAgenda.especialistaAsignacionHorario.personaEspecialista',
                'asignacionHorarioAgenda.especialistaAsignacionHorario.especialidadEspecialista'
            )
            ->where('fecha_agenda', $fecha)
            ->where('anulacion_ficha', false)
            ->get();
        //dd($agendas);
        if ($agendas->isEmpty()) {
            return [];
        }
        $datos = [];
        foreach ($agendas as $agenda) {
            $datos[] = [
                'nombre_paciente' => trim(
                    ($agenda->personaAgenda?->nombres ?? '') . ' ' .
                        ($agenda->personaAgenda?->p_apellido ?? '') . ' ' .
                        ($agenda->personaAgenda?->s_apellido ?? '')
                ),
                'matricula_paciente' => $agenda->personaAgenda->matricula_seguro,
                'tipo_asegurado' => $agenda->personaAgenda->tipoAseguradoPersona->tipo_asegurado ?: 'NO ASEGURADO',
                'edad' => Carbon::parse($agenda->personaAgenda->fecha_nacimiento)->age ?? null,
                'fecha_cita' => Carbon::parse($agenda->fecha_agenda)->Format('d /m/ Y '),
                'dia_agenda' => $agenda->asignacionHorarioAgenda->diaAsignacionHorario->nombre,
                'hora_agenda' => $agenda->hora_agenda,
                'tipo_ficha' => $agenda->servicioPlataformaAgenda->nombre_servicio,
                'creacion_ficha' => $agenda->created_at,
                'ubicacion_ficha' => $agenda->asignacionHorarioAgenda->consultorioAsignacionHorario->consultorioSedes->ubicacion,
                'piso' => $agenda->asignacionHorarioAgenda->consultorioAsignacionHorario->consultorioSedes->piso,
                'consultorio' => $agenda->asignacionHorarioAgenda->consultorioAsignacionHorario->numero_consultorio,
                'especialidad_agenda' => $agenda->asignacionHorarioAgenda->especialistaDetalles->especialidadEspecialista->especialidad,
                'especialista_agenda' => trim(
                    ($agenda->asignacionHorarioAgenda?->especialistaDetalles?->personaEspecialista?->nombres ?? '') . ' ' .
                        ($agenda->asignacionHorarioAgenda?->especialistaDetalles?->personaEspecialista?->p_apellido ?? '') . ' ' .
                        ($agenda->asignacionHorarioAgenda?->especialistaDetalles?->personaEspecialista?->s_apellido ?? '')
                ),
                'horario' => ($agenda->asignacionHorarioAgenda?->horarioAsignacionHorario?->hora_inicio ?? '') . ' - ' .
                    ($agenda->asignacionHorarioAgenda?->horarioAsignacionHorario?->hora_fin ?? ''),
                'turno' => $agenda->asignacionHorarioAgenda->horarioAsignacionHorario->turno,
            ];
        }

        return $datos;
    }


    public function obtenerAgendaPorFecha($id_usuario)
    {
        $user = User::where('id', $id_usuario)->first();
        $datos = $this->obtenerDatosPersonales($user->id_persona);
        $this->tipoAsegurado = $datos['tipo_asegurado'];
        if ($this->tipoAsegurado === 'TITULAR') {
            $ids_personas = [$user->id_persona];
            $titular = Titular::where('id_persona', $user->id_persona)
                ->orderBy('id', 'desc')
                ->first();
            if ($titular) {
                $beneficiarios = Beneficiario::where('id_titular', $titular->id)
                    ->orderBy('id', 'desc')
                    ->get();
                $ids_personas = array_merge(
                    $ids_personas,
                    $beneficiarios->pluck('id_persona')->toArray()
                );
            }

            $this->agenda_por_fecha = Agenda::whereIn('id_persona', $ids_personas)
                ->where('anulacion_ficha', false)
                ->distinct()
                ->pluck('fecha_agenda')
                ->toArray();
        } else {
            $this->agenda_por_fecha = Agenda::where('id_persona', $user->id_persona)
                ->where('anulacion_ficha', false)
                ->distinct()
                ->pluck('fecha_agenda')
                ->toArray();
        }
        // dd($this->tipoAsegurado);
        // dd($this->agenda_por_fecha);
    }

    public function goAgendamientoWeb()
    {
        return redirect()->route('plataforma.agenda-web.index');
    }


    #[On('detalleAgenda')]
    public function detalleAgenda($fecha)
    {
        $user = User::where('id', auth()->user()->id)->first();
        $datos = $this->obtenerDatosPersonales($user->id_persona);
        $this->tipoAsegurado = $datos['tipo_asegurado'];
        $ids_personas = [$user->id_persona];

        if ($this->tipoAsegurado === 'TITULAR') {
            $titular = Titular::where('id_persona', $user->id_persona)
                ->orderBy('id', 'desc')
                ->first();
            if ($titular) {
                $beneficiarios = Beneficiario::where('id_titular', $titular->id)
                    ->where('estado', true)
                    ->orderBy('id', 'desc')
                    ->get();

                $ids_personas = array_merge(
                    $ids_personas,
                    $beneficiarios->pluck('id_persona')->toArray()
                );
            }
        }
        $datos_agenda = [];
        foreach ($ids_personas as $id_per) {
            $agendas = $this->obtenerDatosAgendaPorFecha($id_per, $fecha);

            if (!empty($agendas)) {
                foreach ($agendas as &$agenda) {
                    if ($id_per == $user->id_persona) {
                        $agenda['propio'] = 'PROPIO';
                    } else {
                        $agenda['propio'] = 'OTRO';
                    }
                }
                $datos_agenda = array_merge($datos_agenda, $agendas);
                //dd($datos_agenda);
            }
        }
        if (!empty($datos_agenda)) {
            $this->agenda_datos = $datos_agenda;
            // dd($this->agenda_datos);
            $this->dispatch('abrirModalAgenda');
        } else {
            $this->dispatch('noHayAgenda');
        }
        // dd($ids_personas);
    }


    public function resete()
    {
        $this->reset(
            'edad',
            'fecha_cita',
            'dia_agenda',
            'hora_agenda',
            'tipo_ficha',
            'creacion_ficha',
            'ubicacion_ficha',
            'piso',
            'consultorio',
            'especialidad_agenda',
            'especialista_agenda',
            'horario',
            'turno'
        );
    }
    private function obtenerImagenBase64($idFoto, $liga)
    {

        try {
            if ($idFoto) {
                $path = $liga . $idFoto . '.jpg';
                //dd($path);
                $imageData = Storage::disk('ftp')->get($path);
                $foto_nombre = base64_encode($imageData);
                // dd($idFoto, $liga, $foto_nombre);
                if ($foto_nombre === '') {
                    return null;
                } else {
                    return $foto_nombre;
                }
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
    //----anular

    #[On('anularAgenda')]
    public function anularAgenda(Request $request, $id_agenda, string $motivo)
    {
        // dd('adasd', $id_agenda, $motivo);
        $anulacionSighov1 = new ReplicarAgendaS1();
        $socketService = new SocketService();
        $anulacionAgendaService = new AnulacionAgendaService();
        try {
            DB::beginTransaction();
            $anularAgenda = Agenda::find($id_agenda);
            if ($anularAgenda) {
                $anularAgenda->fecha_anulacion = Carbon::now();
                $anularAgenda->motivo_anulacion = $motivo;
                $anularAgenda->anulacion_ficha = true;
                $anularAgenda->ip = $request->ip();
                $anularAgenda->nombre_equipo = gethostname();
                $anularAgenda->clave_unica = $anularAgenda->clave_unica . '-A-' . Carbon::now()->format('H:i:s');
                $anularAgenda->id_usuario_anula = auth()->user()->id;
                $anularAgenda->id_user_updated = auth()->user()->id;
                $anularAgenda->save();
                // PARA ACTUALIZAR LA APK EN TIEMPO REAL
                // $socketService->emision_anulacion_socket($anularAgenda);
                // PARA GUARDAR LA ANULACION+
                // dd($anularAgenda);
                $mesajeAnular = $anulacionAgendaService->bloquear_anular_agenda($anularAgenda);
                // dd($mesajeAnular['mensaje']);
                if ($mesajeAnular['estado_anulacion']) {
                    $this->dispatch('limiteAnulaciones', detail: $mesajeAnular['mensaje']);
                    DB::rollBack();
                    // return;
                } else {
                    $this->dispatch('anulacionExitosa', detail: $mesajeAnular['mensaje']);
                    // return;
                    DB::commit();
                }
                //PARA ANULAR LA AGENDA SIGHO V1
                //ACTUALIZACION: YA NO SE ANULA DIRECTAMENTE DESDE EL WEB MAIN
                //ACTUALIZACION 2: SE VOLVIOI A PONER PARA EVITAR QUE NO SE ANULEN FICHAS QUE SE HICIERON EN OTRO PERIODO
                if ($anularAgenda->replicado) {
                    $anulacionSighov1->enviarDatosAnulacionAgenda($anularAgenda->id);
                }
                return redirect()->to('/dashboard');
            }
            // $this->dispatch('anulacionExitosa');
        } catch (\Throwable $e) {
            DB::rollBack();
            dd([
                'usuario_id' => auth()->user()->id ?? null,
                'error'   => $e->getMessage() ?? '',
                'archivo'    => $e->getFile() ?? '',
                'linea'    => $e->getLine() ?? '',
                'fecha_hora'   => now(),
            ]);
            $this->dispatch('errorRegistroAnulacion');
            Log::error("Error al anular agenda existente", [
                'usuario_id' => auth()->user()->id ?? null,
                'error'   => $e->getMessage() ?? '',
                'archivo'    => $e->getFile() ?? '',
                'linea'    => $e->getLine() ?? '',
                'fecha_hora'   => now(),
            ]);
        }
    }
}
