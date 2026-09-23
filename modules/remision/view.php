<section class="content-header">
<ol class="breadcrumb">
    <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
    <li class="active"><a href="?module=remision">Nota de Remision</a></li>
</ol><br><hr>
<h1>
    <i class="fa fa-folder icon-title"></i>Datos Remisión de Compras
    <a class="btn btn-primary btn-social pull-right" href="?module=form_remision&form=add" title="Agregar" data-toggle="tooltip">
        <i class="fa fa-plus"></i>Agregar
    </a>
</h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php 
            if(empty($_GET['alert'])){
                echo "";
            }
            elseif($_GET['alert']==1){
                echo "<div class='alert alert-success alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4>  <i class='icon fa fa-check-circle'></i> Exitoso!</h4>
                Datos registrados correctamente
                </div>";
            }
        
           
            elseif($_GET['alert']==2){
                echo "<div class='alert alert-danger alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4>  <i class='icon fa fa-check-circle'></i> Exitoso!</h4>
                Datos anulados correctamente
                </div>";
            }

            elseif($_GET['alert']==3){
                echo "<div class='alert alert-danger alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4>  <i class='icon fa fa-check-circle'></i> Error!</h4>
                No se puedo realizar la acción
                </div>";
            }
            ?>

            <div class="box box-primary">
                <div class="box-body">
                <section class="content-header">
                        <!-- Formulario para seleccionar el rango de fechas -->
                        <form id="printForm" action="modules/remision/print.php" method="GET" target="_blank" class="form-inline pull-right">
                            <div class="form-group">
                                <label for="start_date">Desde:</label>
                                <input type="date" id="start_date" name="start_date" class="form-control" value="<?php echo date("Y-m-d"); ?>"
                                required>
                            </div>
                            <div class="form-group" style="margin-left: 10px;">
                                <label for="end_date">Hasta:</label>
                                <input type="date" id="end_date" name="end_date" class="form-control" 
                                value="<?php echo date("Y-m-d"); ?>"required>
                            </div>
                            <button type="submit" class="btn btn-warning btn-social">
                                <i class="fa fa-print"></i> Imprimir
                            </button>
                        </form>
                        
                    <table id="dataTables1" class="table table-bordered table-striped table-hover">
                        <h2>Lista de Nota Remisión Compras</h2>
                        <thead>
                            <tr>
                                <th class="center">Id</th>
                                <th class="center">Fecha</th>                                  
                                <th class="center">Salida</th>                                  
                                <th class="center">Llegada</th>                                  
                                <th class="center">Chofer</th>                               
                                <th class="center">Estado</th>                                
                                <th class="center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $nro=1;
                            $query = mysqli_query($mysqli, "SELECT
                                pc.cod_remision_compra,
                                pc.fecha_registro,
                                pc.estado,
                                pc.punto_salida,
                                pc.punto_llegada,
                                pc.chofer
                                FROM remision_compras pc
                                WHERE pc.estado <> 'anulado'
                                GROUP BY pc.cod_remision_compra")
                            or die('Error'.mysqli_error($mysqli));

                            while($data = mysqli_fetch_assoc($query)){
                               $cod = $data['cod_remision_compra'];
                               $fecha = $data['fecha_registro'];
                               $estado = $data['estado'];
                               $salida = $data['punto_salida'];
                               $llegada = $data['punto_llegada'];
                               $chofer = $data['chofer'];


                               echo "<tr>
                               <td class='center'>$cod</td>
                               <td class='center'>$fecha</td>
                               <td class='center'> $salida</td>
                               <td class='center'> $llegada</td>
                               <td class='center'> $chofer</td>
                               <td class='center'>$estado</td>                               
                               <td class='center' width='150'>
                               <div>";
                               if ($data['estado']=='activo') { ?>
                                <a data-toggle="tooltip" data-placement="top" title="Modificar"
                                class="btn btn-primary btn-sm" href="modules/remision/proses.php?act=update&cod_remision_compra=<?php echo $data['cod_remision_compra']; ?>" target="_blank">
                                <i style="color:#fff" class="glyphicon glyphicon-edit"></i>
                                </a>   
                                <?php 
                                }
                                if ($data['estado']=='activo') { ?>
                               <a data-toggle="tooltip" data-placement="top" title="Anular" class="btn btn-danger btn-sm"
                                href="modules/remision/proses.php?act=anular&cod_remision_compra=<?php echo $data['cod_remision_compra']; ?>"
                                onclick ="return confirm('Estás seguro/a de anular <?php echo $data['cod_remision_compra']; ?>?');">
                                    <i style="color:#000" class="glyphicon glyphicon-remove"></i>
                                </a>
                                <?php 
                                }
                                ?>  
                                <a data-toggle="tooltip" data-placement="top" title="Imprimir" class="btn btn-warning btn-sm" 
                                href="modules/remision/print.php?act=imprimir&cod_remision_compra=<?php echo $data['cod_remision_compra']; ?>" target="_blank">
                                    <i style="color:#000" class="fa fa-print"></i>
                                </a>
                                <?php 
                                ?>
                                <?php echo "</div>
                                </td>
                                </tr>" ?>
                            <?php }                               
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</section>