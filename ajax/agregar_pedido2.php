<?php 
session_start();
$session_id = session_id();
if (isset($_POST['id'])) { $id = $_POST['id']; }
if (isset($_POST['cantidad'])) { $cantidad = $_POST['cantidad']; }

require_once '../config/database.php';

// Insertar un producto en la tabla temporal
if (!empty($id) and !empty($cantidad)) {
    $insert_tmp = mysqli_query($mysqli, "INSERT INTO tmp (cod_ingrediente, cantidad_tmp, session_id) 
    VALUES('$id', '$cantidad','$session_id')");
}

// Eliminar un producto de la tabla temporal
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $delete = mysqli_query($mysqli, "DELETE FROM tmp WHERE id_tmp='" . $id . "'");
}
?>
<table class="table table-bordered table-striped table-hover">
    <tr class="warning">
        <th>Código</th>
        <th>Tipo ingrediente</th>
        <th>Unidad medida</th>
        <th>Ingrediente</th>
        <th><span class="pull-center">Cantidad</span></th>
        <th><span class="pull-right">Motivo</span></th>
        <th style="width: 36px;"></th>
    </tr>
    <?php 
    $sql = mysqli_query($mysqli, "
    SELECT tmp.id_tmp, tmp.cod_ingrediente AS id_ingrediente, tmp.cantidad_tmp, tmp.id_motivo, 
           ingrediente.descrip_ingrediente, ingrediente.id_t_ingrediente, ingrediente.id_u_medida 
    FROM tmp 
    JOIN ingrediente ON ingrediente.id_ingrediente = tmp.cod_ingrediente 
    WHERE tmp.session_id = '$session_id'
    ");
    while ($row = mysqli_fetch_array($sql)) {
        $id_tmp = $row['id_tmp'];
        $codigo_producto = $row['id_ingrediente'];
        $descrip_producto = $row['descrip_ingrediente'];
        $cantidad = $row['cantidad_tmp'];

        $codigo_tproducto = $row['id_t_ingrediente'];
        $sql_tproducto = mysqli_query($mysqli, "SELECT t_ingrediente FROM tipo_ingrediente WHERE id_t_ingrediente='$codigo_tproducto'");
        $rw_tproducto = mysqli_fetch_array($sql_tproducto);
        $tproducto_nombre = $rw_tproducto['t_ingrediente'];

        $id_u_medida = $row['id_u_medida'];
        $sql_umedida = mysqli_query($mysqli, "SELECT u_descrip FROM u_medida WHERE id_u_medida='$id_u_medida'");
        $rw_u_medida = mysqli_fetch_array($sql_umedida);
        $u_medida_nombre = $rw_u_medida['u_descrip'];

        $id_motivo_seleccionado = $row['id_motivo']; // Motivo ya seleccionado
        ?>
        <tr>
            <td><?php echo $codigo_producto; ?></td>
            <td><?php echo $tproducto_nombre; ?></td>
            <td><?php echo $u_medida_nombre; ?></td>
            <td><?php echo $descrip_producto; ?></td>
            <td><span class="pull-right"><?php echo $cantidad; ?></span></td>
            <td>
                <span class="pull-right">
                    <select class="form-control motivo_selector" onchange="actualizarMotivo(<?php echo $id_tmp; ?>, this.value)">
                        <option value="">Seleccione un motivo</option>
                        <?php 
                        $sql_motivos = mysqli_query($mysqli, "SELECT id_motivo, motivo FROM motivos");
                        while ($motivo_row = mysqli_fetch_array($sql_motivos)) {
                            $selected = ($motivo_row['id_motivo'] == $id_motivo_seleccionado) ? "selected" : "";
                            echo '<option value="' . $motivo_row['id_motivo'] . '" ' . $selected . '>' . $motivo_row['motivo'] . '</option>';
                        }
                        ?>
                    </select>
                </span>
            </td>
            <td>
                <span class="pull-right">
                    <a href="#" onclick="eliminar('<?php echo $id_tmp; ?>')">
                        <i class="glyphicon glyphicon-trash"></i>
                    </a>
                </span>
            </td>
        </tr>
    <?php } ?>
</table>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
function actualizarMotivo(id_tmp, id_motivo) {
    if (id_motivo === "") {
        alert("Debe seleccionar un motivo válido.");
        return;
    }
    $.ajax({
        type: "POST",
        url: "actualizar_motivo.php",
        data: { id_tmp: id_tmp, id_motivo: id_motivo },
        success: function(response) {
            if (response === "success") {
                console.log("Motivo actualizado correctamente.");
            } else {
                alert("Error al actualizar el motivo: " + response);
            }
        }
    });
}
</script>
