<?php include '../PHP/session.php'; include '../PHP/datosPerfil.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Retro Store - Perfil</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
    <!-- Inicio del Código -->
    <div class="wrapper">
        <!-- BARRA DE NAVEGACIÓN -->
        <?php include '../Layout/navbar.php'; ?>

        <div class="container mt-4">
            <h3>Datos Personales</h3><hr>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nombre</label>
                    <input type="text" class="form-control" 
                        value="<?php echo htmlspecialchars($usuario['Usuario_Nombre'] ?? ''); ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Apellidos</label>
                    <input type="text" class="form-control" 
                        value="<?php echo htmlspecialchars($usuario['Usuario_Apellidos'] ?? ''); ?>" readonly>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Teléfono</label>
                    <input type="text" class="form-control" 
                        value="<?php echo htmlspecialchars($usuario['Usuario_Telefono'] ?? ''); ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" 
                        value="<?php echo htmlspecialchars($usuario['Usuario_Email'] ?? ''); ?>" readonly>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Género</label>
                    <input type="text" class="form-control" 
                        value="<?php echo htmlspecialchars($usuario['Usuario_Genero'] ?? ''); ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" 
                        value="<?php echo htmlspecialchars($usuario['Usuario_FechaNacimiento'] ?? ''); ?>" readonly>
                </div>
            </div>

            <h3>Datos de Ubicación</h3><hr>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Dirección</label>
                    <input type="text" class="form-control" 
                        value="<?php echo htmlspecialchars($usuario['Usuario_Direccion'] ?? ''); ?>" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Ciudad</label>
                    <input type="text" class="form-control" 
                        value="<?php echo htmlspecialchars($usuario['Usuario_Ciudad'] ?? ''); ?>" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Estado</label>
                    <input type="text" class="form-control" 
                        value="<?php echo htmlspecialchars($usuario['Usuario_Estado'] ?? ''); ?>" readonly>
                </div>
            </div>

            <a href="./editarPerfil.php" class="btn btn-secondary mt-3">
                <i class="fas fa-user-edit"></i> Editar Perfil
            </a>
        </div>

        <div class="container mt-4">
            <h3>Tarjeta Retro</h3><hr>
            <div class="card text-white" style="background-color: #3c3c52; max-width: 400px; border: none; border-radius: 10px;">
                <div class="card-body">
                    <h5 class="card-title">Número de Tarjeta</h5>
                    <p class="card-text fs-5">
                        <?php echo htmlspecialchars($usuario['Usuario_Tarjeta'] ?? 'No disponible'); ?>
                    </p>

                    <h5 class="card-title mt-3">Puntos</h5>
                    <p class="card-text fs-5">
                        <?php echo htmlspecialchars($usuario['Usuario_Puntos'] ?? '0'); ?>
                    </p>
                </div>
            </div>
        </div>



        <!-- PIE DE PÁGINA -->
        <br> <?php include '../Layout/footer.php'; ?>
    </div>

    <!-- Fin del Código -->
    <!-- Scritps Adicionales -->
</body>
</html>
