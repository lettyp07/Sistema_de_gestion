<?php

session_start();
require_once '../../config/database.php';

// Verificar si el usuario está autenticado
if (empty($_SESSION['username']) || empty($_SESSION['permisos_acceso'])) {
    header("Location: index.php?alert=3");
    exit;
}
//echo "aqui";
if (isset($_GET['act'])) {
    $action = $_GET['act'];

    if ($action === 'insert') {
        // Validación de datos
        $fecha = isset($_GET['fecha']) ? $_GET['fecha'] : '';
        $hora = isset($_GET['hora']) ? $_GET['hora'] : '';
        $estado = isset($_GET['estado']) ? $_GET['estado'] : '';
        $id_control_produccion = isset($_GET['id_control_produccion']) ? intval($_GET['id_control_produccion']) : 0;
        $id_etapa_prod_cabecera = isset($_GET['id_etapa_produccion_cabecera']) ? intval($_GET['id_etapa_produccion_cabecera']) : 0;
        $id_orden_produccion = isset($_GET['id_orden_produccion']) ? intval($_GET['id_orden_produccion']) : 0;
        $id_user = isset($_SESSION['id_user']) ? intval($_SESSION['id_user']) : 0;
        $detalles = isset($_GET['detalles']) ? json_decode($_GET['detalles'], true) : [];
        // Verificar que los datos requeridos no están vacíos
//        // Verificar si la orden de producción existe
//        $query_orden = $mysqli->prepare("SELECT COUNT(*) FROM `orden_produccion` WHERE `id_orden_produccion` = ?");
//        $query_orden->bind_param('i', $id_orden_produccion);
//        $query_orden->execute();
//        $query_orden->bind_result($orden_existente);
//        $query_orden->fetch();
//        if ($orden_existente === 0) {
//            die(json_encode(['success' => false, 'message' => 'La orden de producción no existe.']));
//        }
        // Preparar e insertar en `etapa_produccion`
        $stmt = $mysqli->prepare("
            INSERT INTO control_produccion
            (id_control_produccion, fecha, hora, estado, id_user, id_orden_produccion, id_sucursal)
            VALUES (?,?,?,?,?,?, (SELECT id_sucursal FROM `usuarios` WHERE id_user = ?))
        ");
        $stmt->bind_param('isssiii', $id_control_produccion, $fecha, $hora, $estado, $id_user, $id_orden_produccion, $id_user);

//      CAMBIAMOS EL ESTADO DE LA ETAPA DE CONTROL SEGUN EL ESTADO 
        if ($estado == "controlado") {
            $stmt_etapa = $mysqli->prepare("
            UPDATE etapa_produccion SET estado = 'controlado'
            WHERE id_etapa_produccion = ?
            ");
            $stmt_etapa->bind_param('i', $id_etapa_prod_cabecera);

            if (!$stmt_etapa->execute()) {
                die(json_encode(['success' => false, 'message' => $stmt_etapa->error]));
            }
        } else if ($estado == "anulado") {
            $stmt_etapa = $mysqli->prepare("
            UPDATE etapa_produccion SET estado = 'anulado control'
            WHERE id_etapa_produccion = ?
            ");
            $stmt_etapa->bind_param('i', $id_etapa_prod_cabecera);

            if (!$stmt_etapa->execute()) {
                die(json_encode(['success' => false, 'message' => $stmt_etapa->error]));
            }
        }


        if (!$stmt->execute()) {
            die(json_encode(['success' => false, 'message' => $stmt->error]));
        }



//        // Insertar detalles de etapa
        $stmt_detalle = $mysqli->prepare("
            INSERT INTO detalle_control_produccion
        (id_control_produccion, id_etapa_produccion, ajuste, id_producto)
            VALUES (?, ?, ?, ?)
        ");

        foreach ($detalles as $detalle) {
            $stmt_detalle->bind_param(
                    'iisi',
                    $id_control_produccion,
                    $detalle['id_etapa_produccion'],
                    $detalle['ajuste'],
                    $detalle['id_producto']
            );

            if (!$stmt_detalle->execute()) {
                die(json_encode(['success' => false, 'message' => $stmt_detalle->error]));
            }
        }

        //VERIFICAMOS LA CANTIDAD DE ETAPAS A REALIZAR
        $sql2 = mysqli_query($mysqli, "
            select coalesce(count(e.id_etapa), 0) as cantidad_etapas
            from etapas e 
        ") or die('<div class="alert alert-danger">Error en la consulta: ' . mysqli_error($mysqli) . '</div>');
        
        $etapas = mysqli_fetch_assoc($sql2);
        //OBTENEMOS LA CANTIDAD DE ETAPAS CONTROLADAS
        $sql2 = mysqli_query($mysqli, "
            select 
            coalesce(count(ep.id_etapa_produccion),0) as cantidad_controlado 
            from etapa_produccion ep 
            where ep.estado = 'controlado' and ep.id_orden_produccion  = $id_orden_produccion
                    ") or die('<div class="alert alert-danger">Error en la consulta: ' . mysqli_error($mysqli) . '</div>');
        
        $cab_etapa = mysqli_fetch_assoc($sql2);
       
        if(intval($etapas['cantidad_etapas']) == intval($cab_etapa['cantidad_controlado'])){
            //actualizamos el orden de produccion a utilizado
            //cambiamos el estado del orden utilizado
                    $stmt_orden = $mysqli->prepare("
                        UPDATE orden_produccion SET estado = 'utilizado' where id_orden_produccion = ?
                    ");
                    $stmt_orden->bind_param('i',  $id_orden_produccion);

            //       
                    if (!$stmt_orden->execute()) {
                        die(json_encode(['success' => false, 'message' => $stmt_orden->error]));
                    }
        }

        echo json_encode(['success' => true, 'redirect_url' => 'main.php?module=control_produccion&alert=1']);
        exit;
    } elseif ($action === 'anular') {
        $id_control_produccion= isset($_GET['id']) ? intval($_GET['id']) : 0;

        if ($id_control_produccion === 0) {
            die(json_encode(['success' => false, 'message' => 'ID de control de producción no válido.']));
        }

        $stmt = $mysqli->prepare("UPDATE `control_produccion` SET `estado` = 'anulado' WHERE `id_control_produccion` = ?");
        $stmt->bind_param('i', $id_control_produccion);

        if ($stmt->execute()) {
            header("Location: ../../main.php?module=control_produccion&alert=2");
        } else {
            die(json_encode(['success' => false, 'message' => $stmt->error]));
        }
    }
}
?>
