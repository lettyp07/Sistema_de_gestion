<?php 
    require_once "../../config/database.php";
    if($_GET['act']=='imprimir'){
        if(isset($_GET['id_control_produccion'])){
            $codigo = $_GET['id_control_produccion'];
            //Cabecera de compra
            $cabecera_compra = mysqli_query($mysqli, "SELECT p.*,u.username, s.sucursal 
                               FROM costo_produccion p 
                               JOIN usuarios u
                               ON u.id_user = p.id_user
                               JOIN sucursal s
                               ON s.id_sucursal = u.id_sucursal
                               where p.id_costo_produccion= $codigo")
                                                    or die('Error'.mysqli_error($mysqli));
                                                    while($data = mysqli_fetch_assoc($cabecera_compra)){
                                                         $cod = $data['id_costo_produccion'];
                                                        $fecha = $data['fecha'];
                                                        $hora = $data['hora'];
                                                        $estado = $data['estado'];
                                                        $sucursal = $data['sucursal'];
                                                        }
            //Detalle de compra
            $detalle_compra = mysqli_query($mysqli, "SELECT 
        dp.*, 
        p.descrip  as producto,
        dp.costo_ingredientes,
        dp.costo_hora,
        dp.costo_total
        FROM detalle_costo dp
        join producto p on p.id_producto  = dp.id_producto 
        WHERE dp.id_costo_produccion  = $codigo ")
                    or die('Error'.mysqli_error($mysqli));

        }
    }
?> 
<!DOCTYPE HTML>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>COSTO DE PRODUCCION</title>
    </head>
    <body>
        <div align='center'>
            Registro de Costo de Produccion<br>
            
            <label><strong>Fecha:</strong><?php echo $fecha; ?></label><br>
            <label><strong>hora:</strong><?php echo $hora; ?></label><br>
            <label><strong>Sucursal:</strong><?php echo $sucursal; ?></label><br>
            <label><strong>Estado:</strong><?php echo $estado; ?></label><br>
           
        </div>
        <hr>
            <div>
                <table width="100%" border="0.3" cellpadding="0" cellspacing="0" align="center">
                    <thead style="background:#e8ecee">
                        <tr class="tabla-title">
                            <th height="20" align="center" valign="middle"><small>Producto</small></th>
                            <th height="20" align="center" valign="middle"><small>Costo Ingrediente</small></th>
                            <th height="20" align="center" valign="middle"><small>Costo mano de obra</small></th>
                            <th height="20" align="center" valign="middle"><small>Costo total</small></th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            while ($data2 = mysqli_fetch_assoc($detalle_compra)){
                                $producto = $data2['producto'];
                                $t_producto = $data2['costo_ingredientes'];
                                $ajuste = $data2['costo_hora'];
                                $ajuste2 = $data2['costo_total'];

                                echo "<tr>
                                        <td width='100' align='left'>$producto</td>
                                        <td width='80' align='left'>$t_producto</td>
                                        <td width='80' align='left'>$ajuste</td>
                                        <td width='80' align='left'>$ajuste2</td>

                                      </tr> ";
                            }                        
                            ?>
                    </tbody>
                </table>         
            </div>
            <hr>
            <div align='center'>
            
            </div>
    </body>
</html>
