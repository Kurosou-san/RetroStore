<?php
    include 'conexion_BD.php'; // Conexión a la base de datos

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Obtener el UsuarioID desde el formulario
        $usuarioID = $_POST['UsuarioID'] ?? '';

        if (empty($usuarioID)) {
            echo "<script>alert('El ID del usuario no está disponible.'); 
            window.location.href='../HTML/editarPerfil.php';</script>";
            exit;
        }

        $nombre = trim($_POST['Usuario_Nombre']);
        $apellidos = trim($_POST['Usuario_Apellidos']);
        $telefono = trim($_POST['Usuario_Telefono']);
        $email = trim($_POST['Usuario_Email']);
        $genero = $_POST['Usuario_Genero'];
        $fechaNacimiento = $_POST['Usuario_FechaNacimiento'];
        $direccion = trim($_POST['Usuario_Direccion']);
        $ciudad = trim($_POST['Usuario_Ciudad']);
        $estado = trim($_POST['Usuario_Estado']);

        $sql = "UPDATE Usuarios SET 
            Usuario_Nombre = ?, 
            Usuario_Apellidos = ?, 
            Usuario_Telefono = ?, 
            Usuario_Email = ?, 
            Usuario_Genero = ?, 
            Usuario_FechaNacimiento = ?, 
            Usuario_Direccion = ?, 
            Usuario_Ciudad = ?, 
            Usuario_Estado = ?
            WHERE UsuarioID = ?";

        $params = [$nombre, $apellidos, $telefono, $email, $genero, $fechaNacimiento, $direccion, $ciudad, $estado, $usuarioID];
        $types = "sssssssss" . "i"; // Total: 9 's' + 1 'i' = 10 caracteres



        $stmt = $conexion->prepare($sql);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            echo "<script>alert('Perfil actualizado correctamente.'); window.location.href='../HTML/perfil.php';</script>";
        } else {
            echo "Error al actualizar el perfil: " . $stmt->error;
        }

        $stmt->close();
        $conexion->close();

    } else {
        echo "<script>alert('Acceso no permitido.'); window.location.href='../HTML/editarPerfil.php';</script>";
    }
?>
