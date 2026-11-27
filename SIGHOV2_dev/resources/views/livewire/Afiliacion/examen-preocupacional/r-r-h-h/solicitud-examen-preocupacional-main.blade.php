<div>
<div class="card">
        <div class="card-header">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-sm-2">
                        <x-agregar-button wire:click="redirectToForm">AGREGAR SOLICITUD</x-agregar-button>
                    </div>
                    <!-- Aqui va el boto agregar-->
                    <div class="col-sm-10">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header" style="background-color:#154360">
            <h6 style="font-family: Verdana, sans-serif ; text-align: left; color:white">SOLICITUDES EXAMENES PREOCUPACIONALES
            </h6>
        </div>
        <div class="card-body">

        @livewire('Afiliacion.examen-preocupacional.r-r-h-h.lista-solicitudes-table')
            <!--ACCIONES DE LA LISTA PRINCIPAL-->
        </div>
    </div>
</div>
