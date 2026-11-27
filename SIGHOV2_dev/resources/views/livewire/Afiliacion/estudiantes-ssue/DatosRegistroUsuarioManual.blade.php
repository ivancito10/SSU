<div class="row">
    <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
        <label for="nombre">NOMBRES </label>
        <input type="text" class="form-control @error('nombre') is-invalid @enderror" placeholder=" Nombre"
            style=" border-radius:10px;" wire:model.live="nombre" name="nombre" id="nombre"
            oninput="this.value = this.value.toUpperCase()" wire:change="GenerarMatricula">
        @error('nombre')
            <div class="text-danger">
                <b>{{ $message }}</b>
            </div>
        @enderror
    </div>
    <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
        <label for="ap_paterno">PRIMER APELLIDO </label>
        <input type="text" class="form-control input-comun" placeholder=" Paterno" wire:model.live="ap_paterno"
            name="ap_paterno" id="ap_paterno" oninput="this.value = this.value.toUpperCase()"
            wire:change="GenerarMatricula">
    </div>
    <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
        <label for="ap_materno">SEGUNDO APELLIDO </label>
        <input type="text" class="form-control" style=" border-radius:10px;" placeholder=" Materno"
            wire:model.live="ap_materno" name="ap_materno" id="ap_materno"
            oninput="this.value = this.value.toUpperCase()" wire:change="GenerarMatricula">
    </div>
</div>

<div class="row">
    <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
        <label for="ci">CARNET </label>
        <input type="number" class="form-control @error('ci') is-invalid @enderror" placeholder=" C.I"
            wire:model.live="ci" name="ci" style=" border-radius:10px;" id="ci">
    </div>
    <div class='col-lg-2 col-md-4 col-sm-6 col-xs-12'>
        <label for="complemento">COMP. </label>
        <input type="text" class="form-control" placeholder=" Complemento" wire:model.live="complemento"
            style=" border-radius:10px;" name="complemento" id="complemento"
            oninput="this.value = this.value.toUpperCase()">
    </div>
    <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
        <label for="tipo_doc">TIPO C.I.:</label>
        <select wire:model.live="tipo_doc" name="tipo_doc" id="tipo_doc"
            class="form-control  @error('tipo_doc') is-invalid @enderror" style="width: 100%; border-radius:10px;"
            tabindex="-1" required wire:change='TipoDocumento'>
            <option selected="" value="">Seleccione.. </option>
            <option value="0">NACIONAL</option>
            <option value="1">EXTRANJERO</option>
        </select>
    </div>
    <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
        <label for="zona">EXPEDIDO.</label>
        <select wire:model.live="expedido_ci" name="zona" id="zonas-select"
            class="form-control select2 @error('expedido_ci') is-invalid @enderror"
            style="width: 100%; border-radius:10px;" tabindex="-1" required>
            <option selected="">Seleccione.. </option>
            @foreach ($this->departamento as $dep)
                <option value="{{ $dep->id }}">{{ $dep->sigla }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="row">
    <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
        <label for="fecha_nacimiento">FECHA NAC. </label>
        <input type="date" class="form-control  @error('fecha_nacimiento') is-invalid @enderror"
            placeholder=" Fecha Nacimiento" wire:model.live="fecha_nacimiento" name="fecha_nacimiento"
            max="{{ now()->toDateString() }}" min="1980-01-01" style=" border-radius:10px;" id="fecha_nacimiento"
            wire:change="GenerarMatricula">
        @error('fecha_nacimiento')
            <div class="text-danger">
                <b>{{ $message }}</b>
            </div>
        @enderror
    </div>
    <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
        <label for="sexo"> SEXO</label>
        <div class="col-sm-6 d-flex align-items-center">
            <label class="mr-2"> <strong style="color:#2980b9">M</strong></label>
            <div class="custom-control custom-switch custom-switch-off-info custom-switch-on-pink">
                <input wire:model="sexo" name="sexo" type="checkbox" class="custom-control-input"
                    id="customSwitch2" wire:change="GenerarMatricula">
                <label class="custom-control-label" for="customSwitch2"
                    style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
            </div>
            <label class="ml-2"><strong style="color:#e452b9">F</strong></label>
        </div>
    </div>

    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
        <label for="matricula"> MATRICULA</label>
        <input class="form-control" type="text" wire:model.live="matricula" style=" border-radius:10px;"
            disabled>
    </div>
</div>

@if ($es_extranjero == 0)
    <div class="row">
        <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
            <label for="zona">DEP. NAC.</label>
            <select wire:model.live="dep_nacimiento" name="zona" id="zonas-select"
                class="form-control select2 @error('dep_nacimiento') is-invalid @enderror"
                style="width: 100%;border-radius:10px;" tabindex="-1" required wire:change='ObtenerProvincia()'>
                <option selected="selected">Seleccione.. </option>
                @foreach ($this->departamento as $dep)
                    <option value="{{ $dep->id }}">{{ $dep->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
            <label for="zona">PRO. NAC.</label>
            <select wire:model.live="pro_nacimiento" name="zona" id="zonas-select"
                class="form-control select2 @error('pro_nacimiento') is-invalid @enderror"
                style="width: 100%;border-radius:10px;" tabindex="-1" required wire:change='ObtenerMunicipio()'>
                <option selected="selected">Seleccione.. </option>
                @if ($this->provincias)
                    @foreach ($this->provincias as $pro)
                        <option value="{{ $pro->id }}">{{ $pro->nombre }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
            <label for="zona">MUN. NAC.</label>
            <select wire:model.live="mun_nacimiento" name="zona" id="zonas-select"
                class="form-control select2 @error('mun_nacimiento') is-invalid @enderror"
                style="width: 100%;border-radius:10px;" tabindex="-1" required wire:change="GenerarMatricula">
                <option selected="selected">Seleccione.. </option>
                @if ($this->municipios)
                    @foreach ($this->municipios as $mun)
                        <option value="{{ $mun->id }}">{{ $mun->nombre }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
@endif
