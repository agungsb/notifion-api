<?php

/**
 * Step 1: Require the Slim Framework
 *
 * If you are not using Composer, you need to require the
 * Slim Framework and register its PSR-0 autoloader.
 *
 * If you are using Composer, you can skip this step.
 */
// require 'Slim/Slim.php';
// \Slim\Slim::registerAutoloader();

/**
 * Step 2: Instantiate a Slim application
 *
 * This example instantiates a Slim application using
 * its default settings. However, you will usually configure
 * your Slim application now by passing an associative array
 * of setting names and values into the application constructor.
 */
require 'config.php';
require 'db.php';
require 'JWT.php';
require 'templates/home.php';
require 'templates/createAttach.php';
require 'templates/preview.php';
require 'templates/view.php';

include_once 'GCM.php';

require __DIR__ . '/vendor/autoload.php';
$app = new \Slim\Slim();

/**
 * Step 3: Define the Slim application routes
 *
 * Here we define several Slim application routes that respond
 * to appropriate HTTP request methods. In this example, the second
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`
 * is an anonymous function.
 */
// GET route
$app->get('/', 'home');

// POST route
$app->post('/post', function () {
    echo 'This is a POST route';
}
);

// PUT route
$app->put('/put', function () {
    echo 'This is a PUT route';
}
);

// PATCH route
$app->patch('/patch', function () {
    echo 'This is a PATCH route';
});

// DELETE route
$app->delete('/delete', function () {
    echo 'This is a DELETE route';
}
);

$app->get('/users', 'getAllUsers');
$app->get('/tujuan', 'getTujuan');
$app->get('/penandatangan/:token', 'getPenandatangan');
$app->get('/user/:token', 'getUser');
$app->get('/surats/:token/:offset/:limit', 'getAllSurats');
$app->get('/suratsKeluar/:token/:offset/:limit', 'getAllSuratsKeluar');
$app->get('/suratsDraft/:token/:offset/:limit', 'getAllSuratsDraft');
$app->get('/favorites/:token/:offset/:limit', 'getAllFavorites');
$app->get('/pejabats', 'getAllPejabats');
$app->post('/preview', 'previewSurat');
$app->get('/view/:id/:token', 'viewSurat');
$app->post('/login', 'authLogin');
$app->post('/registerGCMUser', 'registerGCMUser');
$app->post('/unregisterGCMUser', 'unregisterGCMUser');
$app->post('/submitSurat', 'submitSurat');
$app->post('/kirimEmail', 'kirimEmail');
$app->put('/accSurat', 'accSurat');
$app->put('/rejectSurat', 'rejectSurat');
$app->put('/setFavorite', 'setFavorite');
$app->put('/setRead', 'setRead');

/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();

function getAllUsers() {
    $sql = "SELECT users.*, jabatan.* FROM users, jabatan";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $output = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($output);
    } catch (PDOException $e) {
//error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
        echo "{'error':{text':'" . $e->getMessage() . "'}}";
    }
}

function getTujuan() {
    $sql = "SELECT users.* FROM users";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $i = 0;
        while ($row = $stmt->fetch()) {
            $output[$i] = array("deskripsi" => $row['nama'], "identifier" => $row['account'], "keterangan" => "Dosen");
            $i++;
        }
        $query = "SELECT jabatan.*, institusi.nama_institusi FROM jabatan, institusi WHERE jabatan.id_jabatan != '000000000' AND institusi.id_institusi = jabatan.id_institusi";
        $stmt2 = $db->prepare($query);
        $stmt2->execute();
        while ($row = $stmt2->fetch()) {
            $output[$i] = array("deskripsi" => $row['jabatan'], "identifier" => $row['id_jabatan'], "keterangan" => $row['nama_institusi']);
            $i++;
        }
        $db = null;
        echo json_encode($output);
    } catch (PDOException $e) {
//error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
        echo "{'error':{text':'" . $e->getMessage() . "'}}";
    }
}

function getPenandatangan($token) {

    $decode = JWT::decode($token, TK);
    $id_institusi = $decode->id_institusi;

    try {
        $db = getDB();
        $query = "SELECT jabatan.*, institusi.nama_institusi FROM jabatan, institusi WHERE jabatan.id_jabatan != '000000000' AND institusi.id_institusi = :id_institusi AND institusi.id_institusi = jabatan.id_institusi";
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id_institusi", $id_institusi);
        $stmt->execute();
        $i = 0;
        while ($row = $stmt->fetch()) {
            $output[$i] = array("deskripsi" => $row['jabatan'], "identifier" => $row['id_jabatan'], "keterangan" => $row['nama_institusi']);
            $i++;
        }
        $db = null;
        echo json_encode($output);
    } catch (PDOException $e) {
//error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
        echo "{'error':{text':'" . $e->getMessage() . "'}}";
    }
}

