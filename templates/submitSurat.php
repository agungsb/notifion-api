<?php

function submitSurat() {
//    die();
    $db = getDB();
    global $app;
//    $req = json_decode($app->request()->getBody(), true);
    $req = $app->request->post();

    $paramToken = $req['token'];
    $paramUploaded = $req['is_uploaded'];
    $decode = JWT::decode($paramToken, TK);
    if ($decode->valid) {
        $paramSubject = $req['subject'];
        $paramIdInstitusi = $decode->id_institusi;
        $paramNamaInstitusi = $decode->nama_institusi;
        $paramHal = $req['hal'];

        $paramLampiran = $req['lampiran'];

        $paramTujuan = json_decode($req['tujuan']);
        $tujuan = "";
        for ($i = 0; $i < count($paramTujuan); $i++) {
            $tujuan .= $paramTujuan[$i]->identifier . "@+id/";
        }

        $paramPenandatangan = json_decode($req['penandatangan']);
        $penandatangan = "";
        for ($i = 0; $i < count($paramPenandatangan); $i++) {
            $penandatangan = $paramPenandatangan[0]->identifier;
            $penandatanganEmail = $paramPenandatangan[0]->email;
        }
        if (isset($req['isi'])) {
            $paramIsi = str_replace('<span style="color: rgba(0, 0, 0, 0.870588);float: none;background-color: rgb(255, 255, 255);">', '', $req['isi']);
        } else {
            $paramIsi = "";
        }

        $paramTanggalSurat = $req['tanggal_surat'];

        $timezone_identifier = "Asia/Jakarta";
        date_default_timezone_set($timezone_identifier);
        $tanggal_surat = date('Y-m-d', strtotime($paramTanggalSurat));

        $nosurat = checkCounter($db, $paramIdInstitusi, false) . "/UN39." . getKodeUnit($db, $paramIdInstitusi) . "/" . $paramHal . "/" . date('y'); //penomoran surat

        $query = "INSERT INTO `surat`(subject_surat, tujuan, kode_lembaga_pengirim, penandatangan, no_surat, lampiran, kode_hal, isi, tembusan, tanggal_surat, ditandatangani, is_uploaded)"
                . " VALUES(:subject_surat, :tujuan, :id_institusi, :penandatangan, :nosurat, :lampiran, :hal, :isi, :tembusan, :tanggal_surat, :ditandatangani, :is_uploaded)";

        $stmt = $db->prepare($query);
        $stmt->bindValue(":subject_surat", $paramSubject);
        $stmt->bindValue(":tujuan", $tujuan);
        $stmt->bindValue(":id_institusi", $paramIdInstitusi);
        $stmt->bindValue(":penandatangan", $penandatangan);
        $stmt->bindValue(":nosurat", $nosurat);
        $stmt->bindValue(":lampiran", (int) $paramLampiran, PDO::PARAM_INT);
        $stmt->bindValue(":hal", $paramHal);
        $stmt->bindValue(":isi", $paramIsi);
        $paramTembusan = json_decode($req['tembusan']);
        if ($paramTembusan != null) {
            $tembusan = "";
            for ($i = 0; $i < (count($paramTembusan) - 1); $i++) {
                $tembusan .= $paramTembusan[$i]->identifier . "@+id/";
            }
            $tembusan .= $paramTembusan[$i]->identifier;
            $stmt->bindValue(":tembusan", $tembusan);
        } else {
            $stmt->bindValue(":tembusan", "");
        }
        $stmt->bindValue(":tanggal_surat", $tanggal_surat);
        $stmt->bindValue(":ditandatangani", '0');
        $stmt->bindValue(":is_uploaded", $paramUploaded);
//        blobPdf($tanggal_surat, $nosurat, $paramLampiran, $paramHal, $tujuan, $paramIsi, $paramPenandatangan, $tembusan, $paramSubject);
//        die();
        try {
            if (is_connected()) {
                //tambahkan pengaturan if else nya ketika internet mati fungsi yang dijalankan hanya sms

//                echo $tanggal_surat;
//                echo ' || ';
//                echo $nosurat;
//                echo ' || ';
//                echo $paramLampiran;
//                echo ' || ';
//                print_r($paramTujuan);
//                echo $paramIsi . '-';
//                echo ' || ';
//                print_r($paramPenandatangan);
//                print_r($paramTembusan);
//                echo $paramSubject;
//                die();

                $stmt->execute();
                //Add Blob File
                $file_pdf = blobPdf($tanggal_surat, $nosurat, $paramLampiran, $paramTujuan, $paramIsi, $paramPenandatangan, $paramTembusan, $paramSubject);

                if ($paramUploaded) {
                    $file_path = 'assets/uploaded/' . $_FILES['isi']['name'];
                    if (move_uploaded_file($_FILES['isi']['tmp_name'], $file_path)) {
                        if (!InsertSuratUploaded($db, $nosurat, $file_path)) {
                            die('{"result": "Gagal mengupload surat"}');
                        }
                    }
                }

                // Setelah berhasil mengeksekusi query, upload file ke folder yang telah ditentukan
                if ($paramLampiran > 0) {
                    for ($i = 0; $i < $paramLampiran; $i++) {
                        $destination = 'assets/attachments/' . $_FILES[$i]['name'];
                        if (move_uploaded_file($_FILES[$i]['tmp_name'], $destination)) {
                            if (!InsertSuratAttachment($db, $nosurat, $destination)) {
                                die('{"result": "Gagal mengupload lampiran"}');
                            }
                        }
                    }
                }

                if ($penandatangan != null && $paramUploaded == 'false') {
                    $sql = "SELECT surat.file_surat From surat WHERE no_surat='" . $nosurat . "'";
                    $result = $db->prepare($sql);
                    $result->execute();
                    if ($result->rowCount() > 0) { // Jika ditemukan
                        $rowEmail = $result->fetch();
                        $fileSurat = $rowEmail['file_surat'];
                        sendEmail($paramSubject, $penandatanganEmail, $fileSurat, $paramLampiran, $db);
                    }
                } else {
                    $sql = "SELECT surat_uploaded.file_path From surat_uploaded WHERE no_surat='" . $nosurat . "'";
                    $result = $db->prepare($sql);
                    $result->execute();
                    if ($result->rowCount() > 0) { // Jika ditemukan
                        $rowEmail = $result->fetch();
                        $fileSurat = $rowEmail['file_path'];
                        sendEmailUploaded($paramSubject, $penandatanganEmail, $fileSurat);
                    }
                }

                $registration_ids = array();
                if ((pushNotification($db, $penandatangan)) != null) {
                    $registration_ids = pushNotification($db, $penandatangan);
                }

                $gcm = new GCM();
                $pesan = array("message" => $paramSubject, "title" => "Surat keluar untuk $paramNamaInstitusi", "msgcnt" => 1, "sound" => "beep.wav");
                $result = $gcm->send_notification($registration_ids, $pesan);
                echo '{"result": "success", "account": "' . $penandatangan . '"}';
            } else {
                echo '{"result": "internet off"}';
            }
        } catch (PDOException $ex) {
//            echo $ex->getMessage();
            echo '{"result": "' . $ex->getMessage() . '"}';
        }
    } else {
        echo '{"result": "Token tidak valid"}';
    }
    $db = null;
}

