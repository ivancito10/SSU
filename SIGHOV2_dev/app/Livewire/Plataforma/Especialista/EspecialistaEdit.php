<?php

namespace App\Livewire\Plataforma\Especialista;

use App\Models\Plataforma\Especialista;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;

class EspecialistaEdit extends Component
{
    use WithFileUploads;

    public $idEspecialista;

    public $nombreCompleto;
    public $matricula;
    public $carnet;
    public $fechaNacimiento;
    public $gradoAcademico;
    public $especialidad;
    public $permanente;
    public $afiliados;
    public $estudiantes;
    public $convenios;
    public $fechaContratoInicio;
    public $fechaContratoFin;
    public $foto;
    public $switchFotoNueva;

    public $especialistaImagenologia;

    public $especialistaImagenologiaMostrar;

    public function render()
    {
        return view('livewire.Plataforma.especialista.especialista-edit');
    }

    public function mount()
    {
        $this->switchFotoNueva = false;
        $this->datosEspecialista($this->idEspecialista);
    }



    public function datosEspecialista($idEspecialista)
    {
        $especialista = Especialista::with(['personaEspecialista', 'especialidadEspecialista'])
            ->find($idEspecialista);

        $this->nombreCompleto = implode(' ', array_filter([
            $especialista->personaEspecialista->p_apellido,
            $especialista->personaEspecialista->s_apellido,
            $especialista->personaEspecialista->nombres
        ]));
        $this->matricula = $especialista->personaEspecialista->matricula_seguro;
        $this->carnet = implode(' - ', array_filter([
            $especialista->personaEspecialista->ci,
            $especialista->personaEspecialista->complemento
        ]));
        $this->fechaNacimiento = Carbon::parse($especialista->personaEspecialista->fecha_nacimiento)->format('d/m/Y');
        $this->permanente = $especialista->permanente;
        $this->gradoAcademico = $especialista->grado_academico;
        $this->afiliados = $especialista->afiliados;
        $this->estudiantes = $especialista->estudiantes;
        $this->convenios = $especialista->convenios;
        $this->fechaContratoInicio = $especialista->fecha_contrato_inicio;
        $this->fechaContratoFin = $especialista->fecha_contrato_fin;
        $this->especialidad = $especialista->especialidadEspecialista->especialidad;
        $this->especialistaImagenologia = $especialista->especialista_imagenologia;
        $this->especialistaImagenologiaMostrar = $especialista->especialidadEspecialista->especialidad_imagenologia;
        $this->foto = $this->obtenerImagenBase64Doctores($especialista->foto);
    }

    #[On('modificarEspecialista')]
    public function modificarEspecialista()
    {
        //$this->validate();
        if ($this->fechaContratoInicio === "" || $this->fechaContratoInicio === null) {
            $this->fechaContratoInicio = null;
        }

        if ($this->fechaContratoFin === "" || $this->fechaContratoFin === null) {
            $this->fechaContratoFin = null;
        }

        if ($this->permanente) {
            $this->fechaContratoInicio = null;
            $this->fechaContratoFin = null;
        }

        if ((!$this->permanente && $this->fechaContratoInicio === null) || (!$this->permanente && $this->fechaContratoFin === null)) {
            $this->dispatch('eventualSinContrato');
            return;
        }

        try {
            $especialistaActualizar = Especialista::find($this->idEspecialista);

            if ($especialistaActualizar) {
                $especialistaActualizar->afiliados = $this->afiliados;
                $especialistaActualizar->convenios = $this->convenios;
                $especialistaActualizar->estudiantes = $this->estudiantes;
                $especialistaActualizar->permanente = $this->permanente;
                $especialistaActualizar->fecha_contrato_inicio = $this->fechaContratoInicio;
                $especialistaActualizar->fecha_contrato_fin = $this->fechaContratoFin;
                $especialistaActualizar->especialista_imagenologia = $this->especialistaImagenologia;
                $especialistaActualizar->id_user_updated = auth()->user()->id;


                if ($this->switchFotoNueva) {
                    $nombreFoto = $especialistaActualizar->id . '.jpg';
                    $imagePath = $this->foto->getRealPath();
                    $imageData = file_get_contents($imagePath);
                    $this->foto->storeAs('Plataforma/Especialista/Fotos/', $nombreFoto, 'ftp');
                }
                $especialistaActualizar->foto = $especialistaActualizar->id . '.jpg';
                $especialistaActualizar->save();
            }

            $this->dispatch('especialistaModificado');
            return redirect()->route('plataforma.especialistas.index');
        } catch (\Throwable $e) {
            $this->dispatch('especialistaNoModificado');

            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error en modificarEspecialista", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    public function cambioFoto()
    {
        $this->switchFotoNueva = true;
    }

    public function cancelarEspecialista()
    {
        return redirect()->route('plataforma.especialistas.index');
    }

    private function obtenerImagenBase64Doctores($nombreFoto)
    {
        try {
            if ($nombreFoto) {
                try {
                    $path = 'Plataforma/Especialista/Fotos/' . $nombreFoto;
                    $imageData = Storage::disk('ftp')->get($path);
                    $foto_medico = base64_encode($imageData);
                    return $foto_medico;
                } catch (\Exception $e) {
                    Log::error('Error al acceder al servidor FTP en la edición de Especialistas: ' . $e->getMessage());
                    return $foto_medico = null;
                }
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }
}
