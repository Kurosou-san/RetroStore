<?php
    session_start();

    // Verificar si el usuario está logueado
    if (!isset($_SESSION['usuario_id'])) {
        echo '<script>
                alert("Por favor, inicie sesión para acceder a esta página.");
                window.location = "../index.php";
            </script>';
        exit;
    }

    // Obtener información del usuario logueado
    $usuarioID = $_SESSION['usuario_id']; // Nuevo: ID del usuario
    $nombre = $_SESSION['usuario_nombre'] ?? ''; 
    $apellido = $_SESSION['usuario_apellido'] ?? ''; 
    $rol = $_SESSION['usuario_rol'] ?? ''; 

    // Definir roles
    $esAdmin = ($rol === 'Administrador');
    $esCliente = ($rol === 'Cliente');

    // Configurar clase para el color del navbar
    $navbarClass = ($esAdmin || $esCliente) ? 'navbar-dark bg-dark' : 'navbar-light bg-secondary';
?>
