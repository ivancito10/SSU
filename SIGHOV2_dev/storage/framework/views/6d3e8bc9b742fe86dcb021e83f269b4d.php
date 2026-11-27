<?php
use Carbon\Carbon;
?>
<!--[if BLOCK]><![endif]--><?php if($this->numero==0): ?>
<em class="text-info"><b>No existen notificaciones por el momento...</b></em>
<?php endif; ?><!--[if ENDBLOCK]><![endif]-->
<!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->notificaciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $not): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="card ">
    <h5 class="card-header text-white bg-dark">
        <!--[if BLOCK]><![endif]--><?php if($not->prioridades==0): ?>
        <svg style="color: rgb(13, 216, 243);" xmlns="http://www.w3.org/2000/svg" width="16"
            height="16" fill="currentColor" class="bi bi-record-circle-fill" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-8 3a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
        </svg>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <!--[if BLOCK]><![endif]--><?php if($not->prioridades==1): ?>
        <svg style="color: rgb(248, 212, 6);" xmlns="http://www.w3.org/2000/svg" width="16"
            height="16" fill="currentColor" class="bi bi-record-circle-fill" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-8 3a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
        </svg>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <!--[if BLOCK]><![endif]--><?php if($not->prioridades==2): ?>
        <svg style="color: red;" xmlns="http://www.w3.org/2000/svg" width="16"
            height="16" fill="currentColor" class="bi bi-record-circle-fill" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-8 3a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
        </svg>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <?php echo e($not->asunto); ?>

    </h5>
    <div class="card-body">
        <h5 class="card-title"></h5>
        <p class="card-text" style=" width: 400px; 
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;"><?php echo e($not->mensaje); ?>

        </p>

        <div style="display: flex; justify-content: space-between; align-items: center;">
            <button wire:click="notificacion_abierta(<?php echo e($not->id); ?>)" class="btn btn-primary" type="button" data-toggle="collapse"
                data-target="#collapseExample<?php echo e($index); ?>" aria-expanded="false"
                aria-controls="collapseExample<?php echo e($index); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-eye-fill" viewBox="0 0 16 16">
                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                    <path
                        d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
                </svg>
                Leer completo
            </button>
            <em><?php echo e(Carbon::parse($not->fecha_hora_enviada)->diffForHumans(['parts' => 2])); ?></em>
        </div>

        <div class="collapse" id="collapseExample<?php echo e($index); ?>">
            <div class="card card-body text-white bg-success ">
                <?php echo e($not->mensaje); ?>

            </div>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
<?php /**PATH C:\Users\TIC-HP\SSU\SIGHOV2_dev\resources\views/livewire/Administracion/notificaciones/notificacionesLista.blade.php ENDPATH**/ ?>