<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "veterinaria";

    // Crear la conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Revisar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
?>