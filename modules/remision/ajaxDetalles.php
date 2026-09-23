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
        <th>Tipo ingrediente</th>
        <th>Unidad de medida</th>
        <th>Ingrediente</th>
        <th><span class="pull-right">Cantidad</span></th>
        <th style="width: 36px;"></th>
    </tr>
    <tbody id="presupuesto_tb">
    <?php 
    session_start();
    $sql = mysqli_query($mysqli, "DELETE FROM  tmp "
                    . "WHERE  tmp.session_id = '" . session_id() . "'");
        $suma_total=0;
        $sql=mysqli_query($mysqli, "SELECT
            dc.cantidad,
            dc.precio,
            p.id_ingrediente,
            p.descrip_ingrediente,
            u.u_descrip,
            tp.t_ingrediente,
            dc.cantidad  * dc.precio as total
            FROM detalle_compra dc 
            JOIN ingrediente p 
            ON p.id_ingrediente = dc.id_ingrediente
            JOIN u_medida u 
            ON u.id_u_medida =  p.id_u_medida
            JOIN tipo_ingrediente tp 
            ON tp.id_t_ingrediente = p.id_t_ingrediente
            WHERE dc.cod_compra = $id");
        
        
        while($row=mysqli_fetch_array($sql)){
            $insert_tmp = mysqli_query($mysqli, "INSERT INTO "
                    . "`tmp`(`cod_ingrediente`, `cantidad_tmp`, `session_id`) "
                    . "VALUES (".$row['id_ingrediente'].", ".$row['cantidad'].", "
                    . "'".session_id()."')")
            ?>
            <tr>
                <td><?= $row['id_ingrediente'] ?></td>
                <td><?= $row['t_ingrediente'] ?></td>
                <td><?= $row['u_descrip'] ?></td>
                <td><?= $row['descrip_ingrediente'] ?></td>
                <td><span class="pull-right"><?= $row['cantidad']; ?></span></td>
                
            </tr>
       <?php }           
    ?>
           </tbody>
           
</table>