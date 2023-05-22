<?php
//require_once 'jwt_utils.php';

function cors()
{
    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400'); // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }
}
cors();

// Change this to your connection info. 
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'applabanof';
$DATABASE_PASS = '';
$DATABASE_NAME = 'my_applabanof';
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    // If there is an error with the connection, stop the script and display the error.
    echo json_encode(array('response' => 'Failed to connect to MySQL: ' . mysqli_connect_error()));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input", true));

    //$sql = "UPDATE 'individuo' SET 'nome'='" . $data->nome . "','luogoRinvenimento'='" . $data->luogoRinvenimento . "','dataRinvenimento'='" . $data->dataRinvenimento . "','epoca'='" . $data->epoca . "','sessoBiologico'='" . $data->sessoBiologico . "','classeDiEta'='" . $data->classeEta . "','origineGeografica'='" . $data->origineGeografica . "','origineBiologica'='" . $data->origineBiologica . "','pesoCremazione'='" . $data->pesoCremazione . "','volumeCremazione'='" . $data->volumeCremazione . "','stato'='" . $data->stato . "','pesoIndividuo'='" . $data->pesoIndividuo . "','ultimaModifica'='" . time() . "' WHERE id=" . $data->id;


    $sql = "UPDATE individuo SET ";

    if (isset($data->nome))
        $sql .= " nome='" . $data->nome . "',";

    if (isset($data->luogoRinvenimento))
        $sql .= "luogoRinvenimento='" . $data->luogoRinvenimento . "',";

    if (isset($data->dataRinvenimento))
        $sql .= "dataRinvenimento='" . $data->dataRinvenimento . "',";

    if (isset($data->ultimaModifica))
        $sql .= "ultimaModifica='" . time() . "',";

    if (isset($data->classeDiEta))
        $sql .= "classeDiEta='" . $data->classeDiEta . "',";

    if (isset($data->origineBiologica))
        $sql .= "origineBiologica='" . $data->origineBiologica . "',";

    if (isset($data->origineGeografica))
        $sql .= "origineGeografica='" . $data->origineGeografica . "',";

    if (isset($data->sessoBiologico))
        $sql .= "sessoBiologico='" . $data->sessoBiologico . "'";

    $sql .= " WHERE id= " . $data->id;


    if (mysqli_query($con, $sql)) {
        echo json_encode(array('response' => 'success'));

    } else {
        echo json_encode(array('response' => 'error', 'error' => "Could not insert record: " . mysqli_error($con)));
    }

    $con->close();
}
?>