function getAllSurats($token, $offset, $limit) {
    $db = getDB();
    $decode = JWT::decode($token, TK);
    $account = $decode->account;
    $id_jabatan = $decode->id_jabatan;

//    if ($jabatan->status) {
    $query = "SELECT surat.*, surat_terdistribusi.*, institusi.nama_institusi, surat_kode_hal.deskripsi FROM `surat_terdistribusi`, `surat`, `institusi`, `surat_kode_hal` WHERE (surat_terdistribusi.penerima=:account or surat_terdistribusi.penerima=:idJabatan) AND surat_terdistribusi.id_surat = surat.id_surat AND surat.kode_lembaga_pengirim = institusi.id_institusi AND surat.ditandatangani = '1' AND surat_kode_hal.kode_hal = surat.kode_hal ORDER BY surat.tanggal_surat DESC LIMIT :limit OFFSET :offset";
//    } else {
//        $query = "SELECT surat.*, surat_terdistribusi.*, operator.*, institusi.* FROM `surat_terdistribusi`, `surat`, `operator`, `institusi` WHERE surat_terdistribusi.penerima=:account AND surat_terdistribusi.id_surat = surat.id_surat AND surat.id_operator = operator.id_operator AND operator.id_institusi = institusi.id_institusi ORDER BY surat.created DESC LIMIT :limit OFFSET :offset";
//    }
    $stmt = $db->prepare($query);
    $stmt->bindValue(":account", $account);
    $stmt->bindValue(":idJabatan", $id_jabatan);
    $stmt->bindValue(":limit", (int) $limit, PDO::PARAM_INT);
    $stmt->bindValue(":offset", (int) $offset, PDO::PARAM_INT);
    $stmt->execute();
    $i = 0;
    if ($stmt->rowCount() > 0) {
        $i = 0;
        while ($row = $stmt->fetch()) {
            if ($row['penerima'] == $id_jabatan) {
                $role = $id_jabatan;
            } else {
                $role = $account;
            }
            $output[$i] = array("pengirim" => $row['nama_institusi'], "id" => $row['id'], "hal" => $row['deskripsi'], "subject" => $row['subject_surat'], "role" => $role, "notif_web" => filter_var($row['notif_web'], FILTER_VALIDATE_BOOLEAN), "notif_app" => filter_var($row['notif_app'], FILTER_VALIDATE_BOOLEAN), "isFavorite" => filter_var($row['isFavorite'], FILTER_VALIDATE_BOOLEAN), "isUnread" => filter_var($row['isUnread'], FILTER_VALIDATE_BOOLEAN), "no_surat" => $row['no_surat'], "lampiran" => $row['lampiran'], "namaPenandatangan" => getAccountName($db, $row['penandatangan']), "jabatanPenandatangan" => getJabatan($db, $row['penandatangan']), "tanggal" => convertDate($row['tanggal_surat']), "isi" => $row['isi'], "tembusan" => listTembusan($db, $row['tembusan']));
            $i++;
        }
    } else {
        $output = [];
    }
    echo '{"count": ' . $stmt->rowCount() . ', "isUnreads": ' . countUnreads($token) . ', "isFavorites": ' . countFavorites($token) . ', "isUnsigned": ' . countUnsigned($token) . ', "result": ' . json_encode($output) . '}';
}

function getAllSuratsKeluar($token, $offset, $limit) {
    $db = getDB();
    $decode = JWT::decode($token, TK);
    $account = $decode->account;
    $id_jabatan = $decode->id_jabatan;

    $query = "SELECT surat.*, institusi.nama_institusi, surat_kode_hal.deskripsi FROM `surat`, `institusi`, `surat_kode_hal` WHERE (surat.penandatangan=:account or surat.penandatangan=:idJabatan) AND surat.ditandatangani !='2' AND surat.kode_lembaga_pengirim = institusi.id_institusi AND surat.kode_hal = surat_kode_hal.kode_hal ORDER BY surat.created DESC LIMIT :limit OFFSET :offset";

    $stmt = $db->prepare($query);
    $stmt->bindValue(":account", $account);
    $stmt->bindValue(":idJabatan", $id_jabatan);
    $stmt->bindValue(":limit", (int) $limit, PDO::PARAM_INT);
    $stmt->bindValue(":offset", (int) $offset, PDO::PARAM_INT);
    $stmt->execute();
    $i = 0;
    if ($stmt->rowCount() > 0) {
        $i = 0;
        while ($row = $stmt->fetch()) {
            $output[$i] = array("pengirim" => $row['nama_institusi'], "id" => $row['id_surat'], "hal" => $row['deskripsi'], "subject" => $row['subject_surat'], "no_surat" => $row['no_surat'], "lampiran" => $row['lampiran'], "ditandatangani" => (int) $row['ditandatangani'], "namaPenandatangan" => getAccountName($db, $row['penandatangan']), "jabatanPenandatangan" => getJabatan($db, $row['penandatangan']), "tanggal" => convertDate($row['tanggal_surat']), "isi" => $row['isi'], "tujuan" => listTujuan($db, $row['tujuan']), "tembusan" => listTembusan($db, $row['tembusan']));
            $i++;
        }
    } else {
        $output = [];
    }
    echo '{"count": ' . $stmt->rowCount() . ', "isUnreads": ' . countUnreads($token) . ', "isFavorites": ' . countFavorites($token) . ', "isUnsigned": ' . countUnsigned($token) . ', "result": ' . json_encode($output) . '}';
}

