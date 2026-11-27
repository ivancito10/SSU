<?php

namespace App\Livewire\Afiliacion\Beneficiarios;

use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Titular;
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
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Afiliacion\CodigoInstitucionSsu;
use Illuminate\Support\Facades\DB;


final class BeneficiariosTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'BeneficiariosTable';
    public string $sortField = 'beneficiario.id';
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
        $query = Beneficiario::join('administracion.persona', function ($persona) {
            $persona->on('beneficiario.id_persona', '=', 'persona.id');
        })
            ->join('administracion.tipo_asegurado as tipo_asegurado', function ($tipo_asegurado) {
                $tipo_asegurado->on('tipo_asegurado.id', '=', 'persona.id_tipo_asegurado');
            })
            ->select(
                'beneficiario.*',
                DB::raw("TRIM(CONCAT(
                COALESCE(persona.p_apellido, ''), 
                ' ', 
                COALESCE(persona.s_apellido, ''), 
                ' ', 
                COALESCE(persona.nombres, '')
            )) as persona_nombre_completo"),
                'persona.ci as persona_ci',
                'persona.matricula_seguro as matricula_seguro',
                'persona.grupo_sanguineo',
                'persona.complemento as persona_complemento',
                'tipo_asegurado.tipo_asegurado as nombre_asegurado'
            )
            ->where('beneficiario.estado', '=', true);

        return $query;
    }

    public function relationSearch(): array
    {
        return [
            'beneficiarioPersona' => ['nombres', 'p_apellido', 's_apellido', 'matricula_seguro', 'ci'],
            'beneficiarioPersona.tipoAseguradoPersona' => ['tipo_asegurado'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('persona_nombre_completo')
            ->add('carnet_formatted', function ($carnet) {
                if ($carnet->persona_complemento == null) {
                    return $carnet->persona_ci;
                } else {
                    return $carnet->persona_ci . ' - ' . $carnet->persona_complemento;
                }
            })
            ->add('fecha_afiliacion_formatted', fn(Beneficiario $model) => Carbon::parse($model->fecha_afiliacion)->format('d/m/Y'))
            ->add('alergias')
            ->add('foto_nombre')
            ->add('observacion')
            ->add('vigencia', function ($vvdd) {
                if ($vvdd->vigencia == 'VIGENTE') {
                    return '<span style="color: green" title="VIGENTE"> 
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-check-square-fill" viewBox="0 0 16 16">
  <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm10.03 4.97a.75.75 0 0 1 .011 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.75.75 0 0 1 1.08-.022z"/>
</svg>
                    </span>';
                }
                if ($vvdd->vigencia == 'CESANTE') {
                    return '<span style="color: #fcc225" title="CESANTE"> 
                   <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-exclamation-square-fill" viewBox="0 0 16 16">
  <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm6 4c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995A.905.905 0 0 1 8 4m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
</svg>
                    </span>';
                }
                if ($vvdd->vigencia == 'NO VIGENTE') {
                    return '<span style="color: #e12217" title="NO VIGENTE"> 
                  <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-x-square-fill" viewBox="0 0 16 16">
  <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708"/>
</svg>
                    </span>';
                }
            })
            ->add('preafiliacion')
            ->add('estado', function ($estadoBen) {
                if ($estadoBen->estado) {
                    return "<span style='color: green'>ACTIVO</span>";
                } else {
                    return "<span style='color:red'>INACTIVO</span>";
                }
            })
            ->add('telefono_referencia')
            ->add('nombre_referencia')
            ->add('telefono')
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
            ->add('id_titular')
            ->add('grupo_sanguineo')
            ->add('id_persona')
            // ->add('con_remuneracion_text', function ($remuneracion) {
            //     if ($remuneracion->con_remuneracion) {
            //         return "<span style='color: green'> TRASPASO DE APORTES </span>";
            //     }
            // })
            ->add('tipoBeneficiario', function ($tipoBen) {
                if ($tipoBen->vigencia === 'VIGENTE' || $tipoBen->vigencia === 'CESANTE') {
                    if ($tipoBen->con_remuneracion == false) {
                        return $tipoBen->nombre_asegurado;
                    } else {
                        return $tipoBen->nombre_asegurado . ' <b>-</b> TRANSFERENCIA DE APORTES';
                    }
                } elseif ($tipoBen->vigencia === 'NO VIGENTE') {
                    return 'BENEFICIARIO NO VIGENTE';
                } else {
                    return $tipoBen->nombre_asegurado;
                }
            })


            // ->add('matricula_seguro', fn(Beneficiario $model) => $model->beneficiarioPersona->matricula_seguro)
            /* ->add('matricula_seguro_formated', function ($matricula) {
                $titularInstitucion = TitularInstitucion::where('id_titular', $matricula->id_titular)
                    ->with([
                        'titularInstitucionConInstitucion',
                        'titularInstitucionConInstitucion.codigosInstitucion'
                    ])
                    ->orderBy('id', 'asc')
                    ->where('estado', true)
                    ->first();
                if ($titularInstitucion) {
                    $codigoInst = CodigoInstitucionSsu::where('id_institucion', $titularInstitucion->id_institucion)->exists();
                    if ($codigoInst) {
                        $codBen = $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo . '' . $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_beneficiario;
                        $codBeneficiario = $codBen ?: ' ';
                        if ($codBeneficiario) {
                            return $matricula->matricula_seguro . ' - ' . $codBeneficiario;
                        } else {
                            return $matricula->matricula_seguro;
                        }
                    } else {
                        return $matricula->matricula_seguro;
                    }
                } else {
                    return $matricula->matricula_seguro;
                }
            }) */
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')->index()
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center'),

            Column::make('MATRICULA', 'matricula_seguro')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('CARNET', 'carnet_formatted', 'persona_ci')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('NOMBRE COMPLETO', 'persona_nombre_completo')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3; color:white')
                ->bodyAttribute('text-center')
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
            Column::make('TIPO BENEFICIARIO', 'tipoBeneficiario')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('REQUISITOS', 'estado_requisitos')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('VIGENCIA', 'vigencia')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            //  Column::make('EXÁMEN MÉDICO', ' ')
            //      ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
            //      ->bodyAttribute('text-center')
            //          ->sortable()
            //          ->searchable(),

            /*   Column::action('OPCIONES')
                ->headerAttribute('text-center tablita-cabecera-sin-mover', 'font-size:15px; background-color:#2471a3; color:white;')
                ->bodyAttribute('text-left tablita-cuerpo-sin-mover '), */
            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')

        ];
    }


    public function actions(Beneficiario $row): array
    {
        $titularVigente = Titular::find($row->id_titular);

        $buttons = [];

        if ($row->grupo_sanguineo == null) {
            $buttons[] = Button::add('tipo_sangre')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-droplet-fill" viewBox="0 0 16 16">
                    <path d="M8 16a6 6 0 0 0 6-6c0-1.655-1.122-2.904-2.432-4.362C10.254 4.176 8.75 2.503 8 0c0 0-6 5.686-6 10a6 6 0 0 0 6 6M6.646 4.646l.708.708c-.29.29-1.128 1.311-1.907 2.87l-.894-.448c.82-1.641 1.717-2.753 2.093-3.13"/>
                    </svg>')
                ->class('btn btn-red')
                ->tooltip('AGREGAR TIPO DE SANGRE')
                ->dispatchTo('afiliacion.beneficiarios.beneficiarios-main', 'tipoSangreBeneficiario', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('registrar_tipo_sangre.beneficiarios'));
        }

        $buttons[] = Button::add('ver')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                </svg>')
            ->class('btn btn-blue')
            ->tooltip('VER ASEGURADO BENEFICIARIO')
            ->dispatchTo('afiliacion.beneficiarios.beneficiarios-main', 'verBeneficiario', ['data' => $row->id])
            ->can(allowed: auth()->user()->can('ver.beneficiarios'));

        if ($row->vigencia != 'NO VIGENTE') {
            if ($row->nombre_asegurado === 'BENEFICIARIO ESPOSA - ESPOSO') {
                $buttons[] = Button::add('agregarInstitucion')
                    ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-building-add" viewBox="0 0 16 16">
                      <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0"/>
                      <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6.5a.5.5 0 0 1-1 0V1H3v14h3v-2.5a.5.5 0 0 1 .5-.5H8v4H3a1 1 0 0 1-1-1z"/>
                      <path d="M4.5 2a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-6 3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-6 3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z"/>
                      </svg>')
                    ->class('btn btn-cafe')
                    ->tooltip('AGREGAR INSTITUCIÓN')
                    ->dispatchTo('afiliacion.beneficiarios.beneficiarios-main', 'agregarInstitucionBen', ['data' => $row->id])
                    ->can(allowed: auth()->user()->can('agregar_institucion.beneficiarios'));
            }

            $buttons[] = Button::add('edit')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-lines-fill" viewBox="0 0 16 16">
                <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1z"/>
                </svg>')
                ->class('btn btn-orange')
                ->route('afiliacion.beneficiarios.edit-afiliacion', ['id' => $row->id])
                ->tooltip('EDITAR ASEGURADO BENEFICIARIO')
                ->can(allowed: auth()->user()->can('editar.beneficiarios'));
        }
        if (($row->vigencia == 'NO VIGENTE' && $row->fecha_archivo == null) || $row->vigencia === 'CESANTE') {
            $buttons[] = Button::add('archivo')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-archive-fill" viewBox="0 0 16 16">
                <path d="M12.643 15C13.979 15 15 13.845 15 12.5V5H1v7.5C1 13.845 2.021 15 3.357 15zM5.5 7h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1M.8 1a.8.8 0 0 0-.8.8V3a.8.8 0 0 0 .8.8h14.4A.8.8 0 0 0 16 3V1.8a.8.8 0 0 0-.8-.8z"/>
                </svg>')
                ->class('btn btn-orange')
                ->tooltip('ARCHIVO GRUPO')
                ->dispatchTo('afiliacion.beneficiarios.beneficiarios-main', 'archivoGrupo', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('registrar.archivo_grupo'));
        }
        if ($row->grupo_sanguineo != null) {
            $buttons[] = Button::add('reafiliacion')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus-fill" viewBox="0 0 16 16">
                <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5"/>
                </svg>')
                ->class('btn btn-green')
                ->route('afiliacion.beneficiarios.create.reafiliacion', ['id' => $row->id])
                ->tooltip('REAFILIAR ASEGURADO BENEFICIARIO')
                ->can(allowed: auth()->user()->can('reafiliacion.beneficiarios'));
        }
        if ($row->grupo_sanguineo != null && $row->vigencia != 'NO VIGENTE') {
            $buttons[] = Button::add('carnetizacion')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-credit-card-2-back" viewBox="0 0 16 16">
                        <path d="M11 5.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5z"/>
                        <path d="M2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zm13 2v5H1V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1m-1 9H2a1 1 0 0 1-1-1v-1h14v1a1 1 0 0 1-1 1"/>
                        </svg>')
                ->class('btn btn-green')
                ->tooltip('CARNET')
                ->dispatchTo('afiliacion.beneficiarios.beneficiarios-main', 'carnetizacion', ['data' => $row->id])

                ->can(allowed: auth()->user()->can('carntizacion.beneficiarios'));
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
