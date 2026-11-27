<?php

namespace App\Livewire\Aportes\Externos\Aporte;

use App\Http\Controllers\Controller;
use App\Models\Aportes\AporteInstitucional;
use Illuminate\Support\Carbon;
use App\Models\Aportes\Comprobante;
use App\Models\Administracion\Persona;
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
use Livewire\Attributes\On;
use Illuminate\Support\Collection;

final class AportantesInstiTableExt extends PowerGridComponent
{
    use WithExport;
    public $compId;
    public $comprobante;
    public string $sortField = 'aporte_i.id';
    public string $sortDirection = 'asc';

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
        return AporteInstitucional::query()
        ->join('administracion.persona as p', 'p.id', '=', 'aportes.aporte_i.id_persona')
        ->join('aportes.institucion as i', 'i.id', '=', 'aportes.aporte_i.id_institucion')
        ->join('aportes.comprobante as c', 'c.id', '=', 'aportes.aporte_i.id_comp') // Unimos directamente por id_comprobante
        ->join('aportes.mes as m', 'm.id', '=', 'c.id_mes')
        ->where('aportes.aporte_i.id_comp', $this->compId) // Filtramos directamente por id_comprobante
        ->select(
            'p.nombres',
            'p.p_apellido as pap_persona',
            'p.s_apellido',
            'p.ci',
            'p.matricula_seguro',
            'aportes.aporte_i.*', // Selecciona todas las columnas necesarias de aporte_i
            'i.nombre as institucion_nombre',
            'i.id as id_institucion',
            'c.id as id_comp',
            'm.mes',
            'p.estado_asuss as estado_asuss'
        )
        ->orderBy('aportes.aporte_i.id', 'asc');

        // PowerGrid manejará la paginación y la búsqueda sobre este Builder
    }

    public function relationSearch(): array
    {
        return [
            'personaAporteInstitucional' => ['nombres', 'p_apellido', 's_apellido', 'ci'],
            'mesAporteInstitucional' => ['mes'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('id_persona')
            ->add('persona_nombre_completo', fn(AporteInstitucional $model) => $model->personaAporteInstitucional->p_apellido . ' ' . $model->personaAporteInstitucional->s_apellido . ' ' . $model->personaAporteInstitucional->nombres)
            ->add('id_institucion')
            ->add('item')
            ->add('cargo')
            ->add('area')
            ->add('tipo_trabajador')
            ->add('carga_horaria')
            ->add('dias_trabajados')
            ->add('total_ganado', function ($dish) {
                return number_format($dish->total_ganado, 2, ',', '.');
            })
            ->add('total_ganado_dia')
            ->add('total_aporte', fn($dish) => number_format($dish->total_aporte, 2, ',', '.'))
            ->add('gestion')
            ->add('reintegro_normal')
            ->add('id_mes')
            ->add('estado_asuss', function ($dish) {
                if ($dish->estado_asuss) {
                    return '<span style="font-style: italic; color: #1e8449;">Validado</span>';
                } else {
                    return '<span style="font-style: italic; color: #515a5a;">Observado</span>';
                }
            })
            ->add('reintegro_incr_salarial')
            ->add('beneficio_social')
            ->add('afiliado')
            ->add('estado_compensacion_formatted', function ($compensacion) {
                if ($compensacion->estado_compensacion) {
                    return "<span style='color: #c0392b'>SI</span>";
                } else {
                    return "<span style='color: #1e8449'>NO</span>";
                }
            })
            ->add('compensacion_salario_minimo')
            ->add('fecha_ingreso', fn($dish) => Carbon::parse($dish->fecha_ingreso)->format('d/m/Y'))
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('fecha_novedad_retiro_suspension', function ($row) {
                return $row->fecha_novedad_retiro_suspension
                    ? \Carbon\Carbon::parse($row->fecha_novedad_retiro_suspension)->format('d/m/Y')
                    : '';
            }) 
            ->add('motivo_novedad_retiro_suspension', function ($row) {
                switch ($row->motivo_novedad_retiro_suspension) {
                    case 'R':
                        return 'RETIRO';
                    case 'C':
                        return 'COMISIÓN';
                    case 'S':
                        return 'SUSPENSIÓN';
                    case 'F':
                        return 'FALLECIMIENTO';
                    case 'O':
                        return 'OTRO';
                    default:
                        return '';
                }
            }) 
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #245b7f  ; color:white;')
                ->bodyAttribute('text-center')
                ->index(),

            Column::make('NOMBRE COMPLETO', 'persona_nombre_completo', 'p_apellido')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #245b7f  ; color:white;')
                ->sortable()
                ->searchableRaw(
                "CONCAT(p.p_apellido, ' ', p.s_apellido, ' ', p.nombres)"
            ),
                
            Column::make('CARNET', 'ci')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#245b7f ; color:white;')
                ->sortable()
                ->searchableRaw('p.ci'),

            Column::make('ITEM', 'item')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #245b7f  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('CARGO', 'cargo')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #245b7f  ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),

            Column::make('ÁREA', 'area')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #245b7f  ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),

            Column::make('TOTAL GANADO', 'total_ganado')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #245b7f  ; color:white;')
                ->bodyAttribute('text-right')
                ->sortable()
                ->searchable(),

            Column::make('TOTAL APORTE', 'total_aporte')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #245b7f  ; color:white;')
                ->bodyAttribute('text-right')
                ->sortable()
                ->searchable(),

            Column::make('TIPO TRABAJADOR', 'tipo_trabajador')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #245b7f  ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),

            Column::make('DIAS TRABAJADOS', 'dias_trabajados')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #245b7f  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('FECHA INGRESO', 'fecha_ingreso')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #245b7f  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
            Column::make('COMPENSACIÓN', 'estado_compensacion_formatted', 'estado_compensacion')
                ->headerAttribute('text-center ', 'font-size:14px; background-color:#245b7f ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
            Column::make('COMP. SALARIO MÍNIMO', 'compensacion_salario_minimo')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #245b7f  ; color:white;')
                ->bodyAttribute('text-right')
                ->sortable()
                ->searchable(),
            
            Column::make('FECHA NOVEDAD', 'fecha_novedad_retiro_suspension')
                ->headerAttribute('text-center ', 'font-size:14px; background-color:#245b7f  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('MOTIVO NOVEDAD', 'motivo_novedad_retiro_suspension')
                ->headerAttribute('text-center ', 'font-size:14px; background-color:#245b7f  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('ASUSS (ESTADO)', 'estado_asuss')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #245b7f  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #245b7f  ; color:white;')
                ->bodyAttribute('text-center')
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

    public function actions(AporteInstitucional $row): array
    {
        return [
            Button::add('edit')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                </svg>')
                ->id()
                ->class('btn btn-blue')
        ];
    }

    #[On('aportantesGrupoDosExt')]
    public function mostrarAportantes($data)
    {
        $this->compId = $data['comprobanteId'];
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
