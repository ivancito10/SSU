<?php

namespace App\Livewire\Aportes\Comprobante;

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
use App\Livewire\Aportes\AportesInstitucionales\AportesInstitucionalesMain;
use App\Livewire\Aportes\AportesInstitucionales\AportesPreliquidacion;
use App\Livewire\Aportes\AportesInstitucionales\AportantesInstiTable;
use App\Livewire\Aportes\AportesInstitucionales\AportantesInstiTempTable;
use Livewire\Attributes\On;
use Illuminate\Support\Collection;

final class ComprobanteTempTable extends PowerGridComponent
{
    use WithExport;
    public $comprobanteId;
    public $modal = false;
    public $modaPreLiq = false;
    public string $tableName = 'ComprobanteAportesInstitucionales';
    public string $sortField = 'comprobante.id';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        // $this->showCheckBox();

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

    public function datasource(): Collection
    {
        $comprobantes = Comprobante::query()
            ->join('aportes.institucion', function ($institucion) {
                $institucion->on('comprobante.id_institucion', '=', 'institucion.id');
            })
            ->join('aportes.mes', function ($mes) {
                $mes->on('comprobante.id_mes', '=', 'mes.id');
            })
            ->join('administracion.estado', function ($estado) {
                $estado->on('comprobante.id_estado', '=', 'estado.id');
            })
            ->select('comprobante.*', 'institucion.nombre as nombre_institucion', 'institucion.acronimo as acronimo_institucion', 'mes.mes as nombre_mes', 'estado.descripcion as estado_comp')
            ->orderBy('comprobante.id', 'desc')
            ->get();

        $comprobantes->map(function ($comprobante) {
            $comprobante->total_salario_cotizable_formatted = number_format($comprobante->total_salario_cotizable, 2, ',', '.');
            // $comprobante->reintegro_formatted = $comprobante->reintegro ? 'SI' : 'NO';
            // $comprobante->beneficio_social_formatted = $comprobante->beneficio_social ? 'SI' : 'NO';
            // $comprobante->planilla_adicional_formatted = $comprobante->planilla_adicional ? 'SI' : 'NO';
            // $comprobante->nro_registro_comprobante = $comprobante->nro_registro_comprobante == null ? 'Pendiente' : $comprobante->nro_registro_comprobante;
            return $comprobante;
        });
        return $comprobantes;
    }

