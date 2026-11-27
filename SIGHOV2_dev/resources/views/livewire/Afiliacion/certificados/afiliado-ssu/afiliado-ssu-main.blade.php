<div>

    <div class="input-group pr-4" style="justify-content: space-between;">
    <h4 style="color:black;">AFILIACIÓN - <b> LISTA DE CERTIFICADOS DE ASEGURADOS</b></h4>
    @can('registrar.certificados_afiliacion')
        @livewire('Afiliacion.certificados.afiliado-ssu.afiliado-ssu-register')
    @endcan
    </div>
    <br>
    @can('lista.certificados_afiliacion')
        <div class="card-body pt-2 pr-0 pl-0">
            @livewire('afiliacion.certificados.afiliado-ssu.afiliado-ssu-table')
        </div>
    @endcan
</div>

<script>
    
     document.addEventListener('afiliacionNoVigente', function() {
        Swal.fire({
            title: "No se puede emitir el certificado!",
            text: "El asegurado no tiene una afiliación vigente, por lo que no se puede emitir el certificado.",
            icon: "error"
        });
        $('#modalRegister').modal('hide');
        $('#modalCertificadoSsuRegister').modal('hide');
    });
</script>
