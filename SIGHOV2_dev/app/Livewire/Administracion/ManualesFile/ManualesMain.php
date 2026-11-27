<?php

namespace App\Livewire\Administracion\ManualesFile;

use App\Models\Administracion\Manual;
use Livewire\Attributes\On;
use Livewire\Component;

class ManualesMain extends Component
{
    public ?int $manualEstadoId = null;
    public ?array $detalleManual = null;

    public function render()
    {
        return view('livewire.Administracion.manuales-file.manuales-main');
    }

    public function registrar_manual(): void
    {
        $this->dispatch('modalRegistrarManual');
    }

    #[On('ver_manual')]
    public function ver_manual($id): void
    {
        $idManual = is_array($id) ? ($id['id'] ?? null) : $id;
        if (!$idManual) {
            return;
        }

        $manual = Manual::with(['usuarioCreador', 'usuarioEditor'])->find($idManual);
        if (!$manual) {
            $this->dispatch('alertaErrorManual', mensaje: 'No se encontró el manual seleccionado.');
            return;
        }

        $this->detalleManual = [
            'id'             => $manual->id,
            'modulo'         => $manual->modulo,
            'titulo'         => $manual->titulo,
            'version'        => $manual->version ?: '1.0',
            'descripcion'    => $manual->descripcion ?: 'Sin descripción',
            'nombre_archivo' => $manual->nombre_archivo ?: 'S/A',
            'ruta_ftp'       => $manual->ruta_ftp ?: 'S/R',
            'tamano'         => $manual->tamano_formateado,
            'estado'         => (bool) $manual->estado,
            'url_ver'        => route('administracion.manuales.ver', ['id' => $manual->id]),
            'url_descargar'  => route('administracion.manuales.descargar', ['id' => $manual->id]),
            'user_crea'      => $manual->usuarioCreador?->name ?: 'SIN DATO',
            'fecha_creado'   => $manual->created_at?->toDateTimeString(),
            'user_edit'      => $manual->usuarioEditor?->name ?: 'SIN DATO',
            'fecha_editado'  => $manual->updated_at?->toDateTimeString(),
        ];

        $this->dispatch('modalVerManual');
    }

    #[On('cambiar_estado_manual')]
    public function cambiar_estado_manual($id): void
    {
        $idManual = is_array($id) ? ($id['id'] ?? null) : $id;
        if (!$idManual) {
            return;
        }

        $manual = Manual::find($idManual);
        if (!$manual) {
            return;
        }

        $this->manualEstadoId = $manual->id;

        if ($manual->estado) {
            $this->dispatch('deshabilitar_manual');
        } else {
            $this->dispatch('habilitar_manual');
        }
    }

    #[On('confirmar_estado_manual')]
    public function confirmar_estado_manual(): void
    {
        if (!$this->manualEstadoId) {
            return;
        }

        try {
            $manual = Manual::find($this->manualEstadoId);
            if (!$manual) {
                return;
            }

            $manual->estado = !$manual->estado;
            $manual->id_user_updated = auth()->id();
            $manual->save();

            $this->dispatch('pg:eventRefresh-ManualesTable');
            $this->dispatch($manual->estado ? 'manual_habilitado' : 'manual_deshabilitado');
            $this->manualEstadoId = null;
        } catch (\Throwable $e) {
            $this->dispatch('alertaErrorManual', mensaje: 'No se pudo actualizar el estado del manual.');
        }
    }
}
