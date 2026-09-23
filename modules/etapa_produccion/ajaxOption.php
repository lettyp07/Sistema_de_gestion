<?php  
// Inicializar ID
$id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "";
// Verificar si el ID es válido
if ($id === 0) {
    $sql = mysqli_query($mysqli, "
    SELECT *
    FROM etapas
    ORDER BY id_etapa ASC  
    ") or die('<div class="alert alert-danger">Error en la consulta: ' . mysqli_error($mysqli) . '</div>');
    
}

// Incluir configuración de base de datos
require_once '../../config/database.php';

// Consulta para obtener los detalles de la orden de producción
$sql = mysqli_query($mysqli, "
    select 
    e.id_etapa ,
    e.descrip 
    from etapas e 
    where e.id_etapa not in (select ep.id_etapa from etapa_produccion ep where ep.estado = 'controlado' and ep.id_orden_produccion = $id )
        order by e.id_etapa ASC
    ") or die('<div class="alert alert-danger">Error en la consulta: ' . mysqli_error($mysqli) . '</div>');




// Verificar si hay resultados
if (mysqli_num_rows($sql) === 0) {
    echo "<option>No hay etapas por completar</option>";
    exit;
}

?>

        <?php
        
       
       echo '<option value=""></option>';
        while ($row = mysqli_fetch_assoc($sql)) {
            ?>
    <option value="<?= htmlspecialchars($row['id_etapa']) ?>"> <?= htmlspecialchars($row['descrip']) ?></option>
            
        <?php } ?>
    
