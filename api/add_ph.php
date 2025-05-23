<?php

/**
 * Footer
 *
 * Main footer file for the theme.
 * php version 8.3.21
 *
 * @category   Components
 * @package    WordPress
 * @subpackage Theme_Name_Here
 * @author     Your Name <yourname@example.com>
 * @license    https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link       https://yoursite.com
 * @since      1.0.0
 */

header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 1);
$env = parse_ini_file('.env');

$servername = $env["DATABASE_URL"];
$username = $env["DATABASE_USERNAME"];
$password = $env["DATABASE_PASSWORD"];
$dbname = $env["DATABASE_NAME"];

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn-> connect_error) {
    echo json_encode(["Connection failed" => $conn->connect_error]);
    die();
}

$data = json_decode(file_get_contents('php://input'), true);

if (json_last_error() === JSON_ERROR_NONE && !empty($data)) {
    echo json_encode($data);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON']);
    die();
}

if (empty($data['ph_value'])) {
    echo json_encode(['error' => "ph_value is empty"]);
    die();
}
if (empty($data['sensor_id'])) {
    echo json_encode(['error' => "sensor_id is empty"]);
    die();
}


$insert = $conn->prepare("INSERT INTO hydro (ph_value, sensor_id) VALUES (?,?)");
$insert->bind_param("di", $data['ph_value'], $data['sensor_id']);
$insert->execute();

if ($insert->affected_rows > 0) {
    echo json_encode(['success' => "new record created"]);
} else {
    echo json_encode(['error' => $insert->error]);
    $insert->close();
    die();
}

$insert->close();
