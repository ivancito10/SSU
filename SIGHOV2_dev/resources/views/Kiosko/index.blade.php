<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        /**basicos */
        .titulos-datos {
            color: white;
        }

        .label-titulos {
            color: white;
        }

        .card-vista-comun {
            border-radius: 20px;
            height: 100%;
            box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: hsla(223, 5.40%, 25.30%, 0.50);
            padding: 8px;
        }

        .card-vista-comun-temp {
            border-radius: 20px;
            height: auto;
            box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: hsla(223, 5.40%, 25.30%, 0.50);
            padding: 8px;
        }

        .input-texto-vigencia {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #e5e8e8;
            border-color: white;
            font-size: 20px;
            font-weight: 500;
            border-radius: 8px
        }

        .input-texto {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #e5e8e8;
            border-color: white;
            font-size: 18px;
            font-weight: 500;
            border-radius: 8px
        }

        /**para hora y fecha */
        .hora-digital-stilos {
            font-size: 3rem;
            color: white;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            padding: 5px 20px;
            border-radius: 10px;
            text-align: center;
            margin-top: -10px;
            font-weight: 700;
        }

        /*transicion de texto*/
        .transition-text {
            transition: transform 0.8s ease, margin-top 0.8s ease;
        }

        .posicion-inicial {
            margin-top: 180px;
            transform: translateY(0);
        }

        .posicion-final {
            margin-top: 95px;
            transform: translateY(-40px);
        }

        .texto-h1 {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-weight: 800;
            font-size: 70px;
            color: white;
            margin-bottom: 0;
            line-height: 1.1;
        }

        .texto-subtitle {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: 60px;
            font-weight: 700;
            color: white;
            margin-top: 0;
            margin-bottom: 0;
            line-height: 1;
        }

        /**Estilos de la pagina en genral */
        body {
            font-family: -apple-system, BlinkMacSystemFont, sans-serif;
            margin: auto;
            overflow: auto;
            background: linear-gradient(315deg,
                    rgb(3, 16, 134) 3%,
                    rgba(60, 132, 206, 1) 38%,
                    rgb(48, 64, 238) 68%,
                    rgb(73, 176, 228) 98%);
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

        .sighov2 {
            top: 0;
            width: 300px;
            height: 280px;
            z-index: -1;
            /* height: calc(100% - 00px); */
            /* object-fit: cover; Asegura que la imagen cubra el área disponible */
            /* object-position: center; */
            /* background-color: tomato; */
            margin-bottom: -70px;
        }

        .escudo {
            position: absolute;
            width: 220px;
            opacity: 0.2;
            left: -30px;
            top: 10px;
            z-index: -1;
        }

        .contenido {
            display: flex;
            /* justify-content: center;*/
            align-items: center;
            text-align: center;
            flex-direction: column;
            margin: auto;
            height: 90vh;
        }

        .imagessu {
            position: absolute;
            width: 500px;
            opacity: 0.4;
            right: 0;
            bottom: 0;
            z-index: -1;
        }

        @media (max-width: 600px) {
            .escudo {
                width: 200px;
            }

            .container {
                padding: 20px;
            }

            .imagessu {
                width: 300px;
            }

            .logo {
                position: absolute;
                width: 120px;
                bottom: 0;
                top: auto;
                left: 0;
                /* background-color: tomato; */
            }
        }

        @media (max-width: 300px) {
            .escudo {
                width: 150px;
            }

            .imagessu {
                width: 250px;
            }
        }

        .icono {
            width: 100px;
            height: 100px;
            color: #4bc435;
        }

        .icono2 {
            width: 100px;
            height: 100px;
            color: crimson;
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

        .wave {
            background: rgb(255 255 255 / 25%);
            border-radius: 1000% 1000% 0 0;
            position: fixed;
            width: 200%;
            height: 12em;
            animation: wave 10s -3s linear infinite;
            transform: translate3d(0, 0, 0);
            opacity: 0.8;
            bottom: 0;
            left: 0;
            z-index: -1;
        }

        .wave:nth-of-type(2) {
            bottom: -1.25em;
            animation: wave 18s linear reverse infinite;
            opacity: 0.8;
        }

        .wave:nth-of-type(3) {
            bottom: -2.5em;
            animation: wave 20s -1s reverse infinite;
            opacity: 0.9;
        }

        /* permite arrastrar (scroll) pero NO pinch-zoom ni double-tap zoom */
        html,
        body {
            touch-action: pan-x pan-y;
            /* sin pinch-zoom */
        }

        :root {
            --duracion-ciclo: 13s;
            /* 10s visibles + 3s fade+pausa */
        }

        /* 1️⃣ Logo con fade+zoom cíclico */
        .logo-animado {
            width: 700px;
            height: auto;
            display: inline-block;
            position: relative;
            z-index: 1;
            animation: fadeZoomLoop var(--duracion-ciclo) ease-in-out infinite;
        }

        @keyframes fadeZoomLoop {
            0% {
                opacity: 0;
                transform: scale(0.85);
            }

            10% {
                opacity: 1;
                transform: scale(1);
            }

            80% {
                opacity: 1;
                transform: scale(1);
            }

            95%,
            100% {
                opacity: 0;
                transform: scale(0.9);
            }
        }

        /* Halo sincronizado */
        .logo-halo-wrap {
            position: relative;
            display: inline-block;
            overflow: visible;
        }

        .logo-halo-wrap::before {
            content: "";
            position: absolute;
            inset: -6% -8%;
            z-index: 0;
            pointer-events: none;
            background: radial-gradient(circle at 50% 55%,
                    rgba(0, 220, 255, .35) 0%,
                    rgba(0, 170, 255, .18) 25%,
                    rgba(0, 120, 255, .08) 45%,
                    rgba(0, 0, 0, 0) 60%);
            transform: scale(.9);
            filter: blur(12px);
            animation: haloPulseLoop var(--duracion-ciclo) ease-in-out infinite;
        }

        @keyframes haloPulseLoop {

            0%,
            100% {
                opacity: 0;
                transform: scale(.9);
                filter: blur(10px);
            }

            10%,
            80% {
                opacity: 1;
                transform: scale(1);
                filter: blur(16px);
            }
        }

        /* 2️⃣ Texto sincronizado */
        .mensaje-animado {
            font-size: 2rem;
            font-weight: 600;
            color: #f0f8ff;
            text-shadow: 0 0 8px rgba(0, 200, 255, 0.3);
            letter-spacing: 0.8px;
            opacity: 0;
            animation: textFadeLoop var(--duracion-ciclo) ease-in-out infinite;
        }

        @keyframes textFadeLoop {
            0% {
                opacity: 0;
                transform: translateY(10px);
            }

            10% {
                opacity: 1;
                transform: translateY(0);
            }

            80% {
                opacity: 1;
                transform: translateY(0);
            }

            95%,
            100% {
                opacity: 0;
                transform: translateY(-10px);
            }
        }

        /* 3️⃣ Brillo respirante del texto */
        @keyframes glowPulse {

            0%,
            100% {
                text-shadow: 0 0 6px rgba(0, 200, 255, 0.2), 0 0 14px rgba(0, 180, 255, 0.25);
            }

            50% {
                text-shadow: 0 0 10px rgba(0, 220, 255, 0.4), 0 0 22px rgba(0, 200, 255, 0.4);
            }
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <title>KIOSCO</title>
</head>

<body oncontextmenu="return false;">
    <img src="{{ asset('images/edificio_png_2024.png') }}" alt="" class="imagessu" />
    <img class="escudo" src="{{ asset('images/carnets/titular/ssu3.png') }}" alt="" />
    <img class="logo" src="{{ asset('images/logoSSUHDCarnet.png') }}" alt="" />
    @livewire('kiosko.kiosko-main')
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
    integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
    integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // para que se recargue a cada rato
    //  setInterval(function() {
    //      location.reload();
    //  }, 180000); // 60000 ms = 1 minuto
</script>
<script>
    document.addEventListener('faltaDatosBusqueda', function() {
        Swal.fire({
            title: "Faltan datos",
            text: "Para realizar tu busqueda ingresa al menos tu carnet o tu matricula",
            icon: "warning",
            confirmButtonText: "Volver a intentar"
        });
    });
    document.addEventListener('noAfiliadoNoVigente', function() {
        Swal.fire({
            icon: "error",
            title: "No existen registros asociados",
            text: "El código escaneado no pertenece a un afiliado vigente",
        });
    });
</script>
<script>
    function updateHoraDigital() {
        const now = new Date();
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('clock-time').textContent = `${h}:${m}:${s}`;
    }
    setInterval(updateHoraDigital, 1000);
    updateHoraDigital();
</script>

</html>
