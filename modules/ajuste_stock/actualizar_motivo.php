<?php
require_once '../config/database.php';

if (isset($_POST['id_tmp']) && isset($_POST['id_motivo'])) {
    $id_tmp = intval($_POST['id_tmp']);
    $id_motivo = intval($_POST['id_motivo']);

    if ($id_motivo > 0) {
        // Actualizar el motivo en la tabla temporal
        $query = $mysqli->prepare("UPDATE tmp SET id_motivo = ? WHERE id_tmp = ?");
        $query->bind_param('ii', $id_motivo, $id_tmp);
        if ($query->execute()) {
            echo "success";
        } else {
            echo "Error al actualizar el motivo.";
        }
    } else {
        echo "Motivo inválido.";
    }
} else {
    echo "Datos incompletos.";
}
?>
