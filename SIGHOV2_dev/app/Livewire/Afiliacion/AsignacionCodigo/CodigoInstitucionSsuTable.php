<?php

namespace App\Livewire\Afiliacion\AsignacionCodigo;

use App\Models\Aportes\Institucion;
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

final class CodigoInstitucionSsuTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'AsignacionCodigoAfiliacion';
    public string $sortField = 'institucion.id';
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
        return Institucion::query()
            ->join('aportes.clase_aportante', 'institucion.id_clase_aportante', '=', 'clase_aportante.id')
            ->leftjoin('afiliacion.codigo_institucion_ssu', 'institucion.id', '=', 'codigo_institucion_ssu.id_institucion')
            ->select('institucion.*', 'clase_aportante.tipo as claseaportante_tipo', 'codigo_institucion_ssu.tipo_beneficiario as tipo_ben', 'codigo_institucion_ssu.tipo_titular as tipo_tit', 'codigo_institucion_ssu.codigo as codigo_ssu');
            //->whereNot('institucion.nombre', 'UNIVERSIDAD MAYOR DE SAN ANDRES');
    }

    public function relationSearch(): array
    {
        return [
            'claseAportanteInstitucion' => ['tipo'],
            'codigosInstitucion' => ['codigo'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('nit')
            ->add('nro_empleador')
            ->add('actividad_empleador')
            ->add('claseaportante_tipo')
            ->add('acronimo')
            ->add('codigo_ssu')
            ->add('tipo_tit')
            ->add('tipo_ben')
            ->add('nombre')
            ->add('estado', function ($dish) {
                if ($dish->estado) {
                    return "<span style='color: green'><b>ACTIVO</b></span>";
                } else {
                    return "<span style='color:red'><b>INACTIVO</b></span>";
                }
            })
            ->add('tgn')
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('id_usuario_responsable')
            ->add('estado_validado')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')->index()
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center'),

            Column::make('NOMBRE DE INSTITUCIÓN', 'nombre')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->sortable()
                ->searchable(),

            Column::make('ACTIVIDAD DE EMPLEADOR', 'actividad_empleador')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('CLASE APORTANTE', 'claseaportante_tipo')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('ESTADO', 'estado')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('CODIGO', 'codigo_ssu')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('TIPO TITULAR', 'tipo_tit')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('TIPO BENEFICIARIO', 'tipo_ben')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')

        ];
    }

    public function filters(): array
    {
        return [
            //Filter::datepicker('fecha_afiliacion'),
        ];
    }




    public function actions(Institucion $row): array
    {
        $buttons = [];

        $buttons[] = Button::add('ver')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
            </svg>')
            ->class('btn btn-blue')
            ->tooltip('VER')
            ->dispatchTo('afiliacion.asignacion-codigo.codigo-institucion-ssu-main', 'verDetalle', ['data' => $row->id])
            ->can(allowed: auth()->user()->can('ver.codigo_institucional'));

        // Button::add('view')
        // ->slot('View')
        // ->class('btn btn-primary')
        // ->openModal('afiliacion.asignacioncodigo.codigo-institucion-ssu-main',['rowID' => 1212]),

        if ($row->codigo_ssu == null) {
            $buttons[] =  Button::add('asignarcodigo')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-building-fill-add" viewBox="0 0 16 16">
                <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0"/>
                <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v7.256A4.5 4.5 0 0 0 12.5 8a4.5 4.5 0 0 0-3.59 1.787A.5.5 0 0 0 9 9.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .39-.187A4.5 4.5 0 0 0 8.027 12H6.5a.5.5 0 0 0-.5.5V16H3a1 1 0 0 1-1-1zm2 1.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5m3 0v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5m3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM4 5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5M7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm2.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5M4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z"/>
                </svg>')
                ->id()
                ->class('btn btn-orange')
                ->tooltip('ASIGNAR CÓDIGO')
                ->dispatchTo('afiliacion.asignacion-codigo.codigo-institucion-ssu-main', 'asignarCodigoVista', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('asignar.codigo_institucional'));
        } else {
            $buttons[] = Button::add('eliminar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
            <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
            </svg>')
                ->id()
                ->class('btn btn-red')
                ->tooltip('ELIMINAR CODIGO')
                ->dispatchTo('afiliacion.asignacion-codigo.codigo-institucion-ssu-main', 'eliminarAsignacion', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('eliminar.codigo_institucional'));
        }


        return $buttons;
    }
}
