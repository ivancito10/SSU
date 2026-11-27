<?php

namespace App\Livewire\Afiliacion\Certificados\AfiliadoSsu;

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
use DB;

final class AfiliadoSsuPersonaTable extends PowerGridComponent
{
    use WithExport;
    public string $sortField = 'persona.id';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        return [
            Exportable::make('export')
                ->striped(),
            //->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Persona::query()
            ->leftJoin('afiliacion.titular as titular', function ($titular) {
                $titular->on('persona.id', '=', 'titular.id_persona')
                    ->where('titular.estado', true);
            })
            ->leftJoin('afiliacion.beneficiario as beneficiario', function ($beneficiario) {
                $beneficiario->on('persona.id', '=', 'beneficiario.id_persona');
                   
            })
            ->leftJoin('afiliacion.estudiante as estudiante', function ($estudiante) {
                $estudiante->on('persona.id', '=', 'estudiante.id_persona')
                ->where('estudiante.estado', true);
                   
            })
            ->leftjoin('administracion.tipo_asegurado as taseg', function ($tipo_asegurado) {
                $tipo_asegurado->on('persona.id_tipo_asegurado', '=', 'taseg.id');
            })
            ->where('persona.afiliado', true)
            ->where(function ($query) {
                $query->where('titular.estado_vigencia', 'VIGENTE')
                    ->orWhere('titular.estado_vigencia', 'CESANTE')
                    ->orWhere('beneficiario.vigencia', 'VIGENTE')
                    ->orWhere('beneficiario.vigencia', 'CESANTE')
                    ->orWhere('estudiante.estado_validar', 'AFILIADO');
            })
            // Nuevas condiciones para tipo_asegurado
            ->where('taseg.tipo_asegurado', 'not like', '%CONVENIO%')
            ->where('taseg.tipo_asegurado', 'not like', '%INTERIOR%')
            ->where('taseg.tipo_asegurado', 'not like', 'NO ASEGURADO')
            ->select('persona.*', 'taseg.tipo_asegurado as tipoAsegurado');


        // ->where('titular.estado_vigencia', 'VIGENTE')
        // ->where('persona.afiliado', true);
    }

    public function relationSearch(): array
    {
        return [
            //  'PersonaTitular' => ['estado_vigencia'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('ci')
            ->add('complemento')
            ->add('ci_formatted', function ($carnet) {
                if ($carnet->complemento == null) {
                    return $carnet->ci;
                } else {
                    return $carnet->ci . ' - ' . $carnet->complemento;
                }
            })
            ->add('nombre_completo', function ($person) {
                if ($person->p_apellido == null) {
                    return $person->s_apellido . ' ' . $person->nombres;
                } elseif ($person->s_apellido == null) {
                    return $person->p_apellido . ' ' . $person->nombres;
                } else {
                    return $person->p_apellido . ' ' . $person->s_apellido . ' ' . $person->nombres;
                }
            })
            ->add('nombres')
            ->add('p_apellido')
            ->add('s_apellido')
            ->add('sexo')
            ->add('id_estado_civil')
            ->add('id_nacimiento_municipio')
            ->add('id_dept_exp')
            ->add('fecha_nacimiento_formatted', fn(Persona $model) => Carbon::parse($model->fecha_nacimiento)->format('d/m/Y'))
            ->add('matricula_seguro')
            ->add('id_tipo_asegurado')
            ->add('es_extranjero')
            ->add('estado_asuss')
            ->add('afiliado', function ($dish) {
                if ($dish->afiliado) {
                    return "<span style='color: green'><b>SI</b></span>";
                } else {
                    return "<span style='color:red'><b>NO</b></span>";
                }
            })
            ->add('vigencia')
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->index()
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('CARNET', 'ci_formatted', 'ci')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
                Column::make('MATRICULA', 'matricula_seguro', 'matricula_seguro')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            /*             Column::make('Comp', 'complemento')
                ->sortable()
                ->searchable(), */
            Column::make('AFILIADO', 'nombre_completo', 'p_apellido')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),

            Column::make('Nombres', 'nombres')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable()
                ->hidden(isHidden: true, isForceHidden: true),

            Column::make('Paterno', 'p_apellido')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable()
                ->hidden(isHidden: true, isForceHidden: true),

            Column::make('Materno', 's_apellido')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable()
                ->hidden(isHidden: true, isForceHidden: true),


            Column::make('TIPO DE ASEGURADO', 'tipoAsegurado')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),




            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')

        ];
    }

    public function filters(): array
    {
        return [];
    }

    public function actions(Persona $row): array
    {
        return [
            Button::add('ver')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
                        </svg>')
                ->class('btn btn-blue')
                ->tooltip('SELECCIONAR ASEGURADO')
                ->dispatchTo('afiliacion.certificados.afiliado-ssu.afiliado-ssu-register', 'registerCertificado', ['data' => $row->id]),
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
