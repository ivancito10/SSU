@extends('principal')

@section('contenido')
<!-- Contenido específico de la vista Index -->

@can('registrar.suspension_horarios')
<livewire:plataforma.suspension-horario.suspension-horario-create
    :idMedico="$id" />
@else

<p style="color: red;font-size: 25px;"><em><b>No tienes permisos para
            acceder a
            esta sección.</b></em></p>
@endcan
@stop
