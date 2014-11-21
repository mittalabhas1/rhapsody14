<?php
require_once '../register/functions.php';
$file = fopen("contact.txt","w");
$query = mysqli_query($con, "SELECT contact FROM users WHERE 1");
while ($fetch = mysqli_fetch_assoc($query)) {
	fwrite($file,$fetch['contact']."\r\n");
}
fclose($file);
?>