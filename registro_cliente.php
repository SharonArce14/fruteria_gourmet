<?php
session_start();
require_once 'config/database.php';

$mensaje = "";
$error = "";
$exito = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = conectarDB();
    
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $email = $conn->real_escape_string($_POST['email']);
    $telefono = $conn->real_escape_string($_POST['telefono']);
    $direccion = $conn->real_escape_string($_POST['direccion']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    
    // Validaciones
    if (empty($nombre) || empty($email) || empty($password)) {
        $error = "Por favor complete todos los campos obligatorios";
    } elseif ($password !== $password_confirm) {
        $error = "Las contraseñas no coinciden";
    } elseif (strlen($password) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres";
    } else {
        // Verificar si el email ya existe
        $check_email = $conn->query("SELECT id FROM clientes WHERE email = '$email'");
        
        if ($check_email->num_rows > 0) {
            $error = "Este correo electrónico ya está registrado";
        } else {
            // Encriptar contraseña
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            // Insertar cliente
            $sql = "INSERT INTO clientes (nombre, email, telefono, direccion, password) 
                    VALUES ('$nombre', '$email', '$telefono', '$direccion', '$password_hash')";
            
            if ($conn->query($sql)) {
                $exito = true;
                $mensaje = "¡Registro exitoso! Ya puedes realizar tus compras.";
            } else {
                $error = "Error al registrar: " . $conn->error;
            }
        }
    }
    
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Cliente - Fruteria Gourmet</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .register-container {
            width: 100%;
            max-width: 500px;
        }

        .register-box {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .register-box h1 {
            text-align: center;
            color: #667eea;
            margin-bottom: 10px;
            font-size: 32px;
        }

        .register-box h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 20px;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        .alert-error {
            background-color: #fee;
            color: #c33;
            border: 2px solid #fcc;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 2px solid #c3e6cb;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }

        .required {
            color: #ff4757;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .btn {
            width: 100%;
            padding: 14px;
            background-color: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn:hover {
            background-color: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .back-link a:hover {
            color: #5568d3;
        }

        .success-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .success-actions a {
            flex: 1;
            text-align: center;
            padding: 12px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-secondary {
            background: #4CAF50;
            color: white;
        }

        .btn-primary:hover {
            background: #5568d3;
        }

        .btn-secondary:hover {
            background: #45a049;
        }

        .password-hint {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-box">
            <h1>🍓 Frutería Gourmet</h1>
            <h2>Registro de Cliente</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($exito): ?>
                <div class="alert alert-success">
                    <h3>✅ ¡Registro Exitoso!</h3>
                    <p><?php echo $mensaje; ?></p>
                </div>
                <div class="success-actions">
                    <a href="index.php" class="btn-secondary">Ver Catálogo</a>
                </div>
            <?php else: ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="nombre">Nombre Completo <span class="required">*</span></label>
                        <input type="text" id="nombre" name="nombre" required 
                               value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Correo Electrónico <span class="required">*</span></label>
                        <input type="email" id="email" name="email" required 
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="tel" id="telefono" name="telefono" 
                               value="<?php echo isset($_POST['telefono']) ? htmlspecialchars($_POST['telefono']) : ''; ?>"
                               placeholder="Ej: 3001234567">
                    </div>
                    
                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <textarea id="direccion" name="direccion" 
                                  placeholder="Calle, número, barrio, ciudad"><?php echo isset($_POST['direccion']) ? htmlspecialchars($_POST['direccion']) : ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Contraseña <span class="required">*</span></label>
                        <input type="password" id="password" name="password" required minlength="6">
                        <p class="password-hint">Mínimo 6 caracteres</p>
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirm">Confirmar Contraseña <span class="required">*</span></label>
                        <input type="password" id="password_confirm" name="password_confirm" required minlength="6">
                    </div>
                    
                    <button type="submit" class="btn">Registrarse</button>
                </form>
            <?php endif; ?>
            
            <div class="back-link">
                <a href="index.php">← Volver al catálogo</a>
            </div>
        </div>
    </div>
</body>
</html>
```

