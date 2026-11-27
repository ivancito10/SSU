<?php

namespace App\Livewire\Aportes\Externos\AvisoBaja;

use App\Models\Aportes\AvisoBajaInstitucional;
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
use App\Models\Aportes\Institucion;

final class AvisoBajaTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'AvisoBajaTable';
    public string $sortField = 'aviso_baja_institucional.id';
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
        $institucion_user = Institucion::where('estado_validado', '=', true)
            ->where('id_usuario_aviso_baja', '=', auth()->user()->id)
            ->first();

        if ($institucion_user) {
            $id_institucion = $institucion_user->id; // Variable local para consistencia

            return AvisoBajaInstitucional::query()
                ->join('administracion.persona as p', function ($persona) {
                    $persona->on('aportes.aviso_baja_institucional.id_persona', 'p.id');
                })
                ->where('aportes.aviso_baja_institucional.id_institucion', $id_institucion)
                ->select(
                    'aportes.aviso_baja_institucional.*',
                    'p.nombres as persona_nombres',
                    'p.p_apellido as persona_p_apellido',
                    'p.s_apellido as persona_s_apellido'
                );
        } else {
            // Manejar el caso en que no se encuentra la institución del usuario
            return AvisoBajaInstitucional::query()->where('id_institucion', null); // Devuelve una consulta vacía o manejarlo de otra manera.
        }
    }

    public function relationSearch(): array
    {
        return [
            'personaAportante' => ['nombres', 'p_apellido', 's_apellido'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('id_persona')
            ->add('persona_nombre_completo', function ($nombre) {
                if ($nombre->persona_p_apellido == null) {
                    return $nombre->persona_s_apellido . ' ' . $nombre->persona_nombres;
                } elseif ($nombre->persona_s_apellido == null) {
                    return $nombre->persona_p_apellido . ' ' . $nombre->persona_nombres;
                } else {
                    return $nombre->persona_p_apellido . ' ' . $nombre->persona_s_apellido . ' ' . $nombre->persona_nombres;
                }
            })
            ->add('fecha_baja', fn(AvisoBajaInstitucional $model) => Carbon::parse($model->fecha_baja)->format('d/m/Y'))
            ->add('motivo_baja')
            ->add('ultimo_salario')
            ->add('fecha_solicitud', fn(AvisoBajaInstitucional $model) => Carbon::parse($model->fecha_solicitud)->format('d/m/Y'))
            ->add(
                'fecha_validacion',
                fn(AvisoBajaInstitucional $model) => $model->fecha_validacion ? Carbon::parse($model->fecha_validacion)->format('d/m/Y') : ''
            )
            ->add('id_usuario_valida')
            ->add('descripcion')
            ->add('id_user_updated')
            ->add('created_at')
            ->add('updated_at')
            ->add('estado_valida', function ($estado) {
                if ($estado->estado_valida === true) {
                    return '<span> <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="green" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </svg></span>';
                } elseif ($estado->estado_valida === false) {
                    return '<span style="color: orange"> 
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-exclamation-diamond" viewBox="0 0 16 16">
                    <path d="M6.95.435c.58-.58 1.52-.58 2.1 0l6.515 6.516c.58.58.58 1.519 0 2.098L9.05 15.565c-.58.58-1.519.58-2.098 0L.435 9.05a1.48 1.48 0 0 1 0-2.098zm1.4.7a.495.495 0 0 0-.7 0L1.134 7.65a.495.495 0 0 0 0 .7l6.516 6.516a.495.495 0 0 0 .7 0l6.516-6.516a.495.495 0 0 0 0-.7L8.35 1.134z"/>
                    <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                    </svg>
                    </span>';
                } elseif ($estado->estado_valida === null) {
                    return '<span style="color: #c0392b;"> ANULADO </span>';
                }
            })
            ->add('anulado')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->index()
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable(),
            Column::make('NOMBRE COMPLETO', 'persona_nombre_completo')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),

            Column::make('FECHA BAJA', 'fecha_baja', 'fecha_baja')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),
            Column::make('ÚLTIMO SALARIO', 'ultimo_salario')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-left')
                ->searchable(),

            Column::make('MOTIVO BAJA', 'motivo_baja')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-left')
                ->searchable(),

            Column::make('FECHA SOLICITUD', 'fecha_solicitud', 'fecha_solicitud')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),
            Column::make('FECHA VALIDACIÓN', 'fecha_validacion')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('ESTADO VALIDADO', 'estado_valida')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-left')
        ];
    }

    public function filters(): array
    {
        return [
            // Filter::datepicker('fecha_baja'),
            // Filter::datepicker('fecha_solicitud'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions(AvisoBajaInstitucional $row): array
    {
        $buttons = [];
        $buttons[] = Button::add('ver')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                </svg>')
            ->class('btn btn-blue')
            ->tooltip('VER AVISO BAJA INSTITUCIONAL')
            ->dispatchTo('aportes.externos.aviso-baja.aviso-baja-main', 'abrirRegistro', ['data' => $row->id])
            ->can(allowed: auth()->user()->can('ver.aviso_baja'));



        if ($row->anulado == null ||  $row->anulado == false) {
            $buttons[] = Button::add('verpdf')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-pdf-fill" viewBox="0 0 16 16">
                <path d="M5.523 12.424q.21-.124.459-.238a8 8 0 0 1-.45.606c-.28.337-.498.516-.635.572l-.035.012a.3.3 0 0 1-.026-.044c-.056-.11-.054-.216.04-.36.106-.165.319-.354.647-.548m2.455-1.647q-.178.037-.356.078a21 21 0 0 0 .5-1.05 12 12 0 0 0 .51.858q-.326.048-.654.114m2.525.939a4 4 0 0 1-.435-.41q.344.007.612.054c.317.057.466.147.518.209a.1.1 0 0 1 .026.064.44.44 0 0 1-.06.2.3.3 0 0 1-.094.124.1.1 0 0 1-.069.015c-.09-.003-.258-.066-.498-.256M8.278 6.97c-.04.244-.108.524-.2.829a5 5 0 0 1-.089-.346c-.076-.353-.087-.63-.046-.822.038-.177.11-.248.196-.283a.5.5 0 0 1 .145-.04c.013.03.028.092.032.198q.008.183-.038.465z"/>
                <path fill-rule="evenodd" d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2m5.5 1.5v2a1 1 0 0 0 1 1h2zM4.165 13.668c.09.18.23.343.438.419.207.075.412.04.58-.03.318-.13.635-.436.926-.786.333-.401.683-.927 1.021-1.51a11.7 11.7 0 0 1 1.997-.406c.3.383.61.713.91.95.28.22.603.403.934.417a.86.86 0 0 0 .51-.138c.155-.101.27-.247.354-.416.09-.181.145-.37.138-.563a.84.84 0 0 0-.2-.518c-.226-.27-.596-.4-.96-.465a5.8 5.8 0 0 0-1.335-.05 11 11 0 0 1-.98-1.686c.25-.66.437-1.284.52-1.794.036-.218.055-.426.048-.614a1.24 1.24 0 0 0-.127-.538.7.7 0 0 0-.477-.365c-.202-.043-.41 0-.601.077-.377.15-.576.47-.651.823-.073.34-.04.736.046 1.136.088.406.238.848.43 1.295a20 20 0 0 1-1.062 2.227 7.7 7.7 0 0 0-1.482.645c-.37.22-.699.48-.897.787-.21.326-.275.714-.08 1.103"/>
                </svg>')
                ->class('btn btn-teal')
                ->tooltip('VER PDF AVISO BAJA INSTITUCIONAL')
                ->dispatchTo('aportes.externos.aviso-baja.aviso-baja-main', 'abrirPdfBaja', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('ver_pdf.aviso_baja'));
        }
        if ($row->estado_valida == false && $row->anulado == null) {

            $buttons[] = Button::add('anular')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                </svg>')
                ->class('btn btn-red')
                ->tooltip('ANULAR AVISO BAJA INSTITUCIONAL')
                ->dispatchTo('aportes.externos.aviso-baja.aviso-baja-main', 'anularRegistro', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('anular.aviso_baja'));
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
