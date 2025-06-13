<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Retro Store - Inicio de Sesión</title>
  <link rel="icon" type="image/png" href="./Media/Retro.png">
  <!-- Bootstrap 5.3 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Iconos FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="./CSS/styleIndex.css">
</head>

<body>
  <div class="login-container">
    <form action="./PHP/iniciarSesion.php" method="post">
      <h2 class="text-center mb-4">Iniciar Sesión</h2>

      <div class="mb-3">
        <label for="Usuario_Telefono" class="form-label">Número Telefónico:</label>
        <input type="text" class="form-control" id="Usuario_Telefono" name="Usuario_Telefono" placeholder="Ingresa tu número" required>
      </div>

      <div class="mb-3">
        <label for="Usuario_Contraseña" class="form-label">Contraseña:</label>
        <div class="position-relative">
          <input type="password" class="form-control" id="Usuario_Contraseña" name="Usuario_Contraseña" placeholder="Ingresa tu contraseña" required autocomplete="current-password">
          <button type="button" id="showPasswordBtn" class="password-toggle" onclick="mostrarContrasena()" aria-label="Mostrar u ocultar contraseña">
            <i class="fa fa-eye" id="eyeIcon"></i>
          </button>
        </div>
      </div>

      <div class="d-grid">
        <button type="submit" name="btnLogin" class="btn btn-primary">Iniciar Sesión</button>
      </div>

    </form>
  </div>

  <!-- Otros scripts -->
  <script src="./JS/recordarDatos.js"></script>
  <script src="./JS/showPassword.js"></script>
</body>
</html>
