<?php if ($_GET['form'] == 'add') { ?>
    <section class="content-header">
        <h1>
            <i class="fa fa-edit icon-title">Agregar Presupuesto</i>
        </h1>
        <ol class="breadcrumb">
            <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
            <li><a href="?module=presupuesto"> Presupuesto</a></li>
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
                            $query_id = mysqli_query($mysqli, "SELECT MAX(id_presupuesto) as id FROM presupuesto")
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
                                <label class="col-sm-2 control-label">Proveedor</label>
                                <div class="col-sm-4">
                                    <select class="chosen-select" name="codigo_proveedor" data-placeholder="-- Seleccionar proveedor --"
                                            autocomplete="off" required id="codigo_proveedor">
                                        <option value="0"></option>
                                        <?php
                                        $query_prov = mysqli_query($mysqli, "SELECT cod_proveedor, razon_social, ruc
                                        FROM proveedor
                                        ORDER BY cod_proveedor ASC") or die('Error' . mysqli_error($mysqli));
                                        while ($data_prov = mysqli_fetch_assoc($query_prov)) {
                                            echo "<option value=\"$data_prov[cod_proveedor]\"> $data_prov[razon_social] | $data_prov[ruc]</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">PEDIDO</label>
                                <div class="col-sm-4">
                                    <select class="chosen-select" name="codigo_pedido" data-placeholder="-- Seleccionar Pedido --"
                                            autocomplete="off" required id="codigo_pedido" onchange="cargarDetallerPedido(); return false;">
                                        <option value="0"></option>
                                        <?php
                                        $query_prov = mysqli_query($mysqli, "SELECT pc.*, s.sucursal, u.username
                                        FROM pedido_compra pc
                                        JOIN usuarios u
                                        ON pc.id_user = u.id_user
                                        JOIN sucursal s
                                        ON s.id_sucursal = u.id_sucursal
                                        WHERE pc.estado =  'activo'
                                       ORDER BY pc.id_pedido ASC") or die('Error' . mysqli_error($mysqli));
                                        while ($data_prov = mysqli_fetch_assoc($query_prov)) {
                                            echo "<option value=\"$data_prov[id_pedido]\"> NRO PEDIDO ($data_prov[id_pedido]) | Sucursal ($data_prov[sucursal])</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                         
                                <label class="col-sm-1 control-label">Fecha Vencimiento</label>
                                <div class="col-sm-2">
                                    <input type="date" class="form-control" min="<?php echo date("Y-m-d"); ?>"  name="fecha_vencimiento" id="fecha_vencimiento" autocomplete="off" 
                                           value="<?php echo date("Y-m-d"); ?>"  >
                                </div>

                            </div>
                            <hr>               
                            <div class="form-group">
                                <div class="col-sm-12">                   
                                </div>
                            </div>
                            <div id="resultados" class="col-md-9"></div>
                            <div class="box-footer">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input class="btn btn-primary btn-submit" onclick="guardar();" name="Guardar" value="Guardar">
                                        <a href="?module=presupuesto" class="btn btn-default btn-reset">Cancelar</a>
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
    function guardar(){
        let fecha_vencimiento = $("#fecha_vencimiento").val();
        var fechaActual = new Date();
            
            // Obtener los componentes de la fecha
            
            var dia = String(fechaActual.getDate()).padStart(2, '0');
            var mes = String(fechaActual.getMonth() + 1).padStart(2, '0'); // Los meses comienzan en 0
            var anio = fechaActual.getFullYear();
            
            // Formatear la fecha como un string
            var fechaString = anio + '-' + mes + '-' + dia;

            if(fechaString > fecha_vencimiento){
                alert('La fecha de vencimiento no puede ser menor a la actual');
                return false;
            }

        if($("#codigo_pedido").val() === "0"){
            alert('Debes seleccionar un pedido');
            return false;
        }
        
        if($("#total_general").text() === "0"){
            alert('El total no puede estar en cero');
            return false;
        }
        
        let error =  false;
        let arr = [];
        $("#presupuesto_tb tr").each(function (evt) {
           if(parseInt($(this).find("td:eq(6)").text()) === 0){
               alert('Debes ingresar un costo para los productos');
               error =  true;
              
           }
           
           arr.push({
               'id_ingrediente' : $(this).find("td:eq(0)").text(),
               'cantidad' : $(this).find("td:eq(4)").text(),
               'costo' : $(this).find("input").val()
           });
        });
        
        if(error) return false;
        

        //fin de la validación
        var parametros = {
            "act": "insert", 
            "cod_proveedor": $("#codigo_proveedor").val(), 
            "id_pedido": $("#codigo_pedido").val(), 
            "total_presupuesto": $("#total_general").text(), 
            "fecha": $("#fecha").val(), 
            "fecha_vencimiento": $("#fecha_vencimiento").val(), 
            "hora": $("#hora").val(),
            "detalles" : JSON.stringify(arr)
        };
        console.log(parametros);
        $.ajax({
            type: "GET",
            url: "modules/presupuesto/proses.php",
            data: parametros,
            beforeSend: function (objeto) {
                //$("#resultados").html("Mensaje: Cargando...");
            },
            success: function (datos) {
                console.log(datos);
                
                location.href =  datos;
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
            url: "modules/presupuesto/ajaxDetalles.php",
            data: "id=" + $("#codigo_pedido").val(),
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
