<section class="content-header">
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
        <li class="active"><a href="?module=ingrediente">Ingredientes</a></li>
    </ol>
    <br> <hr>
    <h1>
        <i class="fa fa-folder icon-title"></i>Datos de ingredientes
        <a class="btn btn-primary btn-social pull-right" href="?module=form_ingrediente&form=add" title="Agregar" data-toggle="tooltip">
        <i class="fa fa-plus"></i>Agregar
        </a>
    </h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
<?php 
if (empty($_GET['alert'])) {
    echo"";
}
elseif ($_GET['alert']==1) {
    echo "<div class='alert alert-success alert-dismissable'>
    <button type='button' class='close' data-dismiss='alert' aria-hidden= 'true'>&times;</button>
    <h4> <i class='icon fa fa-check-circle'></i>Exitoso!</h4>
    Datos registrados correctamente
    </div>";
}
elseif ($_GET['alert']==2) {
    echo "<div class='alert alert-success alert-dismissable'>
    <button type='button' class='close' data-dismiss='alert' aria-hidden= 'true'>&times;</button>
    <h4> <i class='icon fa fa-check-circle'></i>Exitoso!! </h4>
    Datos modificados correctamente
    </div>";
}
elseif ($_GET['alert']==3) {
    echo "<div class='alert alert-success alert-dismissable'>
    <button type='button' class='close' data-dismiss='alert' aria-hidden= 'true'>&times;</button>
    <h4> <i class='icon fa fa-check-circle'></i>Exitoso!! </h4>
    Datos eliminados correctamente
    </div>";
}
elseif ($_GET['alert']==4) {
    echo "<div class='alert alert-danger alert-dismissable'>
    <button type='button' class='close' data-dismiss='alert' aria-hidden= 'true'>&times;</button>
    <h4> <i class='icon fa fa-check-circle'></i>Error!! </h4>
    No se pudo realizar la operacion
    </div>";
}
?>
            <div class="box box-primary">
                <div class="box-body">
                    <section class="content-header">
                        <a class="btn btn-warning btn-social pull-right" href="modules/ingrediente/print.php" target="_blank">
                            <i class="fa fa-print"></i>Imprimir
                        </a>
                    </section>
                    <table id="dataTables1" class="table table-bordered table-striped table-hover">
                        <h2>Lista de Ingredientes</h2>
                        <thead>
                            <tr>
                                <th class='center'>ID</th>
                                <th class='center'>Tipo ingrediente</th>
                                <th class='center'>Unidad de medida</th>
                                <th class='center'>Ingrediente</th>
                              
                                <th class='center'>Acción</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $nro=1;
                            $query = mysqli_query($mysqli, "SELECT * FROM v_ingrediente")
                                                            or die('Error'.mysqli_error($mysqli));
                            while ($data = mysqli_fetch_assoc($query)) {
                            $cod_producto = $data['id_ingrediente'];
                            $t_p_descrip = $data['t_ingrediente'];
                            $u_descrip = $data['u_descrip'];
                            $p_descrip = $data['descrip_ingrediente'];
                         
                            echo "<tr>
                            <td class='center'> $cod_producto</td>
                            <td class='center'> $t_p_descrip</td>
                            <td class='center'>$u_descrip </td>
                            <td class='center'>$p_descrip </td>
                            
                            <td class='center' width='80'>
                            <div>
                            <a data-toggle='tooltip' data-placement='top' title='Modificar datos de ingrediente'
                            Style='margin-right: 5px' class='btn btn-primary btn-sm'
                            href='?module=form_ingrediente&form=edit&id=$data[id_ingrediente]'>
                            <i class='glyphicon glyphicon-edit' style='color:#fff'></i></a> ";
                            ?>
                            <a data-toggle="tooltip" data-data-placement="top" title="Eliminar datos"
                            class="btn btn-danger btn-sm" href="modules/ingrediente/proses.php?act=delete&id_ingrediente=<?php echo $data['id_ingrediente']; ?>"
                            onclick="return confirm('¿Estas seguro/a de eliminar <?php echo $data['id_ingrediente']; ?>?')">
                        <i class="glyphicon glyphicon-trash"></i>
                    </a>
                            <?php
                            echo "</div>
                            </td>
                            </tr>"?>
                        
                        <?php } ?>
                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</section>