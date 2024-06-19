<?php
session_start();

include "config/database.php";

$message = "Masukan Data Registrasi";


if(isset($_POST['username'])){
  $username = $_POST['username'];
  $password = $_POST['password'];
  $fullname = $_POST['fullname'];
  $role = $_POST['role'];

  $sql = "SELECT * FROM user WHERE username = '$username' LIMIT 1";
  $result = mysqli_query($conn, $sql);

  if(mysqli_num_rows($result) > 0){
    $message = "<b style='color:red;'>Username sudah terdaftar</b>";
  } else {
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $insert_sql = "INSERT INTO user (username, password, fullname, role) VALUES ('$username', '$hashed_password', '$fullname', '$role')";

    
    if(mysqli_query($conn, $insert_sql)){
      $message = "<b style='color:green;'>Registrasi berhasil. Silakan <a href='login.php'>login</a></b>";
    } else {
      $message = "<b style='color:red;'>Registrasi gagal. Silakan coba lagi.</b>";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistem IoT - Registrasi</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="register-logo">
    <b>Sistem</b> IoT
  </div>
  <!-- /.register-logo -->
  <div class="card">
    <div class="card-body register-card-body">
      <p class="login-box-msg"><?php echo $message ?></p>

      <form action="" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="fullname" placeholder="Full Name" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="username" placeholder="Username" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="form-group">
  <label>Hak Akses</label>
    <div class="input-group">
      <select class="form-control" name="role">
      <option value="Admin">Admin</option>
      <option value="User">Pengguna</option>
      </select>
    </div>
    </div>

        <div class="row">
        <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Registrasi</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <p class="mt-3">
        Sudah memiliki akun? <a href="login.php" class="text-center">Masuk</a>
      </p>
    </div>
    <!-- /.register-card-body -->
  </div>
</div>
<!-- /.register-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
