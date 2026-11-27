<?php

namespace App\Livewire\Afiliacion\Titulares;

use App\Models\Aportes\AporteVoluntario;
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

final class TitularConAporteVoluntario extends PowerGridComponent
{
    // use WithExport;
    public $idPersona;
    public string $sortField = 'aporte_v.id';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        return [
            // Exportable::make('export')
            //   ->striped(),
            //->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
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
        // dd($this->idPersona);
        return AporteVoluntario::query()

            ->join('aportes.contrato_voluntario as cv', 'cv.id', '=', 'aportes.aporte_v.id_contrato_voluntario')
            ->join('administracion.persona as p', 'p.id', '=', 'cv.id_persona')
            ->join('aportes.mes as m', 'm.id', '=', 'aportes.aporte_v.id_mes')
            ->where('cv.id_persona', $this->idPersona)
            ->select(
                'aportes.aporte_v.*',
                'm.mes as mes',
            )
            ->orderBy('aportes.aporte_v.id', 'desc');
           

            //dd($var);
            
    }


    public function relationSearch(): array
    {
        return [
            'mesAporteVoluntario' => 'mes',
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('id')
            ->add('total_ganado')
            ->add('total_aporte')
            ->add('gestion')
            ->add('id_contrato_voluntario')
            ->add('id_mes')
            ->add('afiliado')
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')->index()
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center'),

            Column::make('TOTAL GANADO', 'total_ganado')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('TOTAL APORTE', 'total_aporte')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('MES', 'mes')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('GESTION', 'gestion')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
        ];
    }


    //   #[\Livewire\Attributes\On('edit')]
    //   public function edit($rowId): void
    //   {
    //       $this->js('alert('.$rowId.')');
    //   }

    // public function actions(AporteVoluntario $row): array
    // {
    //     return [
    //         Button::add('edit')
    //             ->slot('Edit: '.$row->id)
    //             ->id()
    //             ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
    //             ->dispatch('edit', ['rowId' => $row->id])
    //     ];
    // }

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
