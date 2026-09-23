<?php
session_start();
require_once '../../config/database.php';

if (empty($_SESSION['username']) && empty($_SESSION['password'])) {
    echo "<meta http-equiv='refresh' content='0; url=index.php?alert=3'>";
    exit;
}

if ($_GET['act'] == 'insert') {
    if (isset($_POST['Guardar'])) {
        $codigo = intval($_POST['codigo']);
        $fecha = $_POST['fecha'];
        $hora = $_POST['hora'];
        $tipo_ajuste = $_POST['tipo_ajuste'];
        $estado = 'pendiente';
        $usuario = intval($_SESSION['id_user']);
        $sessionId = session_id();

        // Procesar los productos en `tmp`
        $sql = $mysqli->prepare("
            SELECT t.cod_ingrediente, t.cantidad_tmp, t.id_motivo 
            FROM tmp t 
            WHERE t.session_id = ?
        ");

        if (!$sql) {
            die("Error preparando la consulta: " . $mysqli->error);
        }

        $sql->bind_param('s', $sessionId);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows > 0) {
            $insert_ajuste = $mysqli->prepare("
                INSERT INTO ajuste_stock (id_ajuste, fecha, hora, id_user, tipo_ajuste, estado) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            if (!$insert_ajuste) {
                die("Error preparando la consulta de ajuste: " . $mysqli->error);
            }

            $insert_ajuste->bind_param('isssss', $codigo, $fecha, $hora, $usuario, $tipo_ajuste, $estado);
            $insert_ajuste->execute();

            if ($insert_ajuste->affected_rows > 0) {
                $id_ajuste = $mysqli->insert_id;

                while ($row = $result->fetch_assoc()) {
                    $codigo_producto = intval($row['cod_ingrediente']);
                    $cantidad = intval($row['cantidad_tmp']);
                    $id_motivo = intval($row['id_motivo']); 

                    if ($id_motivo <= 0) {
                        die("Error: El motivo es obligatorio y debe ser válido.");
                    }

                    $insert_detalle = $mysqli->prepare("
                        INSERT INTO detalle_ajuste_stock (id_ajuste, id_ingrediente, cantidad, id_motivo) 
                        VALUES (?, ?, ?, ?)
                    ");

                    if (!$insert_detalle) {
                        die("Error preparando la consulta de detalle: " . $mysqli->error);
                    }

                    $insert_detalle->bind_param('iiii', $id_ajuste, $codigo_producto, $cantidad, $id_motivo);
                    $insert_detalle->execute();

                    $query_stock = $mysqli->prepare("SELECT cantidad FROM stock WHERE id_ingrediente = ?");
                    $query_stock->bind_param('i', $codigo_producto);
                    $query_stock->execute();
                    $result_stock = $query_stock->get_result();

                    if ($result_stock->num_rows == 0) {
                        $insertar_stock = $mysqli->prepare("
                            INSERT INTO stock (id_ingrediente, cantidad) 
                            VALUES (?, ?)
                        ");
                        $insertar_stock->bind_param('ii', $codigo_producto, $cantidad);
                        $insertar_stock->execute();
                    } else {
                        if ($tipo_ajuste == 'Agregar') {
                            $actualizar_stock = $mysqli->prepare("
                                UPDATE stock 
                                SET cantidad = cantidad + ? 
                                WHERE id_ingrediente = ?
                            ");
                        } else {
                            $actualizar_stock = $mysqli->prepare("
                                UPDATE stock 
                                SET cantidad = cantidad - ? 
                                WHERE id_ingrediente = ?
                            ");
                        }

                        if (!$actualizar_stock) {
                            die("Error preparando la consulta de actualización de stock: " . $mysqli->error);
                        }

                        $actualizar_stock->bind_param('ii', $cantidad, $codigo_producto);
                        $actualizar_stock->execute();
                    }
                }

                $delete_tmp = $mysqli->prepare("DELETE FROM tmp WHERE session_id = ?");
                $delete_tmp->bind_param('s', $sessionId);
                $delete_tmp->execute();

                header("Location: ../../main.php?module=ajuste_stock&alert=1");
                exit;
            } else {
                header("Location: ../../main.php?module=ajuste_stock&alert=3");
                exit;
            }
        } else {
            die("Error: No se encontraron productos para procesar.");
        }
    }
} elseif ($_GET['act'] == 'anular') {
    if (isset($_GET['id_ajuste'])) {
        $codigo = intval($_GET['id_ajuste']);

        $sql_detalle = $mysqli->prepare("
            SELECT da.id_ingrediente, da.cantidad, as1.tipo_ajuste 
            FROM detalle_ajuste_stock da
            JOIN ajuste_stock as1 ON da.id_ajuste = as1.id_ajuste
            WHERE da.id_ajuste = ?
        ");

        $sql_detalle->bind_param('i', $codigo);
        $sql_detalle->execute();
        $result_detalle = $sql_detalle->get_result();

        if ($result_detalle->num_rows > 0) {
            while ($row = $result_detalle->fetch_assoc()) {
                $codigo_producto = intval($row['id_ingrediente']);
                $cantidad = intval($row['cantidad']);
                $tipo_ajuste = $row['tipo_ajuste'];

                $update_stock = ($tipo_ajuste == 'Agregar') ? 
                    $mysqli->prepare("UPDATE stock SET cantidad = cantidad - ? WHERE id_ingrediente = ?") : 
                    $mysqli->prepare("UPDATE stock SET cantidad = cantidad + ? WHERE id_ingrediente = ?");
                
                $update_stock->bind_param('ii', $cantidad, $codigo_producto);
                $update_stock->execute();
            }

            $update_ajuste = $mysqli->prepare("UPDATE ajuste_stock SET estado = 'anulado' WHERE id_ajuste = ?");
            $update_ajuste->bind_param('i', $codigo);
            $update_ajuste->execute();

            header("Location: ../../main.php?module=ajuste_stock&alert=2");
            exit;
        } else {
            die("Error: No se encontraron detalles para este ajuste.");
        }
    }
}
?>
