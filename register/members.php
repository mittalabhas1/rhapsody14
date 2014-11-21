<?php
session_start();
require_once 'functions.php';
	$category = $_POST['category'];

	$query = mysqli_query($con, "SELECT min FROM events WHERE category = '$category'");
	$fetch = mysqli_fetch_assoc($query);
	$min = $fetch['min'];
	$i = 1;
	echo '<input type="text" id="rid" value="'.$_SESSION['rid'].'" name="rid" disabled>';
	while ($i < $min) {
		echo '<br /><input type="text" class="mrid" name="mrid[]" placeholder="RY-XXXX (RID of Member '.++$i.')" title="RY-XXXX (RID of Member '.$i.')" required>';
	}
?>