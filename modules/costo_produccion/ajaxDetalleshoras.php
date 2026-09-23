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
    d.id_etapa_produccion,
    TIMESTAMPDIFF(MINUTE, d.hora_ini, d.hora_fin) AS total_minutos,
    COALESCE(SUM(TIMESTAMPDIFF(MINUTE, d.hora_ini, d.hora_fin) * ch.costo_hora) / 60, 0) AS costo_hora
FROM 
    detalle_etapa_produccion d
JOIN 
    orden_produccion op ON op.id_orden_produccion = op.id_orden_produccion
JOIN 
    pedido_cliente pc ON op.id_pedido_cliente = pc.id_pedido_cliente
JOIN 
    detalle_pedido_cliente dp ON pc.id_pedido_cliente = dp.id_pedido_cliente
LEFT JOIN 
    empleados e ON d.id_empleado = e.id_empleados
LEFT JOIN 
    costo_hora ch ON e.id_empleados = ch.id_empleados
WHERE 
    dp.id_pedido_cliente = ?
GROUP BY 
    d.id_etapa_produccion
ORDER BY 
    d.id_etapa_produccion;";

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
                    <th>Total minutos</th>
                    <th>Costo hora</th>
                </tr>
              </thead>
              <tbody>';

        $total_costo_general = 0;

        while ($row = mysqli_fetch_assoc($result)) {
            $total_costo_hora = number_format($row['costo_hora'], 2); // Formato decimal para costo hora
            $total_costo_general += $row['costo_hora']; // Sumar costo hora

            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['id_etapa_produccion']) . '</td>';
            echo '<td>' . htmlspecialchars($row['total_minutos']) . '</td>';
            echo '<td>' . $total_costo_hora . '</td>'; // Mostrando costos formateados
            echo '</tr>';
        }

        // Agregar la fila del total al final
        $total_costo_general_formateado = number_format($total_costo_general, 2); // Sin dividir por 60 para formato directo en horas
        echo '<tr>';
        echo '<td colspan="2" class="text-right"><strong>Total</strong></td>';
        echo '<td>' . $total_costo_general_formateado . '</td>';
        echo '</tr>';

        echo '</tbody></table>';
        echo '</form>';
    }

    mysqli_stmt_close($stmt);
} else {
    echo "<div class='alert alert-danger'>Error en la consulta: " . mysqli_error($mysqli) . "</div>";
}

mysqli_close($mysqli);
?>
