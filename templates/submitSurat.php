<?php

function submitSurat() {
    die();
    $db = getDB();
    global $app;
//    $req = json_decode($app->request()->getBody(), true);
    $req = $app->request->post();

    $paramToken = $req['token'];

    $decode = JWT::decode($paramToken, TK);
    if ($decode->valid) {
        $paramSubject = $req['subject'];
        $paramIdInstitusi = $decode->id_institusi;
        $paramNamaInstitusi = $decode->nama_institusi;
        $paramHal = $req['hal'];
        $paramLampiran = count($_FILES);

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

        $paramIsi = str_replace('<span style="color: rgba(0, 0, 0, 0.870588);float: none;background-color: rgb(255, 255, 255);">', '', $req['isi']);
        $paramTanggalSurat = $req['tanggal_surat'];

        $timezone_identifier = "Asia/Jakarta";
        date_default_timezone_set($timezone_identifier);
        $tanggal_surat = date('Y-m-d', strtotime($paramTanggalSurat));

        $nosurat = checkCounter($db, $paramIdInstitusi, false) . "/UN39." . getKodeUnit($db, $paramIdInstitusi) . "/" . $paramHal . "/" . date('y');

        $query = "INSERT INTO `surat`(subject_surat, tujuan, kode_lembaga_pengirim, penandatangan, no_surat, lampiran, kode_hal, isi, tembusan, tanggal_surat) VALUES(:subject_surat, :tujuan, :id_institusi, :penandatangan, :nosurat, :lampiran, :hal, :isi, :tembusan, :tanggal_surat)";

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
                $tembusan .= $paramTembusan[$i]['identifier'] . "@+id/";
            }
            $tembusan .= $paramTembusan[$i]['identifier'];
            $stmt->bindValue(":tembusan", $tembusan);
        } else {
            $stmt->bindValue(":tembusan", "");
        }
        $stmt->bindValue(":tanggal_surat", $tanggal_surat);
        try {
            if ($stmt->execute()) {
                // Setelah berhasil mengeksekusi query, upload file ke folder yang telah ditentukan
                for ($i = 0; $i < $paramLampiran; $i++) {
                    $destination = 'assets/' . $_FILES[$i]['name'];
                    move_uploaded_file($_FILES[$i]['tmp_name'], $destination);
                }
                $registration_ids = array();
                if ((pushNotification($db, $penandatangan)) != null) {
                    $registration_ids = pushNotification($db, $penandatangan);
                }

                $gcm = new GCM();
                $pesan = array("message" => $paramSubject, "title" => "Surat keluar untuk $paramNamaInstitusi", "msgcnt" => 1, "sound" => "beep.wav");
                $result = $gcm->send_notification($registration_ids, $pesan);
                echo '{"result": "success"}';
            } else {
                echo '{"result": "error"}';
            }
        } catch (PDOException $ex) {
//            echo $ex->getMessage();
            echo '{"result": "error"}';
        }
    } else {
        echo '{"result": "error"}';
    }
    $db = null;
}
