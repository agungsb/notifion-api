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

    if ($check['result']) {
        echo '{"result": true, "data": ' . json_encode($check['data']) . ', "file_lampiran": ' . json_encode(getLampiranFilePath($paramNoSurat)) . '}';
    } else {
        echo '{"result": false}';
    }
}

function checkEditorCredential($db, $no_surat, $id_institusi) {
    $output = array();
    $stmt = $db->prepare("SELECT surat.id_surat, surat.subject_surat, surat.nama_surat, surat.no_surat, surat.jenis,"
            . "surat.hal, surat.isi, surat.kode_hal, surat.kode_lembaga_pengirim, surat.penandatangan, surat.tujuan,"
            . "surat.lampiran, surat.tembusan, surat.tanggal_surat FROM `surat`, `surat_koreksi` WHERE surat_koreksi.no_surat = :no_surat AND surat_koreksi.no_surat = surat.no_surat AND surat.kode_lembaga_pengirim = :id_institusi");
    $stmt->bindValue(':no_surat', $no_surat);
    $stmt->bindValue(':id_institusi', $id_institusi);
    try {
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            $output = array("result" => true, "data" => $stmt->fetch(PDO::FETCH_OBJ));
        } else {
            $output = array("result" => false);
        }
    } catch (PDOException $ex) {
        $output = array("result" => false);
    }
    return $output;
}
