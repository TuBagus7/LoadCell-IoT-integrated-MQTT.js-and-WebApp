<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <?php if(!isset($_GET['page']) || $_GET['page'] == "dashboard"){ ?>
      <li class="nav-item d-none d-sm-inline-block">
        <a class="nav-link" id="status" style="color:red;">Terputus</a>
      </li>
      <?php } ?>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="https://wa.me/62888888888" class="nav-link">Kontak</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
  </nav>