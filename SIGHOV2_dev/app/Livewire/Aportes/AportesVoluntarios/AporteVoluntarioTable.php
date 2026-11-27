<?php

namespace App\Livewire\Aportes\AportesVoluntarios;

use App\Models\Aportes\AporteVoluntario;
use Illuminate\Support\Carbon;
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
use App\Livewire\Aportes\AportesVoluntarios\AporteVoluntarioMain;
use Livewire\Attributes\On;
use Illuminate\Support\Collection;
use DB;

final class AporteVoluntarioTable extends PowerGridComponent
{
    use WithExport;
    public $voluntarioId;

    public string $tableName = 'AporteVoluntarioLista';
    public string $sortField = 'aporte_v.id';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput()
                ->withoutLoading(),
            Footer::make()->showPerPage()->showRecordCount(),
        ];
    }

    public function datasource(): Collection
    {
        $aporteVoluntario = AporteVoluntario::query()
            ->join('aportes.contrato_voluntario', function ($contrato) {
                $contrato->on('aporte_v.id_contrato_voluntario', '=', 'contrato_voluntario.id');
            })
            ->join('administracion.persona', function ($persona) {
                $persona->on('contrato_voluntario.id_persona', '=', 'persona.id');
            })
            ->join('aportes.mes', function ($mes) {
                $mes->on('aporte_v.id_mes', '=', 'mes.id');
            })
            ->leftjoin('aportes.comprobante', function ($comprobante) {
                $comprobante->on('aporte_v.id', '=', 'comprobante.id_aporte_v');
            })
            ->leftjoin('aportes.forma_pago', function ($formaPago) {
                $formaPago->on('comprobante.id', '=', 'forma_pago.id_comprobante');
            })
            ->select(
                'aporte_v.*',
                'mes.mes as nombre_mes',
                'persona.nombres as nombres_p',
                'persona.p_apellido as p_apellido_p',
                'persona.s_apellido as s_apellido_p',
                'persona.ci as p_ci',
                'persona.complemento as p_complemento',
                'comprobante.fecha_liquidacion as fecha_liq',
                'comprobante.nro_registro_comprobante as nro_comprobante',
                'forma_pago.fecha_pago as fecha_pago_p'
            )
            ->orderBy('aporte_v.id', 'desc')
            ->get();
        $aporteVoluntario->map(function ($item) {
            $item->total_aporte_formatted = number_format($item->total_aporte, 2, ',', '.');
            $item->fecha_liq_formatted = Carbon::parse($item->fecha_liq)->format('d/m/Y');
            $item->fecha_pago_p_formatted = Carbon::parse($item->fecha_pago_p)->format('d/m/Y');
            $item->nombre_completo = $item->p_apellido_p . ' ' . $item->s_apellido_p . ' ' . $item->nombres_p;
            $item->total_aporte_formatted = number_format($item->total_aporte, 2, ',', '.');
            return $item;
        });
        return $aporteVoluntario;
    }

    public function relationSearch(): array
    {
        return [
            'mesAporteVoluntario' => ['mes'],
            'contratoVoluntarioAporteVoluntario' => ['nombres', 'p_apellido', 's_apellido', 'ci', 'complemento'],
        ];
    }




    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('total_ganado')
            ->add('total_aporte')
            // ->add('total_aporte_formatted',function($dish) {
            //     return number_format($dish->total_aporte, 2,',','.');
            // })
            ->add('gestion')
            ->add('nombre_mes')
            ->add('carnet_formatted', function ($carnet) {
                if ($carnet->p_complemento == null) {
                    return $carnet->p_ci;
                } else {
                    return $carnet->p_ci . '-' . $carnet->p_complemento;
                }
            })
            ->add('nombre_completo', fn(AporteVoluntario $model) =>
            $model->contratoVoluntarioAporteVoluntario->personaContratoVoluntario->p_apellido . ' ' . $model->contratoVoluntarioAporteVoluntario->personaContratoVoluntario->s_apellido . ' ' . $model->contratoVoluntarioAporteVoluntario->personaContratoVoluntario->nombres)
            ->add('fecha_liq')
            // ->add('fecha_liq_formatted', function ($fecha_liq){
            //     return Carbon::parse($fecha_liq->fecha_liq)->format('d/m/Y');
            // })
            //->add('nro_comprobante')
            ->add('nro_comprobante', function ($nro) {
                if ($nro->nro_comprobante) {
                    return $nro->nro_comprobante . '/' . Carbon::parse($nro->fecha_liquidacion)->year;
                }
            })
            ->add('fecha_pago_p')
            ->add('fecha_pago_p_formatted', function ($fecha_pago) {
                return Carbon::parse($fecha_pago->fecha_pago_p)->format('d/m/Y');
            })
            ->add('afiliado')
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center')
                ->index(),

            Column::make('NRO. COMP', 'nro_comprobante')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('NOMBRE COMPLETO', 'nombre_completo')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('CARNET', 'carnet_formatted', 'p_ci')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),

            /*Column::make('NOMBRE COMPLETO RAW', 'nombre_completo_raw')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3; color:white;')
            ->sortable()
            ->searchable(),*/
            //->hidden(isHidden: true, isForceHidden: true),


            Column::make('TOTAL APORTE', 'total_aporte_formatted')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-right')
                ->sortable()
                ->searchable(),

            Column::make('MES', 'nombre_mes')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('GESTIÓN', 'gestion')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('FECHA DE PAGO', 'fecha_pago_p_formatted')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('FECHA DE LIQUIDACIÓN', 'fecha_liq_formatted')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:  #2471a3; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center')
        ];
    }

    public function filters(): array
    {
        return [];
    }

    #[On('ver')]
    public function ver($rowId): void
    {
        $this->voluntarioId = $rowId;
        $this->dispatch('vistaAporteVoluntario', ['voluntarioId' => $this->voluntarioId])
            ->to(AporteVoluntarioMain::class);
    }

    #[On('pdf')]
    public function pdf($rowId): void
    {
        $this->js('window.open("/aportes/pdf/voluntarios/' . $rowId . '", "_blank")');
    }

    #[On('verCopia')]
    public function verCopia($rowId): void
    {
        $this->js('window.open("/aportes/pdfcopia/voluntarios/' . $rowId . '", "_blank")');
    }

    public function actions(AporteVoluntario $row): array
    {
        return [

            Button::add('ver')
                ->slot(
                    '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                    </svg>',
                )
                ->class('btn btn-infoDos')
                ->tooltip('VER APORTE')
                ->dispatchTo('aportes.aportes-voluntarios.aporte-voluntario-main', 'abrirAporteVoluntario', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('ver.aportes_voluntarios')),

            Button::add('pdf')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-pdf-fill" viewBox="0 0 16 16">
                    <path d="M5.523 12.424q.21-.124.459-.238a8 8 0 0 1-.45.606c-.28.337-.498.516-.635.572l-.035.012a.3.3 0 0 1-.026-.044c-.056-.11-.054-.216.04-.36.106-.165.319-.354.647-.548m2.455-1.647q-.178.037-.356.078a21 21 0 0 0 .5-1.05 12 12 0 0 0 .51.858q-.326.048-.654.114m2.525.939a4 4 0 0 1-.435-.41q.344.007.612.054c.317.057.466.147.518.209a.1.1 0 0 1 .026.064.44.44 0 0 1-.06.2.3.3 0 0 1-.094.124.1.1 0 0 1-.069.015c-.09-.003-.258-.066-.498-.256M8.278 6.97c-.04.244-.108.524-.2.829a5 5 0 0 1-.089-.346c-.076-.353-.087-.63-.046-.822.038-.177.11-.248.196-.283a.5.5 0 0 1 .145-.04c.013.03.028.092.032.198q.008.183-.038.465z"/>
                    <path fill-rule="evenodd" d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2m5.5 1.5v2a1 1 0 0 0 1 1h2zM4.165 13.668c.09.18.23.343.438.419.207.075.412.04.58-.03.318-.13.635-.436.926-.786.333-.401.683-.927 1.021-1.51a11.7 11.7 0 0 1 1.997-.406c.3.383.61.713.91.95.28.22.603.403.934.417a.86.86 0 0 0 .51-.138c.155-.101.27-.247.354-.416.09-.181.145-.37.138-.563a.84.84 0 0 0-.2-.518c-.226-.27-.596-.4-.96-.465a5.8 5.8 0 0 0-1.335-.05 11 11 0 0 1-.98-1.686c.25-.66.437-1.284.52-1.794.036-.218.055-.426.048-.614a1.24 1.24 0 0 0-.127-.538.7.7 0 0 0-.477-.365c-.202-.043-.41 0-.601.077-.377.15-.576.47-.651.823-.073.34-.04.736.046 1.136.088.406.238.848.43 1.295a20 20 0 0 1-1.062 2.227 7.7 7.7 0 0 0-1.482.645c-.37.22-.699.48-.897.787-.21.326-.275.714-.08 1.103"/>
                </svg>')

                ->class('btn btn-red')
                ->tooltip(' COMPROBANTE PDF')
                ->dispatch('pdf', ['rowId' => $row->id])
                ->can(allowed: auth()->user()->can('comprobante_pdf.aportes_voluntarios')),

            Button::add('verCopia')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-pdf-fill" viewBox="0 0 16 16">
                    <path d="M5.523 12.424q.21-.124.459-.238a8 8 0 0 1-.45.606c-.28.337-.498.516-.635.572l-.035.012a.3.3 0 0 1-.026-.044c-.056-.11-.054-.216.04-.36.106-.165.319-.354.647-.548m2.455-1.647q-.178.037-.356.078a21 21 0 0 0 .5-1.05 12 12 0 0 0 .51.858q-.326.048-.654.114m2.525.939a4 4 0 0 1-.435-.41q.344.007.612.054c.317.057.466.147.518.209a.1.1 0 0 1 .026.064.44.44 0 0 1-.06.2.3.3 0 0 1-.094.124.1.1 0 0 1-.069.015c-.09-.003-.258-.066-.498-.256M8.278 6.97c-.04.244-.108.524-.2.829a5 5 0 0 1-.089-.346c-.076-.353-.087-.63-.046-.822.038-.177.11-.248.196-.283a.5.5 0 0 1 .145-.04c.013.03.028.092.032.198q.008.183-.038.465z"/>
                    <path fill-rule="evenodd" d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2m5.5 1.5v2a1 1 0 0 0 1 1h2zM4.165 13.668c.09.18.23.343.438.419.207.075.412.04.58-.03.318-.13.635-.436.926-.786.333-.401.683-.927 1.021-1.51a11.7 11.7 0 0 1 1.997-.406c.3.383.61.713.91.95.28.22.603.403.934.417a.86.86 0 0 0 .51-.138c.155-.101.27-.247.354-.416.09-.181.145-.37.138-.563a.84.84 0 0 0-.2-.518c-.226-.27-.596-.4-.96-.465a5.8 5.8 0 0 0-1.335-.05 11 11 0 0 1-.98-1.686c.25-.66.437-1.284.52-1.794.036-.218.055-.426.048-.614a1.24 1.24 0 0 0-.127-.538.7.7 0 0 0-.477-.365c-.202-.043-.41 0-.601.077-.377.15-.576.47-.651.823-.073.34-.04.736.046 1.136.088.406.238.848.43 1.295a20 20 0 0 1-1.062 2.227 7.7 7.7 0 0 0-1.482.645c-.37.22-.699.48-.897.787-.21.326-.275.714-.08 1.103"/>
                </svg>')
                ->class('btn btn-blue')
                ->tooltip('COMPROBANTE PDF COPIA')
                ->dispatch('verCopia', ['rowId' => $row->id])
                ->can(allowed: auth()->user()->can('comprobante_pdf_copia.aportes_voluntarios')),


        ];
    }
}
