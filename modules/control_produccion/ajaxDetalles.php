<?php
// Obtener el ID del pedido
$id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;

if ($id === 0) {
    echo "<div class='alert alert-warning'>ID de orden no válido.</div>";
    exit;
}

// Incluir configuración de base de datos
require_once '../../config/database.php';

// Consulta SQL para obtener los detalles del pedido
$sql = "SELECT 
        dp.*, 
        e.descrip,
        e2.nombre ,
        p.descrip  as producto
        FROM detalle_etapa_produccion dp
        JOIN etapa_produccion ep ON ep.id_etapa_produccion = dp.id_etapa_produccion
        join empleados e2 on e2.id_empleados = dp.id_empleado 
        JOIN etapas e ON e.id_etapa = ep.id_etapa
        join producto p on p.id_producto  = dp.id_producto 
        WHERE dp.id_etapa_produccion = ?";

if ($stmt = mysqli_prepare($mysqli, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 0) {
        echo "<div class='alert alert-info'>No hay detalles disponibles para esta orden.</div>";
        exit;
    }

    // Construir el formulario
    
    echo '<table class="table table-bordered table-striped table-hover">';
    echo '<thead>
            <tr class="warning">
                <th>Código</th>
                <th>Etapa</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Empleado</th>
                <th>Ajuste</th>
            </tr>
          </thead>
          <tbody id="control_tb">';

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['id_producto']) . '</td>';
        echo '<td>' . htmlspecialchars($row['descrip']) . '</td>';
        echo '<td>' . htmlspecialchars($row['producto']) . '</td>';
        echo '<td>' . htmlspecialchars($row['cantidad']) . '</td>';
        echo '<td>' . htmlspecialchars($row['nombre']) . '</td>';
        
        // Agregar el campo de texto para el ajuste sin el id_etapa_produccion en el nombre
        echo '<td><input type="text" name="ajuste[]" value="" class="form-control"></td>';
        
        echo '</tr>';
    }

    echo '</tbody></table>';
    echo '';
    echo '';

    mysqli_stmt_close($stmt);
} else {
    echo "<div class='alert alert-danger'>Error en la consulta: " . mysqli_error($mysqli) . "</div>";
}

mysqli_close($mysqli);
?>
