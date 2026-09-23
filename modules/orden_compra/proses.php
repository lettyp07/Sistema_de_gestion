<?php 


session_start(); //este nomas faltaba
//tambien falta la importacion de bd
require_once '../../config/database.php';
if(empty($_SESSION['username']) && empty($_SESSION['password'])){
    echo "<meta http-equiv='refresh' content='0; url=index.php?alert=alert=3'>";
}
else{
    if($_GET['act']=='insert'){
        
            //Insertar cabecera de compra
            
            //insertar cabecera
            $query = mysqli_query($mysqli, "INSERT INTO `orden_compra`(
                `cod_proveedor`, id_sucursal, `total_orden`, `estado`, 
                `fecha`, `hora`, `id_user`, `id_presupuesto`, comentario) 
                VALUES ((SELECT cod_proveedor FROM `presupuesto` WHERE id_presupuesto = ".$_GET['id_presupuesto']."), 
                (SELECT id_sucursal FROM `presupuesto` WHERE id_presupuesto = ".$_GET['id_presupuesto']."),
                ".$_GET['total_presupuesto'].", 'ACTIVO', "
                . "'".$_GET['fecha']."', '".$_GET['hora']."', ".$_SESSION['id_user'].","
                . " ".$_GET['id_presupuesto'].", '".$_GET['comentario']."')")
            or die('Error'.mysqli_error($mysqli));
            


            //insertar detalle
            $lista = json_decode($_GET['detalles'], true);
            
            foreach ($lista as $value) {
                
                $query = mysqli_query($mysqli, "INSERT INTO `detalle_orden`
                (`id_orden`, `id_ingrediente`, `cantidad`, `precio`) 
                VALUES ((SELECT COALESCE(MAX(p.id_orden), 1)
                        FROM orden_compra p ), ".$value['id_ingrediente'].", ".$value['cantidad'].", "
                        . "".$value['costo'].")")
                or die('Error'.mysqli_error($mysqli));
            }
            
            //actualizamos el estado del presupuesto
            
            //insertar cabecera
            $query = mysqli_query($mysqli, "UPDATE presupuesto SET estado = 'ORDENADO' "
                    . "WHERE id_presupuesto =" .$_GET['id_presupuesto'])
            or die('Error'.mysqli_error($mysqli));

            if($query){
                echo ("main.php?module=orden_compra&alert=1");
            } else {
                echo ("main.php?module=orden_compra&alert=3");
            }
        }
        
        
        
        if($_GET['act']=='anular'){
            $query = mysqli_query($mysqli, "UPDATE orden_compra SET estado = 'ANULADO' "
                    . "WHERE id_orden =" .$_GET['id_orden'])
            or die('Error'.mysqli_error($mysqli));

            if($query){
                header("Location: ../../main.php?module=orden_compra&alert=2");
            } else {
                header("Location: ../../main.php?module=orden_compra&alert=3");
            }
        }
    }

    

?>