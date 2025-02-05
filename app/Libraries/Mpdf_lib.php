<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '../vendor/autoload.php';

class Mpdf_lib {
    public function __construct() {}

    public function generatePDF($html, $filename = 'documento.pdf', $outputMode = 'D') {
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($html);
        return $mpdf->Output($filename, $outputMode); 
    }
}
