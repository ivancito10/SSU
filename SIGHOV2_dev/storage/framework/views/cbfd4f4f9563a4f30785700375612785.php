<div>
    <div class="row">
        <div class="col-lg-5 col-md-6 col-sm-12 col-12">
            <div class="card p-2" style=" border: 1px solid #dee2e6; border-radius: 8px;">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-center">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="#22c197"
                                class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                                <path
                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                            </svg>
                            <b style="color:#1863db; font-size:20px">INFORMACIONES</b> </span>
                    </div>
                </div>
                <?php if (isset($component)) { $__componentOriginalcb08ce3d51dcfdd6879d73a2840cd5ed = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcb08ce3d51dcfdd6879d73a2840cd5ed = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.carrusel-component','data' => ['id' => 'comuinicadosCarrusel','images' => ['images/Informaciones/agendamientoAPK2.png', 'images/Informaciones/agedamietoWeb2.png']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('carrusel-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'comuinicadosCarrusel','images' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['images/Informaciones/agendamientoAPK2.png', 'images/Informaciones/agedamietoWeb2.png'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcb08ce3d51dcfdd6879d73a2840cd5ed)): ?>
<?php $attributes = $__attributesOriginalcb08ce3d51dcfdd6879d73a2840cd5ed; ?>
<?php unset($__attributesOriginalcb08ce3d51dcfdd6879d73a2840cd5ed); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcb08ce3d51dcfdd6879d73a2840cd5ed)): ?>
<?php $component = $__componentOriginalcb08ce3d51dcfdd6879d73a2840cd5ed; ?>
<?php unset($__componentOriginalcb08ce3d51dcfdd6879d73a2840cd5ed); ?>
<?php endif; ?>
            </div>

        </div>
       
        <div class="col-lg-7 col-md-6 col-sm-12 col-12 ">
            <div class="card p-2" style=" border: 1px solid #dee2e6; border-radius: 8px;">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-center">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="#22c197"
                                class="bi bi-youtube" viewBox="0 0 16 16">
                                <path
                                    d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.01 2.01 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.01 2.01 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31 31 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.01 2.01 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A100 100 0 0 1 7.858 2zM6.4 5.209v4.818l4.157-2.408z" />
                            </svg>
                            <b style="color:#1863db; font-size:20px">TUTORIALES</b> </span>
                    </div>
                </div>
                <?php if (isset($component)) { $__componentOriginald2f9ae8bc40997ea13d0bec92c62f32a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald2f9ae8bc40997ea13d0bec92c62f32a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.video-carrrusel-component','data' => ['videos' => $videos,'id' => 'misVideos']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('video-carrrusel-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['videos' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($videos),'id' => 'misVideos']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald2f9ae8bc40997ea13d0bec92c62f32a)): ?>
<?php $attributes = $__attributesOriginald2f9ae8bc40997ea13d0bec92c62f32a; ?>
<?php unset($__attributesOriginald2f9ae8bc40997ea13d0bec92c62f32a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald2f9ae8bc40997ea13d0bec92c62f32a)): ?>
<?php $component = $__componentOriginald2f9ae8bc40997ea13d0bec92c62f32a; ?>
<?php unset($__componentOriginald2f9ae8bc40997ea13d0bec92c62f32a); ?>
<?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php /**PATH C:\Users\TIC-HP\SSU\SIGHOV2_dev\resources\views/livewire/Administracion/dashboard/comunicados/comunicados-dashboard-main.blade.php ENDPATH**/ ?>