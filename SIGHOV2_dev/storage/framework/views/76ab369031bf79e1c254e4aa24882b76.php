<div>
    <?php if (isset($component)) { $__componentOriginal42f7802344f99fa921e92170309350c5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal42f7802344f99fa921e92170309350c5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal-sin-footer','data' => ['id' => 'registrarManualModal','labelledby' => 'registrarManualModalLabel','title' => 'SUBIR NUEVO MANUAL PDF']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('modal-sin-footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'registrarManualModal','labelledby' => 'registrarManualModalLabel','title' => 'SUBIR NUEVO MANUAL PDF']); ?>
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('administracion.manuales-file.manuales-register');

$__html = app('livewire')->mount($__name, $__params, 'lw-2231972701-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal42f7802344f99fa921e92170309350c5)): ?>
<?php $attributes = $__attributesOriginal42f7802344f99fa921e92170309350c5; ?>
<?php unset($__attributesOriginal42f7802344f99fa921e92170309350c5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal42f7802344f99fa921e92170309350c5)): ?>
<?php $component = $__componentOriginal42f7802344f99fa921e92170309350c5; ?>
<?php unset($__componentOriginal42f7802344f99fa921e92170309350c5); ?>
<?php endif; ?>
</div>
<script>
    window.addEventListener('modalRegistrarManual', () => {
        $('#registrarManualModal').modal('show');
    });

    window.addEventListener('cerrarModalRegistrarManual', () => {
        $('#registrarManualModal').modal('hide');
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open').css('padding-right', '');
    });
</script>
<?php /**PATH C:\Users\TIC-HP\SSU\SIGHOV2_dev\resources\views/livewire/Administracion/manuales-file/modalRegistrarManual.blade.php ENDPATH**/ ?>