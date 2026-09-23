<section class="content-header">
<ol class="breadcrumb">
    <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
    <li class="active"><a href="?module=pedido_cliente">Pedidos cliente</a></li>
</ol><br><hr>
<h1>
    <i class="fa fa-folder icon-title"></i>Datos de pedido cliente
    <a class="btn btn-primary btn-social pull-right" href="?module=form_pedido_cliente&form=add" title="Agregar" data-toggle="tooltip">
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
                        <form id="printForm" action="modules/pedido_cliente/print.php" method="GET" target="_blank" class="form-inline pull-right">
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
                        
                    </section>
                    <table id="dataTables1" class="table table-bordered table-striped table-hover">
                        <h2>Lista de pedido cliente</h2>
                        <thead>
                            <tr>
                                <th class="center">Id</th>
                                <th class="center">Fecha</th>
                                <th class="center">Sucursal</th>
                                <th class="center">Estado</th>
                                <th class="center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $nro=1;
                            $query = mysqli_query($mysqli, "SELECT p.*, s.sucursal, u.username
                             FROM pedido_cliente p
                             JOIN usuarios u
                             ON u.id_user = p.id_user
                             JOIN sucursal s
                             ON s.id_sucursal = p.id_sucursal")
                            or die('Error'.mysqli_error($mysqli));

                            while($data = mysqli_fetch_assoc($query)){
                               $cod = $data['id_pedido_cliente'];
                               $fecha = $data['fecha'];
                               $sucursal = $data['sucursal'];
                               $estado = $data['estado'];
                             
                               echo "<tr>
                               <td class='center'>$cod</td>
                               <td class='center'>$fecha</td>
                               <td class='center'>$sucursal</td>
                               <td class='center'>$estado</td>
                              

                               <td class='center' width='200'>
                               <div>";
                               if ($data['estado']=='activo') { ?>
                                <a data-toggle="tooltip" data-placement="top" title="Modificar"
                                class="btn btn-primary btn-sm" href="modules/pedido_cliente/proses.php?act=update&id_pedido_cliente=<?php echo $data['id_pedido_cliente']; ?>" target="_blank">
                                <i style="color:#fff" class="glyphicon glyphicon-edit"></i>
                                </a>   
                                <?php 
                                }
                                if ($data['estado']=='activo') { ?>
                               <a data-toggle="tooltip" data-placement="top" title="Anular" class="btn btn-danger btn-sm"
                                href="modules/pedido_cliente/proses.php?act=anular&id_pedido_cliente=<?php echo $data['id_pedido_cliente']; ?>"
                                onclick ="return confirm('Estás seguro/a de anular <?php echo $data['id_pedido_cliente']; ?>?');">
                                    <i style="color:#000" class="glyphicon glyphicon-remove"></i>
                                </a>
                                <?php 
                                }
                                ?>  
                                <a data-toggle="tooltip" data-placement="top" title="Imprimir" class="btn btn-warning btn-sm" 
                                href="modules/pedido_cliente/print.php?act=imprimir&id_pedido_cliente=<?php echo $data['id_pedido_cliente']; ?>" target="_blank">
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
                <h1>
                    <a class="btn btn-danger pull-right" href="?module=start" title="Salir" >
                    Salir
                    </a>
                </h1>
        </div>
    </div>
</section>