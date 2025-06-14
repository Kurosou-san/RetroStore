<?php include '../PHP/session.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Retro Store - Registrar Cliente</title>
    <?php include '../Layout/documentCDN.html'; ?>
</head>
<body>
    <!-- Inicio del Código -->
    <div class="wrapper">
        <!-- BARRA DE NAVEGACIÓN -->
        <?php include '../Layout/navbar.php'; ?>

        <div class="container mt-4">
            <h2>Registrar Cliente</h2><hr>
            <form action="../PHP/clienteCreate.php" method="POST">
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
                        <select class="form-control" name="Usuario_Genero">
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
                        <input class="form-control" type="date" name="Usuario_FechaNacimiento">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Ciudad</label>
                        <input class="form-control" name="Usuario_Ciudad">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Estado</label>
                        <input class="form-control" name="Usuario_Estado">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Dirección</label>
                        <textarea class="form-control" name="Usuario_Direccion" rows="3"></textarea>
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
