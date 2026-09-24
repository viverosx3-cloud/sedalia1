<?php
session_start();
include("conexion.php");

$secciones = ['Shampoo', 'Acondicionador', 'Crema para peinar', 'Gel'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sedalia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

    <!-- BARRA DE NAVEGACIÓN -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
      <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">🌀 Sedalia</a>
        
        <div class="ms-auto d-flex align-items-center gap-2">
            <?php if (isset($_SESSION['usuario_nombre'])): ?>
                <!-- Si hay sesión iniciada -->
                <span class="text-light small me-2">Hola, <strong><?php echo $_SESSION['usuario_nombre']; ?></strong></span>
                <a href="logout.php" class="btn btn-outline-light btn-sm">Cerrar Sesión</a>
            <?php else: ?>
                <!-- Si NO hay sesión iniciada -->
                <a href="login.php" class="btn btn-outline-light btn-sm">Iniciar Sesión</a>
            <?php endif; ?>

            <a href="https://wa.me/1234567890" target="_blank" class="btn btn-success btn-sm">
                <i class="bi bi-whatsapp"></i> WhatsApp
            </a>
        </div>
      </div>
    </nav>

    <!-- MÓDULO DE CATÁLOGO POR SECCIONES -->
    <div class="container my-5">
        <?php foreach ($secciones as $seccion): ?>
            
            <div class="d-flex align-items-center my-4">
                <h3 class="fw-bold text-dark m-0"><?php echo $seccion; ?></h3>
                <hr class="flex-grow-1 ms-3">
            </div>

            <div class="row">
                <?php
                $sql = "SELECT * FROM productos WHERE categoria = '$seccion'";
                $resultado = $conexion->query($sql);

                if ($resultado->num_rows > 0) {
                    while($producto = $resultado->fetch_assoc()) {
                        ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title fw-bold"><?php echo $producto['nombre']; ?></h5>
                                    <p class="card-text fw-bold text-success fs-4 mb-3">$<?php echo number_format($producto['precio'], 0, ',', '.'); ?> COP</p>
                                    
                                    <div class="mt-auto">
                                        <?php if (isset($_SESSION['usuario_id'])): ?>
                                            <button class="btn btn-outline-danger w-100">
                                                <i class="bi bi-heart"></i> Agregar a Favoritos
                                            </button>
                                        <?php else: ?>
                                            <a href="login.php?msg=favoritos" class="btn btn-outline-danger w-100">
                                                <i class="bi bi-heart"></i> Agregar a Favoritos
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p class='text-muted ms-3'>No hay productos en esta sección.</p>";
                }
                ?>
            </div>

        <?php endforeach; ?>
    </div>

</body>
</html>