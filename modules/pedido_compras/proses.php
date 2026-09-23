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
            
            //Insertar detalle de compra
            $sql=mysqli_query($mysqli, "SELECT * FROM ingrediente, tmp WHERE ingrediente.id_ingrediente=tmp.cod_ingrediente and session_id = '".session_id()."'");
            while($row = mysqli_fetch_array($sql)){
                $codigo_producto= $row['id_ingrediente'];
                //$precio= $row['precio_tmp'];
                $cantidad= $row['cantidad_tmp'];
                $insert_detalle = mysqli_query($mysqli, "INSERT INTO detalle_pedido_compra (id_ingrediente, id_pedido,
                cantidad) VALUES ($codigo_producto, $codigo, $cantidad)")
                or die('Error'.mysqli_error($mysqli));

            }
            //Insertar cabecera de compra
            //Definir variables
            //$codigo_proveedor = $_POST['codigo_proveedor'];
            $fecha = $_POST['fecha'];
            echo $_POST['fecha'];
            $hora = $_POST['hora'];
            //$suma_total=$_POST['suma_total'];
            $estado='activo';
            $usuario = $_SESSION['id_user'];
            $sucursal = $_POST['sucursal'];
            
            //insertar
            $query = mysqli_query($mysqli, "INSERT INTO pedido_compra (id_pedido, 
           fecha, hora, estado, id_user, id_sucursal)
            VALUES ($codigo, '$fecha', '$hora', '$estado', $usuario, (SELECT id_sucursal FROM usuarios 
            WHERE id_user = $usuario))")
            or die('Error'.mysqli_error($mysqli));
            
            
            $delete=mysqli_query($mysqli, "DELETE FROM tmp WHERE session_id = '".session_id()."'");

            if($query){
                header("Location: ../../main.php?module=pedido&alert=1");
            } else {
                header("Location: ../../main.php?module=pedido&alert=3");
            }
        }
    }
    elseif($_GET['act']=='anular'){
        if(isset($_GET['id_pedido'])){
            $codigo = $_GET['id_pedido'];
            //Anular cabecera de compra (cambiar estado a anulado)
            $query = mysqli_query($mysqli, "UPDATE pedido_compra SET estado='anulado'
                                                                WHERE id_pedido=$codigo")
            or die('Error'.mysqli_error($mysqli));
            
            if($query){
                header("Location: ../../main.php?module=pedido&alert=2");
            } else {
                header("Location: ../../main.php?module=pedido&alert=3");
            }
        }
    }
}
?>