function InsertSuratUploaded($db, $nosurat, $file_path) {
    $query = "INSERT INTO `surat_uploaded`(no_surat, file_path) VALUES(:no_surat, :file_path)";

    $stmt = $db->prepare($query);
    $stmt->bindValue(":no_surat", $nosurat);
    $stmt->bindValue(":file_path", $file_path);
    try {
        if ($stmt->execute()) {
            return true;
        }
    } catch (PDOException $ex) {
        return false;
    }
}

function InsertSuratAttachment($db, $nosurat, $file_path) {
    $query = "INSERT INTO `surat_lampiran`(no_surat, file_path) VALUES(:no_surat, :file_path)";

    $stmt = $db->prepare($query);
    $stmt->bindValue(":no_surat", $nosurat);
    $stmt->bindValue(":file_path", $file_path);
    try {
        if ($stmt->execute()) {
            return true;
        }
    } catch (PDOException $ex) {
        return false;
    }
}

function blobPdf($paramTanggalSurat, $paramNoSurat, $paramLampiran, $tujuan, $input, $penandatangan, $tembusan, $paramSubject) {

    include 'headerpdf.php';

    $dbh = getDB();

    $timezone_identifier = "Asia/Jakarta";
    date_default_timezone_set($timezone_identifier);
    $tanggal_surat = convertDate(date('Y-m-d', strtotime($paramTanggalSurat)));

    if ($paramLampiran == 0) {
        $lam = '-';
    } else {
        $lam = $paramLampiran;
    }

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
    $pdf->MultiCell(0, 0, '' . $lam . '', 0, 'L', 0, 0, 53, 61, true, 0, false, true, 0, 'M', true);
    $pdf->MultiCell(90, 17, '' . $paramSubject . '', 0, 'L', 0, 0, 53, 69, true, 0, false, true, 0, 'T', true);

//second
    $pdf->MultiCell(0, 0, 'Yth.', 0, 'L', 0, 0, 25, 88, true, 0, false, true, 0, 'M', true);
//    $pdf->MultiCell(0, 0, '' . $paramTujuan . '', 0, 'L', 0, 0, 20, 88, true, 0, false, true, 0, 'M', true);
    for ($i = 0; $i < count($tujuan); $i++) {
//        $pdf->MultiCell(170, 0, $tembusan[$i], 0, 'L', 0, 1, 25, '', true, 0, false, true, 0, 'T', true);
        $pdf->MultiCell(170, 0, $tujuan[$i]->name, 0, 'J', 0, 1, 33, '', true, 0, true, true, 0, 'T', true);
    }
    $pdf->MultiCell(90, 17, 'Universitas Negeri Jakarta', 0, 'L', 0, 1, 33, '', true, 0, false, true, 0, 'T', true); //90 = panjang cell 6 lebar cell
//third

    $pdf->MultiCell(170, 0, '' . $input . '', 0, 'J', 0, 1, 25, '', true, 0, true, true, 0, 'T', true); //nilai 1 setelah J adalah posisi cell default berada dibawah
//$pdf->MultiCell($w, $h, $txt, $border, $align, $fill, $ln, $x, $y, $reseth, $stretch, $ishtml, $autopadding, $maxh);

    $pdf->setCellMargins(0, 7, 0, 0);
    $pdf->MultiCell(170, 0, '' . $penandatangan[0]->name . '', 0, 'L', 0, 1, 140, '', true, 0, false, true, 0, 'T', true); //nanti diganti
    $pdf->setCellMargins(0, 23, 0, 0);
    $pdf->MultiCell(170, 0, '' . $penandatangan[0]->nama, 0, 'L', 0, 1, 140, '', true, 0, false, true, 0, 'T', true);
    $pdf->setCellMargins(0, 0, 0, 0);
    $pdf->MultiCell(170, 0, 'NIP.' . $penandatangan[0]->nip, 0, 'L', 0, 1, 140, '', true, 0, false, true, 0, 'T', true);

//Tembusan
    if ($tembusan == null) {
        $pdf->MultiCell(170, 0, '', 0, 'L', 0, 1, 25, '', true, 0, false, true, 0, 'T', true);
    } else {
        $pdf->MultiCell(170, 0, 'Tembusan :', 0, 'L', 0, 1, 25, '', true, 0, false, true, 0, 'T', true);
        for ($i = 0; $i < count($tembusan); $i++) {
            if ($tembusan[$i] != '') {
                $pdf->MultiCell(170, 0, $tembusan[$i]->name, 0, 'L', 0, 1, 25, '', true, 0, false, true, 0, 'T', true);
            }
        }
    }

    $pdf->SetFont('helvetica', '', 10);

// output the HTML content


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
    $output = $pdf->Output('', 'S');
    $savePdf = addslashes($output);

    $sql = "UPDATE surat SET file_surat='" . $savePdf . "' where subject_surat='" . $paramSubject . "'";
    $result = $dbh->prepare($sql);
    $result->execute();
}

