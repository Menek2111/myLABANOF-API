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

    $ossaFlag = false;

    //$sql = "SELECT tipoOsso.nome, tipoOsso.id FROM (tipoOsso INNER JOIN distretto ON tipoOsso.distretto=distretto.id) WHERE distretto.nome= '" . $data->distretto . "'";
    //$sql = "SELECT tipoOsso.*, distretto.nome as distrettoNome FROM tipoOsso INNER JOIN distretto ON tipoOsso.distretto=distretto.id";

    //$sql = "SELECT patologiaGenereale.*, distretto.nome as distrettoNome FROM patologiaGenereale INNER JOIN patologiaPerDistretto ON patologiaPerDistretto.patologia=patologia.id";

    $sql = "SELECT patologiaGenerale.* FROM patologiaGenerale";

    $result = $con->query($sql);
    $ossaRows = array();
    while ($r = $result->fetch_assoc()) {
        $ossaRows[] = $r;
        $ossaFlag = true;
    }

    if ($ossaFlag) {
        echo json_encode(array('response' => 'success', 'results' => $ossaRows));
    } else {
        echo json_encode(array('response' => 'error', 'error' => mysqli_error($con)));
    }

    $con->close();
}
?>