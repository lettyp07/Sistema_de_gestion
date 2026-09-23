<?php
session_start();
require_once '../../config/database.php';

if (isset($_POST['Guardar'])) {
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $codigo_pedido = $_POST['codigo_pedido'];
    $usuario = $_SESSION['id_user'];
    $estado = 'activo';

    // Insertar cabecera del pedido
    $query = "INSERT INTO costo_produccion (fecha, hora, estado, id_user, id_sucursal, id_pedido_cliente)
              VALUES (?, ?, ?, ?, ?, (SELECT id_sucursal FROM usuarios WHERE id_user = ?))";

    if ($stmt = mysqli_prepare($mysqli, $query)) {
        mysqli_stmt_bind_param($stmt, "sssiii", $fecha, $hora, $estado, $usuario, $usuario, $codigo_pedido);

        if (mysqli_stmt_execute($stmt)) {
            $pedido_id = mysqli_insert_id($mysqli);

            if (!empty($_POST['producto']) && is_array($_POST['producto'])) {
                $total_costo_ingredientes = 0;
                $total_costo_hora = 0;

                foreach ($_POST['producto'] as $id_ingrediente => $cantidad) {
                    if ($cantidad > 0) {
                        // Insertar el detalle
                        $insert_detalle = "INSERT INTO detalle_costo (id_costo_produccion, id_producto, cantidad) 
                                           VALUES (?, ?, ?)";
                        if ($stmt_detalle = mysqli_prepare($mysqli, $insert_detalle)) {
                            mysqli_stmt_bind_param($stmt_detalle, "iii", $pedido_id, $id_ingrediente, $cantidad);

                            if (mysqli_stmt_execute($stmt_detalle)) {
                                echo "Detalle del producto guardado correctamente para ID: $id_ingrediente<br>";
                            } else {
                                echo "Error al guardar detalle del producto con ID $id_ingrediente: " . mysqli_error($mysqli) . "<br>";
                            }
                            mysqli_stmt_close($stmt_detalle);
                        } else {
                            echo "Error al preparar la consulta del detalle: " . mysqli_error($mysqli) . "<br>";
                        }

                        // Cálculo de costos
                        $sql_ingredientes = "SELECT 
                                                SUM(ri.cantidad * i.precio) AS costo_ingredientes
                                             FROM 
                                                detalle_pedido_cliente dp
                                             LEFT JOIN receta r ON dp.id_producto = r.id_producto
                                             LEFT JOIN detalle_receta ri ON r.id_receta = ri.id_receta
                                             LEFT JOIN ingrediente i ON ri.id_ingrediente = i.id_ingrediente
                                             WHERE 
                                                dp.id_pedido_cliente = ?";
                        $stmt_ingredientes = mysqli_prepare($mysqli, $sql_ingredientes);
                        mysqli_stmt_bind_param($stmt_ingredientes, "i", $id_ingrediente);
                        mysqli_stmt_execute($stmt_ingredientes);
                        $result_ingredientes = mysqli_stmt_get_result($stmt_ingredientes);

                        if ($row = mysqli_fetch_assoc($result_ingredientes)) {
                            $total_costo_ingredientes += $row['costo_ingredientes'];
                        }
                        mysqli_stmt_close($stmt_ingredientes);

                        $sql_horas = "SELECT 
                                        SUM(TIMESTAMPDIFF(MINUTE, d.hora_ini, d.hora_fin) * ch.costo_hora) / 60 AS costo_hora
                                      FROM 
                                        detalle_pedido_cliente dp
                                      LEFT JOIN orden_produccion op ON dp.id_pedido_cliente = op.id_pedido_cliente
                                      LEFT JOIN detalle_etapa_produccion d ON dp.id_producto = d.id_producto
                                      LEFT JOIN empleados e ON d.id_empleado = e.id_empleados
                                      LEFT JOIN costo_hora ch ON e.id_empleados = ch.id_empleados
                                      WHERE 
                                        dp.id_pedido_cliente = ?";
                        $stmt_horas = mysqli_prepare($mysqli, $sql_horas);
                        mysqli_stmt_bind_param($stmt_horas, "i", $id_ingrediente);
                        mysqli_stmt_execute($stmt_horas);
                        $result_horas = mysqli_stmt_get_result($stmt_horas);

                        if ($row = mysqli_fetch_assoc($result_horas)) {
                            $total_costo_hora += $row['costo_hora'];
                        }
                        mysqli_stmt_close($stmt_horas);
                    } else {
                        echo "Cantidad no válida para producto ID: $id_ingrediente<br>";
                    }
                }

                // Actualizar costos en costo_produccion
                $update_costos = "UPDATE costo_produccion SET costo_ingredientes = ?, costo_hora = ?, costo_total = ? WHERE id_costo_produccion = ?";
                if ($stmt_update = mysqli_prepare($mysqli, $update_costos)) {
                    $costo_total = $total_costo_ingredientes + $total_costo_hora;
                    mysqli_stmt_bind_param($stmt_update, "iiii", $costo_ingredientes, $costo_hora, $costo_total, $pedido_id);

                    if (!mysqli_stmt_execute($stmt_update)) {
                        echo "Error al actualizar costos: " . mysqli_error($mysqli) . "<br>";
                    }
                    mysqli_stmt_close($stmt_update);
                }
            } else {
                echo "No se han seleccionado productos.<br>";
            }

            header("Location: ../../main.php?module=costo_produccion&alert=1");
        } else {
            echo "<div class='alert alert-danger'>Error al guardar: " . mysqli_error($mysqli) . "</div>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "<div class='alert alert-danger'>Error al preparar la consulta: " . mysqli_error($mysqli) . "</div>";
    }

    mysqli_close($mysqli);
}
?>
