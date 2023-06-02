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


    $url = "https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=" . $data->token;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $resp = curl_exec($curl);
    curl_close($curl);
    $output = json_decode($resp);


    if (isset($output->error)) {
        echo json_encode(array('response' => $output));
        exit();
    }


    $sql = "SELECT * FROM individuo";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        $rows = array();
        while ($r = mysqli_fetch_assoc($result)) {
            $rows[] = $r;
        }
        echo json_encode(array('response' => $rows));
    } else {
        echo json_encode(array('response' => '0 risultati'));
        ;
    }

    $con->close();
}
?>