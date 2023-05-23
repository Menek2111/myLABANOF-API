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

    $contatore = 0;


    if ($data->nome != '') {
        if ($contatore == 0) {
            $sql .= "nome='" . $data->nome . "'";
            $contatore++;
        } else {
            $sql .= ", nome='" . $data->nome . "'";
        }
    }

    if ($data->luogoRinvenimento != '') {

        if ($contatore == 0) {
            $sql .= "luogoRinvenimento='" . $data->luogoRinvenimento . "'";
            $contatore++;
        } else {
            $sql .= ", luogoRinvenimento='" . $data->luogoRinvenimento . "'";
        }

    }

    if ($data->dataRinvenimento != '') {
        if ($contatore == 0) {
            $sql .= "dataRinvenimento='" . $data->dataRinvenimento . "'";
            $contatore++;

        } else {
            $sql .= ", dataRinvenimento='" . $data->dataRinvenimento . "'";
            ;
        }
    }

    if ($data->ultimaModifica != '') {
        if ($contatore == 0) {
            $sql .= "ultimaModifica='" . time() . "'";
            $contatore++;
        } else {
            $sql .= ", ultimaModifica='" . time() . "'";

        }

    }

    if ($data->classeDiEta != '') {
        if ($contatore == 0) {
            $sql .= "classeDiEta='" . $data->classeDiEta . "'";
            $contatore++;

        } else {
            $sql .= ", classeDiEta='" . $data->classeDiEta . "'";

        }
    }

    if ($data->origineBiologica != '') {
        if ($contatore == 0) {
            $sql .= "origineBiologica='" . $data->origineBiologica . "'";
            $contatore++;
        } else {
            $sql .= ", origineBiologica='" . $data->origineBiologica . "'";

        }
    }

    if ($data->origineGeografica != '') {
        if ($contatore == 0) {
            $sql .= "origineGeografica='" . $data->origineGeografica . "'";
            $contatore++;
        } else {
            $sql .= ", origineGeografica='" . $data->origineGeografica . "'";

        }
    }

    if ($data->sessoBiologico != '') {
        if ($contatore == 0) {
            $sql .= "sessoBiologico='" . $data->sessoBiologico . "'";
            $contatore++;
        } else {
            $sql .= ", sessoBiologico='" . $data->sessoBiologico . "'";

        }
    }

    $sql .= " WHERE id='" . $data->id . "';";

    if (mysqli_query($con, $sql)) {
        echo json_encode(array('response' => 'success'));

    } else {
        echo json_encode(array('response' => 'error', 'error' => "Could not insert record: " . mysqli_error($con) . " " . $sql));
    }

    $con->close();
}
?>