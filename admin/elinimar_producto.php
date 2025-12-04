<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../config/database.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id) {
    $conn = conectarDB();
    
    $sql = "DELETE FROM productos WHERE id = $id";
    $conn->query($sql);
    
    $conn->close();
}

header("Location: productos.php");
exit();
?>