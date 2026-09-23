<?php 
session_start();
require_once "../../config/database.php";

if (empty($_SESSION['username']) && empty($_SESSION['password'])) {
    echo "<meta http-equiv= 'refresh' content='0; url=index.php?alert=alert=3'>";

}
else{
    if ($_GET['act']=='insert') {
    if (isset($_POST['Guardar'])) {
        $codigo = $_POST['codigo'];
        $p_descrip = $_POST['descrip'];
        $t_p_descrip = $_POST['t_producto'];
        $unidad_medida= $_POST['unidad_medida'];
        
        $query = mysqli_query($mysqli, "INSERT INTO producto (id_producto, descrip, id_t_producto, id_u_medida)
        VALUES ($codigo, '$p_descrip', $t_p_descrip, '$unidad_medida')")
        or die ('Error'.mysqli_error($mysqli));

        if ($query) {
            header("Location: ../../MAIN.PHP?module=producto&alert=1");
        }else{
             
            header("Location: ../../MAIN.PHP?module=producto&alert=4");
        }
    }
    }

elseif ($_GET['act']=='update') {
    if (isset($_POST['Guardar'])) {
        if (isset($_POST['codigo'])) {
            $codigo = $_POST['codigo'];
            $t_p_descrip = $_POST['t_producto'];
            $unidad_medida= $_POST['unidad_medida'];
            $p_descrip = $_POST['descrip'];
        
    

            $query = mysqli_query($mysqli, "UPDATE producto Set id_t_producto = '$t_p_descrip',
                                                                id_u_medida = '$unidad_medida',
                                                                descrip = '$p_descrip'
                                                                Where id_producto= $codigo")
                                                                or die ('Error'.mysqli_error($mysqli));
                                                
            if ($query) {
                header("Location: ../../MAIN.PHP?module=producto&alert=2");
            }else{
                header("Location: ../../MAIN.PHP?module=producto&alert=4");
            }
        }
    }
}elseif ($_GET['act']=='delete') {
    if (isset($_GET['id_producto'])) {
        $codigo = $_GET['id_producto'];
        
        $query = mysqli_query($mysqli, "DELETE FROM producto
                                        Where id_producto = $codigo")
                                        or die('Error'.mysqli_error($mysqli));

    if ($query) {
        header("Location: ../../MAIN.PHP?module=producto&alert=3");
    }else{
        header("Location: ../../MAIN.PHP?module=producto&alert=4");
    }
    }
}
}


?>