<?php
include 'conexion_BD.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = (int)$_POST['PremioID'];

    // Obtener ruta de imagen para eliminarla si existe
    $queryImagen = "SELECT Premio_Imagen FROM Premios WHERE PremioID = ?";
    $stmtImagen = $conexion->prepare($queryImagen);
    $stmtImagen->bind_param("i", $id);
    $stmtImagen->execute();
    $stmtImagen->bind_result($imagenRuta);
    $stmtImagen->fetch();
    $stmtImagen->close();

    // Eliminar registro
    $stmt = $conexion->prepare("DELETE FROM Premios WHERE PremioID = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if ($imagenRuta && file_exists($imagenRuta)) {
            unlink($imagenRuta); // Borra la imagen del servidor
        }

        echo "<script>
                alert('Premio eliminado correctamente.');
                window.location.href = '../Admin/premiosView.php';
              </script>";
    } else {
        echo "<script>
                alert('Error al eliminar el premio: " . $stmt->error . "');
                window.history.back();
              </script>";
    }

    $stmt->close();
    $conexion->close();
}
?>
