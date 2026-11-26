@extends('principal')

@section('contenido')
    @livewire('enfermeria.inyectables.inyectables-register', ['id' => $id ?? null])
@stop