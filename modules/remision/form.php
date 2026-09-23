<?php if ($_GET['form'] == 'add') { ?>
    <script>
        window.onload = function (evt) {
            limpiar();
        };

    </script>
    <section class="content-header">
        <h1>
            <i class="fa fa-edit icon-title">Agregar Nota Remision Compras</i>
        </h1>
        <ol class="breadcrumb">
            <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
            <li><a href="?module=remision">Nota Remision Compras</a></li>
            <li class="active">Agregar</li>
        </ol>
    </section>      

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form role="form" class="form-horizontal" action="modules/remision/proses.php?act=insert" method="POST">
                        <div class="box-body">
                            <?php
                            //Método para generar código
                            $query_id = mysqli_query($mysqli, "SELECT MAX(cod_remision_compra) as id FROM remision_compras")
                                    or die('Error' . mysqli_error($mysqli));
                            $codigo = 1;
                            $count = mysqli_num_rows($query_id);
                            if ($count <> 0) {
                                $data_id = mysqli_fetch_assoc($query_id);
                                $codigo = $data_id['id'] + 1;
                            } else {
                                $codigo = 1;
                            }
                            ?>

                            <div class="form-group">

                                <label class="col-sm-2 control-label">Compra</label>
                                <div class="col-md-10">
                                    <select  required class="form-control" id="pedidos_lst" name="pedidos_lst" onchange="cargarDetallerPedido();">
                                        <option value="0">Selecciona una Compra</option>
                                        <?php
                                        $query_prov = mysqli_query($mysqli, "SELECT
                                        pc.nro_factura ,
                                        pc.cod_compra as cod,
                                        pc.fecha,
                                        p.razon_social,
                                        pc.total_compra
                                        FROM compra pc 
                                        JOIN proveedor p 
                                        ON p.cod_proveedor =  pc.cod_proveedor
                                        WHERE pc.estado = 'activo'") or die('Error' . mysqli_error($mysqli));
                                        while ($data_prov = mysqli_fetch_assoc($query_prov)) {
                                            echo "<option value=\"$data_prov[cod]\">$data_prov[nro_factura] | $data_prov[fecha] | Proveedor $data_prov[razon_social] | Total $data_prov[total_compra]</option>";
                                        }
                                        ?>
                                    </select>

                                </div>
                                <div class="col-md-12">
                                    <hr> 
                                </div>


                                <label class="col-sm-2 control-label">Código</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" name="codigo" value="<?php echo $codigo; ?>" readonly>
                                </div>

                                <label class="col-sm-1 control-label">Fecha</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control date-picker" data-date-format="dd-mm-yyyy" name="fecha" autocomplete="of" 
                                           value="<?php echo date("Y-m-d"); ?>" readonly>
                                </div>
                                

                                <label class="col-sm-1 control-label">Hora</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control date-picker" data-date-format="H-mm-ss" name="hora" autocomplete="of" 
                                           value="<?php echo date("H:i:s"); ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Punto Salida</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" required  name="salida" autocomplete="of" 
                                           >
                                </div>
                                <label class="col-sm-1 control-label">Punto Llegada</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" required  name="llegada" autocomplete="of" 
                                           >
                                </div>
                                <label class="col-sm-1 control-label">Chofer</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" required  name="chofer" autocomplete="of" 
                                           >
                                </div>
                            </div>
                            




                            <hr>               
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label class="col-sm-2 control-label">Productos</label>
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">
                                        <span class="glyphicon glyphicon-plus">Agregar Productos</span>
                                    </button>
                                </div>
                            </div>
                            <div id="resultados" class="col-md-9"></div>



                            <div class="box-footer">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="submit" class="btn btn-primary btn-submit" name="Guardar" value="Guardar">
                                        <a href="?module=remision" class="btn btn-default btn-reset">Cancelar</a>
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
    function limpiar() {
        $.ajax({
            type: "GET",
            url: "./ajax/agregar_pedido.php",
            data: "limpiar=1",
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
            url: "modules/remision/ajaxDetalles.php",
            data: "id=" + $("#pedidos_lst").val(),
            beforeSend: function (objeto) {

            },
            success: function (datos) {
                console.log(datos);
                $("#resultados").html(datos);
               
            }
        });
    }
    $(document).on("keyup", ".costo_presupuesto", function (evt) {
        let costo = parseInt($(this).closest("tr").find("input").val());
        if($(this).val().trim().length === 0){
            costo =  0;
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


<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModallabel">Buscar Productos</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="x" placeholder="Buscar productos" onkeyup="load(1)">
                        </div>
                        <button type="button" class="btn btn-default" onclick="load(1)"><span class="glyphicon glyphicon-search"></span>Buscar</button>
                    </div>                            
                </form>
                <div id="loader" style="position: absolute; text-align: center; top: 55px; width:100%; display:none;"></div>
                <div class="outer_div"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>                                
        </div>
    </div>                                      
</div>



