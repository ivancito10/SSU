<?php

namespace App\Livewire\Aportes\Externos\Aporte;

use App\Models\Aportes\Comprobante;
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
use App\Livewire\Aportes\Externos\Aporte\AportantesInstiTableExt;
use App\Livewire\Aportes\Externos\Aporte\AportantesInstiTempTableExt;
use App\Livewire\Aportes\Externos\Aporte\AporteInstitucionMainExt;

final class AporteInstitucionTableExt extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'ComprobanteAportesInstitucionalesExterno';
    public string $sortField = 'comprobante.id';
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
        return Comprobante::query()
            ->join('aportes.institucion', function ($institucion) {
                $institucion->on('comprobante.id_institucion', '=', 'institucion.id');
            })
            ->join('aportes.mes', function ($mes) {
                $mes->on('comprobante.id_mes', '=', 'mes.id');
            })
            ->join('administracion.estado', function ($estado) {
                $estado->on('comprobante.id_estado', '=', 'estado.id');
            })
            ->select(
                'comprobante.*',
                'institucion.nombre as nombre_institucion',
                'mes.mes as nombre_mes',
                'estado.descripcion as estado_comp'
            )
            ->where('institucion.id_usuario_responsable', '=', auth()->user()->id);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('id_institucion')
            ->add('nombre_institucion')
            ->add('gestion')
            ->add('estado_comp', function ($dish) {
                if ($dish->estado_comp == 'SUBIDO') {
                    return '<span>  SUBIDO  
                    </span>';
                } elseif ($dish->estado_comp == 'VALIDADO') {
                    return '<span style="color: #45a511">
                   <b>VALIDADO</b>
                    </span>
                    ';
                } elseif ($dish->estado_comp == 'REGISTRADO') {
                    return '<span style="color: #f78607"> 
                    <b>REGISTRADO</b>  
                    </span>';
                } elseif ($dish->estado_comp == 'LIQUIDADO') {
                    return '<span style="color: #1d6ca0 "> 
                    <b>A LIQUIDADO</b>
                    </span>';
                }
            })
            ->add('fecha_liquidacion')

            ->add('total_salario_cotizable')
            ->add('total_salario_cotizable_formatted', function ($dish) {
                return number_format($dish->total_salario_cotizable, 2, ',', '.');
            })

            ->add('fecha_liquidacion_formatted', fn(Comprobante $model) => Carbon::parse($model->fecha_liquidacion)->format('d/m/Y'))
            ->add('cantidad_eventuales')
            ->add('cantidad_permanentes')

            ->add('reintegro', function ($dish) {
                if ($dish->reintegro) {
                    return "<span style='color: green'><b>SI</b></span>";
                } else {
                    return "<span style='color: #c0392b'><b>NO</b></span>";
                }
            })
            ->add('beneficio_social', function ($dish) {
                if ($dish->beneficio_social) {
                    return "<span style='color: green'><b>SI</b></span>";
                } else {
                    return "<span style='color: #c0392b'><b>NO</b></span>";
                }
            })
            
            ->add('planilla_adicional', function ($dish) {
                if ($dish->planilla_adicional) {
                    return "<span style='color: green'><b>SI</b></span>";
                } else {
                    return "<span style='color: #c0392b'><b>NO</b></span>";
                }
            }) 
            ->add('retroactivo', function ($dish) {
                if ($dish->retroactivo) {
                    return "<span style='color: green'><b>SI</b></span>";
                } else {
                    return "<span style='color: #c0392b'><b>NO</b></span>";
                }
            })
            ->add('cantidad_eventuales')
            ->add('cantidad_permanentes')
            ->add('cantidad_todo', fn($dish) => number_format(($dish->cantidad_eventuales + $dish->cantidad_permanentes), 0, ',', '.'))
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->index()
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center'),

            Column::make('INSTITUCIÓN', 'nombre_institucion')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->sortable()
                ->searchableRaw("
                    TRIM(CONCAT(
                        COALESCE(institucion.nombre, '')
                    ))
                "),

            Column::make('MES', 'nombre_mes')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchableRaw("TRIM(CONCAT(COALESCE(mes.mes,'')))"),

            Column::make('GESTIÓN', 'gestion')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            /* Column::make('FECHA LIQ.', 'fecha_liquidacion_formatted', 'fecha_liquidacion')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(), */

            Column::make('TOTAL S. COTIZABLE', 'total_salario_cotizable_formatted', 'total_salario_cotizable')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-right')
                ->sortable()
                ->searchable(),

            Column::make('# APORTANTES', 'cantidad_todo')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-right')
                ->sortable()
                ->searchable(),
            Column::make('REINT', 'reintegro')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
            Column::make('BEN', 'beneficio_social')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('P. AD', 'planilla_adicional')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
                Column::make('RETRO', 'retroactivo')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

             

            Column::make('PLANILLA:', 'estado_comp')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),


            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-left'),
        ];
    }

    public function filters(): array
    {
        return [
            //  Filter::datepicker('fecha_liquidacion'),
            // Filter::datepicker('fecha_liquidacion_baja_medica'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    #[\Livewire\Attributes\On('aportantesExt')]
    public function aportantesExt($rowId, $abrirAportantesExt): void
    {
        $modalAportantesExt = $abrirAportantesExt;
        $modalAportantesTempExt = $abrirAportantesExt;
        $comprobanteId = $rowId;

        $comprobante = Comprobante::find($rowId);
        $id_estado = $comprobante->id_estado;

        if ($id_estado == 1) {
            $this->dispatch('aportantesGrupoTempExt', ['modalAportantesTempExt' => $modalAportantesTempExt, 'comprobanteId' => $comprobanteId])
                ->to(AporteInstitucionMainExt::class);
            $this->dispatch('aportantesGrupoDosTempExt', ['comprobanteId' => $comprobanteId])
                ->to(AportantesInstiTempTableExt::class);
        } else {
            $this->dispatch('aportantesGrupoExt', ['modalAportantesExt' => $modalAportantesExt, 'comprobanteId' => $comprobanteId])
                ->to(AporteInstitucionMainExt::class);
            $this->dispatch('aportantesGrupoDosExt', ['comprobanteId' => $comprobanteId])
                ->to(AportantesInstiTableExt::class);
        }
    }

     
    #[\Livewire\Attributes\On('MandarPdf')]
    public function MandarPdf($rowId): void
    {
        // $this->comprobanteId = $rowId;
        // $this->dispatch('ImprimirPDF', ['comprobanteId'=>$this->comprobanteId])
        // ->to(AporteInstitucionMainExt::class);
        $this->js('window.open("/aportes/pdfcopia/' . $rowId . '")');
    }

    public function actions(Comprobante $row): array
    {
        $buttons = [];

        $buttons[] = Button::add('aportantesExt')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                    <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                    </svg>')
            ->class('btn btn-lila')
            ->tooltip('VER APORTANTES')
            ->dispatch('aportantesExt', ['rowId' => $row->id, 'abrirAportantesExt' => true])
            ->can(allowed: auth()->user()->can('ver.aportes_externos'));
  
        if ($row->estado_comp === 'LIQUIDADO') {

            $buttons[] =   Button::add('MandarPdf')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-pdf-fill" viewBox="0 0 16 16">
                    <path d="M5.523 12.424q.21-.124.459-.238a8 8 0 0 1-.45.606c-.28.337-.498.516-.635.572l-.035.012a.3.3 0 0 1-.026-.044c-.056-.11-.054-.216.04-.36.106-.165.319-.354.647-.548m2.455-1.647q-.178.037-.356.078a21 21 0 0 0 .5-1.05 12 12 0 0 0 .51.858q-.326.048-.654.114m2.525.939a4 4 0 0 1-.435-.41q.344.007.612.054c.317.057.466.147.518.209a.1.1 0 0 1 .026.064.44.44 0 0 1-.06.2.3.3 0 0 1-.094.124.1.1 0 0 1-.069.015c-.09-.003-.258-.066-.498-.256M8.278 6.97c-.04.244-.108.524-.2.829a5 5 0 0 1-.089-.346c-.076-.353-.087-.63-.046-.822.038-.177.11-.248.196-.283a.5.5 0 0 1 .145-.04c.013.03.028.092.032.198q.008.183-.038.465z"/>
                    <path fill-rule="evenodd" d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2m5.5 1.5v2a1 1 0 0 0 1 1h2zM4.165 13.668c.09.18.23.343.438.419.207.075.412.04.58-.03.318-.13.635-.436.926-.786.333-.401.683-.927 1.021-1.51a11.7 11.7 0 0 1 1.997-.406c.3.383.61.713.91.95.28.22.603.403.934.417a.86.86 0 0 0 .51-.138c.155-.101.27-.247.354-.416.09-.181.145-.37.138-.563a.84.84 0 0 0-.2-.518c-.226-.27-.596-.4-.96-.465a5.8 5.8 0 0 0-1.335-.05 11 11 0 0 1-.98-1.686c.25-.66.437-1.284.52-1.794.036-.218.055-.426.048-.614a1.24 1.24 0 0 0-.127-.538.7.7 0 0 0-.477-.365c-.202-.043-.41 0-.601.077-.377.15-.576.47-.651.823-.073.34-.04.736.046 1.136.088.406.238.848.43 1.295a20 20 0 0 1-1.062 2.227 7.7 7.7 0 0 0-1.482.645c-.37.22-.699.48-.897.787-.21.326-.275.714-.08 1.103"/>
                </svg>')
                ->class('btn btn-blue')
                ->dispatchTo('aportes.externos.aporte.aporte-institucion-main-ext', 'pdfCopiaComp', ['data' => $row->id])
                ->tooltip('DESCARGAR COMPROBANTE PDF')
                ->can(allowed: auth()->user()->can('ver_pdf_copia.aportes_externos'));
        } else{

            if($row->estado_comp !== 'REGISTRADO'){
                $buttons[] =   Button::add('preliq')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calculator-fill" viewBox="0 0 16 16">
                            <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2zm2 .5v2a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 0-.5.5m0 4v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5M4.5 9a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM4 12.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5M7.5 6a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM7 9.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5m.5 2.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM10 6.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5m.5 2.5a.5.5 0 0 0-.5.5v4a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-4a.5.5 0 0 0-.5-.5z"/>
                        </svg>')
                ->class('btn btn-infoDos')
                //->dispatch('preliq', ['rowId' => $row->id, 'abrirPreLiq' => true])
                ->route('aportes.externos.aportes.preliquidacion', ['id' => $row->id])
                ->tooltip('EVALUAR PRE - LIQUIDACIÓN')
                ->can(allowed: auth()->user()->can('preliquidacion.aportes_externos'));
                  
            }
            if ($row->estado_comp === 'SUBIDO') {
                $buttons[] =   Button::add('eliminarPlanilla')
                    ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                        <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                    </svg>')
                    ->class('btn btn-red')
                    ->tooltip('ELIMINAR PLANILLA')
                    ->dispatchTo('aportes.externos.aporte.aporte-institucion-main-ext', 'funcionEliminarPlanilla', ['data' => $row->id])
                    ->can(allowed: auth()->user()->can('eliminar_planilla.aportes_institucionales'));
            }
            
        }
        if ($row->estado_comp !== 'SUBIDO') {
             
            $buttons[] =   Button::add('descargarPdf')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-xlsx" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M14 4.5V11h-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM7.86 14.841a1.13 1.13 0 0 0 .401.823q.195.162.479.252.284.091.665.091.507 0 .858-.158.355-.158.54-.44a1.17 1.17 0 0 0 .187-.656q0-.336-.135-.56a1 1 0 0 0-.375-.357 2 2 0 0 0-.565-.21l-.621-.144a1 1 0 0 1-.405-.176.37.37 0 0 1-.143-.299q0-.234.184-.384.188-.152.513-.152.214 0 .37.068a.6.6 0 0 1 .245.181.56.56 0 0 1 .12.258h.75a1.1 1.1 0 0 0-.199-.566 1.2 1.2 0 0 0-.5-.41 1.8 1.8 0 0 0-.78-.152q-.44 0-.777.15-.336.149-.527.421-.19.273-.19.639 0 .302.123.524t.351.367q.229.143.54.213l.618.144q.31.073.462.193a.39.39 0 0 1 .153.326.5.5 0 0 1-.085.29.56.56 0 0 1-.255.193q-.168.07-.413.07-.176 0-.32-.04a.8.8 0 0 1-.249-.115.58.58 0 0 1-.255-.384zm-3.726-2.909h.893l-1.274 2.007 1.254 1.992h-.908l-.85-1.415h-.035l-.853 1.415H1.5l1.24-2.016-1.228-1.983h.931l.832 1.438h.036zm1.923 3.325h1.697v.674H5.266v-3.999h.791zm7.636-3.325h.893l-1.274 2.007 1.254 1.992h-.908l-.85-1.415h-.035l-.853 1.415h-.861l1.24-2.016-1.228-1.983h.931l.832 1.438h.036z"/>
                </svg>')
                ->class('btn btn-green')
                ->dispatchTo('aportes.externos.aporte.aporte-institucion-main-ext', 'pdfPlanilla', ['data' => $row->id])
                ->tooltip('EXCEL PLANILLA PDF');
            //->can(allowed: auth()->user()->can('ver_pdf_copia.aportes_externos'));
        }
        if ($row->estado_comp == 'VALIDADO') {

            /* $buttons[] =   Button::add('forma_pago')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-coin" viewBox="0 0 16 16">
                <path d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518z"/>
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                <path d="M8 13.5a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11m0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12"/>
                </svg>')
                ->class('btn btn-teal')
                ->route('aportes.externos.forma-pago-ext', ['id' => $row->id])
                ->tooltip('REGISTRAR FORMA DE PAGO'); */
                //->can(allowed: auth()->user()->can('preliquidacion.aportes_externos'));
            
        }elseif ($row->estado_comp == 'REGISTRADO') {

            /* $buttons[] =   Button::add('descargarPdf')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-database-fill-check" viewBox="0 0 16 16">
                <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.708l.547.548 1.17-1.951a.5.5 0 1 1 .858.514M8 1c-1.573 0-3.022.289-4.096.777C2.875 2.245 2 2.993 2 4s.875 1.755 1.904 2.223C4.978 6.711 6.427 7 8 7s3.022-.289 4.096-.777C13.125 5.755 14 5.007 14 4s-.875-1.755-1.904-2.223C11.022 1.289 9.573 1 8 1"/>
                <path d="M2 7v-.839c.457.432 1.004.751 1.49.972C4.722 7.693 6.318 8 8 8s3.278-.307 4.51-.867c.486-.22 1.033-.54 1.49-.972V7c0 .424-.155.802-.411 1.133a4.51 4.51 0 0 0-4.815 1.843A12 12 0 0 1 8 10c-1.573 0-3.022-.289-4.096-.777C2.875 8.755 2 8.007 2 7m6.257 3.998L8 11c-1.682 0-3.278-.307-4.51-.867-.486-.22-1.033-.54-1.49-.972V10c0 1.007.875 1.755 1.904 2.223C4.978 12.711 6.427 13 8 13h.027a4.55 4.55 0 0 1 .23-2.002m-.002 3L8 14c-1.682 0-3.278-.307-4.51-.867-.486-.22-1.033-.54-1.49-.972V13c0 1.007.875 1.755 1.904 2.223C4.978 15.711 6.427 16 8 16c.536 0 1.058-.034 1.555-.097a4.5 4.5 0 0 1-1.3-1.905"/>
                </svg>')
                ->class('btn btn-orange')
                ->dispatchTo('aportes.externos.aporte.aporte-institucion-main-ext', 'verFormaPago', ['data' => $row->id])
                ->tooltip('VER FORMA DE PAGO REGISTRADO'); */
            //->can(allowed: auth()->user()->can('ver_pdf_copia.aportes_externos'));
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
