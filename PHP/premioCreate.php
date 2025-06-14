<?php
include 'conexion_BD.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre       = trim($_POST['Premio_Nombre']);
    $descripcion  = $_POST['Premio_Descripcion'] ?? null;
    $puntos       = (int)$_POST['Premio_PuntosNecesarios'];
    $disponible   = (int)$_POST['Premio_Disponible'];

    $imagenRuta = null;

    if (isset($_FILES['Premio_Imagen']) && $_FILES['Premio_Imagen']['error'] === UPLOAD_ERR_OK) {
        $nombreArchivoOriginal = basename($_FILES['Premio_Imagen']['name']);
        $extension = pathinfo($nombreArchivoOriginal, PATHINFO_EXTENSION);

        // Usar Premio_Nombre como nombre de archivo (limpiado)
        $nombreLimpio = preg_replace("/[^a-zA-Z0-9_-]/", "", strtolower(str_replace(" ", "_", $nombre)));
        $nombreFinalArchivo = $nombreLimpio . "." . $extension;

        $rutaDestino = '../Media/Premios/' . $nombreFinalArchivo;

        if (move_uploaded_file($_FILES['Premio_Imagen']['tmp_name'], $rutaDestino)) {
            $imagenRuta = $rutaDestino;
        }
    }

    $stmt = $conexion->prepare("
        INSERT INTO Premios 
        (Premio_Nombre, Premio_Descripcion, Premio_PuntosNecesarios, Premio_Disponible, Premio_Imagen) 
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("ssiss", $nombre, $descripcion, $puntos, $disponible, $imagenRuta);

    if ($stmt->execute()) {
        echo "<script>
                alert('Premio creado correctamente.');
                window.location.href = '../Admin/premiosView.php';
            </script>";
    } else {
        echo "<script>
                alert('Error al crear el premio: " . $stmt->error . "');
                window.history.back();
            </script>";
    }

    $stmt->close();
    $conexion->close();
}
?>
