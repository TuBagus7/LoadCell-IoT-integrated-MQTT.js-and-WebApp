<?php
include "../config/database.php";


$webhookResponse = json_decode(file_get_contents('php://input'),true);
$topic = $webhookResponse["topic"];
$ypaload = $webhookResponse["payload"];

$topicExplode = explode("/", $topic);

$serialNumber = $topicExplode[1];
$name = $topicExplode[2];

if($topicExplode[2] == "suhu" || $topicExplode[2] == "kelembaban" || $topicExplode[2] == "potentiometer"){
    $type = "sensor";
} else {
    $type = "actuator";
}

$sql = "INSERT INTO data (serial_number, sensor_actuator, value, name, mqtt_topic)
        VALUES ('$serialNumber', '$type', '$payload', '$name', '$topic')";

mysqli_query($conn, $sql);


?>