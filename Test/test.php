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