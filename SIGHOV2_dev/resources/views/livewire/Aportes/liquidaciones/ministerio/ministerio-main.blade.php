<div>
@can('registrar.liquidaciones_ministerio')
    <!--LISTA CON  MENU DE ACCIONES  PRINCIPAL-->

            <div class="input-group pr-4" style="justify-content: space-between; ">
                <H4 style="color:#212f3c ;">APORTES - <b>LISTA DE  LIQUIDACIÓN AL MINISTERIO DE SALUD </b></H4>
                <!-- Aqui va el boto agregar-->
                @livewire('aportes.liquidaciones.ministerio.ministerio-register')
            </div>
            <br>


@endcan
@can('lista.liquidaciones_ministerio')
    <!--CARD LISTA PRINCIPAL-->
    <div class="row">
        <div class="col-lg-12">
            @livewire('aportes.liquidaciones.ministerio.ministerio-table')
            <!--ACCIONES DE LA LISTA PRINCIPAL-->
        </div>

        <!-- modal ver liquidacion ministerio  pdf -->
        @include('livewire.Aportes.liquidaciones.ministerio.modalMinisterioPdf')
         
    </div>
    {{--Para el cargando--}}
    <div wire:loading>
        <x-cargando-dos />
    </div>
@endcan
</div>
<script>
     document.addEventListener('LiquidacionCorrectaMinisterio', function() {
                    $('#editarReprModalExt').modal('hide');
                    Swal.fire({
                        title: "Guardado!",
                        text: "Se liquidó los comprobantes al Ministerio.",
                        icon: "success"

                    });
                });
                document.addEventListener('LiquidacionIncorrectaMinisterio', function() {
                    $('#editarReprModalExt').modal('hide');
                    Swal.fire({
                        title: "Error",
                        text: "Se produjo un error no se pudo liquidar!!!",
                        icon: "error"

                    });
                });
                

</script>

