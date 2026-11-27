@extends('principal')

@section('contenido')
<!-- Contenido específico de la vista Index -->

@can('editar.prestaciones_interior')
<livewire:vigencia.solicitud-prestacion-medica.solicitud-prestacion-medica-edit
    :idSolicitud="$id" />
@else
<p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
            esta sección.</b></em></p>
@endcan
@stop
