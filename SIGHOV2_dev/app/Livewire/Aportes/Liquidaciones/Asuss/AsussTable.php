<?php

namespace App\Livewire\Aportes\Liquidaciones\Asuss;

use App\Models\Aportes\LiquidacionMinisterioAsuss;
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

final class AsussTable extends PowerGridComponent
{
    use WithExport;
 
    public string $tableName = 'AsussTable'; 
    public string $sortField = 'liquidacion_ministerio_asuss.gestion'; //ESTO ES PARA ORNDENAR
    public string $sortDirection = 'desc'; // DE MANERA DESCENDENTE, YA NO USEN ORDER BY EN LA CONSULTA
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
        return LiquidacionMinisterioAsuss::query()
            ->join('aportes.mes', function ($mes){
                $mes->on('mes.id', '=', 'liquidacion_ministerio_asuss.id_mes');
            })
            ->select('liquidacion_ministerio_asuss.*', 'mes.mes as nombre_mes')
            ->where('liquidacion_ministerio_asuss.estado',true);
    }

    public function relationSearch(): array
    {
        return [
            'mesLiquidacion' => ['mes'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('nro_instituciones_activos')
            ->add('nro_instituciones_pasivos')
            ->add('nro_trabajadores_activos')
            ->add('nro_trabajadores_pasivos')
            ->add('nro_trabajadores_voluntarios')
            ->add('total_general')
            ->add('total_aporte')
            ->add('tasa')
            ->add('cotizacion')
            ->add('nombre_mes')
            ->add('gestion')
            ->add('fecha_liquidacion')
            ->add('fecha_liquidacion_formatted', fn(LiquidacionMinisterioAsuss $model) => Carbon::parse($model->fecha_liquidacion)->format('d/m/Y'))
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')->index()
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
            ->bodyAttribute('text-center')
                ->searchable(),
            Column::make('MES', 'nombre_mes')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
            ->bodyAttribute('text-center')    
            ->sortable()
                ->searchable(),

            Column::make('GESTIÓN', 'gestion')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
            ->bodyAttribute('text-center')    
            ->sortable()
                ->searchable(),

            Column::make('# INSTI. ACTIVOS', 'nro_instituciones_activos')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
            ->bodyAttribute('text-center')    
            ->sortable()
                ->searchable(),

            Column::make('# INSTI. PASIVOS', 'nro_instituciones_pasivos')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
            ->bodyAttribute('text-center')    
            ->sortable()
                ->searchable(),

            Column::make('# VOLUNTARIOS', 'nro_trabajadores_voluntarios')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
            ->bodyAttribute('text-center')    
            ->sortable()
                ->searchable(),
            


            Column::make('COTIZACIÓN ASUSS', 'cotizacion')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
            ->bodyAttribute('text-center')    
            ->sortable()
                ->searchable(),

            Column::make('TASA', 'tasa')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
            ->bodyAttribute('text-center')    
            ->sortable()
                ->searchable(),

            Column::action('OPCIONES')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
            ->bodyAttribute('text-center')
        ];
    }

    public function filters(): array
    {
        return [
            //Filter::datepicker('fecha_liquidacion'),
        ];
    }

    #[\Livewire\Attributes\On('pdfAsuss')]
    public function pdfAsuss($rowId): void
    {   
        $liquidacion = LiquidacionMinisterioAsuss::find($rowId);
        if ($liquidacion) {
            $this->js('window.open("/aportes/Liquidaciones/Asuss/Pdf/' . $liquidacion->id . '/' . $liquidacion->id_mes . '/' . $liquidacion->gestion . '/' . $liquidacion->fecha_liquidacion . '", "_blank")');
        } else{
            $this->js('alert("No se encontró la liquidación con ID: ' . $rowId . '")');
        } 
    }

    public function actions(LiquidacionMinisterioAsuss $row): array
    {
        return [
            Button::add('pdfAsuss')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-pdf-fill" viewBox="0 0 16 16">
                    <path d="M5.523 12.424q.21-.124.459-.238a8 8 0 0 1-.45.606c-.28.337-.498.516-.635.572l-.035.012a.3.3 0 0 1-.026-.044c-.056-.11-.054-.216.04-.36.106-.165.319-.354.647-.548m2.455-1.647q-.178.037-.356.078a21 21 0 0 0 .5-1.05 12 12 0 0 0 .51.858q-.326.048-.654.114m2.525.939a4 4 0 0 1-.435-.41q.344.007.612.054c.317.057.466.147.518.209a.1.1 0 0 1 .026.064.44.44 0 0 1-.06.2.3.3 0 0 1-.094.124.1.1 0 0 1-.069.015c-.09-.003-.258-.066-.498-.256M8.278 6.97c-.04.244-.108.524-.2.829a5 5 0 0 1-.089-.346c-.076-.353-.087-.63-.046-.822.038-.177.11-.248.196-.283a.5.5 0 0 1 .145-.04c.013.03.028.092.032.198q.008.183-.038.465z"/>
                    <path fill-rule="evenodd" d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2m5.5 1.5v2a1 1 0 0 0 1 1h2zM4.165 13.668c.09.18.23.343.438.419.207.075.412.04.58-.03.318-.13.635-.436.926-.786.333-.401.683-.927 1.021-1.51a11.7 11.7 0 0 1 1.997-.406c.3.383.61.713.91.95.28.22.603.403.934.417a.86.86 0 0 0 .51-.138c.155-.101.27-.247.354-.416.09-.181.145-.37.138-.563a.84.84 0 0 0-.2-.518c-.226-.27-.596-.4-.96-.465a5.8 5.8 0 0 0-1.335-.05 11 11 0 0 1-.98-1.686c.25-.66.437-1.284.52-1.794.036-.218.055-.426.048-.614a1.24 1.24 0 0 0-.127-.538.7.7 0 0 0-.477-.365c-.202-.043-.41 0-.601.077-.377.15-.576.47-.651.823-.073.34-.04.736.046 1.136.088.406.238.848.43 1.295a20 20 0 0 1-1.062 2.227 7.7 7.7 0 0 0-1.482.645c-.37.22-.699.48-.897.787-.21.326-.275.714-.08 1.103"/>
                </svg>')
                ->id()
                ->class('btn btn-red')
                ->dispatchTo('aportes.liquidaciones.asuss.asuss-main', 'pdfAsuss', ['data' => $row->id])
                ->tooltip(' PDF DE LIQUIDACIÓN')
                ->can(allowed:auth()->user()->can('liquidacion_pdf.liquidaciones_asuss')),
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
