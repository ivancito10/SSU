<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'id' => 'carouselDefault', // ID único del carrusel
    'images' => [], // Array de imágenes
    'withIndicators' => true, // Mostrar indicadores
    'withControls' => true, // Mostrar controles
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'id' => 'carouselDefault', // ID único del carrusel
    'images' => [], // Array de imágenes
    'withIndicators' => true, // Mostrar indicadores
    'withControls' => true, // Mostrar controles
]); ?>
<?php foreach (array_filter(([
    'id' => 'carouselDefault', // ID único del carrusel
    'images' => [], // Array de imágenes
    'withIndicators' => true, // Mostrar indicadores
    'withControls' => true, // Mostrar controles
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div id="<?php echo e($id); ?>" class="carousel slide" data-ride="carousel">

    
    <!--[if BLOCK]><![endif]--><?php if($withIndicators): ?>
        <ol class="carousel-indicators">
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li data-target="#<?php echo e($id); ?>" data-slide-to="<?php echo e($index); ?>"
                    class="<?php echo e($index === 0 ? 'active' : ''); ?>"></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        </ol>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    
    <div class="carousel-inner" style="background-color:#f2f4f4; padding-right:1rem; border-radius:10px;">
        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="carousel-item <?php echo e($index === 0 ? 'active' : ''); ?>">
                <div class="image-container position-relative d-flex justify-content-center">
                    
                    <img src="<?php echo e(asset($img)); ?>" class="d-block mx-auto" width="400" height="300"
                        alt="slide-<?php echo e($index); ?>" style="cursor: zoom-in;"
                        data-toggle="modal" data-target="#modalZoom-<?php echo e($id); ?>"
                        data-img="<?php echo e(asset($img)); ?>">
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    
    <!--[if BLOCK]><![endif]--><?php if($withControls): ?>
        <a class="carousel-control-prev" href="#<?php echo e($id); ?>" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Anterior</span>
        </a>
        <a class="carousel-control-next" href="#<?php echo e($id); ?>" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Siguiente</span>
        </a>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>


<div class="modal fade" id="modalZoom-<?php echo e($id); ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content bg-transparent border-0 shadow-none">
            <div class="modal-body text-center">
                <img id="zoomImg-<?php echo e($id); ?>" src="" class="img-fluid rounded shadow-lg"
                    alt="zoom">
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const modal = document.getElementById("modalZoom-<?php echo e($id); ?>");
        const modalImg = document.getElementById("zoomImg-<?php echo e($id); ?>");

        $('#modalZoom-<?php echo e($id); ?>').on('show.bs.modal', function(event) {
            const img = $(event.relatedTarget); // en BS4 usamos jQuery
            modalImg.src = img.data('img');
        });
    });
</script>
<?php /**PATH C:\Users\TIC-HP\SSU\SIGHOV2_dev\resources\views/components/carrusel-component.blade.php ENDPATH**/ ?>