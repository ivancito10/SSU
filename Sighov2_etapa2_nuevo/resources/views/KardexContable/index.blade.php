@extends('principal')

@section('contenido')
    @can('menu.reportes_kardex_contable')
        @livewire('kardex-contable.kardex-contable-main')
    @else
        <x-acceso-denegado />
    @endcan
@stop
