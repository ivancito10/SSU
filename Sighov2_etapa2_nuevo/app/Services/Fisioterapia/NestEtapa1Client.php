<?php

namespace App\Services\Fisioterapia;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class NestEtapa1Client
{
    private array $memo = [];
    private ?string $errorConexion = null;

    private function base(): string
    {
        $url = rtrim((string) config('fisioterapia.nest.url'), '/');
        $host = parse_url($url, PHP_URL_HOST);
        $scheme = parse_url($url, PHP_URL_SCHEME);
        if (!$host || !in_array($scheme, ['http', 'https'], true)
            || ($scheme === 'http' && !in_array($host, ['localhost', '127.0.0.1', '[::1]'], true))) {
            throw new RuntimeException('Configure una URL HTTPS de NestJS (HTTP solo se admite en localhost).');
        }
        return $url;
    }

    private function cacheKey(): string
    {
        return 'fisioterapia:nest:token:'.hash('sha256', json_encode([
            config('fisioterapia.nest.url'), 
            config('fisioterapia.nest.username'), 
            config('fisioterapia.nest.password'),
        ]));
    }

    private function token(): string
    {
        return Cache::remember($this->cacheKey(), now()->addMinutes(5), function () {
            $username = config('fisioterapia.nest.username');
            $password = config('fisioterapia.nest.password');
            if (!$username || !$password) {
                $this->errorConexion = 'Falta configurar la cuenta técnica de NestJS en el servidor.';
                throw new RuntimeException($this->errorConexion);
            }
            $response = Http::acceptJson()->connectTimeout(3)->timeout(8)->withoutRedirecting()
                ->post($this->base().'/auth/login', compact('username', 'password'));
            $token = $response->json('accessToken');
            if (!$response->successful() || !is_string($token) || $token === '') {
                $this->errorConexion = 'No se pudo autenticar la cuenta técnica en NestJS.';
                throw new RuntimeException($this->errorConexion);
            }
            return $token;
        });
    }

    public function consultarFisioterapeutas(?int $id = null, string $accion = '', array $query = [], ?bool $estado = null): array
    {
        if (($accion === 'estado' && $estado === null) || ($id !== null && $id < 1) || !in_array($accion, ['', 'estado'], true)
            || ($accion !== '' && $id === null)) {
            throw new RuntimeException('Referencia de fisioterapeuta inválida.');
        }
        $url = $this->base().'/fisioterapeutas'.($id === null ? '' : '/'.$id).($accion === '' ? '' : '/'.$accion);
        for ($intento = 0; $intento < 2; $intento++) {
            $http = Http::acceptJson()->withToken($this->token())->connectTimeout(3)
                ->timeout((int) config('fisioterapia.nest.timeout', 8))->withoutRedirecting();
            $respuesta = $accion === 'estado' ? $http->patch($url, ['estado' => $estado]) : $http->get($url, $query);
            if ($respuesta->status() !== 401) {
                break;
            }
            Cache::forget($this->cacheKey());
        }
        $respuesta->throw();
        return $respuesta->json() ?? [];
    }

    private function peticionRegistro(string $ruta, array $datos, bool $post = false): array
    {
        for ($i = 0; $i < 2; $i++) {
            $http = Http::acceptJson()->withToken($this->token())->connectTimeout(3)
                ->timeout((int) config('fisioterapia.nest.timeout', 8))->withoutRedirecting();
            $respuesta = $post ? $http->post($this->base().$ruta, $datos) : $http->get($this->base().$ruta, $datos);
            if ($respuesta->status() !== 401) break;
            Cache::forget($this->cacheKey());
        }
        return $respuesta->throw()->json();
    }

    public function personaParaIncorporar(string $ci, string $complemento): ?array
    {
        $persona = null;
        $pagina = 1;
        do {
            $query = ['ci'=>$ci,'pagina'=>$pagina++, 'limite'=>100];
            if ($complemento !== '') $query['complemento'] = $complemento;
            $r = $this->peticionRegistro('/personas', $query);
            if (!is_array($r['datos'] ?? null) || !isset($r['meta']['lastPage'])) {
                throw new RuntimeException('Respuesta de personas inválida.');
            }
            foreach ($r['datos'] as $fila) {
                if (strcasecmp($fila['ci'],$ci) || strcasecmp(trim($fila['complemento'] ?? ''),$complemento)) continue;
                if ($persona !== null) throw new \DomainException('Hay varias personas con el mismo documento exacto. Revise los registros antes de continuar.');
                $persona = $fila;
            }
        } while ($pagina <= $r['meta']['lastPage']);
        return $persona;
    }

