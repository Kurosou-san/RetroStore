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
    <title>Editar Premio</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
    <div class="wrapper">
        <?php include '../Layout/navbar.php'; ?> <!-- Navbar -->
        <div class="container mt-4">
            <h2>Editar Premio</h2><hr>
            <form id="formEditarPremio" enctype="multipart/form-data">
                <input type="hidden" name="PremioID" value="<?php echo $id; ?>">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input class="form-control" name="Premio_Nombre" value="<?php echo htmlspecialchars($premio['Premio_Nombre']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Puntos Necesarios</label>
                        <input class="form-control" type="number" name="Premio_PuntosNecesarios" value="<?php echo $premio['Premio_PuntosNecesarios']; ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="Premio_Descripcion" rows="3"><?php echo htmlspecialchars($premio['Premio_Descripcion']); ?></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Disponible</label>
                        <select class="form-control" name="Premio_Disponible">
                            <option value="1" <?php echo $premio['Premio_Disponible'] ? 'selected' : ''; ?>>Sí</option>
                            <option value="0" <?php echo !$premio['Premio_Disponible'] ? 'selected' : ''; ?>>No</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Imagen Actual</label><br>
                        <?php if (!empty($premio['Premio_Imagen'])): ?>
                            <img src="<?php echo $premio['Premio_Imagen']; ?>" style="max-width: 200px;">
                        <?php else: ?>
                            <p>No hay imagen registrada.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Nueva Imagen</label>
                        <input class="form-control" type="file" name="Premio_Imagen" accept="image/*" onchange="mostrarVistaPrevia(event)">
                        <img id="preview" src="#" alt="Vista previa" style="max-width: 200px; display: none; margin-top: 10px;">
                    </div>
                </div>

                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </form>
        </div>
        <br><?php include '../Layout/footer.php'; ?> <!-- Footer -->
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

        document.getElementById('formEditarPremio').addEventListener('submit', async function (e) {
            e.preventDefault(); 

            const form = e.target;
            let imagenURL = null;

            // Subir imagen si hay una nueva
            const archivo = form.Premio_Imagen.files[0]; 
            if (archivo) {
                const formDataImg = new FormData();
                formDataImg.append('Premio_Imagen', archivo);
                formDataImg.append('Premio_Nombre', form.Premio_Nombre.value);

                const imgResponse = await fetch('../PHP/API/premiosNuevaImagen.php', {
                    method: 'POST',
                    body: formDataImg
                });

                const imgResult = await imgResponse.json();

                if (!imgResponse.ok) {
                    alert('Error al subir imagen: ' + (imgResult.error || ''));
                    return;
                }

                imagenURL = imgResult.ruta; // Guardar la ruta para luego enviarla
            }

            // Hacer PUT con datos del formulario y ruta de imagen (si existe)
            const data = {
                PremioID: parseInt(form.PremioID.value),
                Premio_Nombre: form.Premio_Nombre.value,
                Premio_Descripcion: form.Premio_Descripcion.value,
                Premio_PuntosNecesarios: parseInt(form.Premio_PuntosNecesarios.value),
                Premio_Disponible: parseInt(form.Premio_Disponible.value),
                Premio_Imagen_Actual: imagenURL 
            };

            const response = await fetch('../PHP/API/premios.php', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (response.ok) {
                alert('Premio actualizado correctamente.');
                window.location.href = './premiosView.php';
            } else {
                alert('Error: ' + (result.error || 'No se pudo actualizar el premio.'));
            }
        });

    </script>
</body>
</html>
