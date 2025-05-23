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
error_reporting(E_ALL);
ini_set('display_errors', 1);
$env = parse_ini_file('.env');

$servername = $env["DATABASE_URL"];
$username = $env["DATABASE_USERNAME"];
$password = $env["DATABASE_PASSWORD"];
$dbname = $env["DATABASE_NAME"];

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn-> connect_error) {
    die("Connection failed: ". $conn->connect_error);
}

echo "Connected";

$ph_value = $_POST['ph_value'];
if (empty($ph_value)) {
    die("ph_value is empty");
}
$insert = $conn->prepare("INSERT INTO hydro (ph_value) VALUES (?)");
$insert->bind_param("s", $ph_value);
$insert->execute();

if ($insert->affected_rows > 0) {
    echo "New record created<br>";
} else {
    echo "Error: " . $insert->error . "<br>";
}

$insert->close();
