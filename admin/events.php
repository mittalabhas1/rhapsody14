<?php
session_start();
require_once '../register/functions.php';

if (!isset($_SESSION['admin']))		header("Location: home.php");
if (!isset($_GET['event']))		header("Location: home.php");
if (isset($_GET['event'])) {
	$event = $_GET['event'];
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
    	max-width: 200px;
    	word-wrap: break-word;
    	padding: 3px 5px;
    	vertical-align: initial;
    	text-align: center;
    }
    table{
    	margin: auto;
    	width: 90% !important;
    }
    .main{
    	max-width: 100%;
    }
    #logout{position: absolute; top: 20px; right: 20px; color: #ddd; }
    #getBack{position: absolute; top: 20px; left: 20px; color: #ddd; }
    #logout:hover, #getBack:hover{color: #fff;}
    </style>    
    <body>
    <div class="container">
        <header class="clearfix">
			<h1><?php echo $event; ?></h1>
		</header>
		<a href="../admin" id="getBack">&larr; back</a>
		<a href="home.php?log=0" id="logout">Logout</a>
		
		<div class="main"><br /><br />
        	<?php
        	$query = mysqli_query($con, "SELECT * FROM leaders WHERE event = '$event'");
        	
        	echo "<table border='1'>";
        	echo "<tr>";
        	echo "<td>Sr.No.</td>";
        	echo "<td>Rhapsody ID</td>";
        	echo "<td>Name</td>";
        	echo "<td>Gender</td>";
        	echo "<td>Email</td>";
        	echo "<td>Mobile</td>";
        	echo "</tr>";
        	$i = 1;
        	while ($fetch = mysqli_fetch_assoc($query)) {
        		$query1 = mysqli_query($con, "SELECT * FROM users WHERE rid = '$fetch[leader]'");
        		$fetch1 = mysqli_fetch_assoc($query1);
        		
        		echo "<tr>";
        		echo "<td colspan='6'><b><i>".$fetch1['college']."</i></b></td>";
        		echo "</tr>";
	        	
	        	echo "<tr>";
	        	echo "<td>".$i++."</td>";
	        	echo "<td>".$fetch1['rid']."</td>";
	        	echo "<td>".$fetch1['name']."</td>";
	        	echo "<td>".$fetch1['gender']."</td>";
	        	echo "<td>".$fetch1['email']."</td>";
	        	echo "<td>".$fetch1['contact']."</td>";
	        	echo "</tr>";
	        	
	        	$query2 = mysqli_query($con, "SELECT member FROM user WHERE eid = '$fetch[eid]'");
	        	while ($fetch2 = mysqli_fetch_assoc($query2)) {
	        		$query3 = mysqli_query($con, "SELECT * FROM users WHERE rid = '$fetch2[member]'");
	        		$fetch3 = mysqli_fetch_assoc($query3);
                    if (mysqli_num_rows($query3) == 0) {
                        $rid = $fetch2['member'];
                    }else{
                        $rid = $fetch3['rid'];
                    }
	        		echo "<tr>";
		        	echo "<td>".$i++."</td>";
		        	echo "<td>".$rid."</td>";
		        	echo "<td>".$fetch3['name']."</td>";
		        	echo "<td>".$fetch3['gender']."</td>";
		        	echo "<td>".$fetch3['email']."</td>";
		        	echo "<td>".$fetch3['contact']."</td>";
		        	echo "</tr>";
	        	}
        	}
        	echo "</table>";
			?>
        </div>
    </div>
    <?php
}
?>
    </body>
</html>