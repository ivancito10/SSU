<?php

namespace App\Livewire\Afiliacion\Titulares;

use App\Models\Aportes\AporteInstitucional;
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

final class TitularConAporteInstitucional extends PowerGridComponent
{
    use WithExport;
    public $idPersona;
    public $id;
    public string $sortField = "aporte_i.id";
    public string $sortDirection = "desc";



    public function setUp(): array
    {
        return [
            /*Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),*/
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
        //dd('fsd',$this->idPersona);
        return AporteInstitucional::query()
            ->join('aportes.institucion as institucion', function ($institucion) {
                $institucion->on('aporte_i.id_institucion', '=', 'institucion.id');
            })
            ->join('aportes.clase_aportante as clase',function($clase){
                $clase->on('institucion.id_clase_aportante','=','clase.id' );
            } )
            ->join('aportes.mes as mes', function ($mes) {
                $mes->on('aporte_i.id_mes', '=', 'mes.id');
            })
         
            ->where('aportes.aporte_i.id_persona', $this->idPersona)
            ->select(
                'aportes.aporte_i.item',
                'clase.tipo',
                'aportes.aporte_i.cargo',
                'aportes.aporte_i.total_ganado',
                'aportes.aporte_i.area',
                'aportes.aporte_i.gestion',
                'institucion.nombre as nombreInstitucion',
                'mes.mes as mes',
            );
    }

    public function relationSearch(): array
    {
        return [
            'institucionAporteInstitucional' => 'nombre',
            'mesAporteInstitucional' => 'mes',
            'institucionAporteInstitucional.claseAportanteInstitucion' => 'tipo',
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('id_persona')
            ->add('id_institucion')
            ->add('item')
            ->add('cargo')
            ->add('area')
            ->add('tipo_trabajador')
            ->add('carga_horaria')
            ->add('dias_trabajados')
            ->add('total_ganado')
            ->add('total_ganado_dia')
            ->add('total_aporte')
            ->add('gestion')
            ->add('reintegro_normal')
            ->add('id_mes')
            ->add('nombre_mes')
            ->add('reintegro_incr_salarial')
            ->add('beneficio_social')
            ->add('planilla_adicional')
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

            Column::make('INSTITUCIÓN', 'nombreInstitucion')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
            Column::make('TIPO', 'tipo')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('ITEM', 'item')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('CARGO', 'cargo')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('ÁREA', 'area')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('TOTAL GANADO', 'total_ganado')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('MES', 'mes')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('GESTIÓN', 'gestion')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

        ];
    }
}