function getAllSuratsDraft($token, $offset, $limit) {
    $db = getDB();
    $decode = JWT::decode($token, TK);
    $account = $decode->account;
    $id_jabatan = $decode->id_jabatan;

    $query = "SELECT surat.*, institusi.nama_institusi, surat_kode_hal.deskripsi FROM `surat`, `institusi`, `surat_kode_hal` WHERE (surat.penandatangan=:account or surat.penandatangan=:idJabatan) AND surat.ditandatangani='2' AND surat.kode_lembaga_pengirim = institusi.id_institusi AND surat.kode_hal = surat_kode_hal.kode_hal ORDER BY surat.created DESC LIMIT :limit OFFSET :offset";

    $stmt = $db->prepare($query);
    $stmt->bindValue(":account", $account);
    $stmt->bindValue(":idJabatan", $id_jabatan);
    $stmt->bindValue(":limit", (int) $limit, PDO::PARAM_INT);
    $stmt->bindValue(":offset", (int) $offset, PDO::PARAM_INT);
    $stmt->execute();
    $i = 0;
    if ($stmt->rowCount() > 0) {
        $i = 0;
        while ($row = $stmt->fetch()) {
            $output[$i] = array("id" => $row['id_surat'], "subject" => $row['subject_surat'], "lampiran" => $row['lampiran'], "hal" => $row['deskripsi'], "pengirim" => $row['nama_institusi'], "tanggal" => convertDate($row['tanggal_surat']));
            $i++;
        }
    } else {
        $output = [];
    }
    echo '{"count": ' . $stmt->rowCount() . ', "isUnreads": ' . countUnreads($token) . ', "isFavorites": ' . countFavorites($token) . ', "isUnsigned": ' . countUnsigned($token) . ', "result": ' . json_encode($output) . '}';
}

function getAllFavorites($token, $offset, $limit) {
    $db = getDB();
    $decode = JWT::decode($token, TK);
    $account = $decode->account;
    $id_jabatan = $decode->id_jabatan;

//    if ($jabatan->status) {
    $query = "SELECT surat.*, surat_terdistribusi.*, institusi.nama_institusi, surat_kode_hal.deskripsi FROM `surat_terdistribusi`, `surat`, `institusi`, `surat_kode_hal` WHERE (surat_terdistribusi.penerima=:account or surat_terdistribusi.penerima=:idJabatan) AND surat_terdistribusi.id_surat = surat.id_surat AND surat.kode_lembaga_pengirim = institusi.id_institusi AND surat.ditandatangani = '1' AND surat_kode_hal.kode_hal = surat.kode_hal AND surat_terdistribusi.isFavorite = :isFavorite ORDER BY surat.created DESC LIMIT :limit OFFSET :offset";
//    } else {
//        $query = "SELECT surat.*, surat_terdistribusi.*, operator.*, institusi.* FROM `surat_terdistribusi`, `surat`, `operator`, `institusi` WHERE surat_terdistribusi.penerima=:account AND surat_terdistribusi.id_surat = surat.id_surat AND surat.id_operator = operator.id_operator AND operator.id_institusi = institusi.id_institusi AND surat_terdistribusi.is_favorite='1' ORDER BY surat.created DESC";
//    }
    $stmt = $db->prepare($query);
    $stmt->bindValue(":account", $account);
    $stmt->bindValue(":idJabatan", $id_jabatan);
    $stmt->bindValue(":limit", (int) $limit, PDO::PARAM_INT);
    $stmt->bindValue(":offset", (int) $offset, PDO::PARAM_INT);
    $stmt->bindValue(":isFavorite", 1, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $i = 0;
        while ($row = $stmt->fetch()) {
            if ($row['penerima'] == $id_jabatan) {
                $role = $id_jabatan;
            } else {
                $role = $account;
            }
//            $output[$i] = array("pengirim" => $row['nama_institusi'], "id" => $row['id'], "hal" => $row['deskripsi'], "subject" => $row['subject_surat'], "role" => $role, "notif_web" => filter_var($row['notif_web'], FILTER_VALIDATE_BOOLEAN), "notif_app" => filter_var($row['notif_app'], FILTER_VALIDATE_BOOLEAN), "isFavorite" => filter_var($row['isFavorite'], FILTER_VALIDATE_BOOLEAN), "isUnread" => filter_var($row['isUnread'], FILTER_VALIDATE_BOOLEAN), "tanggal" => convertDate($row['tanggal_surat']));
            $output[$i] = array("pengirim" => $row['nama_institusi'], "id" => $row['id'], "hal" => $row['deskripsi'], "subject" => $row['subject_surat'], "role" => $role, "notif_web" => filter_var($row['notif_web'], FILTER_VALIDATE_BOOLEAN), "notif_app" => filter_var($row['notif_app'], FILTER_VALIDATE_BOOLEAN), "isFavorite" => filter_var($row['isFavorite'], FILTER_VALIDATE_BOOLEAN), "isUnread" => filter_var($row['isUnread'], FILTER_VALIDATE_BOOLEAN), "no_surat" => $row['no_surat'], "lampiran" => $row['lampiran'], "namaPenandatangan" => getAccountName($db, $row['penandatangan']), "jabatanPenandatangan" => getJabatan($db, $row['penandatangan']), "tanggal" => convertDate($row['tanggal_surat']), "isi" => $row['isi'], "tembusan" => $row['tembusan']);
            $i++;
        }
    } else {
        $output = [];
    }
    echo '{"count": ' . $stmt->rowCount() . ', "isUnreads": ' . countUnreads($token) . ', "isFavorites": ' . countFavorites($token) . ', "isUnsigned": ' . countUnsigned($token) . ', "result": ' . json_encode($output) . '}';
}

