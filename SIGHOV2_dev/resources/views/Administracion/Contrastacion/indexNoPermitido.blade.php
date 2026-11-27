@extends('principal')

@section('contenido')
<!-- Contenido específico de la vista Index -->
<x-acceso-denegado />


<div class="container d-flex justify-content-center align-items-center" style="min-height: 10vh;">
    <div class="card-comun p-2">
        <p class="text-muted mt-3">
            USTED NO TIENE PERMISO PARA ACCEDER A ESTA PÁGINA
        </p>
    </div>
</div>

@stop