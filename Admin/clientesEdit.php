<?php
    include '../PHP/session.php'; include '../PHP/conexion_BD.php';

    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        echo "<script>alert('ID no válido'); window.location.href = './clientesView.php';</script>";
        exit;
    }

    $id = (int)$_GET['id'];

    $stmt = $conexion->prepare("SELECT Usuario_Nombre, Usuario_Apellidos, Usuario_Email, Usuario_Telefono, Usuario_Contraseña,
     Usuario_Genero, Usuario_FechaNacimiento, Usuario_Ciudad, Usuario_Estado, Usuario_Direccion, 
     Usuario_Puntos, Usuario_Tarjeta FROM Usuarios WHERE UsuarioID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<script>alert('Cliente no encontrado'); window.location.href = './clientesView.php';</script>";
        exit;
    }

    $cliente = $result->fetch_assoc();
    $stmt->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Retro Store - Editar Cliente</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
    <div class="wrapper">
        <?php include '../Layout/navbar.php'; ?> <!-- Navbar -->
        <div class="container mt-4">
            <h2>Editar Cliente</h2><hr>
            <form id="formEditarCliente">
                <input type="hidden" name="UsuarioID" value="<?php echo $id; ?>">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input class="form-control" name="Usuario_Nombre" required value="<?php echo htmlspecialchars($cliente['Usuario_Nombre']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellidos</label>
                        <input class="form-control" name="Usuario_Apellidos" required value="<?php echo htmlspecialchars($cliente['Usuario_Apellidos']); ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Correo Electrónico</label>
                        <input class="form-control" type="email" name="Usuario_Email" required value="<?php echo htmlspecialchars($cliente['Usuario_Email']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input class="form-control" type="text" name="Usuario_Telefono" pattern="\d{10}" maxlength="10" required value="<?php echo htmlspecialchars($cliente['Usuario_Telefono']); ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Fecha de Nacimiento</label>
                        <input class="form-control" type="date" name="Usuario_FechaNacimiento" required value="<?php echo $cliente['Usuario_FechaNacimiento']; ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Género</label>
                        <select class="form-control" name="Usuario_Genero" required>
                            <option value="">Seleccionar</option>
                            <option value="Masculino" <?php if ($cliente['Usuario_Genero'] === 'Masculino') echo 'selected'; ?>>Masculino</option>
                            <option value="Femenino" <?php if ($cliente['Usuario_Genero'] === 'Femenino') echo 'selected'; ?>>Femenino</option>
                            <option value="Otro" <?php if ($cliente['Usuario_Genero'] === 'Otro') echo 'selected'; ?>>Otro</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Contraseña</label>
                        <input class="form-control" type="password" name="Usuario_Contraseña" placeholder="Solo si desea cambiarla">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Ciudad</label>
                        <input class="form-control" name="Usuario_Ciudad" required value="<?php echo htmlspecialchars($cliente['Usuario_Ciudad']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Estado</label>
                        <input class="form-control" name="Usuario_Estado" required value="<?php echo htmlspecialchars($cliente['Usuario_Estado']); ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Dirección</label>
                        <textarea class="form-control" name="Usuario_Direccion" rows="3" required><?php echo htmlspecialchars($cliente['Usuario_Direccion']); ?></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Puntos acumulados</label>
                        <input class="form-control" name="Usuario_Puntos" type="number" value="<?php echo $cliente['Usuario_Puntos']; ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tarjeta</label>
                        <input class="form-control" value="<?php echo $cliente['Usuario_Tarjeta']; ?>" readonly>
                    </div>
                </div>

                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </form>
        </div>
        <br> <?php include '../Layout/footer.php'; ?> <!-- Footer -->
    </div>
    <!-- Script de API Beneficios -->
    <script>
        document.getElementById('formEditarCliente').addEventListener('submit', async function (e) {
            e.preventDefault();

            const form = e.target;

            const data = {
                UsuarioID: parseInt(form.UsuarioID.value),
                Usuario_Nombre: form.Usuario_Nombre.value,
                Usuario_Apellidos: form.Usuario_Apellidos.value,
                Usuario_Email: form.Usuario_Email.value,
                Usuario_Telefono: form.Usuario_Telefono.value,
                Usuario_Contraseña: form.Usuario_Contraseña.value,
                Usuario_Genero: form.Usuario_Genero.value,
                Usuario_FechaNacimiento: form.Usuario_FechaNacimiento.value,
                Usuario_Ciudad: form.Usuario_Ciudad.value,
                Usuario_Estado: form.Usuario_Estado.value,
                Usuario_Direccion: form.Usuario_Direccion.value,
                Usuario_Puntos: form.Usuario_Puntos.value
            };

            const response = await fetch('../PHP/API/clientes.php', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (response.ok) {
                alert('Cliente actualizado correctamente.');
                window.location.href = './clientesView.php';
            } else {
                alert('Error: ' + (result.error || 'No se pudo actualizar el cliente.'));
            }
        });
    </script>
</body>
</html>
