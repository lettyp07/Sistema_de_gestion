<?php
        // Establecer la zona horaria
        date_default_timezone_set('America/Asuncion'); 
        $fechaActual = date("Y-m-d");
?>
<?php
if ($_GET['form'] == 'add') { ?>
    <section class="content-header">
        <h1>
            <i class="fa fa-edit icon-title">Agregar Pedido</i>
        </h1>
        <ol class="breadcrumb">
            <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
            <li><a href="?module=pedido"> Pedidos Ingredientes</a></li>
            <li class="active">Agregar</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form role="form" class="form-horizontal" action="modules/pedido/proses.php?act=insert" method="POST">
                        <div class="box-body">
                            <?php
                            //Método para generar código
                            $query_id = mysqli_query($mysqli, "SELECT MAX(id_pedido) as id FROM pedido_compra")
                                or die('Error' . mysqli_error($mysqli));

                            $count = mysqli_num_rows($query_id);
                            if ($count <> 0) {
                                $data_id = mysqli_fetch_assoc($query_id);
                                $codigo = $data_id['id'] + 1;
                            } else {
                                $codigo = 1;
                            }
                            ?>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Código</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" name="codigo" value="<?php echo $codigo; ?>"
                                        readonly>
                                </div>

                                <label class="col-sm-1 control-label">Fecha</label>
                                    <div class="col-sm-2">
                                        <input type="date" class="form-control date-picker" data-date-format="dd-mm-yyyy"
                                            name="fecha_a" id="fecha" min="<?php echo $fechaActual; ?>"
                                            value="<?php echo $fechaActual; ?>" disabled>
                                        <input type="text" name="fecha" hidden value="<?php echo $fechaActual; ?>">
                                    </div>

                            <label class="col-sm-1 control-label">Hora</label>
                                <div class="col-sm-2">
                                    <input type="time" class="form-control date-picker" data-date-format="h-m-s" name="hora_a"
                                        min="<?php echo date("H:i:s"); ?>" 
                                         value="<?php echo date("H:i:s"); ?>"  disabled>
                                         <input type="text" name="hora" hidden value="<?php echo date("H:i:s"); ?>">
                                </div>
                            </div>

                            <hr>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label class="col-sm-2 control-label">Ingredientes</label>
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">
                                        <span class="glyphicon glyphicon-plus">Agregar Ingredientes</span>
                                    </button>
                                </div>
                            </div>
                            <div id="resultados" class="col-md-9"></div>

                            <div class="box-footer">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="submit" class="btn btn-primary btn-submit" name="Guardar"
                                            value="Guardar">
                                        <a href="?module=pedido" class="btn btn-default btn-reset">Cancelar</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>

<?php } ?>


