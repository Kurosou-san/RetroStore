<?php include '../PHP/session.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Retro Store - Home</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
    <!-- Inicio del Código -->
    <div class="wrapper">
        <!-- BARRA DE NAVEGACIÓN -->
        <?php include '../Layout/navbar.php'; ?>
        <?php
            include '../PHP/conexion_BD.php'; // Conexión a la base de datos

            // Consulta para obtener los beneficios activos
            $sql = "SELECT Empresa_Nombre, Beneficio_Descripcion FROM Beneficios WHERE Beneficio_Activo = 1 ORDER BY Beneficio_FechaCreacion DESC";
            $resultado = $conexion->query($sql);
        ?>
        <div class="container mt-4">
            <div class="row mt-4">
                <?php if ($resultado && $resultado->num_rows > 0): ?>
                    <?php while ($row = $resultado->fetch_assoc()): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($row['Empresa_Nombre']); ?></h5>
                                    <p class="card-text"><?php echo nl2br(htmlspecialchars($row['Beneficio_Descripcion'])); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info" role="alert">
                            No hay empresas con beneficios disponibles en este momento.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- PIE DE PÁGINA -->
        <br> <?php include '../Layout/footer.php'; ?>
    </div>

    <!-- Fin del Código -->
    <!-- Scritps Adicionales -->
</body>
</html>
