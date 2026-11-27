@extends('principal')

@section('contenido')
    <!-- Contenido específico de la vista Index -->
    
    <livewire:Aportes.Institucion.institucion-edit :id_institucion="$id"/>
@stop