<?php
session_start();
require_once '../../config/database.php';

if (empty($_SESSION['username']) && empty($_SESSION['password'])) {
    echo "<meta http-equiv='refresh' content='0; url=index.php?alert=alert=3'>";
} else {
    if ($_GET['act'] == 'insert') {
        if (isset($_POST['Guardar'])) {
            
            $total = 0;
            $t_exenta = 0;
            $t_iva5 = 0;
            $t_iva10 = 0;

            $codigo = $_POST['codigo'];
            $id_orden = $_POST['codigo_orden'];
            // Insertar cabecera de compra
            $fecha = $_POST['fecha'];
            $hora = $_POST['hora'];
            $nro_factura = $_POST['nro_factura'];
            $timbrado = $_POST['timbrado'];
            $total_compra = $_POST['total_compra'];
            $intervalo = $_POST['intervalo'];
            $cantidad_cuotas = $_POST['cantidad_cuotas'];
            $fecha_vto = $_POST['fecha_vto'];
            $estado = 'activo';
            $usuario = $_SESSION['id_user'];
            $condicion = $_POST['condicion_pago'];

            // Actualizar estado de la orden de compra
            $orden = mysqli_query($mysqli, "UPDATE orden_compra SET estado = 'UTILIZADO' WHERE id_orden=$id_orden")
                or die('Error: ' . mysqli_error($mysqli));
                
            // Insertar cabecera de compra
            $query = mysqli_query($mysqli, "INSERT INTO compra (
                cod_compra, id_sucursal, fecha, hora, nro_factura, timbrado, total_compra,
                intervalo, cantidad_cuotas, fecha_vto, estado, id_orden, cod_proveedor,
                id_user, condicion
            ) VALUES (
                $codigo, (SELECT id_sucursal FROM orden_compra WHERE id_orden = $id_orden), 
                '$fecha', '$hora', '$nro_factura', $timbrado, $total_compra,
                $intervalo, $cantidad_cuotas, '$fecha_vto', '$estado', $id_orden,
                (SELECT cod_proveedor FROM orden_compra WHERE id_orden = $id_orden),
                $usuario, '$condicion'
            )") or die('Error: ' . mysqli_error($mysqli));

            // Insertar detalle de compra
            $sql = mysqli_query($mysqli, "SELECT * FROM ingrediente, tmp WHERE ingrediente.id_ingrediente = tmp.cod_ingrediente and tmp.session_id = '" . session_id() . "'");
            while ($row = mysqli_fetch_array($sql)) {
                $codigo_producto = $row['id_ingrediente'];
                $precio = $row['precio_tmp'];
                $cantidad = $row['cantidad_tmp'];
                
                // Obtener el IVA del producto
                $sql_iva = mysqli_query($mysqli, "SELECT iva FROM ingrediente WHERE id_ingrediente = $codigo_producto");
                $row_iva = mysqli_fetch_array($sql_iva);
                $impuesto = $row_iva['iva'];
                
                // Calcular el total con IVA incluido y los impuestos
                $total += intval($precio) * intval($cantidad);
                
                $exenta = 0;
                $iva10 = 0;
                $iva5 = 0;
                
                // Si es exento, no hay IVA
                if (intval($impuesto) == 0) {
                    $exenta = intval($precio) * intval($cantidad);
                } elseif (intval($impuesto) == 10) {
                    // Si es IVA 10%, calcular el IVA de la siguiente forma:
                    $iva10 = (intval($precio) * intval($cantidad)) - (intval($precio) * intval($cantidad) / 1.1);  // IVA 10%
                } elseif (intval($impuesto) == 5) {
                    // Si es IVA 5%, calcular el IVA de la siguiente forma:
                    $iva5 = (intval($precio) * intval($cantidad)) - (intval($precio) * intval($cantidad) / 1.05);  // IVA 5%
                }
                
                // Insertar detalle de compra
                $insert_detalle = mysqli_query($mysqli, "INSERT INTO detalle_compra (
                    id_ingrediente, cod_compra, precio, cantidad, iva5, iva10, exenta
                ) VALUES (
                    $codigo_producto, $codigo, $precio, $cantidad, $iva5, $iva10, $exenta
                )") or die('Error al insertar detalle de compra: ' . mysqli_error($mysqli));
                
                // Insertar en libro compra
                $insertar_libro = mysqli_query($mysqli, "INSERT INTO libro_compra (cod_compra, fecha, iva5, iva10, estado)
                    VALUES ($codigo, '$fecha', $iva5, $iva10, 'activo')")
                or die('Error en la consulta de inserción en libro_compra: ' . mysqli_error($mysqli));
                
                // Insertar en cuentas a pagar
                $insertar_cuenta = mysqli_query($mysqli, "INSERT INTO cuentas_a_pagar (cod_compra, monto, fecha_vencimiento, estado, saldo)
                    VALUES ($codigo, $total, DATE_ADD(CURDATE(), INTERVAL 30 DAY), 'pendiente', 0)")
                or die('Error en la consulta de inserción en cuentas_a_pagar: ' . mysqli_error($mysqli));
                
                // Insertar o actualizar stock
                $query_stock = mysqli_query($mysqli, "SELECT * FROM stock WHERE id_ingrediente=$codigo_producto");
                if (mysqli_num_rows($query_stock) == 0) {
                    // Si no existe, insertar stock
                    $insertar_stock = mysqli_query($mysqli, "INSERT INTO stock (id_ingrediente, cantidad, id_sucursal)
                    VALUES ($codigo_producto, $cantidad, (SELECT id_sucursal FROM orden_compra WHERE id_orden = $id_orden))") 
                    or die('Error al insertar stock: ' . mysqli_error($mysqli));
                } else {
                    // Si existe, actualizar stock
                    $actualizar_stock = mysqli_query($mysqli, "UPDATE stock SET cantidad = cantidad + $cantidad
                    WHERE id_ingrediente=$codigo_producto") or die('Error al actualizar stock: ' . mysqli_error($mysqli));
                }
            }

            // Verificar si la consulta fue exitosa
            if ($query) {
                header("Location: ../../main.php?module=compras&alert=1");
            } else {
                header("Location: ../../main.php?module=compras&alert=3");
            }
        }
    
    } elseif ($_GET['act'] == 'anular') {
        if (isset($_GET['cod_compra'])) {
            $codigo = $_GET['cod_compra'];
            
            // Anular cabecera de compra
            $query = mysqli_query($mysqli, "UPDATE compra SET estado='anulado' WHERE cod_compra=$codigo")
                    or die('Error: ' . mysqli_error($mysqli));

            // Anular libro compra
            $actualizar_libro = mysqli_query($mysqli, "UPDATE libro_compra SET estado='anulado' WHERE cod_compra=$codigo")
                    or die('Error: ' . mysqli_error($mysqli));
            
            // Anular cuentas a pagar
            $actualizar_cuenta = mysqli_query($mysqli, "UPDATE cuentas_a_pagar SET estado='anulado' WHERE cod_compra=$codigo")
                    or die('Error: ' . mysqli_error($mysqli));

            // Consultar detalle de compra
            $sql = mysqli_query($mysqli, "SELECT * FROM detalle_compra WHERE cod_compra=$codigo");
            while ($row = mysqli_fetch_array($sql)) {
                $codigo_producto = $row['id_ingrediente'];
                $cantidad = $row['cantidad'];

                // Actualizar stock
                $actualizar_stock = mysqli_query($mysqli, "UPDATE stock SET cantidad = cantidad - $cantidad WHERE id_ingrediente=$codigo_producto")
                        or die('Error al actualizar stock: ' . mysqli_error($mysqli));
            }

            if ($query) {
                header("Location: ../../main.php?module=compras&alert=2");
            } else {
                header("Location: ../../main.php?module=compras&alert=3");
            }
        }
    }
}
?>
