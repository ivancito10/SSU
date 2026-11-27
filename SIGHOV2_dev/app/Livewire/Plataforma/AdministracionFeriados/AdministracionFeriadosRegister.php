<?php

namespace App\Livewire\Plataforma\AdministracionFeriados;

use App\Models\Plataforma\AdministracionFeriados;
use Livewire\Component;
use App\Models\Plataforma\Dia;

use Livewire\Attributes\On;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AdministracionFeriadosRegister extends Component
{
    public $fecha, $id_dia, $dia, $dias, $descripcion, $estado;
    //protected $listeners = ['crearFeriado'];
    public function render()
    {
        return view('livewire.Plataforma.administracion-feriados.administracion-feriados-register');
    }
    public function mount()
    {
        $this->obtenerDatosDias($this->fecha);
    }

    public function abrirModalDeFeriados()
    {
        $this->dispatch('abrirModalFeriados');
    }
    #[On('obtenerNombreDia')]
    public function obtenerNombreDia()
    {
        return $this->dia = $this->obtenerDatosDias($this->fecha)['nombre_dia'];
    }
    public function obtenerDatosDias($fecha = null)
    {
        Carbon::setLocale('es');
        $fechaFormatear =  Carbon::parse($fecha);
        return [
            'nombre_dia' => mb_strtoupper($fechaFormatear->translatedFormat('l'), 'UTF-8'),
            'nro_dia' => $fechaFormatear->isoFormat('E'),
        ];
    }

    #[On('crearFeriado')]
    public function crearFeriado()
    {
        try {
            $fechaExite = AdministracionFeriados::where('fecha', $this->fecha)->first();
            if ($fechaExite) {
                $this->dispatch('fechaYunik');
                return;
            } else {
                if (!empty($this->fecha)  && !empty($this->descripcion)) {
                    $existeFeriado = AdministracionFeriados::where([
                        ['fecha', $this->fecha,]
                    ])->exists();
                    if (!$existeFeriado) {
                        $dato = AdministracionFeriados::create([
                            'fecha' => $this->fecha,
                            'nro_dia' => $this->obtenerDatosDias($this->fecha)['nro_dia'],
                            'nombre_dia' => $this->obtenerDatosDias($this->fecha)['nombre_dia'],
                            'descripcion' => strtoupper($this->descripcion),
                            'estado' => true,
                            'id_user_created' => auth()->user()->id,
                        ]);
                        $this->dispatch('pg:eventRefresh-FeriadosTable');
                        $this->dispatch('feriadoRegistrado');
                        $this->resetearValores();
                    } else {
                        $this->dispatch('feriadoYaExiste');
                    }
                } else {
                    $this->dispatch('faltaDatos');
                }
            }
        } catch (\Throwable $e) {
            $this->dispatch('feriadooNoRegistrado');

            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al crear feriado", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }
    public  function resetearValores()
    {
        $this->fecha = null;
        $this->descripcion  = null;
    }
}
