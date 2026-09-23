<section class="content-header">
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
        <li class="active"><a href="?module=ciudad">Ciudad</a></li>
    </ol>
    <br> <hr>
    <h1>
        <i class="fa fa-folder icon-title"></i>Datos de la Ciudad
        <a class="btn btn-primary btn-social pull-right" href="?module=form_ciudad&form=add" title="Agregar" data-toggle="tooltip">
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
                        <a class="btn btn-warning btn-social pull-right" href="modules/ciudad/print.php" target="_blank">
                            <i class="fa fa-print"></i>Imprimir
                        </a>
                    </section>
                    <table id="dataTables1" class="table table-bordered table-striped table-hover">
                        <h2>Lista de Ciudades</h2>
                        <thead>
                            <tr>
                                <th class='center'>Código</th>
                                <th class='center'>Ciudad</th>
                                <th class='center'>Departamento</th>
                                <th class='center'>Acción</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $nro=1;
                            $query = mysqli_query($mysqli, "SELECT cod_ciudad, descrip_ciudad, dep.id_departamento, dep.dep_descripcion
                            FROM ciudad ciud
                            JOIN departamento dep
                            WHERE ciud.id_departamento=dep.id_departamento")
                                                            or die('Error'.mysqli_error($mysqli));
                            while ($data = mysqli_fetch_assoc($query)) {
                            $cod_ciudad = $data['cod_ciudad'];
                            $descrip_ciudad = $data['descrip_ciudad'];
                            $dep_descripcion = $data['dep_descripcion'];
                            echo "<tr>
                            <td class='center'> $cod_ciudad</td>
                            <td class='center'>$descrip_ciudad</td>
                            <td class='center'>$dep_descripcion </td>
                            <td class='center' width='80'>
                            <div>
                            <a data-toggle='tooltip' data-placement='top' title='Modificar datos de Ciudad'
                            Style='margin-right: 5px' class='btn btn-primary btn-sm'
                            href='?module=form_ciudad&form=edit&id=$data[cod_ciudad]'>
                            <i class='glyphicon glyphicon-edit' style='color:#fff'></i></a> ";
                            ?>
                            <a data-toggle="tooltip" data-data-placement="top" title="Eliminar datos"
                            class="btn btn-danger btn-sm" href="modules/ciudad/proses.php?act=delete&cod_ciudad=<?php echo $data['cod_ciudad']; ?>"
                            onclick="return confirm('¿Estas seguro/a de eliminar <?php echo $data['descrip_ciudad']; ?>?')">
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