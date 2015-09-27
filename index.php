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
require 'templates/submitSurat.php';
require 'templates/home.php';
require 'templates/preview.php';
require 'templates/preview2.php';
require 'templates/view.php';
require 'templates/attachments.php';

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
});

$app->get('/checkUserOp', 'checkUserOp'); //testing only
$app->get('/users', 'getAllUsers');
$app->get('/users2', 'getAllUsersOP');
$app->get('/tujuan', 'getTujuan');
$app->get('/penandatangan/:token', 'getPenandatangan');
$app->get('/user/:token', 'getUser');
$app->get('/surats/:token/:offset/:limit', 'getAllSurats');
$app->get('/suratsKeluar/:token/:offset/:limit', 'getAllSuratsKeluar');
$app->get('/suratsDraft/:token/:offset/:limit', 'getAllSuratsDraft');
$app->get('/favorites/:token/:offset/:limit', 'getAllFavorites');
$app->get('/pejabats', 'getAllPejabats');
$app->get('/kodeHals', 'getKodeHals');
$app->get('/kodeUnits', 'getKodeUnits2');
$app->get('/instansi', 'getInstansi');
$app->get('/checkIdInstansi', 'checkIdInstansi'); // testing only
$app->get('/checkUserJabatan', 'checkUserJabatan'); // testing only
$app->get('/institusi', 'getInstitusi');
$app->post('/preview', 'previewSurat');
$app->post('/preview2', 'preview2');
$app->get('/view/:id/:token', 'viewSurat');
$app->post('/login', 'authLogin');
$app->post('/registerGCMUser', 'registerGCMUser');
$app->post('/unregisterGCMUser', 'unregisterGCMUser');
$app->post('/submitSurat', 'submitSurat');
$app->post('/editBio', 'editBio');
$app->post('/addUserOp', 'addUserOp');
$app->post('/addInstansi', 'addInstansi');
$app->post('/addInstitusi', 'addInstitusi');
$app->post('/addKodeHal', 'addKodeHal');
$app->post('/addKodeUnit', 'addKodeUnit');
$app->post('/attachments', 'saveAttachments'); // testing only
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
        echo '{"result": ' . json_encode($output) . '}';
    } catch (PDOException $e) {
//error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
        echo "{'error':{text':'" . $e->getMessage() . "'}}";
    }
}

function getAllUsersOP() {
    $sql = "SELECT users.*, institusi.nama_institusi from users, institusi WHERE jenis_user='2' AND users.id_institusi=institusi.id_institusi";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $output = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"result": ' . json_encode($output) . '}';
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
        $query = "SELECT jabatan.*, institusi.nama_institusi, users.nip, users.nama FROM jabatan, institusi, users WHERE jabatan.id_jabatan != '000000000' AND institusi.id_institusi=:id_institusi AND institusi.id_institusi = jabatan.id_institusi AND jabatan.id_jabatan = users.id_jabatan";
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id_institusi", $id_institusi);
        $stmt->execute();
        $i = 0;
        while ($row = $stmt->fetch()) {
            $output[$i] = array("deskripsi" => $row['jabatan'], "identifier" => $row['id_jabatan'], "keterangan" => $row['nama_institusi'], "nip" => $row['nip'], "nama" => $row['nama']);
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
    $db = null;
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
    $db = null;
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
    $db = null;
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
    $db = null;
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
    $db = null;
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
    $db = null;
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
    $db = null;
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
    $db = null;
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
    $db = null;
    echo json_encode($output);
}

function getKodeHals() {
    $db = getDB();
    $query = "SELECT surat_kode_hal.* FROM surat_kode_hal";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $i = 0;
    while ($row = $stmt->fetch()) {
        $output[$i] = array("deskripsi" => $row['deskripsi'], "kode_hal" => $row['kode_hal']);
        $i++;
    }
    $db = null;
    echo '{"result": ' . json_encode($output) . '}';
//    echo json_encode($output);
}

