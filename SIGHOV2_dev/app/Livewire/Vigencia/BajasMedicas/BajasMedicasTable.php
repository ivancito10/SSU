<?php

namespace App\Livewire\Vigencia\BajasMedicas;

use App\Models\Vigencia\BajaMedica;
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

final class BajasMedicasTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'BajaMedicaTable';
   // public string $sortField = 'baja_medica.id';
    public string $sortField = 'baja_medica.fecha_inicio';
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
        //dd(BajaMedica::all()); // Para ver si se están recuperando los registros.

        return BajaMedica::query()
            ->leftJoin('administracion.persona as persona', function ($persona) {
                $persona->on('persona.id', '=', 'vvdd.baja_medica.id_persona');
            })
            ->select(
                'baja_medica.*',
                'persona.nombres as persona_nombres',
                'persona.p_apellido as persona_p_apellido',
                'persona.s_apellido as persona_s_apellido',
                'persona.ci as persona_ci',
                'persona.complemento as persona_complemento',
                'persona.matricula_seguro as persona_matricula',
            );
    }

    public function relationSearch(): array
    {
        return [
            'personaBajaMedica' => ['nombres', 'p_apellido', 's_apellido', 'ci', 'matricula_seguro'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('id_persona')
            ->add('matricula_persona')
            ->add('ci_persona')
            ->add('persona_ci_formatted', function ($persona) {
                if ($persona->id_persona != null) {
                    if ($persona->persona_complemento == null) {
                        return $persona->persona_ci;
                    } else {
                        return $persona->persona_ci . ' - ' . $persona->persona_complemento;
                    }
                } else {
                    if($persona->complemento_persona == null){
                        return $persona->ci_persona;
                    } else {
                        return $persona->ci_persona. ' - ' .$persona->complemento_persona;
                    }
                }
            })
            ->add('matricula_seguro_formatted', function ($personaMatricula){
                if($personaMatricula->id_persona != null){
                    return $personaMatricula->persona_matricula;
                } else {
                    return $personaMatricula->matricula_persona;
                }
            })
            ->add('persona_nombres')
            ->add('persona_nombre_completo', function ($nombrePersona) {
                if ($nombrePersona->id_persona != null) {
                    if ($nombrePersona->persona_p_apellido == null) {
                        return $nombrePersona->persona_s_apellido . ' ' . $nombrePersona->persona_nombres;
                    } elseif ($nombrePersona->persona_s_apellido == null) {
                        return $nombrePersona->persona_p_apellido . ' ' . $nombrePersona->persona_nombres;
                    } else {
                        return $nombrePersona->persona_p_apellido . ' ' . $nombrePersona->persona_s_apellido . ' ' . $nombrePersona->persona_nombres;
                    }
                } else {
                    return $nombrePersona->nombre_persona;
                }
            })
            ->add('persona_p_apellido')
            ->add('cod_baja_medica')
            ->add('fecha_recepcion')
            ->add('fecha_inicio')
            ->add('fecha_inicio_formatted', fn(BajaMedica $model) => Carbon::parse($model->fecha_inicio)->format('d/m/Y'))
            ->add('fecha_fin')
            ->add('fecha_fin_formatted', fn(BajaMedica $model) => Carbon::parse($model->fecha_fin)->format('d/m/Y'))
            ->add('estado_validacion')

            ->add('estado_validacion', function ($solicitud) {
                if ($solicitud->estado_validacion == 'PENDIENTE') {
                    return '<span style="color: #F57C00 ;" title="PENDIENTE">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                    </svg>
                    </span>';
                }
                if ($solicitud->estado_validacion == 'VALIDADO') {
                    return '<span style="color: green ;" title="VALIDADO">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-check-square-fill" viewBox="0 0 16 16">
                    <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm10.03 4.97a.75.75 0 0 1 .011 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.75.75 0 0 1 1.08-.022z"/>
                    </svg>
                    </span>';
                }
                if ($solicitud->estado_validacion == 'HOMOLOGADO') {
                    return '<span style="color:#21618C ;" title="HOMOLOGADO">
               <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-award-fill" viewBox="0 0 16 16">
                    <path d="m8 0 1.669.864 1.858.282.842 1.68 1.337 1.32L13.4 6l.306 1.854-1.337 1.32-.842 1.68-1.858.282L8 12l-1.669-.864-1.858-.282-.842-1.68-1.337-1.32L2.6 6l-.306-1.854 1.337-1.32.842-1.68L6.331.864z"/>
                    <path d="M4 11.794V16l4-1 4 1v-4.206l-2.018.306L8 13.126 6.018 12.1z"/>
                  </svg>
                </span>';
                }
                if ($solicitud->estado_validacion == 'ANULADO') {
                    return '<span style="color: red ;"  title="ANULADO">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-x-square-fill" viewBox="0 0 16 16">
                <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708"/>
                </svg>
                    </span>';
                }
            })
            ->add('id_persona_entregado')
            ->add('id_baja_medica_s')
            ->add('id_especialidad')
            ->add('id_especialista')
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->index(),

            Column::make('COD. BAJA MÉDICA', 'cod_baja_medica')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('ASEGURADO', 'persona_nombre_completo', 'nombre_persona')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                //->bodyAttribute('text-center')
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

            Column::make('CARNET', 'persona_ci_formatted', 'persona_ci')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('MATRICULA', 'matricula_seguro_formatted', 'persona_matricula')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('X', 'nombre_persona')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable()
                ->hidden(isHidden: true, isForceHidden: true),

            Column::make('Y', 'ci_persona')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable()
                ->hidden(isHidden: true, isForceHidden: true),

            Column::make('Z', 'matricula_persona')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable()
                ->hidden(isHidden: true, isForceHidden: true),


            Column::make('FECHA INICIO',  'fecha_inicio_formatted', 'fecha_inicio')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchableRaw("TO_CHAR(fecha_inicio, 'dd/mm/yyyy')"),

            Column::make('FECHA FIN', 'fecha_fin_formatted',  'fecha_fin')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchableRaw("TO_CHAR(fecha_fin, 'dd/mm/yyyy')"),


            Column::make('ESTADO VALIDACIÓN', 'estado_validacion')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')

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

    public function actions(BajaMedica $row): array
    {
        $buttons = [];

        $buttons[] = Button::add('ver')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
            </svg>')
            ->id()
            ->class('btn btn-blue')
            ->tooltip('VER')
            ->dispatchTo('vigencia.bajas-medicas.bajas-medicas-main', 'verBajaMedica', ['data' => $row->id])
            ->can(allowed: auth()->user()->can('ver.baja_medica_hologacion'));


        // Controlador de botones por estado
        if ($row->estado_validacion === 'PENDIENTE') {
            $buttons[] = Button::add('validar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16">
                        <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0"/>
                        </svg>')
                ->id()
                ->class('btn btn-green')
                ->tooltip('VALIDAR')
                ->dispatchTo('vigencia.bajas-medicas.bajas-medicas-main', 'validarBajaMedica', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('validar.baja_medica_hologacion'));

            $buttons[] = Button::add('anular')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-diamond" viewBox="0 0 16 16">
            <path d="M6.95.435c.58-.58 1.52-.58 2.1 0l6.515 6.516c.58.58.58 1.519 0 2.098L9.05 15.565c-.58.58-1.519.58-2.098 0L.435 9.05a1.48 1.48 0 0 1 0-2.098zm1.4.7a.495.495 0 0 0-.7 0L1.134 7.65a.495.495 0 0 0 0 .7l6.516 6.516a.495.495 0 0 0 .7 0l6.516-6.516a.495.495 0 0 0 0-.7L8.35 1.134z"/>
            <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
            </svg>')
                ->id()
                ->class('btn btn-red')
                ->tooltip('ANULAR')
                ->dispatchTo('vigencia.bajas-medicas.bajas-medicas-main', 'anularBajaMedica', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('anular.baja_medica_hologacion'));
        }
        if ($row->estado_validacion === 'VALIDADO') {
            $buttons[] = Button::add('entregar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                        <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                        </svg>')
                ->id()
                ->class('btn btn-orange')
                ->tooltip('ENTREGAR BAJA MÉDICA')
                ->dispatchTo('vigencia.bajas-medicas.bajas-medicas-main', 'entregarBajaMedica', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('anular.baja_medica_hologacion'));
        }

        return $buttons;
    }
}
