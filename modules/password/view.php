<section class="content-header">
    <h1>
        <i class="fa fa-lock icon-title"></i> Modificar Contraseña
    </h1>
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
        <li class="active">Contraseña</li>
        <li class="active">Modificar</li>

    </ol>
</section>

<section  class="content">
    <div class="row">
        <div class="col-md-12">
            <!--Agregar los mensajes de errores-->
            <?php 
            if (empty($_GET['alert'])) {
                echo "";
            }
            elseif ($_GET['alert']==1){
                echo "<div class ='alert alert-danger alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4> <i class='icon fa fa-times-circle'></i>Error </h4>
                No se encuentra el error
                    </div>";
            }
            elseif ($_GET['alert']==2){
                echo "<div class ='alert alert-danger alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4> <i class='icon fa fa-times-circle'></i>Error </h4>
                La nueva contraseña ingresada no coincide
                    </div>";
            }
            elseif ($_GET['alert']==3){
                echo "<div class ='alert alert-success alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4> <i class='icon fa fa-check-circle'></i>Exitoso!</h4>
                La contraseña fue cambiada exitosamente
                    </div>";
            }
            ?>
            <div class="form-group">
               <form role="form" class="form-horizontal" method="POST" action="modules/password/proses.php">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="col-sm-2 control-label">Contraseña antigua</label>
                            <div class="col-sm-5">
                                <input type="password" class="form-control" name="old_pass" autocomplete="off" required>      
                             </div>
                        </div>

                        <div class="form-group">
                            <label for="col-sm-2 control-label">Contraseña Nueva</label>
                            <div class="col-sm-5">
                                <input type="password" class="form-control" name="new_pass" autocomplete="off" required>      
                             </div>
                        </div>

                        <div class="form-group">
                            <label for="col-sm-2 control-label">Confirmar contraseña</label>
                            <div class="col-sm-5">
                                <input type="password" class="form-control" name="retype_pass" autocomplete="off" required>      
                             </div>
                        </div>

                    </div>
                    <div class="box-footer bg-btn-action">
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" class="btn btn-primary btn-submit" name='Guardar' value='Guardar'>

                            </div>
                        </div>
                    </div>

               </form>

            </div>
        </div>

    </div>

</section>