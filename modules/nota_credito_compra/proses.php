<?php

session_start(); 

require_once '../../config/database.php';
if(empty($_SESSION['username']) && empty($_SESSION['password'])){
    echo "<meta http-equiv='refresh' content='0; url=index.php?alert=alert=3'>";
}
else{
    if ($_GET['act'] == 'insert') {
        // Insertar cabecera de compra
        $query = mysqli_query($mysqli, "INSERT INTO `nota_credito_compra` 
            (`cod_proveedor`, `total`, `estado`, `fecha`, `hora`, `id_user`, `cod_compra`, `tipo`)  
            VALUES (
                (SELECT cod_proveedor FROM `compra` WHERE cod_compra = ".$_GET['cod_compra']."), 
                ".$_GET['total_nota'].", 'ACTIVO', '".$_GET['fecha']."', '".$_GET['hora']."', 
                ".$_SESSION['id_user'].", ".$_GET['cod_compra'].", '".$_GET['tipo']."'
            )")
        or die('Error al insertar cabecera: '.mysqli_error($mysqli));
    
        // Verifica si la inserción fue exitosa
        if ($query) {
            // Insertar detalle
            $lista = json_decode($_GET['detalles'], true);
            if ($lista) {
                foreach ($lista as $value) {
                    // Utilizar LAST_INSERT_ID() para obtener el ID de la última nota de crédito insertada
                    $detalleQuery = mysqli_query($mysqli, "INSERT INTO `detalle_nota_credito_compra`
                        (`cod_nota_credito_compra`, `id_ingrediente`, `cantidad`, `precio`) 
                        VALUES (LAST_INSERT_ID(), ".$value['id_ingrediente'].", ".$value['cantidad'].", ".$value['costo'].")")
                    or die('Error al insertar detalle en nota_credito_compra: '.mysqli_error($mysqli));
                }
            } else {
                die('Error: los detalles no están bien formateados o no se recibieron correctamente.');
            }
    
            // Si todo va bien, actualizar otras tablas
            $query_libro = mysqli_query($mysqli, "UPDATE libro_compra SET iva10 = 0, estado = 'NOTA DE ".$_GET['tipo']."' 
                                                  WHERE cod_compra = ".$_GET['cod_compra'])
            or die('Error al actualizar libro_compra: '.mysqli_error($mysqli));
    
            $query_cuentas = mysqli_query($mysqli, "UPDATE cuentas_a_pagar SET monto = 0, estado = 'NOTA DE ".$_GET['tipo']."' 
                                                    WHERE cod_compra = ".$_GET['cod_compra'])
            or die('Error al actualizar cuentas_a_pagar: '.mysqli_error($mysqli));
    
            $query_compra = mysqli_query($mysqli, "UPDATE compra SET estado = 'NOTA DE ".$_GET['tipo']."' 
                                                    WHERE cod_compra = ".$_GET['cod_compra'])
            or die('Error al actualizar compra: '.mysqli_error($mysqli));
    
            // Redirigir según el resultado
            if ($query && $detalleQuery) {
                echo ("main.php?module=notas&alert=1");
            } else {
                echo ("main.php?module=notas&alert=3");
            }
        } else {
            echo "Error al insertar cabecera.";
        } 
    }              
        if($_GET['act']=='anular'){
            $query = mysqli_query($mysqli, "UPDATE nota_credito_compra SET estado = 'ANULADO' "
                    . "WHERE cod_nota_credito_compra =" .$_GET['id_orden'])
            or die('Error'.mysqli_error($mysqli));

            if($query){
                header("Location: ../../main.php?module=notas&alert=2");
            } else {
                header("Location: ../../main.php?module=notas&alert=3");
            }
        }
    }
?>