function listTujuan($dbh, $string) {
    if ($string != "") {
        $arr = explode("@+id/", $string);
        $tempArr = array();
        for ($i = 0; $i < count($arr); $i++) {
            if (!empty($arr[$i])) {
                array_push($tempArr, identifyTujuan($dbh, $arr[$i]));
            }
        }
        $join = implode(", ", $tempArr);
    } else {
        $join = "Tidak Ada";
    }
    return $join;
}

function identifyTujuan($dbh, $params) {
    $result = "";
    $query = "SELECT users.nama FROM users WHERE users.account=:params";
    $stmt = $dbh->prepare($query);
    $stmt->bindValue(':params', $params);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch();
        $result = $row['nama'];
    } else {
        $query2 = "SELECT jabatan.jabatan FROM jabatan, institusi, users WHERE users.id_jabatan=:params AND users.id_jabatan = jabatan.id_jabatan AND jabatan.id_institusi = institusi.id_institusi";
        $stmt2 = $dbh->prepare($query2);
        $stmt2->bindValue(':params', $params);
        $stmt2->execute();
        $row2 = $stmt2->fetch();
        $result = $row2['jabatan'];
    }
    return $result;
}

function listTembusan($dbh, $string) {
    if ($string != "") {
        $arr = explode("@+id/", $string);
        $tempArr = array();
        for ($i = 0; $i < count($arr); $i++) {
            if (!empty($arr[$i])) {
                array_push($tempArr, getJabatan($dbh, $arr[$i]));
            }
        }
        $join = implode(", ", $tempArr);
    } else {
        $join = "Tidak Ada";
    }
    return $join;
}

function countFavorites($token) {

    $db = getDB();
    $decode = JWT::decode($token, TK);
    $account = $decode->account;
    $id_jabatan = $decode->id_jabatan;

//    if ($jabatan->status) {
    $query = "SELECT surat.*, surat_terdistribusi.*, institusi.nama_institusi, surat_kode_hal.deskripsi FROM `surat_terdistribusi`, `surat`, `institusi`, `surat_kode_hal` WHERE (surat_terdistribusi.penerima=:account or surat_terdistribusi.penerima=:idJabatan) AND surat_terdistribusi.id_surat = surat.id_surat AND surat.kode_lembaga_pengirim = institusi.id_institusi AND surat.ditandatangani = '1' AND surat_kode_hal.kode_hal = surat.kode_hal AND surat_terdistribusi.isFavorite = :isFavorite";
//    } else {
//        $query = "SELECT surat.*, surat_terdistribusi.*, operator.*, institusi.* FROM `surat_terdistribusi`, `surat`, `operator`, `institusi` WHERE surat_terdistribusi.penerima=:account AND surat_terdistribusi.id_surat = surat.id_surat AND surat.id_operator = operator.id_operator AND operator.id_institusi = institusi.id_institusi AND surat_terdistribusi.is_favorite='1' ORDER BY surat.created DESC";
//    }
    $stmt = $db->prepare($query);
    $stmt->bindValue(":account", $account);
    $stmt->bindValue(":idJabatan", $id_jabatan);
    $stmt->bindValue(":isFavorite", 1, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->rowCount();
}

function countUnreads($token) {
    $db = getDB();
    $decode = JWT::decode($token, TK);
    $account = $decode->account;
    $id_jabatan = $decode->id_jabatan;

//    if ($jabatan->status) {
    $query = "SELECT surat.*, surat_terdistribusi.*, institusi.nama_institusi, surat_kode_hal.deskripsi FROM `surat_terdistribusi`, `surat`, `institusi`, `surat_kode_hal` WHERE (surat_terdistribusi.penerima=:account or surat_terdistribusi.penerima=:idJabatan) AND surat_terdistribusi.id_surat = surat.id_surat AND surat.kode_lembaga_pengirim = institusi.id_institusi AND surat.ditandatangani = '1' AND surat_kode_hal.kode_hal = surat.kode_hal AND surat_terdistribusi.isUnread = 1";
//    } else {
//        $query = "SELECT surat.*, surat_terdistribusi.*, operator.*, institusi.* FROM `surat_terdistribusi`, `surat`, `operator`, `institusi` WHERE surat_terdistribusi.penerima=:account AND surat_terdistribusi.id_surat = surat.id_surat AND surat.id_operator = operator.id_operator AND operator.id_institusi = institusi.id_institusi ORDER BY surat.created DESC LIMIT :limit OFFSET :offset";
//    }
    $stmt = $db->prepare($query);
    $stmt->bindValue(":account", $account);
    $stmt->bindValue(":idJabatan", $id_jabatan);
    $stmt->execute();
    return $stmt->rowCount();
}

function countUnsigned($token) {
    $db = getDB();
    $decode = JWT::decode($token, TK);
    $account = $decode->account;
    $id_jabatan = $decode->id_jabatan;

    $query = "SELECT surat.* FROM `surat` WHERE (surat.penandatangan=:account or surat.penandatangan=:idJabatan) AND surat.ditandatangani = '0'";

    $stmt = $db->prepare($query);
    $stmt->bindValue(":account", $account);
    $stmt->bindValue(":idJabatan", $id_jabatan);
    $stmt->execute();
    return $stmt->rowCount();
}

function getUser($token) {
    $db = getDB();
    $decode = JWT::decode($token, TK);
    $account = $decode->account;
    $jabatan = json_decode(checkUsersJabatan($token));
    if ($jabatan->status) {
        
    }
    $query = "SELECT users.*, jabatan.jabatan FROM `users`, `jabatan` WHERE users.account=:account AND users.id_jabatan = jabatan.id_jabatan";
    try {
        $stmt = $db->prepare($query);
        $stmt->bindParam("account", $account);
        $stmt->execute();
        $output = $stmt->fetch();
        $db = null;
        echo json_encode($output);
    } catch (PDOException $e) {
//error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
        echo "{'error':{text':'" . $e->getMessage() . "'}}";
    }
}

function checkUsersJabatan($token) {
    $db = getDB();
    $decode = JWT::decode($token, TK);
    $account = $decode->account;
    $query = "SELECT pejabat.account, jabatan.id_jabatan, jabatan.jabatan FROM `pejabat`, `jabatan` WHERE pejabat.account=:account AND pejabat.id_jabatan = jabatan.id_jabatan";
    $stmt = $db->prepare($query);
    $stmt->bindParam("account", $account);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch();
        $output = array("status" => true, "account" => $row['account'], "id_jabatan" => $row['id_jabatan'], "jabatan" => $row['jabatan']);
    } else {
        $output = array('status' => false);
    }
    return json_encode($output);
}

