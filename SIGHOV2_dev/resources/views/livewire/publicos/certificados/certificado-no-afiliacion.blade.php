<div class="form-container">
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

    <div class="form-group">
        <label class="form-label">Apellido Paterno</label>
        <input type="text" class="form-input" wire:model.defer="apellido_paterno" placeholder="APELLIDO PATERNO"
            oninput="this.value = this.value.toUpperCase()">
        @error('apellido_paterno')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label">Apellido Materno</label>
        <input type="text" class="form-input" wire:model.defer="apellido_materno" placeholder="APELLIDO MATERNO"
            oninput="this.value = this.value.toUpperCase()">
        @error('apellido_materno')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label">Nombres <b style="color: red">*</b></label>
        <input type="text" class="form-input" wire:model.defer="nombres" placeholder="NOMBRES"
            oninput="this.value = this.value.toUpperCase()">
        @error('nombres')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label">Número de Identificación (Solo número Ej. 12345678) <b style="color: rgb(237, 42, 42);">*</b></label>
        <input type="number" class="form-input" wire:model.defer="ci" placeholder="CÉDULA DE IDENTIDAD">
        @error('ci')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>



    <div class="form-group">
        <label class="form-label">Fecha de Nacimiento <b style="color: red">*</b></label>
        <input type="date" class="form-input" wire:model.defer="fecha_nacimiento">
        @error('fecha_nacimiento')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>
    <!-- Campo oculto para el token de reCAPTCHA -->
    <input type="hidden" wire:model="recaptchaToken">

    <button class="btn-verificar" wire:click="verificar">
        Solicitar Certificado
    </button>

</div>
<script>
    window.addEventListener('verificacionExitosa', event => {

        Swal.fire({
            position: "top-start",
            icon: "success",
            title: "⬇️ Su certificado se generó correctamente y se descargó en su dispositivo.",
            showConfirmButton: false,
            timer: 8500
        });

    });

        window.addEventListener('personaAsegurada', event => {

        Swal.fire({
            position: "top-start",
            icon: "error",
            title: "Usted se Ecnuentra Afiliado(a), No puede Obtener el Certificado.",
            showConfirmButton: false,
            timer: 8500
        });

    });
</script>
