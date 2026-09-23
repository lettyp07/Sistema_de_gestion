<?php
        // Establecer la zona horaria
        date_default_timezone_set('America/Asuncion'); 
        $fechaActual = date("Y-m-d");
?>
<?php if ($_GET['form'] == 'add') { ?>
    <section class="content-header">
        <h1>
            <i class="fa fa-edit icon-title">Agregar Compras</i>
        </h1>
        <ol class="breadcrumb">
            <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
            <li><a href="?module=compras"> Compras</a></li>
            <li class="active">Agregar</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form role="form" class="form-horizontal" action="modules/compras/proses.php?act=insert" method="POST">
                        <div class="box-body">
                            <?php
                            //Método para generar código
                            $query_id = mysqli_query($mysqli, "SELECT MAX(cod_compra) as id FROM compra")
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
                                    <input type="text" class="form-control date-picker" data-date-format="H-mm-ss"
                                        name="hora" autocomplete="off" value="<?php echo date("H:i:s"); ?>" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">N° de Factura</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="nro_factura" id="nro_factura"
                                    autocomplete="off" required placeholder="Ingrese el N° de Factura"
                                    pattern="\d{3}-\d{3}-\d{7}" title="Formato: 001-002-0001234, solo números permitidos"
                                    oninput="validateFactura()">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">N° de Timbrado</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="timbrado" id="nro_timbrado"
                                    autocomplete="off" required placeholder="Ingrese el N° de Timbrado"
                                    pattern="\d{8}" title="El número de timbrado debe contener 8 dígitos, solo números permitidos" oninput="validateTimbrado()">
                                </div> 
                                <label class="col-sm-1 control-label">Fecha vto</label>
                                <div class="col-sm-2">
                                    <input type="date" class="form-control" name="fecha_vto" id="fecha_venc"
                                        autocomplete="off"
                                        value="<?php echo date("Y-m-d"); ?>" min="<?php echo date("Y-m-d"); ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                            <label class="col-sm-2 control-label">Condición</label>
                                <div class="col-sm-2">
                                    <select type="text" class="form-control" name="condicion_pago"
                                        autocomplete="off" required id="condicion">
                                        <option value="CONTADO">CONTADO</option>
                                        <option value="CREDITO">CREDITO</option>
                                    </select>
                                </div>
                            <div class="form-group">
                                <label class="col-sm-1 control-label">Intervalo</label>
                                <div class="col-sm-1">
                                    <input type="number" min="1" value="1" class="form-control" value="0" name="intervalo"
                                        id="intervalo" readonly autocomplete="off" required>
                                </div>
                                <label class="col-sm-1 control-label">Cuotas</label>
                                <div class="col-sm-1">
                                    <input type="text" class="form-control" value="0" name="cantidad_cuotas" id="cuotas"
                                        readonly autocomplete="off" required>
                                </div>
                            </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Orden de compra</label>
                                <div class="col-sm-5">
                                    <select class="form-control" name="codigo_orden"
                                        data-placeholder="-- Seleccionar Orden --" autocomplete="off" required
                                        id="codigo_orden" onchange="cargarDetalleOrden(); return false;">
                                        <option value=""></option>
                                        <?php
                                        $query_or = mysqli_query($mysqli, "SELECT
                                                oc.id_orden,
                                                p.razon_social,
                                                oc.fecha,
                                                oc.total_orden
                                                FROM orden_compra oc 
                                                JOIN proveedor p 
                                                ON p.cod_proveedor =  oc.cod_proveedor
                                                WHERE oc.estado = 'activo'") or die('Error' . mysqli_error($mysqli));
                                        while ($data_or = mysqli_fetch_assoc($query_or)) {
                                            echo "<option value=\"$data_or[id_orden]\">NRO DE ORDEN | $data_or[id_orden] | $data_or[razon_social] | Total $data_or[total_orden]</option>";
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
                                    <input class="btn btn-primary btn-submit" type="submit" name="Guardar" value="Guardar">
                                    <a href="?module=compras" class="btn btn-default btn-reset">Cancelar</a>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
function validateFactura() {
    var input = document.getElementById('nro_factura');
    var value = input.value;
    
    // Regular expression to match the format 000-000-0000000
    var regex = /^\d{3}-\d{3}-\d{7}$/;

    // Check if the value matches the regex pattern
    if (regex.test(value)) {
        input.setCustomValidity('');
    } else {
        input.setCustomValidity('Formato inválido');
    }
}
</script>
<script>
function validateTimbrado() {
    var input = document.getElementById('nro_timbrado');
    var value = input.value;
    
    // Regular expression to match exactly 8 digits
    var regex = /^\d{8}$/;

    // Check if the value matches the regex pattern
    if (regex.test(value)) {
        input.setCustomValidity('');
    } else {
        input.setCustomValidity('Formato inválido');
    }
}
</script>
<script>
    function load(page) {
        var x = $("#x").val();
        var parametros = { "action": "ajax", "page": page, "x": x };
        $("#loader").fadeIn('slow');
        $.ajax({
            url: './ajax/productos_pedido.php',
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
    function guardar() {
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

            arr.push({
                'id_ingrediente': $(this).find("td:eq(0)").text(),
                'cantidad': $(this).find("td:eq(4)").text(),
                'costo': $(this).find("td:eq(5)").text()
            });
        });

        if (error)
            return false;
        //fin de la validación
        var parametros = {
            "act": "insert",
            "id_orden": $("#codigo_orden").val(),
            "timbrado": $("#nro_timbrado").val(),
            "nro_factura": $("#nro_factura").val(),
            "total_compra": $("#total_general").text(),
            "cantidad_cuotas": $("#cuotas").val(),            
           "condicion": $("#condicion_pago").val(),
            "fecha_vto": $("#fecha_venc").val(),
            "intervalo": $("#in").text(),
            "cod_proveedor": $("#cod").val(),
            "pago": $("#pago").val(),
            "fecha": $("#fecha").val(),
            "hora": $("#hora").val(),

            "detalles": JSON.stringify(arr)
        };
        console.log(parametros);
        $.ajax({
            type: "GET",
            url: "modules/compras/proses.php",
            data: parametros,
            beforeSend: function (objeto) {
                //$("#resultados").html("Mensaje: Cargando...");
            },
            success: function (datos) {
                console.log(datos);
                location.href = datos;
            }
        });
    }

    //----------------------------------------------------------------------------
    //----------------------------------------------------------------------------
    //----------------------------------------------------------------------------
    $(document).on("change", "#condicion", function (evt) {
        if ($("#condicion").val() === "CONTADO") {
            $("#intervalo").attr("readonly", false);
            $("#cuotas").attr("readonly", false);
            $("#intervalo").val("0");
            $("#cuotas").val("0");


        } else {
            $("#intervalo").removeAttr("readonly");
            $("#cuotas").removeAttr("readonly");
            let total = parseInt($("#total_compra").val());
            let intervalos = parseInt($("#intervalo").val());
            $("#cuotas").val(Math.round((total / cantidad_cuotas)  +  ((total / cantidad_cuotas))));
        }
    });

    //----------------------------------------------------------------------------
    //----------------------------------------------------------------------------
    //----------------------------------------------------------------------------
    //----------------------------------------------------------------------------
    $(document).on("change", "#intervalo", function () {
        let total = parseInt($("#total_compra").val());
        let intervalos = parseInt($("#intervalo").val());
        $("#cuotas").val(Math.round((total / intervalos)  +  ((total / cantidad_cuotas))));
    });

    //----------------------------------------------------------------------------
    //----------------------------------------------------------------------------
    //----------------------------------------------------------------------------
    function agregar(id) {
        var precio_compra = $('#precio_compra_' + id).val();
        var cantidad = $('#cantidad_' + id).val();
        if (isNaN(cantidad)) {
            alert('Esto no es un nro');
            document.getElementById('cantidad_' + id).focus();
            return false;
        }
        if (isNaN(precio_compra)) {
            alert('Esto no es un nro');
            document.getElementById('precio_compra_' + id).focus();
            return false;
        }
        //fin de la validación
        var parametros = { "id": id, "precio_compra_": precio_compra, "cantidad": cantidad };
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
    function cargarDetalleOrden() {
        console.log("cargar detalle");
        $.ajax({
            type: "GET",
            url: "modules/compras/ajaxDetalles.php",
            data: "id=" + $("#codigo_orden").val(),
            beforeSend: function (objeto) {

            },
            success: function (datos) {
                //console.log(datos);
                $("#resultados").html(datos);
                let total_general = 0;
                $("#presupuesto_tb tr").each(function (evt) {
                    total_general += parseInt($(this).find("td:eq(6)").text());
                    total_general += parseInt($(this).find("td:eq(7)").text());
                    total_general += parseInt($(this).find("td:eq(8)").text())
                });

                $("#total_general").text(total_general);
                $("#total_compra").val(total_general);

            }
        });
    }

    $(document).on("keyup", ".costo_presupuesto", function (evt) {
        let costo = parseInt($(this).closest("tr").find("input").val());
        if ($(this).val().trim().length === 0) {
            costo = 0;
        }

        let cantidad = parseInt($(this).closest("tr").find("td:eq(4)").text());


        let total = cantidad * costo;
        $(this).closest("tr").find("td:eq(6)").text(total);


        let total_general = 0;
        $("#presupuesto_tb tr").each(function (evt) {
            total_general += parseInt($(this).find("td:eq(6)").text());
            total_general += parseInt($(this).find("td:eq(7)").text());
            total_general += parseInt($(this).find("td:eq(8)").text());
        });

        $("#total_general").text(total_general);

    });



</script>