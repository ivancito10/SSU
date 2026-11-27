<?php

namespace App\Livewire\Vigencia\SolicitudPrestacionMedica;

use App\Models\Vigencia\SolicitudPMedica;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Detail;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class SolicitudPrestacionMedicaTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'SolicitudesTable';
    public string $sortField = 'solicitud_p_medica.id';
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

    public function datasource(): Collection
    {
        /*return SolicitudPMedica::with(['aseguradoSolicitanteSolicitudPMedica' => function ($query) {
                $query->orderBy('id', 'asc')->limit(1);
            }, 'aseguradoSolicitanteSolicitudPMedica.personaAseguradoSolicitante'])   ;*/

        $solicitudesP = SolicitudPMedica::query()
            ->join('vvdd.asegurado_solicitante as asegurado_solicitante', 'id_solicitud_p_medica', '=', 'vvdd.solicitud_p_medica.id')
            ->join('administracion.persona as persona', function ($persona) {
                $persona->on('asegurado_solicitante.id_asegurado_solicitante', '=', 'persona.id');
            })
            ->join('vvdd.seguro_interior as seguro_interior', function ($seguro_interior) {
                $seguro_interior->on('seguro_interior.id', '=', 'vvdd.solicitud_p_medica.id_seguro_interior');
            })

            ->select(
                'solicitud_p_medica.*',
                'persona.nombres as p_nombres',
                'persona.p_apellido as p_p_apellido',
                'persona.s_apellido as p_s_apellido',
                'persona.matricula_seguro',
                'persona.ci',
                'persona.complemento',
                'seguro_interior.nombre_seguro'
            )
            ->whereRaw('asegurado_solicitante.id = (SELECT MIN(sol2.id) FROM vvdd.asegurado_solicitante as sol2 WHERE sol2.id_solicitud_p_medica = asegurado_solicitante.id_solicitud_p_medica)')
            ->orderby('id', 'desc')
            ->get();
        $solicitudesP->map(function ($solicitud) {
            $solicitud->persona_nombre_completo = $solicitud->p_p_apellido . ' ' . $solicitud->p_s_apellido . ' ' . $solicitud->p_nombres;
            $solicitud->duracion = Carbon::parse($solicitud->fecha_inicio)->format('d/m/Y') . ' - ' . Carbon::parse($solicitud->fecha_fin)->format('d/m/Y');
            $solicitud->seguro_interior = $solicitud->nombre_seguro ?? 'SIN DATOS';
            return $solicitud;
        });
        return $solicitudesP;
    }

    public function relationSearch(): array
    {
        return [
            'aseguradoSolicitanteSolicitudPMedica.personaAseguradoSolicitante' => ['nombres', 'p_apellido', 's_apellido', 'matricula_seguro', 'ci'],
            'seguroInteriorSolicitudPMedica' => ['nombre_seguro'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('id')
            ->add('cod_cite', fn(SolicitudPMedica $model) => $model->cod_cite . '/' . Carbon::parse($model->created_at)->format('Y'))
            ->add('nro_hoja_de_ruta')
            ->add('fecha_solicitud_hr_formatted', fn(SolicitudPMedica $model) => Carbon::parse($model->fecha_solicitud_hr)->format('d/m/Y'))
            ->add('estado_validacion', function ($solicitud) {
                if ($solicitud->estado_validacion == 'PENDIENTE') {
                    return '<span style="color: #F57C00 ;"  title="PENDIENTE">
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
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-question-square-fill" viewBox="0 0 16 16">
                <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm3.496 6.033a.237.237 0 0 1-.24-.247C5.35 4.091 6.737 3.5 8.005 3.5c1.396 0 2.672.73 2.672 2.24 0 1.08-.635 1.594-1.244 2.057-.737.559-1.01.768-1.01 1.486v.105a.25.25 0 0 1-.25.25h-.81a.25.25 0 0 1-.25-.246l-.004-.217c-.038-.927.495-1.498 1.168-1.987.59-.444.965-.736.965-1.371 0-.825-.628-1.168-1.314-1.168-.803 0-1.253.478-1.342 1.134-.018.137-.128.25-.266.25h-.825zm2.325 6.443c-.584 0-1.009-.394-1.009-.927 0-.552.425-.94 1.01-.94.609 0 1.028.388 1.028.94 0 .533-.42.927-1.029.927"/>
                </svg>
                </span>';
                }
                if ($solicitud->estado_validacion == 'ANULADO') {
                    return '<span style="color: red ;" title="ANULADO">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-x-square-fill" viewBox="0 0 16 16">
                <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708"/>
                </svg>
                    </span>';
                }
            })
            ->add('fecha_inicio_formatted', fn(SolicitudPMedica $model) => Carbon::parse($model->fecha_inicio)->format('d/m/Y'))
            //->add('duracion', fn(SolicitudPMedica $model) => Carbon::parse($model->fecha_inicio)->format('d/m/Y') . ' - ' . Carbon::parse($model->fecha_fin)->format('d/m/Y'))
            ->add('fecha_fin_formatted', fn(SolicitudPMedica $model) => Carbon::parse($model->fecha_fin)->format('d/m/Y'))
            ->add('otras_limitaciones')
            ->add('id_seguro_interior')
            ->add('id_personal_ssu')
            ->add('observacion')
            ->add('ci_formatted', function ($carnet) {
                return implode(' - ', array_filter([$carnet->ci, $carnet->complemento]));
            })
            //  ->add('persona_nombre_completo', fn(SolicitudPMedica $model) => $model->aseguradoSolicitanteSolicitudPMedica[0]->personaAseguradoSolicitante->p_apellido . ' ' . $model->aseguradoSolicitanteSolicitudPMedica[0]->personaAseguradoSolicitante->s_apellido . ' ' . $model->aseguradoSolicitanteSolicitudPMedica[0]->personaAseguradoSolicitante->nombres)
            // ->add('persona_nombre_completo', function ($nombrePersona){
            //     if($nombrePersona->p_p_apellido == null){
            //         return $nombrePersona->p_s_apellido. ' '.$nombrePersona->p_nombres;
            //     } elseif ($nombrePersona->p_s_apellido == null){
            //         return $nombrePersona->p_p_apellido. ' ' . $nombrePersona->p_nombres;
            //     } else {
            //         return $nombrePersona->p_p_apellido. ' ' .$nombrePersona->p_s_apellido. ' ' .$nombrePersona->p_nombres; 
            //     }
            // })
            // ->add('seguro_interior', fn(SolicitudPMedica $model) => $model->seguroInteriorSolicitudPMedica->nombre_seguro)
            // ->add('seguro_interior', function ($inst){
            //     if($inst->nombre_seguro != null){
            //         return $inst->nombre_seguro;
            //     } 
            // })

            ->add('medicamentos_liname')
            ->add('medicamentos_extraliname')
            ->add('totalidad_esp_serv')
            ->add('estado', function ($estadoSol) {
                if ($estadoSol->estado) {
                    return '<span title="HABILITADO"> <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="green" class="bi bi-check-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                        <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
                        </svg></span>';
                } else {
                    return '<span title="HABILITADO" ><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="red" class="bi bi-x-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                    </svg></span>';
                }
            })
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->index()
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center'),

            Column::make('COD CITE', 'cod_cite')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')

                ->sortable()
                ->searchable(),

            Column::make('SOLICITANTE', 'persona_nombre_completo')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('MATRICULA', 'matricula_seguro')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('CARNET', 'ci_formatted', 'ci')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('DURACIÓN 2', 'duracion')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('SEGURO DEL INTERIOR', 'seguro_interior')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
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
                ->bodyAttribute('text-center'),
        ];
    }

    public function filters(): array
    {
        return [];
    }


    public function actions(SolicitudPMedica $row): array
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
            ->dispatchTo('vigencia.solicitud-prestacion-medica.solicitud-prestacion-medica-main', 'verSolicitud', ['data' => $row->id])
            ->can(allowed: auth()->user()->can('ver.prestaciones_interior'));

        if ($row->estado_validacion === 'PENDIENTE' && $row->estado === true) {
            $buttons[] = Button::add('editar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-lines-fill" viewBox="0 0 16 16">
                        <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1z"/>
                        </svg>')
                // ->id()
                ->class('btn btn-orange')
                //->dispatchTo('vigencia.solicitud-prestacion-medica.solicitud-prestacion-medica-main', 'editarSolicitud', ['data' => $row->id])
                ->route('vigencia.SolicitudesPrestacionesMedicas.edit', ['id' => $row->id])
                ->tooltip('EDITAR SOLICITUD')
                ->can(allowed: auth()->user()->can('editar.prestaciones_interior'));
        }

        if ($row->estado_validacion === 'PENDIENTE' || $row->estado_validacion === 'VALIDADO') {
            $buttons[] = Button::add('pdf')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z"/>
                    </svg>')
                ->class('btn btn-blue')
                ->tooltip('DESCARGAR PDF')
                ->dispatchTo('vigencia.solicitud-prestacion-medica.solicitud-prestacion-medica-main', 'prestacionPdf', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('pdf.prestaciones_interior'));
        }

        if ($row->estado_validacion === 'PENDIENTE' && $row->estado === true) {
            $buttons[] = Button::add('firmar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-vector-pen" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M10.646.646a.5.5 0 0 1 .708 0l4 4a.5.5 0 0 1 0 .708l-1.902 1.902-.829 3.313a1.5 1.5 0 0 1-1.024 1.073L1.254 14.746 4.358 4.4A1.5 1.5 0 0 1 5.43 3.377l3.313-.828zm-1.8 2.908-3.173.793a.5.5 0 0 0-.358.342l-2.57 8.565 8.567-2.57a.5.5 0 0 0 .34-.357l.794-3.174-3.6-3.6z"/>
                                    <path fill-rule="evenodd" d="M2.832 13.228 8 9a1 1 0 1 0-1-1l-4.228 5.168-.026.086z"/>
                                    </svg>')
                ->class('btn btn-green')
                ->route('vigencia.SolicitudesPrestacionesMedicas.firma', ['id' => $row->id])
                ->tooltip('VERIFICAR FIRMA DIGITAL')
                ->can(allowed: auth()->user()->can('firma_digital.prestaciones_interior'));
        }

        if ($row->estado_validacion !== 'ANULADO' || $row->estado_validacion !== 'VALIDADO') {
            if ($row->estado === true) {
                $buttons[] = Button::add('anular')
                    ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-diamond" viewBox="0 0 16 16">
                            <path d="M6.95.435c.58-.58 1.52-.58 2.1 0l6.515 6.516c.58.58.58 1.519 0 2.098L9.05 15.565c-.58.58-1.519.58-2.098 0L.435 9.05a1.48 1.48 0 0 1 0-2.098zm1.4.7a.495.495 0 0 0-.7 0L1.134 7.65a.495.495 0 0 0 0 .7l6.516 6.516a.495.495 0 0 0 .7 0l6.516-6.516a.495.495 0 0 0 0-.7L8.35 1.134z"/>
                            <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                            </svg>')
                    ->id()
                    ->class('btn btn-red')
                    ->tooltip('ANULAR')
                    ->dispatchTo('vigencia.solicitud-prestacion-medica.solicitud-prestacion-medica-main', 'anularSolicitud', ['data' => $row->id])
                    ->can(allowed: auth()->user()->can('anular.prestaciones_interior'));
            }
        }

        return $buttons;
    }
}
