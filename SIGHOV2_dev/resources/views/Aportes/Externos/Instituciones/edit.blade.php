@extends('principal')

@section('contenido')
    <!-- Contenido específico de la vista Index -->
    
    <livewire:Aportes.Externos.institucion.institucion-edit-ext :id_institucion="$id"/>
@stop