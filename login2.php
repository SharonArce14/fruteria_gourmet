<!DOCTYPE html>
<html>
<head>
    <title>Login Simple</title>
</head>
<body>
    <h1>Login Frutería Gourmet</h1>
    
    <form method="POST" action="">
        <p>
            <label>Usuario:</label>
            <input type="text" name="username">
        </p>
        <p>
            <label>Contraseña:</label>
            <input type="password" name="password">
        </p>
        <p>
            <button type="submit">Ingresar</button>
        </p>
    </form>
    
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo "<p>Formulario recibido</p>";
        echo "<p>Usuario: " . $_POST['username'] . "</p>";
        
        require_once 'config/database.php';
        $conn = conectarDB();
        
        $username = $conn->real_escape_string($_POST['username']);
        $password = $_POST['password'];
        
        $sql = "SELECT id, username, password, nombre FROM usuarios WHERE username = '$username'";
        $result = $conn->query($sql);
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nombre'] = $user['nombre'];
                echo "<p style='color:green'>Login exitoso!</p>";
                echo "<p><a href='admin/dashboard.php'>Ir al Dashboard</a></p>";
            } else {
                echo "<p style='color:red'>Contraseña incorrecta</p>";
            }
        } else {
            echo "<p style='color:red'>Usuario no encontrado</p>";
        }
        
        $conn->close();
    }
    ?>
    
    <p><small>Usuario: admin | Contraseña: admin123</small></p>
</body>
</html>