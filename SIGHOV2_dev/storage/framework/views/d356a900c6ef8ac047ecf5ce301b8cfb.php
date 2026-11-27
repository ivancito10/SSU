<link rel="icon" type="image/x-icon" href="<?php echo e(asset('vendor/adminlte/dist/img/sighov2.png')); ?>" />

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

$__html = app('livewire')->mount($__name, $__params, 'lw-364665003-0', $__slots ?? [], get_defined_vars());

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

$__html = app('livewire')->mount($__name, $__params, 'lw-364665003-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

                <!-- Para bloquear la pantalla si la UFV no esta actualizada del dia -->
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('registro.ufv')): ?>
                    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('aportes.ufv.alertaufv.alerta-ufv-main');

$__html = app('livewire')->mount($__name, $__params, 'lw-364665003-2', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                <?php endif; ?>
                <!-- aca el rol y dentr del rol el modal -->

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
    <li class="nav-item me-3">
        <a href="#" class="nav-link" title="Números de emergencia" data-toggle="modal" data-target="#modalEmergencia">
            <i class="fas fa-phone-volume text-danger fa-2x"></i>
        </a>
    </li>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('menu.agenda_web')): ?>
        <li class="nav-item me-3">
            <a id="botonAgendamientoWebPrincipal" href="<?php echo e(route('plataforma.agenda-web.index')); ?>"
                class="btn btn-primary d-flex align-items-center px-3 py-2 shadow-lg rounded-pill fw-bold animate__animated animate__pulse animate__infinite"
                title="Agendamiento Web">
                <span>Agendamiento Web</span>
            </a>
        </li>
    <?php endif; ?>

    <h3 class="card-title ml-2 " style="margin-top:10px;">
        <b class="text-info d-none d-sm-inline">SEGURO SOCIAL UNIVERSITARIO</b>
        <b class="text-info d-none d-sm-none">SSU</b>
        <b class="text-danger d-none d-sm-inline">LA PAZ</b>
    </h3>
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

    <script src="<?php echo e(asset('js/bloqueos.js')); ?>"></script>
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

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\TIC-HP\SSU\SIGHOV2_dev\resources\views/principal.blade.php ENDPATH**/ ?>