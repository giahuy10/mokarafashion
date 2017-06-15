<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mokara_online";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "INSERT INTO fashion_user_logs ( `user_id`, `ip`, `component`, `view`, `layout`, `task`, `item`, `ref`, `time`)
VALUES ('".$_GET['user_id']."','".$_SERVER['REMOTE_ADDR']."','".$_GET['option']."','".$_GET['view']."','".$_GET['layout']."','".$_GET['task']."','".$_GET['item']."','".$_GET['ref']."','".$_GET['time']."')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
