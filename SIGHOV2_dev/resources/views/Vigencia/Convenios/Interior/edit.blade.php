@extends('principal')

@section('contenido')

@can('editar.convenios_interior')
<!-- Contenido específico de la vista Index -->
<livewire:vigencia.convenios.interior.convenio-interior-edit :idAuto="$id" />
@else
<p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
            esta sección.</b></em></p>
@endcan
@stop
