<?php

namespace App\Livewire\Vigencia\OrdenesServicio;

use App\Models\Vigencia\OrdenServicio;
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

final class OrdenesServicioTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'OrdenservicioTable';
    public string $sortField = 'orden_servicio.id';
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

    public function datasource(): Builder{
        return OrdenServicio::query()
        ->join('administracion.persona as persona', function($persona){
            $persona->on('persona.id','=','vvdd.orden_servicio.id_persona');
        })
        ->select(
            'orden_servicio.*',
            'persona.nombres as persona_nombres', 
            'persona.p_apellido as persona_p_apellido', 
            'persona.s_apellido as persona_s_apellido',
            'persona.ci as persona_ci',
            'persona.complemento as persona_complemento',
            'persona.matricula_seguro as persona_matricula_seguro'
        );
    }

    public function relationSearch(): array
    {
        return [
            'personaOrdenServicio'=>['nombres', 'p_apellido', 's_apellido', 'ci', 'matricula_seguro', 'complemento'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('id')
            ->add('nro_orden_servicio')
            ->add('fecha_recepcion_formatted', fn (OrdenServicio $model) => Carbon::parse($model->fecha_recepcion)->format('d/m/Y'))
            ->add('observacion')
            ->add('id_persona')
            //->add('persona_nombre_completo', fn(OrdenServicio $model) => $model->personaOrdenServicio->nombres . ' ' . $model->personaOrdenServicio->p_apellido . ' ' . $model->personaOrdenServicio->s_apellido)
            
            ->add('persona_nombre_completo', function ($nombrePersona){
                if($nombrePersona->persona_p_apellido == null){
                    return $nombrePersona->persona_s_apellido. ' '.$nombrePersona->persona_nombres;
                } elseif ($nombrePersona->persona_s_apellido == null){
                    return $nombrePersona->persona_p_apellido. ' ' . $nombrePersona->persona_nombres;
                } else {
                    return $nombrePersona->persona_p_apellido. ' ' .$nombrePersona->persona_s_apellido. ' ' .$nombrePersona->persona_nombres; 
                }
            })
            ->add('ci_formatted', function ($ci){
                if($ci->persona_complemento == null){
                    return $ci->persona_ci;
                } else {
                    return $ci->persona_ci . ' - ' . $ci->persona_complemento;
                }
            })
            ->add('persona_matricula_seguro')
            ->add('id_orden_servicio_s')
            ->add('fecha_entregado_formatted', fn (OrdenServicio $model) => Carbon::parse($model->fecha_entregado)->format('d/m/Y'))
            ->add('id_persona_entregado')
            ->add('estudio')

            ->add('estado', function ($solicitud) {
                if ($solicitud->estado == 'PENDIENTE') {
                    return '<span style="color: #F57C00 ;" title="PENDIENTE">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16" >
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                    </svg>
                    </span>';
                }
                if ($solicitud->estado == 'VALIDADO') {
                    return '<span style="color: green ;" title="VALIDADO">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-check-square-fill" viewBox="0 0 16 16">
                    <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm10.03 4.97a.75.75 0 0 1 .011 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.75.75 0 0 1 1.08-.022z"/>
                    </svg>
                    </span>';
                }
                if ($solicitud->estado == 'HOMOLOGADO') {
                    return '<span style="color:#21618C ;" title="HOMOLOGADO">
               <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-award-fill" viewBox="0 0 16 16">
                    <path d="m8 0 1.669.864 1.858.282.842 1.68 1.337 1.32L13.4 6l.306 1.854-1.337 1.32-.842 1.68-1.858.282L8 12l-1.669-.864-1.858-.282-.842-1.68-1.337-1.32L2.6 6l-.306-1.854 1.337-1.32.842-1.68L6.331.864z"/>
                    <path d="M4 11.794V16l4-1 4 1v-4.206l-2.018.306L8 13.126 6.018 12.1z"/>
                  </svg>
                </span>';
                }
                if ($solicitud->estado == 'ANULADO') {
                    return '<span style="color: red ;" title="ANULADO">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-x-square-fill" viewBox="0 0 16 16">
                <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708"/>
                </svg>
                    </span>';
                }
            })
            ->add('id_institucion_convenio')
            ->add('id_especialidad')
            ->add('id_especialista')
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

            Column::make('NOMBRE COMPLETO', 'persona_nombre_completo','persona_p_apellido')
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

            Column::make('CARNET', 'ci_formatted','persona_ci')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')    
                ->sortable()
                ->searchable(),

            Column::make('MATRICULA', 'persona_matricula_seguro')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')        
                ->sortable()
                ->searchable(),

            Column::make('NRO. SERVICIO', 'nro_orden_servicio')
            ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('FECHA RECEPCIÓN', 'fecha_recepcion_formatted', 'fecha_recepcion')
            ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->searchableRaw("TO_CHAR(fecha_recepcion, 'dd/mm/yyyy')"),

            Column::make('ESTADO', 'estado')
            ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
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
        return [
          
        ];
    }


    public function actions(OrdenServicio $row): array
    {
        $buttons = [];
    
        // Botón "ver" siempre visible
        $buttons[] = Button::add('ver')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                    <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                    </svg>')
            ->class('btn btn-blue')
            ->tooltip('VER ORDEN DE SERVICIO')
            ->dispatchTo('vigencia.ordenes-servicio.ordenes-servicio-main', 'verOrden', ['data' => $row->id])
            ->can(allowed: auth()->user()->can('ver.ordenes_servicio_hologacion'));
    
        // Botones adicionales según el estado
        if ($row->estado === 'PENDIENTE') {
            $buttons[] = Button::add('validar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16">
                        <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0"/>
                        </svg>')
                ->class('btn btn-green')
                ->tooltip('VALIDAR ORDEN DE SERVICIO')
                ->dispatchTo('vigencia.ordenes-servicio.ordenes-servicio-main', 'validarOrden', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('entregar.ordenes_servicio_hologacion'));
    
            $buttons[] = Button::add('anular')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-diamond" viewBox="0 0 16 16">
                            <path d="M6.95.435c.58-.58 1.52-.58 2.1 0l6.515 6.516c.58.58.58 1.519 0 2.098L9.05 15.565c-.58.58-1.519.58-2.098 0L.435 9.05a1.48 1.48 0 0 1 0-2.098zm1.4.7a.495.495 0 0 0-.7 0L1.134 7.65a.495.495 0 0 0 0 .7l6.516 6.516a.495.495 0 0 0 .7 0l6.516-6.516a.495.495 0 0 0 0-.7L8.35 1.134z"/>
                            <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                            </svg>')
                ->class('btn btn-red')
                ->tooltip('ANULAR ORDEN DE SERVICIO')
                ->dispatchTo('vigencia.ordenes-servicio.ordenes-servicio-main', 'anularOrden', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('ver.ordenes_servicio_hologacion'));
        }
    
        if ($row->estado === 'VALIDADO') {
            $buttons[] = Button::add('entregar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                        <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                        </svg>')
                ->class('btn btn-orange')
                ->tooltip('ENTREGAR ORDEN DE SERVICIO')
                ->dispatchTo('vigencia.ordenes-servicio.ordenes-servicio-main', 'entregarOrden', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('entregar.ordenes_servicio_hologacion'));
        }
    
        // Para estado "HOMOLOGADO", solo el botón "ver" se agrega automáticamente antes.
    
        return $buttons;
    }


}
