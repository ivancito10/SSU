<?php

namespace App\Livewire\Vigencia\BajasMedicas;

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

final class BajaAfiliadosTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'afiliadosBajaTable';
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
        return Persona::query()
            ->leftJoin('afiliacion.titular as titular', function ($titular) {
                $titular->on('persona.id', '=', 'titular.id_persona')
                    ->where('titular.estado', true);
            })
            ->leftJoin('afiliacion.beneficiario as beneficiario', function ($beneficiario) {
                $beneficiario->on('persona.id', '=', 'beneficiario.id_persona')
                    ->where('beneficiario.con_remuneracion', true);
            })
            ->join('administracion.tipo_asegurado as tipo_asegurado', function ($tipo_asegurado) {
                $tipo_asegurado->on('persona.id_tipo_asegurado', '=', 'tipo_asegurado.id');
            })
            ->distinct()
            ->where('persona.afiliado', true)
            ->where(function ($query) {
                $query->where('titular.estado_vigencia', 'VIGENTE')
                    ->orWhere('titular.estado_vigencia', 'CESANTE')
                    ->orWhere('beneficiario.vigencia', 'VIGENTE')
                    ->orWhere('beneficiario.vigencia', 'CESANTE');
            })
            ->whereNotIn('persona.id_tipo_asegurado', ['0', '8', '9', '10', '11'])
            ->select('persona.*', 'tipo_asegurado.tipo_asegurado as tipoAsegurado');
    }

    public function relationSearch(): array
    {
        return [
            'bajaMedicaPersona' => ['nombres', 'p_apellido', 's_apellido', 'matricula_seguro'],
            'tipoAseguradoPersona' => ['tipo_asegurado'],

        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('ci')
            ->add('complemento')
            ->add('carnet_formatted', function ($carnet) {
                if ($carnet->complemento == null) {
                    return $carnet->ci;
                } else {
                    return $carnet->ci . ' - ' . $carnet->complemento;
                }
            })
            //->add('nombreCompleto', fn (Persona $model) => $model->p_apellido . ' ' . $model->s_apellido . ' ' . $model->nombres)
            ->add('persona_nombre_completo', function ($nombrePersona) {
                if ($nombrePersona->p_apellido == null) {
                    return $nombrePersona->s_apellido . ' ' . $nombrePersona->nombres;
                } elseif ($nombrePersona->s_apellido == null) {
                    return $nombrePersona->p_apellido . ' ' . $nombrePersona->nombres;
                } else {
                    return $nombrePersona->p_apellido . ' ' . $nombrePersona->s_apellido . ' ' . $nombrePersona->nombres;
                }
            })
            ->add('tipoAsegurado')
            ->add('nombres')
            ->add('p_apellido')
            ->add('s_apellido')
            ->add('sexo')
            ->add('id_estado_civil')
            ->add('id_nacimiento_municipio')
            ->add('id_dept_exp')
            ->add('fecha_nacimiento_formatted', fn(Persona $model) => Carbon::parse($model->fecha_nacimiento)->format('d/m/Y'))
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
            Column::make('#', 'id')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3; color:white;')
                ->bodyAttribute('text-center')
                ->index(),

            Column::make('NOMBRE COMPLETO', 'persona_nombre_completo', 'p_apellido')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3; color:white;')
                ->sortable()
                ->searchableRaw("
                TRIM(CONCAT(
                    COALESCE(persona.p_apellido, ''), 
                    ' ', 
                    COALESCE(persona.s_apellido, ''), 
                    ' ', 
                    COALESCE(persona.nombres, '')
                ))
            "),

            Column::make('Nombres', 'nombres')
                ->headerAttribute('text-center bg-teal', 'font-size:13px')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable()
                ->hidden(isHidden: true, isForceHidden: true),

            Column::make('Paterno', 'p_apellido')
                ->headerAttribute('text-center bg-teal', 'font-size:13px')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable()
                ->hidden(isHidden: true, isForceHidden: true),

            Column::make('Materno', 's_apellido')
                ->headerAttribute('text-center bg-teal', 'font-size:13px')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable()
                ->hidden(isHidden: true, isForceHidden: true),

            Column::make('CARNET', 'carnet_formatted', 'ci')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),



            Column::make('MATRICULA ', 'matricula_seguro')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('TIPO ASEGEGURADO', 'tipoAsegurado')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3; color:white;')

                ->sortable()
                ->searchable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3; color:white;')
                ->bodyAttribute('text-center')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('fecha_nacimiento'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions(Persona $row): array
    {
        return [
            Button::add('view')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus-fill" viewBox="0 0 16 16">
                <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5"/>
                </svg>')
                ->class('btn btn-infoDos')
                ->route('vigencia.BajasMedicas.lista', ['id' => $row->id])
                ->tooltip('AGREGAR BAJA MÉDICA'),
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
