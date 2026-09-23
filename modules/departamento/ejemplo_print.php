<?php

require_once '../../assets/plugins/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;

$html2pdf = new Html2Pdf();
$html2pdf->writeHTML('<h1>Hola mundo</h1>Con un archivo pdf con PDF2HTML');
$html2pdf->output('ejemplo.pdf');

?>