<?php

namespace App\Livewire\Afiliacion\Ssue;

use Livewire\Component;
use App\Models\Administracion\Persona;
use App\Models\Afiliacion\Estudiante;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Afiliacion\Facultad;
use App\Models\Afiliacion\Carrera;
use Illuminate\Support\Facades\Log;

class SsueCarnetEstudiante extends Component
{
    public $idEst;
    public $nombres, $foto_estudiante, $p_apellido, $s_apellido, $ci,
     $matricula, $edad, $registro_universitario, $carrera, $facultad,
      $fecha_afiliacion, $fecha_vencimiento;
    public function render()
    {
        return view('livewire.Afiliacion.ssue.ssue-carnet-estudiante');
    }

    public function mount(){
        $this->generarCarnetSsue($this->idEst);
    }
    public function generarCarnetSsue($rowId){

        try{
            $personaActual = Persona::find($rowId);
        $this->nombres = $personaActual->nombres;
        $this->p_apellido = $personaActual->p_apellido;
        $this->s_apellido = $personaActual->s_apellido;
        $fecha_nac = $personaActual->fecha_nacimiento;
        $complemento = $personaActual->complemento;
        $this->matricula = $personaActual->matricula_seguro;
        if ($complemento) {
            $this->ci = $personaActual->ci . ' - ' .  $complemento;
        } else {
            $this->ci = $personaActual->ci;
        }

        $estudiante = Estudiante::where('id_persona', $rowId)->where('estado', true)->first();
        $this->foto_estudiante = $this->obtenerImagenBase64($estudiante->foto_nombre);
        $this->registro_universitario = $estudiante->registro_universitario;
        $this->fecha_afiliacion = Carbon::parse($estudiante->fecha_afiliacion)->format('d/m/Y') ;
        $this->fecha_vencimiento = Carbon::parse($estudiante->fecha_fin_vigencia)->format('d/m/Y') ;
         
        $carreraActual = Carrera::find($estudiante->id_carrera);
        if ($carreraActual) {
            $this->carrera = $carreraActual->nombre ?? ' ';
            $id_facultad = $carreraActual->id_facultad;
            if ($id_facultad) {
                $facultadActual = Facultad::find($id_facultad);
                $this->facultad = $facultadActual->nombre;
            } else {
                $this->facultad = '';
            }
        }

        
        if($fecha_nac){
            $this->edad = Carbon::parse($fecha_nac)->age;
        }
        }catch(\Throwable $e){
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Afiliacion:error al generar carnet Ssue", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
       
    }
 
    private function obtenerImagenBase64($filename)
    {
        try {
            if ($filename) {
                $path = 'Afiliacion/SSUE/FOTOS/' . $filename;
                $imageData = Storage::disk('ftp')->get($path);
                
                $fotito = base64_encode($imageData);
                return $fotito;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }
}
