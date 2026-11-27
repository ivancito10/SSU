<?php

namespace App\Livewire\Afiliacion\Titulares;

use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Afiliacion\CodigoInstitucionSsu;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Detail;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use App\Models\Aportes\ContratoVoluntario;


use App\Models\Administracion\Persona;
use Illuminate\Support\Facades\Cache;

final class TitularesTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'TitularesInstitucionalesVoluntarios';
    public string $sortField = 'titular.id';
    public string $sortDirection = 'desc';
    public function setUp(): array
    {
        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()

                ->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
            // Detail::make()
            //   ->view('Afiliacion.titulares.titulares-nuevo')
            //   ->showCollapseIcon()
        ];
    }

    public function datasource(): Builder
    {
        //dd(Titular::all());
        //return Titular::query();
        return Titular::query()
            ->join('administracion.persona as persona', function ($persona) {
                $persona->on('titular.id_persona', '=', 'persona.id');
            })
            ->leftJoin('afiliacion.titular_institucion as t', function ($titularInst) {
                $titularInst->on('t.id_titular', '=', 'titular.id')
                    ->whereRaw(
                        't.id = (
                            SELECT MIN(t2.id)
                            FROM afiliacion.titular_institucion as t2
                            WHERE t2.id_titular = titular.id
                            AND t2.estado=true
                        )'
                    );
            })
            ->leftJoin('aportes.institucion as institucion', function ($institucion) {
                $institucion->on('t.id_institucion', '=', 'institucion.id');
            })
            ->leftJoin('aportes.clase_aportante as clase', function ($clase) {
                $clase->on('clase.id', '=', 'institucion.id_clase_aportante');
            })
            ->leftJoin('afiliacion.codigo_institucion_ssu as codigo', function ($codigo) {
                $codigo->on('codigo.id_institucion', '=', 'institucion.id');
            })
            ->select(
                'titular.*',
                'titular.tipo_titular as tipoTitular',
                'persona.nombres as p_nombres',
                'persona.p_apellido as p_p_apellido',
                'persona.s_apellido as p_s_apellido',
                'persona.matricula_seguro',
                'persona.ci',
                'persona.complemento',
                'persona.grupo_sanguineo',
                'institucion.acronimo as nombre_institucion',
                'codigo.codigo',
                'codigo.tipo_titular',
                'clase.id as id_clase_aportante',
            )
            ->where('titular.estado', true);
        //dd($tit);

    }

    public function relationSearch(): array
    {
        return [
            'titularPersona' => ['nombres', 'p_apellido', 's_apellido', 'matricula_seguro', 'ci', 'complemento'],
            'titularConTitularInstitucion.titularInstitucionConInstitucion' => ['nombre', 'acronimo'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('estado_requisitos', function ($estadoReq) {
                if ($estadoReq->estado_requisitos) {
                    return '<span style="color: green" title="COMPLETO"> <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>
                </span>';
                } else {
                    return '<span style="color: orange" title="PENDIENTE">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                    </svg> 
                    </span>';
                }
            })

            ->add('institucion_formatted', function ($inst) {
                if ($inst->tipoTitular) {
                    if ($inst->nombre_institucion != null) {
                        return $inst->nombre_institucion;
                    } else {
                        return ' BAJA INSTITUCIONAL';
                    }
                } else {
                    return 'APORTANTE VOLUNTARIO';
                }
            })

            ->add('estado_vigencia', function ($dish) {
                if ($dish->estado_vigencia == 'VIGENTE') {
                    return '<span style="color: #229954" title="VIGENTE"> 
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-check-square-fill" viewBox="0 0 16 16">
  <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm10.03 4.97a.75.75 0 0 1 .011 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.75.75 0 0 1 1.08-.022z"/>
</svg>
                    </span>';
                }
                if ($dish->estado_vigencia == 'CESANTE') {
                    return '<span style="color: #fcc225" title="CESANTE"> 
                  <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-exclamation-square-fill" viewBox="0 0 16 16">
  <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm6 4c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995A.905.905 0 0 1 8 4m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
</svg>
                    </span>';
                }
                if ($dish->estado_vigencia == 'NO VIGENTE') {
                    return '<span style="color: #c0392b" title="NO VIGENTE"> 
               <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-x-square-fill" viewBox="0 0 16 16">
  <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708"/>
</svg>
                    </span>';
                }
            })
            ->add('grupo_sanguineo')
            ->add('alergias')
            ->add('telefono_referencia')
            ->add('nombre_referencia')
            ->add('foto_nombre')
            ->add('observacion')
            ->add('fecha_afiliacion_formatted', fn(Titular $model) => Carbon::parse($model->fecha_afiliacion)->format('d/m/Y'))
            ->add('fecha_reafiliacion_formatted', fn(Titular $model) => Carbon::parse($model->fecha_reafiliacion)->format('d/m/Y'))
            ->add('telefono')
            ->add('tipo_afiliacion')
            ->add('id_persona')
            ->add('persona_nombre_completo', function ($row) {
                return trim(
                    ($row->p_p_apellido ?? '') . ' ' .
                        ($row->p_s_apellido ?? '') . ' ' .
                        ($row->p_nombres ?? '')
                );
            })
            ->add('ci_formatted', function ($ci) {
                if ($ci->complemento == null) {
                    return $ci->ci;
                } else {
                    return $ci->ci . ' - ' . $ci->complemento;
                }
            })
            /*  ->add('matricula_seguro_formatted', function ($matricula) {
                $institucionTit = TitularInstitucion::with([
                    'titularInstitucionConInstitucion.codigosInstitucion'
                ])
                    ->where('id_titular', $matricula->id)
                    ->where('estado', true)
                    ->orderBy('id', 'asc')
                    ->first();

                if ($institucionTit && $institucionTit->titularInstitucionConInstitucion) {
                    $codigoInst = $institucionTit->titularInstitucionConInstitucion->codigosInstitucion;

                    if ($codigoInst) {
                        $codTitular = $codigoInst->codigo . '' . $codigoInst->tipo_titular;
                        $codigoTitular = $codTitular ?: ' ';
                        return $matricula->matricula_seguro . ' - ' . $codigoTitular;
                    }
                }
                return $matricula->matricula_seguro;
            }) */
            ->add('matricula_seguro')
            ->add('estado')
            ->add('tipo_titular')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')->index()
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center'),

            Column::make('MATRICULA', 'matricula_seguro', 'matricula_seguro')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable()
                ->sortable(),

            Column::make('C.I.', 'ci_formatted', 'ci')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable()
                ->sortable(),

            Column::make('ASEGURADO', 'persona_nombre_completo', 'p_p_apellido')
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


            // Column::make('INSTITUCION', 'institucion_formatted', 'nombre_institucion')
            Column::make('INSTITUCION', 'institucion_formatted')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                //->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),



            Column::make('FECHA DE AFILIACIÓN', 'fecha_afiliacion_formatted', 'fecha_afiliacion')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->searchableRaw("TO_CHAR(titular.fecha_afiliacion, 'DD/MM/YYYY')")
                ->sortable(),


            Column::make('REQUISITOS', 'estado_requisitos')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('VIGENCIA', 'estado_vigencia')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            //   Column::make('PREOCUPACIONAL', '')
            //   ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
            //   ->bodyAttribute('text-center')
            //       ->sortable()
            //       ->searchable(),


            /*  Column::action('OPCIONES')
                ->headerAttribute('text-center tablita-cabecera-sin-mover', 'font-size:15px; background-color:#2471a3; color:white;')
                ->bodyAttribute('text-left tablita-cuerpo-sin-mover '), */
            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')

        ];
    }

    public function filters(): array
    {
        return [];
    }
    public function actions(Titular $row): array
    {
        $buttons = [];
        $buttons[] = Button::add('ver')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                </svg>')
            ->class('btn btn-blue')
            ->tooltip('VER ASEGURADO')
            //  ->dispatchTo('afiliacion.titulares.titulares-main', 'verTitular', ['data' => $row->id])
            ->dispatchTo('afiliacion.titulares.titulares-ver', 'verTitular', ['data' => $row->id])
            ->can(allowed: auth()->user()->can('ver.titulares'));


        if ($row->grupo_sanguineo == null) {
            $buttons[] = Button::add('tipo_sangre')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-droplet-fill" viewBox="0 0 16 16">
                    <path d="M8 16a6 6 0 0 0 6-6c0-1.655-1.122-2.904-2.432-4.362C10.254 4.176 8.75 2.503 8 0c0 0-6 5.686-6 10a6 6 0 0 0 6 6M6.646 4.646l.708.708c-.29.29-1.128 1.311-1.907 2.87l-.894-.448c.82-1.641 1.717-2.753 2.093-3.13"/>
                    </svg>')
                ->class('btn btn-red')
                ->tooltip('AGREGAR TIPO DE SANGRE')
                ->dispatchTo('afiliacion.titulares.titulares-main', 'tipoSangreTitular', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('registrar_tipo_sangre.titulares'));
        }
        if ($row->estado_vigencia === 'VIGENTE' || $row->estado_vigencia === 'CESANTE') {

            $buttons[] = Button::add('edit')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-lines-fill" viewBox="0 0 16 16">
                <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1z"/>
                </svg>')
                ->class('btn btn-orange')
                ->route('afiliacion.titulares.edit-afiliacion', ['id' => $row->id])
                ->tooltip('EDITAR ASEGURADO')
                ->can(allowed: auth()->user()->can('editar.titulares'));


            //Logica para que aparezca o no el boton de agregar institucion

            $existeTitularInstitucion = TitularInstitucion::where('id_titular', $row->id)->where('estado', true)->first();
            if ($existeTitularInstitucion) {
                $buttons[] = Button::add('agregarInstitucion')
                    ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-building-add" viewBox="0 0 16 16">
                    <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0"/>
                    <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6.5a.5.5 0 0 1-1 0V1H3v14h3v-2.5a.5.5 0 0 1 .5-.5H8v4H3a1 1 0 0 1-1-1z"/>
                    <path d="M4.5 2a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-6 3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-6 3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z"/>
                    </svg>')
                    ->class('btn btn-cafe')
                    ->tooltip('AGREGAR INSTITUCION')
                    ->dispatchTo('afiliacion.titulares.agregar-institucion', 'agregarInstitucion', ['data' => $row->id])
                    ->can(allowed: auth()->user()->can('agregar_institucion.titulares'));
            }

            $buttons[] = Button::add('grupoFamiliar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                </svg>')
                ->class('btn btn-infoDos')
                ->tooltip('GRUPO FAMILIAR')
                ->dispatchTo('afiliacion.titulares.titulares-main', 'verGrupoFamiliar', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('ver_grupo_familiar.titulares'));

            $buttons[] = Button::add('verificar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-check-fill" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L12.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0"/>
                <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                </svg>')
                ->class('btn btn-lila')
                ->tooltip('VERIFICACIÓN DE ASUSS')
                ->dispatchTo('afiliacion.titulares.titulares-main', 'verAsuss', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('ver_registro_asuss.titulares'));

            //Fin de la logica para que etc etc etc

        }
        if ($row->estado_vigencia === 'NO VIGENTE' || $row->estado_vigencia === 'CESANTE') {

            if ($row->fecha_archivo == null) {
                $buttons[] = Button::add('archivo')
                    ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-archive-fill" viewBox="0 0 16 16">
                <path d="M12.643 15C13.979 15 15 13.845 15 12.5V5H1v7.5C1 13.845 2.021 15 3.357 15zM5.5 7h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1M.8 1a.8.8 0 0 0-.8.8V3a.8.8 0 0 0 .8.8h14.4A.8.8 0 0 0 16 3V1.8a.8.8 0 0 0-.8-.8z"/>
                </svg>')
                    ->class('btn btn-orange')
                    ->tooltip('ARCHIVO GRUPO')
                    ->dispatchTo('afiliacion.titulares.titulares-main', 'archivoGrupo', ['data' => $row->id])
                    ->can(allowed: auth()->user()->can('registrar.archivo_grupo'));
            }
        }
        if ($row->grupo_sanguineo != null && $row->estado_vigencia != 'VIGENTE') {
            $buttons[] =  Button::add('reafiliacion')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus-fill" viewBox="0 0 16 16">
                    <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                    <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5"/>
                    </svg>')
                ->class('btn btn-green')
                ->route('afiliacion.titulares.create.reafiliacion', ['id' => $row->id])
                ->tooltip('REAFILIAR ASEGURADO')
                ->can(allowed: auth()->user()->can('reafiliacion.titulares'));
        }

        //para la carnetizacion
        if ($row->grupo_sanguineo != null && ($row->estado_vigencia === 'VIGENTE' || $row->estado_vigencia === 'CESANTE')) {
            $buttons[] = Button::add('carnetizacion')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-credit-card-2-back" viewBox="0 0 16 16">
                <path d="M11 5.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5z"/>
                <path d="M2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zm13 2v5H1V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1m-1 9H2a1 1 0 0 1-1-1v-1h14v1a1 1 0 0 1-1 1"/>
                </svg>')
                ->class('btn btn-green')
                ->tooltip('CARNET')
                ->dispatchTo('afiliacion.titulares.titulares-main', 'carnetizacion', ['data' => $row->id, 'clase' => $row->id_clase_aportante])
                ->can(allowed: auth()->user()->can('carntizacion.titulares'));
        }


        return $buttons;
    }
}
