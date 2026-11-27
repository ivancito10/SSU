@extends('principal')

@section('contenido')
    <!-- Contenido específico de la vista Index -->

@can('registro.instituciones_externas')
 @livewire('Aportes.externos.institucion.institucion-register-ext')
@else

    <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a esta sección.</b></em></p>
@endcan
@stop
