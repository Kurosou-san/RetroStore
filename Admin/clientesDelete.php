<?php
    include '../PHP/session.php'; include '../PHP/conexion_BD.php';

    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        echo "<script>alert('ID no válido'); window.location.href = './clientesView.php';</script>";
        exit;
    }

    $id = (int)$_GET['id'];

    $stmt = $conexion->prepare("SELECT Usuario_Nombre, Usuario_Apellidos, Usuario_Email, Usuario_Telefono,
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
    <title>Retro Store - Eliminar Cliente</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
    <div class="wrapper">
        <?php include '../Layout/navbar.php'; ?> <!-- Navbar -->
        <div class="container mt-4">
            <h2>Eliminar Cliente</h2><hr>
            <form id="formEliminarCliente">
                <input type="hidden" name="UsuarioID" value="<?php echo $id; ?>">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input class="form-control" name="Usuario_Nombre" value="<?php echo htmlspecialchars($cliente['Usuario_Nombre']); ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellidos</label>
                        <input class="form-control" name="Usuario_Apellidos" value="<?php echo htmlspecialchars($cliente['Usuario_Apellidos']); ?>" readonly>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Correo Electrónico</label>
                        <input class="form-control" type="email" name="Usuario_Email" value="<?php echo htmlspecialchars($cliente['Usuario_Email']); ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input class="form-control" type="text" name="Usuario_Telefono" value="<?php echo htmlspecialchars($cliente['Usuario_Telefono']); ?>" readonly>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Fecha de Nacimiento</label>
                        <input class="form-control" type="date" name="Usuario_FechaNacimiento" value="<?php echo $cliente['Usuario_FechaNacimiento']; ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Género</label>
                        <select class="form-control" name="Usuario_Genero" disabled>
                            <option value="">Seleccionar</option>
                            <option value="Masculino" <?php if ($cliente['Usuario_Genero'] === 'Masculino') echo 'selected'; ?>>Masculino</option>
                            <option value="Femenino" <?php if ($cliente['Usuario_Genero'] === 'Femenino') echo 'selected'; ?>>Femenino</option>
                            <option value="Otro" <?php if ($cliente['Usuario_Genero'] === 'Otro') echo 'selected'; ?>>Otro</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Ciudad</label>
                        <input class="form-control" name="Usuario_Ciudad" value="<?php echo htmlspecialchars($cliente['Usuario_Ciudad']); ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Estado</label>
                        <input class="form-control" name="Usuario_Estado" value="<?php echo htmlspecialchars($cliente['Usuario_Estado']); ?>" readonly>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Dirección</label>
                        <textarea class="form-control" name="Usuario_Direccion" rows="3" readonly><?php echo htmlspecialchars($cliente['Usuario_Direccion']); ?></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Puntos acumulados</label>
                        <input class="form-control" name="Usuario_Puntos" type="number" value="<?php echo $cliente['Usuario_Puntos']; ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tarjeta</label>
                        <input class="form-control" value="<?php echo $cliente['Usuario_Tarjeta']; ?>" readonly>
                    </div>
                </div>

                <div class="mt-4">
                    <button class="btn btn-danger" type="submit">
                        <i class="fas fa-trash-alt"></i> Eliminar Cliente
                    </button>
                    <a href="./clientesView.php" class="btn btn-secondary ms-2">Cancelar</a>
                </div>
            </form>
        </div>
        <br> <?php include '../Layout/footer.php'; ?> <!-- Footer -->
    </div>
    <!-- Script de API Clientes -->
    <script>
        document.getElementById('formEliminarCliente').addEventListener('submit', async function (e) {
            e.preventDefault();

            const id = parseInt(document.querySelector('input[name="UsuarioID"]').value);

            const response = await fetch('../PHP/API/clientes.php', {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ UsuarioID: id })
            });

            const result = await response.json();

            if (response.ok) {
                alert('Cliente eliminado correctamente.');
                window.location.href = './clientesView.php';
            } else {
                alert('Error: ' + (result.error || 'No se pudo eliminar.'));
            }
        });
    </script>
</body>
</html>