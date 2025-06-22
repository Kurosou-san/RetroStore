<?php
include '../PHP/session.php';
include '../PHP/conexion_BD.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('ID no válido'); window.location.href = './beneficiosView.php';</script>";
    exit;
}

$id = (int)$_GET['id'];

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
            <form id="formEditarBeneficio">
                <input type="hidden" name="BeneficioID" value="<?php echo $id; ?>">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre de la Empresa</label>
                        <input class="form-control" name="Empresa_Nombre" required value="<?php echo htmlspecialchars($beneficio['Empresa_Nombre']); ?>">
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

    <script>
    document.getElementById('formEditarBeneficio').addEventListener('submit', async function (e) {
        e.preventDefault();

        const form = e.target;

        const data = {
            BeneficioID: parseInt(form.BeneficioID.value),
            Empresa_Nombre: form.Empresa_Nombre.value,
            Beneficio_Descripcion: form.Beneficio_Descripcion.value,
            Beneficio_Activo: parseInt(form.Beneficio_Activo.value)
        };

        const response = await fetch('../PHP/API/beneficios.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.ok) {
            alert('Beneficio actualizado correctamente.');
            window.location.href = './beneficiosView.php';
        } else {
            alert('Error: ' + (result.error || 'No se pudo actualizar el beneficio.'));
        }
    });
    </script>
</body>
</html>
