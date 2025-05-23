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

$stmt = $conn->prepare(
    "SELECT ph_value, sensor_id, mess_time FROM hydro
      WHERE mess_time >= NOW() - INTERVAL 1 HOUR
      ORDER BY mess_time"
);

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $output = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($output);

} else {
    echo json_encode(["error" => 'No entry found']);
    die();
}

$insert->close();
