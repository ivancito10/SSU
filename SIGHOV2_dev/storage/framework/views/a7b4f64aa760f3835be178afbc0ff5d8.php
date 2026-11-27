<div>
    <div class="row">
        <div class="col-lg-7 col-md-6 col-sm-12 col-12">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class=" card card-comun p-2 " style=" border: 1px solid #dee2e6; border-radius: 8px;">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-center">
                                <span><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#22c197"
                                        class="bi bi-calendar-week-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2M9.5 7h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5m3 0h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5M2 10.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5" />
                                    </svg>
                                    <b style="color:#1863db; font-size:20px">DETALLE DE TUS CITAS MÉDICAS</b> </span>
                            </div>
                        </div>
                        <hr class="p-0 m-1">
                        <div class="row">
                            <div class="col-lg-8 col-md-12 col-sm-8 col-8">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                        <span><b>NOMBRE: </b><?php echo e($nombreCompleto); ?> </span>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                        <span><b>MATRÍCULA: </b><?php echo e($matricula); ?> </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-12 col-sm-4 col-4 text-right pt-3 pr-3">
                                <button type="button" class="btn btn-success" wire:click="goAgendamientoWeb"
                                    style="background-color:#008a65">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                                        </svg>
                                        <b> NUEVA AGENDA</b>
                                    </span>
                                </button>
                            </div>
                        </div>
                        <hr class="p-0 m-1">
                        <div style="max-height: 300px; overflow-y: auto;">
                            <!--[if BLOCK]><![endif]--><?php if($agendasTitular->isNotEmpty()): ?>
                                <table class="table">
                                    <thead style="background-color:#f7f8ff; position: sticky; top: 0; z-index: 2; ">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">ESPECIALIDAD</th>
                                            <th scope="col">MÉDICO</th>
                                            <th scope="col">FECHA</th>
                                            <th scope="col">HORA</th>
                                            <th scope="col">TIPO DE FICHA</th>
                                            <th scope="col">ESTADO</th>
                                            <th scope="col">ANULAR</th>
                                        </tr>
                                    </thead>
                                    <tbody>


                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $agendasTitular; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr style="font-size: 0.8rem"></tr>
                                            <th scope="row"><?php echo e($key + 1); ?> </th>
                                            <td><?php echo e($item['especialidad']); ?> </td>
                                            <td><?php echo e($item['especialista']); ?> </td>
                                            <td> <?php echo e(\Carbon\Carbon::parse($item['fecha'])->format('d/m/Y')); ?> </td>
                                            <td><?php echo e($item['hora']); ?> </td>
                                            <td><?php echo e($item['tipo']); ?> </td>
                                            <td>
                                                <!--[if BLOCK]><![endif]--><?php if($item['esSuspension']): ?>
                                                    <p class="text-orange"><b>SUSPENSIÓN</b>
                                                    </p>
                                                <?php else: ?>
                                                    <!--[if BLOCK]><![endif]--><?php if($item['estado'] === 'ANULADO'): ?>
                                                        <p class="text-red"><b>ANULADO</b></p>
                                                    <?php else: ?>
                                                        <p class="text-green"><b>AGENDADO</b></p>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            </td>
                                            <td style="text-align: center; vertical-align: middle;">
                                                <?php
                                                    $fechaHoraAgenda = \Carbon\Carbon::parse(
                                                        $item['fecha'] . ' ' . $item['hora'],
                                                    );
                                                    $horaActual = \Carbon\Carbon::now();
                                                    $minutosRestantes = $horaActual->diffInMinutes(
                                                        $fechaHoraAgenda,
                                                        false,
                                                    );
                                                ?>
                                                <!--[if BLOCK]><![endif]--><?php if($item['esSuspension']): ?>
                                                    <?php if (isset($component)) { $__componentOriginal5b0227c7f70bd0cddd7837fef9ea647d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5b0227c7f70bd0cddd7837fef9ea647d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.eliminar-button','data' => ['title' => 'ANULAR AGENDA','disabled' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('eliminar-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'ANULAR AGENDA','disabled' => true]); ?>
                                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5b0227c7f70bd0cddd7837fef9ea647d)): ?>
