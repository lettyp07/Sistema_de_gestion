<?php
    // Establecer la zona horaria
    date_default_timezone_set('America/Asuncion'); 
    $fechaActual = date("Y-m-d");
?>
<?php if ($_GET['form'] == 'add') { ?>
    <section class="content-header">
        <h1>
            <i class="fa fa-edit icon-title">Agregar Etapas de Producción</i>
        </h1>
        <ol class="breadcrumb">
            <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
            <li><a href="?module=etapa_produccion"> Etapa de Producción</a></li>
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
                            // Generar código único para la nueva etapa de producción
                            $query_id = mysqli_query($mysqli, "SELECT MAX(id_etapa_produccion) as id FROM etapa_produccion")
                                or die('Error: ' . mysqli_error($mysqli));

                            $data_id = mysqli_fetch_assoc($query_id);
                            $codigo = $data_id ? $data_id['id'] + 1 : 1;
                            ?>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Código</label>
                                <div class="col-sm-1">
                                    <input type="text" class="form-control" name="codigo" id="id_etapa_pro" value="<?= htmlspecialchars($codigo) ?>" readonly>
                                </div>

                                <label class="col-sm-1 control-label">Fecha</label>
                                    <div class="col-sm-2">
                                        <input type="date" class="form-control date-picker" data-date-format="dd-mm-yyyy"
                                            name="fecha" id="fecha" min="<?php echo $fechaActual; ?>"
                                            value="<?php echo $fechaActual; ?>" disabled>
                                        <input type="text" name="fecha" hidden value="<?php echo $fechaActual; ?>">
                                    </div>

                            <label class="col-sm-1 control-label">Hora</label>
                                <div class="col-sm-2">
                                    <input type="time" class="form-control" disabled  data-date-format="h-m" name="hora"
                                        min="<?php echo date("H:i"); ?>" 
                                         value="<?php echo date("H:i"); ?>"  >
                                    <input type="hidden" id="hora" value="<?php echo date("H:i"); ?>">
                                         
                                </div>
                           
                            </div>

                            

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Orden Producción:</label>
                                <div class="col-sm-3">
                                    <select class="chosen-select form-control" name="codigo_orden" id="codigo_orden" 
                                            data-placeholder="Selecciona orden" onchange="cargarDetallerPedido();" required>
                                        <option value="0"></option>
                                        <?php
                                        $query_prov = mysqli_query($mysqli, "
                                            SELECT pc.id_orden_produccion, p.id_pedido_cliente, c.ci_ruc, c.cli_nombre
                                            FROM orden_produccion pc
                                            JOIN pedido_cliente p ON pc.id_pedido_cliente = p.id_pedido_cliente
                                            JOIN clientes c ON p.id_cliente = c.id_cliente
                                            where pc.estado = 'activo'
                                            ORDER BY pc.id_orden_produccion ASC
                                        ") or die('Error: ' . mysqli_error($mysqli));
                                        while ($data_prov = mysqli_fetch_assoc($query_prov)) {
                                            echo "<option value=\"{$data_prov['id_orden_produccion']}\">
                                            {$data_prov['id_orden_produccion']} | CLIENTE: {$data_prov['cli_nombre']} | RUC: {$data_prov['ci_ruc']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                              <div class="form-group">
                                <label class="col-sm-2 control-label">Etapa</label>
                                <div class="col-sm-4">
                                    <select class="chosen-select" name="etapa" id="etapa_lst"
                                        data-placeholder="Selecciona etapa" autocomplete="off" required>
                                        <option value=""></option>
                                        <?php
                                        $query_prov = mysqli_query($mysqli, "SELECT *
                                            FROM etapas
                                            ORDER BY id_etapa ASC") or die('Error' . mysqli_error($mysqli));
                                        while ($data_prov = mysqli_fetch_assoc($query_prov)) {
                                            echo "<option value=\"$data_prov[id_etapa]\">$data_prov[descrip]</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>     
                            <hr> 
                            <div id="resultados" class="col-md-9"></div>
                        </div>
                        <div class="box-footer">
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="button" class="btn btn-primary btn-submit" onclick="guardar();">
                                        Guardar
                                    </button>
                                    <a href="?module=etapa_produccion" class="btn btn-default btn-reset">Cancelar</a>
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
    var arr = []; // Definir la variable `arr` como un array vacío.

    function guardar() {
       
       var selectedIndex = $("#etapa_lst")[0].selectedIndex;
       var totalOptions = $('#etapa_lst')[0].options.length - 1;
       
       
       if(selectedIndex > 1){
           alert("No puedes pasar a la siguiente etapa sin terminar la anterior");
           return;
       }
        if($("#etapa_lst").val().length === 0){
            alert("Debes seleccionar una etapa");
            return;
        }
        //validacion de campos
        if($("#codigo_orden").val() === "0"){
            alert("Debes seleccionar un orden de produccion");
            return;
        }
        
        
        //validacion de horas
        let error = false;
        $("#etapa_tb tr").each(function(evt) {
            let hora_inicio = $(this).find("input:eq(0)").val();
            let hora_fin = $(this).find("input:eq(1)").val();
            if(hora_fin < hora_inicio){
                alert("Verifica hora inicio y fin del producto de codigo ("+$(this).find("td:eq(0)").text()+")");
                error =  true;
                return false;
            }
            
        });
        
        
        
        if(error){
            return;
        }
        let detalles = [];
        //creamos el detalle a enviar recorriendo la tabla
        $("#etapa_tb tr").each(function(evt) {
            detalles.push({
                'id_producto' : $(this).find("td:eq(0)").text(),
                'cantidad' : $(this).find("td:eq(4)").text(),
                'hora_ini' : $(this).find("input:eq(0)").val(),
                'hora_fin' : $(this).find("input:eq(1)").val(),
                'id_empleado' : $(this).find("select").val()
            });
    
        });
        console.log(detalles);
        
        const parametros = {
            act: "insert",
            id_etapa_produccion: $("#id_etapa_pro").val(),
            fecha: $("#fecha").val(),
            hora: $("#hora").val(),
            id_orden_produccion : $("#codigo_orden").val(),
            estado: "pendiente",  // Puedes cambiar esto dependiendo de la lógica que quieras
            id_etapa: $("#etapa_lst").val(), // Etapa seleccionada
            detalles : JSON.stringify(detalles)
        };
//
        console.log("Parámetros enviados:", parametros);
//
        $.ajax({
            type: "GET", // Usa POST para enviar datos sensibles.
            url: "modules/etapa_produccion/proses.php",
            data: parametros,
            beforeSend: function () {
                console.log("Guardando datos...");
            },
            success: function (response) {
                console.log("Respuesta del servidor:", response);
                let json_res =  JSON.parse(response);
                if (json_res.success) {
                    alert("Datos guardados correctamente.");
                    location.href = json_res.redirect_url; // Asegúrate de enviar esta URL desde el backend.
                } else {
                    alert("Error: " + json_res.message);
                }
            },
            error: function (error) {
                
                //console.log(error.responseText);
              
               // alert("Error al guardar los datos. "+e);
            }
        });
    }

    function cargarDetallerPedido() {
        const idOrden = $("#codigo_orden").val();
        if (!idOrden || idOrden === "0") {
            alert("Por favor, selecciona una orden válida.");
            return;
        }

        //console.log("Cargando detalles para la orden:", idOrden);
        ///DATOS DE TABLA
        $.ajax({
            type: "GET",
            url: "modules/etapa_produccion/ajaxDetalles.php",
            data: { id: idOrden },
            success: function (datos) {
                $("#resultados").html(datos);
                //console.log("Detalles cargados:", datos);
            },
            error: function () {
                alert("Error al cargar los detalles del pedido.");
            }
        });
        ///DATOS DE ETAPAS OPTION
        $.ajax({
            type: "GET",
            url: "modules/etapa_produccion/ajaxOption.php",
            data: { id: idOrden },
            success: function (datos) {
                $("#etapa_lst").html(datos);
                $("#etapa_lst").trigger("chosen:updated");
               
                
                console.log("Detalles cargados:", datos);
            },
            error: function () {
                alert("Error al cargar los detalles del pedido.");
            }
        });
    }
    
    
    


</script>
