<?php
require_once "../../config/database.php";

if ($_GET['act'] == 'imprimir') {
    if (isset($_GET['cod'])) {
        $codigo = $_GET['cod'];
        
        // Cabecera de compra
        $cabecera_compra = mysqli_query($mysqli, "SELECT
                                pc.cod_remision_compra,
                                pc.fecha_registro,
                                pc.estado,
                                pc.punto_salida,
                                pc.punto_llegada,
                                pc.chofer
                                FROM remision_compras pc
                                WHERE pc.cod_remision_compra = $codigo")
            or die('Error: ' . mysqli_error($mysqli));
        
        while ($data = mysqli_fetch_assoc($cabecera_compra)) {
            $nro_factura = $data['cod_remision_compra'];
            $fecha = $data['fecha_registro'];
            $salida = $data['punto_salida'];
            $llegada = $data['punto_llegada'];
            $chofer = $data['chofer'];
        }
        
        // Detalle de compra
        $detalle_compra = mysqli_query($mysqli, "SELECT
        d.*,
        p.*
        FROM remision_compra_detalle d 
        JOIN ingrediente p ON p.id_ingrediente = d.id_ingrediente
        WHERE d.cod_remision_compra = $codigo")
            or die('Error: ' . mysqli_error($mysqli));
    }
}
?> 
<!DOCTYPE HTML>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Remisión de Compra</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header, .footer {
            text-align: center;
            margin-bottom: 20px;
        }
        .details {
            margin-bottom: 20px;
        }
        .details label {
            display: block;
            font-size: 16px;
            margin-bottom: 10px;
        }
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #cecece;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #004080;
            color: #ffffff;
        }
        thead {
            background-color: #e0e0e0;
        }
        @media print {
            .header, .footer {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Remisión de Compra</h1>
        </div>
        
        <div class="details">
            <label><strong>N° Remisión:</strong> <?php echo $nro_factura; ?></label>
            <label><strong>Fecha:</strong> <?php echo $fecha; ?></label>
            <label><strong>Punto de salida:</strong> <?php echo $salida; ?></label>
            <label><strong>Punto de llegada:</strong> <?php echo $llegada; ?></label>
            <label><strong>Chofer:</strong> <?php echo $chofer; ?></label>
        </div>
        
        <hr>
        
        <div class="table-container">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><small>Ingrediente</small></th>
                        <th><small>Cantidad</small></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($data2 = mysqli_fetch_assoc($detalle_compra)) {
                       // $t_p_descrip = $data2['t_p_descrip'];
                        $p_descrip = $data2['descrip_ingrediente'];
                       // $u_medida = $data2['u_descrip'];
                        $cantidad = $data2['cantidad'];

                        echo "<tr>
                                <td>$p_descrip</td>
                                <td>$cantidad</td>
                              </tr>";
                    }       
                    ?>
                </tbody>
            </table>
        </div>

        <div class="footer">
            <p>Fecha de impresión: <?php echo date('d/m/Y H:i:s'); ?></p>
        </div>
    </div>
</body>
</html>
