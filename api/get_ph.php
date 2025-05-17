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

$stmt = $conn->prepare("SELECT ph_value, mess_time FROM hydro
        WHERE mess_time >= NOW() - INTERVAL 1 HOUR AND ph_value IS NOT NULL");

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $output = $result->fetch_all(MYSQLI_ASSOC);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode($output);

} else {
    echo "Keine Einträge gefunden.";
}

$insert->close();
?>
