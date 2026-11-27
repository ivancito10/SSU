<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'id' => 'videoCarousel',
    'videos' => [],
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'id' => 'videoCarousel',
    'videos' => [],
]); ?>
<?php foreach (array_filter(([
    'id' => 'videoCarousel',
    'videos' => [],
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div id="<?php echo e($id); ?>" class="carousel slide carousel-vertical-videos" data-ride="carousel" data-interval="false">
    <div class="carousel-inner">
        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $videos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="carousel-item <?php echo e($index === 0 ? 'active' : ''); ?>">
                <video class="d-block w-100" controls playsinline preload="none"
                       data-src="<?php echo e(asset($video['src'])); ?>">
                </video>
                <div class="carousel-caption">
                    <p style="font-size: clamp(16px, 3vw, 16px);"><?php echo e($video['title']); ?></p>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    
    <a class="carousel-control-prev" href="#<?php echo e($id); ?>" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Anterior</span>
    </a>
    <a class="carousel-control-next" href="#<?php echo e($id); ?>" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Siguiente</span>
    </a>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const carouselEl = $("#<?php echo e($id); ?>");
        const videos = carouselEl.find("video");

        // Función para cargar un video si aún no tiene src
        function loadVideo(video) {
            if (video && video.dataset.src && !video.src) {
                video.src = video.dataset.src;
                video.load();
            }
        }

        // Cargar y preparar el primer video
        let firstVideo = carouselEl.find(".carousel-item.active video")[0];
        loadVideo(firstVideo);

        // Cuando un video termina, pasar al siguiente slide
        videos.each(function () {
            this.addEventListener("ended", () => {
                carouselEl.carousel("next");
            });
        });

        // Al cambiar de slide
        carouselEl.on("slid.bs.carousel", function (e) {
            // Pausar y reiniciar todos los videos
            videos.each(function () {
                this.pause();
                this.currentTime = 0;
            });

            // Cargar y reproducir el video del slide activo
            const currentVideo = $(e.relatedTarget).find("video")[0];
            if (currentVideo) {
                loadVideo(currentVideo);
                currentVideo.play().catch(() => {
                    // algunos navegadores bloquean autoplay si no hay interacción
                    console.log("Autoplay bloqueado, el usuario debe dar play.");
                });
            }
        });
    });
</script>
<?php /**PATH C:\Users\TIC-HP\SSU\SIGHOV2_dev\resources\views/components/video-carrrusel-component.blade.php ENDPATH**/ ?>