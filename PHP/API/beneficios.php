<?php
    header("Content-Type: application/json");
    require_once '../../PHP/conexion_BD.php'; // $conexion

    // VIEW
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $query = "SELECT * FROM Beneficios WHERE BeneficioID = $id";
            $result = mysqli_query($conexion, $query);
            echo json_encode(mysqli_fetch_assoc($result));
        } else {
            $search = isset($_GET['search']) ? mysqli_real_escape_string($conexion, $_GET['search']) : '';
            $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
            $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;

            $query = "SELECT BeneficioID, Empresa_Nombre, Beneficio_Descripcion, Beneficio_Activo 
                    FROM Beneficios 
                    WHERE Empresa_Nombre LIKE '%$search%' 
                    LIMIT $limit OFFSET $offset";

            $result = mysqli_query($conexion, $query);
            $beneficios = [];

            while ($row = mysqli_fetch_assoc($result)) {
                $beneficios[] = $row;
            }

            echo json_encode($beneficios);
        }
    }

    // INSERT
    elseif ($method === 'POST') {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos JSON inválidos']);
            exit;
        }

        $empresa     = trim($input['Empresa_Nombre'] ?? '');
        $descripcion = $input['Beneficio_Descripcion'] ?? null;
        $activo      = (int)($input['Beneficio_Activo'] ?? 0);

        if ($empresa === '') {
            http_response_code(422);
            echo json_encode(['error' => 'El nombre de la empresa es obligatorio']);
            exit;
        }

        $stmt = $conexion->prepare("
            INSERT INTO Beneficios 
            (Empresa_Nombre, Beneficio_Descripcion, Beneficio_Activo)
            VALUES (?, ?, ?)
        ");

        $stmt->bind_param("ssi", $empresa, $descripcion, $activo);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(['message' => 'Beneficio creado exitosamente']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al insertar en la base de datos']);
        }

        $stmt->close();
        $conexion->close();
    }

    // UPDATE
    elseif ($method === 'PUT') {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input || !isset($input['BeneficioID'])) {
            http_response_code(400);
            echo json_encode(['error' => 'ID o datos inválidos']);
            exit;
        }

        $id          = (int)$input['BeneficioID'];
        $empresa     = trim($input['Empresa_Nombre'] ?? '');
        $descripcion = $input['Beneficio_Descripcion'] ?? null;
        $activo      = (int)($input['Beneficio_Activo'] ?? 0);

        if ($empresa === '') {
            http_response_code(422);
            echo json_encode(['error' => 'El nombre de la empresa es obligatorio']);
            exit;
        }

        $stmt = $conexion->prepare("
            UPDATE Beneficios
            SET Empresa_Nombre = ?, Beneficio_Descripcion = ?, Beneficio_Activo = ?
            WHERE BeneficioID = ?
        ");

        $stmt->bind_param("ssii", $empresa, $descripcion, $activo, $id);

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(['message' => 'Beneficio actualizado correctamente']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al actualizar el beneficio']);
        }

        $stmt->close();
        $conexion->close();
    }

    // DELETE
    elseif ($method === 'DELETE') {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input || !isset($input['BeneficioID'])) {
            http_response_code(400);
            echo json_encode(['error' => 'ID no proporcionado']);
            exit;
        }

        $id = (int)$input['BeneficioID'];

        $stmt = $conexion->prepare("DELETE FROM Beneficios WHERE BeneficioID = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(['message' => 'Beneficio eliminado correctamente']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al eliminar el beneficio']);
        }

        $stmt->close();
        $conexion->close();
    }
?>