<?php
if ($_GET['form'] == 'edit') {
    if (isset($_GET['id'])) {
        $query = mysqli_query($mysqli, "SELECT * from pedido_compra where id_pedido = '$_GET[id]'")
                                        or die ('Error'.mysqli_error($mysqli));
        $data= mysqli_fetch_assoc($query);
    } ?>  

    <section class="content-header">
        <h1>
            <i class="fa fa-edit icon-title">Modificar Pedido</i>
        </h1>
        <ol class="breadcrumb">
            <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
            <li><a href="?module=pedido"> Pedidos Ingredientes</a></li>
            <li class="active">Modificar</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form role="form" class="form-horizontal" action="modules/pedido/proses.php?act=update" method="POST">
                        <div class="box-body">

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Código</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" name="codigo" value="<?php echo ['id_pedido']; ?>"
                                        readonly>
                                </div>

                            
                                <label class="col-sm-1 control-label">Fecha</label>
                                    <div class="col-sm-2">
                                        <input type="date" class="form-control date-picker" data-date-format="dd-mm-yyyy"
                                            name="fecha_a" id="fecha" min="<?php echo $fechaActual; ?>"
                                            value="<?php echo $fechaActual; ?>" disabled>
                                        <input type="text" name="fecha" hidden value="<?php echo $fechaActual; ?>">
                                    </div>

                            <label class="col-sm-1 control-label">Hora</label>
                                <div class="col-sm-2">
                                    <input type="time" class="form-control date-picker" data-date-format="h-m-s" name="hora_a"
                                        min="<?php echo date("H:i:s"); ?>" 
                                         value="<?php echo date("H:i:s"); ?>"  disabled>
                                         <input type="text" name="hora" hidden value="<?php echo date("H:i:s"); ?>">
                                </div>
                            </div>

                            <hr>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label class="col-sm-2 control-label">Ingredientes</label>
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">
                                        <span class="glyphicon glyphicon-plus">Agregar Ingredientes</span>
                                    </button>
                                </div>
                            </div>
                            <div id="resultados" class="col-md-9"></div>

                            <div class="box-footer">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="submit" class="btn btn-primary btn-submit" name="Guardar"
                                            value="Guardar">
                                        <a href="?module=pedido" class="btn btn-default btn-reset">Cancelar</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>

<?php } ?>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
    integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
    crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        load(1);
    });

    function load(page) {
        var x = $("#x").val();
        //JSON
        var parametros = { "action": "ajax", "page": page, "x": x };
        $("#loader").fadeIn('slow');
        $.ajax({
            url: './ajax/productos_pedido1.php',
            data: parametros,
            beforeSend: function (objeto) {
                $('#loader').html('<img src="./images/ajax-loader.gif"> Cargando...');
            },
            success: function (data) {
                $(".outer_div").html(data).fadeIn('slow');
                $('#loader').html('');
            }
        })

    }
</script>
<script>
    function agregar(id) {
        //VALIDAR QUE NO EXISTA EL PRODUCTO
        let repetido = false;
        //console.log("VALOR DE ID-> "+id)
        // Itera sobre cada fila (tr) dentro del cuerpo de la tabla (tbody) con el id 'resultados'
        $("#resultados tbody tr").each(function (evt) {
            // Imprime en la consola el texto del primer td (columna) de la fila actual
            //#myModalconsole.log($(this).find('td:eq(0)').text());

            // Comprueba si el texto del primer td es igual a la variable 'id'
            if ($(this).find('td:eq(0)').text() == id) {
                // Si es igual, establece la variable 'repetido' a true
                repetido = true;
            }
        });

        // Si 'repetido' es true, muestra una alerta y termina la ejecución de la función
        if (repetido) {
            alert("El ingrediente ya ha sido agregado anteriormente");
            return;
        }

        //var precio_compra=$('#precio_compra_'+id).val();
        var cantidad = $('#cantidad_' + id).val();
        if (isNaN(cantidad)) {
            alert('Esto no es un nro');
            document.getElementById('cantidad_' + id).focus();
            return false;
        }
        //fin de la validación
        var parametros = { "id": id, "cantidad": cantidad };
        $.ajax({
            type: "POST",
            url: "./ajax/agregar_pedido.php",
            data: parametros,
            beforeSend: function (objeto) {
                $("#resultados").html("Mensaje: Cargando...");
            },
            success: function (datos) {
                $("#resultados").html(datos);
            }
        });
    }
    function eliminar(id) {
        $.ajax({
            type: "GET",
            url: "./ajax/agregar_pedido.php",
            data: "id=" + id,
            beforeSend: function (objeto) {
                $("#resultados").html("Mensaje: Cargando...");
            },
            success: function (datos) {
                $("#resultados").html(datos);
            }
        });
    }

</script>


<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModallabel">Buscar Ingredientes</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="x" placeholder="Buscar ingredientes"
                                onkeyup="load(1)">
                        </div>
                        <button type="button" class="btn btn-default" onclick="load(1)"><span
                                class="glyphicon glyphicon-search"></span>Buscar</button>
                    </div>
                </form>
                <div id="loader" style="position: absolute; text-align: center; top: 55px; width:100%; display:none;">
                </div>
                <div class="outer_div"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>