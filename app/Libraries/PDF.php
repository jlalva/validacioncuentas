<?php

require_once APPPATH . 'Libraries/fpdf/fpdf.php';

class PDF extends FPDF
{
//Pie de página
    function Footer()
    {

        $this->SetY(-10);

        $this->SetFont('Arial','I',7);
        $this->Line(15, 288, 195,288);
        $this->Image("public/images/web.jpg", 15, 289, 8, 0);
        $this->Cell(55,10,web(),0,0,'C');
        $this->Image("public/images/telefono.png", 75, 288, 8, 0);
        $this->Cell(55,10,telefono(),0,0,'C');
        $this->Image("public/images/direccion.png", 130, 288, 7, 0);
        $this->Cell(75,10,direccion(),0,0,'R');
    }
}
