<section class="content-header">
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
        <li class="active"><a href="?module=cliente">Cliente</a></li>
    </ol>
    <br> <hr>
    <h1>
        <i class="fa fa-folder icon-title"></i>Datos del Cliente
        <a class="btn btn-primary btn-social pull-right" href="?module=form_cliente&form=add" title="Agregar" data-toggle="tooltip">
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
            <!-- Formulario para seleccionar el rango de fechas -->
            <form id="printForm" action="modules/liente/print.php" method="GET" target="_blank" class="form-inline pull-right">
                <div class="form-group">
                    <label for="start_date">Desde:</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" required>
                </div>
                <div class="form-group" style="margin-left: 10px;">
                    <label for="end_date">Hasta:</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-warning btn-social">
                    <i class="fa fa-print"></i> Imprimir
                </button>
            </form>
        </section>

        <table id="dataTables1" class="table table-bordered table-striped table-hover">
            <h2>Lista de cliente</h2>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>CI</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Acción</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $nro = 1;
                $query = mysqli_query($mysqli, "SELECT * FROM clientes")
                    or die('Error' . mysqli_error($mysqli));
                while ($data = mysqli_fetch_assoc($query)) {
                    $cod_proveedor = $data['id_cliente'];
                    $razon_social = $data['cli_nombre'];
                    $ruc = $data['ci_ruc'];
                    $direccion = $data['cli_direccion'];
                    $telefono = $data['cli_telefono'];
                    echo "<tr>
                    <td class='center'> $cod_proveedor</td>
                    <td class='center'>$razon_social</td>
                    <td class='center'>$ruc</td>
                    <td class='center'>$direccion </td>
                    <td class='center'>$telefono</td>
                    <td class='center' width='80'>
                    <div>
                    <a data-toggle='tooltip' data-placement='top' title='Modificar datos de Clientes'
                    style='margin-right: 5px' class='btn btn-primary btn-sm'
                    href='?module=form_cliente&form=edit&id=$data[id_cliente]'>
                    <i class='glyphicon glyphicon-edit' style='color:#fff'></i></a> ";
                    ?>
                    <a data-toggle="tooltip" data-data-placement="top" title="Eliminar datos"
                       class="btn btn-danger btn-sm" href="modules/cliente/proses.php?act=delete&id_cliente=<?php echo $data['id_cliente']; ?>"
                       onclick="return confirm('¿Estás seguro/a de eliminar <?php echo $data['cli_nombre']; ?>?')">
                        <i class="glyphicon glyphicon-trash"></i>
                    </a>
                    <?php
                    echo "</div>
                    </td>
                    </tr>" ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>


        </div>

    </div>

</section>