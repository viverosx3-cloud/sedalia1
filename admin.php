<?php
session_start();
include("conexion.php");

// Validar que sea administrador
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'admin') {
    header("Location: login.php");
    exit();
}

$mensaje = "";

// ELIMINAR PRODUCTO
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conexion->query("DELETE FROM productos WHERE id = $id");
    $mensaje = "<div class='alert alert-success'>Producto eliminado correctamente.</div>";
}

// CREAR O ACTUALIZAR PRODUCTO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id        = $_POST['id'] ?? '';
    $nombre    = trim($_POST['nombre']);
    $precio    = floatval($_POST['precio']);
    $categoria = trim($_POST['categoria']);

    if (!empty($nombre) && !empty($categoria)) {
        if (!empty($id)) {
            // Actualizar (Update)
            $sql = "UPDATE productos SET nombre='$nombre', precio='$precio', categoria='$categoria' WHERE id=$id";
            $accionMensaje = "actualizado";
        } else {
            // Crear (Create)
            $sql = "INSERT INTO productos (nombre, precio, categoria) VALUES ('$nombre', '$precio', '$categoria')";
            $accionMensaje = "creado";
        }

        if ($conexion->query($sql) === TRUE) {
            $mensaje = "<div class='alert alert-success'>Producto $accionMensaje con éxito.</div>";
        } else {
            $mensaje = "<div class='alert alert-danger'>Error al procesar la operación.</div>";
        }
    } else {
        $mensaje = "<div class='alert alert-warning'>Por favor completa los campos obligatorios.</div>";
    }
}

// Obtener producto para editar si se solicita
$productoEditar = ['id' => '', 'nombre' => '', 'precio' => '', 'categoria' => ''];
if (isset($_GET['editar'])) {
    $idEdit = intval($_GET['editar']);
    $resEdit = $conexion->query("SELECT * FROM productos WHERE id = $idEdit");
    if ($resEdit->num_rows > 0) {
        $productoEditar = $resEdit->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sedalia - Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

    <!-- NAVBAR ADMIN -->
    <nav class="navbar navbar-dark bg-dark">
      <div class="container">
        <span class="navbar-brand fw-bold">⚙️ Panel de Administración - Sedalia</span>
        <div class="d-flex align-items-center gap-2">
            <span class="text-light small me-2">Admin: <strong><?php echo $_SESSION['usuario_nombre']; ?></strong></span>
            
            <!-- Botón para el Reporte PDF -->
            <a href="reporte_productos.php" target="_blank" class="btn btn-success btn-sm">
                <i class="bi bi-file-earmark-pdf"></i> Generar Reporte PDF
            </a>
            
            <a href="index.php" class="btn btn-outline-light btn-sm">Ver Catálogo</a>
            <a href="logout.php" class="btn btn-danger btn-sm">Cerrar Sesión</a>
        </div>
      </div>
    </nav>

    <div class="container my-4">
        <?php echo $mensaje; ?>

        <div class="row">
            <!-- FORMULARIO CREAR / EDITAR -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h4 class="fw-bold mb-3"><?php echo !empty($productoEditar['id']) ? 'Editar Producto' : 'Nuevo Producto'; ?></h4>
                        
                        <form method="POST" action="admin.php">
                            <input type="hidden" name="id" value="<?php echo $productoEditar['id']; ?>">
                            
                            <div class="mb-3">
                                <label class="form-label">Nombre del producto</label>
                                <input type="text" name="nombre" class="form-control" value="<?php echo $productoEditar['nombre']; ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Precio (COP)</label>
                                <input type="number" name="precio" class="form-control" value="<?php echo $productoEditar['precio']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Categoría</label>
                                <select name="categoria" class="form-select" required>
                                    <option value="">Seleccione...</option>
                                    <?php
                                    $cats = $conexion->query("SELECT * FROM categorias");
                                    while($c = $cats->fetch_assoc()) {
                                        $selected = ($productoEditar['categoria'] == $c['nombre']) ? 'selected' : '';
                                        echo "<option value='{$c['nombre']}' $selected>{$c['nombre']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-dark w-100">Guardar Producto</button>
                            <?php if (!empty($productoEditar['id'])): ?>
                                <a href="admin.php" class="btn btn-outline-secondary w-100 mt-2">Cancelar Edición</a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>

            <!-- TABLA DE CONSULTA Y ACCIONES (CRUD) -->
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h4 class="fw-bold mb-3">Inventario de Productos</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Precio</th>
                                        <th>Categoría</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $resultado = $conexion->query("SELECT * FROM productos ORDER BY id DESC");
                                    if ($resultado->num_rows > 0) {
                                        while($prod = $resultado->fetch_assoc()) {
                                            ?>
                                            <tr>
                                                <td><?php echo $prod['id']; ?></td>
                                                <td><?php echo $prod['nombre']; ?></td>
                                                <td>$<?php echo number_format($prod['precio'], 0, ',', '.'); ?> COP</td>
                                                <td><span class="badge bg-secondary"><?php echo $prod['categoria']; ?></span></td>
                                                <td>
                                                    <a href="admin.php?editar=<?php echo $prod['id']; ?>" class="btn btn-sm btn-warning text-white"><i class="bi bi-pencil"></i></a>
                                                    <a href="admin.php?eliminar=<?php echo $prod['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este producto?');"><i class="bi bi-trash"></i></a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='5' class='text-center text-muted'>No hay productos registrados.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>