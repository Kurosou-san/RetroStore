<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Retro Store - Registro</title>
  <link rel="icon" type="image/png" href="../Media/Retro.png">
  <!-- Bootstrap 5.3 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Iconos FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="../CSS/styleRegistro.css">
</head>

<body>
  <div class="register-container">
    <form action="../PHP/registrarUsuario.php" method="post">
      <h2 class="text-center mb-4">Registro</h2>

      <div class="mb-3">
        <label for="Usuario_Nombre" class="form-label">Nombre:</label>
        <input type="text" class="form-control" id="Usuario_Nombre" name="Usuario_Nombre" placeholder="Ingresa tu nombre" required>
      </div>

      <div class="mb-3">
        <label for="Usuario_Apellidos" class="form-label">Apellidos:</label>
        <input type="text" class="form-control" id="Usuario_Apellidos" name="Usuario_Apellidos" placeholder="Ingresa tus apellidos" required>
      </div>

      <div class="mb-3">
        <label for="Usuario_Telefono" class="form-label">Número Telefónico:</label>
        <input type="text" class="form-control" id="Usuario_Telefono" name="Usuario_Telefono" placeholder="Ingresa tu número" required>
      </div>

      <div class="mb-3">
        <label for="Usuario_Email" class="form-label">Correo Electrónico:</label>
        <input type="email" class="form-control" id="Usuario_Email" name="Usuario_Email" placeholder="Ingresa tu correo electrónico" required>
      </div>

      <div class="mb-3">
        <label for="Usuario_Contraseña" class="form-label">Contraseña:</label>
        <div class="position-relative">
          <input type="password" class="form-control" id="Usuario_Contraseña" name="Usuario_Contraseña" placeholder="Ingresa tu contraseña" required autocomplete="new-password">
          <button type="button" id="showPasswordBtn" class="password-toggle" onclick="mostrarContrasena()" aria-label="Mostrar u ocultar contraseña">
            <i class="fa fa-eye" id="eyeIcon"></i>
          </button>
        </div>
      </div>

      <div class="d-grid">
        <button type="submit" class="btn btn-primary">Registrarse</button>
      </div>

    </form>
  </div>

  <!-- Otros scripts -->
  <script src="../JS/showPassword.js"></script>
</body>
</html>
