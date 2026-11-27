<?php

namespace App\Services;

use App\Models\Plataforma\Agenda;
use App\Services\ListadoAsegurados;
use Illuminate\Support\Facades\Storage;

class DatosAgenda
{
    public $datosObtenidosAgenda = [];

    public function extraerDatosAgenda($idAgenda)
    {
        $agenda = Agenda::with(['asignacionHorarioAgenda', 
                'suspensionHorarioAgenda',
                'suspensionHorarioAgenda.motivoSuspensionHorario', 
                'personaAgenda', 
                'servicioPlataformaAgenda', 
                'asignacionHorarioAgenda.consultorioAsignacionHorario', 
                'asignacionHorarioAgenda.especialistaAsignacionHorario', 
                'asignacionHorarioAgenda.horarioAsignacionHorario', 
                'asignacionHorarioAgenda.consultorioAsignacionHorario.consultorioSedes', 
                'asignacionHorarioAgenda.consultorioAsignacionHorario.consultorioSedes.residenciaSedes', 
                'asignacionHorarioAgenda.consultorioAsignacionHorario.consultorioSedes.residenciaSedes.zonaResidencia', 
                'asignacionHorarioAgenda.especialistaAsignacionHorario.personaEspecialista', 
                'asignacionHorarioAgenda.especialistaAsignacionHorario.especialidadEspecialista', 
                'usuarioCreador', 
                'usuarioCreador.personaUser'])
            ->where('id', $idAgenda)
            ->whereNot('anulacion_ficha', true)
            ->whereNot('id_persona', null)
            ->orderBy('id', 'desc')
            ->first();
        if ($agenda) {
            $this->datosObtenidosAgenda['hora_agenda'] = $agenda->hora_agenda;
            $this->datosObtenidosAgenda['fecha_agenda'] = $agenda->fecha_agenda;
            $this->datosObtenidosAgenda['fecha_registro'] = $agenda->created_at;
            $this->datosObtenidosAgenda['id_usuario_agenda'] = $agenda->usuarioCreador->id;
            $this->datosObtenidosAgenda['usuario_agenda'] = $agenda->usuarioCreador->name;
            $this->datosObtenidosAgenda['nombre_usuario_agenda'] = $agenda->usuarioCreador->personaUser->p_apellido . ' ' . $agenda->usuarioCreador->personaUser->s_apellido . ' ' . $agenda->usuarioCreador->personaUser->nombres;
            $this->datosObtenidosAgenda['especialista_nombre'] = $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->p_apellido . ' ' . $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->s_apellido . ' ' . $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->nombres;
            $foto_especialista = $this->obtenerImagenBase64Doctores($agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->foto);
            $this->datosObtenidosAgenda['foto_especialista'] = $foto_especialista;
            $this->datosObtenidosAgenda['especialidad'] = $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->especialidadEspecialista->especialidad;
            $this->datosObtenidosAgenda['nro_consultorio'] = $agenda->asignacionHorarioAgenda->consultorioAsignacionHorario->numero_consultorio;
            $this->datosObtenidosAgenda['ubicacion'] = $agenda->asignacionHorarioAgenda->consultorioAsignacionHorario->consultorioSedes->ubicacion;
            $this->datosObtenidosAgenda['piso'] = $agenda->asignacionHorarioAgenda->consultorioAsignacionHorario->consultorioSedes->piso;
            $this->datosObtenidosAgenda['direccion'] = $agenda->asignacionHorarioAgenda->consultorioAsignacionHorario->consultorioSedes->residenciaSedes->direccion;
            $this->datosObtenidosAgenda['nro_vivienda'] = $agenda->asignacionHorarioAgenda->consultorioAsignacionHorario->consultorioSedes->residenciaSedes->nro_vivienda;
            $this->datosObtenidosAgenda['latitud_ubicacion'] = $agenda->asignacionHorarioAgenda->consultorioAsignacionHorario->consultorioSedes->residenciaSedes->latitud;
            $this->datosObtenidosAgenda['longitud_ubicacion'] = $agenda->asignacionHorarioAgenda->consultorioAsignacionHorario->consultorioSedes->residenciaSedes->longitud;
            $this->datosObtenidosAgenda['zona'] = $agenda->asignacionHorarioAgenda->consultorioAsignacionHorario->consultorioSedes->residenciaSedes->zonaResidencia->nombre;
            $this->datosObtenidosAgenda['hora_inicio'] = $agenda->asignacionHorarioAgenda->horarioAsignacionHorario->hora_inicio;
            $this->datosObtenidosAgenda['hora_fin'] = $agenda->asignacionHorarioAgenda->horarioAsignacionHorario->hora_fin;
            $this->datosObtenidosAgenda['turno'] = $agenda->asignacionHorarioAgenda->horarioAsignacionHorario->turno;
            $this->datosObtenidosAgenda['intervalo'] = $agenda->asignacionHorarioAgenda->horarioAsignacionHorario->intervalo;
            $this->datosObtenidosAgenda['servicio'] = $agenda->servicioPlataformaAgenda->nombre_servicio;

            //Para la persona de la agenda

            $datos = new ListadoAsegurados();
            $datosPersona = $datos->busquedaAfiliadoPorIdKiosko($agenda->id_persona);
            //dd($datosPersona);
            $this->datosObtenidosAgenda['id_persona_agendada'] = $datosPersona['id_persona'];
            $this->datosObtenidosAgenda['ci_persona_agendada'] = $datosPersona['ci'];
            $this->datosObtenidosAgenda['complemento_persona_agendada'] = $datosPersona['complemento'];
            $this->datosObtenidosAgenda['matricula_seguro_persona_agendada'] = $datosPersona['matricula_seguro'];
            $this->datosObtenidosAgenda['nombres_persona_agendada'] = $datosPersona['nombres'];
            $this->datosObtenidosAgenda['p_apellido_persona_agendada'] = $datosPersona['p_apellido'];
            $this->datosObtenidosAgenda['s_apellido_persona_agendada'] = $datosPersona['s_apellido'];
            $this->datosObtenidosAgenda['sexo_persona_agendada'] = $datosPersona['sexo'];
            $this->datosObtenidosAgenda['fecha_nacimiento_persona_agendada'] = $datosPersona['fecha_nacimiento'];
            $this->datosObtenidosAgenda['tipo_asegurado_persona_agendada'] = $datosPersona['tipo_asegurado'];
            $this->datosObtenidosAgenda['estado_vigencia_persona_agendada'] = $datosPersona['estado_vigencia'];
            $this->datosObtenidosAgenda['foto_persona_agendada'] = $datosPersona['foto'];
            $this->datosObtenidosAgenda['nombre_institucion_persona_agendada'] = $datosPersona['nombre_institucion'];
            $this->datosObtenidosAgenda['codigo_institucional_persona_agendada'] = $datosPersona['codigo_institucional'];
            $this->datosObtenidosAgenda['institucion_beneficiario_remuneracion_persona_agendada'] = $datosPersona['institucion_beneficiario_remuneracion'];

            if ($agenda->id_suspension_horario == null) {
                $this->datosObtenidosAgenda['estado_agenda'] = 'OCUPADO';
                $this->datosObtenidosAgenda['fecha_suspension'] = null;
                $this->datosObtenidosAgenda['observacion'] = null;
                $this->datosObtenidosAgenda['motivo'] = null;
            } else {
                $this->datosObtenidosAgenda['estado_agenda'] = 'SUSPENDIDO';
                $this->datosObtenidosAgenda['fecha_suspension'] = $agenda->suspensionHorarioAgenda->fecha_suspension;
                $this->datosObtenidosAgenda['observacion'] = $agenda->suspensionHorarioAgenda->observacion;
                $this->datosObtenidosAgenda['motivo'] = $agenda->suspensionHorarioAgenda->motivoSuspensionHorario->nombre_motivo;
            }

            //dd($agenda);
            return $this->datosObtenidosAgenda;
        } else {
            return $this->datosObtenidosAgenda;
        }
    }

    private function obtenerImagenBase64Doctores($idFoto)
    {
        try {
            if ($idFoto) {
                $path = 'Plataforma/Especialista/Fotos/' . $idFoto;
    
                $imageData = Storage::disk('ftp')->get($path);
    
                $foto_medico = base64_encode($imageData);
                return $foto_medico;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
        
    }
}
