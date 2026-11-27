@extends('principal')

@section('contenido')
<!-- Contenido específico de la vista Index -->

@can('editar.institucion_convenio')
<livewire:vigencia.instituciones.institucion-convenio-edit
    :idInstConvenio="$id" />
@else
<p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
            esta sección.</b></em></p>
@endcan
@stop