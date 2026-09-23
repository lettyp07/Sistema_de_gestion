<?php
// Obtener el ID del pedido
$id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;

if ($id === 0) {
    echo "<div class='alert alert-warning'>ID de pedido no válido.</div>";
    exit;
}

// Incluir configuración de base de datos
require_once '../../config/database.php';

// Consulta SQL para obtener los detalles del pedido
$sql = "SELECT 
            dp.id_producto, 
            p.descrip AS producto, 
            COALESCE(SUM(ri.cantidad * i.precio), 0) AS costo_ingredientes
        FROM 
            detalle_pedido_cliente dp
        JOIN 
            producto p ON dp.id_producto = p.id_producto
        LEFT JOIN 
            receta r ON p.id_producto = r.id_producto
        LEFT JOIN 
            detalle_receta ri ON r.id_receta = ri.id_receta
        LEFT JOIN 
            ingrediente i ON ri.id_ingrediente = i.id_ingrediente
        WHERE 
            dp.id_pedido_cliente = ?
        GROUP BY 
            dp.id_producto, p.descrip;";

if ($stmt = mysqli_prepare($mysqli, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 0) {
        echo "<div class='alert alert-info'>No hay detalles disponibles para esta orden.</div>";
    } else {
        // Construir el formulario
        echo '<form id="form_pedido" method="POST" action="proses.php">';
        echo '<table class="table table-bordered table-striped table-hover">';
        echo '<thead>
                <tr class="warning">
                    <th>Código</th>
                    <th>Producto</th>
                    <th>Costo total ingredientes</th>
                </tr>
              </thead>
              <tbody>';

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['id_producto']) . '</td>';
            echo '<td>' . htmlspecialchars($row['producto']) . '</td>';
            echo '<td>' . htmlspecialchars($row['costo_ingredientes']) . '</td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
        echo '</form>';
    }

    mysqli_stmt_close($stmt);
} else {
    echo "<div class='alert alert-danger'>Error en la consulta: " . mysqli_error($mysqli) . "</div>";
}

mysqli_close($mysqli);
?>
