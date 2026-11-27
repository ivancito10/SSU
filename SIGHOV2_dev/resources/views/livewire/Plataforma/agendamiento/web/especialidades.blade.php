<!-- EN ESTA SECCIÓN SE REGISTRAN LOS DATOS -->
<div>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <div class="row">
        <div class="col-lg-10 col-md-10 col-sm-9 col-8" > 
            <div class="card cabecera">
                <b> <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                    </svg>
                    2. <em> Elija la especialidad:</em> </b>
            </div> 
        </div> 
         
        <div class="col-lg-2 col-md-2 col-sm-3 col-4" style="text-align:right;">
            <x-salir-button wire:click="retroceder">ATRÁS</x-salir-button>
        </div>

    </div>
    <div class="row ">

        <!-- <div class="col-lg-9 col-md-9 col-sm-12 col-12 mt-2">
            <input class="form-control" id="especialidadBusqueda" style="border-radius:20px;color:black;"
                placeholder="Ingrese la especialidad para realizar su busqueda" wire:model="especialidadBusqueda"
                wire:input="buscarEspecialidad" wire:keydown.enter="buscarEspecialidad">
        </div> -->

        <div style="height: 700px; overflow-y: auto;">
            <div class="row">
                @foreach ($especialidadesDisponibles as $esp)
                    <div class="col-lg-4 col-md-12 col-sm-12 col-12 mt-2 ">
                        <button class="btn btn-outline-primary d-block mx-auto mb-2 btn-especialidad"
                            wire:click="especialidadSeleccionada({{ $esp['id_especialidad'] }})" title="ELEGIR"
                            style="width: 100%; height: 100%; border-radius:8px;">

                            <i class="{{ $iconosEspecialidades[$esp['especialidad']] ?? 'bi bi-clipboard2-pulse' }}"
                                style="font-size: 1.2rem;"></i>

                            <b> {{ $esp['especialidad'] }} </b>
                        </button>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  " wire:loading wire:target="especialidadSeleccionada">
            <x-cargando-dos />
        </div>
        <br>



        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" wire:loading wire:target="retroceder">
            <x-cargando-dos />
        </div>

    </div>