    public function listarSedes(): array
    {
        $sedes = [];
        $pagina = 1;
        do {
            $r = $this->peticionRegistro('/sedes', ['pagina' => $pagina++, 'limite' => 100]);
            foreach ($r['datos'] as $sede) {
                $sedes[$sede['id']] = implode(' - ', array_filter([$sede['ubicacion'], $sede['piso']], fn ($v) => filled($v))) ?: 'Sede '.$sede['id'];
            }
        } while ($pagina <= $r['meta']['lastPage']);
        return $sedes;
    }

    public function catalogosRegistro($departamento, $provincia): array
    {
        return $this->peticionRegistro('/fisioterapeutas/catalogos', array_filter([
            'idDepartamento'=>$departamento,'idProvincia'=>$provincia,
        ]));
    }

    public function incorporar(array $datos): array
    {
        return $this->peticionRegistro('/fisioterapeutas/incorporaciones', $datos, true);
    }

    public function buscarIdsPorNombre(string $recurso, string $nombre): array
    {
        if (!in_array($recurso, ['personas', 'usuarios'], true)) {
            throw new RuntimeException('Recurso de búsqueda inválido.');
        }
        $ids = [];
        $pagina = 1;
        do {
            try {
                for ($attempt = 0; $attempt < 2; $attempt++) {
                    $response = Http::acceptJson()->withToken($this->token())->connectTimeout(3)
                        ->timeout((int) config('fisioterapia.nest.timeout', 8))->withoutRedirecting()
                        ->get($this->base().'/'.$recurso, ['nombre' => $nombre, 'pagina' => $pagina, 'limite' => 200]);
                    if ($response->status() !== 401) break;
                    Cache::forget($this->cacheKey());
                }
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                throw new RuntimeException('La búsqueda no recibió respuesta de NestJS a tiempo o no pudo conectarse. Intente con nombre y apellido; si persiste, revise el servicio.');
            }
            $data = $response->json();
            if (!$response->successful() || !is_array($data) || !is_array($data['datos'] ?? null)
                || !is_int($data['meta']['lastPage'] ?? null) || $data['meta']['lastPage'] < 1) {
                throw new RuntimeException('No se pudo completar la búsqueda por nombre. Intente nuevamente.');
            }
            if ($data['meta']['lastPage'] > 50) {
                throw new RuntimeException('Hay demasiadas coincidencias. Escriba nombre y apellido para precisar la búsqueda.');
            }
            foreach ($data['datos'] as $row) {
                $id = (string) ($row['id'] ?? '');
                if (!preg_match('/^[1-9][0-9]{0,18}$/', $id)) {
                    throw new RuntimeException('El servicio devolvió una referencia de búsqueda inválida.');
                }
                $ids[$id] = $id;
            }
            if (count($ids) > 10000) {
                throw new RuntimeException('Hay demasiadas coincidencias. Escriba nombre y apellido.');
            }
            $pagina++;
        } while ($pagina <= $data['meta']['lastPage']);
        return array_values($ids);
    }

    public function obtener(string $recurso, string|int|null $id): ?array
    {
        if ($id === null) return null;
        if (!in_array($recurso, ['usuarios', 'personas', 'especialistas/persona', 'sedes'], true)
            || !preg_match('/^[1-9][0-9]{0,18}$/', (string) $id)) {
            throw new RuntimeException('Referencia de etapa 1 inválida.');
        }
        $path = $recurso.'/'.$id;
        if (array_key_exists($path, $this->memo)) return $this->memo[$path];
        if ($this->errorConexion) throw new RuntimeException($this->errorConexion);
        try {
            for ($attempt = 0; $attempt < 2; $attempt++) {
                $response = Http::acceptJson()->withToken($this->token())->connectTimeout(3)
                    ->timeout((int) config('fisioterapia.nest.timeout', 8))->withoutRedirecting()
                    ->get($this->base().'/'.$path);
                if ($response->status() !== 401) break;
                Cache::forget($this->cacheKey());
            }
            if ($response->status() === 404) return $this->memo[$path] = null;
            if ($response->status() === 409) {
                throw new RuntimeException('La persona tiene varios registros de especialista; revise la referencia de la orden.');
            }
            if (!$response->successful()) {
                $this->errorConexion = 'NestJS no está disponible o la cuenta técnica no tiene acceso.';
                throw new RuntimeException($this->errorConexion);
            }
            $data = $response->json();
            if (!is_array($data) || !isset($data[$recurso === 'especialistas/persona' ? 'especialista' : 'id'])) {
                throw new RuntimeException('NestJS devolvió datos con un formato inesperado.');
            }
            return $this->memo[$path] = $data;
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $this->errorConexion = 'No se pudo conectar con NestJS. Intente nuevamente más tarde.';
            throw new RuntimeException($this->errorConexion);
        }
    }
}