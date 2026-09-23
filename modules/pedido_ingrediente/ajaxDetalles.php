<?php
// Verificar si el ID del pedido es válido
$id = 0;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']); 
} else {
    echo "<div class='alert alert-warning'>ID de pedido no válido.</div>";
    exit;
}

require_once '../../config/database.php';

// Consulta para obtener ingredientes necesarios para los productos del pedido cliente
$sql = mysqli_query($mysqli, "SELECT 
        i.id_ingrediente, 
        i.descrip_ingrediente, 
        SUM(d.cantidad * p.cantidad) AS total_cantidad, 
        ti.t_ingrediente, 
        um.u_descrip
    FROM detalle_pedido_cliente p
    JOIN receta r ON p.id_producto = r.id_producto
    JOIN detalle_receta d ON r.id_receta = d.id_receta
    JOIN ingrediente i ON d.id_ingrediente = i.id_ingrediente
    JOIN tipo_ingrediente ti ON i.id_t_ingrediente = ti.id_t_ingrediente
    JOIN u_medida um ON i.id_u_medida = um.id_u_medida
    WHERE p.id_pedido_cliente = $id
    GROUP BY i.id_ingrediente, i.descrip_ingrediente, ti.t_ingrediente, um.u_descrip
") or die('<div class="alert alert-danger">Error: ' . mysqli_error($mysqli) . '</div>');

// Verificar si hay resultados
if (mysqli_num_rows($sql) === 0) {
    echo "<div class='alert alert-info'>No hay ingredientes asociados a los productos de este pedido.</div>";
    exit;
}
?>

<form id="form_pedido" method="POST" action="proses.php">
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr class="warning">
                <th>Código</th>
                <th>Tipo Ingrediente</th>
                <th>Unidad Medida</th>
                <th>Ingrediente</th>
                <th><span class="pull-left">Cantidad Total</span></th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Mostrar los ingredientes agrupados y las cantidades totales
            while ($row = mysqli_fetch_assoc($sql)) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['id_ingrediente']) . '</td>';
                echo '<td>' . htmlspecialchars($row['t_ingrediente']) . '</td>';
                echo '<td>' . htmlspecialchars($row['u_descrip']) . '</td>';
                echo '<td>' . htmlspecialchars($row['descrip_ingrediente']) . '</td>';
                echo '<td><input type="number" name="ingrediente[' . $row['id_ingrediente'] . ']" value="' . htmlspecialchars($row['total_cantidad']) . '" class="form-control" style="width: 60px; padding: 5px;" readonly /></td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</form>
