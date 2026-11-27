<?php

namespace App\Livewire\Aportes\BajasMedicas;

use App\Models\Aportes\AporteInstitucional;
use Illuminate\Support\Carbon;
use App\Models\Aportes\Comprobante;
use App\Models\Administracion\Persona;
use App\Models\Aportes\PlanillaBajaMedica;
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
use Livewire\Attributes\On;


final class AportantesTable extends PowerGridComponent
{
    use WithExport;
    public $compId;
    public $comprobante;
    public $apInstiId;
    public $abrirModalBajaMedica;
    public string $tableName = 'BajasMedicasRegistro';
    public string $sortField = 'aporte_i.id';
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
        return AporteInstitucional::query()
            ->join('administracion.persona as p', function ($p) {
                $p->on('p.id', '=', 'aportes.aporte_i.id_persona');
            })
            ->join('aportes.institucion as i', function ($i) {
                $i->on('i.id', '=', 'aportes.aporte_i.id_institucion');
            })
            ->join('aportes.comprobante as c', function ($join) {
                $join->on('c.id_institucion', '=', 'i.id')
                    ->on('c.id_mes', '=', 'aportes.aporte_i.id_mes')
                    ->on('c.gestion', '=', 'aportes.aporte_i.gestion')
                    ->on('c.reintegro', '=', 'aportes.aporte_i.reintegro_incr_salarial')
                    ->on('c.beneficio_social', '=', 'aportes.aporte_i.beneficio_social')
                    ->on('c.planilla_adicional', '=', 'aportes.aporte_i.planilla_adicional')
                    ->where('c.id', '=', $this->compId);
            })
            ->join('aportes.mes as m', function ($join) {
                $join->on('m.id', '=', 'c.id_mes');
            })
            ->leftjoin('aportes.planilla_baja_medica as pla', function ($pla) {
                $pla->on('pla.id_aporte_i', '=', 'aportes.aporte_i.id');
            })
            ->select(
                'p.nombres',
                'p.p_apellido as pap_persona',
                'p.ci',
                'aportes.aporte_i.*',
                'i.nombre as institucion_nombre',
                'i.id as id_institucion',
                'c.id as id_comprobante',
                'm.mes',
                'p.estado_asuss as estado_asuss',
                'pla.monto_baja_medica',
                'pla.tipo',
                'pla.fecha_inicio',
                'pla.fecha_fin',
                'pla.dias_baja',
            );
    }

    public function relationSearch(): array
    {
        return [
            'personaAporteInstitucional' => ['nombres', 'p_apellido', 's_apellido', 'ci'],
            'mesAporteInstitucional' => ['mes'],
            'institucionAporteInstitucional' => ['nombre']
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('id_persona')
            ->add('persona_nombre_completo', fn(AporteInstitucional $model) => $model->personaAporteInstitucional->p_apellido . ' ' . $model->personaAporteInstitucional->s_apellido . ' ' . $model->personaAporteInstitucional->nombres)
            ->add('tipo_trabajador')
            ->add('ci')
            ->add('dias_trabajados')
            ->add('total_ganado')
            ->add('total_ganado_dia')
            ->add('total_aporte')
            ->add('gestion')
            ->add('reintegro_normal')
            ->add('id_mes')
            ->add('reintegro_incr_salarial')
            ->add('beneficio_social')
            ->add('afiliado')
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at')
            ->add('monto_baja_medica')
            ->add('tipo')
            ->add('fecha_inicio')
            ->add('fecha_fin')

            //->add('fecha_inicio', function($model) {
            //    return \Carbon\Carbon::parse($model->fecha_inicio)->format('d/m/Y');
            //})
            //->add('fecha_fin', function($model) {
            //    return \Carbon\Carbon::parse($model->fecha_fin)->format('d/m/Y');
            //})
            //->add('fecha_inicio_formatted', fn($model) => Carbon::parse($model->fecha_inicio)->format('d/m/Y'))
            //->add('fecha_fin_formatted', fn($model) => Carbon::parse($model->fecha_fin)->format('d/m/Y'))
            ->add('dias_baja');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')->index()
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center'),

            Column::make('NOMBRE COMPLETO', 'persona_nombre_completo', 'pap_persona')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('CARNET', 'ci')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('TIPO', 'tipo_trabajador')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('ITEM', 'item')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('GANADO', 'total_ganado')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('APORTE', 'total_aporte')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('BAJA MÉDICA', 'monto_baja_medica')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('TIPO BAJA MÉDICA', 'tipo')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('FECHA INICIO', 'fecha_inicio')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
            Column::make('FECHA FIN',  'fecha_fin')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
            Column::make('DÍAS BAJA', 'dias_baja')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center'),
        ];
    }

    public function filters(): array
    {
        return [];
    }

    #[On('bajaMedica')]
    public function bajaMedica($rowId, $abrirModalBajaMedica): void
    {
        $this->modalBajaMedica = $abrirModalBajaMedica;
        $this->apInstiId = $rowId;
        $this->dispatch('bajas', ['modalBajaMedica' => $this->modalBajaMedica, 'apInstiId' => $this->apInstiId])
            ->to(BajasMedicasRegister::class);
    }

    public function actions(AporteInstitucional $row): array
    {
        $buttons = [];



        $buttons[] = Button::add('bajaMedica')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-journal-bookmark-fill" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M6 1h6v7a.5.5 0 0 1-.757.429L9 7.083 6.757 8.43A.5.5 0 0 1 6 8z"/>
            <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2"/>
            <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"/>
            </svg>')
            ->class(' btn btn-green')
            ->tooltip('REGISTRAR BAJA')
            ->dispatchTo('aportes.bajas-medicas.bajas-register', 'agregarBajaPlanilla', ['data' => $row->id]);
        // ->dispatch('bajaMedica', ['rowId' => $row->id, 'abrirModalBajaMedica' => true]);

        $existeBaja = PlanillaBajaMedica::where('id_aporte_i', $row->id)->first();

        if ($existeBaja) {
            $buttons[] = Button::add('eliminarBajaMedica')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                    <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                    </svg>')
                ->class(' btn btn-red')
                ->tooltip('ELIMINAR BAJA')
                ->dispatch('eliminarBajaMedica', ['data' => $row->id]);
        }

        return $buttons;
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