function sendEmail($paramSubject, $receiver, $output, $paramLampiran, $db) {
    include 'PHPMailer/PHPMailerAutoload.php';

    $mail = new PHPMailer(); // create a new object
    $mail->IsSMTP(); // enable SMTP
//$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
    $mail->SMTPAuth = true; // authentication enabled
    $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 465; // or 587
    $mail->IsHTML(true);
    $mail->Username = "firdausibnuu@gmail.com";
    $mail->Password = "firdausibnu21";
    $mail->SetFrom("firdaus.ibnuu@gmail.com");
    $mail->Subject = $paramSubject;
    $mail->Body = "Test PDF.";
    $email = $receiver;
    $mail->addStringAttachment($output, $paramSubject . '.pdf');
    if ($paramLampiran > 0) {
        for ($i = 0; $i < $paramLampiran; $i++) {
            $path = getFileLampiran($no_surat, $db);
            $path_lampiran = json_decode($path);
            
            
            $mail->addAttachment($paramLampiran);
        }
    }
    $mail->AddAddress($email);
    if (!$mail->Send()) {
        echo "<script type='text/javascript'>alert('GAGAL MENGIRIM EMAIL.');</script>";
        //header("refresh: 0;url=index.php");
        $mail->ErrorInfo;
    } else {
        echo "<script type='text/javascript'>alert('BERHASIL MENGIRIM EMAIL.');</script>";
        //header("refresh: 0;url=index.php");
    }
}

