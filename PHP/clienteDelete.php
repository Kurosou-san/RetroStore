<?php
    include 'conexion_BD.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = intval($_POST['UsuarioID']);

        $stmt = $conexion->prepare("DELETE FROM Usuarios WHERE UsuarioID = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Cliente eliminado correctamente.');
                    window.location.href = '../Admin/clientesView.php';
                </script>";
        } else {
            echo "<script>
                    alert('Error al eliminar el cliente: " . $stmt->error . "');
                    window.history.back();
                </script>";
        }

        $stmt->close();
        $conexion->close();
    } else {
        echo "<script>
                alert('Acceso denegado.');
                window.location.href = '../Admin/clientesView.php';
            </script>";
    }
?>