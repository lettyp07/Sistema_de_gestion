<?php 
require "config/database.php";

if (empty($_SESSION['username']) && empty ($_SESSION['password'])) {
    echo "<meta http-equiv='refresh' content='0; url=index.php?alert=3'>";
}else{
    if ($_GET['module']=='start') {
      include 'modules/start/view.php';
    
    }elseif ($_GET['module']== 'password'){
      include 'modules/password/view.php';

    }elseif ($_GET['module']== 'user'){
      include 'modules/user/view.php';
      
    }elseif ($_GET['module']== 'form_user'){
      include 'modules/user/form.php';

    }elseif ($_GET['module']== 'perfil'){
      include 'modules/perfil/view.php';
  
    }elseif ($_GET['module']== 'form_perfil'){
      include 'modules/perfil/form.php';

    }elseif ($_GET['module']== 'departamento'){
      include 'modules/departamento/view.php';
  
    }elseif ($_GET['module']== 'form_departamento'){
      include 'modules/departamento/form.php';

    }elseif ($_GET['module']== 'presupuesto'){
      include 'modules/presupuesto/view.php';
  
    }elseif ($_GET['module']== 'form_presupuesto'){
      include 'modules/presupuesto/form.php';

    }elseif ($_GET['module']== 'ciudad'){
      include 'modules/ciudad/view.php';
  
    }elseif ($_GET['module']== 'form_ciudad'){
      include 'modules/ciudad/form.php';
  
    }elseif ($_GET['module']== 'proveedor'){
      include 'modules/proveedor/view.php';
  
    }elseif ($_GET['module']== 'form_proveedor'){
      include 'modules/proveedor/form.php';
  
    }elseif ($_GET['module']== 'clientes'){
      include 'modules/clientes/view.php';

    }elseif ($_GET['module']== 'form_clientes'){
      include 'modules/clientes/form.php';
  
    }elseif ($_GET['module']== 'compras'){
      include 'modules/compras/view.php';
  
    }elseif ($_GET['module']== 'form_compras'){
      include 'modules/compras/form.php';

    }elseif ($_GET['module']== 'stock'){
      include 'modules/stock/view.php';
  
    }elseif ($_GET['module']== 'ingrediente'){
      include 'modules/ingrediente/view.php';
    
    }elseif ($_GET['module']== 'form_ingrediente'){
      include 'modules/ingrediente/form.php';
  
    }elseif ($_GET['module']== 'u_medida'){
      include 'modules/u_medida/view.php';
    
    }elseif ($_GET['module']== 'form_u_medida'){
      include 'modules/u_medida/form.php';
  
    }elseif ($_GET['module']== 'tipo_ingrediente'){
      include 'modules/tipo_ingrediente/view.php';
    
    }elseif ($_GET['module']== 'form_tipo_ingrediente'){
      include 'modules/tipo_ingrediente/form.php';
    
    }elseif ($_GET['module']== 'ingrediente'){
      include 'modules/ingrediente/view.php';
    
    }elseif ($_GET['module']== 'form_ingrediente'){
      include 'modules/ingrediente/form.php';
    
    }elseif ($_GET['module']== 'pedido'){
      include 'modules/pedido/view.php';
    
    }elseif ($_GET['module']== 'form_pedido'){
      include 'modules/pedido/form.php';
    
    }elseif ($_GET['module']== 'orden_compra'){
      include 'modules/orden_compra/view.php';
    
    }elseif ($_GET['module']== 'form_orden_compra'){
      include 'modules/orden_compra/form.php';
  
    }elseif ($_GET['module']== 'remision'){
    include 'modules/remision/view.php';
    
    }elseif ($_GET['module']== 'form_remision'){
      include 'modules/remision/form.php';
  
    }elseif ($_GET['module']== 'notas'){
      include 'modules/nota_credito_compra/view.php';
    
    }elseif ($_GET['module']== 'form_notas'){
      include 'modules/nota_credito_compra/form.php';

    }elseif ($_GET['module']== 'ajuste_stock'){
      include 'modules/ajuste_stock/view.php';
    
    }elseif ($_GET['module']== 'form_ajuste_stock'){
      include 'modules/ajuste_stock/form.php';

    }elseif ($_GET['module']== 'producto'){
      include 'modules/producto/view.php';
  
    }elseif ($_GET['module']== 'form_producto'){
      include 'modules/producto/form.php';

    }elseif ($_GET['module']== 'tipo_producto'){
      include 'modules/tipo_producto/view.php';
  
    }elseif ($_GET['module']== 'form_tipo_producto'){
      include 'modules/tipo_producto/form.php';

    }elseif ($_GET['module']== 'receta'){
      include 'modules/receta/view.php';
  
    }elseif ($_GET['module']== 'form_receta'){
      include 'modules/receta/form.php';
    }
    elseif ($_GET['module']== 'pedido_ingrediente'){
      include 'modules/pedido_ingrediente/view.php';
  
    }elseif ($_GET['module']== 'form_pedido_ingrediente'){
      include 'modules/pedido_ingrediente/form.php';
    }
    elseif ($_GET['module']== 'orden_produccion'){
      include 'modules/orden_produccion/view.php';
  
    }elseif ($_GET['module']== 'form_orden_produccion'){
      include 'modules/orden_produccion/form.php';
    }
    elseif ($_GET['module']== 'etapa_produccion'){
      include 'modules/etapa_produccion/view.php';
  
    }elseif ($_GET['module']== 'form_etapa_produccion'){
      include 'modules/etapa_produccion/form.php';
    }
    elseif ($_GET['module']== 'informe_compra'){
      include 'modules/informe_compra/view.php';
      
    }elseif ($_GET['module']== 'informe_compra'){
      include 'modules/informe_compra/form.php';
    }
    elseif ($_GET['module']== 'pedido_cliente'){
      include 'modules/pedido_cliente/view.php';
      
    }elseif ($_GET['module']== 'form_pedido_cliente'){
      include 'modules/pedido_cliente/form.php';

    }elseif ($_GET['module']== 'cliente'){
      include 'modules/cliente/view.php';
      
    }elseif ($_GET['module']== 'form_cliente'){
      include 'modules/cliente/form.php';
    }
    elseif ($_GET['module']== 'control_produccion'){
      include 'modules/control_produccion/view.php';
      
    }elseif ($_GET['module']== 'form_control_produccion'){
      include 'modules/control_produccion/form.php';
    }
    elseif ($_GET['module']== 'control_calidad'){
      include 'modules/control_calidad/view.php';
      
    }elseif ($_GET['module']== 'form_control_calidad'){
      include 'modules/control_calidad/form.php';
    }
    elseif ($_GET['module']== 'costo_produccion'){
      include 'modules/costo_produccion/view.php';
      
    }elseif ($_GET['module']== 'form_costo_produccion'){
      include 'modules/costo_produccion/form.php';
    }
    elseif ($_GET['module']== 'mermas'){
      include 'modules/mermas/view.php';
      
    }elseif ($_GET['module']== 'form_mermas'){
      include 'modules/mermas/form.php';
    }
  }
?>

