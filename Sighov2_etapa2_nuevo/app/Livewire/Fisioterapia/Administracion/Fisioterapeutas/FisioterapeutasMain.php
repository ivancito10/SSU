<?php

namespace App\Livewire\Fisioterapia\Administracion\Fisioterapeutas;

use App\Services\Fisioterapia\FisioterapeutasService;
use Illuminate\Support\Facades\Gate;
use App\Services\Fisioterapia\HorariosSemanalesService;
use Livewire\Attributes\{Locked, On};
use Livewire\Component;

class FisioterapeutasMain extends Component
{
    #[Locked] public array $detalle = [];
    #[Locked] public ?array $horarios = null;
    #[Locked] public ?int $pendiente = null;
    #[Locked] public ?bool $estadoPendiente = null;

    #[On('ver-fisioterapeuta')]
    public function ver(int $id, FisioterapeutasService $servicio, HorariosSemanalesService $horarios): void
    {
        Gate::authorize('fisioterapia.administrar');
        $this->horarios = null;
        try {
            $this->detalle = $servicio->detalle($id);
        } catch (\Exception $e) {
            report($e);
            $this->dispatch('error-fisioterapeutas');
            return;
        }
        try {
            $this->horarios = $horarios->vigentesPorEspecialista($id);
        } catch (\Exception $e) {
            report($e);
        }
        $this->dispatch('abrir-ficha-fisioterapeuta');
    }

    #[On('solicitar-estado-fisioterapeuta')]
    public function solicitarEstado(int $id, bool $estado, FisioterapeutasService $servicio): void
    {
        Gate::authorize('fisioterapia.administrar');
        $this->pendiente = null;
        try {
            $f = $servicio->detalle($id);
            if ($f['estado'] === !$estado) {
                $this->pendiente = $id;
                $this->estadoPendiente = $estado;
                $this->dispatch('confirmar-estado-fisioterapeuta', estado: $estado, nombre: $f['nombreCompleto'], id: $id);
            } else {
                $this->dispatch('pg:eventRefresh-FisioterapeutasTable');
            }
        } catch (\Exception $e) {
            report($e);
            $this->dispatch('error-fisioterapeutas');
        }
    }

    public function confirmarEstado(int $id, bool $estado, FisioterapeutasService $servicio): void
    {
        Gate::authorize('fisioterapia.administrar');
        if ($this->pendiente !== $id || $this->estadoPendiente !== $estado) return;
        $this->pendiente = null;
        try {
            $servicio->cambiarEstado($id, $estado);
            $this->dispatch('pg:eventRefresh-FisioterapeutasTable');
            $this->dispatch('estado-fisioterapeuta-guardado', estado: $estado);
        } catch (\Exception $e) {
            report($e);
            $this->dispatch('error-fisioterapeutas');
        }
    }

    public function crear()
    {
        Gate::authorize('fisioterapia.administrar');
        return redirect()->route('fisioterapia.administracion.fisioterapeutas.registrar');
    }
    #[Locked] public ?int $idHorarioEspecialista = null;
    #[Locked] public string $nombreHorario = '';
    #[Locked] public array $sedesHorario = [], $politicasHorario = [];
    public array $turno = [];

    #[On('agregar-horario')]
    public function abrirHorario(int $id, FisioterapeutasService $fisios, HorariosSemanalesService $horarios, \App\Services\Fisioterapia\NestEtapa1Client $nest): void
    {
        Gate::authorize('fisioterapia.administrar');
        $this->resetValidation();
        $this->idHorarioEspecialista = null;
        try {
            $f = $fisios->detalle($id);
            if ($f['estado'] !== true) {
                $this->dispatch('error-fisioterapeutas');
                return;
            }
            $this->sedesHorario = $nest->listarSedes();
            $this->politicasHorario = $horarios->politicas();
            $this->nombreHorario = $f['nombreCompleto'];
            $this->turno = ['dia'=>'', 'intervalo'=>'', 'desde'=>'', 'hasta'=>'', 'sede'=>'',
                'politica'=>count($this->politicasHorario) === 1 ? array_key_first($this->politicasHorario) : ''];
            $this->idHorarioEspecialista = $id;
            $this->dispatch('mostrar-formulario-horario');
        } catch (\Exception $e) {
            report($e);
            $this->dispatch('error-fisioterapeutas');
        }
    }

    private function validarHorario(): array
    {
        Gate::authorize('fisioterapia.administrar');
        abort_unless($this->idHorarioEspecialista, 409);
        $datos = $this->validate([
            'turno.dia'=>'required|integer|between:1,5', 'turno.intervalo'=>'required|integer|between:1,1440',
            'turno.desde'=>'required|date_format:H:i', 'turno.hasta'=>'required|date_format:H:i|after:turno.desde',
            'turno.sede'=>['required', \Illuminate\Validation\Rule::in(array_keys($this->sedesHorario))],
            'turno.politica'=>['required', \Illuminate\Validation\Rule::in(array_keys($this->politicasHorario))],
        ])['turno'];
        $minutos = fn ($hora) => (int) substr($hora,0,2) * 60 + (int) substr($hora,3,2);
        if ($datos['intervalo'] > $minutos($datos['hasta']) - $minutos($datos['desde'])) {
            throw \Illuminate\Validation\ValidationException::withMessages(['turno.intervalo'=>'El intervalo supera la duración del turno.']);
        }
        return $datos;
    }

    public function solicitarHorario(): void
    {
        $this->validarHorario();
        $this->dispatch('confirmar-horario');
    }

    public function guardarHorario(FisioterapeutasService $fisios, HorariosSemanalesService $horarios): void
    {
        $datos = $this->validarHorario();
        try {
            $id = $this->idHorarioEspecialista;
            if ($fisios->detalle($id)['estado'] !== true) {
                $this->addError('turno.operacion', 'El fisioterapeuta está deshabilitado.');
                return;
            }
            $horarios->registrar($id, $datos, auth()->id());
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            report($e);
            $this->addError('turno.operacion', 'No se pudo guardar el horario. Vuelva a intentarlo.');
            return;
        }
        $this->idHorarioEspecialista = null;
        $this->dispatch('horario-registrado');
    }

    public function render()
    {
        return view('livewire.fisioterapia.administracion.fisioterapeutas.fisioterapeutas-main');
    }
}