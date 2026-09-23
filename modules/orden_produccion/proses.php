<?php
session_start();
require_once '../../config/database.php';

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los valores del formulario
    $codigo = $_POST['codigo'] ?? null;
    $fecha = $_POST['fecha'] ?? null;
    $hora = $_POST['hora'] ?? null;
    $fecha_ini = $_POST['fecha_ini'] ?? null;
    $fecha_fin = $_POST['fecha_fin'] ?? null;
    $codigo_pedido = $_POST['codigo_pedido'] ?? null;
    $codigo_equipo = $_POST['codigo_equipo'] ?? null;
    $usuario = $_SESSION['id_user'] ?? null; // Asumiendo que el ID de usuario está en la sesión
    $estado = 'activo'; // Estado predeterminado

    // Validar fechas
    if (DateTime::createFromFormat('Y-m-d', $fecha_ini) === false) {
        echo "<div class='alert alert-danger'>La fecha de inicio no tiene el formato correcto.</div>";
        exit;
    }
    if (DateTime::createFromFormat('Y-m-d', $fecha_fin) === false) {
        echo "<div class='alert alert-danger'>La fecha de fin no tiene el formato correcto.</div>";
        exit;
    }

    // Insertar cabecera del pedido
    $query = "INSERT INTO orden_produccion (fecha, hora, estado, fecha_ini, fecha_fin, id_pedido_cliente, id_equipo, id_user, id_sucursal)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($mysqli, $query)) {
        // Vincular los parámetros
        mysqli_stmt_bind_param($stmt, "sssssiiis", $fecha, $hora, $estado, $fecha_ini, $fecha_fin, $codigo_pedido, $codigo_equipo, $usuario, $usuario);

        // Ejecutar la consulta
        if (mysqli_stmt_execute($stmt)) {
            // Obtener el ID de la cabecera insertada
            $pedido_id = mysqli_insert_id($mysqli);

            // Validar que los productos estén seleccionados
            if (!empty($_POST['producto']) && is_array($_POST['producto'])) {
                foreach ($_POST['producto'] as $id_ingrediente => $cantidad) {
                    // Validar si la cantidad es mayor que 0
                    if ($cantidad > 0) {
                        // Insertar el detalle
                        $insert_detalle = "INSERT INTO detalle_orden_produccion (id_orden_produccion, id_producto, cantidad) 
                                           VALUES (?, ?, ?)";
                        if ($stmt_detalle = mysqli_prepare($mysqli, $insert_detalle)) {
                            mysqli_stmt_bind_param($stmt_detalle, "iii", $pedido_id, $id_ingrediente, $cantidad);

                            if (mysqli_stmt_execute($stmt_detalle)) {
                                // Confirmación de detalle guardado
                                echo "Detalle del producto guardado correctamente para ID_Ingrediente: $id_ingrediente<br>";
                            } else {
                                echo "Error al guardar detalle del producto con ID_Ingrediente $id_ingrediente: " . mysqli_error($mysqli) . "<br>";
                            }

                            mysqli_stmt_close($stmt_detalle);
                        } else {
                            echo "Error al preparar la consulta del detalle: " . mysqli_error($mysqli) . "<br>";
                        }
                    } else {
                        echo "Cantidad no válida para producto ID_Ingrediente: $id_ingrediente<br>";
                    }
                }
            } else {
                echo "No se han seleccionado productos.<br>";
            }

            // Actualizar el estado del pedido
            $update_estado = "UPDATE pedido_cliente SET estado = 'Ordenado' WHERE id_pedido_cliente = ?";
            if ($stmt_update = mysqli_prepare($mysqli, $update_estado)) {
                mysqli_stmt_bind_param($stmt_update, "i", $codigo_pedido);
                if (!mysqli_stmt_execute($stmt_update)) {
                    echo "Error al actualizar estado del pedido: " . mysqli_error($mysqli) . "<br>";
                }
                mysqli_stmt_close($stmt_update);
            } else {
                echo "Error al preparar la consulta para actualizar el estado del pedido: " . mysqli_error($mysqli) . "<br>";
            }

            // Redirigir a la página principal
            header("Location: ../../main.php?module=orden_produccion&alert=1");
            exit;
        } else {
            echo "<div class='alert alert-danger'>Error al guardar la cabecera del pedido: " . mysqli_error($mysqli) . "</div>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "<div class='alert alert-danger'>Error al preparar la consulta: " . mysqli_error($mysqli) . "</div>";
    }

    mysqli_close($mysqli);
} else {
    echo "<div class='alert alert-danger'>Método de solicitud no válido.</div>";
}
?>
