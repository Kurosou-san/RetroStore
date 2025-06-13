// Cargar correo y contraseña desde localStorage si el usuario eligió "Recordarme"
function cargarDatosDesdeLocalStorage() {
    const recordar = localStorage.getItem('recordarDatos') === 'true';
    const correoGuardado = localStorage.getItem('correo');
    const contraseñaGuardada = localStorage.getItem('contraseña');

    if (recordar && correoGuardado && contraseñaGuardada) {
        document.getElementById('Usuario_Email').value = correoGuardado;
        document.getElementById('Usuario_Contraseña').value = contraseñaGuardada;
        document.getElementById('recordarDatos').checked = true;
    }
}

// Guardar datos en localStorage solo si el checkbox está marcado
function guardarDatosEnLocalStorage() {
    const recordar = document.getElementById('recordarDatos').checked;
    const correo = document.getElementById('Usuario_Email').value;
    const contraseña = document.getElementById('Usuario_Contraseña').value;

    if (recordar) {
        localStorage.setItem('correo', correo);
        localStorage.setItem('contraseña', contraseña);
        localStorage.setItem('recordarDatos', 'true');
    } else {
        localStorage.removeItem('correo');
        localStorage.removeItem('contraseña');
        localStorage.setItem('recordarDatos', 'false');
    }
}

// Mostrar u ocultar contraseña
function mostrarContrasena() {
    const contraseñaInput = document.getElementById('Usuario_Contraseña');
    const showPasswordBtn = document.getElementById('showPasswordBtn');

    if (contraseñaInput.type === "password") {
        contraseñaInput.type = "text";
        showPasswordBtn.textContent = "Ocultar";
    } else {
        contraseñaInput.type = "password";
        showPasswordBtn.textContent = "Mostrar";
    }
}

// Al cargar la página
window.onload = function () {
    cargarDatosDesdeLocalStorage();
};

// Guardar datos al enviar el formulario
document.querySelector('.formLogin').addEventListener('submit', function () {
    guardarDatosEnLocalStorage();
});
