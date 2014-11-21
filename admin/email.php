<?php
require_once '../register/functions.php';
$file = fopen("email.txt","w");
$query = mysqli_query($con, "SELECT email FROM users WHERE 1");
while ($fetch = mysqli_fetch_assoc($query)) {
	fwrite($file,$fetch['email']."\r\n");
}
fclose($file);
?>