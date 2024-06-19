<?php

// if($_SESSION['role'] != "Admin"){
//   echo "<script> location.href='index.php' </script>";
// }
// echo "Anda telah berhasil diarahkan ke datasensor.php";
$page = $_GET['page'];
$insert = false;

if(isset($_POST['edit_data'])){
  $old_id = $_POST['edit_data'];
  $nama = $_POST['nama'];
  $tinggi_b = $_POST['tb'];
  $berat_b = $_POST['bb'];
  $hasil = $_POST['value'];
  $sql_edit = "UPDATE data SET nama = '$nama', tb = '$tinggi_b', bb = '$berat_b', value = '$hasil' WHERE nama = '$old_id'";
  mysqli_query($conn, $sql_edit); 

}else if (isset($_POST['nama'])) {
  $nama = $_POST['nama'];
  $tinggi_b = $_POST['tb'];
  $berat_b = $_POST['bb'];
  $hasil = $_POST['value'];
  $sql_insert = "INSERT INTO data (nama, tb, bb, value) VALUES ('$nama', '$tinggi_b', '$berat_b','$hasil')";
  mysqli_query($conn, $sql_insert);
  $insert = false;
}

if(isset($_GET['edit'])){
  $id = $_GET['edit'];
  $sql_select_data = "SELECT * FROM data WHERE nama = '$id' LIMIT 1";

  $result = mysqli_query($conn, $sql_select_data);
  $data = mysqli_fetch_assoc($result);
}

if(isset($_GET['delete'])){
  $id = $_GET['delete'];
  $sql_delete = "DELETE FROM data WHERE nama = '$id'";
  mysqli_query($conn, $sql_delete);
}


$sql = "SELECT * FROM data";
$result = mysqli_query($conn, $sql);
?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Riwayat</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <?php
      if ($insert == true) {
        alertsSuccess("Data berhasil ditambahkan");
      }
      ?>
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Pengguna Yang Terdaftar</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
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
                      <td><?php echo $row['nama'] ?></td>
                      <td><?php echo $row['tb'] ?></td>
                      <td><?php echo $row['bb'] ?></td>
                      <td><?php echo $row['value'] ?></td>
                      <td><?php echo $row['time'] ?></td>
                      <td>
                        <a href="?page=<?php echo $page ?>&edit=<?php echo $row['nama'] ?>"> <i class="fas fa-edit"></i></a> 
                        <a href="?page=<?php echo $page ?>&delete=<?php echo $row['nama'] ?>"><i class="fas fa-trash-alt", style="padding-left:30px"></i></a>
                      </td>
                    </tr>
                  <?php } ?>
                  </tfoot>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          
          <?php if(!isset($_GET['edit'])){ ?>

            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Tambah Data</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form method="post" action="?page=<?php echo $page ?>">
                <div class="card-body">
                  <div class="form-group">
                    <label>Nama</label>
                    <input type="text" class="form-control" name="nama" placeholder="Username tidak boleh sama" required>
                  </div>
                  <div class="form-group">
                    <label>Tinggi Badan</label>
                    <input type="text" class="form-control" name="tb" required>
                  </div>
                  <div class="form-group">
                    <label>Berat Badan</label>
                    <input type="text" class="form-control" name="bb" required>
                  </div>
                  <div class="form-group">
                    <label>Value</label>
                    <input type="text" class="form-control" name="value" required>
                  </div>

                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
              </form>
            </div>

          <?php } else { ?>

            <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Ubah Data</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form method="post" action="?page=<?php echo $page ?>">
                <div class="card-body">
                  <div class="form-group">
                    <label>Nama</label>
                    <input type="hidden" name="edit_data" value="<?php echo $data['nama'] ?>">
                    <input type="text" class="form-control" name="nama" value="<?php echo $data['nama'] ?>" placeholder="Masukkan Nama yang Ingin di Ubah" required>
                  </div>
                  <div class="form-group">
                    <label>Tinggi Badan</label>
                     
                    <input type="text" class="form-control" name="tb" value="<?php echo $data['tb'] ?>" placeholder="Masukkan Tinggi Bada yang di ubah" required>
                  </div>
                  <div class="form-group">
                    <label>Berat Badan</label>
                    <input type="text" class="form-control" name="bb" value="<?php echo $data['bb'] ?>" placeholder="Masukkan Nama yang Ingin di Ubah" required>
                  </div>
                  <div class="form-group">
                    <label>Value</label>
                    <input type="text" class="form-control" name="value" value="<?php echo $data['value'] ?>" placeholder="Masukkan Hasil yang Ingin di Ubah" required>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-warning">Ubah</button>
                </div>
              </form>
            </div>

          <?php } ?>

        </div>
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>