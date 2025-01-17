<?php
// Include file konfigurasi database untuk koneksi ke database
include "../config/database.php";

// Tangkap filter waktu dari form, dengan default ke "all" jika tidak diatur
$filter_type = isset($_GET['filter_type']) ? $_GET['filter_type'] : 'all';
$filter_date = isset($_GET['filter_date']) ? $_GET['filter_date'] : 'all';

// Query dasar untuk mengambil semua data
$sql = "SELECT * FROM data";

// Tambahkan kondisi filter berdasarkan tipe yang dipilih pengguna
if ($filter_type !== 'all') {
    if ($filter_type === 'daily') {
        // Filter data berdasarkan tanggal spesifik
        $sql .= " WHERE DATE(time) = '$filter_date'";
    } elseif ($filter_type === 'weekly') {
        // Filter data berdasarkan minggu dalam tahun yang sama
        $sql .= " WHERE WEEK(time) = WEEK('$filter_date') AND YEAR(time) = YEAR('$filter_date')";
    } elseif ($filter_type === 'monthly') {
        // Filter data berdasarkan bulan dan tahun
        $sql .= " WHERE MONTH(time) = MONTH('$filter_date') AND YEAR(time) = YEAR('$filter_date')";
    }
}

// Eksekusi query ke database
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filter Data</title>
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/css/adminlte.min.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.6/css/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Filter Data Sensor</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Form Filter -->
        <form method="GET" action="page/filter.php">
            <div class="row mb-3">
                <div class="col-md-3">
                    <!-- Dropdown untuk memilih tipe filter -->
                    <select name="filter_type" class="form-control" required>
                        <option value="all" <?= $filter_type === 'all' ? 'selected' : '' ?>>Semua Data</option>
                        <option value="daily" <?= $filter_type === 'daily' ? 'selected' : '' ?>>Per Hari</option>
                        <option value="weekly" <?= $filter_type === 'weekly' ? 'selected' : '' ?>>Per Minggu</option>
                        <option value="monthly" <?= $filter_type === 'monthly' ? 'selected' : '' ?>>Per Bulan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <!-- Input untuk memilih tanggal -->
                    <input type="date" name="filter_date" class="form-control" value="<?= $filter_date ?>">
                </div>
                <div class="col-md-3">
                    <!-- Tombol untuk mengirim form filter -->
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>

        <!-- Tabel Data -->
        <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="card-title text-white">Daftar Pengguna Terdaftar</h3>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Umur</th>
                                    <th>Nama</th>
                                    <th>Tinggi Badan (cm)</th>
                                    <th>Berat Badan (kg)</th>
                                    <th>Value</th>
                                    <th>Waktu</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                    <tr>
                                        <td><?php echo $row['umur']; ?></td>
                                        <td><?php echo $row['nama']; ?></td>
                                        <td><?php echo $row['tb']; ?></td>
                                        <td><?php echo $row['bb']; ?></td>
                                        <td><?php echo $row['value']; ?></td>
                                        <td><?php echo $row['time']; ?></td>
                                        <td>
                                            <a href="?page=<?php echo $page; ?>&edit=<?php echo $row['nama']; ?>"> 
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="?page=<?php echo $page; ?>&delete=<?php echo $row['nama']; ?>" style="padding-left:30px">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                    <tr>
                        <th colspan="2">Rata-Rata, Max, Min</th>
                        <th id="avg_tb"></th>
                        <th id="avg_bb"></th>
                        <th colspan="3"></th>
                    </tr>
                </tfoot>
                        </table>
                    </div>
                </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.11.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.6/js/dataTables.bootstrap4.min.js"></script>
<script>
    // Inisialisasi DataTables untuk memberikan fitur seperti pencarian dan pagination
    // $(document).ready(function () {
    //     $('#example1').DataTable();
    // });

    window.alert = function () {};
    
    $(document).ready(function() {
        // Inisialisasi DataTables
        var table = $('#example1').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    footer: true
                },
                {
                    extend: 'csvHtml5',
                    footer: true
                },
                {
                    extend: 'pdfHtml5',
                    footer: true,
                    customize: function(doc) {
                        doc.styles.tableFooter = { alignment: 'center' };
                    }
                }
            ],
            "footerCallback": function(row, data, start, end, display) {
                var api = this.api();

                // Fungsi untuk menghitung rata-rata
                var calculateAverage = function(columnIndex) {
                    var total = 0;
                    var count = 0;

                    api.column(columnIndex, { page: 'current' }).data().each(function(value) {
                        value = parseFloat(value);
                        if (!isNaN(value)) {
                            total += value;
                            count++;
                        }
                    });

                    return count === 0 ? 0 : (total / count).toFixed(2);
                };// Fungsi untuk menghitung nilai maksimum
                var calculateMax = function(columnIndex) {
                    var max = -Infinity;

                    api.column(columnIndex, { page: 'current' }).data().each(function(value) {
                        value = parseFloat(value);
                        if (!isNaN(value) && value > max) {
                            max = value;
                        }
                    });

                    return max === -Infinity ? 'N/A' : max.toFixed(2);
                };

                // Fungsi untuk menghitung nilai minimum
                var calculateMin = function(columnIndex) {
                    var min = Infinity;

                    api.column(columnIndex, { page: 'current' }).data().each(function(value) {
                        value = parseFloat(value);
                        if (!isNaN(value) && value < min) {
                            min = value;
                        }
                    });

                    return min === Infinity ? 'N/A' : min.toFixed(2);
                };

                // Hitung rata-rata, maksimum, dan minimum untuk Tinggi Badan (Index ke-2)
                var avg_tb = calculateAverage(2);
                var max_tb = calculateMax(2);
                var min_tb = calculateMin(2);

                // Hitung rata-rata, maksimum, dan minimum untuk Berat Badan (Index ke-3)
                var avg_bb = calculateAverage(3);
                var max_bb = calculateMax(3);
                var min_bb = calculateMin(3);

                // Tampilkan hasil di footer
                $('#avg_tb').html(`Rata-Rata: ${avg_tb}<br>TB Tertinggi: ${max_tb}<br>TB Terendah: ${min_tb}`);
                $('#avg_bb').html(`Rata-Rata: ${avg_bb}<br>BB Terberat: ${max_bb}<br>BB Terendah: ${min_bb}`);
            }
        });
    });
</script>
</body>
</html>