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
        $dep_descripcion = $_POST['dep_descripcion'];

        $query = mysqli_query($mysqli, "INSERT INTO departamento (id_departamento, dep_descripcion)
        VALUES ($codigo, '$dep_descripcion')") or die ('Error'.mysqli_error($mysqli));

        if ($query) {
            header("Location: ../../MAIN.PHP?module=departamento&alert=1");
        }else{
            header("Location: ../../MAIN.PHP?module=departamento&alert=4");
        }
    }
    }

elseif ($_GET['act']=='update') {
    if (isset($_POST['Guardar'])) {
        if (isset($_POST['codigo'])) {
            $codigo = $_POST['codigo'];
            $dep_descripcion = $_POST['dep_descripcion'];

            $query = mysqli_query($mysqli, "UPDATE departamento Set dep_descripcion = '$dep_descripcion'
                                                                Where id_departamento = $codigo")
                                                                or die ('Error'.mysqli_error($mysqli));
                                                
            if ($query) {
                header("Location: ../../MAIN.PHP?module=departamento&alert=2");
            }else{
                header("Location: ../../MAIN.PHP?module=departamento&alert=4");
            }
        }
    }
}elseif ($_GET['act']=='delete') {
    if (isset($_GET['id_departamento'])) {
        $codigo = $_GET['id_departamento'];
        
        $query = mysqli_query($mysqli, "DELETE FROM departamento
                                        Where id_departamento = $codigo")
                                        or die('Error'.mysqli_error($mysqli));

    if ($query) {
        header("Location: ../../MAIN.PHP?module=departamento&alert=3");
    }else{
        header("Location: ../../MAIN.PHP?module=departamento&alert=4");
    }
    }
}
}


?>