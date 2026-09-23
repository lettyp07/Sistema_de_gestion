<?php 
    require_once "../../config/database.php";
    if($_GET['act']=='imprimir'){
        if(isset($_GET['id_control_produccion'])){
            $codigo = $_GET['id_control_produccion'];
            //Cabecera de compra
            $cabecera_compra = mysqli_query($mysqli, "SELECT p.*,u.username, s.sucursal 
                               FROM control_produccion p 
                               JOIN usuarios u
                               ON u.id_user = p.id_user
                               JOIN sucursal s
                               ON s.id_sucursal = u.id_sucursal
                               where p.id_control_produccion= $codigo")
                                                    or die('Error'.mysqli_error($mysqli));
                                                    while($data = mysqli_fetch_assoc($cabecera_compra)){
                                                         $cod = $data['id_control_produccion'];
                                                        $fecha = $data['fecha'];
                                                        $hora = $data['hora'];
                                                        $estado = $data['estado'];
                                                        $sucursal = $data['sucursal'];
                                                        }
            //Detalle de compra
            $detalle_compra = mysqli_query($mysqli, "SELECT 
        dp.*, 
        p.descrip  as producto,
        tp.t_producto 
        FROM detalle_control_produccion  dp
        join producto p on p.id_producto  = dp.id_producto 
        join tipo_producto tp on tp.id_t_producto = p.id_t_producto 
        WHERE dp.id_control_produccion  = $codigo ")
                    or die('Error'.mysqli_error($mysqli));

        }
    }
?> 
<!DOCTYPE HTML>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>CONTROL DE PRODUCCION</title>
    </head>
    <body>
        <div align='center'>
            Registro de Control de Produccion<br>
            
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
                            <th height="20" align="center" valign="middle"><small>Tipo Producto</small></th>
                            <th height="20" align="center" valign="middle"><small>Ajuste</small></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            while ($data2 = mysqli_fetch_assoc($detalle_compra)){
                                $producto = $data2['producto'];
                                $t_producto = $data2['t_producto'];
                                $ajuste = $data2['ajuste'];

                                echo "<tr>
                                        <td width='100' align='left'>$producto</td>
                                        <td width='80' align='left'>$t_producto</td>
                                        <td width='80' align='left'>$ajuste</td>
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
