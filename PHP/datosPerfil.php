<?php
    include 'conexion_BD.php'; // Usar $conexion

    // Obtener el ID del usuario desde la sesión
    $usuarioID = $_SESSION['usuario_id'];

    // Consulta para obtener los datos del usuario
    $query = "SELECT * FROM Usuarios WHERE UsuarioID = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "i", $usuarioID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Obtener los datos en un array asociativo
    $usuario = mysqli_fetch_assoc($result);

    // Cerrar la consulta
   mysqli_stmt_close($stmt);
?>