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

        try {
            if ($stmt->execute()) { // Jika berhasil meng-update surat
                // Hapus surat dari tabel surat_koreksi
                if (!HapusSuratKoreksi($db, $nosurat)) {
                    die('{"result": "Gagal menghapus surat koreksi"}');
                }

                // Jika ada lampiran lama yang dihapus oleh user
                if ($req['totalRemovedOldAttachments'] > 0) {
                    // Hapus lampiran-lampirannya surat dari tabel surat_lampiran
                    $removedOldAttachments = json_decode($req['removedOldAttachments']);
                    for ($i = 0; $i < count($removedOldAttachments); $i++) {
                        echo $removedOldAttachments[$i]->id_lampiran;
                        if (!HapusSuratAttachmentKoreksi($db, $removedOldAttachments[$i]->id_lampiran)) {
                            die('{"result": "Gagal menghapus lampiran surat koreksi"}');
                        }
                    }
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

                $registration_ids = array();
                if ((pushNotification($db, $penandatangan)) != null) {
                    $registration_ids = pushNotification($db, $penandatangan);
                }

                $gcm = new GCM();
                $pesan = array("message" => $paramSubject, "title" => "Surat keluar untuk $paramNamaInstitusi", "msgcnt" => 1, "sound" => "beep.wav");
                $result = $gcm->send_notification($registration_ids, $pesan);
                echo '{"result": "success", "account": "' . $penandatangan . '"}';
            } else {
                echo '{"result": "Gagal mengeksekusi query"}';
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
