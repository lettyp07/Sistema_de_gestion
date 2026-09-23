<?php 
session_start();
$session_id = session_id();
if(isset($_POST['id'])){$id=$_POST['id'];}
if(isset($_POST['cantidad'])){$cantidad = $_POST['cantidad'];}


require_once '../config/database.php';

if(!empty($id) and !empty($cantidad)){
    $insert_tmp = mysqli_query($mysqli, "INSERT INTO tmppp (cod_producto, cantidad_tmp, session_id) 
    VALUES('$id', '$cantidad','$session_id')");
}
if(isset($_GET['id'])){
    $id=intval($_GET['id']);
    $delete=mysqli_query($mysqli, "DELETE FROM tmppp WHERE id_tmp='".$id."'");
}
?>
<table class="table table-bordered table-striped table-hover">
    <tr class="warning">
        <th>Código</th>
        <th>Tipo producto</th>
        <th>Unidad medida</th>
        <th>Producto</th>
        <th><span class="pull-right">Cantidad</span></th>
        <th style="width: 36px;"></th>
    </tr>
    <?php 
        $suma_total=0;
        $sql=mysqli_query($mysqli, "SELECT * FROM producto, tmppp WHERE producto.id_producto=tmppp.cod_producto and tmppp.session_id='".$session_id."'");
        while($row=mysqli_fetch_array($sql)){
            $id_tmp=$row['id_tmp'];
            $codigo_producto=$row['id_producto'];
            $descrip_producto=$row['descrip'];
            $cantidad=$row['cantidad_tmp'];

            $codigo_tproducto=$row['id_t_producto'];
            $sql_tproducto = mysqli_query($mysqli, "SELECT t_producto FROM tipo_producto WHERE id_t_producto='$codigo_tproducto'");
            $rw_tproducto = mysqli_fetch_array($sql_tproducto);
            $tproducto_nombre= $rw_tproducto['t_producto'];

            $id_u_medida=$row['id_u_medida'];
            $sql_umedida = mysqli_query($mysqli, "SELECT u_descrip FROM u_medida WHERE id_u_medida='$id_u_medida'");
            $rw_u_medida = mysqli_fetch_array($sql_umedida);
            $u_medida_nombre= $rw_u_medida['u_descrip'];

            ?>
            <tr>
                <td><?php echo $codigo_producto; ?></td>
                <td><?php echo $tproducto_nombre; ?></td>
                <td><?php echo $u_medida_nombre; ?></td>
                <td><?php echo $descrip_producto; ?></td>
                <td><span class="pull-right"><?php echo $cantidad; ?></span></td>
                <td><span class="pull-right"><a href="#" onclick="eliminar('<?php echo $id_tmp; ?>')"><i class="glyphicon glyphicon-trash"></i></a></span></td>
            </tr>
       <?php }           
    ?>
            <tr>
                <?php if(empty($codigo_producto)){
                    $codigo_producto=0;
                }else {
                    $codigo_producto;
                } ?>
                <input type="hidden" class="form-control" name="codigo_producto" value="<?php echo $codigo_producto; ?>">
                <?php if(empty($cantidad)){
                    $cantidad=0;
                }else {
                    $cantidad;
                } ?>
                <input type="hidden" class="form-control" name="cantidad" value="<?php echo $cantidad; ?>">
            </tr>
</table>