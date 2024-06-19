<?php
include "config/database.php";
// Contoh query SQL dan koneksi database
$sql = "SELECT * FROM data";
$result = mysqli_query($conn, $sql);

if (isset($_POST['save'])) {
    // Lakukan proses penyimpanan data di sini (jika ada)

    // Setelah proses penyimpanan selesai, arahkan ke datasensor.php
    header('Location: page/datasensor.php');
    exit();
}

?> 
<script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>
<script src="../mqtt/MQTT.js"></script>

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

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><span id="usonic">-</span> CM</h3>
                            <h6>TINGGI BADAN</h6>
                        </div>
                        <div class="icon">
                            <i class="fas fa-ruler-vertical"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="small-box bg-indigo">
                        <div class="inner">
                            <h3><span id="berat">-</span> KG</h3>
                            <h6>BERAT BADAN</h6>
                        </div>
                        <div class="icon">
                            <i class="fas fa-weight"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="small-box bg-maroon">
                        <div class="inner">
                            <h3 id="result">-</h3>
                            <h6>HASIL</h6>
                        </div>
                        <div class="icon">
                            <i class="fas fa-poll-h"></i>
                        </div>
                        </div>
                        </div>
                        <a href="?page=datasensor" class="btn btn-primary">
                        <i class="nav-icon fas fa-save"></i>
                        <p>save</p>
                        </a>
                        
        </form>
            </div>
        </div>
    </div>
</div>
