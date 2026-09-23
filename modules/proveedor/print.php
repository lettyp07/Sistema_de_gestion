<?php
require_once '../../assets/plugins/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf; //Barras invertida alt + 92 \

ob_start();
include 'print_view.php';
$content = ob_get_clean();
$nombre = 'reporte_proveedor.pdf';

$html2pdf = new Html2Pdf ('P','A4','es');
$html2pdf->pdf->SetDisplayMode('fullpage');
$html2pdf->writeHTML($content);
$html2pdf->output($nombre);


?>