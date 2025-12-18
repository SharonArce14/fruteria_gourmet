# Taller Calidad Software - Frutería Gourmet



Sistema de gestión para una frutería gourmet desarrollado en PHP puro.

## Descripción del Proyecto

Sistema web que permite gestionar productos y tipos de productos de una frutería gourmet. Incluye funcionalidades de autenticación, dashboard administrativo, operaciones CRUD completas y un catálogo público con carrito de compras para clientes.

## Tecnologías Utilizadas

- **Backend:** PHP 8.2.12
- **Base de Datos:** MySQL 8.0
- **Frontend:** HTML5, CSS3, JavaScript
- **Servidor:** Apache 2.4.58
- **Control de Versiones:** Git & GitHub
- **Herramientas de Calidad:** SonarQube Cloud

## Características Principales

### Panel de Administración
- ✅ Sistema de autenticación segura con contraseñas encriptadas
- ✅ Dashboard administrativo con estadísticas
- ✅ CRUD completo de productos (Crear, Leer, Actualizar, Eliminar)
- ✅ Gestión de tipos de productos
- ✅ 10 productos predefinidos
- ✅ 2 categorías de productos
- ✅ Relación de base de datos uno a muchos

### Vista del Cliente
- ✅ Catálogo público de productos
- ✅ Carrito de compras funcional
- ✅ Sistema de registro de clientes
- ✅ Diseño responsive

## Estructura del Proyecto
```
fruteria_gourmet/
├── config/
│   └── database.php              # Configuración de base de datos
├── admin/
│   ├── dashboard.php             # Panel principal de administración
│   ├── productos.php             # Listado de productos
│   ├── crear_producto.php        # Crear nuevo producto
│   ├── editar_producto.php       # Editar producto existente
│   ├── eliminar_producto.php     # Eliminar producto
│   └── tipos_productos.php       # Gestión de tipos/categorías
├── assets/
│   └── css/
│       └── style.css             # Estilos del sistema
├── includes/
│   ├── header.php                # Encabezado común
│   └── footer.php                # Pie de página común
├── login.php                     # Inicio de sesión (admin)
├── logout.php                    # Cerrar sesión
├── index.php                     # Catálogo público y carrito
├── registro_cliente.php          # Registro de clientes
└── README.md                     # Este archivo
```

## Base de Datos

### Tablas principales:

**usuarios** - Administradores del sistema
- id, username, password, nombre, fecha_creacion

**clientes** - Clientes registrados
- id, nombre, email, telefono, direccion, password, fecha_registro

**tipos_productos** - Categorías de productos (relación 1:N)
- id, nombre, descripcion, fecha_creacion

**productos** - Productos del catálogo
- id, nombre, descripcion, precio, tipo_producto_id (FK), imagen, disponible, fecha_creacion

### Cardinalidad:
- **Uno a Muchos:** Un tipo de producto puede tener muchos productos

## Instalación

### Requisitos previos:
- XAMPP o WAMP con PHP 8.2+
- MySQL 8.0+
- Navegador web moderno

### Pasos de instalación:

1. **Clonar el repositorio:**
```bash
git clone https://github.com/TU_USUARIO/taller_calidad_software_[tu_nombre].git
```

2. **Mover a la carpeta de XAMPP:**
```bash
# Copiar la carpeta a:
C:\xampp\htdocs\fruteria_gourmet\
```

3. **Crear la base de datos:**
- Abrir phpMyAdmin: `http://localhost/phpmyadmin`
- Ejecutar el script SQL ubicado en `database.sql`

4. **Configurar la conexión:**
- Abrir `config/database.php`
- Verificar las credenciales de la base de datos

5. **Acceder al sistema:**
- Vista pública: `http://localhost/fruteria_gourmet/`
- Panel admin: `http://localhost/fruteria_gourmet/login.php`

## Credenciales de Acceso

### Administrador:
- **Usuario:** admin
- **Contraseña:** admin123

## Funcionalidades CRUD

### Crear (Create):
- Agregar nuevos productos desde `admin/crear_producto.php`
- Agregar tipos de productos desde `admin/tipos_productos.php`

### Leer (Read):
- Visualizar lista de productos en `admin/productos.php`
- Ver dashboard con estadísticas en `admin/dashboard.php`

### Actualizar (Update):
- Editar productos desde `admin/editar_producto.php`

### Eliminar (Delete):
- Eliminar productos desde `admin/eliminar_producto.php`
- Eliminar tipos de productos desde `admin/tipos_productos.php`

## Validaciones y Seguridad

- ✅ Contraseñas encriptadas con `password_hash()`
- ✅ Validación de datos con `filter_input()` y `real_escape_string()`
- ✅ Protección de sesiones
- ✅ Sanitización de entradas HTML con `htmlspecialchars()`

## Análisis de Calidad

Este proyecto ha sido analizado con **SonarQube Cloud** para identificar:
- Bugs
- Vulnerabilidades de seguridad
- Code smells
- Duplicación de código
- Cobertura de código

## Autor

[charon arce]

## Licencia

 Taller de Calidad de Software
