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
        <th>Unidad medida</th>
        <th>Ingrediente</th>
        <th><span class="pull-right">Cantidad</span></th>
        <th><span class="pull-right">Costo</span></th>
        <th><span class="pull-right">Total</span></th>
        <th style="width: 36px;"></th>
    </tr>
    <tbody id="presupuesto_tb">
    <?php 
        $suma_total=0;
        $sql=mysqli_query($mysqli, "SELECT
            dc.cantidad,
            p.id_ingrediente,
            p.descrip_ingrediente,
            u.u_descrip,
            tp.t_ingrediente
            FROM detalle_pedido_compra dc 
            JOIN ingrediente p 
            ON p.id_ingrediente = dc.id_ingrediente
            JOIN u_medida u 
            ON u.id_u_medida =  p.id_u_medida
            JOIN tipo_ingrediente tp 
            ON tp.id_t_ingrediente = p.id_t_ingrediente
            WHERE dc.id_pedido = $id");
        
        
        while($row=mysqli_fetch_array($sql)){
            ?>
            <tr>
                <td><?= $row['id_ingrediente'] ?></td>
                <td><?= $row['t_ingrediente'] ?></td>
                <td><?= $row['u_descrip'] ?></td>
                <td><?= $row['descrip_ingrediente'] ?></td>
                <td><span class="pull-right"><?= $row['cantidad']; ?></span></td>
                <td><span class="pull-right"><input type="text" class="form-control costo_presupuesto" value="0"></span></td>
                <td><span class="pull-right">0</span></td>
                <td><span class="pull-right"><a href="#" onclick="eliminar('<?php echo $row['id_ingrediente']; ?>')"><i class="glyphicon glyphicon-trash"></i></a></span></td>
            </tr>
       <?php }           
    ?>
           </tbody>
            <tfoot>
                <tr>
                    <th colspan="6">TOTAL</th>
                    <th id="total_general">0</th>
                </tr>
            </tfoot>
</table>