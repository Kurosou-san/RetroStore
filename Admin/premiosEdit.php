<?php include '../PHP/session.php';?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Editar Premio</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
<div class="wrapper">
    <?php include '../Layout/navbar.php'; ?>

    <div class="container mt-4">
        <h2>Editar Premio</h2><hr>
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
        <form action="../PHP/premioUpdate.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="PremioID" value="<?php echo $premio['PremioID']; ?>">

            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input class="form-control" name="Premio_Nombre" value="<?php echo htmlspecialchars($premio['Premio_Nombre']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea class="form-control" name="Premio_Descripcion" rows="3"><?php echo htmlspecialchars($premio['Premio_Descripcion']); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Puntos Necesarios</label>
                <input class="form-control" type="number" name="Premio_PuntosNecesarios" value="<?php echo $premio['Premio_PuntosNecesarios']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Disponible</label>
                <select class="form-control" name="Premio_Disponible">
                    <option value="1" <?php echo $premio['Premio_Disponible'] ? 'selected' : ''; ?>>Sí</option>
                    <option value="0" <?php echo !$premio['Premio_Disponible'] ? 'selected' : ''; ?>>No</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Imagen Actual</label><br>
                <?php if (!empty($premio['Premio_Imagen'])): ?>
                    <img src="<?php echo $premio['Premio_Imagen']; ?>" style="max-width: 200px;">
                <?php else: ?>
                    <p>No hay imagen registrada.</p>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Nueva Imagen (opcional)</label>
                <input class="form-control" type="file" name="Premio_Imagen" accept="image/*" onchange="mostrarVistaPrevia(event)">
                <img id="preview" src="#" alt="Vista previa" style="max-width: 200px; display: none; margin-top: 10px;">
            </div>

            <button class="btn btn-primary" type="submit">
                <i class="fas fa-save"></i> Guardar Cambios
            </button>
        </form>
    </div>

    <br><?php include '../Layout/footer.php'; ?>
</div>

<script>
    function mostrarVistaPrevia(event) {
        const input = event.target;
        const preview = document.getElementById('preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
</body>
</html>
