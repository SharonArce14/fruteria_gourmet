<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../config/database.php';

$conn = conectarDB();

// Contar productos
$result_productos = $conn->query("SELECT COUNT(*) as total FROM productos");
$total_productos = $result_productos->fetch_assoc()['total'];

// Contar tipos
$result_tipos = $conn->query("SELECT COUNT(*) as total FROM tipos_productos");
$total_tipos = $result_tipos->fetch_assoc()['total'];

// Productos recientes
$query_recientes = "SELECT p.*, t.nombre as tipo_nombre FROM productos p 
                   INNER JOIN tipos_productos t ON p.tipo_producto_id = t.id 
                   ORDER BY p.fecha_creacion DESC LIMIT 5";
$productos_recientes = $conn->query($query_recientes);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Fruteria Gourmet</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <h1>Dashboard</h1>
        <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?>!</p>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Productos</h3>
                <p class="stat-number"><?php echo $total_productos; ?></p>
            </div>
            
            <div class="stat-card">
                <h3>Tipos de Productos</h3>
                <p class="stat-number"><?php echo $total_tipos; ?></p>
            </div>
        </div>
        
        <div class="recent-section">
            <h2>Productos Recientes</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Tipo</th>
                        <th>Disponible</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($producto = $productos_recientes->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $producto['id']; ?></td>
                        <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                        <td>$<?php echo number_format($producto['precio'], 0); ?></td>
                        <td><?php echo htmlspecialchars($producto['tipo_nombre']); ?></td>
                        <td><?php echo $producto['disponible'] ? 'Si' : 'No'; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <?php 
    include '../includes/footer.php';
    $conn->close();
    ?>
</body>
</html>