function getInstansi() {

    $db = getDB();
    $query = "SELECT instansi.* FROM instansi WHERE id_instansi!='000'";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $i = 0;
    while ($row = $stmt->fetch()) {
        $output[$i] = array("id_instansi" => $row['id_instansi'], "nama_instansi" => $row['nama_instansi']);
        $i++;
    }
    $db = null;
    echo '{"result_Instansi": ' . json_encode($output) . '}';
}

function getInstitusi() {
    $db = getDB();
    $query = "SELECT * FROM institusi WHERE id_instansi !='000'";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $i = 0;
    while ($row = $stmt->fetch()) {
        $output[$i] = array("id_institusi" => $row['id_institusi'], "nama_institusi" => $row['nama_institusi'], "id_instansi" => $row['id_instansi']);
        $i++;
    }
    $db = null;
    echo '{"result": ' . json_encode($output) . '}';
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
        $db = null;
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
    $db = null;
    return $stmt->rowCount();
}

function createGCMUser($gcm_regid, $account) {
    $query = "INSERT INTO `gcm_users`(gcm_regid, account) VALUES('" . $gcm_regid . "', '" . $account . "')";
    $db = getDB();
    $stmt = $db->prepare($query);
    $stmt->execute();
    $db = null;
}

function updateGCMUser($gcm_regid, $account) {
    $query = "UPDATE `gcm_users` SET account='$account' WHERE gcm_regid='$gcm_regid'";
    $db = getDB();
    $stmt = $db->prepare($query);
    $stmt->execute();
    $db = null;
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
    $db = null;
}

function editBio() {
    $db = getDB();
    global $app;
    $req = json_decode($app->request()->getBody(), true);

    $token = $req['token'];
    $paramNama = $req['nama'];
    $paramGender = $req['gender'];
    $paramPassword = $req['password'];
    $paramNip = $req['nip'];
    $paramEmail1 = $req['email1'];
    $paramEmail2 = $req['email2'];
    $paramNohp1 = $req['nohp1'];
    $paramNohp2 = $req['nohp2'];


    $decode = JWT::decode($token, TK);
    $akun = $decode->account;

    $query = "UPDATE `users` SET nama=:nama, password=:password, gender=:jeniskelamin, nip=:nip, email1=:email1, email2=:email2, nohp1=:nohp1, nohp2=:nohp2 WHERE account=:account";
    $stmt = $db->prepare($query);
    $stmt->bindValue(":nama", $paramNama);
    $stmt->bindValue(":password", $paramPassword);
    $stmt->bindValue(":nip", $paramNip);
    $stmt->bindValue(":jeniskelamin", $paramGender);
    $stmt->bindValue(":email1", $paramEmail1);
    $stmt->bindValue(":email2", $paramEmail2);
    $stmt->bindValue(":nohp1", $paramNohp1);
    $stmt->bindValue(":nohp2", $paramNohp2);
    $stmt->bindValue(":account", $akun);

    $stmt->execute();
    $db = null;
    if ($stmt) {
        echo '{"result": "Success"}';
    } else {
        echo '{"result": "there is something wrong}';
    }
}

