<?php

function conectarDB() {
    $host = "localhost";
    $usuario = "fruteria_user";
    $password = "Frut3ria#2025";
    $basedatos = "fruteria_gourmet";
    
    $conn = new mysqli($host, $usuario, $password, $basedatos);
    
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8");
    return $conn;
}

?>
