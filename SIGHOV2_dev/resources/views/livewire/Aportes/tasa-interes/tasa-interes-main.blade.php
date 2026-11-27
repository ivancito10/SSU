<div>
@can('registro.tasa_interes')

        <div class="input-group pr-4" style="justify-content: space-between; ">
            <h4 style="color:#273746;">APORTES - <b> LISTA DE  TASAS DE INTERÉS</b></h4> 
            <!-- Aquí va el botón registrar -->
            <div class="d-flex">
                <div style=" margin-right: 7px;" style="background-color:#ebedef;">
                     
                    <x-link-button style=" padding: 5px 10px;"
                     url="https://www.bcb.gob.bo/?q=tasas_interes">
                        LINK AL BCB
                    </x-link-button>
                </div>
                <div style="margin-right: 7px;">
                    <x-agregar-button style=" padding: 5px 10px;" wire:click="registrar"
                     title="REGISTRAR TASA DE INTERÉS"><b>REGISTRAR</b>   </x-agregar-button> 
                </div>
            </div>
            
        </div>
        <br>
            
@endcan

@can('lista.tasa_interes')
        
        <div class="row">
            <div class="col-lg-12">
                <!-- Aqui va lo de el Listado-->
                @livewire('aportes.tasa-interes.tasa-interes-table-grid')
                    <!-- modal editar tasa -->
                @include('livewire.Aportes.tasa-interes.modalEditarTasa')
                <!-- modal  registrar tasa -->
                @include('livewire.Aportes.tasa-interes.modalRegistrar')
        
            </div>
        </div>
            {{--Para el cargando--}}
            <div wire:loading>
                <x-cargando-dos />
            </div>
    
@endcan
</div>
<script>
    document.addEventListener('activo', function() {
        Swal.fire({
            title: 'Activo!',
            text: "Se actualizo con éxito.",
            icon: 'success'
        });
    });

    document.addEventListener('inactivo', function() {
        Swal.fire({
            title: 'Inactivo!',
            text: "Se actualizo con éxito.",
            icon: 'error'
        });
    });
    document.addEventListener('error', function() {
        Swal.fire({
            title: "Error!",
            text: "Sucedió un error, verifique el error.",
            icon: "warning"            
        });
    });

    document.addEventListener('completarDatos', function() {
        Swal.fire({
            title: '¡Faltan datos!',
            text: "No se registro la tasa de interés.",
            icon: 'warning'
        });
        $('#abrirModal').modal('hide');
    });

</script>