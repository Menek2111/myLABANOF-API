<?php
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

    $sql = "UPDATE patologiaSpecifica SET ";

    $contatore = 0;

    if ($data->osso != '') {

        if ($contatore == 0) {
            $sql .= "osso='" . addslashes($data->osso) . "'";
            $contatore++;
        } else {
            $sql .= ", osso='" . addslashes($data->osso) . "'";
        }

    }

    if ($data->tipoPatologia != '') {
        if ($contatore == 0) {
            $sql .= " tipoPatologia='" . $data->tipoPatologia . "'";
            $contatore++;
        } else {
            $sql .= ", tipoPatologia='" . $data->tipoPatologia . "'";

        }
    }

    if ($data->descrizione != '') {
        if ($contatore == 0) {
            $sql .= "descrizione='" . $data->descrizione . "'";
            $contatore++;

        } else {
            $sql .= ", descrizione='" . $data->descrizione . "'";

        }
    } else {
        if ($contatore == 0) {
            $sql .= "descrizione=null";
            $contatore++;
        } else {
            $sql .= ", descrizione=null";
        }
    }

    if ($data->litica != '') {
        if ($contatore == 0) {
            $sql .= "litica='" . $data->litica . "'";
            $contatore++;
        } else {
            $sql .= ", litica='" . $data->litica . "'";
        }
    }

    if ($data->proliferativa != '') {
        if ($contatore == 0) {
            $sql .= "proliferativa='" . $data->proliferativa . "'";
            $contatore++;
        } else {
            $sql .= ", proliferativa='" . $data->proliferativa . "'";
        }
    }

    if ($data->classePatologia != '') {
        if ($contatore == 0) {
            $sql .= "classePatologia='" . $data->classePatologia . "'";
            $contatore++;
        } else {
            $sql .= ", classePatologia='" . $data->classePatologia . "'";
        }
    }


    $sql .= " WHERE id='" . $data->id . "';";

    if (mysqli_query($con, $sql)) {
        echo json_encode(array('response' => 'success', 'sql' => $sql));
    } else {
        echo json_encode(array('response' => 'error', 'error' => "Could not insert record: " . mysqli_error($con) . " " . $sql));
    }

    $con->close();
}
?>