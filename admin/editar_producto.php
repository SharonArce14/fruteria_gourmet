<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../config/database.php';
require_once '../includes/header.php'; // Cambio: include → include_once/require_once

$conn = conectarDB();

$mensaje = "";
$error = "";
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$id) {
    header("Location: productos.php");
    exit();
}

// Obtener tipos de productos usando prepared statements
$query_tipos = $conn->prepare("SELECT * FROM tipos_productos ORDER BY nombre");
$query_tipos->execute();
$tipos_productos = $query_tipos->get_result();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $tipo_producto_id = intval($_POST['tipo_producto_id']);
    $disponible = isset($_POST['disponible']) ? 1 : 0;

    if ($nombre && $precio && $tipo_producto_id) {

        // Uso de prepared statements para evitar SQL Injection
        $sql = $conn->prepare("UPDATE productos SET 
                nombre=?, 
                descripcion=?, 
                precio=?, 
                tipo_producto_id=?, 
                disponible=?
                WHERE id=?");

        $sql->bind_param("ssdiii", 
            $nombre, 
            $descripcion, 
            $precio, 
            $tipo_producto_id, 
            $disponible,
            $id
        );

        if ($sql->execute()) {
            header("Location: productos.php");
            exit();
        } else {
            $error = "Error al actualizar el producto: " . $conn->error;
        }

    } else {
        $error = "Por favor complete todos los campos requeridos";
    }
}

// Obtener datos del producto de forma segura
$sql = $conn->prepare("SELECT * FROM productos WHERE id = ?");
$sql->bind_param("i", $id);
$sql->execute();
$result = $sql->get_result();
$producto = $result->fetch_assoc();

if (!$producto) {
    header("Location: productos.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto - Fruteria Gourmet</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <div class="container">
        <h1>Editar Producto</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="" class="form-product">
            <div class="form-group">
                <label for="nombre">Nombre *</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="4"><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="precio">Precio *</label>
                <input type="number" id="precio" name="precio" step="0.01" min="0" value="<?php echo $producto['precio']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="tipo_producto_id">Tipo de Producto *</label>
                <select id="tipo_producto_id" name="tipo_producto_id" required>
                    <option value="">Seleccione un tipo</option>
                    <?php while ($tipo = $tipos_productos->fetch_assoc()): ?>
                        <option value="<?php echo $tipo['id']; ?>" 
                                <?php echo ($tipo['id'] == $producto['tipo_producto_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($tipo['nombre']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-group checkbox-group">
                <label>
                    <input type="checkbox" name="disponible" <?php echo $producto['disponible'] ? 'checked' : ''; ?>>
                    Producto disponible
                </label>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Actualizar Producto</button>
                <a href="productos.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
    
    <?php 
    require_once '../includes/footer.php'; // Cambio a require_once
    $conn->close();
    ?>
</body>
</html>
