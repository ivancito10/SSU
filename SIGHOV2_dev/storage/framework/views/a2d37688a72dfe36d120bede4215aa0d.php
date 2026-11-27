<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>INGRESO AL SISTEMA SIGHO V2</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" href="<?php echo e(asset('logo/icono_sighov2.png')); ?>" type="image/png">
    <link rel="stylesheet" href="<?php echo e(asset('css/login.css')); ?>">
</head>

<body class="d-flex justify-content-center align-items-center vh-100">

    <!-- Ondas -->
    <div class="wave"></div>
    <div class="wave"></div>
    <div class="wave"></div>

    <div class="container-split">
        <!-- Lado Izquierdo: Comunicados -->
        <div class="left-panel position-relative overflow-hidden">
            <!-- Fondo de imágenes con fade -->
            <div id="fondoComunicados" class="position-absolute w-100 h-100">
                <?php $__currentLoopData = $fotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $foto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <img class="fondo-item <?php echo e($loop->first ? 'active' : ''); ?>" alt="Imagen FTP" src="<?php echo e($foto); ?>">
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Contenido de comunicados -->
            <div class="contenido-comunicados p-3 text-white">
                <div id="carouselComunicados" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <h1 style="color: #ffffff; text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">
                            PANEL INFORMATIVO
                        </h1>
                        <div class="carousel-item active">
                            <p class="fs-5">
                                Bienvenido al SIGHO V2: el nuevo sistema digital del SSU, pensado para brindar una
                                experiencia más ágil, segura y moderna para nuestros asegurados.
                            </p>
                        </div>
                        <?php $__currentLoopData = $comunicados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comunicado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="carousel-item">
                                <p class="fs-5">
                                    <?php echo $comunicado->mensaje; ?>

                                </p>
                                <div class="text-end fst-italic mt-3 small text-white-50 ">
                                    <strong><?php echo e($comunicado->nombre_unidad); ?></strong> —
                                    <?php echo e(\Carbon\Carbon::parse($comunicado->fecha_inicio)->format('d/m/Y')); ?>

                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="carousel-indicators position-static mt-2">
                        <?php $__currentLoopData = $comunicados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $comunicado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button type="button" data-bs-target="#carouselComunicados"
                                data-bs-slide-to="<?php echo e($index); ?>" class="<?php echo e($index === 0 ? 'active' : ''); ?>"
                                <?php if($index === 0): ?> aria-current="true" <?php endif; ?>
                                aria-label="Comunicado <?php echo e($index + 1); ?>">
                            </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="d-flex justify-content-center gap-2 mt-2">
                        <button class="btn btn-outline-light btn-sm" type="button"
                            data-bs-target="#carouselComunicados" data-bs-slide="prev">
                            <i class="bi bi-arrow-left-circle"></i> Anterior
                        </button>
                        <button class="btn btn-outline-light btn-sm" type="button"
                            data-bs-target="#carouselComunicados" data-bs-slide="next">
                            Siguiente <i class="bi bi-arrow-right-circle"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lado Derecho: Login -->
        <div class="right-panel">
            <img src="/images/LOGO_SIGHOV2.png" alt="Logo SIGHO" class="logo-card">
            <div class="login-container">
                <!-- Marca de agua dentro del login -->
                <img src="/logo/ssu.png" alt="Logo Institucional" class="logo-marca-agua">

                <div class="titulo-principal">Ingreso al Sistema</div>
                <form method="POST" action="<?php echo e(route('login')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                        <input type="text" id="email" name="email" class="form-control"
                            placeholder="Ingrese su Usuario" value="<?php echo e(old('email')); ?>" required autofocus>
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                            <i class="bi bi-lock-fill"></i>
                        </span>
                        <input type="password" id="password" name="password" class="form-control"
                            placeholder="Ingrese su contraseña..." required>
                    </div>

                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger mt-2"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger mt-2 "><b><?php echo e($message); ?></b></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" name="remember" type="checkbox" id="remember">
                            <label class="form-check-label text-white" for="remember">Recuérdame</label>
                        </div>
                        <a href="#" class="text-decoration-none text-dark fw-semibold fst-italic"
                            style="font-size: 0.9rem" data-bs-toggle="modal"
                            data-bs-target="#forgotPasswordModal">¿Olvidaste tu contraseña?</a>
                    </div>

                    <button type="submit" class="btn btn-login w-100 mb-2">Ingresar</button>
                    <div class="text-center mb-2">
                        <small class="text-white fst-italic">Comprometidos con la salud y bienestar de nuestros
                            asegurados.</small>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Recuperar Contraseña -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow">
                <div class="modal-header bg-light border-0">
                    <h5 class="modal-title text-primary" id="forgotPasswordModalLabel">
                        <i class="bi bi-shield-lock-fill me-2"></i> Recuperar Contraseña
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body" style="font-size: 1.1rem;">
                    <p class="mb-3">Para recuperar su contraseña, debe apersonarse a la Unidad de Plataforma de Atención al Cliente o la unidad de Vigencia de Derechos:</p>
                    <ul class="list-unstyled">
                        <li><strong>Hospital del Seguro Universitario Av. 6 de Agosto Planta Baja</strong> </li>
                    </ul>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                        <i class="bi bi-check2-circle me-1"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Fondo dinámico -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const fondos = document.querySelectorAll('#fondoComunicados .fondo-item');
            if (fondos.length <= 1) return;

            let i = 0;
            setInterval(() => {
                fondos[i].classList.remove('active');
                i = (i + 1) % fondos.length;
                fondos[i].classList.add('active');
            }, 5000);
        });
    </script>

    <!-- Mostrar/Ocultar contraseña -->
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bi-lock-fill');
                icon.classList.add('bi-unlock-fill');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bi-unlock-fill');
                icon.classList.add('bi-lock-fill');
            }
        });
    </script>

</body>
</html>
<?php /**PATH C:\Users\TIC-HP\SSU\SIGHOV2_dev\resources\views/auth/login.blade.php ENDPATH**/ ?>