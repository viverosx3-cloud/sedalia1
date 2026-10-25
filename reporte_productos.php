<?php
session_start();
include("conexion.php");

// Validar que sea administrador
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'admin') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Inventario - Sedalia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body class="bg-white p-5" onload="window.print()">

    <div class="container">
        <div class="text-center mb-4">
            <h2 class="fw-bold">🌀 Sedalia - Reporte de Inventario</h2>
            <p class="text-muted">Listado oficial de productos registrados en el sistema</p>
            <p class="small text-secondary">Fecha de generación: <?php echo date("Y-m-d H:i:s"); ?></p>
        </div>

        <table class="table table-bordered align-middle">
            <table class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre del Producto</th>
                        <th>Categoría</th>
                        <th>Precio (COP)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $resultado = $conexion->query("SELECT * FROM productos ORDER BY id ASC");
                    if ($resultado->num_rows > 0) {
                        while($prod = $resultado->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$prod['id']}</td>
                                    <td>{$prod['nombre']}</td>
                                    <td>{$prod['categoria']}</td>
                                    <td>\$" . number_format($prod['precio'], 0, ',', '.') . " COP</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center'>No hay productos registrados.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </table>

        <div class="mt-5 text-end no-print">
            <button onclick="window.print()" class="btn btn-dark">Imprimir / Guardar como PDF</button>
            <a href="admin.php" class="btn btn-outline-secondary">Regresar al Panel</a>
        </div>
    </div>

</body>
</html>