<div>
    
    <div class="input-group pr-4 mb-3" style="justify-content: space-between; align-items: center;">
        <h4 style="color: black; margin: 0; text-transform: uppercase;">
            ENFERMERÍA - <b>REGISTRO DE INYECCIONES</b>
        </h4>
        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('menu.enfermeria')): ?>
            <div class="d-flex">
                <?php if (isset($component)) { $__componentOriginal9c23d2621cd1bc4c240085ffb164f70b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9c23d2621cd1bc4c240085ffb164f70b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.reportes-button','data' => ['class' => 'mr-2','wire:click' => 'abrirModalReporte','title' => 'REPORTE EXCEL']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('reportes-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mr-2','wire:click' => 'abrirModalReporte','title' => 'REPORTE EXCEL']); ?>
                    REPORTE EXCEL
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9c23d2621cd1bc4c240085ffb164f70b)): ?>
<?php $attributes = $__attributesOriginal9c23d2621cd1bc4c240085ffb164f70b; ?>
<?php unset($__attributesOriginal9c23d2621cd1bc4c240085ffb164f70b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9c23d2621cd1bc4c240085ffb164f70b)): ?>
<?php $component = $__componentOriginal9c23d2621cd1bc4c240085ffb164f70b; ?>
<?php unset($__componentOriginal9c23d2621cd1bc4c240085ffb164f70b); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal8be5f430792e886e5793ea4976d1d50c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8be5f430792e886e5793ea4976d1d50c = $attributes; } ?>
<?php $component = App\View\Components\AgregarButton::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('agregar-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AgregarButton::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'redirectToCreate','title' => 'REGISTRAR INYECCIÓN']); ?>
                    REGISTRAR
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8be5f430792e886e5793ea4976d1d50c)): ?>
<?php $attributes = $__attributesOriginal8be5f430792e886e5793ea4976d1d50c; ?>
<?php unset($__attributesOriginal8be5f430792e886e5793ea4976d1d50c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8be5f430792e886e5793ea4976d1d50c)): ?>
<?php $component = $__componentOriginal8be5f430792e886e5793ea4976d1d50c; ?>
<?php unset($__componentOriginal8be5f430792e886e5793ea4976d1d50c); ?>
<?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    
    <div class="w-100">
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('enfermeria.inyectables.inyectables-table');

$__html = app('livewire')->mount($__name, $__params, 'lw-2026692108-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    </div>

    <!-- ========================================== -->
    <!-- VENTANA EMERGENTE (MODAL VER)              -->
    <!-- ========================================== -->
    <?php echo $__env->make('livewire.enfermeria.inyectables.modalVerInyeccion', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- ========================================== -->
    <!-- VENTANA EMERGENTE (MODAL REPORTE EXCEL)    -->
    <!-- ========================================== -->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('menu.enfermeria')): ?>
        <?php echo $__env->make('livewire.enfermeria.inyectables.modalReporteExcel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

    <!-- Scripts de SweetAlert2 -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('alertaError', (data) => {
                Swal.fire({
                    title: 'Alerta!',
                    text: data.mensaje || data[0].mensaje, 
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            });

            Livewire.on('alertaExito', (data) => {
                Swal.fire({
                    title: '¡Modificación Exitosa!',
                    text: data.mensaje || data[0].mensaje,
                    icon: 'success',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#6f42c1'
                });
            });

            // Compatibilidad con los eventos de estado actualizados
            document.addEventListener('estadoActualizado', function () {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: '¡Actualizado!',
                        text: 'El estado de la atención se actualizó con éxito.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });

            document.addEventListener('errorActualizacion', function () {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Error',
                        text: 'No se pudo actualizar el estado del registro.',
                        icon: 'error'
                    });
                }
            });
        });
    </script>
</div><?php /**PATH C:\Users\TIC-HP\SSU\Sighov2_etapa2_nuevo\resources\views/livewire/enfermeria/inyectables/inyectables-main.blade.php ENDPATH**/ ?>