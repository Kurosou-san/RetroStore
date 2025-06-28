<?php
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
        exit;
    }

    // Validar que exista el archivo
    if (!isset($_FILES['Premio_Imagen']) || $_FILES['Premio_Imagen']['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo json_encode(['error' => 'Imagen no recibida o con error']);
        exit;
    }

    $nombrePremio = $_POST['Premio_Nombre'] ?? 'premio';
    $nombreLimpio = preg_replace("/[^a-zA-Z0-9_-]/", "", strtolower(str_replace(" ", "_", $nombrePremio)));

    // Validaciones de seguridad del archivo
    $permitidas = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $mime = mime_content_type($_FILES['Premio_Imagen']['tmp_name']);

    if (!in_array($mime, $permitidas)) {
        http_response_code(415);
        echo json_encode(['error' => 'Formato de imagen no permitido']);
        exit;
    }

    $extension = pathinfo($_FILES['Premio_Imagen']['name'], PATHINFO_EXTENSION);
    $nombreFinal = $nombreLimpio . "_" . time() . "." . $extension;

    $rutaRelativa = '../Media/Premios/' . $nombreFinal;
    $rutaAbsoluta = realpath(__DIR__ . '/../../Media/Premios') . '/' . $nombreFinal;

    if (!move_uploaded_file($_FILES['Premio_Imagen']['tmp_name'], $rutaAbsoluta)) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al mover el archivo']);
        exit;
    }

    echo json_encode(['ruta' => $rutaRelativa]); 
?>