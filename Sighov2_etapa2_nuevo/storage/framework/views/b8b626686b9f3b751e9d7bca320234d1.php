<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['id', 'labelledby', 'title']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['id', 'labelledby', 'title']); ?>
<?php foreach (array_filter((['id', 'labelledby', 'title']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div <?php echo e($attributes); ?> class="modal fade " <?php echo e($id ? "id=$id" : ''); ?> tabindex="-1" aria-labelledby="<?php echo e($labelledby); ?>"
    data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content border-0 shadow-lg rounded-4"
            style="border-radius: 1rem; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);">
            <div class="modal-header"
                style="background-color: #eaeded; border-bottom: 1px solid white; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                <div style="display: flex; align-items: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#ba4a00"
                        class="bi bi-pen-fill" viewBox="0 0 16 16" style="margin-right: 5px;">
                        <path
                            d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001" />
                    </svg>
                    <div style="display: flex; align-items: center;">
                        <h5 style="font-family: Arial, sans-serif; color:black;" class="modal-title"
                            id="<?php echo e($labelledby); ?>"><b><?php echo e($title); ?></b></h5>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:black">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="card-body p-0 m-0">
                <div class="modal-body pb-0">
                    <div class="form-group">
                        <?php echo e($slot); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\Users\TIC-HP\SSU\Sighov2_etapa2_nuevo\resources\views/components/modal-small-sin-footer.blade.php ENDPATH**/ ?>