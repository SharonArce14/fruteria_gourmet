<?php

function conectarDB() {
    $host = "localhost";
    $usuario = getenv('DB_USER') ?: "root";
    $password = getenv('DB_PASSWORD') ?: "";
    $basedatos = "fruteria_gourmet";
    
    $conn = new mysqli($host, $usuario, $password, $basedatos);
    
    if ($conn->connect_error) {
        die("Error de conexion: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8");
    return $conn;
}
