<?php
include '../PHP/session.php';
include '../PHP/conexion_BD.php';

// Validar que se proporcione el ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('ID no válido'); window.location.href = './beneficiosView.php';</script>";
    exit;
}

$id = (int)$_GET['id'];

// Obtener los datos actuales del beneficio
$stmt = $conexion->prepare("SELECT Empresa_Nombre, Beneficio_Descripcion, Beneficio_Activo FROM Beneficios WHERE BeneficioID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('Beneficio no encontrado'); window.location.href = './beneficiosView.php';</script>";
    exit;
}

$beneficio = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Retro Store - Editar Beneficio</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
    <div class="wrapper">
        <?php include '../Layout/navbar.php'; ?>

        <div class="container mt-4">
            <h2>Editar Beneficio</h2><hr>
            <form action="../PHP/beneficioUpdate.php" method="POST">
                <input type="hidden" name="BeneficioID" value="<?php echo $id; ?>">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre de la Empresa</label>
                        <input class="form-control" name="Empresa_Nombre" value="<?php echo htmlspecialchars($beneficio['Empresa_Nombre']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Activo</label>
                        <select class="form-control" name="Beneficio_Activo" required>
                            <option value="1" <?php echo $beneficio['Beneficio_Activo'] ? 'selected' : ''; ?>>Sí</option>
                            <option value="0" <?php echo !$beneficio['Beneficio_Activo'] ? 'selected' : ''; ?>>No</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Descripción del Beneficio</label>
                        <textarea class="form-control" name="Beneficio_Descripcion" rows="3"><?php echo htmlspecialchars($beneficio['Beneficio_Descripcion']); ?></textarea>
                    </div>
                </div>

                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </form>
        </div>

        <br> <?php include '../Layout/footer.php'; ?>
    </div>
</body>
</html>
