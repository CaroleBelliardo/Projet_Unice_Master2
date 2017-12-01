<?php 
require __DIR__.'/vendor/autoload.php';
//require html2pdf/src/Html2Pdf.php;

use Spipu\Html2Pdf\Html2Pdf;
require __DIR__.'/usr/bin/composer';
$html2pdf = new  HTML2PDF();
$html2pdf->writeHTML('<h1>HelloWorld</h1>This is my first test');
$html2pdf->output();
?>
