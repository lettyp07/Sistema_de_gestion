<?php 
session_start();

require_once '../../config/database.php';

if(empty($_SESSION['username']) && empty($_SESSION['password'])){
    echo "<meta http-equiv='refresh' content='0; url=index.php?alert=alert=3'>";
}
else{
    if($_GET['act']=='insert'){
        if(isset($_POST['Guardar'])){
            $codigo = $_POST['codigo'];
           
           
            //Insertar cabecera de compra
            //Definir variables
            $cod_com = $_POST['pedidos_lst'];
            $fecha = $_POST['fecha'];
          
            $estado='activo';
            $usuario = $_SESSION['id_user'];
            $salida = $_POST['salida'];
            $llegada = $_POST['llegada'];
            $chofer = $_POST['chofer'];
           
            $session_id = session_id();
            //insertar
            $query = mysqli_query($mysqli, "INSERT INTO `remision_compras`(`cod_remision_compra`, `fecha_registro`, 
                `punto_salida`, `punto_llegada`, `estado`, `chofer`, `cod_compras`)
            VALUES ($codigo, '$fecha',  '$salida','$llegada', '$estado', '$chofer' ,$cod_com)")
            or die('Error'.mysqli_error($mysqli));
            
             //Insertar detalle de compra
            $sql=mysqli_query($mysqli, "SELECT * FROM ingrediente, tmp WHERE ingrediente.id_ingrediente=tmp.cod_ingrediente and tmp.session_id='".$session_id."'");
            while($row = mysqli_fetch_array($sql)){
                $codigo_producto= $row['cod_ingrediente'];
                $cantidad= $row['cantidad_tmp'];
                $precio= $row['precio_tmp'];
                $insert_detalle = mysqli_query($mysqli, "INSERT INTO remision_compra_detalle "
                        . "(`cod_remision_compra`, `id_ingrediente`, `cantidad`) "
                        . "VALUES ($codigo, $codigo_producto, $cantidad)")
                or die('Error'.mysqli_error($mysqli));
            }
            
            if($query){
                  
                  $delete=mysqli_query($mysqli, "DELETE FROM tmp WHERE session_id='".$session_id."'");
                header("Location: ../../main.php?module=remision&alert=1");
            } else {
                header("Location: ../../main.php?module=remision&alert=3");
            }
        }
    }

    elseif($_GET['act']=='anular'){
        if(isset($_GET['cod'])){
            $codigo = $_GET['cod'];
            //Anular cabecera de compra (cambiar estado a anulado)
            $query = mysqli_query($mysqli, "UPDATE remision_compras SET estado='anulado' WHERE cod_remision_compra=$codigo")
            or die('Error'.mysqli_error($mysqli));            
            }
            if($query){
                header("Location: ../../main.php?module=remision&alert=2");
            } else {
                header("Location: ../../main.php?module=remision&alert=3");
            }
        }
    }

?>