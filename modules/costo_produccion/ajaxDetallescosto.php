<?php
require_once '../../config/database.php';

$id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;

if ($id === 0) {
    echo "<div class='alert alert-warning'>ID de pedido no válido.</div>";
    exit;
}

// Consulta para ingredientes con cantidad
$sql_ingredientes = "SELECT 
                        dp.id_producto, 
                        dp.cantidad, 
                        p.descrip AS producto, 
                        SUM(ri.cantidad * i.precio) * dp.cantidad AS costo_ingredientes
                     FROM 
                        detalle_pedido_cliente dp
                     JOIN producto p ON dp.id_producto = p.id_producto
                     LEFT JOIN receta r ON p.id_producto = r.id_producto
                     LEFT JOIN detalle_receta ri ON r.id_receta = ri.id_receta
                     LEFT JOIN ingrediente i ON ri.id_ingrediente = i.id_ingrediente
                     WHERE 
                        dp.id_pedido_cliente = ?
                     GROUP BY dp.id_producto, dp.cantidad, p.descrip";

$stmt_ingredientes = mysqli_prepare($mysqli, $sql_ingredientes);
mysqli_stmt_bind_param($stmt_ingredientes, "i", $id);
mysqli_stmt_execute($stmt_ingredientes);
$result_ingredientes = mysqli_stmt_get_result($stmt_ingredientes);

// Consulta para horas
$sql_horas = "SELECT 
                dp.id_producto, 
                dp.cantidad, 
                p.descrip AS producto, 
                SUM(TIMESTAMPDIFF(MINUTE, d.hora_ini, d.hora_fin) * ch.costo_hora) / 60 * dp.cantidad AS costo_hora
              FROM 
                detalle_pedido_cliente dp
              JOIN producto p ON dp.id_producto = p.id_producto
              LEFT JOIN orden_produccion op ON dp.id_pedido_cliente = op.id_pedido_cliente
              LEFT JOIN detalle_etapa_produccion d ON dp.id_producto = d.id_producto
              LEFT JOIN empleados e ON d.id_empleado = e.id_empleados
              LEFT JOIN costo_hora ch ON e.id_empleados = ch.id_empleados
              WHERE 
                dp.id_pedido_cliente = ?
              GROUP BY dp.id_producto, dp.cantidad, p.descrip";

$stmt_horas = mysqli_prepare($mysqli, $sql_horas);
mysqli_stmt_bind_param($stmt_horas, "i", $id);
mysqli_stmt_execute($stmt_horas);
$result_horas = mysqli_stmt_get_result($stmt_horas);

// Combinar resultados
$costos = [];
while ($row = mysqli_fetch_assoc($result_ingredientes)) {
    $costos[$row['id_producto']] = [
        'id_producto' => $row['id_producto'],
        'cantidad' => $row['cantidad'],
        'producto' => $row['producto'],
        'costo_ingredientes' => $row['costo_ingredientes'],
        'costo_hora' => 0
    ];
}

while ($row = mysqli_fetch_assoc($result_horas)) {
    if (isset($costos[$row['id_producto']])) {
        $costos[$row['id_producto']]['costo_hora'] = $row['costo_hora'];
    } else {
        $costos[$row['id_producto']] = [
            'id_producto' => $row['id_producto'],
            'cantidad' => $row['cantidad'],
            'producto' => $row['producto'],
            'costo_ingredientes' => 0,
            'costo_hora' => $row['costo_hora']
        ];
    }
}

// Mostrar resultados en tabla
echo '<table class="table table-bordered table-striped">';
echo '<thead>
        <tr>
            <th>ID Producto</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Costo Ingredientes</th>
            <th>Costo Mano de Obra</th>
            <th>Costo Total</th>
        </tr>
      </thead>
      <tbody>';

foreach ($costos as $costo) {
    $costo_total = $costo['costo_ingredientes'] + $costo['costo_hora'];
    echo '<tr>';
    echo '<td>' . htmlspecialchars($costo['id_producto']) . '</td>';
    echo '<td>' . htmlspecialchars($costo['producto']) . '</td>';
    echo '<td>' . number_format($costo['cantidad']) . '</td>';
    echo '<td>' . number_format($costo['costo_ingredientes']) . '</td>';
    echo '<td>' . number_format($costo['costo_hora']) . '</td>';
    echo '<td>' . number_format($costo_total) . '</td>';
    echo '</tr>';
}

echo '</tbody></table>';

mysqli_stmt_close($stmt_ingredientes);
mysqli_stmt_close($stmt_horas);
mysqli_close($mysqli);
?>
