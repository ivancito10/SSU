<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'id',
    'labelledby',
    'title',
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'id',
    'labelledby',
    'title',
]); ?>
<?php foreach (array_filter(([
    'id',
    'labelledby',
    'title',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div class="modal fade" <?php echo e($id ? "id=$id" : ""); ?> tabindex="-1" aria-labelledby="<?php echo e($labelledby); ?>" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#34495e; color:white;">
                <h5 class="modal-title" id="<?php echo e($labelledby); ?>">  <?php echo e($title ?? 'Modal'); ?></h5>
            </div>
            <div class="modal-body">
                <?php echo e($slot); ?>

            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\TIC-HP\SSU\Sighov2_etapa2_nuevo\resources\views/components/small-modal-no-close.blade.php ENDPATH**/ ?>