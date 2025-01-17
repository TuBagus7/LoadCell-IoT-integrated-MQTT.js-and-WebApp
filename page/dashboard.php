<?php
include "../config/database.php";

// Query SQL (jika dibutuhkan)
$sql = "SELECT * FROM data";
$result = mysqli_query($conn, $sql);
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
                <!-- Tinggi Badan -->
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
                
                
                <!-- BMI -->
                <div class="col-lg-4">
                    <div class="small-box bg-blue">
                        <div class="inner">
                            <h3> <span id="bmi">- </span>M2</h3>
                            <h6>BMI</h6>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calculator"></i>
                        </div>
                    </div>
                </div>


                <!-- Hasil -->
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

                <!-- Tombol Save -->
                <div class="col-lg-12">
                    <a href="javascript:void(0);" class="btn btn-primary" id="saveBtn">
                        <i class="nav-icon fas fa-save"></i> Save
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById("saveBtn").addEventListener("click", function () {
        const tb = document.getElementById("usonic").innerText;
        const bb = document.getElementById("berat").innerText;
        const value = document.getElementById("result").innerText;
        const bmi = document.getElementById("bmi").innerText;

        // Redirect to datasensor.php with parameters
        window.location.href = `page/revdatasensor.php?tb=${tb}&bb=${bb}&value=${value}`;
        // page/datasensor.php  
    });
</script>
