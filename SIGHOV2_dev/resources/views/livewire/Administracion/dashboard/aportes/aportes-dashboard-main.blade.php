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

<div id="dashboardCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-header">APORTES</div> 
        <!-- Slide 1: Aportes por mes -->
        <div class="carousel-item active">
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="card-header">
                    <h3 class="card-title">Total de Aportes Recibidos por Mes</h3>
                </div>
                <div class="card-body">
                    <canvas id="aportesPorMesChart" style="width: 100%; height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Slide 2: Top Instituciones -->
        <div class="carousel-item">
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="card-header">
                    <h3 class="card-title">Top 10 Instituciones por Aportes</h3>
                </div>
                <div class="card-body">
                    <canvas id="topInstitucionesChart" style="width: 100%; height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Slide 3: Cantidad de aportantes por institucion -->
        <div class="carousel-item">
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="card-header">
                    <h3 class="card-title">Cantidad de Aportantes por Institución en el mes de {{$mesAnteriorAportes}}</h3>
                </div>
                <div class="card-body">
                    <canvas id="cantidadAportantesPorInstituciones" style="width: 100%; height: 300px;"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Slide 4. Top aportantes voluntarios-->
        <div class="carousel-item">
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="card-header">
                    <h3 class="card-title">Top aportantes voluntarios</h3>
                </div>
                <div class="card-body">
                    <canvas id="topAportantesVol" style="width: 100%; height: 300px;"></canvas>
                </div>
            </div>
        </div>

    </div>

    <!-- Controles del carrusel -->
    <button class="carousel-control-prev" type="button" data-bs-target="#dashboardCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Anterior</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#dashboardCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Siguiente</span>
    </button>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    let chart11;
    let chart12;
    let chart13;
    let chart14;

    function renderAportesChart(){

        const ctxAportes = document.getElementById('aportesPorMesChart');
        const aportesData = {
            labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            datasets: [{
                label: 'Total de Aportes',
                data: @json(array_values($aportesPorMes)),
                fill: false,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192)',
                borderWidth: 2,
                tension: 0.1
            }]
        };
        if (chart11) {
            chart11.destroy(); // Elimina el gráfico anterior antes de redibujar
        }
            chart11 = new Chart(ctxAportes, {
            type: 'line',
            data: aportesData,
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
    //grafica para Instituciones:
    function renderInstitucionChart(){
        const ctxInstitucion = document.getElementById('topInstitucionesChart');

        const instituciones = @json($instituciones);
        const aportes = @json($aportes);

        if (chart12) {
            chart12.destroy(); // Elimina el gráfico anterior antes de redibujar
        }

        chart12 = new Chart(ctxInstitucion, {
            type: 'bar',
            data: {
                labels: instituciones,
                datasets: [{
                    label: 'Aportes',
                    data: aportes,
                    backgroundColor: 'rgba(153, 102, 255)',
                    //backgroundColor: 'rgba(153, 102, 255, 0.5)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                animation: {
                    delay: 500
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
    //grafica aportantes por institucion
    function renderAportantesChart(){
        const ctxAportantes = document.getElementById('cantidadAportantesPorInstituciones');
        const institucionAportantes = @json($institucionAportantes);
        const cantidadAportantes = @json($cantidadAportantes);

        if (chart13) {
            chart13.destroy(); // Elimina el gráfico anterior antes de redibujar
        }

        chart13 = new Chart(ctxAportantes, {
            type: 'bar',
            data: {
                labels: institucionAportantes,
                datasets: [{
                    label: 'Aportes',
                    data: cantidadAportantes,
                    backgroundColor: 'rgba(100, 100, 255)',
                    //backgroundColor: 'rgba(153, 102, 255, 0.5)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                animation: {
                    delay: 500
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    function renderTopAportantesChart(){
        const ctxTopAportantes = document.getElementById('topAportantesVol');
        const nombresAportantes = @json($topPersonasAportantes->keys());
        const montoAportantes = @json($topPersonasAportantes->values());

        if (chart14) {
            chart14.destroy(); // Elimina el gráfico anterior antes de redibujar
        }

        chart14 = new Chart(ctxTopAportantes, {
            type: 'bar',
            data: {
                labels: nombresAportantes,
                datasets: [{
                    label: 'Aportes',
                    data: montoAportantes,
                    backgroundColor: 'rgba(240, 230, 140)',
                    //backgroundColor: 'rgba(153, 102, 255, 0.5)',
                    borderColor: 'rgba(240, 230, 140, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                animation: {
                    delay: 500
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    // Renderizar cuando el slide cambia en el carrusel
    document.querySelector('#dashboardCarousel').addEventListener('slid.bs.carousel', function (event) {
        let activeSlide = event.relatedTarget.querySelector('canvas');

        if (activeSlide) {
            if (activeSlide.id === 'aportesPorMesChart') {
                renderAportesChart();
            } else if (activeSlide.id === 'topInstitucionesChart') {
                renderInstitucionChart();
            }
            else if (activeSlide.id === 'cantidadAportantesPorInstituciones') {
                renderAportantesChart();
            }
            else if (activeSlide.id === 'topAportantesVol') {
                renderTopAportantesChart();
            }
        }
    });

    // Renderizar el primer gráfico cuando la página carga
    renderAportesChart();
});
</script>