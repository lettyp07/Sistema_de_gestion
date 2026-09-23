<?php
// Establecer la zona horaria
date_default_timezone_set('America/Asuncion'); 
$fechaActual = date("Y-m-d");
?>
<?php
if ($_GET['form'] == 'add') { ?>
    <section class="content-header">
        <h1>
            <i class="fa fa-edit icon-title">Agregar Orden</i>
        </h1>
        <ol class="breadcrumb">
            <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
            <li><a href="?module=orden_produccion"> Orden Producción</a></li>
            <li class="active">Agregar</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form role="form" class="form-horizontal" action="modules/orden_produccion/proses.php?act=insert" method="POST">
                        <div class="box-body">
                            <?php
                            //Método para generar código
                            $query_id = mysqli_query($mysqli, "SELECT MAX(id_orden_produccion) as id FROM orden_produccion")
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
                                    <input type="text" class="form-control" name="codigo" value="<?php echo $codigo; ?>" readonly>
                                </div>

                                <label class="col-sm-1 control-label">Fecha</label>
                                <div class="col-sm-2">
                                    <input type="date" class="form-control date-picker" data-date-format="dd-mm-yyyy" name="fecha_a" id="fecha" min="<?php echo $fechaActual; ?>" value="<?php echo $fechaActual; ?>" disabled>
                                    <input type="hidden" name="fecha" value="<?php echo $fechaActual; ?>">
                                </div>

                                <label class="col-sm-1 control-label">Hora</label>
                                <div class="col-sm-2">
                                    <input type="time" class="form-control date-picker" data-date-format="h-m-s" name="hora_a" id="hora" min="<?php echo date("H:i:s"); ?>" value="<?php echo date("H:i:s"); ?>" disabled>
                                    <input type="hidden" name="hora" value="<?php echo date("H:i:s"); ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Pedido Cliente</label>
                                <div class="col-sm-4">
                                    <select class="chosen-select" name="codigo_pedido" data-placeholder="Selecciona un pedido" id="codigo_pedido" onchange="cargarDetallerPedido();">
                                        <option value="0"></option>
                                        <?php
                                        $query_prov = mysqli_query($mysqli, "SELECT pc.id_pedido_cliente, c.ci_ruc, c.cli_nombre
                                        FROM pedido_cliente pc
                                        JOIN clientes c ON pc.id_cliente = c.id_cliente
                                        WHERE pc.estado = 'ENVIADO'
                                        ORDER BY pc.id_pedido_cliente ASC;") or die('Error' . mysqli_error($mysqli));
                                        while ($data_prov = mysqli_fetch_assoc($query_prov)) {
                                            echo "<option value='{$data_prov['id_pedido_cliente']}'>NRO PEDIDO ({$data_prov['id_pedido_cliente']}) | CLIENTE: ({$data_prov['cli_nombre']})</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Fecha Inicio</label>
                            <div class="col-sm-2">
                                <input type="date" class="form-control" min="<?php echo date("Y-m-d"); ?>"  name="fecha_ini" id="fecha_ini" autocomplete="off" 
                                    value="<?php echo date("Y-m-d"); ?>" required>
                            </div>
                        
                            <label class="col-sm-1 control-label">Fecha Fin</label>
                            <div class="col-sm-2">
                                <input type="date" class="form-control" min="<?php echo date("Y-m-d"); ?>" name="fecha_fin" id="fecha_fin" autocomplete="off" 
                                    value="<?php echo date("Y-m-d"); ?>" required>
                            </div>
                        </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Equipo de producción:</label>
                                <div class="col-sm-3">
                                    <select class="chosen-select" name="codigo_equipo" data-placeholder="Selecciona equipo"
                                        autocomplete="off" required id="codigo_equipo">
                                        <option value="0"></option>
                                        <?php
                                        $query_prov = mysqli_query($mysqli, "SELECT * FROM equipo 
                                        ORDER BY id_equipo ASC") or die('Error' . mysqli_error($mysqli));
                                        while ($data_prov = mysqli_fetch_assoc($query_prov)) {
                                            echo "<option value=\"$data_prov[id_equipo]\"> $data_prov[id_equipo] - $data_prov[descrip]</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <hr>
                            <div id="resultados" class="col-md-9"></div>

                            <div class="box-footer">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="submit" class="btn btn-primary btn-submit" name="Guardar" value="Guardar">
                                        <a href="?module=orden_produccion" class="btn btn-default btn-reset">Cancelar</a>
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
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>

<script>
 $(document).ready(function () {
    // Cargar productos en la tabla al iniciar
    load(1);

    // Evento para cargar detalles del pedido al seleccionar un pedido
    $("#codigo_pedido").on("change", function () {
        cargarDetallerPedido();
    });
});

// Función para cargar productos paginados
function load(page) {
    const x = $("#x").val(); // Valor adicional, podría ser filtro
    const parametros = { "action": "ajax", "page": page, "x": x };

    $("#loader").fadeIn('slow'); // Mostrar loader mientras se realiza la solicitud
    $.ajax({
        url: './ajax/productos_pedidos.php', // Cambiar si la ruta es distinta
        data: parametros,
        beforeSend: function () {
            $('#loader').html('<img src="./images/ajax-loader.gif"> Cargando...');
        },
        success: function (data) {
            $(".outer_div").html(data).fadeIn('slow'); // Actualizar lista de productos
            $('#loader').html('');
        },
        error: function () {
            $('#loader').html('<span style="color:red;">Error al cargar productos.</span>');
        }
    });
}

// Función para cargar detalles del pedido
function cargarDetallerPedido() {
    const pedidoId = $("#codigo_pedido").val(); // Obtener el ID del pedido seleccionado

    if (!pedidoId || pedidoId === "0") {
        alert("Por favor selecciona un pedido válido.");
        return;
    }

    $("#resultados").html("<p>Cargando detalles...</p>"); // Mensaje mientras se carga

    $.ajax({
        type: "GET",
        url: "modules/orden_produccion/ajaxDetalles.php", // Ruta para obtener detalles
        data: { id: pedidoId }, // Pasar ID del pedido
        success: function (datos) {
            $("#resultados").html(datos); // Mostrar datos en el contenedor
        },
        error: function () {
            $("#resultados").html("<p style='color:red;'>Error al cargar los detalles del pedido.</p>");
        }
    });
}

</script>
