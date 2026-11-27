@extends('principal')

@section('contenido')

@can('editar.convenios_interior')
<!-- Contenido específico de la vista Index -->
@livewire('Vigencia.Convenios.Interior.convenio-interior-register')
@else
<p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
            esta sección.</b></em></p>
@endcan
@stop
