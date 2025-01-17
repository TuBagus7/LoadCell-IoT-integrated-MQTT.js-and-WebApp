<?php
include "../config/database.php";
$page = isset($_GET['page']) ? $_GET['page'] : '';
$insert = false;

// Tangkap data dari URL
$tb = isset($_GET['tb']) ? $_GET['tb'] : '';
$bb = isset($_GET['bb']) ? $_GET['bb'] : '';
$value = isset($_GET['value']) ? $_GET['value'] : '';

// Cek apakah dalam mode edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql_edit = "SELECT * FROM data WHERE nama = '$id'";
    $result_edit = mysqli_query($conn, $sql_edit);
    if ($result_edit) {
        $edit_data = mysqli_fetch_assoc($result_edit);
    }
}

// Proses tambah atau edit data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['edit_data']) && !empty($_POST['edit_data'])) {
        // Mode Edit
        $old_id = $_POST['edit_data']; // Data lama untuk identifikasi
        $umur = $_POST['umur'];
        $nama = $_POST['nama'];
        $tinggi_b = $_POST['tb'];
        $berat_b = $_POST['bb'];
        $hasil = $_POST['value'];

        $sql_edit = "UPDATE data 
        SET umur = '$umur', nama = '$nama', tb = '$tinggi_b', bb = '$berat_b', value = '$hasil' 
        WHERE nama = '$old_id'";
        mysqli_query($conn, $sql_edit);
    } else {
        // Mode Tambah
        $umur = $_POST['umur'];
        $nama = $_POST['nama'];
        $tinggi_b = $_POST['tb'];
        $berat_b = $_POST['bb'];
        $hasil = $_POST['value'];

        $sql_insert = "INSERT INTO data (umur, nama, tb, bb, value) 
        VALUES ('$umur', '$nama', '$tinggi_b', '$berat_b', '$hasil')";
        mysqli_query($conn, $sql_insert);
        $insert = true;
    }
}

// Proses hapus data
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql_delete = "DELETE FROM data WHERE nama = '$id'";
    mysqli_query($conn, $sql_delete);
}

// Query untuk menampilkan data
$sql = "SELECT * FROM data";
$result = mysqli_query($conn, $sql);

// Query untuk menghitung total pengukuran
$sql_count = "SELECT COUNT(*) AS total FROM data";
$result_count = mysqli_query($conn, $sql_count);
$row_count = mysqli_fetch_assoc($result_count);
$total_pengukuran = $row_count['total'];
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Sensor</title>
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/css/adminlte.min.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.6/css/dataTables.bootstrap4.min.css">
</head>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Sensor</h1>
                </div>
            </div>
        </div>
    </div>
                <?php if ($insert) { ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
                        Data telah ditambahkan.
                    </div>
                <?php } ?>
                <!-- Menampilkan total pengukuran -->
                <div class="alert alert-info">
                    <strong>Total Pengukuran: <?php echo $total_pengukuran; ?> pengukuran</strong>
                </div>
                <!-- Table -->
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

                <!-- Form Tambah/Edit Data -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><?php echo $edit_data ? 'Edit Data' : 'Tambah Data Baru'; ?></h3>
                    </div>
                    <form method="post" action="">
                        <input type="hidden" name="edit_data" value="<?php echo $edit_data ? $edit_data['nama'] : ''; ?>">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Umur</label>
                                <input type="text" class="form-control" name="umur" 
                                        value="<?php echo $edit_data ? $edit_data['umur'] : ''; ?>" 
                                        placeholder="Masukkan Umur Bayi" required>
                            </div>
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" name="nama" 
                                        value="<?php echo $edit_data ? $edit_data['nama'] : ''; ?>" 
                                        placeholder="Masukkan Nama Bayi" required>
                            </div>
                            <div class="form-group">
                                <label>Tinggi Badan (cm)</label>
                                <input type="text" class="form-control" name="tb" 
                                        value="<?php echo $edit_data ? $edit_data['tb'] : $tb; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Berat Badan (kg)</label>
                                <input type="text" class="form-control" name="bb" 
                                        value="<?php echo $edit_data ? $edit_data['bb'] : $bb; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Value</label>
                                <input type="text" class="form-control" name="value" 
                                        value="<?php echo $edit_data ? $edit_data['value'] : $value; ?>" readonly>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <?php echo $edit_data ? 'Update' : 'Simpan'; ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <!-- Tombol Kembali ke Dashboard -->
            </div>
        </div>
    </div>
</section>

    </div>
</div>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/js/adminlte.min.js"></script>
<script src="https://cdn.datatables.net/1.11.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.6/js/dataTables.bootstrap4.min.js"></script>
<script>
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