function getAllPejabats() {
    $db = getDB();
    $query = "SELECT users.nama, users.account, jabatan.jabatan FROM users, instansi, institusi, jabatan, pejabat WHERE users.account = pejabat.account AND pejabat.id_jabatan = jabatan.id_jabatan AND jabatan.id_institusi = institusi.id_institusi AND institusi.id_instansi = instansi.id_instansi";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $i = 0;
    while ($row = $stmt->fetch()) {
        $output[$i] = array("nama" => $row['nama'], "account" => $row['account'], "jabatan" => $row['jabatan']);
        $i++;
    }
    echo json_encode($output);
}

function authLogin() {
    global $app;

    $req = json_decode($app->request()->getBody(), TRUE);

    $paramAccount = $req['account']; // Getting parameter with names
    $paramPassword = $req['password']; // Getting parameter with names

    $query = "SELECT users.user_id, users.account, users.nama, users.id_jabatan, jabatan.id_institusi, institusi.nama_institusi FROM `users`, `jabatan`, `institusi` WHERE users.account=:account AND users.password=:password AND users.id_jabatan=jabatan.id_jabatan AND jabatan.id_institusi=institusi.id_institusi";
    try {
        $db = getDB();
        $stmt = $db->prepare($query);
        $stmt->bindParam("account", $paramAccount);
        $stmt->bindParam("password", $paramPassword);
        $stmt->execute();
        $result = $stmt->fetch();
        $db = null;
        if ($stmt->rowCount() > 0) {
            $token['account'] = $result['account'];
            $token['nama'] = $result['nama'];
            $token['id_jabatan'] = $result['id_jabatan'];
            $token['id_institusi'] = $result['id_institusi'];
            $token['nama_institusi'] = $result['nama_institusi'];
            $token['valid'] = true;
            $encoded = JWT::encode($token, TK);
            $output = array('status' => true, 'token' => $encoded, 'userid' => $result['user_id'], 'account' => $result['account'], 'nama' => $result['nama'], 'jabatan' => $result['id_jabatan'], 'institusi' => $result['id_institusi'], 'nama_institusi' => $result['nama_institusi']);
        } else {
//            $output = array('status' => false, 'message' => $paramAccount . ' - ' . $paramPassword);
            $output = array('status' => false, 'header' => $_SERVER['CONTENT_TYPE'], 'message' => $paramAccount . ' - ' . $paramPassword);
        }
        echo json_encode($output);
    } catch (PDOException $e) {
        echo "{'error':{text':'" . $e->getMessage() . "'}}";
    }
}

function registerGCMUser() {
    global $app;

    $req = json_decode($app->request()->getBody(), TRUE);

    $gcm_regid = $req['gcm_regid'];
    $token = $req['token'];

    $decode = JWT::decode($token, TK);
    $account = $decode->account;

    if (verifyGCMUser($gcm_regid) == 0) {
        createGCMUser($gcm_regid, $account);
        $output = array("status" => true, "event" => "createGCMUser");
    } else {
//        updateGCMUser($gcm_regid, $account);
        $output = array("result" => "OK", "status" => "GCMUser is already exist");
    }
    echo json_encode($output);
}