function sendEmailUploaded($paramSubject, $receiver, $output, $paramLampiran) {
    include 'PHPMailer/PHPMailerAutoload.php';

    $mail = new PHPMailer(); // create a new object
    $mail->IsSMTP(); // enable SMTP
//$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
    $mail->SMTPAuth = true; // authentication enabled
    $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 465; // or 587
    $mail->IsHTML(true);
    $mail->Username = "firdausibnuu@gmail.com";
    $mail->Password = "firdausibnu21";
    $mail->SetFrom("firdaus.ibnuu@gmail.com");
    $mail->Subject = $paramSubject;
    $mail->Body = "Test PDF.";
    $email = $receiver;
    $mail->addAttachment($output);
    if ($paramLampiran > 0) {
        for ($i = 0; $i < $paramLampiran, $i++;) {
            $mail->addAttachment($paramLampiran);
        }
    }
    $mail->addStringAttachment($output, $paramSubject . '.pdf');
    $mail->AddAddress($email);
    if (!$mail->Send()) {
        echo "<script type='text/javascript'>alert('GAGAL MENGIRIM EMAIL.');</script>";
        //header("refresh: 0;url=index.php");
        $mail->ErrorInfo;
    } else {
        echo "<script type='text/javascript'>alert('BERHASIL MENGIRIM EMAIL.');</script>";
        //header("refresh: 0;url=index.php");
    }
}

function getFileLampiran($no_surat, $db) {
    $sqlLampiran = "SELECT surat_lampiran.file_path WHERE no_surat='" . $no_surat . "'";
    $stmtLampiran = $db->prepare($sqlLampiran);
    $stmtLampiran->execute();

    $i = 0;
    while ($row = $stmtLampiran->fetch()) {
        $lampiran[$i] = $row['file_path'];
        $i++;
    }
    
    return $lampiran;
}
