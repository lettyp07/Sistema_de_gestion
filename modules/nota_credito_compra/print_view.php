<?php 
    require_once "../../config/database.php";
    if($_GET['act']=='imprimir'){
        if(isset($_GET['id_orden'])){
            $codigo = $_GET['id_orden'];
            //Cabecera de compra
            $cabecera_compra = mysqli_query($mysqli, "SELECT 
            o.cod_nota_credito_compra,
            o.fecha,
            o.hora,
            o.tipo,
            o.total,
            p.razon_social
            FROM nota_credito_compra o 
            JOIN proveedor p 
            ON p.cod_proveedor = o.cod_proveedor
            WHERE o.cod_nota_credito_compra = $codigo")
                                                    or die('Error'.mysqli_error($mysqli));
                                                    while($data = mysqli_fetch_assoc($cabecera_compra)){
                                                        $cod = $data['cod_nota_credito_compra'];
                                                        $proveedor = $data['razon_social'];
                                                        $fecha = $data['fecha'];
                                                        $hora = $data['hora'];
                                                        $fecha_entrega = $data['tipo'];
                                                        $total_compra = $data['total'];
                                                        }
            //Detalle de compra
            $detalle_compra = mysqli_query($mysqli, "SELECT
            d.cantidad,
            d.precio,
            p.descrip_ingrediente,
            u.u_descrip,
            tp.t_ingrediente
            FROM detalle_nota_credito_compra d 
            JOIN ingrediente p on p.id_ingrediente =  d.id_ingrediente
            JOIN tipo_ingrediente tp ON tp.id_t_ingrediente =  p.id_t_ingrediente
            JOIN u_medida u ON u.id_u_medida =  p.id_u_medida
            WHERE d.cod_nota_credito_compra = $codigo ")
                    or die('Error'.mysqli_error($mysqli));

        }
    }
?> 
<!DOCTYPE HTML>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title> Nota de de compra</title>
    </head>
    <body>
        <div align='center'>
            Registro de Nota compra<br>
            <label><strong>Proveedor:</strong><?php echo $proveedor; ?></label><br>
            <label><strong>Fecha:</strong><?php echo $fecha; ?></label><br>
            <label><strong>Tipo de Nota:</strong><?php echo $fecha_entrega; ?></label><br>
            <label><strong>hora:</strong><?php echo $hora; ?></label><br>
           
        </div>
        <hr>
            <div>
                <table width="100%" border="0.3" cellpadding="0" cellspacing="0" align="center">
                    <thead style="background:#e8ecee">
                        <tr class="tabla-title">
                            <th height="20" align="center" valign="middle"><small>Tipo de Ingrediente</small></th>
                            <th height="20" align="center" valign="middle"><small>Ingrediente</small></th>
                            <th height="20" align="center" valign="middle"><small>Unidad de Medida</small></th>
                            <th height="20" align="center" valign="middle"><small>Costo</small></th>
                            <th height="20" align="center" valign="middle"><small>Cantidad</small></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            while ($data2 = mysqli_fetch_assoc($detalle_compra)){
                                $t_p_descrip = $data2['t_ingrediente'];
                                $p_descrip = $data2['descrip_ingrediente'];
                                $u_medida = $data2['u_descrip'];
                                $precio = $data2['precio'];
                                $cantidad = $data2['cantidad'];

                                echo "<tr>
                                        <td width='100' align='left'>$t_p_descrip</td>
                                        <td width='80' align='left'>$p_descrip</td>
                                        <td width='80' align='left'>$u_medida</td>
                                        <td width='80' align='left'>$precio</td>
                                        <td width='80' align='left'>$cantidad</td>
                                      </tr> ";
                            }                        
                            ?>
                    </tbody>
                </table>         
            </div>
            <hr>
            <div align='center'>
             <label> <strong>El total de la compra es: Gs. <?php echo number_format($total_compra); ?></strong></label> 
            </div>
    </body>
</html>
