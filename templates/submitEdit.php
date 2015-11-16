<?php

function submitEdit() {
    $db = getDB();
    global $app;
    $req = $app->request->post();

    $paramToken = $req['token'];
    $paramUploaded = $req['is_uploaded'];

    $decode = JWT::decode($paramToken, TK);
    if ($decode->valid) {

        $paramSubject = $req['subject'];
        $paramIdInstitusi = $decode->id_institusi;
        $paramNamaInstitusi = $decode->nama_institusi;
        $paramLampiran = $req['lampiran'];
        $paramHal = $req['hal'];
        $nosurat = $req['nosurat'];

        $paramTujuan = json_decode($req['tujuan']);
        $tujuan = "";
        for ($i = 0; $i < count($paramTujuan); $i++) {
            $tujuan .= $paramTujuan[$i]->identifier . "@+id/";
        }

        $paramPenandatangan = json_decode($req['penandatangan']);
        $penandatangan = "";
        for ($i = 0; $i < count($paramPenandatangan); $i++) {
            $penandatangan = $paramPenandatangan[0]->identifier;
        }

        if (isset($req['isi'])) {
            $paramIsi = str_replace('<span style="color: rgba(0, 0, 0, 0.870588);float: none;background-color: rgb(255, 255, 255);">', '', $req['isi']);
        } else {
            $paramIsi = "";
        }
        
        if(isset($req['is_uploaded'])){
            if($req['is_uploaded'] == TRUE){
                $paramIsi = "";
            }
        }

        $paramTanggalSurat = $req['tanggal_surat'];
        $timezone_identifier = "Asia/Jakarta";
        date_default_timezone_set($timezone_identifier);
//        $tanggal_surat = date('Y-m-d', strtotime($paramTanggalSurat));

        $query = "UPDATE `surat` SET subject_surat = :subject_surat, tujuan = :tujuan, kode_lembaga_pengirim = :id_institusi, "
                . "kode_hal = :hal, isi = :isi, lampiran = :lampiran, tembusan = :tembusan, ditandatangani = :ditandatangani, "
                . "is_uploaded = :is_uploaded WHERE no_surat = :no_surat";
        $stmt = $db->prepare($query);
        $stmt->bindValue(":subject_surat", $paramSubject);
        $stmt->bindValue(":tujuan", $tujuan);
        $stmt->bindValue(":id_institusi", $paramIdInstitusi);
        $stmt->bindValue(":hal", $paramHal);
        $stmt->bindValue(":isi", $paramIsi);
        $stmt->bindValue(":lampiran", (int) $paramLampiran, PDO::PARAM_INT);
//        $stmt->bindValue(":penandatangan", $penandatangan);
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
        $stmt->bindValue(":ditandatangani", 0);
        $stmt->bindValue(":is_uploaded", $paramUploaded);
        $stmt->bindValue(":no_surat", $nosurat);

//        $removedOldAttachments = json_decode($req['removedOldAttachments']);
//        for ($i = 0; $i < count($removedOldAttachments); $i++) {
//            echo $removedOldAttachments->id_lampiran;
//        }
        try {
            if (is_connected()) { // Jika berhasil meng-update surat
                $stmt->execute();
                // Jika ada lampiran lama yang dihapus oleh user

                if ($req['totalRemovedOldAttachments'] > 0) {
                    // Hapus lampiran-lampirannya surat dari tabel surat_lampiran
                    $removedOldAttachments = json_decode($req['removedOldAttachments']);
                    for ($i = 0; $i < count($removedOldAttachments); $i++) {
                        echo $removedOldAttachments[$i]->id_lampiran;
                        $tempNama = substr($removedOldAttachments[$i]->file_path, 19);
                        $hapusFileAttachment = unlink("assets/attachments/" . $tempNama);
                        if (!HapusSuratAttachmentKoreksi($db, $removedOldAttachments[$i]->id_lampiran)) {
                            die('{"result": "Gagal menghapus lampiran surat koreksi"}');
                        }
                    }
                }

                // Hapus surat dari tabel surat_koreksi
                if (!HapusSuratKoreksi($db, $nosurat)) {
                    die('{"result": "Gagal menghapus surat koreksi"}');
                }

                // JIka surat merupakan hasil upload, upload file-nya ke folder yang telah ditentukan
                if ($paramUploaded == 'true') {
                    $file_path = 'assets/uploaded/' . $_FILES['isi']['name'];
                    if (move_uploaded_file($_FILES['isi']['tmp_name'], $file_path)) {
                        if (!InsertSuratUploadedKoreksi($db, $nosurat, $file_path)) {
                            die('{"result": "Gagal mengupload surat"}');
                        }
                    }
                }

                // Jika ada lampiran baru, upload file lampiran ke folder yang telah ditentukan
                if ($req['totalNewAttachments'] > 0) {
                    for ($i = 0; $i < $req['totalNewAttachments']; $i++) {
                        $destination = 'assets/attachments/' . $_FILES[$i]['name'];
                        if (move_uploaded_file($_FILES[$i]['tmp_name'], $destination)) {
                            if (!InsertSuratAttachmentKoreksi($db, $nosurat, $destination)) {
                                die('{"result": "Gagal mengupload lampiran"}');
                            }
                        }
                    }
                }

                if ($penandatangan != null && $paramUploaded == 'false') {
                    $sql = "SELECT surat.penandatangan, surat.file_surat From surat WHERE no_surat='" . $nosurat . "'";
                    $result = $db->prepare($sql);
                    $result->execute();
                    if ($result->rowCount() > 0) { // Jika ditemukan
                        $rowEmail = $result->fetch();
                        $fileSurat = $rowEmail['file_surat'];
                        $email = $rowEmail['penandatangan'];

                        $emailnya = pushNotificationEmail($db, $email);
                        $emailnyaa = implode("", $emailnya);
//                        echo $emailnyaa;
//                        die();
                        sendEmailEdit($paramSubject, $emailnyaa, $fileSurat, $paramLampiran, $paramNamaInstitusi);
                    }
                } else {
                    $sql = "SELECT surat_uploaded.file_path, surat.penandatangan From surat, surat_uploaded WHERE surat.no_surat=:no_surat AND surat_uploaded.no_surat = surat.no_surat";
                    $result = $db->prepare($sql);
                    $result->bindValue(':no_surat', $nosurat);
                    $result->execute();
                    if ($result->rowCount() > 0) { // Jika ditemukan
                        $rowEmail = $result->fetch();
                        $fileSurat = $rowEmail['file_path'];
                        $email = $rowEmail['penandatangan'];
                        $emailnya = pushNotificationEmail($db, $email);
                        $emailnyaa = implode("", $emailnya);
                        sendEmailEditUploaded($paramSubject, $emailnyaa, $fileSurat, $paramLampiran, $paramNamaInstitusi);
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
//                echo '{"result": "Internet Off"}';
                if ($stmt->execute()) {
                    // Jika ada lampiran lama yang dihapus oleh user

                    if ($req['totalRemovedOldAttachments'] > 0) {
                        // Hapus lampiran-lampirannya surat dari tabel surat_lampiran
                        $removedOldAttachments = json_decode($req['removedOldAttachments']);
                        for ($i = 0; $i < count($removedOldAttachments); $i++) {
                            echo $removedOldAttachments[$i]->id_lampiran;
                            $tempNama = substr($removedOldAttachments[$i]->file_path, 19);
                            $hapusFileAttachment = unlink("assets/attachments/" . $tempNama);
                            if (!HapusSuratAttachmentKoreksi($db, $removedOldAttachments[$i]->id_lampiran)) {
                                die('{"result": "Gagal menghapus lampiran surat koreksi"}');
                            }
                        }
                    }

                    // Hapus surat dari tabel surat_koreksi
                    if (!HapusSuratKoreksi($db, $nosurat)) {
                        die('{"result": "Gagal menghapus surat koreksi"}');
                    }

                    // JIka surat merupakan hasil upload, upload file-nya ke folder yang telah ditentukan
                    if ($paramUploaded == 'true') {
                        $file_path = 'assets/uploaded/' . $_FILES['isi']['name'];
                        if (move_uploaded_file($_FILES['isi']['tmp_name'], $file_path)) {
                            if (!InsertSuratUploadedKoreksi($db, $nosurat, $file_path)) {
                                die('{"result": "Gagal mengupload surat"}');
                            }
                        }
                    }

                    // Jika ada lampiran baru, upload file lampiran ke folder yang telah ditentukan
                    if ($req['totalNewAttachments'] > 0) {
                        for ($i = 0; $i < $req['totalNewAttachments']; $i++) {
                            $destination = 'assets/attachments/' . $_FILES[$i]['name'];
                            if (move_uploaded_file($_FILES[$i]['tmp_name'], $destination)) {
                                if (!InsertSuratAttachmentKoreksi($db, $nosurat, $destination)) {
                                    die('{"result": "Gagal mengupload lampiran"}');
                                }
                            }
                        }
                    }
                }

                if ($penandatangan != null && $paramUploaded == 'false') {
                    $sql = "SELECT surat.penandatangan, surat.file_surat From surat WHERE no_surat='" . $nosurat . "'";
                    $result = $db->prepare($sql);
                    $result->execute();
                    if ($result->rowCount() > 0) { // Jika ditemukan
                        $rowSMS = $result->fetch();
                        $tujuann = $rowSMS['penandatangan'];
                        $nohp = pushNotificationSMS($db, $tujuann);
                        $tujuanHp = implode("", $nohp);
                        sendSmsEdit($tujuanHp, $db, $paramNamaInstitusi, $paramSubject, $paramLampiran, $nosurat);
                    }
                } else {
                    $sql = "SELECT surat_uploaded.file_path, surat.penandatangan From surat, surat_uploaded WHERE no_surat='" . $nosurat . "'";
                    $result = $db->prepare($sql);
                    $result->execute();
                    if ($result->rowCount() > 0) { // Jika ditemukan
                        $rowSMS = $result->fetch();
                        $tujuann = $rowSMS['penandatangan'];
                        $nohp = pushNotificationSMS($db, $tujuann);
                        $tujuanHp = implode("", $nohp);
                        sendSmsEdit($tujuanHp, $db, $paramNamaInstitusi, $paramSubject, $paramLampiran, $nosurat);
                    }
                }
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

function InsertSuratUploadedKoreksi($db, $nosurat, $file_path) {
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

function InsertSuratAttachmentKoreksi($db, $nosurat, $file_path) {
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

function HapusSuratKoreksi($db, $nosurat) {
    $query = "DELETE from `surat_koreksi` WHERE no_surat=:no_surat";
    $stmt = $db->prepare($query);
    $stmt->bindValue(":no_surat", $nosurat);
    try {
        if ($stmt->execute()) {
            return true;
        }
    } catch (Exception $ex) {
        return false;
    }
}

function HapusSuratAttachmentKoreksi($db, $id_lampiran) {
    $query = "DELETE from `surat_lampiran`WHERE id_lampiran=:id_lampiran";
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id_lampiran", $id_lampiran);
    try {
        if ($stmt->execute()) {
            return true;
        }
    } catch (PDOException $ex) {
        return false;
    }
}

function sendSmsEdit($nohp, $db, $paramInstitusi, $paramSubject, $paramLampiran, $nosurat) {
    $sms = "Surat dari " . $paramInstitusi . " telah dikoreksi. Mengenai " . $paramSubject . " dengan lampiran sebanyak " . $paramLampiran . " Lampiran. Note: Fitur Email dan Android Tidak Aktif, kunjungi website untuk melihat surat.";
    $gammuexe = "C:\gammu\bin\gammu.exe";
    $gammurc = "C:\gammu\bin\gammurc";

    $cmd = $gammuexe . ' -c ' . $gammurc . ' sendsms TEXT ' . $nohp . ' -text "' . $sms . '"';
    if ($out = substr(exec($cmd), 47, 2)) {
        if ($out == "OK") {
            $sql = "UPDATE surat SET pesan_sms='" . $out . "' where no_surat='" . $nosurat . "'";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            echo '{"result": "Internet OFF, Berhasil Kirim Notifikasi SMS"}';
        } else {
            $sql = "UPDATE surat SET pesan_sms='pending' where no_surat='" . $nosurat . "'";
            $stmt = $db->prepare($sql);
            $stmt->execute();
        }
    } else {
        die('{"result": "Gagal Kirim SMS"}');
    }
}

function sendEmailEdit($paramSubject, $receiver, $output, $paramLampiran, $paramNamaInstitusi) {
//    include 'PHPMailer/PHPMailerAutoload.php';
//$db, $no_surat
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
    $mail->SetFrom("notifion.info");
    $mail->Subject = $paramSubject;
    if ($paramLampiran > 0) {
        $mail->Body = "Surat dari " . $paramNamaInstitusi . " Mengenai " . $paramSubject . " sudah diperbaiki dan menunggu untuk di validasi.<br/>Terdapat " . $paramLampiran . " Lampiran, Untuk Mengecek Lampiran, silahkan kunjungi site notifion";
    } else {
        $mail->Body = "Surat dari " . $paramNamaInstitusi . " Mengenai " . $paramSubject . " sudah diperbaiki dan menunggu untuk di validasi.";
    }
    $email = $receiver;
    $mail->addStringAttachment($output, $paramSubject . '.pdf');
//    if ($paramLampiran > 0) {
//        for ($i = 0; $i < $paramLampiran; $i++) {
//            $path = json_decode(getFileLampiran($no_surat, $db));
//            for ($i = 0; $i < count($path); $i++) {
//                $mail->addAttachment($path[$i]->file_path);
//            }
//        }
//    }

    $mail->AddAddress($email);
    if (!$mail->Send()) {
//        echo "GAGAL KIRIM EMAIL";
        //header("refresh: 0;url=index.php");
        $mail->ErrorInfo;
    } else {
//        echo "BERHASIL KIRIM EMAIL";
        //header("refresh: 0;url=index.php");
    }
}

function sendEmailEditUploaded($paramSubject, $receiver, $output, $paramLampiran, $paramNamaInstitusi) {
//    include 'PHPMailer/PHPMailerAutoload.php';

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
    $mail->SetFrom("notifion.info");
    $mail->Subject = $paramSubject;
    if ($paramLampiran > 0) {
        $mail->Body = "Surat dari " . $paramNamaInstitusi . " Mengenai " . $paramSubject . " sudah diperbaiki dan menunggu untuk di validasi.<br/>Terdapat " . $paramLampiran . " Lampiran, Untuk Mengecek Lampiran, silahkan kunjungi site notifion";
    } else {
        $mail->Body = "Surat dari " . $paramNamaInstitusi . " Mengenai " . $paramSubject . " sudah diperbaiki dan menunggu untuk di validasi.";
    }
    $email = $receiver;
    $mail->addAttachment($output);
    $mail->AddAddress($email);
    if (!$mail->Send()) {
//        echo "GAGAL KIRIM EMAIL";
        //header("refresh: 0;url=index.php");
        $mail->ErrorInfo;
    } else {
//        echo "BERHASIL KIRIM EMAIL";
        //header("refresh: 0;url=index.php");
    }
}
