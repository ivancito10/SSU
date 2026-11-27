<?php

namespace App\Jobs;

use App\Models\Administracion\LinkServicio;
use App\Models\Plataforma\Agenda;
use App\Services\ObtenerPoblacion;
use App\Services\RespuestaApisService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ReplicarAgendasWebApk implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        set_time_limit(24000);
        $buscarDatosPersona = new ObtenerPoblacion();

        //$diaAnteriorInicio = Carbon::now()->startOfDay();
        //$diaAnteriorFin = Carbon::now()->endOfDay();

        //$diaAnteriorInicio = Carbon::now()->subDays(1)->startOfDay();
        //$diaAnteriorFin = Carbon::now()->subDays(1)->endOfDay();

        $agendas = Agenda::whereNot('anulacion_ficha', true)
            //->whereBetween('created_at', [$diaAnteriorInicio, $diaAnteriorFin])
            ->where('id_servicio_plataforma', 2)
            ->where('replicado', false)
            ->whereNot('id_persona', null)
            //->where('id', 1108)
            //->whereBetween('id', [1113, 1116])
            ->orderBy('id', 'desc')
            ->get();

        //dd($agendas);

        foreach ($agendas as $key => $agenda) {
            $datosPersona = $buscarDatosPersona->obtenerDatosAfiliacionId($agenda->id_persona);

            $codigo = null;
            $institucion = null;

            if ($datosPersona !== []) {

                if ($datosPersona['tipo_asegurado'] === 'TITULAR') {
                    $codigo = $datosPersona['datos_afiliacion']['codigo_institucional'];
                    $institucion = $datosPersona['datos_afiliacion']['datos_institucion'] !== [] ?
                        $datosPersona['datos_afiliacion']['datos_institucion'][0]['institucion'] : 'APORTANTE VOLUNTARIO';
                }

                if (Str::contains($datosPersona['tipo_asegurado'], 'BENEFICIARIO')) {
                    if ($datosPersona['tipo_asegurado'] === 'BENEFICIARIO DEL INTERIOR') {
                        $institucion = $datosPersona['datos_afiliacion']['nombre_seguro'][0];
                    } else {
                        $codigo = $datosPersona['datos_afiliacion']['codigo_institucional'];
                        $institucion = $datosPersona['datos_afiliacion']['datos_titular']['datos_afiliacion']['datos_institucion'] !== [] ?
                            $datosPersona['datos_afiliacion']['datos_titular']['datos_afiliacion']['datos_institucion'][0]['institucion'] : 'APORTANTE VOLUNTARIO';
                    }
                }

                if ($datosPersona['tipo_asegurado'] === 'TITULAR DEL INTERIOR') {
                    $institucion = $datosPersona['datos_afiliacion']['nombre_seguro'][0];
                }

                if ($datosPersona['tipo_asegurado'] === 'TITULAR CONVENIO') {
                    $institucion = 'CONVENIO DE EMERGENCIA';
                    /*$institucion = implode(' ', array_filter([
                        $datosPersona['datos_afiliacion']['datos_titular_autoriza']['p_apellido'],
                        $datosPersona['datos_afiliacion']['datos_titular_autoriza']['s_apellido'],
                        $datosPersona['datos_afiliacion']['datos_titular_autoriza']['nombres']
                    ]));*/
                }

                if ($datosPersona['tipo_asegurado'] === 'ESTUDIANTE') {
                    $institucion = 'UMSA ESTUDIANTES';
                }
            }

            $this->replicarAgenda($agenda->id, $codigo, $institucion);
        }
    }

    public function replicarAgenda($idAgenda, $codigoInstitucional, $institucion)
    {
        try {
            $datosReplica = [];

            $agenda = Agenda::with([
                'personaAgenda.tipoAseguradoPersona',
                'asignacionHorarioAgenda.especialistaAsignacionHorario.especialidadEspecialista',
                'servicioPlataformaAgenda',
            ])
                ->whereNot('anulacion_ficha', true)
                ->find($idAgenda);
            // dd($agenda);
            if ($agenda) {
                $datosReplica = [
                    'agenda' => [
                        'id_agenda' => $idAgenda,
                        'es_extranjero' => $agenda->personaAgenda->es_extranjero,
                        'ci_paciente' => implode('-', array_filter([$agenda->personaAgenda->ci, $agenda->personaAgenda->complemento])),
                        'ci_medico' => implode('-', array_filter([
                            $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->ci,
                            $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->complemento,
                        ])),
                        'hora' => $agenda->hora_agenda,
                        'fecha' => $agenda->fecha_agenda,
                        'matricula' => $agenda->personaAgenda->matricula_seguro,
                        'nombre_asegurado' => implode(' ', array_filter([
                            $agenda->personaAgenda->p_apellido,
                            $agenda->personaAgenda->s_apellido,
                            $agenda->personaAgenda->nombres,
                        ])),
                        'edad' => Carbon::parse($agenda->personaAgenda->fecha_nacimiento)->age,
                        'sexo' => $agenda->personaAgenda->sexo,
                        'tipo_asegurado' => $agenda->personaAgenda->tipoAseguradoPersona->tipo_asegurado,
                        'especialidad' => $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->especialidadEspecialista->especialidad,
                        'id_user_created_s2' => $agenda->id_user_created,
                        'id_user_updated_s2' => $agenda->id_user_updated,
                        'estado_agenda' => 'AGENDADO',
                    ],
                    'paciente' => [
                        'es_extranjero' => $agenda->personaAgenda->es_extranjero,
                        'nombres' => $agenda->personaAgenda->nombres,
                        'ap_Paterno' => $agenda->personaAgenda->p_apellido,
                        'ap_Materno' => $agenda->personaAgenda->s_apellido,
                        'fecha_nacimiento' => $agenda->personaAgenda->fecha_nacimiento,
                        'genero' => $agenda->personaAgenda->sexo,
                        'numero_identificacion' => implode('-', array_filter([$agenda->personaAgenda->ci, $agenda->personaAgenda->complemento])),
                        'telefono1' => '0',
                        'tipo_asegurado' => $agenda->personaAgenda->id_tipo_asegurado,
                        'matricula' => $agenda->personaAgenda->matricula_seguro,
                        'codigo' => $codigoInstitucional,
                        'institucion' => $institucion,
                    ],
                    'medico' => [
                        'nombres' => $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->nombres,
                        'ap_Paterno' => $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->p_apellido,
                        'ap_Materno' => $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->s_apellido,
                        'fecha_nacimiento' => $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->fecha_nacimiento,
                        'genero' => $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->sexo,
                        'numero_identificacion' => implode('-', array_filter([
                            $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->ci,
                            $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->complemento,
                        ])),
                        'telefono1' => '0',
                    ],
                ];
            }
            //dd($datosReplica);
            //return $datosReplica;

            if ($datosReplica !== []) {
                $agendaReplicada = $this->enviarDatosReplicaAgenda($datosReplica);
                // dd($agendaReplicada,$idAgenda);
                /*if ($agendaReplicada) {
                    //dd($agendaReplicada);
                    $agendaRep = Agenda::find($idAgenda);
                    $agendaRep->replicado = 1;
                    $agendaRep->save();
                    // Agenda::where('id', $idAgenda)->update(['replicado' => true]);
                    //dd(Agenda::where('id', $idAgenda)->update(['replicado' => true]));
                }*/
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        //return $datosReplica;
    }

    public function enviarDatosReplicaAgenda($data)
    {
        //dd($data);
        $url = LinkServicio::where('nombre_servicio', 'REPLICA AGENDA SIGHO1')->first();
        $urlSigho1 = $url->link_servicio;
        $respuesta = Http::post($urlSigho1, $data);
        //$id_user_created = auth()->user()->id;
        $replica = new RespuestaApisService();
        $replica->guardarRespuesta(
            $data,
            $respuesta,
            'SIGHO V1',
            'CONSULTA EXTERNA - SIGHO V1',
            'PLATAFORMA - SIGHO V2 - AGENDA',
            1
        );
        $validacionExito = $respuesta->status() === 200 ? true : false;

        return $validacionExito;
        /*$this->respuestaInsercion = $respuesta->json();
        return $this->respuestaInsercion;*/
    }
}
