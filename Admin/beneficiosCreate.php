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
            <h2>Crear Beneficio</h2><hr>
            <form id="beneficioForm">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre de la Empresa</label>
                        <input class="form-control" name="Empresa_Nombre" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Activo</label>
                        <select class="form-control" name="Beneficio_Activo" required>
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Descripción del Beneficio</label>
                        <textarea class="form-control" name="Beneficio_Descripcion" rows="3"></textarea>
                    </div>
                </div>

                <button class="btn btn-secondary" type="submit">
                    <i class="fas fa-save"></i> Guardar Beneficio
                </button>
            </form>
        </div>

        <br> <?php include '../Layout/footer.php'; ?>
    </div>

    <script>
    document.getElementById('beneficioForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const form = e.target;
        const data = {
            Empresa_Nombre: form.Empresa_Nombre.value,
            Beneficio_Descripcion: form.Beneficio_Descripcion.value,
            Beneficio_Activo: parseInt(form.Beneficio_Activo.value)
        };

        const response = await fetch('../PHP/API/beneficios.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.ok) {
            alert('Beneficio creado correctamente.');
            window.location.href = './beneficiosView.php';
        } else {
            alert('Error: ' + (result.error || 'No se pudo crear el beneficio.'));
        }
    });
    </script>
</body>
</html>
