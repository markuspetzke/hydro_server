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

if (json_last_error() !== JSON_ERROR_NONE && empty($data)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON']);
    die();
}

if (empty($data['day_pump'])) {
    echo json_encode(['error' => "day_pump is empty"]);
    die();
}
if (empty($data['day_break'])) {
    echo json_encode(['error' => "day_break is empty"]);
    die();
}
if (empty($data['night_pump'])) {
    echo json_encode(['error' => "night_pump is empty"]);
    die();
}
if (empty($data['night_break'])) {
    echo json_encode(['error' => "night_break is empty"]);
    die();
}

$insert = $conn->prepare("INSERT INTO settings (day_pump, day_break, night_pump, night_break) VALUES (?,?,?,?)");
$insert->bind_param("iiii", $data['day_pump'], $data['day_break'], $data['night_pump'], $data['night_break']);
$insert->execute();


if ($insert->affected_rows > 0) {
    echo json_encode(['success' => "new record created"]);
} else {
    echo json_encode(['error' => $insert->error]);
    $insert->close();
    die();
}

$insert->close();
