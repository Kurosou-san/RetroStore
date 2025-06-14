<?php include '../PHP/session.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Retro Store - Eliminar Cliente</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
    <div class="wrapper">
        <?php include '../Layout/navbar.php'; ?>
        <div class="container mt-4">
            <h2>Eliminar Cliente</h2><hr>
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
            <form action="../PHP/clienteDelete.php" method="POST">
                <input type="hidden" name="UsuarioID" value="<?php echo $row['UsuarioID']; ?>">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input class="form-control" readonly value="<?php echo htmlspecialchars($row['Usuario_Nombre']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellidos</label>
                        <input class="form-control" readonly value="<?php echo htmlspecialchars($row['Usuario_Apellidos']); ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input class="form-control" readonly value="<?php echo htmlspecialchars($row['Usuario_Telefono']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input class="form-control" type="email" readonly value="<?php echo htmlspecialchars($row['Usuario_Email']); ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Género</label>
                        <input class="form-control" readonly value="<?php echo $row['Usuario_Genero']; ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha de Nacimiento</label>
                        <input class="form-control" type="date" readonly value="<?php echo $row['Usuario_FechaNacimiento']; ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Dirección</label>
                        <input class="form-control" readonly value="<?php echo htmlspecialchars($row['Usuario_Direccion']); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Ciudad</label>
                        <input class="form-control" readonly value="<?php echo htmlspecialchars($row['Usuario_Ciudad']); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Estado</label>
                        <input class="form-control" readonly value="<?php echo htmlspecialchars($row['Usuario_Estado']); ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Tarjeta</label>
                        <input class="form-control" readonly value="<?php echo htmlspecialchars($row['Usuario_Tarjeta']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Puntos acumulados</label>
                        <input class="form-control" readonly value="<?php echo $row['Usuario_Puntos']; ?>">
                    </div>
                </div>

                <div class="mt-4">
                    <button class="btn btn-danger" type="submit">
                        <i class="fas fa-trash-alt"></i> Eliminar Cliente
                    </button>
                    <a href="../Admin/clientesView.php" class="btn btn-secondary ms-2">Cancelar</a>
                </div>
            </form>
        </div>
        <br>
        <?php include '../Layout/footer.php'; ?>
    </div>
</body>
</html>
