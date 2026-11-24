<div>
    <?php if (isset($component)) { $__componentOriginal3bfe81d27f16b6754745d4e606b63b73 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3bfe81d27f16b6754745d4e606b63b73 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.agotarse-button','data' => ['wire:click' => 'abrirListadoMedicamentosPorAgotarse']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('agotarse-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'abrirListadoMedicamentosPorAgotarse']); ?>MEDICAMENTOS POR AGOTARSE
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3bfe81d27f16b6754745d4e606b63b73)): ?>
<?php $attributes = $__attributesOriginal3bfe81d27f16b6754745d4e606b63b73; ?>
<?php unset($__attributesOriginal3bfe81d27f16b6754745d4e606b63b73); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3bfe81d27f16b6754745d4e606b63b73)): ?>
<?php $component = $__componentOriginal3bfe81d27f16b6754745d4e606b63b73; ?>
<?php unset($__componentOriginal3bfe81d27f16b6754745d4e606b63b73); ?>
<?php endif; ?>

</div>
<?php /**PATH C:\Users\TIC-HP\SSU\Sighov2_etapa2_nuevo\resources\views/livewire/farmacia/almacen-farmacia/medicamentos-por-vencer/medicamentos-por-agotarse-main.blade.php ENDPATH**/ ?>