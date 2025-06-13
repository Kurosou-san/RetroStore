<?php
    include '../PHP/conexion_BD.php';
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!$conexion) {
        die("Conexión fallida: " . mysqli_connect_error());
    }

    $productosPorPagina = 8;
    $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    if ($paginaActual < 1) $paginaActual = 1;
    $offset = ($paginaActual - 1) * $productosPorPagina;

    $terminoBusqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
    $categoriaIDSeleccionada = isset($_GET['categoria']) ? (int)$_GET['categoria'] : 0;

    $minPrecio = isset($_GET['minPrecio']) ? (float)$_GET['minPrecio'] : null;
    $maxPrecio = isset($_GET['maxPrecio']) ? (float)$_GET['maxPrecio'] : null;

    $sqlCategorias = "SELECT CategoriaID, Categoria_Nombre FROM Categorias ORDER BY Categoria_Nombre ASC";
    $categorias = $conexion->query($sqlCategorias)->fetch_all(MYSQLI_ASSOC);

    // Armar consulta de productos
    $sqlBase = "
        SELECT p.*, c.Categoria_Nombre
        FROM Productos p
        INNER JOIN Categorias c ON p.CategoriaID = c.CategoriaID
        WHERE 1=1
    ";

    $params = [];
    $types = "";

    if ($terminoBusqueda !== '') {
        $sqlBase .= " AND p.Producto_Nombre LIKE ?";
        $params[] = "%$terminoBusqueda%";
        $types .= "s";
    }

    if ($categoriaIDSeleccionada > 0) {
        $sqlBase .= " AND p.CategoriaID = ?";
        $params[] = $categoriaIDSeleccionada;
        $types .= "i";
    }

    if (!is_null($minPrecio)) {
        $sqlBase .= " AND p.Producto_Precio >= ?";
        $params[] = $minPrecio;
        $types .= "d";
    }

    if (!is_null($maxPrecio)) {
        $sqlBase .= " AND p.Producto_Precio <= ?";
        $params[] = $maxPrecio;
        $types .= "d";
    }

    // Total de productos
    $sqlCount = "SELECT COUNT(*) AS total FROM ($sqlBase) AS sub";
    $stmtCount = $conexion->prepare($sqlCount);
    if ($types !== "") $stmtCount->bind_param($types, ...$params);
    $stmtCount->execute();
    $totalProductos = $stmtCount->get_result()->fetch_assoc()['total'];
    $totalPaginas = ceil($totalProductos / $productosPorPagina);

    // Productos paginados
    $sqlBase .= " ORDER BY p.Producto_Nombre ASC LIMIT ? OFFSET ?";
    $params[] = $productosPorPagina;
    $params[] = $offset;
    $types .= "ii";

    $stmt = $conexion->prepare($sqlBase);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $productos = $stmt->get_result();
?>