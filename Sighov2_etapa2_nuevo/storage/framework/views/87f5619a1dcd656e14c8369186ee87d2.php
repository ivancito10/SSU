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
<?php $component->withAttributes(['id' => 'modalVerInyeccion','labelledby' => 'modalVerInyeccionLabel','title' => 'DETALLE DE ATENCIÓN DE INYECCIÓN '.e($detalleAtencion ? '#' . $detalleAtencion['id'] : '').'']); ?>
        <!--[if BLOCK]><![endif]--><?php if($detalleAtencion): ?>
            <div class="card-borde p-2">
                
                <div class="row">
                    <div class="col-lg-9 col-md-8 col-sm-12 col-12">
                        <div class="card cabecera">
                            <b>DATOS DEL PACIENTE</b>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12 col-12 text-right">
                        <strong>ESTADO: </strong>
                        <!--[if BLOCK]><![endif]--><?php if($detalleAtencion['estado']): ?>
                            <span style="background-color:#1B950E;color:white; padding:5px;border-radius:10px"><b>ACTIVO</b></span>
                        <?php else: ?>
                            <span style="background-color:#EB4123;color:white; padding:5px;border-radius:10px">INACTIVO</span>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                        <strong><i class="fas fa-user mr-1"></i> NOMBRE COMPLETO:</strong>
                        <p class="p-comun-disabled p-1"><?php echo e($detalleAtencion['paciente']['nombre']); ?></p>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <strong><i class="fas fa-id-card mr-1"></i> CARNET:</strong>
                        <p class="p-comun-disabled p-1"><?php echo e($detalleAtencion['paciente']['ci']); ?></p>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <strong><i class="fas fa-barcode mr-1"></i> MATRÍCULA:</strong>
                        <p class="p-comun-disabled p-1"><?php echo e($detalleAtencion['paciente']['matricula']); ?></p>
                    </div>
                </div>

                <div style="height: 0.5rem;"></div>

                
                <div class="row">
                    <div class="col-lg-7 col-md-12 col-sm-12 col-12">
                        <div class="card cabecera">
                            <b>DATOS DE LA ATENCIÓN</b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                        <strong><i class="fas fa-file-medical mr-1"></i> # RECETA:</strong>
                        <p class="p-comun-disabled p-1"><?php echo e($detalleAtencion['nro_receta']); ?></p>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                        <strong><i class="fas fa-calendar-alt mr-1"></i> FECHA REGISTRO:</strong>
                        <p class="p-comun-disabled p-1"><?php echo e($detalleAtencion['fecha_registro']); ?></p>
                    </div>
                </div>

                <div style="height: 0.5rem;"></div>

                
                <div class="row">
                    <div class="col-lg-7 col-md-12 col-sm-12 col-12">
                        <div class="card cabecera">
                            <b>INYECCIONES / MEDICAMENTOS REGISTRADOS</b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <div style="max-height: 300px; overflow-y: auto;">
                            <table class="table tabla-ploma">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">#</th>
                                        <th style="width: 45%;">MEDICAMENTO</th>
                                        <th>OBSERVACIÓN / VÍA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $detalleAtencion['medicamentos']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $med): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($index + 1); ?></td>
                                            <td><b><?php echo e($med['nombre']); ?></b></td>
                                            <td><?php echo e($med['observacion']); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="3" style="text-align: center;">No se registraron medicamentos</td>
                                        </tr>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                
                <!--[if BLOCK]><![endif]--><?php if($detalleAtencion['procedimiento']): ?>
                    <div style="height: 0.5rem;"></div>
                    <div class="row">
                        <div class="col-lg-7 col-md-12 col-sm-12 col-12">
                            <div class="card cabecera">
                                <b>PROCEDIMIENTOS DE ENFERMERÍA REALIZADOS</b>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="d-flex flex-wrap justify-content-around py-2">
                                <?php
                                    $procedimientosLista = [
                                        'curacion_plana'      => 'Curación Plana',
                                        'curacion_infectada'  => 'Curación Infectada',
                                        'oxigenoterapia'      => 'Oxigenoterapia',
                                        'retiro_puntos'       => 'Retiro de Puntos',
                                        'prueba_sensibilidad' => 'Prueba Sensibilidad',
                                        'sangria'             => 'Sangría',
                                    ];
                                ?>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $procedimientosLista; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campo => $etiqueta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <!--[if BLOCK]><![endif]--><?php if($detalleAtencion['procedimiento'][$campo]): ?>
                                        <span class="m-1"
                                            style="background-color:#1B950E; color:white; padding:5px 12px; border-radius:10px; font-size: 13px;">
                                            <i class="fas fa-check-circle mr-1"></i><b><?php echo e($etiqueta); ?></b>
                                        </span>
                                    <?php else: ?>
                                        <span class="m-1"
                                            style="background-color:#e9ecef; color:#6c757d; padding:5px 12px; border-radius:10px; font-size: 13px;">
                                            <i class="fas fa-circle mr-1" style="font-size: 9px;"></i><?php echo e($etiqueta); ?>

                                        </span>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-1">
                            <strong><i class="fas fa-sticky-note mr-1"></i> NOTAS ADICIONALES:</strong>
                            <p class="p-comun-disabled p-1"><?php echo e($detalleAtencion['procedimiento']['observaciones']); ?></p>
                        </div>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <div style="height: 0.5rem;"></div>

            
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                    <div class="p-2 card card-borde mb-2 h-100">
                        <h6 class="mb-3 text-blue font-weight-bold border-bottom pb-1">
                            <i class="fas fa-check-circle mr-1"></i> REGISTRADO POR
                        </h6>
                        <ul class="list-unstyled mb-0">
                            <li>
                                <strong>Nombre de usuario:</strong>
                                <?php echo e($detalleAtencion['user_crea']); ?>

                            </li>
                            <li>
                                <strong>Fecha de registro:</strong>
                                <!--[if BLOCK]><![endif]--><?php if($detalleAtencion['fecha_creado']): ?>
                                    <?php echo e(\Carbon\Carbon::parse($detalleAtencion['fecha_creado'])->format('d/m/Y')); ?>

                                    <b> Hrs:</b>
                                    <?php echo e(\Carbon\Carbon::parse($detalleAtencion['fecha_creado'])->format('H:i A')); ?>

                                <?php else: ?>
                                    SIN DATO
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </li>
                        </ul>
                    </div>
                </div>

                <!--[if BLOCK]><![endif]--><?php if($detalleAtencion['fecha_editado'] != null): ?>
                    <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                        <div class="p-2 card card-borde mb-2 h-100">
                            <h6 class="mb-3 text-orange font-weight-bold border-bottom pb-1">
                                <i class="fas fa-check-circle mr-1"></i> MODIFICADO POR
                            </h6>
                            <ul class="list-unstyled mb-0">
                                <li>
                                    <strong>Nombre de usuario:</strong>
                                    <?php echo e($detalleAtencion['user_edit']); ?>

                                </li>
                                <li>
                                    <strong>Fecha de registro:</strong>
                                    <?php echo e(\Carbon\Carbon::parse($detalleAtencion['fecha_editado'])->format('d/m/Y')); ?>

                                    <b> Hrs:</b>
                                    <?php echo e(\Carbon\Carbon::parse($detalleAtencion['fecha_editado'])->format('H:i A')); ?>

                                </li>
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
    document.addEventListener('verInyeccionModal', function() {
        $('#modalVerInyeccion').modal('show');
    });
</script>
<?php /**PATH C:\Users\TIC-HP\SSU\Sighov2_etapa2_nuevo\resources\views/livewire/enfermeria/inyectables/modalVerInyeccion.blade.php ENDPATH**/ ?>