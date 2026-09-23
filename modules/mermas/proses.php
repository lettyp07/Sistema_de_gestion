<?php
session_start();

require_once '../../config/database.php';

if(empty($_SESSION['username']) && empty($_SESSION['password'])){
    echo "<meta http-equiv='refresh' content='0; url=index.php?alert=3'>";
}
else{
    if($_GET['act']=='insert'){
        if(isset($_POST['Guardar'])){
            $codigo = $_POST['codigo'];
            $fecha = $_POST['fecha'];
            $hora = $_POST['hora'];
            $estado = 'pendiente';
            $usuario = $_SESSION['id_user'];
            $id_control_calidad = $_POST['codigo_control'];
            
            // Obtener sucursal del usuario
            $stmt_sucursal = $mysqli->prepare("SELECT id_sucursal FROM usuarios WHERE id_user = ?");
            $stmt_sucursal->bind_param("i", $usuario);
            $stmt_sucursal->execute();
            $stmt_sucursal->bind_result($id_sucursal);
            $stmt_sucursal->fetch();
            $stmt_sucursal->close();

            if (empty($id_sucursal)) {
                die("Error: No se encontró la sucursal del usuario.");
            }

            // Insertar cabecera de merma
            $query = mysqli_query($mysqli, "INSERT INTO mermas (id_merma, fecha, hora, estado, id_user, id_sucursal, id_control_calidad)
            VALUES ($codigo, '$fecha', '$hora', '$estado', $usuario, $id_sucursal, $id_control_calidad)")
            or die('Error'.mysqli_error($mysqli));

            // Insertar detalle de merma
            $sql = mysqli_query($mysqli, "SELECT * FROM control_calidad, tmppp WHERE control_calidad.id_control_calidad = tmppp.cod_producto and session_id = '".session_id()."'");
            while($row = mysqli_fetch_array($sql)){
                $codigo_producto = $row['cod_producto'];
                $cantidad = $row['cantidad_tmp'];
                $insert_detalle = mysqli_query($mysqli, "INSERT INTO detalle_mermas (id_merma, id_producto, cantidad) VALUES ($codigo, $codigo_producto, $cantidad)")
                or die('Error'.mysqli_error($mysqli));
            }

            // Eliminar registros temporales
            $delete = mysqli_query($mysqli, "DELETE FROM tmppp WHERE session_id = '".session_id()."'");

            if($query){
                header("Location: ../../main.php?module=mermas&alert=1");
            } else {
                header("Location: ../../main.php?module=mermas&alert=3");
            }
        }
    }
}
?>
