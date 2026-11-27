@extends('principal')

@section('contenido')
    <!-- Contenido específico de la vista Index -->
  

@can('menu.tasa_interes')
    @livewire('Aportes.tasa-interes.tasa-interes-main')
@else

    <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a esta sección.</b></em></p>
@endcan

@stop
