<?php 
    include "config/database.php";
    $query =mysqli_query($mysqli, "SELECT id_user, name_user, foto, permisos_acceso FROM usuarios 
    WHERE id_user='$_SESSION[id_user]'")
    or die('error'.mysqli_error($mysqli));
    $data= mysqli_fetch_assoc($query);

?>
<li class="dropdwon user user-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <?php
        if($data['foto']==""){?>
        <img src="images/user/user-default.png" class="user-image" alt="Imagen de usuario">

<?php }
else{?>      
        <img src="images/user/<?php echo $data['foto']?>" class="user-image" alt="Imagen de usuario">
     <?php 
    }
    ?>
     <span class="hidden-xs"><?php echo $data['name_user'];?><i style="margin: left 5px;" class="fa fa-angle-down" ></i></span>

    </a>
    <ul class="dropdown-menu">
        <li class="user-header">
        <?php
        if($data['foto']==""){?>
        <img src="images/user/user-default.png" class="img-circle" alt="Imagen de usuario">

<?php }
else{?>      
        <img src="images/user/<?php echo $data['foto']?>" class="img-circle" alt="Imagen de usuario">
     <?php 
    }
    ?>
    <p>
<?php echo $data['name_user'];?>
<small><?php echo $data['permisos_acceso'];?></small>

    </p>
        </li>
        <li class="user_footer">
            <div class="pull-left">
                <a style="width:80px" href="?module=perfil" class="btn btn-default btn-flat">Perfil</a>
            </div>
            <div class="pull-right">
                <a style="width:80px" data-toggle="modal" href="#logout" class="btn btn-default btn-flat">Salir</a>
            </div>
        </li>
    </ul>
</li>