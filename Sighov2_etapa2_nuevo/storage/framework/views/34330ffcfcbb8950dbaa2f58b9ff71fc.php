<?php $__env->startSection('meta'); ?>
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('vendor/adminlte/dist/img/sighov2.png')); ?>" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('title', 'SIGHO V2'); ?>
<?php $__env->startSection('content'); ?>


    <div class="card m-0 p-0">
        <div class="card-body">
            <div class="card-tools">

                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('administracion.notificaciones.notificaciones-detalles');

$__html = app('livewire')->mount($__name, $__params, 'lw-3162941004-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('administracion.cambio-contrasenia.cambio-contrasenia-inicio');

$__html = app('livewire')->mount($__name, $__params, 'lw-3162941004-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

            </div>

            <?php echo $__env->yieldContent('contenido'); ?>

        </div>
        <div class="card-footer" style="display: block;">
            <b>Versión</b> 2.1
        </div>
    </div>

    <?php echo $__env->make('numeros_emergencia', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content_top_nav_left'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('menu.consulta_externa')): ?>
        <li class="nav-item me-3">
            <a href="<?php echo e(route('atencion_medica.consulta_externa.index')); ?>" class="nav-link" title="CONSULTA EXTERNA">
                <!-- <i class="fas fa-stethoscope text-primary fa-2x"></i> -->
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="#0044A3" class="bi bi-calendar2-heart" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM1 3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v11a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm2 .5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5V4a.5.5 0 0 0-.5-.5zm5 4.493c1.664-1.711 5.825 1.283 0 5.132-5.825-3.85-1.664-6.843 0-5.132"/>
                    </svg>
            </a>
            
        </li>
    <?php endif; ?>
    

    <h4 class="card-title ml-2" style="margin-top:10px;">
         
        <b class="text-info d-none d-sm-inline">SEGURO SOCIAL UNIVERSITARIO</b>
        <b class="text-info d-none d-sm-none">SSU</b>
        <b class="text-danger d-none d-sm-inline">LA PAZ</b>
    </h4>
    <style>
        .card-title {
            font-size: 1.3rem;
        }

        @media (max-width: 576px) {
            .card-title {
                font-size: 0.5rem;
            }
        }

        @media (max-width: 768px) {
            .card-title {
                font-size: 1rem;
            }
        }
    </style>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content_top_nav_right'); ?>
    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/botonesStyles.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/formulariosStyles.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/powerGridStyles.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.worker.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toastTrigger = document.getElementById('liveToastBtn');
            var toastLiveExample = document.getElementById('liveToast');

            if (toastTrigger) {
                toastTrigger.addEventListener('click', function() {
                    var toast = new bootstrap.Toast(toastLiveExample);
                    toast.show();
                });
            }
        });
    </script>
    <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
    <script>
        window.OneSignalDeferred = window.OneSignalDeferred || [];
        OneSignalDeferred.push(async function(OneSignal) {
            await OneSignal.init({
                appId: "ed0394bd-c7ad-488d-a461-4125ccbb5411",
                //   appId: "dd52755f-15c1-4bac-9863-984174a3a0fc",
            });
        });
    </script>

    
    <?php echo \Illuminate\View\Factory::parentPlaceholder('js'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('botonAgendamientoWebPrincipal');
            if (btn) {
                btn.addEventListener('click', function(e) {
                    btn.classList.add('disabled');
                    btn.style.pointerEvents = 'none';
                    btn.style.opacity = '0.7';
                });
            }
        });
    </script>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>

    <div class="pull-right hidden-xs">
        <b class="text-danger">Derechos Reservados © <?php echo e(Carbon\Carbon::now()->year); ?></b><b class="text-primary"> SIGHO
            v2.</b>
        <!-- <b align="rigt">Versión</b> 1.2 -->
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\TIC-HP\SSU\Sighov2_etapa2_nuevo\resources\views/principal.blade.php ENDPATH**/ ?>