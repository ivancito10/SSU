<?php

namespace App\Livewire\Aportes\Externos\Institucion;

use App\Models\Aportes\Institucion;
use App\Livewire\Aportes\Externos\Institucion\InstitucionMainExt;
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

final class InstitucionTableExt extends PowerGridComponent
{
    use WithExport;

     public $institucionId;
     public $modalVerExt = false;
     public string $tableName = 'InstitucionesRegistroExterno';
     public string $sortField = 'institucion.id';
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
        return Institucion::query()
            ->join('aportes.clase_aportante as clase', function ($clase){
                $clase->on('institucion.id_clase_aportante', '=', 'clase.id');
            })
            ->join('administracion.persona', function ($persona){
                $persona->on('institucion.id_persona', '=', 'persona.id');
            })
            ->select('institucion.*', 'clase.tipo as claseaportante_tipo', 
                'persona.nombres as persona_nombres', 'persona.p_apellido as persona_p_apellido', 
                'persona.s_apellido as persona_s_apellido')
            ->where('id_usuario_responsable', auth()->user()->id);
    }

    public function relationSearch(): array
    {
        return [

            'claseAportanteInstitucion' => ['tipo'],
            'personaRepresentanteInstitucion' => ['nombres', 'p_apellido', 's_apellido'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('nit')
            ->add('nro_empleador')
            ->add('actividad_empleador')
            ->add('id_clase_aportante')
            ->add('acronimo')
            ->add('fecha_afiliacion')
            ->add('fecha_afiliacion_formatted', fn (Institucion $model) => Carbon::parse($model->fecha_afiliacion)->format('d/m/Y'))
            ->add('correo')
            ->add('telefono')
            ->add('id_persona')
            ->add('persona_nombre_completo', function ($nombre){
                if($nombre->persona_p_apellido == null){
                    return $nombre->persona_s_apellido. ' ' . $nombre->persona_nombres;
                } elseif($nombre->persona_s_apellido == null){
                    return $nombre->persona_p_apellido. ' ' . $nombre->persona_nombres;
                } else {
                    return $nombre->persona_p_apellido. ' ' .$nombre->persona_s_apellido. ' ' .$nombre->persona_nombres;
                }
            })
            ->add('id_zona')
            ->add('direccion')
            ->add('numero')
            ->add('nombre')
            ->add('estado')
            ->add('estado_formatted', function ($estado) {
                if ($estado->estado) {
                    return '<span> <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="green" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </svg></span>';
                } else {
                    return '<span ><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#c0392b" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                    </svg></span>';
                }
            })
            ->add('estado_validado')
            ->add('estado_validado_formatted', function ($dish) {
                if ($dish->estado_validado) {
                    return '<span style="color: green"> 
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-check-square" viewBox="0 0 16 16">
                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                        <path d="M10.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z"/>
                        </svg>
                    </span>';
                } else {
                    return '<span style="color: orange"> 
                    
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-exclamation-diamond" viewBox="0 0 16 16">
                    <path d="M6.95.435c.58-.58 1.52-.58 2.1 0l6.515 6.516c.58.58.58 1.519 0 2.098L9.05 15.565c-.58.58-1.519.58-2.098 0L.435 9.05a1.48 1.48 0 0 1 0-2.098zm1.4.7a.495.495 0 0 0-.7 0L1.134 7.65a.495.495 0 0 0 0 .7l6.516 6.516a.495.495 0 0 0 .7 0l6.516-6.516a.495.495 0 0 0 0-.7L8.35 1.134z"/>
                    <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                    </svg>
                    </span>';
                }
            })
            ->add('tgn')
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at')
            ->add('id_usuario_responsable');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id') 
                ->index()
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable(),
            
            

            Column::make('INSTITUCIÓN', 'nombre')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
            ->sortable()
            ->searchable(),

            Column::make('NIT', 'nit')             
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
            ->bodyAttribute('text-left')
            ->sortable()
            ->searchable(),

            Column::make('REP. LEGAL', 'persona_nombre_completo', 'persona_p_apellido')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
            ->sortable()
            ->searchable(),

            Column::make('CLASE APORTANTE', 'claseaportante_tipo')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
            ->sortable()
            ->searchable(),
            Column::make('ACRONIMO', 'acronimo')             
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
            ->bodyAttribute('text-left')
            ->sortable()
            ->searchable(),
 
            Column::make('ESTADO', 'estado_formatted', 'estado')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
            ->bodyAttribute('text-center')
            ->sortable()
            ->searchable(),
            Column::make('VALIDACIÓN', 'estado_validado_formatted', 'estado_validado')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
            ->bodyAttribute('text-center')
            ->sortable()
            ->searchable(),  


            Column::action('OPCIONES')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
            ->bodyAttribute('text-center'),
        ];
    }

    public function filters(): array
    {
        return [
            //Filter::datepicker('fecha_afiliacion'),
        ];
    }

    

    #[\Livewire\Attributes\On('verExt')]
    public function verExt($rowId, $abrirModalVerExt): void
    {
        $this->modalVerExt = $abrirModalVerExt;
        $this->institucionId = $rowId;
        $this->dispatch('vistaExt', ['modalVerExt' => $this->modalVerExt, 'institucionId' => $this->institucionId])
        ->to(InstitucionMainExt::class);
    }

    public function actions(Institucion $row): array
    {
        $buttons= [
            Button::add('verExt')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                        <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                        </svg>')
                ->class('btn btn-blue') 
                ->tooltip('VER INSTITUCIÓN')
                ->dispatchTo('aportes.externos.institucion.institucion-main-ext', 'abrirInstitucionExt',['data' => $row->id])
                ->can(allowed:auth()->user()->can('ver.instituciones_externas')),
            
            Button::add('editarExt')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        fill="currentColor"
                        class="bi bi-pencil-square"
                        viewBox="0 0 16 16">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                    </svg>')
                ->class('btn btn-orange')
                ->route('aportes.externos.institucion.edit-externo', ['id' => $row->id])
                ->tooltip('MODIFICAR INSTITUCIÓN')
                ->can(allowed:auth()->user()->can('editar.instituciones_externas')),

            Button::add('editarRepExt')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                </svg>')
                ->class('btn btn-lila') 
                ->route('aportes.externos.institucion.edit-rep-legal-externo', ['id' => $row->id])
                ->tooltip('MODIFICAR REPRESENTANTE LEGAL')
                ->can(allowed:auth()->user()->can('editar_representante.instituciones_externas')),
                
        ];
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
