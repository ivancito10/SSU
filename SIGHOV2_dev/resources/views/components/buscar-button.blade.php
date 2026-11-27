<style>
    .btn-buscar {
        position: relative;
        display: inline-flex;
        align-items: center;
        /*   gap: 5px; */
        width: 100%;
        color: #D6680B;
        border: 1px solid #D6680B;
        border-radius: 10px;
        cursor: pointer;
        overflow: hidden;
        transition: color 0.5s;
        background-color: white;
        padding: 5px 2px;
        transition: transform 0.2s, background-color 0.3s;
    }


    .btn-buscar svg {
        fill: #D6680B;

    }

    .btn-buscar .slot-content {
        color: #D6680B;
        font-weight: 600;
        font-size: 16px;
    }

    /* Efecto al hacer clic */
    .btn-buscar:active {
        transform: scale(0.95);
        background-color: #D6680B;
        color: white;
        /* Cambio ligero de color */
    }

    /* .btn-buscar::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 100%;
        width: 100%;
        background-color: #D6680B;
        z-index: 0;
        transition: left 0.5s;
    }
 */
    /*     .btn-buscar:hover {
        color: white;
    }

    .btn-buscar:hover::before {
        left: 0;
    }

    .btn-buscar svg {
        position: relative;
        z-index: 1;
        fill: #D6680B;
        transition: fill 0.5s;
    }

    .btn-buscar:hover svg {
        fill: white;
    }

    .btn-buscar .slot-content {
        position: relative;
        z-index: 1;
        transition: color 0.5s;
        color: #D6680B;
    }

    .btn-buscar:hover .slot-content {
        color: white;
    } */
</style>
<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-buscar']) }}" data-toggle="tooltip"
    data-placement="top" title="AGREGAR NUEVO">
    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-search"
        viewBox="0 0 16 16">
        <path
            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
    </svg>
    <span class="slot-content"><b>{{ $slot }}</b></span>
</button>
