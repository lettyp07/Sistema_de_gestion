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
        $razon_social = $_POST['cli_nombre'];
        $apellido = $_POST['cli_apellido'];
        $ruc = $_POST['ci_ruc'];

        if (!empty($_POST['cli_direccion'])) {
            $direccion = $_POST['cli_direccion'];
        }else {
            $direccion = "No se encuentran registros";
        }

        if (!empty($_POST['cli_telefono'])) {
            $telefono = $_POST['cli_telefono'];
        }else {
            $telefono = 000;
        }

        $query = mysqli_query($mysqli, "INSERT INTO clientes (id_cliente, cli_nombre, cli_apellido, ci_ruc, cli_direccion, cli_telefono)
        VALUES ($codigo, '$razon_social', '$apellido', $ruc, '$direccion', $telefono )") or die ('Error'.mysqli_error($mysqli));

        if ($query) {
            header("Location: ../../MAIN.PHP?module=cliente&alert=1");
        }else{
            header("Location: ../../MAIN.PHP?module=cliente&alert=4");
        }
    }
    }

elseif ($_GET['act']=='update') {
    if (isset($_POST['Guardar'])) {
        if (isset($_POST['codigo'])) {
            $codigo         = $_POST['codigo'];
            $razon_social   = $_POST['cli_nombre'];
            $apellido  = $_POST['cli_apellido'];
            $ruc            = $_POST['ci_ruc'];

            if (!empty($_POST['direccion'])) {
                $direccion = $_POST['direccion'];
            }else {
                $direccion = "No se encuentran registros";
            }
    
            if (!empty($_POST['telefono'])) {
                $telefono = $_POST['telefono'];
            }else {
                $telefono = 000;
            }

            $query = mysqli_query($mysqli, "UPDATE clientes Set cli_nombre = '$razon_social',
                                                                cli_apellido = '$apellido',
                                                                ci_ruc = $ruc,
                                                                cli_direccion = '$direccion',
                                                                cli_telefono = $telefono
                                                                Where id_cliente = $codigo")
                                                                or die ('Error'.mysqli_error($mysqli));
                                                
            if ($query) {
                header("Location: ../../MAIN.PHP?module=cliente&alert=2");
            }else{
                header("Location: ../../MAIN.PHP?module=cliente&alert=4");
            }
        }
    }
}elseif ($_GET['act']=='delete') {
    if (isset($_GET['id_cliente'])) {
        $codigo = $_GET['id_cliente'];
        
        $query = mysqli_query($mysqli, "DELETE FROM clientes
                                        Where id_cliente = $codigo")
                                        or die('Error'.mysqli_error($mysqli));

    if ($query) {
        header("Location: ../../MAIN.PHP?module=cliente&alert=3");
    }else{
        header("Location: ../../MAIN.PHP?module=cliente&alert=4");
    }
    }
}
}


?>