<?php
session_start();
require_once 'functions.php';
	$category = $_POST['category'];
	$inputs = $_POST['inputs'];

	$query = mysqli_query($con, "SELECT max FROM events WHERE category = '$category'");
	$fetch = mysqli_fetch_assoc($query);
	$max = $fetch['max'];
	if ($max > $inputs) {
		echo '<br /><input type="text" class="mrid" name="mrid[]" placeholder="RY-XXXX (RID of Member '.++$inputs.')" title="RY-XXXX (RID of Member '.++$inputs.')">';
	}else{
		echo "<script type='text/javascript'>alert('The team can\'t be bigger!');</script>";
	}
?>