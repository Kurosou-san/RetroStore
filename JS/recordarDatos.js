// Cargar datos desde localStorage si el usuario eligió "Recordar datos"
function cargarDatosDesdeLocalStorage() {
    const recordar = localStorage.getItem('recordarDatos') === 'true';
    const telefonoGuardado = localStorage.getItem('telefono');
    const contraseñaGuardada = localStorage.getItem('contraseña');

    if (recordar && telefonoGuardado && contraseñaGuardada) {
        document.getElementById('Usuario_Telefono').value = telefonoGuardado;
        document.getElementById('Usuario_Contraseña').value = contraseñaGuardada;
        document.getElementById('recordarDatos').checked = true;
    }
}

// Guardar datos si el checkbox está marcado
function guardarDatosEnLocalStorage() {
    const recordar = document.getElementById('recordarDatos').checked;
    const telefono = document.getElementById('Usuario_Telefono').value;
    const contraseña = document.getElementById('Usuario_Contraseña').value;

    if (recordar) {
        localStorage.setItem('telefono', telefono);
        localStorage.setItem('contraseña', contraseña);
        localStorage.setItem('recordarDatos', 'true');
    } else {
        localStorage.removeItem('telefono');
        localStorage.removeItem('contraseña');
        localStorage.setItem('recordarDatos', 'false');
    }
}


// Ejecutar al cargar
window.onload = function () {
    cargarDatosDesdeLocalStorage();
};

// Guardar datos antes de validar
document.querySelector('.formLogin').addEventListener('submit', function () {
    guardarDatosEnLocalStorage();
});
