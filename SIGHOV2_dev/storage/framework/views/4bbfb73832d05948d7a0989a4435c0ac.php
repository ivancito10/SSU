<?php $detalleManual = $detalleManual ?? null; ?>
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
<?php $component->withAttributes(['id' => 'modalVerManual','labelledby' => 'modalVerManualLabel','title' => 'MANUAL DEL SISTEMA '.e($detalleManual ? '- ' . $detalleManual['modulo'] : '').'']); ?>
        <!--[if BLOCK]><![endif]--><?php if($detalleManual): ?>
            <div class="card-borde p-2">
                <div class="row">
                    <div class="col-lg-9 col-md-8 col-sm-12">
                        <div class="card cabecera">
                            <b>INFORMACIÓN DEL MANUAL</b>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12 text-right">
                        <strong>ESTADO: </strong>
                        <!--[if BLOCK]><![endif]--><?php if($detalleManual['estado']): ?>
                            <span style="background-color:#1B950E;color:white; padding:5px;border-radius:10px"><b>ACTIVO</b></span>
                        <?php else: ?>
                            <span style="background-color:#EB4123;color:white; padding:5px;border-radius:10px"><b>INACTIVO</b></span>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-12 mt-2">
                        <strong>MÓDULO:</strong>
                        <p class="p-comun-disabled p-1"><?php echo e($detalleManual['modulo']); ?></p>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 mt-2">
                        <strong>TÍTULO:</strong>
                        <p class="p-comun-disabled p-1"><?php echo e($detalleManual['titulo']); ?></p>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-12 mt-2">
                        <strong>VERSIÓN:</strong>
                        <p class="p-comun-disabled p-1"><?php echo e($detalleManual['version']); ?></p>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-12">
                        <strong>DESCRIPCIÓN:</strong>
                        <p class="p-comun-disabled p-1"><?php echo e($detalleManual['descripcion']); ?></p>
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12">
                        <strong>ARCHIVO / TAMAÑO:</strong>
                        <p class="p-comun-disabled p-1"><?php echo e($detalleManual['nombre_archivo']); ?> (<?php echo e($detalleManual['tamano']); ?>)</p>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-12 d-flex justify-content-between align-items-center mb-2">
                        <div class="card cabecera px-2 mb-0">
                            <b><i class="fas fa-file-pdf text-danger mr-1"></i> VISTA PREVIA DEL DOCUMENTO</b>
                        </div>
                        <a href="<?php echo e($detalleManual['url_descargar']); ?>" target="_blank" class="btn btn-sm btn-success font-weight-bold">
                            <i class="fas fa-download mr-1"></i> DESCARGAR PDF
                        </a>
                    </div>
                    <div class="col-12">
                        <iframe src="<?php echo e($detalleManual['url_ver']); ?>" style="width: 100%; height: 500px; border: 1px solid #d1d5d8; border-radius: 6px;"></iframe>
                    </div>
                </div>
            </div>

            <div style="height: 0.5rem;"></div>

            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="p-2 card card-borde mb-2 h-100">
                        <h6 class="mb-2 text-blue font-weight-bold border-bottom pb-1">
                            <i class="fas fa-check-circle mr-1"></i> REGISTRADO POR
                        </h6>
                        <ul class="list-unstyled mb-0 small">
                            <li><strong>Usuario:</strong> <?php echo e($detalleManual['user_crea']); ?></li>
                            <li>
                                <strong>Fecha:</strong>
                                <!--[if BLOCK]><![endif]--><?php if($detalleManual['fecha_creado']): ?>
                                    <?php echo e(\Carbon\Carbon::parse($detalleManual['fecha_creado'])->format('d/m/Y H:i')); ?>

                                <?php else: ?>
                                    SIN DATO
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </li>
                        </ul>
                    </div>
                </div>
                <!--[if BLOCK]><![endif]--><?php if($detalleManual['fecha_editado']): ?>
                    <div class="col-lg-6 col-md-12">
                        <div class="p-2 card card-borde mb-2 h-100">
                            <h6 class="mb-2 text-orange font-weight-bold border-bottom pb-1">
                                <i class="fas fa-check-circle mr-1"></i> MODIFICADO POR
                            </h6>
                            <ul class="list-unstyled mb-0 small">
                                <li><strong>Usuario:</strong> <?php echo e($detalleManual['user_edit']); ?></li>
                                <li><strong>Fecha:</strong> <?php echo e(\Carbon\Carbon::parse($detalleManual['fecha_editado'])->format('d/m/Y H:i')); ?></li>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
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
    window.addEventListener('modalVerManual', () => {
        $('#modalVerManual').modal('show');
    });
</script>
<?php /**PATH C:\Users\TIC-HP\SSU\SIGHOV2_dev\resources\views/livewire/Administracion/manuales-file/modalVerManual.blade.php ENDPATH**/ ?>