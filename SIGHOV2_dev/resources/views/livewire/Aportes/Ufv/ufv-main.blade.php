<div>
    @can('registro.ufv') 

    <div class="input-group pr-4" style="justify-content: space-between; ">
        <h4 style="color:black;">APORTES - <b> LISTA DE UNIDAD DE FOMENTO A LA VIVIENDA</b></h4>
        <div class="d-flex">
        
            <div>
                 {{-- <img src="{{ $url_imagen_usuario }}" alt="" style="width: 40px; height: 40px;"> --}}
                <x-link-button style=" padding: 5px 10px;" 
                url="https://www.bcb.gob.bo/?q=servicios/ufv/datos_estadisticos"
                 title="BANCO CENTRAL DE BOLIVIA">LINK AL BCB </x-link-button>
                
            </div>
            <div style="margin-left: 7px;">
 
                <x-subir-archivo-button style=" padding: 5px 10px;" wire:click="subir_excel"
                 title="SUBIR UFV EN FORMATO EXCEL">SUBIR EXCEL</x-subir-archivo-button>
            </div>
            <div style="margin-left: 7px;">
                <x-agregar-button style=" padding: 5px 10px;" wire:click="registrar"
                 title="REGISTRO UFV"><b>REGISTRAR</b></x-agregar-button>
                  
            </div>

        </div>
             
    </div>
    <br>

    
    @endcan
    @can('lista.ufv')
        <!-- <div class="card-header" style="background-color:#B71C1C ">
                   
        </div> -->
        <div class="row">
            <div class="col-lg-12">
                <!-- Aqui va lo de el Listado-->
                @livewire('aportes.ufv.ufv-table')
            </div>
        </div>
                <!-- modal subir planilla ufv -->
            @include('livewire.Aportes.Ufv.modalSubirExcel')
            <!-- modal  registrar ufv -->
            @include('livewire.Aportes.Ufv.modalRegistrar')
            <!-- modal  editar ufv -->
            @include('livewire.Aportes.Ufv.modalEditarUfv')
            
        
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
            text: "Se actualizó con éxito.",
            icon: 'success'
        });
    });
 

    document.addEventListener('inactivo', function() {
        Swal.fire({
            title: 'Inactivo!',
            text: "Se actualizó con éxito.",
            icon: 'success'
        });
    });
    document.addEventListener('error', function() {
        Swal.fire({
            title: "Error!",
            text: "Sucedió un error, verifique el error.",
            icon: "warning"            
        });
    });
</script>