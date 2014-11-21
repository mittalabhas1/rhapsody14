<?php
session_start();
require_once '../register/functions.php';
$show = null;
$error = null;

if (isset($_GET['log']) && $_GET['log'] == 0) {
    if (isset($_SESSION['admin']))
        session_unset($_SESSION['admin']);
	$show = "logout";
}

if (isset($_SESSION['admin']))
	header("Location: ../admin");

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	$admin = clean_input($_POST['admin'], $con);
    $pass = clean_input($_POST['pass'], $con);
    
    if ($admin == "abhas" && $pass == "feeltheBeat") {
    	$_SESSION['admin'] = "abhas";
    	header("Location: ../admin");
    } else {
    	$show = "invalid";
    }
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
    .help {
        color: red;
        font-style: italic;
    }
    td{
    	min-width: 200px;
    	vertical-align: initial;
    }
    .login, .register, .rhapsodyid, .forgotpass{
    	float: left;
    }
    .login, .rhapsodyid, .forgotpass{
    	border: 1px solid;
    	margin-bottom: 10px; 
    }
    .logout, .invalid { padding: 0.5em; display: none; border: 2px solid rgba(200,0,0,0.5);  background: rgba(200,0,0,0.5); border-radius: 8px; font-size: 12px; margin: auto; width: 370px; text-align: center; }
	.logout { border: 2px solid rgba(0,150,0,0.5);  background: rgba(0,150,0,0.5); }
    </style>    
    <body>
    <div class="container">
        <header class="clearfix">
			<h1>Rhapsody Admin Interface</h1>
		</header>

		<div class="invalid">Invalid Credentials !</div>
		<div class="logout">You have succesfully logged out !</div>
        <div class="main">
        	<!--Login form starts here-->
        <div class="login">
        	<form name="loginForm" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="cbp-mc-form" autocomplete="off">
				<div class="cbp-mc-column">
			        <table>
			        	<tr>
			        		<td><label>Username</label></td>
			        		<td><input type="text" name="admin" id="admin" placeholder="Username" required/></td>
			        	</tr>
			        	<tr>
			        		<td><label>Password</label></td>
			        		<td><input type="password" name="pass" id="pass" placeholder="Password" required/></td>
			        	</tr>
			        </table>
			        <div class="cbp-mc-submit-wrap"><input class="cbp-mc-submit" type="submit" name="login" value="Login" /></div>
			    </div>
        	</form>
        </div>
        <!--Login form ends here-->
        
        </div>
    </div>
    <?php
    if ($show == "invalid")
		echo "<script type='text/javascript'>$('.invalid').show();</script>";
	elseif ($show == "logout")
		echo "<script type='text/javascript'>$('.logout').show();</script>";
	?>
    </body>
</html>