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
<div id="dashboardCarouselPlataforma" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <h3 class="carousel-header">PLATAFORMA</h3>
        
        <!-- Slide 1: Aportes por mes -->
        <div class="carousel-item active">
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="card-header">
                    <h3 class="card-title">CANTIDAD DE FICHAS REGISTRADAS EN LOS ULTIMOS 5 DÍAS</h3>
                </div>
                <div class="card-body">
                    <canvas id="agendas5UltDias" style="width: 100%; height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Slide 2: Top Instituciones -->

    </div>

    <!-- Controles del carrusel -->
    <button class="carousel-control-prev" type="button" data-bs-target="#dashboardCarouselPlataforma" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Anterior</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#dashboardCarouselPlataforma" data-bs-slide="next">
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
        const ctxPrestaciones = document.getElementById('agendas5UltDias');

        let dias = @json(array_keys($agendasUlt5Dias)); // Nombres de los dias
        let cantidades = @json(array_values($agendasUlt5Dias)); // Cantidad de prestaciones

        if (chartPrestMedicasMes) {
            chartPrestMedicasMes.destroy(); // Elimina el gráfico anterior antes de redibujar
        }

        chartPrestMedicasMes = new Chart(ctxPrestaciones, {
            type: 'bar',
            data: {
                labels: dias,
                datasets: [{
                    label: 'Fichas',
                    data: cantidades,
                    backgroundColor: 'rgba(40, 97, 161)',
                    borderColor: 'rgba(40, 97, 161, 1)',
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
    document.querySelector('#dashboardCarouselPlataforma').addEventListener('slid.bs.carousel', function (event) {
        let activeSlide = event.relatedTarget.querySelector('canvas');

        if (activeSlide) {
            if (activeSlide.id === 'agendas5UltDias') {
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

