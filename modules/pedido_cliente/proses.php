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
            
            //Insertar detalle
            $sql=mysqli_query($mysqli, "SELECT * FROM producto, tmppp WHERE producto.id_producto=tmppp.cod_producto and session_id = '".session_id()."'");
            while($row = mysqli_fetch_array($sql)){
                $codigo_producto= $row['id_producto'];
                //$precio= $row['precio_tmp'];
                $cantidad= $row['cantidad_tmp'];
                $insert_detalle = mysqli_query($mysqli, "INSERT INTO detalle_pedido_cliente (id_producto, id_pedido_cliente,
                cantidad) VALUES ($codigo_producto, $codigo, $cantidad)")
                or die('Error'.mysqli_error($mysqli));

            }
            //Insertar cabecera
            //Definir variables
            $fecha = $_POST['fecha'];
            echo $_POST['fecha'];
            //$hora = $_POST['hora'];
            $estado='activo';
            $codigo_cliente=$_POST['codigo_cliente'];
            $usuario = $_SESSION['id_user'];
            $sucursal = $_POST['sucursal'];
            
            //insertar
            $query = mysqli_query($mysqli, "INSERT INTO pedido_cliente (id_pedido_cliente, 
           fecha, estado, id_cliente, id_user, id_sucursal)
            VALUES ($codigo, '$fecha', '$estado', $codigo_cliente, $usuario, (SELECT id_sucursal FROM usuarios 
            WHERE id_user = $usuario))")
            or die('Error'.mysqli_error($mysqli));
            
            
            $delete=mysqli_query($mysqli, "DELETE FROM tmppp WHERE session_id = '".session_id()."'");

            if($query){
                header("Location: ../../main.php?module=pedido_cliente&alert=1");
            } else {
                header("Location: ../../main.php?module=pedido_cliente&alert=3");
            }
        }
    }
    elseif($_GET['act']=='anular'){
        if(isset($_GET['id_pedido_cliente'])){
            $codigo = $_GET['id_pedido_cliente'];
            //Anular cabecera (cambiar estado a anulado)
            $query = mysqli_query($mysqli, "UPDATE pedido_cliente SET estado='anulado'
                                                                WHERE id_pedido_cliente=$codigo")
            or die('Error'.mysqli_error($mysqli));
            
            if($query){
                header("Location: ../../main.php?module=pedido_cliente&alert=2");
            } else {
                header("Location: ../../main.php?module=pedido_cliente&alert=3");
            }
        }
    }
}
?>