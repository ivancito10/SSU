<?php

namespace App\Livewire\Vigencia\Convenios\Interior;

use App\Models\Vigencia\AutorizacionInterior;
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

final class ConvenioInteriorTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'AutorizacionesTable';
    public string $sortField = 'autorizacion_interior.id';
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
        return AutorizacionInterior::query()
            ->join('administracion.persona as persona', function ($persona) {
                $persona->on('persona.id', '=', 'vvdd.autorizacion_interior.id_persona_interior');
            })
            ->select(
                'autorizacion_interior.*',
                'persona.nombres as persona_nombres',
                'persona.p_apellido as persona_p_apellido',
                'persona.s_apellido as persona_s_apellido',
                'persona.ci as persona_ci',
                'persona.complemento as persona_complemento',
                'persona.matricula_seguro as persona_matricula'
            );
    }

    public function relationSearch(): array
    {
        return [
            'seguroInteriorAutorizacionInterior' => ['nombre_seguro'],
            'personaInteriorAutorizacionInterior' => ['p_apellido', 's_apellido', 'nombres', 'ci', 'matricula_seguro'],

        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('id')
            //->add('cod_interior', fn (AutorizacionInterior $model) => $model->cod_interior.'/'.Carbon::parse($model->created_at)->format('Y'))
            ->add('cod_interior_formatted', function ($codigo) {
                return 'C/IN/' . $codigo->cod_interior . '/' . Carbon::parse($codigo->created_at)->format('Y');
            })
            ->add('cod_cite_int')
            ->add('duracion', fn(AutorizacionInterior $model) => Carbon::parse($model->fecha_inicio)->format('d/m/Y') . ' - ' . Carbon::parse($model->fecha_fin)->format('d/m/Y'))
            ->add('fecha_inicio_formatted', fn(AutorizacionInterior $model) => Carbon::parse($model->fecha_inicio)->format('d/m/Y'))
            ->add('fecha_fin_formatted', fn(AutorizacionInterior $model) => Carbon::parse($model->fecha_fin)->format('d/m/Y'))
            ->add('id_seguro_interior', fn(AutorizacionInterior $model) => $model->seguroInteriorAutorizacionInterior->nombre_seguro)
            ->add('otras_limitaciones')
            ->add('id_persona_interior', fn(AutorizacionInterior $model) => $model->personaInteriorAutorizacionInterior->p_apellido . ' ' . $model->personaInteriorAutorizacionInterior->s_apellido . ' ' . $model->personaInteriorAutorizacionInterior->nombres)
            ->add('carnet_persona_interior', fn(AutorizacionInterior $model) => $model->personaInteriorAutorizacionInterior->complemento === null ? $model->personaInteriorAutorizacionInterior->ci : $model->personaInteriorAutorizacionInterior->ci . ' - ' . $model->personaInteriorAutorizacionInterior->complemento)
            ->add('matricula_persona_interior', fn(AutorizacionInterior $model) => $model->personaInteriorAutorizacionInterior->matricula_seguro)
            ->add('id_persona_titular')
            ->add('nombres_persona_formatted', function ($nombres) {
                if ($nombres->persona_p_apellido == null) {
                    return $nombres->persona_s_apellido . ' ' . $nombres->persona_nombres;
                } elseif ($nombres->persona_s_apellido == null) {
                    return $nombres->persona_p_apellido . ' ' . $nombres->persona_nombres;
                } else {
                    return $nombres->persona_p_apellido . ' ' . $nombres->persona_s_apellido . ' ' . $nombres->persona_nombres;
                }
            })
            ->add('carnet_persona_formatted', function ($carnet) {
                if ($carnet->persona_complemento == null) {
                    return $carnet->persona_ci;
                } else {
                    return $carnet->persona_ci . ' - ' . $carnet->persona_complemento;
                }
            })
            ->add('matricula_persona_formatted')
            ->add('id_personal_ssu')
            ->add('foto_nombre')
            ->add('observacion')
            ->add('medicamentos_liname')
            ->add('medicamentos_extraliname')
            ->add('totalidad_esp_serv')
            ->add('estado', function ($estadoAut) {
                if ($estadoAut->estado) {
                    return '<span title="ACTIVO"> <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="green" class="bi bi-check-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                        <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
                        </svg></span>';
                } else {
                    return '<span title="INACTIVO" ><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="red" class="bi bi-x-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                        </svg></span>';
                }
            })
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
                    return '<span style="color:#21618C ;"  title="HOMOLOGADO">
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

            Column::make('COD. INTERIOR', 'cod_interior_formatted', 'cod_interior')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            /*Column::make('COD CITE', 'cod_cite_int')
            ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                
                ->sortable()
                ->searchable(),*/

            Column::make('CARNET', 'carnet_persona_formatted', 'persona_ci')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('MATRICULA', 'persona_matricula')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('SOLICITANTE', 'nombres_persona_formatted', 'persona_p_apellido')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('DURACIÓN', 'duracion', 'fecha_inicio')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('SEGURO ORIGEN', 'id_seguro_interior')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('ESTADO VALIDACIÓN', 'estado_validacion')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('ESTADO', 'estado')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')

        ];
    }

    public function filters(): array
    {
        return [];
    }

    public function actions(AutorizacionInterior $row): array
    {
        $buttons = [];

        $buttons[] = Button::add('ver')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
            </svg>')
            ->id()
            ->class('btn btn-blue')
            ->tooltip('VER AUTORIZACION')
            ->dispatchTo('vigencia.convenios.interior.convenio-interior-main', 'verAutorizacion', ['data' => $row->id])
            ->can(allowed: auth()->user()->can('ver.convenios_interior'));


        if ($row->estado_validacion === 'PENDIENTE') {
            $buttons[] = Button::add('editar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-lines-fill" viewBox="0 0 16 16">
                        <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1z"/>
                        </svg>')

                ->class('btn btn-orange')
                ->route('vigencia.convenios.interior.editar', ['id' => $row->id])
                ->tooltip('EDITAR AUTORIZACION')
                ->can(allowed: auth()->user()->can('editar.convenios_interior'));
        }

        if ($row->estado_validacion === 'PENDIENTE') {

            $buttons[] = Button::add('validar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16">
                        <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0"/>
                        </svg>')
                ->id()
                ->class('btn btn-green')
                ->tooltip('VALIDAR AUTORIZACION')
                ->dispatchTo('vigencia.convenios.interior.convenio-interior-main', 'validarAutorizacion', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('validar.convenios_interior'));
        }

        $buttons[] =   Button::add('pdf')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z"/>
            </svg>')

            ->class('btn btn-red')
            ->tooltip('PDF COMPROBANTE')
            /* ->dispatch('pdf', ['rowId' => $row->id]) */
            ->dispatchTo('vigencia.convenios.interior.convenio-interior-main', 'convenioPdf', ['data' => $row->id])
            ->can(allowed: auth()->user()->can('ver.convenios_interior'));

        if ($row->estado_validacion !== 'ANULADO') {

            $buttons[] = Button::add('anular')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-diamond" viewBox="0 0 16 16">
                            <path d="M6.95.435c.58-.58 1.52-.58 2.1 0l6.515 6.516c.58.58.58 1.519 0 2.098L9.05 15.565c-.58.58-1.519.58-2.098 0L.435 9.05a1.48 1.48 0 0 1 0-2.098zm1.4.7a.495.495 0 0 0-.7 0L1.134 7.65a.495.495 0 0 0 0 .7l6.516 6.516a.495.495 0 0 0 .7 0l6.516-6.516a.495.495 0 0 0 0-.7L8.35 1.134z"/>
                            <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                            </svg>')
                ->id()
                ->class('btn btn-red')
                ->tooltip('ANULAR AUTORIZACION')
                ->dispatchTo('vigencia.convenios.interior.convenio-interior-main', 'anularAutorizacion', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('anular.convenios_interior'));
        }

        if ($row->estado_validacion === 'VALIDADO') {
            /*$buttons[] = Button::add('carnetizacion')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-credit-card-2-back" viewBox="0 0 16 16">
                    <path d="M11 5.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5z"/>
                    <path d="M2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zm13 2v5H1V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1m-1 9H2a1 1 0 0 1-1-1v-1h14v1a1 1 0 0 1-1 1"/>
                    </svg>')
                ->class('btn btn-green')
                ->tooltip('CARNET')
                ->dispatchTo('vigencia.convenios.interior.convenio-interior-main', 'carnetizacion', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('carnet.convenios_interior'));*/
            // $buttons[]=Button::add();
        }
        
        /* ->dispatch('ver', ['rowId' => $row->id]) */
        // ->dispatchTo('aportes.aportes-institucionales.aportes-institucionales-main', 'verCopiaPdf', ['data' => $row->id]);
        // ->can(allowed: auth()->user()->can('comprobante_pdf_copia.aportes_institucionales'));  

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
