<?php

namespace App\Livewire\Plataforma\Consultorios;

use App\Models\Plataforma\Sedes;
use App\Models\Administracion\Residencia;
use App\Models\Plataforma\Consultorio;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ConsultoriosForm extends Component
{
    public $ubicaciones;
    public $ubicacion;
    public $piso;
    public $ubicaciones_pisos = [];
    public $nro_consultorio;
    public $direccion;


    public function render()
    {
        $this->ubicaciones = Sedes::select('ubicacion')->groupBy('ubicacion')->get();
        return view('livewire.Plataforma.consultorios.consultorios-form');
    }

    public function VerUbicacion()
    {

        $this->ubicaciones_pisos = Sedes::where('ubicacion', 'like', $this->ubicacion)->get();
    }

    public function direccionSeleccionar()
    {

        $residenciaActual = Sedes::where('ubicacion', 'like', $this->ubicacion)
            ->where('id', $this->piso)->first();

        //  $residenciaActual = Sedes::find($this->piso);
        //$direccion = Residencia::find($residenciaActual->id_residencia);
        //$this->direccion = $direccion->direccion . ' ' . $direccion->nro_vivienda;

        if ($residenciaActual) {
            $direccion = Residencia::find($residenciaActual->id_residencia);
            if ($direccion) {
                $this->direccion = $direccion->direccion . ' ' . $direccion->nro_vivienda;
            } else {
                $this->direccion = null;
            }
        } else {
            $this->direccion = null;
        }
    }

    public function cancelar()
    {
        $this->dispatch('cerrar-modal')->to(ConsultoriosMain::class);
    }

    public function guardar()
    {
        try {
            // Verificar si el número de consultorio está vacío
            if (!empty($this->nro_consultorio)) {
                // Verificar si el consultorio ya existe
                $existeConsultorio = Consultorio::where('id_sedes', $this->piso)
                    ->where('numero_consultorio', $this->nro_consultorio)
                    ->exists();

                // Si no existe, crear un nuevo consultorio
                if (!$existeConsultorio) {
                    Consultorio::create([
                        'numero_consultorio' => $this->nro_consultorio,
                        'id_sedes' => $this->piso,
                        'estado' => 1,
                        'id_user_created' => auth()->user()->id,
                    ]);

                    // Notificar éxito y actualizar la vista
                    $this->dispatch('registro-correcto')->to(ConsultoriosMain::class);
                    $this->dispatch('pg:eventRefresh-ConsultoriosTable');
                } else {
                    // Notificar que el consultorio ya existe
                    $this->dispatch('consultorioYaExiste');
                }

                // Limpiar campos
                $this->reset(['nro_consultorio', 'piso', 'ubicaciones_pisos', 'direccion', 'ubicacion']);
            }
        } catch (\Throwable $e) {
            $this->dispatch('registro-incorrecto')->to(ConsultoriosMain::class);
            $this->reset(['nro_consultorio', 'piso', 'ubicaciones_pisos', 'direccion', 'ubicacion']);

            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al guardar consultorio", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }


    /*
    public function guardar(){
        try {
            if($this->nro_consultorio != null || $this->nro_consultorio != ''){
                $existeConsultorio = Consultorio::where('id_sedes',$this->piso)
                ->where('numero_consultorio',$this->nro_consultorio)
                ->first();
                if(!$existeConsultorio){
                    $consultorio = Consultorio::create([
                        'numero_consultorio' => $this->nro_consultorio,
                        'id_sedes' => $this->piso,
                        'estado' => 1,
                        'id_user_created' => auth()->user()->id,
                    ]);

                    $this->dispatch('registro-correcto')->to(ConsultoriosMain::class);
                    $this->dispatch('pg:eventRefresh-ConsultoriosTable');
                    $this->reset('nro_consultorio'); 
                    $this->reset('piso'); 
                    $this->reset('ubicaciones_pisos'); 
                    $this->reset('direccion'); 
                    $this->reset('ubicacion'); 
                }else{

                    $this->dispatch('consultorioYaExiste');
                }
            } 
        } catch (\Throwable $th) {
            $this->dispatch('registro-incorrecto')->to(ConsultoriosMain::class);
            $this->reset('nro_consultorio'); 
            $this->reset('piso'); 
            $this->reset('ubicaciones_pisos'); 
            $this->reset('direccion'); 
            $this->reset('ubicacion'); 
        }
    }*/
}
