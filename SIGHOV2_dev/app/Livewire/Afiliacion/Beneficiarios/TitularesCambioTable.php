<?php

namespace App\Livewire\Afiliacion\Beneficiarios;

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

final class TitularesCambioTable extends PowerGridComponent
{
    use WithExport;
    public string $sortField = 'titular.id';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        //$this->showCheckBox();

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
        return Titular::query()
            ->join('administracion.persona as p', function ($persona) {
                $persona->on('p.id', '=', 'afiliacion.titular.id_persona');
            })

            ->where('titular.estado', true)
            ->where('titular.estado_requisitos', true)
            ->where('titular.estado_vigencia', '=', 'VIGENTE')
            ->where(function ($query) {
                $query->where('tipo_rentista', '=', 'TITULAR-RENTISTA')
                    ->orWhereNull('tipo_rentista');
            })
            ->select(
                'afiliacion.titular.*',
                'p.nombres as nombres',
                'p.p_apellido as p_apellido',
                'p.s_apellido as s_apellido',
                'p.ci as carnet',
                'p.matricula_seguro as matricula',

            );
    }

    public function relationSearch(): array
    {
        return [
            'titularPersona' => ['nombres', 'p_apellido', 's_apellido', 'matricula_seguro', 'ci'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('id')
            ->add('estado_requisitos')
            ->add('estado_vigencia')
            ->add('telefono_referencia')
            ->add('nombre_referencia')
            ->add('foto_nombre')
            ->add('observacion')
            ->add('fecha_afiliacion_formatted', fn(Titular $model) => Carbon::parse($model->fecha_afiliacion)->format('d/m/Y'))
            ->add('id_persona')
            ->add('persona_nombre_completo', function ($nombreCompleto) {
                if ($nombreCompleto->p_apellido == null) {
                    return $nombreCompleto->s_apellido . ' ' . $nombreCompleto->nombres;
                } elseif ($nombreCompleto->s_apellido == null) {
                    return $nombreCompleto->p_apellido . ' ' . $nombreCompleto->nombres;
                } else {
                    return $nombreCompleto->p_apellido . ' ' . $nombreCompleto->s_apellido . ' ' . $nombreCompleto->nombres;
                }
            })
            ->add('telefono')
            ->add('fecha_reafiliacion_formatted', fn(Titular $model) => Carbon::parse($model->fecha_reafiliacion)->format('d/m/Y'))
            ->add('correo')
            ->add('estado')
            //  ->add('tipo_titular')
            ->add('tipo_titular', function ($tipo_titular) {
                if ($tipo_titular->tipo_titular) {
                    return 'INSTITUCIONAL';
                } else {
                    return 'VOLUNTARIO';
                }
            })
            ->add('id_residencia')
            ->add('tipo_rentista')
            ->add('fecha_baja_formatted', fn(Titular $model) => Carbon::parse($model->fecha_baja)->format('d/m/Y'))
            ->add('permanente')
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

            Column::make('TITULAR', 'persona_nombre_completo', 'p_apellido')
                ->headerAttribute('text-center ',  'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute()
                ->sortable()
                ->searchableRaw("
                TRIM(CONCAT(
                    COALESCE(p.p_apellido, ''), 
                    ' ', 
                    COALESCE(p.s_apellido, ''), 
                    ' ', 
                    COALESCE(p.nombres, '')
                ))
            "),

            Column::make('CARNET', 'carnet', 'carnet')
                ->headerAttribute('text-center ',  'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute()
                ->sortable()
                ->searchable(),

            Column::make('MATRICULA', 'matricula', 'matricula')
                ->headerAttribute('text-center ',  'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute()
                ->sortable()
                ->searchable(),

            Column::make('TIPO DE TITULAR', 'tipo_titular')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute()
                ->sortable()
                ->searchable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center '),
        ];
    }

    public function filters(): array
    {
        return [
        //    Filter::datepicker('fecha_afiliacion'),
        //    Filter::datepicker('fecha_reafiliacion'),
        //    Filter::datepicker('fecha_baja'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions(Titular $row): array
    {
        return [
            Button::add('view')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus-fill" viewBox="0 0 16 16">
                <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5"/>
                </svg>')
                ->class('btn btn-blue')
                ->tooltip('SELECCIONAR AFILIADO')
                ->dispatchTo('afiliacion.beneficiarios.beneficiarios-reafiliacion', 'cambiarTitular', ['data' => $row->id])

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
