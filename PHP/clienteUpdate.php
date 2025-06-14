<?php
include 'conexion_BD.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuarioID = $_POST['UsuarioID'] ?? '';
    if (empty($usuarioID)) {
        echo "<script>alert('ID de usuario no disponible.'); window.location.href='../Admin/clientes.php';</script>";
        exit;
    }

    // Obtener campos del formulario
    $nombre           = trim($_POST['Usuario_Nombre']);
    $apellidos        = trim($_POST['Usuario_Apellidos']);
    $telefono         = trim($_POST['Usuario_Telefono']);
    $email            = trim($_POST['Usuario_Email']);
    $genero           = $_POST['Usuario_Genero'];
    $fechaNacimiento  = $_POST['Usuario_FechaNacimiento'];
    $direccion        = trim($_POST['Usuario_Direccion']);
    $ciudad           = trim($_POST['Usuario_Ciudad']);
    $estado           = trim($_POST['Usuario_Estado']);
    $puntos           = intval($_POST['Usuario_Puntos']);
    $nuevaContrasena  = $_POST['Usuario_Contraseña'];

    if (!empty($nuevaContrasena)) {
        // Actualiza también la contraseña
        $passwordHash = password_hash($nuevaContrasena, PASSWORD_BCRYPT);

        $sql = "UPDATE Usuarios SET 
            Usuario_Nombre = ?, 
            Usuario_Apellidos = ?, 
            Usuario_Telefono = ?, 
            Usuario_Email = ?, 
            Usuario_Genero = ?, 
            Usuario_FechaNacimiento = ?, 
            Usuario_Direccion = ?, 
            Usuario_Ciudad = ?, 
            Usuario_Estado = ?, 
            Usuario_Contraseña = ?, 
            Usuario_Puntos = ?
            WHERE UsuarioID = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param(
            "ssssssssssii",
            $nombre, $apellidos, $telefono, $email, $genero, $fechaNacimiento,
            $direccion, $ciudad, $estado, $passwordHash, $puntos, $usuarioID
        );
    } else {
        // No se actualiza la contraseña
        $sql = "UPDATE Usuarios SET 
            Usuario_Nombre = ?, 
            Usuario_Apellidos = ?, 
            Usuario_Telefono = ?, 
            Usuario_Email = ?, 
            Usuario_Genero = ?, 
            Usuario_FechaNacimiento = ?, 
            Usuario_Direccion = ?, 
            Usuario_Ciudad = ?, 
            Usuario_Estado = ?, 
            Usuario_Puntos = ?
            WHERE UsuarioID = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param(
            "sssssssssii",
            $nombre, $apellidos, $telefono, $email, $genero, $fechaNacimiento,
            $direccion, $ciudad, $estado, $puntos, $usuarioID
        );
    }

    // Ejecutar y responder
    if ($stmt->execute()) {
        echo "<script>alert('Cliente actualizado correctamente.'); window.location.href='../Admin/clientesView.php';</script>";
    } else {
        echo "<script>alert('Error al actualizar: " . $stmt->error . "'); window.history.back();</script>";
    }

    $stmt->close();
    $conexion->close();
} else {
    echo "<script>alert('Acceso denegado.'); window.location.href='../Admin/clientes.php';</script>";
}
?>
