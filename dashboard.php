<?php


/* =========================================================
   DATOS DE LA HUERTA
   ========================================================= */


$cultivos = [
    "Tomate" => [
        "cantidad" => 45,
        "cosecha" => 32,
        "estado" => "Bueno"
    ],
    "Lechuga" => [
        "cantidad" => 60,
        "cosecha" => 18,
        "estado" => "Excelente"
    ],
    "Zanahoria" => [
        "cantidad" => 80,
        "cosecha" => 25,
        "estado" => "Bueno"
    ],
    "Frutilla" => [
        "cantidad" => 35,
        "cosecha" => 12,
        "estado" => "Regular"
    ]
];


/* =========================================================
   ESTADÍSTICAS
   ========================================================= */


$totalCultivos = count($cultivos);
$totalPlantas = 0;
$totalCosecha = 0;


foreach ($cultivos as $cultivo) {
    $totalPlantas += $cultivo["cantidad"];
    $totalCosecha += $cultivo["cosecha"];
}


/* Datos ambientales */
$riegoSemanal = 4250;
$temperatura = 24;
$humedad = 68;
$estadoHuerta = "Excelente";


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Eco-Huerta | ProA</title>
    
    <!-- Google Fonts & Chart.js -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <style>
        :root {
            --bg-main: #f0f4f1;
            --primary: #2e7d32;
            --primary-dark: #1b5e20;
            --primary-light: #e8f5e9;
            --accent: #4caf50;
            --text-main: #1c2d1f;
            --text-muted: #607363;
            --card-bg: rgba(255, 255, 255, 0.85);
            --border-color: #d2e3d4;
            --radius-lg: 20px;
            --radius-md: 12px;
            --shadow-sm: 0 4px 12px rgba(0,0,0,0.03);
            --shadow-md: 0 8px 24px rgba(46,125,50,0.08);
        }


        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }


        body {
            background-color: var(--bg-main);
            background-image: 
                radial-gradient(at 10% 10%, rgba(76, 175, 80, 0.08) 0px, transparent 50%),
                radial-gradient(at 90% 90%, rgba(46, 125, 50, 0.08) 0px, transparent 50%);
            color: var(--text-main);
            min-height: 100vh;
            padding: 30px 20px;
        }


        .dashboard-container {
            max-width: 1240px;
            margin: 0 auto;
        }


        /* Topbar / Header */
        .header {
            background: var(--card-bg);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 24px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            box-shadow: var(--shadow-sm);
        }


        .brand-info h1 {
            font-size: 26px;
            font-weight: 800;
            color: var(--primary-dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }


        .brand-info p {
            color: var(--text-muted);
            font-size: 14px;
            margin-top: 4px;
            font-weight: 500;
        }


        .btn-volver {
            text-decoration: none;
            background: var(--primary);
            color: white;
            padding: 12px 24px;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(46,125,50,0.2);
        }


        .btn-volver:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }


        /* Metric Grid */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }


        .metric-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            padding: 24px;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            position: relative;
            overflow: hidden;
        }


        .metric-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }


        .metric-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }


        .metric-icon {
            width: 48px;
            height: 48px;
            background: var(--primary-light);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }


        .metric-card h3 {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            font-weight: 700;
        }


        .metric-value {
            font-size: 32px;
            font-weight: 800;
            color: var(--primary-dark);
            line-height: 1;
            margin-bottom: 8px;
        }


        .metric-sub {
            font-size: 13px;
            color: var(--text-muted);
        }


        /* Content Layout */
        .charts-grid {
            display: grid;
            grid-template-columns: 3fr 2fr;
            gap: 24px;
            margin-bottom: 28px;
        }


        .panel {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 24px;
            box-shadow: var(--shadow-sm);
        }


        .panel-header {
            margin-bottom: 20px;
        }


        .panel-header h2 {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 10px;
        }


        .chart-wrapper {
            position: relative;
            height: 280px;
            width: 100%;
        }


        /* Table Styling */
        .table-section {
            margin-bottom: 28px;
        }


        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }


        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }


        th {
            background: #e1ebe3;
            color: var(--text-main);
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 14px 18px;
            text-align: left;
        }


        th:first-child { border-top-left-radius: var(--radius-md); border-bottom-left-radius: var(--radius-md); }
        th:last-child { border-top-right-radius: var(--radius-md); border-bottom-right-radius: var(--radius-md); }


        td {
            padding: 16px 18px;
            font-size: 14px;
            color: var(--text-main);
            border-bottom: 1px solid var(--border-color);
            font-weight: 500;
        }


        tr:last-child td {
            border-bottom: none;
        }


        /* Status Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }


        .badge.excelente {
            background: #d1e7dd;
            color: #0f5132;
        }


        .badge.bueno {
            background: #cff4fc;
            color: #055160;
        }


        .badge.regular {
            background: #fff3cd;
            color: #664d03;
        }


        /* Environment Grid */
        .env-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }


        .env-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            padding: 20px;
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: var(--shadow-sm);
        }


        .env-icon {
            font-size: 28px;
            width: 52px;
            height: 52px;
            background: rgba(76, 175, 80, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }


        .env-details h3 {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 600;
        }


        .env-details strong {
            font-size: 20px;
            color: var(--text-main);
            font-weight: 800;
        }


        /* Responsive Breakpoints */
        @media (max-width: 992px) {
            .charts-grid {
                grid-template-columns: 1fr;
            }
        }


        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }
            .env-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>


