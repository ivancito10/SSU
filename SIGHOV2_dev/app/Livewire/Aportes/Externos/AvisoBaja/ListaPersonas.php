<?php

namespace App\Livewire\Aportes\Externos\AvisoBaja;

use App\Models\Administracion\Persona;
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
use Illuminate\Support\Facades\DB;
use App\Models\Aportes\Institucion;

final class ListaPersonas extends PowerGridComponent
{
    use WithExport;
    public $idPersona;
    public string $sortField = 'persona.p_apellido';
    public string $sortDirection = 'asc';

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
        $institucion_user = Institucion::where('estado_validado', '=', true)
            ->where('id_usuario_aviso_baja', '=', auth()->user()->id)
            ->first();

        if ($institucion_user) {
            $id_institucion = $institucion_user->id; // Variable local correcta

            $idPersonas = DB::select("SELECT DISTINCT id_persona FROM aportes.aporte_i WHERE id_institucion = ? ORDER BY id_persona ASC", [$id_institucion]); // Usar $id_institucion

            $idPersonasArray = array_map(function ($item) {
                return $item->id_persona;
            }, $idPersonas);

            return Persona::whereIn('id', $idPersonasArray);
        } else {
            // Manejar el caso en que no se encuentra la institución del usuario
            return Persona::whereIn('id', []); // Devuelve una consulta vacía
        }
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('ci')
            ->add('complemento')
            ->add('nombres')
            ->add('p_apellido')
            ->add('s_apellido')
            ->add('sexo')
            ->add('id_estado_civil')
            ->add('id_nacimiento_municipio')
            ->add('id_dept_exp')
            ->add('fecha_nacimiento', function ($row) {
                return \Carbon\Carbon::parse($row->fecha_nacimiento)->format('d/m/Y');
            })
            ->add('matricula_seguro')
            ->add('id_tipo_asegurado')
            ->add('es_extranjero')
            ->add('estado_asuss', function ($dish) {
                if ($dish->estado_asuss) {
                    return "<span> <b>VALIDADO </b> </span>";
                } else {
                    return "<span> PENDIENTE</span>";
                }
            })
            ->add('afiliado')
            ->add('grupo_sanguineo')
            ->add('alergias')
            ->add('clave_unica')
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->index(),


            Column::make('AP. PATERNO', 'p_apellido')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),

            Column::make('AP. MATERNO', 's_apellido')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),

            Column::make('NOMBRES', 'nombres')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
            ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),

            Column::make('CARNET', 'ci')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),


            Column::make('FECHA NACIMIENTO',  'fecha_nacimiento')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center')    
                ->sortable(),

           

            Column::make('ASSUS (ESTADO)', 'estado_asuss')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
            ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            

            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center')
        ];
    }

    public function filters(): array
    {
        return [
            
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(Persona $row): array
    {
        return [
            Button::add('verExt')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                </svg>')
                ->class('btn btn-green') 
                ->tooltip('REGISTRAR BAJA')
                ->dispatchTo('aportes.externos.aviso-baja.aviso-baja-register', 'registrarBaja',['data' => $row->id])
                //->can(allowed:auth()->user()->can('ver.instituciones_externas'))
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
