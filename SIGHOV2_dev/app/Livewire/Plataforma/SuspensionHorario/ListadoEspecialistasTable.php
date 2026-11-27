<?php

namespace App\Livewire\Plataforma\SuspensionHorario;

use App\Models\Plataforma\Especialista;
use App\Models\Plataforma\SuspensionVacacion;
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

final class ListadoEspecialistasTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'EspecialistasTable';
    public string $sortField = 'especialista.id';
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
        // USANDO DB aprovevho es mas directo jiji
        /*$ultimaVacacion = SuspensionVacacion::query()
            ->select('id_especialista', \DB::raw('MAX(id) as max_id'))
            ->groupBy('id_especialista');*/

        return Especialista::query()
            ->join('administracion.persona as persona', function ($persona) {
                $persona->on('persona.id', '=', 'especialista.id_persona');
            })
            ->join('administracion.especialidad as especialidad', function ($especialidad) {
                $especialidad->on('especialidad.id', '=', 'especialista.id_especialidad');
            })
            /*->leftJoinSub($ultimaVacacion, 'ultima_vacacion', function ($join) {
                $join->on('especialista.id', '=', 'ultima_vacacion.id_especialista');
            })
            ->leftJoin('plataforma.suspension_vacacion as vacacion', function ($vacacion) {
                $vacacion->on('vacacion.id', '=', 'ultima_vacacion.max_id')
                    ->where('vacacion.estado', '=', 'VACACION ACTIVA');
            })
            ->whereNull('vacacion.id')*/
            ->select(
                'especialista.*',
                'persona.nombres as persona_nombres',
                'persona.p_apellido as persona_p_apellido',
                'persona.s_apellido as persona_s_apellido',
                'persona.ci as persona_ci',
                'persona.complemento as persona_complemento',
                'especialidad.especialidad as nombre_especialidad'
            );
    }


    public function relationSearch(): array
    {
        return [
            'personaEspecialista' => ['nombres', 'p_apellido', 's_apellido', 'ci'],
            'especialidadEspecialista' => ['especialidad']
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('fecha_contrato_inicio_formatted', fn(Especialista $model) => Carbon::parse($model->fecha_contrato_inicio)->format('d/m/Y'))
            ->add('fecha_contrato_fin_formatted', fn(Especialista $model) => Carbon::parse($model->fecha_contrato_fin)->format('d/m/Y'))
            ->add('foto')
            ->add('permanente')
            ->add('persona_ci')
            ->add('persona_ci_formatted', function ($carnet) {
                if ($carnet->persona_complemento !== null) {
                    return $carnet->persona_ci . ' - ' . $carnet->persona_complemento;
                } else {
                    return $carnet->persona_ci;
                }
            })
            ->add('estado')
            ->add('estado', function ($dish) {
                if ($dish->estado) {
                    return '<span title="HABILITADO"> <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="green" class="bi bi-check-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                        <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
                        </svg></span>';
                } else {
                    return '<span title="DESHABILITADO" ><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="red" class="bi bi-x-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                        </svg></span>';
                }
            })
            ->add('id_especialidad')
            ->add('id_persona')
            ->add('persona_nombre_completo', function ($nombrePersona) {
                if ($nombrePersona->persona_p_apellido == null) {
                    return $nombrePersona->persona_s_apellido . ' ' . $nombrePersona->persona_nombres;
                } elseif ($nombrePersona->persona_s_apellido == null) {
                    return $nombrePersona->persona_p_apellido . ' ' . $nombrePersona->persona_nombres;
                } else {
                    return $nombrePersona->persona_p_apellido . ' ' . $nombrePersona->persona_s_apellido . ' ' . $nombrePersona->persona_nombres;
                }
            })
            ->add('grado_academico')
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

            Column::make('ESPECIALISTA', 'persona_nombre_completo', 'persona_p_apellido')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')

                ->sortable()
                ->searchable(),

            Column::make('CARNET', 'persona_ci_formatted', 'persona_ci')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')

                ->sortable()
                ->searchable(),

            Column::make('ESPECIALIDAD', 'nombre_especialidad')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')

                ->sortable()
                ->searchable(),


            Column::make('ESTADO', 'estado')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),


            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center'),

        ];
    }

    public function filters(): array
    {
        return [];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions(Especialista $row): array
    {
        return [

            Button::add('validar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-calendar2-x" viewBox="0 0 16 16">
            <path d="M6.146 8.146a.5.5 0 0 1 .708 0L8 9.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 10l1.147 1.146a.5.5 0 0 1-.708.708L8 10.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 10 6.146 8.854a.5.5 0 0 1 0-.708"/>
            <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M2 2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1z"/>
            <path d="M2.5 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H3a.5.5 0 0 1-.5-.5z"/>
            </svg>')
                ->class('btn btn-blue')
                ->route('plataforma.SuspensionHorario.create', ['id' => $row->id])
                ->tooltip('REGISTRAR SUSPENSIÓN DE HORARIO')
        ];
    }
}
