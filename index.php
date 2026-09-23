<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes" name="viewport">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="description" content="Sistema de Producción">
    <meta name="author" content="Letty Peña">

    <link rel="shortcut icon" href="assets/img/favicon.ico" />
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugin/font-awesome-4.6.3/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.css" rel="stylesheet" type="text/css" />

    <title>Sistema de Producción</title>
    <style>
        body {
            background-image: url('assets/img/fondo_pizza.jpg'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: #ffffff;
        }

        .login-box {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 8px;
            max-width: 400px;
            margin: auto;
            margin-top: 100px;
            text-align: center;
        }

        .login-logo img {
            margin-top: -15px;
            height: 50px;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <div style="color: #ffffff" class="login-logo"> 
            <img src="assets/img/" alt="" height="50" />
            <b>Acceso al sistema</b>
        </div>

        <?php 
        if (empty($_GET['alert'])) {
            echo "";
        }
        elseif ($_GET['alert'] == 1) {
            echo "<div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h4><i class='icon fa fa-times-circle'></i> Error al iniciar sesión</h4>
            El usuario o contraseña es incorrecto
            </div>";
        }
        elseif ($_GET['alert'] == 2) {
            echo "<div class='alert alert-success alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h4><i class='icon fa fa-check-circle'></i> Salida exitosa</h4>
            Has cerrado tu sesión correctamente
            </div>";
        }
        elseif ($_GET['alert'] == 3) {
            echo "<div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h4><i class='icon fa fa-times-circle'></i> Atención</h4>
            Debes ingresar un usuario y contraseña
            </div>";
        }
        elseif ($_GET['alert'] == 4) {
            echo "<div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h4><i class='icon fa fa-times-circle'></i> Atención</h4>
            El usuario se encuentra bloqueado
            </div>";
        }
        elseif ($_GET['alert'] == 5) {
            echo "<div class='alert alert-success alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h4><i class='icon fa fa-check-circle'></i> Éxito!</h4>
            El usuario se ha activado con éxito
            </div>";
        }
        elseif ($_GET['alert'] == 6) {
            echo "<div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h4><i class='icon fa fa-times-circle'></i> Atención</h4>
            El usuario se encuentra inactivo
            </div>";
        }
        ?>

        <div class="login-box-body">
            <p class="login-box-msg"><i class="fa fa-user icon-title"></i> Por favor inicie sesión</p>
            <br>
            <form action="login-check.php" method="POST">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" name="username" placeholder="Usuario" autocomplete="off" required>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" name="password" placeholder="Contraseña" autocomplete="off" required>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <br>
                <div class="row">
                    <div class="col-xs-12">
                        <input type="submit" class="btn btn-primary btn-lg btn-block btn-flat" name="login" value="Ingresar">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="assets/plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
</body>
</html>