    public function relationSearch(): array
    {
        return [
            'institucionComprobante' => ['nombre'],
            'mesComprobante' => ['mes'],
            'estadoComprobante' => ['descripcion']
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('id_institucion')
            ->add('nombre_institucion')
            ->add('acronimo_institucion')
            ->add('id_mes')
            ->add('nombre_mes')
            ->add('gestion')
            //->add('nro_registro_comprobante')
            ->add('nro_registro_comprobante', function ($nro) {
                if ($nro->nro_registro_comprobante == null) {
                    return '<span> <span style="color: #626567  ;"><em><b>- </b></em></span></span>';
                } elseif ($nro->nro_registro_comprobante != null) {
                    return $nro->nro_registro_comprobante . '/' . Carbon::parse($nro->fecha_liquidacion)->year;
                }
            })
            ->add('cantidad_eventuales', fn($dish) => number_format($dish->cantidad_eventuales, 0, ',', '.'))
            ->add('cantidad_permanentes', fn($dish) => number_format($dish->cantidad_permanentes, 0, ',', '.'))

            ->add('cantidad_todo', fn($cantidad) => ($cantidad->cantidad_eventuales + $cantidad->cantidad_permanentes))

            ->add('monto_bajas_medicas')
            ->add('id_estado')
            ->add('estado_comp', function ($dish) {
                if ($dish->estado_comp == 'SUBIDO') {
                    return '<span> 
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

            ->add('reintegro')
            ->add('reintegro_formatted', function ($dish) {
                if ($dish->reintegro) {
                    return "<span style='color: green'><b>SI</b></span>";
                } else {
                    return "<span style='color:#cb4335'><b>NO</b></span>";
                }
            })
            ->add('beneficio_social')
            ->add('beneficio_social_formatted', function ($dish) {
                if ($dish->beneficio_social) {
                    return "<span style='color: green'><b>SI</b></span>";
                } else {
                    return "<span style='color:#cb4335'><b>NO</b></span>";
                }
            })
            ->add('planilla_adicional')
            ->add('planilla_adicional_formatted', function ($dish) {
                if ($dish->planilla_adicional) {
                    return "<span style='color: green'><b>SI</b></span>";
                } else {
                    return "<span style='color:#cb4335'><b>NO</b></span>";
                }
            })
            ->add('retroactivo_formatted', function ($dish) {
                if ($dish->retroactivo) {
                    return "<span style='color: green'><b>SI</b></span>";
                } else {
                    return "<span style='color:#cb4335'><b>NO</b></span>";
                }
            })
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('cantidad_todo', fn($dish) => number_format(($dish->cantidad_eventuales + $dish->cantidad_permanentes), 0, ',', '.'));
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->index()
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center',),

            Column::make('NRO. COMP', 'nro_registro_comprobante')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center',)
                ->sortable()
                ->searchable(),
            Column::make('INSTITUCIÓN', 'nombre_institucion')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-left ',)
                ->sortable()
                ->searchable(),

            Column::make('# APORTANTES', 'cantidad_todo')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3; color:white;')
                ->bodyAttribute('text-right',)
                ->sortable()
                ->searchable(), 
            Column::make('TOTAL SALARIO COTIZABLE', 'total_salario_cotizable_formatted')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3; color:white;')
                ->bodyAttribute('text-right',)
                ->sortable()
                ->searchable(),



            Column::make('MES', 'nombre_mes')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center',)
                ->sortable()
                ->searchable(),

            Column::make('GESTIÓN', 'gestion')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3; color:white;')
                ->bodyAttribute('text-center',)
                ->sortable()
                ->searchable(),


            Column::make('REINT', 'reintegro_formatted', 'reintegro')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3; color:white;')
                ->bodyAttribute('text-center',)
                ->sortable(),

            Column::make('BEN', 'beneficio_social_formatted', 'beneficio_social')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3; color:white;')
                ->bodyAttribute('text-center',)
                ->sortable(),

            Column::make('P. AD', 'planilla_adicional_formatted', 'planilla_adicional')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3; color:white;')
                ->bodyAttribute('text-center',)
                ->sortable(),
                Column::make('RETRO', 'retroactivo_formatted', 'retroactivo')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3; color:white;')
                ->bodyAttribute('text-center',)
                ->sortable(),
                
            Column::make('ACRÓNIMO', 'acronimo_institucion')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-left ',)
                ->sortable()
                ->searchable(),    

            Column::make('PLANILLA:', 'estado_comp')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3; color:white;')
                ->bodyAttribute('text-center',)
                ->sortable()
                ->searchable(),

            

            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-left ',)

        ];
    }


    

    #[On('liquidacion')]
    public function liquidacion($rowId, $abrirLiquidacion): void
    {
        $abrirAportantes = $abrirLiquidacion;
        $comprobanteId = $rowId;
        $this->dispatch('liquidacionOn', ['modalLiquidacion' => $this->modalLiquidacion, 'comprobanteId' => $comprobanteId])
            ->to(AportesInstitucionalesMain::class);
    }
    #[On('descargarPdfPlanilla')]
    public function descargarPdfPlanilla($data)
    {
        $this->js('window.open("Externos/aportes/pdfplanilla/' . $data . '", "_blank")');
    }
    #[On('eliminarPlanilla')]
    public function EliminarPlanilla($rowId): void
    {
        $this->dispatch('eliminarPlanillas', ['comprobanteId' => $rowId])->to(AportesInstitucionalesMain::class);
    }
    #[On('validarplanilla')]
    public function ValidarPlanilla($rowId): void
    {
        $this->dispatch('validarPlanilla', ['comprobanteId' => $rowId])->to(AportesInstitucionalesMain::class);
    }
    #[On('registroBajaMedica')]
    public function registrobajaconfirmacion($rowId): void
    {
        $this->dispatch('ConfirmarRegistroBajaMedica', ['comprobanteId' => $rowId])->to(AportesInstitucionalesMain::class);
    }
    #[On('evaluar_preliquidacion')]
    public function evaluarPreliquidacion($rowId)
    {
        $this->dispatch('abrir_vista_preliquidacion', ['comprobanteId' => $rowId])->to(AportesInstitucionalesMain::class);
    }


    #[On('aportantes')]
    public function aportantes($rowId, $abrirAportantes): void
    {
        $modalAportantes = $abrirAportantes;
        $modalAportantesTemp = $abrirAportantes;
        $comprobanteId = $rowId;

        $comprobante = Comprobante::find($rowId);
        $id_estado = $comprobante->id_estado;

        if ($id_estado == 1) {
            $this->dispatch('aportantesGrupoTemp', ['modalAportantesTemp' => $modalAportantesTemp, 'comprobanteId' => $comprobanteId])
                ->to(AportesInstitucionalesMain::class);
            $this->dispatch('aportantesGrupoDosTemp', ['comprobanteId' => $comprobanteId])
                ->to(AportantesInstiTempTable::class);
        } else {
            $this->dispatch('aportantesGrupo', ['modalAportantes' => $modalAportantes, 'comprobanteId' => $comprobanteId])
                ->to(AportesInstitucionalesMain::class);
            $this->dispatch('aportantesGrupoDos', ['comprobanteId' => $comprobanteId])
                ->to(AportantesInstiTable::class);
        }
    }

     


    public function actions(Comprobante $row): array
    {
        $buttons = [];
        $buttons[] =  Button::add('aportantes')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
               <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
               </svg>')
            ->class('btn btn-lila')
            ->tooltip('VER APORTANTES')
            ->dispatch('aportantes', ['rowId' => $row->id, 'abrirAportantes' => true])
            ->can(allowed: auth()->user()->can('ver_aportantes.aportes_institucionales'));

        if ($row->estado_comp === 'SUBIDO') {
            $buttons[] =   Button::add('eliminarPlanilla')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                    <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                </svg>')
                ->class('btn btn-red')
                ->tooltip('ELIMINAR PLANILLA')
                ->dispatch('eliminarPlanilla', ['rowId' => $row->id])
                ->can(allowed: auth()->user()->can('eliminar_planilla.aportes_institucionales'));
 

                // Aqui se valida las planillas
                if(($row->reintegro === false && $row->planilla_adicional === false && $row->beneficio_social == false && $row->retroactivo == false) && ($row->id_institucion == 2 || $row->id_institucion == 6 )){

                }elseif(($row->retroactivo === true) && ($row->id_institucion == 2 || $row->id_institucion == 6 )){

                }else{ 
 
 
                    $buttons[] = Button::add('ValidarPlanilla')
                        ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16">
                        <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0"/>
                        </svg>')
                        ->class('btn btn-green')
                        ->tooltip('VALIDAR PLANILLA')
                        ->dispatch('validarplanilla', ['rowId' => $row->id])
                        ->can(allowed: auth()->user()->can('validar_planilla.aportes_institucionales'));
                }

 
              
        }

        // Aqui los botones de planillas validadas
        if(($row->reintegro === false && $row->planilla_adicional === false && $row->beneficio_social == false && $row->retroactivo == false) && ($row->id_institucion == 2 || $row->id_institucion == 6 )){

        }elseif(($row->retroactivo === true) && ($row->id_institucion == 2 || $row->id_institucion == 6 )){

        }else{
         
        
            if ($row->estado_comp === 'VALIDADO') {
                $buttons[] =   Button::add('preliq')
                    ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calculator-fill" viewBox="0 0 16 16">
                    <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2zm2 .5v2a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 0-.5.5m0 4v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5M4.5 9a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM4 12.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5M7.5 6a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM7 9.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5m.5 2.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM10 6.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5m.5 2.5a.5.5 0 0 0-.5.5v4a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-4a.5.5 0 0 0-.5-.5z"/>
                    </svg>')

                    ->class('btn btn-infoDos')
                    ->tooltip('PRE-LIQUIDACIÓN')
                    //->dispatchTo('aportes.aportes-institucionales.aportes-institucionales-main', 'funcion_preliquidacion', ['data' => $row->id])
                    ->dispatch('evaluar_preliquidacion', ['rowId' => $row->id])
                    //->route('aportes.aporte_institucional.preliquidacion', ['id' => $row->id])
                    ->can(allowed: auth()->user()->can('preliquidacion.aportes_institucionales'));

                $buttons[] = Button::add('formaPago')
                    ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-coin" viewBox="0 0 16 16">
                    <path d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518z"/>
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                    <path d="M8 13.5a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11m0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12"/>
                    </svg>')
                    ->class('btn btn-teal')
                    ->tooltip('REGISTRAR FORMA DE PAGO')
                    ->dispatchTo('aportes.aportes-institucionales.aportes-institucionales-main', 'funcion_formapago_vista', ['data' => $row->id])
                    ->can(allowed: auth()->user()->can('forma_pago.aportes_institucionales'));
            }

            if ($row->estado_comp === 'REGISTRADO') {


                $buttons[] = Button::add('liquidacion')
                    ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-database-fill-check" viewBox="0 0 16 16">
                    <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.708l.547.548 1.17-1.951a.5.5 0 1 1 .858.514M8 1c-1.573 0-3.022.289-4.096.777C2.875 2.245 2 2.993 2 4s.875 1.755 1.904 2.223C4.978 6.711 6.427 7 8 7s3.022-.289 4.096-.777C13.125 5.755 14 5.007 14 4s-.875-1.755-1.904-2.223C11.022 1.289 9.573 1 8 1"/>
                    <path d="M2 7v-.839c.457.432 1.004.751 1.49.972C4.722 7.693 6.318 8 8 8s3.278-.307 4.51-.867c.486-.22 1.033-.54 1.49-.972V7c0 .424-.155.802-.411 1.133a4.51 4.51 0 0 0-4.815 1.843A12 12 0 0 1 8 10c-1.573 0-3.022-.289-4.096-.777C2.875 8.755 2 8.007 2 7m6.257 3.998L8 11c-1.682 0-3.278-.307-4.51-.867-.486-.22-1.033-.54-1.49-.972V10c0 1.007.875 1.755 1.904 2.223C4.978 12.711 6.427 13 8 13h.027a4.55 4.55 0 0 1 .23-2.002m-.002 3L8 14c-1.682 0-3.278-.307-4.51-.867-.486-.22-1.033-.54-1.49-.972V13c0 1.007.875 1.755 1.904 2.223C4.978 15.711 6.427 16 8 16c.536 0 1.058-.034 1.555-.097a4.5 4.5 0 0 1-1.3-1.905"/>
                    </svg>')
                    ->class('btn btn-orange ')
                    ->tooltip('REGISTRAR LIQUIDACIÓN')
                    ->dispatchTo('aportes.aportes-institucionales.aportes-institucionales-main', 'funcion_liquidacion', ['data' => $row->id])
                    ->can(allowed: auth()->user()->can('liquidacion.aportes_institucionales'));
            }
            
            if ($row->estado_comp === 'LIQUIDADO') {

                $buttons[] =   Button::add('pdf')
                    ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-pdf-fill" viewBox="0 0 16 16">
                        <path d="M5.523 12.424q.21-.124.459-.238a8 8 0 0 1-.45.606c-.28.337-.498.516-.635.572l-.035.012a.3.3 0 0 1-.026-.044c-.056-.11-.054-.216.04-.36.106-.165.319-.354.647-.548m2.455-1.647q-.178.037-.356.078a21 21 0 0 0 .5-1.05 12 12 0 0 0 .51.858q-.326.048-.654.114m2.525.939a4 4 0 0 1-.435-.41q.344.007.612.054c.317.057.466.147.518.209a.1.1 0 0 1 .026.064.44.44 0 0 1-.06.2.3.3 0 0 1-.094.124.1.1 0 0 1-.069.015c-.09-.003-.258-.066-.498-.256M8.278 6.97c-.04.244-.108.524-.2.829a5 5 0 0 1-.089-.346c-.076-.353-.087-.63-.046-.822.038-.177.11-.248.196-.283a.5.5 0 0 1 .145-.04c.013.03.028.092.032.198q.008.183-.038.465z"/>
                        <path fill-rule="evenodd" d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2m5.5 1.5v2a1 1 0 0 0 1 1h2zM4.165 13.668c.09.18.23.343.438.419.207.075.412.04.58-.03.318-.13.635-.436.926-.786.333-.401.683-.927 1.021-1.51a11.7 11.7 0 0 1 1.997-.406c.3.383.61.713.91.95.28.22.603.403.934.417a.86.86 0 0 0 .51-.138c.155-.101.27-.247.354-.416.09-.181.145-.37.138-.563a.84.84 0 0 0-.2-.518c-.226-.27-.596-.4-.96-.465a5.8 5.8 0 0 0-1.335-.05 11 11 0 0 1-.98-1.686c.25-.66.437-1.284.52-1.794.036-.218.055-.426.048-.614a1.24 1.24 0 0 0-.127-.538.7.7 0 0 0-.477-.365c-.202-.043-.41 0-.601.077-.377.15-.576.47-.651.823-.073.34-.04.736.046 1.136.088.406.238.848.43 1.295a20 20 0 0 1-1.062 2.227 7.7 7.7 0 0 0-1.482.645c-.37.22-.699.48-.897.787-.21.326-.275.714-.08 1.103"/>
                    </svg>')
                    ->class('btn btn-red')
                    ->tooltip('COMPROBANTE PDF')
                    /* ->dispatch('pdf', ['rowId' => $row->id]) */
                    ->dispatchTo('aportes.aportes-institucionales.aportes-institucionales-main', 'verPdf', ['data' => $row->id])
                    ->can(allowed: auth()->user()->can('comprobante_pdf.aportes_institucionales'));


                $buttons[] = Button::add('ver')
                    ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-pdf-fill" viewBox="0 0 16 16">
                        <path d="M5.523 12.424q.21-.124.459-.238a8 8 0 0 1-.45.606c-.28.337-.498.516-.635.572l-.035.012a.3.3 0 0 1-.026-.044c-.056-.11-.054-.216.04-.36.106-.165.319-.354.647-.548m2.455-1.647q-.178.037-.356.078a21 21 0 0 0 .5-1.05 12 12 0 0 0 .51.858q-.326.048-.654.114m2.525.939a4 4 0 0 1-.435-.41q.344.007.612.054c.317.057.466.147.518.209a.1.1 0 0 1 .026.064.44.44 0 0 1-.06.2.3.3 0 0 1-.094.124.1.1 0 0 1-.069.015c-.09-.003-.258-.066-.498-.256M8.278 6.97c-.04.244-.108.524-.2.829a5 5 0 0 1-.089-.346c-.076-.353-.087-.63-.046-.822.038-.177.11-.248.196-.283a.5.5 0 0 1 .145-.04c.013.03.028.092.032.198q.008.183-.038.465z"/>
                        <path fill-rule="evenodd" d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2m5.5 1.5v2a1 1 0 0 0 1 1h2zM4.165 13.668c.09.18.23.343.438.419.207.075.412.04.58-.03.318-.13.635-.436.926-.786.333-.401.683-.927 1.021-1.51a11.7 11.7 0 0 1 1.997-.406c.3.383.61.713.91.95.28.22.603.403.934.417a.86.86 0 0 0 .51-.138c.155-.101.27-.247.354-.416.09-.181.145-.37.138-.563a.84.84 0 0 0-.2-.518c-.226-.27-.596-.4-.96-.465a5.8 5.8 0 0 0-1.335-.05 11 11 0 0 1-.98-1.686c.25-.66.437-1.284.52-1.794.036-.218.055-.426.048-.614a1.24 1.24 0 0 0-.127-.538.7.7 0 0 0-.477-.365c-.202-.043-.41 0-.601.077-.377.15-.576.47-.651.823-.073.34-.04.736.046 1.136.088.406.238.848.43 1.295a20 20 0 0 1-1.062 2.227 7.7 7.7 0 0 0-1.482.645c-.37.22-.699.48-.897.787-.21.326-.275.714-.08 1.103"/>
                    </svg>')
                    ->class('btn btn-blue')
                    ->tooltip(' COMPROBANTE PDF COPIA')
                    /* ->dispatch('ver', ['rowId' => $row->id]) */
                    ->dispatchTo('aportes.aportes-institucionales.aportes-institucionales-main', 'verCopiaPdf', ['data' => $row->id])
                    ->can(allowed: auth()->user()->can('comprobante_pdf_copia.aportes_institucionales'));

                


                if ( $row->reintegro === true || $row->planilla_adicional === true || $row->beneficio_social == true) {
                } else {

                    $buttons[] = Button::add('bajaMedica')
                        ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-check" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0"/>
                            <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1z"/>
                            <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0z"/>
                            </svg>')
                        ->class('btn btn-orange')
                        ->route('aportes.BajasMedicas.index', ['id' => $row->id])
                        ->tooltip(' AGREGAR BAJAS MÉDICAS')
                        ->can(allowed: auth()->user()->can('registro_baja_medica.aportes_institucionales'));
                    //->dispatch('bajaMedica', ['rowId' => $row->id,'abrirBajaMedica'=>true ]);

                    if($row->monto_bajas_medicas != null){
 
                        $buttons[] = Button::add('registroBajaMedica')
                            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2-circle" viewBox="0 0 16 16">
                            <path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0"/>
                            <path d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0z"/>
                            </svg>')
                            ->class('btn btn-teal')
                            ->tooltip('REEMBOLSAR BAJAS MÉDICAS')
                            ->dispatch('registroBajaMedica', ['rowId' => $row->id])
                            ->can(allowed: auth()->user()->can('reporte_baja_medica.aportes_institucionales')); 
                    }
                }

                
                 if($row->monto_bajas_medicas > 0 && $row->reintegro === false && $row->planilla_adicional === false && $row->beneficio_social == false){
                    $buttons[]=Button::add('pdf_baja_medica')
                    ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="17" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z"/>
                        </svg>')
                    ->class('btn btn-celeste')
                    ->tooltip('PDF REEMBOLSO DE BAJAS MÉDICAS')
                    ->dispatchTo('aportes.aportes-institucionales.aportes-institucionales-main', 'funcion_pdf_baja_medica',['data' => $row->id])
                    ->can(allowed:auth()->user()->can('reporte_baja_medica.aportes_institucionales'));
                    
                } 
            }
        
        }



        if ($row->estado_comp != 'SUBIDO') {
            $buttons[] =   Button::add('excelPdf')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-xlsx" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M14 4.5V11h-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM7.86 14.841a1.13 1.13 0 0 0 .401.823q.195.162.479.252.284.091.665.091.507 0 .858-.158.355-.158.54-.44a1.17 1.17 0 0 0 .187-.656q0-.336-.135-.56a1 1 0 0 0-.375-.357 2 2 0 0 0-.565-.21l-.621-.144a1 1 0 0 1-.405-.176.37.37 0 0 1-.143-.299q0-.234.184-.384.188-.152.513-.152.214 0 .37.068a.6.6 0 0 1 .245.181.56.56 0 0 1 .12.258h.75a1.1 1.1 0 0 0-.199-.566 1.2 1.2 0 0 0-.5-.41 1.8 1.8 0 0 0-.78-.152q-.44 0-.777.15-.336.149-.527.421-.19.273-.19.639 0 .302.123.524t.351.367q.229.143.54.213l.618.144q.31.073.462.193a.39.39 0 0 1 .153.326.5.5 0 0 1-.085.29.56.56 0 0 1-.255.193q-.168.07-.413.07-.176 0-.32-.04a.8.8 0 0 1-.249-.115.58.58 0 0 1-.255-.384zm-3.726-2.909h.893l-1.274 2.007 1.254 1.992h-.908l-.85-1.415h-.035l-.853 1.415H1.5l1.24-2.016-1.228-1.983h.931l.832 1.438h.036zm1.923 3.325h1.697v.674H5.266v-3.999h.791zm7.636-3.325h.893l-1.274 2.007 1.254 1.992h-.908l-.85-1.415h-.035l-.853 1.415h-.861l1.24-2.016-1.228-1.983h.931l.832 1.438h.036z"/>
                </svg>')
                ->class('btn btn-green')
                ->dispatchTo('aportes.aportes-institucionales.aportes-institucionales-main','excelPdf', ['data' => $row->id])
                ->tooltip('EXCEL PLANILLA PDF');
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