<?php $attributes = $__attributesOriginal5b0227c7f70bd0cddd7837fef9ea647d; ?>
<?php unset($__attributesOriginal5b0227c7f70bd0cddd7837fef9ea647d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5b0227c7f70bd0cddd7837fef9ea647d)): ?>
<?php $component = $__componentOriginal5b0227c7f70bd0cddd7837fef9ea647d; ?>
<?php unset($__componentOriginal5b0227c7f70bd0cddd7837fef9ea647d); ?>
<?php endif; ?>
                                                <?php else: ?>
                                                    <!--[if BLOCK]><![endif]--><?php if($item['estado'] === 'AGENDADO' && $item['fecha'] > \Carbon\Carbon::now()->format('Y-m-d')): ?>
                                                        <?php if (isset($component)) { $__componentOriginal5b0227c7f70bd0cddd7837fef9ea647d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5b0227c7f70bd0cddd7837fef9ea647d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.eliminar-button','data' => ['title' => 'ANULAR AGENDA','wire:click' => '$dispatch(\'confirmar-anulacion\', { id: \''.e($item['id_agenda']).'\' })']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('eliminar-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'ANULAR AGENDA','wire:click' => '$dispatch(\'confirmar-anulacion\', { id: \''.e($item['id_agenda']).'\' })']); ?>
                                                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5b0227c7f70bd0cddd7837fef9ea647d)): ?>
<?php $attributes = $__attributesOriginal5b0227c7f70bd0cddd7837fef9ea647d; ?>
<?php unset($__attributesOriginal5b0227c7f70bd0cddd7837fef9ea647d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5b0227c7f70bd0cddd7837fef9ea647d)): ?>
<?php $component = $__componentOriginal5b0227c7f70bd0cddd7837fef9ea647d; ?>
<?php unset($__componentOriginal5b0227c7f70bd0cddd7837fef9ea647d); ?>
<?php endif; ?>
                                                    <?php else: ?>
                                                        <?php if (isset($component)) { $__componentOriginal5b0227c7f70bd0cddd7837fef9ea647d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5b0227c7f70bd0cddd7837fef9ea647d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.eliminar-button','data' => ['title' => 'ANULAR AGENDA','disabled' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('eliminar-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'ANULAR AGENDA','disabled' => true]); ?>
                                                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5b0227c7f70bd0cddd7837fef9ea647d)): ?>
