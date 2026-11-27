<?php

namespace App\Livewire\Afiliacion\Afiliados;

use App\Models\Administracion\Persona;
use App\Models\Afiliacion\Beneficiario;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Footer;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\CodigoInstitucionSsu;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use Illuminate\Support\Collection;

final class AfiliadosTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'personaAfiliados';
    public string $sortField = "persona.id";

    public function setUp(): array
    {
        // $this->showCheckBox();

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

    // comente esto porque al parecer el collection lentea 09/06/2025
    //    public function datasource(): Collection
    //    {
    //        $personas = Persona::where('afiliado', true)
    //            ->whereIn('id_tipo_asegurado', [1, 2, 3, 4, 5, 6, 7, 10, 11, 12, 13])
    //            ->orderByDesc('id')
    //            ->get();
    //
    //        foreach ($personas as $persona) {
    //            $persona->nombreCompleto = trim("{$persona->p_apellido} {$persona->s_apellido} {$persona->nombres}");
    //        }
    //
    //        return $personas;
    //    }
    public function datasource(): Builder
    {
        return Persona::query()
            ->with([
                'tipoAseguradoPersona',
                'PersonaTitular.titularConTitularInstitucion.titularInstitucionConInstitucion.claseAportanteInstitucion',
                'PersonaBeneficiario.beneficiarioConInstitucionBeneficiario.institucionBeneficiarioInstitucion.claseAportanteInstitucion'
            ])
            ->where('afiliado', true)
            ->whereIn('id_tipo_asegurado', [1, 2, 3, 4, 5, 6, 7, 11, 12, 13])
            ->orderByDesc('id');
    }

    // ex conulta 
    /* public function datasource(): Collection
    {
        $personas = Persona::query()
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
            ->distinct()
            ->where(function ($query) {
                $query->where('titular.estado_vigencia', 'VIGENTE')
                    ->orWhere('titular.estado_vigencia', 'CESANTE')
                    ->orWhere('beneficiario.vigencia', 'VIGENTE')
                    ->orWhere('beneficiario.vigencia', 'CESANTE')
                    ->orWhere('estudiante.estado_validar', 'AFILIADO');
            })
            ->whereNotIn('taseg.tipo_asegurado', ['NO ASEGURADO'])
            ->whereRaw("taseg.tipo_asegurado NOT LIKE '%CONVENIO%'")
            ->whereRaw("taseg.tipo_asegurado NOT LIKE '%INTERIOR%'")
            ->select('persona.*', 'taseg.tipo_asegurado as tipoAsegurado')
            ->orderBy('persona.id', 'desc')
            ->get();

        $personas = $personas->map(function ($persona) {
            $persona->matricula_seguro_formatted = $persona->formatted_matricula_seguro;
            $persona->nombreCompleto = $persona->p_apellido . ' ' . $persona->s_apellido . ' ' . $persona->nombres;

            return $persona;
        });


        return $personas;
    }*/
    public function relationSearch(): array
    {
        return [
            'tipoAseguradoPersona' => ['tipo_asegurado'],
        ];
    }
    /* public function formatearMatricula(Persona $persona)
    {
        if ($persona->id_tipo_asegurado == 1) {
            $titular = Titular::where('id_persona', $persona->id)
                ->where('estado', true)
                ->first();
            if ($titular) {
                $institucionTit = TitularInstitucion::with([
                    'titularInstitucionConInstitucion.codigosInstitucion'
                ])
                    ->where('id_titular', $titular->id)
                    ->orderBy('id', 'asc')
                    ->first();

                if ($institucionTit && $institucionTit->titularInstitucionConInstitucion) {
                    $codigoInst = $institucionTit->titularInstitucionConInstitucion->codigosInstitucion;
                    $codTitular = $codigoInst ? $codigoInst->codigo . '' . $codigoInst->tipo_titular : null;
                    return $persona->matricula_seguro . ' ' . ($codTitular ?: '');
                }
            }
        } elseif (in_array($persona->id_tipo_asegurado, [2, 3, 4, 5, 6, 7, 12, 13])) {
            $beneficiario = Beneficiario::where('id_persona', $persona->id)
                ->where('estado', true)
                ->first();
            if ($beneficiario) {
                $institucionTit = TitularInstitucion::with([
                    'titularInstitucionConInstitucion.codigosInstitucion'
                ])
                    ->where('id_titular', $beneficiario->id_titular)
                    ->orderBy('id', 'asc')
                    ->first();

                if ($institucionTit && $institucionTit->titularInstitucionConInstitucion) {
                    $codigoInst = $institucionTit->titularInstitucionConInstitucion->codigosInstitucion;
                    $codBeneficiario = $codigoInst ? $codigoInst->codigo . '' . $codigoInst->tipo_beneficiario : null;
                    return $persona->matricula_seguro . ' ' . ($codBeneficiario ?: '');
                }
            }
        }
        return $persona->matricula_seguro;
    } */
    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('ci')
            ->add('complemento')
            ->add('carnet', function ($carnet) {
                if ($carnet->complemento == null) {
                    return $carnet->ci;
                } else {
                    return $carnet->ci . ' - ' . $carnet->complemento;
                }
            })
            ->add('nombres')
            ->add('p_apellido')
            ->add('s_apellido')
            ->add(
                'nombre_completo',
                fn(Persona $p) =>
                trim("{$p->p_apellido} {$p->s_apellido} {$p->nombres}")
            )
            //->add('matricula_seguro', fn(Persona $p) => $this->formatearMatricula($p))
            ->add('matricula_seguro')
            ->add('sexo')
            ->add('id_estado_civil')
            ->add('id_nacimiento_municipio')
            ->add('id_dept_exp')
            ->add('fecha_nacimiento_formatted', fn(Persona $model) => Carbon::parse($model->fecha_nacimiento)->format('d/m/Y'))
            ->add('id_tipo_asegurado')
            ->add('id_tipo_asegurado_formatted', fn(Persona $model) => $model->tipoAseguradoPersona->tipo_asegurado)
            ->add('es_extranjero')
            ->add('estado_asuss')
            ->add('afiliado')
            ->add('clave_unica')
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3; color:white;')
                ->bodyAttribute('text-center')
                ->index(),

            Column::make('CARNET', 'carnet', 'ci')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                //  ->sortable()
                ->searchable(),

            Column::make('MATRICULA', 'matricula_seguro')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                //->sortable()
                ->searchable(),

            Column::make('NOMBRE COMPLETO', 'nombre_completo')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                //  ->bodyAttribute('text-center')
                //  ->sortable()
                ->searchableRaw("
                TRIM(CONCAT(
                    COALESCE(p_apellido, ''), 
                    ' ', 
                    COALESCE(s_apellido, ''), 
                    ' ', 
                    COALESCE(nombres, '')
                ))
            "),

            Column::make('Nombres', 'nombres')
                ->headerAttribute('text-center bg-teal', 'font-size:13px')
                ->bodyAttribute('text-center')
                // ->sortable()
                ->searchable()
                ->hidden(isHidden: true, isForceHidden: true),

            Column::make('Paterno', 'p_apellido')
                ->headerAttribute('text-center bg-teal', 'font-size:13px')
                ->bodyAttribute('text-center')
                //->sortable()
                ->searchable()
                ->hidden(isHidden: true, isForceHidden: true),

            Column::make('Materno', 's_apellido')
                ->headerAttribute('text-center bg-teal', 'font-size:13px')
                ->bodyAttribute('text-center')
                //->sortable()
                ->searchable()
                ->hidden(isHidden: true, isForceHidden: true),

            Column::make('TIPO ASEGURADO', 'id_tipo_asegurado_formatted')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                //  ->sortable()
                ->searchable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')

        ];
    }

    public function filters(): array
    {
        return [];
    }

    #[\Livewire\Attributes\On('eventoboton')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions(Persona $row): array
    {
        $buttons = [];
        $buttons[] = Button::add('ver')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
            <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
            </svg>')
            ->class('btn btn-blue')
            ->tooltip('VER ASEGURADO')
            ->dispatchTo('afiliacion.afiliados.afiliados-main', 'verAfiliado', ['data' => $row->id])
            ->can(allowed: auth()->user()->can('ver.afiliado_historial'));

        $buttons[] =  Button::add('pdf')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z"/>
        </svg>')
            ->class('btn btn-red')
            ->tooltip('HISTORIAL PDF')
            ->dispatchTo('afiliacion.afiliados.afiliados-main', 'pdfHistorial', ['data' => $row->id])
            ->can(allowed: auth()->user()->can('pdf.afiliado_historial'));

        // $buttons []= Button::add('carnet')
        // ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-credit-card-2-back" viewBox="0 0 16 16">
        // <path d="M11 5.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5z"/>
        // <path d="M2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zm13 2v5H1V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1m-1 9H2a1 1 0 0 1-1-1v-1h14v1a1 1 0 0 1-1 1"/>
        // </svg>')
        // ->class('btn btn-green')
        // ->tooltip(' HISTORIAL CARNET')
        // ->dispatchTo('afiliacion.afiliados.afiliados-main', 'carnetHistorial', ['data' => $row->id]);

        if ($row->id_tipo_asegurado == 1) {
            $buttons[] = Button::add('ficha_afiliacion')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-lines-fill" viewBox="0 0 16 16">
                <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1z"/>
                </svg>')
                ->class('btn btn-lila')
                ->tooltip('FICHA AFILIACIÓN')
                ->dispatchTo('afiliacion.afiliados.afiliados-main', 'ficha_afiliacion', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('ficha_afiliacion.afiliado_historial'));
        }
        if ($row->id_tipo_asegurado != 11) {
            $buttons[] = Button::add('grupo_familiar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
            <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
            </svg>')
                ->class('btn btn-infoDos')
                ->tooltip('GRUPO FAMILIAR')
                ->dispatchTo('afiliacion.afiliados.afiliados-main', 'GrupoFamiliarAfiliado', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('grupo_familiar.afiliado_historial'));
        }
        return $buttons;
    }
}
