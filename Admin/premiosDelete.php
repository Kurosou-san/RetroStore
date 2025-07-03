<?php
    include '../PHP/session.php'; include '../PHP/conexion_BD.php';

    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        echo "<script>alert('ID no válido'); window.location.href = './premiosView.php';</script>";
        exit;
    }

    $id = (int)$_GET['id'];

    $stmt = $conexion->prepare("SELECT Premio_Nombre, Premio_Descripcion, Premio_PuntosNecesarios, Premio_Disponible, Premio_Imagen 
    FROM Premios WHERE PremioID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<script>alert('Premio no encontrado'); window.location.href = './premiosView.php';</script>";
        exit;
    }

    $premio = $result->fetch_assoc();
    $stmt->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Retro Store - Eliminar Premio</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
    <div class="wrapper">
        <?php include '../Layout/navbar.php'; ?> <!-- Navbar -->
        <div class="container mt-4">
            <h2>Eliminar Premio</h2><hr>
            <form id="formEliminarPremio">
                <input type="hidden" name="PremioID" value="<?php echo $id; ?>">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input class="form-control" name="Premio_Nombre" value="<?php echo htmlspecialchars($premio['Premio_Nombre']); ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Puntos Necesarios</label>
                        <input class="form-control" type="number" name="Premio_PuntosNecesarios" value="<?php echo $premio['Premio_PuntosNecesarios']; ?>" readonly>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="Premio_Descripcion" rows="3" readonly><?php echo htmlspecialchars($premio['Premio_Descripcion']); ?></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Disponible</label>
                        <input class="form-control" value="<?php echo $premio['Premio_Disponible'] ? 'Sí' : 'No'; ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Imagen</label><br>
                        <?php if (!empty($premio['Premio_Imagen'])): ?>
                            <img src="<?php echo $premio['Premio_Imagen']; ?>" style="max-width: 200px;">
                        <?php else: ?>
                            <p>No hay imagen registrada.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mt-4">
                    <button class="btn btn-danger" type="submit">
                        <i class="fas fa-trash-alt"></i> Eliminar Premio
                    </button>
                    <a href="./premiosView.php" class="btn btn-secondary ms-2">Cancelar</a>
                </div>
            </form>
        </div>
        <br> <?php include '../Layout/footer.php'; ?> <!-- Footer -->
    </div>
    <!-- Script de API Premios -->
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

        document.getElementById('formEliminarPremio').addEventListener('submit', async function (e) {
            e.preventDefault();

            const id = parseInt(document.querySelector('input[name="PremioID"]').value);

            const response = await fetch('../PHP/API/premios.php', {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ PremioID: id })
            });

            const result = await response.json();

            if (response.ok) {
                alert('Premio eliminado correctamente.');
                window.location.href = './premiosView.php';
            } else {
                alert('Error: ' + (result.error || 'No se pudo eliminar.'));
            }
        });
    </script>
</body>
</html>