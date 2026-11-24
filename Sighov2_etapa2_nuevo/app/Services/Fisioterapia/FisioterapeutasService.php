<?php

namespace App\Services\Fisioterapia;

use Illuminate\Support\Collection;
use RuntimeException;

class FisioterapeutasService
{
    public function __construct(private NestEtapa1Client $nest) {}

    public function listar(): Collection
    {
        $filas = collect();
        $pagina = 1;
        do {
            $respuesta = $this->nest->consultarFisioterapeutas(query: ['pagina' => $pagina++, 'limite' => 100]);
            if (!is_array($respuesta['datos'] ?? null) || !is_int($respuesta['meta']['lastPage'] ?? null)) {
                throw new RuntimeException('Respuesta de listado inválida.');
            }
            foreach ($respuesta['datos'] as $f) {
                $filas->put($f['idEspecialista'], (object) [
                    'id' => $f['idEspecialista'],
                    'carnet' => self::carnet($f),
                    'matricula' => $f['matricula'],
                    'nombre' => $f['nombreCompleto'],
                    'contrato' => $f['tipoContrato'],
                    'desde' => $f['contratoDesde'],
                    'hasta' => $f['contratoHasta'],
                    'estado' => $f['estado'],
                ]);
            }
        } while ($pagina <= $respuesta['meta']['lastPage']);
        return $filas->values();
    }

    public static function carnet(array $persona): string
    {
        return implode('-', array_filter([$persona['ci'], $persona['complemento']], fn ($v) => $v !== null && $v !== ''));
    }

    public function detalle(int $id): array
    {
        $f = $this->nest->consultarFisioterapeutas($id)['fisioterapeuta'] ?? null;
        if (!is_array($f)) {
            throw new RuntimeException('Respuesta de detalle inválida.');
        }
        $f['carnet'] = self::carnet($f);
        return $f;
    }

    public function cambiarEstado(int $id, bool $estado): void
    {
        $this->nest->consultarFisioterapeutas($id, 'estado', estado: $estado);
    }
}