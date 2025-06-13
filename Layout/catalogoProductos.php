<?php include '../PHP/catalogoProductos.php'; ?>

<!-- Layout de productos -->
<div class="row">
    <!-- Filtros -->
    <div class="col-md-3">
        <!-- Categorías dinámicas -->
        <div class="mb-4">
            <h5 class="fw-bold mb-3">Categorías</h5>
            <ul class="list-group shadow-sm">
                <li class="list-group-item">
                    <a href="?" class="text-decoration-none text-dark <?php echo ($categoriaSeleccionada === '') ? 'fw-bold' : ''; ?>">Todas las categorías</a>
                </li>
                <?php foreach ($categorias as $categoria): ?>
                    <li class="list-group-item">
                        <a href="?categoria=<?php echo urlencode($categoria); ?>" class="text-decoration-none text-dark <?php echo ($categoriaSeleccionada === $categoria) ? 'fw-bold' : ''; ?>">
                            <?php echo htmlspecialchars($categoria); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Filtro por precio -->
        <div class="mb-4">
            <h5 class="fw-bold mb-3">Filtrar por precio</h5>
            <form id="priceFilterForm">
                <div class="input-group mb-3">
                    <input type="number" id="minPrice" class="form-control" placeholder="Mínimo" value="<?php echo $minPrecioMostrar; ?>">
                    <span class="input-group-text">-</span>
                    <input type="number" id="maxPrice" class="form-control" placeholder="Máximo" value="<?php echo $maxPrecioMostrar; ?>">
                </div>
                <button type="button" class="btn btn-primary w-100" onclick="filterByPrice()">Aplicar</button>
            </form>
        </div>
    </div>

    <!-- Productos -->
    <div class="col-md-9">
        <!-- Buscador -->
        <div class="row justify-content-between align-items-center mb-4">
            <div class="col-md-8">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Buscar productos..." id="productSearch" value="<?php echo htmlspecialchars($terminoBusqueda); ?>" oninput="suggestProducts(this.value)">
                    <div id="suggestions" class="list-group position-absolute w-100" style="z-index: 10;"></div>
                </div>
            </div>
            <div class="col-md-2 d-flex align-items-center justify-content-center">
                <button class="btn btn-secondary" onclick="startOrStopVoiceRecognition()" title="Búsqueda por voz">
                    <i class="fa fa-microphone"></i>
                </button>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100" onclick="filterProducts()">
                    <i class="fa fa-search"></i> Buscar
                </button>
            </div>
        </div>

        <!-- Productos listados -->
        <div id="productContainer">
            <?php
                if ($resultado->num_rows > 0) {
                    echo '<div class="row row-cols-1 row-cols-md-3 g-4">';
                    while ($fila = $resultado->fetch_assoc()) {
                        echo '<div class="col mb-4">';
                        echo '<div class="card h-100 shadow-sm border-light">';
                        echo '<img src="' . htmlspecialchars($fila['Producto_Imagen']) . '" class="card-img-top img-fluid" alt="' . htmlspecialchars($fila['Producto_Nombre']) . '">';
                        echo '<div class="card-body d-flex flex-column">';
                        echo '<h5 class="card-title">' . htmlspecialchars($fila['Producto_Nombre']) . '</h5>';
                        echo '<p class="card-text text-muted">$' . number_format($fila['Producto_Precio'], 2) . '</p>';
                        echo '<a href="verProducto.php?id=' . $fila['ProductoID'] . '" class="btn btn-primary mt-auto">Ver detalles</a>';
                        echo '</div></div></div>';
                    }
                    echo '</div>';
                } else {
                    echo "<p class='text-center text-muted'>No hay productos disponibles.</p>";
                }
            ?>
        </div>

        <!-- Paginación -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <ul class="pagination justify-content-center">
                    <?php if ($paginaActual > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?pagina=1<?php echo $terminoBusqueda ? '&buscar=' . urlencode($terminoBusqueda) : ''; echo $categoriaSeleccionada ? '&categoria=' . urlencode($categoriaSeleccionada) : ''; ?>">Primera</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="?pagina=<?php echo $paginaActual - 1; ?>">Anterior</a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                        <li class="page-item <?php echo ($i == $paginaActual) ? 'active' : ''; ?>">
                            <a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($paginaActual < $totalPaginas): ?>
                        <li class="page-item">
                            <a class="page-link" href="?pagina=<?php echo $paginaActual + 1; ?>">Siguiente</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="?pagina=<?php echo $totalPaginas; ?>">Última</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    function filterByPrice() {
        const min = document.getElementById('minPrice').value;
        const max = document.getElementById('maxPrice').value;
        const url = new URL(window.location.href);
        if (min) url.searchParams.set('minPrecio', min);
        if (max) url.searchParams.set('maxPrecio', max);
        window.location.href = url.toString();
    }

    function filterProducts() {
        const search = document.getElementById('productSearch').value;
        const url = new URL(window.location.href);
        if (search) {
            url.searchParams.set('buscar', search);
        } else {
            url.searchParams.delete('buscar');
        }
        window.location.href = url.toString();
    }

    // Para búsqueda por voz (placeholder)
    function startOrStopVoiceRecognition() {
        alert("🎤 Función de búsqueda por voz aún no implementada.");
    }
</script>
