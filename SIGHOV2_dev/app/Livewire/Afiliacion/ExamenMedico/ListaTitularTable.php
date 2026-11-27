<?php

namespace App\Livewire\Afiliacion\ExamenMedico;

use App\Models\Afiliacion\Titular;
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

final class ListaTitularTable extends PowerGridComponent
{
    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

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
        return Titular::where('estado', true)->orderBy('id', 'desc');
      
    }

    public function relationSearch(): array
    {
        return [
            'titularPersona'=>['nombres', 'p_apellido', 's_apellido'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('estado_requisitos')
            ->add('estado_vigencia')
            ->add('grupo_sanguineo')
            ->add('alergias')
            ->add('telefono_referencia')
            ->add('nombre_referencia')
            ->add('foto_nombre')
            ->add('observacion')
            ->add('fecha_afiliacion_formatted', fn (Titular $model) => Carbon::parse($model->fecha_afiliacion)->format('d/m/Y'))
            ->add('id_persona')
            ->add('persona_nombre_completo', fn(Titular $model) => $model->titularPersona->nombres . ' ' . $model->titularPersona->p_apellido . ' ' . $model->titularPersona->s_apellido)
            
            ->add('telefono')
            ->add('fecha_reafiliacion_formatted', fn (Titular $model) => Carbon::parse($model->fecha_reafiliacion)->format('d/m/Y'))
            ->add('correo')
            ->add('estado')
            ->add('tipo_titular')
            ->add('id_residencia')
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Nro', 'id')
                ->headerAttribute('text-center bg-info', 'font-size:15px')
                ->sortable()
                ->searchable(),

            Column::make('TITULAR: Nombre completo', 'persona_nombre_completo')
                ->headerAttribute('text-center bg-info', 'font-size:15px')
                ->sortable()
                ->searchable(),

            Column::make('Telefono', 'telefono')
                ->headerAttribute('text-center bg-info', 'font-size:15px')
                ->sortable()
                ->searchable(),

            Column::make('Estado', 'estado')
                ->headerAttribute('text-center bg-info', 'font-size:15px')
                ->sortable()
                ->searchable(),

            Column::action('Action')
                ->headerAttribute('text-center bg-info', 'font-size:15px')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('fecha_afiliacion'),
            Filter::datepicker('fecha_reafiliacion'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(Titular $row): array
    {
        return [
            Button::add('examenMedico')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus-fill" viewBox="0 0 16 16">
                <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5"/>
                </svg>')
                ->class('btn btn-orange')
                ->route('afiliacion.ExamenMedico.create-examenMedico',  ['id' => $row->id])
                ->tooltip('BENEFICIARIO'),
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
