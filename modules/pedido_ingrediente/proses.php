<?php
session_start();
require_once '../../config/database.php';

// Verificar si se ha enviado el formulario
if (isset($_POST['Guardar'])) {
    // Validar y obtener los datos del formulario
    $codigo = $_POST['codigo'];
    $fecha = $_POST['fecha'];
    $codigo_pedido = $_POST['codigo_pedido'];
    $usuario = $_SESSION['id_user']; // Asumiendo que el ID de usuario está en la sesión
    $estado = 'activo'; // Estado predeterminado

    // Insertar cabecera del pedido
    $query = "INSERT INTO pedido_ingrediente (fecha, estado, id_pedido_cliente, id_user, id_sucursal)
              VALUES (?, ?, ?, ?, (SELECT id_sucursal FROM usuarios WHERE id_user = ?))";

    if ($stmt = mysqli_prepare($mysqli, $query)) {
        mysqli_stmt_bind_param($stmt, "ssiii", $fecha, $estado, $codigo_pedido, $usuario, $usuario);

        if (mysqli_stmt_execute($stmt)) {
            // Obtener el ID de la cabecera insertada
            $pedido_id = mysqli_insert_id($mysqli);

            // Insertar detalles de los ingredientes seleccionados en el pedido
            if (isset($_POST['ingrediente']) && count($_POST['ingrediente']) > 0) {
                foreach ($_POST['ingrediente'] as $id_ingrediente => $cantidad) {
                    // Validar si la cantidad es mayor que 0
                    if ($cantidad > 0) {
                        // Verificar el stock disponible
                        $check_stock = "SELECT cantidad FROM stock WHERE id_ingrediente = ? AND id_sucursal = (SELECT id_sucursal FROM usuarios WHERE id_user = ?)";
                        if ($stmt_check = mysqli_prepare($mysqli, $check_stock)) {
                            mysqli_stmt_bind_param($stmt_check, "ii", $id_ingrediente, $usuario);
                            mysqli_stmt_execute($stmt_check);
                            mysqli_stmt_bind_result($stmt_check, $stock_actual);
                            mysqli_stmt_fetch($stmt_check);
                            mysqli_stmt_close($stmt_check);

                            // Verificar si hay suficiente stock
                            if ($stock_actual >= $cantidad) {
                                // Insertar el detalle del pedido
                                $insert_detalle = "INSERT INTO detalle_pedido_ingrediente (id_pedido_ingrediente, id_ingrediente, cantidad) VALUES (?, ?, ?)";
                                if ($stmt_detalle = mysqli_prepare($mysqli, $insert_detalle)) {
                                    mysqli_stmt_bind_param($stmt_detalle, "iii", $pedido_id, $id_ingrediente, $cantidad);

                                    if (mysqli_stmt_execute($stmt_detalle)) {
                                        // Descontar el stock
                                        $update_stock = "UPDATE stock SET cantidad = cantidad - ? WHERE id_ingrediente = ? AND id_sucursal = (SELECT id_sucursal FROM usuarios WHERE id_user = ?)";
                                        if ($stmt_stock = mysqli_prepare($mysqli, $update_stock)) {
                                            mysqli_stmt_bind_param($stmt_stock, "iii", $cantidad, $id_ingrediente, $usuario);
                                            if (!mysqli_stmt_execute($stmt_stock)) {
                                                echo "Error al actualizar el stock del ingrediente con ID $id_ingrediente: " . mysqli_error($mysqli) . "<br>";
                                            }
                                            mysqli_stmt_close($stmt_stock);
                                        } else {
                                            echo "Error al preparar la consulta para actualizar el stock: " . mysqli_error($mysqli) . "<br>";
                                        }
                                    } else {
                                        echo "Error al insertar detalle del ingrediente con ID $id_ingrediente: " . mysqli_error($mysqli) . "<br>";
                                    }

                                    mysqli_stmt_close($stmt_detalle);
                                } else {
                                    echo "Error al preparar la consulta para insertar detalle: " . mysqli_error($mysqli) . "<br>";
                                }
                            } else {
                                echo "Stock insuficiente para el ingrediente con ID $id_ingrediente. Disponible: $stock_actual, Requerido: $cantidad.<br>";
                            }
                        } else {
                            echo "Error al preparar la consulta para verificar el stock: " . mysqli_error($mysqli) . "<br>";
                        }
                    }
                }
            }

            // Actualizar el estado del pedido en la tabla pedido_cliente
            $update_estado = "UPDATE pedido_cliente SET estado = 'ENVIADO' WHERE id_pedido_cliente = ?";
            if ($stmt_estado = mysqli_prepare($mysqli, $update_estado)) {
                mysqli_stmt_bind_param($stmt_estado, "i", $codigo_pedido);
                if (!mysqli_stmt_execute($stmt_estado)) {
                    echo "Error al actualizar el estado del pedido cliente: " . mysqli_error($mysqli) . "<br>";
                }
                mysqli_stmt_close($stmt_estado);
            }

            // Redireccionar si todo salió bien
            header("Location: ../../main.php?module=pedido_ingrediente&alert=1");
        } else {
            echo "<div class='alert alert-danger'>Error al guardar la cabecera del pedido: " . mysqli_error($mysqli) . "</div>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "<div class='alert alert-danger'>Error al preparar la consulta: " . mysqli_error($mysqli) . "</div>";
    }

    // Cerrar la conexión
    mysqli_close($mysqli);
}

// Función para anular un pedido
if (isset($_GET['act']) && $_GET['act'] == 'anular') {
    if (isset($_GET['id_pedido'])) {
        $pedido_id = intval($_GET['id_pedido']);

                // Actualizar el estado del pedido a "ANULADO"
                $update_pedido = "UPDATE pedido_ingrediente SET estado = 'anulado' WHERE id_pedido_ingrediente = ?";
                if ($stmt_update = mysqli_prepare($mysqli, $update_pedido)) {
                    mysqli_stmt_bind_param($stmt_update, "i", $pedido_id);
                    mysqli_stmt_execute($stmt_update);
                    mysqli_stmt_close($stmt_update);
                }
                                // Actualizar el estado del pedido a "ANULADO"
                                $update_pedido = "UPDATE pedido_cliente SET estado = 'activo' WHERE id_pedido_cliente = ?";
                                if ($stmt_update = mysqli_prepare($mysqli, $update_pedido)) {
                                    mysqli_stmt_bind_param($stmt_update, "i", $pedido_id);
                                    mysqli_stmt_execute($stmt_update);
                                    mysqli_stmt_close($stmt_update);
                                }

                // Redireccionar con éxito
                header("Location: ../../main.php?module=pedido_ingrediente&alert=2");
                exit;
            } else {
                echo "El pedido no puede ser anulado porque no está activo.";
            }
        }

?>
