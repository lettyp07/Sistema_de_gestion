<?php if ($_GET['form'] == 'add') { ?>
<section class="content-header">
    <h1>
        <i class="fa fa-edit icon-title">Agregar Nota de compra</i>
    </h1>
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
        <li><a href="?module=orden_compra"> Nota de compra</a></li>
        <li class="active">Agregar</li>
    </ol>
</section>      

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <form role="form" class="form-horizontal">
                    <div class="box-body">
                        <?php
                        // Método para generar código
                        $query_id = mysqli_query($mysqli, "SELECT MAX(cod_nota_credito_compra) as id FROM nota_credito_compra")
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
                                <input type="text" class="form-control date-picker" data-date-format="dd-mm-yyyy" name="fecha" id="fecha" autocomplete="off" 
                                       value="<?php echo date("Y-m-d"); ?>" readonly>
                            </div>

                            <label class="col-sm-1 control-label">Hora</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control date-picker" data-date-format="H-mm-ss" name="hora" id="hora" autocomplete="off" 
                                       value="<?php echo date("H:i:s"); ?>" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Compras</label>
                            <div class="col-sm-4">
                                <select class="chosen-select" name="codigo_presupuesto" data-placeholder="Seleccionar compra"
                                        autocomplete="off" required id="codigo_pedido" onchange="cargarDetallerPedido(); return false;">
                                    <option value="0"></option>
                                    <?php
                                    $query_prov = mysqli_query($mysqli, "SELECT pr.cod_compra, p.razon_social, p.ruc, pr.nro_factura
                                    FROM compra pr
                                    JOIN proveedor  p
                                    ON p.cod_proveedor = pr.cod_proveedor
                                    WHERE pr.estado =  'activo'
                                   ORDER BY pr.cod_compra ASC") or die('Error' . mysqli_error($mysqli));
                                    while ($data_prov = mysqli_fetch_assoc($query_prov)) {
                                        echo "<option value=\"$data_prov[cod_compra]\">$data_prov[razon_social] | $data_prov[ruc] | NRO DE FACTURA ($data_prov[nro_factura])</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <label class="col-sm-1 control-label">Tipo</label>
                            <div class="col-sm-3">
                                <select name="tipo" id="tipo" class="form-control">
                                    <option value="CREDITO">CREDITO</option>
                                    <option value="DEBITO">DEBITO</option>
                                </select>
                            </div>
                        </div>
                        <hr>        
                        <div id="resultados" class="col-md-9"></div>
                        <div class="box-footer">
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input class="btn btn-primary btn-submit" onclick="guardar(); return false;" name="Guardar" value="Guardar">
                                    <a href="?module=notas" class="btn btn-default btn-reset">Cancelar</a>
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
    function guardar() {
        if($("#codigo_pedido").val() === "0"){
             alert('Debes seleccionar un presupuesto');
            return false;
        }
        if ($("#total_general").text() === "0") {
            alert('El total no puede estar en cero');
            return false;
        }

        let error = false;
        let arr = [];
        $("#presupuesto_tb tr").each(function (evt) {
            if (parseInt($(this).find("td:eq(6)").text()) === 0) {
                alert('Debes ingresar un costo para los productos');
                error = true;
            }
            let producto = $(this).find("td:eq(0)").text();
            let cantidad = $(this).find(".cantidad_nota").val();
            let costo = $(this).find("td:eq(5)").text();

            if($(this).find(".check-nota").prop("checked")){
                arr.push({
                    'id_ingrediente': producto,
                    'cantidad': cantidad,
                    'costo': costo
                });
            }
        });

        if (error)
            return false;
            
        // Enviar datos
        var parametros = {
            "act": "insert",
            "cod_compra": $("#codigo_pedido").val(),
            "total_nota": $("#total_general").text(),
            "fecha": $("#fecha").val(),
            "tipo": $("#tipo").val(),
            "hora": $("#hora").val(),
            "detalles": JSON.stringify(arr)
        };

        $.ajax({
            type: "GET",
            url: "modules/nota_credito_compra/proses.php",
            data: parametros,
            beforeSend: function () {
                // Mostrar mensaje de carga
            },
            success: function (datos) {
                location.href = datos; // Redirigir o mostrar el resultado
            }
        });
    }

    // Lógica para cargar los detalles del pedido
    function cargarDetallerPedido() {
        $.ajax({
            type: "GET",
            url: "modules/nota_credito_compra/ajaxDetalles.php",
            data: "id=" + $("#codigo_pedido").val(),
            beforeSend: function () {
                // Cargar detalles
            },
            success: function (datos) {
                $("#resultados").html(datos);
            }
        });
    }

    // Función para actualizar total y cantidades
    $(document).on("change", ".cantidad_nota", function () {
        if ($(this).closest("tr").find(".check-nota").prop("checked")) {
            let cantidad = parseInt($(this).val());
            if ($(this).val().trim().length === 0) {
                cantidad = 0;
            }

            let costo = parseInt($(this).closest("tr").find("td:eq(5)").text());
            let total = cantidad * costo;
            $(this).closest("tr").find("td:eq(6)").text(total);

            let total_general = 0;
            $("#presupuesto_tb tr").each(function () {
                if ($(this).find(".check-nota").prop("checked")) {
                    total_general += parseInt($(this).find("td:eq(6)").text());
                }
            });

            $("#total_general").text(total_general);
        }
    });

    // Calcular total cuando el checkbox cambia
    $(document).on("change", ".check-nota", function () {
        let total_general = 0;

        $("#presupuesto_tb tr").each(function () {
            if ($(this).find(".check-nota").prop("checked")) {
                let cantidad = parseInt($(this).find(".cantidad_nota").val()) || 0;
                let costo = parseInt($(this).find("td:eq(5)").text()) || 0;
                let total = cantidad * costo;
                $(this).find("td:eq(6)").text(total);
                total_general += total;
            }
        });

        $("#total_general").text(total_general);
    });
</script>
