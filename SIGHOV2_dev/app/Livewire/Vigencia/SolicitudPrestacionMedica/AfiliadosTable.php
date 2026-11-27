<?php

namespace App\Livewire\Vigencia\SolicitudPrestacionMedica;

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

final class AfiliadosTable extends PowerGridComponent
{
    use WithExport;
    public string $sortField = 'persona.id';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        return [
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Persona::leftJoin('afiliacion.titular as tit', 'persona.id', '=', 'tit.id_persona')
            ->leftJoin('afiliacion.beneficiario as ben', 'persona.id', '=', 'ben.id_persona')
            ->join('administracion.tipo_asegurado as ta', 'persona.id_tipo_asegurado', '=', 'ta.id')
            ->select('persona.*', 'ta.tipo_asegurado as tipoAsegurado')
            ->distinct()
            ->where('persona.afiliado', true)
            ->where(function($query) {
                $query->whereIn('tit.estado_vigencia', ['VIGENTE','CESANTE'])
                      ->orWhereIn('ben.vigencia', ['VIGENTE', 'CESANTE']);
            })
            ->whereNotIn('persona.id_tipo_asegurado', ['0', '8', '9', '10', '11']);
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
            ->add('complemento')
            ->add('nombres')
            ->add('nombreCompleto', fn (Persona $model) => $model->p_apellido . ' ' . $model->s_apellido . ' ' . $model->nombres)
            ->add('p_apellido')
            ->add('s_apellido')
            ->add('tipoAsegurado')
            ->add('sexo')
            ->add('id_estado_civil')
            ->add('id_nacimiento_municipio')
            ->add('id_dept_exp')
            ->add('fecha_nacimiento_formatted', fn (Persona $model) => Carbon::parse($model->fecha_nacimiento)->format('d/m/Y'))
            ->add('matricula_seguro')
            ->add('id_tipo_asegurado')
            ->add('es_extranjero')
            ->add('estado_asuss')
            ->add('afiliado')
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')->index()
            ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center'),

            Column::make('NOMBRE COMPLETO', 'nombreCompleto', 'p_apellido')
            ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->sortable()
                ->searchableRaw("
                TRIM(CONCAT(
                    COALESCE(persona.p_apellido, ''), 
                    ' ', 
                    COALESCE(persona.s_apellido, ''), 
                    ' ', 
                    COALESCE(persona.nombres, '')
                ))
            "),

            Column::make('NOMBRES', 'nombres')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->sortable()
                ->searchable()
                ->hidden(isHidden: true, isForceHidden: true),
            
            Column::make('P_APELLIDO', 'p_apellido')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->sortable()
                ->searchable()
                ->hidden(isHidden: true, isForceHidden: true),

            Column::make('S_APELLIDO', 's_apellido')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->sortable()
                ->searchable()
                ->hidden(isHidden: true, isForceHidden: true),
                
            Column::make('MATRICULA', 'matricula_seguro')
            ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('TIPO ASEGURADO', 'tipoAsegurado')
            ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                
                ->sortable()
                ->searchable(),

            Column::action('OPCIONES')
            ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
        ];
    }

    public function filters(): array
    {
        return [
        ];
    }

    public function actions(Persona $row): array
    {
        return [
            Button::add('view')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus-fill" viewBox="0 0 16 16">
                <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5"/>
                </svg>')
                ->class('btn btn-limon')
                ->route('vigencia.SolicitudesPrestacionesMedicas.create', ['id' => $row->id])
                ->tooltip('AGREGAR'),
                
        ];
    }
}
