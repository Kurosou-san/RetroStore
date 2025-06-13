<?php include '../PHP/session.php'; include '../PHP/datosPerfil.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Retro Store - Editar Perfil</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
    <!-- Inicio del Código -->
    <div class="wrapper">
        <!-- BARRA DE NAVEGACIÓN -->
        <?php include '../Layout/navbar.php'; ?>

        <div class="container mt-4">
            <form action="../PHP/actualizarPerfil.php" method="POST" enctype="multipart/form-data" id="formPerfil">
                <h3>Datos Personales</h3><hr>

                <input type="hidden" name="UsuarioID" value="<?php echo htmlspecialchars($usuario['UsuarioID']); ?>">
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="Usuario_Nombre" class="form-control" 
                               value="<?php echo htmlspecialchars($usuario['Usuario_Nombre'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellidos</label>
                        <input type="text" name="Usuario_Apellidos" class="form-control" 
                               value="<?php echo htmlspecialchars($usuario['Usuario_Apellidos'] ?? ''); ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input type="tel" name="Usuario_Telefono" class="form-control" 
                               value="<?php echo htmlspecialchars($usuario['Usuario_Telefono'] ?? ''); ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="Usuario_Email" class="form-control" 
                               value="<?php echo htmlspecialchars($usuario['Usuario_Email'] ?? ''); ?>" readonly>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Género</label>
                        <select name="Usuario_Genero" class="form-select" required>
                            <option value="">Seleccionar</option>
                            <option value="Masculino" <?php if(($usuario['Usuario_Genero'] ?? '') === 'Masculino') echo 'selected'; ?>>Masculino</option>
                            <option value="Femenino" <?php if(($usuario['Usuario_Genero'] ?? '') === 'Femenino') echo 'selected'; ?>>Femenino</option>
                            <option value="Otro" <?php if(($usuario['Usuario_Genero'] ?? '') === 'Otro') echo 'selected'; ?>>Otro</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha de Nacimiento</label>
                        <input type="date" name="Usuario_FechaNacimiento" class="form-control" 
                               value="<?php echo htmlspecialchars($usuario['Usuario_FechaNacimiento'] ?? ''); ?>" required>
                    </div>
                </div>

                <h3>Datos de Ubicación</h3><hr>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="Usuario_Direccion" class="form-control" 
                               value="<?php echo htmlspecialchars($usuario['Usuario_Direccion'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Ciudad</label>
                        <input type="text" name="Usuario_Ciudad" class="form-control" 
                               value="<?php echo htmlspecialchars($usuario['Usuario_Ciudad'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Estado</label>
                        <input type="text" name="Usuario_Estado" class="form-control" 
                               value="<?php echo htmlspecialchars($usuario['Usuario_Estado'] ?? ''); ?>" required>
                    </div>
                </div>

                <h3>Contraseña</h3><hr>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nueva contraseña</label>
                        <input type="password" name="Usuario_Contrasena" class="form-control" placeholder="Nueva contraseña (Opcional)">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirmar nueva contraseña</label>
                        <input type="password" name="Usuario_Contrasena2" class="form-control" placeholder="Confirmar contraseña">
                    </div>
                </div>

                <button class="btn btn-secondary" type="submit">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </form>
        </div>

        <!-- PIE DE PÁGINA -->
        <br> <?php include '../Layout/footer.php'; ?>
    </div>

    <!-- Fin del Código -->
    <!-- Scritps Adicionales -->
</body>
</html>
