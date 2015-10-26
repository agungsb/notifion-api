<?php

function viewSurat($id, $token) {
    global $app;

    $app->response->headers->set("Content-Type", "application/pdf");
//include header
    include 'headerpdf.php';
    
    $req = json_decode($app->request->getBody(), true);

//    $token = $req['token']; Jika menggunakan method POST
//    $id = $req['id'];

    $decode = JWT::decode($token, TK);

    $account = $decode->account;
    $id_jabatan = $decode->id_jabatan;

    $dbh = getDB();
    
//    setReads($dbh, $id);

    $query = "SELECT surat.*, surat_terdistribusi.penerima, surat_kode_hal.deskripsi, users.nama, jabatan.jabatan from surat, surat_terdistribusi, surat_kode_hal, users, jabatan WHERE surat_terdistribusi.id=:id_surat AND surat_terdistribusi.id_surat = surat.id_surat AND (surat_terdistribusi.penerima = :id_jabatan OR surat_terdistribusi.penerima = :account) AND surat.kode_hal = surat_kode_hal.kode_hal AND ((surat_terdistribusi.penerima = users.account) OR (surat_terdistribusi.penerima = users.id_jabatan AND users.id_jabatan = jabatan.id_jabatan))";

    $stmt = $dbh->prepare($query);
    $stmt->bindValue(':id_surat', (int) $id, PDO::PARAM_INT);
    $stmt->bindValue(':id_jabatan', $id_jabatan);
    $stmt->bindValue(':account', $account);

    try {
        $stmt->execute();

        $row = $stmt->fetch();

        $hal = $row['deskripsi']; // Mendapatkan deskripsi dari HAL
        $input = $row['isi']; // Mendapatkan isi dari surat
//        $tjb = $row['jabatan'];
//        $nama_pejabat = $row['nama'];
        $nosurat = $row['no_surat'];
        $lam = $row['lampiran'];
        $tanggal = convertDate($row['tanggal_surat']);

        $tjb = getJabatan($dbh, $row['penandatangan']);
        
        $test = getAccountName($dbh, $row['penandatangan']);
        $nama_pejabat = $test['nama'];
        $nip = $test['nip'];

        if ($row['tembusan'] != '') {
            $tembusan = explode("@+id/", $row['tembusan']);
        }

        // Cari nama user berdasarkan jabatan parameter 'tujuan' //
        $query2 = "SELECT users.nama FROM users WHERE users.id_jabatan = :tujuan OR users.account = :tujuan";
        $stmt2 = $dbh->prepare($query2);
        $stmt2->bindValue(":tujuan", $row['penerima']);
        try {
            $stmt2->execute();
            if ($stmt2->rowCount() > 0) { // Jika ditemukan
                $row2 = $stmt2->fetch();
                $tujuan2 = $row2['nama'];
            } else { // Jika tidak ditemukan, berarti suratnya ditujukan kepada pejabat. Cari di tabel pejabat
                $tujuan2 = getJabatan($dbh, $row['penerima']);
            }
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

// add a page

    $pdf->AddPage();


//content PDF
    setlocale(LC_ALL, 'IND');

    $pdf->Ln(55);
    $pdf->SetFont('times', '', 12);


//first
    $pdf->MultiCell(0, 0, '' . $tanggal . '', 0, 'L', 0, 0, 160, 53, true, 0, false, true, 0, 'M', true); //selisih turun cell 9
    $pdf->MultiCell(0, 0, 'No', 0, 'L', 0, 0, 25, 53, true, 0, false, true, 0, 'M', true); //25 : margin dari kiri 53: margin dari atas
    $pdf->MultiCell(0, 0, ':', 0, 'L', 0, 0, 50, 53, true, 0, false, true, 0, 'M', true);
    $pdf->MultiCell(0, 0, 'Lampiran', 0, 'L', 0, 0, 25, 61, true, 0, false, true, 0, 'M', true);
    $pdf->MultiCell(0, 0, ':', 0, 'L', 0, 0, 50, 61, true, 0, false, true, 0, 'M', true);
    $pdf->MultiCell(0, 0, 'Hal', 0, 'L', 0, 0, 25, 69, true, 0, false, true, 0, 'M', true);
    $pdf->MultiCell(0, 0, ':', 0, 'L', 0, 0, 50, 69, true, 0, false, true, 0, 'M', true);
    $pdf->MultiCell(0, 0, '' . $nosurat . '', 0, 'L', 0, 0, 53, 53, true, 0, false, true, 0, 'M', true);
    $pdf->MultiCell(0, 0, '' . $lam . '', 0, 'L', 0, 0, 53, 61, true, 0, false, true, 0, 'M', true);
    $pdf->MultiCell(90, 17, '' . $hal . '', 0, 'L', 0, 0, 53, 69, true, 0, false, true, 0, 'T', true);

//second
    $pdf->MultiCell(0, 0, 'Yth.', 0, 'L', 0, 0, 25, 88, true, 0, false, true, 0, 'M', true);
    $pdf->MultiCell(90, 6, '' . $tujuan2 . '', 0, 'L', 0, 0, 33, 88, true, 0, false, true, 0, 'M', true);
    $pdf->MultiCell(90, 6, 'Universitas Negeri Jakarta', 0, 'L', 0, 0, 33, 93, true, 0, false, true, 0, 'M', true);

//third
//$input = str_replace('<br />', '\n', $_POST['isi']);

    $html = $input;
    $pdf->MultiCell(170, 0, '' . $input . '' . "\n", 0, 'J', 0, 1, 25, 105, true, 0, true, true, 0, 'T', true); //nilai 1 setelah J adalah posisi cell default berada dibawah
//$pdf->MultiCell($w, $h, $txt, $border, $align, $fill, $ln, $x, $y, $reseth, $stretch, $ishtml, $autopadding, $maxh);
////$pdf->writeHTML($html);
//
    $pdf->setCellMargins(0, 7, 0, 0);
    $pdf->MultiCell(170, 0, '' . $tjb . '', 0, 'L', 0, 1, 140, '', true, 0, false, true, 0, 'T', true);
    $pdf->setCellMargins(0, 23, 0, 0);
    $pdf->MultiCell(170, 0, '' . $nama_pejabat . '', 0, 'L', 0, 1, 140, '', true, 0, false, true, 0, 'T', true);
    $pdf->setCellMargins(0, 0, 0, 0);
    $pdf->MultiCell(170, 0, 'NIP' . ".$nip.", 0, 'L', 0, 1, 140, '', true, 0, false, true, 0, 'T', true);

//Tembusan
    if ($tembusan == null) {
        $pdf->MultiCell(170, 0, '', 0, 'L', 0, 1, 25, '', true, 0, false, true, 0, 'T', true);
    } else {
        $pdf->MultiCell(170, 0, 'Tembusan :', 0, 'L', 0, 1, 25, '', true, 0, false, true, 0, 'T', true);
        for ($i = 0; $i < count($tembusan); $i++) {
            if ($tembusan[$i] != '') {
                $pdf->MultiCell(170, 0,  $tembusan[$i], 0, 'L', 0, 1, 25, '', true, 0, false, true, 0, 'T', true);
            }
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

function getAccountName($dbh, $params) {
    $query = "SELECT nama, nip FROM users WHERE account='" . $params . "' or id_jabatan ='" . $params . "'";
    $stmt = $dbh->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch();
    return $row;
}

function isPejabat($dbh, $params){
    $query = "SELECT jabatan.jabatan, institusi.nama_institusi FROM users, jabatan, institusi WHERE (users.account='" . $params . "' OR users.id_jabatan='" . $params . "') AND users.id_jabatan=jabatan.id_jabatan AND jabatan.id_institusi=institusi.id_institusi";
    $stmt = $dbh->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch();
    if($stmt->rowCount() > 0){
        $result = true;
    }else{
        $result = false;
    }
    return $result;
}

function getJabatan($dbh, $params) {
    $query = "SELECT jabatan.jabatan, institusi.nama_institusi FROM users, jabatan, institusi WHERE (users.account='" . $params . "' OR users.id_jabatan='" . $params . "') AND users.id_jabatan=jabatan.id_jabatan AND jabatan.id_institusi=institusi.id_institusi";
    $stmt = $dbh->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch();
    return $row['jabatan'];
}

function setReads($dbh, $id) {
    $query = "UPDATE `surat_terdistribusi` SET isUnread='0' WHERE id=:id";
    try {
        $stmt = $dbh->prepare($query);
        $stmt->bindParam("id", $id);
        $stmt->execute();
    } catch (PDOException $ex) {
        echo "{'error':{text':'" . $ex->getMessage() . "'}}";
    }
}
