<?php

namespace App\Livewire\Plataforma\Especialista;

use App\Models\Plataforma\Especialista;
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

final class EspecialistaTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'EspecialistaTable';
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
        return Especialista::query()
            ->join('administracion.persona as persona', function ($persona) {
                $persona->on('persona.id', '=', 'especialista.id_persona');
            })
            ->join('administracion.especialidad as especialidad', function ($especialidad) {
                $especialidad->on('especialidad.id', '=', 'especialista.id_especialidad');
            })
            ->select(
                'especialista.*',
                'persona.nombres as persona_nombres',
                'persona.p_apellido as persona_p_apellido',
                'persona.s_apellido as persona_s_apellido',
                'persona.ci as persona_ci',
                'persona.complemento as persona_complemento',
                'persona.matricula_seguro as persona_matricula_seguro',
                'especialidad.especialidad as nombre_especialidad'
            );
        //  ->join('administracion.persona', 'especialista.id_persona', '=', 'persona.id')
        //  ->join('administracion.especialidad', 'especialista.id_especialidad', '=', 'especialidad.id')
        //  ->select('especialista.*', 'especialidad.especialidad as nombre_especialidad', 'persona.nombres as persona_nombres', 'persona.p_apellido as persona_p_apellido', 'persona.s_apellido as persona_s_apellido')
        //  ->orderBy('id', 'desc');
    }

    public function relationSearch(): array
    {
        return [

            'personaEspecialista' => ['nombres', 'p_apellido', 's_apellido', 'ci', 'matricula_seguro'],
            'especialidadEspecialista' => ['especialidad']
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('fecha_contrato_inicio_formatted', fn(Especialista $model) => $model->fecha_contrato_inicio ? Carbon::parse($model->fecha_contrato_inicio)->format('d/m/Y') : 'SIN FECHA')
            ->add('fecha_contrato_fin_formatted', fn(Especialista $model) => $model->fecha_contrato_fin ? Carbon::parse($model->fecha_contrato_fin)->format('d/m/Y') : 'SIN FECHA')
            ->add('foto')
            ->add('nombre_especialidad')
            ->add('permanente', fn(Especialista $model) => $model->permanente ? 'PERMANENTE' : 'EVENTUAL')
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
            ->add('especialista_imagenologia', function ($dish) {
                if ($dish->especialista_imagenologia) {
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
            ->add('persona_nombres')
            //->add('persona_nombre_completo', fn(Especialista $model) => $model->personaEspecialista->p_apellido . ' ' . $model->personaEspecialista->s_apellido. ' ' . $model->personaEspecialista->nombres)
            ->add('persona_nombre_completo', function ($nombrePersona) {
                if ($nombrePersona->persona_p_apellido == null) {
                    return $nombrePersona->persona_s_apellido . ' ' . $nombrePersona->persona_nombres;
                } elseif ($nombrePersona->persona_s_apellido == null) {
                    return $nombrePersona->persona_p_apellido . ' ' . $nombrePersona->persona_nombres;
                } else {
                    return $nombrePersona->persona_p_apellido . ' ' . $nombrePersona->persona_s_apellido . ' ' . $nombrePersona->persona_nombres;
                }
            })
            ->add('carnet', function ($carnet) {
                $carnetPersona = $carnet->persona_ci;
                if ($carnet->persona_complemento !== null) {
                    $carnetPersona .= ' - ' . $carnet->persona_complemento;
                }

                return $carnetPersona;
            })
            ->add('persona_matricula_seguro')
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->index()
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center'),

            Column::make('ESPECIALISTA', 'persona_nombre_completo', 'persona_p_apellido')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('CARNET', 'carnet', 'persona_ci')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('MATRICULA', 'persona_matricula_seguro')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('ESPECIALIDAD', 'nombre_especialidad')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('TIPO DE CONTRATO', 'permanente')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('CONTRATO DESDE', 'fecha_contrato_inicio_formatted', 'fecha_contrato_inicio')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->searchable(function ($query, $buscaDate) {
                    try {
                        $buscaDate = Carbon::createFromFormat('d/m/Y', $buscaDate)->format('Y-m-d');
                        $query->whereDate('especialista.fecha_contrato_inicio', $buscaDate);
                    } catch (\Exception $e) {
                        $query->where('especialista.fecha_contrato_inicio', 'like', "%{$buscaDate}%");
                    }
                }),


            Column::make('CONTRATO HASTA', 'fecha_contrato_fin_formatted', 'fecha_contrato_fin')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->searchable(function ($query, $buscaDate) {
                    try {
                        $buscaDate = Carbon::createFromFormat('d/m/Y', $buscaDate)->format('Y-m-d');
                        $query->whereDate('especialista.fecha_contrato_inicio', $buscaDate);
                    } catch (\Exception $e) {
                        $query->where('especialista.fecha_contrato_inicio', 'like', "%{$buscaDate}%");
                    }
                }),


            Column::make('ESTADO', 'estado')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('IMAGENOLOGÍA', 'especialista_imagenologia')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')

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
        $buttons = [];

        if ($row->estado === true) {
            $buttons[] = Button::add('ver')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                    <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                    </svg>')
                ->class('btn btn-blue')
                ->tooltip('VER ESPECIALISTA')
                ->dispatchTo('plataforma.especialista.especialista-main', 'verEspecialista', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('ver.especialistas'));

            $buttons[] =  Button::add('editar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
            </svg>')
                ->class('btn btn-orange')
                ->route('plataforma.especialistas.editar', ['id' => $row->id])
                ->tooltip('MODIFICAR ESPECIALISTA')
                //->dispatchTo('plataforma.especialista.especialista-main', 'editarEspecialista', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('editar.especialistas'));

            $buttons[] = Button::add('asignar_servicio')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-journal-bookmark" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M6 8V1h1v6.117L8.743 6.07a.5.5 0 0 1 .514 0L11 7.117V1h1v7a.5.5 0 0 1-.757.429L9 7.083 6.757 8.43A.5.5 0 0 1 6 8"/>
            <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2"/>
            <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"/>
            </svg>')
                ->class('btn btn-lila')
                ->route('plataforma.especialistas.servicios', ['id' => $row->id])
                ->tooltip('ASIGNAR SERVICIO')
                ->can(allowed: auth()->user()->can('asignar_servicio.especialistas'));


            // $buttons[] = Button::add('asignar_horario')
            //     ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
            // <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"/>
            // <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0"/>
            // </svg>')
            //     ->class('btn btn-teal')
            //     ->route('plataforma.especialistas.asignar',  ['id' => $row->id])
            //     ->tooltip('ASIGNAR HORARIO')
            //     ->can(allowed: auth()->user()->can('asignar_horario.especialistas'));

            // $buttons[] = Button::add('ver_horario')
            //     ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar2-date" viewBox="0 0 16 16">
            // <path d="M6.445 12.688V7.354h-.633A13 13 0 0 0 4.5 8.16v.695c.375-.257.969-.62 1.258-.777h.012v4.61zm1.188-1.305c.047.64.594 1.406 1.703 1.406 1.258 0 2-1.066 2-2.871 0-1.934-.781-2.668-1.953-2.668-.926 0-1.797.672-1.797 1.809 0 1.16.824 1.77 1.676 1.77.746 0 1.23-.376 1.383-.79h.027c-.004 1.316-.461 2.164-1.305 2.164-.664 0-1.008-.45-1.05-.82zm2.953-2.317c0 .696-.559 1.18-1.184 1.18-.601 0-1.144-.383-1.144-1.2 0-.823.582-1.21 1.168-1.21.633 0 1.16.398 1.16 1.23"/>
            // <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M2 2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1z"/>
            // <path d="M2.5 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H3a.5.5 0 0 1-.5-.5z"/>
            // </svg>')
            //     ->class('btn btn-cafe')
            //     ->dispatchTo('plataforma.especialista.especialista-main', 'actualizarAgenda', ['data' => $row->id])
            //     ->tooltip('VER HORARIOS')
            //     ->can(allowed: auth()->user()->hasRole('Super Admin'));

            $buttons[] = Button::add('deshabilitar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                </svg>')
                ->id()
                ->class('btn btn-red')
                ->tooltip('DESHABILITAR')
                ->dispatchTo('plataforma.especialista.especialista-main', 'cambiarEstado', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('desabilitar.especialistas'));
        } else {
            $buttons[] = Button::add('ver')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                    <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                    </svg>')
                ->class('btn btn-blue')
                ->tooltip('VER SERVICIOS')
                ->dispatchTo('plataforma.especialista.especialista-main', 'verEspecialista', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('ver.especialistas'));

            $buttons[] = Button::add('habilitar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                        <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"/>
                        </svg>')
                ->id()
                ->class('btn btn-green')
                ->tooltip('HABILITAR')
                ->dispatchTo('plataforma.especialista.especialista-main', 'cambiarEstado', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('desabilitar.especialistas'));
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
