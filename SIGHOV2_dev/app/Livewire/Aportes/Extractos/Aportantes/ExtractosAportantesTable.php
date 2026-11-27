<?php

namespace App\Livewire\Aportes\Extractos\Aportantes;

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
use App\Livewire\Aportes\Extractos\Aportantes\ExtractoAportantesMain;
use Illuminate\Support\Collection;

final class ExtractosAportantesTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'extratoPersonasTable';
    public string $sortField = 'persona.id';
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
        return Persona::query()
            ->with('tipoAseguradoPersona')
            ->whereNotIn('persona.id_tipo_asegurado', [8, 9, 10, 11, 14, 15]);
    }

    public function relationSearch(): array
    {
        return [
            'tipoAseguradoPersona' => ['tipo_asegurado'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('ci')
            ->add('carnet', function ($carnet) {
                if ($carnet->complemento == null) {
                    return $carnet->ci;
                } else {
                    return $carnet->ci . '-' . $carnet->complemento;
                }
            })
            ->add('complemento')
            ->add('nombres')
            ->add('p_apellido')
            ->add('s_apellido')
            ->add('nombre_completo', function ($nombre) {
                if ($nombre->p_apellido == null) {
                    return $nombre->s_apellido . ' ' . $nombre->nombres;
                } elseif ($nombre->s_apellido == null) {
                    return $nombre->p_apellido . ' ' . $nombre->nombres;
                } else {
                    return $nombre->p_apellido . ' ' . $nombre->s_apellido . ' ' . $nombre->nombres;
                }
            })
            ->add('fecha_nacimiento')
            ->add('matricula_seguro')
            ->add('id_tipo_asegurado')
            ->add('tipo_asegurado', function (Persona $model) {
                return $model->tipoAseguradoPersona?->tipo_asegurado;
            })
            ->add('es_extranjero')
            ->add('estado_asuss')
            ->add('estado_asuss_formatted', function ($dish) {
                if ($dish->estado_asuss) {
                    return "<span> <b>VALIDADO </b> </span>";
                } else {
                    return "<span> PENDIENTE</span>";
                }
            })
            ->add('afiliado', function ($dow) {
                if ($dow->afiliado) {
                    return "<span> SI </span>";
                } else {
                    return "<span> NO </span>";
                }
            });
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->index(),

            Column::make('NOMBRE COMPLETO', 'nombre_completo')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('PRIMER APELLIDO', 'p_apellido')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->sortable()
                ->searchable()
                ->hidden(isHidden: true, isForceHidden: true),

            Column::make('SEGUNDO APELLIDO', 's_apellido')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->sortable()
                ->searchable()
                ->hidden(isHidden: true, isForceHidden: true),

            Column::make('NOMBRES', 'nombres')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->sortable()
                ->searchable()
                ->hidden(isHidden: true, isForceHidden: true),

            Column::make('CARNET', 'carnet', 'ci')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('TIPO ASEGURADO', 'tipo_asegurado')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),

            Column::make('AFILIADO', 'afiliado')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
            Column::make(' ASSUS (ESTADO)', 'estado_asuss_formatted', 'estado_asuss')
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
            //Filter::datepicker('fecha_nacimiento'),
            // Filter::datepicker('fecha_nacimiento'),
        ];
    }

    #[\Livewire\Attributes\On('verextraxto')]
    public function extracto($rowId, $abrirModalEditarExt): void
    {
        $this->modalextracto = $abrirModalEditarExt;
        $this->aportanteId = $rowId;
        $this->dispatch(
            'verextractoModal',
            ['modalExtractoForm' => $this->modalextracto, 'aportanteId' => $this->aportanteId]
        )
            ->to(ExtractoAportantesMain::class);
    }

    public function actions(Persona $row): array
    {
        return [
            Button::add('verExtracto')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-person-fill-gear" viewBox="0 0 16 16">
                <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0m-9 8c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4m9.886-3.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382zM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0"/>
                </svg>')
                ->id()
                ->class('btn btn-lila')
                ->tooltip('EXTRACTO APORTANTE')
                ->dispatch('verextraxto', ['rowId' => $row->id, 'abrirModalEditarExt' => true])
                ->can(allowed: auth()->user()->can('extracto.extracto_persona')),
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
