<div>
    <x-big-component id="asignarCodigoModal" labelledby="asignarLabel" title="ASIGNAR CÓDIGO INSTITUCIONAL"
        saveAction="saveAsignarCodigo()">

        <div class="row">

            <div class="col-lg-6 col-md-4 col-sm-6 col-xs-12">
                <br>
                <label for="codigo_ver" style="color: #0D47A1; font-weight: bold;"></label>
                <select wire:model="codigo_ver" name="codigo_ver" id="codigo_ver" class="form-control"
                    style=" border-radius:10px;width: 100%" 
                     tabindex="-1" required wire:change="mostrarCodigos">
                    <option selected="selected">Seleccione el código </option>
                    @foreach ($codigo_libres as $codigoSel)
                        <option value="{{ $codigoSel->id }}">{{ $codigoSel->codigo . $codigoSel->tipo_titular }} -
                            {{ $codigoSel->codigo . $codigoSel->tipo_beneficiario }}</option>
                    @endforeach
                </select>
                @error('codigo_ver')
                    @if ($message == 'validation.required')
                        <span class="form-text text-danger">Este campo es obligatorio.</span>
                    @else
                        <span class="form-text text-danger">{{ $message }}</span>
                    @endif
                @enderror
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
            </div>
            <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                <label for="titular" style="color: #2471a3; font-weight: bold;"> COD. TITULAR:</label>
                <input class="form-control" id="titular" style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;"
                 disabled wire:model="codigo_titular_ver">
            </div>
            <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                <label for="beneficiario" style="color: #2471a3 ; font-weight: bold;"> COD. BENEFICIARIO:</label>
                <input class="form-control" id="beneficiario" style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;" disabled wire:model="codigo_beneficiario_ver">
            </div>
        </div>
 
        <div class="row">
            <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                <label for="nombre"> INSTITUCIÓN:</label>
                <input class="form-control" name="nombre"  style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;" id="nombre" disabled wire:model=nombre>
            </div>
            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                <label for="actividad"> ACTIVIDAD EMPLEADOR:</label>
                <input class="form-control" name="actividad" style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;" id="actividad" disabled wire:model=actividad>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                <label for="nit"> NIT:</label>
                <input class="form-control" style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;" name="nit" id="nit" disabled wire:model="nit">
            </div>

            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                <label for="tipo"> CLASE DE APORTANTE:</label>
                <input class="form-control" name="tipo" id="tipo"  style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;" disabled wire:model="tipo">
            </div>

            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <label for="representante"> REPRESENTANTE LEGAL:</label>
                <input class="form-control" name="representante" id="representante" style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;" disabled wire:model="representante">
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                <label for="direccion"> DIRECCIÓN:</label>
                <input class="form-control" name="direccion" id="direccion"  style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;" disabled wire:model="direccion">
            </div>
            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                <label for="puerta"> NRO PUERTA:</label>
                <input class="form-control" name="puerta" id="puerta" style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;"  disabled wire:model="puerta">
            </div>
            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                <label for="zona"> ZONA:</label>
                <input class="form-control" name="zona" id="zona" style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;" disabled wire:model="zona">
            </div>

        </div>
   
        <div class="row">
            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                <label for="correo"> CORREO:</label>
                <input class="form-control" name="correo" id="correo" style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;" disabled wire:model="correo">
            </div>
            <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                <label for="telefono"> TELÉFONO:</label>
                <input class="form-control" name="telefono" id="telefono" style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;" disabled wire:model="telefono">
            </div>
            <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                <label for="fecha"> FECHA DE AFILIACIÓN:</label>
                <input class="form-control" name="fecha" id="fecha"
                 style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;" disabled wire:model="fecha_mostrar">
            </div>
        </div>
    </x-big-component>
</div>
<script>
    window.addEventListener('abrirModal', event => {
        $('#asignarCodigoModal').modal('show');
    });

    function saveAsignarCodigo() {
        Swal.fire({
            title: '¿Guardar Asignación?',
            text: "Se registrará la asignación del código institucional.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('GuardarAsignarCodigo');
                $('#asignarCodigoModal').modal('hide');
            } else {
                Swal.fire({
                    title: "Completa todos los datos!",
                    text: "Se canceló el registro",
                    icon: "error",
                    confirmButtonColor: '#ec7063',
                });
                $('#asignarCodigoModal').modal('hide');
            }
        });
    }

    document.addEventListener('AsignadoIncorrectamente', function() {
        $('#asignarCodigoModal').modal('hide');
        Swal.fire({
            title: "Error!!!",
            text: "Se cancelo el registro, verifique los datos!!!",
            icon: "error",
            confirmButtonColor: '#ec7063',

        });
    });
    document.addEventListener('AsignadoCorrectamente', function() {
        $('#asignarCodigoModal').modal('hide');
        Swal.fire({
            title: "<b>Felicidades!!!</b>",
            text: "Se registró correctamente la Asignación!!!",
            icon: "success",
            confirmButtonColor: '#5ea939',

        });
    });

    document.addEventListener('SeDesasignadoError', function() {
        $('#asignarCodigoModal').modal('hide');
        Swal.fire({
            title: "Error!!!",
            text: "Se cancelo!!!",
            icon: "error",
            confirmButtonColor: '#ec7063',

        });
    });
    document.addEventListener('SeDesasignadoCorrectamente', function() {
        $('#asignarCodigoModal').modal('hide');
        Swal.fire({
            title: "<b>ELIMINADO!</b>",
            text: "¡Se eliminó la asignación correctamente!",
            icon: "success",
            // iconColor:'red',
            confirmButtonColor: '#5ea939',

        });
    });



    document.addEventListener('ConfirmarEliminarCodigo', function() {
        Swal.fire({
            title: '¿Eliminar código?',
            text: "Se eliminara el código asignado",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('eliminarCodFunction');
            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Eliminación cancelada",
                    icon: "error",
                    confirmButtonColor: '#ec7063',
                });
            }
        });
    });
</script>
