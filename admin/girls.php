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
    	background: white;
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
    	color: #000;
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
    .eventLink:hover{ color: #000; }
    </style>
    <body>
    <div class="container">
        <header class="clearfix">
			<h1>Rhapsody'14</h1>
		</header>
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
        	$uid = 1;
        	$query = mysqli_query($con, "SELECT * FROM users WHERE gender = 'Female'");
        	echo "<table border='1'>";
        	echo "<tr>";
        	echo "<td>Sr.No.</td>";
        	echo "<td>Rhapsody ID</td>";
        	echo "<td>Name</td>";
        	echo "<td>College</td>";
        	echo "<td>Email</td>";
        	echo "<td>Mobile</td>";
        	echo "<td>Location</td>";
        	echo "</tr>";
        	while ($fetch = mysqli_fetch_assoc($query)) {
                $events = "";
	        	echo "<tr>";
	        	echo "<td>".$uid."</td>";
	        	echo "<td>".$fetch['rid']."</td>";
	        	echo "<td>".$fetch['name']."</td>";
	        	echo "<td>".$fetch['college']."</td>";
	        	echo "<td>".$fetch['email']."</td>";
	        	echo "<td>".$fetch['contact']."</td>";
	        	echo "<td>".$fetch['location']."</td>";
	        	echo "</tr>";
	        	$uid++;
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