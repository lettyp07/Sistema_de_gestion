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
        $dep_descripcion = $_POST['descrip'];

        $query = mysqli_query($mysqli, "INSERT INTO sabor (id_sabor, descrip)
        VALUES ($codigo, '$dep_descripcion')") or die ('Error'.mysqli_error($mysqli));

        if ($query) {
            header("Location: ../../MAIN.PHP?module=deposito&alert=1");
        }else{
            header("Location: ../../MAIN.PHP?module=deposito&alert=4");
        }
    }
    }

elseif ($_GET['act']=='update') {
    if (isset($_POST['Guardar'])) {
        if (isset($_POST['codigo'])) {
            $codigo = $_POST['codigo'];
            $dep_descripcion = $_POST['descrip'];

            $query = mysqli_query($mysqli, "UPDATE sabor Set descrip = '$dep_descripcion'
                                                                Where id_sabor = $codigo")
                                                                or die ('Error'.mysqli_error($mysqli));
                                                
            if ($query) {
                header("Location: ../../MAIN.PHP?module=deposito&alert=2");
            }else{
                header("Location: ../../MAIN.PHP?module=deposito&alert=4");
            }
        }
    }
}elseif ($_GET['act']=='delete') {
    if (isset($_GET['id_sabor'])) {
        $codigo = $_GET['id_sabor'];
        
        $query = mysqli_query($mysqli, "DELETE FROM sabor
                                        Where id_sabor = $codigo")
                                        or die('Error'.mysqli_error($mysqli));

    if ($query) {
        header("Location: ../../MAIN.PHP?module=deposito&alert=3");
    }else{
        header("Location: ../../MAIN.PHP?module=deposito&alert=4");
    }
    }
}
}


?>