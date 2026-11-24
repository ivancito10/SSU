<?php

namespace App\Livewire\Fisioterapia\Ordenes;

use App\Services\Fisioterapia\OrdenesService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class OrdenesMain extends Component
{
    #[Locked]
    public array $detalle = [];

    public function boot(): void
    {
        Gate::authorize('fisioterapia.ordenes.ver');
    }

    #[On('fisioterapia:ver-orden')]
    public function ver(string $id): void
    {
        $this->detalle = [];
        try {
            $this->detalle = app(OrdenesService::class)->detalle($id);
            $this->dispatch('fisioterapia:abrir-orden');
        } catch (ModelNotFoundException $e) {
            $this->dispatch('fisioterapia:error-orden', mensaje: 'La orden ya no existe o no está disponible.');
        }
    }

    public function render()
    {
        return view('livewire.fisioterapia.ordenes.ordenes-main');
    }
}
