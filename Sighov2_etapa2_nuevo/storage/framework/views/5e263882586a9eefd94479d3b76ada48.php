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

   <div class="modal fade " <?php echo e($id ? "id=$id" : ''); ?> tabindex="-1" aria-labelledby="<?php echo e($labelledby); ?>"
       aria-hidden="true" data-backdrop="static" data-keyboard="false">
       <div class="modal-dialog modal-xl">
           <div class="modal-content p-0 m-0 border shadow"
               style="background-color: #ffffff; border-radius: 1rem; border: 1px solid #dee2e6; box-shadow: 0 10px 25px rgba(0, 97, 136, 0.2);">
               <div class="modal-header"
                   style="background-color: #eaeded; border-bottom: 1px solid white; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                   <div style="display: flex; align-items: center;">
                       <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#2471a3"
                           class="bi bi-eye-fill" viewBox="0 0 16 16" style="margin-right: 5px;">
                           <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                           <path
                               d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
                       </svg>
                       <h5 style="font-family: Arial, sans-serif; color:black " class="modal-title"
                           id="<?php echo e($labelledby); ?>"><b><?php echo e($title); ?></b> </h5>
                   </div>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:black">
                       <span aria-hidden="true">&times;</span>
                   </button>
               </div>
               <div style="max-height: 750px; overflow-y: auto;" >
                   <div class="card-body p-0 m-0">
                       <div class="modal-body pb-0">
                           <div class="form-group">
                               <?php echo e($slot); ?>

                           </div>
                       </div>
                   </div>
               </div>
               <div class="modal-footer pt-0"
                   style="background-color: white; border-top: none !important; border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem;">
                   <?php if (isset($component)) { $__componentOriginal7b5f0de4ab1e8ff51838dc34a4fce561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7b5f0de4ab1e8ff51838dc34a4fce561 = $attributes; } ?>
<?php $component = App\View\Components\CancelButton::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('cancel-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\CancelButton::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data-dismiss' => 'modal']); ?>CERRAR <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7b5f0de4ab1e8ff51838dc34a4fce561)): ?>
<?php $attributes = $__attributesOriginal7b5f0de4ab1e8ff51838dc34a4fce561; ?>
<?php unset($__attributesOriginal7b5f0de4ab1e8ff51838dc34a4fce561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7b5f0de4ab1e8ff51838dc34a4fce561)): ?>
<?php $component = $__componentOriginal7b5f0de4ab1e8ff51838dc34a4fce561; ?>
<?php unset($__componentOriginal7b5f0de4ab1e8ff51838dc34a4fce561); ?>
<?php endif; ?>

               </div>
           </div>
       </div>
   </div>
<?php /**PATH C:\Users\TIC-HP\SSU\Sighov2_etapa2_nuevo\resources\views/components/ver-modal.blade.php ENDPATH**/ ?>