function verifyGCMUser($gcm_regid) {
    $query = "SELECT * FROM `gcm_users` WHERE gcm_regid='$gcm_regid'";
    $db = getDB();
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->rowCount();
}

function createGCMUser($gcm_regid, $account) {
    $query = "INSERT INTO `gcm_users`(gcm_regid, account) VALUES('" . $gcm_regid . "', '" . $account . "')";
    $db = getDB();
    $stmt = $db->prepare($query);
    $stmt->execute();
}

function updateGCMUser($gcm_regid, $account) {
    $query = "UPDATE `gcm_users` SET account='$account' WHERE gcm_regid='$gcm_regid'";
    $db = getDB();
    $stmt = $db->prepare($query);
    $stmt->execute();
}

function unregisterGCMUser() {
    global $app;

    $req = json_decode($app->request()->getBody(), TRUE);

    $gcm_regid = $req['gcm_regid'];

    deleteGCMUser($gcm_regid);
    $output = array("status" => true);
    echo json_encode($output);
}

function deleteGCMUser($gcm_regid) {
    $query = "DELETE FROM `gcm_users` WHERE gcm_regid='$gcm_regid'";
    $db = getDB();
    $stmt = $db->prepare($query);
    $stmt->execute();
}

function submitSurat() {
    $db = getDB();
    global $app;
    $req = json_decode($app->request()->getBody(), true);

    $paramToken = $req['token'];

    $decode = JWT::decode($paramToken, TK);
    if ($decode->valid) {
        $paramSubject = $req['subject'];
        $paramTujuan = $req['tujuan'];
        $paramIdInstitusi = $decode->id_institusi;
        $paramNamaInstitusi = $decode->nama_institusi;
        $tujuan = "";
        for ($i = 0; $i < count($paramTujuan); $i++) {
            $tujuan .= $paramTujuan[$i]['identifier'] . "@+id/";
        }
        $paramPenandatangan = $req['penandatangan'];
        $penandatangan = "";
        for ($i = 0; $i < count($paramPenandatangan); $i++) {
//            $penandatangan .= $paramPenandatangan[$i]['identifier'] . "@+id/";
            $penandatangan = $paramPenandatangan[0]['identifier'];
        }
        $paramNosurat = $req['nosurat'];
        $paramLampiran = $req['lampiran'];
        $paramHal = $req['hal'];
        $paramIsi = $req['isi'];
        $paramTembusan = $req['tembusan'];
        $paramTanggalSurat = $req['tanggal_surat'];

        $timezone_identifier = "Asia/Jakarta";
        date_default_timezone_set($timezone_identifier);
        $tanggal_surat = date('Y-m-d', strtotime($paramTanggalSurat));

        $tembusan = "";
        for ($i = 0; $i < count($paramTembusan) - 1; $i++) {
            $tembusan .= $paramTembusan[$i]['identifier'] . "@+id/";
        }
        $tembusan .= $paramTembusan[$i]['identifier'];

        $query = "INSERT INTO `surat`(subject_surat, tujuan, kode_lembaga_pengirim, penandatangan, no_surat, lampiran, kode_hal, isi, tembusan, tanggal_surat) VALUES(:subject_surat, :tujuan, :id_institusi, :penandatangan, :nosurat, :lampiran, :hal, :isi, :tembusan, :tanggal_surat)";

        $stmt = $db->prepare($query);
        $stmt->bindValue(":subject_surat", $paramSubject);
        $stmt->bindValue(":tujuan", $tujuan);
        $stmt->bindValue(":id_institusi", $paramIdInstitusi);
        $stmt->bindValue(":penandatangan", $penandatangan);
        $stmt->bindValue(":nosurat", $paramNosurat);
        $stmt->bindValue(":lampiran", (int) $paramLampiran, PDO::PARAM_INT);
        $stmt->bindValue(":hal", $paramHal);
        $stmt->bindValue(":isi", $paramIsi);
        $stmt->bindValue(":tembusan", $tembusan);
        $stmt->bindValue(":tanggal_surat", $tanggal_surat);

        try {
//            $stmt->execute();
            
//            $registration_ids = array();
//            if ((pushNotification($db, $penandatangan)) != null) {
//                $registration_ids = pushNotification($db, $penandatangan);
//            }
//
//            $gcm = new GCM();
//            $pesan = array("message" => $paramSubject, "title" => "Surat keluar untuk $paramNamaInstitusi", "msgcnt" => 1, "sound" => "beep.wav");
//            $result = $gcm->send_notification($registration_ids, $pesan);

            createAttachment($paramSubject, $tujuan, $paramIdInstitusi, $penandatangan, $paramNosurat, (int) $paramLampiran, $paramHal, $paramIsi, $tembusan, $tanggal_surat);
            
            echo '{"result": "success"}';
        } catch (PDOException $ex) {
//            echo $ex->getMessage();
            echo '{"result": "error"}';
        }
    } else {
        echo '{"result": "error"}';
    }
}

