<div wire:ignore>
    <x-big-component id="addInstitucionReaf" labelledby="addInstitucionLabel" title="AÑADIR INSTITUCION"
        saveAction="addInstitucionReafiliacion()">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="institucion"> INSTITUCION: </label>
                <select wire:model="institucion" id="institucion" class="form-control select2 "
                    style="width: 100%;  border-radius:10px;" tabindex="-1" required wire:change='VerCodigos()'>
                    @if (count($lista_instituciones) > 0)
                        <option value="" disabled>Seleccione la institución</option>
                        @foreach ($lista_instituciones as $dato)
                            <option value="{{ $dato->id }}">{{ $dato->nombre }}</option>
                        @endforeach
                    @else
                        <option value="">No hay códigos disponibles</option>
                    @endif
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                <label for="codigo_titular_ver"> COD. TIT. :</label>
                <input class="form-control"
                    style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;"
                    id="codigo_titular_ver" wire:model=codigo_titular_ver disabled>
            </div>
            <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                <label for="codigo_beneficiario_ver"> COD. BEN.: </label>
                <input class="form-control" id="codigo_beneficiario_ver"
                    style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;"
                    wire:model=codigo_beneficiario_ver disabled>
            </div>
            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                <label for="cargo"> CARGO: </label>
                <input class="form-control" oninput="this.value = this.value.toUpperCase()" style=" border-radius:10px;"
                    id="cargo" wire:model=cargo>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                <label for="fecha_ingreso"> FECHA INGRESO: </label>
                <input type="date" class="form-control" style=" border-radius:10px;" id="fecha_ingreso"
                    wire:model=fecha_ingreso>
            </div>
            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                <label for="salario"> SALARIO: </label>
                <input type="number" class="form-control" style=" border-radius:10px;" id="salario"
                    wire:model=salario>
            </div>
            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                <label for="item"> ITEM: </label>
                <input type="text" class="form-control" id="item" wire:model=item placeholder="Ingresa el item"
                    style="border-radius:10px;">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                <label for="carga_horaria">CARGA HORARIA:</label>
                <select wire:model="carga_horaria" name="carga_horaria" id="carga_horaria"
                    class="form-control select2 @error('carga_horaria') is-invalid @enderror"
                    style=" border-radius:10px;" tabindex="-1" required>
                    <option selected>Seleccione carga horaria</option>
                    <option value="160 Hrs.">160 Hrs.</option>
                    <option value="128 Hrs.">128 Hrs.</option>
                    <option value="114 Hrs.">114 Hrs.</option>
                    <option value="112 Hrs.">112 Hrs.</option>
                    <option value="96 Hrs.">96 Hrs.</option>
                    <option value="80 Hrs.">80 Hrs.</option>
                    <option value="64 Hrs.">64 Hrs.</option>
                    <option value="48 Hrs.">48 Hrs.</option>
                    <option value="32 Hrs.">32 Hrs.</option>
                    <option value="16 Hrs.">16 Hrs.</option>
                    <option value="POR ACTUALIZAR">NINGUNO</option>
                    <option value="TC">TC</option>
                </select>
            </div>
            <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
                <label for="titularidad">TITULARIDAD:</label>
                <select wire:model="titularidad" name="titularidad" id="titularidad"
                    class="form-control select2 @error('titularidad') is-invalid @enderror" style=" border-radius:10px;"
                    tabindex="-1" required>
                    <option selected>Seleccione titularidad</option>
                    <option value="ADMINISTRATIVO">ADMINISTRATIVO</option>
                    <option value="CONTRATADO">CONTRATADO</option>
                    <option value="EVENTUAL">EVENTUAL</option>
                    <option value="INTERINO">INTERINO</option>
                     <option value="INVITADO">INVITADO</option>
                    <option value="TITULAR A">TITULAR A</option>
                    <option value="TITULAR B">TITULAR B</option>
                    <option value="TITULAR C">TITULAR C</option>
                    <option value="TITULAR EMERITO">TITULAR EMERITO</option>
                    <option value="SECTOR SALUD">SECTOR SALUD</option>
                    <option value="NINGUNO">NINGUNO</option>
                </select>
            </div>
        </div>

    </x-big-component>
</div>
<script>
    function modalAddInstReafiliar() {

        $('#addInstitucionReaf').modal('show');
    };

    function addInstitucionReafiliacion() {
        Swal.fire({
            title: '¿Agregar Institución?',
            text: "Se registrarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('addInstitucionReaf');
                $('#addInstitucionReaf').modal('hide');
            } else {
                Swal.fire({
                    title: "Completa todos los datos!",
                    text: "Se canceló el registro",
                    icon: "error"
                });
                $('#addInstitucionReaf').modal('hide');

            }
        });
    }
</script>
