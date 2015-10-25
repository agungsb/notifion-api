<?php

function getSurat() {
    global $app;
    $db = getDB();

    $req = json_decode($app->request()->getBody(), TRUE);

    $paramNoSurat = $req['no_surat'];
    $paramToken = $req['token'];

    $decode = JWT::decode($paramToken, TK);
    $id_institusi = $decode->id_institusi;
}
