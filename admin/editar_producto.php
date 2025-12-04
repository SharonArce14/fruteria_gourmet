<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../config/database.php';

$conn = conectarDB();

$mensaje = "";
$error = "";
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$id) {
    header("Location: productos.php");
    exit();
}

// Obtener tipos de productos
$query_tipos = "SELECT * FROM tipos_productos ORDER BY nombre";
$tipos_productos = $conn->query($query_tipos);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $tipo_producto_id = intval($_POST['tipo_producto_id']);
    $disponible = isset($_POST['disponible']) ? 1 : 0;
    
    if ($nombre && $precio && $tipo_producto_id) {
        $sql = "UPDATE productos SET 
                nombre='$nombre', 
                descripcion='$descripcion', 
                precio=$precio, 
                tipo_producto_id=$tipo_producto_id, 
                disponible=$disponible 
                WHERE id=$id";
        
        if ($conn->query($sql)) {
            header("Location: productos.php");
            exit();
        } else {
            $error = "Error al actualizar el producto: " . $conn->error;
        }
    } else {
        $error = "Por favor complete todos los campos requeridos";
    }
}

// Obtener datos del producto
$sql = "SELECT * FROM productos WHERE id = $id";
$result = $conn->query($sql);
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
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <h1>Editar Producto</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="" class="form-product">
            <div class="form-group">
                <label for="nombre">Nombre *</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="descripcion">Descripcion</label>
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
    include '../includes/footer.php';
    $conn->close();
    ?>
</body>
</html>