<?php 
    require_once "../../config/database.php";
    if($_GET['act']=='imprimir'){
        if(isset($_GET['id_etapa_produccion'])){
            $codigo = $_GET['id_etapa_produccion'];
            //Cabecera de compra
            $cabecera_compra = mysqli_query($mysqli, "SELECT 
                            o.id_etapa_produccion,
                            o.fecha,
                            o.hora ,
                            o.estado ,
                            s.sucursal ,
                            e.descrip  as etapa
                            FROM etapa_produccion o 
                            join sucursal s on s.id_sucursal = o.id_sucursal 
                            join etapas e on e.id_etapa  = o.id_etapa 
                            WHERE o.id_etapa_produccion =  $codigo")
                                                    or die('Error'.mysqli_error($mysqli));
                                                    while($data = mysqli_fetch_assoc($cabecera_compra)){
                                                         $cod = $data['id_etapa_produccion'];
                                                        $fecha = $data['fecha'];
                                                        $hora = $data['hora'];
                                                        $estado = $data['estado'];
                                                        $sucursal = $data['sucursal'];
                                                        $etapa = $data['etapa'];
                                                        }
            //Detalle de compra
            $detalle_compra = mysqli_query($mysqli, "SELECT 
        dp.*, 
        e.descrip,
        e2.nombre ,
        p.descrip  as producto,
        tp.t_producto 
        FROM detalle_etapa_produccion dp
        JOIN etapa_produccion ep ON ep.id_etapa_produccion = dp.id_etapa_produccion
        join empleados e2 on e2.id_empleados = dp.id_empleado 
        JOIN etapas e ON e.id_etapa = ep.id_etapa
        join producto p on p.id_producto  = dp.id_producto 
        join tipo_producto tp on tp.id_t_producto = p.id_t_producto 
        WHERE dp.id_etapa_produccion = $codigo ")
                    or die('Error'.mysqli_error($mysqli));

        }
    }
?> 
<!DOCTYPE HTML>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>ETAPA DE PRODUCCION</title>
    </head>
    <body>
        <div align='center'>
            Registro de Etapa de Produccion<br>
            
            <label><strong>Fecha:</strong><?php echo $fecha; ?></label><br>
            <label><strong>hora:</strong><?php echo $hora; ?></label><br>
            <label><strong>Sucursal:</strong><?php echo $sucursal; ?></label><br>
            <label><strong>Etapa:</strong><?php echo $etapa; ?></label><br>
            <label><strong>Estado:</strong><?php echo $estado; ?></label><br>
           
        </div>
        <hr>
            <div>
                <table width="100%" border="0.3" cellpadding="0" cellspacing="0" align="center">
                    <thead style="background:#e8ecee">
                        <tr class="tabla-title">
                            <th height="20" align="center" valign="middle"><small>Producto</small></th>
                            <th height="20" align="center" valign="middle"><small>Cantidad</small></th>
                            <th height="20" align="center" valign="middle"><small>Tipo Producto</small></th>
                            <th height="20" align="center" valign="middle"><small>Empleado</small></th>
                            <th height="20" align="center" valign="middle"><small>Hora Inicio</small></th>
                            <th height="20" align="center" valign="middle"><small>Hora Fin</small></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            while ($data2 = mysqli_fetch_assoc($detalle_compra)){
                                $producto = $data2['producto'];
                                $cantidad = $data2['cantidad'];
                                $t_producto = $data2['t_producto'];
                                $nombre = $data2['nombre'];
                                $hora_ini = $data2['hora_ini'];
                                $hora_fin = $data2['hora_fin'];

                                echo "<tr>
                                        <td width='100' align='left'>$producto</td>
                                        <td width='80' align='left'>$cantidad</td>
                                        <td width='80' align='left'>$t_producto</td>
                                        <td width='80' align='left'>$nombre</td>
                                        <td width='80' align='left'>$hora_ini</td>
                                        <td width='80' align='left'>$hora_fin</td>
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
