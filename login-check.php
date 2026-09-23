<?php 
require_once "config/database.php";

$username = mysqli_real_escape_string($mysqli, stripslashes(strip_tags(htmlspecialchars(trim($_POST['username'])))));
$password = md5(mysqli_real_escape_string($mysqli, stripslashes(strip_tags(htmlspecialchars(trim($_POST['password']))))));

if (!ctype_alnum($username) OR !ctype_alnum($password)) {
    header("Location: index.php?alert=1");
} else {
    // Consulta al usuario para verificar su estado y los intentos fallidos
    $query = mysqli_query($mysqli, "SELECT * FROM usuarios WHERE username='$username'")
    or die('error'.mysqli_error($mysqli));
    
    if ($data = mysqli_fetch_assoc($query)) {
        if ($data['status'] != 'activo') {
            // Si el usuario está inactivo, redirige o muestra mensaje de bloqueo
            header("Location: index.php?alert=5");
        } elseif ($data['intentos_fallidos'] >= 3) {
            // Si tiene 3 o más intentos fallidos, redirige o bloquea acceso
            header("Location: index.php?alert=4"); // Usuario bloqueado
        } else {
            // Verifica la contraseña
            if ($data['password'] === $password) {
                // Reinicia el contador de intentos fallidos y concede el acceso
                mysqli_query($mysqli, "UPDATE usuarios SET intentos_fallidos=0 WHERE username='$username'");
                
                session_start();
                $_SESSION['id_user'] = $data['id_user'];
                $_SESSION['username'] = $data['username'];
                $_SESSION['name_user'] = $data['name_user'];
                $_SESSION['permisos_acceso'] = $data['permisos_acceso'];
                header("Location: MAIN.PHP?module=start");

            } else {
                // Incrementa el contador de intentos fallidos
                $intentos_fallidos = $data['intentos_fallidos'] + 1;
                mysqli_query($mysqli, "UPDATE usuarios SET intentos_fallidos=$intentos_fallidos WHERE username='$username'");
                header("Location: index.php?alert=1"); // Contraseña incorrecta
            }
        }
    } else {
        header("Location: index.php?alert=1"); // Usuario no encontrado
    }
}
if ($data['intentos_fallidos'] >= 3) {
    // Cambiar el estado a "bloqueado"
    mysqli_query($mysqli, "UPDATE usuarios SET status='bloqueado' WHERE username='$username'");
    header("Location: index.php?alert=4"); // Redirige con mensaje de usuario bloqueado
}

?>
