<?php

session_start();
require_once '../../config/database.php';

// Verificar si el usuario está autenticado
if (empty($_SESSION['username']) || empty($_SESSION['permisos_acceso'])) {
    header("Location: index.php?alert=3");
    exit;
}

if (isset($_GET['act'])) {
    $action = $_GET['act'];

    switch ($action) {
        case 'insert':
            insertarEtapaProduccion($mysqli);
            break;

        case 'anular':
            anularEtapaProduccion($mysqli);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Acción no válida.']);
            exit;
    }
}

function insertarEtapaProduccion($mysqli)
{
    $fecha = $_GET['fecha'] ?? '';
    $hora = $_GET['hora'] ?? '';
    $estado = $_GET['estado'] ?? '';
    $id_etapa_produccion = intval($_GET['id_etapa_produccion'] ?? 0);
    $id_orden_produccion = intval($_GET['id_orden_produccion'] ?? 0);
    $id_etapa = intval($_GET['id_etapa'] ?? 0); // Identifica el tipo de etapa
    $id_user = intval($_SESSION['id_user'] ?? 0);
    $detalles = json_decode($_GET['detalles'] ?? '[]', true);

    if (!$id_orden_produccion || !$id_etapa_produccion || !$id_etapa || !$id_user) {
        die(json_encode(['success' => false, 'message' => 'Datos incompletos.']));
    }

    // Verificar si la etapa ya fue registrada en la orden de producción
    $query_validar = $mysqli->prepare("
        SELECT COUNT(*) 
        FROM `etapa_produccion` 
        WHERE `id_orden_produccion` = ? AND `id_etapa` = ?
    ");
    $query_validar->bind_param('ii', $id_orden_produccion, $id_etapa);
    $query_validar->execute();
    $query_validar->bind_result($etapa_existente);
    $query_validar->fetch();
    $query_validar->close();

    if ($etapa_existente > 0) {
        die(json_encode(['success' => false, 'message' => 'Esta etapa ya ha sido registrada para esta orden de producción.']));
    }

    // Insertar en `etapa_produccion`
    $stmt = $mysqli->prepare("
        INSERT INTO `etapa_produccion` 
        (id_etapa_produccion, fecha, hora, estado, id_orden_produccion, id_user, id_etapa, id_sucursal)
        VALUES (?, ?, ?, ?, ?, ?, ?, (SELECT id_sucursal FROM `usuarios` WHERE id_user = ?))
    ");
    $stmt->bind_param('isssiiii', $id_etapa_produccion, $fecha, $hora, $estado, $id_orden_produccion, $id_user, $id_etapa, $id_user);

    if (!$stmt->execute()) {
        die(json_encode(['success' => false, 'message' => $stmt->error]));
    }

    // Insertar detalles de etapa
    $stmt_detalle = $mysqli->prepare("
        INSERT INTO `detalle_etapa_produccion` 
        (id_etapa_produccion, id_producto, cantidad, hora_ini, hora_fin, id_empleado)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    foreach ($detalles as $detalle) {
        $stmt_detalle->bind_param(
            'iiissi',
            $id_etapa_produccion,
            $detalle['id_producto'],
            $detalle['cantidad'],
            $detalle['hora_ini'],
            $detalle['hora_fin'],
            $detalle['id_empleado']
        );

        if (!$stmt_detalle->execute()) {
            die(json_encode(['success' => false, 'message' => $stmt_detalle->error]));
        }
    }

    // Actualizar estado en `orden_produccion`
    //$stmt_update = $mysqli->prepare("UPDATE `orden_produccion` SET `estado` = ? WHERE `id_orden_produccion` = ?");
    //$stmt_update->bind_param('si', $estado, $id_orden_produccion);

    //if (!$stmt_update->execute()) {
    //    die(json_encode(['success' => false, 'message' => $stmt_update->error]));
    //}

    echo json_encode(['success' => true, 'redirect_url' => 'main.php?module=etapa_produccion&alert=1']);
    exit;
}

function anularEtapaProduccion($mysqli)
{
    $id_etapa_produccion = intval($_GET['id_etapa_produccion'] ?? 0);

    if (!$id_etapa_produccion) {
        die(json_encode(['success' => false, 'message' => 'ID de etapa de producción no válido.']));
    }

    $stmt = $mysqli->prepare("UPDATE `etapa_produccion` SET `estado` = 'anulado' WHERE `id_etapa_produccion` = ?");
    $stmt->bind_param('i', $id_etapa_produccion);

    if ($stmt->execute()) {
        header("Location: ../../main.php?module=etapa_produccion&alert=2");
    } else {
        die(json_encode(['success' => false, 'message' => $stmt->error]));
    }
}
?>
