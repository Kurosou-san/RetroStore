<?php 
include '../PHP/session.php'; 
include '../PHP/conexion_BD.php'; 

// Obtener premios disponibles
$sql = "SELECT * FROM Premios WHERE Premio_Disponible = 1 ORDER BY Premio_FechaCreacion DESC";
$result = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Retro Store - Premios Canjeables</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
    <div class="wrapper">
        <?php include '../Layout/navbar.php'; ?>

        <div class="container mt-4">
            <h2>Premios Canjeables</h2>
            <div class="row">
                <?php if ($result->num_rows > 0): ?>
                    <?php while($premio = $result->fetch_assoc()): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <?php if (!empty($premio['Premio_Imagen'])): ?>
                                    <img src="<?= htmlspecialchars($premio['Premio_Imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($premio['Premio_Nombre']) ?>">
                                <?php else: ?>
                                    <img src="/img/default-premio.png" class="card-img-top" alt="Imagen no disponible">
                                <?php endif; ?>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?= htmlspecialchars($premio['Premio_Nombre']) ?></h5>
                                    <p class="card-text"><?= nl2br(htmlspecialchars($premio['Premio_Descripcion'])) ?></p>
                                    <p><strong>Puntos necesarios:</strong> <?= $premio['Premio_PuntosNecesarios'] ?></p>
                                    <form method="POST" action="../PHP/canjearPremio.php" class="mt-auto">
                                        <input type="hidden" name="PremioID" value="<?= $premio['PremioID'] ?>">
                                        <button type="submit" class="btn btn-primary">Canjear premio</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No hay premios disponibles actualmente.</p>
                <?php endif; ?>
            </div>
        </div>

        <br> <?php include '../Layout/footer.php'; ?>
    </div>
</body>
</html>
