<div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right ">
            <p style="color:#2471a3" class="h5">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path
                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                    <path fill-rule="evenodd"
                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                </svg>
                <em class="ml-2"><b>Ingrese y complete los siguientes datos:</b> </em>
            </p>
        </div>
    </div>
    <div style="height: 0.5rem;"></div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <label for="ubicacion">UBICACIÓN: <b style="color:#cb4335;">*</b></label>
            <select id="ubicacion" wire:model="ubicacion" class="form-control" wire:change='VerUbicacion()'
                style="border-radius:8px;">
                <option value="0" selected>Seleccione la Ubicación</option>
                @foreach ($this->ubicaciones as $ubicacion)
                    <option value="{{ $ubicacion->ubicacion }}">{{ $ubicacion->ubicacion }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
            <label for="piso"> PISO: <b style="color:#cb4335;">*</b></label>
            <select id="piso" wire:model="piso" class="form-control" wire:change='direccionSeleccionar()'
                style="border-radius:8px;">
                <option value="0" selected>Seleccione el piso</option>
                @foreach ($this->ubicaciones_pisos as $pisos)
                    <option value="{{ $pisos->id }}">{{ $pisos->piso }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
            <label for="nro_consultorio"> NÚMERO DE CONSULTORIO: <b style="color:#cb4335;">*</b></label>
            <input id="nro_consultorio" class="form-control" wire:model=nro_consultorio
                oninput="this.value = this.value.toUpperCase()"placeholder="Ingrese el nro. consultorio"
                style="border-radius:8px;">
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <label for="direccion"> DIRECCIÓN: <b style="color:#cb4335;">*</b></label>
            <textarea row=3 id="direccion" class="form-control" disabled wire:model=direccion
                style=" background-color:#f8f9f9; border-radius:8px;" placeholder="Dirección"> </textarea>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="right">
            <x-cancel-button wire:click="cancelar">CANCELAR</x-cancel-button>
            <x-save-button wire:click="guardar">GUARDAR</x-save-button>
        </div>
    </div>
</div>
