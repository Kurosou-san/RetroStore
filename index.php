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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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

      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="recordarDatos">
        <label class="form-check-label" for="recordarDatos">
          Recordar mis datos
        </label>
      </div>

      <div class="d-grid">
        <button type="button" class="btn btn-primary" onclick="validarFormulario()">Iniciar Sesión</button>
      </div>
    </form>
  </div>

  <!-- Modal de Voz -->
  <div class="modal fade" id="voiceModal" tabindex="-1" aria-labelledby="voiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content text-center p-4">
        <h5 class="modal-title mb-3" id="voiceModalLabel" style="color: black;">Verificación por Voz</h5>
        <i class="fa-solid fa-microphone fa-3x mb-3 text-primary" style="cursor:pointer" id="startMic"></i>
        <p id="voiceStatus" style="color: rgb(126, 126, 122);">Di la palabra: <strong>"Ingresar"</strong></p>
        <p class="text-muted" id="resultMessage"></p>
      </div>
    </div>
  </div>


  <!-- Otros scripts -->
  <script src="./JS/recordarDatos.js"></script>
  <script src="./JS/showPassword.js"></script>
  <script src="./JS/comandoVoz.js"></script>
</body>
</html>
