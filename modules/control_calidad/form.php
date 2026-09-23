<?php
// Establecer la zona horaria
date_default_timezone_set('America/Asuncion');
$fechaActual = date("Y-m-d");
$horaActual = date("H:i:s");
?>

<?php if ($_GET['form'] == 'add') { ?>
<section class="content-header">
    <h1>
        <i class="fa fa-edit icon-title"></i> Agregar Control Calidad
    </h1>
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i> Inicio</a></li>
        <li><a href="?module=control_calidad">Control Calidad</a></li>
        <li class="active">Agregar</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <form role="form" class="form-horizontal" action="modules/control_calidad/proses.php?act=insert" method="POST">
                    <div class="box-body">
                        <?php
                        // Generar código único
                        $query_id = mysqli_query($mysqli, "SELECT MAX(id_control_calidad) AS id FROM control_calidad")
                            or die('Error: ' . mysqli_error($mysqli));
                        $codigo = ($query_id && mysqli_num_rows($query_id) > 0) 
                            ? mysqli_fetch_assoc($query_id)['id'] + 1 
                            : 1;
                        ?>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Código</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="codigo" value="<?php echo $codigo; ?>" readonly>
                            </div>

                            <label class="col-sm-1 control-label">Fecha</label>
                            <div class="col-sm-2">
                                <input type="date" class="form-control" name="fecha" value="<?php echo $fechaActual; ?>" readonly>
                            </div>

                            <label class="col-sm-1 control-label">Hora</label>
                            <div class="col-sm-2">
                                <input type="time" class="form-control" name="hora" value="<?php echo $horaActual; ?>" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Orden de Producción:</label>
                            <div class="col-sm-5">
                                <select class="form-control" name="codigo_orden" id="codigo_orden" required>
                                    <option value="">Selecciona una orden</option>
                                    <?php
                                    $query_prov = mysqli_query($mysqli, "SELECT DISTINCT 
                                    op.id_orden_produccion, 
                                    cli.cli_nombre, 
                                    cli.ci_ruc
                                FROM orden_produccion op
                                JOIN pedido_cliente p ON p.id_pedido_cliente = op.id_pedido_cliente
                                JOIN clientes cli ON p.id_cliente = cli.id_cliente
                                JOIN control_produccion cp ON op.id_orden_produccion = cp.id_orden_produccion
                                WHERE op.estado = 'utilizado' 
                                  AND p.estado != 'disponible';                                
                                ");
                                    while ($row = mysqli_fetch_assoc($query_prov)) {
                                        echo "<option value=\"{$row['id_orden_produccion']}\">Orden: {$row['id_orden_produccion']} - Cliente: {$row['cli_nombre']} (CI: {$row['ci_ruc']})</option>";
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
                                    <a href="?module=control_calidad" class="btn btn-default btn-reset">Cancelar</a>
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
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>

<script>
$(document).ready(function () {
    // Evento para cargar detalles del pedido al seleccionar una orden
    $("#codigo_orden").on("change", function () {
        cargarDetallePedido();
    });
});

// Función para cargar detalles del pedido
function cargarDetallePedido() {
    const pedidoId = $("#codigo_orden").val();

    if (!pedidoId) {
        alert("Por favor selecciona una orden válida.");
        return;
    }

    $("#resultados").html("<p>Cargando detalles...</p>");

    $.ajax({
    type: "GET",  
    url: "modules/control_calidad/ajaxDetalles.php",
    data: { id: pedidoId },  
    success: function (datos) {
        $("#resultados").html(datos);
    },
    error: function () {
        $("#resultados").html("<p style='color:red;'>Error al cargar los detalles del pedido.</p>");
    }
});

}
</script>
