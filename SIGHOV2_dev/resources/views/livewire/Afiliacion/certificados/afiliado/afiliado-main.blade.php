<div>
    <div class="input-group pr-4" style="justify-content: space-between;">
        <h4 style="color:black;">AFILIACIÓN - <b>MI LISTA DE CERTIFICADOS</b></h4>

        <x-agregar-button wire:click="abrir">EMITIR MI CERTIFICADO</x-agregar-button>
    </div>
    <br>

    <div class="card-body pt-2 pr-0 pl-0">
        @livewire('Afiliacion.certificados.afiliado.afiliado-table')
    </div>


</div>

<script>
    document.addEventListener('creado', function() {
        Swal.fire({
            title: " Guardado!",
            text: "El certificado fue registrado correctamente.",
            icon: "success"
        });
    });
    document.addEventListener('no_registra', function() {
        Swal.fire({
            title: " Error de registro!",
            text: "El registro fue cancelado.",
            icon: "warning"
        });
        $('#modalRegister').modal('hide');
    });


   
</script>
