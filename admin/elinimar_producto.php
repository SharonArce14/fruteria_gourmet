<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../config/database.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $conn = conectarDB();

    // Uso de prepared statement para prevenir SQL Injection
    $sql = $conn->prepare("DELETE FROM productos WHERE id = ?");
    $sql->bind_param("i", $id);
    $sql->execute();

    $conn->close();
}

header("Location: productos.php");
exit();
