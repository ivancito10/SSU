<div>
    <x-agregar-button data-toggle="modal" data-target="#modalBeneficiarioTitular"> REGISTRAR </x-agregar-button>

    <x-ver-modal id="modalBeneficiarioTitular" labelledby="modalBeneficiarioTitularLabel" title=" LISTA DE ASEGURADOS TITULARES ">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <!--tabla para buscar al titular-->
            @livewire('Afiliacion.beneficiarios.beneficiario-con-titular-table')
            
            </div>  
        </div>
    </x-ver-modal>
</div>
