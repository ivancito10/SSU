<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['id', 'labelledby']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['id', 'labelledby']); ?>
<?php foreach (array_filter((['id', 'labelledby']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div class="modal fade" <?php echo e($id ? "id=$id" : ''); ?> tabindex="-1" aria-labelledby="<?php echo e($labelledby); ?>" aria-hidden="true">
    <div class="modal-dialog custom-modal-size">
        <div class="modal-content p-0 m-0 border shadow "  style="background-color: #ffffff; border-radius: 1rem; border: 1px solid #dee2e6; box-shadow: 0 10px 25px rgba(0, 97, 136, 0.2);">
            <div class="pr-2">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:black">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="card-body ">
                <div class="modal-body p-0" style="max-height: 800px; overflow-y: auto; overflow-x: hidden; ">
                    <div class="form-group">
                        <?php echo e($slot); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .custom-modal-size {
        max-width: 90%;
        /* Ajusta el ancho máximo del modal */
        width: 90%;
        height: 40vh;
        /* Ajusta la altura del modal */
    }
</style>
<?php /**PATH C:\Users\TIC-HP\SSU\SIGHOV2_dev\resources\views/components/ver-modal-sin-titulo.blade.php ENDPATH**/ ?>