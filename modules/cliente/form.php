<?php
if ($_GET['form']=='add') {?>
    <section class="content-header">
        <h1>
            <i class="fa fa-edit icon-title">Agregar Cliente</i>
        </h1>
        <ol class="breadcrumb">
            <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
            <li><a href="?module=cliente">Cliente</a></li>
            <li class="active">Mas</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form role="form" class="form-horizontal" action="modules/cliente/proses.php?act=insert" method="POST">
                        <div class="box-body">
                            <?php 
                            //Metodo para generar codigo
                            $query_id = mysqli_query($mysqli, "SELECT MAX(id_cliente) as id from clientes")
                                                                or die('Error'.mysqli_error($mysqli));

                            $count = mysqli_num_rows($query_id);
                            if ($count <>0 ) {
                                $data_id = mysqli_fetch_assoc($query_id);
                                $codigo = $data_id['id']+1;
                            }else{
                                $codigo=1;
                            }
                            ?>
                            
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Código</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" name="codigo" value="<?php echo $codigo; ?>" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Nombre</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="cli_nombre"
                                    autocomplete="off" required>
                                </div>

                                <div class="form-group">
                                <label class="col-sm-2 control-label">Apellido</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="cli_apellido"
                                    autocomplete="off" required>
                                </div>
                            </div>
                                
                                <label class="col-sm-2 control-label">N° de CI o RUC</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="ci_ruc"
                                    onkeyPress="return goodchars(event, '0123456789', this)" required autocomplete="off">
                                </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Direccion</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="cli_direccion" autocomplete="off">
                                </div>
                            </div>

                                <label class="col-sm-2 control-label">Telefono</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="cli_telefono"
                                    onkeyPress="return goodchars(event, '0123456789', this)"
                                    autocomplete="off">
                                </div>

                            </div>

                                <div class="box-footer">
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10 ">
                                                <input type="submit" class="btn btn-primary btn-submit" name="Guardar" value="Guardar">
                                            <a href="?module=cliente" class="btn btn-default btn-reset">Cancelar</a>
                                        </div>
                                    </div>
                                </div>

                        </div>
                    </form>
                </div>

            </div>

        </div>

    </section>

<?php }
elseif ($_GET['form']=='edit') {
if (isset($_GET['id'])) {
    $query = mysqli_query($mysqli, "SELECT * from clientes where id_cliente = '$_GET[id]'")
                                    or die ('Error'.mysqli_error($mysqli));
    $data= mysqli_fetch_assoc($query);
} ?>

<section class="content-header">
        <h1>
            <i class="fa fa-edit icon-title">Modificar Cliente</i>
        </h1>
        <ol class="breadcrumb">
            <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
            <li><a href="?module=cliente">Cliente</a></li>
            <li class="active">Modificar</li>
        </ol>
</section>

<section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form role="form" class="form-horizontal" action="modules/cliente/proses.php?act=update" method="POST">
                        <div class="box-body">
                            
                        <div class="form-group">
                                <label class="col-sm-2 control-label">Código</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" name="codigo" value="<?php echo $data ['id_cliente']; ?>" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Nombre</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="cli_nombre" value="<?php echo $data['cli_nombre']; ?>"
                                    autocomplete="off" required>
                                </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Apellido</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="cli_apellido" value="<?php echo $data['cli_apellido']; ?>"
                                    autocomplete="off" required>
                                </div>
                            </div>

                                <label class="col-sm-2 control-label">N° de CI o Ruc</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="ci_ruc"
                                    onkeyPress="return goodchars(event, '0123456789', this)"
                                    value="<?php echo $data['ci_ruc']; ?>"required autocomplete="off">
                                </div>

                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Direccion</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="cli_direccion"
                                    value="<?php echo $data['cli_direccion']; ?>" autocomplete="off">
                                </div>

                                <label class="col-sm-2 control-label">Telefono</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="cli_telefono"
                                    onkeyPress="return goodchars(event, '0123456789', this)"
                                    value="<?php echo $data['cli_telefono']; ?>"autocomplete="off">
                                </div>

                            </div>

                                <div class="box-footer">
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10 ">
                                                <input type="submit" class="btn btn-primary btn-submit" name="Guardar" value="Guardar">
                                            <a href="?module=cliente" class="btn btn-default btn-reset">Cancelar</a>
                                        </div>
                                    </div>
                                </div>

                        </div>
                    </form>
                </div>

            </div>

        </div>

    </section>

<?php }
?>