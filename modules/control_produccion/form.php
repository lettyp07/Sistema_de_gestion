<?php
// Establecer la zona horaria
date_default_timezone_set('America/Asuncion'); 
$fechaActual = date("Y-m-d");
?>
<?php
if ($_GET['form'] == 'add') { ?>
    <section class="content-header">
        <h1>
            <i class="fa fa-edit icon-title">Agregar Control Producción</i>
        </h1>
        <ol class="breadcrumb">
            <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
            <li><a href="?module=control_produccion">Control Producción</a></li>
            <li class="active">Agregar</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form role="form" class="form-horizontal" action="modules/control_produccion/proses.php?act=insert" method="POST">
                        <div class="box-body">
                            <?php
                            //Método para generar código
                            $query_id = mysqli_query($mysqli, "SELECT MAX(id_control_produccion) as id FROM control_produccion")
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
                                    <input type="text" class="form-control" id="id_control_produccion" name="codigo" value="<?php echo $codigo; ?>" readonly>
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
                                <label class="col-sm-2 control-label">Etapa de Produccion:</label>
                                <div class="col-sm-5">
                                    <select class="chosen-select form-control" name="codigo_etapa" id="codigo_etapa" 
                                            data-placeholder="Selecciona etapa" onchange="cargarDetallerPedido();" required>
                                        <option value="0"></option>
                                        <?php
                                        $query_prov = mysqli_query($mysqli, "
                                            select 
                                            ep.id_etapa_produccion ,
                                            ep.id_orden_produccion ,
                                            ep.fecha ,
                                            ep.hora ,
                                            ep.id_etapa ,
                                            e.descrip 
                                            from etapa_produccion ep
                                            join etapas e on e.id_etapa = ep.id_etapa 
                                            where ep.estado = 'pendiente'
                                            order by ep.id_etapa_produccion  desc
                                        ") or die('Error: ' . mysqli_error($mysqli));
                                        while ($data_prov = mysqli_fetch_assoc($query_prov)) {
                                            echo "<option value=\"{$data_prov['id_etapa_produccion']}-{$data_prov['id_orden_produccion']}\">
                                                    FECHA: {$data_prov['fecha']} | HORA: {$data_prov['hora']} | ETAPA: {$data_prov['descrip']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <label class="col-sm-1 control-label">Estado</label>
                                <div class="col-sm-3">
                                    <select id="estado_control" class="form-control">
                                        <option value="controlado">controlado</option>
                                        <option value="anulado">anular</option>
                                    </select>
                                </div>
                            </div>

                            <hr>
                            <div id="resultados" class="col-md-9"></div>

                            <div class="box-footer">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="button" class="btn btn-primary btn-submit" onclick="guardar();">
                                        Guardar
                                    </button>
                                        <a href="?module=control_produccion" class="btn btn-default btn-reset">Cancelar</a>
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

// Función para cargar detalles del pedido
function cargarDetallerPedido() {
    const pedidoId = $("#codigo_etapa").val().split("-")[0]; // Obtener el ID del pedido seleccionado

    if (!pedidoId || pedidoId === "0") {
        alert("Por favor selecciona un pedido válido.");
        return;
    }

    $("#resultados").html("<p>Cargando detalles...</p>"); // Mensaje mientras se carga

    $.ajax({
        type: "GET",
        url: "modules/control_produccion/ajaxDetalles.php", // Ruta para obtener detalles
        data: { id: pedidoId }, // Pasar ID del pedido
        success: function (datos) {
            $("#resultados").html(datos); // Mostrar datos en el contenedor
        },
        error: function () {
            $("#resultados").html("<p style='color:red;'>Error al cargar los detalles de la orden produccion.</p>");
        }
    });
}
//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------
function guardar(){
        
      if($("#codigo_etapa").val() === "0"){
          alert("Debes seleccionar una etapa de prodccion");
          return;
      }
      
      
      //creamos el detalle a guardar
      let detalles = [];
      
      $("#control_tb tr").each(function(evt) {
          detalles.push({
              'id_etapa_produccion' : $("#codigo_etapa").val().split("-")[0],
              'id_producto' : $(this).find("td:eq(0)").text(),
              'ajuste' : $(this).find("input").val(),
          });
       });
          
     const parametros = {
            act: "insert",
            id_control_produccion: $("#id_control_produccion").val(),
            fecha: $("#fecha").val(),
            hora: $("#hora").val(),
            id_orden_produccion : $("#codigo_etapa").val().split("-")[1],
            id_etapa_produccion_cabecera : $("#codigo_etapa").val().split("-")[0],
            estado: $("#estado_control").val(),  
            detalles : JSON.stringify(detalles)
        };
//
        console.log("Parámetros enviados:", parametros);
//
        $.ajax({
            type: "GET", // Usa POST para enviar datos sensibles.
            url: "modules/control_produccion/proses.php",
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

</script>
