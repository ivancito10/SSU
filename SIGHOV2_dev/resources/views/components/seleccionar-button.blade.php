<style>
    .btn-seleccionarButton {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        background-color: white;
        border: 1px solid #D6680B;
        border-color: #D6680B;
        border-radius: 10px;
        padding: 8px 12px;
        transition: transform 0.2s, background-color 0.3s;
        
    }

    .btn-seleccionarButton svg {
        fill: #D6680B;
    }

    .btn-seleccionarButton .slot-content {
        color: #D6680B;
        font-weight: 600;
        font-size: 16px;
    }

    /* Efecto al hacer clic */
    .btn-seleccionarButton:active {
        transform: scale(0.95);
        background-color: #D6680B;
        color: white;
        /* Cambio ligero de color */
    }
</style>


<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-seleccionarButton']) }}>
    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-check-circle-fill"
        viewBox="0 0 16 16">
        <path
            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
    </svg>
    <span class="slot-content">{{ $slot }}</span>
</button>
