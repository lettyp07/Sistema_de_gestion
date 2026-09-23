<?php 

session_start(); 

require_once '../../config/database.php';
if(empty($_SESSION['username']) && empty($_SESSION['password'])){
    echo "<meta http-equiv='refresh' content='0; url=index.php?alert=alert=3'>";
}
else{
    if($_GET['act']=='insert'){
       
            //insertar cabecera
            $query = mysqli_query($mysqli, "INSERT INTO `presupuesto`(id_sucursal,
                `cod_proveedor`, `total_presupuesto`, `estado`, 
                `fecha`, `hora`, `id_user`, id_pedido, fecha_vencimiento) 
                VALUES ((SELECT 
            id_sucursal
            FROM `pedido_compra`
            WHERE id_pedido = ".$_GET['id_pedido']."), ".$_GET['cod_proveedor'].", ".$_GET['total_presupuesto'].", 'PENDIENTE', "
            . "'".$_GET['fecha']."', '".$_GET['hora']."', ".$_SESSION['id_user'].", ".$_GET['id_pedido'].", "
                    . "'".$_GET['fecha_vencimiento']."')")
            or die('Error'.mysqli_error($mysqli));

            //insertar detalle
            $lista = json_decode($_GET['detalles'], true);
            
            foreach ($lista as $value) {
                
                $query = mysqli_query($mysqli, "INSERT INTO `detalle_presupuesto`
                (`id_presupuesto`, `id_ingrediente`, `cantidad`, `precio`) 
                VALUES ((SELECT COALESCE(MAX(p.id_presupuesto), 1)
                        FROM presupuesto p ), ".$value['id_ingrediente'].", ".$value['cantidad'].", "
                        . "".$value['costo'].")")
                or die('Error'.mysqli_error($mysqli));
            }
            
          
            //insertar cabecera
            $query = mysqli_query($mysqli, "UPDATE pedido_compra SET estado = 'ENVIADO' "
                    . "WHERE id_pedido =" .$_GET['id_pedido'])
            or die('Error'.mysqli_error($mysqli));

            if($query){
                echo "main.php?module=presupuesto&alert=1";
            } else {
                echo "main.php?module=presupuesto&alert=3";
            }

    }    
	elseif ($_GET['act']=='on') {
		if (isset($_GET['id'])) {
			
			$id_user = $_GET['id'];
			$status  = "ACTIVO";

		
            $query = mysqli_query($mysqli, "UPDATE presupuesto SET estado  = '$status'
                                                          WHERE id_presupuesto = '$id_user'")
                                            or die('error: '.mysqli_error($mysqli));

  
            if ($query) {
               
                header("location: ../../main.php?module=presupuesto&alert=1");
            }
		}
	}
    elseif ($_GET['act']=='off') {
		if (isset($_GET['id'])) {
			
			$id_user = $_GET['id'];
			$status  = "RECHAZADO";

		
            $query = mysqli_query($mysqli, "UPDATE presupuesto SET estado  = '$status'
                                                          WHERE id_presupuesto = '$id_user'")
                                            or die('error: '.mysqli_error($mysqli));

  
            if ($query) {
               
                header("location: ../../main.php?module=presupuesto&alert=2");
            }
		}
	}
        
    }
?>