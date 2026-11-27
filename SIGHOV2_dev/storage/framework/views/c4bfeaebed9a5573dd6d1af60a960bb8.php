<?php if (isset($component)) { $__componentOriginalc91bb4ed2df756ac18bcc72375f3743b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc91bb4ed2df756ac18bcc72375f3743b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ver-modal-sin-titulo','data' => ['id' => 'detalle_agenda','labelledby' => 'detalle_agendaLabel']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('ver-modal-sin-titulo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'detalle_agenda','labelledby' => 'detalle_agendaLabel']); ?>

    <!--[if BLOCK]><![endif]--><?php if($agenda_datos): ?>
        <!--[if BLOCK]><![endif]--><?php if(count($agenda_datos) === 1): ?>
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $agenda_datos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $datos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12 ">
                        <div class="card-comun p-2 mt-1"
                            style="height: 100%;  border: 1px solid #dee2e6;border-radius: 8px;">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div
                                        style=" background-color: #e5f0f9;padding: 2px; border-radius: 8px; color: #2471a3;text-align:center">
                                        <!--[if BLOCK]><![endif]--><?php if($datos['propio'] === 'PROPIO'): ?>
                                            <b>MIS DATOS PERSONALES</b>
                                        <?php else: ?>
                                            <b>DATOS PERSONALES</b>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </div>
                            </div>
                            <div style="height: 0.5rem;"></div>
                            <div class="row">
                                <div class="col-lg-6 col-md-12 col-sm-6 col-6  text-center">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-12  text-center">
                                            <img src="<?php echo e(!empty($fotoAgendaExistente) ? 'data:image/jpg;base64,' . $foto_nombre : asset('images/default_afiliacion.jpg')); ?>"
                                                class="img-thumbnail" alt="img"
                                                style="width: 140px; height: 140px; object-fit: cover;">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-6 col-6  text-center">
                                    <div class="row p-2">
                                        <table style="text-align: left;">
                                            <tr>
                                                <td>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" fill="currentColor" class="bi bi-person-circle"
                                                        viewBox="0 0 16 16">
                                                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                                        <path fill-rule="evenodd"
                                                            d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                                                    </svg>
                                                    <b><?php echo e($datos['nombre_paciente']); ?> </b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" fill="currentColor"
                                                        class="bi bi-person-vcard-fill" viewBox="0 0 16 16">
                                                        <path
                                                            d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm9 1.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4a.5.5 0 0 0-.5.5M9 8a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4A.5.5 0 0 0 9 8m1 2.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 0-1h-3a.5.5 0 0 0-.5.5m-1 2C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 0 2 13h6.96q.04-.245.04-.5M7 6a2 2 0 1 0-4 0 2 2 0 0 0 4 0" />
                                                    </svg>
                                                    <b style="color: #1a5276 ;"><?php echo e($datos['matricula_paciente']); ?>

                                                    </b>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" fill="currentColor" class="bi bi-balloon-fill"
                                                        viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd"
                                                            d="M8.48 10.901C11.211 10.227 13 7.837 13 5A5 5 0 0 0 3 5c0 2.837 1.789 5.227 4.52 5.901l-.244.487a.25.25 0 1 0 .448.224l.04-.08c.009.17.024.315.051.45.068.344.208.622.448 1.102l.013.028c.212.422.182.85.05 1.246-.135.402-.366.751-.534 1.003a.25.25 0 0 0 .416.278l.004-.007c.166-.248.431-.646.588-1.115.16-.479.212-1.051-.076-1.629-.258-.515-.365-.732-.419-1.004a2 2 0 0 1-.037-.289l.008.017a.25.25 0 1 0 .448-.224zM4.352 3.356a4 4 0 0 1 3.15-2.325C7.774.997 8 1.224 8 1.5s-.226.496-.498.542c-.95.162-1.749.78-2.173 1.617a.6.6 0 0 1-.52.341c-.346 0-.599-.329-.457-.644" />
                                                    </svg>
                                                    <b>EDAD: </b><?php echo e($datos['edad']); ?> años
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b style="color: #1a5276 ;font-size:18px"><?php echo e($datos['tipo_asegurado']); ?>

                                                    </b>
                                                </td>
                                            </tr>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-4 col-md-8 col-sm-12 col-12">
                        <div class="card-comun p-2 mt-1"
                            style="height: 100%;  border: 1px solid #dee2e6;border-radius: 8px;">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div
                                        style=" background-color: #e5f0f9;padding: 2px; border-radius: 8px; color: #2471a3;text-align:center">
                                        <!--[if BLOCK]><![endif]--><?php if($datos['propio'] === 'PROPIO'): ?>
                                            <b>DATOS DE MI AGENDA</b>
                                        <?php else: ?>
                                            <b>DATOS DE AGENDA</b>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-5 col-md-4 col-sm-5 col-5">
                                    <strong>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill=" #1E8449" class="bi bi-stopwatch-fill" viewBox="0 0 16 16">
                                            <path
                                                d=" M6.5 0a.5.5 0 0 0 0 1H7v1.07A7.001 7.001 0 0 0 8 16a7 7 0 0 0 5.29-11.584l.013-.012.354-.354.353.354a.5.5 0 1 0 .707-.707l-1.414-1.415a.5.5 0 1 0-.707.707l.354.354-.354.354-.012.012A6.97 6.97 0 0 0 9 2.071V1h.5a.5.5 0 0 0 0-1zm2 5.6V9a.5.5 0 0 1-.5.5H4.5a.5.5 0 0 1 0-1h3V5.6a.5.5 0 1 1 1 0" />
                                        </svg>
                                        HORA:
                                    </strong>
                                    <span>
                                        <?php echo e($datos['hora_agenda']); ?>

                                    </span>
                                </div>

                                <div class="col-lg-7 col-md-8 col-sm-7 col-7">
                                    <strong>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill=" #1E8449" class="bi bi-calendar2-check-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5m9.954 3H2.545c-.3 0-.545.224-.545.5v1c0 .276.244.5.545.5h10.91c.3 0 .545-.224.545-.5v-1c0-.276-.244-.5-.546-.5m-2.6 5.854a.5.5 0 0 0-.708-.708L7.5 10.793 6.354 9.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z" />
                                        </svg>
                                        FECHA CITA:
                                    </strong>
                                    <span>
                                        <?php echo e($datos['fecha_cita']); ?>

                                    </span>
                                </div>

                                <div class="col-lg-5 col-md-4 col-sm-5 col-5">
                                    <strong>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-calendar-day-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V5h16zm-4.785-6.145a.428.428 0 1 0 0-.855.426.426 0 0 0-.43.43c0 .238.192.425.43.425m.336.563h-.672v4.105h.672zm-6.867 4.105v-2.3h2.261v-.61H4.684V7.801h2.464v-.61H4v5.332zm3.296 0h.676V9.98c0-.554.227-1.007.953-1.007.125 0 .258.004.329.015v-.613a2 2 0 0 0-.254-.02c-.582 0-.891.32-1.012.567h-.02v-.504H7.98z" />
                                        </svg>
                                        DÍA:
                                    </strong>
                                    <span>
                                        <?php echo e($datos['dia_agenda']); ?>

                                    </span>
                                </div>
                                <div class="col-lg-7 col-md-8 col-sm-7 col-7">
                                    <strong>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-check2-circle" viewBox="0 0 16 16">
                                            <path
                                                d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0" />
                                            <path
                                                d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0z" />
                                        </svg>
                                        SERVICIO AGENDADO:
                                    </strong>
                                    <span>
                                        <?php echo e($datos['tipo_ficha']); ?>

                                    </span>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <strong>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-calendar2-check-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5m9.954 3H2.545c-.3 0-.545.224-.545.5v1c0 .276.244.5.545.5h10.91c.3 0 .545-.224.545-.5v-1c0-.276-.244-.5-.546-.5m-2.6 5.854a.5.5 0 0 0-.708-.708L7.5 10.793 6.354 9.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z" />
                                        </svg>
                                        FECHA DE REGISTRO:
                                    </strong>
                                    <span>
                                        <?php echo e($datos['creacion_ficha']); ?>

                                    </span>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <hr>
                                    <strong>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">
                                            <path
                                                d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A32 32 0 0 1 8 14.58a32 32 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10" />
                                            <path
                                                d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                        </svg> UBICACIÓN:
                                    </strong>
                                    <span>
                                        <?php echo e($datos['ubicacion_ficha']); ?>

                                    </span>
                                </div>
                                <div class="col-lg-5 col-md-4 col-sm-5 col-5">
                                    <strong>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-buildings-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M15 .5a.5.5 0 0 0-.724-.447l-8 4A.5.5 0 0 0 6 4.5v3.14L.342 9.526A.5.5 0 0 0 0 10v5.5a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5V14h1v1.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5zM2 11h1v1H2zm2 0h1v1H4zm-1 2v1H2v-1zm1 0h1v1H4zm9-10v1h-1V3zM8 5h1v1H8zm1 2v1H8V7zM8 9h1v1H8zm2 0h1v1h-1zm-1 2v1H8v-1zm1 0h1v1h-1zm3-2v1h-1V9zm-1 2h1v1h-1zm-2-4h1v1h-1zm3 0v1h-1V7zm-2-2v1h-1V5zm1 0h1v1h-1z" />
                                        </svg> PISO:
                                    </strong>
                                    <span>
                                        <?php echo e($datos['piso']); ?>

                                    </span>
                                </div>
                                <div class="col-lg-7 col-md-8 col-sm-7 col-7">
                                    <strong>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-door-open-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M1.5 15a.5.5 0 0 0 0 1h13a.5.5 0 0 0 0-1H13V2.5A1.5 1.5 0 0 0 11.5 1H11V.5a.5.5 0 0 0-.57-.495l-7 1A.5.5 0 0 0 3 1.5V15zM11 2h.5a.5.5 0 0 1 .5.5V15h-1zm-2.5 8c-.276 0-.5-.448-.5-1s.224-1 .5-1 .5.448.5 1-.224 1-.5 1" />
                                        </svg> CONSULTORIO:
                                    </strong>
                                    <span>
                                        <?php echo e($datos['consultorio']); ?>

                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                        <div class="card-comun p-2 mt-1"
                            style="height: 100%;  border: 1px solid #dee2e6;border-radius: 8px;">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div
                                        style=" background-color: #e5f0f9;padding: 2px; border-radius: 8px; color: #2471a3;text-align:center">

                                        <b>DATOS DEL MÉDICO</b>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <strong>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-heart-pulse-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M1.475 9C2.702 10.84 4.779 12.871 8 15c3.221-2.129 5.298-4.16 6.525-6H12a.5.5 0 0 1-.464-.314l-1.457-3.642-1.598 5.593a.5.5 0 0 1-.945.049L5.889 6.568l-1.473 2.21A.5.5 0 0 1 4 9z" />
                                            <path
                                                d="M.88 8C-2.427 1.68 4.41-2 7.823 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C11.59-2 18.426 1.68 15.12 8h-2.783l-1.874-4.686a.5.5 0 0 0-.945.049L7.921 8.956 6.464 5.314a.5.5 0 0 0-.88-.091L3.732 8z" />
                                        </svg> ESPECIALIDAD:
                                    </strong>
                                    <span>
                                        <?php echo e($datos['especialidad_agenda']); ?>

                                    </span>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <strong>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                        </svg> ESPECIALISTA
                                    </strong>
                                    <span>
                                        <?php echo e($datos['especialista_agenda']); ?>

                                    </span>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <strong>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-stopwatch-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M6.5 0a.5.5 0 0 0 0 1H7v1.07A7.001 7.001 0 0 0 8 16a7 7 0 0 0 5.29-11.584l.013-.012.354-.354.353.354a.5.5 0 1 0 .707-.707l-1.414-1.415a.5.5 0 1 0-.707.707l.354.354-.354.354-.012.012A6.97 6.97 0 0 0 9 2.071V1h.5a.5.5 0 0 0 0-1zm2 5.6V9a.5.5 0 0 1-.5.5H4.5a.5.5 0 0 1 0-1h3V5.6a.5.5 0 1 1 1 0" />
                                        </svg> HORARIO:
                                    </strong>
                                    <span>
                                        <?php echo e($datos['horario']); ?>

                                    </span>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <strong>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-hourglass-split" viewBox="0 0 16 16">
                                            <path
                                                d="M2.5 15a.5.5 0 1 1 0-1h1v-1a4.5 4.5 0 0 1 2.557-4.06c.29-.139.443-.377.443-.59v-.7c0-.213-.154-.451-.443-.59A4.5 4.5 0 0 1 3.5 3V2h-1a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1h-1v1a4.5 4.5 0 0 1-2.557 4.06c-.29.139-.443.377-.443.59v.7c0 .213.154.451.443.59A4.5 4.5 0 0 1 12.5 13v1h1a.5.5 0 0 1 0 1zm2-13v1c0 .537.12 1.045.337 1.5h6.326c.216-.455.337-.963.337-1.5V2zm3 6.35c0 .701-.478 1.236-1.011 1.492A3.5 3.5 0 0 0 4.5 13s.866-1.299 3-1.48zm1 0v3.17c2.134.181 3 1.48 3 1.48a3.5 3.5 0 0 0-1.989-3.158C8.978 9.586 8.5 9.052 8.5 8.351z" />
                                        </svg> TURNO:
                                    </strong>
                                    <span>
                                        <?php echo e($datos['turno']); ?>

                                    </span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        <?php else: ?>
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $agenda_datos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $datos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="accordion" id="accordionAgendados">
                    <div class="card " style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                        <div class="card-header p-1" style="background-color:#f2f8fd"
                            id="headingVerAgenda<?php echo e($key); ?>">
                            <h2 class="mb-0">
                                <button
                                    class="btn btn-outline btn-block text-left collapsed d-flex justify-content-between align-items-center"
                                    type="button" style="color: #1b5989" data-toggle="collapse"
                                    data-target="#collapseVerAgenda<?php echo e($key); ?>" aria-expanded="false"
                                    aria-controls="collapseVerAgenda<?php echo e($key); ?>">

                                    <strong>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                                            <path
                                                d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
                                        </svg>
                                        VER AGENDA DE: </strong> <?php echo e($datos['nombre_paciente']); ?>


                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        fill="currentColor" class="bi bi-caret-down-fill " viewBox="0 0 16 16">
                                        <path
                                            d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                                    </svg>
                                </button>
                            </h2>
                        </div>
                        <div id="collapseVerAgenda<?php echo e($key); ?>" class="collapse"
                            aria-labelledby="headingVerAgenda<?php echo e($key); ?>" data-parent="#accordionAgendados">
                            <div class="card-body p-1">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-12 ">
                                        <div class="card-comun p-2 mt-1"
                                            style="height: 100%;  border: 1px solid #dee2e6;border-radius: 8px;">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                                    <div
                                                        style=" background-color: #e5f0f9;padding: 2px; border-radius: 8px; color: #2471a3;text-align:center">
                                                        <b>DATOS PERSONALES</b>
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="height: 0.5rem;"></div>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-12 col-sm-6 col-6  text-center">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-12  text-center">
                                                            <img src="<?php echo e(!empty($fotoAgendaExistente) ? 'data:image/jpg;base64,' . $foto_nombre : asset('images/default_afiliacion.jpg')); ?>"
                                                                class="img-thumbnail" alt="img"
                                                                style="width: 140px; height: 140px; object-fit: cover;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-12 col-sm-6 col-6  text-center">
                                                    <div class="row p-2">
                                                        <table style="text-align: left;">
                                                            <tr>
                                                                <td>
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        width="16" height="16"
                                                                        fill="currentColor"
                                                                        class="bi bi-person-circle"
                                                                        viewBox="0 0 16 16">
                                                                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                                                        <path fill-rule="evenodd"
                                                                            d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                                                                    </svg>
                                                                    <b><?php echo e($datos['nombre_paciente']); ?> </b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        width="16" height="16"
                                                                        fill="currentColor"
                                                                        class="bi bi-person-vcard-fill"
                                                                        viewBox="0 0 16 16">
                                                                        <path
                                                                            d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm9 1.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4a.5.5 0 0 0-.5.5M9 8a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4A.5.5 0 0 0 9 8m1 2.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 0-1h-3a.5.5 0 0 0-.5.5m-1 2C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 0 2 13h6.96q.04-.245.04-.5M7 6a2 2 0 1 0-4 0 2 2 0 0 0 4 0" />
                                                                    </svg>
                                                                    <b style="color: #1a5276 ;"><?php echo e($datos['matricula_paciente']); ?>

                                                                    </b>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        width="16" height="16"
                                                                        fill="currentColor" class="bi bi-balloon-fill"
                                                                        viewBox="0 0 16 16">
                                                                        <path fill-rule="evenodd"
                                                                            d="M8.48 10.901C11.211 10.227 13 7.837 13 5A5 5 0 0 0 3 5c0 2.837 1.789 5.227 4.52 5.901l-.244.487a.25.25 0 1 0 .448.224l.04-.08c.009.17.024.315.051.45.068.344.208.622.448 1.102l.013.028c.212.422.182.85.05 1.246-.135.402-.366.751-.534 1.003a.25.25 0 0 0 .416.278l.004-.007c.166-.248.431-.646.588-1.115.16-.479.212-1.051-.076-1.629-.258-.515-.365-.732-.419-1.004a2 2 0 0 1-.037-.289l.008.017a.25.25 0 1 0 .448-.224zM4.352 3.356a4 4 0 0 1 3.15-2.325C7.774.997 8 1.224 8 1.5s-.226.496-.498.542c-.95.162-1.749.78-2.173 1.617a.6.6 0 0 1-.52.341c-.346 0-.599-.329-.457-.644" />
                                                                    </svg>
                                                                    <b>EDAD: </b><?php echo e($datos['edad']); ?> años
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <b style="color: #1a5276 ;font-size:18px"><?php echo e($datos['tipo_asegurado']); ?>

                                                                    </b>
                                                                </td>
                                                            </tr>
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-lg-4 col-md-8 col-sm-12 col-12">
                                        <div class="card-comun p-2 mt-1"
                                            style="height: 100%;  border: 1px solid #dee2e6;border-radius: 8px;">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                                    <div
                                                        style=" background-color: #e5f0f9;padding: 2px; border-radius: 8px; color: #2471a3;text-align:center">
                                                        <b>DATOS DE AGENDA</b>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-5 col-md-4 col-sm-5 col-5">
                                                    <strong>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill=" #1E8449"
                                                            class="bi bi-stopwatch-fill" viewBox="0 0 16 16">
                                                            <path
                                                                d=" M6.5 0a.5.5 0 0 0 0 1H7v1.07A7.001 7.001 0 0 0 8 16a7 7 0 0 0 5.29-11.584l.013-.012.354-.354.353.354a.5.5 0 1 0 .707-.707l-1.414-1.415a.5.5 0 1 0-.707.707l.354.354-.354.354-.012.012A6.97 6.97 0 0 0 9 2.071V1h.5a.5.5 0 0 0 0-1zm2 5.6V9a.5.5 0 0 1-.5.5H4.5a.5.5 0 0 1 0-1h3V5.6a.5.5 0 1 1 1 0" />
                                                        </svg>
                                                        HORA:
                                                    </strong>
                                                    <span>
                                                        <?php echo e($datos['hora_agenda']); ?>

                                                    </span>
                                                </div>

                                                <div class="col-lg-7 col-md-8 col-sm-7 col-7">
                                                    <strong>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill=" #1E8449"
                                                            class="bi bi-calendar2-check-fill" viewBox="0 0 16 16">
                                                            <path
                                                                d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5m9.954 3H2.545c-.3 0-.545.224-.545.5v1c0 .276.244.5.545.5h10.91c.3 0 .545-.224.545-.5v-1c0-.276-.244-.5-.546-.5m-2.6 5.854a.5.5 0 0 0-.708-.708L7.5 10.793 6.354 9.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z" />
                                                        </svg>
                                                        FECHA CITA:
                                                    </strong>
                                                    <span>
                                                        <?php echo e($datos['fecha_cita']); ?>

                                                    </span>
                                                </div>

                                                <div class="col-lg-5 col-md-4 col-sm-5 col-5">
                                                    <strong>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor"
                                                            class="bi bi-calendar-day-fill" viewBox="0 0 16 16">
                                                            <path
                                                                d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V5h16zm-4.785-6.145a.428.428 0 1 0 0-.855.426.426 0 0 0-.43.43c0 .238.192.425.43.425m.336.563h-.672v4.105h.672zm-6.867 4.105v-2.3h2.261v-.61H4.684V7.801h2.464v-.61H4v5.332zm3.296 0h.676V9.98c0-.554.227-1.007.953-1.007.125 0 .258.004.329.015v-.613a2 2 0 0 0-.254-.02c-.582 0-.891.32-1.012.567h-.02v-.504H7.98z" />
                                                        </svg>
                                                        DÍA:
                                                    </strong>
                                                    <span>
                                                        <?php echo e($datos['dia_agenda']); ?>

                                                    </span>
                                                </div>
                                                <div class="col-lg-7 col-md-8 col-sm-7 col-7">
                                                    <strong>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor"
                                                            class="bi bi-check2-circle" viewBox="0 0 16 16">
                                                            <path
                                                                d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0" />
                                                            <path
                                                                d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0z" />
                                                        </svg>
                                                        SERVICIO AGENDADO:
                                                    </strong>
                                                    <span>
                                                        <?php echo e($datos['tipo_ficha']); ?>

                                                    </span>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                                    <strong>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor"
                                                            class="bi bi-calendar2-check-fill" viewBox="0 0 16 16">
                                                            <path
                                                                d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5m9.954 3H2.545c-.3 0-.545.224-.545.5v1c0 .276.244.5.545.5h10.91c.3 0 .545-.224.545-.5v-1c0-.276-.244-.5-.546-.5m-2.6 5.854a.5.5 0 0 0-.708-.708L7.5 10.793 6.354 9.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z" />
                                                        </svg>
                                                        FECHA DE REGISTRO:
                                                    </strong>
                                                    <span>
                                                        <?php echo e($datos['creacion_ficha']); ?>

                                                    </span>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                                    <hr>
                                                    <strong>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor" class="bi bi-geo-alt"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A32 32 0 0 1 8 14.58a32 32 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10" />
                                                            <path
                                                                d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                                        </svg> UBICACIÓN:
                                                    </strong>
                                                    <span>
                                                        <?php echo e($datos['ubicacion_ficha']); ?>

                                                    </span>
                                                </div>
                                                <div class="col-lg-5 col-md-4 col-sm-5 col-5">
                                                    <strong>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor"
                                                            class="bi bi-buildings-fill" viewBox="0 0 16 16">
                                                            <path
                                                                d="M15 .5a.5.5 0 0 0-.724-.447l-8 4A.5.5 0 0 0 6 4.5v3.14L.342 9.526A.5.5 0 0 0 0 10v5.5a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5V14h1v1.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5zM2 11h1v1H2zm2 0h1v1H4zm-1 2v1H2v-1zm1 0h1v1H4zm9-10v1h-1V3zM8 5h1v1H8zm1 2v1H8V7zM8 9h1v1H8zm2 0h1v1h-1zm-1 2v1H8v-1zm1 0h1v1h-1zm3-2v1h-1V9zm-1 2h1v1h-1zm-2-4h1v1h-1zm3 0v1h-1V7zm-2-2v1h-1V5zm1 0h1v1h-1z" />
                                                        </svg> PISO:
                                                    </strong>
                                                    <span>
                                                        <?php echo e($datos['piso']); ?>

                                                    </span>
                                                </div>
                                                <div class="col-lg-7 col-md-8 col-sm-7 col-7">
                                                    <strong>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor"
                                                            class="bi bi-door-open-fill" viewBox="0 0 16 16">
                                                            <path
                                                                d="M1.5 15a.5.5 0 0 0 0 1h13a.5.5 0 0 0 0-1H13V2.5A1.5 1.5 0 0 0 11.5 1H11V.5a.5.5 0 0 0-.57-.495l-7 1A.5.5 0 0 0 3 1.5V15zM11 2h.5a.5.5 0 0 1 .5.5V15h-1zm-2.5 8c-.276 0-.5-.448-.5-1s.224-1 .5-1 .5.448.5 1-.224 1-.5 1" />
                                                        </svg> CONSULTORIO:
                                                    </strong>
                                                    <span>
                                                        <?php echo e($datos['consultorio']); ?>

                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                                        <div class="card-comun p-2 mt-1"
                                            style="height: 100%;  border: 1px solid #dee2e6;border-radius: 8px;">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                                    <div
                                                        style=" background-color: #e5f0f9;padding: 2px; border-radius: 8px; color: #2471a3;text-align:center">
                                                        <b>DATOS DEL MÉDICO</b>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                                    <strong>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor"
                                                            class="bi bi-heart-pulse-fill" viewBox="0 0 16 16">
                                                            <path
                                                                d="M1.475 9C2.702 10.84 4.779 12.871 8 15c3.221-2.129 5.298-4.16 6.525-6H12a.5.5 0 0 1-.464-.314l-1.457-3.642-1.598 5.593a.5.5 0 0 1-.945.049L5.889 6.568l-1.473 2.21A.5.5 0 0 1 4 9z" />
                                                            <path
                                                                d="M.88 8C-2.427 1.68 4.41-2 7.823 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C11.59-2 18.426 1.68 15.12 8h-2.783l-1.874-4.686a.5.5 0 0 0-.945.049L7.921 8.956 6.464 5.314a.5.5 0 0 0-.88-.091L3.732 8z" />
                                                        </svg> ESPECIALIDAD:
                                                    </strong>
                                                    <span>
                                                        <?php echo e($datos['especialidad_agenda']); ?>

                                                    </span>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                                    <strong>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor"
                                                            class="bi bi-person-fill" viewBox="0 0 16 16">
                                                            <path
                                                                d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                                        </svg> ESPECIALISTA
                                                    </strong>
                                                    <span>
                                                        <?php echo e($datos['especialista_agenda']); ?>

                                                    </span>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                                    <strong>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor"
                                                            class="bi bi-stopwatch-fill" viewBox="0 0 16 16">
                                                            <path
                                                                d="M6.5 0a.5.5 0 0 0 0 1H7v1.07A7.001 7.001 0 0 0 8 16a7 7 0 0 0 5.29-11.584l.013-.012.354-.354.353.354a.5.5 0 1 0 .707-.707l-1.414-1.415a.5.5 0 1 0-.707.707l.354.354-.354.354-.012.012A6.97 6.97 0 0 0 9 2.071V1h.5a.5.5 0 0 0 0-1zm2 5.6V9a.5.5 0 0 1-.5.5H4.5a.5.5 0 0 1 0-1h3V5.6a.5.5 0 1 1 1 0" />
                                                        </svg> HORARIO:
                                                    </strong>
                                                    <span>
                                                        <?php echo e($datos['horario']); ?>

                                                    </span>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                                    <strong>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor"
                                                            class="bi bi-hourglass-split" viewBox="0 0 16 16">
                                                            <path
                                                                d="M2.5 15a.5.5 0 1 1 0-1h1v-1a4.5 4.5 0 0 1 2.557-4.06c.29-.139.443-.377.443-.59v-.7c0-.213-.154-.451-.443-.59A4.5 4.5 0 0 1 3.5 3V2h-1a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1h-1v1a4.5 4.5 0 0 1-2.557 4.06c-.29.139-.443.377-.443.59v.7c0 .213.154.451.443.59A4.5 4.5 0 0 1 12.5 13v1h1a.5.5 0 0 1 0 1zm2-13v1c0 .537.12 1.045.337 1.5h6.326c.216-.455.337-.963.337-1.5V2zm3 6.35c0 .701-.478 1.236-1.011 1.492A3.5 3.5 0 0 0 4.5 13s.866-1.299 3-1.48zm1 0v3.17c2.134.181 3 1.48 3 1.48a3.5 3.5 0 0 0-1.989-3.158C8.978 9.586 8.5 9.052 8.5 8.351z" />
                                                        </svg> TURNO:
                                                    </strong>
                                                    <span>
                                                        <?php echo e($datos['turno']); ?>

                                                    </span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->


 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc91bb4ed2df756ac18bcc72375f3743b)): ?>
<?php $attributes = $__attributesOriginalc91bb4ed2df756ac18bcc72375f3743b; ?>
<?php unset($__attributesOriginalc91bb4ed2df756ac18bcc72375f3743b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc91bb4ed2df756ac18bcc72375f3743b)): ?>
<?php $component = $__componentOriginalc91bb4ed2df756ac18bcc72375f3743b; ?>
<?php unset($__componentOriginalc91bb4ed2df756ac18bcc72375f3743b); ?>
<?php endif; ?>
<?php /**PATH C:\Users\TIC-HP\SSU\SIGHOV2_dev\resources\views/livewire/Administracion/dashboard/plataforma/modalDetalleAgenda.blade.php ENDPATH**/ ?>