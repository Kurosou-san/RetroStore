<?php
    include '../PHP/conexion_BD.php';

    $productosPorPagina = 8;
    $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    if ($paginaActual < 1) $paginaActual = 1;
    $offset = ($paginaActual - 1) * $productosPorPagina;

    $terminoBusqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
    $categoriaSeleccionada = isset($_GET['categoria']) ? $_GET['categoria'] : '';

    $minPrecio = isset($_GET['minPrecio']) ? (float)$_GET['minPrecio'] : null;
    $maxPrecio = isset($_GET['maxPrecio']) ? (float)$_GET['maxPrecio'] : null;

    $minPrecioMostrar = ($minPrecio === null) ? 'MIN' : $minPrecio;
    $maxPrecioMostrar = ($maxPrecio === null) ? 'MAX' : $maxPrecio;

    $sqlBase = "SELECT * FROM Productos WHERE Producto_Precio BETWEEN ? AND ?";
    $params = [$minPrecio ?? 0, $maxPrecio ?? PHP_INT_MAX];
    $types = "dd";

    if ($terminoBusqueda !== '') {
        $sqlBase .= " AND Producto_Nombre LIKE ?";
        $params[] = "%$terminoBusqueda%";
        $types .= "s";
    }

    if ($categoriaSeleccionada !== '') {
        $sqlBase .= " AND Producto_Categoria = ?";
        $params[] = $categoriaSeleccionada;
        $types .= "s";
    }

    $sqlBase .= " ORDER BY Producto_Nombre ASC LIMIT ? OFFSET ?";
    $params[] = $productosPorPagina;
    $params[] = $offset;
    $types .= "ii";

    $stmt = $conexion->prepare($sqlBase);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Total de productos para paginación
    $sqlCount = "SELECT COUNT(*) AS total FROM Productos WHERE Producto_Precio BETWEEN ? AND ?";
    $paramsCount = [$minPrecio ?? 0, $maxPrecio ?? PHP_INT_MAX];
    $typesCount = "dd";

    if ($terminoBusqueda !== '') {
        $sqlCount .= " AND Producto_Nombre LIKE ?";
        $paramsCount[] = "%$terminoBusqueda%";
        $typesCount .= "s";
    }
    if ($categoriaSeleccionada !== '') {
        $sqlCount .= " AND Producto_Categoria = ?";
        $paramsCount[] = $categoriaSeleccionada;
        $typesCount .= "s";
    }

    $stmtCount = $conexion->prepare($sqlCount);
    $stmtCount->bind_param($typesCount, ...$paramsCount);
    $stmtCount->execute();
    $totalProductos = $stmtCount->get_result()->fetch_assoc()['total'];

    $totalPaginas = ceil($totalProductos / $productosPorPagina);

    // Obtener categorías únicas
    $categorias = [];
    $resCategorias = $conexion->query("SELECT DISTINCT Producto_Categoria FROM Productos ORDER BY Producto_Categoria ASC");
    while ($row = $resCategorias->fetch_assoc()) {
        $categorias[] = $row['Producto_Categoria'];
    }
?>