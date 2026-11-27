<?php

namespace App\Livewire\Aportes\AportesInstitucionales;

use App\Models\Aportes\AporteInstitucional;
use App\Models\Aportes\Comprobante;
use App\Models\Administracion\Persona;
use Illuminate\Support\Carbon;
use App\Models\Dish;
use Illuminate\Support\Number;
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
use App\Livewire\Aportes\BajasMedicas\BajasMedicasRegister;
use Illuminate\Support\Collection; 
use Illuminate\Support\Facades\DB;

use Livewire\Attributes\On;

final class AportantesInstiTable extends PowerGridComponent
{
    use WithExport;
    public $compId;
    public $comprobante;
    public $count = 0;
    public $apInstiId;
    public $modalBajaMedica = false;
    public string $tableName = 'AportesInstitucionalesTabla';
    public string $sortField = 'aporte_i.id';
    public string $sortDirection = 'asc';

    public string $search = '';

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
        return AporteInstitucional::query()
            ->join('administracion.persona as p', 'p.id', '=', 'aportes.aporte_i.id_persona')
            ->join('aportes.institucion as i', 'i.id', '=', 'aportes.aporte_i.id_institucion')
            ->join('aportes.comprobante as c', 'c.id', '=', 'aportes.aporte_i.id_comp')
            ->join('aportes.mes as m', 'm.id', '=', 'c.id_mes')
            ->where('aportes.aporte_i.id_comp', $this->compId)
            ->select(
                'p.nombres',
                'p.p_apellido as pap_persona',
                'p.s_apellido',
                'p.ci',
                'p.matricula_seguro',
                'aportes.aporte_i.*',
                'i.nombre as institucion_nombre',
                'i.id as id_institucion',
                'c.id as id_comp',
                'm.mes',
                'p.estado_asuss as estado_asuss',
                DB::raw("CONCAT(p.p_apellido, ' ', p.s_apellido, ' ', p.nombres) as persona_nombre_completo")
            )
            ->orderBy('aportes.aporte_i.id', 'asc');
    }


    public function relationSearch(): array
    {
        return [
            'personaAporteInstitucional' => ['p_apellido', 's_apellido', 'nombres'],
            'mesAporteInstitucional' => ['mes'],
        ];
    }


    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')

            ->add('id_persona')
            ->add('persona_nombre_completo')
            ->add('id_institucion')
            //->add('ci',fn(AporteInstitucional $model) => $model->personaAporteInstitucional->ci)
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
            ->add('mes_insti', fn(AporteInstitucional $model) => $model->mesAporteInstitucional->mes)
            ->add('reintegro_incr_salarial')
            ->add('beneficio_social')
            ->add('estado_asuss')
            ->add('estado_asuss_formatted', function ($dish) {
                if ($dish->estado_asuss) {
                    return '<span style="font-style: italic; color: #1e8449;">Validado</span>';
                } else {
                    return '<span style="font-style: italic; color: #515a5a;">Observado</span>';
                }
            })
            ->add('afiliado')
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
            //Column::make('NRO', 'count')
            //->field('id', 'dishes.id')
            //    ->searchable(false)
            //    ->sortable(false),
            Column::make('#', 'id')
                ->headerAttribute('text-center ', 'font-size:14px; background-color: #245b7f    ; color:white;')
                ->bodyAttribute('text-center')
                ->index(),

            Column::make('NOMBRE COMPLETO', 'persona_nombre_completo')
                ->headerAttribute('text-center ', 'font-size:14px; background-color: #245b7f    ; color:white;')
                ->sortable()
                ->searchableRaw(
                "CONCAT(p.p_apellido, ' ', p.s_apellido, ' ', p.nombres)"
            ),

            Column::make('CARNET', 'ci')
                ->headerAttribute('text-center ', 'font-size:14px; background-color: #245b7f  ; color:white;')
                ->sortable()
                ->searchableRaw('p.ci'),

            Column::make('ITEM', 'item')
                ->headerAttribute('text-center ', 'font-size:14px; background-color: #245b7f    ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('CARGO', 'cargo')
                ->headerAttribute('text-center ', 'font-size:14px; background-color: #245b7f    ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('ÁREA', 'area')
                ->headerAttribute('text-center ', 'font-size:14px; background-color: #245b7f    ; color:white;')

                ->sortable()
                ->searchable(),
            Column::make('TOTAL GANADO', 'total_ganado_formatted', 'total_ganado')
                ->headerAttribute('text-center ', 'font-size:14px; background-color: #245b7f    ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('TOTAL APORTE', 'total_aporte_formatted', 'total_aporte')
                ->headerAttribute('text-center ', 'font-size:14px; background-color: #245b7f    ; color:white;')
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
                ->headerAttribute('text-center ', 'font-size:14px; background-color: #245b7f    ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('COMP. SALARIO MÍNIMO', 'compensacion_salario_minimo_formatted', 'compensacion_salario_minimo')
                ->headerAttribute('text-center ', 'font-size:14px; background-color: #245b7f    ; color:white;')
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
                ->headerAttribute('text-center ', 'font-size:14px; background-color: #245b7f    ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            // Column::action('Action')
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

    #[On('bajaMedica')]
    public function bajaMedica($rowId, $abrirBajaMedica): void
    {
        //dd('este es el click');
        $this->modalBajaMedica = $abrirBajaMedica;
        $this->apInstiId = $rowId;
        $this->dispatch('bajas', ['modalBajaMedica' => $this->modalBajaMedica, 'apInstiId' => $this->apInstiId])
            ->to(BajasMedicasRegister::class);
    }



    // public function actions(AporteInstitucional $row): array
    // {
    //     return [
    //         Button::add('bajaMedica')
    //             ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-medical" viewBox="0 0 16 16">
    //                     <path d="M7.5 5.5a.5.5 0 0 0-1 0v.634l-.549-.317a.5.5 0 1 0-.5.866L6 7l-.549.317a.5.5 0 1 0 .5.866l.549-.317V8.5a.5.5 0 1 0 1 0v-.634l.549.317a.5.5 0 1 0 .5-.866L8 7l.549-.317a.5.5 0 1 0-.5-.866l-.549.317zm-2 4.5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zm0 2a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1z"/>
    //                     <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z"/>
    //                     </svg>')
    //             ->id()
    //             ->class('btn btn-orange')
    //
    //             ->dispatch('bajaMedica', ['rowId' => $row->id,'abrirBajaMedica'=>true ]),
    //     ];
    // }



    #[On('aportantesGrupoDos')]
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
