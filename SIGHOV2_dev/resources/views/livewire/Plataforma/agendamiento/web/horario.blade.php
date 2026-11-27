<!-- EN ESTA SECCIÓN SE REGISTRAN LOS DATOS -->
<div>
    <div class="row">
        <div div class="col-lg-10 col-md-10 col-sm-9 col-8"> 
            <div class="card cabecera">
                <b> <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                    </svg>
                    4. <em> Seleccione la hora:</em> </b>
            </div> 
        </div>
        <div class="col-lg-2 col-md-2 col-sm-3 col-4" style="text-align:right;">
            <x-salir-button wire:click="retroceder">ATRÁS</x-salir-button>
        </div>

    </div>

    <div class="row">
        @foreach ($listaHorarios as $key => $horario)
            <div class="col-lg-3 col-md-3 col-sm-3 col-4 mt-2">

                @if ($horario['estado'] == 'LIBRE')
                    @if ($horario['horario_disponible'] === false && !Auth::user()->hasPermissionTo('super-admin-only'))
                        <button type="button" class="btn btn-outline-primary d-block mx-auto mb-2"
                            {{ $horario['hora'] < $horaActualParaVerificacion && $switchMismoDia ? 'disabled' : '' }}
                            disabled wire:click="noPermitirAgenda" wire:loading.attr="disabled" title="ELEGIR"
                            style="width: 100%; height: 100%;  border-radius:8px;">
                            <b>{{ $horario['hora'] }} - SIGUIENTE TURNO</b>
                        </button>
                    @else
                        <button type="button" class="btn btn-outline-success d-block mx-auto mb-2"
                            {{ $horario['hora'] < $horaActualParaVerificacion && $switchMismoDia ? 'disabled' : '' }}
                            wire:click="guardarAgenda('{{ $horario['hora'] }}')" wire:loading.attr="disabled"
                            title="ELEGIR" style="width: 100%; height: 100%;  border-radius:8px;">
                            <b>{{ $horario['hora'] }} </b>
                        </button>
                    @endif
                @elseif($horario['estado'] == 'OCUPADO')
                    <button type="button" class="btn btn-danger d-block mx-auto mb-2" disabled
                        style="width: 100%; height: 100%;  border-radius:8px;">
                        <b>{{ $horario['hora'] }}</b> <em> - ocupado</em>
                    </button>
                @else
                    <button type="button" class="btn btn-warning d-block mx-auto mb-2" disabled
                        style="width: 100%; height: 100%;  border-radius:8px;">
                        <b>{{ $horario['hora'] }}</b><em> - suspendido</em>
                    </button>
                @endif
            </div>
        @endforeach
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" wire:loading wire:target="guardarAgenda">
            <x-cargando-dos />
        </div>
    </div>
    <br>


    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" wire:loading wire:target="retroceder">
            <x-cargando-dos />
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" wire:loading wire:target="guardarAgenda">
            <x-cargando-dos />
        </div>
    </div>

</div>
