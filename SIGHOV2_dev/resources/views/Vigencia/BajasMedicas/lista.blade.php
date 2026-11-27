@extends('principal')

@section('contenido')
<!-- Contenido específico de la vista Index -->

@can('registrar.baja_medica_hologacion')
<div>
    <livewire:vigencia.bajas-medicas.bajas-medicas-register :idAfiliado="$id" />
</div>
@else
<p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
            esta sección.</b></em></p>
@endcan

@stop