<?php $attributes = $__attributesOriginal5b0227c7f70bd0cddd7837fef9ea647d; ?>
<?php unset($__attributesOriginal5b0227c7f70bd0cddd7837fef9ea647d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5b0227c7f70bd0cddd7837fef9ea647d)): ?>
<?php $component = $__componentOriginal5b0227c7f70bd0cddd7837fef9ea647d; ?>
<?php unset($__componentOriginal5b0227c7f70bd0cddd7837fef9ea647d); ?>
<?php endif; ?>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#fab32d"
                                        class="bi bi-exclamation-lg" viewBox="0 0 16 16">
                                        <path transform="rotate(180, 8, 8)"
                                            d="M7.005 3.1a1 1 0 1 1 1.99 0l-.388 6.35a.61.61 0 0 1-1.214 0zM7 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0" />
                                    </svg>
                                    <b> AÚN NO TIENES CITAS MÉDICAS </b>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#fab32d"
                                        class="bi bi-exclamation-lg" viewBox="0 0 16 16">
                                        <path
                                            d="M7.005 3.1a1 1 0 1 1 1.99 0l-.388 6.35a.61.61 0 0 1-1.214 0zM7 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0" />
                                    </svg>
                                </div>

                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <!--[if BLOCK]><![endif]--><?php if($tieneGrupoFamiliar === true): ?>
                            <hr class="p-0 m-1">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-center">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="#22c197" class="bi bi-people-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5" />
                                        </svg>
                                        <b style="color:#1863db; font-size:20px">DETALLE DE CITAS DE TU GRUPO
                                            FAMILIAR</b>
                                    </span>
                                </div>
                            </div>
                            <br>

                            <!-- si tiene grupo familiar -->
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $datosBeneficiarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="accordion" id="accordionDatosVerInsti">
                                    <div class="card"
                                        style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                                        <div class="card-header p-1" style="background-color:#22c197"
                                            id="headingVerInsti<?php echo e($key); ?> ">
                                            <h2 class="mb-0">
                                                <button
                                                    class="btn btn-outline btn-block text-left collapsed d-flex justify-content-between align-items-center"
                                                    type="button" style="color: white" data-toggle="collapse"
                                                    data-target="#collapseVerInsti<?php echo e($key); ?>"
                                                    aria-expanded="false"
                                                    aria-controls="collapseVerInsti<?php echo e($key); ?>">
                                                    <span>
                                                        <b><?php echo e($data['nombreBeneficiario']); ?> -
                                                            <?php echo e($data['matriculaBeneficiario']); ?></b>
                                                    </span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                        height="20" fill="currentColor"
                                                        class="bi bi-caret-down-fill " viewBox="0 0 16 16">
                                                        <path
                                                            d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                                                    </svg>
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="collapseVerInsti<?php echo e($key); ?>" class="collapse"
                                            aria-labelledby="headingVerInsti<?php echo e($key); ?>"
                                            data-parent="#accordionDatosVerInsti">
                                            <div class="card-body p-1" style="max-height: auto; overflow-y: auto;">
                                                <!--[if BLOCK]><![endif]--><?php if($data['agendas']->isNotEmpty()): ?>
                                                    <table class="table">
                                                        <thead
                                                            style="background-color:#f7f8ff; position: sticky; top: 0; z-index: 2; font-size: 0.8rem">
                                                            <tr>
                                                                <th scope="col">#</th>
                                                                <th scope="col">ESPECIALIDAD</th>
                                                                <th scope="col">MÉDICO</th>
                                                                <th scope="col">FECHA</th>
                                                                <th scope="col">HORA</th>
                                                                <th scope="col">TIPO FICHA</th>
                                                                <th scope="col">ESTADO</th>
                                                                <th scope="col">ANULAR</th>

                                                            </tr>
                                                        </thead>
                                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $data['agendas']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $agenda): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <tbody>
                                                                <tr style="font-size: 0.8rem">
                                                                    <th scope="row"><?php echo e($i + 1); ?> </th>
                                                                    <td><?php echo e($agenda['especialidad']); ?> </td>
                                                                    <td><?php echo e($agenda['especialista']); ?> </td>
                                                                    <td> <?php echo e(\Carbon\Carbon::parse($agenda['fecha'])->format('d/m/Y')); ?>

                                                                    </td>
                                                                    <td><?php echo e($agenda['hora']); ?> </td>
                                                                    <td><?php echo e($agenda['tipo']); ?> </td>
                                                                    <td>
                                                                        <!--[if BLOCK]><![endif]--><?php if($agenda['esSuspension']): ?>
                                                                            <p class="text-orange"><b>SUSPENSIÓN</b>
                                                                            </p>
                                                                        <?php else: ?>
                                                                            <!--[if BLOCK]><![endif]--><?php if($agenda['estado'] === 'ANULADO'): ?>
                                                                                <p class="text-red"><b>ANULADO</b></p>
                                                                            <?php else: ?>
                                                                                <p class="text-green"><b>AGENDADO</b>
                                                                                </p>
                                                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                                    </td>
                                                                    <td
                                                                        style="text-align: center; vertical-align: middle;">
                                                                        <?php
                                                                            $fechaHoraAgenda = \Carbon\Carbon::parse(
                                                                                $agenda['fecha'] .
                                                                                    ' ' .
                                                                                    $agenda['hora'],
                                                                            );
                                                                            $horaActual = \Carbon\Carbon::now();
                                                                            $minutosRestantes = $horaActual->diffInMinutes(
                                                                                $fechaHoraAgenda,
                                                                                false,
                                                                            );
                                                                        ?>
                                                                        <!--[if BLOCK]><![endif]--><?php if($agenda['esSuspension']): ?>
                                                                            <?php if (isset($component)) { $__componentOriginal5b0227c7f70bd0cddd7837fef9ea647d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5b0227c7f70bd0cddd7837fef9ea647d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.eliminar-button','data' => ['title' => 'ANULAR AGENDA','disabled' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('eliminar-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'ANULAR AGENDA','disabled' => true]); ?>
                                                                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5b0227c7f70bd0cddd7837fef9ea647d)): ?>
<?php $attributes = $__attributesOriginal5b0227c7f70bd0cddd7837fef9ea647d; ?>
<?php unset($__attributesOriginal5b0227c7f70bd0cddd7837fef9ea647d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5b0227c7f70bd0cddd7837fef9ea647d)): ?>
<?php $component = $__componentOriginal5b0227c7f70bd0cddd7837fef9ea647d; ?>
<?php unset($__componentOriginal5b0227c7f70bd0cddd7837fef9ea647d); ?>
<?php endif; ?>
                                                                        <?php else: ?>
                                                                            <!--[if BLOCK]><![endif]--><?php if($agenda['estado'] === 'AGENDADO' && $minutosRestantes >= 30): ?>
                                                                                <?php if (isset($component)) { $__componentOriginal5b0227c7f70bd0cddd7837fef9ea647d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5b0227c7f70bd0cddd7837fef9ea647d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.eliminar-button','data' => ['title' => 'ANULAR AGENDA','wire:click' => '$dispatch(\'confirmar-anulacion\', { id: \''.e($agenda['id_agenda']).'\' })']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('eliminar-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'ANULAR AGENDA','wire:click' => '$dispatch(\'confirmar-anulacion\', { id: \''.e($agenda['id_agenda']).'\' })']); ?>
                                                                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5b0227c7f70bd0cddd7837fef9ea647d)): ?>
