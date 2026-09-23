<?php
require_once "../../config/database.php";

// Obtener los parámetros de fecha y el ID de compra
$start_date = isset($_GET['start_date']) ? mysqli_real_escape_string($mysqli, $_GET['start_date']) : '';
$end_date = isset($_GET['end_date']) ? mysqli_real_escape_string($mysqli, $_GET['end_date']) : '';
$id_compra = isset($_GET['cod_compra']) ? mysqli_real_escape_string($mysqli, $_GET['cod_compra']) : '';

// Inicializar variables para los datos
$compras = [];
$cabecera_compra = [];
$detalle_compra = [];

// Consultar compras filtradas por fechas
if ($start_date && $end_date) {
    $query = mysqli_query($mysqli, "SELECT c.*, pr.* FROM compra c
        JOIN proveedor pr ON c.cod_proveedor = pr.cod_proveedor
        WHERE c.fecha BETWEEN '$start_date' AND '$end_date'")
        or die('Error en la consulta de compra: ' . mysqli_error($mysqli));
    $compras = mysqli_fetch_all($query, MYSQLI_ASSOC);
}

// Consultar cabecera y detalle de compra si se especificó un ID de compra
if ($id_compra) {
    // Cabecera de compra
    $cabecera_compra_query = mysqli_query($mysqli, "SELECT 
        c.*, p.*
        FROM compra c
        JOIN proveedor p ON c.cod_proveedor = p.cod_proveedor
        WHERE c.cod_compra = '$id_compra'")
        or die('Error en la consulta de cabecera de compra: ' . mysqli_error($mysqli));
    $cabecera_compra = mysqli_fetch_assoc($cabecera_compra_query);

    // Detalle de compra
    $detalle_compra_query = mysqli_query($mysqli, "SELECT d.precio, d. cantidad, p.id_ingrediente, descrip_ingrediente
    FROM detalle_compra d
    JOIN ingrediente p ON d.id_ingrediente = p.id_ingrediente
    WHERE d.cod_compra = '$id_compra'")
        or die('Error en la consulta de detalle de compra: ' . mysqli_error($mysqli));
    $detalle_compra = mysqli_fetch_all($detalle_compra_query, MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura de Compra</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }
        h2 {
            margin-top: 0;
        }
        .header {
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header p {
            margin: 5px 0;
        }
        .invoice-details {
            margin-top: 20px;
        }
        .invoice-details table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .invoice-details th, .invoice-details td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .invoice-details th {
            background-color: #f4f4f4;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            border-top: 2px solid #333;
            padding-top: 10px;
        }
        .footer p {
            margin: 5px 0;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            background-color: #4CAF50;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            margin-top: 20px;
        }
        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Encabezado del Reporte -->
        <div class="header">
            <h1>Factura de Compra</h1>
            <p><strong>Fecha impresión :</strong> <?php echo date('Y-m-d'); ?></p>
            <?php if ($start_date && $end_date): ?>
                <p><strong>Rango de Fechas:</strong> Desde <?php echo htmlspecialchars($start_date); ?> hasta <?php echo htmlspecialchars($end_date); ?></p>
            <?php endif; ?>
        </div>

        <!-- Sección para la impresión de compras filtradas por fechas -->
        <?php if ($start_date && $end_date): ?>
            <h2>Compras Realizadas</h2>
            <table class="invoice-details">
                <thead>
                    <tr>
                        <th>ID Compra</th>
                        <th>Fecha</th>
                        <th>Proveedor</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($compras) > 0): ?>
                        <?php foreach ($compras as $compra): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($compra['cod_compra']); ?></td>
                                <td><?php echo htmlspecialchars($compra['fecha']); ?></td>
                                <td><?php echo htmlspecialchars($compra['razon_social']); ?></td>
                                <td><?php echo htmlspecialchars($compra['estado']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No se encontraron compras para el rango de fechas seleccionado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- Sección para la impresión de detalles de la compra -->
        <?php if ($id_compra && isset($_GET['act']) && $_GET['act'] == 'imprimir'): ?>
            <h2>Factura N° <?php echo htmlspecialchars($cabecera_compra['cod_compra']); ?></h2>
            <div class="invoice-details">
                <h3>Datos de la Factura</h3>
                <table>
                    <tr>
                        <th>Código</th>
                        <td><?php echo htmlspecialchars($cabecera_compra['cod_compra']); ?></td>
                    </tr>
                    <tr>
                        <th>Proveedor</th>
                        <td><?php echo htmlspecialchars($cabecera_compra['razon_social']); ?></td>
                    </tr>
                    <tr>
                        <th>RUC</th>
                        <td><?php echo htmlspecialchars($cabecera_compra['ruc']); ?></td>
                    </tr>
                    <tr>
                        <th>Dirección</th>
                        <td><?php echo htmlspecialchars($cabecera_compra['direccion']); ?></td>
                    </tr>
                    <tr>
                        <th>Teléfono</th>
                        <td><?php echo htmlspecialchars($cabecera_compra['telefono']); ?></td>
                    </tr>
                    <tr>
                        <th>Fecha</th>
                        <td><?php echo htmlspecialchars($cabecera_compra['fecha']); ?></td>
                    </tr>
                    <tr>
                        <th>Usuario</th>
                        <td><?php echo htmlspecialchars($cabecera_compra['id_user']); ?></td>
                    </tr>
                    <tr>
                        <th>Estado</th>
                        <td><?php echo htmlspecialchars($cabecera_compra['estado']); ?></td>
                    </tr>
                </table>

                <h3>Detalles de los Productos</h3>
                <table>
                    <thead>
                        <tr>
                            <th>ID Producto</th>
                            <th>Descripción</th>
                            <th>Precio Unitario</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($detalle_compra) > 0): ?>
                            <?php foreach ($detalle_compra as $detalle): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($detalle['id_ingrediente']); ?></td>
                                    <td><?php echo htmlspecialchars($detalle['descrip_ingrediente']); ?></td>
                                    <td><?php echo number_format($detalle['precio']); ?></td>
                                    <td><?php echo htmlspecialchars($detalle['cantidad']); ?></td>
                                    <td><?php echo number_format($detalle['precio'] * $detalle['cantidad']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">No se encontraron detalles para la compra seleccionada.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <h3>Total de Compra: Gs. <?php echo number_format($cabecera_compra['total_compra'], 2); ?></h3>
            </div>
        <?php endif; ?>

        <!-- Pie de página del Reporte -->
        <div class="footer">
            <p><strong>Generado por:</strong> Sistema de Gestión de Compras</p>
            <p><strong>Fecha de Generación:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
            
        </div>
    </div>
</body>
</html>
