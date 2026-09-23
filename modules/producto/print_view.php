<?php  
require_once "../../config/database.php";

$query = mysqli_query($mysqli, "SELECT p.*, tp.t_producto, u.u_descrip
                                FROM producto p
                                JOIN tipo_producto tp ON tp.id_t_producto = p.id_t_producto
                                JOIN u_medida u ON u.id_u_medida = p.id_u_medida")
                                or die('Error'.mysqli_error($mysqli));

$count = mysqli_num_rows($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../assets/img/favicon.ico">
    <title>Reporte de Producto</title>
</head>
<body>
    <div align="center">
        <img src="../../images/user/producto.jpg"  width="700" height="500">
    </div>
    <div>
        Reporte de producto
    </div>
    <div align="center">
        cantidad: <?php echo $count; ?> 
    </div>
    <hr>
    <div id="tabla">
        <table width="100%" border="0.3" cellpadding="0" cellspacing="0" align="center">
            <thead style="background: #e8ecee">
            <tr class="table-title">
            <th height="30" align="center" valign="middle"><small>Producto</small></th>
            <th height="30" align="center" valign="middle"><small>Unidad de medida</small></th>
            <th height="30" align="center" valign="middle"><small>Tipo Producto</small></th>
            </tr>
        </thead>
        <tbody>
            <?php 
            while ($data = mysqli_fetch_assoc($query)) {
                $t_p_descrip = $data['descrip'];
                $u_descrip = $data['u_descrip'];
                $p_descrip = $data['t_producto'];
              

                echo "<tr>
                <td width='100' align='left'>$t_p_descrip</td>
                <td width='100' align='left'>$u_descrip</td>
                <td width='100' align='left'>$p_descrip</td>
        
                </tr>";
            }
            
            ?>
        </tbody>
        </table>

    </div>
    
</body>
</html>