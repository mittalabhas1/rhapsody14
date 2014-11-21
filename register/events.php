<?php
session_start();
require_once 'functions.php';
$show = null;
$team = null;

if (!isset($_SESSION['rid'])) {
	header("Location: index.php");
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event = clean_input($_POST['event'], $con);
    $category = clean_input($_POST['category'], $con);
    $event = $event." (".$category.")";
    $user = $_SESSION['rid'];
    mysqli_query($con, "INSERT INTO leaders SET leader = '$user', event = '$event', timestamp = NOW()") or die(mysqli_error());
    $query = mysqli_query($con, "SELECT eid FROM leaders WHERE leader = '$user' AND event = '$event'");
    $fetch = mysqli_fetch_assoc($query);
    if(isset($_POST['mrid'])){
	    foreach ($_POST['mrid'] as $member) {
	    	if ($member != "") {
	    		mysqli_query($con, "INSERT INTO user SET eid = '$fetch[eid]', member = '$member', event = '$event', timestamp = NOW()") or die(mysqli_error());
	    		$query1 = mysqli_query($con, "SELECT email FROM users WHERE rid='$member'");
	    		$fetch1 = mysqli_fetch_assoc($query1);

				$msg = "Hey,\nYou have been registered for ".$event." held under Rhapsody, the Annual Spring Cultural Fest of IIT Roorkee. If you or your group leader didn't register for it, call us.\nFor regular updates, follow us on http://www.facebook.com/RhapsodyIITR\n\nRegards,\nTeam Rhapsody";
				$subject = 'Rhapsody, IIT Roorkee - Event Registration Successful';
				$header = 'From: Rhapsody@pravaah.org';
				mail($fetch1['email'], $subject, $msg, $header);
				$show = "accepted";
	    	}
	    }
	}
	$query1 = mysqli_query($con, "SELECT email FROM users WHERE rid='$user'");
	$fetch1 = mysqli_fetch_assoc($query1);

	$msg = "Hey,\nYou have been registered for ".$event." held under Rhapsody, the Annual Spring Cultural Fest of IIT Roorkee. If you or your group leader didn't register for it, call us.\nFor regular updates, follow us on http://www.facebook.com/RhapsodyIITR\n\nRegards,\nTeam Rhapsody";
	$subject = 'Rhapsody, IIT Roorkee - Event Registration Successful';
	$header = 'From: Rhapsody@pravaah.org';
	mail($fetch1['email'], $subject, $msg, $header);
	$show = "accepted";
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Registration - Rhapsody'14</title>
        <link rel="stylesheet" type="text/css" href="css/default.css" />
		<link rel="stylesheet" type="text/css" href="css/component.css" />
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/modernizr.custom.js"></script>
		<script type="text/javascript">
		$(document).ready(function(){
			$("select[name='event']").change(function(){
				var events = $(this).val();
				//console.log(events);
				$.ajax({
					type: "POST",
					url: "category.php",
					data: { events: events }
				})
				.done(function( msg ) {
					//console.log( msg );
					$("select[name='category']").html(msg);
					changeCategory();
				});
			});

			function changeCategory(){
				var category = $("select[name='category']").val();
				//console.log(category);
				$.ajax({
					type: "POST",
					url: "members.php",
					data: { category: category }
				})
				.done(function( msg ) {
					//console.log( msg );
					$("#members").html(msg);
				});
			}

			$("select[name='category']").change(function(){
				changeCategory();
			});

			$('#addMore').click(function(){
				var category = $("select[name='category']").val();
				var inputs = $('#members>input').length;
				//console.log(category);
				//console.log(inputs);
				if (category != "nope") {
					$.ajax({
						type: "POST",
						url: "input.php",
						data: { category: category, inputs: inputs }
					})
					.done(function( msg ) {
						//console.log( msg );
						$("#members").append(msg);
					});
				} else {
					alert('Please select an event.');
				}
			});
		});
		
		</script>
    </head>
    <style type="text/css">
    .main{width:60%;}
    select option{color: #10689a;}
    td{min-width: 300px;vertical-align: top;}
    .rejected, .accepted { padding: 0.5em; display: none; border: 2px solid rgba(200,0,0,0.5);  background: rgba(200,0,0,0.5); border-radius: 8px; font-size: 12px; margin: auto; width: 370px; text-align: center; }
	.accepted { border: 2px solid rgba(0,150,0,0.5);  background: rgba(0,150,0,0.5); }
	#registeredEvents{display: inline-block;width: 400px;border: 1px solid;padding: 20px;float:left; margin-top: 15px;}
	#logout:hover{color:#fff;}
    </style>
    
    <body>
    <div class="container">
        <header class="clearfix">
			<h1>Rhapsody'14</h1>
			<a href="index.php?log=0" style="position:absolute; top: 20px; right: 20px; font-size: 20px;" id="logout">Logout</a>
		</header>
		<div class="accepted">You have successfully registered for this event.</div>
		<div class="rejected">Oops! An error has occured. Please try again latter.</div>
        <div class="main">
			<form name="myForm" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="cbp-mc-form" autocomplete="off">
				<div class="cbp-mc-column">
			        <table>
			        	<tr>
			        		<td><label>Event</label></td>
			        		<td>
			        			<select name="event" id="event" placeholder="Event">
			        				<option value="--">Select an event</option>
			        			<?php
			        				$query = mysqli_query($con, "SELECT category FROM events WHERE event = '-'");
			        				while ($fetch = mysqli_fetch_assoc($query)) {
			        			?>
			        					<option value="<?php echo $fetch['category']; ?>"><?php echo $fetch['category']; ?></option>
			        			<?php
			        				}
			        			?>
				        			
			        			</select>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td><label>Category</label></td>
			        		<td>
			        			<select name="category" id="category" placeholder="Category">
				        			<option value="nope">--</option>
			        			</select>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td><label>Rhapsody ID of Members</label></td>
			        		<td id="members"><input type="text" id="rid" value="<?php echo $_SESSION['rid']; ?>" name="rid" disabled></td>
			        	</tr>
			        	<tr>
			        		<td></td>
			        		<td><input class="cbp-mc-submit" type="button" value="Add Member" id="addMore" /></td>
			        	</tr>
			        </table>
			        <div class="cbp-mc-submit-wrap"><input class="cbp-mc-submit" type="submit" name="button" value="Submit" /></div>
			    </div>
        	</form>
        </div>
        <div id="registeredEvents">
        	<h2>Your Registered Events</h2>
        	<?php
        		$user = $_SESSION['rid'];
        		$query4 = mysqli_query($con, "SELECT * FROM leaders WHERE leader = '$user'");
        		$query5 = mysqli_query($con, "SELECT * FROM user WHERE member = '$user'");
        	?>
        	<ul>
        		<?php while ($fetch4 = mysqli_fetch_assoc($query4)) {
        			echo '<li>'.$fetch4["event"].'</li>';
        		} ?>
        		<?php while ($fetch5 = mysqli_fetch_assoc($query5)) {
        			echo '<li>'.$fetch5["event"].'</li>';
        		} ?>
        	</ul>
		</div>
    </div>
    <?php
    if ($show == "accepted")
		echo "<script type='text/javascript'>$('.accepted').show();</script>";
	elseif ($show == "rejected")
		echo "<script type='text/javascript'>$('.rejected').show();</script>";
	?>
    </body>
</html>

