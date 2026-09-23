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
$sql = "SELECT dp.id_producto,
               dp.id_pedido_cliente, 
               dp.cantidad, 
               tp.t_producto, 
               u.u_descrip, 
               p.descrip
        FROM detalle_pedido_cliente dp
        JOIN pedido_cliente pc ON dp.id_pedido_cliente = pc.id_pedido_cliente
        JOIN producto p ON dp.id_producto = p.id_producto
        JOIN tipo_producto tp ON p.id_t_producto = tp.id_t_producto
        JOIN u_medida u ON p.id_u_medida = u.id_u_medida
        WHERE pc.id_pedido_cliente = ?";

if ($stmt = mysqli_prepare($mysqli, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 0) {
        echo "<div class='alert alert-info'>No hay detalles disponibles para esta orden.</div>";
        exit;
    }

    // Construir el formulario
    echo '<form id="form_pedido" method="POST" action="proses.php">';
    echo '<table class="table table-bordered table-striped table-hover">';
    echo '<thead>
            <tr class="warning">
                <th>Código</th>
                <th>Tipo Producto</th>
                <th>Unidad Medida</th>
                <th>Producto</th>
                <th><span class="pull-left">Cantidad</span></th>
            </tr>
          </thead>
          <tbody>';

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['id_producto']) . '</td>';
        echo '<td>' . htmlspecialchars($row['t_producto']) . '</td>';
        echo '<td>' . htmlspecialchars($row['u_descrip']) . '</td>';
        echo '<td>' . htmlspecialchars($row['descrip']) . '</td>';
        echo '<td>
                <input type="number" name="producto[' . $row['id_producto'] . ']" value="' . htmlspecialchars($row['cantidad']) . '" class="form-control" style="width: 60px; padding: 5px;" readonly />
              </td>';
        echo '</tr>';
    }

    echo '</tbody></table>';
  //  echo '<button type="submit" class="btn btn-primary">Enviar</button>';
    echo '</form>';

    mysqli_stmt_close($stmt);
} else {
    echo "<div class='alert alert-danger'>Error en la consulta: " . mysqli_error($mysqli) . "</div>";
}

mysqli_close($mysqli);
?>
