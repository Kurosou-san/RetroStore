<?php include '../PHP/session.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Retro Store - Crear Beneficio</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
    <div class="wrapper">
        <?php include '../Layout/navbar.php'; ?>

        <div class="container mt-4">
            <h2>Crear Premio</h2><hr>
            <form id="premioForm">
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
        
        document.getElementById('premioForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const form = e.target;
            const data = {
                Premio_Nombre: form.Premio_Nombre.value,
                Premio_Descripcion: form.Premio_Descripcion.value,
                Premio_PuntosNecesarios: form.Premio_PuntosNecesarios.value,
                Premio_Disponible: parseInt(form.Premio_Disponible.value),
                Premio_Imagen: form.Premio_Imagen.value
            };

            const response = await fetch('../PHP/API/premios.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (response.ok) {
                alert('Premio creado correctamente.');
                window.location.href = './premiosView.php';
            } else {
                alert('Error: ' + (result.error || 'No se pudo crear el premio.'));
            }
        });
    </script>
</body>
</html>
