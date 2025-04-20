<?php

require_once APPPATH . 'Libraries/fpdf/fpdf.php';

class PDFSUBIR extends FPDF
{
    function Header()
    {
        $logo = FCPATH . 'public/images/FOTO_EMPRESA/' . logo();
        if (file_exists($logo)) {
            $this->Image($logo, 10, 8, 25);
        }
        // Razón Social
        $this->SetFont('Arial', 'B', 10);
        $this->SetXY(80, 10);
        $this->Cell(110, 10, 'RAZON SOCIAL: '.razonsocial(), 0, 0, 'L');
        // Información del sistema
        $this->SetXY(210, 10);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(50, 5, 'SISTEMA: GENERADOR DE CUENTAS', 0, 2, 'L');
        $this->Cell(50, 5, 'FECHA/HORA: '.date('d-m-Y H:i'), 0, 2, 'L');
        // Línea separadora
        $this->SetXY(35, 25);
        $this->Cell(250, 0, '', 'B', 1, 'C');
        $this->SetFont('Arial','B',12);

        $x = [0=>11,1=>60,2=>60,3=>60,4=>20,5=>35,6=>30];
        $y = 5;
        $this->SetXY(50, 30);
        $this->Cell(220, $y, 'CUENTAS INSTITUCIONALES OFICIALES DE USUARIOS', 'B', 0, 'C');
        $this->SetFont('Arial','B',8);
        $this->Ln(5);
        $this->SetFillColor(255, 255, 0);
        $this->Cell($x[0],$y, utf8_encode('ITEM'),1,0,'C',true);
        $this->Cell($x[1],$y, utf8_decode('NOMBRES'),1,0,'C',true);
        $this->Cell($x[2],$y, utf8_decode('APELLIDOS'),1,0,'C',true);
        $this->Cell($x[3],$y, utf8_decode('EMAIL'),1,0,'C',true);
        $this->Cell($x[4],$y, utf8_decode('ESTADO'),1,0,'C',true);
        $this->Cell($x[5],$y, utf8_decode('ULTIMO ACCESO'),1,0,'C',true);
        $this->Cell($x[6],$y, utf8_decode('ESPACIO USO'),1,1,'C',true);
    }
//Pie de página
    function Footer()
    {
        $this->SetY(-10);
        $this->SetFont('Arial','I',7);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . " de {nb}", 0, 0, 'C');
    }
}