function addUserOp() {
    $db = getDB();
    global $app;
    $req = json_decode($app->request()->getBody(), true);

    $checkUserOp = json_decode(checkUserOp($db));

    $paramPassword = $req['password'];
    $paramInstitusi = $req['institusi'];

    $paramAccount = 'Operator' . $paramInstitusi;

    $tiga = $paramInstitusi . '000';

    for ($i = 0; $i < count($checkUserOp); $i++) { //pengecekan institusi yang belum ada operatornya
//        echo $checkUserOp[$i]->account;
        if ($paramInstitusi != $checkUserOp[$i]->id_institusi) {
            $check = 0;
        } else {
            $check = 1;
            break;
        }
    }

    if ($check == 0) {
        $query = "INSERT INTO users (account, password, id_institusi, jenis_user) VALUES (:account, :password, :id_institusi, '2')";
        $stmt = $db->prepare($query);
        $stmt->bindValue(":account", $paramAccount);
        $stmt->bindValue(":password", $paramPassword);
        $stmt->bindValue(":id_institusi", $paramInstitusi);
        if ($stmt->execute()) {
            $checkUserJab = json_decode(checkUserJabatan($db));
            for ($i = 0; $i < count($checkUserJab); $i++) { //pengecekan institusi yang belum ada id_jabatannya
                if ($checkUserJab[$i]->id_jabatan == "") {
//                    echo "kosong";
                    $check_jab = 0;
                    $paramNamaInstitusi = $checkUserJab[$i]->Nama_institusi;
//                    $paramIdInstitusi = $checkUserJab[$i]->id_institusi_Institusi;
                } else {
                    $check_jab = 1;
                }
            }
            if ($check_jab == 0) {
                $sql = "INSERT INTO jabatan (id_jabatan, id_institusi, jabatan) VALUES (:id_jabatan, :id_institusi, :jabatan)";
                $add_jab = $db->prepare($sql);
                $add_jab->bindValue(":id_jabatan", $tiga);
                $add_jab->bindValue(":id_institusi", $paramInstitusi);
                $add_jab->bindValue(":jabatan", 'Operator ' . $paramNamaInstitusi);
                if ($add_jab->execute()) {
                    $namaNew = 'Operator_' . $paramNamaInstitusi;
                    $idJabNew = "UPDATE users SET id_jabatan=:id_jabs_new WHERE account = '" . $paramAccount . "'";
                    $add_jab_new = $db->prepare($idJabNew);
                    $add_jab_new->bindValue(":id_jabs_new", $tiga);
                    if ($add_jab_new->execute()) {
                        $NamaNew = "UPDATE users SET account=:accountNew WHERE account = '" . $paramAccount . "'";
                        $NamaNew2 = $db->prepare($NamaNew);
                        $NamaNew2->bindValue(":accountNew", $namaNew);
                        $NamaNew2->execute();
                        echo '{"result": "Sukses Buat Account Operator"}';
                    } else {
                        echo '{"result": "Gagal Buat Account Operator"}';
                    }
                } else {
                    echo '{"result": "Gagal tambah Jabatan 1"}';
                }
            } else {
                echo '{"result": "Gagal tambah Jabatan 2"}';
            }
        }
    } else {
        echo '{"result": "Operator di institusi ini sudah ada"}';
    }

    $db = null;
}

function checkUserOp($db) {
//$db = getDB();
    $sql = "SELECT users.*, institusi.nama_institusi from users, institusi WHERE jenis_user='2' AND users.id_institusi=institusi.id_institusi";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $i = 0;
    while ($row = $stmt->fetch()) {
        $output[$i] = array("account" => $row['account'], "id_institusi" => $row['id_institusi'], "nama_institusi" => $row['nama_institusi']);
        $i++;
    }
//    echo '{"result": ' . json_encode($output) . '}';
    return json_encode($output);
}

function checkUserJabatan($db) {

    $sql = "SELECT institusi.id_institusi, institusi.nama_institusi, users.id_jabatan from institusi, users WHERE institusi.id_institusi=users.id_institusi and jenis_user='2'";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $i = 0;
    while ($row = $stmt->fetch()) {
        $output[$i] = array("id_institusi_Institusi" => $row['id_institusi'], "Nama_institusi" => $row['nama_institusi'], "id_jabatan" => $row['id_jabatan']);
        $i++;
    }
//    echo '{"result": ' . json_encode($output) . '}';    
    return json_encode($output);
}

