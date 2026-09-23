<?php  
// Inicializar ID
$id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;

// Verificar si el ID es válido
if ($id === 0) {
    echo "<div class='alert alert-warning'>ID de orden no válido.</div>";
    exit;
}

// Incluir configuración de base de datos
require_once '../../config/database.php';

// Consulta para obtener los detalles de la orden de producción
$sql = mysqli_query($mysqli, "
    SELECT  
        op.id_orden_produccion, 
        dp.id_producto, 
        dp.cantidad,  
        tp.t_producto, 
        u.u_descrip,  
        p.descrip 
    FROM detalle_orden_produccion dp
    JOIN orden_produccion op ON dp.id_orden_produccion = op.id_orden_produccion
    JOIN producto p ON dp.id_producto = p.id_producto
    JOIN tipo_producto tp ON p.id_t_producto = tp.id_t_producto
    JOIN u_medida u ON p.id_u_medida = u.id_u_medida
    WHERE op.id_orden_produccion = $id
") or die('<div class="alert alert-danger">Error en la consulta: ' . mysqli_error($mysqli) . '</div>');


$sql2 = mysqli_query($mysqli, "
    select
    e.id_equipo ,
    e.descrip,
    e2.nombre ,
    e2.id_empleados 
    from orden_produccion op 
    join equipo e on e.id_equipo  = op.id_equipo 
    join detalle_equipo de on e.id_equipo  = de.id_equipo 
    join empleados e2 on e2.id_empleados  = de.id_empleados 
    where op.id_orden_produccion   = $id
") or die('<div class="alert alert-danger">Error en la consulta: ' . mysqli_error($mysqli) . '</div>');

// Verificar si hay resultados
if (mysqli_num_rows($sql) === 0) {
    echo "<div class='alert alert-info'>No se encontraron detalles para esta orden de producción.</div>";
    exit;
}

$lista_empleados = "";

 while ($row = mysqli_fetch_assoc($sql2)) {
     $lista_empleados .= "<option value=".htmlspecialchars($row['id_empleados']).">".htmlspecialchars($row['nombre'])."</option>";
 }

?>
<div id="resultados" class="col-md-9"></div>
<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr class="warning">
            <th>Código</th>
            <th>Tipo producto</th>
            <th>Unidad de medida</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Hora inicio</th>
            <th>Hora fin</th>
            <th>Empleado</th>
        </tr>
    </thead>
    <tbody id="etapa_tb">
        <?php
        $sql = mysqli_query($mysqli, "
            SELECT  
                dp.id_producto, 
                dp.cantidad,
                tp.t_producto, 
                u.u_descrip,  
                p.descrip 
            FROM detalle_orden_produccion dp 
            JOIN producto p ON dp.id_producto = p.id_producto 
            JOIN tipo_producto tp ON p.id_t_producto = tp.id_t_producto 
            JOIN u_medida u ON p.id_u_medida = u.id_u_medida 
            WHERE dp.id_orden_produccion = $id
        ");
        date_default_timezone_set('America/Asuncion');
        $current_time = date('H:i'); // Formato 24 horas (hh:mm)
       
        while ($row = mysqli_fetch_assoc($sql)) {
            ?>
            <tr>
                <td><?= htmlspecialchars($row['id_producto']) ?></td>
                <td><?= htmlspecialchars($row['t_producto']) ?></td>
                <td><?= htmlspecialchars($row['u_descrip']) ?></td>
                <td><?= htmlspecialchars($row['descrip']) ?></td>
                <td><?= htmlspecialchars($row['cantidad']) ?></td>
                <td>
                    <input type="time" value="<?=$current_time?>" class="form-control hora_inicio" name="hora_inicio[<?= $row['id_producto'] ?>]" required>
                </td>
                <td>
                    <input type="time" value="<?=$current_time?>" class="form-control hora_fin" name="hora_fin[<?= $row['id_producto'] ?>]" required>
                </td>
                <td><select class="form-control"><?=$lista_empleados?></select></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
