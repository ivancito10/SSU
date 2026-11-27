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

<div id="dashboardCarouselAfiliacion" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-header">AFILIACION</div>
        <!-- Slide 1: Aportes por mes -->
        <div class="carousel-item active">
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="card-header">
                    <h3 class="card-title">Afiliación de Estudiantes en los Últimos 5 Años</h3>
                </div>
                <div class="card-body">
                    <canvas id="afiliacionAnualChart" style="width: 100%; height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Slide 2: Top Instituciones -->
        <div class="carousel-item">
            <div class="card" style="border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease-in-out;">
                <div class="card-header">
                    <h3 class="card-title">Distribución de Afiliados Vigentes</h3>
                </div>
                <div class="card-body" style="display: flex; justify-content: center; align-items: center;">
                    <canvas id="graficoAfiliadosChart" style="width: 100%; height: 300px;"></canvas>
                </div>

                <!-- 🔹 Caja Destacada para el Total de Afiliados -->
                <div style="background: #007bff; color: white; text-align: center; font-size: 1.2rem; font-weight: bold; padding: 10px 15px; border-radius: 8px; box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.2); position: absolute; bottom: 10px; right: 10px;">
                    <p style="margin: 0;">Total Afiliados</p>
                    <p style="font-size: 1.5rem; font-weight: bold; margin: 0;">{{$totalAfiliados}}</p>
                </div>
            </div>
        </div>

        <!-- Slide 3: Cantidad de afiliados por institucion -->
        <div class="carousel-item">
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="card-header">
                    <h3 class="card-title">Cantidad de afiliados por institucion</h3>
                </div>
                <div class="card-body">
                    <canvas id="graficoAfiliadosInstitucionChart" style="width: 100%; height: 300px;"></canvas>
                </div>
            </div>
        </div>

    </div>

    <!-- Controles del carrusel -->
    <button class="carousel-control-prev" type="button" data-bs-target="#dashboardCarouselAfiliacion" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Anterior</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#dashboardCarouselAfiliacion" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Siguiente</span>
    </button>

</div>
<!-- Bootstrap JS -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

    document.addEventListener('DOMContentLoaded', function () {
    let chartAfiliacion;
    let chartAfiliados;
    let chartAfiliadosPorInstitucion;

    function renderAfiliacionChart() {
        const ctxAnual = document.getElementById('afiliacionAnualChart');

        if (chartAfiliacion) {
            chartAfiliacion.destroy(); // Elimina el gráfico anterior antes de redibujar
        }

        chartAfiliacion = new Chart(ctxAnual, {
            type: 'bar',
            data: {
                labels: @json(array_keys($afiliadosPorAnio)),
                datasets: [{
                    label: 'Afiliados',
                    data: @json(array_values($afiliadosPorAnio)),
                    backgroundColor: 'rgba(54, 162, 235)',
                    borderColor: 'rgba(54, 162, 235, 1)',
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

    function renderAfiliadosChart() {
        const ctxAfiliados = document.getElementById('graficoAfiliadosChart');

        if (chartAfiliados) {
            chartAfiliados.destroy();
        }

        chartAfiliados = new Chart(ctxAfiliados, {
            type: 'pie',
            data: {
                labels: ['Titulares', 'Beneficiarios', 'Estudiantes'],
                datasets: [{
                    label: 'Cantidad de Afiliados',
                    data: [
                        @json($cantidadTitulares),
                        @json($cantidadBeneficiarios),
                        @json($cantidadEstudiantes)
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132)',
                        'rgba(54, 162, 235)',
                        'rgba(255, 206, 86)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }


        function renderAfiliadosInstitucionChart() {
        const ctxAfiliadosInstitucion = document.getElementById('graficoAfiliadosInstitucionChart');

        if (chartAfiliadosPorInstitucion) {
            chartAfiliadosPorInstitucion.destroy();
        }

        //  Extraer datos desde Laravel
        let instituciones = @json($afiliadosPorInstitucion->pluck('nombre_institucion'));
        let titulares = @json($afiliadosPorInstitucion->pluck('total_titulares'));
        let beneficiarios = @json($afiliadosPorInstitucion->pluck('total_beneficiarios'));

        // Crear gráfico de barras con 2 series de datos
        chartAfiliadosPorInstitucion = new Chart(ctxAfiliadosInstitucion, {
            type: 'bar',
            data: {
                labels: instituciones, // Instituciones en eje X
                datasets: [
                    {
                        label: 'Titulares',
                        data: titulares,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)', // Azul
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Beneficiarios',
                        data: beneficiarios,
                        backgroundColor: 'rgba(255, 99, 132, 0.7)', // Rojo
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
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
                    },
                    x: {
                        stacked: false // 🔹 Asegura que las barras estén una al lado de la otra
                    }
                }
            }
        });
    }

    // Renderizar cuando el slide cambia en el carrusel
    document.querySelector('#dashboardCarouselAfiliacion').addEventListener('slid.bs.carousel', function (event) {
        let activeSlide = event.relatedTarget.querySelector('canvas');

        if (activeSlide) {
            if (activeSlide.id === 'afiliacionAnualChart') {
                renderAfiliacionChart();
            } else if (activeSlide.id === 'graficoAfiliadosChart') {
                renderAfiliadosChart();
            }
            else if (activeSlide.id === 'graficoAfiliadosInstitucionChart') {
                renderAfiliadosInstitucionChart();
            }
        }
    });

    // Renderizar el primer gráfico cuando la página carga
    renderAfiliacionChart();
});

    
</script>


