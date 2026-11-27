<x-ver-modal id="verAsignacionCodigoInstitucionalModal" labelledby="verAsignacionCodigoInstitucionalModalLabel"
    title="DATOS DE LA INSTITUCIÓN" saveAction="">
    <div class="row">
        <div class="col-lg-8 col-md-12 col-sm-12 col-12">
            <label for="nombre"> INSTITUCIÓN:</label>
            <input class="form-control" name="nombre"
                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;" id="nombre"
                disabled wire:model=nombre>
        </div>
        <div class="col-lg-2 col-md-6 col-sm-6 col-6">
            <label for="titular" style="color: #2471a3; font-weight: bold;"> COD. TITULAR:</label>
            <input class="form-control" id="titular"
                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;"
                wire:model="codigo_titular_ver" disabled>
        </div>
        <div class="col-lg-2 col-md-6 col-sm-6 col-6">
            <label for="beneficiario" style="color: #2471a3  ; font-weight: bold;"> COD. BENEFICIARIO:</label>
            <input class="form-control" id="beneficiario"
                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;"
                wire:model="codigo_beneficiario_ver" disabled>
        </div>

        <div class="col-lg-3 col-md-4 col-sm-4 col-4">
            <label for="nit"> NIT:</label>
            <input class="form-control" name="nit"
                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;" id="nit"
                disabled wire:model="nit">
        </div>
        <div class="col-lg-7 col-md-8 col-sm-8 col-8">
            <label for="acronimo"> ACRONIMO:</label>
            <input class="form-control" name="acronimo" id="acronimo"
                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;" disabled
                wire:model=acronimo>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-4 col-4">
            <label for="telefono"> TELÉFONO:</label>
            <input class="form-control" name="telefono" id="telefono"
                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;" disabled
                wire:model="telefono">
        </div>

        <div class="col-lg-5 col-md-8 col-sm-8 col-8">
            <label for="actividad"> ACTIVIDAD EMPLEADOR:</label>
            <input class="form-control" name="actividad" id="actividad"
                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;" disabled
                wire:model=actividad>
        </div>

        <div class="col-lg-4 col-md-7 col-sm-7 col-7">
            <label for="nro_empleador"> NRO. EMPLEADOR:</label>
            <input class="form-control" name="nro_empleador" id="nro_empleador"
                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;" disabled
                wire:model=nro_empleador>
        </div>
        <div class="col-lg-3 col-md-5 col-sm-5 col-5">
            <label for="tipo"> CLASE DE APORTANTE:</label>
            <input class="form-control" name="tipo" id="tipo"
                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;" disabled
                wire:model="tipo">
        </div>

        <div class="col-lg-8 col-md-12 col-sm-12 col-12">
            <label for="representante"> REPRESENTANTE LEGAL:</label>
            <input class="form-control" name="representante" id="representante"
                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;" disabled
                wire:model="representante">
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12 col-12">
            <label for="correo"> CORREO:</label>
            <input class="form-control" name="correo" id="correo"
                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;" disabled
                wire:model="correo">
        </div>

        <div class="col-lg-6 col-md-12 col-sm-12 col-12">
            <label for="direccion"> DIRECCIÓN:</label>
            <input class="form-control" name="direccion" id="direccion"
                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;" disabled
                wire:model="direccion">
        </div>
        <div class="col-lg-2 col-md-3 col-sm-3 col-3">
            <label for="puerta"> NRO PUERTA:</label>
            <input class="form-control" name="puerta" id="puerta"
                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;" disabled
                wire:model="puerta">
        </div>
        <div class="col-lg-4 col-md-9 col-sm-9 col-9">
            <label for="zona"> ZONA:</label>
            <input class="form-control" name="zona" id="zona"
                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;" disabled
                wire:model="zona">
        </div>

        <div class="col-lg-6 col-md-12 col-sm-12 col-12">
            <label for="fecha_mostrar"> FECHA DE AFILIACIÓN:</label>
            <input class="form-control" name="fecha_mostrar" id="fecha_mostrar"
                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;" disabled
                wire:model="fecha_mostrar">
        </div>
    </div>

</x-ver-modal>
<script>
    window.addEventListener('abrirAsignacionCodigoInstitucionalModal', event => {
        $('#verAsignacionCodigoInstitucionalModal').modal('show');
    });
</script>
