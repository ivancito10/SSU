<?php

namespace App\Livewire\Vigencia\BajasInstitucionales;

use App\Models\Administracion\Persona;
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

final class AseguradosBajaInstitucionalTable extends PowerGridComponent
{
    use WithExport;

    public string $sortField = "persona.p_apellido";
    public string $sortDirection = "asc";

    public $carnet;
    public $complemento;
    public $matricula;

    public function setUp(): array
    {
        return [      
            //Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        $carnet = $this->carnet;
        $complemento = $this->complemento;
        $matricula = $this->matricula;
        return Persona::query()
            ->join('administracion.tipo_asegurado as tipo_asegurado', function ($tipo_asegurado) {
                $tipo_asegurado->on('persona.id_tipo_asegurado', '=', 'tipo_asegurado.id');
            })
            ->whereNot('persona.id_tipo_asegurado', 0)
            ->where('persona.afiliado', true)
            ->where(function ($query) use ($carnet, $complemento, $matricula) {
                $query->where('persona.ci', '=', $carnet)
                      ->where('persona.complemento', '=', $complemento)
                      ->orWhere('persona.matricula_seguro', '=', $matricula);
            })
            ->select('persona.*', 'tipo_asegurado.tipo_asegurado');
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
            ->add('ci')
            ->add('ci_formatted', function ($carnet){
                if($carnet->complemento == null){
                    return $carnet->ci;
                } else {
                    return $carnet->ci. ' - ' . $carnet->complemento;
                }
            })
            ->add('complemento')
            ->add('nombreCompleto', function ($nombres){
                if($nombres->p_apellido == null){
                    return $nombres->s_apellido . ' ' . $nombres->nombres;
                } elseif ($nombres->s_apellido == null){
                    return $nombres->p_apellido . ' ' . $nombres->nombres;
                } else {
                    return $nombres->p_apellido . ' ' . $nombres->s_apellido . ' ' . $nombres->nombres;
                }                
            })
            ->add('nombres')
            ->add('p_apellido')
            ->add('s_apellido')
            ->add('sexo')
            ->add('id_estado_civil')
            ->add('id_nacimiento_municipio')
            ->add('id_dept_exp')
            ->add('fecha_nacimiento_formatted', fn (Persona $model) => Carbon::parse($model->fecha_nacimiento)->format('d/m/Y'))
            ->add('matricula_seguro')
            ->add('id_tipo_asegurado')
            ->add('es_extranjero')
            ->add('estado_asuss')
            ->add('afiliado')
            ->add('grupo_sanguineo')
            ->add('alergias')
            ->add('clave_unica')
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->index(),

            Column::make('CARNET', 'ci_formatted', 'ci')
                ->sortable()
                ->searchable(),
            
            Column::make('MATRICULA', 'matricula_seguro')
                ->sortable()
                ->searchable(),

            Column::make('NOMBRE COMPLETO', 'nombreCompleto', 'p_apellido')
                ->sortable()
                ->searchable(),
            
            Column::make('Nombres', 'nombres')
                ->sortable()
                ->searchable()->hidden(isHidden: true, isForceHidden: true),

            Column::make('P apellido', 'p_apellido')
                ->sortable()
                ->searchable()->hidden(isHidden: true, isForceHidden: true),

            Column::make('S apellido', 's_apellido')
                ->sortable()
                ->searchable()->hidden(isHidden: true, isForceHidden: true),

            //Column::make('Fecha nacimiento', 'fecha_nacimiento_formatted', 'fecha_nacimiento')
            //    ->sortable(),

            Column::make('TIPO DE ASEGURADO', 'tipo_asegurado')
                ->sortable()
                ->searchable(),

            Column::action('OPCIONES')
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(Persona $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit: '.$row->id)
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('edit', ['rowId' => $row->id])
        ];
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
