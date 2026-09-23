<?php  
$start_date = $_GET['start_date'];
$end_date = $_GET['end_date'];

require_once "../../config/database.php";

if ($start_date && $end_date) {
    // Consultar la base de datos para obtener los proveedores en el rango de fechas
    $query = mysqli_query($mysqli, "SELECT * FROM proveedor WHERE fecha_registro BETWEEN '$start_date' AND '$end_date'")
        or die('Error'.mysqli_error($mysqli));

    // Generar la salida de la impresión (aquí pondrías el código necesario para imprimir los proveedores filtrados)
}
$count = mysqli_num_rows($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../assets/img/favicon.ico">
    <title>Reporte de Proveedor</title>
</head>
<body>
    <div align="center">
        <img src="../../images/user/proveedor.jpg"  width="700" height="500">
    </div>
    <div>
        Reporte de proveedor
    </div>
    <div align="center">
        cantidad: <?php echo $count; ?> 
    </div>
    <hr>
    <div id="tabla">
        <table width="100%" border="0.3" cellpadding="0" cellspacing="0" align="center">
            <thead style="background: #e8ecee">
            <tr class="table-title">
            <th height="30" align="center" valign="middle"><small>Razon Social</small></th>
            <th height="30" align="center" valign="middle"><small>Ruc</small></th>
            <th height="30" align="center" valign="middle"><small>Direccion</small></th>
            <th height="30" align="center" valign="middle"><small>Telefono</small></th>
            </tr>
        </thead>
        <tbody>
            <?php 
            while ($data = mysqli_fetch_assoc($query)) {
                $razon_social = $data['razon_social'];
                $ruc = $data['ruc'];
                $direccion = $data['direccion'];
                $telefono = $data['telefono'];

                echo "<tr>
                <td width='100' align='left'>$razon_social</td>
                <td width='100' align='left'>$ruc</td>
                <td width='100' align='left'>$direccion</td>
                <td width='100' align='left'>$telefono</td>
                </tr>";
            }
            
            ?>
        </tbody>
        </table>

    </div>
    
</body>
</html>