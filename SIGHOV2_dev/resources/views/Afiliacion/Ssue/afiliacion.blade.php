@extends('principal')

@section('contenido')
    {{-- - @if ($estudiante == null)
        <livewire:Afiliacion.ssue.ssue-register />
    @elseif($estudiante->estado_vigencia == false && $estudiante->estado == true)
        <livewire:Afiliacion.ssue.ssue-register />
    @elseif($estudiante->estado_vigencia == true && $estudiante->estado == true)
        <livewire:Afiliacion.ssue.ssue-no-autorizado />
    @endif --}}

    {{--  @if ($estudiante == null)
        <livewire:Afiliacion.ssue.ssue-register />
    @elseif($estudiante->estado_validar == 'AFILIADO' && $estudiante->estado == true && $estudiante->estado_vigencia == false)
        <livewire:Afiliacion.ssue.ssue-register />
    @elseif($estudiante->estado == false)
       <!--  prbar si da a la larga, analizar -->
        <livewire:Afiliacion.ssue.ssue-register />
    @else
        <livewire:Afiliacion.ssue.ssue-no-autorizado />
    @endif --}}

    @if ($estudiante == null || $estudiante->reafiliacion == true)
        <livewire:Afiliacion.ssue.ssue-register />
    @else
        <livewire:Afiliacion.ssue.ssue-no-autorizado />
    @endif
@stop
