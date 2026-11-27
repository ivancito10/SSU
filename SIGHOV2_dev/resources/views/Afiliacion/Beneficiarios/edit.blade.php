@extends('principal')

@section('contenido')
    <!-- Contenido específico de la vista Index -->
    <livewire:Afiliacion.Beneficiarios.beneficiarios-edit :id_beneficiario="$id"/>   
@stop
