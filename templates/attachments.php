<?php

function saveAttachments() {
    global $app;

    $req = $app->request->post();
    
    echo $is_uploaded = $req['is_uploaded'];

    print_r($_FILES);
    

    $destination = 'assets/uploaded/' . $_FILES['isi']['name'];
    move_uploaded_file($_FILES['isi']['tmp_name'], $destination);

//    $req = json_decode($app->request->getBody(), true);
//
//    $token = $req['token'];
//
//    $decode = JWT::decode($token, TK);
//
//    $account = $decode->account;
}
