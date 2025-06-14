<?php include '../PHP/session.php';?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Eliminar Premio</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
<div class="wrapper">
    <?php include '../Layout/navbar.php'; ?>

    <div class="container mt-4">
        <h2>Eliminar Premio</h2><hr>
        <?php
            include '../PHP/conexion_BD.php';

            $id = $_GET['id'] ?? null;
            if (!$id) {
                echo "<script>alert('ID de premio no proporcionado.'); window.history.back();</script>";
                exit;
            }

            $query = "SELECT * FROM Premios WHERE PremioID = ?";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $premio = $result->fetch_assoc();

            if (!$premio) {
                echo "<script>alert('Premio no encontrado.'); window.history.back();</script>";
                exit;
            }
        ?>
        <form action="../PHP/premioDelete.php" method="POST">
            <input type="hidden" name="PremioID" value="<?php echo $premio['PremioID']; ?>">

            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input class="form-control" name="Premio_Nombre" value="<?php echo htmlspecialchars($premio['Premio_Nombre']); ?>" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea class="form-control" name="Premio_Descripcion" rows="3" readonly><?php echo htmlspecialchars($premio['Premio_Descripcion']); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Puntos Necesarios</label>
                <input class="form-control" type="number" name="Premio_PuntosNecesarios" value="<?php echo $premio['Premio_PuntosNecesarios']; ?>" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Disponible</label>
                <input class="form-control" value="<?php echo $premio['Premio_Disponible'] ? 'Sí' : 'No'; ?>" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Imagen</label><br>
                <?php if (!empty($premio['Premio_Imagen'])): ?>
                    <img src="<?php echo $premio['Premio_Imagen']; ?>" style="max-width: 200px;">
                <?php else: ?>
                    <p>No hay imagen registrada.</p>
                <?php endif; ?>
            </div>

            <div class="mt-4">
                <button class="btn btn-danger" type="submit">
                    <i class="fas fa-trash-alt"></i> Eliminar Premio
                </button>
                <a href="../Admin/premiosView.php" class="btn btn-secondary ms-2">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    <br><?php include '../Layout/footer.php'; ?>
</div>
</body>
</html>
