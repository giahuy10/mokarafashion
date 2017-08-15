<?php
require 'config.php';


$conn = new mysqli(DBSERVER, DBUSERNAME, DBPASSWORD, DBNAME);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql2 = "SELECT id FROM rating_users WHERE item_id =".$_GET['item_id']." and ip ='".$_SERVER['REMOTE_ADDR']."'";
echo $sql2."<br/>";
$result = $conn->query($sql2);
if ($result->num_rows > 0) {
	echo "<span class='error'>You've already rated for this coupon.</span>";
	}
else {
	$sql = "INSERT INTO rating_users (  `ip`, `item_id`, `rate`)
	VALUES ('".$_SERVER['REMOTE_ADDR']."','".$_GET['item_id']."','".$_GET['rate']."')";

	if ($conn->query($sql) === TRUE) {
		echo "Thanks for your rating!";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
}


$conn->close();
?>
