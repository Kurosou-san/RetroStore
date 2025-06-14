<?php
include 'session.php';
include 'conexion_BD.php';

if (!isset($_SESSION['usuario_id'])) {
    echo '<script>
        alert("Por favor, inicie sesión para acceder a esta página.");
        window.location = "../index.php";
    </script>';
    exit;
}

$usuarioID = $_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['PremioID'])) {
    $premioID = intval($_POST['PremioID']);

    // Obtener datos del premio
    $sqlPremio = "SELECT Premio_PuntosNecesarios, Premio_Disponible FROM Premios WHERE PremioID = ?";
    $stmtPremio = $conexion->prepare($sqlPremio);
    $stmtPremio->bind_param("i", $premioID);
    $stmtPremio->execute();
    $resultadoPremio = $stmtPremio->get_result();

    if ($resultadoPremio->num_rows === 0) {
        $_SESSION['mensaje_error'] = "Premio no encontrado.";
        echo '<script>
            alert("' . $_SESSION['mensaje_error'] . '");
            window.location = "../HTML/premios.php";
        </script>';
        exit;
    }

    $premio = $resultadoPremio->fetch_assoc();

    if (!$premio['Premio_Disponible']) {
        $_SESSION['mensaje_error'] = "El premio no está disponible para canje.";
        echo '<script>
            alert("' . $_SESSION['mensaje_error'] . '");
            window.location = "../HTML/premios.php";
        </script>';
        exit;
    }

    $puntosNecesarios = intval($premio['Premio_PuntosNecesarios']);

    // Obtener puntos del usuario
    $sqlUsuario = "SELECT Usuario_Puntos FROM Usuarios WHERE UsuarioID = ?";
    $stmtUsuario = $conexion->prepare($sqlUsuario);
    $stmtUsuario->bind_param("i", $usuarioID);
    $stmtUsuario->execute();
    $resultadoUsuario = $stmtUsuario->get_result();

    if ($resultadoUsuario->num_rows === 0) {
        $_SESSION['mensaje_error'] = "Usuario no encontrado.";
        echo '<script>
            alert("' . $_SESSION['mensaje_error'] . '");
            window.location = "../HTML/premios.php";
        </script>';
        exit;
    }

    $usuario = $resultadoUsuario->fetch_assoc();
    $puntosUsuario = intval($usuario['Usuario_Puntos']);

    if ($puntosUsuario < $puntosNecesarios) {
        $_SESSION['mensaje_error'] = "No tienes suficientes puntos para canjear este premio.";
        echo '<script>
            alert("' . $_SESSION['mensaje_error'] . '");
            window.location = "../HTML/premios.php";
        </script>';
        exit;
    }

    $conexion->begin_transaction();

    try {
        $sqlRestarPuntos = "UPDATE Usuarios SET Usuario_Puntos = Usuario_Puntos - ? WHERE UsuarioID = ?";
        $stmtRestar = $conexion->prepare($sqlRestarPuntos);
        $stmtRestar->bind_param("ii", $puntosNecesarios, $usuarioID);
        $stmtRestar->execute();

        if ($stmtRestar->affected_rows === 0) {
            throw new Exception("Error al actualizar puntos del usuario.");
        }

        $sqlInsertCanje = "INSERT INTO Canjes (UsuarioID, PremioID) VALUES (?, ?)";
        $stmtInsert = $conexion->prepare($sqlInsertCanje);
        $stmtInsert->bind_param("ii", $usuarioID, $premioID);
        $stmtInsert->execute();

        if ($stmtInsert->affected_rows === 0) {
            throw new Exception("Error al registrar el canje.");
        }

        $conexion->commit();

        $_SESSION['mensaje_exito'] = "Premio canjeado con éxito.";
        echo '<script>
            alert("' . $_SESSION['mensaje_exito'] . '");
            window.location = "../HTML/premios.php";
        </script>';
        exit;

    } catch (Exception $e) {
        $conexion->rollback();
        $_SESSION['mensaje_error'] = "Error al canjear premio: " . $e->getMessage();
        echo '<script>
            alert("' . $_SESSION['mensaje_error'] . '");
            window.location = "../HTML/premios.php";
        </script>';
        exit;
    }

} else {
    echo '<script>
        alert("Acceso incorrecto.");
        window.location = "../HTML/premios.php";
    </script>';
    exit;
}
