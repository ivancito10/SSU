<?php

namespace App\Livewire\Vigencia\Preafiliacion;

use App\Models\Administracion\Persona;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\TitularInstitucion;
use Illuminate\Support\Str;
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

final class BusquedaTitularTable extends PowerGridComponent
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
        return Persona::join('administracion.tipo_asegurado as ta', function ($asegurado) {
            $asegurado->on('persona.id_tipo_asegurado', '=', 'ta.id');
        })
            ->select('persona.*', 'ta.tipo_asegurado as taseg')
            ->where('id_tipo_asegurado', 1)
            ->where('persona.afiliado', true);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('carnet', fn(Persona $model) => $model->complemento === null ? $model->ci : $model->ci . '-' . $model->complemento)
            ->add('ci')
            ->add('complemento')
            ->add('nombres')
            ->add('p_apellido')
            ->add('s_apellido')
            ->add('sexo')
            ->add('nombre_completo', fn(Persona $model) => $model->p_apellido . ' ' . $model->s_apellido . ' ' . $model->nombres)
            ->add('id_estado_civil')
            ->add('id_nacimiento_municipio')
            ->add('id_dept_exp')
            ->add('fecha_nacimiento_formatted', fn(Persona $model) => Carbon::parse($model->fecha_nacimiento)->format('d/m/Y'))
            ->add('matricula_seguro')
            ->add('matricula_seguro_formatted', function ($matricula) {
                $matriculaCodigo = $matricula->matricula_seguro;
                if ($matricula->taseg == 'TITULAR') {
                    $titular = Titular::where('id_persona', $matricula->id)->orderBy('id', 'desc')->first();
                    if ($titular) {
                        $titularInstitucion = TitularInstitucion::where('id_titular', $titular->id)
                            ->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])->first();
                        if ($titularInstitucion) {
                            $matriculaCodigo = $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                $matricula->matricula_seguro . ' - ' .
                                $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular :
                                $matricula->matricula_seguro;
                        }
                    }
                } elseif (Str::contains($matricula->taseg, 'BENEFICIARIO')) {
                    if ($matricula->taseg == 'BENEFICIARIO DEL INTERIOR') {
                        $matriculaCodigo = $matricula->matricula_seguro;
                    } else {
                        $beneficiario = Beneficiario::where('id_persona', $matricula->id)->orderBy('id', 'desc')->first();
                        if ($beneficiario) {
                            $titular = Titular::where('id', $beneficiario->id_titular)->first();
                            if ($titular) {
                                $titularInstitucion = TitularInstitucion::where('id_titular', $titular->id)
                                    ->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])->first();
                                if ($titularInstitucion) {
                                    $matriculaCodigo = $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                        $matricula->matricula_seguro . ' - ' .
                                        $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                        $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_beneficiario :
                                        $matricula->matricula_seguro;
                                }
                            }
                        }
                    }
                }
                return $matriculaCodigo;
            })
            ->add('id_tipo_asegurado', fn(Persona $model) => $model->tipoAseguradoPersona->tipo_asegurado)
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
            Column::make('#', 'id')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->index(),

            Column::make('CARNET', 'carnet', 'ci')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('MATRICULA', 'matricula_seguro_formatted', 'matricula_seguro')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),

            Column::make('NOMBRE COMPLETO', 'nombre_completo', 'p_apellido')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->searchable(),
            Column::make('Nombres', 'nombres')
                ->headerAttribute('text-center bg-teal', 'font-size:13px')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable()
                ->hidden(isHidden: true, isForceHidden: true),

            Column::make('Paterno', 'p_apellido')
                ->headerAttribute('text-center bg-teal', 'font-size:13px')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable()
                ->hidden(isHidden: true, isForceHidden: true),

            Column::make('Materno', 's_apellido')
                ->headerAttribute('text-center bg-teal', 'font-size:13px')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable()
                ->hidden(isHidden: true, isForceHidden: true),

            /*Column::make('TIPO DE ASEGURADO', 'taseg')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),*/

            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
        ];
    }

    public function actions(Persona $row): array
    {
        return [
            Button::add('selectPersona')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus-fill" viewBox="0 0 16 16">
                <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5"/>
                </svg>')
                ->class('btn btn-blue')
                //->route('plataforma.agendamiento.plataforma.create', ['id' => $row->id])
                ->tooltip('SELECCIONAR TITULAR')
                ->dispatchTo('vigencia.preafiliacion.preafiliacion-register', 'titularSeleccionado', ['data' => $row->id])
        ];
    }
}
