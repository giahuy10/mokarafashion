<?php
require 'configuration.php';
$config = new JConfig();
$servername = $config->host;
$username = $config->user;
$password = $config->password;
$prefix = $config->dbprefix;
$dbname = $config->db;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "INSERT INTO ".$prefix."user_filter ( `user_id`, `ip`, `field_id`, `value`)
VALUES ('".$_GET['user_id']."','".$_GET['ip']."','".$_GET['field_id']."','".$_GET['field_value']."')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
