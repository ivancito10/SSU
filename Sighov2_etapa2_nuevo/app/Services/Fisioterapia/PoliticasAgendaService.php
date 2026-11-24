<?php

namespace App\Services\Fisioterapia;

use App\Models\Fisioterapia\PoliticaAgenda;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PoliticasAgendaService
{
    public function listar(string $buscar = '', string $estado = 'todos', int $porPagina = 10): LengthAwarePaginator
    {
        Gate::authorize('fisioterapia.politicasagenda.ver');
        Validator::make(compact('buscar', 'estado', 'porPagina'), [
            'buscar' => ['string', 'max:100'],
            'estado' => [Rule::in(['todos', 'activos', 'inactivos'])],
            'porPagina' => [Rule::in([10, 25, 50])],
        ])->validate();

        return PoliticaAgenda::query()
            ->when(trim($buscar) !== '', function ($query) use ($buscar) {
                // Parámetros enlazados: el texto de búsqueda nunca se concatena al SQL.
                $texto = '%'.str_replace(['!', '%', '_'], ['!!', '!%', '!_'], mb_strtolower(trim($buscar))).'%';
                $query->where(function ($query) use ($texto) {
                    $query->whereRaw("LOWER(codigo_version) LIKE ? ESCAPE '!'", [$texto]);
                        // ->orWhereRaw("LOWER(nombre) LIKE ? ESCAPE '!'", [$texto])
                        // ->orWhereRaw("LOWER(grupo) LIKE ? ESCAPE '!'", [$texto]);
                });
            })
            ->when($estado === 'activos', fn ($query) => $query->where('estado', true))
            ->when($estado === 'inactivos', fn ($query) => $query->where(fn ($q) => $q->where('estado', false)->orWhereNull('estado')))
            ->orderBy('codigo_version')->orderBy('id')->paginate($porPagina);
    }

    public function obtener(int $id): PoliticaAgenda
    {
        Gate::authorize('fisioterapia.politicasagenda.ver');
        return PoliticaAgenda::findOrFail($id);
    }

    public function siguienteCodigo(): string
    {
        Gate::authorize('fisioterapia.politicasagenda.gestionar');
        $ultimo = 0;
        // Incluye políticas inactivas: sus códigos no se reutilizan.
        foreach (PoliticaAgenda::query()->select('codigo_version')->cursor() as $politica) {
            if (preg_match('/^POL-([0-9]+)$/i', trim($politica->codigo_version ?? ''), $partes)) {
                $numero = ltrim($partes[1], '0');
                if (strlen($numero) > 18) {
                    throw ValidationException::withMessages(['codigo_version' => 'El consecutivo de políticas supera el rango admitido.']);
                }
                $ultimo = max($ultimo, (int) $numero);
            }
        }
        return 'POL-'.str_pad((string) ($ultimo + 1), 3, '0', STR_PAD_LEFT);
    }

    public function validarEdicion(PoliticaAgenda $registro): void
    {
        if ($registro->estado !== true) {
            throw ValidationException::withMessages(['codigo_version' => 'La política de agenda está dada de baja y no puede editarse.']);
        }
    }

    public function validarFormulario(array $entrada): array
    {
        Gate::authorize('fisioterapia.politicasagenda.gestionar');
        $datos = Validator::make($entrada, [
            'cupos_normales' => ['required', 'integer', 'max:100'],
            'cupos_especiales' => ['required', 'integer', 'max:100'],
            'max_sesiones_normales' => ['required', 'integer', 'max:100'],
        ], [
            'required' => 'El campo :attribute es obligatorio.',
            'max' => 'El campo :attribute no debe superar :max caracteres.',
        ])->validate();
        $datos = array_map(fn ($valor) => is_string($valor) ? trim($valor) : $valor, $datos);
        foreach (['cupos_normales', 'cupos_especiales', 'max_sesiones_normales'] as $campo) {
            if ($datos[$campo] === '') {
                throw ValidationException::withMessages([$campo => 'Este campo es obligatorio.']);
            }
        }
        // $datos['grupo'] = ($datos['grupo'] ?? '') === '' ? null : $datos['grupo'];

        return $datos;
    }

    public function guardar(array $entrada, ?int $id = null): PoliticaAgenda
    {
        $datos = $this->validarFormulario($entrada);

        return DB::connection('pgsql')->transaction(function () use ($datos, $id) {
            if ($id === null) {
                // Serializa las altas, incluso cuando todavía no existe ninguna política.
                DB::connection('pgsql')->statement('LOCK TABLE fisioterapia.politicas_agenda IN SHARE ROW EXCLUSIVE MODE');
                $datos['codigo_version'] = $this->siguienteCodigo();
            }
            $politicaagenda = $id === null ? new PoliticaAgenda() : PoliticaAgenda::lockForUpdate()->findOrFail($id);
            if ($id !== null) {
                $this->validarEdicion($politicaagenda);
                $datos['codigo_version'] = $politicaagenda->codigo_version;
            }
            $duplicada = PoliticaAgenda::whereRaw('LOWER(codigo_version) = ?', [mb_strtolower($datos['codigo_version'])])
                ->when($id !== null, fn ($q) => $q->where('id', '<>', $id))->exists();
            if ($duplicada) {
                throw ValidationException::withMessages(['codigo_version' => 'Ya existe una política agenda con este código version, incluso si está inactiva.']);
            }
            $politicaagenda->fill($datos);
            if ($id === null) {
                $politicaagenda->estado = true;
                $politicaagenda->id_user_created = auth()->id();
            } else {
                $politicaagenda->id_user_updated = auth()->id();
            }
            // Eloquent asigna created_at/updated_at; no se reciben desde el formulario.
            $politicaagenda->save();
            return $politicaagenda;
        }, 3);
    }

    public function cambiarEstado(int $id, bool $estado): PoliticaAgenda
    {
        Gate::authorize('fisioterapia.politicasagenda.gestionar');
        return DB::connection('pgsql')->transaction(function () use ($id, $estado) {
            $politicaagenda = PoliticaAgenda::lockForUpdate()->findOrFail($id);
            $politicaagenda->estado = $estado;
            $politicaagenda->id_user_updated = auth()->id();
            $politicaagenda->save();
            return $politicaagenda;
        }, 3);
    }
}
