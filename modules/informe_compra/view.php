<section class="content-header">
<ol class="breadcrumb">
    <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
    <li class="active"><a href="?module=informe_compra">Informe compra</a></li>
</ol><br><hr>
<h1>
    <i class="fa fa-folder icon-title"></i>Datos de informe compra
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
                        <form id="printForm" action="modules/informe_compra/print.php" method="GET" target="_blank" class="form-inline pull-right">
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
                        <h2>Lista de informe compra</h2>
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
                            $query = mysqli_query($mysqli, "SELECT * FROM v_compra")
                            or die('Error'.mysqli_error($mysqli));

                            while($data = mysqli_fetch_assoc($query)){
                               $cod = $data['cod_compra'];
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
                                ?>  
                                <a data-toggle="tooltip" data-placement="top" title="Imprimir" class="btn btn-warning btn-sm" 
                                href="modules/informe_compra/print.php?act=imprimir&cod_compra=<?php echo $data['cod_compra']; ?>" target="_blank">
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