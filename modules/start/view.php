<?php 
if ($_SESSION['permisos_acceso'] =='Super Admin') {?>

<section class="content-header">
    <h1>
        <i class="fa fa-home icon-title"></i>Inicio
    </h1>
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class=" fa fa-home"></i></a></li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-lg-12 col-xs-12">
            <div class="alert alert-info alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <p style="font-size:15px">
                <i class="icon fa fa-user "></i>Bienvenido/a <strong><?php echo $_SESSION['name_user']; ?></strong>
                a la aplicación: <strong>Sistema de Producción</strong>


                </p>
            </div>
        </div> 

    </div>


    <h2>Formulario de movimiento</h2>
     <!-- Fila principal de los bloques-->
    <div class="row">
        <!-- bloque 1 Compras -->
        <div class="col-lg-4 col-xs-6">
            <div style="background-color: #00c0ef; color: #fff" class="small-box">
                <div class="inner">
                    <p><strong>Compras</strong>
                        <ul>
                            <li>Registrar</li>
                            <li>la compra</li>
                            <li>de producto</li>
                        </ul>
                    </p>
                </div>
                <div class="icon">
                    <i class="glyphicon glyphicon-piggy-bank"></i>
                </div>
                <a href="?module=compras" class="small-box-footer" title="Registrar compras" data-toggle="tooltip">
                    <i class="fa fa-plus"></i></a>
            </div>
        </div>
        <!-- Fin bloque 1 Compras -->

        <!-- Bloque 2 - Ventas -->
        <div class="col-lg-4 col-xs-6">
            <div style="background-color: #00a65a; color: #fff" class="small-box">
                <div class="inner">
                    <p><strong>Ventas</strong>
                        <ul>
                            <li>Registrar</li>
                            <li>ventas</li>
                            <li> de productos</li>
                        </ul>
                    </p>
                </div>
                    <div class="icon">
                    <iconify-icon icon="bi-snow"></iconify-icon>
                    </div>
                <a href="?module=ventas" class="small-box-footer" title="Registrar ventas" data-toggle="tooltip">
                    <i class="fa fa-plus"></i></a>
            </div>
        </div>
        <!--Fin bloque 2  ventas -->

                <!--  bloque 3 Stock-->
        <div class="col-lg-4 col-xs-6">
            <div style="background-color: #f39c12; color: #fff" class="small-box">
                <div class="inner">
                    <p><strong>Stock de Ingredientes</strong>
                        <ul>
                            <li>Visualizar </li>
                            <li>Stock</li>
                            <li>de ingredientes</li>
                        </ul>
                    </p>
                </div>
                    <div class="icon">
                    <iconify-icon icon="ant-design:stock-outlined"></iconify-icon>
                    </div>
                <a href="?module=stock" class="small-box-footer" title="Ver Stock de ingredientes" data-toggle="tooltip">
                    <i class="fa fa-plus"></i></a>
            </div>
        </div>

    <!-- Fin bloque 3 Stock-->

        <div class="col-xl-4 col-lg-5">
            <div class="card no-shadow nb-4">
                <div class="card-header py-3 d-flex flex-row aling-items-center just"></div>
            </div>
        </div>
    </div>
</section>

<?php 
} elseif ($_SESSION['permisos_acceso']=='Compras'){?>

<!--Inicio-->
<section class="content-header">
    <h1>
        <i class="fa fa-home icon-title"></i>Inicio
    </h1>
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class=" fa fa-home"></i></a></li>
    </ol>
</section>

<!--Etiqueta de Bienvenido -->
<section class="content">
    <div class="row">
        <div class="col-lg-12 col-xs-12">
            <div class="alert alert-info alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <p style="font-size:15px">
                <i class="icon fa fa-user "></i>Bienvenido/a <strong><?php echo $_SESSION['name_user']; ?></strong>
                a la aplicación: <strong>SysWeb</strong>
                </p>
            </div>
        </div> 
    </div>

    <h2>Formulario de movimiento</h2>
     <!-- Fila principal de los bloques-->
    <div class="row">
        <!-- bloque 1 Compras -->
        <div class="col-lg-4 col-xs-6">
            <div style="background-color: #00c0ef; color: #fff" class="small-box">
                <div class="inner">
                    <p><strong>Compras</strong>
                        <ul>
                            <li>Registrar</li>
                            <li>la compra</li>
                            <li>de producto</li>
                        </ul>
                    </p>
                </div>
                <div class="icon">
                    <i class="glyphicon glyphicon-piggy-bank"></i>
                </div>
                <a href="?module=compras" class="small-box-footer" title="Registrar compras" data-toggle="tooltip">
                    <i class="fa fa-plus"></i></a>
            </div>
        </div>
        <!--Fin del bloque 1 -->

            <!--  bloque 3 Stock-->
        <div class="col-lg-4 col-xs-6">
            <div style="background-color: #f39c12; color: #fff" class="small-box">
                <div class="inner">
                    <p><strong>Stock de productos</strong>
                        <ul>
                            <li>Visualizar </li>
                            <li>Stock</li>
                            <li> de productos</li>
                        </ul>
                    </p>
                </div>
                    <div class="icon">
                    <iconify-icon icon="ant-design:stock-outlined"></iconify-icon>
                    </div>
                <a href="?module=stock" class="small-box-footer" title="Ver Stock de productos" data-toggle="tooltip">
                    <i class="fa fa-plus"></i></a>
            </div>
        </div>
    <!-- Fin bloque 3 Stock-->

    <div class="col-xl-4 col-lg-5">
            <div class="card no-shadow nb-4">
                <div class="card-header py-3 d-flex flex-row aling-items-center just"></div>
            </div>
        </div>
    </div>
</section>

<!--Usuarios de Ventas -->

<?php }elseif ($_SESSION['permisos_acceso']=='Ventas'){?>

<!--Inicio-->
<section class="content-header">
    <h1>
        <i class="fa fa-home icon-title"></i>Inicio
    </h1>
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class=" fa fa-home"></i></a></li>
    </ol>
</section>

<!--Etiqueta de Bienvenido -->
<section class="content">
    <div class="row">
        <div class="col-lg-12 col-xs-12">
            <div class="alert alert-info alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <p style="font-size:15px">
                <i class="icon fa fa-user "></i>Bienvenido/a <strong><?php echo $_SESSION['name_user']; ?></strong>
                a la aplicación: <strong>SysWeb</strong>
                </p>
            </div>
        </div> 
    </div>

    <h2>Formulario de movimiento</h2>
     <!-- Fila principal de los bloques-->
    <div class="row">
         <!--  bloque 2 Ventas-->
    
         <div class="col-lg-4 col-xs-6">
            <div style="background-color: #00a65a; color: #fff" class="small-box">
                <div class="inner">
                    <p><strong>Ventas</strong>
                        <ul>
                            <li>Registrar</li>
                            <li>ventas</li>
                            <li> de productos</li>
                        </ul>
                    </p>
                </div>
                    <div class="icon">
                    <iconify-icon icon="bi-snow"></iconify-icon>
                    </div>
                <a href="?module=ventas" class="small-box-footer" title="Registrar ventas" data-toggle="tooltip">
                    <i class="fa fa-plus"></i></a>
            </div>
        </div>
                <!-- Fin bloque 2 Ventas -->

            <!--  bloque 3 Stock-->
        <div class="col-lg-4 col-xs-6">
            <div style="background-color: #f39c12; color: #fff" class="small-box">
                <div class="inner">
                    <p><strong>Stock de productos</strong>
                        <ul>
                            <li>Visualizar </li>
                            <li>Stock</li>
                            <li> de productos</li>
                        </ul>
                    </p>
                </div>
                    <div class="icon">
                    <iconify-icon icon="ant-design:stock-outlined"></iconify-icon>
                    </div>
                <a href="?module=stock" class="small-box-footer" title="Ver Stock de productos" data-toggle="tooltip">
                    <i class="fa fa-plus"></i></a>
            </div>
        </div>
    <!-- Fin bloque 3 Stock-->

    <div class="col-xl-4 col-lg-5">
            <div class="card no-shadow nb-4">
                <div class="card-header py-3 d-flex flex-row aling-items-center just"></div>
            </div>
        </div>
    </div>
</section>

<?php } ?>