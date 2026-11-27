<div>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @error('recaptcha')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="mb-3">
        <div class="form-container">
            <!-- Grupo Matrícula -->
            <div class="form-group">
                <label for="matricula" class="form-label">Matrícula del Seguro: <strong
                        style="color: red;">*</strong></label>
                <input type="text" class="form-input" wire:model="matricula" maxlength="9" minlength="9"
                    pattern="\d{9}" required style="text-transform: uppercase;" placeholder="Ej:. 001122ABC">
                @error('matricula')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Grupo CI Dividido -->
            <div class="form-group">
                <label class="form-label">Número de CI: <strong style="color: red;">*</strong></label>
                <div class="ci-container">
                    <div class="ci-number-group">
                        <input type="number" class="form-input ci-number" wire:model="ci" minlength="3" maxlength="15"
                            pattern="\d+" required placeholder="Ej:. 12345678">
                        @error('ci')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Complemento CI: </label>
                <div class="ci-container">

                    <div class="ci-complemento-group">

                        <input type="text" class="form-input ci-complemento" wire:model="ci_complemento"
                            placeholder="Ej: 1A" maxlength="2" style="text-transform: uppercase;">
                        @error('ci_complemento')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <!-- Grupo Fecha de Nacimiento -->
            <div class="form-group">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento: <strong
                        style="color: red;">*</strong></label>
                <input type="date" class="form-input" wire:model="fecha_nacimiento" max="{{ date('Y-m-d') }}"
                    required>
                @error('fecha_nacimiento')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <!-- Campo oculto para el token de reCAPTCHA -->
    <input type="hidden" wire:model="recaptchaToken">

    <button class="btn-verificar" wire:click="verificar">
        Verificar y Obtener Usuario
        <span style="margin-left: 8px;">→</span>
    </button>
</div>
</div>

<script>
    window.addEventListener('verificacionExitosa', event => {

        Swal.fire({
            position: "top-end",
            icon: "success",
            title: "⬇️ Su usuario y contraseña se generó correctamente y se descargó en su equipo.",
            showConfirmButton: false,
            timer: 8500
        });

    });

    window.addEventListener('UsuarioYaGenerado', event => {

        Swal.fire({
            position: "top-end",
            icon: "warning",
            title: "El usuario y contraseña ya fue generado anteriormente!",
            showConfirmButton: false,
            timer: 5500
        });
    });

    window.addEventListener('personaNoExistente', event => {

        Swal.fire({
            position: "top-end",
            icon: "error",
            title: "Los Datos ingresados no corresponden a un asegurado vigente y activo en nuestro Seguro!",
            showConfirmButton: false,
            timer: 5500
        });
    });
</script>
