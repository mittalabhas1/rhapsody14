<?php
session_start();
require_once 'functions.php';
	$event = $_POST['events'];

	$query = mysqli_query($con, "SELECT category FROM events WHERE event = '$event'");
	if (mysqli_num_rows($query) != 0) {
		while($fetch = mysqli_fetch_assoc($query)){
		?>
			<option value="<?php echo $fetch['category']; ?>"><?php echo $fetch['category']; ?></option>
		<?php
		}
	}else{
		echo "<option>No Event Sub Category</option>";
	}
?>