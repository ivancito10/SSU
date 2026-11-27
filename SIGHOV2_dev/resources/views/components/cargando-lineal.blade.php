<div class="loader-inline-wrapper">
    <div class="container-dash">
        <div class="dash uno"></div>
        <div class="dash dos"></div>
        <div class="dash tres"></div>
        <div class="dash cuatro"></div>
    </div>
    <div class="cargando">{{ $slot }}</div>
</div>

<style>
    .loader-inline-wrapper {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 20px 0;
    }

    .container-dash {
        display: flex;
        align-items: center;
    }

    .cargando {
        margin-top: 1rem;
        font-size: 0.85rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #000000;
        font-weight: 800;
    }

    .dash {
        margin: 0 8px;
        width: 30px;
        height: 12px;
        border-radius: 8px;
        background: #2471a3;
        box-shadow: 0 0 8px 0 #e5f0f9;
    }

    /* Animaciones */
    .uno {
        transform-origin: center left;
        animation: spin 3s linear infinite;
    }

    .dos {
        transform-origin: center right;
        animation: spin2 3s linear infinite;
        animation-delay: .2s;
    }

    .tres {
        transform-origin: center right;
        animation: spin3 3s linear infinite;
        animation-delay: .3s;
    }

    .cuatro {
        transform-origin: center right;
        animation: spin4 3s linear infinite;
        animation-delay: .4s;
    }

    /* Keyframes (igual que los tuyos) */
    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        25% {
            transform: rotate(360deg);
        }

        30% {
            transform: rotate(370deg);
        }

        35% {
            transform: rotate(360deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    @keyframes spin2 {

        0%,
        20% {
            transform: rotate(0deg);
        }

        30% {
            transform: rotate(-180deg);
        }

        35% {
            transform: rotate(-190deg);
        }

        40%,
        78% {
            transform: rotate(-180deg);
        }

        95% {
            transform: rotate(-360deg);
        }

        100% {
            transform: rotate(-360deg);
        }
    }

    @keyframes spin3 {

        0%,
        27% {
            transform: rotate(0deg);
        }

        40% {
            transform: rotate(180deg);
        }

        50%,
        62% {
            transform: rotate(180deg);
        }

        75% {
            transform: rotate(360deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    @keyframes spin4 {

        0%,
        38% {
            transform: rotate(0deg);
        }

        60% {
            transform: rotate(-360deg);
        }

        75%,
        100% {
            transform: rotate(-360deg);
        }
    }
</style>
