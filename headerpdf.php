<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// Include the main TCPDF library (search for installation path).
require_once('tcpdf/tcpdf.php');

ini_set('display_errors', 'On');
date_default_timezone_set("Asia/Jakarta");
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);
class MYPDF extends TCPDF {

    public function Header() {
        $headerData = $this->getHeaderData();
        $this->getHeaderMargin();
        $this->Image('images/logo_1.JPG', 8, 7, 22,'JPG', '', 'L', false, 300);
        //$this->Image($file, $x, $y, $w, $h, $type, $link, $align, $resize, $dpi);
        //$this->I
        $this->SetFont('times');
        $this->SetFontSize(16.0);
        $this->Cell(0, 0, 'KEMENTERIAN PENDIDIKAN DAN KEBUDAYAAN', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(6);
        $this->SetFont('timesB');
        $this->SetFontSize(14.0);
        $this->Cell(0, 0, 'UNIVERSITAS NEGERI JAKARTA', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(6);
        $this->SetFont('times');
        $this->SetFontSize(11.0);
        $this->Cell(0, 0, 'Kampus Universitas Negeri Jakarta, Jalan Rawamangun Muka, Jakarta 13220', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(5);
        $this->SetFont('times');
        $this->SetFontSize(10.0);
        $this->Cell(0, 0, 'Telepon/Faximile : Rektor: (021)4893854, PR I : 4895130, PR II : 4893918, PR III : 4892926, PR IV : 4893982', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(5);
        $this->SetFont('times');
        $this->SetFontSize(10.0);
        $this->Cell(0, 0, 'BAUK : 4750930, BAAK : 4759081, BAPSI : 4752180', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(4);
        $this->SetFont('times');
        $this->SetFontSize(9.2);
        $this->Cell(0, 0, 'Bagian UHTP: Telepon: 4893726, Bagian Keuangan : 4892414, Bagian Kepegawaian : 4890536, Bagian HUMAS : 4898486', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(4);
        $this->SetFont('times');
        $this->SetFontSize(10);
        $this->Cell(0, 0, 'Laman : www.unj.ac.id', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(1);
        $this->SetFont('timesB');
        $this->SetFontSize(19);
        $this->Cell(0, 0, '_________________________________________________', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        //$this->Cell($w, $h, $txt, $border, $ln, $align, $fill, $link, $stretch, $ignore_min_height, $calign);
        
    }

}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setHeaderMargin(10);
$pdf->SetMargins(17, 60, 0);
