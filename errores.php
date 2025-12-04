<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "PHP está funcionando correctamente<br>";
echo "Versión de PHP: " . phpversion() . "<br>";

// Verificar que el archivo config existe
if (file_exists('config/database.php')) {
    echo "✓ El archivo config/database.php existe<br>";
} else {
    echo "✗ El archivo config/database.php NO existe<br>";
}

// Intentar cargar el archivo
echo "<br>Intentando cargar config/database.php...<br>";
require_once 'config/database.php';
echo "✓ Archivo cargado exitosamente<br>";

// Intentar conectar
echo "<br>Intentando conectar a la base de datos...<br>";
$conn = conectarDB();
echo "✓ Conexión exitosa!<br>";

$conn->close();
?>
```

Accede a `http://localhost/fruteria_gourmet/errores.php`

**Copia aquí TODO lo que aparece** (incluso si son errores).

---

## PASO 4: Mientras tanto, revisemos la configuración

**En XAMPP Control Panel:**
- ¿Apache está en verde con "Running"?
- ¿MySQL está en verde con "Running"?

**Si NO están en verde:**
1. Haz clic en "Start" en cada uno
2. Si sale error, dime qué error aparece

---

## PASO 5: Verifica el navegador

A veces el navegador cachea la página en blanco.

1. Presiona `Ctrl + Shift + R` (recarga forzada)
2. O presiona `F12` → Ve a la pestaña "Console" → ¿Hay algún error?

---

## PASO 6: Verifica la ruta

Confirma que tus archivos estén en:
```
C:\xampp\htdocs\fruteria_gourmet\
├── config\
│   └── database.php
├── login.php
├── hola.php
├── errores.php
└── index.php