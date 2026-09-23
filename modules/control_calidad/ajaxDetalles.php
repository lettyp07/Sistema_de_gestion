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
        WHERE op.id_orden_produccion = ?";

if ($stmt = mysqli_prepare($mysqli, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 0) {
        echo "<div class='alert alert-info'>No hay detalles disponibles para esta orden.</div>";
        exit;
    }

    // Construir la tabla con los datos
    echo '<table class="table table-bordered table-striped table-hover">';
    echo '<thead>
            <tr class="warning">
                <th>Código</th>
                <th>Tipo de Producto</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Unidad de Medida</th>
                <th>Estándar por Unidad</th>
                <th>Detalle (si es Malo)</th>
            </tr>
          </thead>
          <tbody id="control_tb">';

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['id_producto']) . '</td>';
        echo '<td>' . htmlspecialchars($row['t_producto']) . '</td>';
        echo '<td>' . htmlspecialchars($row['descrip']) . '</td>';
        echo '<td>' . htmlspecialchars($row['cantidad']) . '</td>';
        echo '<td>' . htmlspecialchars($row['u_descrip']) . '</td>';
        
        // Generar inputs para cada unidad de cantidad
        $cantidad = intval($row['cantidad']);
        echo '<td>';
        for ($i = 1; $i <= $cantidad; $i++) {
            echo '<div class="input-group mb-2">
                    <span class="input-group-text">#' . $i . '</span>
                    <select name="estandar[' . htmlspecialchars($row['id_producto']) . '][' . $i . ']" class="form-control estandar-select" data-id="detalle_' . $row['id_producto'] . '_' . $i . '">
                        <option value="">Seleccionar</option>
                        <option value="Bueno">Bueno</option>
                        <option value="Aceptable">Aceptable</option>
                        <option value="Malo">Malo</option>
                    </select>
                  </div>';
        }
        echo '</td>';

        // Campo de texto para detalle
        echo '<td>';
        for ($i = 1; $i <= $cantidad; $i++) {
            echo '<div class="input-group mb-2">
                    <textarea 
                        name="detalle[' . htmlspecialchars($row['id_producto']) . '][' . $i . ']" 
                        id="detalle_' . $row['id_producto'] . '_' . $i . '" 
                        class="form-control detalle-text" 
                        style="display: none;" 
                        placeholder="Describe el problema aquí"></textarea>
                  </div>';
        }
        echo '</td>';

        echo '</tr>';
    }

    echo '</tbody></table>';

    mysqli_stmt_close($stmt);
} else {
    echo "<div class='alert alert-danger'>Error en la consulta: " . mysqli_error($mysqli) . "</div>";
}

mysqli_close($mysqli);
?>
<script>
    document.addEventListener('change', function (event) {
        if (event.target.classList.contains('estandar-select')) {
            const detalleId = event.target.dataset.id; // Obtener el ID del área de texto relacionada
            const detalleElement = document.getElementById(detalleId);

            // Mostrar el campo de texto si el estándar es "Malo"
            if (event.target.value === "Malo") {
                detalleElement.style.display = "block";
                detalleElement.setAttribute("required", "required");
            } else {
                detalleElement.style.display = "none";
                detalleElement.removeAttribute("required");
                detalleElement.value = ""; // Limpiar el campo
            }
        }
    });
</script>
