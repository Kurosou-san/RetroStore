<?php include '../PHP/session.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Retro Store - Catalogo de productos</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
    <!-- Inicio del Código -->
    <div class="wrapper">
        <!-- BARRA DE NAVEGACIÓN -->
        <?php include '../Layout/navbar.php'; ?>

        <div class="container mt-4">
            <div class="container mt-4">
                <!-- -->
                <?php include '../Layout/catalogoProductos.php'; ?>
                <?php
                    $conexion->close();
                ?>
                <br>
            </div>
        </div>

        <!-- PIE DE PÁGINA -->
        <?php include '../Layout/footer.php'; ?>
    </div>

    <!-- Fin del Código -->
    <!-- Scritps Adicionales -->
</body>
</html>
