<?php
include "../config/database.php"; // Pastikan file ini terhubung dengan database Anda

// Inisialisasi array hasil
$data_result = [
    "harian" => [],
    "mingguan" => [],
    "bulanan" => [],
    "tahunan" => []
];

// Query untuk data harian
$sql_harian = "SELECT value, COUNT(*) AS jumlah FROM data WHERE DATE(time) = CURDATE() GROUP BY value";
$result_harian = mysqli_query($conn, $sql_harian);
while ($row = mysqli_fetch_assoc($result_harian)) {
    $data_result['harian'][] = $row;
}

// Query untuk data mingguan
$sql_mingguan = "SELECT value, COUNT(*) AS jumlah FROM data WHERE YEARWEEK(time, 1) = YEARWEEK(CURDATE(), 1) GROUP BY value";
$result_mingguan = mysqli_query($conn, $sql_mingguan);
while ($row = mysqli_fetch_assoc($result_mingguan)) {
    $data_result['mingguan'][] = $row;
}

// Query untuk data bulanan
$sql_bulanan = "SELECT value, COUNT(*) AS jumlah FROM data WHERE MONTH(time) = MONTH(CURDATE()) AND YEAR(time) = YEAR(CURDATE()) GROUP BY value";
$result_bulanan = mysqli_query($conn, $sql_bulanan);
while ($row = mysqli_fetch_assoc($result_bulanan)) {
    $data_result['bulanan'][] = $row;
}

// Query untuk data tahunan
$sql_tahunan = "SELECT value, COUNT(*) AS jumlah FROM data WHERE YEAR(time) = YEAR(CURDATE()) GROUP BY value";
$result_tahunan = mysqli_query($conn, $sql_tahunan);
while ($row = mysqli_fetch_assoc($result_tahunan)) {
    $data_result['tahunan'][] = $row;
}

// Encode data ke JSON untuk digunakan di frontend
$json_data = json_encode($data_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grafik Data Indikasi</title>
    <!-- Link ke Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            margin: 20px 0;
            color: #333;
        }
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .card {
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 20px;
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .card canvas {
            width: 100%;
        }
        footer {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            background: #333;
            color: #fff;
            font-size: 14px;
        }
    </style>
</head>
<>
    <div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
            </div>
        </div>
    </div>
    <!-- Kontainer Grid untuk semua grafik -->
    <div class="grid-container">
        <div class="card">
            <h2>Data Harian</h2>
            <canvas id="chartHarian"></canvas>
        </div>
        <div class="card">
            <h2>Data Mingguan</h2>
            <canvas id="chartMingguan"></canvas>
        </div>
        <div class="card">
            <h2>Data Bulanan</h2>
            <canvas id="chartBulanan"></canvas>
        </div>
        <div class="card">
            <h2>Data Tahunan</h2>
            <canvas id="chartTahunan"></canvas>
        </div>
    </div>
    </div>
    

    <footer>
        &copy; 2024 Sistem Indikasi IoT - Dibuat dengan <span style="color: red;">&#9829;</span>
    </footer>

    <script>
        // Ambil data dari PHP yang telah di-encode ke JSON
        const data = <?php echo $json_data; ?>;

        // Fungsi untuk membuat grafik menggunakan Chart.js
        function createChart(ctx, label, dataLabels, dataCounts) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: dataLabels,
                    datasets: [{
                        label: label,
                        data: dataCounts,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Data Harian
        const labelsHarian = data.harian.map(item => item.value);
        const countsHarian = data.harian.map(item => item.jumlah);
        createChart(
            document.getElementById('chartHarian').getContext('2d'),
            'Data Harian',
            labelsHarian,
            countsHarian
        );

        // Data Mingguan
        const labelsMingguan = data.mingguan.map(item => item.value);
        const countsMingguan = data.mingguan.map(item => item.jumlah);
        createChart(
            document.getElementById('chartMingguan').getContext('2d'),
            'Data Mingguan',
            labelsMingguan,
            countsMingguan
        );

        // Data Bulanan
        const labelsBulanan = data.bulanan.map(item => item.value);
        const countsBulanan = data.bulanan.map(item => item.jumlah);
        createChart(
            document.getElementById('chartBulanan').getContext('2d'),
            'Data Bulanan',
            labelsBulanan,
            countsBulanan
        );

        // Data Tahunan
        const labelsTahunan = data.tahunan.map(item => item.value);
        const countsTahunan = data.tahunan.map(item => item.jumlah);
        createChart(
            document.getElementById('chartTahunan').getContext('2d'),
            'Data Tahunan',
            labelsTahunan,
            countsTahunan
        );
    </script>
</body>
</html>
