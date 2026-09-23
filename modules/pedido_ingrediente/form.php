<?php
// Establecer la zona horaria
date_default_timezone_set('America/Asuncion'); 
$fechaActual = date("Y-m-d");
$horaActual = date("H:i:s");
?>

<?php if ($_GET['form'] == 'add') { ?>
<section class="content-header">
    <h1>
        <i class="fa fa-edit icon-title"> Agregar Pedido</i>
    </h1>
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i> Inicio</a></li>
        <li><a href="?module=pedido_ingrediente">Pedidos Ingredientes</a></li>
        <li class="active">Agregar</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <form role="form" class="form-horizontal" action="modules/pedido_ingrediente/proses.php?act=insert" method="POST">
                    <div class="box-body">
                        <?php
                        // Obtener el último ID de pedido
                        $query_id = mysqli_query($mysqli, "SELECT MAX(id_pedido_ingrediente) as id FROM pedido_ingrediente")
                            or die('Error: ' . mysqli_error($mysqli));

                        $data_id = mysqli_fetch_assoc($query_id);
                        $codigo = $data_id ? $data_id['id'] + 1 : 1;
                        ?>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Código</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="codigo" value="<?php echo $codigo; ?>" readonly>
                            </div>

                            <label class="col-sm-1 control-label">Fecha</label>
                            <div class="col-sm-2">
                                <input type="date" class="form-control" name="fecha_a" value="<?php echo $fechaActual; ?>" disabled>
                                <input type="hidden" name="fecha" value="<?php echo $fechaActual; ?>">
                            </div>

                            <label class="col-sm-1 control-label">Hora</label>
                            <div class="col-sm-2">
                                <input type="time" class="form-control" name="hora_a" value="<?php echo $horaActual; ?>" disabled>
                                <input type="hidden" name="hora" value="<?php echo $horaActual; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Pedido Cliente</label>
                            <div class="col-sm-4">
                                <select class="chosen-select" name="codigo_pedido" id="codigo_pedido" onchange="cargarDetallesPedido();">
                                    <option value="0"></option>
                                    <?php
                                    $query_prov = mysqli_query($mysqli, "SELECT pc.id_pedido_cliente, c.ci_ruc, c.cli_nombre
                                    FROM pedido_cliente pc
                                    JOIN clientes c ON pc.id_cliente = c.id_cliente
                                    WHERE pc.estado = 'activo'
                                    ORDER BY pc.id_pedido_cliente ASC;") 
                                        or die('Error: ' . mysqli_error($mysqli));
                                    while ($data_prov = mysqli_fetch_assoc($query_prov)) {
                                        echo "<option value='{$data_prov['id_pedido_cliente']}'>NRO PEDIDO ({$data_prov['id_pedido_cliente']}) | CLIENTE: ({$data_prov['cli_nombre']})</option>";
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
                                    <a href="?module=pedido_ingrediente" class="btn btn-default btn-reset">Cancelar</a>
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
        $('.chosen-select').select2();
    });

    function cargarDetallesPedido() {
        const pedidoId = $('#codigo_pedido').val();
        if (!pedidoId || pedidoId === '0') {
            alert('Por favor selecciona un pedido válido.');
            return;
        }
        
        $.ajax({
            type: 'GET',
            url: 'modules/pedido_ingrediente/ajaxDetalles.php',
            data: { id: pedidoId },
            success: function (datos) {
                $('#resultados').html(datos);
            },
            error: function () {
                alert('Error al cargar los detalles del pedido.');
            }
        });
    }
</script>

