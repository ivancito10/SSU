@extends('principal')

@section('contenido')
    <!-- Contenido específico de la vista Index -->
    
    <livewire:Afiliacion.Titulares.titulares-nuevo :idPersona="$id"/>   
@stop
