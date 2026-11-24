<div>
    <?php if (isset($component)) { $__componentOriginalc936e0311c797e77cf3e13bef9f32325 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc936e0311c797e77cf3e13bef9f32325 = $attributes; } ?>
<?php $component = App\View\Components\VerModal::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('ver-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\VerModal::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'medicamentosPorAgotarseModal','labelledby' => 'medicamentosPorAgotarseModalLabel','title' => 'MEDICAMENTOS POR AGOTARSE']); ?>
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('farmacia.AlmacenFarmacia.MedicamentosPorVencer.MedicamentoStockPorAgotarse');

$__html = app('livewire')->mount($__name, $__params, 'lw-2993322974-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc936e0311c797e77cf3e13bef9f32325)): ?>
<?php $attributes = $__attributesOriginalc936e0311c797e77cf3e13bef9f32325; ?>
<?php unset($__attributesOriginalc936e0311c797e77cf3e13bef9f32325); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc936e0311c797e77cf3e13bef9f32325)): ?>
<?php $component = $__componentOriginalc936e0311c797e77cf3e13bef9f32325; ?>
<?php unset($__componentOriginalc936e0311c797e77cf3e13bef9f32325); ?>
<?php endif; ?>
</div>
<script>
    window.addEventListener('modalVerMedicsAgotados', event => {
        $('#medicamentosPorAgotarseModal').modal('show');
    });
</script>
<?php /**PATH C:\Users\TIC-HP\SSU\Sighov2_etapa2_nuevo\resources\views/livewire/farmacia/almacen-farmacia/medicamentos-por-vencer/modalVerMedicamentosPorAgotarse.blade.php ENDPATH**/ ?>