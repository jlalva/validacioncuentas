<?php

require_once APPPATH . 'Libraries/fpdf/fpdf.php';

class PDFS extends FPDF
{
    function Header()
    {
        $this->Image(base_url('public/images/FOTO_EMPRESA/').logo(), 10, 8, 30); // (ruta, x, y, tamaño)
        // Razón Social
        $this->SetFont('Arial', 'B', 10);
        $this->SetXY(80, 10);
        $this->Cell(110, 10, 'RAZON SOCIAL: '.razonsocial(), 0, 0, 'L');
        // Información del sistema
        $this->SetXY(210, 10);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(50, 5, 'SISTEMA: [Nombre del sistema]', 0, 2, 'L');
        $this->Cell(50, 5, 'FECHA/HORA: '.date('d-m-Y H:i'), 0, 2, 'L');
        //$this->Cell(50, 5, 'TIPO CUENTA: Estudiante/Docente o Administrativo', 0, 2, 'L');
        // Línea separadora
        $this->SetXY(50, 25);
        $this->Cell(220, 0, '', 'B', 1, 'C');
        $this->Ln(5);
    }
//Pie de página
    function Footer()
    {
        $this->SetY(-10);
        $this->SetFont('Arial','I',7);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . " de {nb}", 0, 0, 'C');
        /*$this->Image("public/images/web.jpg", 15, 289, 8, 0);
        $this->Cell(55,10,web(),0,0,'C');
        $this->Image("public/images/telefono.png", 75, 288, 8, 0);
        $this->Cell(55,10,telefono(),0,0,'C');
        $this->Image("public/images/direccion.png", 130, 288, 7, 0);
        $this->Cell(75,10,direccion(),0,0,'R');*/
    }
}
