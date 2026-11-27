<?php

namespace App\Livewire\Afiliacion\Certificados\Afiliado;

use App\Models\Afiliacion\CertificadoAfiliacion;
use App\Models\Administracion\Persona;
use App\Services\GrupoFamiliarPersona;
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
use Symfony\Component\CssSelector\Node\FunctionNode;

final class AfiliadoTable extends PowerGridComponent
{
    use WithExport;
    public string $sortField = 'certificado_afiliacion.id';
    public string $sortDirection = 'desc';
    public $id_usuario, $id_persona, $ids;

    public function setUp(): array
    {
        //$this->showCheckBox();

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
    public function grupoFamiliar($id_tipo_asegurado, $id_persona_buscar)
    {
        $servicio = new GrupoFamiliarPersona();
        if ($id_tipo_asegurado == 1) {
            $familiares = collect($servicio->encontrarTitular($id_persona_buscar));
            if (!empty($familiares)) {
                return $familiares->pluck('id')->toArray();
            }
        }

        return [$id_persona_buscar];
        // dd($familiares);
    }

    public function datasource(): Builder
    {

        $this->id_usuario = auth()->user()->id;
        $this->id_persona = auth()->user()->id_persona;
        //dd($this->id_persona);
        $persona = Persona::find($this->id_persona);

        $this->ids = $this->grupoFamiliar($persona->id_tipo_asegurado, $persona->id);
        //dd($ids);
        return CertificadoAfiliacion::query()
            ->join('administracion.persona as p', 'certificado_afiliacion.id_persona', '=', 'p.id')
            ->where(function ($q) {
                $q->whereIn('certificado_afiliacion.id_persona', (array) $this->ids)
                    ->orWhere('certificado_afiliacion.id_user_created', $this->id_usuario);
            })
            ->select(
                'certificado_afiliacion.*',
                'p.nombres as p_nombres',
                'p.p_apellido as p_p_apellido',
                'p.s_apellido as p_s_apellido',
                'p.matricula_seguro as p_matricula',
                'p.ci as p_ci',
                'p.complemento as p_complemento'
            );
    }

    public function relationSearch(): array
    {
        return [
            'personaCertificadoAfiliacion' => ['nombres', 'p_apellido', 's_apellido', 'matricula_seguro', 'ci'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('nro_cite')
            ->add('gestion')
            ->add('fecha_emision_formatted', fn(CertificadoAfiliacion $model) => Carbon::parse($model->fecha_emision)->format('d/m/Y'))
            ->add('fecha_caducidad_formatted', fn(CertificadoAfiliacion $model) => Carbon::parse($model->fecha_caducidad)->format('d/m/Y'))
            ->add('codigo_seguridad')
            ->add('cod_certificado')
            ->add('id_persona')
            ->add('nombre_completo', function ($persona) {
                if ($persona->p_p_apellido == null) {
                    return $persona->p_s_apellido . ' ' . $persona->p_nombres;
                } elseif ($persona->p_s_apellido == null) {
                    return $persona->p_p_apellido . ' ' . $persona->p_nombres;
                } else {
                    return $persona->p_p_apellido . ' ' . $persona->p_s_apellido . ' ' . $persona->p_nombres;
                }
            })
            ->add('carnet_formatted', function ($carnet) {
                if ($carnet->p_complemento == null) {
                    return $carnet->p_ci;
                } else {
                    return $carnet->p_ci . ' - ' . $carnet->p_complemento;
                }
            })
            ->add('motivo')
            ->add('estado', function ($est) {
                if ($est->estado) {
                    return '<span style="color: green" title="ACTIVO"> <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
</svg>
                    </span>';
                } else {
                    return '<span style="color: orange" title="INACTIVO>
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
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
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center'),

            Column::make('COD. CERTIFICADO', 'cod_certificado')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('AFILIADO', 'nombre_completo', 'p_p_apellido')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->sortable()
                ->searchableRaw("
                TRIM(CONCAT(
                    COALESCE(p.p_apellido, ''), 
                    ' ', 
                    COALESCE(p.s_apellido, ''), 
                    ' ', 
                    COALESCE(p.nombres, '')
                ))
            "),

            Column::make('CARNET', 'carnet_formatted', 'p_ci')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->sortable()
                ->searchable(),

            Column::make('MATRICULA', 'p_matricula')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->sortable()
                ->searchable(),

            Column::make('FECHA EMISION', 'fecha_emision_formatted', 'fecha_emision')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('FECHA CADUCIDAD', 'fecha_caducidad_formatted', 'fecha_caducidad')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('ESTADO', 'estado')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
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

    public function actions(CertificadoAfiliacion $row): array
    {
        return [
            Button::add('edit')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z"/>
                    </svg>')
                ->id()
                ->class('btn btn-blue')
                ->tooltip('PDF COPIA')
                ->dispatchTo('afiliacion.certificados.afiliado.afiliado-main', 'pdf_copia',  ['data' => $row->id])
        ];
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
