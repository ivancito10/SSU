<div>
@can('registrar.liquidaciones_asuss')
        
    <!--LISTA CON  MENU DE ACCIONES  PRINCIPAL-->

        <div class="input-group pr-4" style="justify-content: space-between; ">
            <H4 style="color:#212f3c ;">APORTES - <b>LISTA DE  LIQUIDACIÓN AL ASUSS</b></H4>
                <!-- Aqui va el boto agregar-->
                @livewire('aportes.liquidaciones.asuss.asuss-register')
        </div>
        <br>

    
    

@endcan
@can('lista.liquidaciones_asuss')
    <!--CARD LISTA PRINCIPAL-->
    <div class="row">
        <div class="col-lg-12">
            @livewire('aportes.liquidaciones.asuss.asuss-table')
            <!--ACCIONES DE LA LISTA PRINCIPAL-->
        </div>
        <!-- modal ver liquidacion asuss pdf -->
        @include('livewire.Aportes.liquidaciones.asuss.modalAsussPdf')
         
    </div>
    {{--Para el cargando--}}
    <div wire:loading>
        <x-cargando-dos />
    </div>
@endcan
</div>

<script>
     document.addEventListener('LiquidacionCorrectaAsuss', function() {
                    $('#editarReprModalExt').modal('hide');
                    Swal.fire({
                        title: "Guardado!",
                        text: "Se liquidó los comprobantes al ASUSS.",
                        icon: "success"

                    });
                });
                document.addEventListener('LiquidacionIncorrectaAsuss', function() {
                    $('#editarReprModalExt').modal('hide');
                    Swal.fire({
                        title: "Error",
                        text: "Se produjo un error no se pudo liquidar.",
                        icon: "error"

                    });
                });
</script>