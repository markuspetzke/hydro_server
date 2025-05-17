<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "192.168.0.100";
$username = "user";
$password = "pass";
$dbname = "myDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if($conn-> connect_error) {
  die("Connection failed: ". $conn->connect_error);
}

echo "Connected";

$ph_value = $_POST['ph_value'];
$sql = "INSERT INTO hydro (ph_value) VALUES ('" . $ph_value . "')"; //need to change for sec.

// Ausführen
if ($conn->query($sql) === TRUE) {
    echo "New record created";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>