<div class="dashboard-container">


    <!-- ENCABEZADO -->
    <header class="header">
        <div class="brand-info">
            <h1>🌿 Eco-Huerta ProA</h1>
            <p>Monitoreo en tiempo real • Escuela Experimental ProA</p>
        </div>
        <a href="menu.html" class="btn-volver">
            <span>←</span> Volver al inicio
        </a>
    </header>


    <!-- METRICAS PRINCIPALES -->
    <section class="metrics-grid">
        <div class="metric-card">
            <div class="metric-header">
                <h3>Variedades</h3>
                <div class="metric-icon">🌱</div>
            </div>
            <div class="metric-value"><?php echo $totalCultivos; ?></div>
            <div class="metric-sub">Tipos de cultivos activos</div>
        </div>


        <div class="metric-card">
            <div class="metric-header">
                <h3>Plantas Totales</h3>
                <div class="metric-icon">🪴</div>
            </div>
            <div class="metric-value"><?php echo $totalPlantas; ?></div>
            <div class="metric-sub">Sembradas actualmente</div>
        </div>


        <div class="metric-card">
            <div class="metric-header">
                <h3>Cosecha Total</h3>
                <div class="metric-icon">🧺</div>
            </div>
            <div class="metric-value"><?php echo $totalCosecha; ?> <small style="font-size: 18px;">kg</small></div>
            <div class="metric-sub">Rendimiento acumulado</div>
        </div>


        <div class="metric-card">
            <div class="metric-header">
                <h3>Consumo Riego</h3>
                <div class="metric-icon">💧</div>
            </div>
            <div class="metric-value"><?php echo number_format($riegoSemanal, 0, ',', '.'); ?> <small style="font-size: 18px;">L</small></div>
            <div class="metric-sub">Volumen semanal</div>
        </div>
    </section>


    <!-- GRÁFICOS -->
    <section class="charts-grid">
        <div class="panel">
            <div class="panel-header">
                <h2>📊 Distribución de Planta por Especie</h2>
            </div>
            <div class="chart-wrapper">
                <canvas id="plantasChart"></canvas>
            </div>
        </div>


        <div class="panel">
            <div class="panel-header">
                <h2>🎯 Rendimiento de Cosecha (kg)</h2>
            </div>
            <div class="chart-wrapper">
                <canvas id="cosechaChart"></canvas>
            </div>
        </div>
    </section>


    <!-- TABLA DE DETALLES -->
    <section class="panel table-section">
        <div class="panel-header">
            <h2>📋 Detalle de Cultivos y Diagnóstico</h2>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Cultivo</th>
                        <th>Cantidad</th>
                        <th>Cosecha Esperada/Obtenida</th>
                        <th>Estado de Salud</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cultivos as $nombre => $datos): ?>
                    <tr>
                        <td><strong><?php echo $nombre; ?></strong></td>
                        <td><?php echo $datos["cantidad"]; ?> unidades</td>
                        <td><?php echo $datos["cosecha"]; ?> kg</td>
                        <td>
                            <span class="badge <?php echo strtolower($datos["estado"]); ?>">
                                ● <?php echo $datos["estado"]; ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>


    <!-- CONDICIONES AMBIENTALES -->
    <section class="env-grid">
        <div class="env-card">
            <div class="env-icon">🌡️</div>
            <div class="env-details">
                <h3>Temperatura Promedio</h3>
                <strong><?php echo $temperatura; ?> °C</strong>
            </div>
        </div>


        <div class="env-card">
            <div class="env-icon">💧</div>
            <div class="env-details">
                <h3>Humedad Relativa</h3>
                <strong><?php echo $humedad; ?>%</strong>
            </div>
        </div>


        <div class="env-card">
            <div class="env-icon">✨</div>
            <div class="env-details">
                <h3>Estado General</h3>
                <strong><?php echo $estadoHuerta; ?></strong>
            </div>
        </div>
    </section>


</div>


<script>
    // Configuración global de fuentes para Chart.js
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.color = '#607363';


    // Datos PHP convertidos a JS
    const labels = <?php echo json_encode(array_keys($cultivos)); ?>;
    const cantidades = <?php echo json_encode(array_column($cultivos, 'cantidad')); ?>;
    const cosechas = <?php echo json_encode(array_column($cultivos, 'cosecha')); ?>;


    // Gráfico de Barras - Cantidad de plantas
    const ctxPlantas = document.getElementById('plantasChart').getContext('2d');
    new Chart(ctxPlantas, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Cantidad de plantas',
                data: cantidades,
                backgroundColor: '#2e7d32',
                borderRadius: 8,
                hoverBackgroundColor: '#1b5e20'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.04)' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });


    // Gráfico Donut - Cosecha
    const ctxCosecha = document.getElementById('cosechaChart').getContext('2d');
    new Chart(ctxCosecha, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: cosechas,
                backgroundColor: [
                    '#2e7d32',
                    '#4caf50',
                    '#81c784',
                    '#a5d6a7'
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: { boxWidth: 12, padding: 16 }
                }
            },
            cutout: '65%'
        }
    });
</script>


</body>
</html>
