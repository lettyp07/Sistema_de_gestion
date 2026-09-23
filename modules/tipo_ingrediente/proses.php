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
        $t_p_descrip = $_POST['t_ingrediente'];

        $query = mysqli_query($mysqli, "INSERT INTO tipo_ingrediente (id_t_ingrediente, t_ingrediente)
        VALUES ($codigo, '$t_p_descrip')") or die ('Error'.mysqli_error($mysqli));

        if ($query) {
            header("Location: ../../MAIN.PHP?module=tipo_ingrediente&alert=1");
        }else{
            header("Location: ../../MAIN.PHP?module=tipo_ingrediente&alert=4");
        }
    }
    }

elseif ($_GET['act']=='update') {
    if (isset($_POST['Guardar'])) {
        if (isset($_POST['codigo'])) {
            $codigo = $_POST['codigo'];
            $t_p_descrip = $_POST['t_ingrediente'];

            $query = mysqli_query($mysqli, "UPDATE tipo_ingrediente Set t_ingrediente = '$t_p_descrip'
                                                                Where id_t_ingrediente = $codigo")
                                                                or die ('Error'.mysqli_error($mysqli));
                                                
            if ($query) {
                header("Location: ../../MAIN.PHP?module=tipo_ingrediente&alert=2");
            }else{
                header("Location: ../../MAIN.PHP?module=tipo_ingrediente&alert=4");
            }
        }
    }
}elseif ($_GET['act']=='delete') {
    if (isset($_GET['id_t_ingrediente'])) {
        $codigo = $_GET['id_t_ingrediente'];
        
        $query = mysqli_query($mysqli, "DELETE FROM tipo_ingrediente
                                        Where id_t_ingrediente = $codigo")
                                        or die('Error'.mysqli_error($mysqli));

    if ($query) {
        header("Location: ../../MAIN.PHP?module=tipo_ingrediente&alert=3");
    }else{
        header("Location: ../../MAIN.PHP?module=tipo_ingrediente&alert=4");
    }
    }
}
}


?>