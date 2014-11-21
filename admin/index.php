<?php
session_start();
require_once '../register/functions.php';
$show = "accepted";

if (!isset($_SESSION['admin'])) {
	header("Location: home.php");
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Admin - Rhapsody'14</title>
        <link rel="stylesheet" type="text/css" href="../register/css/default.css" />
		<link rel="stylesheet" type="text/css" href="../register/css/component.css" />
		<script type="text/javascript" src="../register/js/jquery.js"></script>
		<script type="text/javascript" src="../register/js/modernizr.custom.js"></script>
    </head>
    <style type="text/css">
    select option{
    	color: #10689a;
    }
    body{
    	background-position: center;
    	overflow-x: hidden;
    }
    td{
    	min-width: 10px;
    	max-width: 150px;
    	word-wrap: break-word;
    	padding: 3px 5px;
    	vertical-align: initial;
    	text-align: center;
    }
    table{
    	margin: auto;
    	width: 98% !important;
    }
    .main{
    	max-width: 100%;
    }
    #emailIds{
    	position: absolute;
		top: 20px;
		right: 100px;
    }
    #showEmail{ position: absolute; top: 50px; right: 110px; display: none;}
    #mobileNos{
    	position: absolute;
		top: 20px;
		right: 250px;
    }
    #showContact{ position: absolute; top: 50px; right: 257px; display: none;}
    #showEmail>a, #mobileNos>a{color: #eee; text-decoration: none;}
    #logout{position: absolute; top: 20px; right: 20px; color: #eee; }
    #logout:hover{color: #fff;}
    .accepted { position: absolute; top: 50px; width: 10%; left: 45%; padding: 0.5em; display: none; border: 2px solid rgba(200,0,0,0.5);  background: rgba(200,0,0,0.5); border-radius: 8px; font-size: 12px; margin: auto; text-align: center; border: 2px solid rgba(0,150,0,0.5);  background: rgba(0,150,0,0.5); }
    #click, #eventList{ position: absolute; top: 20px; right: 500px; color: #fff; cursor: pointer; }
    #eventList{ background-color: #333; top: 45px; display: none; max-width: 50%; z-index: 9999; padding: 20px; }
    .eventLink{ display: inline-block; width: 50%; color: #ddd; }
    .eventLink:hover{ color: #fff; }
    </style>
    <script type="text/javascript">
    jQuery(document).ready(function(){
    	jQuery('#click').click(function(){
    		if(jQuery('#eventList').attr('class') == "shown")
    			jQuery('#eventList').removeClass('shown').fadeOut();
    		else
    			jQuery('#eventList').addClass('shown').fadeIn();
	    });

	    jQuery('#emailIds>input').click(function(){
	    	$.ajax({
				type: "POST",
				url: "email.php",
			})
			.done(function( msg ) {
				alert('Generated!');
				$('#showEmail').fadeIn();
			});
	    });

	    jQuery('#mobileNos>input').click(function(){
	    	$.ajax({
				type: "POST",
				url: "mobile.php",
			})
			.done(function( msg ) {
				alert('Generated!');
				$('#showContact').fadeIn();
			});
	    });
    });
    </script>
    <body>
    <div class="container">
        <header class="clearfix">
			<h1>Rhapsody'14</h1>
		</header>
		<a href="home.php?log=0" id="logout">Logout</a>
		<div id="click">Events</div>
		<div id="emailIds"><input type="button" value="Generate Email IDs"></div>
		<div id="showEmail"><a href="email.txt" target="_blank">Check email IDs</a></div>
		<div id="mobileNos"><input type="button" value="Generate Mobile Nos."></div>
		<div id="showContact"><a href="contact.txt" target="_blank">Check Mobile Nos.</a></div>
		<div id="eventList">
			<?php
			$query4 = mysqli_query($con, "SELECT * FROM events WHERE event != '-'");
			while ($fetch4 = mysqli_fetch_assoc($query4)) {
				echo '<div class="eventLink"><a href="events.php?event='.$fetch4['event'].' ('.$fetch4['category'].')">'.$fetch4['event'].' ('.$fetch4['category'].')</a></div>';
			}
			?>
		</div>
		<div class="accepted">Welcome, Abhas !</div>
		<div class="main"><br /><br />
        	<?php
        	$query = mysqli_query($con, "SELECT * FROM users WHERE 1");
        	echo "<table border='1'>";
        	echo "<tr>";
        	echo "<td>Sr.No.</td>";
        	echo "<td>Rhapsody ID</td>";
        	echo "<td>Name</td>";
        	echo "<td>Gender</td>";
        	echo "<td>College</td>";
        	echo "<td>Email</td>";
        	echo "<td>Mobile</td>";
        	echo "<td>Location</td>";
        	echo "<td>Verified</td>";
        	echo "<td>TimeStamp</td>";
            echo "<td>Events</td>";
        	echo "</tr>";
        	while ($fetch = mysqli_fetch_assoc($query)) {
                $query1 = mysqli_query($con, "SELECT event FROM leaders WHERE leader = '$fetch[rid]'");
                $events = "";
                while ($fetch1 = mysqli_fetch_assoc($query1)) {
                    $events .= $fetch1['event']." ,";
                }
                $query2 = mysqli_query($con, "SELECT event FROM user WHERE member = '$fetch[rid]'");
                while ($fetch2 = mysqli_fetch_assoc($query2)) {
                    $events .= $fetch2['event']." ,";
                }
	        	echo "<tr>";
	        	echo "<td>".$fetch['uid']."</td>";
	        	echo "<td>".$fetch['rid']."</td>";
	        	echo "<td>".$fetch['name']."</td>";
	        	echo "<td>".$fetch['gender']."</td>";
	        	echo "<td>".$fetch['college']."</td>";
	        	echo "<td>".$fetch['email']."</td>";
	        	echo "<td>".$fetch['contact']."</td>";
	        	echo "<td>".$fetch['location']."</td>";
	        	echo "<td>".$fetch['verified']."</td>";
	        	echo "<td>".$fetch['timestamp']."</td>";
                echo "<td>".$events."</td>";
	        	echo "</tr>";
        	}
        	echo "</table>";
			?>
        </div>
    </div>
    <?php
    if ($show == "accepted")
		echo "<script type='text/javascript'>$('.accepted').show();</script>";
	?>
    </body>
</html>