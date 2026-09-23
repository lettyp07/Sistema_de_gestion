<?php 
require_once '../../assets/plugins/vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;

ob_start();
require 'print_view.php';
$nombre_archivo = "factura_compra.pdf";
$html = ob_get_clean();

$html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8');
$html2pdf->writeHTML($html);
$html2pdf->Output($nombre_archivo);

?>