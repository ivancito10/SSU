<div class="card-borde p-3 mb-4 shadow-sm">
    {{-- Fila de Encabezado: Título y Estados --}}
    <div class="row align-items-center mb-3">
        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="card cabecera bg-light p-2">
                <b class="text-primary"><i class="fas fa-pills mr-2"></i> DATOS DEL MEDICAMENTO EN RECETA</b>
            </div>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-6 col-6 text-right">
            <strong>ENTREGA: </strong>
            <span class="badge badge-success p-2" style="border-radius:10px">
                <b>{{ $detalle->fecha_entrega }}</b>
            </span>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-6 col-6 text-left">
            <strong>FIN TRATAMIENTO: </strong>
            <span class="badge badge-danger p-2" style="border-radius:10px">
                <b>{{ $detalle->fecha_fin_tratamiento }}</b>
            </span>
        </div>
    </div>

    {{-- Información Principal del Medicamento --}}
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-2">
            <strong>
                <i class="fas fa-capsules text-secondary mr-1"></i> MEDICAMENTO:
            </strong>
            <p class="p-comun-disabled p-1">
                {{ $detalle->medicamento }}
            </p>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-2">
            <strong>
                <i class="fas fa-vial text-secondary mr-1"></i> FORMA FARMACÉUTICA:
            </strong>
            <p class="p-comun-disabled p-1">
                {{ $detalle->forma_famacetica }}
            </p>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-2">
            <strong>
                <i class="fas fa-microscope text-secondary mr-1"></i> CONCENTRACIÓN:
            </strong>
            <p class="p-comun-disabled p-1">
                {{ $detalle->concentracion }}
            </p>
        </div>

        <div class="col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
            <strong>
                <i class="fas fa-sort-numeric-up text-secondary mr-1"></i> CANTIDAD RECETADA:
            </strong>
            <p class="p-comun-disabled p-1 font-weight-bold text-primary">
                {{ $detalle->cantidad }} unidades
            </p>
        </div>
    </div>

    {{-- Sección de Instrucciones (Indicaciones y Recomendaciones) --}}
    <div class="row mt-2">
        <div class="col-lg-6 col-md-12">
            <div class="p-3 card mb-2 h-100 border-warning" style="background-color: #fffdf0;">
                <h6 class="mb-2 text-warning font-weight-bold border-bottom pb-1 uppercase">
                    <i class="fas fa-hand-holding-medical mr-1"></i> INDICACIONES DE USO
                </h6>
                <p class="mb-0 text-dark small">
                    {{ $detalle->indicaciones }}
                </p>
            </div>
        </div>

        <div class="col-lg-6 col-md-12">
            <div class="p-3 card mb-2 h-100 border-info" style="background-color: #f0f7ff;">
                <h6 class="mb-2 text-info font-weight-bold border-bottom pb-1 uppercase">
                    <i class="fas fa-info-circle mr-1"></i> RECOMENDACIONES
                </h6>
                <p class="mb-0 text-dark small">
                    {{ $detalle->recomendacion }}
                </p>
            </div>
        </div>
    </div>

    {{-- Footer de la tarjeta: Vía de administración --}}
    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="p-2 border rounded bg-secondary text-white text-center">
                <strong>VÍA DE ADMINISTRACIÓN:</strong> {{ $detalle->forma_administracion }}
            </div>
        </div>
    </div>
    <style>
        /* Estilos basados en tu ejemplo para mantener consistencia */
        .card-borde {
            border: 1px solid #d1d1d1;
            border-radius: 8px;
            background-color: white;
        }

        .cabecera {
            border-left: 5px solid #2471a3;
            font-size: 0.9rem;
        }

        .p-comun-disabled {
            color: #4a4a4a;
            margin-bottom: 0;
            font-size: 0.95rem;
        }
    </style>
</div>