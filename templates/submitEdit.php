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
        $paramLampiran = $req['lampiran'];
        $paramNoSurat = $req['nosurat'];
        $paramHal = $req['hal'];

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
        $tanggal_surat = date('Y-m-d', strtotime($paramTanggalSurat));

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
        
        
    } else {
        echo '{"result": "Token tidak valid"}';
    }
    $db = null;
}
