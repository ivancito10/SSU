<?php

namespace App\Services;

use App\Models\Administracion\Especialidad;
use App\Models\Administracion\Persona;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Estudiante;
use App\Models\Afiliacion\Titular;
use App\Models\Aportes\AporteInstitucional;
use App\Models\Aportes\AporteInstitucionalTemp;
use App\Models\Aportes\Comprobante;
use App\Models\Plataforma\AsignacionHorario;
use App\Models\Plataforma\Especialista;
use App\Models\Vigencia\AutorizacionInterior;
use App\Models\Vigencia\Convenio;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ServiciosCachecito
{
    public function obtenerPersonas()
    {
        $arrayDatos = [];

        $consultaPersona = Persona::whereNot('id_tipo_asegurado', 0)
            ->where('afiliado', true)
            ->with([
                'tipoAseguradoPersona',
                'PersonaTitular',
                'PersonaBeneficiario.titularBeneficiario',
                'autorizacionInteriorPersonaInterior',
                'convenioPersona',
                'PersonaEstudiante'
            ])
            ->orderBy('id', 'asc')
            ->get();

        foreach ($consultaPersona as $persona) {
            $idPersonaTitular = 0;

            $tipoAsegurado = $persona->tipoAseguradoPersona->tipo_asegurado ?? null;

            switch ($tipoAsegurado) {
                case 'TITULAR':
                    $titular = $persona->PersonaTitular->where('estado', true)->first();
                    $idPersonaTitular = $titular->id_persona ?? 0;
                    $liga = 'Afiliacion/FOTOS/';
                    $foto = $titular->foto_nombre ?? null;
                    break;

                case 'BENEFICIARIO DEL INTERIOR':
                    $autorizacionInterior = $persona->autorizacionInteriorPersonaInterior->where('estado', true)->first();
                    $idPersonaTitular = $autorizacionInterior->id_persona_titular ?? 0;
                    $liga = 'Vigencia/FOTOS/CONVENIO/';
                    $foto = $autorizacionInterior->foto_nombre ?? null;
                    break;

                case (Str::contains($tipoAsegurado, 'BENEFICIARIO')):
                    $beneficiario = $persona->PersonaBeneficiario->where('estado', true)->first();
                    $idPersonaTitular = $beneficiario->titularBeneficiario->id_persona ?? 0;
                    $liga = 'Afiliacion/FOTOS/';
                    $foto = $beneficiario->foto_nombre ?? null;
                    break;

                case 'TITULAR DEL INTERIOR':
                    $autorizacionInteriorTitular = $persona->autorizacionInteriorPersonaInterior->where('estado', true)->first();
                    $idPersonaTitular = $autorizacionInteriorTitular->id_persona_titular ?? 0;
                    $liga = 'Vigencia/FOTOS/CONVENIO/';
                    $foto = $autorizacionInteriorTitular->foto_nombre ?? null;
                    break;

                case 'TITULAR CONVENIO':
                    $convenio = $persona->convenioPersona->where('estado', true)->first();
                    $idPersonaTitular = $convenio->id_persona ?? 0;
                    $liga = 'Vigencia/FOTOS/CONVENIO/';
                    $foto = $convenio->foto_nombre ?? null;
                    break;

                case 'ESTUDIANTE':
                    $estudiante = $persona->PersonaEstudiante
                        ->where('estado_vigencia', true)
                        ->where('estado', true)
                        ->where('estado_validar', 'AFILIADO')
                        ->first();
                    $idPersonaTitular = $estudiante->id_persona ?? 0;
                    $liga = 'Afiliacion/SSUE/FOTOS/';
                    $foto = $estudiante->foto_nombre ?? null;
                    break;

                default:
                    $idPersonaTitular = 0;
                    break;
            }

            $arrayDatos[] = [
                'id_persona' => $persona->id,
                'nombres' => $persona->nombres,
                'p_apellido' => $persona->p_apellido,
                's_apellido' => $persona->s_apellido,
                'matricula_seguro' => $persona->matricula_seguro,
                'sexo' => $persona->sexo,
                'fecha_nacimiento' => $persona->fecha_nacimiento,
                'ci' => $persona->ci,
                'complemento' => $persona->complemento,
                'id_tipo_asegurado' => $persona->id_tipo_asegurado,
                'tipo_asegurado' => $tipoAsegurado,
                'excepcion' => $persona->excepcion,
                'id_persona_titular' => $idPersonaTitular,
                'foto' => $foto,
                'liga' => $liga,
            ];
        }

        return $arrayDatos;
    }

    public function listaEspecialidadesHabilitadas()
    {
        $fechaActual = Carbon::now()->format('Y-m-d');

        $especialidades = Especialidad::with(['especialidadHabilitadoServicioEspecialidad' => function ($query) use ($fechaActual) {
            $query->where('id_servicio', 2)
                ->where('estado', true)
                ->where(function ($unQuericito) use ($fechaActual) {
                    $unQuericito->whereNull('fecha_inicio')
                        ->orWhereDate('fecha_inicio', '<=', $fechaActual);
                })
                ->where(function ($otroQuericito) use ($fechaActual) {
                    $otroQuericito->whereNull('fecha_fin')
                        ->orWhereDate('fecha_fin', '>=', $fechaActual);
                });
        }])
            ->where('estado', true)
            ->get();

        $listaEspecialidadesHabilitadas = $especialidades->flatMap(function ($especialidad) {
            return $especialidad->especialidadHabilitadoServicioEspecialidad->map(function ($servicio) use ($especialidad) {
                return [
                    'id_especialidad' => $especialidad->id,
                    'especialidad' => $especialidad->especialidad,
                    'sigla' => $especialidad->sigla,
                    'estado' => $especialidad->estado,
                    'fecha_inicio' => $servicio->fecha_inicio,
                    'fecha_fin' => $servicio->fecha_fin,
                    'id_servicio' => $servicio->id_servicio,
                    'permanente' => $servicio->permanente,
                    'estudiantes' => $especialidad->estudiantes,
                    'estado_habilitado_servicio' => $servicio->estado,
                ];
            });
        })->values()->all();

        return $listaEspecialidadesHabilitadas;
    }

    public function listaEspecialidadesHabilitadasPorFecha($fechaActual, $servicioPlataforma)
    {
        $especialidades = Especialidad::with(['especialidadHabilitadoServicioEspecialidad' => function ($query) use ($fechaActual, $servicioPlataforma) {
            $query->where('id_servicio', $servicioPlataforma)
                ->where('estado', true)
                ->where(function ($unQuericito) use ($fechaActual) {
                    $unQuericito->whereNull('fecha_inicio')
                        ->orWhereDate('fecha_inicio', '<=', $fechaActual);
                })
                ->where(function ($otroQuericito) use ($fechaActual) {
                    $otroQuericito->whereNull('fecha_fin')
                        ->orWhereDate('fecha_fin', '>=', $fechaActual);
                });
        }])
            ->where('estado', true)
            ->get();

        $listaEspecialidadesHabilitadas = $especialidades->flatMap(function ($especialidad) {
            return $especialidad->especialidadHabilitadoServicioEspecialidad->map(function ($servicio) use ($especialidad) {
                return [
                    'id_especialidad' => $especialidad->id,
                    'especialidad' => $especialidad->especialidad,
                    'sigla' => $especialidad->sigla,
                    'estado' => $especialidad->estado,
                    'fecha_inicio' => $servicio->fecha_inicio,
                    'fecha_fin' => $servicio->fecha_fin,
                    'id_servicio' => $servicio->id_servicio,
                    'permanente' => $servicio->permanente,
                    'estudiantes' => $especialidad->estudiantes,
                    'estado_habilitado_servicio' => $servicio->estado,
                ];
            });
        })->values()->all();

        return $listaEspecialidadesHabilitadas;
    }

    public function listaEspecialistasHabilitados()
    {
        $fechaActual = Carbon::now()->format('Y-m-d');

        $especialistas = Especialista::with([
            'personaEspecialista',
            'especialidadEspecialista',
            'suspensionVacacionEspecialista',
            'especialistaHabilitadoEspecialista'
        ])
            ->where('estado', true)
            ->where(function ($quericito) use ($fechaActual) {
                /*$quericito->whereDoesntHave('suspensionVacacionEspecialista')
                    ->orWhereHas('suspensionVacacionEspecialista', function ($otroQuery) {
                        $otroQuery->whereNot('estado', 'VACACION ACTIVA');
                    });*/
                $quericito->whereDoesntHave('suspensionVacacionEspecialista', function ($otroQuery) use ($fechaActual) {
                    $otroQuery->where('estado', 'VACACION ACTIVA')
                        ->whereDate('fecha_inicio', '<=', $fechaActual)
                        ->whereDate('fecha_fin', '>=', $fechaActual);
                });
            })
            ->get();

        $listaEspecialistas = $especialistas->filter(function ($especialista) use ($fechaActual) {
            return $especialista->especialistaHabilitadoEspecialista->filter(function ($servicio) use ($fechaActual) {
                return $servicio->id_servicio === 2 && $servicio->estado &&
                    ($servicio->permanente || ($servicio->fecha_inicio <= $fechaActual && $servicio->fecha_fin >= $fechaActual));
            })->isNotEmpty();
        })->map(function ($especialista) {
            $foto = $this->obtenerFotoEspecialista($especialista->foto);
            $persona = $especialista->personaEspecialista;
            $especialidad = $especialista->especialidadEspecialista;

            $serviciosHabilitados = $especialista->especialistaHabilitadoEspecialista
                ->filter(function ($servicio) {
                    return $servicio->id_servicio === 2 && $servicio->estado;
                })
                ->map(function ($servicio) {
                    return [
                        'id_especialista_habilitado_servicio' => $servicio->id,
                        'fecha_inicio' => $servicio->fecha_inicio,
                        'fecha_fin' => $servicio->fecha_fin,
                        'permanente' => $servicio->permanente,
                        'id_dia' => $servicio->id_dia,
                    ];
                })
                ->values()
                ->all();

            return [
                'id_especialista' => $especialista->id,
                'nombres' => $persona->nombres,
                'p_apellido' => $persona->p_apellido,
                's_apellido' => $persona->s_apellido,
                'carnet' => $persona->complemento !== null ? $persona->ci . ' - ' . $persona->complemento : $persona->ci,
                'sexo' => $persona->sexo,
                'id_especialidad' => $especialidad->id,
                'especialidad' => $especialidad->especialidad,
                'afiliados' => $especialista->afiliados,
                'estudiantes' => $especialista->estudiantes,
                'convenios' => $especialista->convenios,
                'grado_academico' => $especialista->grado_academico,
                'fecha_contrato_inicio' => $especialista->fecha_contrato_inicio,
                'fecha_contrato_fin' => $especialista->fecha_contrato_fin,
                'permanente' => $especialista->permanente,
                'foto' => $foto,
                'especialistaHabilitado' => $serviciosHabilitados,
            ];
        })->values()->all();

        return $listaEspecialistas;
    }

    public function listaEspecialistasHabilitadosPorFecha($fechaActual, $servicioPlataforma)
    {
        $especialistas = Especialista::with([
            'personaEspecialista',
            'especialidadEspecialista',
            'suspensionVacacionEspecialista',
            'especialistaHabilitadoEspecialista'
        ])
            ->where('estado', true)
            /*->where(function ($quericito) {
                    $quericito->whereDoesntHave('suspensionVacacionEspecialista')
                        ->orWhereHas('suspensionVacacionEspecialista', function ($otroQuery) {
                            $otroQuery->whereNot('estado', 'VACACION ACTIVA');
                        });
                })*/
            ->get();

        $listaEspecialistas = $especialistas->filter(function ($especialista) use ($fechaActual, $servicioPlataforma) {
            return $especialista->especialistaHabilitadoEspecialista->filter(function ($servicio) use ($fechaActual, $servicioPlataforma) {
                return $servicio->id_servicio === $servicioPlataforma && $servicio->estado &&
                    ($servicio->permanente || ($servicio->fecha_inicio <= $fechaActual && $servicio->fecha_fin >= $fechaActual));
            })->isNotEmpty();
        })->map(function ($especialista) use ($servicioPlataforma) {
            $foto = $this->obtenerFotoEspecialista($especialista->foto);
            $persona = $especialista->personaEspecialista;
            $especialidad = $especialista->especialidadEspecialista;

            $serviciosHabilitados = $especialista->especialistaHabilitadoEspecialista
                ->filter(function ($servicio) use ($servicioPlataforma){
                    return $servicio->id_servicio === $servicioPlataforma && $servicio->estado;
                })
                ->map(function ($servicio) {
                    return [
                        'id_especialista_habilitado_servicio' => $servicio->id,
                        'fecha_inicio' => $servicio->fecha_inicio,
                        'fecha_fin' => $servicio->fecha_fin,
                        'permanente' => $servicio->permanente,
                        'id_dia' => $servicio->id_dia,
                    ];
                })
                ->values()
                ->all();

            $vacacionesEspecialista = $especialista->suspensionVacacionEspecialista
                ->map(function ($vacacion) {
                    return [
                        'id_suspension_vacacion' => $vacacion->id,
                        'id_especialista_suspension_vacacion' => $vacacion->id_especialista,
                        'estado' => $vacacion->estado,
                        'fecha_inicio_vacacion' => $vacacion->fecha_inicio,
                        'fecha_fin_vacacion' => $vacacion->fecha_fin
                    ];
                })
                ->values()
                ->all();

            return [
                'id_especialista' => $especialista->id,
                'nombres' => $persona->nombres,
                'p_apellido' => $persona->p_apellido,
                's_apellido' => $persona->s_apellido,
                'carnet' => $persona->complemento !== null ? $persona->ci . ' - ' . $persona->complemento : $persona->ci,
                'sexo' => $persona->sexo,
                'id_especialidad' => $especialidad->id,
                'especialidad' => $especialidad->especialidad,
                'afiliados' => $especialista->afiliados,
                'estudiantes' => $especialista->estudiantes,
                'convenios' => $especialista->convenios,
                'grado_academico' => $especialista->grado_academico,
                'fecha_contrato_inicio' => $especialista->fecha_contrato_inicio,
                'fecha_contrato_fin' => $especialista->fecha_contrato_fin,
                'permanente' => $especialista->permanente,
                'foto' => $foto,
                'especialistaHabilitado' => $serviciosHabilitados,
                'vacacionesEspecialista' => $vacacionesEspecialista,
            ];
        })->values()->all();

        return $listaEspecialistas;
    }

    public function listaEspecialistasHabilitadosPorFechaKiosco($fechaActual, $servicioPlataforma, $idEspecialidad)
    {
        $especialistas = Especialista::with([
            'personaEspecialista',
            'especialidadEspecialista',
            'suspensionVacacionEspecialista',
            'especialistaHabilitadoEspecialista'
        ])
            ->where('estado', true)
            ->where('id_especialidad', $idEspecialidad)
            /*->where(function ($quericito) {
                    $quericito->whereDoesntHave('suspensionVacacionEspecialista')
                        ->orWhereHas('suspensionVacacionEspecialista', function ($otroQuery) {
                            $otroQuery->whereNot('estado', 'VACACION ACTIVA');
                        });
                })*/
            ->get();

        $listaEspecialistas = $especialistas->filter(function ($especialista) use ($fechaActual, $servicioPlataforma) {
            return $especialista->especialistaHabilitadoEspecialista->filter(function ($servicio) use ($fechaActual, $servicioPlataforma) {
                return $servicio->id_servicio === $servicioPlataforma && $servicio->estado &&
                    ($servicio->permanente || ($servicio->fecha_inicio <= $fechaActual && $servicio->fecha_fin >= $fechaActual));
            })->isNotEmpty();
        })->map(function ($especialista) use ($servicioPlataforma) {
            $foto = $this->obtenerFotoEspecialista($especialista->foto);
            $persona = $especialista->personaEspecialista;
            $especialidad = $especialista->especialidadEspecialista;

            $serviciosHabilitados = $especialista->especialistaHabilitadoEspecialista
                ->filter(function ($servicio) use ($servicioPlataforma){
                    return $servicio->id_servicio === $servicioPlataforma && $servicio->estado;
                })
                ->map(function ($servicio) {
                    return [
                        'id_especialista_habilitado_servicio' => $servicio->id,
                        'fecha_inicio' => $servicio->fecha_inicio,
                        'fecha_fin' => $servicio->fecha_fin,
                        'permanente' => $servicio->permanente,
                        'id_dia' => $servicio->id_dia,
                    ];
                })
                ->values()
                ->all();

            $vacacionesEspecialista = $especialista->suspensionVacacionEspecialista
                ->map(function ($vacacion) {
                    return [
                        'id_suspension_vacacion' => $vacacion->id,
                        'id_especialista_suspension_vacacion' => $vacacion->id_especialista,
                        'estado' => $vacacion->estado,
                        'fecha_inicio_vacacion' => $vacacion->fecha_inicio,
                        'fecha_fin_vacacion' => $vacacion->fecha_fin
                    ];
                })
                ->values()
                ->all();

            return [
                'id_especialista' => $especialista->id,
                'nombres' => $persona->nombres,
                'p_apellido' => $persona->p_apellido,
                's_apellido' => $persona->s_apellido,
                'carnet' => $persona->complemento !== null ? $persona->ci . ' - ' . $persona->complemento : $persona->ci,
                'sexo' => $persona->sexo,
                'id_especialidad' => $especialidad->id,
                'especialidad' => $especialidad->especialidad,
                'afiliados' => $especialista->afiliados,
                'estudiantes' => $especialista->estudiantes,
                'convenios' => $especialista->convenios,
                'grado_academico' => $especialista->grado_academico,
                'fecha_contrato_inicio' => $especialista->fecha_contrato_inicio,
                'fecha_contrato_fin' => $especialista->fecha_contrato_fin,
                'permanente' => $especialista->permanente,
                'foto' => $foto,
                'especialistaHabilitado' => $serviciosHabilitados,
                'vacacionesEspecialista' => $vacacionesEspecialista,
            ];
        })->values()->all();

        return $listaEspecialistas;
    }

    private function obtenerFotoEspecialista($idFoto)
    {
        try {
            if ($idFoto) {
                $path = 'Plataforma/Especialista/Fotos/' . $idFoto;

                $imageData = Storage::disk('ftp')->get($path);
                $foto = base64_encode($imageData);
                if ($foto === '') {
                    return null;
                } else {
                    return $foto;
                }
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function listaHorarios()
    {
        $fechaActual = Carbon::now()->format('Y-m-d');

        $asignacionesHorarios = AsignacionHorario::with([
            'horarioAsignacionHorario',
            'consultorioAsignacionHorario.consultorioSedes.residenciaSedes.zonaResidencia',
        ])
            ->where(function ($quericito) use ($fechaActual) {
                $quericito->whereNull('fecha_inicio')
                    ->orWhereDate('fecha_inicio', '<=', $fechaActual);
            })
            ->where(function ($otroQuericito) use ($fechaActual) {
                $otroQuericito->whereNull('fecha_fin')
                    ->orWhereDate('fecha_fin', '>=', $fechaActual);
            })
            ->where('estado', true)
            ->get();

        $listaHorarios = $asignacionesHorarios->map(function ($asignacion) {
            $horario = $asignacion->horarioAsignacionHorario;
            $consultorio = $asignacion->consultorioAsignacionHorario;
            $sede = $consultorio?->consultorioSedes;
            $residencia = $sede?->residenciaSedes;
            $zona = $residencia?->zonaResidencia;

            return [
                'id_asignacion_horario' => $asignacion->id,
                'id_especialista' => $asignacion->id_especialista,
                'fecha_inicio' => $asignacion->fecha_inicio,
                'fecha_fin' => $asignacion->fecha_fin,
                'id_dia' => $asignacion->id_dia,
                'id_horario' => $asignacion->id_horario,
                'hora_inicio' => $horario->hora_inicio,
                'hora_fin' => $horario->hora_fin,
                'intervalo' => $horario->intervalo,
                'numero_paciente' => $horario->numero_paciente,
                'turno' => $horario->turno,
                'id_consultorio' => $asignacion->id_consultorio,
                'numero_consultorio' => $consultorio->numero_consultorio,
                'id_sedes' => $consultorio->id_sedes,
                'ubicacion' => $sede->ubicacion,
                'piso' => $sede->piso,
                'id_residencia' => $sede->id_residencia,
                'direccion' => $residencia->direccion,
                'nro_vivienda' => $residencia->nro_vivienda,
                'id_zona' => $residencia->id_zona,
                'zona' => $zona->nombre,
            ];
        })->values()->all();

        return $listaHorarios;
    }

    public function listaHorarioPorFecha($fechaActual)
    {
        $asignacionesHorarios = AsignacionHorario::with([
            'horarioAsignacionHorario',
            'consultorioAsignacionHorario.consultorioSedes.residenciaSedes.zonaResidencia',
        ])
            ->where(function ($quericito) use ($fechaActual) {
                $quericito->whereNull('fecha_inicio')
                    ->orWhereDate('fecha_inicio', '<=', $fechaActual);
            })
            ->where(function ($otroQuericito) use ($fechaActual) {
                $otroQuericito->whereNull('fecha_fin')
                    ->orWhereDate('fecha_fin', '>=', $fechaActual);
            })
            ->where('estado', true)
            ->get();

        $listaHorarios = $asignacionesHorarios->map(function ($asignacion) {
            $horario = $asignacion->horarioAsignacionHorario;
            $consultorio = $asignacion->consultorioAsignacionHorario;
            $sede = $consultorio?->consultorioSedes;
            $residencia = $sede?->residenciaSedes;
            $zona = $residencia?->zonaResidencia;

            return [
                'id_asignacion_horario' => $asignacion->id,
                'id_especialista' => $asignacion->id_especialista,
                'fecha_inicio' => $asignacion->fecha_inicio,
                'fecha_fin' => $asignacion->fecha_fin,
                'id_dia' => $asignacion->id_dia,
                'id_horario' => $asignacion->id_horario,
                'hora_inicio' => $horario->hora_inicio,
                'hora_fin' => $horario->hora_fin,
                'intervalo' => $horario->intervalo,
                'numero_paciente' => $horario->numero_paciente,
                'turno' => $horario->turno,
                'id_consultorio' => $asignacion->id_consultorio,
                'numero_consultorio' => $consultorio->numero_consultorio,
                'id_sedes' => $consultorio->id_sedes,
                'ubicacion' => $sede->ubicacion,
                'piso' => $sede->piso,
                'id_residencia' => $sede->id_residencia,
                'direccion' => $residencia->direccion,
                'nro_vivienda' => $residencia->nro_vivienda,
                'id_zona' => $residencia->id_zona,
                'zona' => $zona->nombre,
            ];
        })->values()->all();

        return $listaHorarios;
    }

    private function obtenerImagenBase64($idFoto, $liga)
    {
        try {
            if ($idFoto) {
                $path = $liga . $idFoto;

                $imageData = Storage::disk('ftp')->get($path);
                $foto_nombre = base64_encode($imageData);
                if ($foto_nombre === '') {
                    return null;
                } else {
                    return $foto_nombre;
                }
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function actualizarDatosComprobanteRaquelita()
    {
        $comprobantes = Comprobante::all();

        foreach ($comprobantes as $comprobante) {
            $aportesI = AporteInstitucional::where('id_institucion', $comprobante->id_institucion)
                ->where('id_mes', $comprobante->id_mes)
                ->where('gestion', $comprobante->gestion)
                ->where('reintegro_incr_salarial', $comprobante->reintegro)
                ->where('beneficio_social', $comprobante->beneficio_social)
                ->where('planilla_adicional', $comprobante->planilla_adicional)
                ->where('retroactivo', $comprobante->retroactivo)
                ->get();

            /*$aportesTempI = AporteInstitucionalTemp::where('id_institucion', $comprobante->id_institucion)
                ->where('id_mes', $comprobante->id_mes)
                ->where('gestion', $comprobante->gestion)
                ->where('reintegro_incr_salarial', $comprobante->reintegro)
                ->where('beneficio_social', $comprobante->beneficio_social)
                ->where('planilla_adicional', $comprobante->planilla_adicional)
                ->where('retroactivo', $comprobante->retroactivo)
                ->get();*/

            foreach ($aportesI as $aporteI) {
                $aporteI->id_comp = $comprobante->id;
                $aporteI->save();
            }

            /*foreach ($aportesTempI as $aporteTempI) {
                $aporteTempI->id_comp = $comprobante->id;
                $aporteTempI->save();
            }*/
        }
    }
}
