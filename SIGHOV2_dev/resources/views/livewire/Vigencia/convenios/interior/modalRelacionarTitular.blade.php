<div>
    <x-ver-modal id="modalTablaTitularInterior" labelledby="modalTablaTitularInteriorLabel"
        title=" TITULARES DEL INTERIOR ">
        {{--<x-agregar-button data-toggle="collapse"
        data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" >
            REGISTRAR TITULAR DEL INTERIOR
        </x-agregar-button>
        <div class="collapse" id="collapseExample" wire:ignore>
            <div class="card card-body" >
            </div>
        </div>--}}
        @livewire('Vigencia.Convenios.Interior.titulares-interior-table')
    </x-ver-modal>
</div>
<script>
    document.addEventListener('titularSel', function () {
        
        Swal.fire({
            title: 'Relacionar con el Titular del Interior?',
            text: "Se guardara la informacion.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, validar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#modalTablaTitularInterior').modal('hide');
            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló la validación",
                    icon: "error"
                });
            }
        });
    });
</script>
