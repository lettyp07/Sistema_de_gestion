<?php
require_once "../../config/database.php";

// Obtener los parámetros de fecha y el ID del pedido
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$id_pedido = isset($_GET['id_pedido']) ? $_GET['id_pedido'] : '';

// Inicializar variables para los datos
$pedidos = [];
$cabecera_compra = [];
$detalle_compra = [];

// Consultar pedidos filtrados por fechas
if ($start_date && $end_date) {
    $query = mysqli_query($mysqli, "SELECT pc.*, s.*, u.*
    FROM pedido_compra pc
    JOIN usuarios u ON pc.id_user = u.id_user
    JOIN sucursal s ON u.id_sucursal = s.id_sucursal
    WHERE pc.fecha BETWEEN '$start_date' AND '$end_date'")
        or die('Error: ' . mysqli_error($mysqli));
    $pedidos = mysqli_fetch_all($query, MYSQLI_ASSOC);
}

// Consultar cabecera y detalle de compra si se especificó un ID de pedido
if ($id_pedido) {
    // Cabecera de compra
    $cabecera_compra_query = mysqli_query($mysqli, "SELECT * FROM v_pedido WHERE id_pedido = $id_pedido")
        or die('Error: ' . mysqli_error($mysqli));
    if ($data = mysqli_fetch_assoc($cabecera_compra_query)) {
        $cabecera_compra = $data;
    }

    // Detalle de compra
    $detalle_compra_query = mysqli_query($mysqli, "SELECT * FROM v_det_pedido WHERE id_pedido = $id_pedido")
        or die('Error: ' . mysqli_error($mysqli));
    $detalle_compra = mysqli_fetch_all($detalle_compra_query, MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Pedidos</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 14px;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .content {
            width: 90%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h2, h3 {
            text-align: center;
            color: #070808;
            font-weight: 300;
        }
        p {
            font-size: 12px;
            color: #666;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
        }
        table, th, td {
            border: 1px solid #e0e0e0;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f9f9f9;
            color: #555;
        }
        .header-section, .footer-section {
            text-align: center;
            margin-top: 20px;
        }
        .footer-section {
            font-size: 10px;
            color: #aaa;
            margin-top: 50px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            background-color: #4CAF50;
            text-decoration: none;
            border-radius: 5px;
            font-size: 12px;
        }
        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="content">
        <!-- Encabezado del Reporte -->
        <div class="header-section">
            <h2>Reporte de Pedidos</h2>
            <p><strong>Fecha:</strong> <?php echo date('Y-m-d'); ?></p>
            <?php if ($start_date && $end_date): ?>
                <p><strong>Rango de Fechas:</strong> Desde <?php echo htmlspecialchars($start_date); ?> hasta <?php echo htmlspecialchars($end_date); ?></p>
            <?php endif; ?>
        </div>

        <!-- Sección para la impresión de pedidos filtrados por fechas -->
        <?php if ($start_date && $end_date): ?>
            <h3>Pedidos Realizados</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Fecha</th>
                        <th>Sucursal</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($pedidos) > 0): ?>
                        <?php foreach ($pedidos as $pedido): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($pedido['id_pedido']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['fecha']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['sucursal']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['estado']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No se encontraron pedidos para el rango de fechas seleccionado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- Sección para la impresión de detalles del pedido -->
        <?php if ($id_pedido && isset($_GET['act']) && $_GET['act'] == 'imprimir'): ?>
            <h3>Detalle del Pedido N° <?php echo htmlspecialchars($cabecera_compra['id_pedido']); ?></h3>
            <table>
                <tr>
                    <th>Código</th>
                    <td><?php echo htmlspecialchars($cabecera_compra['id_pedido']); ?></td>
                </tr>
                <tr>
                    <th>Sucursal</th>
                    <td><?php echo htmlspecialchars($cabecera_compra['sucursal']); ?></td>
                </tr>
                <tr>
                    <th>Fecha</th>
                    <td><?php echo htmlspecialchars($cabecera_compra['fecha']); ?></td>
                </tr>
                <tr>
                    <th>Usuario</th>
                    <td><?php echo htmlspecialchars($cabecera_compra['name_user']); ?></td>
                </tr>
                <tr>
                    <th>Estado</th>
                    <td><?php echo htmlspecialchars($cabecera_compra['estado']); ?></td>
                </tr>
            </table>

            <h3>Ingredientes del Pedido</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tipo ingrediente</th>
                        <th>Ingrediente</th>
                        <th>Unidad medida</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($detalle_compra) > 0): ?>
                        <?php foreach ($detalle_compra as $detalle): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($detalle['id_pedido']); ?></td>
                                <td><?php echo htmlspecialchars($detalle['t_ingrediente']); ?></td>
                                <td><?php echo htmlspecialchars($detalle['descrip_ingrediente']); ?></td>
                                <td><?php echo htmlspecialchars($detalle['u_descrip']); ?></td>
                                <td><?php echo htmlspecialchars($detalle['cantidad']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No se encontraron detalles para el pedido seleccionado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- Pie de página del Reporte -->
        <div class="footer-section">
            <p><strong>Generado por:</strong> Sistema de Gestión de Pedidos</p>
            <p><strong>Fecha de Generación:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
        </div>
    </div>
</body>
</html>

<?php
// Cerrar la conexión a la base de datos
$mysqli->close();
?>