<?php $attributes = $__attributesOriginal5b0227c7f70bd0cddd7837fef9ea647d; ?>
<?php unset($__attributesOriginal5b0227c7f70bd0cddd7837fef9ea647d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5b0227c7f70bd0cddd7837fef9ea647d)): ?>
<?php $component = $__componentOriginal5b0227c7f70bd0cddd7837fef9ea647d; ?>
<?php unset($__componentOriginal5b0227c7f70bd0cddd7837fef9ea647d); ?>
<?php endif; ?>
                                                                            <?php else: ?>
                                                                                <?php if (isset($component)) { $__componentOriginal5b0227c7f70bd0cddd7837fef9ea647d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5b0227c7f70bd0cddd7837fef9ea647d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.eliminar-button','data' => ['title' => 'ANULAR AGENDA','disabled' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('eliminar-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'ANULAR AGENDA','disabled' => true]); ?>
                                                                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5b0227c7f70bd0cddd7837fef9ea647d)): ?>
<?php $attributes = $__attributesOriginal5b0227c7f70bd0cddd7837fef9ea647d; ?>
<?php unset($__attributesOriginal5b0227c7f70bd0cddd7837fef9ea647d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5b0227c7f70bd0cddd7837fef9ea647d)): ?>
<?php $component = $__componentOriginal5b0227c7f70bd0cddd7837fef9ea647d; ?>
<?php unset($__componentOriginal5b0227c7f70bd0cddd7837fef9ea647d); ?>
<?php endif; ?>
                                                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                                    </td>
                                                                </tr>

                                                            </tbody>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                    </table>
                                                <?php else: ?>
                                                    <div class="text-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="25"
                                                            height="25" fill="#fab32d"
                                                            class="bi bi-exclamation-lg" viewBox="0 0 16 16">
                                                            <path transform="rotate(180, 8, 8)"
                                                                d="M7.005 3.1a1 1 0 1 1 1.99 0l-.388 6.35a.61.61 0 0 1-1.214 0zM7 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0" />
                                                        </svg>
                                                        <b> AÚN NO TIENE CITAS MÉDICAS </b>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="25"
                                                            height="25" fill="#fab32d"
                                                            class="bi bi-exclamation-lg" viewBox="0 0 16 16">
                                                            <path
                                                                d="M7.005 3.1a1 1 0 1 1 1.99 0l-.388 6.35a.61.61 0 0 1-1.214 0zM7 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0" />
                                                        </svg>
                                                    </div>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5 col-md-6 col-sm-12 col-12 " wire:ignore>
            <div class="card card-comun p-1" style=" border: 1px solid #dee2e6; border-radius: 8px;">
                <div id="calendario-asegurado" style="height: 50vh; width: 100%; text-align:center;"></div>
            </div>
        </div>
        <?php echo $__env->make('livewire.Administracion.dashboard.plataforma.modalDetalleAgenda', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    </div>
    <style>
        /* Cambiar el color de las letras a negro */
        .fc .fc-col-header-cell-cushion,
        .fc .fc-daygrid-day-number,
        .fc .fc-daygrid-day-events,
        .fc .fc-daygrid-day-frame {
            color: #000;
        }

        /* Cambiar el tamaño de la letra en el título del calendario */
        .fc .fc-toolbar-title {
            font-size: 30px;
            font-weight: bold;
            color: #2471a3;
            text-transform: uppercase;
            background-color: #e5f0f9;
            padding: 2px;
            border-radius: 8px;
        }

        .fc .fc-col-header-cell-cushion {
            font-size: 20px;
            color: #000;
            text-transform: uppercase;
        }

        /* Cambiar el color de las líneas del calendario a negro */
        .fc .fc-daygrid-day-frame,
        .fc .fc-daygrid-day-top,
        .fc .fc-col-header-cell,
        .fc .fc-scrollgrid-sync-inner {
            border-color: #143D80;
        }

        .fc .fc-button.fc-today-button {
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .fc .fc-day-today {
            background-color: #baebb1ff !important;
            border: 2px solid #7fcfa0ff;
        }

        .fc .fc-daygrid-day:hover {
            background-color: #f2f2f2;
            cursor: pointer;
        }
    </style>

</div>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const calendarAsegurado = document.getElementById('calendario-asegurado');
        let fechasConAgendas = <?php echo json_encode($agenda_por_fecha, 15, 512) ?>;
        // console.log('jasd',fechasConAgendas);
        const calendar = new FullCalendar.Calendar(calendarAsegurado, {
            initialView: 'dayGridMonth',
            locale: 'es',

            //height: 500,

            buttonText: {
                today: 'Hoy', // Texto del botón "Today"
            },


            // Botones personalizados
            customButtons: {
                prev: {
                    text: 'MES ANTERIOR', // Texto del botón
                    click: function() {
                        calendar.prev(); // Acción predeterminada
                    },
                },
                next: {
                    text: 'MES SIGUIENTE', // Texto del botón
                    click: function() {
                        calendar.next(); // Acción predeterminada
                    },
                },
            },
            dayCellDidMount: function(info) {
                let fecha = info.date.toISOString().split('T')[0]; // YYYY-MM-DD
                // console.log('entro',fecha);
                if (fechasConAgendas.includes(fecha)) {
                    let icono = document.createElement('span');
                    icono.innerHTML = `
                       <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#2471a3" class="bi bi-calendar-plus-fill" viewBox="0 0 16 16">
                          <path d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2M8.5 8.5V10H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V11H6a.5.5 0 0 1 0-1h1.5V8.5a.5.5 0 0 1 1 0"/>
                        </svg>`;
                    info.el.style.position = 'relative';
                    icono.style.position = 'absolute';
                    icono.style.bottom = '4px';
                    icono.style.right = '4px';
                    info.el.appendChild(icono);
                }
            },

            // Aca empieza los click por día
            dateClick: function(fecha) {
                if (typeof Livewire !== 'undefined') {
                    Livewire.dispatch('detalleAgenda', [fecha.dateStr]);
                }

                Livewire.on('abrirModalAgenda', () => {
                    $('#detalle_agenda').modal('show');
                    setTimeout(() => {
                        calendar.render();
                    }, 100);
                });

                Livewire.on('renderizar', () => {
                    setTimeout(() => {
                        calendar.render();
                    }, 100);
                });
            }
        });
        calendar.render();
    });

    document.addEventListener('noHayAgenda', function() {
        Swal.fire({
            title: "SIN AGENDAS REGISTRADAS.",
            text: "No hay registros de cita medica en esta fecha.",
            icon: "warning",
            timer: 10000,
        });
    });


    document.addEventListener('livewire:initialized', () => {
        Livewire.on('confirmar-anulacion', (data) => {
            // const motivo = "";
            Swal.fire({
                title: '¿Desea anular agenda?',
                icon: 'question',
                text: "Describa los motivos para anular su agenda:",
                input: "text",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, anular',
                cancelButtonText: 'Cancelar',
                preConfirm: async (motivo_texto) => {
                    if (!motivo_texto) {
                        Swal.showValidationMessage(`Introduzca el motivo por favor`);
                        return false;
                    }
                    return motivo_texto;
                },
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    const motivo = result.value;
                    Livewire.dispatch('anularAgenda', {
                        id_agenda: data.id,
                        motivo: motivo
                    });
                    document.getElementById('cargando-anulacion-web').style.display = 'block';
                }
            })
        });

        Livewire.on('alerta', (data) => {
            Swal.fire({
                icon: data.tipo,
                title: data.mensaje,
                showConfirmButton: false,
                timer: 2000
            });
        });
        Livewire.on('recargarPagina', () => {
            window.location.reload();
        });
    });
    document.addEventListener('anulacionExitosa', function() {
        Swal.fire({
            title: "Agenda anulada",
            text: "Se anulo correctamente la agenda",
            icon: "success",
            timer: 10000,
        });
    });
    document.addEventListener('limiteAnulaciones', function(event) {
        const mensaje = event.detail;
        $('#VerAgendaAsegurado').modal('hide');
        Swal.fire({
            title: `${mensaje.detail}`,
            icon: "warning"
        });
    });
</script>
<?php /**PATH C:\Users\TIC-HP\SSU\SIGHOV2_dev\resources\views/livewire/Administracion/dashboard/plataforma/agenda-asegurado.blade.php ENDPATH**/ ?>