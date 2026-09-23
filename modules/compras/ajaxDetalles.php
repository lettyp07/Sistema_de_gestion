<?php 

$id = 0;
if(isset($_GET['id'])){
    $id=$_GET['id'];
    
}

require_once '../../config/database.php';

?>
<table class="table table-bordered table-striped table-hover">
    <tr class="warning">
        <th>Código</th>
        <th>Tipo Ingrediente</th>
        <th>Unidad medida</th>
        <th>Ingrediente</th>
        <th><span class="pull-right">Cantidad</span></th>
        <th><span class="pull-right">Costo</span></th>
        <th><span class="pull-right">EXENTA</span></th>
        <th><span class="pull-right">IVA 5</span></th>
        <th><span class="pull-right">IVA 10</span></th>
        <th style="width: 36px;"></th>
    </tr>
    <tbody id="presupuesto_tb">
    <?php 
    session_start();
    //Insertar detalle de compra
            $sql = mysqli_query($mysqli, "DELETE FROM tmp"
                    . "WHERE  tmp.session_id = '" . session_id() . "'");
        $suma_total=0;
        $sql=mysqli_query($mysqli, "SELECT
        dc.cantidad,
        dc.precio,
        p.id_ingrediente,
        p.descrip_ingrediente,
        u.u_descrip,
        tp.t_ingrediente,
        dc.cantidad * dc.precio AS total,
        CASE
            WHEN p.iva = 0 THEN dc.cantidad * dc.precio -- Exento
            ELSE 0
        END AS exenta,
        CASE
            WHEN p.iva = 5 THEN dc.cantidad * dc.precio -- IVA 5%
            ELSE 0
        END AS iva5,
        CASE
            WHEN p.iva = 10 THEN dc.cantidad * dc.precio -- IVA 10%
            ELSE 0
        END AS iva10
    FROM detalle_orden dc
    JOIN ingrediente p ON p.id_ingrediente = dc.id_ingrediente
    JOIN u_medida u ON u.id_u_medida = p.id_u_medida
    JOIN tipo_ingrediente tp ON tp.id_t_ingrediente = p.id_t_ingrediente
    WHERE dc.id_orden = $id;
    
    ");
        
        $tiva5 = 0;
        $total_general = 0;
        $tiva10 = 0;
        $tivaexenta = 0;
        while($row=mysqli_fetch_array($sql)){
            $exenta = floatval($row['exenta']);  // Asegúrate de convertirlo a float
            $iva5 = floatval($row['iva5']);
            $iva10 = floatval($row['iva10']);
            $insert_tmp = mysqli_query($mysqli, "INSERT INTO "
                    . "`tmp`(`cod_ingrediente`, `cantidad_tmp`, `session_id`, precio_tmp) "
                    . "VALUES (".$row['id_ingrediente'].", ".$row['cantidad'].", "
                    . "'".session_id()."',  ".$row['precio'].")")
                or die('Error'.mysqli_error($mysqli));
            
            //$tiva5 += intval($row['iva5']);
            //$total_general += intval($row['total']);
            //$tiva10 += intval($row['iva10']);
            //$tivaexenta += intval($row['exenta']);

            $tiva5 += is_numeric($row['iva5']) ? intval($row['iva5']) : 0;
            $total_general += is_numeric($row['total']) ? intval($row['total']) : 0;
            $tiva10 += is_numeric($row['iva10']) ? intval($row['iva10']) : 0;
            $tivaexenta += is_numeric($row['exenta']) ? intval($row['exenta']) : 0;

            
            ?>
            <tr>
                <td><?= $row['id_ingrediente'] ?></td>
                <td><?= $row['t_ingrediente'] ?></td>
                <td><?= $row['u_descrip'] ?></td>
                <td><?= $row['descrip_ingrediente'] ?></td>
                <td><span class="pull-right"><?= $row['cantidad']; ?></span></td>
                <td><span class="pull-right"><?= $row['precio']; ?></span></td>
                <td><span class="pull-right"><?= $row['exenta']; ?></span></td>
                <td><span class="pull-right"><?= $row['iva5']; ?></span></td>
                <td><span class="pull-right"><?= $row['iva10']; ?></span></td>
                <td><span class="pull-right"><a href="#" onclick="eliminar('<?php echo $row['id_ingrediente']; ?>')"><i class="glyphicon glyphicon-trash"></i></a></span></td>
            </tr>
            
       <?php }           
       //var_dump($tiva5, $tiva10, $total_general, $tivaexenta);
    ?>
           </tbody>
            <tfoot>
                <tr>
                    <th colspan="6">Sub Totales</th>
                    <th><?=$tivaexenta?></th>
                    <th><?=$tiva5?></th>
                    <th><?=$tiva10?></th>
                </tr>
                <tr>
                    <th colspan="6">TOTAL</th>
                    <th><?=$total_general?></th>
                </tr>
                <tr>
                    <th colspan="2">IVA 5% (<?= round($tiva5 / 21)?>)</th>
                    <th colspan="2">IVA 10% (<?= round($tiva10 / 11)?>)</th>
                    <th colspan="2">Total (<?=round($tiva5 / 21) + round($tiva10 / 11)?>)</th>
                                
                </tr>
                
            <input type="text" name="total_compra" hidden id="total_compra" value='<?=$total_general?>'>
            </tfoot>
</table>