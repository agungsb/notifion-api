<?php

function saveAttachments() {
    global $app;

    $req = $app->request->post();

    echo count($_FILES);

    for ($i = 0; $i < count($_FILES); $i++) {

        $destination = 'assets/' . $_FILES[$i]['name'];
        move_uploaded_file($_FILES[$i]['tmp_name'], $destination);
    }
}