function addInstansi() {
    $db = getDB();
    global $app;
    $req = json_decode($app->request()->getBody(), true);

    $instansi = json_decode(checkIdInstansi());
    $paramIdInstansi = $instansi->id_instansi;
    $paramIdInstansiNew = tambah0($paramIdInstansi, 1);

    $paramInstansi = $req['nama_instansi'];

    $query = "INSERT INTO instansi (id_instansi, nama_instansi) VALUES (:id_instansi, :nama_instansi)";
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id_instansi", $paramIdInstansiNew);
    $stmt->bindValue(":nama_instansi", $paramInstansi);
    $stmt->execute();
    if ($stmt) {
        echo '{"result": "Success"}';
    } else {
        echo '{"result": "there is something wrong}';
    }
}

function checkIdInstansi() {
    $db = getDB();
    $query = "SELECT * FROM instansi WHERE id_instansi !='000' ORDER BY  `instansi`.`id_instansi` DESC ";
    $stmt = $db->prepare($query);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch();
        $output = array("id_instansi" => $row['id_instansi'], "nama_instansi" => $row['nama_instansi']);
    } else {
        $output = array('status' => false);
    }
    $db = null;
    return json_encode($output);
}

function addInstitusi() {
    $db = getDB();
    global $app;
    $req = json_decode($app->request()->getBody(), true);

    $paramNamaInstitusi = $req['nama_institusi'];
    $paramIdInstansi = $req['id_instansi'];

    $paramIdInstansiNew = tambah0($paramIdInstansi, 0);

    $sql = "SELECT id_institusi from institusi WHERE id_instansi =:id_instansi";
    $stmt2 = $db->prepare($sql);
    $stmt2->bindValue(":id_instansi", $paramIdInstansiNew);
    $stmt2->execute();
    $rowCount = $stmt2->rowCount();
    $i = 0;
    while ($row = $stmt2->fetch()) {
        $paramIdInstitusi[$i] = $row['id_institusi'];
        $i++;
    }

    $paramIdInsititusiNew = tambah0($paramIdInstansiNew, 0) . tambah0(intval(substr($paramIdInstitusi[$rowCount - 1], -3)) + 1, 0);
//    echo $paramIdInsititusiNew;
//    die();
    $query = "INSERT INTO institusi (id_instansi, nama_institusi, id_institusi) VALUES (:id_instansi, :nama_institusi, :id_institusi)";
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id_instansi", $paramIdInstansiNew);
    $stmt->bindValue(":nama_institusi", $paramNamaInstitusi);
    $stmt->bindValue(":id_institusi", $paramIdInsititusiNew);
    $stmt->execute();
    if ($stmt) {
        echo '{"result": "Success"}';
    } else {
        echo '{"result": "there is something wrong}';
    }
}

function addKodeHal() {
    $db = getDB();
    global $app;
    $req = json_decode($app->request()->getBody(), true);

    $paramKodeHal = $req['kode_hal'];
    $paramDeskripsi = $req['deskripsi'];

    $query = "INSERT INTO surat_kode_hal(kode_hal, deskripsi) VALUES (:kodehal, :deskripsi)";
    $stmt = $db->prepare($query);
    $stmt->bindValue(":kodehal", $paramKodeHal);
    $stmt->bindValue(":deskripsi", $paramDeskripsi);
    $stmt->execute();

    if ($stmt) {
        echo '{"result": "sukses"}';
    } else {
        echo '{"result": "gagal"}';
    }
}

function addKodeUnit() {
    $db = getDB();
    global $app;
    $req = json_decode($app->request()->getBody(), true);

    $paramKodeUnit = $req['kode_unit'];
    $paramDeskripsi = $req['deskripsi'];
    $paramInstitusi = $req['id_institusi'];


//    $paramInstitusiNew = tambah02($paramInstitusi, 0);


    $query = "INSERT INTO surat_kode_unit(kode_unit, deskripsi, id_institusi) VALUES (:kodeunit, :deskripsi, :id_institusi)";
    $stmt = $db->prepare($query);
    $stmt->bindValue(":kodeunit", $paramKodeUnit);
    $stmt->bindValue(":deskripsi", $paramDeskripsi);
    $stmt->bindValue(":id_institusi", $paramInstitusi);

    $stmt->execute();
    if ($stmt) {
        echo '{"result": "sukses"}';
    } else {
        echo '{"result": "gagal"}';
    }
}