function rejectSurat() {
    $db = getDB();
    global $app;
    $req = json_decode($app->request()->getBody(), true);

    $id_surat = $req['id_surat'];
    $token = $req['token'];

    $decode = JWT::decode($token, TK);
    $account = $decode->account;
    $id_jabatan = $decode->id_jabatan;

    $query = "SELECT surat.* FROM surat WHERE surat.id_surat=:id_surat AND (surat.penandatangan=:account OR surat.penandatangan=:id_jabatan)";
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id_surat", $id_surat);
    $stmt->bindValue(":account", $account);
    $stmt->bindValue(":id_jabatan", $id_jabatan);
    try {
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
//            echo $row['subject_surat'] . " - " . $row['tujuan'] . " - " . $row['tembusan'] . " - " . $nama_institusi;
            sendToDraft($db, $token, $id_surat, $account, $id_jabatan);
        } else {
            echo '{"error": "Action not granted"}';
        }
        $db = null;
    } catch (PDOException $ex) {
        echo '{"error": "' . $ex->getMessage() . '"}';
    }
}

function sendToDraft($db, $token, $id_surat, $account, $id_jabatan) {
    $query = "UPDATE `surat` SET surat.ditandatangani='2' WHERE surat.id_surat=:id_surat AND (surat.penandatangan=:account OR surat.penandatangan=:id_jabatan)";
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id_surat", $id_surat);
    $stmt->bindValue(":account", $account);
    $stmt->bindValue(":id_jabatan", $id_jabatan);
    try {
        $stmt->execute();
        $db = null;
//        echo '{"result": "' . $id_surat . '"}';
        echo '{"isUnreads": ' . countUnreads($token) . ', "isFavorites": ' . countFavorites($token) . ', "isUnsigned": ' . countUnsigned($token) . ', "result": ' . $id_surat . '}';
    } catch (PDOException $ex) {
        echo '{"error": "' . $ex->getMessage() . '"}';
    }
}

function accSurat() {
    $db = getDB();
    global $app;
    $req = json_decode($app->request()->getBody(), true);

    $id_surat = $req['id_surat'];
    $token = $req['token'];

    $decode = JWT::decode($token, TK);
    $account = $decode->account;
    $id_jabatan = $decode->id_jabatan;
    $nama_institusi = $decode->nama_institusi;

    $query = "SELECT surat.* FROM surat WHERE surat.id_surat=:id_surat AND (surat.penandatangan=:account OR surat.penandatangan=:id_jabatan)";
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id_surat", $id_surat);
    $stmt->bindValue(":account", $account);
    $stmt->bindValue(":id_jabatan", $id_jabatan);
    try {
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch();
//            echo $row['subject_surat'] . " - " . $row['tujuan'] . " - " . $row['tembusan'] . " - " . $nama_institusi;
            updateTandatangan($db, $token, $id_surat, $row['subject_surat'], $account, $id_jabatan, $row['tujuan'], $row['tembusan'], $nama_institusi);
        } else {
            echo '{"error": "Action not granted"}';
        }
        $db = null;
    } catch (PDOException $ex) {
        echo '{"error": "' . $ex->getMessage() . '"}';
    }
}

function setFavorite() {
    global $app;
    $req = json_decode($app->request()->getBody(), true);

    $paramToken = $req['token'];
    $paramId = $req['id'];
    $paramStatus = $req['status'];
    if ($paramStatus) {
        $paramStatus = 1;
    } else {
        $paramStatus = 0;
    }

    $query = "UPDATE `surat_terdistribusi` SET isFavorite=:isFavorite WHERE id=:id";
    try {
        $db = getDB();
        $stmt = $db->prepare($query);
        $stmt->bindParam("isFavorite", $paramStatus);
        $stmt->bindParam("id", $paramId);
        $stmt->execute();
        $db = null;
        $output = array('status' => true);
        echo '{"isFavorites": ' . countFavorites($paramToken) . ', "result": ' . json_encode($output) . '}';
    } catch (PDOException $ex) {
        echo "{'error':{text':'" . $ex->getMessage() . "'}}";
    }
}

function setRead() {
    global $app;
    $req = json_decode($app->request()->getBody(), true);

    $paramToken = $req['token'];

    $paramId = $req['id'];

    $query = "UPDATE `surat_terdistribusi` SET isUnread='0' WHERE id=:id";
    try {
        $db = getDB();
        $stmt = $db->prepare($query);
        $stmt->bindParam("id", $paramId);
        $stmt->execute();
        $db = null;
        $output = array('status' => true);
        echo '{"isUnreads": ' . countUnreads($paramToken) . ', "result": ' . json_encode($output) . '}';
    } catch (PDOException $ex) {
        echo "{'error':{text':'" . $ex->getMessage() . "'}}";
    }
}

function updateTandatangan($db, $token, $id_surat, $subject, $account, $id_jabatan, $tujuan, $tembusan, $nama_institusi) {
    $query = "UPDATE `surat` SET surat.ditandatangani='1' WHERE surat.id_surat=:id_surat AND (surat.penandatangan=:account OR surat.penandatangan=:id_jabatan)";
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id_surat", $id_surat);
    $stmt->bindValue(":account", $account);
    $stmt->bindValue(":id_jabatan", $id_jabatan);
    try {
        if ($stmt->execute()) {
            distribusiSurat($db, $token, $id_surat, $subject, $tujuan, $tembusan, $nama_institusi);
        }
        $db = null;
    } catch (PDOException $ex) {
        echo '{"error": "' . $ex->getMessage() . '"}';
    }
}

