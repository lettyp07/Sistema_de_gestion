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
                $insert_detalle = mysqli_query($mysqli, "INSERT INTO detalle_receta (id_ingrediente, id_receta, 
                cantidad) VALUES ($codigo_producto, $codigo, $cantidad)")
                or die('Error'.mysqli_error($mysqli));

            }
            //Insertar cabecera de compra
            //Definir variables
            $codigo_proveedor = $_POST['codigo'];
            $fecha = $_POST['fecha'];
            echo $_POST['fecha'];
            $hora = $_POST['hora'];
            //$suma_total=$_POST['suma_total'];
            $estado='activo';
            $id_producto = $_POST['id_producto'];
            $usuario = $_SESSION['id_user'];
            //insertar
                        $query = "
                INSERT INTO receta (id_receta, fecha, hora, estado, id_producto, id_user, id_sucursal) 
                VALUES (?, ?, ?, ?, ?, ?, 
                (SELECT id_sucursal FROM usuarios WHERE id_user = ?))";

            if ($stmt = $mysqli->prepare($query)) {
                $stmt->bind_param(
                    'isssiii', 
                    $codigo,       // id_receta
                    $fecha,        // fecha
                    $hora,         // hora
                    $estado,       // estado
                    $id_producto,  // id_producto
                    $usuario,      // id_user
                    $usuario       // id_user para subconsulta
                );

                if ($stmt->execute()) {
                    echo "Inserción exitosa";
                } else {
                    echo "Error al ejecutar la consulta: " . $stmt->error;
                }

                $stmt->close();
            } else {
                echo "Error al preparar la consulta: " . $mysqli->error;
            }         

            $delete=mysqli_query($mysqli, "DELETE FROM tmp WHERE session_id = '".session_id()."'");

            if($query){
                header("Location: ../../main.php?module=receta&alert=1");
            } else {
                header("Location: ../../main.php?module=receta&alert=3");
            }
        }
    }

    elseif($_GET['act']=='desactivar'){
        if(isset($_GET['id_receta'])){
            $codigo = $_GET['id_receta'];
            //Anular cabecera de compra (cambiar estado a anulado)
            $query = mysqli_query($mysqli, "UPDATE receta SET estado='desactivado' WHERE id_receta=$codigo")
            or die('Error'.mysqli_error($mysqli));

            
            if($query){
                header("Location: ../../main.php?module=receta&alert=2");
            } else {
                header("Location: ../../main.php?module=receta&alert=3");
            }
        }
    }
}
?>