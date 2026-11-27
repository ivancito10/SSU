<div class="loader-caminando-wrapper">
    <div class="optical-runner"></div>
</div>

<style>
    .loader-caminando-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 30px 0;
    }

    /* Contenedor */
    .optical-runner {
        width: 200px;
        aspect-ratio: 1;
        background-image: url("{{ asset('edificio_png_2024.png') }}");
        background-size: cover;
        background-repeat: no-repeat;

        --stripe-px: 6px;
        --x: 0%;

        position: relative;
        overflow: hidden;
    }

    /* Líneas que generan la ilusión de movimiento */
    .optical-runner::after {
        content: '';
        position: absolute;
        inset: 0;
        background-image: linear-gradient(90deg,
                #f8f4ee 0 80%,
                transparent 0 100%);
        background-size: var(--stripe-px) 100%;
        background-repeat: repeat;
        animation: moverLineas 1s linear infinite;
    }

    /* Piso */
    .optical-runner::before {
        content: '';
        position: absolute;
        inset: auto -50px -1px;
        height: 2px;
        background: radial-gradient(rgb(53 83 14), transparent);
    }

    /* Animación automática */
    @keyframes moverLineas {
        from {
            transform: translateX(0);
        }

        to {
            transform: translateX(100%);
        }
    }
</style>
