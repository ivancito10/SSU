<?php

namespace App\Livewire\Plataforma\SuspensionHorario;

use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class SuspensionHorarioMasivoTable extends PowerGridComponent
{
    public $especialistas;

    #[On('suspensionMasiva')]
    public function suspensionMasiva($esp)
    {
        $this->especialistas = $esp;
        $this->datasource();
    }

    #[On('actualizarEstadoEspecialista')]
    public function actualizarEstadoEspecialista($data)
    {
        foreach ($this->especialistas as &$especialista) {
            if (
                $especialista['id_especialista'] == $data['id_especialista'] &&
                $especialista['id_asignacion_horario'] == $data['id_asignacion_horario'] &&
                $especialista['id_dia'] == $data['id_dia']
            ) {
                $especialista['estado'] = false;
                break;
            }
        }
        unset($especialista);
        $this->datasource();
    }

    public function datasource(): ?Collection
    {
        //dd(collect([$this->especialistas]));

        return collect($this->especialistas)
            ->where('estado', true)
            ->map(function ($especialista) {
                return [
                    'id_especialista' => $especialista['id_especialista'],
                    'id_asignacion_horario' => $especialista['id_asignacion_horario'],
                    'id_dia' => $especialista['id_dia'],
                    'nombre_especialista' => $especialista['nombre_especialista'] ?? '',
                    'carnet_especialista' => $especialista['carnet_especialista'] ?? '',
                    'especialidad' => $especialista['especialidad'] ?? '',
                    'estado' => $especialista['estado']
                ];
            });
    }

    public function setUp(): array
    {
        return [

            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('nombre_especialista')
            ->add('carnet_especialista')
            ->add('especialidad');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->index()
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center'),

            Column::make('NOMBRE ESPECIALISTA', 'nombre_especialista')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable()
                ->sortable(),

            Column::make('CARNET', 'carnet_especialista')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable()
                ->sortable(),

            Column::make('ESPECIALIDAD', 'especialidad')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable()
                ->sortable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center'),
        ];
    }

    public function actions($row)
    {
        //dd($row, $row['id_especialista']);
        $buttons = [];
        $buttons[] = Button::add('suspender')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus-fill" viewBox="0 0 16 16">
                    <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                    <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5"/>
                    </svg>')
            ->class('btn btn-red')
            ->dispatchTo(
                'plataforma.suspension-horario.suspension-horario-masivo',
                'especialistaSeleccionado',
                [
                    'id_especialista' => $row['id_especialista'],
                    'id_asignacion_horario' => $row['id_asignacion_horario'],
                    'id_dia' => $row['id_dia']
                ]
            )
            ->tooltip('REGISTRAR SUSPENSIÓN DE HORARIO');
        return $buttons;
    }
}
