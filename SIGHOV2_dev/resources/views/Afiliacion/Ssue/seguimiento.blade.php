@extends('principal')

@section('contenido')

    @if ($beneficiario)
        <livewire:Afiliacion.ssue.ssue-no-seguimiento />
    @else
        @if (!$estudiante || $estudiante->reafiliacion)
            <livewire:Afiliacion.ssue.ssue-no-seguimiento />
        @elseif ($estudiante->estado_validar == 'OBSERVADO')
            <livewire:Afiliacion.ssue.ssue-editar />
        @else
            <livewire:Afiliacion.ssue.ssue-seguimiento />
        @endif

        {{--  @if ($estudiante)
            @if ($estudiante->estado_validar == 'OBSERVADO')
                <livewire:Afiliacion.ssue.ssue-editar />
            @elseif($estudiante->estado_validar == 'AFILIADO' && $estudiante->estado == true && $estudiante->estado_vigencia == false)
                <livewire:Afiliacion.ssue.ssue-no-seguimiento />
            @else
                <livewire:Afiliacion.ssue.ssue-seguimiento />
            @endif
        @else
            <livewire:Afiliacion.ssue.ssue-no-seguimiento />
        @endif --}}

        {{-- -    @if ($estudiante)
          @if ($estudiante->estado_validar == 'OBSERVADO')
              <livewire:Afiliacion.ssue.ssue-editar />
          @else
              @if ($estudiante->estado_vigencia === false)
                  <livewire:Afiliacion.ssue.ssue-no-seguimiento />
              @else
                  <livewire:Afiliacion.ssue.ssue-seguimiento />
              @endif
          @endif
      @else
          <livewire:Afiliacion.ssue.ssue-no-seguimiento />
      @endif
 --}}
    @endif

@stop
