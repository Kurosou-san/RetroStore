<?php include '../PHP/session.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Retro Store - Crear Premio</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
    <div class="wrapper">
        <?php include '../Layout/navbar.php'; ?>

        <div class="container mt-4">
            <h2>Crear Premio</h2><hr>
            <form action="../PHP/premioCreate.php" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre del Premio</label>
                        <input class="form-control" name="Premio_Nombre" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Puntos Necesarios</label>
                        <input class="form-control" type="number" name="Premio_PuntosNecesarios" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="Premio_Descripcion" rows="3"></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Disponible</label>
                        <select class="form-control" name="Premio_Disponible" required>
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Imagen</label>
                        <input class="form-control" type="file" name="Premio_Imagen" accept="image/*" onchange="mostrarVistaPrevia(event)">
                        <img id="preview" src="#" alt="Vista previa" style="max-width: 200px; display: none; margin-top: 10px;">
                    </div>
                </div>

                <button class="btn btn-secondary" type="submit">
                    <i class="fas fa-save"></i> Guardar Premio
                </button>
            </form>
        </div>

        <br> <?php include '../Layout/footer.php'; ?>
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
