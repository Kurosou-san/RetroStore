<?php
    header("Content-Type: application/json");
    require_once '../../PHP/conexion_BD.php'; // $conexion

    // VIEW
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $query = "SELECT * FROM Productos WHERE ProductosID = $id";
            $result = mysqli_query($conexion, $query);
            echo json_encode(mysqli_fetch_assoc($result));
            exit;
        }

        $search = isset($_GET['search']) ? mysqli_real_escape_string($conexion, $_GET['search']) : '';

        // Consulta solo para contar registros
        if (isset($_GET['count']) && $_GET['count'] === 'true') {
            $query = "SELECT COUNT(*) AS total FROM Productos 
                    WHERE Producto_Nombre LIKE '%$search%'";
            $result = mysqli_query($conexion, $query);
            $row = mysqli_fetch_assoc($result);
            echo json_encode(['total' => intval($row['total'])]);
            exit;
        }

        // Consulta para obtener los registros paginados
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
        $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;

        $query = "SELECT ProductoID, Producto_Nombre, Producto_Codigo, Producto_Descripcion, Producto_Precio, Producto_Stock, Producto_Estado
                FROM Productos 
                WHERE Producto_Nombre LIKE '%$search%' 
                LIMIT $limit OFFSET $offset";

        $result = mysqli_query($conexion, $query);
        $premios = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $premios[] = $row;
        }

        echo json_encode($premios);
        exit;
    }

    // INSERT
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input || !isset($input['Producto_Nombre'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos incompletos o malformateados']);
            exit;
        }

        $codigo      = trim($input['Producto_Codigo']);     
        $nombre      = trim($input['Producto_Nombre']);
        $descripcion = $input['Producto_Descripcion'] ?? null;
        $categoria   = trim($input['Producto_Categoria']);
        $puntaje     = (int)$input['Producto_Puntaje'];
        $precio      = (float)$input['Producto_Precio'];
        $stock       = (int)$input['Producto_Stock'];
        $estado      = trim($input['Producto_Estado'] ?? 'Disponible');
        $imagenRuta  = $input['Producto_Imagen'] ?? null;

        // Insertar en la BD
        $stmt = $conexion->prepare("
            INSERT INTO Productos 
            (Producto_Codigo, Producto_Nombre, Producto_Descripcion, Producto_Categoria, Producto_Puntaje,
            Producto_Precio, Producto_Stock, Producto_Estado, Producto_Imagen)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "ssssiddss", 
            $codigo,
            $nombre,
            $descripcion,
            $categoria,
            $puntaje,
            $precio,
            $stock,
            $estado,
            $imagenRuta
        );

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(['message' => 'Producto creado exitosamente']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al insertar el producto']);
        }

        $stmt->close();
        $conexion->close();
    }

    // UPDATE
    elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        header('Content-Type: application/json');

        $putData = file_get_contents("php://input");
        $putVars = json_decode($putData, true);

        if (!$putVars || !isset($putVars['ProductoID'])) {
            http_response_code(400);
            echo json_encode(['error' => 'ID o datos inválidos']);
            exit;
        }

        // Obtener datos
        $id          = (int)$putVars['ProductoID'];
        $codigo      = trim($putVars['Producto_Codigo'] ?? '');
        $nombre      = trim($putVars['Producto_Nombre'] ?? '');
        $descripcion = $putVars['Producto_Descripcion'] ?? null;
        $categoria   = trim($putVars['Producto_Categoria'] ?? '');
        $puntaje     = (int)($putVars['Producto_Puntaje'] ?? 0);
        $precio      = (float)($putVars['Producto_Precio'] ?? 0);
        $stock       = (int)($putVars['Producto_Stock'] ?? 0);
        $estado      = trim($putVars['Producto_Estado'] ?? 'Disponible');
        $imagenRuta  = $putVars['Producto_Imagen'] ?? null;

        // Validación básica
        if ($nombre === '') {
            http_response_code(422);
            echo json_encode(['error' => 'El nombre del producto es obligatorio']);
            exit;
        }

        // Preparar SQL según presencia de imagen
        if ($imagenRuta !== null) {
            $stmt = $conexion->prepare("
                UPDATE Productos
                SET Producto_Codigo = ?, Producto_Nombre = ?, Producto_Descripcion = ?, Producto_Categoria = ?,
                    Producto_Puntaje = ?, Producto_Precio = ?, Producto_Stock = ?, Producto_Estado = ?, Producto_Imagen = ?
                WHERE ProductoID = ?
            ");
            $stmt->bind_param("ssssiddssi", $codigo, $nombre, $descripcion, $categoria, $puntaje, $precio, $stock, $estado, $imagenRuta, $id);
        } else {
            $stmt = $conexion->prepare("
                UPDATE Productos
                SET Producto_Codigo = ?, Producto_Nombre = ?, Producto_Descripcion = ?, Producto_Categoria = ?,
                    Producto_Puntaje = ?, Producto_Precio = ?, Producto_Stock = ?, Producto_Estado = ?
                WHERE ProductoID = ?
            ");
            $stmt->bind_param("ssssiddsi", $codigo, $nombre, $descripcion, $categoria, $puntaje, $precio, $stock, $estado, $id);
        }

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(['message' => 'Producto actualizado correctamente']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al actualizar el producto']);
        }

        $stmt->close();
        $conexion->close();
    }

    // DELETE
    elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input || !isset($input['ProductoID'])) {
            http_response_code(400);
            echo json_encode(['error' => 'ID no proporcionado']);
            exit;
        }

        $id = (int)$input['ProductoID'];

        $stmt = $conexion->prepare("DELETE FROM Productos WHERE ProductoID = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(['message' => 'Producto eliminado correctamente']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al eliminar el premio']);
        }

        $stmt->close();
        $conexion->close();
    }
?>