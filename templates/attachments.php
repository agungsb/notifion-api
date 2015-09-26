<?php

function saveAttachments() {
    global $app;

    $req = $app->request->post();

    echo count($_FILES);

//    echo count($_FILES);
    
    $destination = 'attachments/' . $filename;
    move_uploaded_file($_FILES['file']['tmp_name'], $destination);

//    $req = json_decode($app->request->getBody(), true);
//
    $token = $req['token'];
//
//    $decode = JWT::decode($token, TK);
//
//    $account = $decode->account;
}
