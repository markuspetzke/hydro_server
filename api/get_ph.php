<?php 
$env = parse_ini_file('.env');

$servername = $env["DATABASE_URL"];
$username = $env["DATABASE_USERNAME"];
$password = $env["DATABASE_PASSWORD"];
$dbname = $env["DATABASE_NAME"];

$conn = new mysqli($servername, $username, $password, $dbname);

if($conn-> connect_error) {
  die("Connection failed: ". $conn->connect_error);
}

echo "Connected";

$sql = "SELECT ph_value, mess_time FROM hydro
        WHERE mess_time >= NOW() - INTERVAL 1 HOUR";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"] . " - pH: " . $row["ph_value"] . " - Zeit: " . $row["timestamp"] . "<br>";
    }
} else {
    echo "Keine Einträge gefunden.";
}

$insert->close();
?>
