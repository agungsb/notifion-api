<?php

function authSurat() {
    global $app;
    $db = getDB();

    $req = json_decode($app->request()->getBody(), TRUE);

    $paramNoSurat = $req['no_surat'];
    $paramToken = $req['token'];

    $decode = JWT::decode($paramToken, TK);
    $id_institusi = $decode->id_institusi;

    $check = checkEditorCredential($db, $paramNoSurat, $id_institusi);
    if ($check->rowCount() == 1) {
        $data = $check->fetch(PDO::FETCH_OBJ);
//        print_r($data);
//        echo $data->is_uploaded;
        if ($data->is_uploaded == 'true') {
            echo '{"result": true, "data": ' . json_encode($data) . ', "file_path": "' . getSuratFilePath($db, $data->no_surat) . '", "file_lampiran": ' . json_encode(getLampiranFilePath($paramNoSurat)) . '}';
        } else if ($data->is_uploaded == 'false') {
            echo '{"result": true, "data": ' . json_encode($data) . ', "file_lampiran": ' . json_encode(getLampiranFilePath($paramNoSurat)) . '}';
        }
    } else {
        echo '{"result": false}';
    }

//    if ($check['result']) {
//        $data = $check['data'];
//        echo '{"result": true, "data": ' . json_encode($data) . ', "file_lampiran": ' . json_encode(getLampiranFilePath($paramNoSurat)) . '}';
//    } else {
//        echo '{"result": false}';
//    }
}

function checkEditorCredential($db, $no_surat, $id_institusi) {
    $output = array();
//    $stmt = $db->prepare("SELECT surat_uploaded.file_path, surat.id_surat, surat.subject_surat, surat.nama_surat, surat.no_surat, surat.jenis,"
//            . "surat.hal, surat.isi, surat.kode_hal, surat.kode_lembaga_pengirim, surat.penandatangan, surat.tujuan,"
//            . "surat.lampiran, surat.tembusan, surat.tanggal_surat, surat.is_uploaded FROM `surat`, `surat_koreksi`, `surat_uploaded` WHERE surat_koreksi.no_surat = :no_surat AND surat_koreksi.no_surat = surat.no_surat AND surat.kode_lembaga_pengirim = :id_institusi AND surat_uploaded.no_surat = surat.no_surat");
    $stmt = $db->prepare("SELECT surat.id_surat, surat.subject_surat, surat.nama_surat, surat.no_surat, surat.jenis,"
            . "surat.hal, surat.isi, surat.kode_hal, surat.kode_lembaga_pengirim, surat.penandatangan, surat.tujuan,"
            . "surat.lampiran, surat.tembusan, surat.tanggal_surat, surat.is_uploaded FROM `surat`, `surat_koreksi` WHERE surat_koreksi.no_surat = :no_surat AND surat_koreksi.no_surat = surat.no_surat AND surat.kode_lembaga_pengirim = :id_institusi");
    $stmt->bindValue(':no_surat', $no_surat);
    $stmt->bindValue(':id_institusi', $id_institusi);
    try {
        $stmt->execute();
        return $stmt;
    } catch (PDOException $ex) {
        $output = array("result" => false);
    }
}

function getSuratFilePath($db, $no_surat) {
    $filepath = "";
    $stmt = $db->prepare("SELECT surat_uploaded.file_path FROM `surat`, `surat_uploaded` WHERE surat.no_surat = :no_surat AND surat.no_surat = surat_uploaded.no_surat");
    try {
        $stmt->bindValue(':no_surat', $no_surat);
        $stmt->execute();
        $row = $stmt->fetch();
        $filepath = $row['file_path'];
    } catch (PDOException $ex) {
        die($ex->getMessage());
    }
    return $filepath;
}
