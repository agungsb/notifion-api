<?php
//============================================================+
// File name   : example_003.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 003 for TCPDF class
//               Custom Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Custom Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');


// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

	//Page header
	public function Header() {
		// Logo
		$image_file = K_PATH_IMAGES.'logo_1.jpg';
		$this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 400, '', false, false, 0, false, false, false);
		// Set font
		$this->SetFont('helvetica', 'B', 20);
		// Title
		$this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 003');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->AddPage();

// set font
$pdf->SetFont('helvetica', 'B', 20);

$pdf->Write(0, 'Example of HTML Justification', '', 0, 'J', true, 0, false, false, 0);

// create some HTML content
//$html = '<span style="text-align:justify;">a <u>abc</u> abcdefghijkl (abcdef) abcdefg <b>abcdefghi</b> a ((abc)) abcd <img src="images/logo_example.png" border="0" height="41" width="41" /> <img src="images/tcpdf_box.svg" alt="test alt attribute" width="80" height="60" border="0" /> abcdef abcdefg <b>abcdefghi</b> a abc abcd abcdef abcdefg <b>abcdefghi</b> a abc abcd abcdef abcdefg <b>abcdefghi</b> a <u>abc</u> abcd abcdef abcdefg <b>abcdefghi</b> a abc \(abcd\) abcdef abcdefg <b>abcdefghi</b> a abc \\\(abcd\\\) abcdef abcdefg <b>abcdefghi</b> a abc abcd abcdef abcdefg <b>abcdefghi</b> a abc abcd abcdef abcdefg <b>abcdefghi</b> a abc abcd abcdef abcdefg abcdefghi a abc abcd <a href="http://tcpdf.org">abcdef abcdefg</a> start a abc before <span style="background-color:yellow">yellow color</span> after a abc abcd abcdef abcdefg abcdefghi a abc abcd end abcdefg abcdefghi a abc abcd abcdef abcdefg abcdefghi a abc abcd abcdef abcdefg abcdefghi a abc abcd abcdef abcdefg abcdefghi a abc abcd abcdef abcdefg abcdefghi a abc abcd abcdef abcdefg abcdefghi a abc abcd abcdef abcdefg abcdefghi a abc abcd abcdef abcdefg abcdefghi<br />abcd abcdef abcdefg abcdefghi<br />abcd abcde abcdef</span>';
$html = "<span>Eden Hazard kembali meraih penghargaan sebagai Pemain Terbaik Tahun Ini. Setelah mendapatkannya dari Asosiasi Pesepak Bola Profesional (PFA), pemain Chelsea itu dianugerahi penghargaan serupa dari Asosiasi Penulis Sepak Bola (FWA). 
 
“Kami dengan bangga mengumumkan pemain Chelsea, Eden Hazard, sebagai Pemain Terbaik FWA Tahun Ini. Selamat,” tulis FWA dalam akun Twitter resminya, Selasa (12/5/2015). 
 
Hazard dinobatkan sebagai pemain terbaik setelah meraih 53 persen suara dari para jurnalis. Penampilan apiknya mengantarkan Chelsea juara Premier League menjadi pertimbangan utama sebagian besar jurnalis memilihnya. 
 
Sementara itu, Pemain Muda Terbaik versi PFA, Harry Kane, menjadi pemain dengan jumlah suara terbanyak kedua di bawah Kane. Adapun peringkat ketiga diisi rekan Hazard di Chelsea, John Terry. 
 
“Hazard tampil konsisten dalam mengantarkan Chelsea menjadi juara. Dia seorang kreator, pencetak gol, dan juga pekerja keras. Hazard benar-benar pemain yang sangat bernilai di skuad Jose Mourinho,” puji Andy Dunn, Chairman FWA. 

Hazard memang menjadi instrumen vital Chelsea musim ini. Selain mengemas 14 gol dan 8 assist, dia juga menjadi satu dari tiga pemain The Blues - bersama Terry dan Branislav Ivanovic - yang selalu dimainkan sebagai starter dalam 36 pertandingan. 
 
Meraih penghargaan individu dari PFA dan FWA membuat Hazard sejajar dengan sejumlah nama besar yang telah lebih dulu meraihnya. Mereka antara lain Thierry Henry, Cristiano Ronaldo, Wayne Rooney, Robin van Persie, Gareth Bale, dan Luis Suarez. </span>";

// set core font
$pdf->SetFont('helvetica', '', 10);

// output the HTML content
//$pdf->writeHTML($html, true, 0, true, true);

$pdf->Ln();

// set UTF-8 Unicode font
$pdf->SetFont('dejavusans', '', 10);

// output the HTML content
$pdf->writeHTML($html, true, 0, true, true,'J');

// reset pointer to the last page
$pdf->lastPage();
//Close and output PDF document
$pdf->Output('example_003.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
