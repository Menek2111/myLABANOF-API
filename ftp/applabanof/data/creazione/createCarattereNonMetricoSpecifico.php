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

    $sql = "INSERT INTO `carattereNonMetricoSpecifico` ";

    $campi = [
        '`individuo`',
        '`tipoCarattereNonMetrico`',
        '`lato`',
        '`valore`'
    ];
    $valori = [
        $data->individuo,
        $data->tipoCarattereNonMetrico,
        $data->lato,
        $data->valore
    ];

    $campiPresenti = [];
    $valoriPresenti = [];

    if ($data->individuo != '') {
        array_push($campiPresenti, $campi[0]);
        array_push($valoriPresenti, "'" . $valori[0] . "'");
    }
    if ($data->tipoCarattereNonMetrico != '') {
        array_push($campiPresenti, $campi[1]);
        array_push($valoriPresenti, "'" . $valori[1] . "'");
    }
    if ($data->lato != '') {
        array_push($campiPresenti, $campi[2]);
        array_push($valoriPresenti, "'" . $valori[2] . "'");
    }
    if ($data->valore != '') {
        array_push($campiPresenti, $campi[3]);
        array_push($valoriPresenti, "'" . $valori[3] . "'");
    }

    $contatore = 0;
    $sql .= "(";
    foreach ($campiPresenti as &$campo) {
        $sql .= $campo;
        $contatore += 1;
        if ($contatore != count($campiPresenti)) {
            $sql .= ",";
        }
    }
    $sql .= ") VALUES (";
    $contatore = 0;
    foreach ($valoriPresenti as &$valore) {
        $sql .= $valore;
        $contatore += 1;
        if ($contatore != count($valoriPresenti)) {
            $sql .= ",";
        }
    }
    $sql .= ")";

    if (mysqli_query($con, $sql)) {
        echo json_encode(array('response' => 'success', 'sql' => $sql));
    } else {
        echo json_encode(array('response' => "Could not insert record: " . mysqli_error($con) . "/" . $sql));

    }

    $con->close();
}
?>