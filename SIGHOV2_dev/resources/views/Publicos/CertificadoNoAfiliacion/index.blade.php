<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Certificado de No Afiliación - SIGHO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="{{ asset('images/ObtenerUsuario.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('images/ObtenerUsuario.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('images/ObtenerUsuario.ico') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    @livewireStyles

    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, sans-serif;
            margin: auto;
            overflow: auto;
            background: linear-gradient(315deg,
                    rgb(5, 114, 90) 3%,
                    rgba(60, 132, 206, 1) 38%,
                    rgb(48, 64, 238) 68%,
                    rgb(30, 173, 209) 98%);
            animation: gradient 15s ease infinite;
            background-size: 400% 400%;
            background-attachment: fixed;
        }

        .logo {
            position: absolute;
            width: 230px;
            opacity: 0.4;
            right: 20px;
            top: 20px;
            z-index: -1;
        }

        .escudo {
            position: absolute;
            width: 220px;
            opacity: 0.2;
            left: -30px;
            top: 10px;
            z-index: -1;
        }

        .imagessu {
            position: absolute;
            width: 500px;
            opacity: 0.4;
            right: 0;
            bottom: 0;
            z-index: -1;
        }

        .contenido {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 20px;
            min-height: 100vh;
            color: white;
        }

        .card-container {
            width: 100%;
            max-width: 800px;
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .card {
            background-color: hsla(229, 28%, 37%, 0.7);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(5px);
        }

        .card-header {
            background-color: rgba(0, 0, 0, 0.2);
            padding: 15px;
            border-radius: 10px 10px 0 0;
            margin: -30px -30px 20px -30px;
            text-align: center;
        }

        .logo-header {
            max-height: 55px;
            margin: 10px auto 0;
            display: block;
        }

        .form-container {
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: .4rem;
        }

        .form-label {
            font-weight: 600;
            font-size: 1rem;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        .form-input {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 1rem;
            width: 100%;
        }

        .form-error {
            color: #ff6b6b;
            font-size: .85rem;
        }

        .btn-verificar {
            padding: 15px;
            border-radius: 10px;
            border: none;
            background: linear-gradient(135deg, #1e88e5, #0d47a1);
            color: white;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            font-size: 1.1rem;
            transition: all .3s;
        }

        .btn-verificar:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 20px rgba(0, 0, 0, .3);
        }

        .result-card {
            display: none;
        }

        .show-result .result-card {
            display: block;
            animation: fadeIn .5s ease;
        }

        @keyframes gradient {
            0% {
                background-position: 0% 0%;
            }

            50% {
                background-position: 100% 100%;
            }

            100% {
                background-position: 0% 0%;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <img src="{{ asset('images/edificio_png_2024.png') }}" class="imagessu">
    <img src="{{ asset('images/carnets/titular/ssu3.png') }}" class="escudo">
    <img src="{{ asset('images/logoSSUHDCarnet.png') }}" class="logo">

    <div class="contenido">
        <div class="card-container">

            <!-- FORMULARIO -->
            <div class="card">
                <div class="card-header">
                    <h1>Certificado de No Afiliación</h1>
                    <img src="{{ asset('images/LOGO_SIGHOV2.png') }}" class="logo-header">
                </div>

                @livewire('publicos.certificados.certificado-no-afiliacion')
            </div>



        </div>
    </div>

    @livewireScripts

    {{-- reCAPTCHA v3 --}}
    <script src="https://www.google.com/recaptcha/api.js?render=6LdabRQrAAAAAAmL58eVgucILPj77fV81_9O-BB6"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("livewire:init", function() {
            function getRecaptchaToken() {
                grecaptcha.ready(function() {
                    grecaptcha.execute("{{ config('services.recaptcha.site_key') }}", {
                        action: "verificar"
                    }).then(function(token) {
                        Livewire.dispatch('setRecaptcha', {
                            token: token
                        });
                    });
                });
            }

            // Obtener token inicial
            getRecaptchaToken();

            // Escuchar evento para resetear el reCAPTCHA
            Livewire.on('resetRecaptcha', () => {
                getRecaptchaToken();
            });

            // Escuchar cuando la verificación es exitosa
            Livewire.on('verificacionExitosa', (data) => {
                document.querySelector('.card-container').classList.add('show-result');
                document.getElementById('result-content').innerHTML = `
                    <div style="text-align: center; margin: 20px 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="#4caf50" style="margin-bottom: 15px;">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                        <h3 style="color: #4caf50;">Verificación Exitosa</h3>
                        <p>Usuario válido encontrado en el sistema</p>
                        <div style="background: rgba(0,0,0,0.2); padding: 15px; border-radius: 8px; margin-top: 20px;">
                            <p><strong>Nombre:</strong> ${data.nombre}</p>
                            <p><strong>CI:</strong> ${data.ci}</p>
                            <p><strong>Matrícula:</strong> ${data.matricula}</p>
                        </div>
                    </div>
                `;
            });


        });
    </script>
    <script>
        window.addEventListener('reload-page', function() {
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "La página expiró por inactividad",
                showConfirmButton: false,
                timer: 5500
            });

            setTimeout(function() {
                location.reload();
            }, 5500); // Espera lo mismo que el tiempo del SweetAlert
        });
    </script>
</body>

</html>
