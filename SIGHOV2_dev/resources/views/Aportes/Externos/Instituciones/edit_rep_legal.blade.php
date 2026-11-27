@extends('principal')

@section('contenido')
    <!-- Contenido específico de la vista Index -->
    
  <livewire:Aportes.Externos.institucion.institucion-edit-representante :id_institucion="$id"/>
@stop