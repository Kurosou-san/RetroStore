<?php
    header("Content-Type: application/json");
    require_once '../../PHP/conexion_BD.php'; // $conexion

    // VIEW
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $query = "SELECT * FROM Premios WHERE PremioID = $id";
            $result = mysqli_query($conexion, $query);
            echo json_encode(mysqli_fetch_assoc($result));
        } else {
            $search = isset($_GET['search']) ? mysqli_real_escape_string($conexion, $_GET['search']) : '';
            $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
            $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;

            $query = "SELECT PremioID, Premio_Nombre, Premio_Descripcion, Premio_PuntosNecesarios, Premio_Disponible 
                    FROM Premios 
                    WHERE Premio_Nombre LIKE '%$search%' 
                    LIMIT $limit OFFSET $offset";

            $result = mysqli_query($conexion, $query);
            $premios = [];

            while ($row = mysqli_fetch_assoc($result)) {
                $premios[] = $row;
            }

            echo json_encode($premios);
        }
    }

    // INSERT
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input || !isset($input['Premio_Nombre'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos incompletos o malformateados']);
            exit;
        }

        $nombre       = trim($input['Premio_Nombre']);
        $descripcion  = $input['Premio_Descripcion'] ?? null;
        $puntos       = (int)$input['Premio_PuntosNecesarios'];
        $disponible   = (int)$input['Premio_Disponible'];
        $imagenRuta   = $input['Premio_Imagen'] ?? null; // Opcionalmente guarda solo el nombre o base64

        // Insertar en la BD
        $stmt = $conexion->prepare("
            INSERT INTO Premios 
            (Premio_Nombre, Premio_Descripcion, Premio_PuntosNecesarios, Premio_Disponible, Premio_Imagen)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->bind_param("ssiss", $nombre, $descripcion, $puntos, $disponible, $imagenRuta);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(['message' => 'Premio creado exitosamente']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al insertar el premio']);
        }

        $stmt->close();
        $conexion->close();
    }

    // UPDATE
    elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        // Para PUT con FormData se requiere este método
        parse_str(file_get_contents("php://input"), $putVars);

        if (!$putVars || !isset($putVars['PremioID'])) {
            http_response_code(400);
            echo json_encode(['error' => 'ID o datos inválidos']);
            exit;
        }

        $id            = (int)$putVars['PremioID'];
        $nombre        = trim($putVars['Premio_Nombre'] ?? '');
        $descripcion   = $putVars['Premio_Descripcion'] ?? null;
        $puntos        = (int)($putVars['Premio_PuntosNecesarios'] ?? 0);
        $disponible    = (int)($putVars['Premio_Disponible'] ?? 0);
        $imagenRuta    = $putVars['Premio_Imagen_Actual'] ?? null;

        if ($nombre === '') {
            http_response_code(422);
            echo json_encode(['error' => 'El nombre del premio es obligatorio']);
            exit;
        }

        // Procesar imagen si se adjunta una nueva
        if (isset($_FILES['Premio_Imagen']) && $_FILES['Premio_Imagen']['error'] === UPLOAD_ERR_OK) {
            $nombreArchivoOriginal = basename($_FILES['Premio_Imagen']['name']);
            $extension = pathinfo($nombreArchivoOriginal, PATHINFO_EXTENSION);

            $nombreLimpio = preg_replace("/[^a-zA-Z0-9_-]/", "", strtolower(str_replace(" ", "_", $nombre)));
            $nombreFinalArchivo = $nombreLimpio . "." . $extension;

            $rutaDestino = '../../Media/Premios/' . $nombreFinalArchivo;

            if (move_uploaded_file($_FILES['Premio_Imagen']['tmp_name'], $rutaDestino)) {
                $imagenRuta = $rutaDestino;
            }
        }

        if ($imagenRuta !== null) {
            $stmt = $conexion->prepare("
                UPDATE Premios
                SET Premio_Nombre = ?, Premio_Descripcion = ?, Premio_PuntosNecesarios = ?, Premio_Disponible = ?, Premio_Imagen = ?
                WHERE PremioID = ?
            ");
            $stmt->bind_param("ssissi", $nombre, $descripcion, $puntos, $disponible, $imagenRuta, $id);
        } else {
            $stmt = $conexion->prepare("
                UPDATE Premios
                SET Premio_Nombre = ?, Premio_Descripcion = ?, Premio_PuntosNecesarios = ?, Premio_Disponible = ?
                WHERE PremioID = ?
            ");
            $stmt->bind_param("ssiii", $nombre, $descripcion, $puntos, $disponible, $id);
        }

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(['message' => 'Premio actualizado correctamente']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al actualizar el premio']);
        }

        $stmt->close();
        $conexion->close();
    }

    // DELETE
    elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input || !isset($input['PremioID'])) {
            http_response_code(400);
            echo json_encode(['error' => 'ID no proporcionado']);
            exit;
        }

        $id = (int)$input['PremioID'];

        $stmt = $conexion->prepare("DELETE FROM Premios WHERE PremioID = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(['message' => 'Premio eliminado correctamente']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al eliminar el premio']);
        }

        $stmt->close();
        $conexion->close();
    }
?>