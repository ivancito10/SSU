<?php

namespace App\Livewire\AtencionMedica\MisRecetas;

use Livewire\Component;
use App\Models\User;
use App\Services\RecetasAseguradoService;

class RecetasAsegurado extends Component
{
    public $nombreCompleto, $matricula;
    public $recetas = [];
    public function render()
    {
        return view('livewire.atencion-medica.mis-recetas.recetas-asegurado');
    }
    public function mount()
    {
        $this->nombreCompleto = auth()->user()->name;
        $this->matricula = auth()->user()->matricula;
        // $id_persona=User::find(auth()->user()->id)->id_persona;
        // $this->consultarRecetas($id_persona);
    }
    public function consultarRecetas()
    {
        $id_persona = User::find(auth()->user()->id)->id_persona;
        // dd($id_persona);
        $response = RecetasAseguradoService::recetasPersona($id_persona);
        dd($response->json());
        if ($response->successful()) {
            $this->recetas = $response->json();
        } else {
            // Extraemos el mensaje del JSON del backend, si no existe usamos uno por defecto
            $errorData = $response->json();
            $mensajeBackend = $errorData['message'] ?? $errorData['error'] ?? 'Error desconocido';
            $codigoStatus = $response->status();

            // Despachamos el evento con datos dinámicos
            $this->dispatch('mostrarSweetAlert', [
                'tipo'    => $this->obtenerIcono($codigoStatus),
                'titulo'  => "Error $codigoStatus",
                'mensaje' => $mensajeBackend,
            ]);
        }
    }

    // Función auxiliar para cambiar el ícono según el código
    private function obtenerIcono($status)
    {
        return match (true) {
            $status >= 500 => 'error',     // Error de servidor
            $status === 404 => 'info',     // No encontrado
            $status === 403 => 'warning',  // No autorizado
            default => 'error',
        };
    }
}
