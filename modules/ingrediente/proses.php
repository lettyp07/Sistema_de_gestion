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
        $p_descrip = $_POST['descrip_ingrediente'];
        $t_p_descrip = $_POST['t_ingrediente'];
        $unidad_medida= $_POST['unidad_medida'];
        
        $query = mysqli_query($mysqli, "INSERT INTO ingrediente (id_ingrediente, descrip_ingrediente, id_t_ingrediente, id_u_medida)
        VALUES ($codigo, '$p_descrip', $t_p_descrip, '$unidad_medida')")
        or die ('Error'.mysqli_error($mysqli));

        if ($query) {
            header("Location: ../../MAIN.PHP?module=ingrediente&alert=1");
        }else{
             
            header("Location: ../../MAIN.PHP?module=ingrediente&alert=4");
        }
    }
    }

elseif ($_GET['act']=='update') {
    if (isset($_POST['Guardar'])) {
        if (isset($_POST['codigo'])) {
            $codigo = $_POST['codigo'];
            $t_p_descrip = $_POST['t_ingrediente'];
            $unidad_medida= $_POST['unidad_medida'];
            $p_descrip = $_POST['descrip_ingrediente'];
        
    

            $query = mysqli_query($mysqli, "UPDATE ingrediente Set id_t_ingrediente = '$t_p_descrip',
                                                                id_u_medida = '$unidad_medida',
                                                                descrip_ingrediente = '$p_descrip'
                                                                Where id_ingrediente = $codigo")
                                                                or die ('Error'.mysqli_error($mysqli));
                                                
            if ($query) {
                header("Location: ../../MAIN.PHP?module=ingrediente&alert=2");
            }else{
                header("Location: ../../MAIN.PHP?module=ingrediente&alert=4");
            }
        }
    }
}elseif ($_GET['act']=='delete') {
    if (isset($_GET['id_ingrediente'])) {
        $codigo = $_GET['id_ingrediente'];
        
        $query = mysqli_query($mysqli, "DELETE FROM ingrediente
                                        Where id_ingrediente = $codigo")
                                        or die('Error'.mysqli_error($mysqli));

    if ($query) {
        header("Location: ../../MAIN.PHP?module=ingrediente&alert=3");
    }else{
        header("Location: ../../MAIN.PHP?module=ingrediente&alert=4");
    }
    }
}
}


?>