<?php
// Mostrar todos los errores de PHP
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Incluir configuración de base de datos
require_once '../../config/database.php';
session_start(); // Asegurar que la sesión esté activa

// Verificar si la solicitud es una inserción de datos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['act'])) {
    if ($_GET['act'] === 'insert') {
        // Código existente para insertar datos
        // Obtener datos del formulario
        $codigo = $_POST['codigo'];  // Código del control calidad
        $fecha = $_POST['fecha'];  // Fecha del control calidad
        $hora = $_POST['hora'];  // Hora del control calidad
        $codigo_orden = $_POST['codigo_orden'];  // ID de la orden de producción
        $id_user = $_SESSION['id_user']; // ID del usuario

        // Consultar el id_sucursal desde la base de datos según el id_user de la sesión
        $query_sucursal = "SELECT id_sucursal FROM usuarios WHERE id_user = ?";
        $stmt_sucursal = mysqli_prepare($mysqli, $query_sucursal);
        if ($stmt_sucursal === false) {
            echo json_encode([
                'success' => false,
                'message' => 'Error al preparar la consulta de sucursal: ' . mysqli_error($mysqli)
            ]);
            exit;
        }

        mysqli_stmt_bind_param($stmt_sucursal, "i", $id_user);
        mysqli_stmt_execute($stmt_sucursal);
        mysqli_stmt_bind_result($stmt_sucursal, $id_sucursal);
        mysqli_stmt_fetch($stmt_sucursal);
        mysqli_stmt_close($stmt_sucursal);

        // Si no se encuentra el id_sucursal, manejar el error
        if (!isset($id_sucursal)) {
            echo json_encode([
                'success' => false,
                'message' => 'No se encontró la sucursal del usuario.'
            ]);
            exit;
        }

        // Insertar el control de calidad
        $query_insert_control = "
            INSERT INTO control_calidad (id_control_calidad, fecha, hora, estado, id_orden_produccion, id_user, id_sucursal)
            VALUES (?, ?, ?, 'activo', ?, ?, ?)
        ";

        if ($stmt = mysqli_prepare($mysqli, $query_insert_control)) {
            mysqli_stmt_bind_param($stmt, "ssssss", $codigo, $fecha, $hora, $codigo_orden, $id_user, $id_sucursal);

            if (mysqli_stmt_execute($stmt)) {
                $control_id = mysqli_insert_id($mysqli); // Obtener el ID de control calidad insertado
                
                // Insertar los detalles de control de calidad (estándares y detalles)
                if (isset($_POST['estandar']) && isset($_POST['detalle'])) {
                    foreach ($_POST['estandar'] as $id_producto => $detalles) {
                        foreach ($detalles as $index => $estandar) {
                            $detalle = isset($_POST['detalle'][$id_producto][$index]) ? $_POST['detalle'][$id_producto][$index] : '';

                            // Insertar en la tabla de detalle control calidad
                            $query_insert_detalle = "
                                INSERT INTO detalle_control_calidad (id_control_calidad, id_producto, estandar, detalle)
                                VALUES (?, ?, ?, ?)
                            ";

                            if ($stmt_detalle = mysqli_prepare($mysqli, $query_insert_detalle)) {
                                mysqli_stmt_bind_param($stmt_detalle, "iiss", $control_id, $id_producto, $estandar, $detalle);
                                mysqli_stmt_execute($stmt_detalle);
                                mysqli_stmt_close($stmt_detalle);
                            } else {
                                echo json_encode([
                                    'success' => false,
                                    'message' => 'Error al insertar detalles: ' . mysqli_error($mysqli)
                                ]);
                                exit;
                            }
                        }
                    }
                }

                // Actualizar el estado de la orden de producción a "culminado"
                $query_update_orden = "
                    UPDATE orden_produccion
                    SET estado = 'culminado'
                    WHERE id_orden_produccion = ?
                ";
                if ($stmt_update_orden = mysqli_prepare($mysqli, $query_update_orden)) {
                    mysqli_stmt_bind_param($stmt_update_orden, "i", $codigo_orden);
                    mysqli_stmt_execute($stmt_update_orden);
                    mysqli_stmt_close($stmt_update_orden);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Error al actualizar estado de la orden de producción.'
                    ]);
                    exit;
                }

                // Actualizar el estado del pedido_cliente relacionado a "disponible"
                $query_update_pedido = "
                    UPDATE pedido_cliente
                    SET estado = 'disponible'
                    WHERE id_pedido_cliente = (SELECT id_pedido_cliente FROM orden_produccion WHERE id_orden_produccion = ?)
                ";
                if ($stmt_update_pedido = mysqli_prepare($mysqli, $query_update_pedido)) {
                    mysqli_stmt_bind_param($stmt_update_pedido, "i", $codigo_orden);
                    mysqli_stmt_execute($stmt_update_pedido);
                    mysqli_stmt_close($stmt_update_pedido);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Error al actualizar estado del pedido cliente.'
                    ]);
                    exit;
                }

                // Enviar respuesta en formato JSON indicando éxito
                echo json_encode([
                    'success' => true,
                    'message' => 'El control de calidad se guardó correctamente.',
                    'redirect_url' => 'main.php?module=control_calidad&alert=1'
                ]);
                exit;
            } else {
                // En caso de error en la inserción
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al insertar el control calidad: ' . mysqli_error($mysqli)
                ]);
                exit;
            }
            mysqli_stmt_close($stmt);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al preparar la consulta de inserción: ' . mysqli_error($mysqli)
            ]);
            exit;
        }
    } elseif ($_GET['act'] === 'anular') {
        // Cambiar el estado a "anulado"
        $id_control_calidad = $_POST['id_control_calidad'];

        $query_anular = "
            UPDATE control_calidad
            SET estado = 'anulado'
            WHERE id_control_calidad = ?
        ";

        if ($stmt_anular = mysqli_prepare($mysqli, $query_anular)) {
            mysqli_stmt_bind_param($stmt_anular, "i", $id_control_calidad);

            if (mysqli_stmt_execute($stmt_anular)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'El estado del control de calidad se cambió a anulado.'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al cambiar el estado del control de calidad: ' . mysqli_error($mysqli)
                ]);
            }
            mysqli_stmt_close($stmt_anular);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al preparar la consulta de anulación: ' . mysqli_error($mysqli)
            ]);
        }
        exit;
    }
}

// Cerrar la conexión con la base de datos
mysqli_close($mysqli);
?>
