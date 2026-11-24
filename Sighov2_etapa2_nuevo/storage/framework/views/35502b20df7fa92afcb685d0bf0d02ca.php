<div>
    <?php if (isset($component)) { $__componentOriginal75c02264ae97999f3624c6bb42e1a288 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal75c02264ae97999f3624c6bb42e1a288 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.asignar-button','data' => ['wire:click' => 'abrirListadoMedicamentosPorVencer']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('asignar-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'abrirListadoMedicamentosPorVencer']); ?>MEDICAMENTOS POR VENCER
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal75c02264ae97999f3624c6bb42e1a288)): ?>
<?php $attributes = $__attributesOriginal75c02264ae97999f3624c6bb42e1a288; ?>
<?php unset($__attributesOriginal75c02264ae97999f3624c6bb42e1a288); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal75c02264ae97999f3624c6bb42e1a288)): ?>
<?php $component = $__componentOriginal75c02264ae97999f3624c6bb42e1a288; ?>
<?php unset($__componentOriginal75c02264ae97999f3624c6bb42e1a288); ?>
<?php endif; ?>

</div>
<?php /**PATH C:\Users\TIC-HP\SSU\Sighov2_etapa2_nuevo\resources\views/livewire/farmacia/almacen-farmacia/medicamentos-por-vencer/medicamentos-por-vencer-main.blade.php ENDPATH**/ ?>