function getKodeUnit($db, $id_institusi) {
    $db = getDB();
    global $app;

    $query = "SELECT surat_kode_unit.* FROM surat_kode_unit WHERE surat_kode_unit.id_institusi=:id_institusi";
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id_institusi", $id_institusi);
    $stmt->execute();
    $row = $stmt->fetch();
    return $row['kode_unit']; //kode unit dari id_institusi di tabel surat_kode_unit
    $db = null;
}

function getKodeUnits2() {
    $db = getDB();
    $query = "SELECT surat_kode_unit.* FROM surat_kode_unit";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $i = 0;
    while ($row = $stmt->fetch()) {
        $output[$i] = array("deskripsi" => $row['deskripsi'], "kode_unit" => $row['kode_unit'], "id_institusi" => $row['id_institusi']);
        $i++;
    }
    $db = null;
    echo '{"result": ' . json_encode($output) . '}';
}

function checkCounter($db, $id_institusi, $is_preview) {
    $query = "SELECT * FROM surat_counter WHERE id_institusi = :id_institusi AND year = :year";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':id_institusi', $id_institusi);
    $stmt->bindValue(':year', date("Y"));
    $stmt->execute();
    if ($stmt->rowCount() > 0) { // Jika ada, maka counter ditambahkan
        $row = $stmt->fetch();
        if ($row['year'] == date("Y")) { // Update existing row
            $result = $row['counter'] + 1;
            if (!$is_preview) {
                updateCounter($db, $id_institusi, $result, date("Y")); // JIka bukan preview surat, tambahkan counternya
            }
        } else if ($row['year'] < date("Y")) { // Create new row with current year
            $result = 1;
            if (!$is_preview) {
                addCounter($db, $id_institusi, $result, date("Y"));
            }
        }
    } else { // Jika belum ada, tambahkan ke dalam tabel surat_counter
        $result = 1;
        if (!$is_preview) {
            addCounter($db, $id_institusi, $result, date("Y"));
        }
    }
    return $result;
}

function updateCounter($db, $id_institusi, $result, $year) {
    $query = "UPDATE `surat_counter` SET counter=:result WHERE id_institusi = :id_institusi AND year = :year";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':result', $result);
    $stmt->bindValue(':id_institusi', $id_institusi);
    $stmt->bindValue(':year', $year);
    $stmt->execute();
}

function addCounter($db, $id_institusi, $counter, $year) {
    $query = "INSERT INTO `surat_counter`(id_institusi, counter, year) VALUES(:id_institusi, :counter, :year)";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':id_institusi', $id_institusi);
    $stmt->bindValue(':counter', $counter);
    $stmt->bindValue(':year', $year);
    $stmt->execute();
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

function tambah0($input, $inc) {
    if (strlen(intval($input)) == 1) {
        if (intval($input) != 9) {
            $out = '00';
            $input = $out . (intval($input) + $inc);
            return $input;
        } else {
            $out = '0';
            $input = $out . (intval($input) + $inc);
            return $input;
        }
    } else if (strlen(intval($input)) == 2) {
        if (intval($input) != 99) {
            $out = '0';
            $input = $out . (intval($input) + $inc);
            return $input;
        } else {
            $out = '';
            $input = $out . (intval($input) + $inc);
            return $input;
        }
    } else if (strlen(intval($input)) == 3) {
        $out = '';
        $input = $out . (intval($input) + $inc);
        return $input;
    }
}

function tambah02($input, $inc) {
    if (strlen(intval($input)) == 4) {
        if (intval($input) != 9) {
            $out = '00';
            $input = $out . (intval($input) + $inc);
            return $input;
        } else {
            $out = '0';
            $input = $out . (intval($input) + $inc);
            return $input;
        }
    }
}
