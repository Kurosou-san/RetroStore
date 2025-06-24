<?php
    header("Content-Type: application/json");
    require_once '../../PHP/conexion_BD.php'; // $conexion

    // VIEW
    if ($_SERVER["REQUEST_METHOD"] === "GET") {
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $query = "SELECT * FROM Usuarios WHERE UsuarioID = $id";
            $result = mysqli_query($conexion, $query);
            echo json_encode(mysqli_fetch_assoc($result));
        } else {
            $search = isset($_GET['search']) ? mysqli_real_escape_string($conexion, $_GET['search']) : '';
            $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
            $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;

            $query = "SELECT UsuarioID, Usuario_Nombre, Usuario_Apellidos, Usuario_Email, Usuario_Telefono 
                    FROM Usuarios 
                    WHERE CONCAT(Usuario_Nombre, ' ', Usuario_Apellidos) LIKE '%$search%' 
                    LIMIT $limit OFFSET $offset";

            $result = mysqli_query($conexion, $query);
            $usuarios = [];

            while ($row = mysqli_fetch_assoc($result)) {
                $usuarios[] = $row;
            }

            echo json_encode($usuarios);
        }
    }

    // INSERT
    elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos JSON inválidos']);
            exit;
        }

        $nombre     = trim($input['Usuario_Nombre'] ?? '');
        $apellidos  = trim($input['Usuario_Apellidos'] ?? '');
        $telefono   = trim($input['Usuario_Telefono'] ?? '');
        $email      = trim($input['Usuario_Email'] ?? '');
        $password   = $input['Usuario_Contraseña'] ?? '';
        $genero     = $input['Usuario_Genero'] ?? null;
        $fechaNac   = $input['Usuario_FechaNacimiento'] ?? null;
        $direccion  = $input['Usuario_Direccion'] ?? null;
        $ciudad     = $input['Usuario_Ciudad'] ?? null;
        $estado     = $input['Usuario_Estado'] ?? null;

        if ($nombre === '' || $apellidos === '' || $telefono === '' || $email === '' || $password === '') {
            http_response_code(422);
            echo json_encode(['error' => 'Faltan campos obligatorios']);
            exit;
        }

        $verificarEmail = mysqli_query($conexion, "SELECT 1 FROM Usuarios WHERE Usuario_Email = '$email'");
        if (mysqli_num_rows($verificarEmail) > 0) {
            http_response_code(409);
            echo json_encode(['error' => 'Correo ya registrado']);
            exit;
        }

        $verificarTelefono = mysqli_query($conexion, "SELECT 1 FROM Usuarios WHERE Usuario_Telefono = '$telefono'");
        if (mysqli_num_rows($verificarTelefono) > 0) {
            http_response_code(409);
            echo json_encode(['error' => 'Teléfono ya registrado']);
            exit;
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $puntosIniciales = 250;

        function generarTarjeta($conexion) {
            do {
                $tarjeta = implode('-', array_map(
                    fn() => str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT),
                    range(1, 4)
                ));
                $valida = mysqli_query($conexion, "SELECT 1 FROM Usuarios WHERE Usuario_Tarjeta = '$tarjeta'");
            } while (mysqli_num_rows($valida) > 0);
            return $tarjeta;
        }

        $tarjeta = generarTarjeta($conexion);

        $stmt = $conexion->prepare("
            INSERT INTO Usuarios (
                Usuario_Nombre, Usuario_Apellidos, Usuario_Telefono, Usuario_Email,
                Usuario_Contraseña, Usuario_Genero, Usuario_FechaNacimiento,
                Usuario_Direccion, Usuario_Ciudad, Usuario_Estado,
                Usuario_Tarjeta, Usuario_Puntos
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "sssssssssssi",
            $nombre, $apellidos, $telefono, $email,
            $passwordHash, $genero, $fechaNac,
            $direccion, $ciudad, $estado,
            $tarjeta, $puntosIniciales
        );

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode([
                'message' => 'Cliente creado correctamente',
                'UsuarioID' => $stmt->insert_id,
                'Tarjeta' => $tarjeta,
                'Puntos' => $puntosIniciales
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al insertar el cliente']);
        }

        $stmt->close();
        $conexion->close();
    }

    // UPDATE
    elseif ($_SERVER["REQUEST_METHOD"] === "PUT") {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input || !isset($input['UsuarioID'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos inválidos']);
            exit;
        }

        $id              = (int)$input['UsuarioID'];
        $nombre          = trim($input['Usuario_Nombre'] ?? '');
        $apellidos       = trim($input['Usuario_Apellidos'] ?? '');
        $telefono        = trim($input['Usuario_Telefono'] ?? '');
        $email           = trim($input['Usuario_Email'] ?? '');
        $genero          = $input['Usuario_Genero'] ?? null;
        $fechaNacimiento = $input['Usuario_FechaNacimiento'] ?? null;
        $direccion       = trim($input['Usuario_Direccion'] ?? '');
        $ciudad          = trim($input['Usuario_Ciudad'] ?? '');
        $estado          = trim($input['Usuario_Estado'] ?? '');
        $puntos          = intval($input['Usuario_Puntos'] ?? 0);
        $nuevaPass       = $input['Usuario_Contraseña'] ?? null;

        if ($nombre === '' || $apellidos === '' || $telefono === '' || $email === '') {
            http_response_code(422);
            echo json_encode(['error' => 'Campos obligatorios incompletos']);
            exit;
        }

        if ($nuevaPass) {
            $passHash = password_hash($nuevaPass, PASSWORD_BCRYPT);
            $sql = "UPDATE Usuarios SET 
                Usuario_Nombre=?, Usuario_Apellidos=?, Usuario_Telefono=?, Usuario_Email=?,
                Usuario_Genero=?, Usuario_FechaNacimiento=?, Usuario_Direccion=?,
                Usuario_Ciudad=?, Usuario_Estado=?, Usuario_Contraseña=?, Usuario_Puntos=?
                WHERE UsuarioID=?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ssssssssssii",
                $nombre, $apellidos, $telefono, $email,
                $genero, $fechaNacimiento, $direccion,
                $ciudad, $estado, $passHash, $puntos, $id
            );
        } else {
            $sql = "UPDATE Usuarios SET 
                Usuario_Nombre=?, Usuario_Apellidos=?, Usuario_Telefono=?, Usuario_Email=?,
                Usuario_Genero=?, Usuario_FechaNacimiento=?, Usuario_Direccion=?,
                Usuario_Ciudad=?, Usuario_Estado=?, Usuario_Puntos=?
                WHERE UsuarioID=?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ssssssssii", 
                $nombre, $apellidos, $telefono, $email,
                $genero, $fechaNacimiento, $direccion,
                $ciudad, $estado, $puntos, $id
            );
        }

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(['message' => 'Cliente actualizado correctamente']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al actualizar cliente']);
        }

        $stmt->close();
        $conexion->close();
    }

    // DELETE
    elseif ($_SERVER["REQUEST_METHOD"] === "DELETE") {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input || !isset($input['UsuarioID'])) {
            http_response_code(400);
            echo json_encode(['error' => 'ID no proporcionado']);
            exit;
        }

        $id = (int)$input['UsuarioID'];

        $stmt = $conexion->prepare("DELETE FROM Usuarios WHERE UsuarioID = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(['message' => 'Cliente eliminado correctamente']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al eliminar cliente']);
        }

        $stmt->close();
        $conexion->close();
    }
?>
