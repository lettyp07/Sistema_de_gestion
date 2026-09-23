<?php if ($_GET['form'] == 'add') { ?>
    <section class="content-header">
        <h1>
            <i class="fa fa-edit icon-title">Agregar Orden de compra</i>
        </h1>
        <ol class="breadcrumb">
            <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
            <li><a href="?module=orden_compra"> Orden de compra</a></li>
            <li class="active">Agregar</li>
        </ol>
    </section>      

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form role="form" class="form-horizontal"  >
                        <div class="box-body">
                            <?php
                            //Método para generar código
                            $query_id = mysqli_query($mysqli, "SELECT MAX(id_orden) as id FROM orden_compra")
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
                                    <input type="text" class="form-control date-picker" data-date-format="dd-mm-yyyy" name="fecha" id="fecha" autocomplete="of" 
                                           value="<?php echo date("Y-m-d"); ?>" readonly>
                                </div>
                               
                                <label class="col-sm-1 control-label">Hora</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control date-picker" data-date-format="H-mm-ss" name="hora" id="hora" autocomplete="of" 
                                           value="<?php echo date("H:i:s"); ?>" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">PRESUPUESTO</label>
                                <div class="col-sm-3">
                                    <select class="chosen-select" name="codigo_presupuesto" data-placeholder="-- Seleccionar Presupuesto --"
                                            autocomplete="off" required id="codigo_pedido" onchange="cargarDetallerPedido(); return false;">
                                        <option value="0"></option>
                                        <?php
                                        $query_prov = mysqli_query($mysqli, "SELECT pr.id_presupuesto, p.razon_social, p.ruc
                                        FROM presupuesto pr
                                        JOIN proveedor  p
                                        ON p.cod_proveedor = pr.cod_proveedor
                                        WHERE pr.estado =  'ACTIVO'
                                       ORDER BY pr.id_presupuesto ASC") or die('Error' . mysqli_error($mysqli));
                                        while ($data_prov = mysqli_fetch_assoc($query_prov)) {
                                            echo "<option value=\"$data_prov[id_presupuesto]\">$data_prov[razon_social] | $data_prov[ruc] | NRO PRESUPUESTO ($data_prov[id_presupuesto])</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                
                                 <label class="col-sm-1 control-label">Comentario</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="comentario" name="comentario" autocomplete="off" 
                                    >
                                </div>
                            </div>
                            <hr>        
                            <div id="resultados" class="col-md-9"></div>
                            <div class="box-footer">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input class="btn btn-primary btn-submit" onclick="guardar(); return false;" name="Guardar" value="Guardar">
                                        <a href="?module=orden_compra" class="btn btn-default btn-reset">Cancelar</a>
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
                                                load(1);
                                            });

                                            function load(page) {
                                                var x = $("#x").val();
                                                var parametros = {"action": "ajax", "page": page, "x": x};
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
        if($("#codigo_pedido").val() === "0"){
             alert('Debes seleccionar un presupuesto');
            return false;
        }
        if ($("#total_general").text() === "0") {
            alert('El total no puede estar en cero');
            return false;
        }
        if ($("#comentario").val().trim().length === 0) {
            alert('Debes ingresar un comentario');
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
            "id_presupuesto": $("#codigo_pedido").val(),
            "total_presupuesto": $("#total_general").text(),
            "fecha": $("#fecha").val(),
           // "fecha_entrega": $("#fecha_entrega").val(),
            "comentario": $("#comentario").val(),
            "hora": $("#hora").val(),
            "detalles": JSON.stringify(arr)
        };
        console.log(parametros);
        $.ajax({
            type: "GET",
            url: "modules/orden_compra/proses.php",
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
        var parametros = {"id": id, "precio_compra_": precio_compra, "cantidad": cantidad};
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

    function cargarDetallerPedido() {
        console.log("cargar detalle");
        $.ajax({
            type: "GET",
            url: "modules/orden_compra/ajaxDetalles.php",
            data: "id=" + $("#codigo_pedido").val(),
            beforeSend: function (objeto) {

            },
            success: function (datos) {
                console.log(datos);
                $("#resultados").html(datos);
                let total_general = 0;
                $("#presupuesto_tb tr").each(function (evt) {
                    total_general += parseInt($(this).find("td:eq(6)").text());
                });

                $("#total_general").text(total_general);

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
        });

        $("#total_general").text(total_general);

    });

</script>



