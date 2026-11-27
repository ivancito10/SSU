<?php

namespace App\Livewire\Aportes\AportesInstitucionales;

use App\Models\Aportes\AporteInstitucionalTemp;
use App\Models\Aportes\Comprobante;
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
use Livewire\Attributes\On;
use Illuminate\Support\Collection;

final class AportantesInstiTempTable extends PowerGridComponent
{
    use WithExport;
    public $compId;
    public $comprobante;
    public $count = 1;
    public string $sortField = 'aporte_i_temp.id';
    public string $sortDirection = 'asc';

    public string $search = '';

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
        return AporteInstitucionalTemp::query()
        ->join('administracion.persona as p', 'p.id', '=', 'aportes.aporte_i_temp.id_persona')
        ->join('aportes.institucion as i', 'i.id', '=', 'aportes.aporte_i_temp.id_institucion')
        ->join('aportes.comprobante as c', 'c.id', '=', 'aportes.aporte_i_temp.id_comp') // Unimos directamente por id_comprobante
        ->join('aportes.mes as m', 'm.id', '=', 'c.id_mes')
        ->where('aportes.aporte_i_temp.id_comp', $this->compId) // Filtramos directamente por id_comprobante
        ->select(
            'p.nombres',
            'p.p_apellido as pap_persona',
            'p.s_apellido',
            'p.ci',
            'p.matricula_seguro',
            'aportes.aporte_i_temp.*', // Selecciona todas las columnas necesarias de aporte_i_temp
            'i.nombre as institucion_nombre',
            'i.id as id_institucion',
            'c.id as id_comp',
            'm.mes',
            'p.estado_asuss as estado_asuss',
        )
        ->orderBy('aportes.aporte_i_temp.id', 'asc');

        // PowerGrid manejará la paginación y la búsqueda sobre este Builder
    }


    public function relationSearch(): array
    {
        return [
            'personaAporteInstitucional' => ['nombres', 'p_apellido', 's_apellido'],
            'mesAporteInstitucional' => ['mes'],

        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('id_persona')
            ->add('persona_nombre_completo', fn(AporteInstitucionalTemp $model) => $model->personaAporteInstitucional->p_apellido . ' ' . $model->personaAporteInstitucional->s_apellido . ' ' . $model->personaAporteInstitucional->nombres)
            //->add('ci',fn(AporteInstitucionalTemp $model) => $model->personaAporteInstitucional->ci)
            ->add('id_institucion')
            ->add('item')
            ->add('cargo')
            ->add('area')
            ->add('tipo_trabajador')
            ->add('carga_horaria')
            ->add('dias_trabajados')
            ->add('total_ganado')
            ->add('total_ganado_formatted', function ($dish) {
                return number_format($dish->total_ganado, 2, ',', '.');
            })
            ->add('total_ganado_dia')
            ->add('total_aporte')
            ->add('total_aporte_formatted', function ($dish) {
                return number_format($dish->total_aporte, 2, ',', '.');
            })
            ->add('gestion')
            ->add('reintegro_normal')
            ->add('id_mes')
            ->add('reintegro_incr_salarial')
            ->add('beneficio_social')
            ->add('afiliado')
            ->add('mes_insti', fn(AporteInstitucionalTemp $model) => $model->mesAporteInstitucional->mes)
            ->add('estado_asuss')
            ->add('estado_asuss_formatted', function ($dish) {
                if ($dish->estado_asuss) {
                    return '<span style="font-style: italic; color: #1e8449;">Validado</span>';
                } else {
                    return '<span style="font-style: italic; color: #515a5a;">Observado</span>';
                }
            })
            ->add('estado_compensacion')
            ->add('estado_compensacion_formatted', function ($compensacion) {
                if ($compensacion->estado_compensacion) {
                    return "<span style='color: #c0392b'>SI</span>";
                } else {
                    return "<span style='color: #1e8449'>NO</span>";
                }
            })
            ->add('compensacion_salario_minimo')
            ->add('compensacion_salario_minimo_formatted', function ($compensacionMonto) {
                return number_format($compensacionMonto->compensacion_salario_minimo, 2, ',', '.');
            })
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
                    case 'I':
                        return 'INGRESO';
                    default:
                        return '';
                }
            }) 
            ->add('created_at');
    }

    public function columns(): array
    {
        return [

            //Column::make('NRO', $this->count++)
            //      ->searchable(false)
            //      ->sortable(false),
            Column::make('#', 'id')
                ->headerAttribute('text-center ', 'font-size:14px; background-color:#245b7f  ; color:white;')
                ->bodyAttribute('text-center')
                ->index(),

            Column::make('NOMBRE COMPLETO', 'persona_nombre_completo')
                ->headerAttribute('text-center ', 'font-size:14px; background-color:#245b7f  ; color:white;')
                ->sortable()
                ->searchableRaw(
                "CONCAT(p.p_apellido, ' ', p.s_apellido, ' ', p.nombres)"
            ),
            Column::make('CARNET', 'ci')
                ->headerAttribute('text-center ', 'font-size:14px; background-color:#245b7f  ; color:white;')
                ->sortable()
                ->searchableRaw('p.ci'),


            Column::make('ITEM', 'item')
                ->headerAttribute('text-center ', 'font-size:14px; background-color:#245b7f  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('CARGO', 'cargo')
                ->headerAttribute('text-center ', 'font-size:14px; background-color:#245b7f  ; color:white;')

                ->sortable()
                ->searchable(),

            Column::make('ÁREA', 'area')
                ->headerAttribute('text-center ', 'font-size:14px; background-color:#245b7f  ; color:white;')

                ->sortable()
                ->searchable(),

            Column::make('TOTAL GANADO', 'total_ganado_formatted', 'total_ganado')
                ->headerAttribute('text-center ', 'font-size:14px; background-color:#245b7f  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),


            Column::make('TOTAL APORTE', 'total_aporte_formatted', 'total_aporte')
                ->headerAttribute('text-center ', 'font-size:14px; background-color:#245b7f  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('TIPO TRABAJADOR', 'tipo_trabajador')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #245b7f   ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),
            Column::make('DIAS TRABAJADOS', 'dias_trabajados')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #245b7f   ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
            Column::make('FECHA INGRESO', 'fecha_ingreso')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #245b7f   ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
            Column::make('COMPENSACIÓN', 'estado_compensacion_formatted', 'estado_compensacion')
                ->headerAttribute('text-center ', 'font-size:14px; background-color:#245b7f  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('COMP. SALARIO MÍNIMO', 'compensacion_salario_minimo_formatted', 'compensacion_salario_minimo')
                ->headerAttribute('text-center ', 'font-size:14px; background-color:#245b7f  ; color:white;')
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
            Column::make(' ASUSS (ESTADO)', 'estado_asuss_formatted', 'estado_asuss')
                ->headerAttribute('text-center ', 'font-size:14px; background-color:#245b7f  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
        ];
    }

    public function filters(): array
    {
        return [
            // Filter::inputText('persona_nombre_completo', 'persona_nombre_completo')
            //     ->placeholder('Buscar nombre...'),
            // Filter::inputText('ci', 'ci')
            //     ->placeholder('Buscar carnet...'),
        ];
    }


    #[On('aportantesGrupoDosTemp')]
    public function mostrarAportantesTemp($data)
    {
        $this->compId = $data['comprobanteId'];
    }
}
