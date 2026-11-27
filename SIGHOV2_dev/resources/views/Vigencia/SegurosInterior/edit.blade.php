@extends('principal')

@section('contenido')

@can('editar.seguros_interior')
<!-- Contenido específico de la vista Index -->
<livewire:vigencia.seguro-interior.seguro-interior-edit :idInstConvenio="$id" />
@else
<p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
            esta sección.</b></em></p>
@endcan
@stop