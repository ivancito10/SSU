<style>
    .carousel-item {
    min-height: 350px;
    }

    .carousel-item .card {
        height: 100%;
    }

    .carousel-item .card-body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 350px; /* Ajusta según necesidad */
    }

    .carousel-item canvas {
        width: 100% !important;
        height: 100% !important;
    }
    .carousel-header {
        text-align: center;
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 15px;
    }
</style>
<div id="dashboardCarouselVigencia" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <h3 class="carousel-header">VIGENCIA</h3>
        <!-- Slide 1: Aportes por mes -->
        <div class="carousel-item active">
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="card-header">
                    <h3 class="card-title">PRESTACIONES MEDICAS POR MES</h3>
                </div>
                <div class="card-body">
                    <canvas id="prestMedicasMesChart" style="width: 100%; height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Slide 2: Top Instituciones -->

    </div>

    <!-- Controles del carrusel -->
    <button class="carousel-control-prev" type="button" data-bs-target="#dashboardCarouselVigencia" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Anterior</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#dashboardCarouselVigencia" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Siguiente</span>
    </button>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

    document.addEventListener('DOMContentLoaded', function () {
    let chartPrestMedicasMes;
    //let chartAfiliados;

    function renderPrestacionesChart() {
        const ctxPrestaciones = document.getElementById('prestMedicasMesChart');

        let meses = @json(array_keys($prestMedicasMes)); // Nombres de los meses
        let cantidades = @json(array_values($prestMedicasMes)); // Cantidad de prestaciones

        if (chartPrestMedicasMes) {
            chartPrestMedicasMes.destroy(); // Elimina el gráfico anterior antes de redibujar
        }

        chartPrestMedicasMes = new Chart(ctxPrestaciones, {
            type: 'bar',
            data: {
                labels: meses,
                datasets: [{
                    label: 'Afiliados',
                    data: cantidades,
                    backgroundColor: 'rgba(75, 192, 192)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    delay: 500
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Renderizar cuando el slide cambia en el carrusel
    document.querySelector('#dashboardCarouselVigencia').addEventListener('slid.bs.carousel', function (event) {
        let activeSlide = event.relatedTarget.querySelector('canvas');

        if (activeSlide) {
            if (activeSlide.id === 'prestMedicasMesChart') {
                renderPrestacionesChart();
            } 
            //else if (activeSlide.id === 'graficoAfiliadosChart') {
            //    renderAfiliadosChart();
            //}
        }
    });

    // Renderizar el primer gráfico cuando la página carga
    renderPrestacionesChart();
});

    
</script>
