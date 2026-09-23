<?php
if ($_GET['form']=='add') {?>
    <section class="content-header">
        <h1>
            <i class="fa fa-edit icon-title">Agregar Ingredientes</i>
        </h1>
        <ol class="breadcrumb">
            <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
            <li><a href="?module=ingrediente">Ingredientes</a></li>
            <li class="active">Agregar</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form role="form" class="form-horizontal" action="modules/ingrediente/proses.php?act=insert" method="POST">
                        <div class="box-body">
                            
                            <?php 
                            //Metodo para generar codigo
                            $query_id = mysqli_query($mysqli, "SELECT MAX(id_ingrediente) as id from ingrediente")
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
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="codigo" value="<?php echo $codigo; ?>" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Ingrediente</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="descrip_ingrediente" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Tipo ingrediente</label>
                                <div class="col-sm-4">
                                    <select class="chosen-select" name="t_ingrediente"
                                        data-placeholder="-- Seleccionar tipo ingrediente --" autocomplete="off" required>
                                        <option value=""></option>
                                        <?php
                                        $query_prov = mysqli_query($mysqli, "SELECT *
                                            FROM tipo_ingrediente
                                            ORDER BY id_t_ingrediente ASC") or die('Error' . mysqli_error($mysqli));
                                        while ($data_prov = mysqli_fetch_assoc($query_prov)) {
                                            echo "<option value=\"$data_prov[id_t_ingrediente]\">$data_prov[t_ingrediente]</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Unidad de medida</label>
                                <div class="col-sm-4">
                                    <select class="chosen-select" name="unidad_medida"
                                        data-placeholder="-- Seleccionar unidad medida --" autocomplete="off" required>
                                        <option value=""></option>
                                        <?php
                                        $query_prov = mysqli_query($mysqli, "SELECT *
                                            FROM u_medida
                                            ORDER BY id_u_medida ASC") or die('Error' . mysqli_error($mysqli));
                                        while ($data_prov = mysqli_fetch_assoc($query_prov)) {
                                            echo "<option value=\"$data_prov[id_u_medida]\">$data_prov[u_descrip]</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                                <div class="box-footer">
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10 ">
                                                <input type="submit" class="btn btn-primary btn-submit" name="Guardar" value="Guardar">
                                            <a href="?module=ingrediente" class="btn btn-default btn-reset">Cancelar</a>
                                        </div>
                                    </div>
                                </div>

                        </div>
                    </form>
                </div>

            </div>

        </div>

    </section>

    <!--Editar ingrediente-->

<?php }
elseif ($_GET['form']=='edit') {
if (isset($_GET['id'])) {
    $query = mysqli_query($mysqli, "SELECT * from v_ingrediente where id_ingrediente = '$_GET[id]'")
                                    or die ('Error'.mysqli_error($mysqli));
    $data= mysqli_fetch_assoc($query);
} ?>

<section class="content-header">
        <h1>
            <i class="fa fa-edit icon-title">Modificar Ingrediente</i>
        </h1>
        <ol class="breadcrumb">
            <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
            <li><a href="?module=ingrediente">Ingrediente</a></li>
            <li class="active">Modificar</li>
        </ol>
</section>

<section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form role="form" class="form-horizontal" action="modules/ingrediente/proses.php?act=update" method="POST">
                        <div class="box-body">
                            
                        <div class="form-group">
                                <label class="col-sm-2 control-label">Código</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="codigo" value="<?php echo $data['id_ingrediente']; ?>" readonly>
                                </div>
                            </div>

                             <div class="form-group">
                                <label class="col-sm-2 control-label">Ingrediente</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="descrip_ingrediente" autocomplete="off" required
                                    value= "<?php echo $data['descrip_ingrediente']; ?>">
                                </div>
                            </div> 
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Tipo ingrediente</label>
                                <div class="col-sm-5">
                                    <select class="chosen-select" name="t_ingrediente" 
                                    data-placeholder="--Seleccionar tipo ingrediente--" 
                                    autocomplete="off" required >
                                        <option value="<?php echo $data['id_t_ingrediente'] ?>"> <?php echo $data['t_ingrediente']; ?></option>
                                        <?php 
                                        $query_ciu= mysqli_query($mysqli, "SELECT id_ingrediente, t.id_t_ingrediente, t.t_ingrediente
                                        FROM ingrediente i
                                        JOIN tipo_ingrediente t
                                        WHERE i.id_t_ingrediente=t.id_t_ingrediente ORDER BY id_ingrediente ASC")
                                                                                    or die('Error'.mysqli_error($mysqli));
                                        while ($data_pro = mysqli_fetch_assoc($query_ciu)){
                                            echo "<option value=\"$data_pro[t_ingrediente]\"></option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Unidad de medida</label>
                                <div class="col-sm-5">
                                    <select class="chosen-select" name="unidad_medida" 
                                    data-placeholder="--Seleccionar unidad de medida--" 
                                    autocomplete="off" required >
                                        <option value="<?php echo $data['id_u_medida'] ?>"> <?php echo $data['u_descrip']; ?></option>
                                        <?php 
                                        $query_ciu= mysqli_query($mysqli, "SELECT id_ingrediente, u.id_u_medida, u.u_descrip
                                        FROM ingrediente i
                                        JOIN u_medida u
                                        WHERE i.id_u_medida=u.id_u_medida ORDER BY id_ingrediente ASC")
                                                                                    or die('Error'.mysqli_error($mysqli));
                                        while ($data_pro = mysqli_fetch_assoc($query_ciu)){
                                            echo "<option value=\"$data_pro[u_descrip]\"></option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                                <div class="box-footer">
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10 ">
                                                <input type="submit" class="btn btn-primary btn-submit" name="Guardar" value="Guardar">
                                            <a href="?module=ingrediente" class="btn btn-default btn-reset">Cancelar</a>
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