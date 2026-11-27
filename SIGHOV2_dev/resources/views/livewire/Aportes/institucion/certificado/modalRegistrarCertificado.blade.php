
<div>
    <x-ver-dos-modal id="registrarCertificado" labelledby="registrarCertificadoLabel" title="LISTADO DE INSTITUCIONES">

        <div class="row">
            <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                
            </div>
            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12" style="text-align:right;">
                <!-- Aqui va el boto agregar nuevo si no existe institucion-->
                <x-agregar-button style=" padding: 5px 10px;" wire:click="registrarInstitucion" title="REGISTRAR INSTITUCIÓN"> REGISTRAR</x-agregar-button>
            </div>
            
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <br>  
                @livewire('Aportes.institucion.certificado.lista-instituciones')
            </div>
        </div>
    </x-ver-dos-modal>

</div>

<script>
    window.addEventListener('modalRegistro', event => {
        $('#registrarCertificado').modal('show');
    });

    
</script>