<?php

namespace App\Services\Fisioterapia;

use Illuminate\Support\Facades\DB;

class HorariosSemanalesService
{
    public function __construct(private NestEtapa1Client $etapa1) {}

    private function vigentes($consulta, string $hoy)
    {
        return $consulta->where('estado', true)
            ->where(fn ($q) => $q->whereNull('vigente_desde')->orWhere('vigente_desde', '<=', $hoy))
            ->where(fn ($q) => $q->whereNull('vigente_hasta')->orWhere('vigente_hasta', '>=', $hoy));
    }

    public function politicas(): array
    {
        $tabla = DB::connection('pgsql')->table('fisioterapia.politicas_agenda');
        return $this->vigentes($tabla, now('America/La_Paz')->toDateString())
            ->orderBy('codigo_version')->pluck('codigo_version', 'id')->all();
    }

    public function registrar(int $especialista, array $datos, int $actor): void
    {
        $db = DB::connection('pgsql');
        $hoy = now('America/La_Paz')->toDateString();
        $db->transaction(function () use ($db, $hoy, $especialista, $datos, $actor) {
            // Serializa también el primer turno, cuando aún no existe una asignación que bloquear.
            $db->selectOne('select pg_advisory_xact_lock(89, ?)', [$especialista]);
            $politica = $this->vigentes($db->table('fisioterapia.politicas_agenda'), $hoy)
                ->where('id', $datos['politica'])->sharedLock()->first();
            if (!$politica) throw \Illuminate\Validation\ValidationException::withMessages(['turno.politica' => 'Seleccione una política vigente.']);
            $asignaciones = $db->table('fisioterapia.asignaciones_sede')->where('id_especialista', $especialista);
            $asignacion = $this->vigentes(clone $asignaciones, $hoy)->where('id_sede', $datos['sede'])->orderBy('id')->first();
            $fin = collect([$politica->vigente_hasta, $asignacion->vigente_hasta ?? null])->filter()->min();
            $cruces = $db->table('fisioterapia.horarios_semanales as h')
                ->join('fisioterapia.asignaciones_sede as a','a.id','=','h.id_asignacion_sede')
                ->join('fisioterapia.politicas_agenda as p','p.id','=','h.id_politica_agenda')
                ->where('a.id_especialista', $especialista)->where('h.dia_semana', $datos['dia'])
                ->where('h.hora_inicio','<',$datos['hasta'])->where('h.hora_fin','>',$datos['desde'])
                ->where('h.estado',true)->where('a.estado',true)->where('p.estado',true)
                ->whereRaw('greatest(h.vigente_desde, a.vigente_desde, p.vigente_desde, cast(? as date)) <= least(h.vigente_hasta, a.vigente_hasta, p.vigente_hasta, coalesce(cast(? as date), \'infinity\'::date))', [$hoy, $fin]);
            if ($cruces->exists()) throw \Illuminate\Validation\ValidationException::withMessages(['turno.desde' => 'El fisioterapeuta ya tiene un turno que se cruza con este horario ese día.']);
            $auditoria = ['vigente_desde' => $hoy, 'estado' => true, 'id_user_created' => $actor];
            $idAsignacion = $asignacion->id ?? $db->table('fisioterapia.asignaciones_sede')->insertGetId(
                $auditoria + ['id_especialista' => $especialista, 'id_sede' => $datos['sede']]
            );
            $db->table('fisioterapia.horarios_semanales')->insert($auditoria + [
                'id_asignacion_sede' => $idAsignacion, 'id_politica_agenda' => $politica->id,
                'dia_semana' => $datos['dia'], 'hora_inicio' => $datos['desde'],
                'hora_fin' => $datos['hasta'], 'intervalo' => $datos['intervalo'],
            ]);
        });
    }

    public function vigentesPorEspecialista(int $id): array
    {
        $hoy = now('America/La_Paz')->toDateString();
        $consulta = DB::connection('pgsql')->table('fisioterapia.horarios_semanales as h')
            ->join('fisioterapia.asignaciones_sede as a', 'a.id', '=', 'h.id_asignacion_sede')
            ->join('fisioterapia.politicas_agenda as p', 'p.id', '=', 'h.id_politica_agenda')
            ->where('a.id_especialista', $id);

        foreach (['h', 'a', 'p'] as $tabla) {
            $consulta->where("$tabla.estado", true)
                ->where(fn ($q) => $q->whereNull("$tabla.vigente_desde")->orWhere("$tabla.vigente_desde", '<=', $hoy))
                ->where(fn ($q) => $q->whereNull("$tabla.vigente_hasta")->orWhere("$tabla.vigente_hasta", '>=', $hoy));
        }

        return $consulta->orderBy('h.dia_semana')->orderBy('h.hora_inicio')->orderBy('a.id_sede')->orderBy('h.id')
            ->get(['h.dia_semana', 'h.hora_inicio', 'h.hora_fin', 'p.cupos_normales', 'a.id_sede'])
            ->map(function ($fila) {
                $sede = $this->etapa1->obtener('sedes', $fila->id_sede);
                return [
                    'diaSemana' => (int) $fila->dia_semana,
                    'horaInicio' => $fila->hora_inicio,
                    'horaFin' => $fila->hora_fin,
                    'cuposNormales' => $fila->cupos_normales,
                    'sede' => ['piso' => $sede['piso'] ?? null, 'ubicacion' => $sede['ubicacion'] ?? null],
                ];
            })->all();
    }
}