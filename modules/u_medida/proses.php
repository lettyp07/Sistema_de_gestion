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
        $u_descrip = $_POST['u_descrip'];

        $query = mysqli_query($mysqli, "INSERT INTO u_medida (id_u_medida, u_descrip)
        VALUES ($codigo, '$u_descrip')") or die ('Error'.mysqli_error($mysqli));

        if ($query) {
            header("Location: ../../MAIN.PHP?module=u_medida&alert=1");
        }else{
            header("Location: ../../MAIN.PHP?module=u_medida&alert=4");
        }
    }
    }

elseif ($_GET['act']=='update') {
    if (isset($_POST['Guardar'])) {
        if (isset($_POST['codigo'])) {
            $codigo = $_POST['codigo'];
            $u_descrip = $_POST['u_descrip'];

            $query = mysqli_query($mysqli, "UPDATE u_medida Set u_descrip = '$u_descrip'
                                                                Where id_u_medida = $codigo")
                                                                or die ('Error'.mysqli_error($mysqli));
                                                
            if ($query) {
                header("Location: ../../MAIN.PHP?module=u_medida&alert=2");
            }else{
                header("Location: ../../MAIN.PHP?module=u_medida&alert=4");
            }
        }
    }
}elseif ($_GET['act']=='delete') {
    if (isset($_GET['id_u_medida'])) {
        $codigo = $_GET['id_u_medida'];
        
        $query = mysqli_query($mysqli, "DELETE FROM u_medida
                                        Where id_u_medida = $codigo")
                                        or die('Error'.mysqli_error($mysqli));

    if ($query) {
        header("Location: ../../MAIN.PHP?module=u_medida&alert=3");
    }else{
        header("Location: ../../MAIN.PHP?module=u_medida&alert=4");
    }
    }
}
}


?>