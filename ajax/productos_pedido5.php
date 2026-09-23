<?php 
require_once '../config/database.php';

$action = (isset($_REQUEST['action']) && $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
if($action == 'ajax'){
    $x = mysqli_real_escape_string($mysqli,(strip_tags($_REQUEST['x'],ENT_QUOTES)));
    $aColumns = array('id_producto', 'id_t_producto', 'id_u_medida', 'descrip');
    $sTable = "producto";
    $sWhere = "";
    if($_GET['x'] != ""){
       $sWhere = "AND (";
       for ($i=0; $i<count($aColumns); $i++){
           $sWhere .=$aColumns[$i]." LIKE '%".$x."%' OR ";
       }
       $sWhere = substr_replace($sWhere, "", -3);
       $sWhere .= ')';
    }
    // Paginación
    include 'paginacion.php';
    $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
    $per_page = 5;
    $adjacents = 4;
    $offset = ($page -1) * $per_page;

    // Contar total de registros
    $count_query = mysqli_query($mysqli, "SELECT count(*) AS numrows FROM $sTable 
                                          WHERE id_producto IN (
                                              SELECT DISTINCT id_producto 
                                              FROM receta
                                          ) $sWhere");
    $row=mysqli_fetch_array($count_query);
    $numrows = $row['numrows'];
    $total_pages = ceil($numrows/$per_page);
    $reload='./index.php';

    // Consulta con filtro de productos que tienen recetas
    $sql = "SELECT * FROM $sTable 
            WHERE id_producto IN (
                SELECT DISTINCT id_producto 
                FROM receta
            ) 
            $sWhere 
            LIMIT $offset, $per_page";
    $query = mysqli_query($mysqli,$sql);

    if($numrows > 0){ ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <tr class="warning">
                    <th>Código</th>
                    <th>Tipo producto</th>
                    <th>Unidad Medida</th>
                    <th>Producto</th>
                    <th><span class="pull-right">Cantidad</span></th>
                    <th style="width:36px;">Seleccionar</th>
                </tr>
                <?php 
                while ($row=mysqli_fetch_array($query)){
                    $id_producto = $row['id_producto'];
                    $descrip_producto = $row['descrip'];

                    $codigo_tproducto = $row['id_t_producto'];
                    $sql_tproducto = mysqli_query($mysqli, "SELECT t_producto FROM tipo_producto WHERE id_t_producto='$codigo_tproducto'");
                    $rw_tproducto = mysqli_fetch_array($sql_tproducto);
                    $tproducto_nombre = $rw_tproducto['t_producto'];

                    $id_u_medida = $row['id_u_medida'];
                    $sql_umedida = mysqli_query($mysqli, "SELECT u_descrip FROM u_medida WHERE id_u_medida='$id_u_medida'");
                    $rw_u_medida = mysqli_fetch_array($sql_umedida);
                    $u_medida_nombre = $rw_u_medida['u_descrip'];

                    //$precio_compra=$row['precio']; ?>
                <tr>
                    <td><?php echo $id_producto; ?></td>
                    <td><?php echo $tproducto_nombre; ?></td>
                    <td><?php echo $u_medida_nombre; ?></td>
                    <td><?php echo $descrip_producto; ?></td>
                    <td class="col-xs-1">
                        <div class="pull-right">
                            <input type="text" class="form-control" style="text-align:right" id="cantidad_<?php echo $id_producto;?>" value="1">
                        </div>
                    </td>
                    <td><span class="pull-right"><a href="#" onclick="agregar('<?php echo $id_producto; ?>')"><i class="glyphicon glyphicon-plus"></i></a></span>
                    </td>
                </tr>    
                <?php }
                ?>
                <tr>
                    <td colspan=5><span class="pull-right">
                    <?php echo paginate($reload, $page, $total_pages, $adjacents);?>
                    </span></td>
                </tr>
            </table>
        </div>
    <?php }
}
?>
