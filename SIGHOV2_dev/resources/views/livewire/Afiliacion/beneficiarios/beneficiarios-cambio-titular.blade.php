<div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <x-buscar-dos-button style="padding:0px" data-toggle="modal" data-target="#aseguradosModalTitular">BUSCAR
            TITULAR</x-buscar-dos-button>
    </div>
    <x-ver-modal id="aseguradosModalTitular" labelledby="aseguradosModalTitular" title="LISTADO DE ASEGURADOS TITULARES">
        @livewire('Afiliacion.beneficiarios.titulares-cambio-table')
    </x-ver-modal>
</div>
<script>
    window.addEventListener('cerrarModalAseguradosTitular', event => {
        Swal.fire({
            title: "Asegurado seleccionado",
            text: "Se seleccionó al asegurado correctamente.",
            icon: "success"
        });
        $('#aseguradosModalTitular').modal('hide');
    });
</script>
