<div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h4 style="color:black;">REPORTES - <b> KARDEX CONTABLE</b></h4>
        </div>
    </div>
    <br>
    <hr style="background-color: #2471a3;">
    <div class="row">
        <h5>REPORTE DE ALMACÉN DE MEDICAMENTOS Y FARMACIA</h5>
    </div>
    <div class="row mb-3">
        <div class="col-lg-2 col-md-12 col-sm-12 mb-2">
            <div class="card-comun text-center p-3 h-100 shadow-sm"
                style="border-radius: 12px; background-color: #ffffff; border-top: 1px solid #e0e0e0; border-left: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; border-bottom: 1px solid #e0e0e0;">
                <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3"
                    style="width: 70px; height: 70px; background-color: #e8f0fe; color: #1a73e8;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                        class="bi bi-file-earmark-arrow-down" viewBox="0 0 16 16">
                        <path
                            d="M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293z" />
                        <path
                            d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z" />
                    </svg>
                </div>
                <h5 class="font-weight-bold" style="color: #2c3e50;">Ingresos</h5>
                <p class="text-muted small text-justify">Permite consultar el detalle de los medicamentos y materiales
                    ingresados a Almacén de medicamentos y Almacén General.</p>
                <x-ver-button wire:click="abrirModalIngresos"></x-ver-button>
            </div>
        </div>

        <div class="col-lg-2 col-md-12 col-sm-12 mb-2">
            <div class="card-comun text-center p-3 h-100 shadow-sm"
                style="border-radius: 12px; background-color: #ffffff; border-top: 1px solid #e0e0e0; border-left: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; border-bottom: 1px solid #e0e0e0;">
                <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3"
                    style="width: 70px; height: 70px; background-color: #e8f0fe; color: #1a73e8;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                        class="bi bi-file-earmark-arrow-up" viewBox="0 0 16 16">
                        <path
                            d="M8.5 11.5a.5.5 0 0 1-1 0V7.707L6.354 8.854a.5.5 0 1 1-.708-.708l2-2a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 7.707z" />
                        <path
                            d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z" />
                    </svg>
                </div>
                <h5 class="font-weight-bold" style="color: #2c3e50;">Salidas</h5>
                <p class="text-muted small text-justify">Permite consultar el detalle de las salidas de medicamentos y
                    materiales desde el Almacén de Medicamentos y el Almacén General.</p>
                <x-ver-button wire:click="abrirModalSalidas"></x-ver-button>
            </div>
        </div>

        <div class="col-lg-2 col-md-12 col-sm-12 mb-2">
            <div class="card-comun text-center p-3 h-100 shadow-sm"
                style="border-radius: 12px; background-color: #ffffff; border-top: 1px solid #e0e0e0; border-left: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; border-bottom: 1px solid #e0e0e0;">
                <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3"
                    style="width: 70px; height: 70px; background-color: #e8f0fe; color: #1a73e8;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                        class="bi bi-journal" viewBox="0 0 16 16">
                        <path
                            d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2" />
                        <path
                            d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z" />
                    </svg>
                </div>
                <h5 class="font-weight-bold" style="color: #2c3e50;">Estado de cuentas</h5>
                <p class="text-muted small text-justify">Reporte consolidado de saldos finales y valoración contable para las cuentas
                    medicamentos 121 y materiales 122.</p>
                <x-ver-button wire:click="abrirModalCuenta121"></x-ver-button>
            </div>
        </div>

        <div class="col-lg-2 col-md-12 col-sm-12 mb-2">
            <div class="card-comun text-center p-3 h-100 shadow-sm"
                style="border-radius: 12px; background-color: #ffffff; border-top: 1px solid #e0e0e0; border-left: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; border-bottom: 1px solid #e0e0e0;">
                <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3"
                    style="width: 70px; height: 70px; background-color: #e8f0fe; color: #1a73e8;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                        class="bi bi-box-seam" viewBox="0 0 16 16">
                        <path
                            d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2zm3.564 1.428L5.838 5.03l2.404.961 6.151-2.46-2.643-1.002zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1.5 1.5 0 0 1-.894 1.37l-7 2.8a1.5 1.5 0 0 1-1.212 0l-7-2.8A1.5 1.5 0 0 1 0 12.162V3.5a.5.5 0 0 1 .314-.464L7.443.184z" />
                    </svg>
                </div>
                <h5 class="font-weight-bold" style="color: #2c3e50;">Saldos</h5>
                <p class="text-muted small text-justify">Consulta el stock actual acumulado y la valoración total de
                    medicamentos por almacén, farmacia y almacén general.</p>
                <x-ver-button wire:click="abrirModalSaldosFinales"></x-ver-button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @include('livewire.kardex-contable.modalVerIngresos')
        </div>
        <div wire:loading>
            <x-cargando-dos />
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @include('livewire.kardex-contable.modalVerSalidas')
        </div>
        <div wire:loading>
            <x-cargando-dos />
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @include('livewire.kardex-contable.modalVerCuenta121')
        </div>
        <div wire:loading>
            <x-cargando-dos />
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @include('livewire.kardex-contable.modalVerSaldosFinales')
        </div>
        <div wire:loading>
            <x-cargando-dos />
        </div>
    </div>


</div>


<script>
    window.addEventListener('abrirModalVerIngresos', event => {
        $('#modalVerIngresos').modal('show');
    });

    window.addEventListener('abrirModalVerSalidas', event => {
        $('#modalVerSalidas').modal('show');
    });

    window.addEventListener('abrirModalVerCuenta121', event => {
        $('#modalVerCuenta121').modal('show');
    });

    window.addEventListener('abrirModalVerSaldosFinales', event => {
        $('#modalVerSaldosFinales').modal('show');
    });
</script>
