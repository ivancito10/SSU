<?php

namespace App\Services\Fisioterapia;

use App\Models\Fisioterapia\Ordenes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use RuntimeException;

class OrdenesService
{
    private array $especialistas = [];
    private array $pacientes = [];

    public function __construct(private NestEtapa1Client $nest) {}

    public function consulta(): Builder
    {
        Gate::authorize('fisioterapia.ordenes.ver');
        return Ordenes::query()->select([
            'id', 
            'nro_solicitud', 
            'nro_sesion', 
            'indicaciones', 
            'id_user_created', 
            'id_persona', 
            'created_at'
        ]);
    }

    public function buscar(string $texto): Builder
    {
        $q = $this->consulta();
        $texto = trim($texto);
        if ($texto === '') return $q;
        if (mb_strlen($texto) > 100) throw new RuntimeException('La búsqueda admite hasta 100 caracteres.');
        if (preg_match('/^[0-9]{1,18}$/', $texto)) {
            return $q->where(function (Builder $b) use ($texto) {
                $b->where('id', $texto);
                if ((int) $texto <= 2147483647) $b->orWhere('nro_solicitud', $texto);
            });
        }
        if (mb_strlen($texto) < 3) {
            throw new RuntimeException('Escriba al menos 3 caracteres para buscar por nombre o apellido del paciente o especialista.');
        }
        // usuarios.nombre también consulta nombres/apellidos de su persona asociada.
        // Se resuelven todas las páginas de coincidencias antes de paginar las órdenes.
        $personas = $this->nest->buscarIdsPorNombre('personas', $texto);
        $usuarios = $this->nest->buscarIdsPorNombre('usuarios', $texto);
        return $q->where(function (Builder $b) use ($personas, $usuarios) {
            $b->whereIn('id_persona', $personas)->orWhereIn('id_user_created', $usuarios);
        });
    }

    public function especialista(?string $id): array
    {
        Gate::authorize('fisioterapia.ordenes.ver');
        $key = $id ?? 'sin-referencia';
        if (isset($this->especialistas[$key])) return $this->especialistas[$key];
        try {
            $user = $this->nest->obtener('usuarios', $id);
            $data = $user ? $this->nest->obtener('especialistas/persona', $user['id_persona'] ?? null) : null;
            return $this->especialistas[$key] = [
                'datos' => $data['especialista'] ?? null,
                'aviso' => $data ? null : 'No se encontró el especialista asociado al usuario creador.'
            ];
        } catch (RuntimeException $e) {
            return $this->especialistas[$key] = [
                'datos' => null, 
                'aviso' => $e->getMessage()
            ];
        }
    }

    public function paciente(?int $id): array
    {
        Gate::authorize('fisioterapia.ordenes.ver');
        $key = $id ?? 'sin-referencia';
        if (isset($this->pacientes[$key])) return $this->pacientes[$key];
        try {
            $data = $this->nest->obtener('personas', $id);
            return $this->pacientes[$key] = [
                'datos' => $data,
                'aviso' => $data ? null : 'No se encontró la persona del paciente.'
            ];
        } catch (RuntimeException $e) {
            return $this->pacientes[$key] = [
                'datos' => null, 
                'aviso' => $e->getMessage()
            ];
        }
    }

    public function detalle(string $id): array
    {
        Gate::authorize('fisioterapia.ordenes.ver');
        abort_unless(preg_match('/^[1-9][0-9]{0,18}$/', $id)
            && (strlen($id) < 19 || strcmp($id, '9223372036854775807') <= 0), 404);
        $orden = $this->consulta()->findOrFail($id);
        $especialista = $this->especialista($orden->id_user_created);
        $paciente = $this->paciente($orden->id_persona);
        return [
            'orden' => $orden->only([
                'id', 'nro_solicitud', 
                'nro_sesion', 
                'indicaciones', 
                'id_user_created', 
                'id_persona'
            ])
            + ['fecha' => $orden->created_at?->format('d/m/Y H:i')],
            'especialista' => $especialista['datos'], 'paciente' => $paciente['datos'],
            'avisos' => array_values(
                array_unique(
                    array_filter([$especialista['aviso'], $paciente['aviso']])
                )
            ),
        ];
    }
}