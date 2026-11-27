@extends('principal')

@section('contenido')
    <!-- Contenido específico de la vista Index -->
    <livewire:Afiliacion.beneficiarios.beneficiario-nuevo :idTitular="$id" />
@stop
