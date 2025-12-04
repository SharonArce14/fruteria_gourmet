<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../config/database.php';

$conn = conectarDB();

$query = "SELECT p.*, t.nombre as tipo_nombre FROM productos p 
          INNER JOIN tipos_productos t ON p.tipo_producto_id = t.id 
          ORDER BY p.id DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Productos - Fruteria Gourmet</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <div class="page-header">
            <h1>Gestion de Productos</h1>
            <a href="crear_producto.php" class="btn btn-primary">+ Nuevo Producto</a>
        </div>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Precio</th>
                    <th>Tipo</th>
                    <th>Disponible</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($producto = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $producto['id']; ?></td>
                    <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                    <td><?php echo htmlspecialchars(substr($producto['descripcion'], 0, 50)); ?>...</td>
                    <td>$<?php echo number_format($producto['precio'], 0); ?></td>
                    <td><?php echo htmlspecialchars($producto['tipo_nombre']); ?></td>
                    <td>
                        <span class="badge <?php echo $producto['disponible'] ? 'badge-success' : 'badge-danger'; ?>">
                            <?php echo $producto['disponible'] ? 'Disponible' : 'No disponible'; ?>
                        </span>
                    </td>
                    <td class="actions">
                        <a href="editar_producto.php?id=<?php echo $producto['id']; ?>" class="btn btn-small btn-edit">Editar</a>
                        <a href="eliminar_producto.php?id=<?php echo $producto['id']; ?>" 
                           class="btn btn-small btn-delete" 
                           onclick="return confirm('¿Esta seguro de eliminar este producto?')">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    
    <?php 
    include '../includes/footer.php';
    $conn->close();
    ?>
</body>
</html>