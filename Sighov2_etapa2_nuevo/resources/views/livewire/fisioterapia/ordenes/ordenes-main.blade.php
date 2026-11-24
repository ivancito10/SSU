<div>
    <h4 style="color:black">FISIOTERAPIA - <b>LISTA DE ÓRDENES</b></h4>
    <div class="card-body pt-2 pr-0 pl-0">
        <!-- <p class="text-muted small">Busque por nombre o apellidos del especialista o paciente (mínimo 3 caracteres), ID de orden o número de solicitud. Las coincidencias de ambos nombres tienen la misma prioridad.</p>
        <p class="text-muted small">Si aparece “Datos no disponibles”, abra la orden para consultar el aviso de conexión o de referencia.</p> -->
        <div id="avisoBusquedaOrdenes" class="alert alert-info" role="status" hidden wire:ignore></div>
        <div class="card-body">
            @livewire('fisioterapia.ordenes.ordenes-table')
            @include('livewire.fisioterapia.ordenes.modalVerOrden')
        </div>
    </div>
    @script
    <script>
        $wire.on('fisioterapia:aviso-busqueda-orden', (event) => {
            const aviso = document.getElementById('avisoBusquedaOrdenes');
            aviso.textContent = event.mensaje;
            aviso.hidden = !event.mensaje;
        });
        $wire.on('fisioterapia:abrir-orden', () => $('#verOrdenFisioterapia').modal('show'));
        $wire.on('fisioterapia:error-orden', (event) => Swal.fire({
            icon: 'warning', title: 'No se pudo consultar la orden', text: event.mensaje
        }));
    </script>
    @endscript
</div>