<?php
include "../config/database.php";


$webhookResponse = json_decode(json: file_get_contents(filename: 'php://input'),associative: true);
$topic = $webhookResponse["topic"];
$payload = $webhookResponse["payload"];

$topicExplode = explode(separator: "/", string: $topic);
$payloadExplode = explode(separator: "/", string: $payload);


$nama = $payloadExplode[0];
$tb = $payloadExplode[1];
$bb = $payloadExplode[2];
$value = $payloadExplode[3];

// if($topicExplode[2] == "suhu" || $topicExplode[2] == "kelembaban" || $topicExplode[2] == "potentiometer"){
//     $type = "sensor";
// } else {
//     $type = "actuator";
// }

$sql = "INSERT INTO data (nama, tb, bb, value)
        VALUES ('$nama', '$tb', '$bb', '$value')";

mysqli_query( $conn,$sql);

// echo $sql;
?>