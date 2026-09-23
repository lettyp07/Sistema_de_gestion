<section class="content-header">
<ol class="breadcrumb">
    <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
    <li class="active"><a href="?module=stock">Stock</a></li>
</ol><br><hr>
<h1>
    <i class="fa fa-folder icon-title"></i>Stock de ingredientes
</h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <table id="dataTables1" class="table table-bordered table-striped table-hover">
                        <h2>Stock de ingredientes</h2>
                        <thead>
                            <tr>
                                <th class="center">ID</th>
                                <th class="center">Tipo ingrediente</th>
                                <th class="center">Ingrediente</th>
                                <th class="center">Unidad medida</th>
                                <th class="center">Cantidad disponible</th>
                                <th class="center">Cantidad Mínima</th>
                                <th class="center">Advertencia</th> 
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $nro=1;
                            // Consulta de stock, ahora con la cantidad mínima
                            $query = mysqli_query($mysqli, "SELECT * FROM v_stock") 
                            or die('Error'.mysqli_error($mysqli));

                            while($data = mysqli_fetch_assoc($query)){
                               $cod_producto = $data['id_ingrediente'];
                               $t_p_descrip = $data['t_ingrediente'];
                               $p_descrip = $data['descrip_ingrediente'];
                               $u_descrip = $data['u_descrip'];
                               $cantidad = $data['cantidad'];
                               $cantidad_minima = $data['cantidad_minima']; 

                               // Verificar si la cantidad es menor que la mínima
                               $advertencia = '';
                               if ($cantidad < $cantidad_minima) {
                                   $advertencia = '<span style="color: red; font-weight: bold;">¡Stock bajo!</span>';
                               }

                               echo "<tr>
                                   <td class='center'>$cod_producto</td>
                                   <td class='center'>$t_p_descrip</td>
                                   <td class='center'>$p_descrip</td>
                                   <td class='center'>$u_descrip</td>
                                   <td class='center'>$cantidad</td>
                                   <td class='center'>$cantidad_minima</td> <!-- Mostrar cantidad mínima -->
                                   <td class='center'>$advertencia</td> <!-- Mostrar advertencia -->
                               </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
