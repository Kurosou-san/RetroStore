<?php include '../PHP/session.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Retro Store - Registrar Cliente</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
    <div class="wrapper">
        <?php include '../Layout/navbar.php'; ?> <!-- Navbar -->
        <div class="container mt-4">
            <h2>Registrar Cliente</h2><hr>
            <form id="clienteForm">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input class="form-control" name="Usuario_Nombre" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellidos</label>
                        <input class="form-control" name="Usuario_Apellidos" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Correo Electrónico</label>
                        <input class="form-control" type="email" name="Usuario_Email" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input class="form-control" type="text" name="Usuario_Telefono" pattern="\d{10}" maxlength="10" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Contraseña</label>
                        <input class="form-control" type="password" name="Usuario_Contraseña" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Género</label>
                        <select class="form-control" name="Usuario_Genero" required>
                            <option value="">Seleccionar</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Fecha de Nacimiento</label>
                        <input class="form-control" type="date" name="Usuario_FechaNacimiento" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Ciudad</label>
                        <input class="form-control" name="Usuario_Ciudad" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Estado</label>
                        <input class="form-control" name="Usuario_Estado" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Dirección</label>
                        <textarea class="form-control" name="Usuario_Direccion" rows="3" required></textarea>
                    </div>
                </div>

                <button class="btn btn-secondary" type="submit">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </form>
        </div>
        <br> <?php include '../Layout/footer.php'; ?> <!-- Footer -->
    </div>

    <!-- Script de API Clientes -->
    <script>
        document.getElementById('clienteForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const form = e.target;
            const data = {
                Usuario_Nombre: form.Usuario_Nombre.value,
                Usuario_Apellidos: form.Usuario_Apellidos.value,
                Usuario_Email: form.Usuario_Email.value,
                Usuario_Telefono: form.Usuario_Telefono.value,
                Usuario_Contraseña: form.Usuario_Contraseña.value,
                Usuario_Genero: form.Usuario_Genero.value,
                Usuario_FechaNacimiento: form.Usuario_FechaNacimiento.value,
                Usuario_Ciudad: form.Usuario_Ciudad.value,
                Usuario_Estado: form.Usuario_Estado.value,
                Usuario_Direccion: form.Usuario_Direccion.value
            };

            const response = await fetch('../PHP/API/clientes.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (response.ok) {
                alert('Cliente registrado correctamente.');
                window.location.href = './clientesView.php';
            } else {
                alert('Error: ' + (result.error || 'No se pudo registrar el cliente.'));
            }
        });
    </script>
</body>
</html>