function distribusiSurat($db, $token, $id_surat, $subject, $tu, $tembusan, $nama_institusi) {
    $tujuan = explode("@+id/", $tu); // explode dulu tujuannya

    $registration_ids = array();

    for ($i = 0; $i < count($tujuan); $i++) {
        if (!empty($tujuan[$i])) {
//            echo $id_surat . " - " . $tujuan[$i] . " - " . $tembusan . " <br/>";
            kirimSurat($db, $id_surat, $tujuan[$i], $tembusan);
            if ((pushNotification($db, $tujuan[$i])) != null) {
                $registration_ids = pushNotification($db, $tujuan[$i]);
            }
        }
    }
    if (count($registration_ids) > 0) {
        $gcm = new GCM();
        $pesan = array("message" => $subject, "title" => "Surat baru dari $nama_institusi", "msgcnt" => 1, "sound" => "beep.wav");
        $result = $gcm->send_notification($registration_ids, $pesan);
    } else {
        $result = '"Not a GCM User"';
    }
    echo '{"isUnreads": ' . countUnreads($token) . ', "isFavorites": ' . countFavorites($token) . ', "isUnsigned": ' . countUnsigned($token) . ', "result": ' . $result . '}';
//    echo $result;
}

function kirimSurat($db, $id_surat, $tujuan, $tembusan) {
    $query = "INSERT INTO `surat_terdistribusi`(`id_surat`, `penerima`, `isUnread`) VALUES (:id_surat, :tujuan, 1)";
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id_surat", $id_surat);
    $stmt->bindValue(":tujuan", $tujuan);
//    $stmt->bindValue(":tembusan", $tembusan);

    try {
        if ($stmt->execute()) {
//            echo '{"result": "OK"}';
        }
        $db = null;
    } catch (PDOException $ex) {
        echo '{"error": "' . $ex->getMessage() . '"}';
    }
}

function pushNotification($db, $tujuan) {

    $query = "SELECT users.*, gcm_users.* FROM users, gcm_users WHERE (users.account = :tujuan OR users.id_jabatan = :tujuan) AND users.account = gcm_users.account";
    $stmt = $db->prepare($query);
    $stmt->bindValue(":tujuan", $tujuan);
    try {
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $output = array();
//            $pesan = array("message" => $subject, "title" => "Surat baru dari $nama_institusi", "msgcnt" => 1, "sound" => "beep.wav");
//                $registration_ids = array($row['gcm_regid']);
//                array_push($registration_ids, $row['gcm_regid']);
//                $result = $gcm->send_notification($registration_ids, $pesan);
//                echo $result;
//            return $row['gcm_regid'];
            while ($row = $stmt->fetch()) {
                array_push($output, $row['gcm_regid']);
            }
            return $output;
        }

        $db = null;
    } catch (PDOException $ex) {
        echo '{"error": "' . $ex->getMessage() . '"}';
    }
}

function convertTimestamp($timestamp) {
    $exp = explode(" ", $timestamp);
    $expDate = explode("-", $exp[0]);
    $stringifyDate = $expDate[2] . " " . listBulan($expDate[1]) . " " . $expDate[0];
    $stringifyDate .= ", " . $exp[1];
    return $stringifyDate;
}

function convertDate($date) {
    $expDate = explode("-", $date);
    $stringifyDate = $expDate[2] . " " . listBulan($expDate[1]) . " " . $expDate[0];
    return $stringifyDate;
}

function listBulan($index) {
    $bulan = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
    return $bulan[$index - 1];
}

function kirimEmail() {
//    echo 'Kirim Email';
    require_once 'PHPMailer/PHPMailerAutoload.php';
    require_once('tcpdf/tcpdf.php');

    global $app;
    $req = json_decode($app->request()->getBody(), true);

//    $paramSender = $req['sender'];
    $paramReceiver = $req['receiver'];
    $paramSubject = $req['subject_surat'];

    $nama_file = $paramSubject . '.pdf';
    $pdf->Output('$nama_file', 'I');
    echo $paramSubject . "-" . $paramReceiver;

    $mail = new PHPMailer(); // create a new object
    $mail->IsSMTP(); // enable SMTP
    //$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
    $mail->SMTPAuth = true; // authentication enabled
    $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 465; // or 587
    $mail->IsHTML(true);
    $mail->Username = "firdaus.ibnuu@gmail.com";
    $mail->Password = "firdausibnu21";
    $mail->SetFrom("PDF");
    $mail->Subject = "$paramSubject";
    $mail->Body = "Test PDF.";
//$email = 'akbar.kusuma@zentum-intizhara.com';
    $email = $paramReceiver;
    $mail->addStringAttachment($output, $nama_file);
    $mail->AddAddress($email);
    if (!$mail->Send()) {
        echo "<script type='text/javascript'>alert('GAGAL MENGIRIM EMAIL.');</script>";
        //header("refresh: 0;url=index.php");
        $mail->ErrorInfo;
    } else {
        echo "<script type='text/javascript'>alert('BERHASIL MENGIRIM EMAIL.');</script>";
        //header("refresh: 0;url=index.php");
    }
}
