<?php

namespace App\Livewire\Afiliacion\EstudiantesSsue;

use App\Models\Afiliacion\HistorialMatriculaEstudiante;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class HistorialMatriculas extends PowerGridComponent
{
    use WithExport;
    public $idPersona;
    public string $sortField = 'historial_matricula_estudiante.id';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }
    protected $listeners = ['setPersonaId'];

    public function setPersonaId($idPersona)
    {
        $this->idPersona = $idPersona;
        $this->fillData();
    }

    public function datasource(): Builder
    {
        //dd($this->id_persona);
        return HistorialMatriculaEstudiante::query()->where('id_persona',$this->idPersona);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('id')
            ->add('id_persona')
            ->add('registro_universitario')
            ->add('nro_matricula')
            ->add('gestion_matricula')
            ->add('carrera')
            ->add('facultad')
            ->add('gestion_ingreso')
            ->add('tipo_estudiante', function ($dish) {
                if ($dish->tipo_estudiante == '1') {
                    return 'ESTUDIANTE REGULAR';
                }
                if ($dish->tipo_estudiante == '2') {
                    return 'ESTUDIANTE CON CARRERA PARALELA';
                }
                if ($dish->tipo_estudiante == '3') {
                    return 'ESTUDIANTE CON CAMBIO DE CARRERA';
                }
            })
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', '#')
                ->index()
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center'),

            Column::make('Registro universitario', 'registro_universitario')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('Nro matricula', 'nro_matricula')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('Gestion matricula', 'gestion_matricula')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('Carrera', 'carrera')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('Facultad', 'facultad')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->sortable()
                ->searchable(),

            Column::make('Gestion ingreso', 'gestion_ingreso')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('Tipo estudiante', 'tipo_estudiante')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),


        ];
    }

    public function filters(): array
    {
        return [];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }



    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
