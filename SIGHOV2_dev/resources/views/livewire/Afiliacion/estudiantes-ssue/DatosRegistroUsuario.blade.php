<div class="row">
    <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
        <label for="nombre">NOMBRES: </label>
        <input style=" border-radius:10px;" type="text" class="form-control @error('nombre') is-invalid @enderror"
            disabled placeholder=" Nombre" wire:model.live="nombre" name="nombre" id="nombre"
            oninput="this.value = this.value.toUpperCase()">
    </div>
    <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
        <label for="ap_paterno">PRIMER APELLIDO: </label>
        <input style=" border-radius:10px;" type="text" class="form-control" disabled placeholder=" Paterno"
            wire:model.live="ap_paterno" name="ap_paterno" id="ap_paterno"
            oninput="this.value = this.value.toUpperCase()">
    </div>
    <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
        <label for="ap_materno">SEGUNDO APELLIDO: </label>
        <input style=" border-radius:10px;" type="text" class="form-control" disabled placeholder=" Materno"
            wire:model.live="ap_materno" name="ap_materno" id="ap_materno"
            oninput="this.value = this.value.toUpperCase()">
    </div>
</div>
<div class="row">
    <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
        <label for="ci">CARNET: </label>
        <input style=" border-radius:10px;" type="number" class="form-control" disabled placeholder=" C.I"
            wire:model.live="ci" name="ci" id="ci" oninput="this.value = this.value.toUpperCase()">
    </div>
    <div class='col-lg-2 col-md-4 col-sm-6 col-xs-12'>
        <label for="complemento">COMP.: </label>
        <input style=" border-radius:10px;" type="text" class="form-control" disabled placeholder=" Complemento"
            wire:model.live="complemento" name="complemento" id="complemento"
            oninput="this.value = this.value.toUpperCase()">
    </div>
    <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
        <label for="tipo_doc">TIPO C.I.:</label>
        <select style=" border-radius:10px;" wire:model.live="tipo_doc"
            class="form-control  @error('tipo_doc') is-invalid @enderror" style="width: 100%;" tabindex="-1" required
            wire:change=TipoDocumento>
            <option selected=" ">Seleccione.. </option>
            <option value="0">NACIONAL</option>
            <option value="1">EXTRANJERO</option>
        </select>
    </div>
    <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
        <label for="expedido_ci">EXPEDIDO:</label>
        <select style=" border-radius:10px;" wire:model.live="expedido_ci"
            class="form-control  @error('expedido_ci') is-invalid @enderror" style="width: 100%;" tabindex="-1"
            required>
            <option selected="selected">Seleccione.. </option>
            @foreach ($this->departamento as $dep)
                <option value="{{ $dep->id }}">{{ $dep->sigla }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="row">
    <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
        <label for="fecha_nacimiento_des">FECHA NAC: </label>
        <input type="date" class="form-control  @error('fecha_nacimiento_des') is-invalid @enderror"
            placeholder=" Fecha Nacimiento" wire:model.live="fecha_nacimiento_des" name="fecha_nacimiento_des"
            style=" border-radius:10px;" id="fecha_nacimiento_des" min="1980-01-01" max="{{ now()->toDateString() }}"
            wire:change="GenerarMatricula">
    </div>

    <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
        <label for="sexo"> SEXO:</label>
        <div class="col-sm-6 d-flex align-items-center">
            <label class="mr-2"> <strong style="color:#2980b9">M</strong></label>
            <div class="custom-control custom-switch custom-switch-off-info custom-switch-on-pink">
                <input style=" border-radius:10px;" wire:model="sexo" name="sexo" type="checkbox"
                    class="custom-control-input" id="customSwitch2" wire:change="GenerarMatricula">
                <label class="custom-control-label" for="customSwitch2"
                    style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
            </div>
            <label class="ml-2"><strong style="color:#e452b9">F</strong></label>
        </div>
    </div>

    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
        <label for="matricula"> MATRICULA:</label>
        <input style=" border-radius:10px;" class="form-control" type="text" wire:model.live="matricula"
            disabled>
    </div>
</div>

@if ($es_extranjero == 0)
    <div class="row">
        <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
            <label for="zona">DEP. NAC.:</label>
            <select style=" border-radius:10px;" wire:model.live="dep_nacimiento" name="zona" id="zonas-select"
                class="form-control select2 @error('dep_nacimiento') is-invalid @enderror" style="width: 100%;"
                tabindex="-1" required wire:change='ObtenerProvincia()'>
                <option selected="selected">Seleccione.. </option>
                @foreach ($this->departamento as $dep)
                    <option value="{{ $dep->id }}">{{ $dep->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
            <label for="zona">PRO. NAC.:</label>
            <select style=" border-radius:10px;" wire:model.live="pro_nacimiento" name="zona" id="zonas-select"
                class="form-control select2 @error('pro_nacimiento') is-invalid @enderror" style="width: 100%;"
                tabindex="-1" required wire:change='ObtenerMunicipio()'>
                <option selected="selected">Seleccione.. </option>
                @if ($this->provincias)
                    @foreach ($this->provincias as $pro)
                        <option value="{{ $pro->id }}">{{ $pro->nombre }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
            <label for="zona">MUN. NAC.:</label>
            <select style=" border-radius:10px;" wire:model.live="mun_nacimiento" name="zona" id="zonas-select"
                class="form-control select2 @error('mun_nacimiento') is-invalid @enderror" style="width: 100%;"
                tabindex="-1" required wire:change="GenerarMatricula">
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
