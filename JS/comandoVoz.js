
function validarFormulario() {
  const telefono = document.getElementById('Usuario_Telefono').value.trim();
  const contrasena = document.getElementById('Usuario_Contraseña').value.trim();

  if (!telefono || !contrasena) {
    alert("Por favor, completa todos los campos.");
    return;
  }

  // Validación OK → enviar por fetch para validar en backend
  const formData = new FormData();
  formData.append("Usuario_Telefono", telefono);
  formData.append("Usuario_Contraseña", contrasena);

  fetch('./PHP/iniciarSesion.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.text())
  .then(data => {
    if (data.includes("¡Bienvenido")) {
      // Mostrar modal de voz
      const modal = new bootstrap.Modal(document.getElementById('voiceModal'));
      modal.show();
    } else {
      // Error de credenciales
      document.write(data);
    }
  })
  .catch(error => {
    console.error('Error:', error);
  });
}

document.getElementById("startMic").addEventListener("click", () => {
  const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
  recognition.lang = 'es-ES';
  recognition.interimResults = false;
  recognition.maxAlternatives = 1;

  document.getElementById("voiceStatus").textContent = "Escuchando...";

  recognition.onresult = function(event) {
    const transcript = event.results[0][0].transcript.trim().toLowerCase();
    const esperado = "ingresar";

    if (transcript === esperado) {
      document.getElementById("resultMessage").textContent = "Frase correcta. Redirigiendo...";
      setTimeout(() => {
        window.location.href = "./HTML/index.php";
      }, 1500);
    } else {
      document.getElementById("resultMessage").textContent = "Frase incorrecta. Intenta de nuevo.";
      document.getElementById("voiceStatus").textContent = 'Di la palabra: "Ingresar"';
    }
  };

  recognition.onerror = function() {
    document.getElementById("resultMessage").textContent = "Error al reconocer voz. Intenta otra vez.";
    document.getElementById("voiceStatus").textContent = 'Di la palabra: "Ingresar"';
  };

  recognition.start();
});

