<?php

namespace App\Livewire\Vigencia\OrdenesServicio;

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

final class PersonaSelectTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'personasOrdenesTable';
    public string $sortField = 'persona.id';
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

    public function datasource(): Builder
    {
        
        return Persona::where('afiliado', true)         
            ->whereNot('id_tipo_asegurado', 0);
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
            ->add('complemento')
            ->add('ci_formatted', function ($ci){
                if($ci->complemento == null){
                    return $ci->ci;
                } else {
                    return $ci->ci.' - '.$ci->complemento;
                }
            })
            ->add('nombres')
            ->add('p_apellido')
            ->add('s_apellido')
            ->add('persona_nombre_completo', function ($nombrePersona){
                if($nombrePersona->p_apellido == null){
                    return $nombrePersona->s_apellido. ' '.$nombrePersona->nombres;
                } elseif ($nombrePersona->s_apellido == null){
                    return $nombrePersona->p_apellido. ' ' . $nombrePersona->nombres;
                } else {
                    return $nombrePersona->p_apellido. ' ' .$nombrePersona->s_apellido. ' ' .$nombrePersona->nombres; 
                }
            })
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
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')->index()
            ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center'),

            Column::make('NOMBRE COMPLETO', 'persona_nombre_completo','p_apellido')
            ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->sortable()
                ->searchable(),


            Column::make('AP. PATERNO', 'p_apellido')
            ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->sortable()
                ->searchable()
                ->hidden(isHidden: true, isForceHidden: true),
                
            Column::make('AP. PATERNO', 'p_apellido ')
            ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->sortable()
                ->searchable()
                ->hidden(isHidden: true, isForceHidden: true),

            Column::make('AP. MATERNO', 's_apellido')
            ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->sortable()
                ->searchable()
                ->hidden(isHidden: true, isForceHidden: true),
                
            Column::make('NOMBRES', 'nombres')
            ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->sortable()
                ->searchable()
                ->hidden(isHidden: true, isForceHidden: true),

        
            Column::make('CARNET','ci_formatted' ,'ci')
            ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
               // ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

           

            Column::make('MATRICULA', 'matricula_seguro')
            ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
               // ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::action('OPCIONES')
            ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
            ->bodyAttribute('text-center')
        ];
    }

    public function filters(): array
    {
        return [

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
            Button::add('registrar')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus-fill" viewBox="0 0 16 16">
                <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5"/>
                </svg>')
            ->class('btn btn-green')
            ->route('vigencia.OrdenesServicio.create', ['id' => $row->id])
            ->tooltip('Agregar Orden de Servicio'),
    

        ];
    }
}
