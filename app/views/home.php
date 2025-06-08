<?php

$result = fetchTemperatureData();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Weather Station Dashboard</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Charts -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
        }
        
        .dashboard-header {
            background: var(--primary-color);
            padding: 2rem;
            margin-bottom: 2rem;
            color: white;
        }
        
        .gauge-container {
            height: 400px;
            position: relative;
            margin-bottom: 2rem;
        }
        
        .data-table {
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
            border-radius: 0.5rem;
            overflow: hidden;
        }
        
        .table thead th {
            background: var(--primary-color);
            color: white;
        }
    </style>
</head>

<body>
    <header class="dashboard-header text-center">
        <h1 class="mb-3">Environmental Monitoring Dashboard</h1>
        <p class="lead">Real-time temperature and humidity tracking</p>
    </header>
    <?php
    if (session('success')) {
      ?>
      <div class="alert alert-success alert-dismissible fade show" style="background-color: rgb(58, 151, 0); color: aliceblue;" role="alert">
        <strong>Success!</strong> <?= session('success') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?php
      unset($_SESSION['success']);
    }

    if (session('error')) {
      ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> <?= session('error') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?php
      unset($_SESSION['error']);
    }
    ?>
    <main class="container-lg">
        <div class="row g-4">
            <div class="col-12 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div id="chart_temperature" class="gauge-container"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div id="chart_humidity" class="gauge-container"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-12">
                <div class="data-table">
                    <table class="table table-hover table-striped mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Temperature (°C)</th>
                                <th>Humidity (%)</th>
                                <th>Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>
                          <?php foreach($result as $index => $row): ?>
                          <tr>
                            <td><?= $index + 1 ?></td>
                            <td class="temperature-cell"></td>
                            <td class="humidity-cell"></td>
                            <td class="timestamp-cell"></td>
                          </tr>
                          <script>
                            (async function fetchRowData() {
                                try {
                                    const response = await fetch("<?php echo ROOT('fetch'); ?>");
                                    const { temperature, humidity, created_date } = await response.json();
                                    document.querySelectorAll('.temperature-cell')[<?= $index ?>].textContent = parseFloat(temperature).toFixed(1);
                                    document.querySelectorAll('.humidity-cell')[<?= $index ?>].textContent = parseFloat(humidity).toFixed(1);
                                    document.querySelectorAll('.timestamp-cell')[<?= $index ?>].textContent = new Date(created_date).toLocaleString('en-US', {
                                        month: 'short',
                                        day: 'numeric',
                                        year: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    });
                                } catch (error) {
                                    console.error('Failed to fetch row data:', error);
                                }
                            })();
                          </script>
                          <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
      const ajaxUrl = "<?php echo ROOT('ajax'); ?>";
    (function() {
        'use strict';
        
        const chartConfig = {
            width:  '100%',
            height: '100%',
            redFrom: 70,
            redTo: 100,
            yellowFrom: 40,
            yellowTo: 70,
            greenFrom: 0,
            greenTo: 40,
            minorTicks: 5,
            majorTicks: ['0','20','40','60','80','100']
        };

        function initCharts() {
            google.charts.load('current', {'packages':['gauge']});
            google.charts.setOnLoadCallback(() => {
                const tempChart = createChart('chart_temperature', 'Temperature', '°C');
                const humidChart = createChart('chart_humidity', 'Humidity', '%');
                startDataRefresh(tempChart, humidChart);
            });
        }

        function createChart(elementId, label, unit) {
            const data = google.visualization.arrayToDataTable([
                ['Label', 'Value'],
                [label, 0]
            ]);
            
            const container = document.getElementById(elementId);
            const chart = new google.visualization.Gauge(container);
            chart.draw(data, {...chartConfig, min: 0, max: 100});
            
            return {
                chart,
                data,
                label,
                unit
            };
        }

        function startDataRefresh(...charts) {
            setInterval(async () => {
                try {
                    const response = await fetch(ajaxUrl);
                    const {temperature, humidity} = await response.json();
                    
                    charts.forEach(chart => {
                        const value = chart.label === 'Temperature' ? temperature : humidity;
                        chart.data.setValue(0, 1, parseFloat(value).toFixed(2));
                        chart.chart.draw(chart.data, chartConfig);
                    });
                } catch(error) {
                    console.error('Data refresh failed:', error);
                }
            }, 2000);
        }

        window.addEventListener('load', initCharts);
        window.addEventListener('resize', () => google.charts.setOnLoadCallback(initCharts));
    })();
    </script>
</body>
</html>