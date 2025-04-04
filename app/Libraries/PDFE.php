<?php

require_once APPPATH . 'Libraries/fpdf/fpdf.php';

class PDFE extends FPDF
{
    function Header()
    {
        $this->Image(base_url('public/images/FOTO_EMPRESA/').logo(), 10, 8, 25); // (ruta, x, y, tamaño)
        // Razón Social
        $this->SetFont('Arial', 'B', 10);
        $this->SetXY(80, 10);
        $this->Cell(110, 10, 'RAZON SOCIAL: '.razonsocial(), 0, 0, 'L');
        // Información del sistema
        $this->SetXY(225, 10);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(50, 5, 'SISTEMA: GENERADOR DE CUENTAS', 0, 2, 'L');
        $this->Cell(50, 5, 'FECHA/HORA: '.date('d-m-Y H:i'), 0, 2, 'L');
        // Línea separadora
        $this->SetXY(35, 25);
        $this->Cell(250, 0, '', 'B', 1, 'C');
        $this->Ln(5);
        $this->SetXY(50, 30);
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(0, 51, 102); // Azul oscuro
        $this->SetTextColor(255, 255, 255); // Texto blanco
        $this->Cell(190, 10, 'LISTA DE CORREOS INSTITUCIONALES DE NUEVOS ESTUDIANTES', 1, 1, 'C', true);
        $this->Ln();
        $x = [0=>10,1=>30,2=>30,3=>17,4=>14,5=>17,6=>30,7=>50,8=>34,9=>20,10=>50,11=>30,12=>28,13=>15];
        $y = 5;
        $this->SetFont('Arial','B',7);
        $this->SetTextColor(0, 0, 0);
        $this->Cell($x[0],$y, utf8_encode('ITEM'),1,0,'C');
        $this->Cell($x[3],$y, utf8_decode('CODIGO'),1,0,'C');
        $this->Cell($x[4],$y, utf8_decode('DNI'),1,0,'C');
        $this->Cell($x[1],$y, utf8_decode('NOMBRES'),1,0,'C');
        $this->Cell($x[2],$y, utf8_decode('APELLIDOS'),1,0,'C');
        $this->Cell($x[5],$y, utf8_decode('CELULAR'),1,0,'C');
        $this->Cell($x[6],$y, utf8_decode('CORREO PERSONAL'),1,0,'C');
        $this->Cell($x[11],$y,utf8_decode('FACULTAD'),1,0,'C');
        $this->Cell($x[12],$y,utf8_decode('ESCUELA'),1,0,'C');
        $this->Cell($x[13],$y,utf8_decode('SEDE'),1,0,'C');
        $this->SetFillColor(0, 102, 51);
        $this->Cell($x[8],$y,utf8_decode('CORREO INSTITUCIONAL'),1,0,'C',true);
        $this->Cell($x[9],$y,utf8_decode('CONTRASEÑA'),1,1,'C',true);
    }
//Pie de página
    function Footer()
    {
        $this->SetY(-10);
        $this->SetFont('Arial','I',7);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . " de {nb}", 0, 0, 'C');
    }
}
