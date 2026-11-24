<div>
    <h1>PAGINITA PARA EJECUTAR CÓDIGO</h1>
    <p class="mb-0 pb-0">Esta páginita solo la pueden ver los super admins, los más perrones aquí. La idea es que podamos
        poner acá el codigo que queramos ejecutar en producción
        sin tener que estar modificando otros archivos y siendo precavidos al respecto.
        <br>
    </p>
    <p class="mt-0 pt-0 text-right">
        <i>- RisBou</i>
    </p>
    {{-- <x-ver-button wire:click="ejecutarActualizacionStockReal" disabled>
        EJECUTAR ACTUALIZACIÓN STOCK REAL
    </x-ver-button> --}}

    {{-- <x-ver-button wire:click="ejecutarAjusteMovimientoPostInventario" disabled>
        EJECUTAR AJUSTE MOVIMIENTO POST INVENTARIO
    </x-ver-button> --}}

    {{-- <x-ver-button wire:click="ejecutarAjusteMovimientoRecetasPorInventario">
        EJECUTAR AJUSTE MOVIMIENTO <i><b>EN RECETAS</b></i> POST INVENTARIO
    </x-ver-button>

    <x-ver-button wire:click="ejecutarAjusteMovimientoRecetasPorInventarioEntregados">
        EJECUTAR AJUSTE MOVIMIENTO ENTREGAS <i><b>EN RECETAS</b></i> POST INVENTARIO
    </x-ver-button> --}}

    <x-ver-button wire:click="verIdInstitucion">
        ASÍ SE VE EL ID_INSTITUCION EN LA ETAPA 2
    </x-ver-button>
    <x-ver-button wire:click="testJobs">
        PRUEBA DE TAREAS EN SEGUNDO PLANO CON REDIS
    </x-ver-button>
    <x-ver-button wire:click="modalPdfPosicion">
        Modal de seccion de pdf de posicion
    </x-ver-button>
    @include('livewire.Administracion.pruebas-formales-ejecucion-codigo.modalPdfPosision')
    {{-- @livewire('administracion.pruebas-formales-ejecucion-codigo.pdf-posicion') --}}

    {{-- PARA LA EJECUCIÓN DE LA RECALCULACION DE MOVIMIENTO (ALMACEN Y FARMACIA JUNTOS) --}}
    <hr>

    <h6><strong>IMPORTANTE:</strong> ESTA SECCIÓN PERMITE INTRODUCIR UN ID MEDICAMENTO PARA RECALCULAR TODOS LOS
        SALDOS DE SUS MOVIMIENTOS.
        <strong>OJO</strong> EJECUTA A LA VEZ LO MOVIMIENTOS DE ALMACEN DE MEDICAMENTOS Y FARMACIA DEL MISMO
        MOVIMIENTO
    </h6>
    <div class="col-lg-4 col-md-12 col-sm-12 col-12">
        <label for="idMedicamento">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hash"
                viewBox="0 0 16 16">
                <path
                    d="M8.39 12.648a1 1 0 0 0-.015.18c0 .305.21.508.5.508.266 0 .492-.172.555-.477l.554-2.703h1.204c.421 0 .617-.234.617-.547 0-.312-.188-.53-.617-.53h-.985l.516-2.524h1.265c.43 0 .618-.227.618-.547 0-.313-.188-.524-.618-.524h-1.046l.476-2.304a1 1 0 0 0 .016-.164.51.51 0 0 0-.516-.516.54.54 0 0 0-.539.43l-.523 2.554H7.617l.477-2.304c.008-.04.015-.118.015-.164a.51.51 0 0 0-.523-.516.54.54 0 0 0-.531.43L6.53 5.484H5.414c-.43 0-.617.22-.617.532s.187.539.617.539h.906l-.515 2.523H4.609c-.421 0-.609.219-.609.531s.188.547.61.547h.976l-.516 2.492c-.008.04-.015.125-.015.18 0 .305.21.508.5.508.265 0 .492-.172.554-.477l.555-2.703h2.242zm-1-6.109h2.266l-.515 2.563H6.859l.532-2.563z" />
            </svg>
            ID MEDICAMENTO:<spam style="color:#cb4335;"><b>*</b></spam></label>
        <input class="form-control input-comun" type="number" id="idMedicamento" wire:model="idMedicamento"
            placeholder="Introduzca el id del medicamento">

        <x-ver-button wire:click="recalculoMovimientosAlmFarm">
            EJECUTA EL RECALCULO DE MOVIMIENTOS
        </x-ver-button>
    </div>













    <div wire:loading>
        <x-cargando-dos />
    </div>
</div>
