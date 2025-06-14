<?php include '../PHP/session.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Retro Store - Editar Cliente</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
    <div class="wrapper">
        <?php include '../Layout/navbar.php'; ?>
        <div class="container mt-4">
            <h2>Editar Cliente</h2><hr>
            <?php
                include '../PHP/conexion_BD.php';

                if (!isset($_GET['id'])) {
                    die("ID de cliente no especificado.");
                }

                $usuarioID = intval($_GET['id']);
                $query = "SELECT * FROM Usuarios WHERE UsuarioID = $usuarioID";
                $result = mysqli_query($conexion, $query);

                if (!$result || mysqli_num_rows($result) === 0) {
                    die("Cliente no encontrado.");
                }

                $row = mysqli_fetch_assoc($result);
            ?>
            <form action="../PHP/clienteUpdate.php" method="POST">
                <input type="hidden" name="UsuarioID" value="<?php echo $row['UsuarioID']; ?>">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input class="form-control" name="Usuario_Nombre" required value="<?php echo htmlspecialchars($row['Usuario_Nombre']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellidos</label>
                        <input class="form-control" name="Usuario_Apellidos" required value="<?php echo htmlspecialchars($row['Usuario_Apellidos']); ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input class="form-control" name="Usuario_Telefono" required value="<?php echo htmlspecialchars($row['Usuario_Telefono']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input class="form-control" type="email" name="Usuario_Email" required value="<?php echo htmlspecialchars($row['Usuario_Email']); ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Género</label>
                        <select class="form-control" name="Usuario_Genero">
                            <option value="Masculino" <?php if ($row['Usuario_Genero'] === 'Masculino') echo 'selected'; ?>>Masculino</option>
                            <option value="Femenino" <?php if ($row['Usuario_Genero'] === 'Femenino') echo 'selected'; ?>>Femenino</option>
                            <option value="Otro" <?php if ($row['Usuario_Genero'] === 'Otro') echo 'selected'; ?>>Otro</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha de Nacimiento</label>
                        <input class="form-control" type="date" name="Usuario_FechaNacimiento" value="<?php echo $row['Usuario_FechaNacimiento']; ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Dirección</label>
                        <input class="form-control" name="Usuario_Direccion" value="<?php echo htmlspecialchars($row['Usuario_Direccion']); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Ciudad</label>
                        <input class="form-control" name="Usuario_Ciudad" value="<?php echo htmlspecialchars($row['Usuario_Ciudad']); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Estado</label>
                        <input class="form-control" name="Usuario_Estado" value="<?php echo htmlspecialchars($row['Usuario_Estado']); ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Contraseña</label>
                        <input class="form-control" type="password" name="Usuario_Contraseña" placeholder="Solo si desea cambiarla">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tarjeta</label>
                        <input class="form-control" value="<?php echo $row['Usuario_Tarjeta']; ?>" readonly>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Puntos acumulados</label>
                        <input class="form-control" name="Usuario_Puntos" type="number" value="<?php echo $row['Usuario_Puntos']; ?>">
                    </div>
                </div>

                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </form>
        </div>
        <br>
        <?php include '../Layout/footer.php'; ?>
    </div>
</body>
</html>
