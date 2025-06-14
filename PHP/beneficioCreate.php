<?php
include 'conexion_BD.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $empresa    = trim($_POST['Empresa_Nombre']);
    $descripcion = $_POST['Beneficio_Descripcion'] ?? null;
    $activo      = (int)$_POST['Beneficio_Activo'];

    $stmt = $conexion->prepare("
        INSERT INTO Beneficios 
        (Empresa_Nombre, Beneficio_Descripcion, Beneficio_Activo) 
        VALUES (?, ?, ?)
    ");

    $stmt->bind_param("ssi", $empresa, $descripcion, $activo);

    if ($stmt->execute()) {
        echo "<script>
                alert('Beneficio creado correctamente.');
                window.location.href = '../Admin/beneficiosView.php';
            </script>";
    } else {
        echo "<script>
                alert('Error al crear el beneficio: " . $stmt->error . "');
                window.history.back();
            </script>";
    }

    $stmt->close();
    $conexion->close();
}
?>
