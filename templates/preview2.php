<?php

function preview2() {
    global $app;
    $db = getDB();

    $app->response->headers->set("Content-Type", "application/pdf");

    include 'headerpdf.php';

    $req = json_decode($app->request()->getBody(), TRUE);

    $paramToken = $req['token'];

    $decode = JWT::decode($paramToken, TK);

    $id_institusi = $decode->id_institusi;

    $paramSubject = $req['subject']; // Getting parameter with names
    $paramTanggalSurat = $req['tanggal_surat']; // Getting parameter with names
//    $paramNoSurat = $req['nosurat'];
    $paramLampiran2 = $req['lampiran'];
        
        if($paramLampiran2 == 0){
            $paramLampiran = '-';
        }  else {
            $paramLampiran = $req['lampiran'];
        }
    $paramHal = $req['subject'];
    $paramTujuan = $req['tujuan'];
    $paramPenandatangan = $req['penandatangan'];
    $paramTembusan = $req['tembusan'];
    $paramIsiSurat = $req['isi'];   

    $paramNoSurat = checkCounter($db, $id_institusi, true) . "/UN39." . getKodeUnit($db, $id_institusi) . "/" . $req['hal'] . "/" . date('y');

    $timezone_identifier = "Asia/Jakarta";
    date_default_timezone_set($timezone_identifier);
    $tanggal_surat = convertDate(date('Y-m-d', strtotime($paramTanggalSurat)));
    $db = null;

// add a page

    $pdf->AddPage();


//content PDF
    setlocale(LC_ALL, 'IND');

    $pdf->Ln(55);
    $pdf->SetFont('times', '', 12);


//first
    $pdf->MultiCell(0, 0, '' . $tanggal_surat . '', 0, 'L', 0, 0, 160, 53, true, 0, false, true, 0, 'M', true); //selisih turun cell 9
    $pdf->MultiCell(0, 0, 'No', 0, 'L', 0, 0, 25, 53, true, 0, false, true, 0, 'M', true); //25 : margin dari kiri 53: margin dari atas
    $pdf->MultiCell(0, 0, ':', 0, 'L', 0, 0, 50, 53, true, 0, false, true, 0, 'M', true);
    $pdf->MultiCell(0, 0, 'Lampiran', 0, 'L', 0, 0, 25, 61, true, 0, false, true, 0, 'M', true);
    $pdf->MultiCell(0, 0, ':', 0, 'L', 0, 0, 50, 61, true, 0, false, true, 0, 'M', true);
    $pdf->MultiCell(0, 0, 'Hal', 0, 'L', 0, 0, 25, 69, true, 0, false, true, 0, 'M', true);
    $pdf->MultiCell(0, 0, ':', 0, 'L', 0, 0, 50, 69, true, 0, false, true, 0, 'M', true);
    $pdf->MultiCell(0, 0, '' . $paramNoSurat . '', 0, 'L', 0, 0, 53, 53, true, 0, false, true, 0, 'M', true);
    $pdf->MultiCell(0, 0, '' . $paramLampiran . '', 0, 'L', 0, 0, 53, 61, true, 0, false, true, 0, 'M', true);
    $pdf->MultiCell(90, 17, '' . $paramHal . '', 0, 'L', 0, 0, 53, 69, true, 0, false, true, 0, 'T', true);

//second
    $pdf->MultiCell(0, 0, 'Yth.', 0, 'L', 0, 0, 25, 88, true, 0, false, true, 0, 'M', true);
//    $pdf->MultiCell(0, 0, '' . $paramTujuan . '', 0, 'L', 0, 0, 20, 88, true, 0, false, true, 0, 'M', true);
    for ($i = 0; $i < count($paramTujuan); $i++) {
        if ($paramTujuan[$i] != '') {
            $pdf->MultiCell(170, 0, $paramTujuan[$i]['name'], 0, 'J', 0, 1, 33, '', true, 0, true, true, 0, 'T', true);
        }
    }
    $pdf->MultiCell(90, 17, 'Universitas Negeri Jakarta', 0, 'L', 0, 1, 33, '', true, 0, false, true, 0, 'T', true); //90 = panjang cell 6 lebar cell
//third
//    $input = str_replace("&lt;br/&gt;", "\\nbbb", $paramIsiSurat);
//    $paramIsiSurat = "<p><span style=\"color\: rgba(0, 0, 0, 0.870588)\;float\: none\;background\-color: rgb(255\, 255\,
//255)\;\">asdaf</span></p>
//";
//    $input = "<p>asdf</p><p>asdf<span>asdf</span></p>";
    $input = $paramIsiSurat;
    $pdf->MultiCell(170, 0, '' . $input . '', 0, 'J', 0, 1, 25, '', true, 0, true, true, 0, 'T', true); //nilai 1 setelah J adalah posisi cell default berada dibawah
//$pdf->MultiCell($w, $h, $txt, $border, $align, $fill, $ln, $x, $y, $reseth, $stretch, $ishtml, $autopadding, $maxh);
////$pdf->writeHTML($html);
//
    $pdf->setCellMargins(0, 7, 0, 0);
    $pdf->MultiCell(170, 0, '' . $paramPenandatangan[0]['name'] . '', 0, 'L', 0, 1, 140, '', true, 0, false, true, 0, 'T', true); //nanti diganti
    $pdf->setCellMargins(0, 23, 0, 0);
    $pdf->MultiCell(170, 0, '' . $paramPenandatangan[0]['nama'], 0, 'L', 0, 1, 140, '', true, 0, false, true, 0, 'T', true);
    $pdf->setCellMargins(0, 0, 0, 0);
    $pdf->MultiCell(170, 0, 'NIP.' . $paramPenandatangan[0]['nip'], 0, 'L', 0, 1, 140, '', true, 0, false, true, 0, 'T', true);

//Tembusan
    $pdf->setCellMargins(0, 7, 0, 0);
    $pdf->setCellMargins(0, 0, 0, 0);
    for ($i = 0; $i < count($paramTembusan); $i++) {
        if ($paramTembusan[$i] != '') {
            $pdf->MultiCell(170, 0, 'Tembusan :', 0, 'L', 0, 1, 25, '', true, 0, false, true, 0, 'T', true);
            $pdf->MultiCell(170, 0, ($i + 1) . '. ' . $paramTembusan[$i]['name'], 0, 'L', 0, 1, 25, '', true, 0, false, true, 0, 'T', true);
        }
    }

// create some HTMNomor                 : L content
//$html = '<span style="text-align:justify;">a <u>abc</u> abcdefghijkl (abcdef) abcdefg <b>abcdefghi</b> a ((abc)) abcd <img src="images/logo_example.png" border="0" height="41" width="41" /> <img src="images/tcpdf_box.svg" alt="test alt attribute" width="80" height="60" border="0" /> abcdef abcdefg <b>abcdefghi</b> a abc abcd abcdef abcdefg <b>abcdefghi</b> a abc abcd abcdef abcdefg <b>abcdefghi</b> a <u>abc</u> abcd abcdef abcdefg <b>abcdefghi</b> a abc \(abcd\) abcdef abcdefg <b>abcdefghi</b> a abc \\\(abcd\\\) abcdef abcdefg <b>abcdefghi</b> a abc abcd abcdef abcdefg <b>abcdefghi</b> a abc abcd abcdef abcdefg <b>abcdefghi</b> a abc abcd abcdef abcdefg abcdefghi a abc abcd <a href="http://tcpdf.org">abcdef abcdefg</a> start a abc before <span style="background-color:yellow">yellow color</span> after a abc abcd abcdef abcdefg abcdefghi a abc abcd end abcdefg abcdefghi a abc abcd abcdef abcdefg abcdefghi a abc abcd abcdef abcdefg abcdefghi a abc abcd abcdef abcdefg abcdefghi a abc abcd abcdef abcdefg abcdefghi a abc abcd abcdef abcdefg abcdefghi a abc abcd abcdef abcdefg abcdefghi a abc abcd abcdef abcdefg abcdefghi<br />abcd abcdef abcdefg abcdefghi<br />abcd abcde abcdef</span>';
//$html = "<span>Much playing will only make you happy temporarily</span>";
//$input = $_POST['isi'];
// set core font
    $pdf->SetFont('helvetica', '', 10);

// output the HTML content
//$pdf->writeHTML($html, true, 0, true, true);

    $pdf->Ln();

// set UTF-8 Unicode font
    $pdf->SetFont('dejavusans', '', 10);

// output the HTML content
//$pdf->writeHTML($html, true, 0, true, true,'J');
//$pdf->Write(0, $input);
// reset pointer to the last page
    $pdf->lastPage();

// ---------------------------------------------------------
//Close and output PDF document
    $nama_pengirim = $_POST['sender'];
    $nama_file = $nosurat . '.pdf';
    $output = $pdf->Output('', 'S');
    $savePdf = addslashes($output);
    $pdf->Output($nama_file, 'I');
}
